<?php
if (str_contains(__FILE__, 'feladat')) {
	define('SERVER_ROOT', 'http://szeleromuvek.awebprogjo.nhely.hu/');
	define('SITE_ROOT', 'http://szeleromuvek.awebprogjo.nhely.hu/');
	require_once (SERVER_ROOT.'controllers/'.'router.php');
} else {
	define('SERVER_ROOT', realpath(dirname(__FILE__)).'\\');
	define('SITE_ROOT', 'http://localhost/');
	require_once (SERVER_ROOT . '/controllers/' . 'router.php');
}

