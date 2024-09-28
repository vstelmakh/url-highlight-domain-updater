<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use VStelmakh\UrlHighlight\DomainUpdater\Domain;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    #[DataProvider('valueDataProvider')]
    public function testValue(string $input, string $expected, bool $isValid): void
    {
        if (!$isValid) {
            $this->expectException(\DomainException::class);
        }

        $domain = new Domain($input);
        self::assertSame($expected, $domain->getValue());
    }

    /** @return array<mixed> */
    public static function valueDataProvider(): array
    {
        return [
            'valid' => ['com', 'com', true],
            'upper case' => ['COM', 'com', true],
            'mixed case' => ['Com', 'com', true],
            'unicode' => ['укр', 'укр', true],
            'unicode upper case' => ['УКР', 'укр', true],
            'unicode mixed case' => ['Укр', 'укр', true],
            'with numbers' => ['1st', '1st', true],
            'empty' => ['', '', false],
            'only whitespace' => [' ', '', false],
            'with whitespace' => ['hello world', '', false],
            'with dot' => ['hello.world', '', false],
            'with dot prefix' => ['.com', '', false],
            'with dash' => ['hello-world', '', false],
            'with plus' => ['hello+world', '', false],
            'min length' => ['a', 'a', true],
            'max length' => ['abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijk', 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijk', true],
            'overflow' => ['abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijkl', '', false],
        ];
    }
}
