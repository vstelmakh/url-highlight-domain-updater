<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Result;

use VStelmakh\UrlHighlight\DomainUpdater\Domain;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;
use VStelmakh\UrlHighlight\DomainUpdater\Result\Formatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $domainList = $this->getDomainList();
        $formatter = Formatter::create();
        $actual = $formatter->format($domainList);
        $expected = $this->getExpectedResult();
        self::assertSame($expected, $actual);
    }

    private function getDomainList(): DomainList
    {
        $expected = new DomainList(2024092800, new \DateTimeImmutable('2024-09-28 07:07:01 UTC'));
        $this->addDomain($expected, 'ORG');
        $this->addDomain($expected, 'NET');
        $this->addDomain($expected, 'COM');
        return $expected;
    }

    private function addDomain(DomainList $domainList, string $domain): void
    {
        $domainList->addDomain(new Domain($domain));
    }

    private function getExpectedResult(): string
    {
        return <<<PHP
            <?php

            namespace VStelmakh\UrlHighlight;

            /**
             * @internal
             */
            interface Domains
            {
                /**
                 * List of valid top-level domains provided by IANA (https://www.iana.org/)
                 * Source: http://data.iana.org/TLD/tlds-alpha-by-domain.txt
                 */
                public const TOP_LEVEL_DOMAINS = [
                    'com' => true,
                    'net' => true,
                    'org' => true,
                ];
            }

            PHP;
    }
}
