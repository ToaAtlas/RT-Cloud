<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;
use micro\orm\DAO;

class MyDisques extends Controller{
	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView('main/vHeader.html', array('infoUser' => Auth::getInfoUser()));
		}
	}

	public function index() {
		echo Jquery::compile();
		if (Auth::isAuth()){ //verifie user connecté
			$user = Auth::getUser(); // get user name		
			$userId = $user->getId(); // on recup l'id user
			$disques = \micro\orm\DAO::getAll('disque', 'idUtilisateur = '. $userId);// on recup les disques du user, tableau d'objet
			foreach($disques as $disque) {
				$disque->occupation = DirectoryUtils::formatBytes($disque->getOccupation() / 100 * $disque->getQuota());
				$disque->occupationTotal = DirectoryUtils::formatBytes($disque->getQuota());

				$occupation = $disque->getOccupation();

				if($occupation <= 100 && $occupation > 80)
					$disque->progressStyle = 'danger';

				if($occupation <= 80 && $occupation > 50)
					$disque->progressStyle = 'warning';

				if($occupation <= 50 && $occupation > 10)
					$disque->progressStyle = 'success';

				if($occupation <= 10 && $occupation > 0)
					$disque->progressStyle = 'info';
			}


			$this->loadView('MyDisques/index.html', array('user' => $user, 'disques' => $disques));
		}
		else {
			$msg = new DisplayedMessage();
			$msg->setContent('Vous devez vous connecter pour avoir accès à cette ressource')
				->setType('danger')
				->setDismissable(false)
				->show($this);
			echo Auth::getInfoUser();
		}
	}

	public function update() {
		if(isset($_POST) && !empty($_POST)) {
			$error = false;

			if(empty($_POST['name'])) {
				echo '<div class="alert alert-danger">Le nom ne doit pas être vide</div>';
				$error = true;
			}

			if(!$error) {
				$user = Auth::getUser();
				$name = htmlspecialchars($_POST['name']);
				$createdAt = date('Y-m-d H:m:s');

				$disk = new Disque();
				$disk->setUtilisateur($user);
				$disk->setNom($name);
				$disk->setCreatedAt($createdAt);

				if(DAO::insert($disk, true)) {
					$cloud = $GLOBALS['config']['cloud'];
					$path = $cloud['root'] . $cloud['prefix'] . $user->getLogin() . '/' . $name;
					mkdir($path);

					header('Location: /RT-Cloud/Scan/show/'. DAO::$db->lastInserId());
					return false;
				}
			}
		}

		$this->loadView('MyDisques/create.html');
	}

	public function rename() {
		$valid_input = ['diskId', 'userId', 'name'];
		if(!empty($_POST)) {
			foreach($_POST as $input => $v) {
				if(!in_array($input, $valid_input)) {
					//TODO Error
					return false;
				}
			}

			$user = Auth::getUser();
			$disk = DAO::getOne('disque', 'id = '. $_POST['diskId'] .'&& idUtilisateur = '. $_POST['userId']);
			$oldname = $disk->getNom();
			$disk->setNom($_POST['name']);

			$path = $GLOBALS['config']['cloud']['root'] . $GLOBALS['config']['cloud']['prefix'] . $user->getLogin() . '/';
			$req = rename($path . $oldname, $path . $_POST['name']);

			if(DAO::update($disk) && $req) {
				header('Location: /RT-Cloud/Scan/show/'. $_POST['diskId']);
				return false;
			}
			else
				echo '<div class="alert alert-danger">Une erreur est survenue, veuillez rééssayer ultérieurement</div>';
		}
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}

}