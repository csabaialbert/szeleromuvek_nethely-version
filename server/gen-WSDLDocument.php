<?php
//A WSDL generátor bemenő adatainak megadására szolgáló fájl.
//error_reporting(0);
require 'tables.php';
require 'WSDLDocument/WSDLDocument.php';
$wsdl = new WSDLDocument('Tables', SERVER_ROOT . "server/server.php", SERVER_ROOT . "server/");
$wsdl->formatOutput = true;
$wsdlfile = $wsdl->saveXML();
echo $wsdlfile;
file_put_contents("tables.wsdl", $wsdlfile);
?>
