<?php
include_once __DIR__ . '/../models/RegisterModel.php';
class RegisterController
{
    public function index(){
        require_once __DIR__ . '/../views/register.php';
        $password_message = '';
    }

    public function __construct()
    {}

    public function add_user(){
        unset($_SESSION['password_message']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email'] ?? '');
            $login = htmlspecialchars($_POST['login'] ?? '');
            $password = htmlspecialchars($_POST['password'] ?? '');
            $password_repeat = htmlspecialchars($_POST['password_rep'] ?? '');


            $registerModel = new RegisterModel();
            $error_msg = $registerModel->register($email, $login, $password, $password_repeat);

            if ($error_msg) {
                $_SESSION['password_message'] = $error_msg;
                $this->redirect('/register?error_message=Hasła się nie zgadzają');
            }else{
                $this->redirect('/login');
            }

        } else {
            // If not a POST request or no file provided, return error
            echo "<p>Error: Invalid request.</p>";
        }
    }

    private function redirect($location){
        header("Location: $location");
        exit;
    }
}