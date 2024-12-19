<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require '/vendor/autoload.php';

class ChatServer implements MessageComponentInterface
{
    protected $clients; // Liste des connexions actives
    protected $userConnections = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }
    public static function getChatServer()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    // Méthode appelée lorsqu'un client se connecte
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Nouvelle connexion : ({$conn->resourceId})\n";
    }

    // Méthode appelée lorsqu'un message est reçu
    public function onMessage(ConnectionInterface $from, $message)
    {
        $data = json_decode($message, true);

        if ($data['type'] === 'authenticate') {
            $this->userConnections[$data['userId']] = $from;
            $from->send(json_encode(['status' => 'authenticated']));
            return;
        }

        if (isset($data['type']) && $data['type'] === 'private') {
            $recipientId = $data['to']; // ID du destinataire
            $content = $data['message']; // Message à envoyer

            foreach ($this->clients as $client) {
                if ($client->resourceId == $recipientId) {
                    $client->send(json_encode([
                        'from' => $from->resourceId,
                        'message' => $content
                    ]));
                    return;
                }
            }

            $from->send(json_encode(['error' => 'Utilisateur non trouvé']));
        } else {
            $from->send(json_encode(['error' => 'Type de message non valide']));
        }
    }

    // Méthode appelée lorsqu'un client se déconnecte
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connexion fermée : ({$conn->resourceId})\n";
    }

    // Méthode appelée en cas d'erreur
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Erreur : {$e->getMessage()}\n";
        $conn->close();
    }
}

// Lancer le serveur
use Ratchet\Server\IoServer;

$server = IoServer::factory(
    new ChatServer(),
    8080
);

echo "Serveur démarré sur le port 8080...\n";
$server->run();
