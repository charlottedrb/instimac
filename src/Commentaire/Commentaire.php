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
                c_cacher Bool NOT NULL,
                p_id     Int NOT NULL,
                u_id     Int NOT NULL
                ,CONSTRAINT commentaire_PK PRIMARY KEY (c_id)
               /*,CONSTRAINT commentaire_photo_FK FOREIGN KEY (p_id) REFERENCES photo(p_id)
               ,CONSTRAINT commentaire_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id) */
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($this->database->exec($sql)) return TRUE;
        return FALSE;
    }

    public function getMultiple($photoId)
    {
        $sql = 'SELECT commentaires.c_id AS c_id, 
               commentaires.c_date AS c_date, 
               commentaires.c_texte AS c_texte, 
               commentaires.c_cacher AS c_cacher, 
               commentaires.p_id AS p_id, 
               commentaires.u_id AS u_id, 
               utilisateurs.u_prenom AS u_prenom,
               utilisateurs.u_nom AS u_nom  
        FROM commentaires JOIN utilisateurs ON commentaires.u_id = utilisateurs.u_id WHERE p_id=? ORDER BY c_date';

        $this->database->addParam($photoId);
        $data = $this->database->process($sql);

        $commentaires = [];

        if ($data !== FALSE && !empty($data)) {
            foreach ($data as $value) {
                $commentaires[] = [
                    'id' => $value['c_id'],
                    'date' => $value['c_date'],
                    'contenu' => $value['c_texte'],
                    'utilisateur' => [
                        'id' => $value['u_id'],
                        'nom' => $value['u_prenom'] . ' ' . $value['u_nom'],
                    ]
                ];
            }
        }

        return $commentaires;
    }

    public function set($u_id, $p_id, $c_texte)
    {
        $this->created = date('Y-m-d H:i:s');
        $this->utilisateurId = $u_id;
        $this->photoId = $p_id;
        $this->texte = $c_texte;

        $fields = [
            'c_date',
            'c_texte',
            'c_cacher',
            'p_id',
            'u_id'
        ];

        $values = [
            $this->created,
            $c_texte,
            FALSE,
            $p_id,
            $u_id
        ];

        if ($this->database->insert(self::TABLE, $fields, $values) !== FALSE) {

            $where = [
                'c_date' => $this->created,
                'c_texte' => $this->texte,
                'p_id' => $this->photoId,
            ];

            $this->database->where($where);
            $data = $this->database->select(self::TABLE);

            if (!empty($data)) {
                $this->id = (int)$data[0]['c_id'];
                return TRUE;
            }
        }
        return FALSE;
    }

    public function delete($commentaireId, $userId = FALSE)
    {
        if ($userId) {
            $this->utilisateurId = (int)$userId;
            return $this->database->delete(self::TABLE, ['c_id' => $commentaireId, 'u_id' => $this->utilisateurId]);
        } else {
            return $this->database->delete(self::TABLE, ['c_id' => $commentaireId]);
        }
    }

    public function __destruct()
    {
        $this->database = NULL;
    }

    public function afficher($commentaires)
    {
        $affichage = '<div class="commentaire">';
        if ($commentaires == FALSE) {
            $affichage .= 'il n\'y a pas de commentaire';
        } else {
            foreach ($commentaires as $commentaire) {
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