<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Parser;

use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Parser
{
    public function __construct(
        private readonly VersionParser $versionParser,
        private readonly LastUpdatedParser $lastUpdatedParser,
        private readonly DomainParser $domainParser,
    ) {}

    /** @param array<string> $data */
    public function parse(array $data): DomainList
    {
        if (empty($data)) {
            throw new \UnexpectedValueException('Unable to parse empty data.');
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
