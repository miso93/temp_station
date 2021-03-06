<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 08.03.2016
 * Time: 21:52
 */

define("ROOT", __DIR__ . "/");

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Config
{

    private static $config = [
        'mysql' => [
            'host' => '91.127.105.132',
            'db_name' => 'meteo_station',
            'user'    => 'root',
            'pass'    => 'toor',
            'charset' => 'utf8',
        ],
    ];

    public static function get($key, $default = null)
    {
        list($first, $second) = explode('.', $key);

        if (isset(self::$config[$first][$second])) {
            return self::$config[$first][$second];
        }

        return $default;
    }
}