<?php
require_once __DIR__ . '/../web/vendor/autoload.php';
class Database
{
    function __construct(){
        $this->db = $this->get_db();
    }
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
        $this->db->images->insertOne([
            'author' => $autor,
            'title' => $title,
            'image_name' => $image_name,
            'user' => $user,
        ]);
    }


    public function user_exists($email, $username): bool
    {
        try {
            $user = $this->db->users->findOne([
                '$or' => [
                    ['email' => $email],
                    ['username' => $username]
                ]
            ]);
            return $user !== null;
        } catch (Exception $e) {
            return false;
        }
    }

    public function add_user_to_db($email, $username, $password): string
    {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $this->db->users->insertOne([
                'email' => $email,
                'username' => $username,
                'password' => $hashed_password,
            ]);
            return '';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function get_user_id($login, $password): array
    {
        $msg = [
            'user' => '',
            'msg' => ''
        ];
        try {
            $user = $this->db->users->findOne(['username' => $login]);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $msg['user'] = $user['_id']->__toString();;
                    $msg['msg'] = '';
                } else {
                    $msg['user'] = '';
                    $msg['msg'] = "Invalid password.";
                }
            } else {
                $msg['user'] = '';
                $msg['msg'] = "No user found with the given username.";
            }
        } catch (Exception $e) {
            $msg['user'] = '';
            $msg['msg'] = $e->getMessage();
        }
        return $msg;
    }

    public function get_image_by_name($image_name){
        try {
            $image = $this->db->images->findOne(['image_name' => $image_name]);
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