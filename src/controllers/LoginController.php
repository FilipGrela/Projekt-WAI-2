<?php
include_once __DIR__ . '/../models/LoginModel.php';
include_once __DIR__ . '/../core/Database.php';
class LoginController
{
    function __construct(){
        $this->db = new Database();

    }
    public function index(){
        require_once __DIR__ . '/../views/login.php';
    }

    public function login_user(){
        unset($_SESSION['login_message']);
        unset($_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = htmlspecialchars($_POST['login'] ?? '');
            $password = htmlspecialchars($_POST['password'] ?? '');
            $loginModel = new LoginModel();
            $result = $this->db->get_user_id($login, $password);
            $user_id = $result['user'];
            $message = $result['msg'];

            if ($message != ''){
                $_SESSION['login_message'] = $message;
                (new Router)->redirect('/login');
            }

            $_SESSION['user_id'] = $user_id;
            (new Router)->redirect('/gallery');

        }else {
            // If not a POST request or no file provided, return error
            echo "<p>Error: Invalid request.</p>";
        }
    }
}