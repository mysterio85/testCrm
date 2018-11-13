<?php

namespace App\Model;


use Core\Model\Model;

class UserModel extends Model
{
    protected $table = "users";

    public function login()
    {
        return $this->query("SELECT * FROM $this->table WHERE login = '' AND password = md5()");
    }
}