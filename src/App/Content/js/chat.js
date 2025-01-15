// JavaScript WebSocket Client for Private Conversations

const socketUrl = "ws://localhost:8081/chat"; // Replace with your WebSocket server URL
let socket;

// Function to connect to the WebSocket server
function connectWebSocket() {
    socket = new WebSocket(socketUrl);

    socket.onopen = () => {
        console.log("Connected to WebSocket server");
    };

    socket.onmessage = (event) => {
        const data = JSON.parse(event.data);
        if (data.error) {
            console.error("Error from server:", data.error);
        } else {
            console.log("Message received:", data);
        }
    };

    socket.onclose = () => {
        console.log("Disconnected from WebSocket server");
    };

    socket.onerror = (error) => {
        console.error("WebSocket error:", error);
    };
}

// Function to initiate a private conversation
function initiatePrivateConversation(userId) {
    if (socket && socket.readyState === WebSocket.OPEN) {
        const message = {
            action: "initiate",
            targetUser: userId
        };
        socket.send(JSON.stringify(message));
        console.log(`Initiating private conversation with user: ${userId}`);
    } else {
        console.error("WebSocket connection is not open");
    }
}

// Function to send a private message
function sendPrivateMessage(userId, message) {
    if (socket && socket.readyState === WebSocket.OPEN) {
        const payload = {
            targetUser: userId,
            message: message
        };
        socket.send(JSON.stringify(payload));
        console.log(`Message sent to user ${userId}: ${message}`);
    } else {
        console.error("WebSocket connection is not open");
    }
}

// Example usage:
connectWebSocket();

// Wait for connection to open before interacting
setTimeout(() => {
    initiatePrivateConversation("user123");
    sendPrivateMessage("user123", "Hello, this is a private message!");
}, 1000);
