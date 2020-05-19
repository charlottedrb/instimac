<?php

class Fake
{
    public static function generateName()
    {
        $d = ['Grégoire', 'Luc', 'Paul', 'John', 'Dayno', 'Davie', 'Polaiy', 'Karl', 'Léa', 'Camille', 'Albert', 'Jean-Luc', 'Benoit', 'Babar'];
        return self::random($d);
    }

    private static function random($tab)
    {
        $rand = random_int(0, count($tab) - 1);
        return $tab[$rand];
    }

    public static function generateSurname()
    {
        $d = ['Petit', 'Latte', 'Joseph', 'Ambrio', 'Italo', 'Masifou', 'Stein', 'Casoni', 'Mam', 'Iov', 'Samoio'];
        return self::random($d);
    }

    public static function generateEmail()
    {
        $type = ['g.dsfsdf', 'john.smith', 'taylor.smth', 'laol', 'loalo-tail', 'italo', 'iov.cyp', 'jl.marc', 'alo-63530'];
        $host = ['gmail', 'hotmail', 'yahoo', 'live', 'wanadoo', 'orange', 'free', 'bbox', 'soch'];
        $extension = ['fr', 'en', 'com', 'al', 'ge', 'tv', 'be'];
        return self::random($type) . '@' . self::random($host) . '.' . self::random($extension);
    }

    public static function generateTask()
    {
        $d = ['Wash my hands', 'Learn the courses', 'Draw the new mona Lisa', 'Call johny for the fire', 'Sing for charity show', 'Tidy my room'];
        return self::random($d);
    }

    public static function generateDate()
    {
        //Generate a timestamp using mt_rand.
        $timestamp = mt_rand(1, time());
        //Format that timestamp into a readable date string.
        return date("Y-m-d H:i:s", $timestamp);
    }

    public static function generateText()
    {
        return "The most common lorem ipsum text reads as follows: Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    }

}
