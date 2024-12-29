<?php
include_once __DIR__ . '/../models/RegisterModel.php';
class RegisterController
{
    public function index(){
        require_once __DIR__ . '/../views/register.php';
    }
}