<?php

if(!$_REQUEST['nsaa'])  //AD or coach voting: check their login
{
   require '../functions.php';
   require '../variables.php';

   //connect to db
   $db=mysql_connect("$db_host",$db_user,$db_pass);
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
   if($sample==1) { $school_ch="Test's School"; $header="<table width=100%><tr align=center><td>"; }
}
if(!$school_ch)
   $school_ch=GetSchool($session);
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
         $sql="SELECT * FROM $db_name2.di_votes WHERE school='$school2' AND coach='$coach' AND officialid='$offid[$i]'";
	 $result=mysql_query($sql);
 	 if(mysql_num_rows($result)==0)
	 {
	    $sql2="INSERT INTO $db_name2.di_votes (school,coach,district,officialid) VALUES ('$school2','$coach','$dist','$offid[$i]')";
	 }
	 else
	 {
	    $sql2="UPDATE $db_name2.di_votes SET district='$dist' WHERE school='$school2' AND coach='$coach' AND officialid='$offid[$i]'";
	 }
	 $result2=mysql_query($sql2);

         $sql="SELECT * FROM $db_name2.di_judges WHERE id='$offid[$i]'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $offlist.="$row[first] $row[last] ($row[city]), $row[register]<br>";
         $count++;
      }
      else	//DELETE FROM DATABASE
      {
	 $sql="DELETE FROM $db_name2.di_votes WHERE school='$school2' AND coach='$coach' AND officialid='$offid[$i]'";
         $result=mysql_query($sql);
      }
   }
   $meetref2=addslashes(trim($meetref));
   $sql="SELECT * FROM $db_name2.di_meetref WHERE school='$school2' AND coach='$coach'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//insert
      $sql2="INSERT INTO $db_name2.di_meetref (school,meetref,coach) VALUES ('$school2','$meetref2','$coach')";
   else
      $sql2="UPDATE $db_name2.di_meetref SET meetref='$meetref2' WHERE school='$school2' AND coach='$coach'";
   $result2=mysql_query($sql2);
   if($meetref=='') 
      $offlist.="<br>You did NOT make a recommnedation for Diving Referee.<br>";
   else
      $offlist.="<br>You recommended <b>$meetref</b> for Diving Referee.<br>";

   if($count<=7)
   {
      echo $init_html;
      echo $header;
      echo "<br><br><table><tr align=left><td><b>You have voted for the following State Diving Judges:</td></tr>";
      echo "<tr align=left><td>$offlist</td></tr>";
      echo "<tr align=left><td><b>Thank you for voting!<br><br></b>";
      if($level==1)
         echo "<a href=\"vote.php?sport=di&session=$session\">Return to Diving Ballots Admin</a>";
      else echo "<a href=\"../welcome.php?session=$session\">Home</a>";
      echo "</td></tr></table>";
      echo $end_html;
      exit();
   }
}

echo $init_html;
echo $header;

if($level==3)	//coach
{
   $sql="SELECT t1.sport FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(!ereg("Swimming",$row[sport]))
   {
      echo "<br><br>You are not the swimming coach.";
      exit();
   }
   if(ereg("Boys",$row[sport])) $coach='boys';
   else $coach='girls';
}
//else coach should be given (chosen on vote.php page)

//see if this person has voted already
$sql="SELECT * FROM $db_name2.di_votes WHERE school='$school2' AND coach='$coach'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && $level!=1 && !($count>7 && $submit))
{
   echo "<br><br>You have already submitted your ballot for State Diving Judges.<br><br>";
   echo "Thank You!<br><br><a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
//ELSE
echo "<br>";
if($level==1 && $sample!=1)
   echo "<a href=\"vote.php?sport=di&session=$session\">Return to Diving Ballots Admin</a><br><br>";
echo "<form method=post action=\"vote_di.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=coach value=\"$coach\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<table cellspacing=0 cellpadding=5 class='nine'><caption><b>";
if($coach=='boys') $coachsp="Boys Swimming";
else if($coach=='girls') $coachsp="Girls Swimming";
else $coachsp="??";
if($level==1) echo "$school_ch $coachsp Coach's ";
echo "State Diving Judges Ballot:</b><br><i>Please vote for a maximum of <b>7</b> judges.";
if($count>7 && $submit)
{
   echo "<br><font style=\"color:red\"><b>You have voted for too many judges. Please vote for a maximum of 7 judges and click \"Submit Ballot\".</b></font>";
}
echo "<br><br></caption>";
//get judges from di_judges table
$sql="SELECT * FROM $db_name2.di_judges ORDER BY register DESC,last,first";
$result=mysql_query($sql);
echo "<tr align=left valign=top><td>";
$total=mysql_num_rows($result);
$percol=$total/2;
$curcol=0;
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($curcol>=$percol) 
   {
      echo "</td><td>";
      $curcol=0;
   }
   echo "<input type=checkbox name=\"check[$ix]\" value='x'";
   $sql2="SELECT * FROM $db_name2.di_votes WHERE school='$school2' AND coach='$coach' AND officialid='$row[id]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0) echo " checked";
   echo ">$row[first] $row[last] ($row[city]), <b>$row[register]</b>";
   echo "<input type=hidden name=\"offid[$ix]\" value=\"$row[id]\"><br><br>";
   $curcol++; $ix++;
}
echo "</td></tr>";
$sql="SELECT meetref FROM $db_name2.di_meetref WHERE school='$school2' AND coach='$coach'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<tr align=left><th colspan=2 align=left>I recommend <input type=text class=tiny size=30 value=\"$row[0]\" name=\"meetref\"> serve as Diving Referee.</th></tr>";
echo "<tr align=center><td colspan=4><input type=submit name=submit value=\"Submit Ballot\"></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;

?>
