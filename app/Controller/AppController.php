<?php

namespace App\Controller;

use \Core\Auth\Auth;
use \App;
use Core\Controller\Controller;

class AppController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $app = App::getInstance();
        $auth = new Auth($app->getDatabase());
        if (!$auth->logged()) {
            $this->forbidden();
        }
    }


    /**
     * Méthode de redirection d'un utilisateur vers la page de login
     */
    protected function forbidden()
    {
        header('HTTP/1.0 403 Forbidden');
        header('Location: user.login');
    }

    /**
     * Méthode de chargement de model
     * @param $model
     */
    protected function loadModel($model)
    {
        $this->$model = App::getInstance()->getModel($model);
    }

}