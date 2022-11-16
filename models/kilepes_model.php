<?php

class Kilepes_Model {
	public function get_data(): array {
		$retData['eredmény'] = "OK";
		$retData['uzenet'] = "Viszontlátásra, kedves " . $_SESSION['userlastname'] . " " . $_SESSION['userfirstname'] . "!";
		$_SESSION['userid'] = 0;
		$_SESSION['userlastname'] = "";
		$_SESSION['userfirstname'] = "";
		$_SESSION['userlevel'] = "1__";
		Menu::setMenu();
		return $retData;
	}
}
