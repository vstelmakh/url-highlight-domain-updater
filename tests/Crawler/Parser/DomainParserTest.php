<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Crawler\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser\DomainParser;
use PHPUnit\Framework\TestCase;

class DomainParserTest extends TestCase
{
    #[DataProvider('parseDataProvider')]
    public function testParse(string $input, string $expected, bool $isValid): void
    {
        $parser = new DomainParser();

        if (!$isValid) {
            $this->expectException(\RuntimeException::class);
        }

        $actual = $parser->parse($input);
        self::assertSame($expected, $actual->getValue());
    }

    /** @return array<mixed> */
    public static function parseDataProvider(): array
    {
        return [
            'lower case' => ['com', 'com', true],
            'upper case' => ['COM', 'com', true],
            'mix case' => ['Com', 'com', true],
            'whitespace' => [' com  ', 'com', true],
            'unicode' => ['укр', 'укр', true],
            'punnucode' => ['xn--j1amh', 'укр', true],
            'punnucode whitespace' => [' xn--j1amh  ', 'укр', true],
            'punnucode invalid' => [' xn--000  ', '', false],
        ];
    }
}
