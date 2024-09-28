<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler;

use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Crawler
{
    public const string IANA_TLD_LIST_URL = 'http://data.iana.org/TLD/tlds-alpha-by-domain.txt';

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
        $content = $this->client->getContentByLines(self::IANA_TLD_LIST_URL);
        return $this->parser->parse($content);
    }
}
