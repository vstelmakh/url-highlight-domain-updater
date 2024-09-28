<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Crawler\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser\LastUpdatedParser;
use PHPUnit\Framework\TestCase;

class LastUpdatedParserTest extends TestCase
{
    #[DataProvider('parseDataProvider')]
    public function testParse(string $input, \DateTimeImmutable $expected, bool $isValid): void
    {
        $parser = new LastUpdatedParser();
        if (!$isValid) {
            $this->expectException(\RuntimeException::class);
        }
        $actual = $parser->parse($input);
        self::assertEquals($expected, $actual);
    }

    /** @return array<mixed> */
    public static function parseDataProvider(): array
    {
        return [
            'valid' => ['# Version 2024092800, Last Updated Sat Sep 28 07:07:01 2024 UTC', new \DateTimeImmutable('2024-09-28 07:07:01 UTC'), true],
            'valid short' => ['last updated Sat Sep 28 07:07:01 2024 UTC', new \DateTimeImmutable('2024-09-28 07:07:01 UTC'), true],
            'invalid, missing timezone' => ['# Version 2024092800, Last Updated Sat Sep 28 07:07:01 2024', new \DateTimeImmutable(), false],
            'invalid, impossible date' => ['# Version 2024092800, Last Updated Sat Sep 32 07:07:01 2024', new \DateTimeImmutable(), false],
        ];
    }
}
