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
require 'variables.php';

//if(file_exists("error_log")) system("rm error_log");

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

function CanTakeSupTestOnline($offid,$sport)
{
   if(HasPaid($offid,$sport)) return TRUE;
   else return FALSE;
	/*
   $sql="SELECT * FROM test2officials WHERE sport='$sport' AND offid='$offid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) return TRUE;
   else return FALSE;
	*/
}
function FormatPhone($phone,$schooldir=NULL)
{
   require 'variables.php';
   if($schooldir)	//phone nums in ###-###-####x### format
   {
      //GET MAIN PHONE OF THIS SCHOOL
      $sql="SELECT * FROM $db_name.headers WHERE school='".addslashes($schooldir)."'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $ph=split("-",$row[phone]);
      $mainareacode=$ph[0];
      $mainph="(".$ph[0].")".$ph[1]."-".$ph[2];	//MAIN SCHOOL PHONE
      $ph=split("-",$phone); 
      if(count($ph)==4 || strlen($ph[1])==3) //AREA CODE GIVEN
      {
         $area=$ph[0];
         if(trim($area)=="") $area=$mainareacode;
         $pre=$ph[1];
         $post=$ph[2];
         $ext=$ph[3];
      }
      else if(count($ph)==3) //USE MAIN AREA CODE
      {
         $area=$mainareacode;
         $pre=$ph[0];
         $post=$ph[1];
         $ext=$ph[2];
      }
      if(trim($pre)=="" || trim($post)=="") 
         $userph=$mainph;
      else
         $userph="(".$area.")".$pre."-".$post;
      if(trim($ext)!='')
         $userph.=" x".$ext;
      return $userph;
   }
   else
   {
      $phone=ereg_replace("[^0-9]","",$phone);
      if($phone=="") return $phone;
      $newphone="(".substr($phone,0,3).")".substr($phone,3,3)."-".substr($phone,6,4);
      return $newphone;
   }
}
function ValidUser($session,$type=NULL)
{
//return true if user is valid, false otherwise
    $sql="SELECT * FROM sessions WHERE session_id='$session'";
    $sql.=($type==NULL)?" AND type is NULL":" AND type='judge'";
//    echo $sql;die();
    $result=mysql_query($sql);
    if(mysql_num_rows($result)==0)
        return false;
    else return true;
}
function GetOffID($session)
{
   $sql="SELECT t1.offid FROM logins AS t1, sessions AS t2 WHERE t2.login_id=t1.id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
}
function GetJudgeID($session)
{
   $sql="SELECT t1.offid FROM logins_j AS t1, sessions AS t2 WHERE t2.login_id=t1.id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
}
function GetOffClass($offid,$sport,$year="")
{
   //RETURN CLASSIFICATION FOR $offid in $sport
   if($year=="") 
   {
      $year=date("Y");
      if(date("m")<6) $year--;
   }
   $year1=$year+1;
   $regyr="$year-$year1";
   $sql="SELECT * FROM ".$sport."off_hist WHERE offid='$offid' AND regyr='$regyr'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row['class'];
}
function GetOffName($offid)
{
   require 'variables.php';
   $sql="SELECT first,middle,last FROM $db_name2.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $name="$row[first] $row[middle] $row[last]";
   return $name;
}
function GetJudgeName($offid)
{
   $sql="SELECT first,middle,last FROM judges WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $name=trim($row[first])." ".trim($row[last]);
   if($offid==0 || $offid=="" || !$offid)
      $name="NSAA";
   return $name;
}
function GetSportName($sport)
{
   if($sport=="wr") return "Wrestling";
   if($sport=="vb") return "Volleyball";
   if($sport=="ubo") return "Unified Bowling";
   if($sport=='sp') return "Speech";
   if($sport=='pp') return "Play Production";
   if($sport=='judge' || $sport=='sppp') return "Speech/Play Production";
   if($sport=='bbb' || $sport=='bb_b') return "Boys Basketball";
   if($sport=='bbg' || $sport=='bb_g') return "Girls Basketball";
   if($sport=='so_b' || $sport=='bsoc' || $sport=='sob') return "Boys Soccer";
   if($sport=='so_g' || $sport=='gsoc' || $sport=='sog') return "Girls Soccer";
   if($sport=='tr_b' || $sport=='trb') return "Boys Track";
   if($sport=='tr_g' || $sport=='trg') return "Girls Track";
   if($sport=='cc_b' || $sport=='ccb') return "Boys Cross-Country";
   if($sport=='cc_g' || $sport=='ccg') return "Girls Cross-Country";
   if($sport=='cc') return "Cross-Country";
   if($sport=='te_b' || $sport=='teb') return "Boys Tennis";
   if($sport=='te_g' || $sport=='teg') return "Girls Tennis";
   if($sport=='sw_b') return "Boys Swimming";
   if($sport=='sw_g') return "Girls Swimming";
   if($sport=="gob" || $sport=='go_b') return "Boys Golf";
   if($sport=="gog" || $sport=='go_g') return "Girls Golf";
   if($sport=="sb" ) return "Softball";
   if($sport=='go') return "Golf";
   if($sport=='ad') return "AD's";
      require 'variables.php';
   for($i=0;$i<count($activity);$i++)
   {
      if($activity[$i]==$sport)
	return $act_long[$i];
   }
   return "Sport";
}
function GetSpeechEvent($event)
{
   require 'variables.php';
   for($i=0;$i<count($spevents);$i++)
   {
      if($event==$spevents[$i])
      {
	 $eventname=$spevents2[$i];
 	 $i=count($spevents);
      }
   }
   return $eventname;
}
function GetObsID($session)
{
   $sql="SELECT t1.obsid,t1.name FROM logins AS t1, sessions AS t2 WHERE t2.login_id=t1.id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[1]=="NSAA") $row[0]=1;
   return $row[0];
}
function GetObsName($obsid)
{
   $sql="SELECT first,last FROM observers WHERE id='$obsid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $obsname=$row[0]." ".$row[1];
   if($obsid=="1") $obsname="NSAA";
   return $obsname;
}
function TableExists($tablename)
{
   $sql="SHOW TABLES LIKE '$tablename'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return false;
   else return true;
}
function GetOffHeader($session)
{
     $logo="NSAAheaderLogin.jpg";
    $string="<table width=\"100%\" cellspacing=0 cellpadding=0>";
    $string.="<tr align=center><td colspan=20>";
    $string.="<img src=\"$home/images/$logo\" width=\"800px\">";
    $string.="</td></tr>";
    return $string;
}

function GetHeader($session,$page="home")
{
  //get level of user
  $level=GetLevel($session);
  if($level==1) { $colspan=2; $align="left"; }
  else { $colspan=1; $align="center"; }
     $logo="NSAAheaderLogin.jpg";
     $color="#ffffff";
     $string="<table width=\"100%\" cellspacing=0 cellpadding=0>";
     $string.="<tr><td bgcolor=#19204 height=\"10px\" colspan=$colspan>&nbsp;</td></tr>";
     $string.="<tr align=center>";
     $string.="<td align=center valign=top bgcolor=$color colspan=$colspan>";
     $string.="<img width=\"800px\" src=\"/images/$logo\"></td></tr>";
     $string.="<tr><td bgcolor=#19204f colspan=$colspan height=\"10px\"><center>";
     $string.="<a class=header style=\"color:#ffffff\" href=\"/nsaaforms/officials/welcome.php?session=$session\">Home</a>";
     $string.="&nbsp;&nbsp;&nbsp;<font style=\"color:#ffffff;\"><b>|&nbsp;&nbsp;&nbsp;</b></font>";
     $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/officials/logout.php?session=$session\">Logout</a>";
     $string.="</td></tr>";
     if($level==1)
     {
        $string.="<tr align=left><td><table cellspacing=0 cellpadding=5 border=1 bordercolor=#000000 width=100%>";
        $string.="<tr align=center>";
        $pages=array("welcome","officialsapp","manageoff","rulesmeetingadmin","testreport","test2report","schedadmin","apptooff","vote","contractadmin","reportcardsadmin","ejectionadmin","yellowcardadmin","obshome","duedates");
        $pages2=array("Home","Registration","Manage Officials","Rules Meetings","Part 1 Tests","Part 2 Tests","Schedules","Apps to Officiate","Ballots","Contracts & Assignments","Game Report Cards","Ejections","Yellow Cards","Observations","Due Dates");
        for($i=0;$i<count($pages);$i++)
        {
           $string.="<td";
           if($page==$pages[$i]) { $string.=" bgcolor=#000000"; $linkcolor="#FFFFFF"; }
           else { $string.=" bgcolor=#E0E0E0"; $linkcolor="#000000"; }
           $string.="><a class=header style=\"color:$linkcolor\" href=\"".$pages[$i].".php?session=$session\">".$pages2[$i]."</a></td>";
        }
        $string.="</tr></table></td></tr><tr align=center><td align=center>";
     }
     else $string.="<tr align=center><td align=center>";
   return $string;
}
function GetHeaderJ($session,$page="home")
{
  //get header for judges page
  //get level of user
  $level=GetLevelJ($session);
  if($level==1) { $colspan=2; $align="left"; }
  else { $colspan=1; $align="center"; }
     $logo="NSAAheaderLogin.jpg";
     $color="#ffffff";
     $string="<table width=100% cellspacing=0 cellpadding=0>";
     $string.="<tr><td bgcolor=#000000 colspan=$colspan>&nbsp;</td></tr>";
     $string.="<tr align=center>";
     $string.="<td valign=center bgcolor=$color colspan=$colspan>";
     $string.="<img src=\"/images/$logo\" width=\"800px\"></td></tr>";
     $string.="<tr><td bgcolor=#000000 colspan=$colspan><center>";
     $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/officials/jwelcome.php?session=$session\">Home</a>";
     $string.="&nbsp;&nbsp;&nbsp;<font color=#FFFFFF><b>|&nbsp;&nbsp;&nbsp;</b></font>";
     $string.="<a class=header style=\"color:#FFFFFF\" href=\"/nsaaforms/officials/jlogout.php?session=$session\">Logout</a>";
     $string.="</td></tr>";  
     if($level==1)
     {
        $string.="<tr align=left><td><table cellspacing=0 cellpadding=5 border=1 bordercolor=#000000 width=100%>";
        $string.="<tr align=center>";
        $pages=array("jwelcome","judgesreg","managejudge","jtestreport","apptojudge","jvote","jcontractadmin","statespeech","jduedates");
        $pages2=array("Home","Registration","Manage Judges","Online Tests","Apps to Judge","Ballots","Contracts","State Speech","Due Dates");
        for($i=0;$i<count($pages);$i++)
        {
           $string.="<td";
           if($page==$pages[$i]) { $string.=" bgcolor=#000000"; $linkcolor="#FFFFFF"; }
           else { $string.=" bgcolor=#E0E0E0"; $linkcolor="#000000"; }
           $string.="><a class=header style=\"color:$linkcolor\" href=\"".$pages[$i].".php?session=$session\">".$pages2[$i]."</a></td>";
        }
        $string.="</tr></table></td></tr><tr align=center><td align=center>";
     }
     else $string.="<tr align=center><td align=center>";
   return $string;
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
function GetDueDate($sport,$form)
{
//take form name and return due date like: May 5, 2004
   $table=$form."_duedates";
   if($form=="test") $field="test";
   else $field="sport";
   $sql="SELECT duedate FROM $table WHERE $field='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $duedate=$row[0];
   return $duedate;
}
function GetTestDueDate($sport,$field='duedate')
{
   $sql="SELECT $field FROM test_duedates WHERE test='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
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

function PastDue($due_date,$limit)
{ 
   //check if form is more than $limit days past due date
   if(ereg("-",$due_date))
   {
      $date=split("-",$due_date);
      $month=$date[1];
      $day=$date[2];
      $year=$date[0];
   }
   else
   {
      $date=split(" ",$due_date);
      $month=substr($date[0],0,3);
      $month=GetMonthNum($month);
      $day=substr($date[1],0,strlen($date[1])-1);
      $year=$date[2];
   }
   $date=mktime(0,0,0,$month,$day,$year);
   $today=time();
   $diff=$today-$date;		//difference in sec
   $diff=$diff/(60*60*24);	//difference in days
   $oneday=1;
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
function GetLevelJ($session)
{
   //get level of current user (judges)
   $sql="SELECT t2.level FROM sessions AS t1, logins_j AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $level=$row[0];
   return $level;
}

function GetName($session)
{
   //Get user's specifics from logins table using $session
   $sql="SELECT t2.name FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $name=$row[0];
   return $name;
}

function DueSoon($form_date,$period)
{
   if(!$period) $period=7;
   if(ereg("-",$form_date))
   {
      $due_date=split("-",$form_date);
      $month=$due_date[1];
      $day=$due_date[2];
      $year=$due_date[0];
   }
   else
   {
      $due_date=split(" ",$form_date);
      $month=substr($due_date[0],0,3);
      $month=GetMonthNum($month);
      $day=substr($due_date[1],0,strlen($due_date[1])-1);
      $year=$due_date[2];
   }
   $duedate=mktime(0,0,0,$month,$day,$year);
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
   $words=split(" ",$string);
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
      if(ereg("\(",$words[$i]) && strlen($words[$i])>2)	//capitalize word in ()
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

function Capitalize($string)
{
   //capitalizes first letter in every word in $string and lowercases the rest
   $strings=split(" ",$string);
   $newstr="";
   for($i=0;$i<count($strings);$i++)
   {
      $str1=substr($strings[$i],0,1);
      $str2=substr($strings[$i],1,strlen($strings[$i])-1);
      $newstr.=strtoupper($str1).strtolower($str2)." ";
   }
   return $newstr;
}

function Unique($string)
{
   //string is comma-delimited
   //return string with duplicates taken out
   $string=split(",",$string);
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
   $string=split(",",$string);
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
function JudgeIsRegistered($offid,$activity)
{
   //RETURN TRUE IF JUDGE IS REGISTERED FOR THIS YEAR IN THIS SPORT, else FALSE
   $sql="SELECT * FROM judges WHERE id='$offid' AND $activity='x'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function JudgeIsRegisteredLastYear($offid,$activity)
{
   //RETURN TRUE IF JUDGE IS REGISTERED FOR THIS YEAR IN THIS SPORT, else FALSE
   $sql="SELECT * FROM judges_last_year WHERE id='$offid' AND $activity='x'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function JudgeIsApplied($offid,$activity)
{  
   if ($activity=='speech')
   $sql="SELECT id FROM spapply WHERE offid='$offid'"; 
   else
   $sql="SELECT id FROM ppapply WHERE offid='$offid'"; 
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function IsRegistered2011($schoolid,$sport)
{
   if($sport=='sob') $sport='so_b';
   else if($sport=='sog') $sport='so_g';
   else if($sport=='ccg') $sport='cc_g';
   else if($sport=='ccb') $sport='cc_b';
   else if($sport=='bbb') $sport='bb_b';
   else if($sport=='bbg') $sport='bb_g';
   if($sport=='cc')
   {
      if(IsRegistered2011($schoolid,'cc_b') || IsRegistered2011($schoolid,'cc_g'))
         return true;
      else
         return false;
   }
   if($sport=='di')
   {
      if(IsRegistered2011($schoolid,'sw_b') || IsRegistered2011($schoolid,'sw_g'))
         return true;
      else
         return false;
   }
   else if($sport=='tr')
   {
      if(IsRegistered2011($schoolid,'tr_b') || IsRegistered2011($schoolid,'tr_g'))
         return true;
      else
         return false;
   }
   else if(ereg("fb",$sport)) $sport="fb";
   else if($sport=='de')
   {
      if(IsRegistered2011($schoolid,'de_ld') || IsRegistered2011($schoolid,'de_cx'))
         return true;
      else
         return false;
   }
   else if($sport=='im' || $sport=='vm') $sport="mu";
   $sql="SELECT * FROM nsaascores.schoolregistration WHERE schoolid='$schoolid' AND sport='$sport' AND datepaid!='0000-00-00'";
   if($sport=='cc')
      $sql="SELECT * FROM nsaascores.schoolregistration WHERE schoolid='$schoolid' AND (sport='cc_b' OR sport='cc_g') AND datepaid!='0000-00-00'";
   else if($sport=='so')
      $sql="SELECT * FROM nsaascores.schoolregistration WHERE schoolid='$schoolid' AND (sport='so_b' OR sport='so_g') AND datepaid!='0000-00-00'";
   else if(ereg("de",$sport))
      $sql="SELECT * FROM nsaascores.schoolregistration WHERE schoolid='$schoolid' AND (sport='de_ld' OR sport='de_cx') AND datepaid!='0000-00-00'";
   else if($sport=='sw')
      $sql="SELECT * FROM nsaascores.schoolregistration WHERE schoolid='$schoolid' AND (sport='sw_b' OR sport='sw_g') AND datepaid!='0000-00-00'";
   else if($sport=='tr')
      $sql="SELECT * FROM nsaascores.schoolregistration WHERE schoolid='$schoolid' AND (sport='tr_b' OR sport='tr_g') AND datepaid!='0000-00-00'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
function IsRegistered($school,$abbrev)
{
   //return true if school is registered for given activity, else false
   require '../variables.php';
   $school2=ereg_replace("\'","\'",$school);
   if(ereg("fb",$abbrev)) $abbrev="fb";
   for($i=0;$i<count($act_regi2);$i++)
   {
      if($act_regi2[$i]==$abbrev || ($abbrev=="sog" && $act_regi2[$i]=="so_g") || ($abbrev=="sob" && $act_regi2[$i]=="so_b") || ($abbrev=="bbb" && $act_regi2[$i]=="bb_b") || ($abbrev=="bbg" && $act_regi2[$i]=="bb_g") || ($abbrev=="mu" && ($act_regi2[$i]=="im" || $act_regi2[$i]=="vm")) || ($act_regi2[$i]=="jo" && ($abbrev=="np" || $abbrev=="yb")))
         $index=$i;
   }
   $field=ereg_replace(" ","_",$act_regi[$index]);
   $sql="SELECT * FROM $db_name.registration WHERE school='$school2' AND $field='x'";
   $result=mysql_query($sql);
   if($abbrev=='so' || $abbrev=='cc' || $abbrev=='go' || $abbrev=='bb' || $abbrev=='tr' || $abbrev=='te') //check boys OR girls
   {
      $abbrev1=$abbrev."_g"; $abbrev2=$abbrev."_b";
      for($i=0;$i<count($act_regi2);$i++)
      {
         if($act_regi2[$i]==$abbrev1)
            $index1=$i;
         else if($act_regi2[$i]==$abbrev2)
     	    $index2=$i;
      }
      $field1=ereg_replace(" ","_",$act_regi[$index1]);
      $field2=ereg_replace(" ","_",$act_regi[$index2]);
      $sql="SELECT * FROM $db_name.registration WHERE school='$school2' AND ($field1='x' OR $field2='x')";
      $result=mysql_query($sql);
   }
   $ct=mysql_num_rows($result);
   if($ct>0)
      return true;
   else
      return false;
}

function IsDeclared($school,$abbrev)
{
   require 'variables.php';
   //return TRUE if school is declared for given fall activity, else FALSE
   $school2=ereg_replace("\'","\'",$school);
   $sql="SELECT * FROM $db_name.declaration WHERE school='$school2' AND $abbrev='y'";
   if($abbrev=='cc')
      $sql="SELECT * FROM $db_name.declaration WHERE school='$school2' AND (cc_b='y' OR cc_g='y')";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0) return TRUE;
   else return FALSE;
}
function RiggedSendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles)
{
   require("../PHPMailer/mail.inc.php");
   // Instantiate your new class  
   $mail = new MyMailer;
   // Now you only need to add the necessary stuff  
   $mail->AddAddress($To, $ToName);
   //$mall->AddReplyTo("officials@iahsaa.org", "Laura Morlan");
   $mail->Subject = $Subject;
   $mail->IsHTML(true);
   $mail->Body = $Html;
   for($i=0;$i<count($AttmFiles);$i++)
   {
      $mail->AddAttachment($AttmFiles[$i], $AttmFiles[$i]);  // optional name  
   }
   if(!$mail->Send())
      return false;
   return true;
}
function SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles)
{
 
  require 'variables.php';
  
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
		   $mail->AddAddress($To, $ToName);
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
	}		
  $Subject=ereg_replace("\'","\'",$Subject);
  $time=date('r');
  $sql="INSERT INTO $db_name.maillog (recipient,subject,time) VALUES ('$To','$Subject','$time')";
  $result=mysql_query($sql);
  return $dump;
}

function GetTeamCode($co_op,$sport)
{
   //returns unique team code for group of schools co-oping together
   /*
   $code=0;
   $schools=split("/",$co_op);
   for($i=0;$i<count($schools);$i++)
   {
      $schools[$i]=ereg_replace("\'","\'",$schools[$i]);
      $sql="SELECT id FROM headers WHERE school='$schools[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $code+=$row[0];
   }
   if($code==0) $code=rand(1000,10000);
   $sql="SELECT * FROM headers WHERE id='$code'";
   $result=mysql_query($sql);
   while(mysql_num_rows($result)>0)
   {
      $code++;
      $result=mysql_query($sql);
   }
   */
   $co_op2=ereg_replace("\'","\'",$co_op);
   $sql="SELECT * FROM coop_schools WHERE coopname='$co_op2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $schools=split("/",$co_op2);
      $sql2="INSERT INTO coop_schools (school1,school2,coopname,sport) VALUES ('$schools[0]','$schools[1]','$co_op2','$sport')";
      $result2=mysql_query($sql2);
   }
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $code=$row[0];
   $code="CO".$code;
   return $code;
}

function CleanupFiles()
{
   $now=time();
   $fivehours=5*60*60;
   if($open=opendir('/home/nsaahome/reports'))
   {
      while(false !==($file=readdir($open)))
      {
         if(($now-fileatime("/home/nsaahome/reports/$file"))>=$fivehours && !ereg("pdf",$file))
         {
            citgf_unlink("/home/nsaahome/reports/$file");
         }
      }
   }
   if($open=opendir('output'))
   {
      while(false !==($file=readdir($open)))
      {
         if(($now-fileatime("output/$file"))>=$fivehours)
         {
            citgf_unlink("output/$file");
         }
      }
   }
   else return FALSE;
}

function CleanCurrency($amount)
{
   //return clean version of $amount (no $ or ,)
   $newamount=ereg_replace("[$]","",$amount);
   $newamount=ereg_replace(",","",$newamount);
   return $newamount;
}

function DoesQualify($event,$mark)
{
   //determine if mark meets state swim meet qualifications
   //return Automatic if meets auto qualifier
   //return Secondary if meets secondary qualifier
   //return no if meets neither
   //see sw_qualify table for qualifying times
   if(ereg("Diving",$event))	//Diving event
   {
      if($mark<300) 
	 return "no";
      else 
	 return "Automatic";
   }
   if(ereg("[:]",$mark))	//mark is in min:sec.tenths format
   {
      $sql="SELECT qualmark,automark FROM sw_qualify WHERE eventfull='$event'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $second=split("[:.]",$row[0]); 
      $auto=split("[:.]",$row[1]);
      $mark2=split("[:.]",$mark);
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
   $tempmark=split("[:.]",$mark);
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

function GetEventAbbrev($eventlong)
{
   //return abbreviation for speech event (used in judgespick2.php)
   require 'variables.php';
   for($i=0;$i<count($prefs_lg);$i++)
   {
      if($eventlong==$prefs_lg[$i])
         return $prefs_sm2[$i];
   }
   return "?";
}

function GeneratePasscode($lastname,$official)
{
   //Generate passcode for official/judge
   if($official==1) $table="logins";
   else $table="logins_j";

   //New Passcode=first 6 letters of last name, then random 4 digit number
   $lastpart=substr(ereg_replace("[^a-zA-Z]","",$lastname),0,6);
   $num=rand(1000,9999);
   $passcode=$lastpart.$num;
   //Ensure that passcode is not already in use
   $sql2="SELECT * FROM $table WHERE passcode='$passcode'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $num=rand(1000,9999);
      $passcode=$lastpart.$num;
      $sql2="SELECT * FROM $table WHERE passcode='$passcode'";
      $result2=mysql_query($sql2);
   }
   return $passcode;
}

function GetSchoolYear($year="",$month="")
{
   if($year=="") $year=date("Y");
   if($month=="") $month=date("m");
   //get current school year in yyyy-yyyy format
   if($month<6)
      $year1=$year-1;
   else
      $year1=$year;
   $year2=$year1+1;
   $regyr=$year1."-".$year2;
   return $regyr;
}

function HasPaid($offid,$sport,$database="")
{
   require 'variables.php';
   if($database="") $database=$db_name2;
   if($sport=='any')	//check if official has paid for ANY sport
   {
      $paid=0;
      for($i=0;$i<count($activity);$i++)
      {
         $table=$activity[$i]."off";
         $sql2="SELECT * FROM $database.$table WHERE offid='$offid' AND payment!=''";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>0)
            $paid=1;
      }
      if($paid==1) return TRUE;
      else return FALSE;
   }
   else
   {
      $table=$sport."off";
      $sql="SELECT * FROM $database.$table WHERE offid='$offid' AND payment!=''";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
         return TRUE;
      else
	 return FALSE;
   }
}
function GetObservations($year,$sport,$offlast,$offfirst,$sort="t1.last,t1.first,t2.dateeval",$obsid=0)
{
   require 'variables.php';
   if($year=="this")  $dbname=$db_name2;
   else $dbname=$db_name2.$year;
   $obstable=$sport."observe";
   $schedtable=$sport."sched";
   if(preg_match("/clinic/",$sport))
   {
      $sql="SELECT DISTINCT t2.*,t1.first,t1.last FROM $dbname.officials AS t1, $dbname.$obstable AS t2 WHERE t1.id=t2.offid AND t2.dateeval!='' ";
      if(trim($offlast)!="") $sql.="AND (t1.last LIKE '$offlast%' OR t2.official LIKE '%$offlast%') ";
      if(trim($offfirst)!="") $sql.="AND (t1.first LIKE '$offfirst%' OR t2.official LIKE '%$offfirst%') ";
      //if($obsid>0) $sql.="AND t2.obsid='$obsid' ";
      $sql.="ORDER BY $sort";
   }
   else
   {
      $sql="SELECT DISTINCT t2.*,t1.first,t1.last,t3.offdate AS clinicdate FROM $dbname.officials AS t1, $dbname.$obstable AS t2,$dbname.$schedtable AS t3 WHERE t2.gameid=t3.id AND t1.id=t2.offid AND t2.dateeval!='' ";
      if($sport=='bb' && $year!="20052006")
         $sql="SELECT DISTINCT t2.*,t1.first,t1.last,t3.offdate AS clinicdate FROM $dbname.officials AS t1, $dbname.$obstable AS t2,$dbname.$schedtable AS t3 WHERE t2.gameid=t3.id AND (t1.id=t2.offid OR t1.id=t2.offid2 OR t1.id=t2.offid3) AND t2.dateeval!='' ";
      if(trim($offlast)!="") $sql.="AND t1.last LIKE '$offlast%' ";
      if(trim($offfirst)!="") $sql.="AND t1.first LIKE '$offfirst%' ";
      //if($obsid>0) $sql.="AND t2.obsid='$obsid' ";
      $sql.="ORDER BY $sort";
   }
//echo $sql."<br>";
   $result=mysql_query($sql);
   $getobs=array(); $ix=0;
//if(mysql_error()) echo "$sql<br>".mysql_error();
   while($row=mysql_fetch_array($result))
   {
      $getobs[offid][$ix]=$row[offid];
      $getobs[offfirst][$ix]=$row[first]; $getobs[offlast][$ix]=$row[last];
      $getobs[official][$ix]=$row[official];
      $getobs[gameid][$ix]=$row[gameid];
      $getobs[gamedate][$ix]=$row[clinicdate];
      $getobs[location][$ix]=$row[location];
      if($sport=='bb')
         $getobs[postseasongame][$ix]=$row[postseasongame];
      $getobs[obsid][$ix]=$row[obsid];
      if($year=="20052006")
      {
         $sql3="SELECT name FROM $dbname.logins WHERE id='$row[obsid]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $obsname=$row3[name];
      }
      else
      {
         if($row[obsid]==1) 
	 {
	   $obsname="NSAA"; $obsemail=""; $obsphone="";
	 }
	 else
	 {
         $sql3="SELECT * FROM $dbname.observers WHERE id='$row[obsid]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $obsname=$row3[first]." ".$row3[last];
	 if($row[obsid]==1) $obsname="NSAA";
	 $obsemail=$row3[email];
	 if($row3[workph]!='') $obsphone=$row3[workph];
	 else if($row2[cellph]!='') $obsphone=$row3[cellph];
	 else $obsphone=$row3[homeph];
         }
      }
      $getobs[obsname][$ix]=$obsname;
      $getobs[home][$ix]=$row[home];
      $getobs[visitor][$ix]=$row[visitor];
      $getobs[dateeval][$ix]=$row[dateeval];
      $getobs[obsemail][$ix]=$obsemail;
	$getobs[obsphone][$ix]=$obsphone;
      $ix++;
   }
   return $getobs;
}
function GetOffContracts($sport,$offid,$session,$database="")
{
   require 'variables.php';
   if($database=="") $database=$db_name2;
   $database2=preg_replace("/officials/","scores",$database);
   $fallyear=substr(preg_replace("/nsaaofficials/","",$database),0,4);

   //return array with info on contracts for this official in this sport
   $districts=$sport."districts";
   $disttimes=$sport."disttimes";
   $contracts=$sport."contracts";
   $brackets=$sport."brackets";
   $sql="SHOW TABLES LIKE '$contracts'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) 
      return false;

   $contract=array(); $ix=0;
   if($sport=='fb')
   {
      $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
      for($i=0;$i<count($rounds);$i++)
      {
         if($fallyear<=2006)
	    $sql="SELECT DISTINCT t1.post AS offpost,t1.accept AS offaccept,t1.confirm AS offconfirm,t2.id,t2.class,t2.round,t2.school1,t2.school2,t2.day,t2.gamenum FROM $database.$contracts AS t1, $database.$brackets AS t2 WHERE t1.gameid=t2.id AND t1.offid='$offid' AND t2.round='$rounds[$i]' AND t1.post='y' ORDER BY t2.day";
         else 
	    $sql="SELECT DISTINCT t1.post AS offpost,t1.accept AS offaccept,t1.confirm AS offconfirm,t2.id,t2.class,t2.round,t2.sid1,t2.sid2,t2.day,t2.gamenum FROM $database.$contracts AS t1, $database.$brackets AS t2 WHERE t1.gameid=t2.id AND t1.offid='$offid' AND t2.round='$rounds[$i]' AND t1.post='y' ORDER BY t2.day";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
	    $contract[url][$ix]="fbcontract.php?session=$session&gameid=$row[id]";
		//GET TEAMS (Different before 2012-13 than 2012-13 and on)
	    if($fallyear<=2006)
	    {
	       $school1=$row[school1]; $school2=$row[school2];
	    }
	    else
	    {
	       if($fallyear<=2012)
	       {
	          $sid1=$row[sid1]; $sid2=$row[sid2];
	       }   
	       else
	       {
	          $sql2="SELECT * FROM $database2.fbsched WHERE class='$row[class]' AND received='$row[day]' AND gamenum='$row[gamenum]'";
	          $result2=mysql_query($sql2);
	          $row2=mysql_fetch_array($result2);
	          $sid1=$row2[sid]; $sid2=$row2[oppid];
	       }
	       $sql2="SELECT * FROM $database2.fbschool WHERE sid='$sid1'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $school1=$row2[school];
               $sql2="SELECT * FROM $database2.fbschool WHERE sid='$sid2'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
	       $school2=$row2[school];
	    }
  	    $contract[linktitle][$ix]="Class $row[class], $row[round] ($school1 vs. $school2)";
	    if($row[round]=="First Round") $rabbrev="1ST";
      	    else if($row[round]=="Second Round") $rabbrev="2ND";
	    else if($row[round]=="Quarterfinals") $rabbrev="QTR";
	    else if($row[round]=="Semifinals") $rabbrev="SEMI";
	    else if($row[round]=="Finals") $rabbrev="FINAL";
	    else $rabbrev=$row[round];
	    $contract[abbrev][$ix]="$row[class]-$rabbrev";
  	    $contract[post][$ix]=$row[offpost];
	    $contract[accept][$ix]=$row[offaccept];
	    $contract[confirm][$ix]=$row[offconfirm];
	    $ix++;
         }
      }
      return $contract;
   }
   else if($sport=='wr')
   {
      $types=array("District","State","State Dual");
      for($i=0;$i<count($types);$i++)
      {
         $sql="SELECT DISTINCT t1.post AS offpost,t1.accept AS offaccept,t1.confirm AS offconfirm,t2.* FROM $database.$contracts AS t1, $database.$districts AS t2 WHERE t1.distid=t2.id AND t1.offid='$offid' AND t2.type='$types[$i]' AND t1.post='y' ORDER BY t2.class,t2.district";
         $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
	 {
	    if($types[$i]=="District")
	       $contract[url][$ix]="wrcontract.php?session=$session&distid=$row[id]";
            else if($types[$i]=="State")
               $contract[url][$ix]="wrstatecontract.php?session=$session&distid=$row[id]";
	    else
	       $contract[url][$ix]="wrstatedualcontract.php?session=$session&distid=$row[id]";
	    $contract[linktitle][$ix]="$row[type]";
            if($row[type]=="State" || $row[type]=="State Dual") $contract[linktitle][$ix].=" Tournament";
	    else $contract[linktitle][$ix].=" $row[class]-$row[district]";
	    $contract[abbrev][$ix]="$row[type] $row[class]-$row[district]";
	    $contract[post][$ix]=$row[offpost];
	    $contract[accept][$ix]=$row[offaccept];
	    $contract[confirm][$ix]=$row[offconfirm];
	    $ix++;
	 }
      }
      return $contract;
   }
   else
   {
      if(ereg("bb",$sport))
	 $types=array("District","Subdistrict","District Final","State");
      else if(ereg("so",$sport) || $sport=='vb')
         $types=array("District","Subdistrict","District Final","Substate","State");
      else 
         $types=array("District","State");
      for($i=0;$i<count($types);$i++)
      {
	 $sql="SELECT DISTINCT t1.post AS offpost,t1.accept AS offaccept,t1.confirm AS offconfirm,t3.* FROM $database.$contracts AS t1, $database.$disttimes AS t2, $database.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='$types[$i]' AND t1.offid='$offid' AND t1.post='y' ORDER BY t3.class,t3.district";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
	 {
	    $contract[url][$ix]=$sport;
            if($row[type]=='State') $contract[url][$ix].="state";
	    $contract[url][$ix].="contract.php?session=$session&distid=$row[id]";
	    $contract[linktitle][$ix]=$row[type];
	    if($row[type]=="State") $contract[linktitle][$ix].=" Tournament";
	    else $contract[linktitle][$ix].=" $row[class]-$row[district]";
	    $contract[abbrev][$ix]="$row[type] $row[class]-$row[district]";
	    $contract[post][$ix]=$row[offpost];
	    $contract[accept][$ix]=$row[offaccept];
	    $contract[confirm][$ix]=$row[offconfirm];
	    $ix++;
	 } 
      }
      return $contract;
   } 
}
function CountVotes($sport,$offid,$adcoach)
{
   $table=$sport."_votes";
   $sql="SELECT * FROM $table WHERE officialid='$offid'";
   if($adcoach=='ad' OR $adcoach=='coach')
      $sql.=" AND ad_coach='$adcoach'";
   $result=mysql_query($sql);
   return mysql_num_rows($result);
}
function IsReportCardOff($offid)
{
   return FALSE;
   if($offid=='3427' || $offid=='4080' || $offid=='4679' || $offid=='3890' || $offid=='4439' || $offid=='4901' || $offid=='4758' || $offid=='6310' || $offid=='4507' || $offid=='3686' || $offid=='4365' || $offid=='4765' || $offid=='3975')
      return TRUE;
   else
      return FALSE;
}
function IsReportCardSchool($school)
{   
   return FALSE;
   if($school=="Test's School" || $school=='Schuyler' || $school=="Gering" || $school=="Omaha Skutt Catholic" || $school=="Valentine" || $school=="Tekamah-Herman" || $school=="David City" || $school=="Bishop Neumann" || $school=="Wahoo" || $school=="Mitchell" || $school=="Malcolm" || $school=="Thayer Central" || $school=="Neligh-Oakdale" || $school=="Newman Grove" || $school=="Freeman" || $school=="Bancroft-Rosalie" || $school=="Pope John" || $school=="Wausa" || $school=="Brady" || $school=="Falls City Sacred Heart" || $school=="Dodge")
      return TRUE;
   else
      return FALSE;
}
function IsDateOfRulesMtg($link_date,$sport)
{
   $table=$sport."ruleshosts";
   $sql="SELECT * FROM $table WHERE mtgdate='$link_date'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
      return TRUE;
   else
      return FALSE;
}
function IsDateOfSupTest($link_date)
{
   $table="suptesthosts";
   $sql="SELECT * FROM $table WHERE mtgdate='$link_date'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
      return TRUE;
   else
      return FALSE;
}
function IsAffiliateOff($offid)
{
   $sql="SELECT state FROM officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(strtoupper($row[state])=="NE")
      return FALSE;
   else
      return TRUE;
}
function OfficiatedDistricts($offid,$insince,$database,$sport)
{
   require 'variables.php';
   $table=$sport."contracts";
   $sql="SHOW TABLES LIKE '$table'";
   $result=mysql_query($sql);
   $genders=0;
   if(mysql_num_rows($result)==0)	//try boys/girls table
   {
      $sql="SHOW TABLES LIKE '".$sport."gcontracts'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0) return FALSE;
      else 
      {
         $genders=1;
         $table=$sport."bcontracts";
         $table2=$sport."gcontracts";
      }
   }
   if($insince=="IN")
   {
      $sql2="SELECT * FROM $database.$table WHERE offid='$offid' AND accept='y' AND confirm='y'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)>0) //official was contracted to officiate in this year for this sport
         return TRUE; 
      if($genders==1)
      {
         $sql2="SELECT * FROM $database.$table2 WHERE offid='$offid' AND accept='y' AND confirm='y'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if(mysql_num_rows($result2)>0) //official was contracted to officiate in this year for this sport
            return TRUE;
      }
   }
   else        //SINCE
   {
      $sql2="SHOW DATABASES LIKE '$db_name2%'";
      $result2=mysql_query($sql2);
      $d=0;
      while($row2=mysql_fetch_array($result2))
      {
         if($database==$row2[0])
            $d="X";
         if($d==0 || $d=="X")  //always take "$db_name2" or any database equal to or after $yeardist
         {
            $sql3="SELECT * FROM $row2[0].$table WHERE offid='$offid' AND accept='y' AND confirm='y'";
            $result3=mysql_query($sql3);
            $row3=mysql_fetch_array($result3);
            if(mysql_num_rows($result3)>0) //official was contracted to officiate in this year for this sport
               return TRUE; 
	    if($genders==1)
	    {
               $sql3="SELECT * FROM $row2[0].$table2 WHERE offid='$offid' AND accept='y' AND confirm='y'";
               $result3=mysql_query($sql3);
               $row3=mysql_fetch_array($result3);
               if(mysql_num_rows($result3)>0) //official was contracted to officiate in this year for this sport
                  return TRUE;
	    }
         }
         $d++;
      }
   }
   return FALSE;
}
function GetSportYears($sport,$offid)
{
   $sql="SELECT * FROM ".$sport."off WHERE offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) return 0;
   return $row[years];
}
function OfficiatedState($offid,$insince,$year,$sport)
{
   $table=$sport."off";
   if(strlen($year)==2 && $year>=70) $fullyear="19".$year;
   else if(strlen($year)==2 ) $fullyear="20".$year;
   else if(strlen($year)==4) $year=substr($year,2,2);
   if($insince=="IN")
   {
      $sql2="SELECT * FROM $table WHERE offid='$offid' AND stateyears LIKE '%$year%'";
      if($sport=='bb')
         $sql2="SELECT * FROM $table WHERE offid='$offid' AND (bstateyears LIKE '%$year%' OR gstateyears LIKE '%$year%')";  
      $result2=mysql_query($sql2);
//echo "$sql2<br>";
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)>0) //official was contracted to officiate in this year for this sport
         return TRUE;
   }
   else        //SINCE
   {
      $sql2="SELECT * FROM $table WHERE offid='$offid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($sport=='bb')
      {
         $yearstr=$row2[gstateyears].",".$row2[bstateyears];
         $years=split(",",$yearstr);
      }
      else
         $years=split(",",$row2[stateyears]);
      for($i=0;$i<count($years);$i++)
      {
	 if($years[$i]!='' && $years[$i]<70) $years[$i]="19".$years[$i];
	 else if($years[$i]!='') $years[$i]="20".$years[$i];
         if($years[$i]!='' && $fullyear<=$years[$i])
	    return TRUE; 
      }
   }
   return FALSE;
}
function YearsOfficiatedState($offid,$sport)
{
   $table=$sport."off";
   $sql="SELECT * FROM $table WHERE offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $num="";
   if($sport=='bb')
   {
      if($row[bnumstateyears]>$row[gnumstateyears]) $num=$row[bnumstateyears];
      else $num=$row[gnumstateyears];
   }
   else
      $num=$row[numstateyears];
   if($num=="") $num=0;
   return $num;
}
function GetDistrictSpeechPlace($event,$studentid)
{
   require 'variables.php';
   $studentid=trim($studentid);
   if($event!='dram' && $event!='duet')
   {
      $field=$event."_stud";
      $sql="SELECT * FROM $db_name.sp_state_qual WHERE ($field LIKE '$studentid,%' OR $field LIKE '%,$studentid,%' OR $field LIKE '%,$studentid')";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0) return 0;
      $studs=split(",",$row[$field]);
      for($i=0;$i<count($studs);$i++)
      {
	 $place=$i+1;
	 if($studs[$i]==$studentid) return $place;
      }
      return 0;
   }
   else
   {
      if($event=='dram') $table="sp_state_drama";
      else $table="sp_state_duet";
      $field=$event."_stud";
      $studs=split(",",$studentid);
      $sql="SELECT * FROM $db_name.$table WHERE ";
      for($i=0;$i<count($studs);$i++)
      {
         $sql.="($field LIKE '$studs[$i],%' OR $field LIKE '%,$studs[$i],%' OR $field LIKE '%,$studs[$i]') AND ";
      }
      $sql=substr($sql,0,strlen($sql)-5);
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0) return 0;
      else return $row[place];
   } 
   return 0;
}
function GetSpeechDistrict($event,$studentid)
{
   require 'variables.php';
   $studentid=trim($studentid); $distid=0;
   if($event!='dram' && $event!='duet')
   {
      $field=$event."_stud";
      $sql="SELECT * FROM $db_name.sp_state_qual WHERE ($field LIKE '$studentid,%' OR $field LIKE '%,$studentid,%' OR $field LIKE '%,$studentid')";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0) return 0;
      $distid=$row[dist_id];
   }
   else
   {
      if($event=='dram') $table="sp_state_drama";
      else $table="sp_state_duet";
      $field=$event."_stud";
      $studs=split(",",$studentid);
      $sql="SELECT * FROM $db_name.$table WHERE ";
      for($i=0;$i<count($studs);$i++)
      {
	 $sql.="($field LIKE '$studs[$i],%' OR $field LIKE '%,$studs[$i],%' OR $field LIKE '%,$studs[$i]') AND ";
      }
      $sql=substr($sql,0,strlen($sql)-5);
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0) return 0;
      $distid=$row[dist_id];
   }
   if($distid!=0)
   {
      $sql="SELECT * FROM spdistricts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      return "$row[class]-$row[district]";
   }
   else
      return 0;
}
function GetSearchDescription($query)
{
  //return description of search criteria entered that produced $query
   $temp=split(" WHERE ",$query);
   $fields=split(" AND ",$temp[1]);
   $search="";
   if(ereg("AS t1",$query))
   {
      for($i=0;$i<count($activity);$i++)
      {
         if($sport==$activity[$i])
            $search.="$act_long[$i] Officials, ";
      }
   }
   for($i=0;$i<count($fields);$i++)
   {
      if(!ereg("OR",$fields[$i]))
      {
         if(ereg("LIKE",$fields[$i]))
         {
            $parts=split(" LIKE ",$fields[$i]);
            $curfield=ereg_replace("t2.","",$parts[0]);
            $curfield=ereg_replace("t1.","",$curfield);
            $curvalue=ereg_replace("\'","",$parts[1]);
            $curvalue=ereg_replace("%","",$curvalue);
         }
         else if(ereg("state",$fields[$i]))
         {
            $parts=split("=",$fields[$i]);
            $curfield=ereg_replace("t2.","",$parts[0]);
            $curfield=ereg_replace("t1.","",$curfield);
            $curvalue=$parts[1];
         }
         else   //equality/inequality
         {
            $parts=split(" ",$fields[$i]);
            $curfield=ereg_replace("t2.","",$parts[0]);
            $curfield=ereg_replace("t1.","",$curfield);
            $curfield=ereg_replace("t3.","",$curfield);
            $curvalue=$parts[1]." ".$parts[2];
            if($curfield=="senttofed" || $curfield=="rm" || $curfield=="nhsoa")
            {
               if($parts[1]=='>' || (($curfield=="nhsoa" || $curfield=="rm") && $parts[1]=="="))        //sent to fed/attended rm: yes
                  $curvalue="YES";
               else
                  $curvalue="NO";
            }
         }
      }
      else
      {
         $fields[$i]=substr($fields[$i],1,strlen($fields[$i])-2);
         $parts=split(" OR ",$fields[$i]);
         $parts2=split(" LIKE ",$parts[0]);
         $curfield="area";
         $curvalue=ereg_replace("\'","",$parts2[1]);
         $curvalue=ereg_replace("%","",$curvalue);
      }
      switch($curfield)
      {
         case "socsec":
            $search.="Soc Sec # starts w/ <i>$curvalue</i>, ";
            break;
         case "last":
            $search.="Last Name starts w/ <i>$curvalue</i>, ";
            break;
         case "first":
            $search.="First Name starts w/ <i>$curvalue</i>, ";
            break;
         case "city":
            $search.="City - $curvalue, ";
            break;
         case "state":
            $search.="State - $curvalue, ";
            break;
         case "zip":
            $search.="Zip - $curvalue, ";
            break;
         case "area":
            $search.="Area Code - $curvalue, ";
            break;
         case "email":
            $search.="E-mail starts w/ <i>$curvalue</i>, ";
            break;
         case "payment":
            $search.="Payment - $curvalue, ";
            break;
         case "senttofed":
            $search.="Sent to NFHS: $curvalue, ";
            break;
         case "nhsoa":
	    $search.="NHSOA Membership: $curvalue, ";
	    break;
         case "gender":
	    $search.="Gender: $curvalue, ";
	    break;
         case "minority":
	    $search.="Minorities ONLY, ";
	    break;
         case "class":
            $search.="Class - $curvalue, ";
            break;
         case "suptestdate":
            $search.="Sup Test Date $curvalue, ";
            break;
         case "mailing":
            $search.="Mailing # $curvalue, ";
	    break;
         case "years":
            $search.="Years $curvalue, ";
            break;
         case "currentst":
            $search.="Current ST - $curvalue, ";
            break;
         case "retaketest":
            $search.="Retake Test $curvalue, ";
            break;
         case "chosen":
            $search.="Chosen - Yes, ";
            break;
         case "patches":
            $search.="Patches - $curvalue, ";
            break;
         case "rm":
            $search.="Attended Rules Meeting - $curvalue, ";
            break;
         case "regyr":
            $search.="for Registration Year $curvalue, ";
            break;
         default:
            $search.="";
      }
   }
   $search=substr($search,0,strlen($search)-2);
   return $search;
}
function CountVarsityContests($offid,$sport,$year="")
{
   if($year=='') $year=date("Y");
   $schedule=$sport."sched";
   $sql="SHOW TABLES LIKE '$table'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return 0;
   $year2=$year+1;
   $sql="SHOW DATABASES LIKE 'nsaaofficials".$year.$year2."'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
      $database="nsaaofficials";
   else $database="nsaaofficials".$year.$year2;
   //Regular Season Games
   if($sport=='fb')	//make sure to count crew MEMBERS too - get them from fbapply table
      $sql="SELECT t1.* FROM $database.$schedule AS t1, $database.fbapply AS t2 WHERE t1.offid=t2.offid AND (t2.offid='$offid' OR t2.chief='$offid' OR t2.referee='$offid' OR t2.umpire='$offid' OR t2.linesman='$offid' OR t2.linejudge='$offid' OR t2.backjudge='$offid')";
   else
      $sql="SELECT * FROM $database.$schedule WHERE offid='$offid'";
   $result=mysql_query($sql);
   $count=mysql_num_rows($result);

   //Postseason Contracts (except State)
   if($sport=='ba' || $sport=='sb' || $sport=='vb')
   {
      //Single Gender Sports with __disttimes table 
      $contracts=$sport."contracts"; $districts=$sport."districts"; $disttimes=$sport."disttimes";
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$disttimes AS t2, $database.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type!='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      $count+=mysql_num_rows($result);
      //STATE
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$disttimes AS t2, $database.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)	//OFFICIATED STATE
      {
         if($sport=='bs') $count+=4;
         else if($sport=='sb') $count+=6;
         else if($sport=='vb') $count+=4;
      }
   }
   else if($sport=='so' || $sport=='bb')
   {
      //Dual Gender Sports
	//GIRLS
      $contracts=$sport."gcontracts"; $districts=$sport."gdistricts"; $disttimes=$sport."gdisttimes";
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$disttimes AS t2, $database.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type!='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      $count+=mysql_num_rows($result);
        //BOYS STATE      
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$disttimes AS t2, $database.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)     //OFFICIATED STATE      
      {
         if($sport=='so') $count+=1;
         else if($sport=='bb') $count+=4;
      }
   	//BOYS
      $contracts=$sport."bcontracts"; $districts=$sport."bdistricts"; $disttimes=$sport."bdisttimes";
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$disttimes AS t2, $database.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type!='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      $count+=mysql_num_rows($result);
       	//BOYS STATE      
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$disttimes AS t2, $database.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)     //OFFICIATED STATE
      {
         if($sport=='so') $count+=1;
         else if($sport=='bb') $count+=4;
      }
   }
   else if($sport=='wr')
   {
      //Wrestling
      	//DISTRICTS (=3)
      $contracts=$sport."contracts"; $districts=$sport."districts"; 
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$districts AS t2 WHERE t1.distid=t2.id AND t2.type!='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) $count+=3;
	//STATE (=4)
      $sql="SELECT t1.* FROM $database.$contracts AS t1, $database.$districts AS t2 WHERE t1.distid=t2.id AND t2.type='State' AND t1.offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0) $count+=4;
   }
   else if($sport=='fb')
   {
      //Football
      $sql="SELECT DISTINCT t1.offid FROM $database.fbsched AS t1, $database.fbapply AS t2 WHERE t1.offid=t2.offid AND (t2.offid='$offid' OR t2.chief='$offid' OR t2.referee='$offid' OR t2.umpire='$offid' OR t2.linesman='$offid' OR t2.linejudge='$offid' OR t2.backjudge='$offid')";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)>0)
      {
         $sql="SELECT t1.* FROM $database.fbcontracts AS t1, $database.fbbrackets AS t2 WHERE t1.gameid=t2.id AND t1.offid='$row[offid]'";
         $result=mysql_query($sql);
         $count+=mysql_num_rows($result);
      }
   }
   else return 0; 	//Contests not asked for on application form
   return $count;
}
function GetOffSports($offid)
{
   require 'variables.php';
   $spreg_abb="";
   $spreg_long="";
   $ix=0; $fboff=0;
   for($i=0;$i<count($activity);$i++)
   {
      $table=$activity[$i]."off";
      $sql="SELECT * FROM $table WHERE offid='$offid' AND payment!=''";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)     //Official has paid for this sport
      {
         $spreg_abb.=$activity[$i].",";
         $spreg_long.=$act_long[$i].",";
         $ix++;
         if($activity[$i]=='fb') $fboff=1;
      }
   }
   if($spreg_abb!='') 
   {
      $spreg_abb=substr($spreg_abb,0,strlen($spreg_abb)-1);
      $spreg_long=substr($spreg_long,0,strlen($spreg_long)-1);
      return $spreg_abb.";".$spreg_long;
   }
   else return FALSE;
}
function GetAppsToOffReminders($session)
{
   $offid=GetOffID($session);
   $offsports=GetOffSports($offid);
   if(!$offsports) return FALSE;
   $temp=split(";",$offsports);
   $spreg_abb=split(",",$temp[0]);
   $spreg_long=split(",",$temp[1]);

      $sql="SELECT * FROM app_duedates";
      $result=mysql_query($sql);
      $html="";
      while($row=mysql_fetch_array($result))
      {
         $cursport=$row[sport];
         for($i=0;$i<count($spreg_abb);$i++)
         {
            if($cursport==$spreg_abb[$i] && DueSoon($row[duedate],15) && !PastDue($row[duedate],0))
            {
               if($cursport=='di') $appsport='sw';
               else $appsport=$cursport;
               $html.="<p><a class=small";
               if($appsport=='tr') $html.=" style=\"color:red\"";
               $html.=" href=\"".$appsport."app.php?session=$session&sport=$cursport\">$spreg_long[$i]";
               if($spreg_abb[$i]=='tr') //Track: No app, just checkbox on schedule:
                  $html.=" (Indicate on your Schedule if you would like to be a State Track & Field Starter)";
               else
                  $html.=" Application to Officiate";
               $due=split("-",$row[duedate]);
               $html.="</a> (Due $due[1]/$due[2]/$due[0])</p>";
            }  
         }  
      }
   if($html!='')
   {
      $html="<p><b>The following <u>Applications to Officiate</u> are DUE SOON:</b></p>".$html;
      return $html;
   }
   else return FALSE;
}
function GetSchedReminders($session)
{
   $offid=GetOffID($session);
   $offsports=GetOffSports($offid);
   if(!$offsports) return FALSE;
   $temp=split(";",$offsports);
   $spreg_abb=split(",",$temp[0]);
   $spreg_long=split(",",$temp[1]);

      $html=""; $curseason=GetCurrentSeason();
      for($i=0;$i<count($spreg_abb);$i++)
      {
         if($curseason==GetSeason($spreg_abb[$i]))
	 {
            $html.="<li><a class=small href=\"schedule.php?session=$session&sport=".$spreg_abb[$i]."\">$spreg_long[$i] Schedule</a>";
	    if($spreg_abb[$i]=="fb")
	       $html.="&nbsp;<label style=\"color:#ff0000;font-weight:bold;\">- ONLY THE CREW CHIEF NEEDS TO FILL OUT THE SCHEDULE</label>";
	    $html.="</li>";
 	 }
      }
     
   if($html!='')
   {
      $html="<ul>".$html."</ul>";
      if($curseason=="Winter")
      {
         if(date("m")>6) $year=date("Y");
	 else $year=date("Y")-1;
	 $year2=$year+1;
         $html="<p><b>DON'T FORGET TO FILL OUT YOUR $curseason SCHEDULE(S)!<br><font style=\"color:red\">***Please check your schedules to make sure you did not accidentally enter dates that are IN THE PAST, such as 01/07/$year instead of 01/07/$year2.</font></b></p>".$html;
      }
      else
         $html="<p><font style=\"color:red\"><b>DON'T FORGET TO FILL OUT YOUR ".strtoupper($curseason)." SCHEDULE(S)!!</b></font></p>".$html;
         $html.="<p><font style=\"color:\"><b>IF YOU ARE A SUB OFFICIAL - SUBMIT YOUR GAMES HERE</b></font></p>";
      return $html;
   }
   else return FALSE;
}
function GetContractReminders($session)
{
   $offid=GetOffID($session);
   $offsports=GetOffSports($offid);
   if(!$offsports) return FALSE;
   $temp=split(";",$offsports);
   $spreg_abb=split(",",$temp[0]);
   $spreg_long=split(",",$temp[1]);

      $contractstr=""; $contractinfo="";
      for($j=0;$j<count($spreg_abb);$j++)
      {
         $sport=$spreg_abb[$j];
         if($sport=='bb') $sport='bbb';
         else if($sport=='so') $sport='sob';
         $contracts=GetOffContracts($sport,$offid,$session);
         $sportname=GetSportName($sport);
         if(count($contracts[url])>0) $contractstr.=$sportname.", ";
         for($i=0;$i<count($contracts[url]);$i++)
         {
            $accept=$contracts[accept][$i];
            $post=$contracts[post][$i];
            if($post=='y' && $accept=='')               
		$contractinfo.="<p>You have a <b>$sportname</b> contract you need to respond to.  Please go to the <a class=small href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9#4\">Contracts</a> section below.</p>";
         }
         if($sport=='bbb' || $sport=='sob')
         {
            if($sport=='bbb') $sport='bbg';
            else $sport='sog';
            $contracts=GetOffContracts($sport,$offid,$session);
            $sportname=GetSportName($sport);
            if(count($contracts[url])>0) $contractstr.=$sportname.", ";
            for($i=0;$i<count($contracts[url]);$i++)
            {
               $accept=$contracts[accept][$i];
               if($accept=='')
                  $contractinfo.="<p>You have a <b>$sportname</b> contract you need to respond to.  Please go to the <a class=small href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9#4\">Contracts</a> section below.</p>";
            }
         }
      }
  
   if($contractinfo!='')
   {
      return "<p><b>Please respond to the following CONTRACTS as soon as possible:</b></p>".$contractinfo;
   }
   else return FALSE;
}
function GetTestReminders($session)
{
   $offid=GetOffID($session);
   $offsports=GetOffSports($offid);
   if(IsAffiliateOff($offid)) return FALSE;
   if(!$offsports) return FALSE;
   $temp=split(";",$offsports);
   $spreg_abb=split(",",$temp[0]);
   $spreg_long=split(",",$temp[1]);

      $sql="SELECT * FROM test_duedates";
      $result=mysql_query($sql);
      $html="";
      while($row=mysql_fetch_array($result))
      {
         $cursport=$row[test];
         for($i=0;$i<count($spreg_abb);$i++)
         {
            if($cursport==$spreg_abb[$i] && DueSoon($row[duedate],10) && !PastDue($row[duedate],2))
            {
               //$duedate=split("-",$row[duedate]); 
			   $duedate=split("-",$row[fakeduedate]);
               $time=mktime(0,0,0,$duedate[1],$duedate[2],$duedate[0]);
               $due_date=date("F d, Y",$time);
               //get num of questions on this test
               $testtable=$spreg_abb[$i]."test";
               $sql2="SELECT id FROM $testtable";
               $result2=mysql_query($sql2);
               $questotal=mysql_num_rows($result2);
               //see if they have submitted test yet
               $testtable=$spreg_abb[$i]."test_results";
               $sql2="SELECT * FROM $testtable WHERE offid='$offid'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               if($row2[datetaken]=="") //not submitted, get number answered
               {
                  $answered=0;
                  for($j=1;$j<=$questotal;$j++)
                  {
                     $index="ques".$j;
                     if($row2[$index]!='')
                        $answered++;
                  }
                  $note="You have NOT completed this test. <a class=small href=\"".$spreg_abb[$i]."test.php?session=$session\"><b>Go to ".$spreg_long[$i]." Test &rarr;</b></a>";
                  $color="red";
               }
               else     //submitted
               {
                  $date=date("F d, Y",$row2[datetaken]);
                  $note="You completed and submitted this test on $date.";
                  $color="blue";
               }
               $html.="<p>$spreg_long[$i] Test (Due $due_date)<br><font style=\"color:$color\">$note</font></p>";
            }   
         }  
      }//end while loop
   
   if($html!='')
   {
      return "<p><b>The following ONLINE TESTS are due soon:</b></p>".$html;
   }
   else return FALSE;
}
function GetStateSpeechCode($sid,$event,$studentids)
{
   require 'variables.php';
   if($event=="dram" || $event=="duet")
   {
      //If more than 1 participant, return $sid + "A" or "B" depending on id value in database table
      if($event=="dram") $table="sp_state_drama";
      else $table="sp_state_duet";
      $field=$event."_sch"; $field2=$event."_stud";
      $sql="SELECT * FROM $db_name.$table WHERE $field='$sid' ORDER BY id";
      $result=mysql_query($sql);
      $letters=array("A","B","C","D","E");	//Realistically each school will only have up to 2 drama or duet groups, but just in case, we allow for 5 here
      $ix=0;
      if(mysql_num_rows($result)<=1) return $sid;
      while($row=mysql_fetch_array($result))
      {
         if(preg_replace("/[^0-9]/","",$studentids)==preg_replace("/[^0-9]/","",$row[$field2]))
            return $sid.$letters[$ix];
         $ix++;
      }
      return $sid;
   }
   else return $sid;
}
function GetCrewChief($offid)
{
   //RETURN CREW CHIEF OFFID OF FB CREW
   $sql="SELECT offid FROM fbapply WHERE (referee='$offid' OR umpire='$offid' OR linesman='$offid' OR linejudge='$offid' OR backjudge='$offid' OR offid='$offid')";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[0];
}
function IsNewJudge($judgeid)
{
   $sql="SELECT * FROM judges WHERE firstyr='x' AND id='$judgeid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) return TRUE;
   else return FALSE;
}
function IsNewOfficial($offid)
{
   $sql="SHOW TABLES LIKE '%off_hist'";
   $result=mysql_query($sql);
   if(date("m")>=6) $thisfallyr=date("Y");
   else $thisfallyr=date("Y")-1;
   $thisspringyr=$thisfallyr+1;
   $curregyr="$thisfallyr-$thisspringyr";
   $newforallsports=1;
   while($row=mysql_fetch_array($result))
   {
      //FOR EACH SPORT:
      $sql2="SELECT * FROM $row[0] WHERE offid='$offid' AND regyr<'$curregyr' AND appdate!='0000-00-00' AND class!='' ORDER BY appdate DESC LIMIT 1";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)	//THEY HAVE REGISTERED BEFORE THIS YEAR FOR THIS SPORT
      {
	 $row2=mysql_fetch_array($result2);	//CHECK IF TWO YEARS HAVE LAPSED (MEANS THEY CAN START OVER)
	 $regyr=substr($row2[regyr],0,4);
	 if(($thisfallyr-$regyr)<=2) //NOT NEW FOR THIS SPORT
	 {
	    $newforallsports=0;
	    //echo "-- not new for ".strtoupper(substr($row[0],0,2))."\r\n";
	 }
	 //else echo "-- NEW for ".strtoupper(substr($row[0],0,2))."\r\n";
      }
      //else echo "-- NEW for ".strtoupper(substr($row[0],0,2))."\r\n";
   }
   if($newforallsports==1) return TRUE;
   else return FALSE;
}
function IsNewOfficialForSport($offid,$sport,$thisfallyr='')
{
   if($thisfallyr=="")
   {
      if(date("m")>=6) $thisfallyr=date("Y");
      else $thisfallyr=date("Y")-1;
   }
   $thisspringyr=$thisfallyr+1;
   $curregyr="$thisfallyr-$thisspringyr";
   $newforallsports=1;
   
      $sql2="SELECT * FROM ".$sport."off_hist WHERE offid='$offid' AND regyr<'$curregyr' AND appdate!='0000-00-00' AND class!='' ORDER BY appdate DESC LIMIT 1";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)    //THEY HAVE REGISTERED BEFORE THIS YEAR FOR THIS SPORT
      {
         $row2=mysql_fetch_array($result2);     //CHECK IF TWO YEARS HAVE LAPSED (MEANS THEY CAN START OVER)
         $regyr=substr($row2[regyr],0,4);
         if(($thisfallyr-$regyr)<=2) //NOT NEW FOR THIS SPORT
	    return FALSE;
      }

   return TRUE;
}
function GetPart1TestScore($offid,$sport,$year="",$attempts=FALSE)
{
   require "variables.php";

   if($year=="") 
   {
      $year=date("Y");
      if(date("m")<6) $year--;
   }
   $year1=$year+1;
   $sql="SELECT obtest,obtestattempts FROM ".$sport."off_hist WHERE offid='$offid' AND regyr='$year-$year1'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   if($attempts) return $row[obtest]."-".$row[obtestattempts];
   return $row[obtest];
}
function GetSPart1TestScore($offid,$sport,$year="",$attempts=FALSE)
{
   require "variables.php";

   if($year=="") 
   {
      $year=date("Y");
      if(date("m")<6) $year--;
   }
   $year1=$year+1;
   $sql="SELECT sobtest,sobtestattempts FROM ".$sport."off_hist WHERE offid='$offid' AND regyr='$year-$year1'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   if($attempts) return $row[sobtest]."-".$row[sobtestattempts];
   return $row[sobtest];
}
function GetPart2TestScore($offid,$sport,$class="R")
{
   require "variables.php";

   //FIRST MAKE SURE THERE IS A PART 2 TEST FOR THIS SPORT
   $sql="SHOW TABLES LIKE '".$sport."test2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return "100;".date("Y"); //ALWAYS PASSES

   //GET YEAR REQUIREMENT SETTING FOR $class
   $sql="SELECT part2yrs FROM classificationsettings WHERE classification='$class'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $range=$row[0];	//NUMBER OF YEARS WE CAN GO BACK TO MEET THIS RANK'S REQUIREMENTS

   $minyear=date("Y")-$range; //ex: 2014 - 5 = 2009
   $minyear1=$minyear+1;
   $regyr="$minyear-$minyear1";

   $sql="SELECT suptest,regyr FROM ".$sport."off_hist WHERE offid='$offid' AND suptest!='' AND regyr>='$regyr' ORDER BY suptest DESC LIMIT 1"; //AND class='$class'
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   return $row[suptest].";".substr($row[regyr],0,4);
}
function CompletedPart2TestRequirement($offid,$sport,$classification,$year="",$verbose=FALSE)
{
   //Returns whether or not official completed the Part 2 Test requirement
   //which is based on settings in classificationsettings table
   require "variables.php";

   if($year=="")
   {
      $year=date("Y");
      if(date("m")<6) $year--;
   }

   $mytest=explode(";",GetPart2TestScore($offid,$sport,$classification));
   $thescore=$mytest[0]; $theyear=$mytest[1]; $theyear1=$theyear+1;

   //GET TEST SCORE REQUIRED FOR UPGRADE & YEAR REQUIREMENT
   $sql="SELECT part2test,part2yrs FROM classificationsettings WHERE classification='$classification'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $mintestscore=$row[part2test]; 
   $testyears=$row[part2yrs];

   $minyear=$year-$testyears; //ex: 2014 - 5 = 2009

   if($classification=="R")	//THEY GET $testyears YEARS TO ACTUALLY TAKE THE TEST FROM WHEN THEY ARE A NEW OFFICIAL
   {
      $curyr=$minyear+1;	//FALL YEAR OF CURRENT REGISTRATION YEAR
      while($curyr<=$year) 	//IF A NEW OFFICIAL IN ANY OF THESE PAST $testyears YEARS, THEY DON'T NEED A PART 2 SCORE
      {
         if(IsNewOfficialForSport($offid,$sport,$curyr))	//WAS A NEW OFFICIAL FOR $sport in $curyr - NO NEED FOR A PART 2 SCORE
	 {
	    //echo "$offid IS NEW OFF IN $curyr, PART 2: ".GetPart2TestScore($offid,$sport,$classification)."<br>";
	    return TRUE;
	 }
	 $curyr++;
      }
   }

   //CHECK THIS OFFICIAL TO SEE IF HE OR SHE MET THE REQUIREMENTS:
   if($verbose) echo "Minimum score needed: $mintestscore in $minyear (got $thescore in $theyear)\r\n";
   if($theyear>=$minyear && $thescore>=$mintestscore)	//Scored high enough within # of years
      return TRUE;
   else
      return FALSE;
}
function CompletedRulesMeeting($offid,$sport,$year="")
{
   require "variables.php";

   if($year=="")
   {
      $year=date("Y");
      if(date("m")<6) $year--;
   }
   $year1=$year+1;
   $sql="SELECT rm FROM ".$sport."off_hist WHERE offid='$offid' AND regyr='$year-$year1'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   if($row[rm]=='x') return TRUE;
   else return FALSE;
}
function UpdateRank($offid,$sport,$year="",$verbose=FALSE)
{
   //Check to see if we can update the official to a higher rank
   //Triggered by rules meeting attendance, Part 1 or Part 2 test submission

   require "variables.php";

   if($year=="")
   {
      $year=date("Y");
      if(date("m")<6) $year--;
   }
   $year1=$year+1;
   $lastyr1=$year-1; $lastyr2=$year-2;
   if($verbose) echo "YEAR: $year\r\n";

   //CHECK IF NSAA OVERRIDED THEIR CLASS; IF SO RETURN
   $sql="SELECT * FROM ".$sport."off_hist WHERE offid='$offid' AND regyr='$year-$year1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[overrideclass]=='x') return TRUE;

   //GET SETTINGS FOR EACH RANK
   $sql="SELECT * FROM classificationsettings WHERE classification='R'";
   $result=mysql_query($sql);
   $R=mysql_fetch_array($result);
   $sql="SELECT * FROM classificationsettings WHERE classification='A'";
   $result=mysql_query($sql);
   $A=mysql_fetch_array($result);
   $sql="SELECT * FROM classificationsettings WHERE classification='C'";
   $result=mysql_query($sql);
   $C=mysql_fetch_array($result);

   //GET THIS OFFICIAL'S RECORD FOR THIS YEAR
   $sql="SELECT * FROM ".$sport."off_hist WHERE regyr='$year-$year1' AND offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $curclass=$row['class'];
   if($verbose) echo "CURRENT RANK: $curclass\r\n";
   $lastyr1class=GetOffClass($offid,$sport,$lastyr1);
   $lastyr2class=GetOffClass($offid,$sport,$lastyr2);
   if($verbose) echo "PREVIOUS RANKS: $lastyr1class ($lastyr1), $lastyr2class ($lastyr2)\r\n";
   $r=0;	//SUCCESSIVE YEARS AT LEAST AN R
   $curyr=$lastyr1;
   for($i=0;$i<$A[prevclassyrs];$i++)	//FOR AS MANY YEARS BACK AS THE SETTINGS REQUIRE...
   {
      $theclass=GetOffClass($offid,$sport,$curyr);	 //GET THEIR CLASS/RANK FOR THAT YEAR
      if($theclass=="R" || $theclass=="A" || $theclass=="C") $r++;  //IF IT'S AT LEAST AN R, INCREMENT AND CONTINUE BACK ANOTHER YEAR
      else $i=$A[prevclassyrs]; 	//OTHERWISE GET OUT OF THE LOOP
      $curyr--;	//GO BACK ONE MORE YEAR UNTIL WE HIT $A[prevclassyrs] CONSECUTIVE YEARS
   }
   if($verbose) echo "YEARS AS >=R: $r (NEED ".$A[prevclassyrs].")\r\n";
   $a=0;	//SUCCESSIVE YEARS AT LEAST AN A
   $curyr=$lastyr1;
   for($i=0;$i<$C[prevclassyrs];$i++)   //FOR AS MANY YEARS BACK AS THE SETTINGS REQUIRE...
   {
      $theclass=GetOffClass($offid,$sport,$curyr);      //GET THEIR CLASS/RANK FOR THAT YEAR
      if($theclass=="A" || $theclass=="C") $a++;  //IF IT'S AT LEAST AN A, INCREMENT AND CONTINUE BACK ANOTHER YEAR
      else $i=$C[prevclassyrs];         //OTHERWISE GET OUT OF THE LOOP
      $curyr--; //GO BACK ONE MORE YEAR UNTIL WE HIT $C[prevclassyrs] CONSECUTIVE YEARS
   }
   if($verbose) echo "YEARS AS >=A: $a (NEED ".$C[prevclassyrs].")\r\n";

   $sql2="SELECT * FROM ".$sport."off_hist WHERE offid='$offid' AND regyr='$year-$year1'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $contestct2=trim($row2[contest]);
   if($contestct2=="") $contestct2=0;
   if($verbose) echo "CONTESTS: $contestct2\r\n";

   if(CompletedRulesMeeting($offid,$sport)) $rm="X";
   else $rm="";
   if($verbose) echo "RULES MEETING: $rm\r\n";

   //CHECK FOR C, then A, then R
   $contests=$sport."contests"; $shouldbe="";
   if($sport=="sw" || $sport=="tr") 
   {
      $C[$contests]=0; $A[$contests]=0; $R[$contests]=0;
   }
   if($verbose)
   {
      echo "PART 1: ".GetPart1TestScore($offid,$sport)." - needs ".$R[part1test]." for R, ".$A[part1test]." for A, ".$C[part1test]." for C\r\n";
      echo "PART 2:\r\n          ";
      $mytest=explode(";",GetPart2TestScore($offid,$sport,"R"));
      $thescore=$mytest[0]; $theyear=$mytest[1]; $theyear1=$theyear+1;
      echo "R (needs ".$R[part2test]."): $thescore, $theyear\r\n          ";
      if(CompletedPart2TestRequirement($offid,$sport,"R","",TRUE)) echo "...DID complete for R\r\n          ";
      else echo "...Did NOT complete for R\r\n          ";
      $mytest=explode(";",GetPart2TestScore($offid,$sport,"A"));
      $thescore=$mytest[0]; $theyear=$mytest[1]; $theyear1=$theyear+1;
      echo "A (needs ".$A[part2test]."): $thescore, $theyear\r\n          ";
      if(CompletedPart2TestRequirement($offid,$sport,"A","",TRUE)) echo "...DID complete for A\r\n          ";
      else echo "...Did NOT complete for A\r\n          ";
      $mytest=explode(";",GetPart2TestScore($offid,$sport,"C"));
      $thescore=$mytest[0]; $theyear=$mytest[1]; $theyear1=$theyear+1;
      echo "C (needs ".$C[part2test]."): $thescore, $theyear\r\n          ";
      if(CompletedPart2TestRequirement($offid,$sport,"C","",TRUE)) echo "...DID complete for C\r\n          ";
      else echo "...Did NOT complete for C\r\n          ";
   }
   if($sport!='tr' && $sport!='sw' && $a>=$C[prevclassyrs] && CompletedRulesMeeting($offid,$sport) && GetPart1TestScore($offid,$sport)>=$C[part1test] && CompletedPart2TestRequirement($offid,$sport,"C") && $contestct2>=$C[$contests])
      $shouldbe="C";
   else if($sport!='tr' && $sport!='sw' && $r>=$A[prevclassyrs] && CompletedRulesMeeting($offid,$sport) && GetPart1TestScore($offid,$sport)>=$A[part1test] && CompletedPart2TestRequirement($offid,$sport,"A") && $contestct2>=$A[$contests])
      $shouldbe="A";
   else if(CompletedRulesMeeting($offid,$sport) && GetPart1TestScore($offid,$sport)>=$R[part1test] && CompletedPart2TestRequirement($offid,$sport,"R") && $contestct2>=$R[$contests])
      $shouldbe="R";
   else $shouldbe="";

   if($verbose) echo "SHOULD BE: $shouldbe\r\n";

   if($shouldbe!=$curclass && $curclass!='AFF')
   {
	/*
	$sql="SELECT * FROM ".$sport."off WHERE offid='$offid'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);	//GET suptestdate
	*/
        $sql="UPDATE ".$sport."off_hist SET class='$shouldbe',notes='Updated classification on ".date("m/d/y")."' WHERE offid='$offid' AND regyr='$year-$year1'";
      $result=mysql_query($sql);
	if(mysql_error())
	{
	   echo "ERROR: ".mysql_error()."<br>$sql<br>"; exit();
	}
        $sql="UPDATE ".$sport."off SET class='$shouldbe' WHERE offid='$offid'";
        $result=mysql_query($sql);
        if(mysql_error())
        {
           echo "ERROR: ".mysql_error()."<br>$sql<br>"; exit();
        }
	/*
       echo "$sql<br>";
      $test2=explode(";",GetPart2TestScore($offid,$sport,$shouldbe));
      $csv="\"".strtoupper($sport)."\",\"$offid\",\"".GetOffname($offid)."\",\"$rm\",\"".GetPart1TestScore($offid,$sport)."\",\"$test2[0]\",\"$test2[1]\",\"$row[suptestdate]\",\"$contestct2\",\"$lastyr2class\",\"$lastyr1class\",\"$curclass\",\"$shouldbe\"\r\n";
      return $csv;
	*/
	
	if($shouldbe!='' && $shouldbe!='AFF')	//MAILING NUMBER OF 100 in __off TABLE
	{
	   $sql="UPDATE ".$sport."off SET mailing='100' WHERE offid='$offid'";
	   $result=mysql_query($sql);
	}

      //SendMail("nsaa@nsaahome.org","NSAA","ann@womentalksports.com","Ann Gaffigan","An official has been upgraded","An official has been upgraded from \"$curclass\" to \"$shouldbe\"","Official $offid has been upgraded from \"$curclass\" to \"$shouldbe\" in $sport. Check the mailing number too.",array());
	return TRUE;
   }
}
function HasClinic($sport)
{
   if($sport=="sb" || $sport=="fb" || $sport="vb" || $sport=="sw" || $sport=="ba" || $sport=="tr" || ereg("so",$sport) || ereg("bb",$sport) || $sport=='wr')  
	return TRUE;
   else return FALSE;
}
function CleanSessions()
{
   $oldtime=time()-(3*24*60*60);        //OLDER THAN 3 DAYS
   $sql="DELETE FROM sessions WHERE session_id<'$oldtime'";
   $result=mysql_query($sql);
}
function OffIsDoubleAssigned($sport, $date, $offid)
{
   if($sport=='bbb') $sport2='bbg';
   else if($sport=='bbg') $sport2='bbb';
   else if($sport=='sob') $sport2='sog';
   else if($sport=='sog') $sport2='sob';
   else $sport2=$sport;

   //GET ASSIGNMENTS FOR THIS DAY IN $sport
   $sql="SELECT DISTINCT t2.class,t2.district,t2.id FROM ".$sport."contracts AS t1, ".$sport."districts AS t2, ".$sport."disttimes AS t3 WHERE t1.disttimesid=t3.id AND t3.distid=t2.id AND t1.offid='$offid' AND t2.type!='State' AND t3.day='$date'";
   $dists=array(); $d=0;
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $dists[$d]=$sport.$row[id];
      $d++;
   }
   $sql0=$sql;

   //NOW GET THE ONES FOR $sport2
   $sql="SELECT DISTINCT t2.class,t2.district,t2.id FROM ".$sport2."contracts AS t1, ".$sport2."districts AS t2, ".$sport2."disttimes AS t3 WHERE t1.disttimesid=t3.id AND t3.distid=t2.id AND t1.offid='$offid' AND t2.type!='State' AND t3.day='$date'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $dists[$d]=$sport2.$row[id];
      $d++;
   }

   $dists=array_unique($dists);
   if(count($dists)>1) return TRUE;
   return FALSE;
}
function GetAssignedOfficials($sport,$distid)
{
   if($sport=='wr')
      $sql="SELECT DISTINCT t3.* FROM ".$sport."contracts AS t1,".$sport."districts AS t2,officials AS t3 WHERE t1.distid=t2.id AND t2.id='$distid' AND t1.offid=t3.id AND t1.confirm='y' ORDER BY t3.last,t3.first,t3.middle";
   else
      $sql="SELECT DISTINCT t3.* FROM ".$sport."contracts AS t1,".$sport."disttimes AS t2,officials AS t3 WHERE t1.disttimesid=t2.id AND t2.distid='$distid' AND t1.offid=t3.id ORDER BY t3.last,t3.first,t3.middle";
   $result=mysql_query($sql);
   $string="";
   while($row=mysql_fetch_array($result))
   {
      $string.="<p><b>$row[first] $row[middle] $row[last]</b></p><ul>";
      $string.="<li>Phone: ";
      if($row[homeph]!='')
         $string.="(H) (".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."&nbsp;&nbsp;";
      if($row[workph]!='')
         $string.="(W) (".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."&nbsp;&nbsp;";
      if($row[cellph]!='')
         $string.="(C) (".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4)."&nbsp;&nbsp;";
      $string.="</li><li>E-mail: <a class='small' href=\"mailto:$row[email]\">$row[email]</a></li>";
      $string.="<li>Address:<br>$row[address]<br>$row[city], $row[state] $row[zip]</li></ul>";
   }
   return $string;
}


if (function_exists('sendsemails')) {
    
} else {
	
   
	function sendsemails($session,$annid,$recipients=''){
	
		$recips=explode("<recipient>",$recipients);
		$replytoname="NSAA";
		 $sql="SELECT * FROM nsaaofficials.messages WHERE id='$annid'";
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


?>
