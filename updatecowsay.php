<?php
/**
 * Telegram Cowsay Bot Example.
 * Add @cowmooobot to try it!
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
include("Telegram.php");

// Set the bot TOKEN
$bot_id = "bot_token";
// Instances the class
$telegram = new Telegram($bot_id);

/* If you need to manually take some parameters
*  $result = $telegram->getData();
*  $text = $result["message"] ["text"];
*  $chat_id = $result["message"] ["chat"]["id"];
*/

// Take text and chat_id from the message
$text = $telegram->Text();
$chat_id = $telegram->ChatID();


if ($text == "/start") {
	$content = array('chat_id' => $chat_id, 'text' => "Welcome to CowBot \xF0\x9F\x90\xAE \nPlease type /cowsay");
    $telegram->sendMessage($content);
}
if ($text == "/cowsay" ) {
	$randstring = rand() . sha1(time());
	$cowurl = "http://francesco-laurita.info/fortune/fortune_image_w.php?preview=".$randstring;
	$content = array('chat_id' => $chat_id, 'text' => $cowurl);
    $telegram->sendMessage($content);
}
if ($text == "/credit") {
    $reply = "Eleirbag89 Telegram PHP API http://telegrambot.ienadeprex.com \nFrancesco Laurita (for the cowsay script) http://francesco-laurita.info/wordpress/fortune-cowsay-on-php-5";
    $content = array('chat_id' => $chat_id, 'text' => $reply);
    $telegram->sendMessage($content);
}

if ($text == "/git") {
    $reply = "Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP";
    $content = array('chat_id' => $chat_id, 'text' => $reply);
    $telegram->sendMessage($content);
}

?>
