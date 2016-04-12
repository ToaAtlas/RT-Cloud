<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;
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
			var_dump($user); // c'est un objet
			echo"<br><br>";
			$userId = $user->getId(); // on recup l'id user
			$disque = \micro\orm\DAO::getAll("disque", "idUtilisateur=$userId");
			var_dump($disque); // on recup les disques du user
			echo"<br><br>";
			$numDisque = 1;
			foreach ($disque as $disque){ // on les affiche
				echo "disque numéro $numDisque<br>";
				echo $disque->getSize()." taille";
				echo "<br>";
				echo $disque->getOccupation()." occupation";
				echo "<br>";
				echo $disque->getQuota().' quota';
				echo "<br>";
				$numDisque++;
			}
		}
		else {
			echo "<div class='content'><h4>Veuillez vous connecter</h4></div>";
		}
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}

}