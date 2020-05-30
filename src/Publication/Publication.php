<?php

/**
 * @author Laurine Capdeville
 */

namespace Publication;

use Database\Database;
use File\File;

class Publication
{
    const TABLE = 'photos';

    public $database = NULL;
    public $id;
    public $date;
    public $lieu;
    public $description;
    public $groupeId;
    public $photoId;
    public $utilisateurId;
    public $hide = FALSE;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    public function init()
    {
        //opération de création de la table dans la base de données
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . '(
            p_id INT NOT NULL AUTO_INCREMENT, 
            p_date DATETIME NOT NULL, 
            p_lieu VARCHAR(255),
            p_description VARCHAR(255) NULL,
            f_id INT NULL,
            u_id INT NULL,
            p_hide BOOLEAN NOT NULL, 
            g_id INT NULL,
            PRIMARY KEY (p_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        return $this->database->exec($sql);
    }

    public function test()
    {
        if ($this->set('Copernic H représente', 'Belle description, lisible en tout point', 2, TRUE) === FALSE) return FALSE;
        if ($this->set('Copernic H représente', 'Belle description, lisible en tout point', 3, TRUE) === FALSE) return FALSE;
        if ($this->set('Copernic H représente', 'Belle description, lisible en tout point', 4, TRUE) === FALSE) return FALSE;
        if ($this->getById() === FALSE) return FALSE;
        if ($this->getById($this->id) === FALSE) return FALSE;
        if ($this->update() === FALSE) return FALSE;
        if ($this->delete() === FALSE) return FALSE;
        return TRUE;
    }

    public function set($lieu, $description, $groupeId, $utilisateurId, $photoId = NULL, $hide = FALSE)
    {
        $this->date = date('Y-m-d H:i:s');
        $this->lieu = $lieu;
        $this->description = $description;
        $this->groupeId = $groupeId;
        $this->photoId = $photoId;
        $this->utilisateurId = $utilisateurId;
        $this->hide = $hide;

        $fields = [
            'p_date',
            'p_lieu',
            'p_description',
            'p_hide',
            'g_id',
            'u_id',
            'f_id',
        ];

        $values = [
            $this->date,
            $this->lieu,
            $this->description,
            $this->hide,
            $this->groupeId,
            $this->utilisateurId,
            $this->photoId,
        ];

        if ($this->database->insert(self::TABLE, $fields, $values) !== FALSE) {

            $where = [
                'p_date' => $this->date,
                'p_lieu' => $this->lieu,
                'p_description' => $this->description,
                'p_hide' => $this->hide,
            ];

            $data = $this->database->select(self::TABLE, ['p_id'], $where);

            if ($data !== FALSE && !empty($data)) {

                $data = $data[0];
                $this->id = (int)$data['p_id'];
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getById($id = FALSE)
    {
        if ($id) $this->id = (int)$id;

        $this->database->where(['p_id' => $this->id]);
        $data = $this->database->select(self::TABLE);

        if ($data !== FALSE && !empty($data)) {
            $data = $data[0];
            $this->date = $data['p_date'];
            $this->lieu = $data['p_lieu'];
            $this->description = $data['p_description'];
            $this->hide = $data['p_hide'];
            $this->photoId = $data['f_id'];
            $this->groupeId = $data['g_id'];
            $this->utilisateurId = $data['u_id'];
            return TRUE;
        }
        return FALSE;
    }

    public function update()
    {
        $values = [
            'p_date' => $this->date,
            'p_lieu' => $this->lieu,
            'p_description' => $this->description,
            'p_hide' => $this->hide,
            'f_id' => $this->photoId,
            'g_id' => $this->groupeId,
            'u_id' => $this->utilisateurId,
        ];

        $where = ['p_id' => $this->id];

        if ($this->database->update(self::TABLE, $values, $where) !== FALSE) return TRUE;
        return FALSE;
    }

    public function delete()
    {
        $where = ['p_id' => $this->id];
        if ($this->database->delete(self::TABLE, $where) !== FALSE) return TRUE;
        return FALSE;
    }

    public function getMultiple($groupeId = FALSE)
    {
        if ($groupeId) $this->groupeId = (int)$groupeId;

        $results = [];

        $sql = 'SELECT ' . self::TABLE . '.*, utilisateurs.u_id, utilisateurs.u_prenom, utilisateurs.u_nom FROM photos
        JOIN utilisateurs ON ' . self::TABLE . '.u_id = utilisateurs.u_id WHERE g_id=? ORDER BY p_date';

        $this->database->addParam($this->groupeId);
        $data = $this->database->process($sql);

        if ($data !== FALSE && !empty($data)) {

            $file = new File($this->database);

            foreach ($data as $row) {

                $results[] = [
                    'id' => (int)$row['p_id'],
                    'date' => $row['p_date'],
                    'lieu' => $row['p_lieu'],
                    'description' => $row['p_description'],
                    'photoURL' => $file->idToURL($row['f_id']),
                    'utilisateur' => [
                        'photoURL' => './img/default-user.jpg',
                        'nom' => $row['u_prenom'].' '.$row['u_nom'],
                    ],
                ];
            }
            return $results;
        }
    }

    public function __destruct()
    {
        $this->database = NULL;
    }
}