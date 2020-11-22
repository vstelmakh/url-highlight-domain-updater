<?php

namespace VStelmakh\UrlHighlight\DomainUpdater\Diff;

use VStelmakh\UrlHighlight\Domains;
use VStelmakh\UrlHighlight\DomainUpdater\Parser\Data;

class DiffFactory
{
    /**
     * @param Data $parsedData
     * @return Diff
     */
    public static function createDiff(Data $parsedData): Diff
    {
        $currentDomains = array_keys(Domains::TOP_LEVEL_DOMAINS);
        $parsedDomains = $parsedData->getDomains();
        return new Diff($currentDomains, $parsedDomains);
    }
}
