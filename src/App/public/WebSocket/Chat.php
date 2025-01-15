<?php

namespace App\public\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage; // Stores connections with associated user IDs
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn, ['userId' => null]); // Attach connection without user ID initially
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if (!isset($data['action'])) {
            $from->send(json_encode(['error' => 'Invalid message format']));
            return;
        }

        switch ($data['action']) {
            case 'initiate':
                $this->handleInitiate($from, $data);
                break;
            case 'message':
                $this->handleMessage($from, $data);
                break;
            default:
                $from->send(json_encode(['error' => 'Unknown action']));
        }
    }

    private function handleInitiate(ConnectionInterface $from, $data)
    {
        if (!isset($data['targetUser'])) {
            $from->send(json_encode(['error' => 'targetUser is required']));
            return;
        }

        $from->userId = $data['targetUser']; // Assign the userId to the connection
        echo "Connection {$from->resourceId} identified as user {$data['targetUser']}\n";

        $from->send(json_encode(['status' => 'User ID set successfully']));
    }

    private function handleMessage(ConnectionInterface $from, $data)
    {
        if (!isset($data['targetUser']) || !isset($data['message'])) {
            $from->send(json_encode(['error' => 'targetUser and message are required']));
            return;
        }

        $targetUser = $data['targetUser'];
        $message = $data['message'];

        $recipient = null;

        foreach ($this->clients as $client) {
            if ($this->clients[$client]['userId'] === $targetUser) {
                $recipient = $client;
                break;
            }
        }

        if ($recipient) {
            $recipient->send(json_encode([
                'from' => $from->userId,
                'message' => $message
            ]));
            echo "Message from {$from->resourceId} to user {$targetUser}: {$message}\n";
        } else {
            $from->send(json_encode(['error' => 'Recipient not connected']));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
