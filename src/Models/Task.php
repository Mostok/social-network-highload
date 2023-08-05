<?php
define('MYSQL_ASSOC', MYSQLI_ASSOC);

class Task
{
    public static function fetchTasks($limit, $page, $order_by, $desc = 'ASC')
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
        } catch (Exception $e) {
            error_log($e);
            mysqli_rollback($db);
            return false;
        }
    }

    public static function store($name, $email, $text)
    {

        try {
            global $db;

            $input = "INSERT INTO `tasks` (`id`, `email`, `text`, `name`, `date`) VALUES (NULL, ?, ?, ?, CURRENT_DATE());";
            $smtp = mysqli_prepare($db, $input);

            mysqli_stmt_bind_param($smtp, "sss", $name, $email, $text);
            mysqli_stmt_execute($smtp);
            mysqli_stmt_close($smtp);

            return true;
        } catch (Exception $e) {
            error_log($e);
            mysqli_rollback($db);
            return false;
        }
    }

}
