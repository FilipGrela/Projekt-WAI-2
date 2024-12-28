<?php
require_once __DIR__ . '/../vendor/autoload.php';
//mongodb://<username>:<password>@<host>:<port>/<database>?<options>

$malpa = '%40';
//$uri = 'mongodb://admin:p%40ssw0rd@127.0.0.1:27017/waiDB';
$uri = 'mongodb://wai_web:w%40i_w3b@127.0.0.1:27017/waiDB';
$client = new MongoDB\Client($uri);

try {

    $client->test->command(['ping' => 1]);

    echo 'Successfully pinged the MongoDB server.', PHP_EOL;

} catch (MongoDB\Driver\Exception\RuntimeException $e) {

    printf("Failed to ping the MongoDB server: %s\n", $e->getMessage());

}