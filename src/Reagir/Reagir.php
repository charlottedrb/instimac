<?php

/**
 * @author Léa Laffitte
 */

//espace de nom ou "namespace" correspond au nom du dossier Parent.
namespace Reagir;


class Reagir
{

    //Nom de propriét en "camelCase"
    public $database = NULL;

    //Appelé à chaque construction d'un objet
    public function __construct(&$database)
    {
        $this->database = $database;
    }

    /* Initialise la fonctionnalité dans la base de donnée
     * sera appelé dans le fichier "/src/init.php"
     */
    public static function init(&$database)
    {
        $sql = 'CREATE TABLE IF NOT EXISTS reagir(
            p_id   Int NOT NULL ,
            u_id   Int NOT NULL ,
            r_type Int NOT NULL
            ,CONSTRAINT reagir_PK PRIMARY KEY (p_id,u_id)
            ,CONSTRAINT reagir_photo_FK FOREIGN KEY (p_id) REFERENCES photo(p_id)
            ,CONSTRAINT reagir_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($database->query($sql))
        {
            return TRUE;
        }else
            return FALSE;
        }
        
    }

    private function creatTabBySQL($requetSQL) //retour un tableau si requete marche
    {
        $donnees = $database->query($requetSQL);
        if ($donnees == FALSE) {
            return FALSE;
        }

        $tabDonnee = $donnees->fetchAll();
        
        $donnees->closeCursor();
        return $tabDonnee;
    }

    function tabTypeEtNbrReactionPhoto($p_id) //retoune un tab avec le type et le nombre de reaction de ce type
    {
        $sql='SELECT r_type, COUNT(*) AS nbrReaction FROM reagir WHERE p_id='.$p_id.'GROUP BY r_type';
        $tabReaction = creatTabBySQL($sql);
        return $tabReaction;
    }

    public function ajoutReaction( $u_id, $p_id, $r_type)
    {
        $sql='SELECT * FROM reagir WHERE u_id='.$u_id.'p_id='.$p_id;
        $donnees =$database->query($sql);
        if ($donnees == FALSE) {
            $sql ='INSERT INTO reagir (p_id, u_id)
                 VALUES (\''.$p_id.'\',\''.$u_id.'\', \''.$r_type.'\')';
            $database->query($sql) or die(print_r($bdd->errorInfo()));
        }
        
        
    }

    public function suprimerReaction( $u_id, $p_id)
    {
        $sql='SELECT * FROM reagir WHERE u_id='.$u_id.'p_id='.$p_id;
        $donnees =$database->query($sql);
        if ($donnees != FALSE) {
            $sql ='DELETE FROM reagir WHERE u_id='.$u_id.'p_id='.$p_id;
            $database->query($sql) or die(print_r($bdd->errorInfo()));
        }
        
        $database->query($sql) or die(print_r($bdd->errorInfo()));
        
    }
    
    private function affReaction($p_id)
    {
        $tabReaction =tabTypeEtNbrReactionPhoto($p_id);
        $affichage = '<div class="reaction">'
        if($tabReaction!= FALSE)
        {
            foreach ($tabReaction as $reaction) {
                $affichage .= 
                    '<div class="typeReaction">'.$reaction['r_type'].'</div>'
                    .'<div class="nbrReaction">'.$reaction['nbrReaction'].'</div>'
                }
        }

        $affichage .= '</div>';
        echo $affichage;
    }

    //Appelé à chaque destruction d'un objet, unset(), fin de script, destruction
    public function __destruct()
    {
        $this->database = NULL;
    }

}