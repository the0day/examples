<?php

namespace App\Helpers;

class ContractConnector
{
    public static $Error = false;
    public static $Data = false;

    public static function accept($err, $data)
    {
        self::$Error = $err;
        self::$Data = $data;
    }

    public static function response()
    {
        if (is_array(self::$Data)) {
            return self::$Data;
        }

        return self::$Data;
    }

}
