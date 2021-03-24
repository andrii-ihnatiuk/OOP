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
						$var = $this->getVar($param['name'], $param['source']); // Вытаскиваем из _POST полученные данные и записываем в $var

						if ($var) { // Параметр установлен
							if (isset($param['pattern'])) {
								$var = $this->validate($param['pattern'], $var); // Проверка полученного значения
							}
							$request[$param['name']] = $var; // Последовательно записываем данные в request
						} else if ($param['required'] == true) { // Параметр обязателен, но пуст, сообщаем об этом
							throw new \Exception('Missing attribute:' . ' ' . $param['name']);
						} else if (isset($param['default'])) { // Если существует значение по умолчанию тогда применяем его
							$request[$param['name']] = $param['default'];
						}
					}

					if (method_exists($this->model, $path[1] . $path[2])) { // Вызов formsubmitAmbassador()
						$method = [$this->model, $path[1] . $path[2]];
						$result = $method($request);
					}

				}

			}
		}

		return $result;

	}

	protected function validate(String $pattern, String $var) { // ПРОВЕРКА ДАННЫХ НА КОРРЕКТНОСТЬ 
		$file = ROOT . 'library/regex.php';

		if (file_exists($file))
			include $file;

		if (isset($regex[$pattern])) 
			$target = $regex[$pattern]; // Цель исследования: firstname/secondname/...

		if (! preg_match($target['pattern'], $var)) 
			throw new \Exception("Given attribute is incorrect: $pattern");

		if (isset($target['callback'])) 
			$var = $target['callback']($var); // Вызываем проверку для phone

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