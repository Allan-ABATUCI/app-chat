<?php
require_once 'app/start.php';
require_once "app/Chatapp/Utils/functions.php";


//Liste des contrôleurs -- A RENSEIGNER
$controllers = ['login', 'list'];
//Nom du contrôleur par défaut-- A RENSEIGNER
$controller_default = "login";

//On teste si le paramètre controller existe et correspond à un contrôleur de la liste $controllers
if (isset($_GET['controller']) and in_array($_GET['controller'], $controllers)) {
    $nom_controller = $_GET['controller'];
} else {
    $nom_controller = $controller_default;
}

//On détermine le nom de la classe du contrôleur
$nom_classe = 'Controller_' . $nom_controller;

//On détermine le nom du fichier contenant la définition du contrôleur
$nom_fichier = 'Controllers/' . $nom_classe . '.php';

//Si le fichier existe et est accessible en lecture
if (is_readable($nom_fichier)) {
    // on instancie un objet de cette classe
    new $nom_controller();
} else {
    die("Error 404: not found!");
}
