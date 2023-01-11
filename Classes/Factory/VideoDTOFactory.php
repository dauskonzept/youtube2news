<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Factory;

use DSKZPT\YouTube2News\Domain\Model\Dto\VideoDTO;

class VideoDTOFactory
{
    public function create(string $videoId, \DateTimeImmutable $publishedAt): VideoDTO
    {
        return new VideoDTO($videoId, $publishedAt);
    }

    /**
     * @param mixed[] $videosData
     *
     * @return VideoDTO[]
     */
    public function createFromApiResponse(array $videosData): array
    {
        $return = [];

        foreach ($videosData as $videoSnippet) {
            if (isset($videoSnippet['id']['videoId']) === false) {
                continue;
            }

            $return[] = $this->createFromSnippetData($videoSnippet);
        }

        return $return;
    }

    /**
     * @param mixed[] $data
     *
     * @throws \Exception
     */
    public function createFromSnippetData(array $data): VideoDTO
    {
        $snippet = $data['snippet'];
        $videoId = $data['id']['videoId'];

        $publishedAt = new \DateTimeImmutable($snippet['publishedAt']);

        return $this->create($videoId, $publishedAt)
            ->setTitle($snippet['title'])
            ->setDescription($snippet['description'])
            ->setThumbnails($snippet['thumbnails'])
            ->setPublishTime(new \DateTimeImmutable($snippet['publishTime']))
            ->setChannelId($snippet['channelId'])
            ->setChannelTitle($snippet['channelTitle'])
            ->setLiveBroadcastContent($snippet['liveBroadcastContent']);
    }
}
