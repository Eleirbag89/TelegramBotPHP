<?php
include("Telegram.php");
global $BOT_TOKEN; 
$BOT_TOKEN = getenv('BOT_TOKEN');
global $CHAT_ID;
$CHAT_ID = getenv('CHAT_ID');