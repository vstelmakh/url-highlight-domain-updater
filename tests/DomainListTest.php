<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use VStelmakh\UrlHighlight\DomainUpdater\Domain;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;
use PHPUnit\Framework\TestCase;

class DomainListTest extends TestCase
{
    #[DataProvider('versionDataProvider')]
    public function testVersion(int $input, bool $isValid): void
    {
        if (!$isValid) {
            $this->expectException(\DomainException::class);
        }

        $lastUpdated = new \DateTimeImmutable('2024-01-01');
        $domainList = new DomainList($input, $lastUpdated);
        self::assertSame($input, $domainList->getVersion());
    }

    /** @return array<mixed> */
    public static function versionDataProvider(): array
    {
        return [
            'valid' => [1, true],
            'long' => [1234567890, true],
            'zero' => [0, false],
            'negative' => [-1, false],
        ];
    }

    #[DataProvider('lastUpdatedDataProvider')]
    public function testLastUpdated(\DateTimeImmutable $input, bool $isValid): void
    {
        if (!$isValid) {
            $this->expectException(\DomainException::class);
        }

        $domainList = new DomainList(1, $input);
        self::assertSame($input, $domainList->getLastUpdated());
    }

    /** @return array<mixed> */
    public static function lastUpdatedDataProvider(): array
    {
        return [
            'oldest' => [new \DateTimeImmutable('1983-01-01 00:00:00 UTC'), true],
            'before oldest' => [new \DateTimeImmutable('1982-12-31 23:59:59 UTC'), false],
            'now' => [new \DateTimeImmutable(), true],
        ];
    }

    public function testAddDomain(): void
    {
        $lastUpdated = new \DateTimeImmutable('2024-01-01');
        $domainList = new DomainList(1, $lastUpdated);

        $domain1 = new Domain('com');
        $domainList->addDomain($domain1);

        $domain2 = new Domain('com'); // duplicate
        $domainList->addDomain($domain2);

        $domain3 = new Domain('net');
        $domainList->addDomain($domain3);

        $actual = $domainList->getDomains();
        $expected = ['com' => $domain2, 'net' => $domain3];
        self::assertSame($actual, $expected);
        self::assertSame(2, $domainList->getCount());
    }
}
