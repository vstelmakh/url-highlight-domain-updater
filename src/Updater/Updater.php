<?php

declare(strict_types=1);

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
        $this->persister->validate($resultPath, $isOverwrite);
        $domainList = $this->crawler->crawlDomains();
        $resultPath = $this->persister->save($domainList, $resultPath, $isOverwrite);

        return new UpdaterResult(
            $resultPath,
            $domainList->getVersion(),
            $domainList->getLastUpdated(),
            $domainList->getCount(),
        );
    }
}
