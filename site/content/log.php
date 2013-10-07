<?PHP

echo "<h1>Logs</h1>";

$res=dbquery("SELECT * FROM logs ORDER BY log_timestamp DESC;");
if (mysql_num_rows($res)>0)
{
	echo "<table style=\"width:100%\"><tr><th class=\"tbltitle\">Zeit</th><th class=\"tbltitle\">Datum</th><th class=\"tbltitle\">Text</th></tr>";
	while ($arr=mysql_fetch_array($res))
	{
		echo "<tr><td class=\"tbldata\">".date("H:i",$arr['log_timestamp'])."</td>";
		echo "<td class=\"tbldata\">".date("d.m.Y",$arr['log_timestamp'])."</td>";
		echo "<td class=\"tbldata\">".text2html($arr['log_text'])."</td></tr>";
	}
	echo "</table>";
}
else
	echo "<i>Keine Logs vorhanden!</i>";