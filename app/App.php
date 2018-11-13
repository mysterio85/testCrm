<?php

use Core\Config;
use Core\Database\Database;

class App
{

    private $db_instance;
    private static $_instance;

    public static function load()
    {

        session_start();
        require ROOT . '/app/Autoloader.php';
        App\Autoloader::register();
        require ROOT . '/core/Autoloader.php';
        Core\Autoloader::register();
        require ROOT . '/vendor/autoload.php';
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }


    public function getModel($name)
    {

        $name = '\\App\\Model\\' . ucfirst($name) . 'Model';
        return new $name($this->getDatabase());
    }


    public function getDatabase()
    {

        $config = Config::getInstance(ROOT . '/config/config.php');
        if (is_null($this->db_instance)) {
            $this->db_instance = new Database($config->get('db_name'),
                $config->get('db_user'), $config->get('db_pass'),
                $config->get('db_host'));
        }
        return $this->db_instance;
    }
}