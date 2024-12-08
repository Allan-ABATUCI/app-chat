<?php require_once 'view_begin.php'; ?>
<header>
    <h1>Messagerie Instantanée</h1>
</header>
<main>
    <div class="contacts">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un contact...">
        </div>
        <ul class="contact-list">
            <li class="contact-item">
                <div class="contact-info">
                    <div class="contact-name">BRADLEY</div>
                    <div class="last-message">Message récent ici...</div>
                </div>
                <div class="timestamp">10:30</div>
            </li>
            <li class="contact-item">
                <div class="contact-info">
                    <div class="contact-name">MANEL</div>
                    <div class="last-message">Message récent ici...</div>
                </div>
                <div class="timestamp">09:45</div>
            </li>
            <li class="contact-item">
                <div class="contact-info">
                    <div class="contact-name">ALLAN</div>
                    <div class="last-message">Message récent ici...</div>
                </div>
                <div class="timestamp">08:15</div>
            </li>
        </ul>
    </div>
</main>
</body>

</html>
<?php require_once 'view_end.php'; ?>