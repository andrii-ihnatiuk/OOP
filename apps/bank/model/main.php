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

		$key = null; // Ключ API телеграм 

		if (strlen($key) == 0) { // Проверка на наличие ключа API бота
			throw new \Exception('Missing API key!');
		}

		$result = null;
		$chat = null // ID мого чату
		$text = "Нова заявка на заміну *IBAN*:\n" . $data['firstname'] . ' '. $data['secondname'];

		if (array_key_exists('position', $data)) { // Формирование запроса в зависимости от наличия поля
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
