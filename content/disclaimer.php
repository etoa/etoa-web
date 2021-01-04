<br />
<?PHP

use App\Support\TextUtil;

echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxTitle\">Haftungsausschluss </div>";
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxData\">";
echo TextUtil::get("disclaimer");
echo "</div>";
echo "<div class=\"boxLine\"></div>";
