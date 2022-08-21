<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler;

use VStelmakh\UrlHighlight\DomainUpdater\Exception\CrawlingException;

class Crawler
{
    public const TLD_LIST_URL = 'http://data.iana.org/TLD/tlds-alpha-by-domain.txt';

    /**
     * @return string[]
     */
    public function getDataFromIANA(): array
    {
        $result = file(self::TLD_LIST_URL, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);

        if ($result === false) {
            $error = error_get_last();
            throw new CrawlingException(sprintf(
                'Error "%s" on crawling from "%s".',
                $error['message'] ?? '',
                self::TLD_LIST_URL
            ));
        }

        return $result;
    }
}
