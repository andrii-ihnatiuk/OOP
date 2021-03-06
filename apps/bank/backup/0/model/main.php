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

	public function formsubmitAmbassador(array $data):?array {
		// Тут модель повинна бути допрацьована, щоб використовувати бази даних, тощо

		$key = '1630441797:AAGkZjxDWBPZ54Qh5C0N4nXbYuQy5V-YxUU'; // Ключ API телеграм 

		if (strlen($key) == 0) { // Проверка на наличие ключа API бота
			throw new \Exception('Missing API key!');
		}

		$result = null;
		$chat = 496102264;
		$text = "Нова заявка на заміну *IBAN*:\n" . $data['firstname'] . ' '. $data['secondname'];

		if (strlen($data['position']) > 0) { // Формирование запроса в зависимости от наличия поля
			$text .= ', ' . $data['position'];
		}
		$text .= "\n*IBAN*: " . $data['iban'] . "\n*Зв'язок*: " . $data['phone'];

		$text = urlencode($text);
		$answer = file_get_contents("https://api.telegram.org/bot$key/sendMessage?parse_mode=markdown&chat_id=$chat&text=$text");
		$answer = json_decode($answer, true);
		$result = ['message' => $answer['result']];
		return $result;
	}

	public function __construct() {

	}
}