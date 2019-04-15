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

global $lastdb;



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

$plus="<font style=\"font-size:12pt;\"><b>&#8853;</b></font>";
$minus="<font style=\"font-size:12pt;\"><b>&#8854;</b></font>";
$offmileagerate="1.00";	//MILEAGE RATE

$spevents=array("hum","ser","ext","poet","pers","ent","inf","dram","duet");
$spevents2=array("Humorous Interpretation of Prose","Serious Interpretation of Prose","Extemporaneous Speaking","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");
$spevents3=array("Humorous Prose","Serious Prose","Extemporaneous Speaking","Poetry","Persuasive","Entertainment","Informative","Drama","Duet Acting");

$classes=array("A","B","C1","C2","D1","D2");

$prefs_sm=array("humprose","serprose","oralpoetry","persuasive","entertain","extemp","inform","oraldrama","duet");
$prefs_sm2=array("Hum Prose","Ser Prose","Poetry","Pers Speak","Entertain Speak","Extemp Speak","Public Speak","Drama","Duet");
$prefs_lg=array("Humorous Interpretation of Prose","Serious Interpretation of Prose","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Extemporaneous Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");
$prefs_lg2=array("Hum<br>Prose","Ser<br>Prose","Poetry","Pers<br>Speak","Entertain<br>Speak","Extemp<br>Speak","Public<br>Speak","Drama","Duet");
$sp_export1=array("HUMOROUS PROSE","SERIOUS PROSE","EXTEMPORANEOUS SPEAKING","POETRY","PERSUASIVE SPEAKING","ENTERTAINMENT SPEAKING","INFORMATIVE PUBLIC SPEAKING","DRAMA","DUET ACTING");
$sp_export2=array("Humorous Interpretation of Prose","Serious Interpretation of Prose","Extemporaneous Speaking","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");

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

//officials application form date options

/***SOCCER***/
$sopositions=array("Center","AR1","AR2");

/***GIRLS TENNIS***/
$te_ghostdates=array("Friday, May 13, 2011");
$te_ghostdates2=array("2011-05-13");
$te_ghostdates_sm=array("5/13");

/***BOYS TENNIS***/
$te_bhostdates=array("Friday, October 8, 2010");
$te_bhostdates2=array("2010-10-08");
$te_bhostdates_sm=array("10/8");

$finalsubmit="Check this box when you have finished entering information and wish to make your final submission of this form to the NSAA.";

//create array of activity titles
$act_long=array("Softball","Basketball","Soccer","Football","Wrestling","Baseball","Volleyball","Swimming/Diving","Track","Diving");
$activity=array("sb","bb","so","fb","wr","ba","vb","sw","tr","di");

$eject_long=array("Baseball","Boys Basketball","Girls Basketball","Boys Cross-Country","Girls Cross-Country","Football","Boys Golf","Girls Golf","Boys Soccer","Girls Soccer","Softball","Boys Swimming/Diving","Girls Swimming/Diving","Boys Tennis","Girls Tennis","Boys Track & Field","Girls Track & Field","Volleyball","Wrestling");
$eject=array("ba","bb","bb","cc","cc","fb","go","go","so","so","sb","sw","sw","te","te","tr","tr","vb","wr");
$eject2=array("ba","bb_b","bb_g","cc_b","cc_g","fb","go_b","go_g","so_b","so_g","sb","sw_b","sw_g","te_b","te_g","tr_b","tr_g","vb","wr");

//months
$months=array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

$months2=array("January","February","March","April","May","June","July","August","September","October","November","December");

//top of html page and header info
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
<script type=\"text/javascript\" src=\"/javascript/UserLookup.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/Tree.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/Lookup.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/DBLookup.js\"></script>
<script type=\"text/javascript\" src=\"/javascript/OffAssign.js\"></script>
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

$end_html="</td></tr></table><script type=\"text/javascript\">

 // if (typeof replaceurl == 'function') { 

//		replaceurl();
//	}
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-47019718-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script></body></html>";

$email_note="(Separate multiple e-mail addresses with COMMAS)";
?>
