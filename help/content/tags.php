<div id="innercontent">
<h1>Tags</h1>

<?PHP
	if (isset($_GET['id']) && $_GET['id']>0)
	{
		$sql="SELECT
			name
		FROM 
			help_tag
		WHERE
			id=".intval($_GET['id']).";";
		$res=dbquery($sql);	
		if (mysql_num_rows($res)>0)
		{		
			$arr = mysql_fetch_array($res);
			$tagName = $arr['name'];

			echo "<h2>FAQ</h2>";
			if (isset($_GET['userid']) && $_GET['userid']>0)
			{			
				$sql="SELECT
					faq_question,
					faq_id
				FROM 
					faq f
				INNER JOIN
					help_tag_rel r ON f.faq_id=r.item_id
					AND r.domain = 'faq'
					AND r.tag_id=".intval($_GET['id'])."
				LEFT JOIN faq_comments a ON a.comment_faq_id=f.faq_id 
				WHERE 
				(comment_user_id=".$_GET['userid']."
				OR faq_user_id=".$_GET['userid'].")
				ORDER BY faq_question;";		

				$ures = dbquery("
				SELECT
					u.*
				FROM
					wcf1_user u
				WHERE
					u.userID='".$_GET['userid']."'
				;");
				if (mysql_num_rows($ures)>0)
				{
					$uarr = mysql_Fetch_assoc($ures);
				}
			}
			else
			{
				$sql="SELECT
					faq_question,
					faq_id
				FROM 
					faq f
				INNER JOIN
					help_tag_rel r
				ON f.faq_id=r.item_id
				AND r.domain = 'faq'
				AND r.tag_id=".intval($_GET['id'])."
				ORDER BY faq_question;";
			}
			$res=dbquery($sql);
			$c = mysql_num_rows($res);
			if ($c>0)
			{
				echo '<b>'.$c.'</b> Einträge getaggt mit <b>'.$tagName.'</b>';
				if (isset($uarr))
				{
					echo ' mit Beiträgen von <a href="?page=user&amp;id='.$uarr['userID'].'">'.$uarr['username'].'</a>';
				}	
				echo ':<br/><br/>';
				echo "<ul>";
				while ($arr=mysql_fetch_array($res))
				{
					echo "<li><a href=\"?page=faq&amp;faq=".$arr['faq_id']."\">".$arr['faq_question']."</a></li>";
				}
				echo '</ul><br/>';
			}
			else
			{
				echo "<i>Deine Suche nach <b>".$tagName."</b> ergab keine Treffer!</i><br/><br/>";
			}			
			
			echo "<h2>Wiki</h2>";
				$sql="SELECT * FROM (SELECT
					w.title,
					w.hash,
					w.id
				FROM 
					articles w
				INNER JOIN
					help_tag_rel r
				ON w.id=r.item_id
				AND r.domain = 'wiki'
				AND r.tag_id=".intval($_GET['id'])."
				ORDER BY w.hash,w.rev DESC) a
				GROUP BY a.hash
				ORDER BY title
				;";
			$res=dbquery($sql);
			$c = mysql_num_rows($res);
			if ($c>0)
			{
				echo '<b>'.$c.'</b> Einträge getaggt mit <b>'.$tagName.'</b>';
				echo ':<br/><br/>';
				echo "<ul>";
				while ($arr=mysql_fetch_array($res))
				{
					echo "<li><a href=\"?page=article&amp;article=".$arr['hash']."\">".$arr['title']."</a></li>";
				}
				echo '</ul><br/>';
			}
			else
			{
				echo "<i>Deine Suche nach <b>".$tagName."</b> ergab keine Treffer!</i><br/><br/>";
			}				
			
			
			
		}
		else
		{
			echo "<i>Dieser Tag existiert nicht!</i><br/><br/>";
		}
	}
	else
	{
		$tags = array();

		$res = dbquery("SELECT t.id,t.name,COUNT(r.item_id) AS cnt
		FROM help_tag t
		INNER JOIN help_tag_rel r ON t.id=r.tag_id
		INNER JOIN faq f ON r.item_id=f.faq_id
		GROUP BY t.id
		ORDER BY t.name
		;");
		while ($arr = mysql_fetch_assoc($res))
		{
			$tags[$arr['name']] = $arr;
		}		
		$res = dbquery("SELECT * FROM (SELECT
					w.title,
					w.hash,
					w.id
				FROM 
					articles w
				INNER JOIN
					help_tag_rel r
				ON w.id=r.item_id
				AND r.domain = 'wiki'

				ORDER BY w.hash,w.rev DESC) a
				GROUP BY a.hash
				ORDER BY title
		;");
		while ($arr = mysql_fetch_assoc($res))
		{
			$tags[$arr['name']] = $arr;
		}			
		
		foreach ($tags as $arr) 
		{
			if (!isset($max_qty)) $max_qty = $arr['cnt'];
			if (!isset($min_qty)) $min_qty = $arr['cnt'];
			$max_qty = max($arr['cnt'],$max_qty);
			$min_qty = min($arr['cnt'],$min_qty);
		}
		$max_size = 28; // max font size in pixels
		$min_size = 13; // min font size in pixels
		$spread = $max_qty - $min_qty;
		if ($spread == 0) { 
				$spread = 1;
		}
		$step = ($max_size - $min_size) / ($spread);
		foreach ($tags as $arr) 
		{
			$size = round($min_size + (($arr['cnt'] - $min_qty) * $step));
			echo "<a style=\"font-size: ".$size."px\" href=\"?page=tags&amp;id=".$arr['id']."\" title=\"".$arr['cnt']." Einträge getaggt mit '".$arr['name']."'\">".$arr['name']."</a> ";
		}		
	}
?>
</div>