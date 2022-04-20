<?php

require_once '../../../vendor/autoload.php';

$bot_token = 'bot_token';
$telegram = new Telegram($bot_token);

$telegram->getUpdates();
$telegram->serveUpdate(0);

$res = $telegram->sendMessage([
    'chat_id' => 'adsf',  // Chat not found
    'text'    => 'Hello world',
]);
var_dump($res);
