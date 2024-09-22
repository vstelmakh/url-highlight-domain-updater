<?php

namespace VStelmakh\UrlHighlight\DomainUpdater;

final class Domain
{
    private readonly string $value;

    public function __construct(string $value) {
        $this->validateIsNotEmpty($value);
        $this->validateContainsValidCharacters($value);
        $this->value = $this->normalizeValue($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function normalizeValue(string $value): string
    {
        return mb_strtolower($value);
    }

    private function validateIsNotEmpty(string $value): void
    {
        if (empty($value)) {
            throw new \DomainException('Domain value should not be empty.');
        }
    }

    private function validateContainsValidCharacters(string $value): void
    {
        // not: whitespace, mathematical, currency, modifier symbol, control point, punctuation
        $isValid = preg_match('/^[^\p{Z}\p{Sm}\p{Sc}\p{Sk}\p{C}\p{P}]{1,63}$/u', $value);
        if ($isValid === false) {
            throw new \DomainException('Domain value contains invalid characters.');
        }
    }
}
