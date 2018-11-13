<?php

namespace Core;


class Autoloader
{

    /**
     * Enregistrement de l'autoloader
     */
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclusion du fichier de la classe
     *
     * @param $class string Nom de la classe a autocharger
     */
    static function autoload($class)
    {
        if (strpos($class, __NAMESPACE__ . '\\') === 0) {
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require __DIR__ . '/' . $class . '.php';
        }
    }
}