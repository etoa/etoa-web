<?php
    $site_title = 'Runden';
?>

<h1>Runden</h1>
Bitte wähle eine Runde aus der Navigation, um deren Hilfe anzuzeigen!<br/>
<h2>Status und Links</h2>

<?php foreach (App\Models\Round::active() as $round): ?>
    <fieldset style="float:left;width:250px;margin-right:20px;">
        <legend><?= $round->name ?></legend>
        <ul>
            <li><a target="_blank" href="<?= loginRoundUrl($round, 'register') ?>">Anmelden</a></li>
            <li><a target="_blank" href="<?= loginRoundUrl($round, 'pwforgot') ?>">Passwort vergessen</a></li>
            <li><a target="_blank" href="<?= loginRoundUrl($round, 'contact') ?>">Admin kontaktieren</a></li>
        </ul>
    </fieldset>
<?php endforeach; ?>
