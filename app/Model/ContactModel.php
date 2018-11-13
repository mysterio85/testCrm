<?php

namespace App\Model;


use Core\Model\Model;

class ContactModel extends Model
{
    protected $table = "contacts";

    /**
     * Méthode de récupération des contacts d'un utilisateur
     * @param $idUser
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function getContactByUser($idUser)
    {
        return $this->query("SELECT * FROM $this->table WHERE userId = $idUser");
    }
}