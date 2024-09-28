<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Crawler;

use PHPUnit\Framework\MockObject\MockObject;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Client;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use PHPUnit\Framework\TestCase;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser;
use VStelmakh\UrlHighlight\DomainUpdater\Domain;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class CrawlerTest extends TestCase
{
    public function testCrawlDomains(): void
    {
        $crawler = new Crawler($this->createTestClient(), Parser::create());
        $actual = $crawler->crawlDomains();
        $expected = $this->getExpectedDomainList();
        self::assertEquals($expected, $actual);
    }

    private function createTestClient(): MockObject&Client
    {
        $content = [
            '# Version 2024092800, Last Updated Sat Sep 28 07:07:01 2024 UTC',
            'COM',
            'ORG',
            'NET',
        ];
        $client = $this->createMock(Client::class);
        $client->method('getContentByLines')->willReturn($content);
        return $client;
    }

    private function getExpectedDomainList(): DomainList
    {
        $expected = new DomainList(2024092800, new \DateTimeImmutable('2024-09-28 07:07:01 UTC'));
        $this->addDomain($expected, 'COM');
        $this->addDomain($expected, 'ORG');
        $this->addDomain($expected, 'NET');
        return $expected;
    }

    private function addDomain(DomainList $domainList, string $domain): void
    {
        $domainList->addDomain(new Domain($domain));
    }
}
