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
$result = $telegram->getData();

$chat_id = $result["message"] ["chat"]["id"];
$content = array('chat_id' => $chat_id, 'text' => "Test");
$telegram->sendMessage($content);
```
See update.php for the complete example.

Build keyboard parameters
------------
```php
function buildKeyBoard(array $options, $onetime=true, $resize=true, $selective=true)
```
Check [ReplyKeyBoardMarkUp] (https://core.telegram.org/bots/api#replykeyboardmarkup) for more info.

Emoticons
------------
For a list of emoticons to use in your bot messages, please refer to the column Bytes of this table:
http://apps.timwhitlock.info/emoji/tables/unicode

Contact me
------------
You can contact me [via Telegram](https://telegram.me/ggrillo) but if you have an issue please [open](https://github.com/Eleirbag89/TelegramBotPHP/issues) one.
