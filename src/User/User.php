<?php

namespace User;

use Database\Database;
use \PDO;
use Token\Token;

class User
{
    use Token;

    const TABLE = 'utilisateurs';
    public $database = null;

    public $id;
    public $name;
    public $surname;
    public $email;

    public $enabled = 0;
    public $level = ['VISITOR'];
    public $password;
    public $created;
    public $connected;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    //TODO: VALID PASSWORD
    public static function isValidPassword($password)
    {
        return TRUE;
    }

    /**
     * Initialise l'utilisateur en bdd
     * @return bool
     */
    public function init()
    {
        $req = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . ' (
            u_id int(11) NOT NULL AUTO_INCREMENT,
            u_prenom varchar(50) NOT NULL,
            u_nom varchar(50) NOT NULL,
            u_email varchar(255) NOT NULL,
            u_password varchar(255) NOT NULL,
            u_role varchar(255) NOT NULL,
            u_actif tinyint(1) NOT NULL,
            u_date_creation datetime NOT NULL,
            u_connection datetime NOT NULL,
            PRIMARY KEY (u_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($this->database->exec($req) === FALSE) return FALSE;

        $columns = [
            'u_prenom',
            'u_nom',
            'u_email',
            'u_password',
            'u_role',
            'u_actif',
            'u_date_creation',
            'u_connection',
        ];

        $values = [
            [
                'Lola',
                'Fakil',
                'gregor.g12@gmail.com',
                self::generatePass(self::_token(15)),
                json_encode(['VISITOR']),
                0,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ]
        ];
        if ($this->database->insert(self::TABLE, $columns, $values) === FALSE) return FALSE;
        return TRUE;
    }

    public static function generatePass($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    /**
     * Test user class
     * @return bool
     */
    public function test()
    {
        $password = 'test';

        $this->name = "test";
        $this->surname = "surname test";
        $this->email = "test@test" . self::_token(5) . ".com";
        $this->password = $password;
        $this->enabled = 1;

        if ($this->create(['ADMIN', 'VISITOR']) === FALSE) return FALSE;
        if ($this->auth($this->email, $password) === FALSE) return FALSE;
        if ($this->get($this->id) === FALSE) return FALSE;
        if ($this->disable() === FALSE) return FALSE;
        if ($this->enable() === FALSE) return FALSE;
        return TRUE;
    }

    public function create($level = ['VISITOR'])
    {
        if ($this->emailExists()) return FALSE;

        $this->created = date('Y-m-d H:i:s');

        $columns = [
            'u_prenom',
            'u_nom',
            'u_email',
            'u_password',
            'u_role',
            'u_actif',
            'u_date_creation',
            'u_connection',
        ];


        $values = [
            $this->name,
            $this->surname,
            $this->email,
            self::generatePass($this->password),
            json_encode($level),
            $this->enabled,
            $this->created,
            $this->created,
        ];

        if ($this->database->insert(self::TABLE, $columns, $values) === FALSE) return false;

        $values = [
            'u_prenom' => $this->name,
            'u_email' => $this->email,
            'u_date_creation' => $this->created,
        ];
        $this->database->where($values);
        $data = $this->database->select(self::TABLE, ['u_id'], TRUE);

        if ($data !== FALSE) {
            $this->id = $data[0]['u_id'];
            return TRUE;
        }
        return false;
    }

    public function emailExists()
    {
        $values = ['u_email' => $this->email];
        $this->database->where($values);
        $data = $this->database->select(self::TABLE, ['u_id'], TRUE);
        if ( $data !== FALSE && !empty($data)) return true;
        return FALSE;
    }

    public function auth($login, $pass)
    {
        $where = ['u_email' => $login, 'u_actif' => 1];

        $this->database->where($where);
        $data = $this->database->select(self::TABLE, FALSE, TRUE);

        if ($data !== FALSE && !empty($data)) {

            $data = $data[0];

            if ($this->checkPassword($pass, $data['u_password'])) {
                $this->id = $data['u_id'];
                $this->name = $data['u_prenom'];
                $this->surname = $data['u_nom'];
                $this->email = $data['u_email'];
                $this->enabled = $data['u_actif'];
                $this->level = json_decode($data['u_role']);
                $this->created = $data['u_date_creation'];
                $this->connected = $data['u_connection'];
                return TRUE;
            }
        }
        return FALSE;
    }

    public function checkPassword($raw, $hash)
    {
        return password_verify($raw, $hash);
    }

    public function updatePassword($new)
    {
        $where = ['u_id' => $this->id];
        $values = [
            'u_password' => self::generatePass($new),
            'u_connection' => date('Y-m-d H:i:s')
        ];

        if ($data = $this->database->update(self::TABLE, $values, $where)) return true;
        return false;
    }

    public function update()
    {
        $this->connected = date('Y-m-d H:i:s');
        $where = ['u_id' => $this->id];
        $values = [
            'u_prenom' => $this->name,
            'u_nom' => $this->surname,
            'u_email' => $this->email,
            'u_actif' => $this->enabled,
            'u_connection' => $this->connected,
        ];

        if ($data = $this->database->update(self::TABLE, $values, $where)) return true;
        return false;
    }

    public function isGranted($access)
    {
        if (!$this->refreshRights()) return false;
        if ($this->enabled == 0) return false;

        foreach ($this->level as $value) {
            if (in_array($value, $access)) return true;
        }
        return false;
    }

    public function refreshRights()
    {
        $this->database->where(['u_id' => $this->id]);
        $data = $this->database->select(self::TABLE, ['u_actif', 'u_role'], TRUE);
        if ($data !== FALSE & !empty($data)) {
            $this->enabled = $data[0]['u_actif'];
            $this->level = json_decode($data[0]['u_role']);
            return true;
        }
        return false;
    }

    public function get($id)
    {
        $this->database->where(['u_id' => $id]);
        $data = $this->database->select(self::TABLE, FALSE, TRUE);
        if ($data !== FALSE & !empty($data)) {

            $data = $data[0];

            $this->id = $data['u_id'];
            $this->name = $data['u_prenom'];
            $this->surname = $data['u_nom'];
            $this->email = $data['u_email'];
            $this->enabled = $data['u_actif'];
            $this->level = json_decode($data['u_role']);
            $this->created = $data['u_date_creation'];
            $this->connected = $data['u_connection'];

            return [
                'id' => $this->id,
                'name' => $this->name,
                'surname' => $this->surname,
                'email' => $this->email,
                'enabled' => $this->enabled,
                'level' => $this->level,
                'created' => $this->created,
                'connected' => $this->connected,
            ];
        }
        return FALSE;
    }

    public function getAll($where = [])
    {
        $users = [];
        $data = $this->database->select(self::TABLE, FALSE, $where);
        if ( $data !== FALSE && !empty($data)) {

            foreach ($data as $user) {
                $users[] = [
                    'id' => $user['u_id'],
                    'name' => $user['u_prenom'],
                    'surname' => $user['u_nom'],
                    'email' => $user['u_email'],
                    'enabled' => $user['u_actif'],
                    'level' => json_decode($user['u_role']),
                    'created' => $user['u_date_creation'],
                    'connected' => $user['u_connection'],
                ];
            }
            return $users;
        }
        return false;
    }

    public function disable()
    {
        if ($this->database->update(self::TABLE, ['u_actif' => 0], ['u_id' => $this->id]) !== FALSE) return TRUE;
        return FALSE;
    }

    public function enable()
    {
        if ($this->database->update(self::TABLE, ['u_actif' => 1], ['u_id' => $this->id]) !== FALSE) return TRUE;
        return FALSE;
    }

    public function delete()
    {
        if ($this->database->delete(self::TABLE, ['u_id' => $this->id])) return true;
        return false;
    }

    public function __destruct()
    {
        $this->database = null;
        $this->password = null;
    }

}