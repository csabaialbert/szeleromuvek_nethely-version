<?php

class Hirbekuld_Controller {
	public string $baseName = 'hirek';

	public function main(array $vars) {
		$hirbekuldModel = new Hirbekuld_Model();

		$hirbekuldData = $hirbekuldModel->get_data($vars);
		$hirekModel = new Hirek_Model();
		$retData = $hirekModel->get_data($vars);
		$retData['hirbekuld-eredmeny'] = $hirbekuldData['eredmeny'];
		$retData['hirbekuld-uzenet'] = $hirbekuldData['uzenet'];

		$view = new View_Loader($this->baseName . '_main');
		foreach ($retData as $name => $value)
			$view->assign($name, $value);
	}
}
