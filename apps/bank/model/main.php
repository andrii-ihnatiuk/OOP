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
							'buttons' => [[
								Entities\Button::get(1),
								Entities\Button::get(-1)]
							]
							// 'buttons' => [
							// 	[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
							// 	['id' => -1, 'title' => '🚀 Розпочати роботу']]
							// ]
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
								[Entities\Button::get(1),
								Entities\Button::get(3)],
								[Entities\Button::get(4)]
							]
							// 'buttons' => [
							// 	[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
							// 	['id' => 2, 'title' => "Надати номер", 'request' => 'contact']],
							// 	[['id' => 3, 'title' => 'Отримати привітання', 'request' => 'click'], 
							// 	['id' => 4, 'title' => 'Ввести свій IBAN']]
							// ]
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
										[Entities\Button::get(1),
										]
								]
								// 'buttons' => [
								// 	[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
								// 	['id' => 2, 'title' => "Надати номер", 'request' => 'contact']]
								// ]  
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
									[Entities\Button::get(1),
									Entities\Button::get(3)],
									[Entities\Button::get(4)]
								]	
								
								// 'buttons' => [
								//	[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
								//	['id' => 2, 'title' => "Надати номер", 'request' => 'contact']],
								//	[['id' => 3, 'title' => 'Отримати привітання', 'request' => 'click'], 
								//	['id' => 4, 'title' => 'Ввести свій IBAN', 'request'=> null]] // на null реагирует ок
								// ]
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
