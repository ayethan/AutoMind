<?php

namespace App\Utils;

use App\Setting;

class Helpers {
    public static function getValue(String $key) {
        $setting =  Setting::select('value', 'data_type')->where('key', $key)->first();
        $value = $setting->value;
        $data_type = $setting->data_type;
        settype($value, $data_type);
        return $value;
    }
}