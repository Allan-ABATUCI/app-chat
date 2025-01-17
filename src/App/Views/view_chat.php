<?php
$dest = $_GET['dest'];
?>

<!DOCTYPE html>
<html lang="fr">
<!-- Structure HTML depuis ci-dessus -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test WebSocket Chat</title>
</head>

<body>
    <h1>Test WebSocket Chat</h1>

    <!-- Affichage de l'ID du destinataire depuis le GET -->
    <p id="recipientInfo">ID du destinataire : <?php echo $dest; ?></p>

    <!-- Journal de chat -->
    <textarea id="log" cols="50" rows="10" readonly></textarea><br>

    <!-- Champ de saisie pour le message -->
    <input id="message" type="text" placeholder="Tapez votre message ici" required><br><br>

    <button onclick="sendMessage()">Envoyer</button>

    <script>
        // Vérifier si la page a reçu l'ID du destinataire via GET
        window.onload = function() {
            // Récupérer l'ID du destinataire depuis une variable GET (présumée envoyée du backend)
            const recipientId = "<?php echo $dest; ?>";

            // Afficher l'ID du destinataire sur la page
            if (recipientId) {
                document.getElementById("recipientInfo").textContent = "ID du destinataire : " + recipientId;
                initiateWebSocket(recipientId); // Initier la connexion WebSocket avec l'ID du destinataire
            } else {
                document.getElementById("recipientInfo").textContent = "Aucun ID de destinataire fourni.";
            }
        };

        var conn;

        // Fonction pour initier la connexion WebSocket avec l'ID du destinataire
        function initiateWebSocket(recipientId) {
            conn = new WebSocket('ws://localhost:8081/chat?userId=<?php echo $_SESSION['id'] ?? '' ?>' + '&dest=' + recipientId); // Ouvrir la connexion WebSocket avec l'ID utilisateur

            conn.onopen = function(e) {
                console.log("Connexion WebSocket établie !");
            };

            conn.onmessage = function(e) {
                console.log("Message reçu : " + e.data);
                document.getElementById("log").value += e.data + '\n'; // Ajouter le message reçu au journal
            };

            conn.onerror = function(e) {
                console.error("Erreur WebSocket :", e);
            };

            conn.onclose = function(e) {
                console.log("Connexion WebSocket fermée.");
            };
        }

        // Fonction pour envoyer un message au destinataire
        function sendMessage() {
            const message = document.getElementById("message").value; // Récupérer le message saisi

            // Si le message n'est pas vide
            if (message.trim() !== "") {
                const messageData = {
                    dest: "<?php echo $dest; ?>", // Récupérer l'ID du destinataire depuis PHP (GET)
                    message: message
                };

                // Envoyer le message via la connexion WebSocket
                conn.send(JSON.stringify(messageData));

                // Optionnellement, vider le champ après l'envoi du message
                document.getElementById("message").value = '';
            }
        }
    </script>
</body>

</html>