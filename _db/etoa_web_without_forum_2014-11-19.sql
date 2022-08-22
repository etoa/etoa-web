-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 27. Nov 2014 um 23:24
-- Server Version: 5.5.40-cll
-- PHP-Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `dysignch_etoa`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `config`
--

CREATE TABLE `config` (
  `config_id` int(11) NOT NULL,
  `config_name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `config_value` text COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `config`
--

INSERT INTO `config` (`config_id`, `config_name`, `config_value`) VALUES
(226, 'infobox_board_blacklist', '11,57,58,59,4,2,3,82,42,99,8,20,114,116,117,138,139,151,159,152,153,154,155,171,182,183,202,212,213,214,215,217,218,219,220,221,222,223,235,236,237,238,239,250,260,271,272,273,274,286,287,303,313,331'),
(55, 'server_notice', ''),
(225, 'loginadmin_group', '22'),
(130, 'buttons', ''),
(59, 'indexjscript', '<!-- Global site tag (gtag.js) - Google Analytics -->\r\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-4499873-4\"></script>\r\n<script>\r\n  window.dataLayer = window.dataLayer || [];\r\n  function gtag(){dataLayer.push(arguments);}\r\n  gtag(\'js\', new Date());\r\n\r\n  gtag(\'config\', \'UA-4499873-4\');\r\n</script>\r\n'),
(188, 'adds', ''),
(220, 'news_board', '6'),
(222, 'rules_thread', '9904'),
(223, 'ts_link', 'https://discord.gg/w6pKn9c'),
(227, 'status_board', '103'),
(63, 'maintenance_mode', '0'),
(64, 'footer_js', ''),
(221, 'rules_board', '10'),
(228, 'support_board', '21'),
(229, 'forum_mail', 'forum@etoa.ch'),
(230, 'forum_url', 'http://forum.etoa.ch');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dl_files`
--

CREATE TABLE IF NOT EXISTS `dl_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `folder_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `ext` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `dlcount` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `file` (`file`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dl_folders`
--

CREATE TABLE IF NOT EXISTS `dl_folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `dl_folders`
--

INSERT INTO `dl_folders` (`id`, `name`, `parent_id`, `sort`) VALUES
(1, 'Rechner', 0, 0),
(2, 'Banner', 4, 0),
(3, 'Fan-Art', 0, 0),
(4, 'Offiziell', 0, 0),
(5, 'Logo', 4, 0),
(6, 'History', 4, 0),
(7, 'Kampf', 1, 0),
(8, 'Wirtschaft', 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rounds`
--

CREATE TABLE IF NOT EXISTS `rounds` (
  `round_id` int(11) NOT NULL AUTO_INCREMENT,
  `round_name` varchar(255) NOT NULL,
  `round_url` varchar(255) NOT NULL,
  `round_active` int(1) NOT NULL DEFAULT '1',
  `round_startdate` int(30) NOT NULL DEFAULT '0',
  `round_status_online` int(1) unsigned NOT NULL DEFAULT '1',
  `round_status_ping` int(10) unsigned NOT NULL DEFAULT '0',
  `round_status_checked` int(10) unsigned NOT NULL DEFAULT '0',
  `round_status_changed` int(10) unsigned NOT NULL DEFAULT '0',
  `round_status_ip` varchar(15) NOT NULL DEFAULT '127.0.0.1',
  `round_status_port` int(5) unsigned NOT NULL DEFAULT '80',
  `round_status_error` varchar(250) NOT NULL DEFAULT ' ',
  `round_alturl` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`round_id`),
  KEY `round_name` (`round_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Daten für Tabelle `rounds`
--

INSERT INTO `rounds` (`round_id`, `round_name`, `round_url`, `round_active`, `round_startdate`, `round_status_online`, `round_status_ping`, `round_status_checked`, `round_status_changed`, `round_status_ip`, `round_status_port`, `round_status_error`, `round_alturl`) VALUES
(53, '13. Runde', 'http://round13.game.etoa.net', 1, 0, 1, 0, 0, 0, '127.0.0.1', 80, ' ', ''),
(52, '14. Runde', 'http://round14.game.etoa.net', 0, 0, 1, 0, 0, 0, '127.0.0.1', 80, ' ', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `text_id` int(11) NOT NULL AUTO_INCREMENT,
  `text_keyword` varchar(255) NOT NULL,
  `text_text` text NOT NULL,
  `text_last_changes` int(50) NOT NULL DEFAULT '0',
  `text_name` varchar(50) NOT NULL,
  `text_author_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`text_id`),
  UNIQUE KEY `text_keyword` (`text_keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `texts`
--

INSERT INTO `texts` (`text_id`, `text_keyword`, `text_text`, `text_last_changes`, `text_name`, `text_author_id`) VALUES
(2, 'story', 'Im Jahre 2913 nach Erdzeit-Rechnung sucht eine schreckliche Katastrophe die gesamte Milchstrassen-Galaxie heim: Durch das gleichzeitige Kollabieren vieler Sterne im Zentrum der Galaxie entsteht ein riesiges schwarzes Loch, welches die Stabilität der ganzen Galaxis durcheinanderbringt und viele Sternensysteme zerstört. Die Menschheit, und auch viele andere Rassen, die die Galaxie bevölkern und viele Planeten kolonialisiert haben, entscheiden sich für ein waghalsiges Projekt: Sie wollen alle ihre Bewohner, deren Planeten noch nicht zerstört oder vom schwarzen Loch aufgesogen worden sind, in die Nachbargalaxie Andromeda evakuieren und dort eine neue Zivilisation aufbauen. \r\nDie Flüge dorthin dauern sehr lange und noch nie war ein Wesen unserer Galaxie so weit weg geflogen. So machen sich nun viele verschiedene Gruppen auf den Weg, um in der Galaxie Andromeda eine neue, fruchtbare Heimat zu finden und dort ein neues Imperium aufzubauen. Nun liegt es an dir, für eine Gruppe von Flüchtlingen auf einem Planeten eine neue Kolonie zu gründen und daraus eine mächtige Zivilisation zu machen! Aber: es hat nicht unendlich viel Platz in Andromeda, und jede aufsteigende Zivilisation benötigt immer mehr Ressourcen. \r\nEs kann sein, dass es schon bald zu Krieg kommen wird zwischen den verschiedenen Gruppen, darum sei auf der Hut und bereite dich vor. Die Zukunft wird zeigen, wer stark genug ist, sich die Herrschaft über Andromeda zu sichern.', 1209812724, 'Story', 1),
(3, 'disclaimer', '[b]1. Inhalt des Onlineangebotes[/b]\r\n\r\nDer Autor übernimmt keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen. Haftungsansprüche gegen den Autor, welche sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollständiger Informationen verursacht wurden, sind grundsätzlich ausgeschlossen, sofern seitens des Autors kein nachweislich vorsätzliches oder grob fahrlässiges Verschulden vorliegt.\r\nAlle Angebote sind freibleibend und unverbindlich. Der Autor behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.\r\n\r\n[b]2. Verweise und Links[/b]\r\nBei direkten oder indirekten Verweisen auf fremde Webseiten (Hyperlinks), die außerhalb des Verantwortungsbereiches des Autors liegen, würde eine Haftungsverpflichtung ausschließlich in dem Fall in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch möglich und zumutbar wäre, die Nutzung im Falle rechtswidriger Inhalte zu verhindern.\r\nDer Autor erklärt hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukünftige Gestaltung, die Inhalte oder die Urheberschaft der verlinkten/verknüpften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrücklich von allen Inhalten aller verlinkten /verknüpften Seiten, die nach der Linksetzung verändert wurden. Diese Feststellung gilt für alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie für Fremdeinträge in vom Autor eingerichteten Gästebüchern, Diskussionsforen, Linkverzeichnissen, Mailinglisten und in allen anderen Formen von Datenbanken, auf deren Inhalt externe Schreibzugriffe möglich sind. Für illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.\r\n\r\n[b]3. Urheber- und Kennzeichenrecht[/b]\r\nDer Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Grafiken, Tondokumente, Videosequenzen und Texte zu beachten, von ihm selbst erstellte Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurückzugreifen.\r\nAlle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschützten Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschützt sind!\r\nDas Copyright für veröffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten. Eine Vervielfältigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrückliche Zustimmung des Autors nicht gestattet.\r\n\r\n[b]4. Datenschutz[/b]\r\nSofern innerhalb des Internetangebotes die Möglichkeit zur Eingabe persönlicher oder geschäftlicher Daten (Emailadressen, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdrücklich freiwilliger Basis. Die Inanspruchnahme und Bezahlung aller angebotenen Dienste ist - soweit technisch möglich und zumutbar - auch ohne Angabe solcher Daten bzw. unter Angabe anonymisierter Daten oder eines Pseudonyms gestattet. Die Nutzung der im Rahmen des Impressums oder vergleichbarer Angaben veröffentlichten Kontaktdaten wie Postanschriften, Telefon- und Faxnummern sowie Emailadressen durch Dritte zur Übersendung von nicht ausdrücklich angeforderten Informationen ist nicht gestattet. Rechtliche Schritte gegen die Versender von sogenannten Spam-Mails bei Verstössen gegen dieses Verbot sind ausdrücklich vorbehalten.\r\n\r\n[b]5. Rechtswirksamkeit dieses Haftungsausschlusses[/b]\r\nDieser Haftungsausschluss ist als Teil des Internetangebotes zu betrachten, von dem aus auf diese Seite verwiesen wurde. Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollständig entsprechen sollten, bleiben die übrigen Teile des Dokumentes in ihrem Inhalt und ihrer Gültigkeit davon unberührt. ', 1163587873, 'Disclaimer', 1),
(4, 'impressum', '[b]Betreiber[/b]\r\nVerein EtoA Gaming\r\n[mailurl]mail@etoa.ch[/mailurl]\r\n\r\nProgrammfehler bitte im Forum melden. Fragen zu Sperrungen, Meldung von Cheatern, Namensänderungen bitte mit einem Admin der entsprechenden Runde klären.\r\n\r\n[b]Projektleiter / Leiter Entwicklung[/b]\r\nNicolas Perrenoud [url http://www.etoa.ch/forum/index.php?page=User&userID=2]*MrCage*[/url]\r\n[mailurl]mrcage@etoa.ch[/mailurl]\r\n\r\n[b]Credits[/b]\r\nDie Liste der Entwickler befindet sich [url=https://github.com/etoa]hier[/url]', 1312531494, 'Impressum', 1),
(8, 'history', '[h2]Ursprung[/h2]\r\nEtoA war ursprünglich der praktische Teil einer Maturaarbeit am Gymnasium Oberaargau. Die Idee entstand im Aprlil 2004, das Grundspiel wurde im Sommer/Herbst 2004 erstellt. Die Arbeit wurde Ende Oktober 2004 eingereicht und gleichzeitig wurde auch die erste Beta-Phase gestartet. Im Dezember 2004 startete dann die erste reguläre Runde. [url http://www.etoa.ch/pub/Maturaarbeit Nicolas Perrenoud.pdf]Download der Maturaarbeit für Interessierte (PDF)[/url]\r\n\r\n[img]http://www.etoa.ch/pub/history/urgame.jpg[/img] Ur-Version\r\n\r\n[img]http://www.etoa.ch/pub/history/firstlogin.jpg[/img] Erste Loginseite\r\n\r\n[img]http://www.etoa.ch/pub/history/betaplanet.jpg[/img] Planet (Erste Beta)\r\n\r\n[h2]Version 1[/h1]\r\nKommt noch.\r\n\r\n\r\n[h2]Version 2[/h1]\r\nKommt noch.\r\n', 1206551321, 'Entstehungsgeschichte', 0),
(5, 'home', 'Escape to Andromeda ist ein [b]browserbasiertes Sci-Fi Multiplayerspiel[/b]. Es ist eine [b]strategische Weltraumsimulation[/b], bei der Spieler aus der ganzen Welt gleichzeitig gegeneinander antreten können. Du brauchst nur einen normalen Webbrowser um mitzuspielen. Die Anmeldung und das Spiel sind [b]kostenlos[/b], wir finanzieren uns alleine durch Bannerwerbung und Spenden. Klicke [url ?page=features]hier[/url] um Features und Screenshots anzuschauen.\r\n\r\n[url /register][img]public/images/anmelden.jpg[/img][/url]\r\n\r\nBesuche auch unser [url /forum/]Forum[/url], um andere Spieler kennenzulernen, und unsere ständig wachsende [url /help]Hilfe-Seite[/url], um bei Problemen rasch Unterstützung zu erhalten.\r\n\r\n', 1415305946, 'Home', 1),
(6, 'spenden', 'So könnt ihr EtoA finanziell unterstützen\r\n\r\n[b]Schweiz[/b]\r\nEinzahlung auf Postkonto:\r\nEtoA Gaming\r\n3377 Walliswil b. Wangen\r\nKonto: 91-389373-3\r\nClearing-Nr: 9000\r\nKonto wird in EUR geführt, man kann aber ohne Probleme in CHF einzahlen.\r\n\r\n[b]International[/b]\r\nIBAN: CH95 0900 0000 9138 9373 3\r\nBIC: POFICHBEXX\r\nBezeichnung: EtoA Gaming\r\nAdresse der Bank: \r\nSwiss Post - PostFinance\r\nNordring 8\r\n3030 Bern\r\nSwitzerland\r\n', 1313143969, 'Spenden', 1),
(7, 'features', '[h2]Was ist Escape to Andromeda?[/h2]\r\nEtoA ist ein Spiel der intergalaktischen Eroberung. Du startest mit nur einem unentwickelten Planeten und verwandelst diesen in ein mächtiges Imperium, fähig deine hart erarbeiteten Kolonien zu verteidigen.\r\n[list][element][b]Wähle[/b] aus über zehn Rassen eine aus und erhalte dadurch einzigartige Schiffe und taktische Möglichkeiten.[/element][element][b]Erschaffe[/b] eine wirtschaftliche und militärische Infrastruktur um dein Streben nach den neuesten technologischen Errungenschaften zu ermöglichen.[/element][element][b]Führe[/b] Kriege gegen andere Imperien, da du dich gegen andere beim Kampf um die Rohstoffe durchsetzen musst.[/element][element][b]Verhandle[/b] mit anderen Imperatoren und [b]bilde[/b] Allianzen und Bündnisse.[/element][element][b]Besorge[/b] dir dringend benötigte Rohstoffe über den Handel und [b]versteigere[/b] Schiffe im Marktplatz.[/element][element][b]Baue[/b] eine Flotte um deinen Interessen im ganzen Universum Nachdruck zu verleihen.[/element][element][b]Lagere[/b] deine Rohstoffe hinter einer unüberwindbaren planetaren Verteidigung.[/element][element][b]Sammle[/b] Erfahrungspunkte mit einem einzigartigen Spezialschiff und verbessere damit deine ganze Flotte.[/element][/list]EtoA bietet dir grenzenlose Möglichkeiten. \r\nWirst du deine Nachbarn terrorisiern? Oder wirst Du der Rächer der Hilflosen sein?\r\n\r\n[h2]Features[/h2]\r\nHier eine Liste der Features von EtoA:[list][element]Verschiedene Rassen mit unterschiedlichen Boni und eigenen Raumschiffen[/element][element]2D-Raumkarte mit verschiedenen Arten von Planeten und Sternen[/element][element]Grosse Vielfalt an Raumschiffen mit Spezialaktionen[/element][element]Allianz mit eigenen Foren, Umfragen und Rundmail[/element][element]Kriegs- und Bündnissystem[/element][element]Marktplatz und Auktionshaus zum Handeln von Ressourcen und Schiffen[/element][element]Individuell ausbaubares Spezialschiff mit Erfahrungspunktsystem[/element][element]Viele Statistiken und umfangreiche Hilfe[/element][element]Nachrichtensystem[/element][element]Notizblock [/element][element]Freundesliste[/element][element]Bookmarks[/element][element]Laufend neue Features durch kontinuierliche Weiterentwicklung[/element][element]und vieles mehr...[/element][/list]\r\n[h2]Bilder[/h2]\r\nKlicke [url ?page=screenshots]hier[/url] um einige Screenshots von EtoA anzusehen.\r\n', 1361026430, 'Features', 8),
(9, 'weitersagen', 'Hilf mit, EtoA bekannter zu machen und binde unser Banner auf deiner Website ein! Hier findest du den Quellcode um das Banner einzubinden:\r\n\r\n[center][img]pub/banner/banner1.jpg[/img]\r\n\r\n[codebox 2 65]<a href=\\"http://www.etoa.ch\\"><img src=\\"http://www.etoa.ch/pub/banner/banner1.jpg\\" width=\\"468\\" height=\\"60\\" alt=\\"EtoA Online-Game\\" border=\\"0\\" /></a>[/codebox]\r\n\r\n[img]pub/banner/banner2.jpg[/img]\r\n\r\n[codebox 2 65]<a href=\\"http://www.etoa.ch\\"><img src=\\"http://www.etoa.ch/pub/banner/banner2.jpg\\" width=\\"468\\" height=\\"60\\" alt=\\"EtoA Online-Game\\" border=\\"0\\" /></a>[/codebox]\r\n\r\n[img]pub/banner/banner3.jpg[/img]\r\n\r\n[codebox 2 65]<a href=\\"http://www.etoa.ch\\"><img src=\\"http://www.etoa.ch/pub/banner/banner3.jpg\\" width=\\"468\\" height=\\"60\\" alt=\\"EtoA Online-Game\\" border=\\"0\\" /></a>[/codebox][/center]', 1209064360, 'Weitersagen / Banner', 0),
(10, 'privacy', 'EtoA Gaming nimmt den Schutz Ihrer personenbezogenen Daten sehr ernst. Die nachfolgende Erklärung informiert Sie darüber, welche personenbezogenen Daten wir erheben und wie wir diese verarbeiten und nutzen.\r\n\r\n[b]1. Gegenstand dieser Datenschutzerklärung[/b]\r\nDiese Datenschutzerklärung gilt für alle Dienste welche auf unserer Internetseiten www.etoa.ch und den entsprechenden Subdomains angeboten werden.\r\nSoweit nicht anders erwähnt, regelt diese Datenschutzerklärung ausschließlich, wie EtoA Gaming mit Ihren personenbezogenen Daten umgeht. Für den Fall, dass Sie Leistungen Dritter in Anspruch nehmen, gelten ausschließlich die Datenschutzbedingungen dieser Dritten. EtoA Gaming überprüft die Datenschutzbedingungen Dritter nicht.\r\n\r\n[b]2. Verantwortliche Stelle[/b]\r\nVerantwortliche Stelle im Sinne des Bundesdatenschutzgesetzes ist EtoA Gaming gemäss [url http://www.etoa.ch/impressum]Impressum[/url].\r\nBei Fragen zum Datenschutz wenden Sie sich bitte an info@etoa.ch. Unter dieser Adresse können Sie jederzeit Fragen zum Datenschutz stellen, Ihre bei uns gespeicherten Daten abfragen, ändern oder löschen lassen. Alternativ können Sie sich auch per Post an uns über die im Impressum stehende Postadresse wenden. Bitte geben Sie – nach Möglichkeit – den Server und Ihren Spielernamen an.\r\n\r\n[b]3. Erhebung von Daten[/b]\r\nGrundsätzlich können Sie unser Internetangebot aufrufen, ohne uns personenbezogene Daten mitzuteilen. Es steht Ihnen jederzeit frei, sich zu entscheiden, ob Sie uns Ihre Daten mitteilen möchten. Wir erfassen Ihre Daten um den mit Ihnen geschlossenen Nutzervertrag erfüllen zu können. Hierzu zählt die [i]Überwachung der Einhaltung der Nutzungsbedingungen und Spielregeln[/i].\r\nSofern Sie sich auf unseren Internetseiten registrieren oder unseren Newsletter abonnieren möchten, so ist die Angabe von personenbezogenen Daten notwendig. Bei diesen Daten handelt es sich beispielsweise. um [i]Ihren Namen, Ihre Anschrift, Ihre E-Mail-Adresse und sonstige persönliche Daten[/i], die von EtoA Gaming im Einzelfall abgefragt werden. In vielen Fällen ist eine Nutzung der Leistungen von EtoA Gaming unter Verwendung von Pseudonymen möglich. Nutzer werden aufgefordert, von dieser Möglichkeit Gebrauch zu machen.\r\nDarüber hinaus werden einzelne Daten wie z. B. [i]IP-Adresse, Browser-Typ, aufgerufene Seiten, ausgefüllte Formulare und Zugriffszeiten[/i] durch Ihren Computer automatisch an uns übertragen und auf unserem Server gespeichert. Außer zum Zwecke der Verfolgung unzulässiger Verwendung unseres Internetangebotes werten wir diese Daten lediglich zur Statistik und Leistungserstellung aus. Soweit möglich werden die Daten hierbei anonymisiert.\r\n\r\nUm Ihnen unser Internetangebot so angenehm wie möglich zu gestalten, verwenden wir, wie viele andere Unternehmen auch, sog. Cookies. Diese Cookies werden nach Ende Ihres Besuchs auf unseren Webseiten in der Regel automatisch von Ihrer Festplatte gelöscht. Ihr Internetbrowser lässt sich so einstellen, dass Cookies generell abgelehnt werden. Die Nutzung unserer Internetseiten ist dann ggf. aus technischen Gründen nicht oder nur eingeschränkt möglich.\r\nWir erheben, verarbeiten und nutzen personenbezogene Daten – soweit nicht bereits durch gesetzliche Erlaubnistatbestände gestattet – nur mit Ihrer Einwilligung. Soweit die Einwilligung im Rahmen unseres Internetangebots elektronisch erklärt wird, tragen wir den gesetzlichen Hinweispflichten Rechnung.\r\n\r\n[b]4. Verwendung Ihrer Daten[/b]\r\nWir nutzen Ihre personenbezogenen Daten, um unser Dienstleistungsangebot bedarfsgerecht zu gestalten und stetig zu verbessern.\r\n\r\nEtoA Gaming verwendet Ihre persönlichen Informationen für die Begründung, Durchführung und Abwicklung Ihres Nutzungsverhältnisses mit EtoA Gaming.\r\nDarüber hinaus nutzen wir Ihre Daten auch, um mit Ihnen zu kommunizieren. Hierzu gehört, dass wir Sie gegebenenfalls über Neuigkeiten in unserem Dienstleistungsangebot per E-Mail informieren. So verwenden wir Ihre Daten dazu, Sie in unregelmässigen Abständen über Neuigkeiten zu unseren Angeboten zu informieren. Wir werden Sie im Rahmen der jeweiligen Information auf die Möglichkeit des Abbestellens hinweisen.\r\nSchließlich verwenden wir Ihre Daten dazu, einem Missbrauch unserer Webseiten vorzubeugen und unberechtigte Zugriffe zu verfolgen.\r\n\r\n[b]5. Weitergabe von Daten[/b]\r\nSelbstverständlich werden Ihre personenbezogenen Daten nicht an Dritte verkauft. Backups unserer Datenbanken werden bei unseren Hostingprovidern gespeichert. Diese Dienstleister haben nur insoweit Zugang zu Ihren persönlichen Daten, wie dies zur Erfüllung ihrer Aufgaben notwendig ist. Diese Dienstleister sind verpflichtet, Ihre personenbezogenen Daten gemäß dieser Datenschutzerklärung sowie den maßgeblichen Datenschutzgesetzen zu behandeln.\r\nFür den Fall einer Beendung dieses Projekts werden sämtliche personenbezogenen Daten gelöscht. Bei einer Weitergabe an eine andere Projektleitung werden die Daten an diese weitergegeben, sie unterstehen aber weiterhin dieser Datenschutzerklärung.\r\n\r\n[b]6. Löschung von Daten[/b]\r\nSoweit Ihre Daten nicht mehr für die vorgenannten Zwecke einschließlich rechtlichen Gründen erforderlich sind, werden diese gelöscht.\r\n\r\n[b]7. Datensicherheit[/b]\r\nWir verarbeiten die von Ihnen erhobenen Informationen nach dem schweizerischen Datenschutzrecht. Alle Mitarbeiter sind auf das Datengeheimnis und die Datenschutzbestimmungen verpflichtet und diesbezüglich eingewiesen.', 1239963031, 'Datenschutzerklärung', 0);

--
-- Constraints der exportierten Tabellen
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
