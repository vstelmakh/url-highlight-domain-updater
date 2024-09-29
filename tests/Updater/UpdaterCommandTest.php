<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Updater;

use Symfony\Component\Console\Tester\CommandTester;
use VStelmakh\UrlHighlight\DomainUpdater\Result\FileExistsException;
use VStelmakh\UrlHighlight\DomainUpdater\Updater\Updater;
use VStelmakh\UrlHighlight\DomainUpdater\Updater\UpdaterCommand;
use PHPUnit\Framework\TestCase;
use VStelmakh\UrlHighlight\DomainUpdater\Updater\UpdaterResult;

class UpdaterCommandTest extends TestCase
{
    public function testExecuteWithOverwrite(): void
    {
        $resultPath = 'path/to/TestResult.php';
        $isOverwrite = true;
        $version = 2024092800;
        $lastUpdate = new \DateTimeImmutable('2024-09-28 07:07:01 UTC');
        $updateResult = new UpdaterResult($resultPath, $version, $lastUpdate, 1445);

        $updater = $this->createMock(Updater::class);
        $updater
            ->expects($this->once())
            ->method('update')
            ->with($resultPath, $isOverwrite)
            ->willReturn($updateResult);

        $command = new UpdaterCommand($updater);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--overwrite' => $isOverwrite,
            'result_path' => $resultPath,
        ]);

        $display = $commandTester->getDisplay();
        $expected = <<<TXT
            Updating domains from IANA... done
            
            Version:      2024092800              
            Last updated: 2024-09-28 07:07:01 UTC 
            Domain count: 1445                    
            
            Result saved to: path/to/TestResult.php

            TXT;

        self::assertSame($expected, $display);
    }

    public function testExecuteNoOverwrite(): void
    {
        $resultPath = 'path/to/TestResult.php';
        $isOverwrite = false;
        $version = 2024092800;
        $lastUpdate = new \DateTimeImmutable('2024-09-28 07:07:01 UTC');
        $updateResult = new UpdaterResult($resultPath, $version, $lastUpdate, 1445);

        $updater = $this->createMock(Updater::class);
        $updater
            ->expects($this->once())
            ->method('update')
            ->with($resultPath, $isOverwrite)
            ->willReturn($updateResult);

        $command = new UpdaterCommand($updater);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'result_path' => $resultPath,
        ]);

        $display = $commandTester->getDisplay();
        $expected = <<<TXT
            Updating domains from IANA... done
            
            Version:      2024092800              
            Last updated: 2024-09-28 07:07:01 UTC 
            Domain count: 1445                    
            
            Result saved to: path/to/TestResult.php

            TXT;

        self::assertSame($expected, $display);
    }

    public function testExecuteFileExists(): void
    {
        $resultPath = 'path/to/TestResult.php';
        $isOverwrite = false;

        $updater = $this->createMock(Updater::class);
        $updater
            ->expects($this->once())
            ->method('update')
            ->with($resultPath, $isOverwrite)
            ->willThrowException(new FileExistsException());

        $command = new UpdaterCommand($updater);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'result_path' => $resultPath,
        ]);

        $display = $commandTester->getDisplay();
        $expected = <<<TXT
            Updating domains from IANA... error
            File already exists: path/to/TestResult.php. Consider using --overwrite option.

            TXT;

        self::assertSame($expected, $display);
    }
}
