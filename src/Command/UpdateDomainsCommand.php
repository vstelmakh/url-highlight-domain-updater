<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VStelmakh\UrlHighlight\DomainUpdater\Diff\Diff;
use VStelmakh\UrlHighlight\DomainUpdater\DomainUpdater;
use VStelmakh\UrlHighlight\DomainUpdater\DomainUpdaterFactory;

class UpdateDomainsCommand extends Command
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    /** @var DomainUpdater */
    private $domainUpdater;

    protected function configure(): void
    {
        $this->setName('update-domains');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->domainUpdater = DomainUpdaterFactory::create();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $diff = $this->domainUpdater->update();
        $this->printDomainsDiff($output, $diff);
        return self::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @param Diff $diff
     */
    private function printDomainsDiff(OutputInterface $output, Diff $diff): void
    {
        $output->writeln('Diff:');
        if ($diff->isIdentical()) {
            $output->writeln(' <fg=yellow>~</> identical');
        } else {
            $this->printDomains($output, $diff->getRemovedDomains(), ' <fg=red>-</> ');
            $this->printDomains($output, $diff->getNewDomains(), ' <fg=green>+</> ');
        }
    }

    /**
     * @param OutputInterface $output
     * @param string[] $domains
     * @param string $prefix
     */
    private function printDomains(OutputInterface $output, array $domains, string $prefix): void
    {
        foreach ($domains as $domain) {
            $output->writeln($prefix . $domain);
        }
    }
}
