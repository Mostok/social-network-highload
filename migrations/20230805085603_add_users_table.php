<?php

use Phpmig\Migration\Migration;

class AddUsersTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            create table if not exists users (
                id INT PRIMARY KEY AUTO_INCREMENT,
                first_name VARCHAR(255),
                second_name VARCHAR(255),
                birthdate DATE,
                sex VARCHAR(1),
                biography VARCHAR(255),
                city VARCHAR(255),
                password VARCHAR(255)
            );
        ";
        $container = $this->getContainer();
        $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = "drop table if exists users";
        $container = $this->getContainer();
        $container['db']->query($sql);
    }
}
