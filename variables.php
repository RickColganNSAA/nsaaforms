<?php

$server_source=$_SERVER['DOCUMENT_ROOT']."/"; 

global $db_host;
global $db_user;
global $db_pass;
global $db_name;
global $stateassn;
global $db_user2;
global $db_pass2;
global $db_name2;
global $db_test;

global $lastdb;
global $totalconnection;

require_once $server_source.'define_paths.php';
require_once $server_source.'dbfunction.php';
if($db_host==''){
	

$db_name="nsaascores";
$db_user="nsaa";
$db_pass="3zyg15rexvs4kgo";
$db_name2="nsaaofficials";
$db_user2="nsaa";
$db_pass2="3zyg15rexvs4kgo";
$db_host="phpapp-pub-new.c1pz8ojztooh.us-east-1.rds.amazonaws.com";
//$db_host="phpapp-pub.c1pz8ojztooh.us-east-1.rds.amazonaws.com";
$stateassn="NSAA";
$db_test="testwildcard";
global $lastdb;

	if($_SERVER['HTTP_HOST']=='nsaahome.criticalitgroup.com'){
		
		$db_host="localhost";
		$db_user="root";
		$db_user2="root";
		$db_pass="HmvSOoLAwYwfo";
		$db_pass2="HmvSOoLAwYwfo";
	}
	if($_SERVER['HTTP_HOST']=='dev.nsaahome.org'||$_SERVER['HTTPS_HOST']=='dev.nsaahome.org'){
	
	$db_host="localhost";
	$db_user="root";
	$db_user2="root";
	$db_pass="HmvSOoLAwYwfo";
	$db_pass2="HmvSOoLAwYwfo";
}


}
else {
global $db_host;
global $db_user;
global $db_pass;
global $db_name;
global $stateassn;
global $db_user2;
global $db_pass2;
global $db_name2;
global $db_test;

global $lastdb;
global $totalconnection;
}
//VIRTUAL MERCHANT VARIABLES
$VirtualMerchantAction="https://www.myvirtualmerchant.com/VirtualMerchant/process.do";
$VirtualMerchantID="554849"; //"8001372054" "402351";
$VirtualMerchantPIN="L6MTAT"; //"YLGYWO";
$VirtualMerchantUserID="webpage";

//MAX PREPS
$mpkey="wxQTXgK4u7vX2P95EeJGa7JQIGLWzuj508YYoH2TOxPQlZW-c";

//main e-mail to send forms to
$main_email="jangele@nsaahome.org";
$from_email="nsaa@nsaahome.org";
$de_email="jschwartz@nsaahome.org";
$cc_email="rschmidt@nsaahome.org";
$sp_email="ccallaway@nsaahome.org";
$tr_email="nneuhaus@nsaahome.org";

$offmileagerate="1.00";        //MILEAGE RATE

$finalsubmit="Check this box when you have finished entering information and wish to make your final submission of this form to the NSAA.";


//TENNIS
//POSITIONS FOR SEEDED PLAYERS ON BRACKET (POSITION = LINE)
//Example: $seedpos[X][Y][2] = Line the #3-Seeded Player sits on for a X-person bracket with Y seeded players
$seedpos[33][12]=array("1","32","18","15","9","24","28","5","7","26","22","11");
//THESE ARE THE SAME BECAUSE IF <32, WILL HAVE BYES TO GET THE NUMBER UP TO 32
$seedpos[32][12]=array("1","32","18","15","9","24","28","5","7","26","22","11");
$seedpos[32][11]=array("1","32","18","15","9","24","28","5","7","26","22","11");
$seedpos[28][12]=array("1","32","18","15","9","24","28","5","7","26","22","11");
$seedpos[28][11]=array("1","32","18","15","9","24","28","5","7","26","22","11");
//$seedpos16[12]=array("1","16","7","10","12","5","4","13","14","3","11","6");
$seedpos[16][12]=array("1","16","10","7","5","12","13","4","3","14","11","6");

//javscript coding for Auto Tab
$autotab="var isNN = (navigator.appName.indexOf(\"Netscape\")!=-1);
function autoTab(input,len, e) {
var keyCode = (isNN) ? e.which : e.keyCode;
var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
if(input.value.length >= len && !containsElement(filter,keyCode)) {
input.value = input.value.slice(0, len);
input.form[(getIndex(input)+1) % input.form.length].focus();
}
function containsElement(arr, ele) {
var found = false, index = 0;
while(!found && index < arr.length)
if(arr[index] == ele)
found = true;
else
index++;
return found;
}
function getIndex(input) {
var index = -1, i = 0, found = false;
while (i < input.form.length && index == -1)
if (input.form[i] == input)index = i;
else i++;
return index;
}
return true;
}";

//create array of activity titles
$activity=array("fb68","fb11","vb","sb","cc","te","bb","wr","sw","go","tr","ba","so","ch","sp","pp","de","im","vm","jo","ubo","utr");
//$act_long=array("Football 6/8","Football 11","Volleyball","Softball","Cross-Country","Tennis","Basketball","Wrestling","Swimming","Golf","Track","Baseball","Soccer","Cheerleading/Spirit","Speech","Play Production","Debate","Instrumental Music","Vocal Music","Journalism");

//activities for Academic All-State Nominations
$allstatesp=array("Fall Season","Girls Golf","Boys Tennis","Softball","Girls Cross-Country","Boys Cross-Country","Volleyball","Football","Play Production","Unified Bowling","Winter Season","Wrestling","Girls Swimming and Diving","Boys Swimming and Diving","Girls Basketball","Boys Basketball","Speech","Lincoln-Douglas Debate","Spring Season","Music","Journalism","Baseball","Girls Soccer","Boys Soccer","Girls Tennis","Girls Track and Field","Boys Track and Field","Boys Golf","Unified Track and Field");
$allstatesp2=array("","go_g","te_b","sb","cc_g","cc_b","vb","fb","pp","ubo","","wr","sw_g","sw_b","bb_g","bb_b","sp","de","","mu","jo","ba","so_g","so_b","te_g","tr_g","tr_b","go_b","utr");

$hostsports=array("ba","bbb","bbg","ccb","ccg","fb","go_b","go_g","pp","sb","sob","sog","sp","teb","teg","trb","trg","vb","wr","ubo");
$hostsports2=array("Baseball","Boys Basketball","Girls Basketball","Boys Cross-Country","Girls Cross-Country","Football","Boys Golf","Girls Golf","Play Production","Softball","Boys Soccer","Girls Soccer","Speech","Boys Tennis","Girls Tennis","Boys Track & Field","Girls Track & Field","Volleyball","Wrestling","Unified Bowling");

$coopsports=array("fb6","fb8","fb11","vb","ccb","ccg","gog","teb","sb","ubo","pp","swb","swg","wr","bbb","bbg","sp","de","ba","trb","trg","teg","gob","sob","sog","utr","vm","im","jo");
$coopsports2=array("fb6","fb8","fb11","vb","bcc","gcc","ggo","bte","sb","ubo","pp","bsw","gsw","wr","bbb","gbb","sp","de","ba","btr","gtr","gte","bgo","bso","gso","utr","vm","im","jo");
$coopsections=array("Fall","Fall","Fall","Fall","Fall","Fall","Fall","Fall","Fall","Fall","Fall","Winter","Winter","Winter","Winter","Winter","Winter","Winter","Spring","Spring","Spring","Spring","Spring","Spring","Spring","Spring","Other","Other","Other");

$stateacts=array("ba","bbb","bbg","cc","fb","gob","gog","pp","sob","sog","sb","sp","teb","teg","tr","vb","wr");
$statepartacts=array("ba","bbb","bbg","ccb","ccg","fb","gob","gog","jo","pp","sob","sog","sb","sp","sw","teb","teg","trb","trg","vb","wr","ubo","utr");

//array of long names of activities
$act_long=array("Baseball","Boys Basketball","Girls Basketball","Cheerleading/Spirit","Boys Cross-Country","Girls Cross-Country","Debate","Football 6/8","Football 11","Boys Golf","Girls Golf","Journalism","Instrumental Music","Vocal Music","Play Production","Boys Soccer","Girls Soccer","Softball","Speech","Boys Swimming","Girls Swimming","Boys Tennis","Girls Tennis","Boys Track & Field","Girls Track & Field","Volleyball","Wrestling","Unified Bowling","Unified Track & Field");

//DECLARATION ACTIVITIES:
//(used in diradmin.php)
$decacts=array("fb","vb","cc_b","cc_g","go_g","te_b","sb","pp");

//ORIGINAL REGISTRATION ACTIVITIES:
$act_regi=array("fb","vb","cc_b","cc_g","go_g","te_b","sb","pp","sp","de_ld","de_cx","im","vm","jo","wr","bb_b","bb_g","sw_b","sw_g","ba","tr_b","tr_g","te_g","go_b","so_g","so_b","ch","ubo","utr");
$act_regi2=array("Football","Volleyball","Boys Cross-Country","Girls Cross-Country","Girls Golf","Boys Tennis","Softball","Play Production","Speech","Lincoln-Douglas Debate","Cross-Examination Debate","Instrumental Music","Vocal Music","Journalism","Wrestling","Boys Basketball","Girls Basketball","Boys Swimming","Girls Swimming","Baseball","Boys Track & Field","Girls Track & Field","Girls Tennis","Boys Golf","Girls Soccer","Boys Soccer","Cheerleading/Spirit","Unified Bowling","Unified Track & Field");
//NEW REGISTRATION ACTIVITIES AS OF June 16, 2011:
$regacts=array("fb","vb","cc_b","cc_g","go_g","te_b","sb","ubo","pp","sp","de","mu","jo","wr","bb_b","bb_g","sw_b","sw_g","ba","tr_b","tr_g","te_g","go_b","so_b","so_g","utr");
$regactseasons=array("Fall","Fall","Fall","Fall","Fall","Fall","Fall","Fall","Winter","Winter","Winter","Winter","Winter","Winter","Winter","Winter","Winter","Winter","Spring","Spring","Spring","Spring","Spring","Spring","Spring","Spring");
$regactdecs=array(1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

$act_long2=array("Football 6/8","Football 11","Volleyball","Softball","Cross-Country","Tennis","Basketball","Wrestling","Swimming","Golf","Track & Field","Baseball","Soccer","Cheerleading/Spirit","Speech","Play Production","Debate","Instrumental Music","Vocal Music","Journalism","Unified Bowling","Unified Track & Field");

//directory prefix
$home=$_SERVER['DOCUMENT_ROOT']."";

//months
$months=array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

$months2=array("January","February","March","April","May","June","July","August","September","October","November","December");

//sports positions
   //SB:
   $sb_positions=array("P","C","INF","1B","2B","SS","3B","OF","LF","CF","RF","Util","DP");
   //BA:
   $ba_positions=array("P","C","INF","1B","2B","SS","3B","OF","LF","CF","RF","Util","DH");
   //VB:
   $vb_positions=array("OH","MH","MB","DS","S","LH","RH","L");
   //FB:
   $fb_off_posns=array("C","OG","OT","OL","OE","SE","TE","QB","RB","FB","WB","SB","WR","K","P");
   $fb_def_posns=array("NT","DG","DT","DE","DB","LB","CB","S","K","P");
   //SO:
   $so_positions=array("F","MF","D","GK");
   //BB:
   $bb_positions=array("G","F","C");

//weight classes for wrestling
$weights=array(106,113,120,126,132,138,145,152,160,170,182,195,220,285);

//Wording for bottom of forms above "Save" button:
$certify="I hereby certify the above students are eligible under the rules of the NSAA.";
$certify2="I hereby certify the above student is eligible under the rules of the NSAA.";

$mobile_end_html="<script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-47019718-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type=\"text/javascript\">
window.onload = function(){
replaceurl();
}
function  replaceurl (){
	var links = document.getElementsByTagName('a');
	for(var i=0; i<links.length; i++) {
	
		
		var n = links[i].href.includes('.php');
		var n2 = links[i].href.includes('nsaa-static.s3.amazonaws.com');
		
		if(n==true && n2==true)
		{
			var url=links[i].href;
			if(url.includes('nsaa-static.s3.amazonaws.com')==true)
			{
				links[i].setAttribute('href', url.replace('nsaa-static.s3.amazonaws.com', 'secure.nsaahome.org'));
				
			}
		}
	}
}
</script>
</body></html>";

//top of html page and header info
$init_htmlNEWTINYMCE="<!DOCTYPE html><html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"><script type=\"text/javascript\" src=\"/tinymce/js/tinymce/tinymce.min.js\"></script>
<script type=\"text/javascript\">
window.onload = function(){
replaceurl();
}
function  replaceurl (){
	var links = document.getElementsByTagName('a');
	for(var i=0; i<links.length; i++) {
		
		var n = links[i].href.includes('.php');
		var n2 = links[i].href.includes('nsaa-static.s3.amazonaws.com');
		
		if(n==true && n2==true)
		{
			var url=links[i].href;
			if(url.includes('nsaa-static.s3.amazonaws.com')==true)
			{
				links[i].setAttribute('href', url.replace('nsaa-static.s3.amazonaws.com', 'secure.nsaahome.org'));
				
			}
		}
	}
}
</script>
</head><body>";
$init_html_ajax="<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\">
<script type=\"text/javascript\" src=\"/javascript/Utilities.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/AjaxUpdater.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/HTTP.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/Ajax.js\"></script>
<script type=\"text/javascript\">
window.onload = function(){
replaceurl();
}
function  replaceurl (){
	var links = document.getElementsByTagName('a');
	for(var i=0; i<links.length; i++) {
		
		var n = links[i].href.includes('.php');
		var n2 = links[i].href.includes('nsaa-static.s3.amazonaws.com');
		
		if(n==true && n2==true)
		{
			var url=links[i].href;
			if(url.includes('nsaa-static.s3.amazonaws.com')==true)
			{
				links[i].setAttribute('href', url.replace('nsaa-static.s3.amazonaws.com', 'secure.nsaahome.org'));
				
			}
		}
	}
}
</script>";

$init_html="<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"><script type=\"text/javascript\" src=\"/tinymce09102015/jscripts/tiny_mce/tiny_mce.js\"></script>
<script type=\"text/javascript\">
window.onload = function(){
replaceurl();
}
function  replaceurl (){
	var links = document.getElementsByTagName('a');
	for(var i=0; i<links.length; i++) {
		
		var n = links[i].href.includes('.php');
		var n2 = links[i].href.includes('nsaa-static.s3.amazonaws.com');
		
		if(n==true && n2==true)
		{
			var url=links[i].href;
			if(url.includes('nsaa-static.s3.amazonaws.com')==true)
			{
				links[i].setAttribute('href', url.replace('nsaa-static.s3.amazonaws.com', 'secure.nsaahome.org'));
				
			}
		}
	}
}
</script>
</head><body>";
$init_html_ajax="<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\">
<script type=\"text/javascript\" src=\"/javascript/Utilities.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/AjaxUpdater.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/HTTP.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/Ajax.js\"></script>
<script type=\"text/javascript\">
window.onload = function(){
replaceurl();
}
function  replaceurl (){
	var links = document.getElementsByTagName('a');
	for(var i=0; i<links.length; i++) {
		
		var n = links[i].href.includes('.php');
		var n2 = links[i].href.includes('nsaa-static.s3.amazonaws.com');
		
		if(n==true && n2==true)
		{
			var url=links[i].href;
			if(url.includes('nsaa-static.s3.amazonaws.com')==true)
			{
				links[i].setAttribute('href', url.replace('nsaa-static.s3.amazonaws.com', 'secure.nsaahome.org'));
				
			}
		}
	}
}
</script>";

$end_html="</td></tr></table>
<script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-47019718-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body></html>";
/*
<tr align=right><td colspan=2><br><br><br><br><br><br>
        <table width=135 border=0 cellpadding=2 cellspacing=0>
        <tr>
        <td width=135 align=center valign=top><script src=\"https://seal.verisign.com/getseal?host_name=nsaahome.org&size=S&use_flash=NO&use_transparent=NO&lang=en\"></script><br>
        <a href=\"http://www.verisign.com/ssl/ssl-information-center/\" target=\"_blank\"  style=\"color:#000000; text-decoration:none;font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align=center; margin=0px; padding:0px;\">ABOUT SSL CERTIFICATES</a></td>
        </tr>
        </table>
*/

//Wording for above E-mail Form portion of printer-friendly form:
$email_note="(Separate multiple e-mail addresses with COMMAS)";

//events for Track & Field
$treventslong=array("Pole Vault","High Jump","Long Jump","Triple Jump","Shot Put","Discus Throw","100-Meter Dash","200-Meter Dash","400-Meter Dash","800-Meter Run","1600-Meter Run","3200-Meter Run","110-Meter Hurdles","300-Meter Int. Hurdles","400-Meter Relay","1600-Meter Relay","3200-Meter Relay","Team Scores");

$trevents=array("pv","hj","lj","tj","sp","d","100","200","400m","800","1600m","3200m","110","300","400r","1600r","3200r","teamscores");

$treventslong_g=array("Pole Vault","High Jump","Long Jump","Shot Put","Discus Throw","Triple Jump","100-Meter Dash","200-Meter Dash","400-Meter Dash","800-Meter Run","1600-Meter Run","3200-Meter Run","100-Meter Hurdles","300-Meter Hurdles","400-Meter Relay","1600-Meter Relay","3200-Meter Relay","Team Scores");

$trevents_g=array("pv","hj","lj","sp","d","tj","100","200","400m","800","1600m","3200m","100h","300","400r","1600r","3200r","teamscores");

$trexample=array("12-5.25","5-4.5","20-8.75","45-1","50-1.5","155-2.5","12.0","25.8","59.8 or 1:01.4","2:10.8","4:55.2","11:02.3","14.9","47.9 or 1:00.5","46.1 or 1:02.9","3:57.1","10:02.4");
$trexampleacc=array("12-5.25","5-4.5","20-8.75","45-1","50-1.5","145-5.5","12.01","25.67","59.44 or 1:01.34","2:10.87","4:55.43","11:02.34","14.93","47.98 or 1:00/43","46.11 or 1:01.34","3:57.45","10:02.34");

$measure=array("ft in","ft in","ft in","ft in","ft in","ft in","sec","sec","min sec","min sec","min sec","min sec","sec","sec","sec","min sec","min sec");

$autoperf[A]=array("13-7","6-3","21-5","44-1","54-11","161-6","","","","","","","","","","","");
$autoperf[B]=array("13-8","6-3","21-1","44-2","52-8","149-9","","","","","","","","","","","");
$autoperf[C]=array("12-10","6-2","20-8","43-0","52-10","147-3","","","","","","","","","","","");
$autoperf[D]=array("11-8","6-1","20-9","42-3","49-9","136-11","","","","","","","","","","","");
$autoperf_g[A]=array("9-5","5-2","17-1","39-4","123-9","35-1","","","","","","","","","","","","");
$autoperf_g[B]=array("9-8","5-1","17-0","39-2","123-6","34-11","","","","","","","","","","","","");
$autoperf_g[C]=array("9-8","5-2","16-8","37-11","117-1","35-0","","","","","","","","","","","","");
$autoperf_g[D]=array("9-0","5-2","16-7","37-6","116-6","34-10","","","","","","","","","","","","");

$limit=array();
$limit[A][track]=12;	//top 7 finishers for Class A in track events
$limit[A][field]=4;	//top 4 finishers for Class A in field events
$limit[A][relay_sh]=12;	//top 7 relay teams for Class A (400m,1600m relays)
$limit[A][relay_lg]=12;	//top 7 relay teams for Class A (3200m relay)
$limit[B][track]=9;	//...etc...
$limit[B][field]=3;
$limit[B][relay_sh]=6;
$limit[B][relay_lg]=6;
$limit[C][track]=8;
$limit[C][field]=2;
$limit[C][relay_sh]=8;
$limit[C][relay_lg]=8;
$limit[D][track]=8;
$limit[D][field]=2;
$limit[D][relay_sh]=8;
$limit[D][relay_lg]=8;

//swimming events
$sw_events=array("200 Medley Relay","200 Freestyle","200 Individual Medley","50 Freestyle","100 Butterfly","100 Freestyle","500 Freestyle","200 Free Relay","100 Backstroke","100 Breaststroke","400 Free Relay","Diving");

//events for journalism contest
$jo_events=array("News/Feature Photography","Editorial Writing","Entertainment Writing","Sports News","Feature Writing","Headline Writing","Advertising","Yearbook Layout","Column Writing","Editorial Cartooning","Sports Feature","News Writing","Newspaper Layout","Yearbook Theme Development","Yearbook Theme Copy Writing","Yearbook Sports Feature Writing","Yearbook Feature");

//vars for Site Surveys
$ques2="<font size=3><i>Participating Information:</i></font><br>1) Indicate your choice of an NSAA school site for the sub-district/district in which your school is a participant:<br>&nbsp;&nbsp;&nbsp;&nbsp;";

$ques3="<br>2) If your school prefers another site, indicate your choice of that site:<br>&nbsp;&nbsp;&nbsp;&nbsp;";


//$staffs IS USED IN ADDRESS BOOK
$staffs=array("Superintendent","Principal","Athletic Director","Activities Director","Assistant Athletic Director","AD Secretary","Football","Boys Basketball","Girls Basketball","Boys Track & Field","Girls Track & Field","Boys Cross-Country","Girls Cross-Country","Volleyball","Wrestling","Boys Golf","Girls Golf","Boys Tennis","Girls Tennis","Boys Swimming","Girls Swimming","Baseball","Boys Soccer","Girls Soccer","Speech","Play Production","Debate","Instrumental Music","Vocal Music","Orchestra","Softball","Unified Bowling","Board President","Student Council Sponsor","Home Page","Sup Fax","Trainer","Journalism","Guidance Counselor");

$staffs_sm=array("Sup","Pr","AD","Act Dir","Assist AD","AD Sec","FB","BB","GB","BT","GT","BCC","GCC","VB","WR","BGO","GGO","BTen","GTen","BSw","GSw","Ba","BSo","GSo","SP","PP","Deb","IM","VM","Orchestra","SB","UBO","BD Pres","Stud Coun","Home Page","Sup Fax","Trainer","J","Guid Coun");

$staffs_cd=array("","","","","","","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Coach","Director","Director","Director","Director","Director","Director","Coach","","","","","","Director","");

//$staffs2 IS USED IN PRINTED/ONLINE DIRECTORY:
$staffs2=array("Superintendent","Principal","Athletic Director","Activities Director","Assistant Athletic Director","AD Secretary","Football","Volleyball","Boys Cross-Country","Girls Cross-Country","Cross-Country","Softball","Unified Bowling","Girls Golf","Boys Golf","Golf","Boys Tennis","Girls Tennis","Tennis","Baseball","Boys Basketball","Girls Basketball","Basketball","Wrestling","Boys Swimming","Girls Swimming","Swimming","Boys Track & Field","Girls Track & Field","Track & Field","Boys Soccer","Girls Soccer","Soccer","Speech","Music","Orchestra","Journalism","Guidance Counselor","Board President","Trainer");

$staffs_sm2=array("Su","Pr","AD","Act. Dir.","AAD","ADSec","FB","VB","BCC","GCC","CC","SB","UBO","GGo","BGo","GO","BTe","GTe","TEN","Ba","BBB","GBB","BB","WR","BSw","GSw","SW","BT","GT","TR","BSo","GSo","SO","SP/PP/Deb","MU","OR","J","GC","Bd. Pres.","Trainer");

	//1 if activity that can be registered for, else 0
$staffs_regsp=array("0","0","0","0","0","0","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","1","0","1","1","1","1");

//USED ON SCHOOL DIRECTORY PAGE
$staff=array("Superintendent","Principal","Athletic Director","Activities Director","AD Secretary","Assistant Athletic Director","Boys Cross-Country","Girls Cross-Country","Football","Girls Golf","Softball","Unified Bowling","Boys Tennis","Volleyball","Boys Basketball","Girls Basketball","Boys Swimming","Girls Swimming","Wrestling","Baseball","Boys Golf","Boys Soccer","Girls Soccer","Girls Tennis","Boys Track & Field","Girls Track & Field","Unified Track & Field","Debate","Journalism","Instrumental Music","Vocal Music","Orchestra","Play Production","Speech","Guidance Counselor","Student Council Sponsor","Board President","Trainer");

?>
