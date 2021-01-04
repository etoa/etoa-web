<h1>Werbung</h1>
<?php
if (isset($_POST['submit'])) {
    set_config('adds', $_POST['adds']);
    echo message("info", "Gespeichert!");
}
?>
<form action="?page=<?= $page ?>" method="post">
    Reches Vertikalbanner:<br />
    <textarea name="adds" rows="30" cols="120"><?= get_config('adds', '') ?></textarea>
    <br /><br />
    <input type="submit" name="submit" value="Übernehmen" />
</form>
