<h1>Hilfe-Artikel</h1>
<?PHP

	if (isset($_POST['new_submit']) && $_POST['new_title']!="")
	{
		dbquery("INSERT INTO 
		articles 
		(
			title,
			changed
		)
		VALUES
		(
			'".addslashes($_POST['new_title'])."',
			".time()."
		);");
		$_GET['id']=mysql_insert_id();
	}

	if (isset($_POST['submit']) && $_POST['text_text']!="" && $_POST['text_id']>0)
	{
		dbquery("UPDATE 
		articles 
		SET 
		text='".addslashes($_POST['text_text'])."', 
		title='".addslashes($_POST['title'])."',
		changed=".time()." 
		WHERE id=".$_POST['text_id'].";");
		$_GET['id']=$_POST['text_id'];
	}

	if (isset($_POST['del']) && $_POST['text_id']>0)
	{
		dbquery("DELETE FROM articles
		WHERE id=".$_POST['text_id'].";");
	}

	$res=dbquery("SELECT * FROM articles;");
	echo "<form action=\"?page=$page\" method=\"post\">";
	if (mysql_num_rows($res)>0)
	{
		echo "<table class=\"tbl\">";
		echo "<tr><th>Titel:</th><td>
		<select name=\"text_select\" id=\"text_select\" onchange=\"document.location='?page=$page&id='+this.options[this.selectedIndex].value\">";
		$first=null;
		while ($arr=mysql_fetch_array($res))
		{
			if ($first==null)
				$first=$arr;
			echo "<option value=\"".$arr['id']."\"";
			if (isset($_GET['id']) && $_GET['id']==$arr['id']) 
			{
				echo " selected=\"selected\"";
				$first=$arr;
			}
			echo ">".$arr['title']."</option>";
		}
		echo "</select> <input type=\"button\" value=\"Anzeigen\" onclick=\"document.location='?page=$page&id='+document.getElementById('text_select').options[document.getElementById('text_select').selectedIndex].value\" /></td></tr>";
		echo "<tr><th>Name:</th><td><input type=\"text\" size=\"40\" name=\"title\" value=\"".$first['title']."\" /></td></tr>";
		echo "<tr><th>Letzte Änderung:</th><td>".df($first['changed'])."</td></tr>";
		echo "<tr><td colspan=\"2\"><textarea name=\"text_text\" rows=\"28\" cols=\"100\">".stripslashes($first['text'])."</textarea>
		<br/><a href=\"http://daringfireball.net/projects/markdown/syntax\" target=\"_blank\">Syntax</a> &nbsp; 
		<a href=\"http://michelf.com/projects/php-markdown/extra/\" target=\"_blank\">Erweiterte Syntax</a>
		</td></tr>";
		echo "</table><br/>
		<input type=\"hidden\" name=\"text_id\" value=\"".$first['id']."\" />
		<input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" />";
		echo "&nbsp; <input type=\"button\" value=\"Vorschau\" onclick=\"window.open('http://www.etoa.ch/help/?page=article&amp;article=".$first['id']."');\" />";
		echo "&nbsp; <input type=\"submit\" name=\"del\" value=\"Löschen\" onclick=\"return confirm('Wirklich löschen?')\" /> ";
	}
	else
	{
		echo "<i>Keine Texte vorhanden!</i><br/><br/>";
	}
	echo "<h3>Neuer Artikel</h3>
	Artikel mit Titel <input type=\"text\" size=\"40\" name=\"new_title\" value=\"\" />
	&nbsp;<input type=\"submit\" name=\"new_submit\" value=\"Erstellen\" />";
	echo "</form>";
?>