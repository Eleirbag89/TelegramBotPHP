<?php
use PHPUnit\Framework\TestCase;

class TelegramTest extends TestCase
{
    public function testSample()
    {
        $test = getenv('GH_REPO_NAME');
        $this->assertEquals('TelegramBotPHP', $test);
    }
}
?>