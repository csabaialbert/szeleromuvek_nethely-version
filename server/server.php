<?php
//Egy SOAP server inicializálása, ami a tables.php file-t használja.
require("tables.php");
$server = new SoapServer("tables.wsdl");
$server->setClass('Tables');
$server->handle();
?>