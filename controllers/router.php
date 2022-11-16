<?php

session_start();
if (!isset($_SESSION['userid'])) $_SESSION['userid'] = 0;
if (!isset($_SESSION['userfirstname'])) $_SESSION['userfirstname'] = "";
if (!isset($_SESSION['userlastname'])) $_SESSION['userlastname'] = "";
if (!isset($_SESSION['userlevel'])) $_SESSION['userlevel'] = "1__";

include(SERVER_ROOT . 'includes/database.inc.php');
include(SERVER_ROOT . 'includes/menu.inc.php');
include(SERVER_ROOT . 'client/dev_napra.php');

// Felbontjuk a paramétereket. Az & elválasztó jellel végzett felbontás
// megfelelő lesz, első eleme a megtekinteni kívánt oldal neve.

$page = "nyitolap";
$vars = array();

$request = $_SERVER['QUERY_STRING'];

if ($request != "" && $request != "_ijt=opqhlf5fug9l6tjhjoa7nvmg7o&_ij_reload=RELOAD_ON_SAVE") {
	$params = explode('/', $request);
	$page = array_shift($params); // a kért oldal neve

	$vars += $_POST;

	foreach ($params as $p) {
		$vars[] = $p;
	}
}

// Meghatározzuk a kért oldalhoz tartozó vezérlőt. Ha megtaláltuk
// a fájlt és a hozzá tartozó vezérlő oldalt is, akkor betöltjük az
// előbbiekben lekérdezett paramétereket továbbadva.

$controllerfile = $page; // . ($subpage != "" ? "_" . $subpage : "");
$target = SERVER_ROOT . 'controllers/' . $controllerfile . '.php';
if (!file_exists($target)) {
	$controllerfile = "error404";
	$target = SERVER_ROOT . 'controllers/error404.php';
}

include_once($target);
$class = ucfirst($controllerfile) . '_Controller';
if (class_exists($class)) {
	$controller = new $class;
} else {
	die('class does not exists!');
}

// spl_autoload_register(...) függvény, amely ismeretlen osztály hívásakor megpróbálja automatikusan betölteni a megfelelő fájlt.
// A modellekhez használjuk, egységesen nevezzük el fájljainkat (osztály nevével megegyező, csupa kisbetűs .php)
spl_autoload_register(function ($className) {
	$file = SERVER_ROOT . 'models/' . strtolower($className) . '.php';
	if (file_exists($file)) {
		include_once($file);
	} else {
		die("File '$file' containing class '$className' not found.");
	}
});

$controller->main($vars);
