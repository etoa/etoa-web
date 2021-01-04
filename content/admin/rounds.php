<h1>Runden</h1>
<?php
if (isset($_POST['submit']) || isset($_POST['submit_new'])) {
    if (count($_POST['round_name']) > 0) {
        foreach ($_POST['round_name'] as $k => $v) {
            if ($_POST['round_name'][$k] != "" && $_POST['round_url'][$k] != "") {
                dbquery("
                    UPDATE " . dbtable('rounds') . "
                    SET
                        round_name='" . $_POST['round_name'][$k] . "',
                        round_url='" . $_POST['round_url'][$k] . "',
                        round_active='" . $_POST['round_active'][$k] . "'
                    WHERE
                        round_id=$k
                ;");
            }
        }
        echo message("info", "Änderungen gespeichert!");
    }
    if (isset($_POST['round_del']) && count($_POST['round_del']) > 0) {
        foreach ($_POST['round_del'] as $k => $v) {
            if ($v == 1) {
                dbquery("
                    DELETE FROM " . dbtable('rounds') . "
                    WHERE round_id=$k
                ;");
            }
        }
    }
}
if (isset($_POST['submit_new'])) {
    dbquery("
        INSERT INTO " . dbtable('rounds') . "
        (round_active)
        VALUES(0)
    ;");
}

$res = dbquery("
    SELECT *
    FROM " . dbtable('rounds') . "
    ORDER BY
        round_active DESC,
        round_name ASC
;");
echo "<form action=\"?page=$page\" method=\"post\">";
if (mysql_num_rows($res) > 0) {
    echo "<table class=\"tbl\" style=\"width:750px;\">";
    echo "<tr><th>Name:</th><th>Url:</th><th>Anzeigen:</th><th>Löschen:</th></tr>";
    while ($arr = mysql_fetch_array($res)) {
        echo "<tr>
            <td>
                <input type=\"text\" name=\"round_name[" . $arr['round_id'] . "]\" value=\"" . $arr['round_name'] . "\" size=\"20\" />
            </td>
            <td>
                <input type=\"text\" name=\"round_url[" . $arr['round_id'] . "]\" value=\"" . $arr['round_url'] . "\" size=\"50\" />
            </td>
            <td>
                <label><input type=\"radio\" name=\"round_active[" . $arr['round_id'] . "]\" value=\"1\"" . ($arr['round_active'] == 1 ? "checked=\"checked\"" : '') . " /> Ja</label>
                <label><input type=\"radio\" name=\"round_active[" . $arr['round_id'] . "]\" value=\"0\"" . ($arr['round_active'] == 0 ? "checked=\"checked\"" : '') . "/> Nein</label>
            </td>
            <td>
                <input type=\"checkbox\" name=\"round_del[" . $arr['round_id'] . "]\" value=\"1\"/>
            </td>
        </tr>";
    }
    echo "</table><br/><input type=\"submit\" name=\"submit\" value=\"Übernehmen\" /> ";
} else {
    echo "<i>Keine Runden vorhanden!</i><br/><br/>";
}
echo "<input type=\"submit\" name=\"submit_new\" value=\"Neue Runde\" /></form>";
