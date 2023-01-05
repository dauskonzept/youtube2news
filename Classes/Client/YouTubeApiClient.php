<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "youtube2news" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DSKZPT\YouTube2News\Client;

use DSKZPT\YouTube2News\Domain\Model\Dto\VideoDTO;
use DSKZPT\YouTube2News\Factory\VideoDTOFactory;
use Psr\Http\Message\RequestFactoryInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class YouTubeApiClient
{
    private string $apiKey;

    private RequestFactory $requestFactory;

    private const API_BASE_URL = 'https://www.googleapis.com/youtube/v3';

    private VideoDTOFactory $videoDTOFactory;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        /** @var RequestFactory $requestFactory */
        $requestFactory = GeneralUtility::makeInstance(RequestFactoryInterface::class);
        $this->requestFactory = $requestFactory;

        /** @var VideoDTOFactory $videoDTOFactory */
        $videoDTOFactory = GeneralUtility::makeInstance(VideoDTOFactory::class);
        $this->videoDTOFactory = $videoDTOFactory;
    }

    /**
     * @return VideoDTO[]
     *
     * @throws \Exception
     */
    public function getVideosByUsername(string $username, int $limit = 50): array
    {
        $channelId = $this->getChannelIdByUsername($username);

        $endpoint = sprintf(
            '/search?order=date&part=snippet&channelId=%s&maxResults=%s',
            $channelId,
            $limit
        );

        $response = $this->request($endpoint);

        $videos = $response['items'];

        // todo: get paginated videos if nessessary

        return $this->videoDTOFactory->createFromApiResponse($videos);
    }

    public function getChannelIdByUsername(string $username): string
    {
        $channelSnippet = $this->getChannelSnippet($username);

        return $channelSnippet['items'][0]['id'];
    }

    /**
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getChannelSnippet(string $username): array
    {
        $endpoint = sprintf(
            '/channels?part=snippet&forUsername=%s',
            $username
        );

        return $this->request($endpoint);
    }

    /**
     * @param array<string, mixed> $additionalOptions
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    private function request(string $endpoint, string $method = 'GET', array $additionalOptions = []): array
    {
        $url = sprintf('%s%s%s', self::API_BASE_URL, $endpoint, '&key=' . $this->apiKey);

        $response = $this->requestFactory->request($url, $method, $additionalOptions);

        if ($response->getStatusCode() !== 200) {
            throw new \HttpRequestException($response->getReasonPhrase());
        }

        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }
}
