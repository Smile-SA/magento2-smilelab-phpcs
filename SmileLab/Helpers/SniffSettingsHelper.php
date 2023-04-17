<?php

declare(strict_types=1);

namespace SmileLab\Helpers;

use PHP_CodeSniffer\Config;

class SniffSettingsHelper
{
    /**
     * Check whether a setting is enabled on the current PHP version.
     */
    public static function isEnabledByPhpVersion(?bool $value, int $phpVersionLimit): bool
    {
        if ($value !== null) {
            return $value;
        }

        $phpVersion = Config::getConfigData('php_version') !== null
            ? (int) Config::getConfigData('php_version')
            : PHP_VERSION_ID;

        return $phpVersion >= $phpVersionLimit;
    }
}
