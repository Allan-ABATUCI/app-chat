<?php

namespace App\Models;

use PDO;

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
        include "src/App/Auth/credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET names 'utf8'");
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
    public function getOnlineUsers()
    {
        $sql = "SELECT Users.user_id, Users.username, Users.email 
            FROM UserStatus 
            JOIN Users ON UserStatus.user_id = Users.user_id 
            WHERE is_online = TRUE";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getLastMessage($id, $id_sender)
    {
        $req = $this->bd->prepare("SELECT * FROM Message WHERE receiver_id=:id and sender_id=:ids ORDER BY created_at desc limit 1");
        $req->bindValue(':ids', $id_sender, PDO::PARAM_STR);
        $req->bindValue(':id', $id, PDO::PARAM_STR);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function getUser($email, $mdp)
    {

        // Prepare the SQL statement with placeholders
        $req = $this->bd->prepare("SELECT * FROM Users WHERE email = :email AND password_hash = :hashedPassword");

        // Bind the values to the placeholders
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':hashedPassword', $mdp, PDO::PARAM_STR);

        // Execute the statement
        $req->execute();

        // Fetch the user data
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function UserExists($email)
    {
        $req = $this->bd->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    public function createUser($username, $email, $mdp)
    {
        // Préparer la requête SQL avec des marqueurs
        $req = $this->bd->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hashedPassword)");

        // Lier les valeurs aux marqueurs
        $req->bindValue(':username', $username, PDO::PARAM_STR);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':hashedPassword', $mdp, PDO::PARAM_STR);

        // Exécuter la requête
        $req->execute();

        // Retourner l'ID du nouvel utilisateur créé (optionnel)
        return $this->bd->lastInsertId();
    }

    public function updateUserStatus($userId, $isOnline)
    {
        // Check if a UserStatus record exists for the given user ID
        $checkSql = "SELECT COUNT(*) FROM UserStatus WHERE user_id = :user_id";
        $checkStmt = $this->bd->prepare($checkSql);
        $checkStmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $checkStmt->execute();
        $recordExists = $checkStmt->fetchColumn() > 0;

        if ($recordExists) {
            // Update existing record
            $sql = "UPDATE UserStatus SET is_online = :is_online WHERE user_id = :user_id";
            $stmt = $this->bd->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':is_online', $isOnline, PDO::PARAM_BOOL);
            $stmt->execute();
        } else {
            // Insert new record
            $sql = "INSERT INTO UserStatus (user_id, is_online) VALUES (:user_id, :is_online)";
            $stmt = $this->bd->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':is_online', $isOnline, PDO::PARAM_BOOL);
            $stmt->execute();
        }
    }




    public function getConversation() {}
}
