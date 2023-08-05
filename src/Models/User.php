<?php

define('MYSQL_ASSOC', MYSQLI_ASSOC);

class User
{
    public static function checkUser($name, $password)
    {
        try {
            global $db;
            $select = "SELECT * FROM `users` WHERE `name` LIKE ? AND `password` LIKE ?";
            $smtp = mysqli_prepare($db, $select);

            mysqli_stmt_bind_param($smtp, "ss", $name, $password);
            mysqli_stmt_execute($smtp);

            $res = mysqli_stmt_get_result($smtp);
            $user = mysqli_fetch_assoc($res);
            mysqli_stmt_close($smtp);
            return $user;
        } catch (Exception $e) {
            error_log($e);
            mysqli_rollback($db);
            return false;
        }
    }
}
