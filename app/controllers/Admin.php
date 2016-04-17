<?php
use micro\orm\DAO;
class Admin extends \BaseController {

	public function index() {
		$count = (object)[];
		$count->all = (object)[];
		$count->today = (object)[];

		$count->all->user = DAO::count('utilisateur');
		$count->all->disk = DAO::count('disque');
		$count->all->tarif = DAO::count('tarif');
		$count->all->service = DAO::count('service');

		$count->today->user = DAO::count('utilisateur', 'DAY(createdAt) = DAY(NOW())');
		$count->today->disk = DAO::count('disque', 'DAY(createdAt) = DAY(NOW())');


		$this->loadView('Admin/index.html', ['count' => $count]);
	}

	public function user() {
		$users = DAO::getAll('utilisateur');
		foreach($users as $user) {
			$user->countDisk = DAO::count('disque', 'idUtilisateur = '. $user->getId());
			$user->disks = DAO::getAll('disque', 'idUtilisateur = '. $user->getId());
			$user->diskTarif = 0;

			foreach($user->disks as $disk) {
				$tarif = ModelUtils::getDisqueTarif($disk);
				if ($tarif != null)
					$user->diskTarif += $tarif->getPrix();
			}
		}

		$this->loadView('Admin/user.html', ['users' => $users]);
	}
}