<?php
require_once __DIR__ . '/../models/RegisterModel.php';
class RegisterController
{
    public function __construct()
    {}

    public function index(){
        if (isset($_SESSION['user_id'])){
            (new Router)->redirect('/gallery');
        }
        include_once __DIR__ . '/../views/register.php';
    }

    public function add_user(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email'] ?? '');
            $login = htmlspecialchars($_POST['login'] ?? '');
            $password = htmlspecialchars($_POST['password'] ?? '');
            $password_repeat = htmlspecialchars($_POST['password_rep'] ?? '');


            $registerModel = new RegisterModel();
            $error_msg = $registerModel->register($email, $login, $password, $password_repeat);

            if ($error_msg) {
                (new Router())->redirect('/register?error_message='.$error_msg);
            }else{
                (new Router())->redirect('/login');
            }

        } else {
            echo "<p>Error: Invalid request.</p>";
        }
    }

}