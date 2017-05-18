<?php
require_once '../../../vendor/autoload.php';


$bot_id = "bot_token";
$telegram = new Telegram($bot_id);

$res = $telegram->sendMessage([
    'chat_id' => 'adsf',  // Chat not found
    'text' => 'Hello world'
]);
var_dump($res);