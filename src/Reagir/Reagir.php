<?php

/**
 * @author Léa Laffitte
 */

//espace de nom ou "namespace" correspond au nom du dossier Parent.
namespace Reagir;


use Database\Database;

class Reagir
{
    const TABLE = 'reactions';

    public $database = NULL;
    public $count = 0;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    /**
     * Initialise en bdd
     * @return bool
     */
    public function init()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . '(
            r_id INT NOT NULL AUTO_INCREMENT, 
            p_id INT NOT NULL,
            u_id INT NOT NULL,
            r_type INT NOT NULL,
            CONSTRAINT PRIMARY KEY (r_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        return $this->database->exec($sql);
    }

    public function test()
    {
        if ($this->set(1, 3, 1) === FALSE) return FALSE;
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
     * Retourne un tab avec le type et le nombre de reaction de ce type
     * @param $photoId
     * @return boolean|int
     */
    function getCount($photoId)
    {
        $sql = 'SELECT r_type, COUNT(*) AS count FROM '.self::TABLE.' WHERE p_id=? GROUP BY r_type';
        $this->database->addParam($photoId);
        $data = $this->database->process($sql);

        if ($data !== FALSE && !empty($data)) {
            $data[0]['count'] = $this->count;
            return TRUE;
        }
        return FALSE;
    }

    public function __destruct()
    {
        $this->database = NULL;
    }
}