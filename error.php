<?PHP
// Used as error page if a file is not found or access has been denied
// see .htaccess for configuration

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
	

?><!DOCTYPE html>
<html>
	<meta charset="UTF-8" />
	<title><?=$errname?></title>
	<style type="text/css"><!--/*--><![CDATA[/*><!--*/ 
		body { 
			color: #fff; 
			background: #0E0E0E; 
			font-family: arial, helvetica, verdana;
			font-size:10pt;
		}		
		h1 {
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
		p, address {
			margin-left: 3em;
		}
		span {
			font-size: smaller;
		}
		.message {
			text-align: center;
			width: 500px;
			margin: 100px auto 50px;
		}
		.links {
			width: 650px;
			margin: 0px auto;
			text-align: center;
		}
		.links ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
		}
		.links ul li {
			display: inline-block;
			margin: 0;
			padding: 0;
		}
		.links ul li a {
			text-decoration: none;
			border: 1px solid #3D3D3D;
			border-radius: 2px;
			padding: 8px;
			margin: 5px;
			background: #2C2C2C;
			color: #ccc;
		}
		.links ul li a:hover {
			color: #fff;
			background: #262626;
			border: 1px solid #373737;
		}
	/*]]>*/--></style>
	</head>
	<body>
		<div class="message">
			<p><img src="http://etoa.ch/site/images/logo.gif" alt="logo"/></p>
			<h1><?PHP echo $errname; ?>!</h1>
			<p><?PHP echo $errtext;?></p>
		</div>
		<div class="links">
			<ul>
				<li><a href="javascript:history.back();">Zurück zur vorherigen Seite</a></li>
				<li><a href="http://etoa.ch">Startseite</a></li>
				<li><a href="http://forum.etoa.ch">Forum</a></li>
				<li><a href="http://etoa.ch/help">Hilfecenter</a></li>
			</ul>
		</div>
	</body>
</html>