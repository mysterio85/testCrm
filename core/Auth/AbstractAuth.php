<?php

namespace Core\Auth;

use Core\Database\Database;

abstract class AbstractAuth
{

    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Récupération de l'utilisateur courant avec retour de la session s'il est connecté
     * @return bool
     */
    public function getUserId()
    {
        if ($this->logged()) {
            return $_SESSION['auth'];
        }
        return false;
    }

    /**
     * Mathode de connexion de l'utilisateur a partir de son login et du mot de passe encoder en md5
     * @param $login
     * @param $password
     *
     * @return boolean
     */
    abstract protected function login($login, $password);

    /**
     * Méthode pour vérifier si l'utilisateur est connecté
     * @return bool
     */
    public function logged()
    {
        return isset($_SESSION['auth']);
    }

}