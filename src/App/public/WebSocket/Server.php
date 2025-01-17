<?php

namespace App\public\WebSocket;

use Ratchet\App;
use App\public\WebSocket\Chat;


class Server
{
    public function run()
    {
        $handler = new Chat();

        $app = new App('localhost', 8081, '0.0.0.0');
        $app->route('/chat', $handler, ['*']);
        $app->run();
    }
}
