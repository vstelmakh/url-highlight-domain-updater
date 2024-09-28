<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser;

class VersionParser
{
    public function parse(string $string): int
    {
        preg_match('/Version\s+(\d+),/ui', $string, $matches);
        $version = $matches[1] ?? null;
        if ($version === null) {
            throw new \RuntimeException(sprintf('Unable to parse "version" value from "%s".', $string));
        }
        return (int) $version;
    }
}
