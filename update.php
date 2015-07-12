<?php
/**
 * Telegram Bot example.
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
include("Telegram.php");

// Set the bot TOKEN
$bot_id = "bot_token";
// Instances the class
$telegram = new Telegram($bot_id);
// Get the data from Telegram
$result = $telegram->getData();

// Take text and chat_id from the message
$text = $result["message"] ["text"];
$chat_id = $result["message"] ["chat"]["id"];

// Check if the text is a command
if ($text == "/test") {
	if ($telegram->messageFromGroup($result)) {
		$reply = "Chat Group";
	} else {
		$reply = "Private Chat";
	}
        // Create option for the custom keyboard. Array of array string
        $option = array( array("A", "B"), array("C", "D") );
        // Get the keyboard
	$keyb = $telegram->buildKeyBoard($option);
	$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $reply);
	$telegram->sendMessage($content);
}
if ($text == "/git") {
    $reply = "Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP";
    // Build the reply array
    $content = array('chat_id' => $chat_id, 'text' => $reply);
    $telegram->sendMessage($content);
}

?>
