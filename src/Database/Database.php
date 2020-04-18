<?php

namespace Database;

use \PDO;

class Database
{

    const HOST = 'localhost';
    const DATABASE = 'dev';
    const USER = 'root';
    const PASSWORD = '';
    const ENCODAGE = 'utf8';

    //PDO Objet
    public $connection = NULL;

    public function connect()
    {
        try {
            $this->connection = new PDO('mysql:dbname=' . self::DATABASE . ';host=' . self::HOST, self::USER, self::PASSWORD /* , array(PDO::ATTR_PERSISTENT => true ) */);

        } catch (PDOException $e) {

            echo 'Connexion échouée : ' . $e->getMessage();
        }

        if ($this->connection == null || !$this->connection) exit;
        else {

            if ($this->connection->query('SET NAMES "' . self::ENCODAGE . '"') !== FALSE) {
                return true;
            }
            return false;
        }
    }

    public function __destruct()
    {
        $this->database = NULL;
    }

}