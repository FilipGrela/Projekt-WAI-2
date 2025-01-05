<?php
require_once __DIR__ . '/../models/LoginModel.php';
require_once __DIR__ . '/../core/Database.php';
class LoginController
{
    function __construct(){
        $this->db = new Database();
    }
    public function index(){
        if (isset($_SESSION['user_id'])){
            (new Router)->redirect('/gallery');
        }
        include_once __DIR__ . '/../views/login.php';
    }

    public function login_user(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = htmlspecialchars($_POST['login'] ?? '');
            $password = htmlspecialchars($_POST['password'] ?? '');

            $result = $this->db->get_user_id($login, $password);
            $user_id = $result['user'];
            $message = $result['msg'];

            if ($message != ''){
                (new Router)->redirect('/login?' . 'error_message=' . $message);
            }

            $_SESSION['login'] = $login;
            $_SESSION['user_id'] = $user_id;
            (new Router)->redirect('/gallery');

        }else {
            echo "<p>Error: Invalid request.</p>";
        }
    }


    public function logout_user()
    {
        $_SESSION = [];
        session_destroy();
        session_start();

        (new Router)->redirect('/gallery');
    }
}