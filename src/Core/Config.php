<?php

namespace Core;

class Config {
    private static $config = [];

    public static function load($path) {
        if (file_exists($path)) {
            self::$config = require $path;
        }
    }

    public static function get($key, $default = null) {
        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }

        return $value;
    }

    public static function all() {
        return self::$config;
    }
}
