<?php
include("Telegram.php");


$bot_id = "bot_token";
$telegram = new Telegram($bot_id);

$result = $telegram->getData();


$text = $result["message"] ["text"];
$chat_id = $result["message"] ["chat"]["id"];


if ($text == "/help") {
	$reply = "Comandi: /proroga /appuntamento /orario /pappy /fefy /derp /help";
	$content = array('chat_id' => $chat_id, 'text' => $reply);
    $telegram->sendMessage($content);
}

?>
