<?php

namespace App\Model;


use Core\Model\Model;

class AddresseModel extends Model
{
    protected $table = "addresses";

    /**
     * Méthode pour résupérer la liste toutes les adresses
     * @return array|bool|mixed|\PDOStatement
     */
    public function getAll()
    {
        return $this->query("SELECT * FROM $this->table");
    }

    /**
     * Méthode de récupération des adresses d'utilisateur
     * @param $idContact
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function getByContact($idContact)
    {
        return $this->query("SELECT * FROM $this->table WHERE idContact =$idContact");
    }


    /**
     * Méthode de récupération d'une adresse à partir de don Id
     * @param $idAdresse
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function getById($idAdresse)
    {
        return $this->findById($idAdresse);
    }
}