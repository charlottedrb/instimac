<?php

namespace Session;

use Sanitize\Sanitize;
use Token\Token;


class Session
{
    use Token;
    const DEV_MODE = TRUE;

    const KEY_LENGTH = 20;
    const DOMAIN = '';
    const DOMAIN_PATH = '';
    const COOKIE_THROUGH_HTTPS_ONLY = TRUE;
    const COOKIE_EXPIRATION_TIME = 1036800;
    const COOKIE_TOKEN_NAME = 'carot';


    private $ip;
    private $userAgent;
    private $token;

    public function __construct()
    {
        $this->ip = self::getIp();
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->token = $this->_token(self::KEY_LENGTH);
        if (self::DEV_MODE) setcookie(self::COOKIE_TOKEN_NAME, $this->token, time() + self::COOKIE_EXPIRATION_TIME , self::DOMAIN_PATH, self::DOMAIN);
        else setcookie(self::COOKIE_TOKEN_NAME, $this->token, time() + self::COOKIE_EXPIRATION_TIME , self::DOMAIN_PATH, self::DOMAIN, self::COOKIE_THROUGH_HTTPS_ONLY );
    }

    private function getIp()
    {
        // IP si internet partagé
        if (isset($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
        // IP derrière un proxy
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        // Sinon : IP normale
        return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : FALSE);
    }

    public static function getCookieName()
    {
        return self::COOKIE_TOKEN_NAME;
    }

    public function check()
    {
        if (self::getIp() !== $this->ip && $this->userAgent !== $_SERVER['HTTP_USER_AGENT']) return FALSE;
        if (self::checkToken()) return TRUE;
        echo 'BAD TOKEN';
        return FALSE;
    }

    private function checkToken()
    {
        var_dump($_COOKIE);
        if (!empty($_COOKIE[self::COOKIE_TOKEN_NAME])) {
            $securedToken = Sanitize::sanitize($_COOKIE[self::COOKIE_TOKEN_NAME]);
            if ($securedToken === $this->token) return true;
        }
        echo 'COOKIE NOT HERRRE';
        return false;
    }

    public function get()
    {
        return [
            'ip' => $this->ip,
            'userAgent' => $this->userAgent,
        ];
    }
}