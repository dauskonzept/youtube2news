<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "youtube2news" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DSKZPT\YouTube2News\Service;

class EmojiRemover
{
    /**
     * This function removes emoji from string
     * Used to filter Tweets for emoji characters.
     */
    public static function filter(string $text): string
    {
        $text = iconv('UTF-8', 'ISO-8859-15//IGNORE', $text);
        assert(is_string($text));

        $text = preg_replace('/\s+/', ' ', $text);
        assert(is_string($text));

        return (string)iconv('ISO-8859-15', 'UTF-8', $text);
    }
}
