<?php

namespace Sanitize;

Class Sanitize
{
    public static function test()
    {
        $testValues = [42, 42.3, '42.3', '<script class="">En base de données %_ </script>'];

        echo '[ Class Sanitize test in progress..' . "\n<br>";
        foreach ($testValues as $value) {
            echo $value . ' => ' . self::sanitize($value) . "\n<br>";
        }
        echo '[ Class Sanitize test DONE.' . "\n<br>";
    }

    public static function sanitize($value, $filterForDatabase = true)
    {
        // On regarde si le type de la variable est un nombre entier (int) et on le transtype,
        // si il est numérique -> to float
        if (ctype_digit($value)) return intval($value);
        if (is_numeric($value)) return floatval($value);

        //Sinon htmlspecialchars — Convertit les caractères spéciaux en entités HTML,
        //ENT_QUOTES = Convertit les guillemets doubles et les guillemets simples.
        $value = htmlspecialchars($value, ENT_QUOTES);

        //Si la variable != des caractères a-zA-Z,-,\ on applique,
        if (!preg_match('/^([a-z0-9\\-]+)$/si', $value)) {
            $value = htmlentities($value);
            if ($filterForDatabase) $value = addcslashes($value, '%_');
            return $value;
        }
        return $value;
    }

    public static function checkEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function boolean($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function display($string)
    {
        return html_entity_decode($string);
    }

    public static function decode($string)
    {
        return html_entity_decode(htmlspecialchars_decode($string));
    }

    public static function arrayFields(&$array, $fields = [])
    {
        $secured = [];

        foreach ($fields as $field) {
            $secured[$field] = self::sanitize($array[$field]);
        }

        return $secured;
    }

    public static function checkEmptyFields(&$array, $fields = [])
    {
        foreach ($fields as $value) {
            if (empty($array[$value])) return false;
        }
        return true;
    }

    public static function checkIssetFields(&$array, $fields = [])
    {
        foreach ($fields as $value) {
            if (!isset($array[$value])) return false;
        }
        return true;
    }

    public static function sanitizeRecursive(&$value, $key)
    {
        $value = self::sanitize($value);
    }

    //recursive version of sanitize for post fields
    public static function fieldRecursive(&$array, $fields = [])
    {
        $secured = [];
        foreach ($fields as $field) {
            $array = $array[$field];
            array_walk_recursive($array, [__CLASS__, 'sanitizeRecursive']);
            $secured[$field] = $array;
        }
        return $secured;
    }
}