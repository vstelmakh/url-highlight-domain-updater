<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler;

class Crawler
{
    public const TLD_LIST_URL = 'http://data.iana.org/TLD/tlds-alpha-by-domain.txt';

    /**
     * @return string[]
     */
    public function getDataFromIANA(): array
    {
        // TODO: add error handling
        return file(self::TLD_LIST_URL, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);
    }
}
