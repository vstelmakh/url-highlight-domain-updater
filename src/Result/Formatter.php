<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Result;

use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Formatter
{
    public static function create(): self
    {
        return new self();
    }

    public function format(DomainList $domainList): string
    {
        $domains = $this->getDomainValues($domainList);
        sort($domains, SORT_STRING);
        $domainsResult = $this->getDomainsResult($domains);
        return $this->getForUrlHighlight($domainsResult);
    }

    /** @return array<string> */
    private function getDomainValues(DomainList $domainList): array
    {
        $result = [];
        $domains = $domainList->getDomains();
        foreach ($domains as $domain) {
            $result[] = $domain->getValue();
        }
        return $result;
    }

    /** @param array<string> $domains */
    private function getDomainsResult(array $domains): string
    {
        $spacer = str_repeat(' ', 8);
        $result = '';
        foreach ($domains as $domain) {
            $result .= sprintf("%s'%s' => true,\n", $spacer, $domain);
        }
        return trim($result);
    }

    private function getForUrlHighlight(string $domainsResult): string
    {
        $source = Crawler::IANA_TLD_LIST_URL;

        return <<<PHP
            <?php
            
            declare(strict_types=1);

            namespace VStelmakh\UrlHighlight;

            /**
             * @internal
             */
            interface Domains
            {
                /**
                 * List of valid top-level domains provided by IANA (https://www.iana.org/)
                 * Source: {$source}
                 */
                public const TOP_LEVEL_DOMAINS = [
                    {$domainsResult}
                ];
            }

            PHP;
    }
}
