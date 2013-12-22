<?PHP

	$e = isset($_GET['e']) ? $_GET['e'] : 404;

	switch ($e)
	{
		case 403:
			$errname = "Fehler 403 - Zugriff verweigert";
			$errtext = "Der Zugriff auf das angeforderte Verzeichnis ist nicht m&ouml;glich.
    Entweder ist kein Index-Dokument vorhanden oder das Verzeichnis
    ist zugriffsgesch&uuml;tzt.";
			break;		
		case 401:
			$errname = "Fehler 401 - Authentisierung fehlgeschlagen";
			$errtext = "Der Server konnte nicht verifizieren, ob Sie autorisiert sind,
    auf diese URL zuzugreifen.
    Entweder wurden falsche Referenzen (z.B. ein falsches Passwort)
    angegeben oder ihr Browser versteht nicht, wie die geforderten
    Referenzen zu &uuml;bermitteln sind.<br/>
    Sofern Sie f&uuml;r den Zugriff berechtigt sind, &uuml;berpr&uuml;fen
    Sie bitte die eingegebene User-ID und das Passwort und versuchen Sie
    es erneut.";
			break;		
		default:	
			$errname = "Fehler 404 - Seite nicht gefunden";
			$errtext = "Der angeforderte URL konnte auf dem Server nicht gefunden werden.
		    Sofern Sie den URL manuell eingegeben haben,
		    &uuml;berpr&uuml;fen Sie bitte die Schreibweise und versuchen Sie es erneut.";
	}
	

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<head>
<title>Seite nicht gefunden!</title>
<style type="text/css"><!--/*--><![CDATA[/*><!--*/ 
    body { 
    	color: #fff; 
    	background: #000; 
    	font-family: arial, helvetica, verdana;
    	font-size:10pt;
    }
    
    h1	{
    	font-size:12pt;
    }
    a { 
    	color: #ddf; 
    	font-weight:bold;
    }
    a:hover { 
    	color: #aad; 
    	font-weight:bold;
    	text-decoration:underline;
    }
    p, address {margin-left: 3em;}
    span {font-size: smaller;}
/*]]>*/--></style>
</head>

<body>
	<div style="text-align:center;width:500px;margin:100px auto;">
		<img src="http://etoa.ch/site/images/logo.gif" alt="logo"/>


		<h1><?PHP echo $errname; ?>!</h1>
		<p><?PHP echo $errtext;?></p>

</div>
<div style="width:250px;margin:0px auto">
		<ul>
			<li><a href="javascript:history.back();">Zurück zur vorherigen 
			Seite</a></li> <li><a href="http://etoa.ch">EtoA Startseite</a></li>
			<li><a href="http://forum.etoa.ch">EtoA Forum</a></li>					<li><a 
			href="http://etoa.ch/help">EtoA Hilfecenter</a></li>					
			</ul>

</div>

</body>
</html>

