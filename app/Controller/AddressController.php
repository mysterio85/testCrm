<?php

namespace App\Controller;


use Core\Controller\ControllerInterface;

class AddressController extends AppController implements ControllerInterface
{

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Addresse');
        $this->loadModel('Contact');

    }


    /**
     * Affichage de la liste des adresses d'un Utilisateur
     */
    public function index()
    {
        $idContact = intval($_GET['id']);
        $contact = $this->Contact->findById($idContact);
        $address = $this->Addresse->getByContact($idContact);
        echo $this->twig->render('addresslist.html.twig', [
            'addresses' => $address,
            'idContact' => $idContact,
            'contact'   => $contact
        ]);

    }

    /**
     * Ajout d'adresse pour un contact
     */
    public function add()
    {
        $error = false;
        $id = intval($_GET['id']);

        if (!empty($_POST)) {

            $response = $this->sanitize($_POST);

            if ($response["response"]) {

                $idContact = $response['idContact'];
                $result = $this->Addresse->create([
                    'number'     => $response['number'],
                    'city'       => $response['city'],
                    'country'    => $response['country'],
                    'postalCode' => $response['postalCode'],
                    'street'     => $response['street'],
                    'idContact'  => $response['idContact']
                ]);

                if ($result) {
                    header("Location: /address.index/$idContact");
                } else {
                    $error = true;
                    $this->twig->render('addressadd.html.twig',
                        ["idContact" => $id,'error' => $error]);
                }
            } else {
                $error = true;
                $this->twig->render('addressadd.html.twig',
                    ["idContact" => $id,'error' => $error]);

            }

        }
        echo $this->twig->render('addressadd.html.twig',
            ["idContact" => $id,'error' => $error]);

    }

    /**
     * Modification d'une adresse d'un contact
     */
    public function edit()
    {
        $error = false;
        $id = intval($_GET['id']);
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);


            if ($response["response"]) {
                $addresse = $this->Addresse->findById($id);
                $result = $this->Addresse->update($id,
                    [
                        'number'     => $response['number'],
                        'city'       => $response['city'],
                        'country'    => $response['country'],
                        'postalCode' => $response['postalCode'],
                        'street'     => $response['street'],
                    ]);
                if ($result) {
                    header("Location: /address.index/$addresse->idContact");
                }else {
                    $error = true;
                    $this->twig->render('addressadd.html.twig',
                        ["idContact" => $id,'error' => $error]);

                }
            } else {

                $error = true;
                $this->twig->render('addressadd.html.twig',
                    ["idContact" => $id,'error' => $error]);

            }
        }
        $data = $this->Addresse->findById($id);
        echo $this->twig->render('addressadd.html.twig',
            [
                'data'      => $data,
                "idContact" => $data->idContact
            ]);

    }

    /**
     * Supression d'une adresse d'un contact
     */
    public function delete()
    {
        $id = intval($_GET['id']);
        $addresse = $this->Addresse->findById($id);
        if ($addresse) {
            $result = $this->Addresse->delete($id);
            if ($result) {
                header("Location: /address.index/$addresse->idContact");
            }
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

        $number = $_POST['number'];
        $city = strtoupper($_POST['city']);
        $country = strtoupper($_POST['country']);
        $postalCode = $_POST['postalCode'];
        $street = strtoupper($_POST['street']);
        $idContact = intval($_POST['idContact']);


        if ($number && $city && $country && $postalCode && $street
            && $idContact
        ) {
            return [
                'response'   => true,
                'number'     => $_POST['number'],
                'city'       => strtoupper($_POST['city']),
                'country'    => strtoupper($_POST['country']),
                'postalCode' => $_POST['postalCode'],
                'street'     => strtoupper($_POST['street']),
                'idContact'  => $_POST['idContact']
            ];
        } else {
            return ['response' => false];
        }

    }
}