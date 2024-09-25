<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;
use VStelmakh\UrlHighlight\DomainUpdater\Result\Persister;

class Command extends SymfonyCommand
{
    private const string NAME = 'update-domains';
    private const string OPT_OVERWRITE = 'overwrite';
    private const string ARG_RESULT = 'result_path';

    public function __construct(
        private readonly Crawler $crawler,
        private readonly Persister $persister,
    ) {
        parent::__construct(self::NAME);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crawling domains from IANA and generating corresponding result in Url highlight compatible format.')
            ->addOption(self::OPT_OVERWRITE, 'o', InputOption::VALUE_NONE, 'Overwrite result file if it exists')
            ->addArgument(self::ARG_RESULT, InputArgument::REQUIRED, 'Path to save result')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $isOverwrite = $input->getOption(self::OPT_OVERWRITE);
        $filePath = $input->getArgument(self::ARG_RESULT);

        $this->validateFilePath($isOverwrite, $filePath);

        $domainList = $this->crawlDomains($output);
        $this->renderCrawlReport($output, $domainList);
        $this->saveResult($output, $domainList, $filePath);

        return self::SUCCESS;
    }

    private function validateFilePath(bool $isOverwrite, string $filePath): void
    {
        $dirName = dirname($filePath);
        if (!is_writable($dirName)) {
            throw new \RuntimeException(sprintf('Directory "%s" is not writable.', $dirName));
        }

        if (!$isOverwrite && file_exists($filePath)) {
            throw new \RuntimeException(sprintf(
                'File "%s" already exists. Consider using "%s" option to overwrite existing file.',
                $filePath,
                self::OPT_OVERWRITE,
            ));
        }
    }

    private function crawlDomains(OutputInterface $output): DomainList
    {
        $output->write('Crawling domains from IANA...');

        try {
            $domainList = $this->crawler->crawlDomains();
            $output->writeln(' <fg=green>done</>');
            return $domainList;
        } catch (\Throwable $e) {
            $output->writeln(' <fg=red>error</>');
            throw $e;
        }
    }

    private function renderCrawlReport(OutputInterface $output, DomainList $domainList): void
    {
        $version = $domainList->getVersion();
        $updatedAt = $domainList->getLastUpdated()->format('Y-m-d H:i:s T');
        $domainCount = count($domainList->getDomains());

        $table = new Table($output);
        $table->setStyle('compact');

        $table->setRows([
            ['Version:', $version],
            ['Last updated:', $updatedAt],
            ['Domain count:', $domainCount],
        ]);

        $output->writeln('');
        $table->render();
        $output->writeln('');
    }

    private function saveResult(OutputInterface $output, DomainList $domainList, string $path): void
    {
        $this->persister->save($domainList, $path);
        $output->writeln(sprintf('Result saved as: <fg=yellow>%s</>', $path));
    }
}
