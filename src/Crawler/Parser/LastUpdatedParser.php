<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser;

class LastUpdatedParser
{
    private const string DATE_FORMAT = 'D M d H:i:s Y T';

    public function parse(string $string): \DateTimeImmutable
    {
        $value = $this->parseValue($string);
        return $this->createDateTime($value);
    }

    private function parseValue(string $string): string
    {
        preg_match('/Last\sUpdated\s(.+)/ui', $string, $matches);
        $value = $matches[1] ?? null;
        if ($value === null) {
            throw new \RuntimeException(sprintf('Unable to parse "last updated" value from "%s".', $string));
        }

        return $value;
    }

    private function createDateTime(string $value): \DateTimeImmutable
    {
        $datetime = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $value);
        if ($datetime === false) {
            throw new \RuntimeException(sprintf(
                'Unable to parse "last updated" date "%s" as format "%s".',
                $value,
                self::DATE_FORMAT,
            ));
        }

        return $datetime;
    }
}
