<?php

namespace App\Helpers;

class VersionComparisonHelper
{
    /** Compare version for timezone */
    public function compareVersion($version): string
    {
        $version = explode('+', $version);
        $version = $version[1] ?? VERAION_COMPARE_VAL;
        if((int)$version < VERAION_COMPARE_VAL) $timezone = TIMEZONES['EB']; else $timezone = TIMEZONES['UTC'];
        return $timezone;
    }

}