var lastType = "";
function changeAction(type) 
{
	if(document.getElementById('loginround').value == '') 
	{
		alert('Du hast keine Runde ausgewählt.');
	}
	else 
	{
		if(type == "login" && lastType == "") 
		{
			var url = document.getElementById('loginround').value ;
			document.getElementById('loginform').action = url;
		}
		else 
		{
			var url = document.getElementById('loginround').value;
			document.getElementById('loginform').action = url;
			document.getElementById('loginform').submit();
		}
	}
}			

function setDefault()
{
	var a = new Date();
	a = new Date(a.getTime() +1000*60*60*24*365);
	document.cookie = 'round='+document.getElementById('loginround').value+';expires='+a.toGMTString()+';';
}

function toggleBox(boxId)
{
	b = document.getElementById(boxId);
	if (b.style.display=='none')
	{
		b.style.display='';
	}
	else
	{
		b.style.display='none';
	}
}


function lib_bwcheck(){ //Browser-Überprüfung//
	this.ver=navigator.appVersion
	this.agent=navigator.userAgent
	this.dom=document.getElementById?1:0
	this.opera5=this.agent.indexOf("Opera 5")>-1
	this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom && !this.opera5)?1:0; 
	this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom && !this.opera5)?1:0;
	this.ie4=(document.all && !this.dom && !this.opera5)?1:0;
	this.ie=this.ie4||this.ie5||this.ie6
	this.mac=this.agent.indexOf("Mac")>-1
	this.ns6=(this.dom && parseInt(this.ver) >= 5) ?1:0; 
	this.ns4=(document.layers && !this.dom)?1:0;
	this.bw=(this.ie6 || this.ie5 || this.ie4 || this.ns4 || this.ns6 || this.opera5)
	return this
}
var bw=new lib_bwcheck()

var speed = 50
var loop, timer

function makeObj(obj,nest){
    nest=(!nest) ? "":'document.'+nest+'.'
	this.el=bw.dom?document.getElementById(obj):bw.ie4?document.all[obj]:bw.ns4?eval(nest+'document.'+obj):0;
  	this.css=bw.dom?document.getElementById(obj).style:bw.ie4?document.all[obj].style:bw.ns4?eval(nest+'document.'+obj):0;
	this.scrollHeight=bw.ns4?this.css.document.height:this.el.offsetHeight
	this.clipHeight=bw.ns4?this.css.clip.height:this.el.offsetHeight
	this.up=goUp;this.down=goDown;
	this.moveIt=moveIt; this.x=0; this.y=0;
    this.obj = obj + "Object"
    eval(this.obj + "=this")
    return this
}

var px = bw.ns4||window.opera?"":"px";

function moveIt(x,y){
	this.x = x
	this.y = y
	this.css.left = this.x+px
	this.css.top = this.y+px
}

function goDown(move){
	if (this.y>-this.scrollHeight+oCont.clipHeight){
		this.moveIt(0,this.y-move)
			if (loop) setTimeout(this.obj+".down("+move+")",speed)
	}
}

function goUp(move){
	if (this.y<0){
		this.moveIt(0,this.y-move)
		if (loop) setTimeout(this.obj+".up("+move+")",speed)
	}
}

function scroll(speed){
	if (scrolltextLoaded){
		loop = true;
		if (speed>0) oScroll.down(speed)
		else oScroll.up(speed)
	}
}

function noScroll(){
	loop = false
	if (timer) clearTimeout(timer)
}

var scrolltextLoaded = false
function scrolltextInit(){
	oCont = new makeObj('divScrollTextCont')
	oScroll = new makeObj('divText','divScrollTextCont')
	oScroll.moveIt(0,0)
	oCont.css.visibility = "visible"
	scrolltextLoaded = true
}



userAgent = window.navigator.userAgent;
browserVers = parseInt(userAgent.charAt(userAgent.indexOf("/")+1),10);
function newImage(arg) {
	if (document.images) {
		rslt = new Image();
		rslt.src = arg;
		return rslt;
	}
}


function UniDropDown(enable)
{	
	if (enable)
		document.getElementById('uniDropDown').style.visibility='visible';
	else
		document.getElementById('uniDropDown').style.visibility='hidden';


}

function findElement(n,ly) {
	if (browserVers < 4)		return document[n];
	var curDoc = ly ? ly.document : document;
	var elem = curDoc[n];
	if (!elem) {
		for (var i=0;i<curDoc.layers.length;i++) {
			elem = findElement(n,curDoc.layers[i]);
			if (elem) return elem;
		}
	}
	return elem;
}


function changeImages() {
	if (document.images) {
		var img;
		for (var i=0; i<changeImages.arguments.length; i+=2) {
			img = null;
			if (document.layers) {
				img = findElement(changeImages.arguments[i],0);
			}
			else {
				img = document.images[changeImages.arguments[i]];
			}
			if (img) {
				img.src = changeImages.arguments[i+1];
			}
		}
	}
}


function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);


function changeText(eintrag) {
	document.forms['loginform'].elements['login_round'].value=eintrag;
	UniDropDown(false);
}

function changeRegUniText(eintrag) {
	document.forms['registerform'].elements['reg_round'].value=eintrag;
	UniRegDropDown(false);
}

function UniRegDropDown(enable)
{	
	if (enable)
		document.getElementById('uniRegDropDown').style.visibility='visible';
	else
		document.getElementById('uniRegDropDown').style.visibility='hidden';


}

function changeRaceUniText(eintrag) {
	document.forms['registerform'].elements['register_user_race'].value=eintrag;
	UniRaceDropDown(false);
}

function UniRaceDropDown(enable)
{	
	if (enable)
		document.getElementById('uniRaceDropDown').style.visibility='visible';
	else
		document.getElementById('uniRaceDropDown').style.visibility='hidden';


}

function changePwUniText(eintrag) {
	document.forms['pwrequestform'].elements['pw_round'].value=eintrag;
	UniPwDropDown(false);
}

function UniPwDropDown(enable)
{	
	if (enable)
		document.getElementById('uniPwDropDown').style.visibility='visible';
	else
		document.getElementById('uniPwDropDown').style.visibility='hidden';


}

function changePrUniText(eintrag) {
	document.forms['pranger'].elements['pr_round'].value=eintrag;
	UniPrDropDown(false);
}

function UniPrDropDown(enable)
{	
	if (enable)
		document.getElementById('uniPrDropDown').style.visibility='visible';
	else
		document.getElementById('uniPrDropDown').style.visibility='hidden';


}

function changeTabelleUniText(eintrag) {
	document.forms['pranger'].elements['pr_tabelle'].value=eintrag;
	UniPrTabelleDropDown(false);
}

function UniPrTabelleDropDown(enable)
{	
	if (enable)
		document.getElementById('uniPrTabelle').style.visibility='visible';
	else
		document.getElementById('uniPrTabelle').style.visibility='hidden';


}

function FilterRanglisteByAlly() {
	
}

function FilterRanglisteByTech() {
	
}

function FilterRanglisteByFleet() {
	
}

function FilterRanglisteByPlayer() {
	
}






/*
 Pleas leave this notice.
 DHTML tip message version 1.5.4 copyright Essam Gamal 2003
 Home Page: (http://migoicons.tripod.com)
 Email: (migoicons@hotmail.com)
 Updated on :7/30/2003
*/

var MI_IE=MI_IE4=MI_NN4=MI_ONN=MI_NN=MI_pSub=MI_sNav=0;mig_dNav()
var Style=[],Text=[],Count=0,move=0,fl=0,isOK=1,hs,e_d,tb,w=window,PX=(MI_pSub)?"px":""
var d_r=(MI_IE&&document.compatMode=="CSS1Compat")? "document.documentElement":"document.body"
var ww=w.innerWidth
var wh=w.innerHeight
var sbw=MI_ONN? 15:0

function mig_hand(){
if(MI_sNav){
w.onresize=mig_re
document.onmousemove=mig_mo
if(MI_NN4) document.captureEvents(Event.MOUSEMOVE)
}}

function mig_dNav(){
var ua=navigator.userAgent.toLowerCase()
MI_pSub=navigator.productSub
MI_OPR=ua.indexOf("opera")>-1?parseInt(ua.substring(ua.indexOf("opera")+6,ua.length)):0
MI_IE=document.all&&!MI_OPR?parseFloat(ua.substring(ua.indexOf("msie")+5,ua.length)):0
MI_IE4=parseInt(MI_IE)==4
MI_NN4=navigator.appName.toLowerCase()=="netscape"&&!document.getElementById
MI_NN=MI_NN4||document.getElementById&&!document.all
MI_ONN=MI_NN4||MI_pSub<20020823
MI_sNav=MI_NN||MI_IE||MI_OPR>=7
}

function mig_cssf(){
if(MI_IE>=5.5&&FiltersEnabled){fl=1
var d=" progid:DXImageTransform.Microsoft."
mig_layCss().filter="revealTrans()"+d+"Fade(Overlap=1.00 enabled=0)"+d+"Inset(enabled=0)"+d+"Iris(irisstyle=PLUS,motion=in enabled=0)"+d+"Iris(irisstyle=PLUS,motion=out enabled=0)"+d+"Iris(irisstyle=DIAMOND,motion=in enabled=0)"+d+"Iris(irisstyle=DIAMOND,motion=out enabled=0)"+d+"Iris(irisstyle=CROSS,motion=in enabled=0)"+d+"Iris(irisstyle=CROSS,motion=out enabled=0)"+d+"Iris(irisstyle=STAR,motion=in enabled=0)"+d+"Iris(irisstyle=STAR,motion=out enabled=0)"+d+"RadialWipe(wipestyle=CLOCK enabled=0)"+d+"RadialWipe(wipestyle=WEDGE enabled=0)"+d+"RadialWipe(wipestyle=RADIAL enabled=0)"+d+"Pixelate(MaxSquare=35,enabled=0)"+d+"Slide(slidestyle=HIDE,Bands=25 enabled=0)"+d+"Slide(slidestyle=PUSH,Bands=25 enabled=0)"+d+"Slide(slidestyle=SWAP,Bands=25 enabled=0)"+d+"Spiral(GridSizeX=16,GridSizeY=16 enabled=0)"+d+"Stretch(stretchstyle=HIDE enabled=0)"+d+"Stretch(stretchstyle=PUSH enabled=0)"+d+"Stretch(stretchstyle=SPIN enabled=0)"+d+"Wheel(spokes=16 enabled=0)"+d+"GradientWipe(GradientSize=1.00,wipestyle=0,motion=forward enabled=0)"+d+"GradientWipe(GradientSize=1.00,wipestyle=0,motion=reverse enabled=0)"+d+"GradientWipe(GradientSize=1.00,wipestyle=1,motion=forward enabled=0)"+d+"GradientWipe(GradientSize=1.00,wipestyle=1,motion=reverse enabled=0)"+d+"Zigzag(GridSizeX=8,GridSizeY=8 enabled=0)"+d+"Alpha(enabled=0)"+d+"Dropshadow(OffX=3,OffY=3,Positive=true,enabled=0)"+d+"Shadow(strength=3,direction=135,enabled=0)"
}}

function stm(t,s){
if(MI_sNav&&isOK){
if(document.onmousemove!=mig_mo||w.onresize!=mig_re) mig_hand()
if(fl&&s[17]>-1&&s[18]>0)mig_layCss().visibility="hidden"
var ab="";var ap=""
var titCol=s[0]?"COLOR='"+s[0]+"'":""
var titBgCol=s[1]&&!s[2]?"BGCOLOR='"+s[1]+"'":""
var titBgImg=s[2]?"BACKGROUND='"+s[2]+"'":""
var titTxtAli=s[3]?"ALIGN='"+s[3]+"'":""
var txtCol=s[6]?"COLOR='"+s[6]+"'":""
var txtBgCol=s[7]&&!s[8]?"BGCOLOR='"+s[7]+"'":""
var txtBgImg=s[8]?"BACKGROUND='"+s[8]+"'":""
var txtTxtAli=s[9]?"ALIGN='"+s[9]+"'":""
var tipHeight=s[13]? "HEIGHT='"+s[13]+"'":""
var brdCol=s[15]? "BGCOLOR='"+s[15]+"'":""
if(!s[4])s[4]="Verdana,Arial,Helvetica"
if(!s[5])s[5]=1
if(!s[10])s[10]="Verdana,Arial,Helvetica"
if(!s[11])s[11]=1
if(!s[12])s[12]=200
if(!s[14])s[14]=0
if(!s[16])s[16]=0
if(!s[24])s[24]=10
if(!s[25])s[25]=10
hs=s[22]
if(MI_pSub==20001108){
if(s[14])ab="STYLE='border:"+s[14]+"px solid"+" "+s[15]+"'";
ap="STYLE='padding:"+s[16]+"px "+s[16]+"px "+s[16]+"px "+s[16]+"px'"}
var closeLink=hs==3?"<TD ALIGN='right'><FONT SIZE='"+s[5]+"' FACE='"+s[4]+"'><A HREF='javascript:void(0)' ONCLICK='mig_hide(0)' STYLE='text-decoration:none;color:"+s[0]+"'><B>Close</B></A></FONT></TD>":""
var title=t[0]||hs==3?"<TABLE WIDTH='100%' BORDER='0' CELLPADDING='0' CELLSPACING='0' "+titBgCol+" "+titBgImg+"><TR><TD "+titTxtAli+"><FONT SIZE='"+s[5]+"' FACE='"+s[4]+"' "+titCol+"><B>"+t[0]+"</B></FONT></TD>"+closeLink+"</TR></TABLE>":"";
var txt="<TABLE "+ab+" WIDTH='"+s[12]+"' BORDER='0' CELLSPACING='0' CELLPADDING='"+s[14]+"' "+brdCol+"><TR><TD>"+title+"<TABLE WIDTH='100%' "+tipHeight+" BORDER='0' CELLPADDING='"+s[16]+"' CELLSPACING='0' "+txtBgCol+" "+txtBgImg+"><TR><TD "+txtTxtAli+" "+ap+" VALIGN='top'><FONT SIZE='"+s[11]+"' FACE='"+s[10]+"' "+txtCol +">"+t[1]+"</FONT></TD></TR></TABLE></TD></TR></TABLE>"
mig_wlay(txt)
tb={trans:s[17],dur:s[18],opac:s[19],st:s[20],sc:s[21],pos:s[23],xpos:s[24],ypos:s[25]}
if(MI_IE4)mig_layCss().width=s[12]
e_d=mig_ed()
Count=0
move=1
}}

function mig_mo(e){
if(move){
var X=0,Y=0,s_d=mig_scd(),w_d=mig_wd()
var mx=MI_NN?e.pageX:MI_IE4?event.x:event.x+s_d[0]
var my=MI_NN?e.pageY:MI_IE4?event.y:event.y+s_d[1]
if(MI_IE4)e_d=mig_ed()
switch(tb.pos){
case 1:X=mx-e_d[0]-tb.xpos+6;Y=my+tb.ypos;break
case 2:X=mx-(e_d[0]/2);Y=my+tb.ypos;break
case 3:X=tb.xpos+s_d[0];Y=tb.ypos+s_d[1];break
case 4:X=tb.xpos;Y=tb.ypos;break
default:X=mx+tb.xpos;Y=my+tb.ypos}
if(w_d[0]+s_d[0]<e_d[0]+X+sbw)X=w_d[0]+s_d[0]-e_d[0]-sbw
if(w_d[1]+s_d[1]<e_d[1]+Y+sbw){if(tb.pos>2)Y=w_d[1]+s_d[1]-e_d[1]-sbw;else Y=my-e_d[1]}
if(X<s_d[0])X=s_d[0]
with(mig_layCss()){left=X+PX;top=Y+PX}
mig_dis()
}}

function mig_dis(){Count++
if(Count==1){
if(fl){
if(tb.trans==51)tb.trans=parseInt(Math.random()*50)
var at=tb.trans>-1&&tb.trans<24&&tb.dur>0
var af=tb.trans>23&&tb.trans<51&&tb.dur>0
var t=mig_lay().filters[af?tb.trans-23:0]
for(var p=28;p<31;p++){mig_lay().filters[p].enabled=0}
for(var s=0;s<28;s++){if(mig_lay().filters[s].status)mig_lay().filters[s].stop()}
for(var e=1;e<3;e++){if(tb.sc&&tb.st==e){with(mig_lay().filters[28+e]){enabled=1;color=tb.sc}}}
if(tb.opac>0&&tb.opac<100){with(mig_lay().filters[28]){enabled=1;opacity=tb.opac}}
if(at||af){if(at)mig_lay().filters[0].transition=tb.trans;t.duration=tb.dur;t.apply()}}
mig_layCss().visibility=MI_NN4?"show":"visible"
if(fl&&(at||af))t.play()
if(hs>0&&hs<4)move=0
}}

function mig_layCss(){return MI_NN4?mig_lay():mig_lay().style}
function mig_lay(){with(document)return MI_NN4?layers[TipId]:MI_IE4?all[TipId]:getElementById(TipId)}
function mig_wlay(txt){if(MI_NN4){with(mig_lay().document){open();write(txt);close()}}else mig_lay().innerHTML=txt}
function mig_hide(C){if(!MI_NN4||MI_NN4&&C)mig_wlay("");with(mig_layCss()){visibility=MI_NN4?"hide":"hidden";left=0;top=-800}}
function mig_scd(){return [parseInt(MI_IE?eval(d_r).scrollLeft:w.pageXOffset),parseInt(MI_IE?eval(d_r).scrollTop:w.pageYOffset)]}
function mig_re(){var w_d=mig_wd();if(MI_NN4&&(w_d[0]-ww||w_d[1]-wh))location.reload();else if(hs==3||hs==2) mig_hide(1)}
function mig_wd(){return [parseInt(MI_ONN?w.innerWidth:eval(d_r).clientWidth),parseInt(MI_ONN?w.innerHeight:eval(d_r).clientHeight)]}
function mig_ed(){return [parseInt(MI_NN4?mig_lay().clip.width:mig_lay().offsetWidth)+3,parseInt(MI_NN4?mig_lay().clip.height:mig_lay().offsetHeight)+5]}
function htm(){if(MI_sNav&&isOK){if(hs!=4){move=0;if(hs!=3&&hs!=2){mig_hide(1)}}}}

function mig_clay(){
if(!mig_lay()){isOK=0
alert("DHTML TIP MESSAGE VERSION 1.5 ERROR NOTICE.\n<DIV ID=\""+TipId+"\"></DIV> tag missing or its ID has been altered")}
else{mig_hand();mig_cssf()}}


//
//Fügt per Klick BB-Codes ein bbcode(arg1=formular ort, arg2=Kürzel, arg3=text)
//

var bbtags   = new Array();


tag_prompt = "Geben Sie einen Text ein:";
img_prompt = "Bitte geben Sie die volle Bildadresse ein:";
font_formatter_prompt = "Geben Sie einen Text ein - ";
link_text_prompt = "Geben Sie einen Linknamen ein (optional):";
link_url_prompt = "Geben Sie die volle Adresse des Links ein:";
link_email_prompt = "Geben Sie eine E-Mail-Adresse ein:";
list_type_prompt = "Was für eine Liste möchten Sie? Geben Sie '1' ein für eine nummerierte Liste, 'a' für ein alphabetische, oder gar nichts für eine einfache Punktliste.";
list_item_prompt = "Geben Sie einen Listenpunkt ein.\nGeben Sie nichts ein oder drücken 'Abbrechen' um die Liste fertigzustellen.";


// browser detection
var myAgent   = navigator.userAgent.toLowerCase();
var myVersion = parseInt(navigator.appVersion);
var is_ie   = ((myAgent.indexOf("msie") != -1)  && (myAgent.indexOf("opera") == -1));
var is_win   =  ((myAgent.indexOf("win")!=-1) || (myAgent.indexOf("16bit")!=-1));

function setmode(modeValue) {
 	document.cookie = "bbcodemode="+modeValue+"; path=/; expires=Wed, 1 Jan 2020 00:00:00 GMT;";
}

function normalMode(theForm) {
		return true;
}

function getArraySize(theArray) {
 	for (i = 0; i < theArray.length; i++) {
  		if ((theArray[i] == "undefined") || (theArray[i] == "") || (theArray[i] == null)) return i;
	}

 	return theArray.length;
}

function pushArray(theArray, value) {
 	theArraySize = getArraySize(theArray);
 	theArray[theArraySize] = value;
}

function popArray(theArray) {
	theArraySize = getArraySize(theArray);
 	retVal = theArray[theArraySize - 1];
 	delete theArray[theArraySize - 1];
 	return retVal;
}


function smilie(theSmilie) {
	addText(" " + theSmilie, "", false, document.bbform);
}

function closetag(theForm) {
 	if (!normalMode(theForm)) {
  		if (bbtags[0]) addText("[/"+ popArray(bbtags) +"]", "", false, theForm);
  	}

 	setFocus(theForm);
}

function closeall(theForm) {
 	if (!normalMode(theForm)) {
  		if (bbtags[0]) {
   			while (bbtags[0]) {
    				addText("[/"+ popArray(bbtags) +"]", "", false, theForm);
   			}
   		}
 	}

 	setFocus(theForm);
}


function fontformat(theForm,theValue,theType) {
 	setFocus(theForm);

 	if (normalMode(theForm)) {
  		if (theValue != 0) {

   			var selectedText = getSelectedText(theForm);
   			var insertText = prompt(font_formatter_prompt+" "+theType, selectedText);
   			if ((insertText != null) && (insertText != "")) {
    				addText("["+theType+"="+theValue+"]"+insertText+"[/"+theType+"]", "", false, theForm);
    			}
  		}
 	}
 	else {
		if(addText("["+theType+"="+theValue+"]", "[/"+theType+"]", true, theForm)) {
			pushArray(bbtags, theType);
		}
	}

 	theForm.sizeselect.selectedIndex = 0;
 	theForm.fontselect.selectedIndex = 0;
 	theForm.colorselect.selectedIndex = 0;

 	setFocus(theForm);
}


function bbcode(theForm, theTag, promptText) {
	if ( normalMode(theForm) || (theTag=="IMG")) {
		var selectedText = getSelectedText(theForm);
		if (promptText == '' || selectedText != '') promptText = selectedText;

		inserttext = prompt(((theTag == "IMG") ? (img_prompt) : (tag_prompt)) + "\n[" + theTag + "]xxx[/" + theTag + "]", promptText);
		if ( (inserttext != null) && (inserttext != "") ) {
			addText("[" + theTag + "]" + inserttext + "[/" + theTag + "]", "", false, theForm);
		}
	}
	else {
		var donotinsert = false;
  		for (i = 0; i < bbtags.length; i++) {
   			if (bbtags[i] == theTag) donotinsert = true;
  		}

  		if (!donotinsert) {
   			if(addText("[" + theTag + "]", "[/" + theTag + "]", true, theForm)){
				pushArray(bbtags, theTag);
			}
  		}
		else {
			var lastindex = 0;

			for (i = 0 ; i < bbtags.length; i++ ) {
				if ( bbtags[i] == theTag ) {
					lastindex = i;
				}
			}

			while (bbtags[lastindex]) {
				tagRemove = popArray(bbtags);
				addText("[/" + tagRemove + "]", "", false, theForm);
			}
		}
	}
}

function namedlink(theForm,theType) {
	var selected = getSelectedText(theForm);

	var linkText = prompt(link_text_prompt,selected);
	var prompttext;

	if (theType == 'url') {
 		prompt_text = link_url_prompt;
 		prompt_contents = "http://";
	}
	else {
		prompt_text = link_email_prompt;
		prompt_contents = "";
		}

	linkURL = prompt(prompt_text,prompt_contents);


	if ((linkURL != null) && (linkURL != "")) {
		var theText = '';

		if ((linkText != null) && (linkText != "")) {
   			theText = "["+theType+"="+linkURL+"]"+linkText+"[/"+theType+"]";
   		}
		else {
			theText = "["+theType+"]"+linkURL+"[/"+theType+"]";
		}

  		addText(theText, "", false, theForm);
 	}
}


function dolist(theForm) {
 	listType = prompt(list_type_prompt, "");
 	if ((listType == "a") || (listType == "1")) {
  		theList = "[list="+listType+"]\n";
  		listEend = "[/list="+listType+"] ";
 	}
 	else {
  		theList = "[list]\n";
  		listEend = "[/list] ";
 	}

 	listEntry = "initial";
 	while ((listEntry != "") && (listEntry != null)) {
  		listEntry = prompt(list_item_prompt, "");
  		if ((listEntry != "") && (listEntry != null)) theList = theList+"[*]"+listEntry+"\n";
 	}

 	addText(theList + listEend, "", false, theForm);
}


function addText(theTag, theClsTag, isSingle, theForm)
{
	var isClose = false;
	var message = theForm.message;
	var set=false;
  	var old=false;
  	var selected="";

  	if(message.textLength>=0 ) { // mozilla, firebird, netscape
  		if(theClsTag!="" && message.selectionStart!=message.selectionEnd) {
  			selected=message.value.substring(message.selectionStart,message.selectionEnd);
  			str=theTag + selected+ theClsTag;
  			old=true;
  			isClose = true;
  		}
		else {
			str=theTag;
		}

		message.focus();
		start=message.selectionStart;
		end=message.textLength;
		endtext=message.value.substring(message.selectionEnd,end);
		starttext=message.value.substring(0,start);
		message.value=starttext + str + endtext;
		message.selectionStart=start;
		message.selectionEnd=start;

		message.selectionStart = message.selectionStart + str.length;

		if(old) { return false; }

		set=true;

		if(isSingle) {
			isClose = false;
		}
	}
	if ( (myVersion >= 4) && is_ie && is_win) {  // Internet Explorer
		if(message.isTextEdit) {
			message.focus();
			var sel = document.selection;
			var rng = sel.createRange();
			rng.colapse;
			if((sel.type == "Text" || sel.type == "None") && rng != null){
				if(theClsTag != "" && rng.text.length > 0)
					theTag += rng.text + theClsTag;
				else if(isSingle)
					isClose = true;

				rng.text = theTag;
			}
		}
		else{
			if(isSingle) isClose = true;

			if(!set) {
      				message.value += theTag;
      			}
		}
	}
	else
	{
		if(isSingle) isClose = true;

		if(!set) {
      			message.value += theTag;
      		}
	}

	message.focus();

	return isClose;
}


function getSelectedText(theForm) {
	var message = theForm.message;
	var selected = '';

	if(navigator.appName=="Netscape" &&  message.textLength>=0 && message.selectionStart!=message.selectionEnd )
  		selected=message.value.substring(message.selectionStart,message.selectionEnd);

	else if( (myVersion >= 4) && is_ie && is_win ) {
		if(message.isTextEdit){
			message.focus();
			var sel = document.selection;
			var rng = sel.createRange();
			rng.colapse;

			if((sel.type == "Text" || sel.type == "None") && rng != null){
				if(rng.text.length > 0) selected = rng.text;
			}
		}
	}

  	return selected;
}

function setFocus(theForm) {
 	theForm.message.focus();
}
