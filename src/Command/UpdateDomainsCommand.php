<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\DomainUpdater;
use VStelmakh\UrlHighlight\DomainUpdater\Generator\Generator;
use VStelmakh\UrlHighlight\DomainUpdater\Parser\Parser;

class UpdateDomainsCommand extends Command
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    protected function configure(): void
    {
        $this->setName('update-domains');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $crawler = new Crawler();
        $parser = new Parser();
        $generator = new Generator();
        $domainUpdater = new DomainUpdater($crawler, $parser, $generator);

        $domainUpdater->update();

        return self::SUCCESS;
    }
}
