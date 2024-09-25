<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\ResultGenerator;

use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class ResultGenerator
{
    public function generate(DomainList $domainList): string
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
        return <<<PHP
            <?php

            namespace VStelmakh\UrlHighlight;

            /**
             * @internal
             */
            interface Domains
            {
                /**
                 * List of valid top-level domains provided by IANA (https://www.iana.org/)
                 * Source: http://data.iana.org/TLD/tlds-alpha-by-domain.txt
                 */
                public const TOP_LEVEL_DOMAINS = [
                    {$domainsResult}
                ];
            }
            PHP;
    }
}
