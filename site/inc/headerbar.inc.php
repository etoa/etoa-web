<?PHP
	function headerbarCss()
	{
?>
<style type="text/css">
	#headerbar	{
	height:24px;
	background:#000 url('http://etoa.ch/images/headerbar.jpg') repeat-x;
	padding:0px;
	margin:0px;
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
}

#headerbar input, #headerbar select,#headerbar textarea	{
	background:#000;
	color:#fff;
	border:1px solid #fff;
	font-size:9pt;
}
</style>
<?PHP
	}

	function headerbarHtml()
	{
		?>
<div id="headerbar">
	<a href="http://etoa.ch" style="float:left;">
		<img src="http://etoa.ch/images/headerbar_logo.jpg" alt="Logo" style="border:none;" />	
	</a>
	<div style="float:right;padding-top:2px;padding-right:5px;">
		
<form action="<?PHP echo FORUM_URL;?>/search.php" method="post">
	<input name="searchprefix" value="" type="hidden"/>
	<input type="hidden" name="boardids[]" value="*" />
  <input type="hidden" name="searchdate" value="0" />
  <input type="hidden" name="topiconly" value="0" />
  <input type="hidden" name="showposts" value="0" />
  <input type="hidden" name="beforeafter" value="after" />
  <input type="hidden" name="sortorder" value="desc" />
  <input name="searchstring" value="" class="input" size="20" type="text" />
	<input name="send" value="send" type="hidden" />
 	<input name="sid" value="" type="hidden" />
 	<input class="input" name="submit" accesskey="S" value="Im Forum suchen" type="submit" />
	&nbsp;&nbsp;&nbsp;&nbsp;
		<select onchange="document.location=this.options[this.selectedIndex].value">
			<option value="#">Seite w&auml;hlen...</option>
			<option value="http://etoa.ch">Login</option>
			<option value="http://forum.etoa.ch">Forum</option>
			<option value="http://help.etoa.ch">Hilfe + FAQ</option>
			<option value="<?PHP echo FORUM_URL;?>/board.php?boardid=21">Technischer Support</option>
			<option value="http://dev.etoa.ch">Entwicklung</option>
		</select></form>		
	</div>
	<br style="clear:both;"/>
</div>
<div style="height:25px"></div>
<?PHP
}
?>