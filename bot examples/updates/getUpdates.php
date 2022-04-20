<?php
/**
 * Telegram Bot Example whitout WebHook.
 * It uses getUpdates Telegram's API.
 *
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
include 'Telegram.php';

$bot_token = 'bot_token';
$telegram = new Telegram($bot_token);

// Get all the new updates and set the new correct update_id
$req = $telegram->getUpdates();
for ($i = 0; $i < $telegram->UpdateCount(); $i++) {
    // You NEED to call serveUpdate before accessing the values of message in Telegram Class
    $telegram->serveUpdate($i);
    $text = $telegram->Text();
    $chat_id = $telegram->ChatID();

    if ($text == '/start') {
        $reply = 'Working';
        $content = ['chat_id' => $chat_id, 'text' => $reply];
        $telegram->sendMessage($content);
    }
    if ($text == '/test') {
        if ($telegram->messageFromGroup()) {
            $reply = 'Chat Group';
        } else {
            $reply = 'Private Chat';
        }
        // Create option for the custom keyboard. Array of array string
        $option = [['A', 'B'], ['C', 'D']];
        // Get the keyboard
        $keyb = $telegram->buildKeyBoard($option);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $reply];
        $telegram->sendMessage($content);
    }
    if ($text == '/git') {
        $reply = 'Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP';
        // Build the reply array
        $content = ['chat_id' => $chat_id, 'text' => $reply];
        $telegram->sendMessage($content);
    }
}
