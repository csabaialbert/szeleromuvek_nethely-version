<?php

class Arfolyamok_Controller {
	public string $baseName = 'arfolyamok';  //meghatározni, hogy melyik oldalon vagyunk

	public function main(array $vars) // a router által továbbított paramétereket kapja
	{
		//betöltjük a nézetet
		$view = new View_Loader($this->baseName . "_main");
	}
}

?>