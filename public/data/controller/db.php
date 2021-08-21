<?php

/*
The database connection controller
*/

class DB
{
    private static $dBConnection;


    public static function connectDB()
    {
        if (self::$dBConnection === null) {
            self::$dBConnection = new PDO('mysql:host=localhost;dbname=seawatch;charset=utf8', '', '');

            self::$dBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$dBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }

        return self::$dBConnection;
    }
}
