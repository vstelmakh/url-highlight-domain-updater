<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Updater;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use VStelmakh\UrlHighlight\DomainUpdater\Result\FileExistsException;

class UpdaterCommand extends Command
{
    private const string NAME = 'update-domains';
    private const string OPT_OVERWRITE = 'overwrite';
    private const string ARG_RESULT = 'result_path';

    public function __construct(
        private readonly Updater $updater,
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
        $isOverwrite = (bool) $input->getOption(self::OPT_OVERWRITE);
        $resultPath = (string) $input->getArgument(self::ARG_RESULT);

        $output->write('Updating domains from IANA...');

        try {
            $result = $this->updater->update($resultPath, $isOverwrite);
            $output->writeln(' <fg=green>done</>');
        } catch (FileExistsException $e) {
            $output->writeln(' <fg=red>error</>');
            $output->writeln(sprintf('File already exists: <fg=yellow>%s</>. Consider using <fg=cyan>--%s</> option.', $resultPath, self::OPT_OVERWRITE));
            return self::INVALID;
        } catch (\Throwable $e) {
            $output->writeln(' <fg=red>error</>');
            throw $e;
        }

        $this->renderResult($output, $result);

        return self::SUCCESS;
    }

    private function renderResult(OutputInterface $output, UpdaterResult $result): void
    {
        $table = new Table($output);
        $table->setStyle('compact');

        $table->setRows([
            ['Version:', $result->version],
            ['Last updated:', $result->lastUpdated->format('Y-m-d H:i:s T')],
            ['Domain count:', $result->domainCount],
        ]);

        $output->writeln('');
        $table->render();
        $output->writeln('');
        $output->writeln(sprintf('Result saved to: <fg=yellow>%s</>', $result->resultPath));
    }
}
