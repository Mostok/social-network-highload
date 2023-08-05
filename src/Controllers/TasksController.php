<?php

include_once ROOT . '/Models/Task.php';

class TasksController
{

    public function main()
    {
        header('Location: /tasks');
        return true;
    }

    function list() {
        $page = (isset($_GET['page']) && (int) $_GET['page']) ? $_GET['page'] : 1;

        $desc = $_GET['desc'] ?? "ASC";

        $order_by = $_GET['order_by'] ?? 'id';

        $limit = 3;

        [$list, $maxpages] = Task::fetchTasks($limit, $page, $order_by, $desc);
        if ($page > $maxpages) {
            $page = $maxpages;
            $_GET['page'] = $page;
        }
        $contentPage = ROOT . "/Views/tasks.php";
        require_once ROOT . "/Views/header.php";
        return true;
    }

    public function create()
    {
        $contentPage = ROOT . "/Views/create.php";
        require_once ROOT . "/Views/header.php";
        return true;
    }

    public function store()
    {
        $errors = array();
        if (empty($_POST['name'])) {
            $errors[] = 'Не заполненно поле ИМЯ';
        }

        if (empty($_POST['text'])) {
            $errors[] = 'Не заполненно поле ТЕКСТ';
        }

        if (empty($_POST['email'])) {
            $errors[] = 'Не заполненно поле EMAIL';
        } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email не email';
        }

        if (!empty($errors)) {
            $contentPage = ROOT . "/Views/create.php";
            require_once ROOT . "/Views/header.php";
        } else {
            $name = htmlentities($_POST['name'], ENT_QUOTES, "UTF-8");
            $text = htmlentities($_POST['text'], ENT_QUOTES, "UTF-8");
            $email = htmlentities($_POST['email'], ENT_QUOTES, "UTF-8");
            $store = Task::store($name, $email, $text);

            if ($store) {
                $contentPage = ROOT . "/Views/store.php";
                require_once ROOT . "/Views/header.php";
                return true;
            }
        }
    }
}
