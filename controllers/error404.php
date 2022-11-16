<?php

class Error404_Controller {
	public string $baseName = 'error404';

	public function main(array $vars) {
		$view = new View_Loader($this->baseName . '_main');
	}
}
