<?php

/**
 * @author
 */

namespace Exemple;

class Template
{
    public $database = NULL;

    public function __construct(&$database)
    {
        $this->database = $database;
    }

    public static function init(&$database)
    {
        //opération de création de la.es table.s dans la base de données
        return FALSE;
    }

    public function get()
    {
        return FALSE;
    }

    public function set()
    {
        return FALSE;
    }

    public function update()
    {
        return FALSE;
    }

    public function delete()
    {
        return FALSE;
    }

    public function __destruct()
    {
        $this->database = NULL;
    }

}