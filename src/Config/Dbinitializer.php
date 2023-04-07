<?php

namespace App\Config;

use Exception;
use PDO;

class DbInitializer
{
    public static function getPdoInstance(): PDO
    {
        if(!isset($_ENV['DB_HOST']) ||
        !isset($_ENV['DB_PORT']) ||
        !isset($_ENV['DB_NAME']) ||
        !isset ($_ENV['DB_USER']) ||
        !isset ($_ENV['DB_PASSWORD']) ||
        !isset ($_ENV['DB_CHARSET'])
        ){
            throw new Exception('Unable to load configuration, please load configuration via Dotenv::load');
        }

        $dsn = "mysql:host=" . $_ENV['DB_HOST'] .
            ";dbname=" . $_ENV['DB_NAME'] .
            ";port=" . $_ENV['DB_PORT'] .
            ";charset=" . $_ENV['DB_CHARSET'];

        $options =         [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        return new PDO(
            $dsn,
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            $options
        );
    }
}
