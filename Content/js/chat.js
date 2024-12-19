const conn = new WebSocket('ws://localhost:8080');

conn.onopen = function () {
    console.log('Connecté au serveur WebSocket');
};

conn.onmessage = function (event) {
    const data = JSON.parse(event.data);
    console.log('Message reçu :', data);
};

function sendPrivateMessage(to, message) {
    const payload = {
        type: 'private',
        to: to,
        message: message
    };
    conn.send(JSON.stringify(payload));
}

// Exemple : envoyer un message à l'utilisateur ayant l'ID 3
// sendPrivateMessage(3, 'Salut, utilisateur 3 !');
