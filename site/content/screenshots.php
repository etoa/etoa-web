<?PHP
		echo "<br/><div class=\"boxLine\"></div>";
		echo "<div class=\"boxTitle\"><h2>Bilder von EtoA</h2></div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\"><br/>";
		
		$dir = "site/images/screenshots";

		$files = array('allianz','auktion','bauhof','hilfe','planet','raumkarte','userstatistik','wirtschaft');

		$cnt=0;
		foreach ($files as $f)
		{
			$cnt++;
				$file = $dir."/".$f.".jpg";
				$file_small = $dir."/".$f."_small.jpg";
				$fs = getimagesize($file);
				$name = ucfirst($f);
				echo "&nbsp; &nbsp; &nbsp;
				<a href=\"".$file."\" onclick=\"return hs.expand(this)\">
				<img src=\"".$file_small."\" style=\"width:250px;height:187px;\" alt=\"".$name."\" title=".$name."/></a> &nbsp; 
				<div class=\"highslide-caption\">".$name."</div>";

			if ($cnt==2)
			{
				$cnt=0;
				echo "<br/><br/>";
			}
		}
		
		echo "</div>";
		echo "<div class=\"boxLine\"></div><br/>";		
?>