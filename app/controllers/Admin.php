<?php
use micro\orm\DAO;
class Admin extends \BaseController {

	public function index() {
		$this->loadView('Admin/index.html');
	}
}