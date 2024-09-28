<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler;

use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Crawler
{
    public static function create(): self
    {
        return new self(new Client(), Parser::create());
    }

    public function __construct(
        private readonly Client $client,
        private readonly Parser $parser,
    ) {}

    public function crawlDomains(): DomainList
    {
        $tldData = $this->client->getTldData();
        return $this->parser->parse($tldData);
    }
}
