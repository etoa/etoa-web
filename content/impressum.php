<?PHP

use App\Support\TextUtil;

echo "<br/><div class=\"boxLine\"></div>";
echo "<div class=\"boxTitle\">Wer für dieses Projekt verantwortlich ist:</div>";
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxData\">";
echo TextUtil::get("impressum");
echo "</div>";
echo "<div class=\"boxLine\"></div>";
