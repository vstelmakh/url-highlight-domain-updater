<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Result;

use VStelmakh\UrlHighlight\DomainUpdater\DomainList;

class Persister
{
    public static function create(): self
    {
        return new self(new Formatter());
    }

    public function __construct(
        private readonly Formatter $formatter,
    ) {}

    public function save(DomainList $domainList, string $resultPath, bool $isOverwrite): void
    {
        $this->validateWritable($resultPath);
        $this->validateOverwrite($resultPath, $isOverwrite);

        $result = $this->formatter->format($domainList);
        file_put_contents($resultPath, $result);
    }

    private function validateWritable(string $path): void
    {
        $dirName = dirname($path);
        if (!is_writable($dirName)) {
            throw new \RuntimeException(sprintf('Directory "%s" is not writable.', $dirName));
        }
    }

    private function validateOverwrite(string $path, bool $isOverwrite): void
    {
        if (!$isOverwrite && file_exists($path)) {
            throw new \RuntimeException(sprintf(
                'File "%s" already exists. Consider setting "%s" parameter to true to overwrite existing file.',
                $path,
                'isOverwrite',
            ));
        }
    }
}
