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
    const TABLE = 'test_files';
    const FILE_PATH = 'php' . DIRECTORY_SEPARATOR . 'upload';
    const LOG = TRUE;

    public $database;
    public $id;
    public $mime;
    public $originName;
    public $serverName;
    public $serverPath;
    public $created;
    public $userId = NULL;

    private $maxSize = FALSE;
    private $forbiddenExtensions = array('htaccess', 'php', 'js');

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

    public static function getUploadProgressName()
    {
        return ini_get("session.upload_progress.name");
    }

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

        if ($this->database->process($sql)) return TRUE;
        return FALSE;
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
            if (!mkdir($destinationPath, 755)) return false;
            $this->log(__METHOD__ . " Create dir");
        }

        if (!$this->upload($file, $destinationPath)) return FALSE;
        $this->log(__METHOD__ . ' ' . "Uplaoded");

        if (!$this->_register()) return FALSE;

        return TRUE;
    }

    private function _basePath()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    public function log($content)
    {
        if (self::LOG) echo "$content\n<br>";
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
        $this->log(__METHOD__ . ' ' . "File uploaded checked OK");

        //do not exceed max size ?
        if ($this->maxSize !== FALSE && $file['size'] > $this->maxSize) return FALSE;
        $this->log(__METHOD__ . ' ' . "Max size OK");

        //on recupere l'extension sans le point
        $extension = $this->_extractExtension($file['name']);
        $this->log(__METHOD__ . ' ' . "Extension: $extension");

        // On vérifie que le fichier est authorisé
        if (!$this->_checkAuthorisedExtension($extension)) return FALSE;

        $this->originName = $file['name'];
        $this->mime = mime_content_type($file['tmp_name']);
        $this->log(__METHOD__ . ' ' . "Mime: $this->mime");

        //Create another file name to avoid over writting a same file
        $this->serverName = $this->_fileName($extension);
        $this->serverPath = self::FILE_PATH;

        $destination = $this->_basePath() . DIRECTORY_SEPARATOR . self::FILE_PATH . DIRECTORY_SEPARATOR . $this->serverName;
        $this->log(__METHOD__ . ' ' . "destination : $destination");

        $result = move_uploaded_file($file['tmp_name'], $destination);

        return $result;
    }

    //Use the trait Token to avoid code duplication
    use Token;

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
        if (in_array($extension, $this->forbiddenExtensions)) return FALSE;
        $this->log(__METHOD__ . ' ' . "Autorized OK");
        return TRUE;
    }

    /**
     * Generate a unique file name to avoid conflict in the server path
     * @param $extension
     * @return string
     * @throws Exception
     */
    private function _fileName($extension): string
    {
        $date = new DateTime();
        $date = $date->format('YmdHis');
        $serverName = $date . '_' . $this->_token(10) . '.' . $extension;
        $this->log(__METHOD__ . ' ' . "Server name: $serverName");
        return $serverName;
    }

    /**
     * Register the file in database and update the id property with the database id
     * @return bool
     */
    private function _register()
    {
        if (!$this->database->insert(
            self::TABLE,
            [
                'f_origin_name',
                'f_server_name',
                'f_server_path',
                'f_mime',
                'f_created',
                'user_id'
            ],
            [
                $this->originName,
                $this->serverName,
                $this->serverPath,
                $this->mime,
                $this->created,
                $this->userId,
            ]
        )) return FALSE;
        $this->log(__METHOD__ . ' ' . "INSERTED in db OK");

        $request = $this->database->select(self::TABLE, ['f_id'], TRUE);
        if (!$request) return FALSE;
        $this->id = $request[0]['f_id'];
        $this->log(__METHOD__ . ' ' . "Registered OK");

        return TRUE;
    }

    /**
     * Get by id a file
     * @param bool $id
     * @return bool
     */
    public function get($id = FALSE)
    {
        if ($id !== FALSE) $this->id = (int)$id;

        $data = $this->database->select(self::TABLE, FALSE, ['f_id' => $this->id]);

        if ($data !== FALSE && !empty($data)) {
            $data = $data[0];
            $this->mime = $data['f_mime'];
            $this->originName = $data['f_origin_name'];
            $this->serverName = $data['f_server_name'];
            $this->serverPath = $data['f_server_path'];
            $this->created = $data['f_created'];
            $this->userId = $data['user_id'];
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
        $data = $this->database->select(self::TABLE, FALSE, $where);

        if ($data !== FALSE && !empty($data)) {
            foreach ($data as $value) {

                $files[] = [
                    'id' => $value['file_id'],
                    'mime' => $value['file_mime'],
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

    function __destruct()
    {
        $this->database = NULL;
    }
}