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
                $this->link = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname='  . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
                $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance = $this;
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        return self::$instance;
    }

}
