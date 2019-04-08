<?php
//IMPORT POST/GET VARIABLES
if($_REQUEST)
{
   foreach($_REQUEST as $key => $value)
   {
        $$key=$value;
   }
}
if($_FILES)
{
   foreach($_FILES as $key => $value)
   {
      $$key = $_FILES[$key]['tmp_name'];
   }
}
require "../variables.php";

//EACH YEAR:
//Move existing slides/ files to a new folder for last year
//Open PPT file and go to File - Save As Pictures - will save them all with proper filename format
//FTP the new slides to the slides/ folder
//Update the slidect below and in WatchedAllSlides()
//Make sure wrassessors table has enough slide__ fields
$slidect=79;	//ALSO CHANGE THIS IN WATCHEDALLSLIDES()

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$states=array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WV","WA","WI","WY","DC");

function ValidAssessor($session)
{
   $sql="SELECT * FROM wrassessors WHERE session='$session'";
   $result=mysql_query($sql);
   if(!$session || $session=='') return FALSE;
   else if(mysql_num_rows($result)>0) return TRUE;
   else return FALSE;
}
function ValidAdmin($session)
{
  //return true if user is logged in as NSAA to School Login, false otherwise
  $sql="SELECT t1.* FROM sessions AS t1,logins AS t2 WHERE t1.login_id=t2.id AND t1.session_id='$session' AND t2.level='1'";
  $result=mysql_query($sql);
  if(mysql_num_rows($result)==0)
     return false;
  else return true;
}
function GetWRAUserName($userid)
{
   $sql="SELECT * FROM wrassessors WHERE userid='$userid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return "$row[first] $row[last]";
}
function GetWRAUserID($session)
{
   $sql="SELECT * FROM wrassessors WHERE session='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[userid];
}
function IsPaid($userid)
{
   $sql="SELECT * FROM wrassessorsapp WHERE assessorid='$userid' AND approved='yes' ORDER BY appid DESC LIMIT 1";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;		//HAS NEVER REGISTERED
   else 	//HAS REGISTERED, WAS IT THIS SCHOOL YEAR?
   {
      $row=mysql_fetch_array($result);
      $year=date("Y",$row[appid]);	//YEAR REGISTERED
      $month=date("m",$row[appid]);	//MONTH REGISTERED
      $curyr=date("Y");			//CURRENT YEAR
      $curmo=date("m");			//CURRENT MONTH
      if($curmo<6)	//IF TODAY B/T Jan 1 & May 31, YEAR IN THE FALL WAS $curyr-1
         $fallyear=$curyr-1;
      else		//ELSE, YEAR IN THE FALL WAS/IS $curyr
         $fallyear=$curyr; 
      if($month<6) 	//IF REGISTERED b/t Jan 1 & May 31, YEAR IN THE FALL WAS $year-1
         $regfallyear=$year-1;
      else		//ELSE, YEAR IN THE FALL WAS $year
         $regfallyear=$year;	 
      if($fallyear==$regfallyear)	//YES, REGISTERED THIS YEAR
         return TRUE;
      else return FALSE;
   } 
}
function GetYearsRegistered($userid)
{
   //RETURN LIST OF YEARS USER COMPLETED REGISTRATION FOR
   $sql="SELECT * FROM wrassessorsapp WHERE assessorid='$userid' AND approved='yes' ORDER BY appid ASC";
   $result=mysql_query($sql);
   $regyrs="";
   while($row=mysql_fetch_array($result))
   {
      $year=date("Y",$row[appid]); $mo=date("m",$row[appid]);
      if($mo<6) $year--;
      $regyrs.=$year.", ";
   }
   $regyrs=substr($regyrs,0,strlen($regyrs)-2);
   return $regyrs;
}
function WatchedAllSlides($userid)
{
   //AS OF OCT 2014 - use an Articulate Presentation; no longer counting slides
   $sql2="SELECT * FROM wrassessors WHERE userid='$userid' AND datecompleted>0";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0) return FALSE;
   else return TRUE;

   /*
   $total=79;
   $sql="SELECT * FROM wrassessors WHERE userid='$userid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   for($i=1;$i<=$total;$i++)
   { 
      if($row['slide'.$i]!='x') return FALSE;
   }
   return TRUE;
   */
} 
function GetAdminHeader($session)
{
   $header=GetAssessorHeader($session);
   $header=ereg_replace("wrassessor/index","welcome",$header);
   $header=ereg_replace("wrassessor/logout","logout",$header);
   return $header;
}
function GetAssessorHeader($session)
{
     $logo="NSAAheaderLogin.jpg";
     $color="#ffffff";
     $string="<table width=100% cellspacing=0 cellpadding=0>";
     $string.="<tr><td bgcolor=\"#19204f\">&nbsp;</td></tr>";
     $string.="<tr align=center>";
     $string.="<td valign=center bgcolor=$color>";
     $string.="<img src=\"/images/$logo\" width=\"800px\"></td></tr>";
     $string.="<tr><td bgcolor=#19204f><center>";
     $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/wrassessor/index.php?session=$session\">Home</a>";
     $string.="&nbsp;&nbsp;&nbsp;<font color=#FFFFFF><b>|&nbsp;&nbsp;&nbsp;</b></font>";
     $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/wrassessor/logout.php?session=$session\">Logout</a>";
     $string.="</td></tr>";
     $string.="<tr align=center><td align=center>";
     return $string;
}
?>
