<?php

namespace App;

class Util
{
    public static function money($value)
    {
        $format = numfmt_create('en_US', \NumberFormatter::CURRENCY);
        return numfmt_format_currency($format, $value, 'USD');
    }
}
