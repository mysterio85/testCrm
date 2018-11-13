<?php
/**
 * Created by PhpStorm.
 * User: essoa
 * Date: 13/11/2018
 * Time: 10:18
 */

namespace Core\Controller;


interface ControllerInterface
{
    /**
     * @Methode pour page d'accueil
     */
    public function index();

    /**
     * Methode pour page de creation
     */
    public function add();

    /**
     * Methode pour page de modification
     */
    public function edit();

    /**
     * Methode pour page de suppression
     */
    public function delete();

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function sanitize($data = []);

}