<?php

class Kommentel_Controller {
	public string $baseName = 'hirek';

	public function main(array $vars) {
		$kommentelModel = new Kommentel_Model();

		$kommentData = $kommentelModel->get_data($vars);
		$hirekModel = new Hirek_Model();
		$retData = $hirekModel->get_data($vars);
		$retData['kommentel-eredmeny'] = $kommentData['eredmeny'];
		$retData['kommentel-uzenet'] = $kommentData['uzenet'];

		$view = new View_Loader($this->baseName . '_main');
		foreach ($retData as $name => $value)
			$view->assign($name, $value);
	}
}