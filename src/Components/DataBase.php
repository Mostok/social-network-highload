<?php

namespace Src\Components;

use PDO;
use PDOException;

class DataBase
{
    public static $instance;
    public $link;

    public function __construct()
    {
        if (!self::$instance) {
            try {
                $this->link = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname='  . $_ENV['DB_DATABASE'] . ';charset=utf8mb4', $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
                $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->link->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8mb4");
                $this->link->exec("SET NAMES utf8mb4");

                self::$instance = $this;
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        return self::$instance;
    }

}
