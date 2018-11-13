<?php

namespace Core\Auth;

use Core\Database\Database;

class Auth
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
    public function login($login, $password)
    {
        $user = $this->database->prepare('SELECT * FROM users WHERE login = ?',
            [$login], null, true);
        if ($user) {
            if ($user->password === md5($password)) {
                $_SESSION['auth'] = ["id"    => $user->id,
                                     "login" => $user->login,
                                     "email" => $user->email
                ];
                return true;
            }
        }
        return false;
    }

    /**
     * Méthode pour vérifier si l'utilisateur est connecté
     * @return bool
     */
    public function logged()
    {
        return isset($_SESSION['auth']);
    }

}