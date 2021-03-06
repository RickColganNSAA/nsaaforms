<?php
$server_source=str_replace('nsaaforms/officials/functions.php','', __FILE__);
$server_source=str_replace('calculate/functions.php','', $server_source);
$server_source=str_replace('nsaaforms/functions.php','', $server_source);
$server_source=str_replace('functions.php','', $server_source); 
if($_SERVER['DOCUMENT_ROOT']!='')
	$server_source=$_SERVER['DOCUMENT_ROOT']."/";  


require_once $server_source.'define_paths.php';
require_once $server_source.'dbfunction.php';

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
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
putenv("TZ=America/Chicago");

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);
//$db=mysqli_connect("$db_host","$db_user","$db_pass","$db_name");

function ValidUser($session,$database="")
{
  require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
  //return true if user is valid, false otherwise
  if($database=="") $database=$db_name;
  $sql="SELECT * FROM $database.sessions WHERE session_id='$session'";
  $result=mysql_query($sql);
  if(mysql_num_rows($result)==0)
  {
     //echo "$sql<br>".mysql_error()."<br>"; exit();
     return false;
  }
  else return true;
}

function GetStudentInfo($studentid,$grade = TRUE,$database = NULL)
{
   require_once('variables.php');
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $sql="SELECT first,last,semesters FROM ";
   if($database) $sql.="$database.";
   else $sql.=$db_name.".";
   $sql.="eligibility WHERE id='$studentid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) //TRY LAST YEAR
   {
      $database=GetDatabase(date("Y")-1);
      $sql="SELECT first,last,semesters FROM $database.eligibility WHERE id='$studentid'";
      $result=mysql_query($sql);
   }
   $row=mysql_fetch_array($result);
   if(preg_match("/\(/",$row[0]))	//nickname
   {
      $first_nick=explode("(",$row[first]);
      $first_nick[1]=trim($first_nick[1]);
      $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      $row[0]=$first;
   }
   if(!$grade) return "$row[0] $row[1]";
   return "$row[0] $row[1] (".GetYear($row[2]).")";
}
function GetPhone($loginid)
{
   //Get Phone # for user. The reason we have a function for this is because if a staff member has only a partial #
   //listed, or no phone at all, we want to show the main school phone #.
   $sql="SELECT t1.*,t2.phone AS schphone FROM logins AS t1,headers AS t2 WHERE t1.school=t2.school AND t1.id='$loginid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $schph=explode("-",$row[schphone]);
   $area=$schph[0];
   $ph=explode("-",$row[phone]);
   $ph[0]=trim($ph[0]); $ph[1]=trim($ph[1]); $ph[2]=trim($ph[2]); $ph[3]=trim($ph[3]);
   if($row[phone]=='' || $row[phone]=='---') $phone=$row[schphone];
   else if($ph[1]=="" && $ph[2]=="")
   {
      $phone=$row[schphone];
      if($ph[3]!='') $phone.=" x$ph[3]";
   }
   else if($ph[0]=="")
   {
      $phone=$area."-".$ph[1]."-".$ph[2];
      if($ph[3]!='') $phone.=" x$ph[3]";
   }
   else
   {
      $phone="$ph[0]-$ph[1]-$ph[2]";
      if($ph[3]!='') $phone.=" x$ph[3]";
   }
   return $phone;
}
function FormatPhone($phone)
{
   $phone=preg_replace("/[^0-9]/","",$phone);
   if($phone=="") return $phone;
   $newphone="(".substr($phone,0,3).")".substr($phone,3,3)."-".substr($phone,6,4);
   return $newphone;
}
function GetEmail($categ)
{
   //return e-mail associated with sport or main e-mail
   switch($categ)
   {
      case "main":
	 $email="nsaa@nsaahome.org";
	 break;
      case "sw":
	 $email="jschwartz@nsaahome.org";
	 break;
      case "de":
	 $email="jschwartz@nsaahome.org";
	 break;
      case "jo":
	 $email="dvelder@nsaahome.org";
	 break;
      case "pp":
	 $email="dvelder@nsaahome.org";
	 break;
      case "tr":
	 $email="nneuhaus@nsaahome.org";
	 break;
      case "wr":
	 $email="rhigson@nsaahome.org";
	 break;
      case "sp":
	 $email="ccallaway@nsaahome.org";
	 break;
      case "cc":
	 $email="nneuhaus@nsaahome.org";
	 break;
      case "directory":
	 $email="ccallaway@nsaahome.org";
	 break;
      default:
	 $email="nsaa@nsaahome.org";
   }
   return $email;
}
function GetAge($dob="0000-00-00")
{
   //RETURN AGE OF PERSON WHO WAS BORN ON $dob
   if($dob=="0000-00-00") return 0;
   if(preg_match("/[0-9]{2}-[0-9]{2}-[0-9]{4}/",$dob))
   {
	$date=explode("-",$dob);
 	$dob=$date[2]."-".$date[0]."-".$date[1];
   }
   $today=date("Y-m-d");
   $sql="SELECT YEAR('$today') - YEAR('$dob') - (DATE_FORMAT('$today','%m%d') < DATE_FORMAT('$dob','%m%d')) AS diff_years";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
} 
function GetYearM($semester)
{
  //return year in school, given the semester
  if(!$semester) return "";
  if($semester==1 || $semester==2)
    return 7;
  else if($semester==3 || $semester==4)
    return 8;
  else if($semester>4)
    return ">8";
  else
    return "<7";
}
function GetYear($semester)
{
  //return year in school, given the semester
  if(!$semester) return "";
  if($semester==1 || $semester==2)
    return 9;
  else if($semester==3 || $semester==4)
    return 10;
  else if($semester==5 || $semester==6)
    return 11;
  else if($semester==7 || $semester==8)
    return 12;
  else if($semester<1)
    return "<9";
  else if($semester>8)
    return ">12";
  else return "";
}
function GetSchool($session)
{
   //get school of user in session: 
   $sql="SELECT t2.school FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[school];
}
function GetSchool2($schoolid)
{
   $sql="SELECT school FROM headers WHERE id='$schoolid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) return FALSE;
   return $row[school];
}
function GetSchoolID($session)
{
   //get schoolid of user in session:
   $sql="SELECT t3.id FROM sessions AS t1, logins AS t2,headers AS t3 WHERE t1.session_id='$session' AND t1.login_id=t2.id AND t3.school=t2.school";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[id];
}
function GetSchoolIDBySchool($school)
{
   //get schoolid of user in session:
   $sql="SELECT id FROM headers WHERE school='$school'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[id];
}
function GetSchoolID2($school,$year="")
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   if($year=="") $year=date("Y");
   $database=GetDatabase($year);
   //get schoolid of school:
   $sql="SELECT id FROM $database.headers WHERE school='".addslashes($school)."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[id];
}
function GetSIDByStudent($studentid,$sport='tr',$database="")
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   require_once('variables.php');
   if($database=="" || $database==$db_name) 
   {
      $database=$db_name; $year=date("Y");
   }
   else 
   {
      $year=substr(preg_replace("/[^0-9]/","",$database),0,4);
   }
   $sql="SELECT school FROM $database.eligibility WHERE id='$studentid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid=GetSID2($row[school],$sport,$year);
   return $sid;
}
function IsGirlsOnly($schoolid)
{
   $sql="SELECT * FROM headers WHERE id='$schoolid' AND girlsonly='x'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function IsBoysOnly($schoolid)
{
   $sql="SELECT * FROM headers WHERE id='$schoolid' AND boysonly='x'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function GetActivity($session)
{
   //get activity of user (coach) in session
   $sql="SELECT t2.sport FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[sport];
}
function GetUserName($session)
{
   //get name from logins table of this user
   $sql="SELECT t2.name FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[name];
}
function GetUserID($session)
{
   //get id from logins table of this user
   $sql="SELECT t2.id FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[id];
}
function GetMobileHeader($session)
{
   $html="<html><head><title>NSAA Scores Entry</title><link href=\"/css/formsmobile.css\" rel=stylesheet type=\"text/css\"></head><body>";
   $html.="<div id='header'><a href=\"/nsaaforms/welcome.php?session=$session\">Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"/nsaaforms/logout.php?session=$session\">Logout</a></div>";
   return $html;
}
function GetHeader($session)
{
  //get header info from db:
  $school=GetSchool($session);
  $school2=addslashes($school);
  $sql="SELECT * FROM headers WHERE school='$school2'";
  $result=mysql_query($sql);
  $row=mysql_fetch_array($result);
  $logo=$row[2];
  $color1=$row[3];
  $color2=$row[4];
  $mascot=strtoupper($row[6]);
  $address1=$row[7];
  $address2=$row[8];
  $city_etc="$row[9] $row[10]";
  $phone=$row[11];
  $school=strtoupper($school);

  //get level of user
  $level=GetLevel($session);
  if($level==9) $level=1;
  if($level==4)
  {
     $color1="#00008B";
     $color2="#FFFFFF";
     $school="NSAA College Login";
  }
  else if($level==5)
  {
     $color1="#00008B";
     $color2="#FFFFFF";
  }
  else if($level==6)
  {
     $color1="#00008B";
     $color2="#FFFFFF";
     $school="NSAA ESU Login";
  }
  else if($level==7 || $level==8)
  {
     $color1="#00008B";
     $color2="#FFFFFF";
  }

  if($level==1 || $level==7 || $level==8)
  {
     $logo="NSAAheaderLogin.jpg";
     $color="#ffffff";
     $string="<table width=100% cellspacing=0 cellpadding=0>";
     $string.="<tr><td bgcolor=#19204f>&nbsp;</td></tr>";
     $string.="<tr align=center>";
     $string.="<td valign=center bgcolor=$color>";
     $string.="<img src=\"/images/$logo\" width=\"800px\"></td></tr>";
     $string.="<tr><td bgcolor=#19204f><center>";
     if(GetUserName($session)=="Cornerstone")
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/cornerstone/index.php?session=$session\">Main Menu</a>";
     else if($level==1)
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/welcome.php?session=$session\">Home</a>";
     else if($level==8)
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/middle/welcome.php?session=$session\" target=\"_top\">Home</a>";
     else
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/sanctions/welcome.php?session=$session\">Home</a>";
     $string.="&nbsp;&nbsp;&nbsp;<font color=#FFFFFF><b>|&nbsp;&nbsp;&nbsp;</b></font>";
     if($level==1)
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/logout.php?session=$session\">Logout</a>";
     else if($level==8)
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/middle/logout.php?session=$session\" target=\"_top\">Logout</a>";
     else
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/sanctions/logout.php?session=$session\">Logout</a>";
     $string.="</td></tr>";
     $string.="<tr align=center><td align=center>";
  }
  else
  {
     $string="<table width=100% cellspacing=0 cellpadding=5>";
     $string.="<tr valign=top>";
     if($level<4 && citgf_file_exists($_SERVER['DOCUMENT_ROOT']."/images/$logo") && trim($logo)!='')
     {
	$string.="<td align=center style=\"width:125px;\"><img style=\"width:120px;\" src=\"/images/$logo\"></td>";
	$colspan=1;
     }
     else if($level==2)	//logo image not available
     {
        $string.="<td align=center width='100px'><br><a class=small href=\"directory.php?session=$session\">Upload your School Logo</a></td>";
        $colspan=1;
     }
     else
        $colspan=2;
     $string.="<td bgcolor=$color1 colspan=$colspan";
     $string.="><strong><font ";
     if($school=="LINCOLN NORTH STAR")
	$string.="color=#FFFFFF";
     else
	$string.="color=$color2";
     $string.=" size=5 face=\"Arial, Helvetica, sans-serif\">";
     $string.="$school $mascot</font></strong><br>";
     if($level<4)
     {
     $string.="<font ";
     if($school=="LINCOLN NORTH STAR") 
	$string.="color=#FFFFFF";
     else
	$string.="color=$color2";
     $string.=" size=2 face=\"Arial, Helvetica, sans-serif\">";
     $string.="<strong>$address1";
     if($address2!="") $string.="<br>$address2";
     $string.="<br>$city_etc<br>$phone</strong></font>";
     if($address1=='' || $city_etc=='' || $phone=='')
        $string.="<br><a class=small style=\"color:$color2\" href=\"directory.php?session=$session\">Complete your School's Information</a>";
     }
     $string.="</td></tr>";
     $string.="<tr align=center><td bgcolor=$color2 colspan=2>";
     $string.="<a class=header ";
     if($school=="LINCOLN NORTH STAR")
	$color1="#FFFFFF";
     $string.="style=\"color:$color1\" href=\"/nsaaforms/welcome.php?session=$session\">Home</a>";
     $string.="&nbsp;&nbsp;&nbsp;<font color=$color1><b>|</b></font>&nbsp;&nbsp;&nbsp;";
     if($level<4)
     {
     $string.="<a class=header";
     $string.=" style=\"color:$color1\" href=\"/forms.php\" target=new>Administration Forms (PDF)</a>&nbsp;&nbsp;&nbsp;";
     $string.="<font color=$color1><b>|</b></font>&nbsp;&nbsp;&nbsp;";
     }
     $string.="<a class=header";
     $string.=" style=\"color:$color1\" href=\"/nsaaforms/logout.php?session=$session\">Logout</a>";
     if(preg_match("/#FFFFFF/",$color2)) $string.="<br><hr>";
     $string.="</td></tr><tr align=center><td colspan=2 align=center><!--Begin Main Body-->";
   }
   return $string;
}

function GetEligHeader($session,$add_students="0")
{
  //get header to go at top of eligibility page 
  $school=GetSchool($session);
  $school2=addslashes($school);
  $sql="SELECT * FROM headers WHERE school='$school2'";
  $result=mysql_query($sql);
  $row=mysql_fetch_array($result);
  $color1=$row[3];
  $color2=$row[4];
  $mascot=strtoupper($row[6]);
  $school=strtoupper($school);
  if(mysql_num_rows($result)==0)	//NSAA-Access
  {
     $logo="NSAAheaderLogin_short.jpg";
     $color="#ffffff";
     $string="<table";
     if($add_students!=1)
     {
	$string.=" height=100%";
     }
     $string.=" width=100%";
     $string.=" bordercolor=#19204f border=1 cellspacing=0 cellpadding=0";
     $string.=">";
     $string.="<tr align=left bgcolor=$color border=0><td colspan=27 style=\"background-image:url('$home/images/$logo');background-repeat:no-repeat;background-position:top center;\">";
     $string.="<table cellpadding=0><tr align=left>";
     $string.="<td><font color=#19204>*** Don't forget to SAVE! ***<br>";
     $string.="Use TAB to move between fields<br>";
     $string.="and SPACE to make a check</font></td>";
     //$string.="<td><img src=\"$home/images/$logo\" height=\"70px\"></td></tr></table>";
     $string.="<td>&nbsp;</td></tr></table>";
     $string.="</td></tr>";
     if($add_students==1)
     {
        $string.="<tr align=center bgcolor=#000000><td colspan=27>";
        $string.="<a class=header style=color:#FFFFFF href=\"/nsaaforms/welcome.php?session=$session\">Home</a>";
        $string.="&nbsp;&nbsp;&nbsp;<font color=#FFFFFF><b>|</b></font>&nbsp;&nbsp;&nbsp;";
	$string.="<a class=header style=color:#FFFFFF href=\"/nsaaforms/logout.php?session=$session\">Logout</a>";
	$string.="&nbsp;&nbsp;&nbsp;<font color=#FFFFFF><b>|</b></font>&nbsp;&nbsp;&nbsp;";
        $string.="<a class=header style=color:#FFFFFF target=new href=\"/nsaaforms/help_ad.pdf\">Help</a>";
	$string.="</td></tr>";
      }
  }
  else 	//AD-access
  {
     $string="<table";
     if($add_students!=1) 
     {
	$string.=" height=100%";
     }
     $string.=" width=100%";
     $string.=" bordercolor=#000000 border=1 cellspacing=0 cellpadding=0";
     $string.=">";
     $string.="<tr align=left><td colspan=27 bgcolor=$color1>";
     $string.="<table width=100% cellspacing=0 cellpadding=0><tr align=left><td>";
     if($school=="LINCOLN NORTH STAR")
	$color2="#FFFFFF";
     $string.="<font color=$color2>*** Don't Forget to SAVE! ***<br>";
     $string.="Use TAB to move between fields ";
     $string.="and SPACE to make a check</font></td>";
     $string.="<td width=60%><center>";
     $string.="<strong><font size=5 face=\"Arial, Helvetica, sans-serif\" color=$color2>";
     $string.="$school $mascot</font></strong><br>";
     $string.="<font size=3 color=$color2><b>Eligibility List</b></font>";
     $string.="</center></td>";
     $string.="<td align=right><font color=$color2>NOTE: If you cannot see the";
     $string.=" column headers, scroll down.</font></td></tr></table>";
     $string.="</td></tr>";
  }
  return $string;
}

function GetActivityQuery($activity_name)
{
//return the portion of the WHERE-clause of the query if activity_name is
//one of the activities selected (usually in a multiple-select box)
   $activity_name=trim($activity_name);
   switch($activity_name)       //ugly case statement to make query
   {
      case "Baseball":
         $sql="ba='x' OR ";
         break;
      case "Boys Basketball":
	 $sql="(bb='x' AND gender='M') OR ";
	 break;
      case "Girls Basketball":
	 $sql="(bb='x' AND gender='F') OR ";
       	 break;
      case "Cheerleading/Spirit":
         $sql="ch='x' OR ";
         break;
      case "Boys Cross-Country":
         $sql="(cc='x' AND gender='M') OR ";
         break;
      case "Girls Cross-Country":
         $sql="(cc='x' AND gender='F') OR ";
         break;
      case "Debate":
	 $sql="de='x' OR ";
         break;
      case "Football 6/8":
         $sql="fb68='x' OR ";
         break;
      case "Football 11":
	 $sql="fb11='x' OR ";
    	 break;
      case "Football":
	 $sql.="(fb11='x' OR fb68='x') OR ";
	 break;
      case "Boys Golf":
         $sql="(go='x' AND gender='M') OR ";
	 break;
      case "Girls Golf":
         $sql="(go='x' AND gender='F') OR ";
         break;
      case "Journalism":
         $sql="jo='x' OR ";
         break;
      case "Music":
         $sql="(im='x' OR vm='x') OR ";
   	 break;
      case "Instrumental Music":
	 $sql="im='x' OR ";
	 break;
      case "Vocal Music":
	 $sql="vm='x' OR ";
	 break;
      case "Play Production":
         $sql="pp='x' OR ";
         break;
      case "Boys Soccer":
         $sql="(so='x' AND gender='M') OR ";
	 break;
      case "Girls Soccer":
         $sql="(so='x' AND gender='F') OR ";
	 break;
      case "Softball":
         $sql="sb='x' OR ";
         break;
      case "Speech":
	 $sql="sp='x' OR ";
	 break;
      case "Boys Swimming":
         $sql="(sw='x' AND gender='M') OR ";
         break;
      case "Girls Swimming":
	 $sql="(sw='x' AND gender='F') OR ";
     	 break;
      case "Boys Tennis":
         $sql="(te='x' AND gender='M') OR ";
         break;
      case "Girls Tennis":
         $sql="(te='x' AND gender='F') OR ";
         break;
      case "Boys Track & Field":
         $sql="(tr='x' AND gender='M') OR ";
         break;
      case "Girls Track & Field":
	    $sql="(tr='x' AND gender='F') OR ";
	    break;
      case "Volleyball":
         $sql="vb='x' OR ";
         break;
      case "Wrestling":
         $sql="wr='x' OR ";
         break;
	 case "Unified Bowling":
         $sql="ubo='x' OR ";
         break;	
   }
   return $sql;
}

function GetActivityAbbrev($activity_long)
{
//return the 2-letter abbreviation of the given activity name
   $activity_long=trim($activity_long);
   switch($activity_long)
   {
      case "Guidance Counselor":
	 $abb="gc";
	 break;
      case "Athletic Director":
	 $abb="ad";
	 break;
      case "Principal":
	 $abb="pr";
	 break;
      case "Baseball":
	 $abb="ba";
         break;
      case "Boys Basketball":
      case "Girls Basketball":
      case "Basketball":
	 $abb="bb";
  	 break;
      case "Cheerleading/Spirit":
	 $abb="ch";
         break;
      case "Boys Cross-Country":
      case "Girls Cross-Country":
      case "Cross-Country":
      case "Boys CC":
      case "Girls CC":
	 $abb="cc";
         break;
      case "Debate":
	 $abb="de";
         break;
      case "Football 6/8":
	 $abb="fb68";
	 break;
      case "Football 11":
	 $abb="fb11";
	 break;
      case "Football":
	 $abb="fb";
	 break;
      case "Boys Golf":
      case "Girls Golf":
      case "Golf":
	 $abb="go";
	 break;
      case "Journalism":
	 $abb="jo";
	 break;
      case "Music":
	 $abb="mu";
	 break;
      case "Play Production":
	 $abb="pp";
	 break;
      case "Boys Soccer":
      case "Girls Soccer":
      case "Soccer":
	 $abb="so";
	 break;
      case "Softball":
	 $abb="sb";
	 break;
      case "Speech":
	 $abb="sp";
	 break;
      case "Boys Swimming":
      case "Girls Swimming":
      case "Swimming":
      case "Swimming/Diving":
	 $abb="sw";
	 break;
      case "Boys Tennis":
      case "Boys Tennis, Class A";
      case "Boys Tennis, Class B";
      case "Girls Tennis":
      case "Girls Tennis, Class A";
      case "Girls Tennis, Class B";
      case "Tennis":
	 $abb="te";
	 break;
      case "Boys Track & Field":
      case "Boys Track":
      case "Girls Track & Field":
      case "Girls Track":
      case "Track & Field":
      case "Track":
	 $abb="tr";
	 break;
      case "Volleyball":
	 $abb="vb";
	 break;
      case "Wrestling":
	 $abb="wr";
	 break;
      case "Speech":
	 $abb="sp";
	 break;
      case "LD Debate":
	 $abb="ld_de";
	 break;
      case "CX Debate":
	 $abb="cx_de";
	 break;
      case "Cheerleading":
	 $abb="ch";
	 break;
      case "Activities Director":
	 $abb="acd";
	 break;
      case "Superintendent":
	 $abb="su";
	 break;
      case "Principle":
	 $abb="pr";
	 break;
      case "Instrumental Music":
	 $abb="im";
	 break;
      case "Vocal Music":
	 $abb="vm";
	 break;
      case "Newspaper":
	 $abb="np";
	 break;
      case "Yearbook":
	 $abb="yb";
	 break;
      case "Student Council Sponsor":
	 $abb="scs";
	 break;
      case "Board President":
	 $abb="bp";
	 break;
      case "Trainer":
	 $abb="trn";
	 break;
      case "Orchestra":
	 $abb="orc";
	 break;
      case "Assistant Athletic Director":
	 $abb="asst";
	 break;
      case "AD Secretary":
	 $abb="adsec";
	 break;
      case "Unified Bowling":
	 $abb="ubo";
	 break;
	  case "Unified Track & Field":
	 $abb="utr";
	 break;
      default:
	 $abb=$activity_long;
   }
   return $abb;
}
function GetActivityAbbrev2($activity_name)
{
   //get activity abbrev with gender appended:
   $abbrev=GetActivityAbbrev($activity_name);
   if(preg_match("/Girls/",$activity_name)) $abbrev.="_g";
   else if(preg_match("/Boys/",$activity_name)) $abbrev.="_b";
   return $abbrev;
}
function GetSchoolTable($actabb)
{
   $table="";
   if(preg_match("/ba/",$actabb)) $table="baschool";
   else if($actabb=="bbb" || $actabb=="bb_b" || $actabb=="bb") $table="bbbschool";
   else if($actabb=="bbg" || $actabb=="bb_g") $table="bbgschool";
   else if($actabb=="ccb" || $actabb=="cc_b" || $actabb=="cc") $table="ccbschool";
   else if($actabb=="ccg" || $actabb=="cc_g") $table="ccgschool";
   else if(preg_match("/de/",$actabb)) $table="deschool";
   else if(preg_match("/fb/",$actabb)) $table="fbschool";
   else if(preg_match("/jo/",$actabb)) $table="joschool";
   else if(preg_match("/pp/",$actabb)) $table="ppschool";
   else if(preg_match("/sb/",$actabb)) $table="sbschool";
   else if($actabb=="so_b" || $actabb=="sob" || $actabb=="so") $table="sobschool";
   else if($actabb=="so_g" || $actabb=="sog") $table="sogschool";
   else if(preg_match("/sp/",$actabb)) $table="spschool";
   else if(preg_match("/sw/",$actabb)) $table="swschool";
   else if($actabb=="te_b" || $actabb=="teb" || $actabb=="te") $table="te_bschool";
   else if($actabb=="te_g" || $actabb=="teg") $table="te_gschool";
   else if($actabb=="go_b" || $actabb=="gob" || $actabb=="go") $table="go_bschool";
   else if($actabb=="go_g" || $actabb=="gog") $table="go_gschool";
   else if($actabb=="trb" || $actabb=="tr_b" || $actabb=="tr") $table="trbschool";
   else if($actabb=="trg" || $actabb=="tr_g") $table="trgschool";
   else if(preg_match("/vb/",$actabb)) $table="vbschool";
   else if(preg_match("/wr/",$actabb)) $table="wrschool";
   else if($actabb=="mu") $table="muschools";
   else if($actabb=="ubo") $table="uboschool";
   return $table;
}
function GetEjectionActivity($act)
{  
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/officials/variables.php';
   //take value from $eject2 array and return corresponding value from $eject_long
   for($i=0;$i<count($eject2);$i++)
   {
      if($eject2[$i]==$act)
         $act_name=$eject_long[$i];
   }
   return $act_name;
}
function GetActivityName($sport)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/officials/variables.php';
   if($sport=='ad') return "AD";
   for($i=0;$i<count($activity);$i++)
   {
      if($activity[$i]==$sport)
      {
	if($sport=='tr') return "Track & Field";
        return $act_long[$i];
      }
   }
   if($sport=='utr') return "Unified Track & Field";
   if($sport=="ubo") return "Unified Bowling";
   if($sport=='wrd') return "Dual Wrestling";
   if($sport=='ch') return "Cheerleading/Spirit";
   if($sport=='sp') return "Speech";
   if($sport=='pp') return "Play Production";
   if($sport=='sppp') return "Speech/Play Production";
   if($sport=='bbb' || $sport=='bb_b' || $sport=='b_bb') return "Boys Basketball";
   if($sport=='bbg' || $sport=='bb_g' || $sport=='g_bb') return "Girls Basketball";
   if($sport=='so_b' || $sport=="bsoc" || $sport=='sob'|| $sport=='b_so') return "Boys Soccer";
   if($sport=='so_g' || $sport=="gsoc" || $sport=='sog' || $sport=='g_so') return "Girls Soccer";
   if($sport=='ba' || $sport=='base') return "Baseball";
   if($sport=='trb' || $sport=='tr_b' || $sport=='b_tr') return "Boys Track & Field";
   if($sport=='trg' || $sport=='tr_g' || $sport=='g_tr') return "Girls Track & Field";
   if($sport=='cc_b' || $sport=='b_cc' || $sport=='ccb') return "Boys Cross-Country";
   if($sport=='cc_g' || $sport=='g_cc' || $sport=='ccg') return "Girls Cross-Country";
   if($sport=='cc') return "Cross-Country";
   if($sport=='teb' || $sport=='te_b' || $sport=='b_te') return "Boys Tennis";
   if($sport=='teg' || $sport=='te_g' || $sport=='g_te') return "Girls Tennis";
   if($sport=="te") return "Tennis";
   if($sport=='sw_b' || $sport=='b_sw' || $sport=='swb') return "Boys Swimming";
   if($sport=='sw_g' || $sport=='g_sw' || $sport=='swg') return "Girls Swimming";
   if($sport=='go_b' || $sport=='gob' || $sport=='b_go') return "Boys Golf";
   if($sport=='go_g' || $sport=='gog' || $sport=='g_go') return "Girls Golf";
   if($sport=='go') return "Golf";
   if($sport=='jo') return "Journalism";
   if($sport=='sw_state') return "State Swimming";
   if($sport=='mu') return "Music";
   if($sport=='im') return "Instrumental Music";
   if($sport=='vm') return "Vocal Music";
   if($sport=='de') return "Debate";
   if($sport=='de_ld') return "Debate - Lincoln Douglas";
   if($sport=='de_cx') return "Debate - Cross";
   if($sport=='fb6') return "Football 6-Man";
   if($sport=='fb8') return "Football 8-Man";
   if($sport=='fb68') return "Football 6/8";
   if($sport=='fb11') return "Football 11";
   return FALSE;
}
function GetMusicSiteID($schoolid,$loginid=0)
{
   if($schoolid=="1616")	//TEST's SCHOOL
      return "1";
        //District Directors: when logged into School Login, grab their site's id (mudistricts.id)
   if($schoolid==0)	//College or other non-HS user
   {
        $sql="SELECT * FROM mudistricts WHERE loginid1='$loginid' OR loginid2='$loginid'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if(!$loginid || mysql_num_rows($result)==0) return 0;
        else return $row[id];
   }
   else
   {
        $sql="SELECT * FROM mudistricts WHERE schoolid1='$schoolid' OR schoolid2='$schoolid'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if(!$schoolid || mysql_num_rows($result)==0) return 0;
        else return $row[id];
   }
}
function GetMusicDistrictID($schoolid=0,$loginid=0)
{
        //District Coordinators: when logged into School Login, grab their district's id (mubigdistricts.id)
     	if($schoolid)
 	{
	   $sql="SELECT * FROM mubigdistricts WHERE schoolid1='$schoolid' OR schoolid2='$schoolid'";
	   $result=mysql_query($sql);
	   $row=mysql_fetch_array($result);
	   if(!$loginid && mysql_num_rows($result)==0) return 0;
	   if($loginid)
	   {
              $sql2="SELECT * FROM logins WHERE id='$loginid'";
	      $result2=mysql_query($sql2);
	      $row2=mysql_fetch_array($result2);
	      if($row2[level]==2 || preg_match("/Music/",$row2[sport]) || $row2[sport]=="Orchestra")
	      {
	         $sql="SELECT * FROM mubigdistricts WHERE loginid1='$loginid' OR loginid2='$loginid'";
	 	 $result=mysql_query($sql);
		 $row=mysql_fetch_array($result);
	         return $row[id];
	      }
	      else return 0;
	   }
	   else return $row[id];
        }
	else
	{
           $sql="SELECT * FROM mubigdistricts WHERE loginid1='$loginid' OR loginid2='$loginid'";
           $result=mysql_query($sql);
           $row=mysql_fetch_array($result);
           if(mysql_num_rows($result)==0) return 0;
           else return $row[id];
	}
}
function WillBeTooOld($dob,$semesters)
{
//looks at student's date of birth and semester and figures out if student
//will be 19 before Aug 1 of senior year
   //First, get date that will be Aug 1 before senior year for student:
   $year=GetYear($semesters);
   $cur_year=date(Y);
   $cur_month=date(n);
   if($cur_month<=6)    //b/t Jan and June
      $yrs_to_sr=11-$year;
   else                 //b/t July and Dec
      $yrs_to_sr=12-$year;
   $sr_yr=$cur_year+$yrs_to_sr;
   $sr_mo=8;
   $sr_day=1;
   //Now, calculate student's age on that date:
   $dob=explode("-",$dob);
   $b_month=$dob[0];
   $b_day=$dob[1];
   $b_year=$dob[2];
   $july31="$sr_yr-07-31";
   $dob="$b_year-$b_month-$b_day";
   $sql="SELECT (YEAR('$july31') - YEAR('$dob') - (DATE_FORMAT('$july31', '%m%d') < DATE_FORMAT('$dob', '%m%d'))) as diff_years";
//echo $sql;
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $age=$row[diff_years];
//echo "\r\n".$age."\r\n";
   //Return true if age >= 19
   if($age>=19 && $semesters!=0) return true;
   else return false;
}
function OldWillBeTooOld($dob,$semesters)
{
//looks at student's date of birth and semester and figures out if student
//will be 19 before Aug 1 of senior year
   //First, get date that will be Aug 1 before senior year for student:
   $year=GetYear($semesters);
   $cur_year=date(Y);
   $cur_month=date(n);
   if($cur_month<=6)	//b/t Jan and June
      $yrs_to_sr=11-$year;
   else			//b/t July and Dec
      $yrs_to_sr=12-$year;
   $sr_yr=$cur_year+$yrs_to_sr;
   $sr_mo=8;
   $sr_day=1;
   //Now, calculate student's age on that date:
   $dob=explode("-",$dob);
   $b_month=$dob[0];
   $b_day=$dob[1];
   $b_year=$dob[2];
   $year_dif=$sr_yr-$b_year;
   if(($b_month>$sr_mo) || ($b_month==$sr_mo && $sr_day<$b_day))
      $age=$year_dif-1;
   else
      $age=$year_dif;
   //Return true if age >= 19
   if($age>=19 && $semesters!=0) return true;
   else return false;
}
function WillBeTooOldM($dob,$semesters)
{
//looks at student's date of birth and semester and figures out if student	--> MIDDLE SCHOOL STUDENTS
//will be 15 before Aug 1 of 8th grade year
   //First, get date that will be Aug 1 before senior year for student:
   $year=GetYearM($semesters);
   $cur_year=date(Y);
   $cur_month=date(n);
   if($cur_month<=6)    //b/t Jan and June
      $yrs_to_sr=7-$year;
   else                 //b/t July and Dec
      $yrs_to_sr=8-$year;
   $sr_yr=$cur_year+$yrs_to_sr;
   $sr_mo=8;
   $sr_day=1;
   //Now, calculate student's age on that date:
   $dob=explode("-",$dob);
   $b_month=$dob[1];
   $b_day=$dob[2];
   $b_year=$dob[0];
   $year_dif=$sr_yr-$b_year;
   if(($b_month>$sr_mo) || ($b_month==$sr_mo && $sr_day<$b_day))
      $age=$year_dif-1;
   else
      $age=$year_dif;
   //Return true if age >= 15
   if($age>=15 && $semesters!=0) return true;
   else return false;
}
function IsTooOld($dob)
{
//return true if student is too old for the current school year 
   $cur_year=date(Y);
   $cur_month=date(n);
   $cur_day=date(j);
   $dob=explode("-",$dob);
   $b_month=$dob[0];
   $b_day=$dob[1];
   $b_year=$dob[2];
   //get their age on July 31 of the current school year:
   if($cur_month>=7)    //first semester
   {
      $age=$cur_year-$b_year;
      if($b_month>7) $age--;
   }
   else         //second semester
   {
      $age=($cur_year-1)-$b_year;
      if($b_month>7) $age--;
   }
   if($age>=19) $too_old=true;
   else $too_old=false;
   return $too_old;
}
function IsTooOldM($dob)
{
//return true if student is too old for the current school year	--> MIDDLE SCHOOL STUDENTS
   $cur_year=date(Y);
   $cur_month=date(n);
   $cur_day=date(j);
   $dob=explode("-",$dob);
   $b_month=$dob[1];
   $b_day=$dob[2];
   $b_year=$dob[0];
   //get their age on July 31 of the current school year:
   if($cur_month>=7)    //first semester
   {
      $age=$cur_year-$b_year;
      if($b_month>7) $age--;
   }
   else         //second semester
   {
      $age=($cur_year-1)-$b_year;
      if($b_month>7) $age--;
   }
   if($age>=15) $too_old=true;
   else $too_old=false;
   return $too_old;
}

function GetMonthNum($month)
{
   //return number of 3-letter abbrev of month
   switch($month)
   {
      case "Jan":
	 $mo_num=1;
	 break;
      case "Feb":
	 $mo_num=2;
	 break;
      case "Mar":
	 $mo_num=3;
	 break;
      case "Apr":
	 $mo_num=4;
	 break;
      case "May":
	 $mo_num=5;
	 break;
      case "Jun":
	 $mo_num=6;
	 break;
      case "Jul":
	 $mo_num=7;
	 break;
      case "Aug":
	 $mo_num=8;
	 break;
      case "Sep":
	 $mo_num=9;
	 break;
      case "Oct":
	 $mo_num=10;
	 break;
      case "Nov":
	 $mo_num=11;
	 break;
      case "Dec":
	 $mo_num=12;
	 break;
   }
   return $mo_num;
}
function GetScheduleDueDate($sport,$datefield="lockdate")
{
   if($sport=="gog") $sport="go_g";
   else if($sport=="gob") $sport="go_b";
   $sql="SELECT * FROM wildcard_duedates WHERE sport='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[$datefield];
}
function GetMiscDueDate($sport,$datefield="duedate")
{
   $sql="SELECT * FROM misc_duedates WHERE sport='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[$datefield];
}
function GetReimDueDate($sport,$datefield="duedate")
{
   if(preg_match("/cc/",$sport)) $sport="cc";
   else if(preg_match("/tr/",$sport)) $sport="tr";
   $sql="SELECT * FROM reim_duedates WHERE sport='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[$datefield];
}
function GetDueDate($form)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   //take form name and return due date like: May 5, 2004
   if($form=="bbb") $form="bb_b";
   else if($form=='bbg') $form="bb_g";
   else if(preg_match("/fb/",$form)) $form="fb";
   else if($form=="sob") $form="so_b";
   else if($form=="sog") $form="so_g";
   $sql="SELECT duedate FROM $db_name.form_duedates WHERE form='$form'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $due_date=$row[0];
   return $due_date;
}

function GetEligDate($activ)
{
//take activity and return due date for eligibility list like: May 5, 2004
   $sql="SELECT duedate FROM elig_duedates WHERE sport='$activ'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $due_date=$row[0];
   return $due_date;
}

function GetMonthDays($month)
{
 // given the number of the month, return number of days in that month
   switch($month)
   {
      case 1:
      case 3:
      case 5:
      case 7:
      case 8:
      case 10:
      case 12:
	 $length=31;
	 break;
      case 2:
	 $length=28;
      case 4:
      case 6:
      case 9:
      case 11:
	 $length=30;
	 break;
   }
   return $length;
}
function GetNow()
{
   return date("r");
}
function PastDue($due_date,$limit=0)
{ 
   //check if form is more than $limit days past due date
   if(preg_match("/-/",$due_date))
   {
      $date=explode("-",$due_date);
      $month=$date[1];
      $day=$date[2];
      $year=$date[0];
   } 
   else
   {
      $date=explode(" ",$due_date);
      $month=substr($date[0],0,3);
      $month=GetMonthNum($month);
      $day=substr($date[1],0,strlen($date[1])-1);
      $year=$date[2];
   }
   $date=mktime(0,0,0,$month,$day,$year);
   $today=time();	//-(60*60*12);	//go til noon next day
   $diff=$today-$date;          //difference in sec
   $diff=$diff/(60*60*24);      //difference in days
   $oneday=1;	//go to midnight on due date
   if($diff>($limit+$oneday)) return true;
   else return false;
}

function GetLatePage($due_date)
{
   $string="";
   $string.="<center><br><br><font size=2>Your team's form was due on <b>";
   $string.=$due_date;
   $string.="</b>.<br>You can no longer make changes to this form.</font>";
   return $string;
}

function GetLevel($session)
{
   //get level of current user
   $sql="SELECT t2.level FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $level=$row[0];
   return $level;
}

function DueSoon($form_date,$period="7")
{
   $due_date=explode("-",$form_date);
   $duedate=mktime(0,0,0,intval($due_date[1]),intval($due_date[2]),intval($due_date[0]));
   $today=time();
   $diff=$duedate-$today;
   $diff=$diff/(60*60*24);	//$diff=days between today and due date
   if($diff<=$period && $diff>=-5) $reminder=1;
   else $reminder=0;

   if($reminder==1) return true;
   else return false;
}

function CapFirst($string)
{
//capitalize only first letter of each word, rest of letters lowercase
   $words=explode(" ",$string);
   $string="";
   for($i=0;$i<count($words);$i++)
   {
      $lower=strtolower(substr($words[$i],1,strlen($words[$i])));
      $upper=strtoupper(substr($words[$i],0,1));
      if(strlen($words[$i])<3)
      {
	 $words[$i]=strtoupper($words[$i]);
      }
      else
      {
         $words[$i]="$upper$lower";
      }
      if(preg_match("/\(/",$words[$i]) && strlen($words[$i])>2)	//capitalize word in ()
      {
	 $sub_word=trim($words[$i]);
	 $index=strpos($words[$i],")");
	 $close=1;
	 if($index==false) 
	 {
	    $index=strlen($words[$i]);
	    $close=0;
	 }
	 else
	 {
	    $index--;
	 }
	 $sub_word=substr($words[$i],1,$index);
	 $sub_word=CapFirst($sub_word);
	 $words[$i]="($sub_word";
	 if($close!=0)	//closing parentheses
	 {
	    $words[$i].=")";
	 }
      }
      if(strpos($words[$i],"-"))	//capitalize word after '-'
      {
	 $sub_word=trim($words[$i]);
	 $str="-";
	 $index=strpos($sub_word,$str);
	 $sub_word1=substr($sub_word,0,$index);
	 $sub_word2=substr($sub_word,$index+1,strlen($sub_word));
	 $sub_word2=CapFirst($sub_word2);
	 $words[$i]="$sub_word1-";
	 $words[$i].="$sub_word2";
      }
      $string.="$words[$i] ";
   }
   $string=substr($string,0,strlen($string)-1);
   return $string;
}

function Unique($string)
{
   //string is comma-delimited
   //return string with duplicates taken out
   $string=explode(",",$string);
   $uniq_str="";
   for($i=0;$i<count($string);$i++)
   {
      $dup=0;
      for($j=0;$j<$i;$j++)
      {
	 if($string[$j]==$string[$i])
	    $dup=1;
      }
      if($dup==0)
      {
	 $temp=$string[$i];
	 $uniq_str.="$temp,";
      }
   }
   $uniq_str=substr($uniq_str,0,strlen($uniq_str)-1);
   return $uniq_str;
}
function NonUnique($string)
{
   //return elements of a comma-delimimted string that are duplicates
   $string=explode(",",$string);
   $dup_str="";
   for($i=0;$i<count($string);$i++)
   {
      $dup=0;
      for($j=0;$j<$i;$j++)
      {
	 if($string[$j]==$string[$i])
	    $dup=1;
      }
      if($dup==1)
      {
	 $temp=$string[$i];
	 $dup_str.="$temp,";
      }
   }
   $dup_str=substr($dup_str, 0, strlen($dup_str)-1);
   return $dup_str;
}
function GetRegistrationDatePaid($schoolid,$sport,$niceformat=0)
{
   $sql="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$sport' AND datepaid!='0000-00-00'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   $datepaid=$row[datepaid];
   if(!$niceformat) return $datepaid;
   $date=explode("-",$datepaid);
   return "$date[1]/$date[2]";
}
function IsRegistered2011($schoolid,$sport,$year="",$includepending=FALSE,$usetable="schoolregistration")
{
   require_once('variables.php');
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   if($year=="") $database=$db_name;
   else $database=GetDatabase($year);

   if($sport=='sob') $sport='so_b';
   else if($sport=='sog') $sport='so_g';
   else if($sport=='ccg') $sport='cc_g';
   else if($sport=='ccb') $sport='cc_b';
   else if($sport=='bbb') $sport='bb_b';
   else if($sport=='bbg') $sport='bb_g';
   else if($sport=='trg') $sport="tr_g";
   else if($sport=='trb') $sport="tr_b";
   else if($sport=='swg') $sport='sw_g';
   else if($sport=='swb') $sport='sw_b';
   if($sport=='cc')
   {
      if(IsRegistered2011($schoolid,'cc_b',$year,$includepending,$usetable) || IsRegistered2011($schoolid,'cc_g',$year,$includepending,$usetable))
         return true;
      else
         return false;
   }
   if($sport=='bb')
   {
      if(IsRegistered2011($schoolid,'bb_b',$year,$includepending,$usetable) || IsRegistered2011($schoolid,'bb_g',$year,$includepending,$usetable))
         return true;
      else
         return false;
   }
   if($sport=='di')
   {
      if(IsRegistered2011($schoolid,'sw_b',$year,$includepending,$usetable) || IsRegistered2011($schoolid,'sw_g',$year,$includepending,$usetable))
         return true;
      else
         return false;
   }
   else if($sport=='tr')
   {
      if(IsRegistered2011($schoolid,'tr_b',$year,$includepending,$usetable) || IsRegistered2011($schoolid,'tr_g',$year,$includepending,$usetable))
         return true;
      else
         return false;
   }
   else if(preg_match("/fb/",$sport)) $sport="fb";
   else if($sport=='de')
   {
      if(IsRegistered2011($schoolid,'de_ld',$year,$includepending,$usetable) || IsRegistered2011($schoolid,'de_cx',$year,$includepending,$usetable))
         return true;
      else
         return false;
   }
   else if($sport=='im' || $sport=='vm') $sport="mu";
   $sql="SELECT * FROM $database.$usetable WHERE schoolid='$schoolid' AND sport='$sport' AND ";
   if($sport=='cc')
      $sql="SELECT * FROM $database.$usetable WHERE schoolid='$schoolid' AND (sport='cc_b' OR sport='cc_g') AND ";
   else if($sport=='so')
      $sql="SELECT * FROM $database.$usetable WHERE schoolid='$schoolid' AND (sport='so_b' OR sport='so_g') AND ";
   else if(preg_match("/de/",$sport))
      $sql="SELECT * FROM $database.$usetable WHERE schoolid='$schoolid' AND (sport='de_ld' OR sport='de_cx') AND ";
   else if($sport=='sw')
      $sql="SELECT * FROM $database.$usetable WHERE schoolid='$schoolid' AND (sport='sw_b' OR sport='sw_g') AND ";
   else if($sport=='tr')
      $sql="SELECT * FROM $database.$usetable WHERE schoolid='$schoolid' AND (sport='tr_b' OR sport='tr_g') AND ";
   if($includepending) $sql.="datesub>0";       //GET ANY SCHOOL THAT REGISTERED, PAID OR NOT
   else $sql.="datepaid!='0000-00-00'";         //ONLY GET SCHOOLS THAT HAVE PAID
   
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function IsRegistered($school,$abbrev,$value='x')
{
   $sql="SELECT id FROM headers WHERE school='".addslashes($school)."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $schoolid=$row[id];
   return IsRegistered2011($schoolid,$abbrev);
   //return true if school is registered for given activity, else false
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $school2=addslashes($school);
   if($abbrev=='cc')
   {
      if(IsRegistered($school,'cc_b') || IsRegistered($school,'cc_g'))
         return true;
      else
	 return false;
   }
   if($abbrev=='di')
   {
      if(IsRegistered($school,'sw_b') || IsRegistered($school,'sw_g'))
         return true;
      else
         return false;
   }
   else if($abbrev=='tr')
   {
      if(IsRegistered($school,'tr_b') || IsRegistered($school,'tr_g'))
         return true;
      else
         return false;
   }
   if(preg_match("/fb/",$abbrev)) $abbrev="fb";
   if($abbrev=='de')
   {
      $sqlstr="(de_ld='$value' OR de_cx='$value')"; $i=count($act_regi);
   }
   else if($abbrev=='mu')
   {
      $sqlstr="(im='$value' OR vm='$value')"; $i=count($act_regi);
   }
   for($i=0;$i<count($act_regi);$i++)
   {
      if($act_regi[$i]==$abbrev || ($abbrev=='gob' && $act_regi[$i]=='go_b') || ($abbrev=='gog' && $act_regi[$i]=='go_g') || ($abbrev=='bbb' && $act_regi[$i]=='bb_b') || ($abbrev=='bbg' && $act_regi[$i]=='bb_g') || ($abbrev=='ccb' && $act_regi[$i]=='cc_b') || ($abbrev=='ccg' && $act_regi[$i]=='cc_g') || ($abbrev=='sob' && $act_regi[$i]=='so_b') || ($abbrev=='sog' && $act_regi[$i]=='so_g') || ($act_regi[$i]=="jo" && ($abbrev=="np" || $abbrev=="yb")))
      {
	 $sqlstr=$act_regi[$i]."='$value'"; $i=count($act_regi);
      }
   }
   $sql="SELECT * FROM registration WHERE school='$school2' AND $sqlstr";
   if(substr($sql,strlen($sql)-4,4)=="AND ") $sql=substr($sql,0,strlen($sql)-5);
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if(mysql_error()) echo "$sql:<br>".mysql_error()."<br><br>";
   if($ct>0)   
      return true;
   else   
      return false;
}
function IsSportOrAct($abbrev)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $foundsp=0;
   for($i=0;$i<count($act_regi);$i++)
   {
      if($act_regi[$i]==$abbrev) $foundsp=1;
   }
   if($abbrev=='mu' || preg_match("/fb/",$abbrev) || $abbrev=='de')
      $foundsp=1;
   if($foundsp==1) return true;
   else return false;
}
function IsDeclared($school,$abbrev)
{
   return IsRegistered2011(GetSchoolID2($school),$abbrev,"",TRUE);
   
   /**** ABOVE ADDED 7.24.14 SINCE WE DO NOT USE DECLARATIONS ANYMORE ****/

   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   //return TRUE if school is declared for given fall activity, else FALSE
   $school2=addslashes($school);
   $sql="SELECT * FROM $db_name.declaration WHERE school='$school2' AND $abbrev='y'";
   if($abbrev=='cc')
      $sql="SELECT * FROM $db_name.declaration WHERE school='$school2' AND (cc_b='y' OR cc_g='y')";
   else if($abbrev=='fb')
      $sql="SELECT * FROM $db_name.declaration WHERE school='$school2' AND (fb6='y' OR fb8='y' OR fb11='y')";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0) return TRUE;
   else return FALSE;
}
function RiggedSendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles)
{
   require_once("PHPMailer/mail.inc.php");
   // Instantiate your new class  
   $mail = new MyMailer;
   // Now you only need to add the necessary stuff  
   $mail->AddAddress($To, $ToName);
   $mail->Subject = $Subject;
   $mail->IsHTML(true);
   $mail->Body = $Html;
   for($i=0;$i<count($AttmFiles);$i++)
   {
      $mail->AddAttachment($AttmFiles[$i]);  // optional name  
   }
   if(!$mail->Send())
      return false;
   return true;
}
function updateLogoMail($school,$Html,$AttmFiles){
    require_once("PHPMailer/mail.inc.php");
    // Instantiate your new class
    $mail = new MyMailer;
    // Now you only need to add the necessary stuff
    $mail->AddAddress("mhuber@nsaahome.org", "NSAA Admin");
    $mail->Subject = "[$school] has changed their logo";
    $mail->IsHTML(true);
    $mail->Body = $Html;
    for($i=0;$i<count($AttmFiles);$i++)
    {
        $mail->AddAttachment($AttmFiles[$i]);  // optional name
    }
    if(!$mail->Send())
        return false;
    return true;
}

function SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles)
{
    global $SESClient;
	
	
  $Html=$Html?$Html:preg_replace("/\n/","{br}",$Text) or die("neither text nor html part present.");
  $Text=$Text?$Text:"Sorry, but you need an html mailer to read this mail.";
  
	 if(count($AttmFiles)<1) {
		   try {
     $result = $SESClient->sendEmail([
    'Destination' => [
        'ToAddresses' => [
            $To,
        ],
    ],
    'Message' => [
        'Body' => [
            'Html' => [
                'Charset' => CHARSET,
                'Data' => $Html,
            ],
			'Text' => [
                'Charset' => CHARSET,
                'Data' => $Text,
            ],
        ],
        'Subject' => [
            'Charset' => CHARSET,
            'Data' => $Subject,
        ],
    ],
    'Source' => 'nsaa@nsaahome.org',
    
]);

     $messageId = $result->get('MessageId');
     $dump=$messageId;

} catch (SesException $error) {
     $dump="The email was not sent. Error message: ".$error->getAwsErrorMessage()."\n";
	 $notsent=1;
     //echo("The email was not sent. Error message: ".$error->getAwsErrorMessage()."\n");
}
	}
	else {
		
		$notsent=1;
	}
	
	if(isset($notsent)){
		
	   include_once($_SERVER['DOCUMENT_ROOT']."/nsaaforms/PHPMailer/class.phpmailer.php");
	   // Instantiate your new class  
	   $mail = new PHPMailer;
	   // Now you only need to add the necessary stuff  
	   $To=str_replace(' ','',str_replace(';',',',$To));
	   
	   $counter=explode(',',$To);
	   foreach($counter as $email) 
	   $mail->AddAddress($email, $email);
	   
	   $mail->SMTPAuth = true;
	   $mail->SMTPDebug = 3;
	   $mail->SMTPSecure = 'tls';
	   $mail->Port = 587;
	   $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
	   $mail->Username = 'AKIAIAW365GLM626GB3A';
	//Password to use for SMTP authentication
		$mail->Password = 'ApJmfV8svBn0op5PJIYKREpp8nSevkQYNNzgpBd5ZpHH';
	   $mail->setFrom("nsaa@nsaahome.org", "NSAA");
	   $mail->AddReplyTo("nsaa@nsaahome.org", "NSAA");
	  
	   $mail->Subject = $Subject;
	   $mail->IsHTML(true);
	   $mail->AltBody = $Text;
	   $mail->Body = $Html;
		
		if($AttmFiles)
	  {
		foreach($AttmFiles as $AttmFile)
		{
		  $patharray = explode ("/", $AttmFile); 
		  $FileName=$patharray[count($patharray)-1];
		  
		  $fd=fopen(citgf_fopen ($AttmFile), "r");
		  //$FileContent=fread($fd,citgf_filesize($AttmFile));
		  $FileContent=stream_get_contents($fd);
		  fclose ($fd);
		  
		  $mail->addStringAttachment($FileContent, $FileName);
		}
	  }
		if (!$mail->preSend()) {
			 $mail->ErrorInfo;
			 $dump=null;
		} else {
			// Create a new variable that contains the MIME message.
			 $message = $mail->getSentMIMEMessage();
		}

		// Try to send the message.
		try {
			$result = $SESClient->sendRawEmail([
				'RawMessage' => [
					'Data' => $message
				]
			]);
			// If the message was sent, show the message ID.
			$messageId = $result->get('MessageId');
			$dump=true;
		} catch (SesException $error) {
			// If the message was not sent, show a message explaining what went wrong.
			$dump=null;
		}
	   
		/*if(!$mail->send()) {
			$dump=null;
		} else {
			$dump=true;
		}
		
		*/
	
	}
  
  $Subject=addslashes($Subject);
  $time=date(r);
  $sql="INSERT INTO maillog (recipient,subject,time) VALUES ('$To','$Subject','$time')";
  $result=mysql_query($sql);
  return $dump;
}

function CleanForPDF($string,$striptags=FALSE)
{
   $string=preg_replace("/&amp;/","and",$string);
   //$string=preg_replace("/&nbsp;/"," ",$string);
   $string=preg_replace("/&ndash;/","-",$string);
   $string=preg_replace("/&mdash;/","-",$string);
   $string=preg_replace("/&rsquo;/","'",$string);
   $string=preg_replace("/[^a-z0-9$%\\040\\.\\-\\+\\_\\/\\\"\'\!\,\\\\\<\>\/@: ~;&]/i"," ",$string);
   return $string;
}

function CleanCurrency($amount)
{
   //return clean version of $amount (no $ or ,)
   $newamount=str_replace('$',"",$amount);
   $newamount=preg_replace("/,/","",$newamount);
   return $newamount;
}

function DoesQualify($event,$mark)
{
   //determine if mark meets state swim meet qualifications
   //return Automatic if meets auto qualifier
   //return Secondary if meets secondary qualifier
   //return no if meets neither
   //see sw_qualify table for qualifying times
   if(preg_match("/Diving/",$event))	//Diving event
   {
      if($mark<300) 
	 return "no";
      else 
	 return "Automatic";
   }
   if(preg_match("/:/",$mark))	//mark is in min:sec.tenths format
   {
      $sql="SELECT qualmark,automark FROM sw_qualify WHERE eventfull='$event'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	//      $second=preg_split("/:|\./",$qualmark);
      $second=preg_split("/:|\./",$row[0]); 
      $auto=preg_split("/:|\./",$row[1]);
      $mark2=preg_split("/:|\./",$mark);
      //first check against auto mark
      $autoqual=1;	//assume it DOES meet auto mark
      if($mark2[0]>$auto[0])	//minutes are greater than auto mark minutes
	 $autoqual=0;
      else if($mark2[0]==$auto[0] && $mark2[1]>$auto[1]) //minutes same, seconds greater than auto
	 $autoqual=0;
      else if($mark2[0]==$auto[0] && $mark2[1]==$auto[1] && $mark2[2]>$auto[2]) //min/sec same, tenths greater
	 $autoqual=0;
      if($autoqual==1)
	 return "Automatic";
      else	//check secondary time
      {
	 $secqual=1;	//check in same manner as above
	 if($mark2[0]>$second[0])
	    $secqual=0;
	 else if($mark2[0]==$second[0] && $mark2[1]>$second[1])
	    $secqual=0;
	 else if($mark2[0]==$second[0] && $mark2[1]==$second[1] && $mark2[2]>$second[2])
	    $secqual=0;
	 if($secqual==1)
	    return "Secondary";
	 else	//did not meet either qualifying time
	    return "no";
      }
   }
   else	//mark is in seconds format
   {
      //check autoqual first
      $sql="SELECT qualmarksec,automarksec FROM sw_qualify WHERE eventfull='$event'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $row[0]=number_format($row[0],2,'.','');
      $row[1]=number_format($row[1],2,'.','');
      $autoqual=1;	//assume it meets auto qualifier
      if($mark>$row[1])	//mark is slower than autoqualifier
	 $autoqual=0;
      if($autoqual==1)
	 return "Automatic";
      else	//check secondary
      {
	 $secqual=1;
	 if($mark>$row[0])
	    $secqual=0;
	 if($secqual==1)
	    return "Secondary";
	 else	//did not meet either time
	    return "no";
      }
   }
}

function ConvertSecToMin($sec)
{
   $min=floor($sec/60);
   $sec=floor($sec%60);
   return "$min minutes, $sec seconds";
}

function ConvertToSec($mark)
{
   //convert mark in min:sec.hundredths format to seconds
   $tempmark=preg_split("/:|\./",$mark);
   $marksec=$tempmark[0]*60;
   $marksec+=$tempmark[1];
   $marksec.=".".$tempmark[2];
   return $marksec;
}

function ConvertFromSec($mark)
{
   //convert mark in seconds format to mm:ss.hh format
   $min=$mark/60;
   $min=substr($min,0,1);
   $sec=$mark-($min*60);
   $sec=number_format($sec,2,'.','');
   if(substr($sec,1,1)==".")	//only one num before decimal
      $sec="0".$sec;
   $marksec="$min:$sec";
   return $marksec;
}

function GetSWEvent($distance,$stroke,$i_r,$sex)
{
   //return name of swinmming event in correct format for sw_verify_perf_ table
   $event="";
   if($sex=="F")  $event.="Girls ";
   else  $event.="Boys ";
   if($distance=="1" || $stroke=="6") 
   {
      $event.="Diving";
      return $event;
   }
   $event.=$distance." ";
   if($stroke=="5")    //Medley
   {
      if(trim($i_r)=="R") //Relay
         $event.="Medley Relay";
      else      //indy
         $event.="Individual Medley";
   }
   else if($stroke=="1")       //Freestyle
   {
      if(trim($i_r)=="R") //Relay
         $event.="Free Relay";
      else      //indy
         $event.="Freestyle";
   }
   else
   {
      switch($stroke)
      {
         case 2:
      	    $event.="Backstroke";
	    break;
	 case 3:
	    $event.="Breaststroke";
	    break;
	 case 4:
	    $event.="Butterfly";
	    break;
      }
   }
   return $event;
}
function GetTrackStandard($event,$gender,$class)
{
   $field="class".$class;
   $sql="SELECT $field FROM tr_standards WHERE event='$event' AND gender='$gender'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) return FALSE;
   else return $row[0];
}
function GetRoster($sport,$school,$gender)
{
   $school2=addslashes($school);
   if($gender=='M') $sport2=$sport."_b";
   elseif($gender=='F') $sport2=$sport."_g";

   $sql="SELECT DISTINCT id,last,first,middle,semesters FROM eligibility WHERE $sport='x' ";
   if($gender=='M' || $gender=='F') $sql.="AND gender='$gender' ";
   $sql.="AND school='$school2'";
   $result=mysql_query($sql);
   $ix=0;
   $students=array();
   while($row=mysql_fetch_array($result))
   {
      $year=GetYear($row[4]);
      $students[$ix]="$row[2] $row[1],$year";
      $ix++;
   }

   //now get co_op students
   if($sport=='te' || $sport=='tr')
   {
      $sql="SELECT DISTINCT t1.id,t1.last,t1.first,t1.middle,t1.semesters FROM eligibility AS t1, ".$sport2."_coop AS t2 WHERE t1.$sport='x' ";
      if($gender=='M' || $gender=='F') $sql.="AND t1.gender='$gender' ";
      $sql.="AND t1.id=t2.student_id AND t2.co_op='$school2'";
   }
   else
      $sql="SELECT DISTINCT t1.id,t1.last,t1.first,t1.middle,t1.semesters FROM eligibility AS t1, $sport2 AS t2 WHERE t2.co_op='$school2' AND t1.id=t2.student_id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $year=GetYear($row[4]);
      $students[$ix]="$row[2] $row[1],$year";
      $ix++;
   }
   sort($students);
   return $students;
}
function RomanNumeralize($number)
{
   switch($number)
   {
      case 1:
	 $roman="I";
	 break;
      case 2:
	 $roman="II";
	 break;
      case 3:
	 $roman="III";
	 break;
      case 4:
	 $roman="IV";
	 break;
      case 5:
	 $roman="V";
	 break;
      case 6:
	 $roman="VI";
	 break;
      case 7:
	 $roman="VII";
	 break;
      case 8:
	 $roman="VIII";
	 break;
      case 9:
	 $roman="IX";
	 break;
      case 10:
	 $roman="X";
   }
   return $roman;
}
function CountDistEntries($school,$act)
{
   //require "../calculate/functions.php";
   $school2=addslashes($school);
   if(preg_match("/te/",$act))    //tennis
   {
      $sql2="SELECT t2.sid FROM headers AS t1,".$act."school AS t2 WHERE t1.school='$school2' AND (t1.id=t2.mainsch OR t1.id=t2.othersch1 OR t1.id=t2.othersch2 OR t1.id=t2.othersch3)";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $sql="SELECT * FROM ".$act."state WHERE sid='$row2[sid]'";
      //$sql="SELECT * FROM $act WHERE school='$school2' AND (type='1singles' OR type='1doubles' OR type='2singles' OR type='2doubles' OR type='substitute')";
   }
   else if($act=="de")    //debate
      $sql="SELECT * FROM $act WHERE school='$school2'";
   else
   {
      if($act=="pp")
      {
         $act.="_students";
         $sql="SELECT * FROM $act WHERE (school='$school2' OR co_op='$school2')";
      }
      else
         $sql="SELECT * FROM $act WHERE (co_op='$school2' OR school='$school2') AND checked='y'";
   }
   $result=mysql_query($sql);
   $num=mysql_num_rows($result);
   if(preg_match("/te/",$act))
   {
      while($row=mysql_fetch_array($result))
      {
         if(preg_match("/doubles/",$row[division]))   //if type is doubles
            $num++;     //count partner as well
      }
   }
   if($act=="de")
   {
      $num=0;
      $row=mysql_fetch_array($result);
      if($row[2]!="" && $row[2]!=NULL) $num++;
      if($row[6]!="" && $row[6]!=NULL) $num++;
   }
   return $num;
}
function IsReportCardSchool($school)
{
   if($school=="Test's School" || $school=='Schuyler' || $school=="Gering" || $school=="Omaha Skutt Catholic" || $school=="Valentine" || $school=="Tekamah-Herman" || $school=="David City" || $school=="Bishop Neumann" || $school=="Wahoo" || $school=="Mitchell" || $school=="Malcolm" || $school=="Thayer Central" || $school=="Neligh-Oakdale" || $school=="Newman Grove" || $school=="Freeman" || $school=="Bancroft-Rosalie" || $school=="Pope John" || $school=="Wausa" || $school=="Brady" || $school=="Falls City Sacred Heart" || $school=="Dodge")
      return TRUE;
   else
      return FALSE;
}
function IsReportCardOff($offid)   
{
   if($offid=='3427' || $offid=='4080' || $offid=='4679' || $offid=='3890' || $offid=='4439' || $offid=='4901' || $offid=='4758' || $offid=='6310' || $offid=='4507' || $offid=='3686' || $offid=='4365' || $offid=='4765' || $offid=='3975')
      return TRUE;
   else
      return FALSE;
}
function GetADInfo($school,$emailonly=FALSE)
{
   $school2=addslashes($school);
   $sql2="SELECT t1.name,t1.phone,t2.phone AS schphone,t1.email FROM logins AS t1, headers AS t2 WHERE t1.school=t2.school AND t1.school='".$school2."' AND t1.level='2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[name]!='')
   {
      if($row2[email]!='')
      {
	 if($emailonly) $adphone=$row2[email];
         else
	    $adphone="AD: $row2[name] <a href=\"mailto:$row2[email]\" class=small>$row2[email]</a> ";
      }
      else if(!$emailonly)
         $adphone="AD: $row2[name] ";
   }
   else
   {
      $sql2="SELECT t1.name,t1.phone,t2.phone AS schphone,t1.email FROM logins AS t1, headers AS t2 WHERE t1.school=t2.school AND t1.school='$school2' AND t1.sport='Activities Director'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($row2[email]!='')
      {
	 if($emailonly) $adphone=$row2[email];
         else
	    $adphone="Act Dir: $row2[name] <a href=\"mailto:$row2[email]\" class=small>$row2[email]</a> ";
      }
      else if(!$emailonly)
         $adphone="Act Dir: $row2[name] ";
   }
   if($emailonly) return $adphone;
   $temp1=explode("-",$row2[phone]);
   $temp2=explode("-",$row2[schphone]);
   if($temp1[1]!='' && $temp1[2]!='')
      $adphone.="$temp2[0]-$temp1[1]-$temp1[2]";
   else 
      $adphone.="$temp2[0]-$temp2[1]-$temp2[2]";
   if($temp1[3]!='') $adphone.=" ext $temp1[3]";
   return $adphone;
}
function SchoolDoesHost($session,$sport)
{
   require 'variables.php';
   //RETURN TRUE IF THIS USER'S SCHOOL IS HOSTING THIS SPORT
   //get school's id from headers table
   $school=GetSchool($session); $school2=addslashes($school);
   $sql="SELECT id FROM logins WHERE level='2' AND school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id];
   $districts=$sport."districts";
   $sql="SELECT id FROM $db_name2.$districts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0) return $row[id];
   else return FALSE;
}
function GetHostings($session,$headeronly=FALSE)
{
   //GET HOST CONTRACTS FOR THIS SCHOOL
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');

   $school=GetSchool($session); $school2=addslashes($school);
   $month=date("m");  $string="<table>";  $hosting=0;

   //get school's id from logins table
   $sql="SELECT id FROM $db_name.logins WHERE level='2' AND school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id];

   //first check if it is the summer and school didn't fill our spring financial reports yet:
   $year=date("Y"); $year1=$year+1; $year0=$year-1;
   $month=date("m");
   if($month>=6 && $month<=8)
   {
      $archivedb1=$db_name2.$year0.$year;
      $archivedb2=$db_name.$year0.$year;
      $sql="SHOW DATABASES LIKE '$archivedb'";
      $result=mysql_query($sql);
      $archive=0;
      if(mysql_num_rows($result)==0)
      {
         $year00=$year0-1;
         $archivedb1=$db_name2.$year00.$year0;
         $archivedb2=$db_name.$year00.$year0;
         $curyear="$year0-$year";
         $lastyear="$year00-$year0";
         $sql="SHOW DATABASES LIKE '$archivedb1'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0) $archive=0;
         else $archive=1;
      }
      else
      {
         $archive=1;
         $curyear="$year-$year1";
         $lastyear="$year0-$year";
      }
      $springsports=array("ba","so_b","so_g","tr");
      for($i=0;$i<count($springsports);$i++)
      {
         $districts=$springsports[$i]."districts";
  	 if($springsports[$i]=='tr') $districts="trbdistricts";
         $finance="finance_".$springsports[$i];
         $sql="SELECT id FROM $archivedb1.$districts WHERE hostid='$hostid'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)>0)	//check financial report on file for districts they hosted
         {
            while($row=mysql_fetch_array($result))
	    {
	       $distid=$row[0];
	       $sql2="SELECT * FROM $finance WHERE distid='$distid'";
	       $result2=mysql_query($sql2);
	       if(mysql_num_rows($result2)==0)	//no report on file, show link to this report
	       {
	          $sportname=GetActivityName($springsports[$i]);
                  $string.="<tr align=center><td><a class=small href=\"financialreports/".$springsports[$i]."finance.php?session=$session&distid=$distid&database=$archivedb2\">Click Here for your $lastyear ".GetActivityName($springsports[$i])." Financial Report</a></td></tr>";
	       }	
	    }
	 }
      }
   }
 
   $hostsportstring="";
   for($i=0;$i<count($hostsports);$i++)	//FOR EACH SPORT THIS SCHOOL IS HOSTING...
   {
      if($month>=6 || GetSeason($hostsports[$i])!="Fall")	//DON'T SHOW FALL SPORTS ANYMORE AFTER FIRST OF THE YEAR
      {
         $sportname=GetActivityName($hostsports[$i]);
         $districts=$hostsports[$i]."districts"; $disttimes=$hostsports[$i]."disttimes";
         if($hostsports[$i]=='fb') $districts="fbbrackets";

	//GET TOURNAMENT(S) THIS SCHOOL IS HOSTING
         $sql="SELECT *,id as distid FROM $db_name2.$districts WHERE hostid='$hostid'";
         if(preg_match("/bb/",$hostsports[$i])) $sql.=" OR hostid2='$hostid'";
	 $sql.=" ORDER BY type,class,district";
         
         if($hostsports[$i]=='fb')	//GET FOOTBALL SID FOR THIS SCHOOL. ALSO GET BOTH OPPONENTS FOR EACH GAME, NOT JUST HOST
   	 {
	    $sql2="SELECT t1.* FROM $db_name.fbschool AS t1, $db_name.headers AS t2 WHERE t1.mainsch=t2.id AND t2.school='$school2'";	
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    $sid=$row2[sid];
	    $sql="SELECT t1.*,t2.showoffs,t2.round FROM $db_name.fbsched AS t1,$db_name2.fbbrackets AS t2 WHERE t1.class=t2.class AND t1.gamenum=t2.gamenum AND ((t2.round='Finals' AND (t1.sid='$sid' OR t1.oppid='$sid')) OR (t2.round!='Finals' AND t1.homeid='$sid')) AND t1.round=t2.roundnum";
         }
      	 $result=mysql_query($sql);
	 $hostinfo=array(); $h=0;
         while($row=mysql_fetch_array($result))
         {
            $hostinfo[homeid][$h]=$row[homeid];
	    $hostinfo[scoreid][$h]=$row[scoreid];
	    $hostinfo[type][$h]=$row[type];
	    $hostinfo[accept][$h]=$row[accept]; $hostinfo[confirm][$h]=$row[confirm];
	    $hostinfo[post][$h]=$row[post];
	    $hostinfo[showoffs][$h]=$row[showoffs];
	    $hostinfo[showdistrict][$h]=$row[showdistrict];
	    $hostinfo[gender][$h]=$row[gender];
	    $hostinfo['class'][$h]=$row['class'];
	    $hostinfo[district][$h]=$row[district];
	    $hostinfo[round][$h]=$row[round];	
      	    $hostinfo[sid][$h]=$row[sid]; $hostinfo[oppid][$h]=$row[oppid];
	    $hostinfo[distid][$h]=$row[distid]; $hostinfo[disttimesid][$h]=$row[disttimesid];
	    $hostinfo[dates][$h]=$row[dates];
	    $h++;
	 }

   	 if($hostsports[$i]=='ba' || preg_match("/bb/",$hostsports[$i]))	//CHECK FOR BASEBALL/CLASS A BASKETBALL HOSTINGS IN $disttimes
	 {
	    $sql="SELECT t1.*, t1.id AS disttimesid,t2.type,t2.showoffs,";
	    if(preg_match("/bb/",$hostsports[$i])) $sql.="t2.gender,";
	    $sql.="t2.class,t2.district FROM $db_name2.$disttimes AS t1,$db_name2.$districts AS t2 WHERE t1.distid=t2.id AND t1.hostid='$hostid' ORDER BY t2.district,t1.day,t1.gamenum";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
               $hostinfo[homeid][$h]=$row[homeid];
               $hostinfo[scoreid][$h]=$row[scoreid];
               $hostinfo[type][$h]=$row[type];
               $hostinfo[accept][$h]=$row[accept]; $hostinfo[confirm][$h]=$row[confirm];
               $hostinfo[post][$h]=$row[post];
               $hostinfo[showoffs][$h]=$row[showoffs];
               $hostinfo[showdistrict][$h]=$row[showdistrict];
               $hostinfo[gender][$h]=$row[gender];
               $hostinfo['class'][$h]=$row['class'];
               $hostinfo[district][$h]=$row[district];
	       $hostinfo['gamenum'][$h]=$row['gamenum'];
               $hostinfo[round][$h]=$row[round];
               $hostinfo[sid][$h]=$row[sid]; $hostinfo[oppid][$h]=$row[oppid];
               $hostinfo[distid][$h]=$row[distid]; $hostinfo['disttimesid'][$h]=$row['disttimesid'];
               $h++;
	    }
         }

	 //ITERATE THROUGH $hostinfo
	 for($h=0;$h<count($hostinfo[distid]);$h++)
	 {
            if($hostsports[$i]=='fb' && $hostinfo[homeid][$h]==$sid) $fbhost=1;
	    else if($hostsports[$i]=='fb') $fbhost=0;
            if($hostinfo[type][$h]!='District Final' && $hostinfo[type][$h]!='Substate' && $hostsports[$i]!='fb' && $hostinfo[accept][$h]=='' && $hostinfo[post][$h]=='y')
            {
	       //CONTRACTS THAT NEED TO BE ACCEPTED (District Finals, Substate and Football do not have host contracts)
	       $hosting=1;
	       if(!preg_match("/$sportname/",$hostsportstring)) 
	   	  $hostsportstring.="$sportname, ";
               $string.="<tr align=center><td><table frame=all rules=all style=\"width:550px;border:#808080 1px solid;\" cellspacing=0 cellpadding=5 class='nine'><caption><b>$sportname:</b></caption><tr align=left><td>&nbsp;&nbsp;You have been selected to host ";
    	       if($hostinfo[showdistrict][$h]!='x' && preg_match("/go/",$hostsports[$i]) && $hostinfo['class'][$h]=="A") 	//CLASS A GOLF - NO DISTRICT KNOWN YET
		  $string.="a ".$hostinfo[gender][$h]." Class ".$hostinfo['class'][$h]." ".$hostinfo[type][$h]." $sportname Tournament:";
               if(preg_match("/tr/",$hostsports[$i]))	//TRACK AND FIELD
                  $string.="the ".$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." $sportname Meet:";
	       else if($hostinfo['disttimesid'][$h]>0)	//SINGLE GAME WITHIN A DISTRICT (BASEBALL/CLASS A BASKETBALL)
	          $string.="a ".$hostinfo[gender][$h]." ".$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." $sportname Tournament Game: (Game ".$hostinfo[gamenum][$h].")";
               else
               	  $string.="the ".$hostinfo[gender][$h]." ".$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." $sportname Tournament:";
               $string.="<ul>";
               $string.="<li><a target='_blank' href=\"officials/hostcontract.php?session=$session&sport=$hostsports[$i]&distid=".$hostinfo[distid][$h]."&disttimesid=".$hostinfo[disttimesid][$h]."\">Click Here to Accept/Decline this Contract to Host</a></li>";
               $string.="</ul></td></tr>";
               $string.="</table></td></tr>";
            }
	    else if(($hostinfo[accept][$h]=='y' && $hostinfo[post][$h]=='y') || ($hostsports[$i]=='fb' && $hostinfo[showoffs][$h]=='y') || (($hostinfo[type][$h]=='District Final' || $hostinfo[type][$h]=="Substate") && $hostinfo[showoffs][$h]=='y'))
            {
	       //IF CONTRACT IS ACCEPTED (OR for Dist Finals & FB: if officials released), SHOW OFFICIALS LIST
	       $hosting=1;
               if(!preg_match("/$sportname/",$hostsportstring)) 
                  $hostsportstring.="$sportname, ";
               $string.="<tr align=center><td><br><table cellspacing=0 cellpadding=5 rules=none frame=box style=\"width:550px;border:#a0a0a0 1px solid;\" class=nine><caption><b>$sportname:</b></caption>";
               if($hostsports[$i]=='fb' && (!$fbhost || $hostinfo[round][$h]=='Finals'))
                  $string.="<tr align=left><td>&nbsp;&nbsp; CONGRATULATIONS! You have made it to ";
               else
                  $string.="<tr align=left><td>&nbsp;&nbsp;You have agreed to host ";
               if($hostsports[$i]=='fb' && $hostinfo[showoffs][$h]=='y')	
	       {
		  $sql2="SELECT school FROM fbschool WHERE sid='".$hostinfo[sid][$h]."'";
	   	  $result2=mysql_query($sql2);	
	          $row2=mysql_fetch_array($result2);
		  $school1=$row2[0];
                  $sql2="SELECT school FROM fbschool WHERE sid='".$hostinfo[oppid][$h]."'";
                  $result2=mysql_query($sql2);  
                  $row2=mysql_fetch_array($result2);
	          $school2=$row2[0];
                  $string.="the <b><u>Class ".$hostinfo['class'][$h]." ".$hostinfo[round][$h]." Playoff Game ($school1 VS $school2)</b></u>:";
	       }
               else if($hostinfo['disttimesid'][$h]>0)  //SINGLE GAME W/I A DISTRICT (BASEBALL/CLASS A BASKETBALL)
                  $string.="a ".$hostinfo[gender][$h]." ".$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." $sportname Tournament Game (Game ".$hostinfo[gamenum][$h]."):";
	       else if(preg_match("/go/",$hostsports[$i]) && $hostinfo['class'][$h]=="A" && $hostinfo[showdistrict][$h]!='x')
	 	  $string.="a <b><u>".$hostinfo[gender][$h]." Class ".$hostinfo['class'][$h]." ".$hostinfo[type][$h]." $sportname Tournament</u></b>:";
               else if(preg_match("/tr/",$hostsports[$i]))
                  $string.="the <b><u>".$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." $sportname Meet:</u></b>";
               else if($hostsports[$i]!='fb')
                  $string.="<b><u>".$hostinfo[gender][$h]." ".$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." $sportname Tournament</u></b>:";
               $string.="<ul>";
               if($hostsports[$i]!='fb' && $hostinfo[type][$h]!='District Final' && $hostinfo[type][$h]!='Substate')
               {
                  if($hostinfo[confirm][$h]=='y')
                  {
                     $string.="<li>The NSAA has <i><b>confirmed</b></i> your contract.";
			//(NON-BASEBALL/CLASS A BASKETBALL) code by robin
                     if($hostsports[$i]=='vb' || preg_match("/so/",$hostsports[$i]) || (preg_match("/bb/",$hostsports[$i]) && $hostinfo['class'][$h]!="A") || $hostsports[$i]=='sb'|| $hostsports[$i]=="ubo")
	   	     {
                        $string.="<li>Please enter the date and time for each game in your tournament <a target=new href=\"officials/hostslots.php?session=$session&ad=1&sport=$hostsports[$i]&distid=".$hostinfo[distid][$h]."&disttimesid=".$hostinfo[disttimesid][$h]."\">Here</a>";
		     }
                     //check if due date for this dist entry form is past; if so, show link to forms
	    	     if(preg_match("/te/",$hostsports[$i]))
		     {
			$string.="<li><a href=\"te/host_".$hostsports[$i].".php?session=$session\">$sportname District Host Main Menu</a></li>";
		     }
	  	     if(preg_match("/tr/",$hostsports[$i]))
		     {
			if($hostsports[$i]=="trb") { $genderword="Boys"; $duedate1=GetDueDate('tr_b'); }
			else { $genderword="Girls"; $duedate1=GetDueDate('tr_g'); }
		        if(PastDue($duedate1,0) && $duedate1!='')
			   $string.="<li><a href=\"entryforms.php?distid=".$hostinfo[distid][$h]."&session=$session&sport=".$hostsports[$i]."\">$genderword Track & Field District Entry Forms submitted by schools in your district</a></li>";
                     }
                     else if(preg_match("/cc/",$hostsports[$i]))
                     {
                        $duedate1=GetDueDate("cc_b"); $duedate2=GetDueDate("cc_g");
                        if(PastDue($duedate1,0) && $duedate1!='')
                           $string.="<li><a href=\"entryforms.php?distid=".$hostinfo[distid][$h]."&session=$session&sport=$hostsports[$i]\">Cross-Country District Entry Forms submitted by the schools in your district</a></li>";
                        else if(PastDue($duedate2,0) && $duedate2!='')
                           $string.="<li><a href=\"entryforms.php?distid=".$hostinfo[distid][$h]."&session=$session&sport=$hostsports[$i]\">Cross-Country District Entry Forms submitted by the schools in your district</a></li>";
                     }
	       	     else
		     {
                        $duedate=GetDueDate($hostsports[$i]);
                        if(PastDue($duedate,0) && $duedate!='' && $hostsports[$i]!='pp' && $hostsports[$i]!='sp')
                           $string.="<li><a href=\"entryforms.php?distid=".$hostinfo[distid][$h]."&disttimesid=".$hostinfo[disttimesid][$h]."&session=$session&sport=$hostsports[$i]\">$sportname District Entry Forms submitted by schools you are hosting</a></li>";
	                else if(($hostsports[$i]=='pp' || $hostsports[$i]=='sp') && $duedate!='')  	//SPEECH/PLAY
			{
			   $showdate=GetDueDate($hostsports[$i].'showentries');
   			   if(PastDue($showdate,0))
			      $string.="<li><a href=\"entryforms.php?distid=".$hostinfo[distid][$h]."&session=$session&sport=$hostsports[$i]\">$sportname District Entry Forms submitted by the schools in your district</a></li>";
		        } 
		     }
                  }
                  else if($hostinfo[confirm][$h]=='n')
                     $string.="<li>The NSAA has <i>declined</i> your contract.";
                  else
                     $string.="<li>The NSAA has not responded to your contract.  Please check back later.";
                  if($hostinfo[confirm][$h]=='y' && (preg_match("/tr/",$hostsports[$i]) || $hostsports[$i]=='bbb' || $hostsports[$i]=='bbg' || $hostsports[$i]=='fb' || $hostsports[$i]=='vb' || $hostsports[$i]=='wr' || preg_match("/so/",$hostsports[$i]) || $hostsports[$i]=='ba') || $hostsports[$i]=='sb')
                  {
	    	     $bdistid=$hostinfo[distid][$h];
                     if($hostsports[$i]=='bbb') $page="bb_bfinance";
                     else if($hostsports[$i]=='bbg') $page="bb_gfinance";
                     else if($hostsports[$i]=='sob') $page="so_bfinance";
                     else if($hostsports[$i]=='sog') $page="so_gfinance";
	             else if(preg_match("/tr/",$hostsports[$i])) $page="trfinance";
                     else $page=$hostsports[$i]."finance";
		     if(preg_match("/tr/",$hostsports[$i])) 
	             {
			$labelsport="Track & Field";
			if(preg_match("/g/",$hostsports[$i]))	//GET BOYS $distid
		        {
			   $sql2="SELECT * FROM $db_name2.trbdistricts WHERE class='".$hostinfo['class'][$h]."' AND district='".$hostinfo[district][$h]."'";
			   $result2=mysql_query($sql2);
			   $row2=mysql_fetch_array($result2);
			   $bdistid=$row2[id];
		        }
	             }
		     else $labelsport=$hostsports2[$i];
                     $string.="<li><a href=\"financialreports/$page.php?session=$session&sport=$hostsports[$i]&distid=$bdistid&disttimesid=".$hostinfo[disttimesid][$h]."\">Click Here for your $labelsport Financial Report</a></li>";
                  }
                  $string.="<li><a target=new href=\"officials/hostcontract.php?session=$session&sport=$hostsports[$i]&distid=".$hostinfo[distid][$h]."&disttimesid=".$hostinfo[disttimesid][$h]."\">Click Here to View your Contract to Host</a></li>";
               }
	       else if($hostsports[$i]=='fb' && $fbhost && $hostinfo[round][$h]!='Finals')
	       {
	          $string.="<li><a href=\"financialreports/fbfinance.php?session=$session&scoreid=".$hostinfo[scoreid][$h]."\">Click Here for your Football Financial Report</a></li>";
	       }
               if(($hostsports[$i]=='sp' || $hostsports[$i]=='pp' || $hostinfo[showoffs][$h]=='y') && ($hostsports[$i]=='fb' || $hostinfo[confirm][$h]=='y' || $hostinfo[type][$h]=='District Final' || $hostinfo[type][$h]=='Substate'))
               {
                  $string.="<li>";
                  if($hostsports[$i]=='pp' || $hostsports[$i]=='sp')
                     $string.="<a target=new href=\"officials/".$hostsports[$i]."showtoad.php?session=$session&id=".$hostinfo[distid][$h]."&sport=$hostsports[$i]\">$hostports2[$i] Judges assigned to ";
                  else if($hostsports[$i]=='fb')
                     $string.="<a href=\"officials/".$hostsports[$i]."showtoad.php?session=$session&id=".$hostinfo[scoreid][$h]."\" target=new>$sportname Officials assigned to ";
		  else
                     $string.="<a href=\"officials/".$hostsports[$i]."showtoad.php?session=$session&id=".$hostinfo[distid][$h]."\" target=new>$sportname Officials assigned to ";
                  if($hostsports[$i]=='fb')
		  {
	             $sql2="SELECT school FROM fbschool WHERE sid='".$hostinfo[sid][$h]."'";
                     $result2=mysql_query($sql2); 
                     $row2=mysql_fetch_array($result2);
                     $school1=$row2[0];
                     $sql2="SELECT school FROM fbschool WHERE sid='".$hostinfo[oppid][$h]."'";
                     $result2=mysql_query($sql2); 
                     $row2=mysql_fetch_array($result2);
                     $school2=$row2[0];
                     $string.="Class ".$nostinfo['class'][$h].", ".$hostinfo[round][$h]." ($school1 VS $school2)";
	          }
                  else if(preg_match("/bb/",$hostsports[$i]) || $hostsports[$i]=='so')
                     $string.=$hostinfo[gender][$h]." ".$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h];
		  else if(preg_match("/go/",$hostsports[$i]) && $hostinfo['class'][$h]=="A")
	  	     $string.=$hostinfo[gender][$h]." Class ".$hostinfo['class'][$h]." ".$hostinfo[type][$h];
                  else
                     $string.=$hostinfo[type][$h]." ".$hostinfo['class'][$h]."-".$hostinfo[district][$h];
                  $string.="</a></li>";
		  if($hostsports[$i]=='pp')
		  {
		     $string.="<li><a href=\"pp/districtresults.php?session=$session&distid=".$hostinfo[distid][$h]."\">Enter District ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." Results</a></li>";
		     $string.="<li><a href=\"pp/createdistcert.php?distid=".$hostinfo[distid][$h]."&session=$session\">Create & Print Play Production District Award Certificates</a></li>";
		  }
               }
               if($hostinfo[type][$h]=="District Final" || $hostinfo[type][$h]=="Substate")
               {
                  //check if due date for this dist entry form is past; if so, show link to forms; also show link to financial report
                  $duedate=GetDueDate($hostsports[$i]);
                  if(PastDue($duedate,0))
                  {
                     $string.="<li><a href=\"entryforms.php?distid=".$hostinfo[distid][$h]."&disttimesid=".$hostinfo[disttimesid][$h]."&session=$session&sport=$hostsports[$i]\">$sportname District Entry Forms submitted by the schools in your ".$hostinfo[type][$h]."</a></li>";
                  }
	          if($hostsports[$i]=='bbb' || $hostsports[$i]=='bbg' || $hostsports[$i]=='vb' || $hostsports[$i]=='fb' || preg_match("/so/",$hostsports[$i]))
	          {
		     $sportname=GetActivityName($hostsports[$i]);
	             if(preg_match("/bb/",$hostsports[$i])) $page="bb_".substr($hostsports[$i],2,1)."finance";
                     else if(preg_match("/so/",$hostsports[$i])) $page="so_".substr($hostsports[$i],2,1)."finance";
	  	     else $page=$hostsports[$i]."finance";
	    	     $string.="<li><a href=\"financialreports/".$page.".php?session=$session&distid=".$hostinfo[distid][$h]."&disttimesid=".$hostinfo[disttimesid][$h]."\">Click Here for your $sportname Financial Report</a></li>";
		  }
               }
               if($hostinfo[confirm][$h]=='y' && preg_match("/cc/",$hostsports[$i]))  //for CC: show link to results
               {
                  $date=$hostinfo[dates][$h];    //one day for CC districts
                  if((PastDue($date,-3) && $date!="" && !preg_match("/00-00/",$date)))
                  {
	             if($hostsports[$i]=='ccb')
                        $string.="<li><a href=\"cc/state_cc_b_edit.php?dist_select=".$hostinfo[distid][$h]."&session=$session\">BOYS Cross-Country District Results Form</a></li>";
	             else
                        $string.="<li><a href=\"cc/state_cc_g_edit.php?dist_select=".$hostinfo[distid][$h]."&session=$session\">GIRLS Cross-Country District Results Form</a></li>";
                  }
		  else if($date!="" && !preg_match("/00-00/",$date) && !PastDue($date,-3))
		  {
		      $date2=explode("-",$date);
		      $datesec=mktime(0,0,0,$date2[1],$date2[2],$date2[0]);
		      $seesec=$datesec-(2*24*60*60);
		      $string.="<li>Your <b>Cross-Country District Results Forms</b> will be available on <b>".date("F j, Y",$seesec)."</b>.</li>";
	   	  }
               }
               else if($hostinfo[confirm][$h]=='y' && preg_match("/go/",$hostsports[$i]))  //for GOB/GOG: show link to results
               {
                  $date=$hostinfo[dates][$h];   //ONE DAY FOR GO DISTRICTS
                  if((PastDue($date,-5) && $date!="" && !preg_match("/00-00/",$date)))
                  {
                     $string.="<li><a href=\"go/districtresults.php?distid=".$hostinfo[distid][$h]."&sport=$hostsports[$i]&session=$session\">District ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." Results Form</a></li>";
                  }
                  else if($date!="" && !preg_match("/00-00/",$date) && !PastDue($date,-5))
                  {
                      $date2=explode("-",$date);
                      $datesec=mktime(0,0,0,$date2[1],$date2[2],$date2[0]);
                      $seesec=$datesec-(4*24*60*60);
                      $string.="<li>Your <b>District ".$hostinfo['class'][$h]."-".$hostinfo[district][$h]." Results Forms</b> will be available on <b>".date("F j, Y",$seesec)."</b>.</li>";
                  }
               }
               else if($hostinfo[confirm][$h]=='y' && $hostsports[$i]=='sp')   //SP: show link to results
               {
                  $dates=explode("/",$hostinfo[dates][$h]);
                  $date=$dates[0];
                  if(PastDue($date,-5) && $hostinfo[dates][$h]!='' && !preg_match("/00-00/",$date))
                  {
                     $string.="<li><a href=\"sp/sp_state_view.php?distid=".$hostinfo[distid][$h]."&session=$session\">Speech District Results Entry Form</a></li>";
		     $string.="<li><a href=\"sp/createdistcert.php?distid=".$hostinfo[distid][$h]."&session=$session\">Create & Print District Award Certificates</a></li>";
                  }
               }
               else if($hostinfo[confirm][$h]=='y' && preg_match("/tr/",$hostsports[$i]))     //TR: Show link to results
               {
                  $date=explode("-",$hostinfo[dates][$h]);
                  $showdate="$date[0]-05-01";
                  if(PastDue($showdate,-1) && $hostinfo[dates][$h]!='')
                  {
                     $string.="<li><a href=\"tr/tr_state_edit_b.php?session=$session&distid=".$hostinfo[distid][$h]."\">Boys Track & Field District Results Entry Form</a></li>";
                     $string.="<li><a href=\"tr/tr_state_edit_g.php?session=$session&distid=".$hostinfo[distid][$h]."\">Girls Track & Field District Results Entry Form</a></li>";
                  }
	 	  else 
		     $string.="<li><font style=\"color:#8b0000\"><b>The link to the Track & Field District Results Entry Forms will be here starting MAY 1ST.</font></b></li>";
               }
	       $string.="</td></tr></table>";
	    }//end if host agreement accepted
	 }//end for each district hosting
      }//end if month >6 or not a fall sport
   }//end for each sport
   if($headeronly)
   {
      if($hosting==0)
	 return "[You have no contracts at this time.]";
      else
         return substr($hostsportstring,0,strlen($hostsportstring)-2);
   }
   if($hosting==0) 
      $string.="<tr align=center><td>[You have no contracts at this time.]</td></tr>";
   $string.="</table>";
   return $string;
}
function GetRulesMeetings($session)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $school=GetSchool($session);
   $school2=addslashes($school);
   $string='<table>';
   //check if school is hosting any rules meetings
   $sql="USE $db_name2";
   $result=mysql_query($sql);
   $sql="SHOW TABLES LIKE '%ruleshosts'";
   $result=mysql_query($sql);
   $hosting=0;
   while($row=mysql_fetch_array($result))
   {
      $temp=explode("ruleshosts",$row[0]);
      $cursp=$temp[0];
      $curtbl=$row[0];
      $sql2="SELECT * FROM $db_name2.$curtbl WHERE hostname='$school2' AND post='y'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         $hosting=1;
         while($row2=mysql_fetch_array($result2))
         {
            $string.="<tr align=center><td><table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
            $string.="<tr align=left><td><b>You have been selected to host a ".GetActivityName($cursp)." Rules Meeting";
            if($row2[type]!='Regular') $string.=" ($row2[type] Site)";
            $date=explode("-",$row2[mtgdate]);
            $string.=" on $date[1]/$date[2]/$date[0]</b>:<br><ul>";
            if($row2[accept]=='')
               $string.="<li><a target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Click Here to Respond to your Contract to Host</a></li>";
            else
            {
               if($row2[accept]=='y')
               {
                  $string.="<li>You have ACCEPTED your <a target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Contract to Host</a></li>";
                  if($row2[confirm]=='')
                     $string.="<li>The NSAA has not responded to your contract yet.</li>";
                  else if($row2[confirm]=='y')
                     $string.="<li>The NSAA has CONFIRMED your contract.</li>";
                  else
                     $string.="<li>The NSAA has REJECTED your contract.</li>";
               }
               else     //accept==n
               {
                  $string.="<li>You have DECLINED your <a target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Contract to Host</a></li>";
                  if($row2[confirm]=='y')
                     $string.="<li>The NSAA has ACKNOWLEDGED your contract.</li>";
                  else
                     $string.="<li>The NSAA has not responded to your contract yet.</li>";
               }
            }
            $string.="</ul></td></tr></table></td></tr>"; 
         }
      }//end if contracted to host $cursp rules meeting(s)
   }//end for each sport
   if($hosting==0)
      $string.="<tr align=center><td>[You have no contracts at this time.]</td></tr>";
   $string.="</table>";
   return $string;
}
function GetSupTests($session)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $school=GetSchool($session);
   $school2=addslashes($school);
   $string='<table>';
   //check if school is hosting any sup tests
   $hosting=0;
   $sql2="SELECT * FROM $db_name2.suptesthosts WHERE hostname='$school2' AND post='y'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      $hosting=1;
      while($row2=mysql_fetch_array($result2))
      {
         $string.="<tr align=center><td><table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
         $string.="<tr align=left><td><b>You have been selected to host a Supervised Test";
         $date=explode("-",$row2[mtgdate]);
         $string.=" on $date[1]/$date[2]/$date[0]</b>:<br><ul>";
         if($row2[accept]=='')
            $string.="<li><a target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Click Here to Respond to your Contract to Host</a></li>";
         else
         {
            if($row2[accept]=='y')
            {
               $string.="<li>You have ACCEPTED your <a target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Contract to Host</a></li>";
               if($row2[confirm]=='')
                  $string.="<li>The NSAA has not responded to your contract yet.</li>";
               else if($row2[confirm]=='y')
                  $string.="<li>The NSAA has CONFIRMED your contract.</li>";
               else
                  $string.="<li>The NSAA has REJECTED your contract.</li>";
            }
            else     //accept==n
            {
               $string.="<li>You have DECLINED your <a target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Contract to Host</a></li>";
               if($row2[confirm]=='y')
                  $string.="<li>The NSAA has ACKNOWLEDGED your contract.</li>";
               else
                  $string.="<li>The NSAA has not responded to your contract yet.</li>";
            }
         }
         $string.="</ul></td></tr></table></td></tr>";
      }
   }//end if contracted to host sup test(s)
   if($hosting==0)
      $string.="<tr align=center><td>[You have no contracts at this time.]</td></tr>";
   $string.="</table>";
   return $string;
}
function GetMessages($session)
{
   $string="<table>";
   $level=GetLevel($session); 
   $school=GetSchool($session); $school2=addslashes($school);
   if($level==2)
   {
      $sql="SELECT * FROM messages WHERE (sportreg!='' OR school='All' OR school='All Schools' OR school='$school2') AND sport IS NULL AND poster='NSAA' AND CURDATE()<=end_date ORDER BY post_date DESC";
      $result=mysql_query($sql);
      $ct=0;
      while($row=mysql_fetch_array($result))
      {
	 if($row[sportreg]!='')
	 {
	    if(IsRegistered($school,$row[sportreg])) { if($ct==0) $latest=$row[title]; $ct++; }
         }
	 else { if($ct==0) $latest=$row[title]; $ct++; }
      } 
	  //if(PastDue("2018-02-12",-1)){
		  /*
	  if(time()>1518512400){
	  $string.="<tr align=left><td><a class=small href=\"../calculate/wildcard/fbschedules.php?session=$session\">Your 2018 & 2019 Football Schedules</a></td></tr>";
	  $string.="<tr align=left><td><a class=small href=\"../calculate/wildcard/fbschedules.php?session=$session&file=remaining\" target=\"_blank\"><div>2018 & 2019 Football Schedules</div></a></td></tr>"; 
      }else{
	  $string.="<tr align=left><td><a class=small href=\"#\">Your 2018 & 2019 Football Schedules</a></td></tr>";
	  $string.="<tr align=left><td><a class=small href=\"#\"><div>2018 & 2019 Football Schedules</div></a></td></tr>"; 
      }
	  
	  */
      $string.="<tr align=left><td><a class=small href=\"view_messages.php?session=$session\">You have $ct";
      if($ct==1) $string.=" message ";
      else $string.=" messages ";
      $string.="from the NSAA ";
      if($ct>0) $string.="(Latest post: $latest)";
      $sql="SELECT t1.* FROM messages AS t1,largeschools AS t2 WHERE ((t1.school=t2.schgroup AND t2.school='$school2' AND t1.sportreg!='') OR t1.school='$school2') AND t1.poster LIKE '%Public Schools'";
      $result=mysql_query($sql);
      $ct=0;
      while($row=mysql_fetch_array($result))
      {
	 if($row[sportreg]!='')
	 {
	    if(IsRegistered($school,$row[sportreg])) { if($ct==0) $latest=$row[title]; $ct++; }
	 }
	 else { if($ct==0) $latest=$row[title]; $ct++; }
      }
      if($ct==1)
         $string.="<br> and $ct message from $row[poster] (Latest post: $latest)";
      else if($ct>1)
         $string.="<br> and $ct messages from $row[poster] (Latest post: $latest)";
      $string.="</a></td></tr>";
      $string.="<tr align=left><td>";
      $string.="<a class=small href=\"post_message.php?session=$session\">Post New Message to Coach(es)</a></td></tr>";
      $string.="<tr align=left><td>";
      $string.="<a class=small href=\"edit_message.php?session=$session\">Edit/Delete an Existing Message</a></td></tr>";
      $string.="<tr align=left><td>";
      $string.="<a class=small href=\"contact.php?session=$session\">Contact the NSAA</a></td></tr>";
   }
   $string.="</table>";
   return $string;
}
function GetReminders($session)
{
   $level=GetLevel($session);
   $school=GetSchool($session);
   $string.="<table cellpadding=3 cellspacing=2>";
   $reimreminder=GetReimReminder($session);
   if($reimreminder!='')
      $string.="<tr align=left><td colspan=3><b>Reimbursement Forms Due Soon:</b></td><td>$reimreminder";
   $coopreminder=GetCoopReminder($session);
   if($coopreminder!='') 
      $string.="<tr align=left><td colspan=3><b>Cooperative Agreements Expiring Soon:</b></td></tr>$coopreminder";
   if(date('m')>=8 && date('m')<12)
      $string.="<tr align=left><th colspan=3 class=smaller>Don't forget to <a href=\"fb/view_fb_stats.php?session=$session\" class=small>Update Your Football Statistics!</a></th></tr>";
   if(time()<mktime(18,0,0,12,16,2009))
      $string.="<tr align=left><th colspan=3>*** The Football PRIORITY LIST Form IS NOW AVAILABLE under Activity Select --> Football. ***</th></td></tr>";
   $ballotreminder=GetBallotReminder($session);
   if($ballotreminder!="") 
      $string.="<tr align=left><td colspan=3><b>Ballots Due Soon:</b></td></tr>$ballotreminder";
   $hostreminder=GetHostReminder($school);
   if($hostreminder!="") 
      $string.="<tr align=left><td colspan=3><b>Hosting Contracts:</b></td></tr>$hostreminder";
   $rulesreminder=GetRulesReminder($school);
   if($rulesreminder!="") $string.=$rulesreminder;
   $suptestreminder=GetSupTestReminder($school);
   if($suptestreminder!='') $string.=$suptestreminder;
   $eligreminder=GetEligReminder($session);
   if($eligreminder!='') $string.="<tr align=left><td colspan=3><b>Eligibility Lists Due Soon:</b></td></tr>$eligreminder";
   $formsreminder=GetFormsReminder($session);
   if($formsreminder!="") $string.="<tr align=left><td colspan=3><b>Entry Forms Due Soon:</b></td></tr>$formsreminder";
   $string.="</table>";
   return $string;
}
function GetCoopReminder($session)
{
   //Does this school have any cooperative agreements that are about to expire?
   $schoolid=GetSchoolID($session);
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $sql="SHOW TABLES LIKE '%school'";
   $result=mysql_query($sql);
   $coops="";
   while($row=mysql_fetch_array($result))
   {
      $sport=preg_replace("/school/","",$row[0]);
      $table=$row[0];
      $sql2="SELECT * FROM $table WHERE (mainsch='$schoolid' OR othersch1='$schoolid' OR othersch2='$schoolid' OR othersch3='$schoolid') AND coopexpdate<=DATE_ADD(CURDATE(),INTERVAL 30 DAY) AND coopexpdate>=CURDATE()";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
	 $row2=mysql_fetch_array($result2);
	 $date=explode("-",$row2[coopexpdate]);
         $coops.="<tr align=left><td>".strtoupper(GetActivityName($sport))."</td><td bgcolor='#ff0000'><label style='color:#ffffff;'>Expires $date[1]/$date[2]/$date[0]</label></td><td>Please renew your cooperative agreement through the NSAA.</td></tr>";
      }
   }
   if($coops!='') $coops.="<tr><td colspan=3>&nbsp;</td></tr>"; 
   return $coops;
}
function GetHostReminder($school)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $school2=addslashes($school);
   //check if any host contracts need to be responded to
   $month=date("m");
   $hostinfo=""; 
   for($i=0;$i<count($hostsports);$i++)
   {
      if($month>=6 || GetSeason($hostsports[$i])!="Fall")
      {
	 $sportname=GetActivityName($hostsports[$i]);
         $districts=$hostsports[$i]."districts";
         $sql="SELECT id FROM $db_name.logins WHERE level='2' AND school='$school2'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $hostid=$row[0];
         $sql="SELECT * FROM $db_name2.$districts WHERE hostid='$hostid'";
         if($hostsports[$i]=='fb')
         {
            $districts="fbbrackets";
	    $sql2="SELECT t1.sid FROM fbschool AS t1, headers AS t2 WHERE t1.mainsch=t2.id AND t2.school='$school2'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
            $sql="SELECT * FROM $db_name2.$districts WHERE ((hostschool='$row2[0]' AND round!='Finals') OR (round='Finals' AND (sid1='$row2[0]' OR sid2='$row2[0]')))";
         }
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            if($row[post]=='y' && $row[accept]=='') //need to respond to contract
            {
               $hostinfo.="You have been selected to host a <b>";
               if($hostsports[$i]=='fb') $hostinfo.="Football Playoffs Game";
               else if($hostsports[$i]=='tr') $hostinfo.="$row[type] Track & Field Meet";
               else $hostinfo.="$row[type] $sportname Tournament";
               $hostinfo.="</b>. Please go to the <a class=small href=\"#\" onclick=\"javascript:Tree.toggle('menu2');\">District Host Information</a> section below.<br>";
            }
         }
      }//end if we're in the fall OR sport is not a fall sport
   }
   $sql="USE $db_name";
   $result=mysql_query($sql);
   if($hostinfo!='')
      return "<tr align=left><td colspan=3>$hostinfo</td></tr>";
   else return "";
}
function GetRulesReminder($school)
{
   $school2=addslashes($school);
   $rulesinfo='';
   //check if school is hosting any rules meetings  
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $sql="USE $db_name2";
   $result=mysql_query($sql);
   $sql="SHOW TABLES LIKE '%ruleshosts'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $temp=explode("ruleshosts",$row[0]);
      $cursp=$temp[0];
      $ruleshosts=$cursp."ruleshosts";
      $sql2="SELECT * FROM $db_name2.$ruleshosts WHERE hostname='$school2'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $mtgdate=$row2[mtgdate];
         $date=explode("-",$mtgdate);
         $mtgdatesec=mktime(0,0,0,$date[1],$date[2],$date[0]);
         $noshowdatesec=$mtgdatesec+(60*24*60*60);      //add 60 days
         $now=time();
         if($now<=$noshowdatesec)
         {
            if($row2[post]=='y' && $row2[accept]=='')   //need to respond to contract
            {
               $rulesinfo.="You have been selected to host a <b>".GetActivityName($cursp)." Rules Meeting</b>.  Please go to the <a class=small href=\"#\" onclick=\"javascript:Tree.toggle('menu11');\">Rules Meeting Host Information</a> section below.<br>";
            }
         }
      }
   }
   $sql="USE $db_name";
   $result=mysql_query($sql);
   return "<tr align=left><td>$rulesinfo</td></tr>";
}
function GetSupTestReminder($school)
{
   $suptestinfo=''; $school2=addslashes($school);
   //check if school is hosting any supervised tests
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $sql2="SELECT * FROM $db_name2.suptesthosts WHERE hostname='$school2'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $mtgdate=$row2[mtgdate];
      $date=explode("-",$mtgdate);
      $mtgdatesec=mktime(0,0,0,$date[1],$date[2],$date[0]);
      $noshowdatesec=$mtgdatesec+(60*24*60*60);      //add 60 days
      $now=time();
      if($now<=$noshowdatesec)
      {
         if($row2[post]=='y' && $row2[accept]=='')   //need to respond to contract
         {
            $suptestinfo.="You have been selected to host a Supervised Test</b>.  Please go to the <a class=small href=\"#\" onclick=\"javascript:Tree.toggle('menu10');\">Supervised Test Host Information</a> section below.<br>";
         }
      }
   }
   return "<tr align=left><td>$suptestinfo</td></tr>";
}
function GetHostHeader($session)
{
   return "<span style=\"color:blue\">".GetHostings($session,TRUE)."</span>"; //AS OF 1/19/15
}
function GetSupTestHeader($session)
{
   //check if school is hosting any supervised tests
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $school=GetSchool($session); $school2=addslashes($school);
   $supteststr="<font style=\"color:blue\">"; $month=date('m');
   $sql2="SELECT * FROM $db_name2.suptesthosts WHERE hostname='$school2'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $mtgdate=$row2[mtgdate];
      $date=explode("-",$mtgdate);
      $mtgdatesec=mktime(0,0,0,$date[1],$date[2],$date[0]);
      $noshowdatesec=$mtgdatesec+(60*24*60*60);      //add 60 days
      $now=time();
      if($now<=$noshowdatesec)
      {
         $supteststr="[You have at least 1 Supervised Test Host Contract.]"; $hosting=1;
      }
   }
   if($hosting==1)
      $supteststr="<font style=\"color:blue\"><b>$supteststr</b></font>";
   else
      $supteststr="<font style=\"font-size:8pt;font-weight:normal;\">[You have no contracts at this time.]</font>";
   return $supteststr;
}
function GetRulesHeader($session)
{
   //check if school is hosting any rules meetings
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   $school=GetSchool($session); $school2=addslashes($school);
   $rulesstr="<font style=\"color:blue\">"; $month=date('m');
   $sql="USE $db_name2";
   $result=mysql_query($sql);
   $sql="SHOW TABLES LIKE '%ruleshosts'";
   $result=mysql_query($sql);
   $hosting=0;
   while($row=mysql_fetch_array($result))
   {
      $temp=explode("ruleshosts",$row[0]);
      $cursp=$temp[0];
      $ruleshosts=$cursp."ruleshosts";
      $sql2="SELECT * FROM $db_name2.$ruleshosts WHERE hostname='$school2'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $mtgdate=$row2[mtgdate];
         $date=explode("-",$mtgdate);
         $mtgdatesec=mktime(0,0,0,$date[1],$date[2],$date[0]);
         $noshowdatesec=$mtgdatesec+(60*24*60*60);      //add 60 days
         $now=time();
         if($now<=$noshowdatesec)
         {
            if($row2[post]=='y' && !preg_match("/".GetActivityName($cursp)."/",$rulesstr))
	    {
               $rulesstr.=GetActivityName($cursp).", "; $hosting=1;
	    }
         }
      }
   }
   $sql="USE $db_name";
   $result=mysql_query($sql);
   if($hosting==1)
      $rulesstr=substr($rulesstr,0,strlen($rulesstr)-2)."</font>";
   else
      $rulesstr="<font style=\"font-size:8pt;font-weight:normal;\">[You have no contracts at this time.]</font>";
   return $rulesstr;
}
function GetBallotReminder($session)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   //check if any ballots are active - if so, show link for AD/Coach to vote
   $today=date("Y-m-d");
   $sql="SELECT * FROM $db_name2.vote_duedates WHERE startdate<='$today' AND enddate>='$today'";
   $result=mysql_query($sql);
   $ballots="";
   while($row=mysql_fetch_array($result))
   {
      $start=explode("-",$row[startdate]); $end=explode("-",$row[enddate]);
      if(IsRegistered(GetSchool($session),$row[sport])) 
         $ballots.="<a class=small href=\"officials/vote_".$row[sport].".php?session=$session\">".GetActivityName($row[sport])." Ballots</a> are available $start[1]/$start[2] to $end[1]/$end[2]<br>";
   }
   $string="";
   if($ballots!='')
      $string="<tr align=left><td>$ballots</td></tr>";
   return $string;
}
function GetReimReminder($session)
{
   //Get List of Sports for which Reimbursement Forms are "due soon"
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $level=GetLevel($session);
   if($level==3) return "";
   $schoolid=GetSchoolID($session);
   $school=GetSchool($session);
   $school2=addslashes($school);
   $sql="SELECT * FROM reim_duedates WHERE DATE_SUB(duedate,INTERVAL 14 DAY)<=CURDATE() AND duedate>=CURDATE() ORDER BY duedate ASC";
   if($school=="Test's School")
      $sql="SELECT * FROM reim_duedates ORDER BY duedate";
   $result=mysql_query($sql);
   $string="";
   while($row=mysql_fetch_array($result))
   {
      $sport=$row[sport]; $date=explode("-",$row[duedate]);
      if($sport=='cc' || $sport=='tr') $sport.="g";
      if(CanSubmitReimbursement($schoolid,$sport) || $school=="Test's School")
      {

	 $string.="<tr align=left><td><b>".GetActivityName($sport)."</b></td><td>due $date[1]/$date[2]/$date[0]</td>";
         $sql2="SELECT * FROM reimbursements WHERE sport='$sport' AND datesub>0 AND schoolid='$schoolid'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
	 if(mysql_num_rows($result2)==0) $string.="<td><a href=\"reimbursements.php?session=$session&sport=$sport\" class=\"small\">Submit reimbursement form</a></td></tr>";
	 else $string.="<td><i>You submitted this form on ".date("m/d/y",$row2[datesub]).".</i></td></tr>";
      }
      if($sport=='ccg' || $sport=='trg')	//NOW SHOW ccb and trb
      {
	 $sport=preg_replace("/g/","b",$sport);
         if(CanSubmitReimbursement($schoolid,$sport) || $school=="Test's School")
         {

            $string.="<tr align=left><td><b>".GetActivityName($sport)."</b></td><td>due $date[1]/$date[2]/$date[0]</td>";
            $sql2="SELECT * FROM reimbursements WHERE sport='$sport' AND datesub>0 AND schoolid='$schoolid'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            if(mysql_num_rows($result2)==0) $string.="<td><a href=\"reimbursements.php?session=$session&sport=$sport\" class=\"small\">Submit reimbursement form</a></td></tr>";
            else $string.="<td><i>You submitted this form on ".date("m/d/y",$row2[datesub]).".</i></td></tr>";
         }
      }
   }
   return $string;
}
function GetEligReminder($session)
{
   //get list of sports for which eligibility is "due soon"
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $level=GetLevel($session);
   if($level==3) $sport=GetActivity($session);
   $school=GetSchool($session);
   $school2=addslashes($school);
   $music=0; $string=""; $football=0;
   for($i=0;$i<count($act_long);$i++)
   {
      if($sport==$act_long[$i] || $level!=3)
      {
      $abbrev=GetActivityAbbrev2($act_long[$i]);
      $sportname=$act_long[$i];
      if($abbrev=="fb11") $sportname="Football (Class A/B/Week 0 Teams)";
      else if($abbrev=="fb68") $sportname="Football (Class D1/D2)";
      if((preg_match("/Music/",$sportname) && $music==0) || !preg_match("/Music/",$sportname))
      {
         //if Music AND VM or IM has not been passed in array yet OR non-Music activity, continue:
         if(preg_match("/Music/",$sportname))
         {
            $abbrev="mu"; $music=1; $sportname="Music";
         }
         $elig_due_date=GetEligDate($abbrev); $eligdate=explode("-",$elig_due_date);
	 if(!preg_match("/fb/",$abbrev) && GetCurrentSeason()==GetSeason($abbrev) && $eligdate[0]==date("Y") && !PastDue($elig_due_date,2))
         {
            $date=explode("-",$elig_due_date);
            $sql="SELECT * FROM eligibility WHERE school='$school2' AND $abbrev='x'";
            if($abbrev=="mu")
               $sql="SELECT * FROM eligibility WHERE school='$school2' AND (vm='x' OR im='x')";
            else if(preg_match("/_b/",$abbrev))
            {
               $abbrev=substr($abbrev,0,strlen($abbrev)-2);
	       $sql="SELECT * FROM eligibility WHERE school='$school2' AND $abbrev='x'";
               $sql.=" AND gender='M'";
            }
            else if(preg_match("/_g/",$abbrev))
            {
               $abbrev=substr($abbrev,0,strlen($abbrev)-2);
               $sql="SELECT * FROM eligibility WHERE school='$school2' AND $abbrev='x'";
               $sql.=" AND gender='F'";
            }
            $result=mysql_query($sql);
            $ct=mysql_num_rows($result);   
	    $string.="<tr align=left><td>$sportname</td><td>$date[1]/$date[2]/$date[0]</td><td>You have $ct participants listed.</td></tr>";
 	 }
	 else if($abbrev=='fb68' && GetCurrentSeason()=="Fall" && $eligdate[0]==date("Y") && !PastDue($elig_due_date,2))
	 {
            $elig_due_date=GetEligDate('fb11');
            $date=explode("-",$elig_due_date);
	    $string.="<tr align=left><td colspan=3>FOOTBALL:</td></tr>";
	    $string.="<tr align=left valign=top><td colspan=2>Football (Class A/B/Week 0 Teams) - due $date[1]/$date[2]/$date[0]<br>";
            $elig_due_date=GetEligDate('fb68');
            $date=explode("-",$elig_due_date);
	    $string.="Football (Class C1/C2/D1/D2) - due $date[1]/$date[2]/$date[0]</td>";
            $sql="SELECT * FROM eligibility WHERE school='$school2' AND fb11='x'";
            $result=mysql_query($sql);
            $ct=mysql_num_rows($result);
	    $string.="<td>Football 11 - You have $ct participants listed.<br>";
            $sql="SELECT * FROM eligibility WHERE school='$school2' AND fb68='x'";
            $result=mysql_query($sql);
            $ct=mysql_num_rows($result);
            $string.="Football 6/8 - You have $ct participants listed.</td></tr>";
	    $football=1;
         }	
	 //else $string.="<tr align=left><td>$abbrev</td><td>".GetSeason($abbrev)."</td><td></td></tr>";
      }//end if Music and music=0 OR non-Music
      }//end if not coach or this is the coach's activity
   }
   return $string;
}
function GetFormsReminder($session)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $level=GetLevel($session);
   if($level==3) $sport=GetActivity($session);
   $school=GetSchool($session);
   $school2=addslashes($school);

   $music=0; $string="";
   for($i=0;$i<count($act_long);$i++)
   {
      if($sport==$act_long[$i] || $level==2)
      {   
         $abbrev=GetActivityAbbrev2($act_long[$i]);
         $sportname=$act_long[$i];
         if((preg_match("/Music/",$sportname) && $music==0) || !preg_match("/Music/",$sportname))
         {
            //if Music AND VM or IM has not been passed in array yet OR non-Music activity, continue:
            if(preg_match("/Music/",$sportname))
            {
               $abbrev="mu"; $music=1; $sportname="Music";
            }
            $form_due_date=GetDueDate($abbrev);
	    if(preg_match("/Tennis/",$act_long[$i]))
            {
	       $class=GetClass(GetSID2($school,$abbrev,date("Y")),$abbrev,date("Y"));
	       if($class=="A") { $formtype="State"; $form_due_date=GetDueDate($abbrev."state");}
	       else $formtype="District";
	    }
	    else $formtype="District";
            if(DueSoon($form_due_date) && !PastDue($form_due_date,0) && (IsRegistered($school, $abbrev) || $school=="Test's School") && $abbrev!="sp" && $abbrev!="pp")
            {
               $date=explode("-",$form_due_date);
               $form=$sportname;
               if($abbrev=="fb11") $sportname="Football (Class A/B/Week 0 Teams)";
               else if($abbrev=="fb68") $sportname="Football (Class C1/C2/D1/D2)";
               $string.="<tr valign=top align=left><td><a class=small href=\"forms.php?activity_ch=$form&session=$session\">$sportname";
	       if(preg_match("/te/",$abbrev)) $string.=" (State)";
	       else $string.=" (Districts)";
	       $string.="</a></td>";
               if(preg_match("/fb/",$abbrev))
                  $string.="<td>Due by 10 am the day after your team qualifies for State Semifinals</td>";
	       if(preg_match("/te/",$abbrev))
	       {
		  $date=explode("-",GetDueDate($abbrev."state"));
		  $string.="<td>$date[1]/$date[2]/$date[0]</td>";
	       }
               else
                  $string.="<td>$date[1]/$date[2]/$date[0]</td>";
               if($abbrev=="pp") $abbrev.="_students";
               if($abbrev=="de")      //Debate
                  $sql="SELECT * FROM $abbrev WHERE school='$school2'";
               else if($abbrev=="pp_students")     //Play Production
                  $sql="SELECT id FROM $abbrev WHERE (school='$school2' OR co_op='$school2')";
               else if(preg_match("/tr_/",$abbrev))        //Track
                  $sql="SELECT * FROM $abbrev WHERE school='$school2' AND student_id!='0'";
	       else if(preg_match("/fb/",$abbrev)) 	//Football
	          $sql="SELECT t1.* FROM fb_state AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
               else if(preg_match("/te/",$abbrev))        //Tennis $class=GetClass($sid,$sport,$year)
                  $sql="SELECT * FROM ".$abbrev."state WHERE sid='".GetSID2($school2,$abbrev)."'";
	       else if(preg_match("/te/",$abbrev))
	          $sql="SELECT * FROM $abbrev WHERE sid='".GetSID2($school2,$abbrev)."'";
	       else
		  $sql="SELECT id FROM $abbrev WHERE (school='$school2' OR co_op='$school2') AND checked='y'";	
               $result=mysql_query($sql);
               $ct=mysql_num_rows($result);
               if(preg_match("/te/",$abbrev))
               {
                  while($row=mysql_fetch_array($result))
                  {
                     if(preg_match("/doubles/",$row[division]))   //if type is doubles
                        $ct++;     //count partner as well
                  }
               }
               else if($abbrev=="de" && $ct!=0)
               {
                  if($row[2]!="0" && $row[6]!="0")
                     $ct=2;
                  else if($row[2]!="0" || $row[6]!="0")
                     $ct=1;
                  else
                     $ct=0;
               }
	       if(preg_match("/te_/",$abbrev))
	       {
		  $table=$abbrev."state";
	          //else $table=$abbrev;
		  $sql2="SELECT t1.* FROM $table AS t1, ".$abbrev."school AS t2, headers AS t3 WHERE t1.sid=t2.sid AND t2.mainsch=t3.id AND t3.school='$school2' AND t1.player1>0 ORDER BY t1.division";
	   	  $result2=mysql_query($sql2);
	          if($level==2) $string.="<td width='300px'>Your coach has entered ";
	          else $string.="<td width='300px'>You have entered ";
		  while($row2=mysql_fetch_array($result2))
	   	  {
		     if($row2[division]=="singles1") $string.="#1 Singles, ";
		     else if($row2[division]=="singles2") $string.="#2 Singles, ";
	             else if($row2[division]=="doubles1") $string.="#1 Doubles, ";
	 	     else if($row2[division]=="doubles2") $string.="#2 Doubles, ";
		     else if($row2[division]=="substitute") $string.="Substitutes, ";
	          }
	         
		  if(mysql_num_rows($result2)==0)
		     $string.=" NO players on the $formtype entry form yet.</td>";
	          else
		     $string=substr($string,0,strlen($string)-2)." on the $formtype entry form.</td>";
	       }
               else if($abbrev!="mu" && $abbrev!="im" && $abbrev!="vm")
	       {
		  if($level==2)
                     $string.="<td>Your coach has entered $ct students on the roster</td>";
		  else
		     $string.="<td>You have entered $ct students on the roster</td>";
	       }
               $string.="</tr>";
	    }
         }
      }
   }
   if($level==2)
   {
      //see if directory is due soon
      $sql="SELECT duedate FROM misc_duedates WHERE sport='Directory'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(DueSoon($row[0]) && !PastDue($row[0],1))
      {
         $temp=explode("-",$row[0]);
	 $string.="<tr align=left><td><a class=small href=\"directory.php?session=$session\">School Directory</a></td><td colspan=2>$temp[1]/$temp[2]/$temp[3]</tr>";
      }   
      //see if FB priority list is open
      $sql="SELECT duedate,showdate FROM misc_duedates WHERE sport='priority'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $temp=explode(";",GetFBYears());
      $year1=$temp[0];
      $year2=$temp[1];
      $schooltbl=GetSchoolsTable('fb',$year1,$year2);
      if(PastDue($row['showdate'],-1) && !PastDue($row['duedate'],3))
      {
	 $sql2="SELECT t1.* FROM fbpriority AS t1, headers AS t2, $schooltbl AS t3 WHERE t1.sid=t3.sid AND t2.id=t3.mainsch AND t2.school='$school2' AND t1.datesub!=''";
	 $result2=mysql_query($sql2);
	 if(mysql_num_rows($result2)==0)	//not submitted yet
	 {
	    $temp=explode("-",$row[0]);
	    $string="<tr align=left><td><a class=small href=\"fb/prioritymemo.php?session=$session\">$year1-$year2 Football Priority List</a></td><td colspan=2>$temp[1]/$temp[2]/$temp[0]</td></tr>";
         }
      }
   }//end if AD
   if($level==2 && $string!='') $string="<tr align=left><td colspan=2><b>The following forms are due soon:</b></td></tr>".$string;
   return $string;
}
function GetTeamTable($sport)
{
   $sql="SHOW TABLES LIKE '".$sport."school'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) 
   {
      $row=mysql_fetch_array($result); return $row[0];
   }
   else if(strlen($sport)==3)
   {
      $sport=substr($sport,0,2);	//remove gender specification, b or g
      $sql="SHOW TABLES LIKE '".$sport."school'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) 
      {
         $row=mysql_fetch_array($result); return $row[0];
      }
   } 
   else if(strlen($sport)==2)
   {
      $sport=$sport."b";        //add boys gender specification, b
      $sql="SHOW TABLES LIKE '".$sport."school'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $row=mysql_fetch_array($result); return $row[0];
      }
   }
   else return false;
}
function ASAIsUnlocked($schoolid,$season,$year="")
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $database=GetDatabase($year);
   $sql="SELECT * FROM $database.allstatenomlocks WHERE schoolid='$schoolid' AND season='$season'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) return TRUE;
   else return FALSE;
}
function GetRegistrationAmount($schoolid,$requestedamount="totalfee",$season="")
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';

   $sql="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' LIMIT 1";
   $result=mysql_query($sql);
   $sql2="SELECT * FROM schoolmembership WHERE schoolid='$schoolid' LIMIT 1";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result)==0 && mysql_num_rows($result2)==0) return FALSE;
   $row=mysql_fetch_array($result);
   $row2=mysql_fetch_array($result2);
   $totalfee=$row[totalfee];    //TOTAL FEE DUE
   if(mysql_num_rows($result2)>0 && mysql_num_rows($result)==0) { $totalfee+=40; }
   $latefee=$row[latefee];
   $overridelatefee=$row[overridelatefee];
   $overrideamtpaid=$row[overrideamtpaid];
   $amtpaid=$row[amtpaid];
   if(mysql_num_rows($result2)>0)
      $amountpaid=$row2[amtpaid];
   else
      $amountpaid=0;       //THIS WILL BE CALCULATED - AMOUNT PAID SO FAR
   $fallamountpaid=0; $winteramountpaid=0; $springamountpaid=0;
   $fallfee=0; $winterfee=0; $springfee=0;
   for($i=0;$i<count($regacts);$i++)
   {
      $sql="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
      $result=mysql_query($sql);
      $curamount=0;
      if($row=mysql_fetch_array($result))
      {
         if($row[participate]=='x')
         {
            if($regactseasons[$i]=="Fall") $fallfee+=60;
            else if($regactseasons[$i]=="Winter") $winterfee+=60;
            else $springfee+=60;
         }
         if($row[ccfee]=='x' && $row[sport]=='cc_g')
         {
            if($regactseasons[$i]=="Fall") $fallfee+=20;
            else if($regactseasons[$i]=="Winter") $winterfee+=20;
            else $springfee+=20;
         }
         if($row[wrfee]=='x')
         {
            if($regactseasons[$i]=="Fall") $fallfee+=30;
            else if($regactseasons[$i]=="Winter") $winterfee+=30;
            else $springfee+=30;
         }
         if($row[datepaid]!='0000-00-00' && PastDue($row[datepaid],-1)) $curamount+=60;
         if($row[sport]=='cc_g' && $row[ccfeedatepaid]!='0000-00-00' && PastDue($row[ccfeedatepaid],-1)) $curamount+=20;
         if($row[wrfeedatepaid]!='0000-00-00' && PastDue($row[wrfeedatepaid],-1)) $curamount+=30;
         $amountpaid+=$curamount;
         if($regactseasons[$i]=="Fall") $fallamountpaid+=$curamount;
         else if($regactseasons[$i]=="Winter") $winteramountpaid+=$curamount;
         else $springamountpaid+=$curamount;
      }
   }
   $autolatefee=0;
   if(($fallfee-$fallamountpaid)>0 && PastDue(GetMiscDueDate("registration_fall"),0)) $autolatefee+=50;
   if(($winterfee-$winteramountpaid)>0 && PastDue(GetMiscDueDate("registration_winter"),0)) $autolatefee+=50;
   if(($springfee-$springamountpaid)>0 && PastDue(GetMiscDueDate("registration_spring"),0)) $autolatefee+=50;
   if($overridelatefee!='x') $latefee=$autolatefee;
   if($requestedamount=="late")
      return $latefee;
   else if($requestedamount=="paid")
   {
      if($season=="" && $overrideamtpaid!='x') return $amountpaid;
      else if($season=="") return $amtpaid;
      else if($season=="Fall") return $fallamountpaid;
      else if($season=="Winter") return $winteramountpaid;
      else if($season=="Spring") return $springamountpaid;
      else return FALSE;
   }
   else if($requestedamount=="totalfee")
   {
      if($season=="") return $totalfee;
      else if($season=="Fall") return $fallfee;
      else if($season=="Winter") return $winterfee;
      else if($season=="Spring") return $springfee;
      else return FALSE;
   }
   else if($requestedamount=="due")
   {
      if($season=="" && $overrideamtpaid=='x') $amountpaid=$amtpaid;
      if($season=="") return number_format(($totalfee+$latefee-$amountpaid),2,'.','');
      else if($season=="Fall") return number_format(($fallfee-$fallamountpaid),2,'.','');
      else if($season=="Winter") return number_format(($winterfee-$winteramountpaid),2,'.','');
      else if($season=="Spring") return number_format(($springfee-$springamountpaid),2,'.','');
      else return FALSE;
   }
   else return FALSE;
}
function GetRegistrationSeason($sport)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
   for($i=0;$i<count($regacts);$i++)
   {
      if($regacts[$i]==$sport) return $regactseasons[$i];
   }
}
function GetColors($schoolid,$sport)
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $sql="SELECT * FROM headers WHERE id='$schoolid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $colors=$row[color_names];	//DEFAULT COLORS FOR SCHOOL
   $sid=GetSID2(GetSchool2($schoolid),$sport);
   $sql="SELECT * FROM ".$sport."school WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 || mysql_error()) return $colors;
   if($row[colors]!='') $colors=$row[colors];	//COLORS SPECIFIC TO THIS SPORT/CO-OP
   return $colors;
}
function GetMascot($schoolid,$sport)
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $sql="SELECT * FROM headers WHERE id='$schoolid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $mascot=$row[mascot];   //DEFAULT MASCOT FOR SCHOOL
   $sid=GetSID2(GetSchool2($schoolid),$sport);
   $sql="SELECT * FROM ".$sport."school WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 || mysql_error()) return $mascot;
   if($row[mascot]!='') $mascot=$row[mascot];	//MASCOT SPECIFIC TO THIS SPORT/CO-OP
   return $mascot;
}
function GetCoaches($schoolid,$sport,$sid=0)
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   if($sid>0)
   {
      $sql="SELECT mainsch FROM ".GetSchoolTable($sport)." WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $schoolid=$row[0];
   }
   else
      $sid=GetSID2(GetSchool2($schoolid),$sport);
   $school2=addslashes(GetSchool2($schoolid));
   $sql="SELECT name FROM logins WHERE school='$school2' AND level='3' AND sport LIKE '".GetActivityName($sport)."%'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $coach=$row[0];
   if($sid==0) $sid=GetSID2(GetSchool2($schoolid),$sport);
   $sql="SELECT * FROM ".GetSchoolTable($sport)." WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 || mysql_error()) return $coach;
   if($row[coach]!='') $coach=$row[coach];   //COACH SPECIFIC TO THIS SPORT/CO-OP
   return $coach;
}
function GetCoachCell($schoolid=0,$sport,$sid=0)
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   if($sid>0)
   {
      $sql="SELECT mainsch FROM ".GetSchoolTable($sport)." WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $schoolid=$row[0];
   }
   else
      $sid=GetSID2(GetSchool2($schoolid),$sport);
   $school2=addslashes(GetSchool2($schoolid));
   $sql="SELECT * FROM ".GetSchoolTable($sport)." WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[directorcell];
}
function GetAsstCoaches($schoolid,$sport)
{
   $school2=addslashes(GetSchool2($schoolid));
   $sql="SELECT name,asst_coaches FROM logins WHERE level='3' AND sport LIKE '".GetActivityName($sport)."' AND school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[asst_coaches];
}
function GetUrgentAlerts($session)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';

   $level=GetLevel($session);

   if($level==1)
   {
   	/*** RETURN HTML FOR URGENT ALERTS FOR NSAA ADMIN USER ***/
   $html="";
   //COOP AGREEMENT FORMS NEEDING ACTION - get any coopapps awaiting NSAA approval
   $sql="SELECT * FROM coopapp WHERE datesubtoNSAA>0 && execdate=0 ORDER BY datesubtoNSAA DESC";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $html.="<tr align=center><td>";
        $html.="<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;margin:5px;\">";
        $html.="<tr align=\"center\"><td colspan=\"4\" style=\"font-size:12px;color: red;\"><b>Cooperative Sponsorship Agreement Forms requiring Action</b><br><a href=\"coopappadmin.php?session=$session\">Go to Coop Agreements Main Menu</a></td></tr>";
        $html.="<tr align=center><td><b>Date<br>Submitted</b></td><td><b>Schools</b><br>(Click for Form)</td>";
        $html.="<td><b>Activities</b></td>";
        $html.="<td><b>Delete</b></td></tr>";
        while($row=mysql_fetch_array($result))
        {
             $html.="<tr align=left><td>".date("m/d/y",$row[datesubtoNSAA])."</td>";
	     $html.="<td><a class=small target=\"_blank\" href=\"coopapp.php?session=$session&coopappid=$row[id]\">".GetSchool2($row[schoolid1]);
    	     for($i=2;$i<=4;$i++)
	     {
	        $schvar="schoolid".$i;
	        if($row[$schvar]>0) $html.=", ".GetSchool2($row[$schvar]);
	     }
	     $html.="</a></td>";
	     $actstr="";
	     for($i=0;$i<count($coopsports);$i++)
	     {
	        if($row[$coopsports[$i]]=='x') $actstr.=strtoupper($coopsports2[$i]).", ";
    	     }
	     $actstr=substr($actstr,0,strlen($actstr)-2);
	     $html.="<td>$actstr</td>";
             $html.="<td><a class=small href=\"coopappadmin.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this form?  The information cannot be recovered once you do so.');\">Delete</a></td></tr>";
      	}
        $html.="</table></td></tr>";
   }
   //HARDSHIP REQUEST FORMS NEEDING ACTION:
   //get any hardship request forms awaiting NSAA approval
   $hardship_sql="SELECT * FROM hardship WHERE datesub!='' && execsignature='' ORDER BY datesub DESC";
   $hardship_result=mysql_query($hardship_sql);
   if(mysql_num_rows($hardship_result)>0)
   {
        $html.="<tr align=\"center\"><td>";
        $html.="<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;margin:5px;\">";
        $html.="<tr align=\"center\"><td colspan=\"4\" style=\"font-size:12px;color: red;\"><b>Hardship Request Forms requiring Action</b><br><a href=\"hardshipadmin.php?session=$session\">Go to Hardship Request Main Menu</a></td></tr>";
        $html.="<tr align=center><td><b>Date<br>Submitted</b></td><td><b>School</b></td>";
        $html.="<td><b>Student</b><br>(Click for Form)</td>";
        $html.="<td><b>Delete</b></td></tr>";
        while($row=mysql_fetch_array($hardship_result))
        {
             $html.="<tr align=left><td>".date("m/d/y",$row[datesub])."</td>";
             $html.="<td>$row[school]</td>";
             $sql2="SELECT first,last FROM eligibility WHERE id='$row[studentid]'";
             $result2=mysql_query($sql2);
             $row2=mysql_fetch_array($result2);             
	     if(mysql_num_rows($result2)==0) $name="[No Name found for Student ID# $row[studentid]]";
	     else $name="$row2[first] $row2[last]";
	     $html.="<td><a class=small target=new href=\"hardship.php?session=$session&id=$row[id]&header=no\">$name</a></td>";
	     $html.="<td><a class=small href=\"hardshipadmin.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this form?  The information cannot be recovered once you do so.');\">Delete</a></td></tr>";
        }
        $html.="</table>";
        $html.="</td></tr>";
   }
   //FOREIGN EXCHANGE FORMS NEEDING ACTION:
   $sql2="SELECT * FROM forexsettings";
   $result2=mysql_query($sql2);
   $form=mysql_fetch_array($result2);
   $sql="SELECT * FROM forex WHERE datesub>0 AND execdate=0 ORDER BY datesub DESC";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
        $html.="<tr align=\"center\"><td>";
        $html.="<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;margin:5px;\">";
        $html.="<tr align=\"center\"><td colspan=\"4\" style=\"font-size:12px;color: red;\"><b>".$form[formnickname]."s requiring Action</b><br><a class=small href=\"forexadmin.php?session=$session\">Go to ".$form[formnickname]." Main Menu</a></td></tr>";
        $html.="<tr align=center><td><b>Date<br>Submitted</b></td><td><b>School</b></td>";
        $html.="<td><b>Student</b><br>(Click for Form)</td>";
        $html.="<td><b>Country</b></td></tr>";
        while($row=mysql_fetch_array($result))
	{
   	   $html.="<tr align=left><td>".date("m/d/y",$row[datesub])."</td>";
   	   $html.="<td>$row[school]</td>";
   	   $sql2="SELECT first,last FROM eligibility WHERE id='$row[studentid]'";
   	   $result2=mysql_query($sql2);
   	   $row2=mysql_fetch_array($result2);
   	   $html.="<td><a class=small target=new href=\"forex.php?session=$session&id=$row[id]&header=no\">$row2[first] $row2[last]</a></td>";
           $html.="<td>$row[country]</td></tr>";
	}
	$html.="</table>";
	$html.="</td></tr>";
   }
   }	//END LEVEL 1
   else if($level==2)		//AD USER
   {
      $html="";
      $schoolid=GetSchoolID($session);
      $school=GetSchool($session); $school2=addslashes($school);

      //GET ANY INDIVIDUAL SCHOOL FORMS FOR COOP APPS THEY NEED TO TEND TO
      $sql="SELECT * FROM coopapp WHERE (schoolid1='$schoolid' OR schoolid2='$schoolid' OR schoolid3='$schoolid' OR schoolid4='$schoolid') AND datesubtoschools>0 AND datesubtoNSAA=0 ORDER BY datesubtoschools DESC";
      $result=mysql_query($sql);
      $subhtml="";
      while($row=mysql_fetch_array($result))
      {
	 //Check to see if this school has submitted their portion of the form yet
         $sql2="SELECT * FROM coopschoolapp WHERE coopappid='$row[id]' AND schoolid='$schoolid' AND datesub>0";
	 $result2=mysql_query($sql2);
	 if(mysql_num_rows($result2)==0)	//THEIR PORTION HASN'T BEEN SUBMITTED
	 {
	    $subhtml.="<tr align=left><td>".GetSchool2($row[schoolid1])." (Head School)<br>";
            if($row[schoolid2]>0) $subhtml.=GetSchool2($row[schoolid2])."<br>";
            if($row[schoolid3]>0) $subhtml.=GetSchool2($row[schoolid3])."<br>";
            if($row[schoolid4]>0) $subhtml.=GetSchool2($row[schoolid4])."<br>"; 
    	    $subhtml.="</td><td>";
	    $actstr="";
	    for($i=0;$i<count($coopsports);$i++)
	    {
	       if($row[$coopsports[$i]]=='x') $actstr.=strtoupper($coopsports2[$i]).", ";
	    }
  	    $actstr=substr($actstr,0,strlen($actstr)-2);
	    $subhtml.=$actstr."</td>";
	    $subhtml.="<td><a class='small' href=\"coopapp.php?session=$session&coopappid=$row[id]\" target=\"_blank\">View the Main Sponsorship Agreement Form</a>";
	    if($schoolid!=$row[schoolid1]) $subhtml.="<br>(Filled out by ".GetSchool2($row[schoolid1]).")";
	    $subhtml.="<br><br><a style=\"background-color:yellow;\" href=\"coopschoolapp.php?session=$session&coopappid=$row[id]&schoolid=$schoolid\">Complete your School's Resolution Form</a></td>";
	    $subhtml.="</tr>";
	 }

         //NOW CHECK IF THIS SCHOOL IS A HEAD SCHOOL AND THE MAIN COOP APP IS READY TO SUBMIT TO THE NSAA
	 if($schoolid==$row[schoolid1])
	 {
	    $readytosubmit=1;
	    for($i=1;$i<=4;$i++)
	    {
	       $schvar="schoolid".$i;
	       $curschid=$row[$schvar];
	       $sql2="SELECT * FROM coopschoolapp WHERE coopappid='$row[id]' AND schoolid='$curschid' AND datesub>0";
	       $result2=mysql_query($sql2);
	       if($curschid>0 && mysql_num_rows($result2)==0)	//MISSING COMPLETED FORM FOR THIS SCHOOL
	          $readytosubmit=0;
	    }
	    if($readytosubmit==1)
	    {
               $subhtml.="<tr align=left><td>".GetSchool2($row[schoolid1])." (Head School)<br>";
               if($row[schoolid2]>0) $subhtml.=GetSchool2($row[schoolid2])."<br>";
               if($row[schoolid3]>0) $subhtml.=GetSchool2($row[schoolid3])."<br>";
               if($row[schoolid4]>0) $subhtml.=GetSchool2($row[schoolid4])."<br>";
               $subhtml.="</td><td>";
               $actstr="";
               for($i=0;$i<count($coopsports);$i++)
               {
                  if($row[$coopsports[$i]]=='x') $actstr.=strtoupper($coopsports2[$i]).", ";
               }
               $actstr=substr($actstr,0,strlen($actstr)-2);
               $subhtml.=$actstr."</td>";
               $subhtml.="<td bgcolor='yellow'><b>The co-oping schools have completed their portion of this form!</b><br><br><a href=\"coopapp.php?session=$session&coopappid=$row[id]\">Review & Submit this Sponsorship Agreement Form to the NSAA</a>";
               $subhtml.="</td></tr>";
	    }
	 }
      }

      if($subhtml!='')
	 $html.="<table cellspacing=0 cellpadding=3 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><tr align=center><td colspan=3 style=\"color:red\"><b>Cooperative Sponsorship Agreement Forms:</b><br><a href=\"coopappindex.php?session=$session\">Go to Coop Sponsorship Agreements Main Menu</a></td></tr><tr align=center><td><b>Co-oping Schools</b></td><td><b>Activities</b></td><td><b>Forms</b></td></tr>".$subhtml."</table><br>";

      //Get any hardship request forms where NSAA has taken action in the last day
      $hardship_sql="SELECT * FROM hardship WHERE DATE_SUB( CURDATE() ,INTERVAL 5 DAY)<= FROM_UNIXTIME( execdate ) AND school='$school2'";
      $hardship_result=mysql_query($hardship_sql);
      if(mysql_num_rows($hardship_result)>0)
      {
          $html.="<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
          $html.="<caption class=small style=\"color: red;\"><b>Hardship Request Forms acted on recently by NSAA:</b><br><br></caption>";
          $html.="<tr align=center><td><b>Submitted</b></td><td><b>Student</b><br>(Click for form)</td><td colspan=\"2\"><b>Action taken by<br>Executive Director</b></td></tr>";
          while($row=mysql_fetch_array($hardship_result))
          {
              $html.="<tr align=left><td>";
              if($row[datesub]=='') $html.="NO";
              else $html.=date("m/d/Y",$row[datesub]);
              $html.="</td><td><a class=small ";
              if($row[datesub]!='') $html.="target=\"_blank\" ";
              $html.="href=\"hardship.php?session=$session";
              if($row[datesub]!='') $html.="&header=no";
              $html.="&id=$row[id]\">";
              $sql2="SELECT first,last FROM eligibility WHERE id='$row[studentid]'";
              $result2=mysql_query($sql2);
              $row2=mysql_fetch_array($result2);
              $html.="$row2[first] $row2[last]</a></td>";
              if($row[execsignature]=='')
                 $html.="<td colspan=\"2\">NO</td>";
              else 
	      {
                 $action = ($row['eligible'] == 'y') ? "eligible" : "not eligible";
                 $html.="<td>".date("m/d/Y",$row[execdate])."</td>";
                 $html.="<td>".$action."</td>";
              }
              $html.="</tr>";
           }
           $html.="</table>";
       }
   }
   return $html;
}
function GetGolfSeasonReportDash($sport,$session,$sid=0)
{
     if(date("m")>=6) $yearch=date("Y");
    else $yearch=date("Y")-1;
    $schedules=GetSchedule($sid,($sport=="gog"?"go_g":"go_b"),$yearch);
    for ($i=0;$i<count($schedules['tournid']);$i++){
        $tourn_id=$schedules['tournid'][$i];
        $opp_id=$schedules['oppid'][$i];
        $sid_id=$schedules['sid'][$i];
        $score=explode("-",$schedules['score'][$i]);
        $sidscore=$score[0];
        $oppscore=$score[1];
        $tournteam=$sport."tournteam";
        $sql="SELECT * FROM $tournteam WHERE tournid=$tourn_id AND sid=$sid_id";

        $result=mysql_query($sql);
        if (mysql_num_rows($result)==0 && $sid!=0){
            $sql="INSERT INTO $tournteam(tournid, sid, score) VALUES ('$tourn_id','$sid_id','$sidscore')";
            mysql_query($sql);
        }

        $sql="SELECT * FROM $tournteam WHERE tournid=$tourn_id AND sid=$opp_id";
        $result=mysql_query($sql);
        if (mysql_num_rows($result)==0 && $opp_id!=0){
            $sql="INSERT INTO $tournteam(tournid, sid, score) VALUES ('$tourn_id','$opp_id','$oppscore')";
            mysql_query($sql);
        }
    }

    require_once($_SERVER['DOCUMENT_ROOT']."/calculate/functions.php");
    if(!$sid) $sid=GetSID($session,$sport);
    //For the top of go/resultsmain.php (AD or Coach Login)
    //Show Golf Tournaments & Results entered; link to enter new tournament
    $sportname=GetActivityName($sport);
    $html="<table class='nine' cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\"><caption><h2>$sportname Tournament Schedule & Results:</h2></caption>";
    $abbrev=preg_replace("/_/","",$sport);
    $tourntbl=($sport=="gog"?"go_g":"go_b")."tourn";
    $teamtbl=$sport."tournteam";
    $indytbl=$tourntbl."indy";
    $schooltbl=GetSchoolTable($abbrev);
    $schoolid=GetSchoolID($session);
    //SHOW TOURNAMENTS ENTERED & INDICATE IF RESULTS HAVE BEEN SUBMITTED
    $sql="SELECT t2.*,t1.score FROM $teamtbl AS t1, $tourntbl AS t2 WHERE t1.tournid=t2.tid AND t1.sid='$sid' ORDER BY t2.received ASC";
    $result=mysql_query($sql);
    if(mysql_num_rows($result)>0)
        $html.="<tr align=\"center\"><td><b>Tournament</b></td><td><b>Date</b></td><td><b>Results</b></td></tr>";
    while($row=mysql_fetch_array($result))
    {
        $date=explode("-",$row[received]);
        $html.="<tr align=\"left\"><td>$row[name]</td><td>$date[1]/$date[2]/$date[0]</td><td>";
        if($row[unlockreport]=='x') 	//Results have been unlocked by the NSAA
            $html.="<p><i>This tournament's report has been <b>UNLOCKED</b> by the NSAA for you to correct.</i></p><a class=small href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[tid]\">Correct the Report &rarr;</a>";
        else if($row[datesub]>0)	//Results are submitted
            $html.="<p><i>Report submitted by ".GetSchool2($row['schoolid'])." on ".date("m/d/y",$row['datesub'])." at ".date("g:ia",$row['datesub']).".</i></p><a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[tid]&sid=$sid\">View Report &rarr;</a>";
        else if($schoolid!=$row['schoolid'])	//This school is not the host
            $html.="<a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[tid]\">View Tournament &rarr;</a><p><b>".GetSchool2($row['schoolid'])."</b> will need to submit the results for this tournament.</p>";
        else	//Report not submitted yet
            $html.="<a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[tid]\">Enter Tournament Report &rarr;</a>";
        if($row['score']==0)	//If they still have no score, they can remove themselves from tournament
            $html.="<p style=\"text-align:right;\"><a href=\"/nsaaforms/go/resultsmain.php?session=$session&sport=$abbrev&removetournid=$row[tid]\" onClick=\"return confirm('Are you sure you want to remove this tournament from your schedule?');\" class=\"small\"><i>Remove this tournament from your schedule</i></a></p>";
        $html.="</td></tr>";
    }
    $html.="</table>";
    if(mysql_num_rows($result)==0)
        $html.="<p><i>No tournaments have been entered on your schedule yet.</i></p>";
    //If not past due date, can enter more tournaments on their schedule:
    $duedate=GetScheduleDueDate($abbrev);
    if(!PastDue($duedate,0)){}
//      $html.="<br><p><a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev\">Enter a Regular Season Tournament &rarr;</a></p>";
    else
        $html.="<br><p><i>Regular season tournament entry was locked at midnight on ".date("F j, Y",strtotime($duedate)).". You can no longer enter tournaments on your schedule.</i></p>";

    return $html;
}
function GetGolfSeasonReportDash1($sport,$session,$sid=0,$school)
{
   //require_once("D:\xampp\htdocs/calculate/functions.php");
   //require_once("../calculate/functions.php");
   if(!$sid) $sid=GetSID($session,$sport);
   //For the top of go/resultsmain.php (AD or Coach Login)
   //Show Golf Tournaments & Results entered; link to enter new tournament
  // print_r($sport); exit;
   $sportname=GetActivityName($sport);
   $html="<table class='nine' cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\"><caption><h2>$sportname Tournament Schedule & Results:</h2></caption>";
   $abbrev=preg_replace("/_/","",$sport);
   $tourntbl=$abbrev."tourn";
   $teamtbl=$tourntbl."team";
   $indytbl=$tourntbl."indy";
   $schooltbl=GetSchoolTable($abbrev);
   //$schoolid=GetSchoolID($session); 
   $schoolid=GetSchoolIDBySchool($school); 
   //print_r($sport); exit; 
      //SHOW TOURNAMENTS ENTERED & INDICATE IF RESULTS HAVE BEEN SUBMITTED
   $sql="SELECT t2.*,t1.score FROM $teamtbl AS t1, $tourntbl AS t2 WHERE t1.tournid=t2.id AND t1.sid='$sid' ORDER BY t2.tourndate ASC";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
      $html.="<tr align=\"center\"><td><b>Tournament</b></td><td><b>Date</b></td><td><b>Results</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $html.="<tr align=\"left\"><td>$row[tournname]</td><td>$date[1]/$date[2]/$date[0]</td><td>";
      if($row[unlockreport]=='x') 	//Results have been unlocked by the NSAA
	 $html.="<p><i>This tournament's report has been <b>UNLOCKED</b> by the NSAA for you to correct.</i></p><a class=small href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[id]&school_ch=$school\">Correct the Report &rarr;</a>";
      else if($row[datesub]>0)	//Results are submitted
	 $html.="<p><i>Report submitted by ".GetSchool2($row['schoolid'])." on ".date("m/d/y",$row['datesub'])." at ".date("g:ia",$row['datesub']).".</i></p><a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[id]&school_ch=$school\">View Report &rarr;</a>";
      else if($schoolid!=$row['schoolid'])
	 $html.="<a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[id]&school_ch=$school\">View Tournament &rarr;</a><p><b>".GetSchool2($row['schoolid'])."</b> will need to submit the results for this tournament.</p>";
      else	//Not submitted yet
	 $html.="<a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&tournid=$row[id]&school_ch=$school\">Enter Tournament Report &rarr;</a>";
      if($row['score']==0)
         $html.="<p style=\"text-align:right;\"><a href=\"/nsaaforms/go/results_main.php?session=$session&sport=$sport&school_ch=$school&removetournid=$row[id]&school=$school\" onClick=\"return confirm('Are you sure you want to remove this tournament from your schedule?');\" class=\"small\"><i>Remove this tournament from your schedule</i></a></p>";
      $html.="</td></tr>";
   }
   $html.="</table>";
   if(mysql_num_rows($result)==0)
      $html.="<p><i>No tournaments have been entered on your schedule yet.</i></p>";
   $html.="<br><p><a href=\"/nsaaforms/go/gotournresults.php?session=$session&sport=$abbrev&school=$school\">Enter a Regular Season Tournament &rarr;</a></p>";
 
   return $html;
}
function IsMobile()
{
   $op = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
   $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
   $ac = strtolower($_SERVER['HTTP_ACCEPT']);
   $ip = $_SERVER['REMOTE_ADDR'];
   $ismobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
        || $op != ''
        || strpos($ua, 'sony') !== false
        || strpos($ua, 'symbian') !== false
        || strpos($ua, 'nokia') !== false
        || strpos($ua, 'samsung') !== false
        || (strpos($ua, 'mobile') !== false && !strpos($ua,'ipad'))
        || strpos($ua, 'windows ce') !== false
        || strpos($ua, 'epoc') !== false
        || strpos($ua, 'opera mini') !== false
        || strpos($ua, 'nitro') !== false
        || strpos($ua, 'j2me') !== false
        || strpos($ua, 'midp-') !== false
        || strpos($ua, 'cldc-') !== false
        || strpos($ua, 'netfront') !== false
        || strpos($ua, 'mot') !== false
        || strpos($ua, 'up.browser') !== false
        || strpos($ua, 'up.link') !== false
        || strpos($ua, 'audiovox') !== false
        || strpos($ua, 'blackberry') !== false
        || strpos($ua, 'ericsson,') !== false
        || strpos($ua, 'panasonic') !== false
        || strpos($ua, 'philips') !== false
        || strpos($ua, 'sanyo') !== false
        || strpos($ua, 'sharp') !== false
        || strpos($ua, 'sie-') !== false
        || strpos($ua, 'portalmmm') !== false
        || strpos($ua, 'blazer') !== false
        || strpos($ua, 'avantgo') !== false
        || strpos($ua, 'danger') !== false
        || strpos($ua, 'palm') !== false
        || strpos($ua, 'series60') !== false
        || strpos($ua, 'palmsource') !== false
        || strpos($ua, 'pocketpc') !== false
        || strpos($ua, 'smartphone') !== false
        || strpos($ua, 'rover') !== false
        || strpos($ua, 'ipaq') !== false
        || strpos($ua, 'au-mic,') !== false
        || strpos($ua, 'alcatel') !== false
        || strpos($ua, 'ericy') !== false
        || strpos($ua, 'up.link') !== false
        || strpos($ua, 'vodafone/') !== false
        || strpos($ua, 'wap1.') !== false
        || strpos($ua, 'wap2.') !== false;
   return $ismobile;
}
function GetScoresDashboard($session,$sport="")
{
   require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
   $level=GetLevel($session);
   if($level>2) return FALSE;     //ONLY AD CAN SEE ALL SPORTS
   if($sport=="")
   {
      $sports=array(); $ix=0;
      $sql="SELECT * FROM wildcard_duedates WHERE sport NOT LIKE 'go%' AND sport!='sw' AND sport!='wr' ORDER BY lockdate";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $sports[$ix]=$row[sport]; $ix++;
      }
   }
   else $sports=array($sport);
   $html="";
   if($sport=='wr') return $html;
   for($i=0;$i<count($sports);$i++)
   {
      //SEE IF THIS SCHOOL HAD A GAME TODAY OR YESTERDAY FOR THIS SPORT AND IS MISSING THE SCORE (CHECK TOURNAMENTS TOO)
      $sid=GetSID($session,$sports[$i]);
      if(GetMissingScores($sports[$i],'0000-00-00',$sid) && $sid!="NO SID FOUND") 
      {
         $html.="<p style=\"margin:5px 0px;\"><b>".GetActivityName($sports[$i])."</b></p>";
         $missing=GetMissingScores($sports[$i],'0000-00-00',$sid);
	 for($j=0;$j<count($missing);$j++)
	 {
	    $html.="<p style=\"margin:2px 0px;\">".$missing[$j]."</p>";
	 }
         $html.="<p><a href=\"../calculate/wildcard/editscores.php?session=$session&sport=$sports[$i]&sid=$sid";
	 if($sports[$i]=='vb') $html.="#firstnull";
	 $html.="\">Go to ".GetActivityName($sports[$i])." Scores</a></p>";
      }
   }
   if($html!='')
   {
      $html="<div class='normalwhite' style=\"width:400px;\"><h2 style=\"font-size:14px;color:#8b0000;text-align:center\">Enter Team Scores:</h2>".$html."</div><br>";
   }
   return $html;
}
function GetCoopSchoolAppErrors($coopappid,$schoolid)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';

   $sql="SELECT * FROM coopapp WHERE id='$coopappid'";
   $result=mysql_query($sql);
   $app=mysql_fetch_array($result);

   $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='$schoolid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   $errors="";

   //BOARD MEMBER 1
   if(trim($row[boardmember1])=="")
   {
      if($app[renewal]=='x')
         $errors.="You must enter the Board Member who introduced the resolution and moved its adoption.<br>";
      else
         $errors.="You must enter the name of the Superintendent of your school.<br>";
   }
   //BOARD MEMBER 2
   if(trim($row[boardmember2])=="" && $app[renewal]!='x')
      $errors.="You must enter the Board Member who seconded the motion for adoption.<br>";
   //In FAVOR OF
   if(trim($row[infavor])=="" && $app[renewal]!='x')
      $errors.="You must enter the names of those who voted IN FAVOR OF the adoption of the resolution.<br>";
   //CHAIRMAN SIG
   if(trim($row[boardchair])=="" && $app[renewal]!='x')
      $errors.="You must enter the electronic signature (name) of the Chair of the Board of Education.<br>";
   //CLERK SIG
   if(trim($row[boardclerk])=="" && $app[renewal]!='x')
      $errors.="You must enter the electronic signature (name) of the Clerk of the Board of Education.<br>";

   return $errors;
}
function GetCoopAppErrors($coopappid)
{
   require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';

   $sql="SELECT * FROM coopapp WHERE id='$coopappid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   $errors="";
   //DISTRICTS ENTERED FOR EACH SCHOOL
   for($i=1;$i<=4;$i++)
   {
      $schvar="schoolid".$i; $distvar="dist".$i;
      if($row[$schvar]>0 && $row[$distvar]==0)
         $errors.="You must enter the School District No. for ".GetSchool2($row[$schvar])."<br>";
   }
   //AT LEAST ONE ACTIVITY CHECKED
   $actchecked=0;
   for($i=0;$i<count($coopsports);$i++)
   { 
      if($row[$coopsports[$i]]=='x') $actchecked++;
   }
   if($actchecked==0)
      $errors.="You must check at least one Activity for which these schools are applying for cooperative sponsorship.<br>"; 
   //2 YEARS CHECKED (year1 AND year2)
   if($row[year1]==0 || $row[year2]==0)
      $errors.="You must check TWO school years listed below as the years during which this combined program will be in effect.<br>";
   //PURPOSE - must at least fill out first bullet
   if(trim($row[purpose1])=="" && $row[renewal]!='x')
      $errors.="You must complete Section 2 - PURPOSE, the conditions which have prompted the school boards to agree to combine programs.<br>";
   //TEAM NAME, MASCOT and COLORS
   if((trim($row[teamname])=='' || trim($row[mascot])=='' || trim($row[colors])=='') && $row[renewal]!='x')
      $errors.="You must indicate the combined program's Team Name, Mascot and Colors.<br>";
   if($row[renewal]!='x')
   {
   //ALLOCATION - must fill in all 10 bullets
   $missingalloc=0;
   for($i=1;$i<=10;$i++)
   {
      $var="allocation".$i;
      if(trim($row[$var])=="")
         $missingalloc=1;
   }
   if($missingalloc==1)
      $errors.="You must complete all 10 bullet points under 4(c) - Allocation of Costs.<br>";
   //GATE RECEIPTS
   if(trim($row[gatereceipts1])=="" || trim($row[gatereceipts2])=="")
      $errors.="You must enter the method of allocation (BOTH sections) for Gate Receipts.<br>";
   //HEAD COACH SCHOOL DISTRICT
   if($row[headcoachdist]==0)
      $errors.="You must enter the School District No. for the school board that will be employing the Head Coach.<br>";
   //INSURANCE
   if(trim($row[claimantins])=="" || trim($row[claimins])=="")
      $errors.="You must enter the amount of Liability Insurance for any claimant AND for any number of claims arising out of a single occurrence.<br>";
   }
   return $errors;
}
function GoogleGetMileage($fromcity,$tocity,$fromstate="NE",$tostate="NE")
{
   require_once('xml_regex.php');
   $GKey="AIzaSyD5mZQdWbhOsHQpjYsdtZxSSJRAw7ApJ7A";
   $fromstate=trim(strtoupper($fromstate));
   $tostate=trim(strtoupper($tostate));
   $fromcity=trim($fromcity); $tocity=trim($tocity);
   $fromcity=strtoupper($fromcity); $tocity=strtoupper($tocity);
   $fromcity=preg_replace("/[^A-Z ]/","",$fromcity);
   $tocity=preg_replace("/[^A-Z ]/","",$tocity);
   $fromcity=preg_replace("/(FT )/","FORT ",$fromcity);
   $tocity=preg_replace("/(DEWITT)/","DE WITT",$tocity);
   $fromcity=preg_replace("/(DEWITT)/","DE WITT",$fromcity);
   $tocity=preg_replace("/(FT )/","FORT ",$tocity);
   if($fromcity=='' || $tocity=='' || $fromcity==$tocity)
   {
      if($thorough) echo "fromcity or tocity blank<br>";
      return 0;
   }
   $fromcityurl=urlencode("$fromcity, $fromstate");
   $tocityurl=urlencode("$tocity, $tostate");
   $url="https://maps.googleapis.com/maps/api/distancematrix/xml?origins=$fromcityurl&destinations=$tocityurl&key=$GKey&units=imperial";
   //echo $url;
   $curl_handle = curl_init();
   curl_setopt($curl_handle, CURLOPT_URL, $url);
   curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
   curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,10);
   curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
   //curl_setopt($curl_handle, CURLOPT_POST, 1);	//THIS WORKS ON IHSAA BUT NOT NSAA FOR SOME REASON
   $buffer = curl_exec($curl_handle);
   curl_close($curl_handle);
   $status = value_in('status', $buffer);
   if($status=="OK")
   {
        $routes=element_set('distance',$buffer);
        foreach($routes as $route)
        {
           $miles=value_in('text',$route);
           $miles=preg_replace("/[^0-9\.]/","",$miles);
        }
        $rounddown=floor($miles); $roundup=ceil($miles);
        if($roundup-$miles <= 0.5)      //DETERMINE WHETHER TO ROUND UP OR DOWN
           $miles=$roundup;
        else
           $miles=$rounddown;
        return $miles;
   }
   else return "<p>Could not find $fromcity, $fromstate to $tocity, $tostate</p>";
}
function GetMileage($fromcity,$tocity,$thorough=FALSE,$fromstate="NE",$tostate="NE")    //GET MILEAGE VIA MAPQUEST
{
   return GoogleGetMileage($fromcity,$tocity,$fromstate,$tostate);      //SWITCHED TO GOOGLE DISTANCE API FEB 17 2015
}
function GetHostCity($sport,$type="State",$class="")
{
   require 'variables.php';
   $sql="SELECT * FROM $db_name2.".$sport."districts WHERE type='State' ";
   if($class!='') $sql.="AND class='$class' ";
   $sql.="ORDER BY class LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 || trim($row[city])=="") return "?? Host City Unknown ??";
   return trim($row[city]);
}
function CanSubmitReimbursement($schoolid,$sport)
{
   require_once($_SERVER['DOCUMENT_ROOT']."/calculate/functions.php");

   $dbyear=date("Y");
   if(date("m")<7) $dbyear--;
   $database=GetDatabase($dbyear);

   //RETURN TRUE IF SCHOOL CAN SEE REIM FORM FOR THIS SPORT
   //WRESTLING - ALL REGISTERED WR SCHOOLS CAN
   //CC & TR - SEE IF THEY QUALIFIED FOR STATE VIA DIST RESULTS
   //ALL OTHERS - SEE IF THEY'RE MARKED IN state__ FIELD IN headers TABLE
   $sport=preg_replace("/_/","",$sport);
   if($sport=='wr')
   {
      if(IsRegistered2011($schoolid,$sport,$dbyear) && PastDue("$dbyear-11-01",0)) return TRUE;
      else return FALSE;
   }
   else if(preg_match("/cc/",$sport))
   {
      $sid=GetSID2(GetSchool2($schoolid),$sport);
      $sql="SELECT * FROM $database.cc_g_state_team WHERE sid='$sid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) return TRUE;
      $sql="SELECT * FROM $database.cc_g_state_indy WHERE sid='$sid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) return TRUE;
      $sql="SELECT * FROM $database.cc_b_state_team WHERE sid='$sid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) return TRUE;
      $sql="SELECT * FROM $database.cc_b_state_indy WHERE sid='$sid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) return TRUE;
      else return FALSE;
   }
   else if(preg_match("/tr/",$sport))
   {
      $sid=GetSID2(GetSchool2($schoolid),$sport);
      $sql="SELECT sid FROM nsaastatetrack.trstateparticipants WHERE sid='$sid'";
      if($sport=='trb') $sql.=" AND gender='B'";
      else $sql.=" AND gender='G'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0 && PastDue(date("Y")."-05-15",0) && !PastDue(date("Y")."-08-01",0)) return TRUE;
      else return FALSE;
   }
   else	//ALL OTHERS
   {
      $field="state".$sport;
      $sql="SELECT $field FROM $database.headers WHERE id='$schoolid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[$field]=='x') return TRUE;
      else return FALSE;
   }
}
function JOJudgeIsAssignedToClass($judgeid,$class)
{
   $sql="SELECT * FROM jojudge_classes WHERE jojudgeid='$judgeid' AND class='$class'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) return TRUE;
   return FALSE;
}
function GetJOSetting($field)
{
   $sql="SELECT $field FROM josettings";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
}
function GetJOStateSetting($field)
{
   $sql="SELECT $field FROM jostatesettings";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
}
function GetJOEntryErrors($sid=0,$catid=0)
{
   if($sid==0 && $catid==0) return "";
   $errors="";
   if($catid>0 && $sid>0)
   {
	//Check to see if any student has >1 entry per event
      $sql="SELECT * FROM joentries WHERE studentid>0 AND catid='$catid' AND sid='$sid'";
      $result=mysql_query($sql);
      $usedstudids=array(); $u=0;
      while($row=mysql_fetch_array($result))
      {
         $curstuds=array($row[studentid]); $c=1;
         for($j=2;$j<=6;$j++)
         {
            $var="studentid".$j;
            if($row[$var]>0)
            {
               $curstuds[$c]=$row[$var]; $c++;
            }
         }
         asort($curstuds);
         $curstudlist=implode(",",$curstuds);
         if(in_array($curstudlist,$usedstudids) && $curstudlist!='')
         {
	    $names="";
	    $studids=explode(",",$curstudlist);
	    for($i=0;$i<count($studids);$i++)
	    {
	       $names.=GetStudentInfo($studids[$i],FALSE).", ";
	    }
	    $names=substr($names,0,strlen($names)-2);
	    if(count($studids)>1) $hashave="have";
	    else $hashave="has";
	    $errors.="<p>$names $hashave more than one entry in this event.</p>";
	 }
	 else
	 {
	    $usedstudids[$u]=$curstudlist; $u++;
	 }
      }
   }
   return $errors;
}
function GetJOStateEntryErrors($sid=0,$catid=0)
{
   if($sid==0 && $catid==0) return "";
   $errors="";
   if($catid>0 && $sid>0)
   {
	//Check to see if any student has >1 entry per event
      $sql="SELECT * FROM jostateentries WHERE studentid>0 AND catid='$catid' AND sid='$sid'";
      $result=mysql_query($sql);
      $usedstudids=array(); $u=0;
      while($row=mysql_fetch_array($result))
      {
         $curstuds=array($row[studentid]); $c=1;
         for($j=2;$j<=6;$j++)
         {
            $var="studentid".$j;
            if($row[$var]>0)
            {
               $curstuds[$c]=$row[$var]; $c++;
            }
         }
         asort($curstuds);
         $curstudlist=implode(",",$curstuds);
         if(in_array($curstudlist,$usedstudids) && $curstudlist!='')
         {
	    $names="";
	    $studids=explode(",",$curstudlist);
	    for($i=0;$i<count($studids);$i++)
	    {
	       $names.=GetStudentInfo($studids[$i],FALSE).", ";
	    }
	    $names=substr($names,0,strlen($names)-2);
	    if(count($studids)>1) $hashave="have";
	    else $hashave="has";
	    $errors.="<p>$names $hashave more than one entry in this event.</p>";
	 }
	 else
	 {
	    $usedstudids[$u]=$curstudlist; $u++;
	 }
      }
   }
   return $errors;
}
function GetJOCategory($catid)
{
   $sql="SELECT * FROM jocategories WHERE id='$catid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[category];
}
function GetJOStateCategory($catid)
{
   $sql="SELECT * FROM jostatecategories WHERE id='$catid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[category];
}
function GetJOJudgeForCategory($catid,$class='')
{
   $sql="SELECT * FROM joassignments WHERE catid='$catid'";
   if($class!='') $sql.=" AND class='$class'";
   else $sql.=" ORDER BY class";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[judgeid];
}
function GetJOStateJudgeForCategory($catid,$class='')
{
   $sql="SELECT * FROM jostateassignments WHERE catid='$catid'";
   if($class!='') $sql.=" AND class='$class'";
   else $sql.=" ORDER BY class";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[judgeid];
}
function GetJOJudgeAssignment($judgeid)
{
   $sql="SELECT * FROM joassignments WHERE judgeid='$judgeid'";
   $result=mysql_query($sql);
   $list="";
   while($row=mysql_fetch_array($result))
   {
      $list.=$row[id].",";
   }
   if($list!='') $list=substr($list,0,strlen($list)-1);
   return $list;
}
function GetJOStateJudgeAssignment($judgeid)
{
   $sql="SELECT * FROM jostateassignments WHERE judgeid='$judgeid'";
   $result=mysql_query($sql);
   $list="";
   while($row=mysql_fetch_array($result))
   {
      $list.=$row[id].",";
   }
   if($list!='') $list=substr($list,0,strlen($list)-1);
   return $list;
}
function GetJOJudgeCategory($judgeid)
{
   $sql="SELECT * FROM joassignments WHERE judgeid='$judgeid'";
   $result=mysql_query($sql);
   $cats="";
   while($row=mysql_fetch_array($result))
   {
      $cats.=$row[catid].",";
   }
   if($cats!='') $cats=substr($cats,0,strlen($cats)-1);
   return $cats;
}
function GetJOStateJudgeCategory($judgeid)
{
   $sql="SELECT * FROM jostateassignments WHERE judgeid='$judgeid'";
   $result=mysql_query($sql);
   $cats="";
   while($row=mysql_fetch_array($result))
   {
      $cats.=$row[catid].",";
   }
   if($cats!='') $cats=substr($cats,0,strlen($cats)-1);
   return $cats;
}
function LoginJOJudge($email,$password)
{
   //RETURN $session IF SUCCESSFUL LOGIN, else FALSE
   $sql="SELECT * FROM jojudges WHERE email='$email' AND password='$password'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   $judgeid=$row[id];
   $session=time();
   $sql="SELECT * FROM jojudges WHERE session='$session'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $session--;
      $sql="SELECT * FROM jojudges WHERE session='$session'";
      $result=mysql_query($sql);
   }
   //NOW SESSION IS ASSUREDLY UNIQUE FOR jojudges
   $sql="UPDATE jojudges SET session='$session' WHERE id='$judgeid'";
   $result=mysql_query($sql);
   return $session;
}
function LoginJOStateJudge($email,$password)
{
   //RETURN $session IF SUCCESSFUL LOGIN, else FALSE
   $sql="SELECT * FROM jostatejudges WHERE email='$email' AND password='$password'"; 
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   $judgeid=$row[id]; 
   $session=time();
   $sql="SELECT * FROM jostatejudges WHERE session='$session'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $session--;
      $sql="SELECT * FROM jostatejudges WHERE session='$session'";
      $result=mysql_query($sql);
   }
   //NOW SESSION IS ASSUREDLY UNIQUE FOR jojudges
   $sql="UPDATE jostatejudges SET session='$session' WHERE id='$judgeid'";
   $result=mysql_query($sql);
   return $session;
}
function ValidJOJudge($session)
{
   $sql="SELECT * FROM jojudges WHERE session='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function ValidJOStateJudge($session)
{
   $sql="SELECT * FROM jostatejudges WHERE session='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function GetJOJudgeID($session)
{
   $sql="SELECT * FROM jojudges WHERE session='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   return $row[id];
}
function GetJOStateJudgeID($session)
{
   $sql="SELECT * FROM jostatejudges WHERE session='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   return $row[id];
}
function GetJOJudgeName($session=0,$judgeid=0)
{
   if($session)
      $sql="SELECT * FROM jojudges WHERE session='$session'";
   else
      $sql="SELECT * FROM jojudges WHERE id='$judgeid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   return "$row[first] $row[last]";
}
function GetJOStateJudgeName($session=0,$judgeid=0)
{
   if($session)
      $sql="SELECT * FROM jostatejudges WHERE session='$session'";
   else
      $sql="SELECT * FROM jostatejudges WHERE id='$judgeid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   return "$row[first] $row[last]";
}
function GetJOHeader($session)
{
     $logo="NSAAheaderLogin.jpg"; $color="#ffffff";
     $string="<table width=100% cellspacing=0 cellpadding=0>";
     $string.="<tr><td bgcolor=#19204f>&nbsp;</td></tr>";
     $string.="<tr align=center>";
     $string.="<td valign=center bgcolor=$color>";
     $string.="<img src=\"/images/$logo\" width=\"800px\"></td></tr>";
     $string.="<tr><td bgcolor=#19204f><center>";
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/jo/judgemain.php?session=$session\">Home</a>";
     $string.="&nbsp;&nbsp;&nbsp;<font color=#FFFFFF><b>|&nbsp;&nbsp;&nbsp;</b></font>";
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/jo/logout.php?session=$session\">Logout</a>";
     $string.="</td></tr>";
     $string.="<tr align=center><td align=center>";

  return $string;
}
function GetJOStateHeader($session)
{
     $logo="NSAAheaderLogin.jpg"; $color="#ffffff";
     $string="<table width=100% cellspacing=0 cellpadding=0>";
     $string.="<tr><td bgcolor=#19204f>&nbsp;</td></tr>";
     $string.="<tr align=center>";
     $string.="<td valign=center bgcolor=$color>";
     $string.="<img src=\"/images/$logo\" width=\"800px\"></td></tr>";
     $string.="<tr><td bgcolor=#19204f><center>";
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/jo/judgestatemain.php?session=$session\">Home</a>";
     $string.="&nbsp;&nbsp;&nbsp;<font color=#FFFFFF><b>|&nbsp;&nbsp;&nbsp;</b></font>";
        $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/jo/jlogout.php?session=$session\">Logout</a>";
     $string.="</td></tr>";
     $string.="<tr align=center><td align=center>";

  return $string;
}
function IsJOTestSchool($schoolid)
{
   $schs=array("1357","1408","1411","1393","1389","1508","1513","1569");
   for($i=0;$i<count($schs);$i++)
   {
      if($schoolid==$schs[$i]) return TRUE;
   }
   return FALSE;
}
function GetWRVideoTeams()
{
   $teams="<team>";
   $sql="SELECT DISTINCT redteam FROM wrvideos ORDER BY redteam";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if(!preg_match("/<team>".$row[0]."<team>/",$teams))
      {
         $teams.=$row[0]."<team>";
      }
   }
   $sql="SELECT DISTINCT blueteam FROM wrvideos ORDER BY blueteam";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if(!preg_match("/<team>".$row[0]."<team>/",$teams))
      {
         $teams.=$row[0]."<team>";
      }
   }
   if($teams=="<team>") return FALSE;
   $teams=substr($teams,6,strlen($teams)-12);
   return $teams;
}
function IsInWRVideoCart($sessionid,$first,$last,$team)
{
      $sql="SELECT * FROM wrvideocarts WHERE sessionid='$sessionid' AND wrestlerfirst='".addslashes($first)."' AND wrestlerlast='".addslashes($last)."' AND wrestlerteam='".addslashes($team)."'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) return TRUE;
      else return FALSE;
}
function GetWRCartCount($sessionid)
{
      $sql="SELECT * FROM wrvideocarts WHERE sessionid='$sessionid'";
      $result=mysql_query($sql);
      return mysql_num_rows($result);
}
function GetJOStateQualifierList()
{
   //RETURN LIST OF QUALIFIERS IN EACH EVENT FOR EACH CLASS

   $html="";
   $sql="SELECT * FROM jocategories ORDER BY category";
   $result=mysql_query($sql);
   $catct=mysql_num_rows($result); $i=0;
   while($row=mysql_fetch_array($result))               //FOR EACH CATEGORY
   {
      $catid=$row[id];
      $html.="<h3><u>$row[category]</u></h3><table width='100%'><tr align=left valign=top>";
      //$sql1="SELECT t1.*,t2.orderby FROM joentries AS t1,joqualifiers AS t2 WHERE t1.id=t2.entryid AND t2.catid='$catid' AND t1.studentid>0 ORDER BY t2.orderby";
      $sql0="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
      $result0=mysql_query($sql0);
      while($row0=mysql_fetch_array($result0))
      {
      $class=$row0[0];
	$html.="<td width='50%'><h3>CLASS $class:</h3>";

      //$sql1="SELECT t1.* FROM joentries AS t1, joschool AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.classrank>=1 AND t1.classrank<=12 AND t1.catid='$catid' ORDER BY t1.classrank";
      $sql1="SELECT t1.id AS qualid,t1.orderby,t2.* FROM joqualifiers AS t1,joentries AS t2,joschool AS t3 WHERE t1.entryid=t2.id AND t2.sid=t3.sid AND t1.class='$class' AND t1.catid='$catid' ORDER BY t1.orderby";
      $result1=mysql_query($sql1);
      $labelalternates=0;
      while($row1=mysql_fetch_array($result1))          //FOR EACH PLACE, SHOW STUDENT & SCHOOL
      {
	/*
         if($row1[orderby]>=13 && $labelalternates==0) 	//CHECK TO SEE IF WE NEED TO LABEL ALTERNATES
	 {
	    if($row1[classrank]!=1)
	    {
	       $html.="<br><b><u>Alternates:</u></b><br>"; $labelalternates=1;
	    }
         }
	*/
         $html.="<p>".GetStudentInfo($row1[studentid],FALSE).", ";
         for($j=2;$j<=6;$j++)
         {
            $var="studentid".$j;
            if($row1[$var]>0) $html.=GetStudentInfo($row1[$var],FALSE).", ";
         }
         $html.=GetSchoolName($row1[sid],'jo')."</p>";
         $file_url=strpos($row1[filename],'http')===0?$row1[filename]:"/nsaaforms/downloads/".$row1[filename];
         $html.="<p style='padding-left:20px;'><a class='small' href='$file_url' target=\"_blank\">$row1[label]</a></p>";
         if($row1[filename2]!=''){
              $file2_url=strpos($row1[filename2],'http')===0?$row1[filename2]:"/nsaaforms/downloads/".$row1[filename2];
              $html.="<p style=\"padding-left:20px;\"><a class=\"small\" href='$file2_url' target=\"_blank\">$row1[label2]</a></p>";
         }
         if($row1[filename3]!=''){
              $file3_url=strpos($row1[filename3],'http')===0?$row1[filename3]:"/nsaaforms/downloads/".$row1[filename3];
              $html.="<p style=\"padding-left:20px;\"><a class=\"small\" href='$file3_url' target=\"_blank\">$row1[label3]</a></p>";
         }

      }
      if(mysql_num_rows($result1)==0)
      {
         $html.="<p style=\"color:#ff0000;\"><b><i>The State Qualifiers for $row[category] have not been saved yet under \"Manage State Journalism Contest Qualifiers.\"</i></b></p>";
      }
      $html.="<br>";
      $i++;
      //if($i==(floor($catct/2))) $html.="<!--HALFWAY-->";
	$html.="</td>";
      } //END for each class
	$html.="</tr></table>";
   }
   return $html;
}
function GetJOEntries($catid=0,$year="",$jcomments=FALSE)
{
   if(!$catid) return FALSE;
   require_once( 'variables.php');
   if($year=="" || !$year) $database=$db_name;
   else $database=GetDatabase($year);

    //SHOW ALL ENTRIES ON THE WEBSITE FOR THIS CATEGORY

      $sql1="SELECT * FROM $database.jocategories WHERE id='$catid'";
      $result1=mysql_query($sql1);
      $row1=mysql_fetch_array($result1);

   $html="<h2>NSAA Journalism Entries: ".strtoupper(GetJOCategory($catid))."</h2>";
   $html.="<table class='nine' cellspacing=0 cellpadding=5 style=\"max-width:1000px;\"><tr valign=top align=left>";
   $sql="SELECT DISTINCT class FROM $database.joschool WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))               //FOR EACH CLASS
   {
      $class=$row['class'];
      $html.="<td width='50%'><h2>CLASS $class:</h2>"; $curcol=0;
     
         $sql2="SELECT t1.* FROM $database.joentries AS t1,$database.joschool AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.catid='$catid' AND t1.studentid>0 AND filename!='' ORDER BY t2.school";
         $result2=mysql_query($sql2);
         $curct=0;
         
            while($row2=mysql_fetch_array($result2))
            {
	       if(citgf_file_exists("../downloads/$row2[filename]"))
	       {
               $html.="<p>".GetStudentInfo($row2[studentid],FALSE).", ";
               for($j=2;$j<=6;$j++)
               {
                  $var="studentid".$j;
                  if($row2[$var]>0) $html.=GetStudentInfo($row2[$var],FALSE).", ";
               }
               $html.=GetSchoolName($row2[sid],'jo')."</p>";
               if($row2[label]=="") $row2[label]="Entry";
               $html.="<p style='padding-left:20px;'><a class='small' href=\"/nsaaforms/downloads/$row2[filename]\" target=\"_blank\">$row2[label]</a></p>";
               if($row2[filename2]!='')
                  $html.="<p style=\"padding-left:20px;\"><a class=\"small\" href=\"/nsaaforms/downloads/$row2[filename2]\" target=\"_blank\">$row2[label2]</a></p>";
	       if($jcomments && $row2[judgecomments]!='')
	       {
		  $html.="<p style=\"padding-left:20px;\"><b>Judge's Comments:</b> <i>$row2[judgecomments]</i></p>";
	       }
               $curct++;
	       }
            }
       
      $html.="</td>";
   }//END FOR EACH CLASS
   $html.="</tr></table>";
   return $html;   
}
function GetJOFullResults($public=FALSE,$year='')
{
   require_once( 'variables.php');
   if($year=="" || !$year) $database=$db_name;
   else $database=GetDatabase($year);
   //RETURN JO FULL RESULTS TO SHOW ON WEBSITE
   //IF public==TRUE, only show approved categories

   $html="<b>TOP 12 FINISHERS FROM ".date("Y")." PRELIMINARY JUDGING:</b>";
   $sql="SELECT DISTINCT class FROM $database.joschool WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))               //FOR EACH CLASS
   {
      $class=$row['class'];
      $html.="<div style=\"margin:0;padding:0;page-break-after:always;\"><table class='nine' cellspacing=0 cellpadding=5><caption><b>Class $class Preliminary Results:</b></caption>";
      $sql1="SELECT * FROM $database.jocategories ORDER BY category";
      $result1=mysql_query($sql1);
      if(mysql_num_rows($result1)%2==0) $percol=mysql_num_rows($result1)/2;
      else $percol=ceil(mysql_num_rows($result1)/2);
      $html.="<tr align=left valign=top><td width='50%'>"; $curcol=0;
      while($row1=mysql_fetch_array($result1))          //FOR EACH EVENT
      {
         $catid=$row1[id];
         if($curcol==$percol) $html.="</td><td>";
    	 else if($curcol>0) $html.="<br>";
         $html.="<b>".strtoupper($row1[category])."</b>";
         if($row1['showplace']!=1)
            $html.="<p><i>This list does not indicate final placement. Final placements will be announced at the award ceremony.</i></p>";
	 else $html.="<br>";
         $sql2="SELECT t1.* FROM $database.joentries AS t1,$database.joschool AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.catid='$catid' AND t1.classrank>0 AND t1.classrank<=12 AND t1.studentid>0 ";
         if($row1[showplace]==1) $sql2.="ORDER BY t1.classrank";
	 else $sql2.="ORDER BY t2.school";
         $result2=mysql_query($sql2);
         $curct=0;
         if($row1[webapproved]>0 || !$public)
         {
            while($row2=mysql_fetch_array($result2))
            {
               $html.="<p>";
	       if($row1[showplace]==1) $html.="$row2[classrank]. ";
	       $html.=GetStudentInfo($row2[studentid],FALSE).", ";
               for($j=2;$j<=6;$j++)
               {
                  $var="studentid".$j;
                  if($row2[$var]>0) $html.=GetStudentInfo($row2[$var],FALSE).", ";
               }
               $html.=GetSchoolName($row2[sid],'jo')."</p>";
                $file_url=strpos($row2[filename],'http')===0?$row2[filename]:"/nsaaforms/downloads/".$row2[filename];
                $html.="<p style='padding-left:20px;'><a class='small' href='$file_url' target=\"_blank\">$row2[label]</a></p>";
                if($row2[filename2]!=''){
                    $file2_url=strpos($row2[filename2],'http')===0?$row2[filename2]:"/nsaaforms/downloads/".$row2[filename2];
                    $html.="<p style=\"padding-left:20px;\"><a class=\"small\" href='$file2_url' target=\"_blank\">$row2[label2]</a></p>";
                }
                if($row2[filename3]!=''){
                    $file3_url=strpos($row2[filename3],'http')===0?$row2[filename3]:"/nsaaforms/downloads/".$row2[filename3];
                    $html.="<p style=\"padding-left:20px;\"><a class=\"small\" href='$file3_url' target=\"_blank\">$row2[label3]</a></p>";
                }
	       $curct++;
            }
	 }
         while($curct<12)
         {
            $rank=$curct+1; 
     	    $html.="$rank.<br>"; 
	    $curct++;
         }
         $curcol++;
      } //END FOR EACH CATEGORY
      $html.="</td></tr></table></div>";	 //Page break after each class
   } //END FOR EACH CLASS
   //TEAM RESULTS
   //$html.="<h3>".date("Y")." State Journalism Sweepstakes Results:</h3>".GetJOTeamResults($public);
   return $html;
}
function GetJOTeamResults($public)
{
   //RETURN TABLE OF JO SWEEPSTAKES (TEAM RESULTS)
  
   //IF $public==TRUE and THERE ARE ANY CATEGORIES THAT HAVE NOT BEEN APPROVED YET, WE SHOW INFO NOT AVAILABLE
   $sql="SELECT * FROM jocategories WHERE webapproved=0";
   $result=mysql_query($sql);
   if($public && mysql_num_rows($result)>0)	//SOME HAVE YET TO BE APPROVED
   {
      $html="<table cellspacing=0 cellpadding=5 class='nine' style=\"width:700px;\"><caption><i>Information not available at this time.</caption></table>";
      return $html;
   }

   //FIRST GET RESULTS AND CALCULATE TEAM POINTS
   $sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   $points=array(); //$points[class][sid]=points for that team
   while($row=mysql_fetch_array($result))
   {
      $curclass=$row['class']; $points[$curclass]=array();
      $sql2="SELECT t1.* FROM joentries AS t1,joschool AS t2 WHERE t1.sid=t2.sid AND t2.class='$row[class]' AND t1.studentid>0";
      $sql2.=" AND t1.classrank>0 AND t1.classrank<=3 ORDER BY t1.classrank";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         //3 points for 1st, 2 for 2nd, 1 for 3rd
         if($row2[classrank]==1) $pts=3;
         else if($row2[classrank]==2) $pts=2;
         else $pts=1;
         $points[$curclass][$row2[sid]]+=$pts;
      }
   }

   $html="<table cellspacing=0 cellpadding=5 class='nine' style=\"width:700px;\">";
   $sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   $percol=ceil(mysql_num_rows($result)/2);
   $html.="<tr align=left valign=top><td width='50%'>";
   $curcol=0;
   while($row=mysql_fetch_array($result))
   {
      if($curcol==$percol)
         $html.="</td><td>";
      $html.="<p><b>CLASS $row[class] Sweepstakes:</b></p><ol>";
      $curclass=$row['class'];
      $sids=array(); $pts=array(); $p=0;
      foreach($points[$curclass] as $key => $value)
      {
         $sids[$p]=$key; $pts[$p]=$value; $p++;
      }
      array_multisort($pts,SORT_DESC,SORT_NUMERIC,$sids);
      for($i=0;$i<count($pts);$i++)
      {
         $cursid=$sids[$i]; $curpts=$pts[$i];
         $html.="<li> ".GetSchoolName($cursid,'jo').", $curpts</li>";
      }
      $html.="</ol>";
      $curcol++;
   }
   $html.="</td></tr></table>";
   return $html;
}
function ConfigureSchoolForProgramSchedule($school,$max=26,$errorcheck=FALSE)
{
   //Configure School (Team Name) to fit properly on Schedule on State Program page
   //Refer to ba/view_ba.php for an example of usage
   //If a school name is > $max characters, wrap it to next line and indent
   if(strlen($school)<=$max) return $school;
   //return $school;
   //Start by splitting by / - or space. 
   $pieces=preg_split("/([\s\/-])/m",$school,-1,PREG_SPLIT_DELIM_CAPTURE); // | PREG_SPLIT_NO_EMPTY);   
   $line2=end($pieces);
   if($errorcheck) echo $line2."\r\n";
   //SEE IF THIS TOOK OFF ENOUGH CHARACTERS FROM LINE 1:
   $ct=1;
   while((strlen($school)-strlen($line2))>$max)
   {
      //$curpieces=preg_split("/([\s\/-])/m",$line1,-1,PREG_SPLIT_DELIM_CAPTURE); // | PREG_SPLIT_NO_EMPTY);
      $index=count($pieces)-($ct+1);
      $line2.=" ".$pieces[$index];
	if($errorcheck) echo $line2." ".strlen($line2)." $max\r\n";
      $ct++;
   }
   //NOW WE KNOW TO TAKE OFF $ct ITEMS FROM $pieces ARRAY TO MAKE IT FIT
   $line1=""; $i=0;
   while($i<(count($pieces)-$ct))
   {
      $line1.=$pieces[$i];	//string
      $i++;
      $line1.=$pieces[$i];	//delimiter
      $i++;
      if(trim($pieces[$i])=="") 	//there were two delimiters
      {
	 $i++;
	 $line1.=$pieces[$i];
	 $i++;
      }
   }
   //LAST THING PUT ON $line1 SHOULD HAVE BEEN A DELIMITER
   $line1=trim($line1);
   //NOW GENERATE $line2
   $line2=""; $i=(count($pieces)-$ct);
   while($i<count($pieces))
   {
      $line2.=$pieces[$i];      //string
      $i++;
      $line2.=$pieces[$i];      //delimiter
      $i++;
      if(trim($pieces[$i])=="")         //there were two delimiters
      {
         $i++;
         $line2.=$pieces[$i];
         $i++;
      }
   }
   return "$line1<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$line2"; 
}
function CanAccessCaucusProposal($school="",$loginid=0)
{
   if($loginid>0)
   {
      $sql="SELECT * FROM logins WHERE id='$loginid'";
   }
   else if($school!='')
   {
      $sql="SELECT * FROM logins WHERE school='".addslashes($school)."' AND (level=5 OR level=2) ORDER BY level LIMIT 1";
   }
   else return FALSE;
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[caucusproposal]=='x') return TRUE;
   else return FALSE;
}
function GetTennisMeetResults($sport="te_b",$meetid=0,$sid=0)
{
//$results=GetTennisMeetResults("te_b",$meetid,$sid);
      $sql="SELECT * FROM ".$sport."meetresults WHERE ";
      if($sid) $sql.="(oppid1='$sid' OR oppid2='$sid') AND ";
      $sql.="meetid='$meetid' ORDER BY division";
      $result=mysql_query($sql);
      $string="";
      while($row=mysql_fetch_array($result))
      {
         if(preg_match("/doubles/",$row[division]))
         {
            $temp=explode("doubles",$row[division]);
            $division="Doubles";
         }
         else
         {
            $temp=explode("singles",$row[division]);
            $division="Singles";
         }
	 if($sid==0) $division="<b>#".$temp[1]."</b> ".$division;
         else $division="#".$temp[1]." ".$division;
         $string.="$row[id]<detail>$division<detail>";
         if(($sid==0 && $row[winnerid]==$row[oppid1]) || ($sid>0 && $row[oppid1]==$sid))
         {
            $player1=$row[player1]; $player2=$row[player2]; $player3=$row[player3]; $player4=$row[player4];
            $oppid2=$row[oppid2]; $oppid1=$row[oppid1];
	    if($sid>0 && $row[winnerid]==$row[oppid1]) $winloss="W";
	    else if($sid>0) $winloss="L";
         }
         else
         {
            $player1=$row[player3]; $player2=$row[player4]; $player3=$row[player1]; $player4=$row[player2];
            $oppid2=$row[oppid1]; $oppid1=$row[oppid2];
            if($sid>0 && $row[winnerid]==$row[oppid2]) $winloss="W";
            else if($sid>0) $winloss="L";
         }
         $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player1'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
    	 if($sid==0)
            $string.="<b>".GetSchoolName($oppid1,$sport)."</b>: ";
	 $string.="$row2[first] $row2[last] (".GetYear($row2[semesters]).")";
         if(preg_match("/double/",$row[division]))
         {
            $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player2'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $string.=", $row2[first] $row2[last] (".GetYear($row2[semesters]).")";
         }
         $string.="<detail>$row[varsityjv1]<detail>";
         if($oppid2!="1000000000")
         {
	    if($sid==0) $string.="<b>";
            $string.=GetSchoolName($oppid2,$sport);
	    if($sid==0) $string.="</b>";
 	    $string.=": ";
            $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player3'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $string.="$row2[first] $row2[last] (".GetYear($row2[semesters]).")";
            if(preg_match("/double/",$row[division]))
            {
               $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player4'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $string.=", $row2[first] $row2[last] (".GetYear($row2[semesters]).")";
            }
         }
         else
         {
            $string.="$row[oosschool]: ";
            $string.="$row[oosplayer1]";
            if(preg_match("/double/",$row[division]))
               $string.=", $row[oosplayer2]";
         }
         $string.="<detail>$row[varsityjv2]<detail>";
	 if($sid>0)	//WIN or LOSS?
	 {
	    $string.=$winloss."<detail>$row[score]<detail>";
	 }
         else
	 {
	    if(!preg_match("/[^0-9- ]/",$row[score])) //IF REGULAR Score1-Score2 score
	    {
	       $score=explode("-",$row[score]);
	       if($row[winnerid]==$row[oppid1]) $row[score]=$score[0]."-".$score[1];
	       else $row[score]=$score[1]."-".$score[0];
  	    }
	    $string.="<detail>$row[score]<detail>";
	 }
	 $string.="$row[oppid1]<result>";
      }
      $string=substr($string,0,strlen($string)-8);
      if(mysql_error()) $string=mysql_error();
      else if(mysql_num_rows($result)==0) $string="[No Results]";
   return $string;
}
function CleanSessions()
{
   $oldtime=time()-(3*24*60*60);        //OLDER THAN 3 DAYS
   $sql="DELETE FROM sessions WHERE session_id<'$oldtime'";
   $result=mysql_query($sql);
}
function GetCupRegistrationTable()
{
   //Return the "tablename" field from cupregistrationtable table
   //This field is either the default - schoolregistration - or the archived table from the current year
   //In April or May of each year, Megan H archives schoolregistration, and we need to be able to continue
   //   referring to that info that was archived through May 31
   $sql="SELECT tablename FROM cupregistrationtable";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
}
function GetCupClass($enrollment)
{
   $sql="SELECT class FROM cupclasssettings WHERE minenroll<=$enrollment AND maxenroll>=$enrollment AND class!=''";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result))
      return $row['class'];
   else return "[CLASS?]";
}
function CupAssignClasses()
{
    //GO THROUGH ENROLLMENT AND ASSIGN Cup Classes ACCORDING TO cupclasssettings
    $sql="SELECT * FROM cupclasssettings WHERE class!='' ORDER BY minenroll, maxenroll";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result))
    {
        $sql2="SELECT id,girlsenrollment FROM headers WHERE CAST(girlsenrollment AS UNSIGNED)>='$row[minenroll]' AND CAST(girlsenrollment AS UNSIGNED)<='$row[maxenroll]';";
        $result2=mysql_query($sql2);
        while($row2=mysql_fetch_array($result2))
        {
            $row2['girlsenrollment'];
            $sql3="UPDATE cupschools SET cupclass='$row[class]' WHERE schoolid='$row2[id]'";
            $result3=mysql_query($sql3);
        }
    }
}
function GetCupPointsForPlace($place,$placect)
{
   //GIVEN THE place & place ct (# of teams who have this place - in event of ties, place ct>1), 
   //RETURN THE NUMBER OF POINTS THIS PLACE SHOULD GET, PULLING FROM cupppointssettings
   if($placect==1)	//RETURN cuppointssetings.points
   {
      $sql="SELECT points FROM cuppointssettings WHERE place='$place'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      return $row['points'];
   }
   //ELSE THERE IS A TIE - GET $placect PLACES, STARTING AT $place; RETURN AVERAGE
   $place2=$place+$placect-1;
   $sql="SELECT SUM(points) FROM cuppointssettings WHERE place>='$place' AND place<='$place2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return ($row[0]/$placect);
}
function CupAssignPoints($sport='',$class,$message=TRUE)
{
   if($sport=='') return FALSE;
   //Look at cuppplaces and cupschoolsactivities to see which schools get points for this sport/class

   //FIRST CLEAR OUT THE SCHOOLS IN cuppoints
   $sql="DELETE FROM cuppoints WHERE activity='$sport' AND class='$class'";
   $result=mysql_query($sql);

   if($class=="reg")    //REGISTRATION POINTS
   {
      $points=GetCupPointAmount(0);
      $sql="SELECT * FROM cupschoolsactivities WHERE activity='$sport' AND participating='x'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $sql2="INSERT INTO cuppoints (activity,class,schoolid,points) VALUES ('$sport','reg','$row[schoolid]','$points')";
         $result2=mysql_query($sql2);
	echo mysql_error();
      }
      return;
   } 

   //NOW GO THROUGH THE PLACES ENTERED AND ASSIGN POINTS:
   if($sport=='wrd') $schsp='wr';
   else $schsp=$sport;
   if(preg_match("/sw/",$sport)) $schooltable="swschool";
   else $schooltable=$schsp."school";
   $sql="SELECT t1.*,t2.school,t2.mainsch,t2.othersch1,t2.othersch2,t2.othersch3 FROM cupplaces AS t1, ".$schooltable." AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.activity='$sport' ORDER BY t1.place";
   //code by robin
   if ($sport=="ubo"){
        $sql = "SELECT t1.*,t2.school,t2.mainsch,t2.othersch1,t2.othersch2,t2.othersch3 FROM cupplaces AS t1, " . $schooltable . " AS t2 WHERE t1.sid=t2.sid AND t1.activity='$sport' ORDER BY t1.place";
    }
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) 
   {
      if($message) return "<p>All points have been reset for Class $class ".GetActivityName($sport).".</p>";
      else return;
   }
   $message="";
   $wrschools=array(); $w=0;
   while($row=mysql_fetch_array($result))
   {
      $message.="<p><b>$row[place]) ".strtoupper($row[school]).":</b></p><ul>";
      //HOW MANY OTHER TEAMS IN THIS CLASS GOT THE SAME PLACE (ties)
      $sql3="SELECT t1.id FROM cupplaces AS t1, ".$schooltable." AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.place='$row[place]' AND t1.activity='$sport'";
      //code by robin
      if ($sport=="ubo"){
          $sql3="SELECT t1.id FROM cupplaces AS t1, ".$schooltable." AS t2 WHERE t1.sid=t2.sid AND t1.place='$row[place]' AND t1.activity='$sport'";
      }
      $result3=mysql_query($sql3);
      $placect=mysql_num_rows($result3);
      $points=GetCupPointsForPlace($row[place],$placect);
      //MAIN SCH FIRST, THEN OTHER SCH 1-3
      for($i=0;$i<=3;$i++)
      {
	 if($i==0) $field="mainsch";
  	 else $field="othersch".$i;
         if($row[$field]>0)
         {
            $sql2="SELECT * FROM cupschoolsactivities WHERE schoolid='".$row[$field]."' AND activity='$schsp'";
            $result2=mysql_query($sql2);
		/*	TOOK THIS OUT OCTOBER 7th
            if(mysql_num_rows($result2)==0)	//Check registration for this school and activity, insert
            {
               if(IsRegistered2011($row[$field],$schsp)) $participating='x';
               else $participating="";
               $sql2="INSERT INTO cupschoolsactivities (schoolid,activity,participating) VALUES ('".$row[$field]."','$schsp','$participating')";
	       $result2=mysql_query($sql2);
               $sql2="SELECT * FROM cupschoolsactivities WHERE schoolid='".$row[$field]."' AND activity='$schsp'";
               $result2=mysql_query($sql2);
            }
		*/
            $row2=mysql_fetch_array($result2);
            if($row2[participating]=='x')	//YES THEY GET POINTS
            {
	       $sql2="SELECT * FROM cuppoints WHERE activity='$sport' AND schoolid='".$row[$field]."' AND class='$class'";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)==0)
               {
	          $sql2="INSERT INTO cuppoints (class,activity,schoolid,points) VALUES ('$class','$sport','".$row[$field]."','$points')";
	          $result2=mysql_query($sql2);
               }
	       else
	       {
	          $row2=mysql_fetch_array($result2);
 	          $sql2="UPDATE cuppoints SET points='$points' WHERE id='$row[id]'";
	          $result2=mysql_query($sql2);
	       }
	       $message.="<li><b>".GetSchool2($row[$field])." </b>was given <b><u>$points</u></b> points.</li>";
	       //UPDATE TOTALS FOR THIS SCHOOL
	       UpdateCupPointTotals($row[$field]);
	       if(preg_match("/wr/",$sport))	//STORE THIS SCHOOL SO WE CAN CHECK IT LATER
	       {
		  $wrschools[$w]=$row[$field]; $w++;
 	       }       
            }
            else $message.="<li><b>".GetSchool2($row[$field])."</b> is not participating in this sport.</li>";
	 } //END IF $field > 0 
      } //END FOR EACH SCHOOL IN CO-OP (IF COOP)
      $message.="</ul>";
   }	//END FOR EACH TEAM THAT PLACED
   //NOW CHECK ON WRESTLING SCHOOLS, IF ANY
   if(count($wrschools)>0)
   {
      //For each school, get their WR and WRD points out in order of lowest to highest.
      //As long as they have points in WR AND WRD:
 	//Remove the lowest, keep the highest.
      	//If both the same, keep WR points (I just made this arbitrary decision)
      $wrmessage="";
      for($i=0;$i<count($wrschools);$i++)
      {
         $sql="SELECT id,activity,points FROM cuppoints WHERE class='$class' AND (activity='wr' OR activity='wrd') AND schoolid='$wrschools[$i]' ORDER BY points ASC,activity DESC";
	 $result=mysql_query($sql);
         if(mysql_num_rows($result)==2)	//Have both WR and WRD
         {
            $row=mysql_fetch_array($result); $ignoreid=$row[id];
	    $ignorepts=$row['points']; $ignoreact=GetActivityName($row['activity']);
	    if($row['activity']=="wr") $usesp="Dual Wrestling";
	    else $usesp="Wrestling"; 
            $row=mysql_fetch_array($result);
	    $usepts=$row['points'];
	    $sql="UPDATE cuppoints SET ignorepts='x' WHERE id='$ignoreid'";
            $result=mysql_query($sql);
	    $wrmessage.="<li>".GetSchool2($wrschools[$i])."'s <b><u>$ignoreact</b></u> points ($ignorepts) were ignored in favor of their $usesp points ($usepts).</li>";
            UpdateCupPointTotals($wrschools[$i]);
         }
      }
      if($wrmessage!='') $message.="<p><u><b>WRESTLING/WRESTLING DUAL POINTS:</u></b></p><ul>$wrmessage</ul>";
   }
   if($message) return $message;
}
function GetCupPointAmount($place)
{
   $sql="SELECT * FROM cuppointssettings WHERE place='$place'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[points];
}
function UpdateCupPointTotals($schoolid)
{
   //Update girlspoints, boyspoints and allpoints in cupschools table for $schoolid
   $sql="SELECT * FROM cupschools WHERE schoolid='$schoolid'";
   $result=mysql_query($sql);
   $sch=mysql_fetch_array($result);

   $sql="SELECT * FROM cupactivities";
   $result=mysql_query($sql);
   $i=0; $total=0; $girls=0; $boys=0;
   while($row=mysql_fetch_array($result))
   {
      $sport=$row[activity];
      $points=0;
        //REGISTRATION
      $sql2="SELECT t1.participating,t2.points FROM cupschoolsactivities AS t1, cuppoints AS t2 WHERE t1.activity=t2.activity AND t1.schoolid=t2.schoolid AND t2.class='reg' AND t1.schoolid='$schoolid' AND t1.activity='$sport'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)==0) $row2[points]=0;
      $regpoints=$row2[points];
      if($sport=='mu')
      {
         //Need at least 2 IM entries to get 5 points; another 5 for >=2 VM entries
         //Need at least 2 IM entries to get 5 points; another 5 for >=2 VM entries
         $sql2="SELECT * FROM cuppoints WHERE activity='mu' AND schoolid='$schoolid' AND class='$sch[cupclass]'";
         $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $points=intval($row2[points]);
      } //end if Music
      else
      {
        //TOP 8
         $sql2="SELECT * FROM cuppoints WHERE schoolid='$schoolid' AND activity='$sport' AND class!='reg' AND ignorepts!='x'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if(mysql_num_rows($result2)==0) $row2[points]=0;
         $points+=$row2[points];
      } //END IF NOT MUSIC
	//GIRLS POINTS
      $curgirls=0;
      if($sch[gender]!='boys')     //NOT A BOYS ONLY SCHOOL
      {
         if($row[gender]=="Girls") $curgirls=$regpoints+$points;      //GET ALL THE POINTS FOR A GIRLS SPORT
         else if($row[gender]=='')	//GENDER NEUTRAL SPORT
         {
            //if($sch[gender]=="girls")      //GIRLS ONLY SCHOOL GETS ALL POINTS FOR GENDER NEUTRAL SPORT
               $curgirls=$points+$regpoints;
            //else                           //GENDER NEUTRAL SCHOOL GETS 1/2 PTS FOR GENDER NEUTRAL SPORT
              // $curgirls=($points/2)+$regpoints;
         }
      }
      $girls+=$curgirls;
   	//BOYS POINTS
      $curboys=0;
      if($sch[gender]!='girls')     //NOT A GIRLS ONLY SCHOOL
      {
         if($row[gender]=="Boys") $curboys=$points+$regpoints;        //GET ALL THE POINTS FOR A BOYS SPORT
         else if($row[gender]=='')
         {
            //if($sch[gender]=="boys")      //BOYS ONLY SCHOOL GETS ALL POINTS FOR GENDER NEUTRAL SPORT
               $curboys=$points+$regpoints;
            //else                           //GENDER NEUTRAL SCHOOL GETS 1/2 PTS FOR GENDER NEUTRAL SPORT
              // $curboys=($points/2)+$regpoints;
         }
      }
      $boys+=$curboys;
   	//TOTAL POINTS
      $total+=($points+$regpoints);
   }

   $girls+=$sch[adjustptsgirls];
   $boys+=$sch[adjustptsboys];
   $total+=$sch[adjustpts];
   $sql="UPDATE cupschools SET girlspoints='$girls',boyspoints='$boys',allpoints='$total' WHERE schoolid='$schoolid'";
   $result=mysql_query($sql);
   return;
}
function GetCupHeader($class="",$spreadsheet=FALSE,$gender="")
{
   //RETURN HEADER THE NSAA CUSTOMIZED FOR THIS CLASS
   $sql="SELECT * FROM cupclasssettings WHERE class='$class'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($spreadsheet)    return $row['ssheadertext'];
   else if($gender=='boys') return $row['bheadertext'];
   else if($gender=='girls') return $row['gheadertext'];
   return $row['headertext'];
}
function WriteFBExports($school='')
{
   require_once($_SERVER['DOCUMENT_ROOT']."/calculate/functions.php");

   if($school=='') return FALSE;

   $school2=addslashes($school);
   $sql="SELECT * FROM headers WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $schoolid=$row['id'];
   if(mysql_num_rows($result)==0) return FALSE;
   $enroll=$row[enrollment];
   $sid=GetSID2($school,'fb');
   $sql="SELECT * FROM fbschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row2=mysql_fetch_array($result);
   if($row2[mascot]!='') $mascot=$row2[mascot];
   else $mascot=$row[mascot];
   if($row2[colors]!='') $colors=$row2[colors];
   else $colors=$row['color_names'];
   if($row2[coach]!='') $coach=$row2[coach];
   $class=$row2['class'];
   $csv.="\"School:\",\"".GetSchoolName($sid,'fb',date("Y"))."\"\r\n\"Mascot:\",\"$mascot\"\r\n\"Colors:\",\"$colors\"\r\n\"Class:\",\"$class\"\r\n";

   //Get Head Coach if don't have it already
   if(!$coach || $coach=="")
   {
      $sql="SELECT * FROM logins WHERE level=3 AND school='$school2' AND sport LIKE 'Football%'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $coach=$row['name']; 
   }
   //Rest of FB staff
   $sql="SELECT * FROM fb_staff WHERE school_id='$schoolid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $asst_coaches=$row['asst_coaches'];
   $trainers=$row['ath_trainers'];
   $managers=$row['managers'];
   $csv.="\r\n\"Playoff Roster:\"\r\n"; $csv1=$csv; $csv2=$csv;
   $csv1.="\"Starter\",\"Medalist\",\"Light Jersey No\",\"Dark Jersey No\",\"Player Name\",\"Pronunciation\",\"Grade\",\"Off Posn/Def Posn\",\"Height\",\"Weight\"\r\n";
   $csv2.="\"Light Jersey No\",\"Dark Jersey No\",\"Player Name\",\"Grade\",\"Off Posn/Def Posn\",\"Height\",\"Weight\"\r\n";
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM fb_state AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t1.jersey_lt";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $year=GetYear($row[semesters]);
      $height=preg_replace("/-/","'",$row[10])."\"\"";
      if($row[6]=='y') $start="S";
      else $start="";
      if($row[7]=='y') $medal="M";
      else $medal="";
      if(trim($row[nickname])!='') $row[first]=$row[nickname];
      $csv1.="\"$start\",\"$medal\",\"$row[4]\",\"$row[5]\",\"$row[first] $row[last]\",\"$row[3]\",\"$year\",\"$row[8]/$row[9]\",\"$height\",\"$row[11]\"\r\n";
      $csv2.="\"$row[4]\",\"$row[5]\",\"$row[first] $row[last]\",\"$year\",\"$row[8]/$row[9]\",\"$height\",\"$row[11]\"\r\n";
      $sql2="UPDATE fb_state SET submitted='".time()."' WHERE id='$row[id]'";
      $result2=mysql_query($sql2);
   }
   //ADD SEASON GAMES AND SCORES TO CSV FILE:
   $year=GetFallYear('fb');
   $csv="\"Games\"\r\n\"Opponent\",\"Score\",\"Opp. Score\",\"Extra\",\r\n";
   $sched=GetSchedule($sid,'fb',$year,TRUE,FALSE,TRUE);
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
      {
         $csv.="\"".GetSchoolName($sched[oppid][$i],'fb',$year)."\",";
         $score=explode("-",$sched[score][$i]);
         $csv.="\"$score[0]\",\"$score[1]\",\"".$sched[extra][$i]."\"\r\n";
      }
   }
   //add playoff games to CSV file:
   for($i=0;$i<count($opp);$i++)
   {
      if($opp[$i]!="Choose Opponent")
      {
         $sql="SELECT school FROM headers WHERE id='$opp[$i]'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $csv.="\"$row[0]\",\"$score[$i]\",\"$opp_score[$i]\"\r\n";
      }
   }
   $csv.="\r\n\"Head Coach:\",\"$coach\"\r\n\"Assistant Coaches:\",\"$asst_coaches\"\r\n\"Athletic Trainers:\",\"$trainers\"\r\n";
   $csv.="\"Managers:\",\"$managers\"\r\n\"NSAA Enrollment:\",\"$enroll\"\r\n";
   $sql="SELECT * FROM fbschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $csv.="\"Number of Times in Playoffs:\",\"$row[tripstostate]\"\r\n\"Most Recent State Tournament Appearance:\",\"$row[mostrecent]\"\r\n";
   $csv.="\"State Championship Years:\",\"$row[championships]\"\r\n\"State Runner-Up Years:\",\"$row[runnerup]\"\r\n";
   $csv1.=$csv;
   $csv2.=$csv;
   $sch=strtolower(preg_replace("/[^0-9a-zA-Z]/","",$school));
   $file1=$sch."footballstateannc";
   $file2=$sch."footballstatedan";
   
   
   writefile("/home/nsaahome/attachments/$file1.csv",$csv1 );
   writefile("/home/nsaahome/attachments/$file2.csv",$csv2 );
   
   //exit;
   /*
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$file1.csv"),"w");
   fwrite($open,$csv1);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$file1.csv");
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$file2.csv"),"w");
   fwrite($open,$csv2);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$file2.csv");
 */
}
function CoachOrDirector($activity="")
{
   require 'variables.php';
   for($i=0;$i<count($staffs);$i++)
   {
      if($staffs[$i]==$activity) return $staffs_cd[$i];
   }
   return "";
}
function GetDateSelectOptions($mdy="DD",$curvalue="",$min=0,$max=0)
{
   //Return the <option>'s for a <select> that is for a month, day or year, with $curvalue selected
   if(!$min)
   {
      if($mdy=="DD" || $mdy=="MM") $min=1;
      else $min=date("Y")-1;
   }
   if(!$max)
   {
      if($mdy=="DD") $max=31;
      else if($mdy=="MM") $max=12;
      else $max=date("Y")+1;
   }
   if($mdy=="DD" || $mdy=="MM") $default="00";
   else $default="0000";
   $string="<option value=\"$default\">$mdy</option>";
   for($i=$min;$i<=$max;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      $string.="<option value=\"$m\"";
      if($curvalue==$m) $string.=" selected";
      $string.=">$m</option>";
   }
   return $string;
}
if (function_exists('sendsemails')) {
    
} else {
	
   
	function sendsemails($session,$annid,$recipients=''){
	
		$recips=explode("<recipient>",$recipients);
		$replytoname="NSAA";
		 $sql="SELECT * FROM messages WHERE id='$annid'";
		$result=mysql_query($sql);
		if(mysql_num_rows($result)==0)
		{
		   
		}
		else
		{
		   $row=mysql_fetch_array($result);
		   $replyto=$row[fromemail];
		   if(trim($replyto)=="") $replyto="nsaa@nsaahome.org";
		   $title=$row[title]; 
		   $announcement=$row[message];  
		   $linkname=$row[linkname]; 
		   $email_text="The following message has been posted by the NSAA:\r\n\r\n".$announcement;   
		   if($row[filename]!='')
			  $email_text.="\r\n\r\nA file was attached to this announcement.  Please login at https://secure.nsaahome.org/nsaaforms/ to view the attachment under \"Messages\".  Thank You!";
		  
		   $email_html=str_replace("\r\n","<br>",$email_text);   
		   $email_html=str_replace("https://secure.nsaahome.org/nsaaforms/","<a href=\"https://secure.nsaahome.org/nsaaforms/\">https://secure.nsaahome.org/nsaaforms/</a>",$email_html);
		   $attm=array(); $ct=0;
		   
		   for($i=0;$i<count($recips);$i++)
		   {
			   
			   if(($ct%1)==0) sleep(1);
			   
			    $To=$recips[$i]; $ToName=$To; 
			  
			  if(trim($To)!='' ) 
			  {
				 
				SendMail($replyto,$replytoname,$To,$ToName,$title,$email_text,$email_html,$attm);
			 $ct++;
			  }
		   }
		   //echo "$ct emails sent!";
			}
	}


}



if (function_exists('wildcardsendsemails')) {
    
} else {
	
   
	function wildcardsendsemails($session,$from,$recipients='',$title,$email_html,$attm=array()){

			$email_html=ereg_replace("`","'",$email_html);
            $email_html=str_replace("~",'""',$email_html);
			$email_text=ereg_replace("<br>","\r\n",$email_html);
			$email_text=strip_tags($email_html);
			
			

			$recips=explode("<recipient>",$recipients);
			$ct=0;
			for($i=0;$i<count($recips);$i++)
			{
			   $To=$recips[$i]; $ToName=$To;
			   if(trim($To)!='' && $To!="agaffigan@gazelleincorporated.com" && $To!="run7soccer@aim.com") 
			   {
				  SendMail($from,"NSAA",$To,$ToName,$title,$email_text,$email_html,$attm);
				  sleep(1);
				  $ct++;
			   }
			}
	}


}


?>
