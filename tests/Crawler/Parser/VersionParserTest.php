<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Crawler\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser\VersionParser;
use PHPUnit\Framework\TestCase;

class VersionParserTest extends TestCase
{
    #[DataProvider('parseDataProvider')]
    public function testParse(string $input, int $expected, bool $isValid): void
    {
        $versionParser = new VersionParser();
        if (!$isValid) {
            $this->expectException(\RuntimeException::class);
        }
        $actual = $versionParser->parse($input);
        self::assertSame($expected, $actual);
    }

    /** @return array<mixed> */
    public static function parseDataProvider(): array
    {
        return [
            'valid' => ['# Version 2024092800, Last Updated Sat Sep 28 07:07:01 2024 UTC', 2024092800, true],
            'valid, short' => ['Version 2024092800,', 2024092800, true],
            'invalid, missing' => ['# Last Updated Sat Sep 28 07:07:01 2024 UTC', 0, false],
            'invalid, value' => ['# Version null, Last Updated Sat Sep 28 07:07:01 2024 UTC', 0, false],
        ];
    }
}
