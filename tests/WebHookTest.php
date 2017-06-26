<?php
use PHPUnit\Framework\TestCase;

class WebHookTest extends TestCase
{
    /**
     * @dataProvider urlsProvider
     */
    public function testSetWebHook($url, $expectedBool, $expectedDesc)
    {
        global $BOT_TOKEN;
        global $CHAT_ID;
        global $WEBHOOK_URL;
        $telegram = new Telegram($BOT_TOKEN);
        $reply = $telegram->setWebhook($url);
        $this->assertEquals($expectedBool, $reply['ok']);
        $this->assertEquals($expectedDesc, $reply['description']);
    }
    
      public function testDeleteWebHook()
    {
        global $BOT_TOKEN;
        $telegram = new Telegram($BOT_TOKEN);
        $reply = $telegram->deleteWebhook();
        $this->assertTrue($reply['ok']);
    }
    
    public function urlsProvider()
    {
        return [
            ["test", false, "Bad Request: bad webhook: getaddrinfo: Name or service not known"],
            ["http://google.com", false, "Bad Request: bad webhook: HTTPS url must be provided for webhook"],
            ["https://google.com", true, "Webhook was set"],
            ["", true, "Webhook was deleted"]
        ];
    }
    public function tearDown()
    {
        sleep(2);
    }
    
    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
        global $WEBHOOK_URL;
         global $BOT_TOKEN;
        $telegram = new Telegram($BOT_TOKEN);
        $reply = $telegram->setWebhook($WEBHOOK_URL);
    }
}
?>