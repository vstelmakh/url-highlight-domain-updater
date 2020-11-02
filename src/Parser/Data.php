<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Parser;

class Data
{
    /**
     * @var int
     */
    private $version;

    /**
     * @var \DateTimeImmutable
     */
    private $lastUpdated;

    /**
     * @var array&string[]
     */
    private $domains;

    /**
     * @internal
     * @param int $version
     * @param \DateTimeImmutable $lastUpdated
     * @param array|string[] $domains
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
     * @return array&string[]
     */
    public function getDomains(): array
    {
        return $this->domains;
    }
}
