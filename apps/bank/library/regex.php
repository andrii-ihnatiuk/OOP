<?php
$regex = [
    'name' => [
        'pattern' => '/^[a-zA-ZА-Яа-яёЁ]+$/u'
    ],
    'phone' => [
        'pattern' => '/^(\+?38)?0?\s?[5-9][0-9]\s?[0-9]{3}\s?[0-9]{4}$/', // Учитываются пробелы, +380/380/0/_ 
        'callback' => function($var) {
            $var = preg_replace('/\s+/', '', $var); // Обрезаем все пробелы в строке

			$param = strlen($var); // Подсчет длины номера
			switch ($param) { // Редактирование номера в зависимости от длины
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
            return $var;
        }
    ],
    'iban' => [
        'pattern' => '/^[A-Z]{2}[0-9]{2}[a-zA-Z0-9]{11,30}$/' // Без учитывания пробелов при вводе, но с учетом всех стран 
    ]                                                         // Планируется добавить проверку по контрольной сумме iban
];
