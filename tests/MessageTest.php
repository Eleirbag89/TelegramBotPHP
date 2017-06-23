<?php
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testSendMessage()
    {
        global $BOT_TOKEN;
        global $CHAT_ID;
        $telegram = new Telegram($BOT_TOKEN);
        $reply = "It works!";
        $content = array('chat_id' => $CHAT_ID, 'text' => $reply);
        $reply = $telegram->sendMessage($content);
        $this->assertTrue($reply['ok']);
    }
}
?>