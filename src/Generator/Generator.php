<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Generator;

use Symfony\Component\Filesystem\Filesystem;
use VStelmakh\UrlHighlight\Domains;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\Parser\Data;

class Generator
{
    private const DATE_FORMAT = 'Y-m-d H:i:s T';

    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param Data $data
     * @return void
     */
    public function generate(Data $data): void
    {
        $reflection = new \ReflectionClass(Domains::class);

        $template = $this->renderTemplate(__DIR__ . '/domains.tpl.php', [
            'namespace' => $reflection->getNamespaceName(),
            'className' => $reflection->getShortName(),
            'sourceUrl' => Crawler::TLD_LIST_URL,
            'dateFormat' => self::DATE_FORMAT,
            'createdAt' => (new \DateTime('now', new \DateTimeZone('UTC')))->format(self::DATE_FORMAT),
            'version' => $data->getVersion(),
            'lastUpdated' => $data->getLastUpdated()->format(self::DATE_FORMAT),
            'domains' => array_fill_keys($data->getDomains(), true),
        ]);

        $this->validateSyntax($template);

        /** @var string $filename */
        $filename = $reflection->getFileName();
        $this->filesystem->dumpFile($filename, $template);
    }

    /**
     * @param string $templatePath
     * @param mixed[] $parameters
     * @return string
     */
    private function renderTemplate(string $templatePath, array $parameters): string
    {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;
        return ob_get_clean() ?: '';
    }

    /**
     * @param string $string
     */
    private function validateSyntax(string $string): void
    {
        try {
            $tokens = token_get_all($string, TOKEN_PARSE);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Result file has invalid PHP syntax', 0, $e);
        }
    }
}
