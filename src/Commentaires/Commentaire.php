<?php

/**
 * @author Léa Laffitte
 */

//espace de nom ou "namespace" correspond au nom du dossier Parent.
namespace Commentaires;


use Database\Database;

class Commentaires
{
    public $id;
    public $idUt;
    public $idPhoto;
    public $date;
    public $texte;
    public $estCache;

    //Nom de propriét en "camelCase"
    public $database = NULL;

    public function __construct(Database &$database)
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
    
        ,CONSTRAINT commentaire_publication_FK FOREIGN KEY (p_id) REFERENCES publication(p_id)
        ,CONSTRAINT commentaire_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($database->exec($sql)) return TRUE;
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

    public function getTabComPhotoDateCoissant($p_id)
    {
        $sql='SELECT commentaire.c_id AS c_id, commentaire.c_date AS c_date, commentaire.c_texte AS c_texte, commentaire.c_cacher AS c_cacher, commentaire.p_id AS p_id, commentaire.u_id AS u_id, utilisateur.u_nom AS u_nom  FROM commentaire JOIN utilisateur ON commentaire.u_id = utilisateur.u_id WHERE p_id='.$p_id.'ORDER BY c_date';
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
        $sql='SELECT *  FROM commentaire WHERE u_id='.$u_id.'ORDER BY c_date';
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


    public function ajout()
    {
        if($this->$idUt=== NULL ||$this->$idPhoto === NULL||$this->$texte === NULL)
        {
            return FALSE;
        }
        $this->$date = date('Y-m-d h:i:s');
        $this->$estCache = FALSE;
        $sql ='INSERT INTO commentaire (c_date, c_texte, c_cacher, p_id, u_id)
            VALUES (\''.$this->$date.'\', \''.$this->$texte.'\',\''.$this->$estCache.'\',\''.$this->$idPhoto.'\',\''.$this->$idUt.'\')';

        //TODO recupérer id pour le metre ds id
        return $database->query($sql);

        
    }

    public function suprimerCommentaire( $c_id, $p_id)
    {
        $sql='SELECT * FROM commentaire AS com JOIN publication AS pub ON com.p_id= pub.p_id WHERE com.c_id=\''.$c_id.'\' AND ( com.p_id=\''.$p_id.'\' OR pub.p_id=\''.$p_id.'\')' ;
        $donnees =$database->query($sql);
        if ($donnees == FALSE) 
        {
            return false;
        }
        $sql ='DELETE FROM commentaire WHERE c_id='.$c_id;
        return $database->query($sql);
    }
    
    private function affTabCom($tabCom)
    {
        $affichage = '<div class="commentaire">';
        if($tabCom== FALSE)
        {
            $affichage .='il n\'y a pas de commentaire';
        }else
        {
            foreach ($tabCom as $commentaire) {
                if($commentaire['c_cacher']==FALSE){
                    $affichage .= 
                        '<div class="nomUtilisateurCommentaire">'.$commentaire['u_nom'].'</div>'
                        .'<div class="dateCommentaire">'.$commentaire['c_date'].'</div>'
                        .'<div class="texteCommentaire">'.$commentaire['c_texte'].'</div>';
                }
            }
        }

        $affichage .= '</div>';
        echo $affichage;
    }

    public function affComPhoto($p_id)
    {
        $tabCom = getTabComPhotoDateCoissant($p_id);
        affTabCom ($tabCom);
       
    }

    //Appelé à chaque destruction d'un objet, unset(), fin de script, destruction
    public function __destruct()
    {
        $this->database = NULL;
    }

}