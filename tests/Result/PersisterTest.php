<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Tests\Result;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Filesystem\Filesystem;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;
use VStelmakh\UrlHighlight\DomainUpdater\Result\FileExistsException;
use VStelmakh\UrlHighlight\DomainUpdater\Result\Formatter;
use VStelmakh\UrlHighlight\DomainUpdater\Result\PathProvider;
use VStelmakh\UrlHighlight\DomainUpdater\Result\Persister;
use PHPUnit\Framework\TestCase;

class PersisterTest extends TestCase
{
    public function testSaveNoOverwrite(): void
    {
        $domainList = new DomainList(1, new \DateTimeImmutable());
        $resultPath = 'TestResult.php';
        $isOverwrite = false;
        $result = 'result';
        $absolutePath = '/absolute/path/' . $resultPath;

        $formatter = $this->createMock(Formatter::class);
        $formatter->method('format')->willReturn($result);

        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('exists')->willReturn(false);
        $filesystem->expects($this->once())->method('dumpFile')->with($absolutePath, $result);

        $pathProvider = $this->createMock(PathProvider::class);
        $pathProvider->method('getAbsolute')->willReturn($absolutePath);

        $persister = new Persister($formatter, $filesystem, $pathProvider);
        $actual = $persister->save($domainList, $resultPath, $isOverwrite);
        self::assertSame($absolutePath, $actual);
    }

    public function testSaveWithOverwrite(): void
    {
        $domainList = new DomainList(1, new \DateTimeImmutable());
        $resultPath = 'TestResult.php';
        $isOverwrite = true;
        $result = 'result';
        $absolutePath = '/absolute/path/' . $resultPath;

        $formatter = $this->createMock(Formatter::class);
        $formatter->method('format')->willReturn($result);

        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('exists')->willReturn(true);
        $filesystem->expects($this->once())->method('dumpFile')->with($absolutePath, $result);

        $pathProvider = $this->createMock(PathProvider::class);
        $pathProvider->method('getAbsolute')->willReturn($absolutePath);

        $persister = new Persister($formatter, $filesystem, $pathProvider);
        $actual = $persister->save($domainList, $resultPath, $isOverwrite);
        self::assertSame($absolutePath, $actual);
    }

    public function testSaveFileExists(): void
    {
        $domainList = new DomainList(1, new \DateTimeImmutable());
        $resultPath = 'TestResult.php';
        $isOverwrite = false;
        $absolutePath = '/absolute/path/' . $resultPath;

        $formatter = $this->createMock(Formatter::class);

        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('exists')->willReturn(true);
        $filesystem->expects($this->never())->method('dumpFile');

        $pathProvider = $this->createMock(PathProvider::class);
        $pathProvider->method('getAbsolute')->willReturn($absolutePath);

        $persister = new Persister($formatter, $filesystem, $pathProvider);

        $this->expectException(FileExistsException::class);
        $persister->save($domainList, $resultPath, $isOverwrite);
    }

    #[DataProvider('validateDataProvider')]
    public function testValidate(bool $isExists, bool $isOverwrite, bool $isValid): void
    {
        $formatter = $this->createMock(Formatter::class);

        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('exists')->willReturn($isExists);

        $pathProvider = $this->createMock(PathProvider::class);

        $persister = new Persister($formatter, $filesystem, $pathProvider);
        if (!$isValid) {
            $this->expectException(FileExistsException::class);
        } else {
            $this->expectNotToPerformAssertions();
        }
        $persister->validate('path', $isOverwrite);
    }

    /** @return array<mixed> */
    public static function validateDataProvider(): array
    {
        return [
            'exist, with overwrite' => [true, true, true],
            'exist, no overwrite' => [true, false, false],
            'not exist, with overwrite' => [false, true, true],
            'not exist, no overwrite' => [false, false, true],
        ];
    }
}
