<?php

namespace App\Helpers;

class VersionComparisonHelper
{
    /** Compare version for timezone */
    public function compareVersion(string $version): string
    {
        $version = explode('+', $version);
        $version = $version[1] ?? VERAION_COMPARE_VAL;
        if((int)$version < VERAION_COMPARE_VAL){
            return TIMEZONES['EB'];
        }
        return TIMEZONES['UTC'];
    }

}