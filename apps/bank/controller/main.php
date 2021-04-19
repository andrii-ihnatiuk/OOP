<?php
/**
 * User Controller
 *
 * @author Serhii Shkrabak
 * @global object $CORE
 * @package Controller\Main
 */
namespace Controller;
class Main
{
	use \Library\Shared;

	private $model;

	private function prepare(Mixed $var, Array $param):mixed {
		$result = null;
		if ($var) { // Если передали значение определенного атрибута f.e для uni/webhook type|value|code
			$result = $var;
		} else { // Если у атрибута нет значения, делаем доп. проверку на его обязательность
			if (!isset($param['required'])) {
				if (isset($param['default']))
					$result = $param['default'];
				else
					throw new \Exception($param['name'] . ' has no default value', 6);
			}
			else
				throw new \Exception($param['name'], 1);
		}
		return $result;
	}

	private function run($method, $data):?array {
		$result = null;
		if (method_exists($this->model, $method)) { // Если в \Model\Main.php есть такой метод
			$callback = [$this->model, $method]; // Так передаем метод объекта класса Main [more]: https://www.php.net/manual/ru/language.types.callable.php
			$result = call_user_func_array($callback, $data); // Вызывает callback-функцию с массивом параметров data f.e uniwebhook()
		} else throw new \Exception($method, 5);
		return $result; 
	}

	public function exec():?array {
		$result = null;
		$url = $this->getVar('REQUEST_URI', 'e');
		$url = explode('?', $url)[0]; // Разбивает url на массив, используя ? в виде разделителя, получает [0] элемент
		$path = explode('/', $url); // Получает массив: f.e /form/get выдаст ([0]=> [1]=>form [2]=>get)


		if (isset($path[2]) && !strpos($path[1], '.')) { // Disallow directory changing
			$file = ROOT . 'model/config/methods/' . $path[1] . '.php'; // На основе разбитого url определяет какой файл будет подгружаться
			$method = $path[1] . $path[2]; // На основе url определяется метод: f.e tg/webhook вызовет tgwebhook()
			if (file_exists($file)) {
				include $file;
				if (isset($methods[$path[2]])) { // Проверяем наличие метода
					$details = $methods[$path[2]]; 

					$uniget = $this->getVar('uniroad'); // Приходит извне, только если обращаться через POST
					if ($uniget) { // Если параметр uniroad есть в _POST
						// Условие выполнилось - это означает что мы получили запрос от главного бота, начинаем обрабатывать
						$GLOBALS['uni.user'] = $this->getVar('user'); // Получаем от кого пришло сообщение
						$result = ($method == 'uniwebhook') ? [ // Принимает ('callback' => []) если ulr: /uni/webhook
							'callback' => []
						] : []; // В противном случае $result = []

						$query = $this->getVar('query', 'p'); // Вытаскиваем из _POST query
						if (gettype($query) == 'string')
							$query = json_decode($query, true); // Декодируем из json в ассоциативный массив
						$data = [];

						foreach ($query as $task) {

							foreach ($details['params'] as $param) { // В файлах uni.php|tg.php есть ключ params
								// Получаем переменную либо из источника, указанного в tg.php, либо из массива $task, который передаем в метод
								// f.e если метод webhook, то $var примет переданное значение type|value|code (см. uni.php)
								$var = $this->getVar($param['name'], isset($param['source']) ? $param['source'] : 'p', from: $task);
	
								$data[$param['name']] = $this->prepare($var, $param); // Проверка полученных данных
							}

							if (isset($result['callback'])) { // Выполнится если метод будет uniwebhook()
								$callback = $this->run($method, $data); // Вызываем метод 

								if ($callback)
									$result['callback'][] = $callback; // Записываем результат работы функции в ключ callback
							}
							else
								$result[] = $this->run($method, $data); 
						}
					} else { // Если запрос пришел не из системы uniroad
						$data = [];
						foreach ($details['params'] as $param) {
							$var = $this->getVar($param['name'], isset($param['source']) ? $param['source'] : 'p');
							$data[$param['name']] = $this->prepare($var, $param);
						}
						$result = $this->run($method, $data); 
					}

				}

			}
			else
				throw new \Exception("{$path[1]}.{$path[2]}", 5);
		}

		return $result;
	}

	public function __construct() {
		// CORS configuration
		$origin = $this -> getVar('HTTP_ORIGIN', 'e');
		$front = $this -> getVar('FRONT', 'e');

		foreach ( [$front] as $allowed )
			if ( $origin == "https://$allowed") {
				header("Access-Control-Allow-Origin: $origin");
				header('Access-Control-Allow-Credentials: true');
			}
		$this->model = new \Model\Main;
	}
}