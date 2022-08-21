<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Parser;

use VStelmakh\UrlHighlight\DomainUpdater\Exception\ParsingException;

class Parser
{
    /**
     * @param string[] $data
     * @return Data
     */
    public function parse(array $data): Data
    {
        if (empty($data)) {
            throw new ParsingException('Unable to parse empty data.');
        }

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
        $version = $matches[1] ?? null;
        if ($version === null) {
            throw new ParsingException(sprintf('Unable to parse "version" value from "%s".', $string));
        }
        return (int) $version;
    }

    /**
     * @param string $string
     * @return \DateTimeImmutable
     */
    private function parseLastUpdated(string $string): \DateTimeImmutable
    {
        preg_match('/Last\sUpdated\s(.+)/ui', $string, $matches);
        $lastUpdated = $matches[1] ?? null;
        if ($lastUpdated === null) {
            throw new ParsingException(sprintf('Unable to parse "last updated" value from "%s".', $string));
        }

        $dateFormat = 'D M d H:i:s Y T';
        $datetime = \DateTimeImmutable::createFromFormat($dateFormat, $lastUpdated);
        if ($datetime === false) {
            throw new ParsingException(sprintf(
                'Unable to parse "last updated" date "%s" as format "%s".',
                $lastUpdated,
                $dateFormat
            ));
        }

        return $datetime;
    }

    /**
     * @param string[] $domains
     * @return string[]
     */
    private function parseDomains(array $domains): array
    {
        if (empty($domains)) {
            throw new ParsingException('Unable to parse domains.');
        }

        $result = [];
        foreach ($domains as $domain) {
            $domain = trim($domain);
            $this->validateDomain($domain);

            $domainLowercase = mb_strtolower($domain);

            if ($this->isPunycode($domainLowercase)) {
                $domainDecoded = idn_to_utf8($domainLowercase);

                if ($domainDecoded === false) {
                    throw new ParsingException(sprintf(
                        'Error "%s" on decoding punycode domain "%s".',
                        error_get_last()['message'] ?? '',
                        $domainLowercase
                    ));
                }

                $domainLowercase = $domainDecoded;
            }

            $result[] = $domainLowercase;
        }

        $result = array_unique($result);
        sort($result, SORT_STRING);

        return $result;
    }

    /**
     * @param string $domain
     * @return void
     */
    private function validateDomain(string $domain): void
    {
        if (empty($domain) || preg_match('/\s/u', $domain)) {
            throw new ParsingException(sprintf(
                'Invalid domain "%s" parsed.',
                $domain
            ));
        }
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
