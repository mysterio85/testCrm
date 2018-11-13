<?php

namespace Core\Api;

class Api
{

    public $_request;
    public $_content_type = "application/json";
    public $_methode = "";
    public $_code = 200;


    public function __construct()
    {
        $this->inputs();
    }


    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
        if ((int)method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            $this->response('', 404);
        }
    }

    /**
     * @return mixed
     */
    private function getStatusMessage()
    {
        $status = [
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            406 => 'Not Acceptable',
            404 => 'Not Found',
            500 => 'Internal Server Error'
        ];

        return ($status[$this->_code]) ? $status[$this->_code] : $status[500];

    }

    /**
     * Récupération de la méthode
     *
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }


    /**
     * Récupération des donnés envoyées
     */
    public function inputs()
    {
        switch ($this->getRequestMethod()) {
            case "POST":
                $this->_request = $this->cleanInputs($_POST);
                break;
            case "GET":
            case "DELETE":
                $this->_request = $this->cleanInputs($_GET);
                break;
            case "PUT":
                parse_str(file_get_contents("php://input"), $this->_request);
                $this->_request = $this->cleanInputs($this->_request);
                break;
            default:
                $this->response('', 406);
                break;
        }

    }

    public function response($data, $status)
    {
        $this->_code = ($status) ? $status : 200;
        $this->setHeader();
        echo $data;
        exit;
    }


    /**
     * Mise à jour de l'entete
     */
    private function setHeader()
    {
        header("HTTP/1.1 " . $this->_code . " " . $this->getStatusMessage());
        header("Content-Type:" . $this->_content_type);

    }

    private function cleanInputs($data)
    {
        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $clean_input = trim($data);
        }
        return $clean_input;
    }


}