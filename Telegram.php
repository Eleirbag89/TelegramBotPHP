<?php

/**
 * Telegram Bot Class.
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
class Telegram {

    private $bot_id = "";
    private $data = array();
    private $updates = array();

    public function __construct($bot_id) {
        $this->bot_id = $bot_id;
        $this->data = $this->getData();
    }

    public function getMe() {
        return $this->endpoint("getMe", array(), false);
    }

    public function sendMessage(array $content) {
        return $this->endpoint("sendMessage", $content);
    }

    public function endpoint($api, array $content, $post = true) {
        $url = 'https://api.telegram.org/bot' . $this->bot_id . '/' . $api;
        if ($post)
            return $this->sendAPIRequest($url, $content);
        else
            return $this->sendAPIRequest($url, array(), false);
    }

    public function sendPhoto(array $content) {
        return $this->endpoint("sendPhoto", $content);
    }

    public function sendAudio(array $content) {
        return $this->endpoint("sendAudio", $content);
    }

    public function sendDocument(array $content) {
        return $this->endpoint("sendDocument", $content);
    }

    public function sendSticker(array $content) {
        return $this->endpoint("sendSticker", $content);
    }

    public function sendVideo(array $content) {
        return $this->endpoint("sendVideo", $content);
    }

    public function sendVoice(array $content) {
        return $this->endpoint("sendVoice", $content);
    }

    public function sendLocation(array $content) {
        return $this->endpoint("sendLocation", $content);
    }

    public function sendChatAction(array $content) {
        return $this->endpoint("sendChatAction", $content);
    }

    public function getData() {
        if (empty($this->data)) {
            $rawData = file_get_contents("php://input");
            return json_decode($rawData, true);
        } else {
            return $this->data;
        }
    }

    public function setData(array $data) {
        $this->data = data;
    }

    public function Text() {
        return $this->data["message"] ["text"];
    }

    public function ChatID() {
        return $this->data["message"]["chat"]["id"];
    }

    public function Date() {
        return $this->data["message"]["date"];
    }

    public function FirstName() {
        return $this->data["message"]["from"]["first_name"];
    }

    public function LastName() {
        return $this->data["message"]["from"]["last_name"];
    }

    public function Username() {
        return $this->data["message"]["from"]["username"];
    }

    public function Location() {
        return $this->data["message"]["location"];
    }

    public function UpdateID() {
        return $this->data["update_id"];
    }

    public function UpdateCount() {
        return count($this->updates["result"]);
    }

    public function messageFromGroup() {
        if ($this->data["message"]["chat"]["title"] == "") {
            return false;
        }
        return true;
    }

    public function buildKeyBoard(array $options, $onetime = true, $resize = true, $selective = true) {
        $replyMarkup = array(
            'keyboard' => $options,
            'one_time_keyboard' => $onetime,
            'resize_keyboard' => $resize,
            'selective' => $selective
        );
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }

    public function getUpdates($offset = 0, $limit = 100, $timeout = 0, $update = true) {
        $content = array('offset' => $offset, 'limit' => $limit, 'timeout' => $timeot);
        $reply = $this->endpoint("getUpdates", $content);
        $this->updates = json_decode($reply, true);
        if ($update) {
            $last_element_id = $this->updates["result"][count($this->updates["result"]) - 1]["update_id"] + 1;
            $content = array('offset' => $last_element_id, 'limit' => "1", 'timeout' => $timeot);
            $this->endpoint("getUpdates", $content);
        }
        return $this->updates;
    }

    public function serveUpdate($update) {
        $this->data = $this->updates["result"][$update];
    }

    private function sendAPIRequest($url, array $content, $post = true) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

// Helper for Uploading file using CURL
if (!function_exists('curl_file_create')) {

    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
                . ($postname ? : basename($filename))
                . ($mimetype ? ";type=$mimetype" : '');
    }

}
?>