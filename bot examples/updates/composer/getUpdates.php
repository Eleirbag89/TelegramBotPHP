<?php

require_once '../../../vendor/autoload.php';

$bot_token = 'bot_token';
$telegram = new Telegram($bot_token);

var_dump($telegram->getUpdates());
