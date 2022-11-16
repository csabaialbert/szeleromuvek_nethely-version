<?php

class Nyitolap_Controller {
	public string $baseName = 'nyitolap';

	public function main(array $vars) {
		$view = new View_Loader($this->baseName . "_main");
	}
}
