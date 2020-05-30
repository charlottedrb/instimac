<?php

/**
 * @author Léa Laffitte
 */

//espace de nom ou "namespace" correspond au nom du dossier Parent.
namespace Commentaire;


use Database\Database;

class Commentaire
{
    const TABLE = 'commentaires';

    public $database = NULL;


    public $id;
    public $created;
    public $texte;
    public $photoId;
    public $utilisateurId;
    private $hide;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    /* Initialise la fonctionnalité dans la base de donnée
     * sera appelé dans le fichier "/src/init.php"
     */
    public function init()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . '(
                c_id     Int  Auto_increment  NOT NULL ,
                c_date   Datetime NOT NULL ,
                c_texte  Varchar (242) NOT NULL ,
                c_cacher Bool NOT NULL ,
                p_id     Int NOT NULL,
                u_id     Int NOT NULL
                ,CONSTRAINT commentaire_PK PRIMARY KEY (c_id)
               /*,CONSTRAINT commentaire_photo_FK FOREIGN KEY (p_id) REFERENCES photo(p_id)
               ,CONSTRAINT commentaire_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id) */
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($this->database->exec($sql)) return TRUE;
        return FALSE;
    }

    public function getTabComPhotoDateCoissant($photoId)
    {
        $sql = 'SELECT commentaires.c_id AS c_id, 
               commentaires.c_date AS c_date, 
               commentaires.c_texte AS c_texte, 
               commentaires.c_cacher AS c_cacher, 
               commentaires.p_id AS p_id, 
               commentaires.u_id AS u_id, 
               utilisateurs.u_nom AS u_nom  
        FROM commentaires JOIN utilisateurs ON commentaires.u_id = utilisateurs.u_id WHERE p_id=? ORDER BY c_date';

        $this->database->addParam($photoId);
        $data = $this->database->process($sql);

        $commentaires = [];

        if ($data !== FALSE && !empty($data)) {
            foreach ($data as $value) {
                $commentaires[] = [
                    'id' => $data['c_id'],
                    'date' => $data['c_date'],
                    'contenu' => $data['c_texte'],
                    'utilisateur' => [
                        'id' => $data['u_id'],
                        'nom' => $data['u_nom'],
                    ]
                ];
            }
        }

        return $commentaires;
    }

    public function set($u_id, $p_id, $c_date, $c_texte)
    {
        $fields = ['c_date', 'c_texte', 'c_cacher', 'p_id', 'u_id'];
        $values = [date('Y-m-d H:i:s'), $c_texte, FALSE, $p_id, $c_texte];

        if ($this->database->insert(self::TABLE, $fields  ,$values  ) === FALSE) return FALSE;
        return TRUE;
    }

    public function delete($commentaireId)
    {
        return $this->database->delete(self::TABLE, ['c_id' => $commentaireId]);
    }

    public function __destruct()
    {
        $this->database = NULL;
    }

    public function afficher($tabCom)
    {
        $affichage = '<div class="commentaire">';
        if ($tabCom == FALSE) {
            $affichage .= 'il n\'y a pas de commentaire';
        } else {
            foreach ($tabCom as $commentaire) {
                if ($commentaire['c_cacher'] == FALSE) {
                    $affichage .=
                        '<div class="nomUtilisateurCommentaire">' . $commentaire['u_nom'] . '</div>'
                        . '<div class="dateCommentaire">' . $commentaire['c_date'] . '</div>'
                        . '<div class="texteCommentaire">' . $commentaire['c_texte'] . '</div>';
                }
            }
        }

        $affichage .= '</div>';
        echo $affichage;
    }

}