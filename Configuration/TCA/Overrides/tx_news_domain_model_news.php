<?php

defined('TYPO3') or die();

$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['1672912210'] =
    ['YouTube Video', 1672912210, 'actions-brand-youtube'];

$GLOBALS['TCA']['tx_news_domain_model_news']['types']['1672912210'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['2'];

$fields = [
    'video_id' => [
        'exclude' => 1,
        'label' => 'Video ID',
        'config' => [
            'type' => 'input',
            'size' => 30,
        ],
    ],
    'posted_by' => [
        'exclude' => 1,
        'label' => 'Posted by',
        'config' => [
            'type' => 'input',
            'size' => 30,
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tx_news_domain_model_news',
    'tx_youtube2news_fields',
    'video_id, posted_by'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;YouTube,--palette--;LLL:EXT:youtube2news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.palette.tx_youtube2news_fields;tx_youtube2news_fields',
    '1672912210',
    ''
);
