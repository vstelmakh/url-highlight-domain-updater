<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Generator;

use VStelmakh\UrlHighlight\Domains;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\Parser\Data;

class Generator
{
    private const DATE_FORMAT = 'Y-m-d H:i:s T';

    public function generate(Data $data): void
    {
        $reflection = new \ReflectionClass(Domains::class);

        $template = $this->renderTemplate(__DIR__ . '/domains.tpl.php', [
            'namespace' => $reflection->getNamespaceName(),
            'className' => $reflection->getShortName(),
            'sourceUrl' => Crawler::TLD_LIST_URL,
            'dateFormat' => self::DATE_FORMAT,
            'fileCreated' => date(self::DATE_FORMAT),
            'version' => $data->getVersion(),
            'lastUpdated' => $data->getLastUpdated()->format(self::DATE_FORMAT),
            'domains' => $data->getDomains(),
        ]);

        $isValidSyntax = $this->isValidSyntax($template);

        if ($isValidSyntax) {
            // TODO: Check if writable
            $filename = $reflection->getFileName();
            copy($filename, $filename . '.bak');
            file_put_contents($filename, $template, LOCK_EX);
        } else {
            // TODO: Handle error
        }
    }

    private function renderTemplate(string $templatePath, array $parameters): string
    {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;
        return ob_get_clean();
    }

    private function isValidSyntax(string $string): bool
    {
        try {
            token_get_all($string, TOKEN_PARSE);
        } catch (\Throwable $e) {
            // TODO: throw error
            return false;
        }

        return true;
    }
}
