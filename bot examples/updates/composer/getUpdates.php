<?php

require_once '../../../vendor/autoload.php';

$bot_id = 'bot_token';
$telegram = new Telegram($bot_id);

var_dump($telegram->getUpdates());
