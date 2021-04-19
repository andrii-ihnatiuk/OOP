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
						'value' => "Сервіс: Реквізити банківських акаунтів користувачів\nЛаскаво просимо до нашої сторінки!",
						'keyboard' => [
							'inline' => true, // Кнопка в сообщении или в меню
							'buttons' => [
								[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
								['id' => -1, 'title' => '🚀 Розпочати роботу']]
							]
						]
					];
					break;
				}
				else if ($value == '/back' or $value == '🚪 Назад') {
					$result = [
						'to' => $GLOBALS['uni.user'],
						'type' => 'message',
						'value' => "*Повернено до головного розділу*",
						'keyboard' => [
							'inline' => true, // false - в request работает contact | true - работает message, click
							'buttons' => [
								[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
								['id' => 2, 'title' => "Надати номер", 'request' => 'contact']],
								[['id' => 3, 'title' => 'Отримати привітання', 'request' => 'click'], 
								['id' => 4, 'title' => 'Ввести свій IBAN']]
							]
						]
					];
				}
				else
				$result = [
					'to' => $GLOBALS['uni.user'],
					'type' => 'message',
					'value' => "Сервіс `Банківські реквізити користувачів` отримав повідомлення: $value"
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
							'value' => "*Введено до нового розділу*",
							'keyboard' => [
								'inline' => false, // false - в request работает contact | true - работает message, click
								'buttons' => [
									[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
									['id' => 2, 'title' => "Надати номер", 'request' => 'contact']]
								]
							]
						];
					}	
					else if ($code == 3) {
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => "Привіт! 🙋"
						];
					}
					else if ($code == 4) {
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => "🗿 Я ще таке не вмію"
						];
					}
					else {
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => "*Виберіть наступну дію:*",
							'keyboard' => [
								'inline' => true, // false - в request работает contact | true - работает message, click
								'buttons' => [
									[['id' => 1, 'title' => '🚪 Назад', 'request' => 'message', 'value' => 'вихід'],
									['id' => 2, 'title' => "Надати номер", 'request' => 'contact']],
									[['id' => 3, 'title' => 'Отримати привітання', 'request' => 'click'], 
									['id' => 4, 'title' => 'Ввести свій IBAN']]
								]
							]
						];
					}
					// Просто логирование для упрощения разработки
					$file = ROOT . '/model/log.txt';
					$message = 'ENTERED CLICK. Action:'.$type.', Val:'.$value.', Code:'.$code."\n";
					file_put_contents($file, $message, FILE_APPEND);

					break;

				case 'contact':
					$result = [
						'to' => $GLOBALS['uni.user'],
						'type' => 'message',
						'value' => "Сервіс `Банківські реквізити користувачів`. Отримано номер $value"
					];
					break;
		}

		return $result;
	}

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