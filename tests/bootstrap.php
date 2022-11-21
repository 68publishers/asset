<?php

declare(strict_types=1);

use Tester\Environment;

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}

Environment::setup();
date_default_timezone_set('Europe/Prague');
