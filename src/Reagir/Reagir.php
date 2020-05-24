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
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . '(
            r_id INT NOT NULL AUTO_INCREMENT, 
            p_id INT NOT NULL,
            u_id INT NOT NULL,
            r_type INT NOT NULL,
            CONSTRAINT reagir_PK PRIMARY KEY (p_id,u_id),
            CONSTRAINT reagir_photo_FK FOREIGN KEY (p_id) REFERENCES photo(p_id),
            CONSTRAINT reagir_utilisateur0_FK FOREIGN KEY (u_id) REFERENCES utilisateur(u_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        return $this->database->exec($sql);
    }

    public function test()
    {
        if ($this->set(1, 1, 1) === FALSE) return FALSE;
        if ($this->set(1, 1, 1) === FALSE) return FALSE;
        if ($this->set(1, 1, 1) === FALSE) return FALSE;
        if ($this->get(1) === FALSE) return FALSE;
        if ($this->delete(1, 1) === FALSE) return FALSE;
        return TRUE;
    }

    /**
     * Set une réaction dans la base de donnée
     * @param $typeReaction
     * @param $photoId
     * @param $utilisateurId
     * @return array|bool
     */
    public function set($typeReaction, $photoId, $utilisateurId)
    {
        return $this->database->insert(self::TABLE, ['p_id', 'u_id', 'r_type'], [$photoId, $utilisateurId, $typeReaction]);
    }

    /**
     * Supprime une réction dans la base de données
     * @param $photoId
     * @param $utilisateurId
     * @return array|bool
     */
    public function delete($photoId, $utilisateurId)
    {
        return $this->database->delete(self::TABLE, ['u_id' => $utilisateurId, 'p_id' => $photoId]);
    }

    /**
     * retourne un tab avec le type et le nombre de reaction de ce type
     * @param $photoId
     * @return mixed
     */
    function getCount($photoId)
    {
        $sql = 'SELECT r_type, COUNT(r_id) AS nbrReaction FROM reagir WHERE p_id=? GROUP BY r_type';
        $this->database->addParam($photoId);
        $this->database->process($sql);
    }

    public function __destruct()
    {
        $this->database = NULL;
    }
}