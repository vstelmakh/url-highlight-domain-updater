<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Result;

use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Persister
{
    public static function create(): self
    {
        return new self(new Formatter());
    }

    public function __construct(
        private readonly Formatter $formatter,
    ) {}

    public function save(DomainList $domainList, string $filePath): void
    {
        $result = $this->formatter->format($domainList);
        file_put_contents($filePath, $result);
    }
}
