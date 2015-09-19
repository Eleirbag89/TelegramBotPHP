# TelegramBotPHP
> A very simple PHP [Telegram Bot API](https://core.telegram.org/bots) for sending messages.    
> Compliant with the September 18, 2015 Telegram Bot API update.

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

* Copy Telegram.php into your server and include it in your new bot script
```php
include("Telegram.php");
$telegram = new Telegram($bot_id);
```

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
$telegram->downloadFile($file["file_path"], "./my_downloaded_file_on_local_server.png");
```

See update.php or update cowsay.php for the complete example.
If you wanna see the CowSay Bot in action [add it] (https://telegram.me/cowmooobot).

If you want to use GetUpdates instead of the WebHook you need to call the the serveUpdate function inside a for cycle.
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

* getMe()  
[A method for testing your bot] (https://core.telegram.org/bots/api#getme).  
* sendMessage(array $content)  
[Send a message] (https://core.telegram.org/bots/api#sendmessage).  
$content is an array with at least chat_id and text.
* forwardMessage(array $content)  
[Forward a message] (https://core.telegram.org/bots/api#forwardmessage).  
$content is an array with chat_id, from_chat_id and message_id.
* sendPhoto(array $content)  
[Send a photo] (https://core.telegram.org/bots/api#sendphoto).  
$content is an array with at least chat_id and photo.
* sendAudio(array $content)  
[Send an audio] (https://core.telegram.org/bots/api#sendaudio).  
$content is an array with at least chat_id and audio.
* sendDocument(array $content)  
[Send a document] (https://core.telegram.org/bots/api#senddocument).  
$content is an array with at least chat_id and document.
* sendSticker(array $content)  
[Send a sticker] (https://core.telegram.org/bots/api#sendsticker).  
$content is an array with at least chat_id and sticker.
* sendVideo(array $content)  
[Send a video] (https://core.telegram.org/bots/api#sendvideo).  
$content is an array with at least chat_id and video.
* sendVoice(array $content)  
[Send a voice message] (https://core.telegram.org/bots/api#sendvoice).  
$content is an array with at least chat_id and audio.
* sendLocation(array $content)  
[Send a location] (https://core.telegram.org/bots/api#sendlocation).  
$content is an array with at least chat_id, latitude and longitude.
* sendChatAction(array $content)  
[Send a chat action] (https://core.telegram.org/bots/api#sendchataction).  
$content is an array with at least chat_id and action.
* getUserProfilePhotos(array $content)  
[Get a list of profile pictures for a user] (https://core.telegram.org/bots/api#getuserprofilephotos).  
$content is an array with at least user_id.
* getFile($file_id)  
[Use this method to get basic info about a file and prepare it for downloading] (https://core.telegram.org/bots/api#getfile).  
* downloadFile($telegram_file_path, $local_file_path)  
Download a File using the Thelegram's file_path returned from getFile() and save it in $local_file_path. 
* setWebHook($url, $certificate)  
[Set a WebHook for the bot] (https://core.telegram.org/bots/api#setwebhook).  
* getData()  
Return the user request as array
* Text()  
Return the Text of the user message
* ChatID()  
Return the id of the chat
* Date()  
Return the date of the mesage (Timestamp)
* FirstName()  
Return the user's first name
* LastName()  
Return the user's last name
* Username()  
Return the user's username
* messageFromGroup()  
Check if the message is sent from a group chat (boolean)    
* getUpdates($offset = 0, $limit = 100, $timeout = 0, $update = true)    
Get the updates. If $update = true confirm the update to Telegram in order to avoid duplicate replies.
See [Telegram doc] (https://core.telegram.org/bots/api#getting-updates)  for the other parameters.
* serveUpdate($update)    
Set the current message to the one with index $update.
* UpdateID()  
Get the message's Update ID.
* UpdateCount()  
Return the GetUpdates messages count.

Build keyboard parameters
------------
```php
buildKeyBoard(array $options, $onetime=true, $resize=true, $selective=true)
```
Send a custom keyboard. $option is an array of array string.  
Check [ReplyKeyBoardMarkUp] (https://core.telegram.org/bots/api#replykeyboardmarkup) for more info.    

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
You can support me using Flattr.    

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=eleirbag89&url=https://github.com/Eleirbag89/TelegramBotPHP&title=TelegramBotPHP&language=&tags=github&category=software) 