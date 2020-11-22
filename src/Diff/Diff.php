<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Diff;

class Diff
{
    /**
     * @var array&string[]
     */
    private $removedDomains;

    /**
     * @var array&string[]
     */
    private $newDomains;

    /**
     * @param array&string[] $currentDomains
     * @param array&string[] $parsedDomains
     */
    public function __construct(array $currentDomains, array $parsedDomains)
    {
        $this->removedDomains = array_diff($currentDomains, $parsedDomains);
        $this->newDomains = array_diff($parsedDomains, $currentDomains);
    }

    /**
     * @return array|string[]
     */
    public function getRemovedDomains(): array
    {
        return $this->removedDomains;
    }

    /**
     * @return array|string[]
     */
    public function getNewDomains(): array
    {
        return $this->newDomains;
    }

    /**
     * @return bool
     */
    public function isIdentical(): bool
    {
        return empty($this->removedDomains) && empty($this->newDomains);
    }
}
