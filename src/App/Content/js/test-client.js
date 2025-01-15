const WebSocket = require('ws');

const ws = new WebSocket('ws://localhost:8080?token=valid_token_123');

ws.on('open', () => {
    console.log('Connected to the server');
    ws.send(JSON.stringify({ action: 'join', room: 'general' }));

    setTimeout(() => {
        ws.send(JSON.stringify({ room: 'general', message: 'Hello Room!' }));
    }, 1000);
});

ws.on('message', (data) => {
    console.log('Received:', data);
});

ws.on('close', () => {
    console.log('Disconnected from the server');
});

ws.on('error', (error) => {
    console.error('Error:', error);
});
