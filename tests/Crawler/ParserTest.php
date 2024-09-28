<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Crawler;

use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser;
use PHPUnit\Framework\TestCase;
use VStelmakh\UrlHighlight\DomainUpdater\Domain;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $parser = Parser::create();
        $content = $this->getContent();
        $actual = $parser->parse($content);
        $expected = $this->getExpectedDomainList();
        self::assertEquals($expected, $actual);
    }

    /** @return array<string> */
    private function getContent(): array
    {
        return [
            '# Version 2024092800, Last Updated Sat Sep 28 07:07:01 2024 UTC',
            'COM',
            'ORG',
            'NET',
        ];
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
