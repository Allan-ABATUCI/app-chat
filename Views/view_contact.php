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
            <?php foreach($contacts as $c=>$row):?>
            <li class="contact-item">
                <div class="contact-info">
                    <div class="contact-name"><?php echo $row['username']??''?></div>
                    <div class="last-message"><?php echo $row['lastmessage'][$c]['content']??'' ?></div>
                </div>
                <div class="timestamp"><?php echo $row['lastmessage'][$c]['created_at']??''?></div>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</main>
</body>

</html>
<?php require_once 'view_end.php'; ?>