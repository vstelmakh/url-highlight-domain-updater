<?php

namespace VStelmakh\UrlHighlight\DomainUpdater;

final class DomainList
{
    private readonly int $version;
    private readonly \DateTimeImmutable $lastUpdated;

    /** @var Domain[] */
    private array $domains = [];

    public function __construct(int $version, \DateTimeImmutable $lastUpdated)
    {
        $this->validateGreaterThanZero($version);
        $this->version = $version;

        $this->validateAfterInternetWasBorn($lastUpdated);
        $this->lastUpdated = $lastUpdated;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getLastUpdated(): \DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    /** @return Domain[] */
    public function getDomains(): array
    {
        return $this->domains;
    }

    public function addDomain(Domain $domain): void
    {
        $value = $domain->getValue();
        $this->domains[$value] = $domain;
    }

    private function validateGreaterThanZero(int $value): void
    {
        if ($value <= 0) {
            throw new \DomainException('Version should be greater than 0.');
        }
    }

    private function validateAfterInternetWasBorn(\DateTimeInterface $dateTime): void
    {
        // ARPANET and the Defense Data Network officially changed to the TCP/IP standard on January 1, 1983, hence the birth of the Internet.
        $internetBirthDate = new \DateTimeImmutable('1983-01-01 00:00:00 UTC');
        if ($internetBirthDate > $dateTime) {
            throw new \DomainException('Last updated date should be after the internet was born.');
        }
    }
}
