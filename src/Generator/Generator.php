<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Generator;

use VStelmakh\UrlHighlight\Domains;
use VStelmakh\UrlHighlight\DomainUpdater\Crawler\Crawler;
use VStelmakh\UrlHighlight\DomainUpdater\Parser\Data;

class Generator
{
    private const DATE_FORMAT = 'Y-m-d H:i:s T';

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

        $isValidSyntax = $this->isValidSyntax($template);

        if ($isValidSyntax) {
            // TODO: Check if writable
            $filename = $reflection->getFileName();
            copy($filename, $filename . '.old');
            file_put_contents($filename, $template, LOCK_EX);
        } else {
            // TODO: Handle error
        }
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
        return ob_get_clean();
    }

    /**
     * @param string $string
     * @return bool
     */
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
