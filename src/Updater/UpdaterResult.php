<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Updater;

final readonly class UpdaterResult
{
    public function __construct(
        public int $version,
        public \DateTimeImmutable $lastUpdated,
        public int $domainCount,
    ) {}
}
