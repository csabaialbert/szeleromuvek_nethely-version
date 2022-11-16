<?php

class View_Loader {
	private array $data = array();
	private string|bool $render = FALSE;
	private array|bool $selectedItems = FALSE;
	private string|bool $style = FALSE;

	public function __construct($viewName) {
		$file = SERVER_ROOT . 'views/' . strtolower($viewName) . '.php';
		if (file_exists($file) && strtolower($viewName) != 'soaplista_main') {
			$this->render = $file;
			$this->selectedItems = explode("_", $viewName);
		}
		if (strtolower($viewName) == 'adatlekerdezes_main') {
			$kliensnev = SERVER_ROOT . 'client/' . strtolower($viewName) . '.php';
			$this->render = $kliensnev;
			$this->selectedItems = explode("_", $viewName);
		}
		$file = SERVER_ROOT . 'css/' . strtolower($viewName) . '.css';
		if (file_exists($file)) {
			$this->style = SITE_ROOT . 'css/' . strtolower($viewName) . '.css';
		}
	}

	public function assign($variable, $value) {
		$this->data[$variable] = $value;
	}

	public function __destruct() {
		$this->data['render'] = $this->render;
		$this->data['selectedItems'] = $this->selectedItems;
		$this->data['style'] = $this->style;
		$viewData = $this->data;
		include(SERVER_ROOT . 'views/page_main.php');
	}
}
