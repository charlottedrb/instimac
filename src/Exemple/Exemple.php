<?php

//mettre votre nom dans le fichier

/**
 * @author Grégoire Petitalot
 */

//espace de nom ou "namespace" correspond au nom du dossier Parent.
namespace Exemple;

/*
 * Chaque classe regroupe les fonctionnalité liées à une seule entité (ex: Tache, Utilisateur,etc.)
 * Toujours écrit avec une majuscule "Database"
 */
class Exemple
{
    //Constantes en majuscule avec _ pour séparer
    const CONSTANTE_TROP_COLL = TRUE;

    //Nom de propriét en "camelCase"
    public $database = NULL;
    public $proprietePafaite = 'BONjouR';
    private $proprieteCache;

    //Appelé à chaque construction d'un objet
    public function __construct(&$database)
    {
        $this->database = $database;
    }

    /* Initialise la fonctionnalité dans la base de donnée
     * sera appelé dans le fichier "/src/init.php"
     */
    public static function init(&$database)
    {
        //opération de création de la.es table.s dans la base de données
        return FALSE;
    }

    public function get()
    {
        return FALSE;
    }

    public function set()
    {
        return FALSE;
    }

    public function update()
    {
        return FALSE;
    }

    public function delete()
    {
        return FALSE;
    }

    //Appelé à chaque destruction d'un objet, unset(), fin de script, destruction
    public function __destruct()
    {
        $this->database = NULL;
    }

}