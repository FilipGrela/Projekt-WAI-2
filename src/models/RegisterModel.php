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


        // Check if user with the given email or username already exists
        if ($this->db->user_exists($email, $username)) {
            return "User with the same email or username already exists.";
        }
        $error = $this->db->add_user_to_db($email, $username, $password);

        return  $error;
    }

}