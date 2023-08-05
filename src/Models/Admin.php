<?php

define('MYSQL_ASSOC', MYSQLI_ASSOC);

class Admin
{
    public static function fetchTasks($limit, $page, $order_by, $desc = '')
    {
        try {
            global $db;

            $res = mysqli_query($db, "SELECT COUNT(*) FROM `tasks`");
            $row = mysqli_fetch_row($res);
            $total = $row[0];
            $maxpages = ceil($total / $limit);
            $page = $page > $maxpages ? $maxpages : $page;
            $offset = ($page - 1) * $limit;

            $desc = ($desc === 'DESC') ? 'DESC' : 'ASC';
            $colums = array("name", "email", "ready");
            $key = array_search($order_by, $colums);
            $column = $key ? $colums[$key] : 'id';

            $select = "SELECT * FROM `tasks` ORDER BY `$column` $desc LIMIT ? OFFSET ?";

            $smtp = mysqli_prepare($db, $select);
            mysqli_stmt_bind_param($smtp, "ii", $limit, $offset);
            mysqli_stmt_execute($smtp);

            $res = mysqli_stmt_get_result($smtp);
            $list = mysqli_fetch_all($res, MYSQL_ASSOC);
            mysqli_stmt_close($smtp);

            return [$list, $maxpages];
        } catch (Exeption $e) {
            error_log($e);
            mysqli_rollback($db);
            return false;
        }
    }

    public static function taskPerformed($id)
    {

        try {
            global $db;

            $update = "UPDATE `tasks` SET `ready` = '1' WHERE `id` = ?";
            $smtp = mysqli_prepare($db, $update);
            mysqli_stmt_bind_param($smtp, "i", $id);
            mysqli_stmt_execute($smtp);
            mysqli_stmt_close($smtp);

            return true;
        } catch (Exeption $e) {
            error_log($e);
            mysqli_rollback($db);
            return false;
        }
    }

    public static function getTaskById($id)
    {
        try {
            global $db;

            $select = "SELECT * FROM `tasks` WHERE `id` = ?";
            $smtp = mysqli_prepare($db, $select);
            mysqli_stmt_bind_param($smtp, "i", $id);
            mysqli_stmt_execute($smtp);
            $res = mysqli_stmt_get_result($smtp);
            $task = mysqli_fetch_all($res, MYSQL_ASSOC);
            mysqli_stmt_close($smtp);

            return array_shift($task);
        } catch (Exeption $e) {
            error_log($e);
            mysqli_rollback($db);
            return false;
        }
    }

    public static function updateTaskText($id, $text)
    {
        try {
            global $db;

            $update = "UPDATE `tasks` SET `text` = ?, `admin_change` = '1' WHERE `id` = ?";
            $smtp = mysqli_prepare($db, $update);
            mysqli_stmt_bind_param($smtp, "si", $text, $id);
            mysqli_stmt_execute($smtp);
            mysqli_stmt_close($smtp);

            return true;
        } catch (Exeption $e) {
            error_log($e);
            mysqli_rollback($db);
            return false;
        }
    }
}
