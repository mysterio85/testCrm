<?php

namespace App\Controller;

use Core\Auth\Auth;
use \App;
use Core\Controller\Controller;

class UserController extends Controller
{

    public function login()
    {
        $errors = false;

        if (!empty($_POST)) {

            $auth = new Auth(App::getInstance()->getDatabase());

            if ($auth->login($_POST['login'], $_POST['password'])) {
                header('Location: contact.index');
            } else {
                $errors = true;
            }
        }
        echo $this->twig->render('login.html.twig', ['errors' => $errors]);
    }

}