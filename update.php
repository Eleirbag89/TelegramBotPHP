<?php
include("Telegram.php");


$bot_id = "bot_token";
$telegram = new Telegram($bot_id);

$result = $telegram->getData();


$text = $result["message"] ["text"];
$chat_id = $result["message"] ["chat"]["id"];


if ($text == "/git") {
	$reply = "Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP";
	$content = array('chat_id' => $chat_id, 'text' => $reply);
    $telegram->sendMessage($content);
}

?>
