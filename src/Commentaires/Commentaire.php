<?php

/**
 * @author Léa Laffitte
 */

//espace de nom ou "namespace" correspond au nom du dossier Parent.
namespace Commentaires;


class Commentaires
{
    //Constantes en majuscule avec _ pour séparer
    const CONSTANTE_TROP_COLL = TRUE;

    //Nom de propriét en "camelCase"
    public $database = NULL;

    /*
    *CREATE TABLE commentaire(
    *    c_id     Int  Auto_increment  NOT NULL ,
    *    c_date   Datetime NOT NULL ,
    *    c_texte  Varchar (242) NOT NULL ,
    *    c_cacher Bool NOT NULL ,
    *    p_id     Int NOT NULL ,
    *    u_id     Int NOT NULL
	*   ,CONSTRAINT commentaire_PK PRIMARY KEY (c_id)
    *
	*   ,CONSTRAINT commentaire_photo_FK FOREIGN KEY (p_id) REFERENCES photo(p_id)
	*   ,CONSTRAINT commentaire_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id)
    *   )ENGINE=InnoDB;
    */

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
        $sql = 'CREATE TABLE IF NOT EXISTS commentaire(
                c_id     Int  Auto_increment  NOT NULL ,
                c_date   Datetime NOT NULL ,
                c_texte  Varchar (242) NOT NULL ,
                c_cacher Bool NOT NULL ,
                p_id     Int NOT NULL ,
                u_id     Int NOT NULL
               ,CONSTRAINT commentaire_PK PRIMARY KEY (c_id)
               ,CONSTRAINT commentaire_photo_FK FOREIGN KEY (p_id) REFERENCES photo(p_id)
               ,CONSTRAINT commentaire_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($database->query($sql))
        {
            return TRUE;
        }else
            return FALSE;
        }
        
    }

    private function creatTabBySQL($requetSQL) //retour un tableau si requet marche
    {
        $donnees = $database->query($requetSQL);
        if ($donnees == FALSE) {
            return FALSE;
        }

        $tabDonnee = $donnees->fetchAll();
        

        $donnees->closeCursor();
        return $tabDonnee;
    }

    public function getTabComPhotoDateCoissant($p_id)
    {
        $sql='SELECT * FROM commentaire WHERE p_id='.$p_id.'ORDER BY c_date';
        $tabComPhoto = creatTabBySQL($sql);

        return $tabComPhoto;
    }

    public function getTabComPhotoDateDecoissant($p_id)
    {
        $sql='SELECT * FROM commentaire WHERE p_id='.$p_id.'ORDER BY c_date DESC';
        $tabComPhoto = creatTabBySQL($sql);

        return $tabComPhoto;
    }

    public function getTabComUtilisateurDateCoissant($u_id)
    {
        $sql='SELECT * FROM commentaire WHERE u_id='.$u_id.'ORDER BY c_date';
        $tabComUt = creatTabBySQL($sql);

        return $tabComUt;
    }

    public function getTabComUtilisateurDateDecoissant($u_id)
    {
        $sql='SELECT * FROM commentaire WHERE u_id='.$u_id.'ORDER BY c_date DESC';
        $tabComUt = creatTabBySQL($sql);

        return $tabComUt;
    }

    public function ajoutCommentaire( $u_id, $p_id, $c_date, $c_texte)
    {
        $sql ='INSERT INTO commentaire (c_date, c_texte, c_cacher, p_id, u_id)
        VALUES (\''.$c_date.'\', \''.$c_texte.'\',\'FALSE\',\''.$p_id.'\',\''.$u_id.'\')';
        $database->query($sql) or die(print_r($bdd->errorInfo()));
        
    }

    public function suprimerCommentaire( $c_id)
    {
        $sql ='DELETE FROM commentaire WHERE c_id='.$c_id;
        $database->query($sql) or die(print_r($bdd->errorInfo()));
        
    }
    
    //Appelé à chaque destruction d'un objet, unset(), fin de script, destruction
    public function __destruct()
    {
        $this->database = NULL;
    }

}