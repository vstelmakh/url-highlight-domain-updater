<?php echo '<?php' . PHP_EOL ?>

namespace <?php echo $namespace ?>;

/**
 * List of valid top-level domains provided by IANA (https://www.iana.org/)
 * Source: <?php echo $sourceUrl . PHP_EOL ?>
 */
interface <?php echo $className . PHP_EOL ?>
{
    public const DATE_FORMAT = <?php var_export($dateFormat) ?>;
    public const CREATED_AT = <?php var_export($createdAt) ?>;

    public const IANA_VERSION = <?php var_export($version) ?>;
    public const IANA_UPDATED_AT = <?php var_export($lastUpdated) ?>;

    public const TOP_LEVEL_DOMAINS = <?php var_export($domains) ?>;
}
