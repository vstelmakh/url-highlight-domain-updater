<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Diff;

class Diff
{
    /** @var string[] */
    private $removedDomains;

    /** @var string[] */
    private $newDomains;

    /**
     * @param string[] $currentDomains
     * @param string[] $parsedDomains
     */
    public function __construct(array $currentDomains, array $parsedDomains)
    {
        $this->removedDomains = array_diff($currentDomains, $parsedDomains);
        $this->newDomains = array_diff($parsedDomains, $currentDomains);
    }

    /**
     * @return string[]
     */
    public function getRemovedDomains(): array
    {
        return $this->removedDomains;
    }

    /**
     * @return string[]
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
