<?php
/**
 * Telegram Cowsay Bot Example.
 * Add @cowmooobot to try it!
 *
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
include 'Telegram.php';

// Set the bot TOKEN
$bot_token = 'bot_token';
// Instances the class
$telegram = new Telegram($bot_token);

/* If you need to manually take some parameters
*  $result = $telegram->getData();
*  $text = $result["message"] ["text"];
*  $chat_id = $result["message"] ["chat"]["id"];
*/

// Take text and chat_id from the message
$text = $telegram->Text();
$chat_id = $telegram->ChatID();

if ($text == '/start') {
    $option = [["\xF0\x9F\x90\xAE"], ['Git', 'Credit']];
    // Create a permanent custom keyboard
    $keyb = $telegram->buildKeyBoard($option, $onetime = false);
    $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Welcome to CowBot \xF0\x9F\x90\xAE \nPlease type /cowsay or click the Cow button !"];
    $telegram->sendMessage($content);
}
if ($text == '/cowsay' || $text == "\xF0\x9F\x90\xAE") {
    $randstring = rand().sha1(time());
    $cowurl = 'http://bangame.altervista.org/cowsay/fortune_image_w.php?preview='.$randstring;
    $content = ['chat_id' => $chat_id, 'text' => $cowurl];
    $telegram->sendMessage($content);
}
if ($text == '/credit' || $text == 'Credit') {
    $reply = "Eleirbag89 Telegram PHP API http://telegrambot.ienadeprex.com \nFrancesco Laurita (for the cowsay script) http://francesco-laurita.info/wordpress/fortune-cowsay-on-php-5";
    $content = ['chat_id' => $chat_id, 'text' => $reply];
    $telegram->sendMessage($content);
}

if ($text == '/git' || $text == 'Git') {
    $reply = 'Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP';
    $content = ['chat_id' => $chat_id, 'text' => $reply];
    $telegram->sendMessage($content);
}
