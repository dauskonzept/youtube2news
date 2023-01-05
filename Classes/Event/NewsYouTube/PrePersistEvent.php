<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Event\NewsYouTube;

use DSKZPT\YouTube2News\Domain\Model\Dto\VideoDTO;
use DSKZPT\YouTube2News\Domain\Model\NewsYouTube;

class PrePersistEvent
{
    private NewsYouTube $newsItem;

    private VideoDTO $video;

    /**
     * Used to control if given Video should be imported/persisted
     */
    private bool $persistVideo = true;

    public function __construct(NewsYouTube $newsItem, VideoDTO $video)
    {
        $this->newsItem = $newsItem;
        $this->video = $video;
    }

    public function getNewsYouTube(): NewsYouTube
    {
        return $this->newsItem;
    }

    public function setNewsYouTube(NewsYouTube $newsItem): void
    {
        $this->newsItem = $newsItem;
    }

    public function getVideo(): VideoDTO
    {
        return $this->video;
    }

    public function setVideoData(VideoDTO $video): void
    {
        $this->video = $video;
    }

    public function persistVideo(): bool
    {
        return $this->persistVideo;
    }

    public function doNotPersistVideo(): self
    {
        $this->persistVideo = false;

        return $this;
    }
}
