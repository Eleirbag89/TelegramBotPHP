# TelegramBotPHP
> A very simple PHP [Telegram Bot API](https://core.telegram.org/bots) for sending messages.    
> (Almost) Compliant with the November 21, 2016 Telegram Bot API update.

Requirements
---------

* PHP5
* Curl for PHP5 must be enabled.
* Telegram API key, you can get one simply with [@BotFather](https://core.telegram.org/bots#botfather) with simple commands right after creating your bot.

For the WebHook:
* An SSL certificate (Telegram API requires this). You can use [Cloudflare's Free Flexible SSL](https://www.cloudflare.com/ssl) which crypts the web traffic from end user to their proxies if you're using CloudFlare DNS.    
Since the August 29 update you can use a self-signed ssl certificate.

For the GetUpdates:
* Some way to execute the script in order to serve messages (for example cronjob)

Installation
---------

#### composer
```
composer require shakibonline/telegrambotphp v0.0.2
```

#### manually


* Copy Telegram.php into your server and include it in your new bot script
```php
include("Telegram.php");
$telegram = new Telegram($bot_id);
```

* To enable error log file, also copy TelegramErrorLogger.php in the same directory of Telegram.php file

Configuration (WebHook)
---------

Navigate to 
https://api.telegram.org/bot(BOT_ID)/setWebhook?url=https://yoursite.com/your_update.php
Or use the Telegram class setWebhook method.

Examples
---------

```php
$telegram = new Telegram($bot_id);
$chat_id = $telegram->ChatID();
$content = array('chat_id' => $chat_id, 'text' => "Test");
$telegram->sendMessage($content);
```

If you want to get some specific parameter from the Telegram response:
```php
$telegram = new Telegram($bot_id);
$result = $telegram->getData();
$text = $result["message"] ["text"];
$chat_id = $result["message"] ["chat"]["id"];
$content = array('chat_id' => $chat_id, 'text' => "Test");
$telegram->sendMessage($content);
```

To upload a Photo or some other files, you need to load it with CurlFile:
```php
// Load a local file to upload. If is already on Telegram's Servers just pass the resource id
$img = curl_file_create('test.png','image/png'); 
$content = array('chat_id' => $chat_id, 'photo' => $img );
$telegram->sendPhoto($content);
```

To download a file on the Telegram's servers
```php
$file = $telegram->getFile($file_id);
$telegram->downloadFile($file["result"]["file_path"], "./my_downloaded_file_on_local_server.png");
```

See update.php or update cowsay.php for the complete example.
If you wanna see the CowSay Bot in action [add it] (https://telegram.me/cowmooobot).

If you want to use GetUpdates instead of the WebHook you need to call the the `serveUpdate` function inside a for cycle.
```php
$req = $telegram->getUpdates();
for ($i = 0; $i < $telegram-> UpdateCount(); $i++) {
	// You NEED to call serveUpdate before accessing the values of message in Telegram Class
	$telegram->serveUpdate($i);
	$text = $telegram->Text();
	$chat_id = $telegram->ChatID();

	if ($text == "/start") {
		$reply = "Working";
		$content = array('chat_id' => $chat_id, 'text' => $reply);
		$telegram->sendMessage($content);
	}
	// DO OTHER STUFF
}
```
See getUpdates.php for the complete example.

Functions
------------

For a complete and up-to-date functions documentation check http://eleirbag89.github.io/TelegramBotPHP/

Build keyboard parameters
------------
```php
buildKeyBoard(array $options, $onetime=true, $resize=true, $selective=true)
```
Send a custom keyboard. $option is an array of array KeyboardButton.  
Check [ReplyKeyBoardMarkUp] (https://core.telegram.org/bots/api#replykeyboardmarkup) for more info.    

```php
buildInlineKeyBoard(array $inline_keyboard)
```
Send a custom keyboard. $inline_keyboard is an array of array InlineKeyboardButton.  
Check [InlineKeyboardMarkup] (https://core.telegram.org/bots/api#inlinekeyboardmarkup) for more info.    

```php
buildInlineKeyBoardButton($text, $url, $callback_data, $switch_inline_query)
```
Create an InlineKeyboardButton.    
Check [InlineKeyBoardButton] (https://core.telegram.org/bots/api#inlinekeyboardbutton) for more info.    

```php
buildKeyBoardButton($text, $url, $request_contact, $request_location)
```
Create a KeyboardButton.    
Check [KeyBoardButton] (https://core.telegram.org/bots/api#keyboardbutton) for more info.    


```php
buildKeyBoardHide($selective=true)
```
Hide a custom keyboard.  
Check [ReplyKeyBoarHide] (https://core.telegram.org/bots/api#replykeyboardhide) for more info.    

```php
buildForceReply($selective=true)
```
Show a Reply interface to the user.  
Check [ForceReply] (https://core.telegram.org/bots/api#forcereply) for more info.

Emoticons
------------
For a list of emoticons to use in your bot messages, please refer to the column Bytes of this table:
http://apps.timwhitlock.info/emoji/tables/unicode

Contact me
------------
You can contact me [via Telegram](https://telegram.me/ggrillo) but if you have an issue please [open](https://github.com/Eleirbag89/TelegramBotPHP/issues) one.

Support me
------------
You can buy me a beer or two using [Paypal](https://paypal.me/eleirbag89)    
or support me using Flattr.    

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=eleirbag89&url=https://github.com/Eleirbag89/TelegramBotPHP&title=TelegramBotPHP&language=&tags=github&category=software) 
