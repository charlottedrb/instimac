<?php

/**
 * @author
 */

namespace Publication;

class Publication
{
    public $database = NULL;

    public function __construct(&$database)
    {
        $this->database = $database;
    }

    public static function init(&$database)
    {
        //opération de création de la.es table.s dans la base de données
        CREATE TABLE photo 
        (
            p_id INT [PRIMARY_KEY] [AUTO_INCREMENT], 
            p_date DATETIME, 
            p_lieu VARCHAR(50),
            p_description VARCHAR(255),
            p_cacher BOOLEAN
        );
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