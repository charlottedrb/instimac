<?php

/**
 * @author
 */

namespace Publication;

class Publication {
    const TABLE = 'photos';
    public $database = NULL;
    public $id; 
    public $date = NULL; 
    public $lieu = NULL;
    public $description = NULL; 
    public $hide = TRUE;  

    public function __construct($database) {
        $this->database = $database;
    }

    public static function init($database) {
        //opération de création de la table dans la base de données
        $sql = 'CREATE TABLE IF NOT EXISTS' .self::TABLE. '(
            p_id INT NOT NULL AUTO_INCREMENT, 
            p_date DATETIME NOT NULL CURRENT_TIMESTAMP, 
            p_lieu VARCHAR(50),
            p_description VARCHAR(255),
            p_hide BOOLEAN NOT NULL, 
            PRIMARY KEY (p_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if($database->query($sql)) {
            return TRUE; 
        } else {
            return FALSE;
        }
    }

    public function get($id) {
        $sql = $this->database->prepare('SELECT * FROM ' .self::TABLE. ' WHERE p_id = :id');
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        if($sql->execute() === TRUE) {
            //retourne tableau de données
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //erreur : la requête n'a pas pu s'exécuter.
            return FALSE;
        }
    }

    //Fonction set qui permet de changer les valeurs d'un objet. Nécessité un ID, une VALUE et la COLONNE à modifier
    public function set($id, $value, $column){
        $sql = $this->database->prepare('UPDATE' .self::TABLE. " SET :column = :value WHERE p_id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->bindParam(':value', $value, PDO::PARAM_STR);
        $sql->bindParam(':value', $column, PDO::PARAM_STR);
        if($sql->execute() == TRUE){
            return TRUE; 
        } else {
            return FALSE;
        }
    }
    
    public function delete($id)
    {
        $sql = $this->database->prepare('DELETE FROM' .self::TABLE. 'WHERE id = :id');
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        if($sql->execute() == TRUE){
            return TRUE; 
        } else {
            return FALSE;
        }
    }

    public function __destruct()
    {
        $this->database = NULL;
    }


}

//requete preparée
// $sql = $this->database->prepare('INSERT INTO ' .self::TABLE. ' (p_lieu, p_description, p_hide) VALUES (:lieu, :desc, :hide)');
// if($sql->execute( array(
//     ':lieu' => $lieu,
//     ':desc' => $description,
//     ':id' => $id
// )));

