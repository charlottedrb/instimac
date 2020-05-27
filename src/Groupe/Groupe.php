<?php

/**
 * @author Laurine Capdeville
 */

namespace Groupe;

class Groupe
{
    public $database = NULL;

    public function __construct(&$database)
    {
        $this->database = $database;
    }

    public static function init(&$database)
    {
        $sql = 'CREATE TABLE tableau(
        t_id            Int  Auto_increment  NOT NULL ,
        t_nom           Varchar (50) NOT NULL ,
        t_description   Varchar (200) NOT NULL ,
        t_date_creation Datetime NOT NULL ,
        t_cacher        Bool NOT NULL ,
        u_id            Int NOT NULL
	    ,CONSTRAINT tableau_PK PRIMARY KEY (t_id)

	    ,CONSTRAINT tableau_utilisateur_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($database->query($sql))
        {
            return TRUE;
        }else
        {
            return FALSE;
        }
        return FALSE;
    }

    //retour un tableau si requet marche
    private function creatTabBySQL($requetSQL)
    {
        $donnees = $database->query($requetSQL);
        if ($donnees == FALSE) {
            return FALSE;
        }

        $tabDonnee = $donnees->fetchAll();


        $donnees->closeCursor();
        return $tabDonnee;
    }


    public function getGroupe($t_id)
    {
        $sql='SELECT * FROM tableau WHERE t_id='.$t_id;
        $tabGroupe = creatTabBySQL($sql);

        return $tabGroupe;
    }

    public function ajoutCroupe($t_nom,$t_description,$t_date_creation,$u_id)
    {
        $sql ='INSERT INTO commentaire (t_nom, t_description, t_date_creation, t_cacher, u_id)
            VALUES (\''.$t_nom.'\', \''.$t_description.'\', \''.$t_date_creation.'\',\'FALSE\',\''.$u_id.'\')';
        $database->query($sql) or die(print_r($bdd->errorInfo()));

    }

    public function update()
    {
        return FALSE;
    }

    public function delete($t_id)
    {
        $sql='SELECT * FROM tableau WHERE t_id='.$t_id;
        $donnees =$database->query($sql);
        if ($donnees != FALSE)
        {
            $sql ='DELETE FROM tableau WHERE t_id='.$t_id;
            $database->query($sql) or die(print_r($bdd->errorInfo()));
        }
    }

    public function __destruct()
    {
        $this->database = NULL;
    }

}