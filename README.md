# TelegramBotPHP
[![API](https://img.shields.io/badge/Telegram%20Bot%20API-November%2017%2C%202017-36ade1.svg)](https://core.telegram.org/bots/api)
![PHP](https://img.shields.io/badge/php-%3E%3D5.3-8892bf.svg)
![CURL](https://img.shields.io/badge/cURL-required-green.svg)

[![Total Downloads](https://poser.pugx.org/eleirbag89/telegrambotphp/downloads)](https://packagist.org/packages/eleirbag89/telegrambotphp)
[![License](https://poser.pugx.org/eleirbag89/telegrambotphp/license)](https://packagist.org/packages/eleirbag89/telegrambotphp)
[![StyleCI](https://styleci.io/repos/38492095/shield?branch=master)](https://styleci.io/repos/38492095)

A very simple PHP [Telegram Bot API](https://core.telegram.org/bots).    
Compliant with the November 17, 2017 Telegram Bot API update.

Requirements
---------

* PHP >= 5.3
* Curl extension for PHP5 must be enabled.
* Telegram API key, you can get one simply with [@BotFather](https://core.telegram.org/bots#botfather) with simple commands right after creating your bot.

For the WebHook:
* An VALID SSL certificate (Telegram API requires this). You can use [Cloudflare's Free Flexible SSL](https://www.cloudflare.com/ssl) which crypts the web traffic from end user to their proxies if you're using CloudFlare DNS.    
Since the August 29 update you can use a self-signed ssl certificate.

For the getUpdates(Long Polling):
* Some way to execute the script in order to serve messages (for example cronjob)

Download
---------

#### Using Composer

From your project directory, run:
```
composer require eleirbag89/telegrambotphp
```
or
```
php composer.phar require eleirbag89/telegrambotphp
```
Note: If you don't have Composer you can download it [HERE](https://getcomposer.org/download/).

#### Using release archives

https://github.com/Eleirbag89/TelegramBotPHP/releases

#### Using Git

From a project directory, run:
```
git clone https://github.com/Eleirbag89/TelegramBotPHP.git
```

Installation
---------

#### Via Composer's autoloader

After downloading by using Composer, you can include Composer's autoloader:
```php
include (__DIR__ . '/vendor/autoload.php');

$telegram = new Telegram('YOUR TELEGRAM TOKEN HERE');
```

#### Via TelegramBotPHP class

Copy Telegram.php into your server and include it in your new bot script:
```php
include 'Telegram.php';

$telegram = new Telegram('YOUR TELEGRAM TOKEN HERE');
```

Note: To enable error log file, also copy TelegramErrorLogger.php in the same directory of Telegram.php file.

Configuration (WebHook)
---------

Navigate to 
https://api.telegram.org/bot(BOT_TOKEN)/setWebhook?url=https://yoursite.com/your_update.php
Or use the Telegram class setWebhook method.

Examples
---------

```php
$telegram = new Telegram('YOUR TELEGRAM TOKEN HERE');

$chat_id = $telegram->ChatID();
$content = array('chat_id' => $chat_id, 'text' => 'Test');
$telegram->sendMessage($content);
```

If you want to get some specific parameter from the Telegram response:
```php
$telegram = new Telegram('YOUR TELEGRAM TOKEN HERE');

$result = $telegram->getData();
$text = $result['message'] ['text'];
$chat_id = $result['message'] ['chat']['id'];
$content = array('chat_id' => $chat_id, 'text' => 'Test');
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
$telegram->downloadFile($file['result']['file_path'], './my_downloaded_file_on_local_server.png');
```

See update.php or update cowsay.php for the complete example.
If you wanna see the CowSay Bot in action [add it](https://telegram.me/cowmooobot).

If you want to use getUpdates instead of the WebHook you need to call the the `serveUpdate` function inside a for cycle.
```php
$telegram = new Telegram('YOUR TELEGRAM TOKEN HERE');

$req = $telegram->getUpdates();

for ($i = 0; $i < $telegram-> UpdateCount(); $i++) {
	// You NEED to call serveUpdate before accessing the values of message in Telegram Class
	$telegram->serveUpdate($i);
	$text = $telegram->Text();
	$chat_id = $telegram->ChatID();

	if ($text == '/start') {
		$reply = 'Working';
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

Build keyboards
------------

Telegram's bots can have two different kind of keyboards: Inline and Reply.    
The InlineKeyboard is linked to a particular message, while the ReplyKeyboard is linked to the whole chat.    
They are both an array of array of buttons, which rapresent the rows and columns.    
For instance you can arrange a ReplyKeyboard like this:
![ReplyKeabordExample](https://picload.org/image/rilclcwr/replykeyboard.png)
using this code:
```php
$option = array( 
    //First row
    array($telegram->buildKeyboardButton("Button 1"), $telegram->buildKeyboardButton("Button 2")), 
    //Second row 
    array($telegram->buildKeyboardButton("Button 3"), $telegram->buildKeyboardButton("Button 4"), $telegram->buildKeyboardButton("Button 5")), 
    //Third row
    array($telegram->buildKeyboardButton("Button 6")) );
$keyb = $telegram->buildKeyBoard($option, $onetime=false);
$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "This is a Keyboard Test");
$telegram->sendMessage($content);
```
When a user click on the button, the button text is send back to the bot.    
For an InlineKeyboard it's pretty much the same (but you need to provide a valid URL or a Callback data)
![InlineKeabordExample](https://picload.org/image/rilclcwa/replykeyboardinline.png)
```php
$option = array( 
    //First row
    array($telegram->buildInlineKeyBoardButton("Button 1", $url="http://link1.com"), $telegram->buildInlineKeyBoardButton("Button 2", $url="http://link2.com")), 
    //Second row 
    array($telegram->buildInlineKeyBoardButton("Button 3", $url="http://link3.com"), $telegram->buildInlineKeyBoardButton("Button 4", $url="http://link4.com"), $telegram->buildInlineKeyBoardButton("Button 5", $url="http://link5.com")), 
    //Third row
    array($telegram->buildInlineKeyBoardButton("Button 6", $url="http://link6.com")) );
$keyb = $telegram->buildInlineKeyBoard($option);
$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "This is a Keyboard Test");
$telegram->sendMessage($content);
```
This is the list of all the helper functions to make keyboards easily:     

```php
buildKeyBoard(array $options, $onetime=true, $resize=true, $selective=true)
```
Send a custom keyboard. $option is an array of array KeyboardButton.  
Check [ReplyKeyBoardMarkUp](https://core.telegram.org/bots/api#replykeyboardmarkup) for more info.    

```php
buildInlineKeyBoard(array $inline_keyboard)
```
Send a custom keyboard. $inline_keyboard is an array of array InlineKeyboardButton.  
Check [InlineKeyboardMarkup](https://core.telegram.org/bots/api#inlinekeyboardmarkup) for more info.    

```php
buildInlineKeyBoardButton($text, $url, $callback_data, $switch_inline_query)
```
Create an InlineKeyboardButton.    
Check [InlineKeyBoardButton](https://core.telegram.org/bots/api#inlinekeyboardbutton) for more info.    

```php
buildKeyBoardButton($text, $url, $request_contact, $request_location)
```
Create a KeyboardButton.    
Check [KeyBoardButton](https://core.telegram.org/bots/api#keyboardbutton) for more info.    


```php
buildKeyBoardHide($selective=true)
```
Hide a custom keyboard.  
Check [ReplyKeyBoarHide](https://core.telegram.org/bots/api#replykeyboardhide) for more info.    

```php
buildForceReply($selective=true)
```
Show a Reply interface to the user.  
Check [ForceReply](https://core.telegram.org/bots/api#forcereply) for more info.

List of Bots using the library
------------

Let me know using this [Issue](https://github.com/Eleirbag89/TelegramBotPHP/issues/80) if you have made a bot using this API, I will add it to this section.    
* [iHarfBot](https://t.me/iHarfBot) - Lang: Persian/Farsi
* [AsansorBot](https://t.me/AsansorBot) - Lang: Persian/Farsi
* [IDPbot](https://t.me/IDPbot) - Lang: Persian/Farsi (Down as 14/10/2019)
* [Evil Insult Generator](https://telegram.me/EvilInsultGeneratorBot) - Lang English/German
* [ibelitbot](https://telegram.me/ibelitbot) -Lang Persian (Down as 14/10/2019)
* [Advanced Pools Bot](https://telegram.me/apollbot) - Lang Italian/English([Contribute](https://poeditor.com/join/project/NMAUjrAZ5f))
* [Btn_Bot](https://telegram.me/Btn_Bot) - Lang pt-BR - Cria botões transparente para publicações.
* [DiffSGLaPuntabot](https://telegram.me/DiffSGLaPuntabot) [DiffGravinaCTbot](https://telegram.me/DiffGravinaCTbot) [DiffAdranobot](https://t.me/DiffAdranobot) - Lang: it/Italian - Bots to handle Recycling in San Giovanni La Punta, Gravina and Adrano (OpenSource Template https://github.com/Gualty/BOTDifferenziata)
* [GoBot](https://t.me/CoReYe_PGO_bot)
* [RaidBot](https://t.me/CoReYe_PGO_Raid_bot)

Emoticons
------------
For a list of emoticons to use in your bot messages, please refer to the column Bytes of this table:
http://apps.timwhitlock.info/emoji/tables/unicode

License
------------

This open-source software is distributed under the MIT License. See LICENSE.md

Contributing
------------

All kinds of contributions are welcome - code, tests, documentation, bug reports, new features, etc...

* Send feedbacks.
* Submit bug reports.
* Write/Edit the documents.
* Fix bugs or add new features.

Contact me
------------

You can contact me [via Telegram](https://telegram.me/ggrillo) but if you have an issue please [open](https://github.com/Eleirbag89/TelegramBotPHP/issues) one.

Support me
------------

You can support me using via LiberaPay [![Donate using Liberapay](https://liberapay.com/assets/widgets/donate.svg)](https://liberapay.com/eleirbag89/donate)

or buy me a beer or two using [Paypal](https://paypal.me/eleirbag89). 
