<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler;

class Client
{
    public const string IANA_TLD_LIST_URL = 'http://data.iana.org/TLD/tlds-alpha-by-domain.txt';

    /** @return array<string> */
    public function getTldData(): array
    {
        $result = file(self::IANA_TLD_LIST_URL, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);

        if ($result === false) {
            $this->throwError();
        }

        return $result;
    }

    private function throwError(): never
    {
        $error = error_get_last();
        throw new \RuntimeException(sprintf(
            'Error "%s" on crawling from "%s".',
            $error['message'] ?? '',
            self::IANA_TLD_LIST_URL,
        ));
    }
}
