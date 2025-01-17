<?php

namespace App\Controllers;

use App\Models\Model;

class Controller_login extends Controller
{

    public function action_default()
    {
        $this->action_form_login();
    }
    public function action_form_login()
    {

        $this->render('login');
    }
    public function action_login()
    {
        if (isset($_POST['submit_login'])) {
            $bd = Model::getModel();
            $email = $_POST['email'];
            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);


            if ($bd->UserExists($email, $mdp)) {
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $bd->getUser($email, $mdp)['user_id'];
            } else {
                $message = "Mauvais identifiant ou mot de passe";
                echo $message;
            }
        }
    }
    public function action_register()
    {
        if (isset($_POST['submit_register'])) {
            $email = $_POST['email'];
            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            $bd = Model::getModel();

            if (!$bd->UserExists($email)) {
                $bd->createUser($email, $mdp);
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $bd->getUser($email)['user_id'];
            } else {
                $message = "L'utilisateur existe déjà";
                echo $message;
            }
        }
    }
}
