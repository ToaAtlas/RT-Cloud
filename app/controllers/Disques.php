<?php
class Disques extends \_DefaultController {

	public function __construct(){
		parent::__construct();
		$this->title="Disques";
		$this->model="Disque";
	}

	public function create() {
		$this->loadView('Disques/create.html');
	}
}