<?php
namespace App\Controllers;
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
            $email = $_POST['email'];
            $mdp = sha1($_POST['mdp']);

            $bd = Model::getModel();

            if ($bd->UserExists($email, $mdp)) {
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $bd->getUser($email, $mdp)['id'];
            } else {
                $message = "Mauvais identifiant ou mot de passe";
                echo $message;
            }
        }
    }
}