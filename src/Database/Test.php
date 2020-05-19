<?php

namespace Database;

use \PDO;
use \PDOException;

class Test extends Database
{
    const TABLE = 'test';

    public function init()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . ' (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, nom VARCHAR(100) NULL )';
        if ($this->process($sql)) return TRUE;

        /*
        if (!$this->insert(self::TABLE, ['nom'], [['gregoire'], ['Ambre']])) return FALSE;

        if (!$this->select(self::TABLE, ['id'])) return FALSE;
        if (!$this->select(self::TABLE, FALSE, TRUE)) return FALSE;

        if (!$this->update(self::TABLE, ['nom' => 'LAURA'], ['id' => 1])) return FALSE;

        if (!$this->delete(self::TABLE, ['id' => 1])) return FALSE;

        if (!$this->drop(self::TABLE)) return FALSE; */

        return TRUE;
    }

}
