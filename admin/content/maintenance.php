<h1>Wartungsmodus Login-Seite</h1>
<?php
if (isset($_POST['submit'])) {
    set_config('maintenance_mode', $_POST['config_value']);
    echo message("info", "Gespeichert!");
}

$maintenance_mode = get_config('maintenance_mode', 0);
echo "<form action=\"?page=$page\" method=\"post\">";
echo "<label><input type=\"radio\" value=\"1\" name=\"config_value\" " . ($maintenance_mode == 1 ? ' checked="checked"' : '') . " /> <span style=\"color:#f00\">Aktiv</span></label><br/>
    <label><input type=\"radio\" value=\"0\" name=\"config_value\" " . ($maintenance_mode == 0 ? ' checked="checked"' : '') . " /> <span style=\"color:#0f0\">Inaktiv</span></label>";
echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
echo "</form>";
