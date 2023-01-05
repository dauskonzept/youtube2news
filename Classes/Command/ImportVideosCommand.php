<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "youtube2news" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DSKZPT\YouTube2News\Command;

use DSKZPT\YouTube2News\Client\YouTubeApiClient;
use DSKZPT\YouTube2News\Domain\Model\Dto\VideoDTO;
use DSKZPT\YouTube2News\Domain\Model\NewsYouTube;
use DSKZPT\YouTube2News\Domain\Repository\NewsYouTubeRepository;
use DSKZPT\YouTube2News\Event\NewsYouTube\NotPersistedEvent;
use DSKZPT\YouTube2News\Event\NewsYouTube\PostPersistEvent;
use DSKZPT\YouTube2News\Event\NewsYouTube\PrePersistEvent;
use DSKZPT\YouTube2News\Service\EmojiRemover;
use DSKZPT\YouTube2News\Service\SlugService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

class ImportVideosCommand extends Command
{
    private NewsYouTubeRepository $newsRepository;

    private PersistenceManagerInterface $persistenceManager;

    private EventDispatcherInterface $eventDispatcher;

    private SlugService $slugService;

    /**
     * @var array<string, string>
     */
    private array $extConf;

    public function __construct(
        NewsYouTubeRepository $newsRepository,
        PersistenceManagerInterface $persistenceManager,
        EventDispatcherInterface $eventDispatcher,
        SlugService $slugService
    ) {
        $this->newsRepository = $newsRepository;
        $this->persistenceManager = $persistenceManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->slugService = $slugService;

        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->extConf = $extensionConfiguration->get('youtube2news');

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Imports tweets as ETX:news articles.')
            ->addArgument('channelname', InputArgument::REQUIRED, 'The YouTube channel name to import videos from')
            ->addArgument('storagePid', InputArgument::REQUIRED, 'The PID where to save the news records')
            ->addArgument('limit', InputArgument::OPTIONAL, 'The maximum number of videos to import.', 25);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $channelName = $input->getArgument('channelname');
        $limit = (int)$input->getArgument('limit');
        $storagePid = $input->getArgument('storagePid');

        $youtubeApiClient = new YouTubeApiClient($this->extConf['api_key']);

        $videos = $youtubeApiClient->getVideosByUsername($channelName, $limit);

        foreach ($videos as $video) {
            $this->processVideo($video, (int)$storagePid);
        }

        return Command::SUCCESS;
    }

    private function processVideo(VideoDTO $video, int $storagePid): NewsYouTube
    {
        $newsItem = $this->newsRepository->findOneByVideoId($video->getVideoId()) ?? new NewsYouTube();

        $filteredTitle = htmlspecialchars_decode(EmojiRemover::filter($video->getTitle()));
        $filteredDescription = EmojiRemover::filter($video->getDescription());
        $pathSegment = $this->getPathSegmet($filteredTitle, $storagePid);

        $newsItem->setTitle($filteredTitle);
        $newsItem->setPathSegment($pathSegment);
        $newsItem->setBodytext($filteredDescription);
        $newsItem->setTeaser($filteredDescription);
        $newsItem->setVideoId($video->getVideoId());
        $newsItem->setPostedBy($video->getChannelTitle());
        $newsItem->setPid($storagePid);
        $newsItem->setDatetime(\DateTime::createFromImmutable($video->getPublishedAt()));
        $newsItem->setExternalurl(sprintf('https://www.youtube.com/watch?v=%s', $video->getVideoId()));

        /** @var PrePersistEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new PrePersistEvent($newsItem, $video)
        );

        if ($event->persistVideo() === false) {
            $this->eventDispatcher->dispatch(new NotPersistedEvent($newsItem, $video));

            return $newsItem;
        }

        $newsItem = $event->getNewsYouTube();
        $isAlreadyImported = $newsItem->getUid() !== null;

        $this->newsRepository->add($newsItem);
        $this->persistenceManager->persistAll();

        // Don't download tweets media again
        if ($isAlreadyImported === true) {
            return $newsItem;
        }

        /** @var PostPersistEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new PostPersistEvent($newsItem, $video)
        );

        return $event->getNewsYouTube();
    }

    private function getPathSegmet(string $stringToSlugify, int $storagePid): string
    {
        return $this->slugService->generateSlugUniqueInPid(
            [
                'title' => $stringToSlugify,
            ],
            $storagePid,
            'tx_news_domain_model_news',
            'path_segment'
        );
    }
}
