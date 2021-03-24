<?php
$methods = [
	'submitAmbassador' => [
		'params' => [
			[
				'name' => 'firstname',
				'source' => 'p',
				'required' => true,
				'pattern' => 'name',
				'default' => 'DEFAULT'
			],
			[
				'name' => 'secondname',
				'source' => 'p',
				'required' => true,
				'pattern' => 'name'
			],
			[
				'name' => 'position',
				'source' => 'p',
				'required' => false,
				'default' => ''
			],
			[
				'name' => 'phone',
				'source' => 'p',
				'required' => true,
				'pattern' => 'phone'
			],
			[
				'name' => 'iban',
				'source' => 'p',
				'required' => true,
				'pattern' => 'iban'
			]
		]
	]
];