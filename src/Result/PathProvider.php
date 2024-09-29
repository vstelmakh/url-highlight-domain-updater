<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlight\DomainUpdater\Result;

use Symfony\Component\Filesystem\Path;

class PathProvider
{
    private const string PROJECT_ROOT = __DIR__ . '/../..';

    public function getAbsolute(string $path): string
    {
        $workDir = getcwd() ?: Path::canonicalize(self::PROJECT_ROOT);
        return Path::makeAbsolute($path, $workDir);
    }
}
