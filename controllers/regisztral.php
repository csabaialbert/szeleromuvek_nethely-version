<?php

class Regisztral_Controller {
	public string $baseName = 'beleptet';    //meghatározni, hogy melyik oldalon vagyunk

	public function main(array $vars) {    // a router által továbbított paramétereket kapja
		$regisztralModel = new Regisztral_Model;    //az osztályhoz tartozó modell
		//a modellben beregisztráljuk a felhasználót
		$retData = $regisztralModel->get_data($vars);
		if ($retData['eredmeny'] == "ERROR")
			$this->baseName = "belepes";
		//betöltjük a nézetet
		$view = new View_Loader($this->baseName . '_main');
		//Átadjuk a lekérdezett adatokat a nézetnek
		foreach ($retData as $name => $value)
			$view->assign($name, $value);
	}
}