<?php


namespace File;

use Database\Database;
use DateTime;
use Exception;
use Token\Token;

/**
 * Class File
 * @package File
 * @author © 2020 Grégoire Petitalot
 */
class File
{
    //Use the trait Token to avoid code duplication
    use Token;

    const TABLE = 'files';
    const FILE_PATH = 'upload';
    const LOG = FALSE;

    public $database;
    public $id = NULL;
    public $mime;
    public $originName;
    public $serverName;
    public $serverPath;
    public $url;
    public $created;
    public $userId = NULL;

    private $maxSize = FALSE;
    private $_forbiddenExtensions = ['htaccess', 'php', 'js'];
    private $_authorisedExtensions = ['jpg', 'png', 'gif'];

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    public static function rearrangeFileArray($fileArrayFromForm)
    {
        $result = array();
        foreach ($fileArrayFromForm as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $result[$key2][$key1] = $value2;
            }
        }
        return $result;
    }

    /**
     * Get the session key for file progress
     * @param $token
     * @return string
     */
    public static function getSessionProgressKey($token)
    {
        return ini_get("session.upload_progress.prefix") . $token;
    }

    /**
     *
     * @return string
     */
    public static function getUploadProgressName()
    {
        return ini_get("session.upload_progress.name");
    }

    /**
     * Get the minimum refresh time in secondes between to upload progress request
     * @return string
     */
    public static function getUploadProgressMinRefreshTime()
    {
        return ini_get('session.upload_progress.min_freq');
    }

    public static function getUploadProgressToken($length = 10)
    {
        return self::_token($length);
    }

    /**
     * Initialise the table into database by database property
     * @return bool
     */
    public function init()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE . ' ( 
        f_id INT(11) NOT NULL AUTO_INCREMENT, 
        f_origin_name VARCHAR(255) NOT NULL, 
        f_server_name VARCHAR(255) NOT NULL, 
        f_server_path VARCHAR(255) NOT NULL, 
        f_mime VARCHAR(255) NOT NULL, 
        f_created DATETIME NOT NULL,
        user_id INT(11) NULL,
        PRIMARY KEY (f_id) ) ENGINE = InnoDB; ';

        if ($this->database->process($sql) === FALSE) return FALSE;
        return TRUE;
    }

    public function test($file)
    {
        $this->_log(__METHOD__ . "set");
        if ($this->set($file) === FALSE) return FALSE;
        $this->_log(__METHOD__ . "set OK");

        if ($this->getById($this->id) === FALSE) return FALSE;
        $this->_log(__METHOD__ . "OK get by id");

        if ($this->getAll() === FALSE) return FALSE;
        $this->_log(__METHOD__ . "OK getAll");

        if ($this->idToURL() === FALSE) return FALSE;
        $this->_log(__METHOD__ . "OK idTOURL");

        if ($this->getContent() === FALSE) return FALSE;
        $this->_log(__METHOD__ . "OK getContentById");

        return TRUE;
    }

    public function _log($content)
    {
        if (self::LOG) echo "$content\n<br>";
    }

    /**
     * Set the file in database and upload in the right server path
     * @param $file
     * @param bool $destinationPath
     * @return bool
     * @throws Exception
     */
    public function set($file, $destinationPath = FALSE)
    {
        $this->created = date('Y-m-d H:i:s');

        if ($destinationPath === FALSE) $destinationPath = self::FILE_PATH;
        $destinationPath = $this->_basePath() . DIRECTORY_SEPARATOR . $destinationPath;

        if (!file_exists($destinationPath)) {
            if (!mkdir($destinationPath, 755)) return FALSE;
            $this->_log(__METHOD__ . "OK Create dir");
        } else {
            $this->_log(__METHOD__ . "OK Path already exists");
        }

        if (!$this->upload($file, $destinationPath)) return FALSE;
        $this->_log(__METHOD__ . ' ' . "OK Uploaded");

        if (!$this->_register()) return FALSE;
        $this->_log(__METHOD__ . ' ' . "OK Registered");

        return TRUE;
    }

    private function _basePath()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * Verify and upload the sended file
     * @param $file
     * @param bool $destinationPath
     * @return bool
     * @throws Exception
     */
    public function upload($file, $destinationPath = FALSE)
    {
        // Check if file correctly uploaded and if
        if (!isset($file) || $file['error'] > 0) return FALSE;
        $this->_log(__METHOD__ . ' ' . "OK File uploaded checked");

        //do not exceed max size ?
        if ($this->maxSize !== FALSE && $file['size'] > $this->maxSize) return FALSE;
        $this->_log(__METHOD__ . ' ' . "OK Max size");

        //on recupere l'extension sans le point
        $extension = $this->_extractExtension($file['name']);
        $this->_log(__METHOD__ . ' ' . "Extension: $extension");

        // On vérifie que le fichier est authorisé
        if (!$this->_checkAuthorisedExtension($extension)) return FALSE;

        $this->originName = $file['name'];
        $this->mime = mime_content_type($file['tmp_name']);
        $this->_log(__METHOD__ . ' ' . "Mime: $this->mime");

        //Create another file name to avoid over writting a same file
        $this->serverName = $this->_fileName($extension);
        $this->serverPath = self::FILE_PATH;

        $destination = $this->_basePath() . DIRECTORY_SEPARATOR . self::FILE_PATH . DIRECTORY_SEPARATOR . $this->serverName;
        $this->_log(__METHOD__ . ' ' . ": $destination");

        return move_uploaded_file($file['tmp_name'], $destination);
    }

    /**
     * Extract the file extension
     * @param $name
     * @return false|string
     */
    private function _extractExtension($name)
    {
        return substr(strrchr($name, '.'), 1);
    }

    /**
     * Check by extension if the file is authorised
     * @param $extension
     * @return bool
     */
    private function _checkAuthorisedExtension($extension)
    {
        $extension = strtolower($extension);
        if (!empty($this->_authorisedExtensions) && !in_array($extension, $this->_authorisedExtensions)) return FALSE;
        if (in_array($extension, $this->_forbiddenExtensions)) return FALSE;
        $this->_log(__METHOD__ . ' ' . "Autorized OK");
        return TRUE;
    }

    /**
     * Generate a unique file name to avoid conflict in the server path
     * @param $extension
     * @return string
     * @throws Exception
     */
    private function _fileName($extension)
    {
        $date = new DateTime();
        $date = $date->format('YmdHis');
        $serverName = $date . '_' . $this->_token(10) . '.' . $extension;
        $this->_log(__METHOD__ . ' ' . "Server name: $serverName");
        return $serverName;
    }

    /**
     * Register the file in database and update the id property with the database id
     * @return bool
     */
    private function _register()
    {
        $fields = [
            'f_origin_name',
            'f_server_name',
            'f_server_path',
            'f_mime',
            'f_created',
            'user_id'
        ];

        $values = [
            $this->originName,
            $this->serverName,
            $this->serverPath,
            $this->mime,
            $this->created,
            $this->userId,
        ];

        if ($this->database->insert(self::TABLE, $fields, $values) === FALSE) return FALSE;
        $this->_log(__METHOD__ . ' ' . "INSERTED in db OK");

        $where = [
            'f_origin_name' => $this->originName,
            'f_server_name' => $this->serverName,
            'f_server_path' => $this->serverPath,
            'f_mime' => $this->mime,
            'f_created' => $this->created,
        ];

        $this->database->where($where);
        $data = $this->database->select(self::TABLE, ['f_id'], TRUE);

        if ($data === FALSE && empty($data)) return FALSE;

        $this->id = $data[0]['f_id'];
        $this->_log(__METHOD__ . ' ' . "Registered OK $this->id");

        return TRUE;
    }

    /**
     * Get by id a file
     * @param bool $id
     * @return bool
     */
    public function getById($id = FALSE)
    {
        if ($id !== FALSE) $this->id = (int)$id;

        $this->database->where(['f_id' => $this->id]);
        $data = $this->database->select(self::TABLE);

        if ($data !== FALSE && !empty($data)) {
            $data = $data[0];
            $this->mime = $data['f_mime'];
            $this->originName = $data['f_origin_name'];
            $this->serverName = $data['f_server_name'];
            $this->serverPath = $data['f_server_path'];
            $this->created = $data['f_created'];
            $this->userId = $data['user_id'];
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Get many files by filters
     * @param array $where
     * @return array|bool
     */
    public function getAll($where = [])
    {
        $files = [];
        $this->database->where($where);
        $data = $this->database->select(self::TABLE);

        if ($data !== FALSE && !empty($data)) {

            foreach ($data as $value) {
                $files[] = [
                    'id' => $value['f_id'],
                    'mime' => $value['f_mime'],
                    'origin_name' => $value['f_origin_name'],
                    'server_name' => $value['f_server_name'],
                    'server_path' => $value['f_server_path'],
                    'created' => $value['f_created'],
                    'user_id' => $value['user_id'],
                ];
            }
            return $files;
        }
        return FALSE;
    }

    /**
     * Convert ID to FILE URL
     * @param bool $id
     * @return string
     */
    public function idToURL($id = FALSE)
    {
        if ($id !== FALSE) $this->id = (int)$id;
        return './api/publication/get-photo.php?id=' . $this->id;
    }

    public function getContent()
    {
        $path = $this->getPath();
        if (!file_exists($path)) return NULL;
        return file_get_contents($path);
    }

    public function getPath()
    {
        return $this->_basePath() . DIRECTORY_SEPARATOR . $this->serverPath . DIRECTORY_SEPARATOR . $this->serverName;
    }

    function __destruct()
    {
        $this->database = NULL;
    }
}