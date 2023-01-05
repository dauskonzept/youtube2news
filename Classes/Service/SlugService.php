<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SlugService
{
    /**
     * This gets all records from the given table, respecting the enablefields, where
     *  the slug is NULL in the slug-field.
     *
     * All of the records will be looped get a new slug generated and saved to the
     * slug-field.
     *
     * @param string $table
     * @param string|array $titleField one fieldname as string ('field1') or array of fieldnames (['field1', 'field2'])
     * @param string $slugField
     *
     * @throws SiteNotFoundException
     */
    public function checkAndFixMissingSlugs($table, $titleField, $slugField): void
    {
        /**************************************************************************
         * Get QueryBuilder to fetch records with empty slugs */
        $qb_slugFetch = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        /**************************************************************************
         * Fetch records with empty Slugs */
        $qb_slugFetch
            ->select('uid', 'pid')
            ->from($table)
            ->where(
                $qb_slugFetch->expr()->isNull($slugField)
            )
            ->orWhere(
                $qb_slugFetch->expr()->eq($slugField, $qb_slugFetch->quote(''))
            );

        if (\gettype($titleField) === 'array') {
            foreach ($titleField as $field) {
                $qb_slugFetch->addSelect($field);
            }
        }

        $emptySlugs = $qb_slugFetch
            ->execute()
            ->fetchAll();

        /**************************************************************************
         * Only if there are records found, go on with the next steps */
        if (!empty($emptySlugs)) {
            /**************************************************************************
             * Get QueryBuilder to Update records with empty slugs */
            $qb_slugUpdate = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($table);

            /**************************************************************************
             * Loop through all records in $emptySlugs */
            foreach ($emptySlugs as $record) {
                if (\gettype($titleField) === 'array') {
                    $title = '';
                    foreach ($titleField as $field) {
                        $title .= $record[$field] . ' ';
                    }
                } else {
                    $title = $titleField;
                }
                /**************************************************************************
                 * Generate a new unique slug for in the same pid as the current record */
                $newSlug = $this->generateSlugUniqueInSite(
                    $title,
                    $record['pid'],
                    $table,
                    $slugField
                );
                /**************************************************************************
                 * Update the slug-field */
                $qb_slugUpdate
                    ->update($table)
                    ->where(
                        $qb_slugUpdate->expr()->eq('uid', $record['uid'])
                    )
                    ->set($slugField, $newSlug)
                    ->execute();
            }
        }
    }

    /**
     * Generates a unique-in-pid slug.
     *
     * @param int $pid The page ID for which this slug should be unique. Can be e.g. the sysfolder
     *  where the record is saved, or eventually the rootpage, when there could be multiple items
     *  with the same name.
     * @param string $tablename The name of the table, for which the slug is checked/created
     * @param string $slugfield The fieldname where the slug is saved. This is used to check for
     *  Uniqueness - you have to save the generated slug on your own
     */
    public function generateSlugUniqueInPid(array $slugConfig, $pid, $tablename, $slugfield = 'slug'): string
    {
        $helper = $this
            ->getSlugHelper($tablename, $slugfield);

        $slug = $helper
            ->generate($slugConfig, $pid);

        $state = RecordStateFactory::forName($tablename)
            ->fromArray($slugConfig, $pid, 'NEW');

        return $helper
            ->buildSlugForUniqueInPid($slug, $state);
    }

    /**
     * Generates a unique-in-site slug. This is way more calculation intensive and will need much
     *  more time, when many records are cycled.
     *
     * @param string $title the string thats used to generate the slug
     * @param int $pid The page ID for which this slug should be unique. Can be e.g. the sysfolder
     *  where the record is saved, or eventually the rootpage, when there could be multiple items
     *  with the same name.
     * @param string $tablename The name of the table, for which the slug is checked/created
     * @param string $slugfield The fieldname where the slug is saved. This is used to check for
     *  Uniqueness - you have to save the generated slug on your own
     *
     * @throws SiteNotFoundException
     */
    public function generateSlugUniqueInSite($title, $pid, $tablename, $slugfield = 'slug'): string
    {
        $helper = $this
            ->getSlugHelper($tablename, $slugfield);

        $slug = $helper
            ->generate([
                'name' => $title,
            ], $pid);

        $state = RecordStateFactory::forName($tablename)
            ->fromArray([
                'name' => $title,
            ], $pid, 'NEW');

        return $helper
            ->buildSlugForUniqueInPid($slug, $state);
    }

    /**
     * @param string $tablename
     * @param string $slugfield
     */
    public function getSlugHelper($tablename, $slugfield = 'slug'): SlugHelper
    {
        $fieldConfig = $GLOBALS['TCA'][$tablename]['columns'][$slugfield]['config'];
        $helper = GeneralUtility::makeInstance(
            SlugHelper::class,
            $tablename,
            $slugfield,
            $fieldConfig
        );

        return $helper;
    }
}
