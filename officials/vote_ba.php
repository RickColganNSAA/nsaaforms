<?php
/* BALLOT FOR COACHES TO VOTE FOR BASEBALL UMPIRES */
/* ADAPTED FROM vote_bbb.php ON 3/14/12 (GAFFIGAN) */

if(!$_REQUEST['nsaa'])  //AD or coach voting: check their login
{
   require '../functions.php';
   require '../variables.php';

   //connect to db
   $db=mysql_connect($db_host,$db_user,$db_pass);
   mysql_select_db($db_name, $db);
   if(!ValidUser($session))
   {
      header("Location:../index.php?error=1");
      exit();
   }
   $header=GetHeader($session);
   $level=GetLevel($session);
}
else
{
   require 'functions.php';
   require 'variables.php';

   //connect to db
   $db=mysql_connect("$db_host",$db_user2,$db_pass2);
   mysql_select_db($db_name2, $db);
   if(!ValidUser($session))
   {
      header("Location:index.php?error=1");
      exit();
   }
   $header=GetHeader($session,"vote");
   $level=GetLevel($session);
}
if(!$school_ch && $level!=100 && $sample!=1)
   $school_ch=GetSchool($session);
if($level==1 && $sample==1)
   $school_ch="Test's School";
$school2=addslashes($school_ch);

$sql="SELECT nsaadist FROM $db_name.headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$dist=$row[0];

if($submit)
{
   $offlist=""; $count=0;
   for($i=0;$i<count($offid);$i++)
   {
      if($check[$i]=='x')
      {
         $sql="SELECT * FROM $db_name2.ba_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
	 $result=mysql_query($sql);
 	 if(mysql_num_rows($result)==0)
	 {
	    $sql2="INSERT INTO $db_name2.ba_votes (school,ad_coach,district,officialid) VALUES ('$school2','$ad_coach','$dist','$offid[$i]')";
	 }
	 else
	 {
	    $sql2="UPDATE $db_name2.ba_votes SET district='$dist' WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
	 }
	 $result2=mysql_query($sql2);

         $sql="SELECT first,last,city FROM $db_name2.officials WHERE id='$offid[$i]'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $offlist.="$row[first] $row[last] ($row[city])<br>";
         $count++;
      }
      else	//DELETE FROM DATABASE
      {
	 $sql="DELETE FROM $db_name2.ba_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
         $result=mysql_query($sql);
      }
   }
   if($count<=10)
   {
      echo $init_html;
      echo $header;
      echo "<br><br><table><tr align=left><td><b>You have voted for the following Baseball Umpires:</td></tr>";
      echo "<tr align=left><td>$offlist</td></tr>";
      echo "<tr align=left><td><b>Thank you for voting!<br><br></b>";
      if($level==1)
         echo "<a href=\"vote.php?sport=ba&session=$session\">Return to Baseball Ballots Admin</a>";
      else echo "<a href=\"../welcome.php?session=$session\">Home</a>";
      echo "</td></tr></table>";
      echo $end_html;
      exit();
   }
}

echo $init_html;
if($sample!=1) echo $header;

if($level==2)	//AD
   $ad_coach='ad';
else if($level==3)	//coach
   $ad_coach='coach';
//else ad_coach should be given (chosen on vote.php page)

//see if this person has voted already
$sql="SELECT * FROM $db_name2.ba_votes WHERE school='$school2' AND ad_coach='$ad_coach'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && $level!=1 && !($count>10 && $submit))
{
   echo "<br><br>You have already submitted your ballot for Baseball Umpires.<br><br>";
   echo "Thank You!<br><br><a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
//ELSE
echo "<form method=post action=\"vote_ba.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=ad_coach value=\"$ad_coach\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<table style='width:800px;' cellspacing=0 cellpadding=4 class='nine'><caption><b>";
if($sample==1) echo "Preview of ";
echo "Baseball Officials Ballot:</b><br><i>Please vote for a maximum of <b>10</b> officials.";
if($count>10 && $submit)
{
   echo "<br><font style=\"color:red\"><b>You have voted for too many officials. Please vote for a maximum of 10 officials and click \"Submit Ballot\".</b></font>";
}
echo "<br><br></caption>";

//OFFICIALS ON THE BALLOT MUST HAVE APPLIED TO WORK STATE, AVAILABLE ON ALL THE STATE TOURN DATES, MAILING >=100
$sql="SELECT t1.id,t1.first,t1.last,t1.city,t1.photofile,t1.photoapproved FROM $db_name2.officials AS t1, $db_name2.baoff AS t2, $db_name2.baapply AS t3 WHERE t1.id=t2.offid AND t2.offid=t3.offid AND t3.date5='x' AND t3.date6='x' AND t3.date7='x' AND t3.date8='x' AND t3.date9='x' ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
$total=mysql_num_rows($result);
$percol=$total/3;
$curcol=0;
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($ix%3==0) echo "<tr align=left valign=top height='110px'>";
   echo "<td";
   if($ix%2==0) echo " bgcolor='#e0e0e0'";
   echo "><input type=checkbox style='float:left;' name=\"check[$ix]\" value='x'";
   $sql2="SELECT * FROM $db_name2.ba_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$row[id]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0) echo " checked";
   echo ">";
   if($row[photofile]!='' && citgf_file_exists("photos/$row[photofile]") && $row[photoapproved]=='x')
      echo "<img src=\"photos/$row[photofile]\" style=\"float:left;height:100px;margin:3px;\">";
   echo "<b>$row[first] $row[last]</b><br>(".trim($row[city]).")<div style='clear:both;'></div>";
   echo "<input type=hidden name=\"offid[$ix]\" value=\"$row[id]\">";
   echo "</td>";
   if(($ix+1)%3==0) echo "</tr>";
   $ix++;
}
echo "</td></tr>";
echo "<tr align=center><td colspan=4><input type=submit name=submit value=\"Submit Ballot\"";
if($sample==1) echo " disabled";
echo "></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;

?>
