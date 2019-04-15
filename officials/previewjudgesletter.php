<?php
require_once('functions.php');
require_once('variables.php');

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

?>
<html>
<head>
   <title>NSAA | Judges Application Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<table><tr align=center><td>

<?php
//show letter
echo "<table class='nine' style='width:600px;'><tr align=left><td>";
$date=split("-",GetTestDueDate("sp"));
$duedate=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
$year1=$date[0]; $year2=$year1+1;
$schoolyear="$year1-$year2";
echo "<p>Dear Judge,</p>
<p>
You have successfully completed the online judge's application form. You will need to view the NSAA rules meeting online and score an 80% or higher on the online test to complete registration for the $schoolyear school year. It is strongly recommended to the NSAA member schools that they give priority to hiring NSAA registered judges.
</p><p><b>
JUDGES MANUAL</b></p><p>
A Judges Manual and an NSAA pocket calendar will be sent to you First Class no later than one week after the application has been completed online. (Early registrant's mai
lings may be delayed until the manual has been published.)</p><p>
The Judge's Manual is available on the <a href=\"/\">NSAA website</a> under the <a href=\"/sp.php\">Speech</a> and <a href=\"/pp.php\">Play Production</a> links and also on your judges login page.</p>
 <p>
<b>RULES MEETINGS</b></p><p>
Before gaining access to rules meeting, you must pay the registration fee. The NSAA is offering FREE on-line rules meetings for a limited time period, which will satisfy the rules meeting requirement for head coaches and judges. During the two week \"no charge\" period, head coaches and judges can access the online rules meeting using their NSAA passcode. </p><ul><li><b>NEW JUDGES:</b><br>Go to the NSAA Home Page and click on the Speech or Play Production link.  Refer to the first item in regard to judge's registration.</li>
<li><b>LAST YEAR'S NSAA-REGISTERED JUDGES:</b><br>Go to the NSAA Home Page and Login with your NSAA judge's passcode.<br><br><label style='color:#ff0000;'><b><u>For those individuals that are a Head Speech or Play Production coach AND a Judge</u>, please login with your judges passcode to view the rules meeting. You will be asked if you are also a head Speech or Play Production coach, at which time you can select the school at which you coach.</b></label> </li></ul><p><a href=\"https://secure.nsaahome.org/nsaaforms/officials/rulesschedule.php?sport=sppp\" target=\"_blank\">Click Here for the Speech/Play Production Online Rules Meeting Schedule</a></p>";
echo "
<p><b>EXAMINATION</b></p><p>
The open book test must be completed online on or before $duedate to have your name placed on the roster of available registered judges. This information will be listed on the NSAA website under the secure administrator's login page. Any test received after this date will not be processed. Once registration has been completed, first year judges will be sent a lapel pin. Judges may purchase additional lapel pins for $5.00 each. </p>
<p><b>APPLICATIONS TO JUDGE DISTRICT & STATE</b></p>
<p>Judges interested in judging district or state, please go to the \"applications to judge district & state\" section of your login page.  Complete the Play Production and/or Speech application.  Judges are always needed, so please take the time to complete the application.  Judges completing the applications will be listed for coaches to vote their preference on judges for state.  Judges for state will be selected using the ballot information as one of the criteria in selection.  Judges hired to work the state championship will be expected to be available to judge the entire day. </p>
<p><b>PUBLICATION OF REGISTERED JUDGES</b></p><p>Judges who have completed registration will be listed on the NSAA website under the secure administrator's login page, showing only name and city that they reside.</p>";
echo "</td></tr></table>";
?>
</td></tr>
</table>
</body>
</html>
