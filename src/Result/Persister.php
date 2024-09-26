<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Result;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Persister
{
    public static function create(): self
    {
        return new self(new Formatter(), new Filesystem());
    }

    public function __construct(
        private readonly Formatter $formatter,
        private readonly Filesystem $filesystem,
    ) {}

    public function save(DomainList $domainList, string $resultPath, bool $isOverwrite): string
    {
        $absolutePath = Path::makeAbsolute($resultPath, getcwd());
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
                $path
            ));
        }
    }
}
