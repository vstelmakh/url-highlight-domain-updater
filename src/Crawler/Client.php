<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler;

class Client
{
    /** @return array<string> */
    public function getContentByLines(string $url): array
    {
        $result = file($url, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);

        if ($result === false) {
            $this->throwError($url);
        }

        return $result;
    }

    private function throwError(string $url): never
    {
        $error = error_get_last();
        throw new \RuntimeException(sprintf(
            'Error "%s" on crawling from "%s".',
            $error['message'] ?? '',
            $url,
        ));
    }
}
