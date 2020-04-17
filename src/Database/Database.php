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
    public $pdo = NULL;

    public function connect()
    {
        try {
            $this->pdo = new PDO('mysql:dbname=' . self::DATABASE . ';host=' . self::HOST, self::USER, self::PASSWORD /* , array(PDO::ATTR_PERSISTENT => true ) */);

        } catch (PDOException $e) {

            echo 'Connexion échouée : ' . $e->getMessage();
        }

        if ($this->pdo == null || !$this->pdo) exit;
        else {

            if ($this->pdo->query('SET NAMES "' . self::ENCODAGE . '"') !== FALSE) {

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