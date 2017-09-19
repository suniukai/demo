<?php
namespace Commissions;

class Config
{
    private static $currencies;

    public static function init($filename)
    {
        self::$currencies = json_decode(@file_get_contents($filename), true);
        if (!self::$currencies) {
            throw new \Exception('Config has not been loaded');
        }
    }

    public static function currencies()
    {
        if (self::$currencies) {
            return self::$currencies;
        }
        throw new \Exception('No currencies has been loaded');
    }
}
