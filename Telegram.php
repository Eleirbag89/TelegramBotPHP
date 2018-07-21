<?php

if (file_exists('TelegramErrorLogger.php')) {
    require_once 'TelegramErrorLogger.php';
}

/**
 * Telegram Bot Class.
 *
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
class Telegram
{
    /**
     * Constant for type Inline Query.
     */
    const INLINE_QUERY = 'inline_query';
    /**
     * Constant for type Callback Query.
     */
    const CALLBACK_QUERY = 'callback_query';
    /**
     * Constant for type Edited Message.
     */
    const EDITED_MESSAGE = 'edited_message';
    /**
     * Constant for type Reply.
     */
    const REPLY = 'reply';
    /**
     * Constant for type Message.
     */
    const MESSAGE = 'message';
    /**
     * Constant for type Photo.
     */
    const PHOTO = 'photo';
    /**
     * Constant for type Video.
     */
    const VIDEO = 'video';
    /**
     * Constant for type Audio.
     */
    const AUDIO = 'audio';
    /**
     * Constant for type Voice.
     */
    const VOICE = 'voice';
    /**
     * Constant for type Document.
     */
    const DOCUMENT = 'document';
    /**
     * Constant for type Location.
     */
    const LOCATION = 'location';
    /**
     * Constant for type Contact.
     */
    const CONTACT = 'contact';
    /**
     * Constant for type Channel Post.
     */
    const CHANNEL_POST = 'channel_post';
    /**
     * Constant for type Contact.
     */
    const STICKER = 'sticker';
    /**
     * Constant for type Video Note.
     */
    const VIDEONOTE = 'video_note';

    private $bot_token = '';
    private $data = [];
    private $updates = [];
    private $log_errors;
    private $proxy = [];

    /// Class constructor

    /**
     * Create a Telegram instance from the bot token
     * \param $bot_token the bot token
     * \param $log_errors enable or disable the logging
     * \param $proxy array with the proxy configuration (url, port, type, auth)
     * \return an instance of the class.
     */
    public function __construct($bot_token, $log_errors = true, array $proxy = [])
    {
        $this->bot_token = $bot_token;
        $this->data = $this->getData();
        $this->log_errors = $log_errors;
        $this->proxy = $proxy;
    }

    /// Do requests to Telegram Bot API

    /**
     * Contacts the various API's endpoints
     * \param $api the API endpoint
     * \param $content the request parameters as array
     * \param $post boolean tells if $content needs to be sends
     * \return the JSON Telegram's reply.
     */
    public function endpoint($api, array $content, $post = true)
    {
        $url = 'https://api.telegram.org/bot'.$this->bot_token.'/'.$api;
        if ($post) {
            $reply = $this->sendAPIRequest($url, $content);
        } else {
            $reply = $this->sendAPIRequest($url, [], false);
        }

        return json_decode($reply, true);
    }

    /// A method for testing your bot.

    /**
     * A simple method for testing your bot's auth token. Requires no parameters.
     * Returns basic information about the bot in form of a User object.
     * \return the JSON Telegram's reply.
     */
    public function getMe()
    {
        return $this->endpoint('getMe', [], false);
    }

    /// A method for responding http to Telegram.

    /**
     * \return the HTTP 200 to Telegram.
     */
    public function respondSuccess()
    {
        http_response_code(200);

        return json_encode(['status' => 'success']);
    }

    /// Send a message

    /**
     * Use this method to send text messages.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * text
     * String
     * Yes
     * Text of the message to be sent
     *
     * parse_mode
     * String
     * Optional
     * Send Markdown, if you want Telegram apps to show bold, italic and inline URLs in your bot's message. For the moment, only Telegram for Android supports this.
     *
     * disable_web_page_preview
     * Boolean
     * Optional
     * Disables link previews for links in this message
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardHide or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendMessage(array $content)
    {
        $content['text'] = ' '.$content['text'];
        return $this->endpoint('sendMessage', $content);
    }

    /// Forward a message

    /**
     * Use this method to forward messages of any kind. On success, the sent Message is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * from_chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message silently. Users will receive a notification with no sound.
     *
     * message_id
     * Integer
     * Yes
     * Unique message identifier
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function forwardMessage(array $content)
    {
        $content['text'] = ' '.$content['text'];

        return $this->endpoint('forwardMessage', $content);
    }

    /// Send a photo

    /**
     * Use this method to send photos. On success, the sent Message is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * photo
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Photo to send. Pass a file_id as String to send a photo that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a photo from the Internet, or upload a new photo using multipart/form-data.
     *
     * caption
     * String
     * Optional
     * Photo caption (may also be used when resending photos by file_id).
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message silently. Users will receive a notification with no sound.
     *
     * reply_markup
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendPhoto(array $content)
    {
        return $this->endpoint('sendPhoto', $content);
    }

    /// Send an audio

    /**
     * Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .mp3 format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.
     * For sending voice messages, use the sendVoice method instead.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * audio
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Audio file to send. Pass a file_id as String to send an audio file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an audio file from the Internet, or upload a new one using multipart/form-data.
     *
     * duration
     * Integer
     * Optional
     * Duration of the audio in seconds
     *
     * performer
     * String
     * Optional
     * Performer
     *
     * title
     * String
     * Optional
     * Track name
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message silently. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendAudio(array $content)
    {
        return $this->endpoint('sendAudio', $content);
    }

    /// Send a document

    /**
     * Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * document
     * InputFile or String
     * Yes
     * File to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data.
     *
     * caption
     * String
     * Optional
     * Document caption (may also be used when resending documents by file_id), 0-200 characters.
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message silently. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendDocument(array $content)
    {
        return $this->endpoint('sendDocument', $content);
    }

    /// Send a sticker

    /**
     * Use this method to send .webp stickers. On success, the sent Message is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the message recipient — User or GroupChat id
     *
     * sticker
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Sticker to send. You can either pass a file_id as String to resend a sticker that is already on the Telegram servers, or upload a new sticker using multipart/form-data.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * ReplyKeyboardMarkup or ReplyKeyboardHide or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendSticker(array $content)
    {
        return $this->endpoint('sendSticker', $content);
    }

    /// Send a video

    /**
     * Use this method to send video files, Telegram clients support mp4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the message recipient — User or GroupChat id
     *
     * video
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Video to send. Pass a file_id as String to send a video that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a video from the Internet, or upload a new video using multipart/form-data.
     *
     * duration
     * Integer
     * Optional
     * Duration of sent video in seconds
     *
     * width
     * Integer
     * Optional
     * Video width
     *
     * height
     * Integer
     * Optional
     * Video height
     *
     * caption
     * String
     * Optional
     * Video caption (may also be used when resending videos by file_id).
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message silently. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendVideo(array $content)
    {
        return $this->endpoint('sendVideo', $content);
    }

    /// Send a voice message

    /**
     *  Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .ogg file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * voice
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Audio file to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data.
     *
     * caption
     * String
     * Optional
     * Voice message caption, 0-200 characters
     *
     * duration
     * Integer
     * Optional
     * Duration of sent audio in seconds
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message silently. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendVoice(array $content)
    {
        return $this->endpoint('sendVoice', $content);
    }

    /// Send a location

    /**
     *  Use this method to send point on the map. On success, the sent Message is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * latitude
     * Float number
     * Yes
     * Latitude of location
     *
     * longitude
     * Float number
     * Yes
     * Longitude of location
     *
     * live_period
     * Integer
     * Optional
     * Period in seconds for which the location will be updated (see <a href="https://telegram.org/blog/live-locations">Live Locations</a>, should be between 60 and 86400.
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message silently. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply
     * Optional
     * Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendLocation(array $content)
    {
        return $this->endpoint('sendLocation', $content);
    }

    /// Edit Message Live Location

    /**
     * Use this method to edit live location messages sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>). A location can be edited until its live_period expires or editing is explicitly disabled by a call to <a href="https://core.telegram.org/bots/api#stopmessagelivelocation">stopMessageLiveLocation</a>. On success, if the edited message was sent by the bot, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise True is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Optional
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * message_id
     * Integer
     * Optional
     * Required if inline_message_id is not specified. Identifier of the sent message
     *
     * inline_message_id
     * String
     * Optional
     * Required if chat_id and message_id are not specified. Identifier of the inline message
     *
     * latitude
     * Float number
     * Yes
     * Latitude of new location
     *
     * longitude
     * Float number
     * Yes
     * Longitude of new location
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a>
     * Optional
     * A JSON-serialized object for a new <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function editMessageLiveLocation(array $content)
    {
        return $this->endpoint('editMessageLiveLocation', $content);
    }

    /// Stop Message Live Location

    /**
     * Use this method to stop updating a live location message sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>) before live_period expires. On success, if the message was sent by the bot, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise True is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Optional
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * message_id
     * Integer
     * Optional
     * Required if inline_message_id is not specified. Identifier of the sent message
     *
     * inline_message_id
     * String
     * Optional
     * Required if chat_id and message_id are not specified. Identifier of the inline message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a>
     * Optional
     * A JSON-serialized object for a new <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function stopMessageLiveLocation(array $content)
    {
        return $this->endpoint('stopMessageLiveLocation', $content);
    }

    /// Set Chat Sticker Set

    /**
     * Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Use the field can_set_sticker_set optionally returned in <a href="https://core.telegram.org/bots/api#getchat">getChat</a> requests to check if the bot can use this method. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function setChatStickerSet(array $content)
    {
        return $this->endpoint('setChatStickerSet', $content);
    }

    /// Delete Chat Sticker Set

    /**
     * Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Use the field can_set_sticker_set optionally returned in <a href="https://core.telegram.org/bots/api#getchat">getChat</a> requests to check if the bot can use this method. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function deleteChatStickerSet(array $content)
    {
        return $this->endpoint('deleteChatStickerSet', $content);
    }

    /// Send Media Group

    /**
     * Use this method to send a group of photos or videos as an album. On success, an array of the sent <a href="https://core.telegram.org/bots/api#message">Messages</a> is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * media
     * Array of <a href="https://core.telegram.org/bots/api#inputmedia">InputMedia</a>
     * Yes
     * A JSON-serialized array describing photos and videos to be sent, must include 2–10 items
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the messages <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the messages are a reply, ID of the original message
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendMediaGroup(array $content)
    {
        return $this->endpoint('sendMediaGroup', $content);
    }

    /// Send Venue

    /**
     * Use this method to send information about a venue. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * latitude
     * Float number
     * Yes
     * Latitude of the venue
     *
     * longitude
     * Float number
     * Yes
     * Longitude of the venue
     *
     * title
     * String
     * Yes
     * Name of the venue
     *
     * address
     * String
     * Yes
     * Address of the venue
     *
     * foursquare_id
     * String
     * Optional
     * Foursquare identifier of the venue
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. iOS users will not receive a notification, Android users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardmarkup">ReplyKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardhide">ReplyKeyboardHide</a> or <a href="https://core.telegram.org/bots/api#forcereply">ForceReply</a>
     * Optional
     * Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>, <a href="https://core.telegram.org/bots#keyboards">custom reply keyboard</a>, instructions to hide reply keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendVenue(array $content)
    {
        return $this->endpoint('sendVenue', $content);
    }

    /// Send contact

    /**
     * Use this method to send phone contacts. On success, the sent Message is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * phone_number
     * String
     * Yes
     * Contact&#39;s phone number
     *
     * first_name
     * String
     * Yes
     * Contact&#39;s first name
     *
     * last_name
     * String
     * Optional
     * Contact&#39;s last name
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. iOS users will not receive a notification, Android users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardmarkup">ReplyKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardhide">ReplyKeyboardHide</a> or <a href="https://core.telegram.org/bots/api#forcereply">ForceReply</a>
     * Optional
     * Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>, <a href="https://core.telegram.org/bots#keyboards">custom reply keyboard</a>, instructions to hide keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply
     */
    public function sendContact(array $content)
    {
        return $this->endpoint('sendContact', $content);
    }

    /// Send a chat action

    /**
     *  Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status).
     *
     * Example: The ImageBot needs some time to process a request and upload the image. Instead of sending a text message along the lines of “Retrieving image, please wait…”, the bot may use sendChatAction with action = upload_photo. The user will see a “sending photo” status for the bot.
     *
     * We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the message recipient — User or GroupChat id
     *
     * action
     * String
     * Yes
     * Type of action to broadcast. Choose one, depending on what the user is about to receive: typing for text messages, upload_photo for photos, record_video or upload_video for videos, record_audio or upload_audio for audio files, upload_document for general files, find_location for location data.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendChatAction(array $content)
    {
        return $this->endpoint('sendChatAction', $content);
    }

    /// Get a list of profile pictures for a user

    /**
     *  Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * user_id
     * Integer
     * Yes
     * Unique identifier of the target user
     *
     * offset
     * Integer
     * Optional
     * Sequential number of the first photo to be returned. By default, all photos are returned.
     *
     * limit
     * Integer
     * Optional
     * Limits the number of photos to be retrieved. Values between 1—100 are accepted. Defaults to 100.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function getUserProfilePhotos(array $content)
    {
        return $this->endpoint('getUserProfilePhotos', $content);
    }

    /// Use this method to get basic info about a file and prepare it for downloading

    /**
     *  Use this method to get basic info about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
     * \param $file_id String File identifier to get info about
     * \return the JSON Telegram's reply.
     */
    public function getFile($file_id)
    {
        $content = ['file_id' => $file_id];

        return $this->endpoint('getFile', $content)['result'];
    }

    /// Kick Chat Member

    /**
     * Use this method to kick a user from a group or a supergroup. In the case of supergroups, the user will not be able to return to the group on their own using invite links, etc., unless <a href="https://core.telegram.org/bots/api#unbanchatmember">unbanned</a> first. The bot must be an administrator in the group for this to work. Returns True on success.<br>
     * Note: This will method only work if the \˜All Members Are Admins\' setting is off in the target group. Otherwise members may only be removed by the group&#39;s creator or by the member that added them.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target group or username of the target supergroup (in the format \c \@supergroupusername)
     *
     * user_id
     * Integer
     * Yes
     * Unique identifier of the target user
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function kickChatMember(array $content)
    {
        return $this->endpoint('kickChatMember', $content);
    }

    /// Leave Chat

    /**
     * Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target supergroup or channel (in the format \c \@channelusername)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function leaveChat(array $content)
    {
        return $this->endpoint('leaveChat', $content);
    }

    /// Unban Chat Member

    /**
     * Use this method to unban a previously kicked user in a supergroup. The user will not return to the group automatically, but will be able to join via link, etc. The bot must be an administrator in the group for this to work. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target group or username of the target supergroup (in the format <code>@supergroupusername</code>)
     *
     * user_id
     * Integer
     * Yes
     * Unique identifier of the target user
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function unbanChatMember(array $content)
    {
        return $this->endpoint('unbanChatMember', $content);
    }

    /// Get Chat Information

    /**
     * Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a <a href="https://core.telegram.org/bots/api#chat">Chat</a> object on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target supergroup or channel (in the format \c \@channelusername)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function getChat(array $content)
    {
        return $this->endpoint('getChat', $content);
    }

    /**
     * Use this method to get a list of administrators in a chat. On success, returns an Array of <a href="https://core.telegram.org/bots/api#chatmember">ChatMember</a> objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target supergroup or channel (in the format \c \@channelusername)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function getChatAdministrators(array $content)
    {
        return $this->endpoint('getChatAdministrators', $content);
    }

    /**
     * Use this method to get the number of members in a chat. Returns Int on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target supergroup or channel (in the format \c \@channelusername)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function getChatMembersCount(array $content)
    {
        return $this->endpoint('getChatMembersCount', $content);
    }

    /**
     * Use this method to get information about a member of a chat. Returns a <a href="https://core.telegram.org/bots/api#chatmember">ChatMember</a> object on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target supergroup or channel (in the format \c \@channelusername)
     *
     * user_id
     * Integer
     * Yes
     * Unique identifier of the target user
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function getChatMember(array $content)
    {
        return $this->endpoint('getChatMember', $content);
    }

    /**
     * Use this method to send answers to an inline query. On success, True is returned.<br>No more than 50 results per query are allowed.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * inline_query_id
     * String
     * Yes
     * Unique identifier for the answered query
     *
     * results
     * Array of <a href="https://core.telegram.org/bots/api#inlinequeryresult">InlineQueryResult</a>
     * Yes
     * A JSON-serialized array of results for the inline query
     *
     * cache_time
     * Integer
     * Optional
     * The maximum amount of time in seconds that the result of the inline query may be cached on the server. Defaults to 300.
     *
     * is_personal
     * Boolean
     * Optional
     * Pass True, if results may be cached on the server side only for the user that sent the query. By default, results may be returned to any user who sends the same query
     *
     * next_offset
     * String
     * Optional
     * Pass the offset that a client should send in the next query with the same text to receive more results. Pass an empty string if there are no more results or if you donâ€˜t support pagination. Offset length canâ€™t exceed 64 bytes.
     *
     * switch_pm_text
     * String
     * Optional
     * If passed, clients will display a button with specified text that switches the user to a private chat with the bot and sends the bot a start message with the parameter switch_pm_parameter
     *
     * switch_pm_parameter
     * String
     * Optional
     * Parameter for the start message sent to the bot when user presses the switch button<br><br>Example: An inline bot that sends YouTube videos can ask the user to connect the bot to their YouTube account to adapt search results accordingly. To do this, it displays a â€˜Connect your YouTube accountâ€™ button above the results, or even before showing any. The user presses the button, switches to a private chat with the bot and, in doing so, passes a start parameter that instructs the bot to return an oauth link. Once done, the bot can offer a <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">switch_inline</a> button so that the user can easily return to the chat where they wanted to use the bot&#39;s inline capabilities.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function answerInlineQuery(array $content)
    {
        return $this->endpoint('answerInlineQuery', $content);
    }

    /// Set Game Score

    /**
     * Use this method to set the score of the specified user in a game. On success, if the message was sent by the bot, returns the edited Message, otherwise returns True. Returns an error, if the new score is not greater than the user&#39;s current score in the chat and force is False.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * user_id
     * Integer
     * Yes
     * User identifier
     *
     * score
     * Integer
     * Yes
     * New score, must be non-negative
     *
     * force
     * Boolean
     * Optional
     * Pass True, if the high score is allowed to decrease. This can be useful when fixing mistakes or banning cheaters
     *
     * disable_edit_message
     * Boolean
     * Optional
     * Pass True, if the game message should not be automatically edited to include the current scoreboard
     *
     * chat_id
     * Integer
     * Optional
     * Required if inline_message_id is not specified. Unique identifier for the target chat
     *
     * message_id
     * Integer
     * Optional
     * Required if inline_message_id is not specified. Identifier of the sent message
     *
     * inline_message_id
     * String
     * Optional
     * Required if chat_id and message_id are not specified. Identifier of the inline message
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function setGameScore(array $content)
    {
        return $this->endpoint('setGameScore', $content);
    }

    /// Answer a callback Query

    /**
     * Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * callback_query_id
     * String
     * Yes
     * Unique identifier for the query to be answered
     *
     * text
     * String
     * Optional
     * Text of the notification. If not specified, nothing will be shown to the user
     *
     * show_alert
     * Boolean
     * Optional
     * If true, an alert will be shown by the client instead of a notification at the top of the chat screen. Defaults to false.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function answerCallbackQuery(array $content)
    {
        return $this->endpoint('answerCallbackQuery', $content);
    }

    /**
     * Use this method to edit text messages sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>). On success, if edited message is sent by the bot, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise True is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * No
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * message_id
     * Integer
     * No
     * Required if inline_message_id is not specified. Unique identifier of the sent message
     *
     * inline_message_id
     * String
     * No
     * Required if chat_id and message_id are not specified. Identifier of the inline message
     *
     * text
     * String
     * Yes
     * New text of the message
     *
     * parse_mode
     * String
     * Optional
     * Send <a href="https://core.telegram.org/bots/api#markdown-style">Markdown</a> or <a href="https://core.telegram.org/bots/api#html-style">HTML</a>, if you want Telegram apps to show <a href="https://core.telegram.org/bots/api#formatting-options">bold, italic, fixed-width text or inline URLs</a> in your bot&#39;s message.
     *
     * disable_web_page_preview
     * Boolean
     * Optional
     * Disables link previews for links in this message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a>
     * Optional
     * A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function editMessageText(array $content)
    {
        return $this->endpoint('editMessageText', $content);
    }

    /**
     * Use this method to edit captions of messages sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>). On success, if edited message is sent by the bot, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise True is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * No
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * message_id
     * Integer
     * No
     * Required if inline_message_id is not specified. Unique identifier of the sent message
     *
     * inline_message_id
     * String
     * No
     * Required if chat_id and message_id are not specified. Identifier of the inline message
     *
     * caption
     * String
     * Optional
     * New caption of the message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a>
     * Optional
     * A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function editMessageCaption(array $content)
    {
        return $this->endpoint('editMessageCaption', $content);
    }

    /**
     * Use this method to edit only the reply markup of messages sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>).  On success, if edited message is sent by the bot, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise True is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * No
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * message_id
     * Integer
     * No
     * Required if inline_message_id is not specified. Unique identifier of the sent message
     *
     * inline_message_id
     * String
     * No
     * Required if chat_id and message_id are not specified. Identifier of the inline message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a>
     * Optional
     * A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function editMessageReplyMarkup(array $content)
    {
        return $this->endpoint('editMessageReplyMarkup', $content);
    }

    /// Use this method to download a file

    /**
     *  Use this method to to download a file from the Telegram servers.
     * \param $telegram_file_path String File path on Telegram servers
     * \param $local_file_path String File path where save the file.
     */
    public function downloadFile($telegram_file_path, $local_file_path)
    {
        $file_url = 'https://api.telegram.org/file/bot'.$this->bot_token.'/'.$telegram_file_path;
        $in = fopen($file_url, 'rb');
        $out = fopen($local_file_path, 'wb');

        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    /// Set a WebHook for the bot

    /**
     *  Use this method to specify a url and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified url, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts.
     *
     * If you'd like to make sure that the Webhook request comes from Telegram, we recommend using a secret path in the URL, e.g. https://www.example.com/<token>. Since nobody else knows your botâ€˜s token, you can be pretty sure itâ€™s us.
     * \param $url String HTTPS url to send updates to. Use an empty string to remove webhook integration
     * \param $certificate InputFile Upload your public key certificate so that the root certificate in use can be checked
     * \return the JSON Telegram's reply.
     */
    public function setWebhook($url, $certificate = '')
    {
        if ($certificate == '') {
            $requestBody = ['url' => $url];
        } else {
            $requestBody = ['url' => $url, 'certificate' => "@$certificate"];
        }

        return $this->endpoint('setWebhook', $requestBody, true);
    }

    /// Delete the WebHook for the bot

    /**
     *  Use this method to remove webhook integration if you decide to switch back to <a href="https://core.telegram.org/bots/api#getupdates">getUpdates</a>. Returns True on success. Requires no parameters.
     * \return the JSON Telegram's reply.
     */
    public function deleteWebhook()
    {
        return $this->endpoint('deleteWebhook', [], false);
    }

    /// Get the data of the current message

    /** Get the POST request of a user in a Webhook or the message actually processed in a getUpdates() enviroment.
     * \return the JSON users's message.
     */
    public function getData()
    {
        if (empty($this->data)) {
            $rawData = file_get_contents('php://input');

            return json_decode($rawData, true);
        } else {
            return $this->data;
        }
    }

    /// Set the data currently used
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /// Get the text of the current message

    /**
     * \return the String users's text.
     */
    public function Text()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['data'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['text'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['text'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['query'];
        } else {
            if (isset($this->data['message']['text'])) {
                return @$this->data['message']['text'];
            } else {
                return '';
            }
        }
    }

    public function Caption()
    {
        return @$this->data['message']['caption'];
    }

    /// Get the chat_id of the current message

    /**
     * \return the String users's chat_id.
     */
    public function ChatID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['chat']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['chat']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['chat']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }

        return $this->data['message']['chat']['id'];
    }

    /// Get the message_id of the current message

    /**
     * \return the String message_id.
     */
    public function MessageID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['message_id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['message_id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['message_id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['id'];
        }

        return @$this->data['message']['message_id'];
    }

    /// Get the reply_to_message message_id of the current message

    /**
     * \return the String reply_to_message message_id.
     */
    public function ReplyToMessageID()
    {
        return $this->data['message']['reply_to_message']['message_id'];
    }

    /// Get the reply_to_message forward_from user_id of the current message

    /**
     * \return the String reply_to_message forward_from user_id.
     */
    public function ReplyToMessageFromUserID()
    {
        return $this->data['message']['reply_to_message']['forward_from']['id'];
    }

    /// Get the inline_query of the current update

    /**
     * \return the Array inline_query.
     */
    public function Inline_Query()
    {
        if (isset($this->data['inline_query'])) {
            return @$this->data['inline_query'];
        } else {
            return false;
        }
    }

    /// Get the callback_query of the current update

    /**
     * \return the String callback_query.
     */
    public function Callback_Query()
    {
        if (isset($this->data['callback_query'])) {
            return @$this->data['callback_query'];
        } else {
            return false;
        }
    }

    /// Get the callback_query id of the current update

    /**
     * \return the String callback_query id.
     */
    public function Callback_ID()
    {
        return $this->data['callback_query']['id'];
    }

    /// Get the data of the current callback

    /**
     * \deprecated Use Text() instead
     * \return the String callback_data.
     */
    public function Callback_Data()
    {
        return $this->data['callback_query']['data'];
    }

    /// Get the message of the current callback

    /**
     * \return the Callback Message.
     */
    public function Callback_Message()
    {
        return $this->data['callback_query']['message'];
    }

    /// Get the message_id of the current callback

    /**
     * \return the Callback MessageID.
     */
    public function Callback_MessageID()
    {
        return $this->data['callback_query']['message']['message_id'];
    }

    /// Get the chat_id of the current callback

    /**
     * \deprecated Use ChatId() instead
     * \return the String callback_query.
     */
    public function Callback_ChatID()
    {
        return $this->data['callback_query']['message']['chat']['id'];
    }

    /// Get the date of the current message

    /**
     * \return the String message's date.
     */
    public function Date()
    {
        return $this->data['message']['date'];
    }

    /// Get the first name of the user
    public function FirstName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['first_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['first_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['first_name'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['first_name'];
        }

        return @$this->data['message']['from']['first_name'];
    }

    /// Get the last name of the user
    public function LastName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['last_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['last_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['last_name'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['last_name'];
        } else {
            if (isset($this->data['message']['from']['last_name'])) {
                return @$this->data['message']['from']['last_name'];
            } else {
                return '';
            }
        }
    }

    /// Get the username of the user
    public function Username()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['username'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['username'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['username'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['username'];
        } else {
            if (isset($this->data['message']['from']['username'])) {
                return @$this->data['message']['from']['username'];
            } else {
                return '';
            }
        }
    }

    /// Get the location in the message
    public function Location()
    {
        return $this->data['message']['location'];
    }

    /// Get the update_id of the message
    public function UpdateID()
    {
        return $this->data['update_id'];
    }

    /// Get the number of updates
    public function UpdateCount()
    {
        return count($this->updates['result']);
    }

    /// Get user's id of current message
    public function UserID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return $this->data['callback_query']['from']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return $this->data['channel_post']['from']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }

        return @$this->data['message']['from']['id'];
    }

    /// Get user's id of current forwarded message
    public function FromID()
    {
        return $this->data['message']['forward_from']['id'];
    }

    /// Get chat's id where current message forwarded from
    public function FromChatID()
    {
        return $this->data['message']['forward_from_chat']['id'];
    }

    /// Tell if a message is from a group or user chat

    /**
     *  \return BOOLEAN true if the message is from a Group chat, false otherwise.
     */
    public function messageFromGroup()
    {
        if ($this->data['message']['chat']['type'] == 'private') {
            return false;
        }

        return true;
    }

    /// Get the title of the group chat

    /**
     *  \return a String of the title chat.
     */
    public function messageFromGroupTitle()
    {
        if ($this->data['message']['chat']['type'] != 'private') {
            return $this->data['message']['chat']['title'];
        }
    }

    /// Set a custom keyboard

    /** This object represents a custom keyboard with reply options
     * \param $options Array of Array of String; Array of button rows, each represented by an Array of Strings
     * \param $onetime Boolean Requests clients to hide the keyboard as soon as it's been used. Defaults to false.
     * \param $resize Boolean Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     * \param $selective Boolean Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * \return the requested keyboard as Json.
     */
    public function buildKeyBoard(array $options, $onetime = false, $resize = true, $selective = true)
    {
        $replyMarkup = [
            'keyboard'          => $options,
            'one_time_keyboard' => $onetime,
            'resize_keyboard'   => $resize,
            'selective'         => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup, true);

        return $encodedMarkup;
    }

    /// Set an InlineKeyBoard

    /** This object represents an inline keyboard that appears right next to the message it belongs to.
     * \param $options Array of Array of InlineKeyboardButton; Array of button rows, each represented by an Array of InlineKeyboardButton
     * \return the requested keyboard as Json.
     */
    public function buildInlineKeyBoard(array $options, $encode = true)
    {
        $replyMarkup = [
            'inline_keyboard' => $options,
        ];
        if ($encode) {
            $encodedMarkup = json_encode($replyMarkup, true);
        } else {
            $encodedMarkup = $replyMarkup;
        }

        return $encodedMarkup;
    }

    /// Create an InlineKeyboardButton

    /** This object represents one button of an inline keyboard. You must use exactly one of the optional fields.
     * \param $text String; Array of button rows, each represented by an Array of Strings
     * \param $url String Optional. HTTP url to be opened when button is pressed
     * \param $callback_data String Optional. Data to be sent in a callback query to the bot when button is pressed
     * \param $switch_inline_query String Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot‘s username and the specified inline query in the input field. Can be empty, in which case just the bot’s username will be inserted.
     * \param $switch_inline_query_current_chat String Optional. Optional. If set, pressing the button will insert the bot‘s username and the specified inline query in the current chat's input field. Can be empty, in which case only the bot’s username will be inserted.
     * \param $callback_game  String Optional. Description of the game that will be launched when the user presses the button.
     * \param $pay  Boolean Optional. Specify True, to send a <a href="https://core.telegram.org/bots/api#payments">Pay button</a>.
     * \return the requested button as Array.
     */
    public function buildInlineKeyboardButton($text, $url = '', $callback_data = '', $switch_inline_query = null, $switch_inline_query_current_chat = null, $callback_game = '', $pay = '')
    {
        $replyMarkup = [
            'text' => $text,
        ];
        if ($url != '') {
            $replyMarkup['url'] = $url;
        } elseif ($callback_data != '') {
            $replyMarkup['callback_data'] = $callback_data;
        } elseif (!is_null($switch_inline_query)) {
            $replyMarkup['switch_inline_query'] = $switch_inline_query;
        } elseif (!is_null($switch_inline_query_current_chat)) {
            $replyMarkup['switch_inline_query_current_chat'] = $switch_inline_query_current_chat;
        } elseif ($callback_game != '') {
            $replyMarkup['callback_game'] = $callback_game;
        } elseif ($pay != '') {
            $replyMarkup['pay'] = $pay;
        }

        return $replyMarkup;
    }

    /// Create a KeyboardButton

    /** This object represents one button of an inline keyboard. You must use exactly one of the optional fields.
     * \param $text String; Array of button rows, each represented by an Array of Strings
     * \param $request_contact Boolean Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
     * \param $request_location Boolean Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
     * \return the requested button as Array.
     */
    public function buildKeyboardButton($text, $request_contact = false, $request_location = false)
    {
        $replyMarkup = [
            'text'             => $text,
            'request_contact'  => $request_contact,
            'request_location' => $request_location,
        ];

        return $replyMarkup;
    }

    /// Hide a custom keyboard

    /** Upon receiving a message with this object, Telegram clients will hide the current custom keyboard and display the default letter-keyboard. By default, custom keyboards are displayed until a new keyboard is sent by a bot. An exception is made for one-time keyboards that are hidden immediately after the user presses a button.
     * \param $selective Boolean Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * \return the requested keyboard hide as Array.
     */
    public function buildKeyBoardHide($selective = true)
    {
        $replyMarkup = [
            'remove_keyboard' => true,
            'selective'       => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup, true);

        return $encodedMarkup;
    }

    /// Display a reply interface to the user
    /* Upon receiving a message with this object, Telegram clients will display a reply interface to the user (act as if the user has selected the bot‘s message and tapped ’Reply'). This can be extremely useful if you want to create user-friendly step-by-step interfaces without having to sacrifice privacy mode.
     * \param $selective Boolean Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * \return the requested force reply as Array
     */
    public function buildForceReply($selective = true)
    {
        $replyMarkup = [
            'force_reply' => true,
            'selective'   => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup, true);

        return $encodedMarkup;
    }

    // Payments
    /// Send an invoice

    /**
     * Use this method to send invoices. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer
     * Yes
     * Unique identifier for the target private chat
     *
     * title
     * String
     * Yes
     * Product name
     *
     * description
     * String
     * Yes
     * Product description
     *
     * payload
     * String
     * Yes
     * Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use for your internal processes.
     *
     * provider_token
     * String
     * Yes
     * Payments provider token, obtained via <a href="/">Botfather</a>
     *
     * start_parameter
     * String
     * Yes
     * Unique deep-linking parameter that can be used to generate this invoice when used as a start parameter
     *
     * currency
     * String
     * Yes
     * Three-letter ISO 4217 currency code, see <a href="https://core.telegram.org/bots/payments#supported-currencies">more on currencies</a>
     *
     * prices
     * Array of <a href="https://core.telegram.org/bots/api#labeledprice">LabeledPrice</a>
     * Yes
     * Price breakdown, a list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.)
     *
     * provider_data
     * String
     * Optional
     * JSON-encoded data about the invoice, which will be shared with the payment provider. A detailed description of required fields should be provided by the payment provider.
     *
     * photo_url
     * String
     * Optional
     * URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service. People like it better when they see what they are paying for.
     *
     * photo_size
     * Integer
     * Optional
     * Photo size
     *
     * photo_width
     * Integer
     * Optional
     * Photo width
     *
     * photo_height
     * Integer
     * Optional
     * Photo height
     *
     * need_name
     * Bool
     * Optional
     * Pass True, if you require the user's full name to complete the order
     *
     * need_phone_number
     * Boolean
     * Optional
     * Pass True, if you require the user's phone number to complete the order
     *
     * need_email
     * Bool
     * Optional
     * Pass True, if you require the user's email to complete the order
     *
     * need_shipping_address
     * Boolean
     * Optional
     * Pass True, if you require the user's shipping address to complete the order
     *
     * is_flexible
     * Boolean
     * Optional
     * Pass True, if the final price depends on the shipping method
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a>
     * Optional
     * A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>. If empty, one 'Pay <code>total price</code>' button will be shown. If not empty, the first button must be a Pay button.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendInvoice(array $content)
    {
        return $this->endpoint('sendInvoice', $content);
    }

    /// Answer a shipping query

    /**
     * Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an <a href="https://core.telegram.org/bots/api#updates">Update</a> with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * shipping_query_id
     * String
     * Yes
     * Unique identifier for the query to be answered
     *
     * ok
     * Boolean
     * Yes
     * Specify True if delivery to the specified address is possible and False if there are any problems (for example, if delivery to the specified address is not possible)
     *
     * shipping_options
     * Array of <a href="https://core.telegram.org/bots/api#shippingoption">ShippingOption</a>
     * Optional
     * Required if ok is True. A JSON-serialized array of available shipping options.
     *
     * error_message
     * String
     * Optional
     * Required if ok is False. Error message in human readable form that explains why it is impossible to complete the order (e.g. "Sorry, delivery to your desired address is unavailable'). Telegram will display this message to the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function answerShippingQuery(array $content)
    {
        return $this->endpoint('answerShippingQuery', $content);
    }

    /// Answer a PreCheckout query

    /**
     * Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an <a href="https://core.telegram.org/bots/api#">Update</a> with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * pre_checkout_query_id
     * String
     * Yes
     * Unique identifier for the query to be answered
     *
     * ok
     * Boolean
     * Yes
     * Specify True if everything is alright (goods are available, etc.) and the bot is ready to proceed with the order. Use False if there are any problems.
     *
     * error_message
     * String
     * Optional
     * Required if ok is False. Error message in human readable form that explains the reason for failure to proceed with the checkout (e.g. "Sorry, somebody just bought the last of our amazing black T-shirts while you were busy filling out your payment details. Please choose a different color or garment!"). Telegram will display this message to the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function answerPreCheckoutQuery(array $content)
    {
        return $this->endpoint('answerPreCheckoutQuery', $content);
    }

    /// Send a video note

    /**
     * As of <a href="https://telegram.org/blog/video-messages-and-telescope">v.4.0</a>, Telegram clients support rounded square mp4 videos of up to 1 minute long. Use this method to send video messages. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * video_note
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Video note to send. Pass a file_id as String to send a video note that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More info on Sending Files »</a>. Sending video notes by a URL is currently unsupported
     *
     * duration
     * Integer
     * Optional
     * Duration of sent video in seconds
     *
     * length
     * Integer
     * Optional
     * Video width and height
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. iOS users will not receive a notification, Android users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardmarkup">ReplyKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardremove">ReplyKeyboardRemove</a> or <a href="https://core.telegram.org/bots/api#forcereply">ForceReply</a>
     * Optional
     * Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>, <a href="https://core.telegram.org/bots#keyboards">custom reply keyboard</a>, instructions to remove reply keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function sendVideoNote(array $content)
    {
        return $this->endpoint('sendVideoNote', $content);
    }

    /// Restrict Chat Member

    /**
     * Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate admin rights. Pass True for all boolean parameters to lift restrictions from a user. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * photo
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Photo to send. Pass a file_id as String to send a photo that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a photo from the Internet, or upload a new photo using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More info on Sending Files »</a>
     *
     * caption
     * String
     * Optional
     * Photo caption (may also be used when resending photos by file_id), 0-200 characters
     *
     * disable_notification
     * Boolean
     * Optional
     * Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     *
     * reply_to_message_id
     * Integer
     * Optional
     * If the message is a reply, ID of the original message
     *
     * reply_markup
     * <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">InlineKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardmarkup">ReplyKeyboardMarkup</a> or <a href="https://core.telegram.org/bots/api#replykeyboardremove">ReplyKeyboardRemove</a> or <a href="https://core.telegram.org/bots/api#forcereply">ForceReply</a>
     * Optional
     * Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots#inline-keyboards-and-on-the-fly-updating">inline keyboard</a>, <a href="https://core.telegram.org/bots#keyboards">custom reply keyboard</a>, instructions to remove reply keyboard or to force a reply from the user.
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function restrictChatMember(array $content)
    {
        return $this->endpoint('restrictChatMember', $content);
    }

    /// Promote Chat Member

    /**
     * Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Pass False for all boolean parameters to demote a user. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * user_id
     * Integer
     * Yes
     * Unique identifier of the target user
     *
     * can_change_info
     * Boolean
     * No
     * Pass True, if the administrator can change chat title, photo and other settings
     *
     * can_post_messages
     * Boolean
     * No
     * Pass True, if the administrator can create channel posts, channels only
     *
     * can_edit_messages
     * Boolean
     * No
     * Pass True, if the administrator can edit messages of other users, channels only
     *
     * can_delete_messages
     * Boolean
     * No
     * Pass True, if the administrator can delete messages of other users
     *
     * can_invite_users
     * Boolean
     * No
     * Pass True, if the administrator can invite new users to the chat
     *
     * can_restrict_members
     * Boolean
     * No
     * Pass True, if the administrator can restrict, ban or unban chat members
     *
     * can_pin_messages
     * Boolean
     * No
     * Pass True, if the administrator can pin messages, supergroups only
     *
     * can_promote_members
     * Boolean
     * No
     * Pass True, if the administrator can add new administrators with a subset of his own privileges or demote administrators that he has promoted, directly or indirectly (promoted by administrators that were appointed by him)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function promoteChatMember(array $content)
    {
        return $this->endpoint('promoteChatMember', $content);
    }

    //// Export Chat Invite Link

    /**
     * Use this method to export an invite link to a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns exported invite link as String on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function exportChatInviteLink(array $content)
    {
        return $this->endpoint('exportChatInviteLink', $content);
    }

    /// Set Chat Photo

    /**
     * Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success. Note: In regular groups (non-supergroups), this method will only work if the ‘All Members Are Admins’ setting is off in the target group.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * photo
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a>
     * Yes
     * New chat photo, uploaded using multipart/form-data
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function setChatPhoto(array $content)
    {
        return $this->endpoint('setChatPhoto', $content);
    }

    /// Delete Chat Photo

    /**
     * Use this method to delete a chat photo. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success. Note: In regular groups (non-supergroups), this method will only work if the ‘All Members Are Admins’ setting is off in the target group.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function deleteChatPhoto(array $content)
    {
        return $this->endpoint('deleteChatPhoto', $content);
    }

    /// Set Chat Title

    /**
     * Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success. Note: In regular groups (non-supergroups), this method will only work if the ‘All Members Are Admins’ setting is off in the target group.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * title
     * String
     * Yes
     * New chat title, 1-255 characters
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function setChatTitle(array $content)
    {
        return $this->endpoint('setChatTitle', $content);
    }

    /// Set Chat Description

    /**
     * Use this method to change the description of a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * description
     * String
     * No
     * New chat description, 0-255 characters
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function setChatDescription(array $content)
    {
        return $this->endpoint('setChatDescription', $content);
    }

    /// Pin Chat Message

    /**
     * Use this method to pin a message in a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * message_id
     * Integer
     * Yes
     * Identifier of a message to pin
     *
     * disable_notification
     * Boolean
     * No
     * Pass True, if it is not necessary to send a notification to all group members about the new pinned message
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function pinChatMessage(array $content)
    {
        return $this->endpoint('pinChatMessage', $content);
    }

    /// Unpin Chat Message

    /**
     * Use this method to unpin a message in a supergroup chat. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function unpinChatMessage(array $content)
    {
        return $this->endpoint('unpinChatMessage', $content);
    }

    /// Get Sticker Set

    /**
     * Use this method to get a sticker set. On success, a StickerSet object is returned.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * name
     * String
     * Yes
     * Short name of the sticker set that is used in <code>t.me/addstickers/</code> URLs (e.g., animals)
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function getStickerSet(array $content)
    {
        return $this->endpoint('getStickerSet', $content);
    }

    /// Upload Sticker File

    /**
     * Use this method to upload a .png file with a sticker for later use in createNewStickerSet and addStickerToSet methods (can be used multiple times). Returns the uploaded File on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * user_id
     * Integer
     * Yes
     * User identifier of sticker file owner
     *
     * png_sticker
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a>
     * Yes
     * Png image with the sticker, must be up to 512 kilobytes in size, dimensions must not exceed 512px, and either width or height must be exactly 512px. <a href="https://core.telegram.org/bots/api#sending-files">More info on Sending Files »</a>
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function uploadStickerFile(array $content)
    {
        return $this->endpoint('uploadStickerFile', $content);
    }

    /// Create New Sticker Set

    /**
     * Use this method to create new sticker set owned by a user. The bot will be able to edit the created sticker set. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * user_id
     * Integer
     * Yes
     * User identifier of created sticker set owner
     *
     * name
     * String
     * Yes
     * Short name of sticker set, to be used in <code>t.me/addstickers/</code> URLs (e.g., animals). Can contain only english letters, digits and underscores. Must begin with a letter, can't contain consecutive underscores and must end in “_by_&lt;bot username&gt;”. &lt;bot_username&gt; is case insensitive. 1-64 characters.
     *
     * title
     * String
     * Yes
     * Sticker set title, 1-64 characters
     *
     * png_sticker
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Png image with the sticker, must be up to 512 kilobytes in size, dimensions must not exceed 512px, and either width or height must be exactly 512px. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More info on Sending Files »</a>
     *
     * emojis
     * String
     * Yes
     * One or more emoji corresponding to the sticker
     *
     * is_masks
     * Boolean
     * Optional
     * Pass True, if a set of mask stickers should be created
     *
     * mask_position
     * <a href="https://core.telegram.org/bots/api#maskposition">MaskPosition</a>
     * Optional
     * Position where the mask should be placed on faces
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function createNewStickerSet(array $content)
    {
        return $this->endpoint('createNewStickerSet', $content);
    }

    /// Add Sticker To Set

    /**
     * Use this method to add a new sticker to a set created by the bot. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * user_id
     * Integer
     * Yes
     * User identifier of sticker set owner
     *
     * name
     * String
     * Yes
     * Sticker set name
     *
     * png_sticker
     * <a href="https://core.telegram.org/bots/api#inputfile">InputFile</a> or String
     * Yes
     * Png image with the sticker, must be up to 512 kilobytes in size, dimensions must not exceed 512px, and either width or height must be exactly 512px. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More info on Sending Files »</a>
     *
     * emojis
     * String
     * Yes
     * One or more emoji corresponding to the sticker
     *
     * mask_position
     * <a href="https://core.telegram.org/bots/api#maskposition">MaskPosition</a>
     * Optional
     * Position where the mask should be placed on faces
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function addStickerToSet(array $content)
    {
        return $this->endpoint('addStickerToSet', $content);
    }

    /// Set Sticker Position In Set

    /**
     * Use this method to move a sticker in a set created by the bot to a specific position . Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * sticker
     * String
     * Yes
     * File identifier of the sticker
     *
     * position
     * Integer
     * Yes
     * New sticker position in the set, zero-based
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function setStickerPositionInSet(array $content)
    {
        return $this->endpoint('setStickerPositionInSet', $content);
    }

    /// Delete Sticker From Set

    /**
     * Use this method to delete a sticker from a set created by the bot. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * sticker
     * String
     * Yes
     * File identifier of the sticker
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function deleteStickerFromSet(array $content)
    {
        return $this->endpoint('deleteStickerFromSet', $content);
    }

    /// Delete a message

    /**
     * Use this method to delete a message. A message can only be deleted if it was sent less than 48 hours ago. Any such recently sent outgoing message may be deleted. Additionally, if the bot is an administrator in a group chat, it can delete any message. If the bot is an administrator in a supergroup, it can delete messages from any other user and service messages about people joining or leaving the group (other types of service messages may only be removed by the group creator). In channels, bots can only remove their own messages. Returns True on success.
     *
     * Parameters
     * Type
     * Required
     * Description
     *
     * chat_id
     * Integer or String
     * Yes
     * Unique identifier for the target chat or username of the target channel (in the format \c \@channelusername)
     *
     * message_id
     * Integer
     * Yes
     * Identifier of the message to delete
     *
     * \param $content the request parameters as array
     * \return the JSON Telegram's reply.
     */
    public function deleteMessage(array $content)
    {
        return $this->endpoint('deleteMessage', $content);
    }

    /// Receive incoming messages using polling

    /** Use this method to receive incoming updates using long polling.
     * \param $offset Integer Identifier of the first update to be returned. Must be greater by one than the highest among the identifiers of previously received updates. By default, updates starting with the earliest unconfirmed update are returned. An update is considered confirmed as soon as getUpdates is called with an offset higher than its update_id.
     * \param $limit Integer Limits the number of updates to be retrieved. Values between 1—100 are accepted. Defaults to 100
     * \param $timeout Integer Timeout in seconds for long polling. Defaults to 0, i.e. usual short polling
     * \param $update Boolean If true updates the pending message list to the last update received. Default to true.
     * \return the updates as Array.
     */
    public function getUpdates($offset = 0, $limit = 100, $timeout = 0, $update = true)
    {
        $content = ['offset' => $offset, 'limit' => $limit, 'timeout' => $timeout];
        $this->updates = $this->endpoint('getUpdates', $content);
        if ($update) {
            if (count($this->updates['result']) >= 1) { //for CLI working.
                $last_element_id = $this->updates['result'][count($this->updates['result']) - 1]['update_id'] + 1;
                $content = ['offset' => $last_element_id, 'limit' => '1', 'timeout' => $timeout];
                $this->endpoint('getUpdates', $content);
            }
        }

        return $this->updates;
    }

    /// Serve an update

    /** Use this method to use the bultin function like Text() or Username() on a specific update.
     * \param $update Integer The index of the update in the updates array.
     */
    public function serveUpdate($update)
    {
        $this->data = $this->updates['result'][$update];
    }

    /// Return current update type

    /**
     * Return current update type `False` on failure.
     *
     * @return bool|string
     */
    public function getUpdateType()
    {
        $update = $this->data;
        if (isset($update['inline_query'])) {
            return self::INLINE_QUERY;
        }
        if (isset($update['callback_query'])) {
            return self::CALLBACK_QUERY;
        }
        if (isset($update['edited_message'])) {
            return self::EDITED_MESSAGE;
        }
        if (isset($update['message']['reply_to_message'])) {
            return self::REPLY;
        }
        if (isset($update['message']['text'])) {
            return self::MESSAGE;
        }
        if (isset($update['message']['photo'])) {
            return self::PHOTO;
        }
        if (isset($update['message']['video'])) {
            return self::VIDEO;
        }
        if (isset($update['message']['audio'])) {
            return self::AUDIO;
        }
        if (isset($update['message']['voice'])) {
            return self::VOICE;
        }
        if (isset($update['message']['contact'])) {
            return self::CONTACT;
        }
        if (isset($update['message']['document'])) {
            return self::DOCUMENT;
        }
        if (isset($update['message']['location'])) {
            return self::LOCATION;
        }
        if (isset($update['channel_post'])) {
            return self::CHANNEL_POST;
        }
        if (isset($update['message']['video_note'])) {
            return self::VIDEONOTE;
        }
        if (isset($update['message']['sticker'])) {
            return self::STICKER;
        }

        return false;
    }

    private function sendAPIRequest($url, array $content, $post = true)
    {
        if (isset($content['chat_id'])) {
            $url = $url.'?chat_id='.$content['chat_id'];
            unset($content['chat_id']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        echo 'inside curl if';
        if (!empty($this->proxy)) {
            echo 'inside proxy if';
            if (array_key_exists('type', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy['type']);
            }

            if (array_key_exists('auth', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy['auth']);
            }

            if (array_key_exists('url', $this->proxy)) {
                echo 'Proxy Url';
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy['url']);
            }

            if (array_key_exists('port', $this->proxy)) {
                echo 'Proxy port';
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy['port']);
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok' => false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }
        echo $result;
        curl_close($ch);
        if ($this->log_errors) {
            if (class_exists('TelegramErrorLogger')) {
                $loggerArray = ($this->getData() == null) ? [$content] : [$this->getData(), $content];
                TelegramErrorLogger::log(json_decode($result, true), $loggerArray);
            }
        }

        return $result;
    }
}

// Helper for Uploading file using CURL
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename="
        .($postname ?: basename($filename))
        .($mimetype ? ";type=$mimetype" : '');
    }
}
