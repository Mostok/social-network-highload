<?php

include_once ROOT . '/Models/Admin.php';

class AdminController
{

    private function checkAuthorizeUser()
    {
        if (!isset($_SESSION['logged_user'])) {
            header('Location: /tasks');
            exit;
        }
    }

    public function tasks()
    {
        $this->checkAuthorizeUser();

        $page = (isset($_GET['page']) && (int) $_GET['page']) ? $_GET['page'] : 1;
        $desc = $_GET['desc'] ?? "ASC";
        $order_by = $_GET['order_by'] ?? 'id';
        $limit = 3;

        [$list, $maxpages] = Admin::fetchTasks($limit, $page, $order_by, $desc);
        if ($page > $maxpages) {
            $page = $maxpages;
            $_GET['page'] = $page;
        }
        $contentPage = ROOT . "/Views/adminTasks.php";
        require_once ROOT . "/Views/header.php";
        return true;
    }

    public function taskPerformed($id)
    {
        $this->checkAuthorizeUser();
        $update = Admin::taskPerformed($id);
        if ($update) {
            header('Location: /admin');
            return true;
        }
    }

    public function taskEdit($id)
    {
        $this->checkAuthorizeUser();

        $task = Admin::getTaskById($id);

        $email = $task['email'];
        $name = $task['name'];
        $text = $task['text'];

        $contentPage = ROOT . "/Views/adminEditTask.php";
        require_once ROOT . "/Views/header.php";
        return true;
    }

    public function taskUpdate($id)
    {
        $this->checkAuthorizeUser();

        $errors = array();
        if (empty($_POST['text'])) {
            $errors[] = 'Не заполненно поле ТЕКСТ';
        }

        if (!empty($errors)) {

            $task = Admin::getTaskById($id);

            $email = $task['email'];
            $name = $task['name'];
            $text = $task['text'];

            $contentPage = ROOT . "/Views/adminEditTask.php";
            require_once ROOT . "/Views/header.php";
            return true;
        } else {
            $text = htmlentities($_POST['text'], ENT_QUOTES, "UTF-8");
            $update = Admin::updateTaskText($id, $text);

            if ($update) {
                $contentPage = ROOT . "/Views/adminUpdateTask.php";
                require_once ROOT . "/Views/header.php";
                return true;
            }
        }
    }

}
