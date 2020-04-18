<?php

/**
 * @author Grégoire Petitalot
 */
namespace Exemple;

use \PDO;

class Tache
{
    const TABLE = 'taches';

    public $database = NULL;
    public $id;
    public $name = NULL;
    public $date = NULL;
    public $hide = FALSE;

    public function __construct(&$database)
    {
        $this->database = $database;
    }

    /**
     * Création de la table en base de donnée
     * @param $database object PDO passé en réference
     * @return TRUE si ok | FALSE en cas d'échec
     */
    public static function init(&$database)
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . ' (
            t_id int(11) NOT NULL AUTO_INCREMENT,
            t_name varchar(255) NOT NULL,
            t_date datetime NOT NULL,
            t_hide tinyint(1) NOT NULL,
            PRIMARY KEY (t_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($database->query($sql))
        {
            for($i=0; $i < 20; $i++)
            {
                $sql = 'INSERT INTO '.self::TABLE.' (t_name, t_date, t_hide) VALUES ("Tache '.$i.'", NOW(), TRUE)';
                if ($database->query($sql) == FALSE ) return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Récupère la tâche dans la base données grâce à l'id
     * @param int $id facultatif, sinon prend puclic $id en param
     * @return TRUE si ok | FALSE en cas d'échec
     */
    public function getById($id = false)
    {
        //On transtype pour être sûr d'avoir du INT
        if ($id) $this->id = (int)$id;

        //On fait une requete préparée pour être sur d'éviter les injections SQL.
        $requete = $this->database->prepare('SELECT t_name, t_date, t_hide FROM ' . self::TABLE . ' WHERE t_id=?');

        //On ajoute les valeurs de nos paramètres à notre obejct PDO,
        // cela remplace les "?" par nos valeurs de manière sécurisé
        $requete->bindParam(1, $this->id, PDO::PARAM_INT);

        //on execute la requete préparée avec execute()
        if ($requete->execute()) {

            //si il ya une ligne dans les résultats on la stocke dans data et on met à jour notre objet
            if ($data = $requete->fetch()) {
                $this->name = $data['t_name'];
                $this->date = $data['t_date'];
                $this->hide = $data['t_hide'];
                return TRUE;
            }
        }
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

    //Appelé à chaque destruction d'un objet, unset(), fin de script, destruction
    public function __destruct()
    {
        $this->database = NULL;
    }

}