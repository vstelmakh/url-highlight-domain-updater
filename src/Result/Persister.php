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
        $this->validateOverwrite($absolutePath, $isOverwrite);

        $result = $this->formatter->format($domainList);

        try {
            $this->filesystem->dumpFile($absolutePath, $result);
        } catch (\Throwable $e) {
            throw new \RuntimeException(sprintf('Unable to save result. %s', $e->getMessage()), $e);
        }

        return $absolutePath;
    }

    private function validateOverwrite(string $path, bool $isOverwrite): void
    {
        if (!$isOverwrite && $this->filesystem->exists($path)) {
            throw new \RuntimeException(sprintf(
                'File "%s" already exists. Consider setting "%s" parameter to true to overwrite existing file.',
                $path,
                'isOverwrite',
            ));
        }
    }
}
