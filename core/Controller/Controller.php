<?php

namespace Core\Controller;

class Controller
{
    Const URI = "http://leboncoin.local/";
    protected $loader;
    protected $twig;

    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(ROOT . '/app/Views');
        $this->twig = new \Twig_Environment($this->loader);
        $this->twig->addGlobal('session', $_SESSION);
    }

    public function apiClient($methode, $datas = [])
    {
        $api = self::URI . $methode;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($curl);
        curl_close($curl);
        return json_decode($return);
    }

}