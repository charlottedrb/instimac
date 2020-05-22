<?php

namespace User;

use Database\Database;
use \PDO;
use Token\Token;

class User
{
    const TABLE = 'test_users';
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

    use Token;

    public function init()
    {
        $req = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . ' (
            user_id int(11) NOT NULL AUTO_INCREMENT,
            user_name varchar(50) NOT NULL,
            user_surname varchar(50) NOT NULL,
            user_email varchar(255) NOT NULL,
            user_password varchar(255) NOT NULL,
            user_level varchar(255) NOT NULL,
            user_enabled tinyint(1) NOT NULL,
            user_created datetime NOT NULL,
            user_connected datetime NOT NULL,
            PRIMARY KEY (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        if ($this->database->exec($req)) {

            $columns = [
                'user_name',
                'user_surname',
                'user_email',
                'user_password',
                'user_level',
                'user_enabled',
                'user_created',
                'user_connected',
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
                ],
                [
                    'Grégoire',
                    'Petitalot',
                    'perso@gregoirep.com',
                    self::generatePass('Te63st200#'),
                    json_encode(['ADMIN', 'USER']),
                    1,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                ]
            ];
            return $this->database->insert(self::TABLE, $columns, $values);
        }
        return FALSE;
    }

    public static function generatePass($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    public function updatePassword($new)
    {
        $where = ['user_id' => $this->id];
        $values = [
            'user_password' => self::generatePass($new),
            'user_connected' => date('Y-m-d H:i:s')
        ];

        if ($data = $this->database->update(self::TABLE, $values, $where)) return true;
        return false;
    }

    public function create($level = ['VISITOR'])
    {
        if ($this->emailExists()) return FALSE;

        $this->created = date('Y-m-d H:i:s');

        $columns = [
            'user_name',
            'user_surname',
            'user_email',
            'user_password',
            'user_level',
            'user_enabled',
            'user_created',
            'user_connected',
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

        if (!$this->database->insert(self::TABLE, $columns, $values)) return false;

        $values = [
            'user_name' => $this->name,
            'user_email' => $this->email,
            'user_created' => $this->created,
        ];
        $this->database->where($values);
        $data = $this->database->select(self::TABLE, ['user_id'], TRUE);

        if ($data !== FALSE) {
            $this->id = $data[0]['user_id'];
            return TRUE;
        }
        return false;
    }

    public function emailExists()
    {
        $values = ['user_email' => $this->email];
        $this->database->where($values);
        if ($this->database->select(self::TABLE, ['user_id'], TRUE)) return true;
        return false;
    }

    public function auth($login, $pass)
    {
        $where = ['user_email' => $login, 'user_enabled' => 1];
        $this->database->where($where);
        $data = $this->database->select(self::TABLE, FALSE, TRUE);
        var_dump($data);
        if ($data !== FALSE) {
            $data = $data[0];
            if ($this->checkPassword($pass, $data['user_password'])) {
                $this->id = $data['user_id'];
                $this->name = $data['user_name'];
                $this->surname = $data['user_surname'];
                $this->email = $data['user_email'];
                $this->enabled = $data['user_enabled'];
                $this->level = json_decode($data['user_level']);
                $this->created = $data['user_created'];
                $this->connected = $data['user_connected'];
                return TRUE;
            }
        }
        return FALSE;
    }

    public function checkPassword($raw, $hash)
    {
        return password_verify($raw, $hash);
    }

    public function update()
    {
        $this->connected = date('Y-m-d H:i:s');
        $where = ['user_id' => $this->id];
        $values = [
            'user_name' => $this->name,
            'user_surname' => $this->surname,
            'user_email' => $this->email,
            'user_enabled' => $this->enabled,
            'user_connected' => $this->connected,
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
        $this->database->where(['user_id' => $this->id]);
        if ($data = $this->database->select(self::TABLE, ['user_enabled', 'user_level'], TRUE)) {
            $this->enabled = $data[0]['user_enabled'];
            $this->level = json_decode($data[0]['user_level']);
            return true;
        }
        return false;
    }

    public function get($id)
    {
        $this->database->where(['user_id' => $id]);
        if ($data = $this->database->select(self::TABLE, FALSE, TRUE)) {

            $this->id = $data['user_id'];
            $this->name = $data['user_name'];
            $this->surname = $data['user_surname'];
            $this->email = $data['user_email'];
            $this->enabled = $data['user_enabled'];
            $this->level = json_decode($data['user_level']);
            $this->created = $data['user_created'];
            $this->connected = $data['user_connected'];

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
        return false;
    }

    public function getAll($where = [])
    {
        $users = [];
        if ($data = $this->database->select(self::TABLE, FALSE, $where)) {

            foreach ($data as $user) {
                $users[] = [
                    'id' => $user['user_id'],
                    'name' => $user['user_name'],
                    'surname' => $user['user_surname'],
                    'email' => $user['user_email'],
                    'enabled' => $user['user_enabled'],
                    'level' => json_decode($user['user_level']),
                    'created' => $user['user_created'],
                    'connected' => $user['user_connected'],
                ];
            }
            return $users;
        }
        return false;
    }

    public function disable()
    {
        if ($this->database->update(self::TABLE, ['user_enabled' => 0], ['user_id' => $this->id])) return true;
        return false;
    }

    public function delete()
    {
        if ($this->database->delete(self::TABLE, ['user_id' => $this->id])) return true;
        return false;
    }

    public function __destruct()
    {
        $this->database = null;
        $this->password = null;
    }

}