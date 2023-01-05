<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Event\NewsYouTube;

use DSKZPT\YouTube2News\Domain\Model\NewsYouTube;
use TYPO3\CMS\Core\Resource\File;

class PostDownloadMediaEvent
{
    private NewsYouTube $newsItem;

    private File $file;

    public function __construct(NewsYouTube $newsItem, File $file)
    {
        $this->newsItem = $newsItem;
        $this->file = $file;
    }

    public function getNewsYouTube(): NewsYouTube
    {
        return $this->newsItem;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
