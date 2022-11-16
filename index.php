<?php
if (str_contains(__FILE__, 'feladat')) {
	define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);
	define('SITE_ROOT', 'http://adamvarhegyi.albertcsabai.awebprogjo.nhely.hu/');
	require_once (SERVER_ROOT.'controllers/'.'router.php');
} else {
	define('SERVER_ROOT', realpath(dirname(__FILE__)).'\\');
	define('SITE_ROOT', 'http://localhost/');
	require_once (SERVER_ROOT . '/controllers/' . 'router.php');
}

?>