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
			$userId = $user->getId(); // on recup l'id user
			$disques = \micro\orm\DAO::getAll("disque", "idUtilisateur=$userId");// on recup les disques du user, tableau d'objet
			$this->loadView("MyDisques\index.html", array("user"=>$user,"disques"=>$disques));
			
//foreach ($disques as $disque){ // on les affiche
//				echo "disque numéro $numDisque<br>";
//				echo $disque->getNom()." nom";
//				echo "<br>";
//				echo $disque->getOccupation()." occupation";
//				echo "<br>";
//				echo $rslt = $disque->getQuota()/ModelUtils::sizeConverter("Ko").'ko quota';
//				echo "<br>";
//				echo "<br>";
//				$numDisque++;
//			}
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