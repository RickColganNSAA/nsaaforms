<?php

if(!$_REQUEST['nsaa'])	//AD or coach voting: check their login
{
   require '../functions.php';
   require '../variables.php';

   //connect to db
   $db=mysql_connect("$db_host",$db_user,$db_pass);
   mysql_select_db($db_name, $db);
   if(!ValidUser($_REQUEST['session']))
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
   $db=mysql_connect($db_host,$db_user2,$db_pass2);
   mysql_select_db($db_name2, $db);
   if(!ValidUser($session))
   {
      header("Location:index.php?error=1");
      exit();
   }
   $header=GetHeader($session);
   $level=GetLevel($session);
   if($level==3) $level=100;
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
      if($rank[$i]!='none')
      {
         $sql="SELECT * FROM $db_name2.bbb_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
	 $result=mysql_query($sql);
 	 if(mysql_num_rows($result)==0)
	 {
	    $sql2="INSERT INTO $db_name2.bbb_votes (school,ad_coach,district,officialid,rank) VALUES ('$school2','$ad_coach','$dist','$offid[$i]','$rank[$i]')";
	 }
	 else
	 {
	    $sql2="UPDATE $db_name2.bbb_votes SET district='$dist',rank='$rank[$i]' WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
	 }
	 $result2=mysql_query($sql2);

         $sql="SELECT first,last,city FROM $db_name2.officials WHERE id='$offid[$i]'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $offlist.="$row[first] $row[last] ($row[city]) - ";
	 if($rank[$i]=='-2') $offlist.="POOR";
         else if($rank[$i]=='-1') $offlist.="BELOW AVERAGE";
	 else if($rank[$i]=='0') $offlist.="AVERAGE";
	 else if($rank[$i]=='1') $offlist.="GOOD";
	 else if($rank[$i]=='2') $offlist.="OUTSTANDING";
	 $offlist.="<br>";
         $count++;
      }
      else	//DELETE FROM DATABASE
      {
	 $sql="DELETE FROM $db_name2.bbb_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
         $result=mysql_query($sql);
      }
   }
   //confirmation message:
   echo $init_html;
   if($sample!=1) echo $header;
   echo "<br><br><table><tr align=left><td><b>You have voted for the following Boys Basketball officials:</td></tr>";
   echo "<tr align=left><td>$offlist</td></tr>";
   echo "<tr align=left><td><b>Thank you for voting!<br><br></b>";
   if($level==1)
      echo "<a href=\"vote.php?sport=bbb&session=$session\">Return to Boys Basketball Ballots Admin</a>";
   else echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo "</td></tr></table>";
   echo $end_html;
   exit();
}

echo $init_html;
if($sample!=1) echo $header;

if($level==2)	//AD
   $ad_coach='ad';
else if($level==3)	//coach
   $ad_coach='coach';
//else ad_coach should be given (chosen on vote.php page)

//see if this person has voted already
$sql="SELECT * FROM $db_name2.bbb_votes WHERE school='$school2' AND ad_coach='$ad_coach'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && $level!=1)
{
   echo "<br><br>You have already submitted your ballot for Boys Basketball Officials.<br><br>";
   echo "Thank You!<br><br><a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
//ELSE
echo "<form method=post action=\"vote_bbb.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=ad_coach value=\"$ad_coach\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\"><br>";
echo "<table class='nine' cellspacing=0 cellpadding=6><caption><b>";
if($sample==1) echo "Preview of ";
echo "Boys Basketball Officials Ballot:</b>";
echo "<p>Please select a rating below for each official you are familiar with. Officials are listed alphabetically by last name.</p><p>You may rate as many officials as you'd like. When you are finished, click \"Submit Ballot\" at the bottom of this screen.</p><br>";
echo "</caption>";
//get officials to be on ballot: applied to work state and are Certified
$database=$db_name2;
//$sql="SELECT t1.id,t1.first,t1.last,t1.city,t1.photofile,t1.photoapproved FROM $db_name2.officials AS t1, $db_name2.bboff AS t2, $db_name2.bbapply AS t3 WHERE t1.id=t2.offid AND t2.offid=t3.offid AND t2.class='C' AND t3.date14='x' AND t3.date15='x' AND t3.date16='x' ORDER BY t1.last,t1.first";
$sql="SELECT t1.id,t1.first,t1.last,t1.city,t1.photofile,t1.photoapproved FROM $db_name2.officials AS t1, $db_name2.bboff AS t2, $db_name2.bbapply AS t3 WHERE t1.id=t2.offid AND t2.offid=t3.offid AND  (t3.date14='x' OR t3.date15='x' OR t3.date16='x') ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
$total=mysql_num_rows($result);
$percol=$total/4;
$curcol=0;
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr valign=top align=left";
   if($ix%2==0) echo " bgcolor=#E0E0E0";
   if($row[photofile]!='' && citgf_file_exists("photos/$row[photofile]") && $row[photoapproved]=='x')
      echo "><td><img src=\"photos/$row[photofile]\" style=\"float:left;margin:0 5px 0 0;height:100px;\"><b>$row[first] $row[last]</b><br>($row[city])</td>";
   else
      echo "><td><b>$row[first] $row[last]</b><br>($row[city])</td>";
   $sql2="SELECT * FROM $db_name2.bbb_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$row[id]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td><input type=hidden name=\"offid[$ix]\" value=\"$row[id]\"><input type=radio name=\"rank[$ix]\" value='none'";
   if($level==100) echo " disabled";
   if(mysql_num_rows($result2)==0) echo " checked";
   echo "><b>No Vote</b></td><td>";
   echo "<input type=radio name=\"rank[$ix]\" value='-2'";
   if($level==100) echo " disabled";
   if($row2[rank]=="-2") echo " checked";
   echo ">Poor</td><td>";
   echo "<input type=radio name=\"rank[$ix]\" value='-1'";
   if($level==100) echo " disabled";
   if($row2[rank]=="-2") echo " checked";
   echo ">Below Average</td><td>";
   echo "<input type=radio name=\"rank[$ix]\" value='0'";
   if($level==100) echo " disabled";
   if($row2[rank]=="0") echo " checked";
   echo ">Average</td><td>";
   echo "<input type=radio name=\"rank[$ix]\" value='1'";
   if($level==100) echo " disabled";
   if($row2[rank]=="1") echo " checked";
   echo ">Good</td><td>";
   echo "<input type=radio name=\"rank[$ix]\" value='2'";
   if($level==100) echo " disabled";
   if($row2[rank]=="2") echo " checked";
   echo ">Outstanding";
   echo "</td></tr>";
   $curcol++; $ix++;
}
echo "<tr align=center><td colspan=7><input type=submit name=submit value=\"Submit Ballot\"";
if($sample==1 || $level==100) echo " disabled";
echo "></td></tr>";
echo "</table>";
echo "</form>";
if($level==100) echo "<a href=\"welcome.php?obssport=bbb&session=$session\">Return Home</a>";
echo $end_html;

?>
