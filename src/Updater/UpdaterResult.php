<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Updater;

final readonly class UpdaterResult
{
    public function __construct(
        public string $resultPath,
        public int $version,
        public \DateTimeImmutable $lastUpdated,
        public int $domainCount,
    ) {}
}
