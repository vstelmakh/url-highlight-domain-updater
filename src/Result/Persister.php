<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Result;

use Symfony\Component\Filesystem\Filesystem;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Persister
{
    public static function create(): self
    {
        return new self(new Formatter(), new Filesystem(), new PathProvider());
    }

    public function __construct(
        private readonly Formatter $formatter,
        private readonly Filesystem $filesystem,
        private readonly PathProvider $pathProvider,
    ) {}

    public function save(DomainList $domainList, string $resultPath, bool $isOverwrite): string
    {
        $absolutePath = $this->pathProvider->getAbsolute($resultPath);
        $this->validate($absolutePath, $isOverwrite);

        $result = $this->formatter->format($domainList);
        $this->filesystem->dumpFile($absolutePath, $result);

        return $absolutePath;
    }

    public function validate(string $path, bool $isOverwrite): void
    {
        if (!$isOverwrite && $this->filesystem->exists($path)) {
            throw new FileExistsException(sprintf(
                'File "%s" already exists, with overwrite not allowed.',
                $path,
            ));
        }
    }
}
