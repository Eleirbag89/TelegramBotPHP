<?php
include("Telegram.php");
global $BOT_TOKEN; 
$BOT_TOKEN = getenv('BOT_TOKEN');
global $CHAT_ID;
$CHAT_ID = getenv('CHAT_ID');
global $WEBHOOK_URL;
$WEBHOOK_URL = getenv('WEBHOOK_URL');