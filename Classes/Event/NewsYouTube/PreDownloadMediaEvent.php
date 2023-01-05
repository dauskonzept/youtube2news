<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Event\NewsYouTube;

use DSKZPT\YouTube2News\Domain\Model\Dto\VideoDTO;
use DSKZPT\YouTube2News\Domain\Model\NewsYouTube;

class PreDownloadMediaEvent
{
    private NewsYouTube $newsItem;

    private VideoDTO $video;

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

    public function setVideo(VideoDTO $video): void
    {
        $this->video = $video;
    }

    public function getVideoData(): VideoDTO
    {
        return $this->video;
    }
}
