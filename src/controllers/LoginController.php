<?php
include_once __DIR__ . '/../models/LoginModel.php';
class LoginController
{
    public function index(){
        require_once __DIR__ . '/../views/login.php';
    }
}