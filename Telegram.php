<?php
/**
 * Telegram Bot Class.
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
class Telegram {
	private $bot_id = "";
	private $data = array();
	
	public function __construct($bot_id) {  
            $this->bot_id = $bot_id;
            $this->data = $this->getData();
	}
	public function sendMessage(array $content) {
		$url = 'https://api.telegram.org/bot' . $this->bot_id . '/sendMessage';
		$this->sendAPIRequest($url, $content);

	}
	public function getData() {
		if ( empty($this->data)) {
			$rawData = file_get_contents("php://input");
			return json_decode($rawData,true);
		}else {
			return $this->data;
		}
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
	public function messageFromGroup() {
		if ($this->data["message"]["chat"]["title"] == "") {
			return false;
		}
		return true;
	}
	public function buildKeyBoard(array $options, $onetime=true, $resize=true, $selective=true) {
		$replyMarkup = array(
			'keyboard' => $options,
			'one_time_keyboard' => $onetime,
			'resize_keyboard' => $resize,
			'selective'		=> $selective
		);
		$encodedMarkup = json_encode($replyMarkup,true);
		return $encodedMarkup;
	}
	private function sendAPIRequest($url, array $content) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
		$result = curl_exec($ch);
		curl_close($ch);
	}
        
}

?>