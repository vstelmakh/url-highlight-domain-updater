<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Parser;

class Data
{
    /** @var int */
    private $version;

    /** @var \DateTimeImmutable */
    private $lastUpdated;

    /** @var string[] */
    private $domains;

    /**
     * @internal
     * @param int $version
     * @param \DateTimeImmutable $lastUpdated
     * @param string[] $domains
     */
    public function __construct(int $version, \DateTimeImmutable $lastUpdated, array $domains)
    {
        $this->version = $version;
        $this->lastUpdated = $lastUpdated;
        $this->domains = $domains;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastUpdated(): \DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    /**
     * @return string[]
     */
    public function getDomains(): array
    {
        return $this->domains;
    }
}
