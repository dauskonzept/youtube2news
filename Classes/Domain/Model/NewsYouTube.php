<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Domain\Model;

use GeorgRinger\News as GeorgRingerNews;

class NewsYouTube extends GeorgRingerNews\Domain\Model\News
{
    /**
     * @var int
     */
    protected $_languageUid = -1;

    protected string $videoId = '';

    protected string $postedBy = '';

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function setVideoId(string $videoId): self
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function getPostedBy(): string
    {
        return $this->postedBy;
    }

    public function setPostedBy(string $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }
}
