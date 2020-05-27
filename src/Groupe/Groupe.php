<?php

/**
 * @author Laurine Capdeville
 */

namespace Groupe;

use Database\Database;
use Publication\Publication;

class Groupe
{
    const TABLE = 'groupes';

    public $database = NULL;
    public $id;
    public $nom;
    public $lieu;
    public $date;
    public $hide = FALSE;
    public $utilisateurId;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    /**
     * Initialise in bdd
     * @return bool
     */
    public function init()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS groupes(
        g_id            Int  Auto_increment  NOT NULL,
        g_nom           VARCHAR (50) NOT NULL,
        g_lieu          Varchar (255) NULL,
        g_date          Datetime NOT NULL,
        g_cacher        BOOLEAN NULL,
        u_id            INT NULL
	    ,CONSTRAINT tableau_PK PRIMARY KEY (g_id)
	    ,CONSTRAINT tableau_utilisateur_FK FOREIGN KEY (u_id) REFERENCES utilisateurs(u_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        return $this->database->exec($sql);
    }

    /**
     * Test the class
     * @return bool
     */
    public function test()
    {
        if ($this->set('IMAC1', 'COpernicassoulet', '2020-05-23 12:21:00', 1, TRUE) === FALSE) return FALSE;
        if ($this->set('Jeudimac', 'Trop de jus de fruits', '2020-05-23 12:21:00', 2, TRUE) === FALSE) return FALSE;
        if ($this->getMultiple() === FALSE) return FALSE;
        if ($this->getById($this->id) === FALSE) return FALSE;
        if ($this->update('IMAC1', 'Coperevolution', '2020-05-23 12:21:00', TRUE) === FALSE) return FALSE;
        if ($this->delete($this->id, $this->utilisateurId) === FALSE) return FALSE;

        return TRUE;
    }

    /**
     * @param $nom
     * @param $lieu
     * @param $date
     * @param $utilisateurId
     * @param bool $hide
     * @return bool
     */
    public function set($nom, $lieu, $date, $utilisateurId, $hide = FALSE)
    {
        $this->nom = $nom;
        $this->lieu = $lieu;
        $this->date = $date;
        $this->utilisateurId = $utilisateurId;
        $this->hide = $hide;
        $this->date = date('Y-m-d H:i:s');

        $this->database->insert(self::TABLE,
            [
                'g_nom',
                'g_lieu',
                'g_date',
                'g_cacher',
                'u_id',
            ],
            [
                $this->nom,
                $this->lieu,
                $this->date,
                $this->hide,
                $this->utilisateurId,
            ]);

        $this->database->where(['g_nom' => $this->nom, 'g_date' => $this->date, 'g_lieu' => $this->lieu]);
        $data = $this->database->select(self::TABLE, ['g_id']);

        if ($data !== FALSE && !empty($data)) {
            $data = $data[0];
            $this->id = (int)$data['g_id'];
            return TRUE;
        }
        return FALSE;
    }

    public function getMultiple($filters = [])
    {
        $results = [];

        $this->database->where($filters);
        $data = $this->database->select(self::TABLE, FALSE);

        if ($data !== FALSE && !empty($data)) {

            foreach ($data as $row) {
                $groupe = new Groupe($this->database);
                $groupe->id = (int)$row['g_id'];
                $groupe->date = $row['g_date'];
                $groupe->lieu = $row['g_lieu'];
                $groupe->hide = (boolean)$row['g_cacher'];
                $groupe->utilisateurId = (int)$row['u_id'];

                $results[] = $groupe;
            }
            return $results;
        }
        return FALSE;
    }

    /**
     * @param bool $id
     * @return bool
     */
    public function getById($id = FALSE)
    {
        if ($id) $this->id = (int)$id;

        $this->database->where(['g_id' => $this->id]);
        $data = $this->database->select(self::TABLE);
        if ($this->delete(1, 1) === FALSE) return FALSE;

        if ($data !== FALSE & !empty($data)) {

            $data = $data[0];

            $this->id = $data['g_id'];
            $this->nom = $data['g_nom'];
            $this->lieu = $data['g_nom'];
            $this->date = $data['g_date'];
            $this->hide = $data['g_cacher'];
            $this->utilisateurId = $data['u_id'];

            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $groupeId
     * @param $utilisateurId
     * @return array|bool
     */
    public function delete($groupeId, $utilisateurId)
    {
        return $this->database->delete(self::TABLE, ['u_id' => $utilisateurId, 'g_id' => $groupeId]);
    }

    /**
     * @param bool $nom
     * @param bool $lieu
     * @param bool $date
     * @param bool $hide
     * @return bool
     */
    public function update($nom = FALSE, $lieu = FALSE, $date = FALSE, $hide = FALSE)
    {
        $this->nom = $nom;
        $this->lieu = $lieu;
        $this->date = $date;
        $this->hide = $hide;

        $values = [
            'g_nom' => $this->nom,
            'g_lieu' => $this->lieu,
            'g_date' => $this->date,
            'g_cacher' => $this->hide,
        ];

        $where = ['g_id' => $this->id];

        if ($this->database->update(self::TABLE, $values, $where) === FALSE) return FALSE;
        return TRUE;
    }

    public function __destruct()
    {
        $this->database = NULL;
    }

}