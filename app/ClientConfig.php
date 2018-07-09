<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 30-05-2018
 * Time: 20:00
 */

namespace App;


class ClientConfig
{
    public static function configJson()
    {
        return json_encode(self::config());
    }

    public static function config()
    {
        return [
            'APP_URL' => env( 'APP_URL' ),
            'AUTH0_DOMAIN' => env('AUTH0_DOMAIN'),
            'AUTH0_FRONTEND_CLIENT_ID' => env('AUTH0_FRONTEND_CLIENT_ID'),
        ];
    }
}