<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Domain\Repository;

use DSKZPT\YouTube2News\Domain\Model\NewsYouTube;
use GeorgRinger\News as GeorgRingerNews;

class NewsYouTubeRepository extends GeorgRingerNews\Domain\Repository\NewsRepository
{
    public function findOneByVideoId(string $videoId): ?NewsYouTube
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching($query->equals('videoId', $videoId));

        /** @var NewsYouTube|null $result */
        $result = $query
            ->setLimit(1)
            ->execute()
            ->getFirst();

        return $result;
    }
}
