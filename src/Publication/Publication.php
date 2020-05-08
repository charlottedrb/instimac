<?php

/**
 * @author
 */

namespace Publication;

class Publication
{
    const TABLE = 'photos';
    public $database = NULL;
    public $id; 
    public $date = NULL; 
    public $lieu = NULL;
    public $description = NULL; 
    public $hide = TRUE;  

    public function __construct(&$database)
    {
        $this->database = $database;
    }

    public static function init(&$database)
    {
        //opération de création de la.es table.s dans la base de données
        $sql = 'CREATE TABLE IF NOT EXISTS' .self::TABLE. '(
            p_id INT NOT NULL AUTO_INCREMENT, 
            p_date DATETIME NOT NULL, 
            p_lieu VARCHAR(50),
            p_description VARCHAR(255),
            p_hide BOOLEAN NOT NULL, 
            PRIMARY KEY (p_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if($database->query($sql)){
            $sql = 'INSERT INTO ' .self::TABLE. ' (p_date, p_lieu, p_description, p_hide) VALUES (NOW(),' .$lieu. ',' .$description. ', TRUE)';
            //requete preparée
            $q = $this->database->prepare($sql);
            if($requete->execute()) {
                
            }
            if($database->query($sql) == FALSE) return FALSE;

            return TRUE; 
        }
        return FALSE;
    }

    public function get($id)
    {
        if($id) $this->id = (int)$id; 

        $requete = $this->database->prepare('SELECT p_date, p_lieu, p_description, p_hide FROM ' .self::TABLE. 'WHERE p_id=?');

        $requete->bindParam(1, $this->id, PDO::PARAM_INT);
        if($requete->execute()) {
            if($data = $requete->fetch()) {
                $this->date = $data['p_date'];
                $this->lieu = $data['p_lieu'];
                $this->description = $data['p_description'];
                $this->hide = $data['p_hide'];
                return TRUE;
            }
        }

        return FALSE;
    }

    public function set($id = false)
    {
        if($id) $this->id = (int)$id; 

        $requete = $this->database->prepare('UPDATE ' .self::TABLE. "SET p_date ='" .$this->date. "', p_lieu='" .$this->lieu. "', p_description='" .$this->description. "', p_hide='" .$this->hide. "' WHERE p_id=?");

        $requete->bindParam(1, $this->id, PDO::PARAM_INT);
        if($requete->execute()) {
            if($data = $requete->fetch()) {
                $this->date = $data['p_date'];
                $this->lieu = $data['p_lieu'];
                $this->description = $data['p_description'];
                $this->hide = $data['p_hide'];
                return TRUE;
            }
        }
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