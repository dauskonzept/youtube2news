<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Event\NewsYouTube;

use DSKZPT\YouTube2News\Domain\Model\Dto\VideoDTO;
use DSKZPT\YouTube2News\Domain\Model\NewsYouTube;

class NotPersistedEvent
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

    public function getVideo(): VideoDTO
    {
        return $this->video;
    }
}
