<?php

namespace VStelmakh\UrlHighlight\DomainUpdater;

use Symfony\Component\Filesystem\Filesystem;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\Generator\Generator;
use VStelmakh\UrlHighlight\DomainUpdater\Parser\Parser;

class DomainUpdaterFactory
{
    public static function create(): DomainUpdater
    {
        $filesystem = new Filesystem();
        $crawler = new Crawler();
        $parser = new Parser();
        $generator = new Generator($filesystem);
        return new DomainUpdater($crawler, $parser, $generator);
    }
}
