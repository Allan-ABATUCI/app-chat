<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Chat Test</title>
</head>


<body>
    <h1>WebSocket Chat Test</h1>
    <textarea id="log" cols="50" rows="10" readonly></textarea><br>
    <input id="message" type="text" placeholder="Type your message here">
    <button onclick="sendMessage()">Send</button>
</body>

<script>
    var conn = new WebSocket('ws://localhost:8081/chat');


    conn.onopen = function(e) {
        -
        conn.send(JSON.stringify(

        ));
    };

    conn.onmessage = function(e) {
        console.log(e.data);
    };
</script>

</html>