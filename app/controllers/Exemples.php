<?php

/**
 * Created by PhpStorm.
 * User: François
 * Date: 31/03/2016
 * Time: 14:11
 */
class Exemples extends BaseController{

    public function index() // requete sql
    {
        $services = \micro\orm\DAO::getAll("service");
        foreach ($services as $service){
            echo $service->getNom()."<br>";
        }
    }
    public function disques()
    {
        $disques=\micro\orm\DAO::getAll("Disque"); //appel de vue
        $this->loadView("Exemples\disques.html", array("disks"=>$disques));
    }

    public function sortedUsers ($field = "login", $order = "ASC"){ //tri requete avec tableau
        $users = \micro\orm\DAO::getAll("Utilisateur", "1=1 ORDER BY {$field} {$order}");
        $this->loadView("Exemples\sortedUser.html", array("users"=>$users, "field"=>$field, "order"=>$order));
    }
    public function displayService($id=null) {
        $isNew=false;
        if(isset($id)){
            $service=micro\orm\DAO::getOne("service", $id);

        }else{
            $service = new Service();
            $service->setNom("Nouveau service");
            $service->setPrix(200);
            $isNew = true;
        }
        $this->loadView("Exemples\displayService.html", array("service"=>$service,"isNew"=>$isNew));
    }
    public function serviceAdd($nom, $prix){
        $service = new Service();
        $service->setNom($nom);
        $service->setPrix($prix);
        if (\micro\orm\DAO::insert($service)) {
            echo "<div class='alert alert-success'>Service ajouté dans la base de données</div>";
        }}
    public function serviceDelete($id){
            $service=\micro\orm\DAO::getOne("Service",$id);
            if(isset($service)) {
                if (\micro\orm\DAO::delete($service)){
                    echo "<div class'alert alert-success'>Service {$service} supprimé.</div>";
                }
            }
            }


        }