<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Parser;

class Parser
{
    /**
     * @param array $data
     * @return Data
     */
    public function parse(array $data): Data
    {
        // TODO: add data format validation
        $firstRow = array_shift($data);
        $version = $this->parseVersion($firstRow);
        $lastUpdated = $this->parseLastUpdated($firstRow);

        $domains = $this->parseDomains($data);

        return new Data($version, $lastUpdated, $domains);
    }

    /**
     * @param string $string
     * @return int
     */
    private function parseVersion(string $string): int
    {
        preg_match('/Version\s+(\d+),/ui', $string, $matches);
        return (int) $matches[1];
    }

    /**
     * @param string $string
     * @return \DateTimeImmutable
     */
    private function parseLastUpdated(string $string): \DateTimeImmutable
    {
        preg_match('/Last\sUpdated\s(.+)/ui', $string, $matches);
        return \DateTimeImmutable::createFromFormat('D M d H:i:s Y T', $matches[1]);
    }

    /**
     * @param array&string[] $data
     * @return array&string[]
     */
    private function parseDomains(array $data): array
    {
        $result = [];
        foreach ($data as $domain) {
            // TODO: trim, check if valid
            $domainLowercase = mb_strtolower($domain);
            $result[] = $this->isPunycode($domainLowercase) ? idn_to_utf8($domainLowercase) : $domainLowercase;
        }

        $result = array_unique($result);
        sort($result, SORT_STRING);

        return $result;
    }

    /**
     * @param string $string
     * @return bool
     */
    private function isPunycode(string $string): bool
    {
        return mb_strpos($string, 'xn--') === 0;
    }
}
