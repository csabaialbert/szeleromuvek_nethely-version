<?php

class Kilepes_Controller {
	public string $baseName = 'kilepes';  //meghatározni, hogy melyik oldalon vagyunk

	public function main(array $vars) // a router által továbbított paramétereket kapja
	{
		$kilepesModel = new Kilepes_Model;  //az osztályhoz tartozó modell
		//a modellben belépteti a felhasználót
		$retData = $kilepesModel->get_data();
		//betöltjük a nézetet
		$view = new View_Loader($this->baseName . '_main');
		//átadjuk a lekérdezett adatokat a nézetnek
		foreach ($retData as $name => $value)
			$view->assign($name, $value);
	}
}

