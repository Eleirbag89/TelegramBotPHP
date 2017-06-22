<?php
use PHPUnit\Framework\TestCase;

class TelegramTest extends TestCase
{
    public function testSample()
    {
        $bot_id = getenv('BOT_TOKEN');
        $chat_id = getenv('CHAT_ID');
        
        $telegram = new Telegram($bot_id);
        $reply = "It works!";
        $content = array('chat_id' => $chat_id, 'text' => $reply);
        $reply = $telegram->sendMessage($content);
        $this->assertTrue($reply['ok']);
    }
}
?>