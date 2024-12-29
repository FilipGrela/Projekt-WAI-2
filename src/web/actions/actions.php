<?php
require_once __DIR__ . '/../vendor/autoload.php';
//mongodb://<username>:<password>@<host>:<port>/<database>?<options>

function get_db() {
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b'
        ]);
    return $mongo->wai;
}

function add_user($email, $username, $password){
    try {
        $db = get_db();
        $user = $db->users->insertOne([
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ]);
        return 0;
    } catch (Exception $e) {
        return $e;
        echo $e;
    }

}

function add_image_to_db($autor, $title, $image_name, $user){
    $db = get_db();
    $db->images->insertOne([
        'author' => $autor,
        'title' => $title,
        'image_name' => $image_name,
        'user' => $user,
    ]);
}


function get_image_by_name($image_name)
{
    try {
        $db = get_db();
        $image = $db->images->findOne(['image_name' => $image_name]);
        if ($image) {

            return $image;
        } else {
            return "No image found with the given name.";
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}