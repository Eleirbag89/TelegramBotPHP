# TelegramBotPHP
> A very simple PHP [Telegram Bot API](https://core.telegram.org/bots) for sending messages.

Requirements
---------

* PHP5
* Curl for PHP5 must be enabled.
* An SSL certificate (Telegram API requires this). You can use [Cloudflare's Free Flexible SSL](https://www.cloudflare.com/ssl) which crypts the web traffic from end user to their proxies if you're using CloudFlare DNS.
* Telegram API key, you can get one simply with [@BotFather](https://core.telegram.org/bots#botfather) with simple commands right after creating your bot.

Installation
---------

* Copy the php files into your server

Configuration
---------

Navigate to 
https://api.telegram.org/bot(BOT_ID)/setWebhook?url=https://yoursite.com/your_update.php

Example
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

See update.php for the complete example.

Functions
------------

* sendMessage(array $content)
Send a message. $content is an array with at least chat_id and text.
* getData()
Return the user request as array
```php
Text()
```
Return the Text of the user message
```php
ChatID()
```
Return the id of the chat
```php
Date() {
```
Return the date of the mesage (Timestamp)
```php
FirstName()
```
Return the user first name
```php
LastName()
```
Return the user last name
```php
Username()
```
Return the user username
```php
messageFromGroup()
```
Check if the message is sent from a group chat (boolean)

Build keyboard parameters
------------
```php
buildKeyBoard(array $options, $onetime=true, $resize=true, $selective=true)
```
Send a custome keyboard. $option is an array of array string.
Check [ReplyKeyBoardMarkUp] (https://core.telegram.org/bots/api#replykeyboardmarkup) for more info.

Emoticons
------------
For a list of emoticons to use in your bot messages, please refer to the column Bytes of this table:
http://apps.timwhitlock.info/emoji/tables/unicode

Contact me
------------
You can contact me [via Telegram](https://telegram.me/ggrillo) but if you have an issue please [open](https://github.com/Eleirbag89/TelegramBotPHP/issues) one.
