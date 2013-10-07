<h1>Admin-User</h1>
<?PHP
	if ($_GET['action']=="new")
	{
		echo "<form action=\"?page=$page\" method=\"post\">";
		echo "<table class=\"tbl\" style=\"margin:0px auto\">";
		echo "<th>Nick:</th><td><input type=\"text\" name=\"".LOGIN_USER_NICK_FIELD."\" /></td></tr>";
		echo "<th>E-Mail:</th><td><input type=\"text\" name=\"user_email\" /></td></tr>";
		echo "<th>Passwort:</th><td><input type=\"password\" name=\"".LOGIN_USER_PW_FIELD."\" /></td></tr>";
		echo "</table><br/><input type=\"submit\" name=\"submit_new\" value=\"Speichern\" /> ";
		echo "<input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"&Uuml;bersicht\" /></form>";
	}
	elseif ($_GET['edit']>0)
	{
		echo "<form action=\"?page=$page\" method=\"post\">";
		$res=dbquery("SELECT * FROM ".LOGIN_USER_TABLE." WHERE user_id=".$_GET['edit'].";");
		if (mysql_num_rows($res)>0)
		{
			$arr=mysql_fetch_array($res);
			echo "<table class=\"tbl\" style=\"margin:0px auto\">";
			echo "<th>Nick:</th><td><input type=\"text\" name=\"".LOGIN_USER_NICK_FIELD."\" value=\"".$arr[LOGIN_USER_NICK_FIELD]."\" /></td></tr>";
			echo "<th>E-Mail:</th><td><input type=\"text\" name=\"user_email\" value=\"".$arr['user_email']."\" /></td></tr>";
			echo "<th>Neues Passwort:</th><td><input type=\"password\" name=\"".LOGIN_USER_PW_FIELD."\" value=\"\" /></td></tr>";
			echo "<th>Letzte Aktivit&auml;t:</th><td>".df($arr['user_atime'])."</td></tr>";
			echo "<th>IP:</th><td>".$arr['user_ip']."</td></tr>";
			echo "<th>Host:</th><td>".$arr['user_host']."</td></tr>";
			echo "</table><br/><input type=\"submit\" name=\"submit_edit\" value=\"Speichern\" /> ";
			echo "<input type=\"hidden\" name=\"user_id\" value=\"".$arr['user_id']."\" />";
		}
		else
			echo "<i>ID nicht gefunden!</i><br/><br/>";		
		echo "<input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"&Uuml;bersicht\" /></form>";
	}
	else
	{
		if ($_POST['submit_new']!="")
		{
			dbquery("INSERT INTO ".LOGIN_USER_TABLE." (".LOGIN_USER_NICK_FIELD.",user_email,".LOGIN_USER_PW_FIELD.") VALUES ('".$_POST[LOGIN_USER_NICK_FIELD]."','".$_POST['user_email']."','".md5($_POST[LOGIN_USER_PW_FIELD])."');");
			echo "Neuer User gespeichert!<br/><br/>";
		}		
		if ($_POST['submit_edit']!="")
		{
			if ($_POST[LOGIN_USER_PW_FIELD]!="")
				$pwstring = ",".LOGIN_USER_PW_FIELD."='".md5($_POST[LOGIN_USER_PW_FIELD])."'";
			else
				$pwstring="";
			dbquery("UPDATE ".LOGIN_USER_TABLE." SET ".LOGIN_USER_NICK_FIELD."='".$_POST[LOGIN_USER_NICK_FIELD]."',user_email='".$_POST['user_email']."' $pwstring WHERE user_id=".$_POST['user_id'].";");
			echo "&Auml;nderungen &uuml;bernommen!<br/><br/>";
		}
		if ($_GET['del']>0 && $_GET['del']!=$s->var['id'])
		{
			dbquery("DELETE FROM ".LOGIN_USER_TABLE." WHERE user_id=".$_GET['del'].";");
			echo "User gel&ouml;scht!<br/><br/>";
		}		

		$res=dbquery("SELECT * FROM ".LOGIN_USER_TABLE.";");
		echo "<table class=\"tbl\"><th>Nick</th><th>E-Mail</th><th>Letzte Aktivit&auml;t</th><th>IP</th><th>Aktion</th></tr>";
		while ($arr=mysql_fetch_array($res))
		{
			echo "<tr><td>".$arr[LOGIN_USER_NICK_FIELD]."</td>";	
			echo "<td>".$arr['user_email']."</td>";	
			echo "<td>".df($arr['user_atime'])."</td>";	
			echo "<td>".$arr['user_ip']."</td>";	
			echo "<td><a href=\"?page=$page&amp;edit=".$arr['user_id']."\">Bearbeiten</a>";
			if ($arr['user_id']!=$s->var['id'])
				echo " <a href=\"?page=$page&amp;del=".$arr['user_id']."\" onclick=\"return confirm('Soll \'".$arr[LOGIN_USER_NICK_FIELD]."\' wirklich gel&ouml;scht werden?')\">L&ouml;schen</a>";
			echo "</td></tr>";
		}
		echo "</table><br/>";
		echo "<input type=\"button\" onclick=\"document.location='?page=$page&action=new'\" value=\"Neuer User\" /></form>";
		
	}
?>