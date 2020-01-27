<?php

// Connexion a la BDD :
    $pdo = new PDO('mysql:host=localhost;dbname=deal', 
                'root', 
                '', 
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8') 
);

//
// Puis créer une session ou y acceder si elle existe :
session_start();

// definition du chemin du site : 
define('RACINE_SITE', '/eval2/'); // indiquer ici le dossier dans lequel se situe votre site sans "localhost". Permet de créer des chemins absolus (avec le / au debut)
// Neccessaires dans les inclusions ( header.php, footer.php).

// Variables pour afficher :
$contenu = '';
$contenu_gauche = '';
$contenu_droite = '';

// Inclusion des fonctions :
require_once 'functions.php';
