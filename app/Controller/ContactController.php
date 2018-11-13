<?php

namespace App\Controller;


use Core\Controller\ControllerInterface;

class ContactController extends AppController implements ControllerInterface
{

    protected $userId;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Contact');
        $this->userId = $_SESSION['auth']['id'];
    }

    /**
     * Affichage de la liste des contacts de l'utilisateur connecté
     */
    public function index()
    {
        $contacts = $this->Contact->getContactByUser($this->userId);
        echo $this->twig->render('index.html.twig', ['contacts' => $contacts]);

    }

    /**
     * Ajout d'un contact
     */
    public function add()
    {
        $error = false;
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);
            if ($response["response"]) {
                $result = $this->Contact->create([
                    'nom'    => $response['nom'],
                    'prenom' => $response['prenom'],
                    'email'  => $response['email'],
                    'userId' => $this->userId
                ]);
                if ($result) {
                    header('Location: /contact.index');
                }
            } else {
                $error = true;
                $this->twig->render('add.html.twig', ['error' => $error]);
            }

        }
        echo $this->twig->render('add.html.twig', ['error' => $error]);

    }

    /**
     * Modification d'un contact
     */
    public function edit()
    {
        $error = false;
        $id = intval($_GET['id']);
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);

            if ($response["response"]) {
                $result = $this->Contact->update($id,
                    [
                        'nom'    => $response['nom'],
                        'prenom' => $response['prenom'],
                        'email'  => $response['email']
                    ]);
                if ($result) {
                    return $this->index();
                }
            } else {
                $error = true;
                $this->twig->render('add.html.twig', ['error' => $error]);
            }
        }
        $data = $this->Contact->findById($id);
        echo $this->twig->render('add.html.twig',
            ['data' => $data, 'error' => $error]);

    }

    /**
     * Suppression d'un contact
     */
    public function delete()
    {
        $result = $this->Contact->delete($_GET['id']);
        if ($result) {
            header('Location: /contact.index');
        }

    }

    /**
     * Vérifie les contrainte d'enregistrement
     *
     * @param array $data
     *
     * @return array
     */
    public function sanitize($data = [])
    {
        $nom = ucfirst($_POST['nom']);
        $prenom = ucfirst($_POST['prenom']);
        $email = strtolower($_POST['email']);

        $isPalindrome = $this->apiClient('palindrome', ['name' => $nom]);
        $isEmail = $this->apiClient('email', ['email' => $email]);
        if ((!$isPalindrome->response) && $isEmail->response && $prenom) {
            return [
                'response' => true,
                'email'    => $email,
                'prenom'   => $prenom,
                'nom'      => $nom
            ];
        }

    }
}