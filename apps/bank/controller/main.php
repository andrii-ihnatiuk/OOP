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
	
	public function exec():?array {
		$result = null;
		$url = $this->getVar('REQUEST_URI', 'e');
		$path = explode('/', $url);

		if (isset($path[2]) && !strpos($path[1], '.')) { // Disallow directory changing
			$file = ROOT . 'model/config/methods/' . $path[1] . '.php';
			if (file_exists($file)) {
				include $file;

				if (isset($methods[$path[2]])) {
					$details = $methods[$path[2]]; // details = submitAmbassador[]
					$request = [];
					foreach ($details['params'] as $param) { // param тут это один набор name-source-pattern
						$var = $this->getVar($param['name'], $param['source']); // вытаскиваем из _POST полученные данные и записываем в $var

						if ($var) {
							$var = $this->validate($param['name'], $var); // Проверка полученного значения

							$request[$param['name']] = $var; // последовательно записываем данные в request
						} else if ($param['required'] == true) { // Параметр пуст, сообщаем об этом
							throw new \Exception('Missing attribute:' . ' ' . $param['name']);
						}
					}

					if (method_exists($this->model, $path[1] . $path[2])) { // вызов formsubmitAmbassador()
						$method = [$this->model, $path[1] . $path[2]];
						$result = $method($request);
					}

				}

			}
		}

		return $result;

	}

	protected function validate(String $name, String $var) { // ПРОВЕРКА ДАННЫХ НА КОРРЕКТНОСТЬ 
		$file = ROOT . 'library/regex.php';
		if (file_exists($file)) {
			include $file;
		}
		$target = $regex[$name]; // цель исследования: firstname/secondname/...

		if (strlen($target['pattern']) > 0) { // если существует записанный паттерн - сверяем
			if (! preg_match($target['pattern'], $var)) {
				throw new \Exception("Given attribute is incorrect: $name");
			}
		}
		if ($name == 'phone') {
			$var = preg_replace('/\s+/', '', $var); // обрезаем все пробелы в строке

			$param = strlen($var); // подсчет длины номера
			switch ($param) { // редактирование номера в зависимости от длины
				case 9:
					$var = preg_replace('/^/', '+380', $var);
					break;
				case 10:
					$var = preg_replace('/^/', '+38', $var);
					break;
				case 11:
					$var = preg_replace('/^/', '+3', $var);
					break;
				case 12:
					$var = preg_replace('/^/', '+', $var);
					break;
				default:
					break;
			}
		}

		return $var;
	}

	public function __construct() {
		// CORS configuration
		$origin = $this -> getVar('HTTP_ORIGIN', 'e');
		
		foreach ( [$this -> getVar('FRONT', 'e')] as $allowed );
			if ( $origin == "https://$allowed") {
				header( "Access-Control-Allow-Origin: $origin" );
				header( 'Access-Control-Allow-Credentials: true' );
			}
		$this->model = new \Model\Main;
	}
}