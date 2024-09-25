<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Crawler\Parser;

use VStelmakh\UrlHighlight\DomainUpdater\Domain;

class DomainParser
{
    public function parse(string $string): Domain
    {
        $string = trim($string);
        $string = mb_strtolower($string);
        $value = $this->isPunycode($string) ? $this->decodePunycode($string) : $string;
        return new Domain($value);
    }

    private function isPunycode(string $value): bool
    {
        return mb_strpos($value, 'xn--') === 0;
    }

    private function decodePunycode(string $value): string
    {
        $result = idn_to_utf8($value);

        if ($result === false) {
            throw new \RuntimeException(sprintf(
                'Error "%s" on decoding punycode domain "%s".',
                error_get_last()['message'] ?? '',
                $value
            ));
        }

        return $result;
    }
}
