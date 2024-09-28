<?php

declare(strict_types=1);

$domains = file('http://data.iana.org/TLD/tlds-alpha-by-domain.txt', FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);

array_shift($domains); // remove first row (version info)

$result = [];
$punycodes = [];
foreach ($domains as $domain) {
    $domainLowercase = mb_strtolower($domain);
    $start = mb_substr($domainLowercase, 0, 4);

    if ($start === 'xn--') {
        $punycodes[] = $domainLowercase;
    } else {
        $result[] = $domainLowercase;
    }
}

foreach ($punycodes as $punycode) {
    $result[] = idn_to_utf8($punycode);
}

$result = array_flip($result);
$result = array_map(function ($value) {
    return true;
}, $result);

ksort($result, SORT_NATURAL);

echo 'Domains count: ' . count($result) . "\n";
var_export($result);
echo "\n";
