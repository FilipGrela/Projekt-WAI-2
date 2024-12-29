<?php
require_once __DIR__ . '/../web/vendor/autoload.php';
class Database
{
    public function get_db() {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai",
            [
                'username' => 'wai_web',
                'password' => 'w@i_w3b'
            ]);
        return $mongo->wai;
    }

    public function add_image_to_db($autor, $title, $image_name, $user){
        $db = $this->get_db();
        $db->images->insertOne([
            'author' => $autor,
            'title' => $title,
            'image_name' => $image_name,
            'user' => $user,
        ]);
    }

    public function get_image_by_name($image_name){
        try {
            $db = $this->get_db();
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
}