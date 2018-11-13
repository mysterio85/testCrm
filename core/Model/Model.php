<?php

namespace Core\Model;

use Core\Database\Database;

abstract class Model
{

    protected $model;
    protected $database;

    public function __construct(Database $database)
    {

        $this->database = $database;

        if (is_null($this->model)) {
            $tab = explode('\\', get_class($this));
            $class = end($tab);
            $this->model = strtolower(str_replace('Model', '', $class)) . 's';
        }
    }

    public function getAll()
    {
        return $this->query('SELECT * FROM ' . $this->model);
    }

    public function findById($id)
    {
        return $this->query("SELECT * FROM {$this->model} WHERE id = ?", [$id],
            true);
    }

    public function query($statement, $attributes = null, $one = false)
    {
        if ($attributes) {
            return $this->database->prepare(
                $statement,
                $attributes,
                null,
                $one
            );
        } else {
            return $this->database->query(
                $statement,
                null,
                $one
            );
        }
    }

    /**
     * TODO: possibilité de refactorer
     * @param $fields
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function create($fields)
    {
        $fields = $this->cleanInputs($fields);
        $sql_parts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $sql_part = implode(', ', $sql_parts);
        return $this->query("INSERT INTO {$this->table} SET $sql_part",
            $attributes, true);
    }

    public function update($id, $fields)
    {
        $fields = $this->cleanInputs($fields);
        $sql_parts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sql_part = implode(', ', $sql_parts);
        return $this->query("UPDATE {$this->table} SET $sql_part WHERE id = ?",
            $attributes, true);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id],
            true);
    }

    /**
     * Nétoyer les données postées
     *
     * @param $data
     *
     * @return array|string
     */
    private function cleanInputs($data)
    {
        $clean_input = [];
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