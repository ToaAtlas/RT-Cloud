<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;
use micro\orm\DAO;

class MyDisques extends Controller{
	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
		}
	}
	public function index() {
		echo Jquery::compile();
		if (Auth::isAuth()){ //verifie user connecté
			$user = Auth::getUser(); // get user name		
			$userId = $user->getId(); // on recup l'id user
			$disques = \micro\orm\DAO::getAll("disque", "idUtilisateur=$userId");// on recup les disques du user, tableau d'objet
			foreach($disques as $disque) {
				$disque->occupation = DirectoryUtils::formatBytes($disque->getOccupation() / 100 * $disque->getQuota());
				$disque->occupationTotal = DirectoryUtils::formatBytes($disque->getQuota());
			}


			$this->loadView("MyDisques/index.html", array("user"=>$user,"disques"=>$disques));
		}
		else {
			echo "<div id='content'><h4>Veuillez vous connecter</h4></div>";
		}
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}

}