<?php

namespace App\public\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryParams);
        $this->clients->attach($conn, $queryParams);
        echo "New connection\n";
    }
    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Décoder le message JSON entrant
        $message = json_decode($msg, true);
        // Diffuser le message au destinataire spécifié
        foreach ($this->clients as $client) {
            if ($client !== $from && $this->clients[$client]['userId'] === $message['dest']) {
                $client->send($message['message']);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connexion fermée\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Erreur : {$e->getMessage()}\n";
        $conn->close();
    }
}
