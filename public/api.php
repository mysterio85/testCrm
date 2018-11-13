<?php

require_once("../core/Api/Api.php");

class Api extends \Core\Api\Api
{

    public function palindrome()
    {
        if ($this->getRequestMethod() != "POST") {
            $this->response('', 406);
        }

        $name = $this->_request['name'];
        if ($name) {
            if (strtoupper($name) == strrev(strtoupper($name))) {
                $this->response($this->json(["response" => true]), 200);
            } else {
                $this->response($this->json(["response" => false]), 200);
            }
        }
    }

    /**
     * Vérification du format de l'email
     */
    public function email()
    {
        if ($this->getRequestMethod() != "POST") {
            $this->response('', 406);
        }
        $email = $this->_request['email'];
        if ($email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->response($this->json([
                    "response" => true,
                    "message"  => "L'email est au bon format"
                ]), 200);
            } else {
                $this->response($this->json([
                    "response" => false,
                    "message"  => "Le format de l'email n'est pas correct"
                ]), 200);
            }
        }
    }

    /**
     * Encodage des données en json
     *
     * @param $data
     *
     * @return string
     */
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }

    }

}

$api = new Api();
$api->processApi();