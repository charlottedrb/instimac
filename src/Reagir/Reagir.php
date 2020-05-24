<?php

/**
 * @author Léa Laffitte
 */

//espace de nom ou "namespace" correspond au nom du dossier Parent.
namespace Reagir;


use Database\Database;

class Reagir
{
    const TABLE = 'reagir';

    public $database = NULL;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }


    public function init()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS reagir(
            p_id   Int NOT NULL ,
            u_id   Int NOT NULL ,
            r_type Int NOT NULL
            ,CONSTRAINT reagir_PK PRIMARY KEY (p_id,u_id)
            ,CONSTRAINT reagir_photo_FK FOREIGN KEY (p_id) REFERENCES photo(p_id)
            ,CONSTRAINT reagir_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        return $this->database->exec($sql);
    }

    /**
     * retourne un tab avec le type et le nombre de reaction de ce type
     * @param $photoId
     * @return mixed
     */
    function getCount($photoId)
    {
        $sql = 'SELECT r_type, COUNT(*) AS nbrReaction FROM reagir WHERE p_id=? GROUP BY r_type';
    }

    public function set($typeReaction, $photoId, $utilisateurId)
    {
        $sql = 'SELECT * FROM reagir WHERE u_id=' . $u_id . 'p_id=' . $p_id;
    }

    public function delete($photoId, $utilisateurId)
    {
        return $this->database->delete(self::TABLE, ['u_id' => $utilisateurId, 'p_id' => $photoId]);
    }

    public function __destruct()
    {
        $this->database = NULL;
    }
}