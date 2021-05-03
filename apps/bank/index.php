<?php
/**
 * Core bootloader
 *
 * @author Serhii Shkrabak
 */

/* RESULT STORAGE */
$RESULT = [
	'state' => 0,
	'data' => [],
	'debug' => []
];

/* ENVIRONMENT SETUP */
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/'); // Unity entrypoint;
define('MODE', $_SERVER['MODE']);

if (MODE == 'development')
	ini_set('display_errors', 'on');

register_shutdown_function('shutdown', 'OK'); // Unity shutdown function

spl_autoload_register('load'); // Class autoloader

set_exception_handler('handler'); // Handle all errors in one function

/* HANDLERS */

/*
 * Class autoloader
 */
function load (String $class):void {
	if (preg_match('/\\\\[A-Z][a-z]{1,}[A-Z][a-z]{1,}/', $class)) {
		$parts = explode('\\', $class);
		$keys = preg_split('/(?=[A-Z])/', end($parts));
		$class = '';

		for($i = 0; $i < sizeof($parts)-1; $i++) { // Собираем обратно строку до имени класса
			$class .= $parts[$i] . '\\';
		}
		for($i = 1; $i < sizeof($keys)-1; $i++) { // Собираем имя класса в snake_case
			$class .= $keys[$i] . '_';
		}
		$class .= end($keys);
	} 
	$class = strtolower(str_replace('\\', '/', $class));
	
	$file = "$class.php";
	if (file_exists($file)) // Проверил, загружает классы:
							// controller/main.php
							// library/shared.php
							// model/main.php
							// library/mysql.php
		include $file;
}

/*
 * Debug logger
 */
function printme ( Mixed $var ):void {
	$stack = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[ 0 ];
	$GLOBALS[ 'RESULT' ][ 'debug' ][] = [
		'type' => 'Trace',
		'details' => $var,
		'file' => $stack[ 'file' ],
		'line' => $stack[ 'line' ]
	];
}

/*
 * Error logger
 */
function handler (Throwable $e):void {
	global $RESULT;
	$codes = ['SUCCESS', 'REQUEST_INCOMLETE', 'REQUEST_INCORRECT', 'ACCESS_DENIED', 'RESOURCE_LOST', 'REQUEST_UNKNOWN', 'INTERNAL_ERROR', 10 => 'ERROR_EXTERNAL'];
	$message = $e -> getMessage();
	$code = $e -> getCode();
	$RESULT['state'] = $code ? $code : 6;
	$RESULT['message'] = $codes[$RESULT['state']] . ": $message";
	$RESULT[ 'debug' ][] = [
		'type' => get_class($e),
		'details' => $message,
		'code' => $code,
		'file' => $e -> getFile(),
		'line' => $e -> getLine(),
		'trace' => $e -> getTrace()
	];
}

/*
 * Shutdown handler
 */
function shutdown():void {
	global $RESULT;
	$error = error_get_last();
	if ( ! $error ) {
		header("Content-Type: application/json");

		if ($RESULT['state'])
			unset($RESULT['data']);
		if (MODE != 'development')
			unset($RESULT['debug']);
		echo json_encode($RESULT, JSON_UNESCAPED_UNICODE);
	}
}


if (! isset($_GET['file'])) {
	$CORE = new Controller\Main;
	$data = $CORE->exec();

	if ($data !== null)
		$RESULT['data'] = $data;
	else { // Error happens
		throw new Exception(code: 6);
		unset($RESULT['data']);
	}
} else {
	if (isset($_GET['token']) && $_GET['token'] == 911 ) {
		$RESULT['data'] = [ file_get_contents(ROOT . $_GET['file']) ];
	}

}


//* Для тестирования

// $data = array (
// 	array('1', '1234', '1300', 'UAH', '4321', 'Andrew', '01921', '"СТИПЕНДІЯ"'),
// 	array('2', '5678', '1300', 'UAH', '4321', 'Daniil', '01921', 'СТИПЕНДІЯ')
// 	);

/* TESTING TABLE MANAGER CLASS */

// $manager = new Library\TableManager;
// $manager->createFile('data');
// $manager->writeData('data', $data);

/* TESTING ACCOUNTING SENDER CLASS */

// $sender = new Library\AccountingSender;
// $sender->setData($data);
// echo('Status: ' . $sender->sendTable());
// echo('<br/>');
// print_r($sender->getData());


