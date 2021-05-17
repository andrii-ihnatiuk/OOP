<?php
/**
 * User Controller
 *
 * @author Serhii Shkrabak
 * @global object $CORE->model
 * @package Model\Main
 */
namespace Model;


class Main
{
	use \Library\Shared;
	
	public function uniwebhook(String $type = '', String $value = '', Int $code = 0):?array {
		$result = null;
		switch ($type) {
			case 'message':
				if ($value == 'вихід') {
					$result = ['type' => 'context', 'set' => null];
				} 
				else if ($value == '/start') {
					$result = [
						'to' => $GLOBALS['uni.user'],
						'type' => 'message',
						'value' => Entities\Message::search(label: 'greeting'),
						'keyboard' => [
							'inline' => true, // Кнопка в сообщении или в меню
							'buttons' => [
								[Entities\Button::get(1),
								Entities\Button::get(-1)]
							]
						]
					];
					break;
				}
				else if ($value == '/back' or $value == '🚪 Назад') {
					$result = [
						'to' => $GLOBALS['uni.user'],
						'type' => 'message',
						'value' => Entities\Message::search(label: 'back'),
						'keyboard' => [
							'inline' => true, // false - в request работает contact | true - работает message, click
							'buttons' => [
								Entities\Button::get([1, 3]),
								[Entities\Button::get(4)]
							]	
						]
					];
				}
				else
				$result = [
					'to' => $GLOBALS['uni.user'],
					'type' => 'message',
					'value' => Entities\Message::search(label: 'got_message') . ' ' . $value
				];
				break;

				case 'click': 
					if ($code == 1) {
						$result = ['type' => 'context', 'set' => null];
					} 
					else if ($code == 2) {
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => Entities\Message::search(label: 'forward'),
							'keyboard' => [
								'inline' => false, // false - в request работает contact | true - работает message, click
								'buttons' => [
									[Entities\Button::get(1)]
								]
							]
						];
					}	
					else if ($code == 3) {
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => Entities\Message::search(label: 'hi')
						];
					}
					else if ($code == 4) {
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => Entities\Message::search(label: 'idk')
						];
					}
					else {
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => Entities\Message::search(label: 'next'),
							'keyboard' => [
								'inline' => true, // false - в request работает contact | true - работает message, click
								'buttons' => [
									Entities\Button::get([1, 3]),
									[Entities\Button::get(4)]
								]	
							]
						];
					}
					// Логирование для упрощения разработки
					$file = ROOT . '/model/log.txt';
					$message = 'ENTERED CLICK. Action:'.$type.', Val:'.$value.', Code:'.$code."\n";
					file_put_contents($file, $message, FILE_APPEND);

					break;

				case 'contact':
					$result = [
						'to' => $GLOBALS['uni.user'],
						'type' => 'message',
						'value' => Entities\Message::search(label: 'got_number') . ' ' . $value
					];
					break;
		}	

		return $result;
	}

	//? Не нужно
	public function formsubmitAmbassador(String $firstname, String $secondname, String $phone, String $position = ''):?array {
		$result = null;
		$chat = 891022220;
		$this->TG->alert("Нова заявка в *Цифрові Амбасадори*:\n$firstname $secondname, $position\n*Зв'язок*: $phone");
		$result = [];
		return $result;
	}


	//? API METHOD
	public function formmyInfo(String $name, String $surname, String $patronymic, String $iban):?array {

		$result = null;
		$not_valid = ['result' => 'not valid'];

		$file = ROOT . '/library/regex.php';
		if (file_exists($file))
			include $file;
		else
			throw new \Exception("Required file doesn't exist!");

		foreach (['name', 'surname', 'patronymic', 'iban'] as $key) {
			if ($$key) {
				$target = $regex[$key];
				if (! preg_match($target['pattern'], $$key)) {
					$not_valid['details'][] = $key;
				}
				else if (isset($target['callback'])) 
					$target['callback']($$key) == false ? $not_valid['details'][] = $key : null; 
			}
		}

		if (count($not_valid) == 1) {
			$user = new Entities\User('user1', $iban, $name, $surname, $patronymic);
			$result = [
				'result' => 'valid',
				'details' => [
					'name' => $user->getName(),
					'surname' => $user->getSurname(),
					'patronymic' => $user->getPatronymic(),
					'IBAN' => $user->getIban()
				]
			];
		} else 
			$result = $not_valid;
		

		return $result;
	}
	//? API METHOD
	public function bankReport(String $chat):?array {

		$result = null;
		//! DONT FORGET TO DELETE BEFORE PUSH
		$key = ''; // Ключ API телеграм 

		if (strlen($key) == 0) {
			throw new \Exception('Missing API key!');
		}
		if ($chat == '') 
			$chat = 496102264;

		$path = ROOT . '/library/data/data.csv';	
		if (!file_exists($path)) {
			throw new \Exception("File doesn't exist!");
		}	

		$filepath = realpath($path);
		$post = array('chat_id' => $chat,'document'=>curl_file_create($filepath)); 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot" . $key . "/sendDocument"); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec ($ch);
		curl_close ($ch); 

		$output = json_decode($output, true);
		if (isset($output['ok']) && $output['ok'] == false) {
			$result = [
				'ok' => false,
				'details' => $output['description']
			];
		} else 
			$result = ['message' => $output['result']];
		
		
		return $result;

	}

	public function __construct() {
		$this->db = new \Library\MySQL('core',
			\Library\MySQL::connect(
				$this->getVar('DB_HOST', 'e'),
				$this->getVar('DB_USER', 'e'),
				$this->getVar('DB_PASS', 'e')
			) );
		$this->setDB($this->db);
	
	}

}
