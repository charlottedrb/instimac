<?php

/**
 * @author Laurine Capdeville
 */

namespace Publication;

use Database\Database;

class Publication
{
    const TABLE = 'photos';

    public $database = NULL;
    public $id;
    public $date;
    public $lieu;
    public $description;
    public $hide = FALSE;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    public function init()
    {
        //opération de création de la.es table.s dans la base de données
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . '(
            p_id INT NOT NULL AUTO_INCREMENT, 
            p_date DATETIME NOT NULL, 
            p_lieu VARCHAR(70),
            p_description VARCHAR(255),
            p_hide BOOLEAN, 
            PRIMARY KEY (p_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        return $this->database->exec($sql);
    }

    public function test()
    {
        if ($this->set('Copernic H représente', 'Belle description, lisible en tout point', TRUE) === FALSE) return FALSE;
        if ($this->getById() === FALSE) return FALSE;
        if ($this->getById($this->id) === FALSE) return FALSE;
        if ($this->update() === FALSE) return FALSE;
        if ($this->delete() === FALSE) return FALSE;
        return TRUE;
    }

    public function set($lieu = FALSE, $description = FALSE, $hide = FALSE)
    {
        $this->date = date('Y-m-d H:i:s');

        if ($lieu) $this->lieu = $lieu;
        if ($description) $this->description = $description;
        if ($hide) $this->hide = $hide;

        $fields = [
            'p_date',
            'p_lieu',
            'p_description',
            'p_hide',
        ];

        $values = [
            $this->date,
            $this->lieu,
            $this->description,
            $this->hide,
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
            return FALSE;
        }
        return FALSE;
    }

    public function getById($id = FALSE)
    {
        if ($id) $this->id = (int)$id;

        $this->where(['p_id' => $this->id]);
        $data = $this->database->select(self::TABLE, ['p_date', 'p_description', 'p_hide']);

        if ($data !== FALSE && !empty($data)) {
            $data = $data[0];
            $this->date = $data['p_date'];
            $this->lieu = $data['p_lieu'];
            $this->description = $data['p_description'];
            $this->hide = $data['p_hide'];
            return TRUE;
        }
        return FALSE;
    }

    public function getMultiple($filters = [])
    {
        $results = [];

        $data = $this->database->select(self::TABLE, FALSE, $filters);

        if ($data !== FALSE && !empty($data)) {

            foreach ($data as $row) {

                $publication = new Publication($this->database);
                $publication->id = (int)$row['p_id'];
                $publication->date = $row['p_date'];
                $publication->lieu = $row['p_lieu'];
                $publication->description = $row['p_description'];
                $publication->hide = (boolean)$row['p_hide'];

                $results[] = $publication;
            }
            return $results;
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

    public function __destruct()
    {
        $this->database = NULL;
    }

}