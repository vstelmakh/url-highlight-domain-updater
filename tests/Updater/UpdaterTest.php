<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Updater;

use PHPUnit\Framework\TestCase;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;
use VStelmakh\UrlHighlight\DomainUpdater\Result\FileExistsException;
use VStelmakh\UrlHighlight\DomainUpdater\Result\Persister;
use VStelmakh\UrlHighlight\DomainUpdater\Updater\Updater;
use VStelmakh\UrlHighlight\DomainUpdater\Updater\UpdaterResult;

class UpdaterTest extends TestCase
{
    public function testUpdate(): void
    {
        $lastUpdated = new \DateTimeImmutable();
        $domainList = new DomainList(1, $lastUpdated);
        $resultPath = 'TestResult.php';
        $isOverwrite = true;
        $expected = new UpdaterResult($resultPath, 1, $lastUpdated, 0);

        $crawler = $this->createMock(Crawler::class);
        $crawler->method('crawlDomains')->willReturn($domainList);

        $persister = $this->createMock(Persister::class);
        $persister->method('save')->willReturn($resultPath);

        $updater = new Updater($crawler, $persister);
        $actual = $updater->update($resultPath, $isOverwrite);
        self::assertEquals($expected, $actual);
    }

    public function testUpdateNotCrawlIfUnableToPersist(): void
    {
        $crawler = $this->createMock(Crawler::class);
        $crawler->expects($this->never())->method('crawlDomains');

        $persister = $this->createMock(Persister::class);
        $persister->method('validate')->willThrowException(new FileExistsException());

        $updater = new Updater($crawler, $persister);
        $this->expectException(FileExistsException::class);
        $updater->update('TestResult.php', true);
    }
}
