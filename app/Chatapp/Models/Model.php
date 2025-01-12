<?php

class Model
{
    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {
        include "/home/Web/Auth/credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET nameS 'utf8'");
    }

    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * retourne un tableau de tableau associatif des utilisateurs online
     * @return array
     */
    public function getOnlineUsers(){
        $req=$this->bd->prepare("SELECT * FROM UserStatus JOIN User USING(user_id) WHERE is_online=TRUE");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastMessage($id,$id_sender){
        $req=$this->bd->prepare("SELECT * FROM Message WHERE receiver_id=:id and sender_id=:ids ORDER BY created_at desc limit 1");
        $req->bindValue(':ids',$id_sender,PDO::PARAM_STR);
        $req->bindValue(':id',$id,PDO::PARAM_STR);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    public function getUser($email,$mdp){
        $req = $this->bd->prepare("SELECT * FROM users WHERE email = :email AND mdp = :id");
        $req->bindValue(':email',$email,PDO::PARAM_STR);
        $req->bindValue(':mdp',$mdp,PDO::PARAM_STR);
        $req->execute();
        return $req->fetch();
    }

    public function getConversation (){}

}