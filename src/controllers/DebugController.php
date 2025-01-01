<?php
class DebugController
{
    public function __construct()
    {

        ini_set('display_errors', 1);
        error_reporting(E_ALL);


        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

    }

    public function index(){
        include_once __DIR__ . '/../views/debug.php';
    }
}