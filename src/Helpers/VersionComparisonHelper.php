<?php

namespace App\Helpers;

class VersionComparisonHelper
{
    /** Compare version for timezone */
    public function compareVersion(string $version): string
    {
        if(version_compare($version, VERAION_COMPARE_VAL, ">=")){
            return TIMEZONES['UTC'];
        }
        return TIMEZONES['EB'];
    }

}