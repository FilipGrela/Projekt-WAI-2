<?php

require_once __DIR__ . '/../core/Database.php';

class RegisterModel
{
    function __construct(){
        $this->db = new Database();
    }

    function register($email, $username, $password, $password_repeat)
    {
        if($password != $password_repeat){
            return "Passwords do not match";
        }
        $error = $this->db->add_user_to_db($email, $username, $password);

        return  $error;
    }

}