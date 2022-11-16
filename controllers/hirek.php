<?php

class Hirek_Controller {
	public string $baseName = 'hirek';

	public function main(array $vars) {
		$hirekModel = new Hirek_Model;

		$retData = $hirekModel->get_data($vars);
		$view = new View_Loader($this->baseName . '_main');
		foreach ($retData as $name => $value)
			$view->assign($name, $value);
	}
}
