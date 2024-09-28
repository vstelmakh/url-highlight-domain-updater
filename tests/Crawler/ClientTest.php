<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Crawler;

use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testGetContentByLines(): void
    {
        $client = new Client();
        $url = __DIR__ . '/client-test-content.txt';
        $actual = $client->getContentByLines($url);
        $expected = [
            'First line',
            'This is line 2',
            'And this is line 4',
            'one more line here',
        ];

        self::assertSame($expected, $actual);
    }
}
