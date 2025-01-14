<?php
namespace Chatapp\Websocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $rooms;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        //décode data qu'on envoie par le client
        $data = json_decode($msg, true);
        //renvoie erreur si room pas spécifiée ou il y a pas de message peut être l'nelever en prod?
        if (isset($data['room']) || isset($data['message'])) {
            $from->send(json_encode(['error' => 'format message invalid']));
        }

        $room = $data['room'];
        $message = $data['message'];

        //
        if (!isset($this->rooms[$room])) {
            $from->send(json_encode(['error' => 'Room n\'existe pas']));
            return;
        }


        echo sprintf(
            'Connection %d envoie message "%s" à room %d ' . "\n"
            ,
            $from->resourceId,
            $msg,
            $room
        );

        //
        foreach ($this->rooms[$room] as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send(json_encode([
                    'room' => $room,
                    'message' => $message,
                    'from' => $from->ressourceId
                ]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
$app = new Ratchet\App('localhost', 8080, '0.0.0.0');
$app->route('/chat', new Chat(), array('*')); // Allow any origin for simplicity
$app->run();