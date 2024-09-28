<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler;

use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser\DomainParser;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser\LastUpdatedParser;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser\VersionParser;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Parser
{
    public static function create(): self
    {
        return new self(
            new VersionParser(),
            new LastUpdatedParser(),
            new DomainParser(),
        );
    }

    public function __construct(
        private readonly VersionParser $versionParser,
        private readonly LastUpdatedParser $lastUpdatedParser,
        private readonly DomainParser $domainParser,
    ) {}

    /** @param array<string> $data */
    public function parse(array $data): DomainList
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Unable to parse empty data.');
        }

        $firstLine = array_shift($data);
        $version = $this->versionParser->parse($firstLine);
        $lastUpdated = $this->lastUpdatedParser->parse($firstLine);

        $domains = new DomainList($version, $lastUpdated);
        foreach ($data as $line) {
            $domain = $this->domainParser->parse($line);
            $domains->addDomain($domain);
        }

        return $domains;
    }
}
