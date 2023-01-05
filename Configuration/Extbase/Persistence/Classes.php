<?php

declare(strict_types=1);

return [
    \GeorgRinger\News\Domain\Model\News::class => [
        'subclasses' => [
            1672912210 => \DSKZPT\YouTube2News\Domain\Model\NewsYouTube::class,
        ],
    ],
    \DSKZPT\YouTube2News\Domain\Model\NewsYouTube::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 1672912210,
    ],
];
