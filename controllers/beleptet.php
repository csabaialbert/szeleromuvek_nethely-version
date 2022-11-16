<?php

class Beleptet_Controller {
	public string $baseName = 'beleptet';    //meghatározni, hogy melyik oldalon vagyunk

	public function main(array $vars) {    // a router által továbbított paramétereket kapja
		$beleptetModel = new Beleptet_Model;        //az osztályhoz tartozó modell
		//a modellben belépteti a felhasználót
		$retData = $beleptetModel->get_data($vars);
		if ($retData['eredmeny'] == "ERROR")
			$this->baseName = "belepes";
		//betöltjük a nézetet
		$view = new View_Loader($this->baseName . '_main');
		//Átadjuk a lekérdezett adatokat a nézetnek
		foreach ($retData as $name => $value)
			$view->assign($name, $value);
	}
}

