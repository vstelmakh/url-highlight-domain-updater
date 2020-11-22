<?php

namespace VStelmakh\UrlHighlight\DomainUpdater;

use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\Diff\Diff;
use VStelmakh\UrlHighlight\DomainUpdater\Diff\DiffFactory;
use VStelmakh\UrlHighlight\DomainUpdater\Generator\Generator;
use VStelmakh\UrlHighlight\DomainUpdater\Parser\Parser;

class DomainUpdater
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @param Crawler $crawler
     * @param Parser $parser
     * @param Generator $generator
     */
    public function __construct(Crawler $crawler, Parser $parser, Generator $generator)
    {
        $this->crawler = $crawler;
        $this->parser = $parser;
        $this->generator = $generator;
    }

    public function update(): Diff
    {
        $data = $this->crawler->getDataFromIANA();
        $data = $this->parser->parse($data);
        $diff = DiffFactory::createDiff($data);
        $this->generator->generate($data);
        return $diff;
    }
}
