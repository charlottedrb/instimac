<?php

namespace Database;

use \PDO;
use \PDOException;

/**
 * Class used to manage a mysql database based on PDO
 * @package Database
 * @author © 2020 Grégoire Petitalot
 */
class Database
{
    const HOST = 'localhost';
    const DATABASE = 'dev';
    const USER = 'root';
    const PASSWORD = '';
    const ENCODAGE = 'utf8';

    public $pdo;
    public $sqlRequest;
    private $_params = [];
    private $_where = [];

    private $_logEnabled = FALSE;
    private $_logCount = 0;

    /**
     * convert $value into a boolean
     * @param $value
     * @return bool
     */
    public static function intToBool(&$value)
    {
        if ($value >= 1) return TRUE;
        return FALSE;
    }

    /**
     * Connection to the database, store a PDO object into the property $pdo
     * @return bool
     */
    public function connect()
    {
        try {
            $this->pdo = new PDO('mysql:dbname=' . self::DATABASE . ';host=' . self::HOST, self::USER, self::PASSWORD);
        } catch
        (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        if ($this->pdo == null || !$this->pdo) return FALSE;
        return $this->exec('SET NAMES "' . self::ENCODAGE . '"');
    }

    /**
     * Execute a request that needs no results
     * @param $request
     * @return bool
     */
    public function exec($request)
    {
        $request = $this->pdo->exec($request);
        if ($request !== FALSE) return TRUE;
        return FALSE;
    }

    /**
     * Process a SQL select request with attributes
     * @param $table
     * @param $attributes
     * @param bool $resultsRequired
     * @return array|bool
     */
    public function select($table, $attributes, $resultsRequired = FALSE)
    {
        if ($attributes === FALSE) $attributes = ['*'];
        $this->sqlRequest = 'SELECT ' . self::_generateList($attributes) . ' FROM ' . $table;
        return $this->process($this->sqlRequest, $resultsRequired, TRUE);
    }

    /**
     * Generate the list with coma and ? for a sql request
     * @param $list
     * @param string $sep
     * @param string $before
     * @param string $after
     * @return string
     */
    private static function _generateList($list, $sep = ',', $before = '', $after = '')
    {
        $string = '';
        if (is_array($list)) {
            $c = count($list);
            for ($i = 0; $i < $c; $i++) {
                if ($i > 0) $string .= $sep;
                $string .= $before . $list[$i] . $after;
            }
        } else if (is_int($list)) {
            for ($i = 0; $i < $list; $i++) {
                if ($i > 0) $string .= $sep;
                $string .= $before . $after;
            }
        }
        return $string;
    }

    /**
     * Process a request by preparing the sql requuest in SQL set params and execute it
     * @param $req
     * @param bool $resultsRequired
     * @return bool | array
     */
    public function process($req, $resultsRequired = FALSE, $returnEmptyArray = FALSE)
    {
        $this->sqlRequest = $req;
        $this->sqlRequest .= $this->_generateWhere($this->_where);
        $this->_talkative($this->sqlRequest);
        $request = $this->pdo->prepare($this->sqlRequest);
        $this->_setParams($request);

        if ($request->execute()) {

            $data = $request->fetchAll();
            //var_dump($data);

            if (!empty($data)) {
                $request->closeCursor();
                $this->_clear();
                return $data;
            } elseif ($returnEmptyArray === TRUE && $resultsRequired === FALSE) {
                return [];
            }

            $request->closeCursor();
            $this->_clear();

            if ($resultsRequired) return FALSE;
            return TRUE;
        }
        $request->closeCursor();
        $this->_clear();
        return FALSE;
    }

    /**
     * Generate the WHERE part in a sql request with the arguments passed in $where
     * @param array $where
     * @return string|null
     */
    private function _generateWhere($where = [])
    {
        if (count($where) == 0) return NULL;

        $count = 0;
        $req = ' WHERE ';
        foreach ($where as $key => $value) {
            if ($count > 0) $req .= ' AND ';
            $count++;
            $req .= $key . '=?';
            $this->_params[] = $value;
        }
        return $req;
    }

    /**
     * Show the generated requests with the class during process
     * @param $text
     */
    private function _talkative($text)
    {
        if ($this->_logEnabled) echo 'DataBase | ' . $this->_logCount . ' | ' . $text . "\n<br>";
        $this->_logCount++;
    }

    /**
     * Set the params into the pdo object with the _params[] and define the type of them with PDO CONSTANTS
     */
    private function _setParams(&$PDOStatement)
    {
        $c = 1;
        $type = NULL;
        foreach ($this->_params as $key => &$val) {

            switch ($val) {
                case is_bool($val):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_int($val):
                    $type = PDO::PARAM_INT;
                    break;
                case is_string($val):
                    $type = PDO::PARAM_STR;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
            $PDOStatement->bindParam($c, $val, $type);
            $c++;
        }
    }

    /**
     * Clear the $_where and $_params array at the end of the request
     */
    private function _clear()
    {
        $this->_where = $this->_params = [];
        $this->sqlRequest = NULL;
    }

    /**
     * Process an insert SQL request, if an array contain array is passed, the method insert multiple group of values by one request
     * @param $table
     * @param $attributes
     * @param $values
     * @return array|bool
     */
    public function insert($table, $attributes, $values)
    {
        $this->sqlRequest = 'INSERT INTO ' . $table . '(' . self::_generateList($attributes) . ') VALUES ';

        if (is_array($values[0])) {
            //generate a list of question mark and coma for prepared request
            $comaList = '(' . self::_generateList(count($values[0]), ',', '?') . ')';
            $c = count($values);

            for ($i = 0; $i < $c; $i++) {
                if ($i > 0) $this->sqlRequest .= ',';
                $this->sqlRequest .= $comaList;

                foreach ($values[$i] as &$value) {
                    $this->addParam($value);
                }
            }
        } else {
            $this->sqlRequest .= ' (' . self::_generateList(count($values), ',', '?') . ')';
            foreach ($values as &$value) {
                $this->addParam($value);
            }
        }
        //$this->_talkative($this->sqlRequest);
        return $this->process($this->sqlRequest);
    }

    /**
     * Add a new prepared param to the future generated request
     * @param $value
     */
    public function addParam($value)
    {
        $this->_params[] = $value;
    }

    /**
     * Process an update sql request with values and optionnal where conditions
     * @param $table
     * @param $values
     * @param bool $where
     * @return array|bool
     */
    public function update($table, $values, $where = false)
    {
        $this->sqlRequest = 'UPDATE ' . $table . ' SET ';
        $i = 0;
        foreach ($values as $key => $value) {
            if ($i > 0) $this->sqlRequest .= ',';
            $this->sqlRequest .= $key . '=?';
            $this->addParam($value);
            $i++;
        }
        $this->where($where);
        $this->_talkative($this->sqlRequest);
        return $this->process($this->sqlRequest);
    }

    /**
     * Add where condition.s to the where generation queue
     * @param $filters
     * @return bool
     */
    public function where($filters)
    {
        if (is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->_where[$key] = $value;
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Process an SQL delete request
     * @param $table
     * @param $where
     * @return array|bool
     */
    public function delete($table, $where)
    {
        $this->sqlRequest = 'DELETE FROM ' . $table;
        $this->where($where);
        $this->_talkative($this->sqlRequest);
        return $this->process($this->sqlRequest);
    }

    /**
     * Process a DROP sql request
     * @param $table
     * @return array|bool
     */
    public function drop($table)
    {
        $this->sqlRequest = 'DROP TABLE IF EXISTS ' . $table;
        $this->_talkative($this->sqlRequest);
        return $this->process($this->sqlRequest);
    }

    function __destruct()
    {
        $this->pdo = null;
    }
}