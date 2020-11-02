<?php echo '<?php' . PHP_EOL ?>

namespace <?php echo $namespace ?>;

/**
 * List of valid top-level domains provided by IANA (https://www.iana.org/)
 * Source: <?php echo $sourceUrl . PHP_EOL ?>
 */
interface <?php echo $className . PHP_EOL ?>
{
    public const DATE_FORMAT = '<?php echo $dateFormat ?>';
    public const FILE_CREATED = '<?php echo $fileCreated ?>';

    public const VERSION = <?php echo $version ?>;
    public const LAST_UPDATED = '<?php echo $lastUpdated ?>';

    public const TOP_LEVEL_DOMAINS = [
<?php foreach ($domains as $domain) {
    echo '        \'' . $domain . '\' => true,' . PHP_EOL;
} ?>
    ];
}
