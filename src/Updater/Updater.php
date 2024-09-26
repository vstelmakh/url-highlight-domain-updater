<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Updater;

use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\Result\Persister;

class Updater
{
    public static function create(): self
    {
        return new self(
            Crawler::create(),
            Persister::create(),
        );
    }

    public function __construct(
        private readonly Crawler $crawler,
        private readonly Persister $persister,
    ) {}

    public function update(string $resultPath, bool $isOverwrite): UpdaterResult
    {
        $domainList = $this->crawler->crawlDomains();
        $this->persister->save($domainList, $resultPath, $isOverwrite);

        return new UpdaterResult(
            $domainList->getVersion(),
            $domainList->getLastUpdated(),
            $domainList->getCount(),
        );
    }
}
