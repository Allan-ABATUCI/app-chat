<?php

require_once 'vendor/autoload.php';
ini_set('max_execution_time', 900);
ini_set('memory_limit', '10G');
ini_set('mysql.connect_timeout', 300);
ini_set('default_socket_timeout', 300);
ignore_user_abort(true);
// Liste des contrôleurs -- A RENSEIGNER
$controllers = ['login', 'list', 'chat'];
// Nom du contrôleur par défaut -- A RENSEIGNER
$controller_default = "login";

// On teste si le paramètre controller existe et correspond à un contrôleur de la liste $controllers
if (isset($_GET['controller']) && in_array($_GET['controller'], $controllers)) {
    $nom_controller = $_GET['controller'];
} else {
    $nom_controller = $controller_default;
}

// On détermine le nom de la classe du contrôleur
$nom_classe = 'App\Controllers\Controller_' . $nom_controller;

if (class_exists($nom_classe)) {
    new $nom_classe();
} else {
    die("Error 404: Controller class not found!");
}
