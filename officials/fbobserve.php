<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   $print=1; $sample=1;
   //header("Location:index.php?error=1");
   //exit();
}
else $sample=0;
$level=GetLevel($session);

if(!$dbname || $dbname=="" || $dbname=="nsaaofficialsthis") $dbname="$db_name2";

$fyear=substr(preg_replace("/[^0-9]/","",$dbname),0,4);	//FALL YEAR
if($dbname!=$db_name2 && $fyear<2012)
{
   //PRIOR TO 2012 - USE OLD FORM
   header("Location:2011fbobserve.php?dbname=$dbname&session=$session&sport=fb&gameid=$gameid&offid=$offid&obsid=$obsid");
   exit();
}
else if($fyear>=2012 && $fyear<=2013)
{
   //2012-2013
   header("Location:fbobserve2013.php?dbname=$dbname&session=$session&sport=fb&gameid=$gameid&offid=$offid&obsid=$obsid");
   exit();
}

if(!$obsid) $obsid=GetObsID($session);
if(!ereg("20052006",$dbname))
   $obsname=GetObsName($obsid);
else
{
   $sql="SELECT name FROM $dbname.logins WHERE id='$obsid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $obsname=$row[0];
}
if($obsid=="1") $obsname="NSAA";

if(!$gameid) $gameid=$game;
if($newgameid)
{
   $sql="UPDATE $dbname.fbobserve SET gameid='$newgameid' WHERE gameid='$gameid' AND obsid='$obsid'";
   $result=mysql_query($sql);
   $gameid=$newgameid;
}

if($saveeval || $submiteval || $savechanges)	//put eval in db and show user what he/she entered
{
   $home=addslashes($home);
   $visitor=addslashes($visitor);
   $site=addslashes($site);
   $refereename=addslashes($refereename);
   $umpirename=addslashes($umpirename);
   $linesmanname=addslashes($linesmanname);
   $linejudgename=addslashes($linejudgename);
   $backjudgename=addslashes($backjudgename);
   $weather=addslashes($weather);
   $fieldcond=addslashes($fieldcond);
   $quality=addslashes($quality);
   $appearance=addslashes($appearance);
   $pregame=addslashes($pregame);
   $cointoss=addslashes($cointoss);
   $measuringfirstdown=addslashes($measuringfirstdown);
   $presnap=addslashes($presnap);
   $srefereerun=addslashes($srefereerun);
   $srefereepass=addslashes($srefereepass);
   $sumpirerun=addslashes($sumpirerun);
   $sumpirepass=addslashes($sumpirepass);
   $slinesmanrun=addslashes($slinesmanrun);
   $slinesmanpass=addslashes($slinesmanpass);
   $slinejudgerun=addslashes($slinejudgerun);
   $slinejudgepass=addslashes($slinejudgepass);
   $sbackjudgerun=addslashes($sbackjudgerun);
   $sbackjudgepass=addslashes($sbackjudgepass);
   $kickoff=addslashes($kickoff);
   $punt=addslashes($punt);
   $patfg=addslashes($patfg);
   $goalline=addslashes($goalline);
   $adminpenalties=addslashes($adminpenalties);
   $timeout=addslashes($timeout);
   $btperiods=addslashes($btperiods);
   $bthalves=addslashes($bthalves);
   $sidelinebox=addslashes($sidelinebox);
   $gametempo=addslashes($gametempo);
   $recommendations=addslashes($recommendations);
   $comments=addslashes($comments);
   $dateeval=time();

   $sql="SELECT * FROM $dbname.fbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO fbobserve (obsid,offid,gameid,home,visitor,homescore,visitorscore,site,refereename,umpirename,linesmanname,linejudgename,backjudgename,slinesmanrun4,slinesmanpass4,slinejudgerun4,slinejudgepass4, ";
      for($i=1;$i<=10;$i++)
	 $sql2.="weather".$i.", ";
      $sql2.="weather,";
      for($i=1;$i<=3;$i++)
	 $sql2.="fieldcond".$i.", ";
      $sql2.="fieldcond,quality,";
      for($i=1;$i<=5;$i++)
	 $sql2.="appearance".$i.", ";
      $sql2.="appearance,";
      for($i=1;$i<=8;$i++)
	 $sql2.="pregame".$i.", ";
      $sql2.="pregame,";
      for($i=1;$i<=6;$i++)
	 $sql2.="cointoss".$i.", ";
      $sql2.="cointoss,referee,umpire,linesman,linejudge,backjudge,measuringfirstdown,";
      for($i=1;$i<=3;$i++)
	 $sql2.="psreferee".$i.",psumpire".$i.",pslinesman".$i.",pslinejudge".$i.",psbackjudge".$i.", ";
      $sql2.="presnap,";
      for($i=1;$i<=3;$i++)
         $sql2.="srefereerun".$i.",srefereepass".$i.",sumpirerun".$i.",sumpirepass".$i.",slinesmanrun".$i.",slinesmanpass".$i.",slinejudgerun".$i.",slinejudgepass".$i.",sbackjudgerun".$i.",sbackjudgepass".$i.", ";
      $sql2.="srefereerun,srefereepass,sumpirerun,sumpirepass,slinesmanrun,slinesmanpass,slinejudgerun,slinejudgepass,sbackjudgerun,sbackjudgepass, ";
      for($i=1;$i<=3;$i++)
         $sql2.="kreferee".$i.",kumpire".$i.",klinesman".$i.",klinejudge".$i.",kbackjudge".$i.",preferee".$i.",pumpire".$i.",plinesman".$i.",plinejudge".$i.",pbackjudge".$i.", ";
      for($i=1;$i<=8;$i++)
         $sql2.="kickoff".$i.", ";
      $sql2.="kickoff,punt, ";
      for($i=1;$i<=5;$i++)
         $sql2.="patfg".$i.", ";
      $sql2.="patfg,";
      for($i=1;$i<=3;$i++)
         $sql2.="goalline".$i.", ";
      $sql2.="goalline,apreferee,apumpire,aplinesman,aplinejudge,apbackjudge,adminpenalties,toreferee,toumpire,tolinesman,tolinejudge,tobackjudge,timeout,btpreferee,btpumpire,btplinesman,btplinejudge,btpbackjudge,btperiods,";
      for($i=1;$i<=5;$i++)
	 $sql2.="bthalves".$i.", ";
      $sql2.="bthalves,sidelinebox1,sidelinebox2,sidelinebox,gametempo,recommendations,comments,postseason,postlevelA,postlevelB,postlevelC,postlevelD) VALUES ('$obsid','$offid','$gameid','$home','$visitor','$homescore','$visitorscore','$site','$refereename','$umpirename','$linesmanname','$linejudgename','$backjudgename','$slinesmanrun4','$slinesmanpass4','$slinejudgerun4','$slinejudgepass4',";
      for($i=1;$i<=10;$i++)
      {
	 $var="weather".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$weather',";
      for($i=1;$i<=3;$i++)
      {
         $var="fieldcond".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$fieldcond','$quality',";
      for($i=1;$i<=5;$i++)
      {
         $var="appearance".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$appearance',";
      for($i=1;$i<=8;$i++)
      {
         $var="pregame".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$pregame',";
      for($i=1;$i<=6;$i++)
      {
         $var="cointoss".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$cointoss','$referee','$umpire','$linesman','$linejudge','$backjudge','$measuringfirstdown',";
      for($i=1;$i<=3;$i++)
      {
         $var1="psreferee".$i; $var2="psumpire".$i; $var3="pslinesman".$i; $var4="pslinejudge".$i; $var5="psbackjudge".$i;
         $sql2.="'".$$var1."','".$$var2."','".$$var3."','".$$var4."','".$$var5."',";
      }
      $sql2.="'$presnap',";
      for($i=1;$i<=3;$i++)
      {
         $run1="srefereerun".$i; $pass1="srefereepass".$i;
         $run2="sumpirerun".$i; $pass2="sumpirepass".$i;
         $run3="slinesmanrun".$i; $pass3="slinesmanpass".$i;
         $run4="slinejudgerun".$i; $pass4="slinejudgepass".$i;
         $run5="sbackjudgerun".$i; $pass5="sbackjudgepass".$i;
         $sql2.="'".$$run1."','".$$pass1."','".$$run2."','".$$pass2."','".$$run3."','".$$pass3."','".$$run4."','".$$pass4."','".$$run5."','".$$pass5."',";
      }
      $sql2.="'$srefereerun','$srefereepass','$sumpirerun','$sumpirepass','$slinesmanrun','$slinesmanpass','$slinejudgerun','$slinejudgepass','$sbackjudgerun','$sbackjudgepass',";
      for($i=1;$i<=3;$i++)
      {
         $k1="kreferee".$i; $k2="kumpire".$i; $k3="klinesman".$i; $k4="klinejudge".$i; $k5="kbackjudge".$i;
         $p1="preferee".$i; $p2="pumpire".$i; $p3="plinesman".$i; $p4="plinejudge".$i; $p5="pbackjudge".$i;
         $sql2.="'".$$k1."','".$$k2."','".$$k3."','".$$k4."','".$$k5."','".$$p1."','".$$p2."','".$$p3."','".$$p4."','".$$p5."',";
      }
      for($i=1;$i<=8;$i++)
      {
         $var="kickoff".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$kickoff','$punt',";
      for($i=1;$i<=5;$i++)
      {
         $var="patfg".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$patfg',";
      for($i=1;$i<=3;$i++)
      {
         $var="goalline".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$goalline','$apreferee','$apumpire','$aplinesman','$aplinejudge','$apbackjudge','$adminpenalties','$toreferee','$toumpire','$tolinesman','$tolinejudge','$tobackjudge','$timeout','$btpreferee','$btpumpire','$btplinesman','$btplinejudge','$btpbackjudge','$btperiods',";
      for($i=1;$i<=5;$i++)
      {
         $var="bthalves".$i; $sql2.="'".$$var."',";
      }
      $sql2.="'$bthalves','$sidelinebox1','$sidelinebox2','$sidelinebox','$gametempo','$recommendations','$comments','$postseason','$postlevelA','$postlevelB','$postlevelC','$postlevelD')";
   }
   else
   {
      $sql2="UPDATE fbobserve SET home='$home',visitor='$visitor',site='$site',homescore='$homescore',visitorscore='$visitorscore',refereename='$refereename',umpirename='$umpirename',linesmanname='$linesmanname',linejudgename='$linejudgename',backjudgename='$backjudgename',slinesmanrun4='$slinesmanrun4',slinesmanpass4='$slinesmanpass4',slinejudgerun4='$slinejudgerun4',slinejudgepass4='$slinejudgepass4',";
      for($i=1;$i<=10;$i++)
      {
         $var="weather".$i; $sql2.="$var='".$$var."',";
      }
      $sql2.="weather='$weather',";
      for($i=1;$i<=3;$i++)
      {
         $var="fieldcond".$i; $sql2.="$var='".$$var."',";
      }
      $sql2.="fieldcond='$fieldcond',quality='$quality',";
      for($i=1;$i<=5;$i++)
      {
         $var="appearance".$i; $sql2.="$var='".$$var."',";
      }
      $sql2.="appearance='$appearance',";
      for($i=1;$i<=8;$i++)
      {
         $var="pregame".$i; $sql2.="$var='".$$var."',";
      }
      $sql2.="pregame='$pregame',";
      for($i=1;$i<=6;$i++)
      {
         $var="cointoss".$i; $sql2.="$var='".$$var."',";
      }
      $sql2.="cointoss='$cointoss',referee='$referee',umpire='$umpire',linesman='$linesman',linejudge='$linejudge',backjudge='$backjudge',measuringfirstdown='$measuringfirstdown',";
      for($i=1;$i<=3;$i++)
      {
         $var1="psreferee".$i; $var2="psumpire".$i; $var3="pslinesman".$i; $var4="pslinejudge".$i; $var5="psbackjudge".$i;
         $sql2.="$var1='".$$var1."',$var2='".$$var2."',$var3='".$$var3."',$var4='".$$var4."',$var5='".$$var5."',";
      }
      $sql2.="presnap='$presnap',";
      for($i=1;$i<=3;$i++)
      {
         $run1="srefereerun".$i; $pass1="srefereepass".$i;
         $run2="sumpirerun".$i; $pass2="sumpirepass".$i;
         $run3="slinesmanrun".$i; $pass3="slinesmanpass".$i;
         $run4="slinejudgerun".$i; $pass4="slinejudgepass".$i;
         $run5="sbackjudgerun".$i; $pass5="sbackjudgepass".$i;
         $sql2.="$run1='".$$run1."',$pass1='".$$pass1."',$run2='".$$run2."',$pass2='".$$pass2."',$run3='".$$run3."',$pass3='".$$pass3."',$run4='".$$run4."',$pass4='".$$pass4."',$run5='".$$run5."',$pass5='".$$pass5."',";
      }
      $sql2.="srefereerun='$srefereerun',srefereepass='$srefereepass',sumpirerun='$sumpirerun',sumpirepass='$sumpirepass',slinesmanrun='$slinesmanrun',slinesmanpass='$slinesmanpass',slinejudgerun='$slinejudgerun',slinejudgepass='$slinejudgepass',sbackjudgerun='$sbackjudgerun',sbackjudgepass='$sbackjudgepass',";
      for($i=1;$i<=3;$i++)
      {
         $k1="kreferee".$i; $k2="kumpire".$i; $k3="klinesman".$i; $k4="klinejudge".$i; $k5="kbackjudge".$i;
         $p1="preferee".$i; $p2="pumpire".$i; $p3="plinesman".$i; $p4="plinejudge".$i; $p5="pbackjudge".$i;
         $sql2.="$k1='".$$k1."',$k2='".$$k2."',$k3='".$$k3."',$k4='".$$k4."',$k5='".$$k5."',$p1='".$$p1."',$p2='".$$p2."',$p3='".$$p3."',$p4='".$$p4."',$p5='".$$p5."',";
      }
      for($i=1;$i<=8;$i++)
      {
         $var="kickoff".$i; $sql2.="$var='".$$var."',";
      }
      $sql2.="kickoff='$kickoff',punt='$punt',";
      for($i=1;$i<=5;$i++)
      {
         $var="patfg".$i; $sql2.="$var='".$$var."',";
         //$var.="patfg".$i; $sql2.="'".$$var."',";
      }
      $sql2.="patfg='$patfg',";
      for($i=1;$i<=3;$i++)
      {
         $var="goalline".$i; $sql2.="$var='".$$var."',";
      }
      $sql2.="goalline='$goalline',apreferee='$apreferee',apumpire='$apumpire',aplinesman='$aplinesman',aplinejudge='$aplinejudge',apbackjudge='$apbackjudge',adminpenalties='$adminpenalties',toreferee='$toreferee',toumpire='$toumpire',tolinesman='$tolinesman',tolinejudge='$tolinejudge',tobackjudge='$tobackjudge',timeout='$timeout',btpreferee='$btpreferee',btpumpire='$btpumpire',btplinesman='$btplinesman',btplinejudge='$btplinejudge',btpbackjudge='$btpbackjudge',btperiods='$btperiods',";
      for($i=1;$i<=5;$i++)
      {
         $var="bthalves".$i; $sql2.="$var='".$$var."',";
      }
      //$sql2=substr($sql2,0,strlen($sql2)-1);
      $sql2.=" bthalves='$bthalves', sidelinebox1='$sidelinebox1',sidelinebox2='$sidelinebox2',sidelinebox='$sidelinebox',gametempo='$gametempo',recommendations='$recommendations',comments='$comments',postseason='$postseason',postlevelA='$postlevelA',postlevelB='$postlevelB',postlevelC='$postlevelC',postlevelD='$postlevelD'";
      $sql2.=" WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   }
   
   $result2=mysql_query($sql2);
   if($_SERVER['REMOTE_ADDR']=="70.94.10.34")
      echo "$sql2<br>".mysql_error();

   //if Saved, don't put dateeval in; if Submitted, do AND e-mail official that they have a new one
   if($submiteval=="Submit Evaluation")
   {
      $sql2="UPDATE fbobserve SET dateeval='$dateeval' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
      $result2=mysql_query($sql2);

      $sql2="SELECT first,last,email FROM $dbname.officials WHERE id='$offid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($row2[email]!="")	//e-mail provided
      {
	 $From="nsaa@nsaahome.criticalitgroup.com";
	 $FromName="NSAA";
	 $To=$row2[email];
	 $ToName="$row2[first] $row2[last]";
	 $Subject="An NSAA Official's Evaluation has been submitted for you";
	 $Text="A Nebraska School Activities Association Football Official's Evaluation has been filled out in your name.  Please login at http://nsaahome.criticalitgroup.com/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
	 $Html="A Nebraska School Activities Association Football Official's Evaluation has been filled out in your name.  Please login at <a href=\"http://nsaahome.criticalitgroup.com/nsaaforms/officials/\">http://nsaahome.criticalitgroup.com/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!";
	 $Attm=array();
	 if($obsid==22)
	 {
	 //   SendMail($From,$FromName,"agaffigan@gazelleincorporated.com","Ann Gaffigan",$Subject,$Text,$Html,$Attm);
	 }
         else
         {
	  //  SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
         }
      }
   }
}

//get answers if already submitted and only allow user to view, NOT edit
$sql="SELECT * FROM $dbname.fbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
//echo $sql;
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)>0)
{
   $sql2="DESCRIBE `fbobserve`";	//GET ALL DATA
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[0]!='offid' AND $row2[0]!='obsid')
      {
         $fieldname=$row2[0];
         $fieldvalue=$row[$fieldname];
         $$fieldname=$fieldvalue;
      }
   }  
}
if(mysql_num_rows($result)>0 && $row[dateeval]!="") 
{
   $submitted=1; $saved=0;
}
else if(mysql_num_rows($result)>0)
{
   $saved=1; $submitted=0;
}
else 
{
   $submitted=0; $saved=0;
}
if(mysql_num_rows($result)>0 && $levelofplay!="" && $levelofplay!="frosh" && $levelofplay!="jv" && $levelofplay!="var")	//level=other
{
   $levelofplay='other';
   $levelspec=$row[levelofplay];
}
if(mysql_num_rows($result)>0) $dateeval=date("F d, Y",$dateeval);
if($refereename=="" && $umpirename=='' && $linesmanname=='' && $linejudgename=='' && $backjudgename=='')	//PRE-POPULATE CREW MEMBERS
{
   $sql="SELECT referee,umpire,linesman,linejudge,backjudge FROM $dbname.fbapply WHERE offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $refereename=GetOffName($row[referee]);
   $umpirename=GetOffName($row[umpire]);
   $linesmanname=GetOffName($row[linesman]);
   $linejudgename=GetOffName($row[linejudge]);
   $backjudgename=GetOffName($row[backjudge]);
}

//get schools listed on this schedule entry
$sql="SELECT schools FROM $dbname.fbsched WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schools=$row[0];

echo $init_html;
?>
<style>
h3
{
   text-decoration:underline;
   margin: 4px 0px 6px 0px;
}
h4
{
   font-size:14px;
   margin:5px 0;
}
h5
{
   font-size:13px;
   margin-left:20px;
   margin-top:5px;
   margin-bottom:5px;
}
div.evalbox
{
   text-align:left;
   font-size:12px;
   width:800px;
   padding:3px;
   margin:5px;
}
</style>
<?php
echo "<table width='100%'><tr align=center><td>";
echo "<a href=\"javascript:window.close();\" class=small>CLOSE WINDOW</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"javascript:window.print();\" class=small>PRINT</a>";
echo "<br>";
if($submitted==1 && $submiteval=="Submit Evaluation")
{
   echo "<div class='alert' style=\"width:500px;\">Thank you for submitting your evaluation!  Your evaluation is shown below.</div><br><br>";
}

if($sample==0)
{
echo "<form method=post action=\"fbobserve.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=offid value=\"$offid\">";
echo "<input type=hidden name=gameid value=\"$gameid\">";
echo "<input type=hidden name=obsid value=\"$obsid\">";
}

if($saveeval=="Save & Keep Editing" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been saved.  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br>You must click \"Submit Evaluation\" at the bottom of this screen in order for your evaluation to be sent to the NSAA.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</b></font><br>";
else if($submiteval=="Submit Evaluation" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></font><br>";

echo "<br><table width='800px'><caption><b>NSAA Football Officials Evaluation Form:</b><br>";
if(!($print==1 && $level==2) && $sample==0)
{
   echo "(Evaluated by $obsname";
   if($submitted==1)
      echo " $dateeval";
   echo ")";
}
if(($level==1 || !$submitted) && $gameid && $gameid!='new')
{
   echo "<br><a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=fb&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
   if($level==1)
      echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"fbobserve.php?session=$session&dbname=$dbname&sport=fb&gameid=$gameid&obsid=$obsid&offid=$offid&edit=1\">Edit this Observation</a>";
}
echo "</caption></table>";

//if gameid='new', allow (NSAA) user to choose game and fill out eval
if($gameid=='new' || $go=="Go")
{
   echo "<div class='evalbox'><select name=gameid><option value='new'>Choose Game</option>";
   $sql="SELECT * FROM $dbname.fbsched WHERE offid='$offid' ORDER BY offdate";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value='$row[id]'";
      if($gameid==$row[id]) echo " selected";
      $date=split("-",$row[offdate]);
      $offdate="$date[1]/$date[2]";
      if($row[gametime]!="TBA")
      {
         $time=split("-",$row[gametime]);
         $gametime="$time[0]:$time[1]$time[2]";
      }
      else
      {
	 $gametime="Time: TBA";
      }
      echo ">$offdate $gametime @$row[location] ($row[schools])</option>";
   }
   echo "</select>&nbsp;<input type=submit name=\"go\"t value=\"Go\"></div>";
}

//array of answer options
$ans=array("Satisfactory","Unsatisfactory");

if($gameid && $gameid!="new" || $print==1)
{

echo "<div class='evalbox'><table cellspacing=0 cellpadding=3 class='nine'>";
if($print!=1)
{
   //MAIN INFORMATION ABOUT THE GAME AND THE CREW:
   //GAME/OBSERVATION DATE:
   $sql="SELECT offdate,location FROM $dbname.fbsched WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)	//CAN'T FIND THE GAME
   {
      echo "<tr align=left><td colspan=2><div style=\"width:650px;\" class=error><b>GAME #$gameid NOT FOUND.</b><br><br>";
      echo "The official may have changed their schedule in such a way that Game #$gameid is no longer in the system.  Please select the game you are reporting on from the list below.  If you do not see the game, please contact the official and ask him or her to enter it into their schedule.</div></td></tr>";
      echo "<tr align=left><td><b>Select Game:</b></td><td><select name=\"newgameid\" onchange=\"submit();\">";
      $sql="SELECT * FROM $dbname.fbsched WHERE offid='$offid' ORDER BY offdate";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $temp=split("-",$row[offdate]);
         echo "<option value='$row[id]'";
         if($gameid==$row[id]) echo " selected";
         echo ">$temp[1]/$temp[2]/$temp[0]: $row[schools]</option>";
      }
      echo "</select></td></tr>";
   }
   else					//FOUND THE GAME
   {
      echo "<tr align=left><td><b>Date of Observation:</b></td>";
      $temp=split("-",$row[0]);
      $offdate="$temp[1]/$temp[2]/$temp[0]";
      echo "<td>$offdate</td></tr>";
   }
   //GAME LOCATION (USER ENTERS)
   echo "<tr align=left><td><b>Game Location:</b></td>";
   if($submitted==1 && $edit!=1)
      echo "<td>$site</td></tr>";
   else    //by default, show location listed on official's schedule for this game
      echo "<td><input type=text class=tiny size=30 name=site value=\"$row[location]\"></td></tr>";
   //CREW MEMBERS:
	//CHIEF
   echo "<tr align=left><td><b>Crew Chief:</b></td>";
   $sql="SELECT first,last,city FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<td>$row[first] $row[last]</td></tr>";
   echo "<tr align=left><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>The crew chief entered the crew members below on their schedule for the season.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please edit their names below if they were different for this game than what is shown,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;or add the crew members' names if they have not yet been entered.</td></tr>";
	//OTHER MEMBERS:
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Referee:</td><td>";
   if($edit!=1 && $submitted==1) echo $refereename."</td></tr>";
   else echo "<input type=text name=\"refereename\" id=\"refereename\" size=30 value=\"$refereename\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Umpire:</td><td>";
   if($edit!=1 && $submitted==1) echo $umpirename."</td></tr>";
   else echo "<input type=text name=\"umpirename\" id=\"umpirename\" size=30 value=\"$umpirename\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Linesman:</td><td>";
   if($edit!=1 && $submitted==1) echo $linesmanname."</td></tr>";
   else echo "<input type=text name=\"linesmanname\" id=\"linesmanname\" size=30 value=\"$linesmanname\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Line Judge:</td><td>";
   if($edit!=1 && $submitted==1) echo $linejudgename."</td></tr>";
   else echo "<input type=text name=\"linejudgename\" id=\"linejudgename\" size=30 value=\"$linejudgename\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Back Judge:</td><td>";
   if($edit!=1 && $submitted==1) echo $backjudgename."</td></tr>";
   else echo "<input type=text name=\"backjudgename\" id=\"backjudgename\" size=30 value=\"$backjudgename\"></td></tr>";
}
else	//SHOW FILLABLE FIELDS FOR IF THEY PRINT A BLANK FORM
{
   echo "<tr align=left><td><b>Date of Observation:</b></td>";
   echo "<td><input type=text name=dateeval size=30></td></tr>";
   echo "<tr align=left><td><b>Game Location:</b></td>";
   echo "<td><input type=text name=gamesite size=30></td></tr>";
   echo "<tr align=left><td><b>Crew Chief:</b></td>";
   echo "<td><input type=text name=chief size=30></td></tr>";
   echo "<tr align=left><td align=right><b>Referee:</b></td>";
   echo "<td><input type=text name=refereename size=30></td></tr>";
   echo "<tr align=left><td align=right><b>Umpire:</b></td>";
   echo "<td><input type=text name=umpirename size=30></td></tr>";
   echo "<tr align=left><td align=right><b>Linesman:</b></td>";
   echo "<td><input type=text name=linesmanname size=30></td></tr>";
   echo "<tr align=left><td align=right><b>Line Judge:</b></td>";
   echo "<td><input type=text name=linejudgename size=30></td></tr>";
   echo "<tr align=left><td align=right><b>Back Judge:</b></td>";
   echo "<td><input type=text name=backjudgename size=30></td></tr>";
}
echo "<tr align=left><td><br><b>Home Team:</b></td>";
if($edit!=1 && $submitted==1)
   echo "<td><br>$home - $homescore</td></tr>";
else
   echo "<td><br><input type=text class=tiny size=30 name=home value=\"$home\">&nbsp;&nbsp;&nbsp;&nbsp;Score <input type=text size=3 name=\"homescore\" value=\"$homescore\"></td></tr>";
echo "<tr align=left><td><b>Visiting Team:</b></td>";
if($edit!=1 && $submitted==1)
   echo "<td>$visitor - $visitorscore</td></tr>";
else
   echo "<td><input type=text class=tiny size=30 name=visitor value=\"$visitor\">&nbsp;&nbsp;&nbsp;&nbsp;Score <input type=text size=3 name=\"visitorscore\" value=\"$visitorscore\"></td></tr>";
/*
echo "<tr align=left><td><b>Level:</b></td>";
if($edit!=1 && $submitted==1)
{
   echo "<td>".strtoupper($levelofplay);
   if($levelofplay=='other')
      echo ": $levelspec";
   echo "</td>";
}
else
{
   echo "<td><input type=radio name=levelofplay value='frosh'";
   if($levelofplay=='frosh') echo " checked";
   echo ">Frosh&nbsp;";
   echo "<input type=radio name=levelofplay value='jv'";
   if($levelofplay=='jv') echo " checked";
   echo ">JV&nbsp;";
   echo "<input type=radio name=levelofplay value='var'";
   if($levelofplay=='var') echo " checked";
   echo ">Varsity&nbsp;";
   echo "<input type=radio name=levelofplay value='other'";
   if($levelofplay=='other') echo " checked";
   echo ">Other (specify)&nbsp;";
   echo "<input type=text name=levelspec size=20 class=tiny";
   if($levelofplay=='other') echo " value=\"$levelspec\"";
   echo "></td>";
}
echo "</tr>";
*/
echo "</table></div>";


//WEATHER
	//Weather criteria:
	$curcriteria=array("Hot","Warm","Chilly","Cold","Windy","Breezy","Calm","Rain","Drizzle","Snow");
echo "<div class='evalbox'><h3>Weather Conditions:</h3>";
echo "<table class='nine' cellspacing=0 cellpadding=4><tr align=left>";
for($i=0;$i<count($curcriteria);$i++)
{
   $ix=$i+1;
   $curvar="weather".$ix;
   echo "<td><input type=checkbox name=\"$curvar\" value=\"x\"";
   if($$curvar=='x') echo " checked";
   echo "> $curcriteria[$i]</td>";
   if($i==3) 
      echo "<td>&nbsp;</td><td>&nbsp;</td></tr><tr align=left>";
}
echo "</tr></table><br>";
if($edit!=1 && $submitted==1)
   echo "Comments: $weather</div>";
else
   echo "Comments:<br><textarea style=\"width:700px;height:50px;\" name=weather>$weather</textarea></div>";

//FIELD CONDITIONS:
echo "<div class='evalbox'><h3>Field Conditions:</h3>";
echo "<table class='nine' cellspacing=0 cellpadding=4><tr align=left>";
echo "<td><input type=checkbox name=\"fieldcond1\" value=\"x\"";
if($fieldcond1=='x') echo " checked";
echo "> Field Turf</td>";
echo "<td><input type=checkbox name=\"fieldcond2\" value=\"x\"";
if($fieldcond2=='x') echo " checked";
echo "> Grass</td>";
echo "<td><input type=checkbox name=\"fieldcond3\" value=\"x\"";
if($fieldcond3=='x') echo " checked";
echo "> Field marked correctly</td>";
echo "</tr></table><br>";
if($edit!=1 && $submitted==1)
   echo "Comments: $fieldcond</div>";
else
   echo "Comments:<br><textarea style=\"width:700px;height:50px;\" name=\"fieldcond\">$fieldcond</textarea></div>";

//QUALITY OF GAME
//if($print==1)
  // echo "<div class='evalbox' style=\"page-break-after:always;\">";
//else
   echo "<div class='evalbox'>";
echo "<h3>Quality of Game/Unusual Circumstances:</h3>";
if($edit!=1 && $submitted==1)
   echo "$quality</div>";
else
   echo "<textarea style=\"width:700px;height:50px;\" name=\"quality\">$quality</textarea></div>";

//PHYSICAL APPEARANCE OF CREW
echo "<div class='evalbox'><h3>Physical Appearance of Crew:</h3>";
echo "<table class='nine' cellspacing=0 cellpadding=4><tr align=left>";
echo "<td><input type=checkbox name=\"appearance1\" value=\"x\"";
if($appearance1=='x') echo " checked";
echo "> Clean matching uniforms</td>";
echo "<td><input type=checkbox name=\"appearance2\" value=\"x\"";
if($appearance2=='x') echo " checked";
echo "> Black shoes(Minimal White)</td>";
echo "<td><input type=checkbox name=\"appearance3\" value=\"x\"";
if($appearance3=='x') echo " checked";
echo "> No flags showing</td>";
echo "</tr><tr align=left>";
echo "<td><input type=checkbox name=\"appearance4\" value=\"x\"";
if($appearance4=='x') echo " checked";
echo "> Stand tall, professional demeanor</td>";
/* echo "<td colspan=2><input type=checkbox name=\"appearance5\" value=\"x\"";
if($appearance5=='x') echo " checked";
echo "> No crossed arms, hands on hips, hands in pockets</td>"; */
echo "</tr></table><br>";
if($edit!=1 && $submitted==1)
   echo "Comments: $appearance</div>";
else 
   echo "Comments:<br><textarea style=\"width:700px;height:50px;\" name=\"appearance\">$appearance</textarea></div>";


//PRE-GAME RESPONSIBILITIES
echo "<div class='evalbox'><h3>Pre-Game Responsibilities:</h3>";
echo "<table class='nine' cellspacing=0 cellpadding=4><tr align=left>";
echo "<td><input type=checkbox name=\"pregame1\" value=\"x\"";
if($pregame1=='x') echo " checked";
echo "> On field at least 30 minutes prior to kick-off</td>";
echo "<td colspan=2><input type=checkbox name=\"pregame2\" value=\"x\"";
if($pregame2=='x') echo " checked";
echo "> 1st - Meet home coach then visitor coach</td>";
echo "</tr><tr align=left>";
echo "<td><input type=checkbox name=\"pregame3\" value=\"x\"";
if($pregame3=='x') echo " checked";
echo "> Check line to gain equipment and meet crew</td>";
echo "<td colspan=2><input type=checkbox name=\"pregame4\" value=\"x\"";
if($pregame4=='x') echo " checked";
echo "> Meet with clock operator (In locker room or on field)</td>";
echo "</tr><tr align=left>";
echo "<td><input type=checkbox name=\"pregame5\" value=\"x\"";
if($pregame5=='x') echo " checked";
echo "> R & LJ on 40s (Press box side)</td>";
echo "<td><input type=checkbox name=\"pregame6\" value=\"x\"";
if($pregame6=='x') echo " checked";
echo "> U on 50, L& BJ on 40s (Chain Side)</td>";
echo "<td><input type=checkbox name=\"pregame7\" value=\"x\"";
if($pregame7=='x') echo " checked";
echo "> Inspect playing field & pylons</td></tr><tr>";
echo "<td><input type=checkbox name=\"pregame8\" value=\"x\"";
if($pregame8=='x') echo " checked";
echo "> Move about field to observe warmups and report uniform violations</td>";
echo "</tr></table><br>";
if($edit!=1 && $submitted==1)
   echo "Comments: $pregame</div>";
else
   echo "Comments:<br><textarea style=\"width:700px;height:50px;\" name=\"pregame\">$pregame</textarea></div>";

//COIN TOSS
echo "<div class='evalbox'><h3>Coin Toss:</h3>";
echo "<table class='nine' cellspacing=0 cellpadding=4><tr align=left>";
echo "<td><input type=checkbox name=\"cointoss1\" value=\"x\"";
if($cointoss1=='x') echo " checked";
echo "> 20 minutes prior to kick-off</td>";
echo "<td><input type=checkbox name=\"cointoss2\" value=\"x\"";
if($cointoss2=='x') echo " checked";
echo "> Press box side of field (Near sideline)</td>";
echo "<td><input type=checkbox name=\"cointoss3\" value=\"x\"";
if($cointoss3=='x') echo " checked";
echo "> Captains' backs to their sideline</td>";
echo "</tr><tr align=left>";
echo "<td><input type=checkbox name=\"cointoss4\" value=\"x\"";
if($cointoss4=='x') echo " checked";
echo "> Crew stand behind Umpire</td>";
echo "<td><input type=checkbox name=\"cointoss5\" value=\"x\"";
if($cointoss5=='x') echo " checked";
echo "> Introduce the entire crew</td>";
echo "<td><input type=checkbox name=\"cointoss6\" value=\"x\"";
if($cointoss6=='x') echo " checked";
echo "> R - Face the press box to signal</td>";
echo "</tr></table><br>";
if($edit!=1 && $submitted==1)
   echo "Comments: $cointoss</div>";
else
   echo "Comments:<br><textarea style=\"width:700px;height:50px;\" name=\"cointoss\">$cointoss</textarea></div>";

//KICK-OFF
echo "<div class='evalbox'><h3>Kick-Off</h3>";
        //Referee
echo "<table class='nine' cellspacing=0 cellpadding=3 width='800px'><tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Referee:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td width='35%'><input type=checkbox name=\"kreferee1\" value=\"x\"";
if($kreferee1=='x') echo " checked";
echo "> R's GL - L's side - Just inside of hash</td><td width='25%'><input type=checkbox name=\"kreferee2\" value=\"x\"";
if($kreferee2=='x') echo " checked";
echo "> Observe action not ball</td><td><input type=checkbox name=\"kreferee3\" value=\"x\"";
if($kreferee3=='x') echo " checked";
echo "> Follow runner - release to covering official</td></tr>";
echo "</table></td></tr>";
        //Umpire
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Umpire:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td width='35%'><input type=checkbox name=\"kumpire1\" value=\"x\"";
if($kumpire1=='x') echo " checked";
echo "> R's 20 yd line - opposite side of linesman</td><td width='25%'><input type=checkbox name=\"kumpire2\" value=\"x\"";
if($kumpire2=='x') echo " checked";
echo "> Observe action not ball</td><td><input type=checkbox name=\"kumpire3\" value=\"x\"";
if($kumpire3=='x') echo " checked";
echo "> Maintain sideline coverage at all times</td></tr>";
echo "</table></td></tr>";
        //Linesman
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Linesman:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td width='35%'><input type=checkbox name=\"klinesman1\" value=\"x\"";
if($klinesman1=='x') echo " checked";
echo "> R's 30 yd line - same side as chains</td><td width='25%'><input type=checkbox name=\"klinesman2\" value=\"x\"";
if($klinesman2=='x') echo " checked";
echo "> Observe action not ball</td><td><input type=checkbox name=\"klinesman3\" value=\"x\"";
if($klinesman3=='x') echo " checked";
echo "> Maintain sideline coverage at all times</td></tr>";
echo "</table></td></tr>";
        //Line Judge
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Line Judge:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td width='35%'><input type=checkbox name=\"klinejudge1\" value=\"x\"";
if($klinejudge1=='x') echo " checked";
echo "> R's free kick line - opposite side of linesman</td><td width='25%'><input type=checkbox name=\"klinejudge2\" value=\"x\"";
if($klinejudge2=='x') echo " checked";
echo "> Observe action not ball</td><td><input type=checkbox name=\"klinejudge3\" value=\"x\"";
if($klinejudge3=='x') echo " checked";
echo "> Cover down field as kick requires</td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Back Judge:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td width='35%'><input type=checkbox name=\"kbackjudge1\" value=\"x\"";
if($kbackjudge1=='x') echo " checked";
echo "> K's free kick line - same side as chains</td><td width='25%'><input type=checkbox name=\"kbackjudge2\" value=\"x\"";
if($kbackjudge2=='x') echo " checked";
echo "> Observe action not ball</td><td><input type=checkbox name=\"kbackjudge3\" value=\"x\"";
if($kbackjudge3=='x') echo " checked";
echo "> Move to center of field/inside-out coverage</td></tr>";
echo "</table></td></tr>";
        //Other Kick off:
echo "<tr align=left><td colspan=3><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3>";
echo "<tr align=left>";
echo "<td width='43%'><input type=checkbox name=\"kickoff1\" value=\"x\"";
if($kickoff1=='x') echo " checked";
echo "> One minute after PAT and successful field goal.</td>";
echo "<td><input type=checkbox name=\"kickoff2\" value=\"x\"";
if($kickoff2=='x') echo " checked";
echo "> Back Judge & Line Judge - Move up own sideline to clear for play.</td>";
echo "</tr>";
echo "<tr align=left>";
echo "<td width='43%'><input type=checkbox name=\"kickoff3\" value=\"x\"";
if($kickoff3=='x') echo " checked";
echo "> Only covering official(s) start/stop clock.</td>";
echo "<td><input type=checkbox name=\"kickoff4\" value=\"x\"";
if($kickoff4=='x') echo " checked";
echo "> Back Judge - responsible for K goal line</td>";
echo "</tr>";
echo "<tr align=left>";
echo "<td width='43%'><input type=checkbox name=\"kickoff5\" value=\"x\"";
if($kickoff5=='x') echo " checked";
echo "> All officials have bag in hand.</td>";
echo "<td><input type=checkbox name=\"kickoff6\" value=\"x\"";
if($kickoff6=='x') echo " checked";
echo "> All officials hand over head to signal ready.</td>";
echo "</tr>";
echo "<tr align=left>";
echo "<td width='43%'><input type=checkbox name=\"kickoff7\" value=\"x\"";
if($kickoff7=='x') echo " checked";
echo "> Back Judge - Give kicker instructions on the field.</td>";
echo "<td><input type=checkbox name=\"kickoff8\" value=\"x\"";
if($kickoff8=='x') echo " checked";
echo "> Line Judge - Temporarily move to 9 yd marks/identify free-kick line.</td>";
echo "</tr>";
echo "</table>";
echo "<br>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $kickoff</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"kickoff\">$kickoff</textarea></td></tr>";
echo "</table></div>";

//PRE-SNAP ALIGNMENT (SCRIMMAGE PLAYS)
echo "<div class='evalbox'><h3>Pre-Snap Alignment (Scrimmage Plays)</h3>";
echo "<table class='nine' cellspacing=0 cellpadding=4>";
	//Referee
echo "<tr align=left><td><b>Referee:</b></td><td><input type=checkbox name=\"psreferee1\" value=\"x\"";
if($psreferee1=='x') echo " checked";
echo "> Step out to signal RFP, back out</td><td><input type=checkbox name=\"psreferee2\" value=\"x\"";
if($psreferee2=='x') echo " checked";
echo "> 7 yds/7 yds from deepest back</td><td><input type=checkbox name=\"psreferee3\" value=\"x\"";
if($psreferee3=='x') echo " checked";
echo "> Passing arm side of QB</td></tr>";
	//Umpire
echo "<tr align=left><td><b>Umpire:</b></td><td><input type=checkbox name=\"psumpire1\" value=\"x\"";
if($psumpire1=='x') echo " checked";
echo "> Stay on ball until RFP, back out</td><td><input type=checkbox name=\"psumpire2\" value=\"x\"";
if($psumpire2=='x') echo " checked";
echo "> 1 yd deeper than linebackers</td><td><input type=checkbox name=\"psumpire3\" value=\"x\"";
if($psumpire3=='x') echo " checked";
echo "> Vary position between tackles</td></tr>";
	//Linesman
echo "<tr align=left><td><b>Linesman:</b></td><td><input type=checkbox name=\"pslinesman1\" value=\"x\"";
if($pslinesman1=='x') echo " checked";
echo "> Start out of bounds</td><td><input type=checkbox name=\"pslinesman2\" value=\"x\"";
if($pslinesman2=='x') echo " checked";
echo "> Move in based on formation</td><td><input type=checkbox name=\"pslinesman3\" value=\"x\"";
if($pslinesman3=='x') echo " checked";
echo "> Establish LOS with foot nearest offense</td></tr>";
	//Line Judge
echo "<tr align=left><td><b>Line Judge:</b></td><td><input type=checkbox name=\"pslinejudge1\" value=\"x\"";
if($pslinejudge1=='x') echo " checked";
echo "> Start out of bounds</td><td><input type=checkbox name=\"pslinejudge2\" value=\"x\"";
if($pslinejudge2=='x') echo " checked";
echo "> Move in based on formation</td><td><input type=checkbox name=\"pslinejudge3\" value=\"x\"";
if($pslinejudge3=='x') echo " checked";
echo "> Establish LOS with foot nearest offense</td></tr>";
	//Back Judge
echo "<tr align=left><td><b>Back Judge:</b></td><td><input type=checkbox name=\"psbackjudge1\" value=\"x\"";
if($psbackjudge1=='x') echo " checked";
echo "> Approximately 20 yds off LOS</td><td><input type=checkbox name=\"psbackjudge2\" value=\"x\"";
if($psbackjudge2=='x') echo " checked";
echo "> Position to formation strength</td><td><input type=checkbox name=\"psbackjudge3\" value=\"x\"";
if($psbackjudge3=='x') echo " checked";
echo "> Don't step back on snap</td></tr>";
echo "</table><br>";
if($edit!=1 && $submitted==1)
   echo "Comments: $presnap</div>";
else
   echo "Comments:<br><textarea style=\"width:700px;height:50px;\" name=\"presnap\">$presnap</textarea></div>";

//SCRIMMAGE PLAYS
echo "<div class='evalbox'><h3>Scrimmage Plays</h3>";
	//Referee
echo "<table class='nine' cellspacing=0 cellpadding=3 width='800px'><tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Referee:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;\">";
echo "<tr align=left><td colspan=3><b>Run Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"srefereerun1\" value=\"x\"";
if($srefereerun1=='x') echo " checked";
echo "> Clean up behind play</td><td><input type=checkbox name=\"srefereerun2\" value=\"x\"";
if($srefereerun2=='x') echo " checked";
echo "> Cover wide plays to the sideline</td><td><input type=checkbox name=\"srefereerun3\" value=\"x\"";
if($srefereerun3=='x') echo " checked";
echo "> Help Umpire on short plays</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $srefereerun</div>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"srefereerun\">$srefereerun</textarea></div>";
echo "<tr align=left><td colspan=3><b>Pass Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"srefereepass1\" value=\"x\"";
if($srefereepass1=='x') echo " checked";
echo "> Stay with passer</td><td><input type=checkbox name=\"srefereepass2\" value=\"x\"";
if($srefereepass2=='x') echo " checked";
echo "> Observe blocks behind neutral zone</td><td><input type=checkbox name=\"srefereepass3\" value=\"x\"";
if($srefereepass3=='x') echo " checked";
echo "> Communicate \"Balls Away\"</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $srefereepass</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"srefereepass\">$srefereepass</textarea></td></tr>";
echo "</table></td></tr>";
	//Umpire
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Umpire:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;\">";
echo "<tr align=left><td colspan=3><b>Run Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"sumpirerun1\" value=\"x\"";
if($sumpirerun1=='x') echo " checked";
echo "> Slide/pivot to avoid contact</td><td><input type=checkbox name=\"sumpirerun2\" value=\"x\"";
if($sumpirerun2=='x') echo " checked";
echo "> Follow play inside out</td><td><input type=checkbox name=\"sumpirerun3\" value=\"x\"";
if($sumpirerun3=='x') echo " checked";
echo "> Move with play (Hash to hash)</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $sumpirerun</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"sumpirerun\">$sumpirerun</textarea></td></tr>";
echo "<tr align=left><td colspan=3><b>Pass Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"sumpirepass1\" value=\"x\"";
if($sumpirepass1=='x') echo " checked";
//echo "> Step up to the expanded neutral zone</td><td><input type=checkbox name=\"sumpirepass2\" value=\"x\"";
echo "> Step aggressively to neutral zone</td><td><input type=checkbox name=\"sumpirepass2\" value=\"x\"";
if($sumpirepass2=='x') echo " checked";
echo "> Stay with blockers after pass</td><td><input type=checkbox name=\"sumpirepass3\" value=\"x\"";
if($sumpirepass3=='x') echo " checked";
echo "> Cover short passes over middle</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $sumpirepass</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"sumpirepass\">$sumpirepass</textarea></td></tr>";
echo "</table></td></tr>";
	//Linesman
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Linesman:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;\">";
echo "<tr align=left><td colspan=3><b>Run Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"slinesmanrun1\" value=\"x\"";
if($slinesmanrun1=='x') echo " checked";
echo "> Pause until ball crosses LOS</td><td><input type=checkbox name=\"slinesmanrun2\" value=\"x\"";
if($slinesmanrun2=='x') echo " checked";
echo "> Squeeze & clean up backside (To hash)</td></tr><tr align=left><td><input type=checkbox name=\"slinesmanrun4\" value=\"x\"";
if($slinesmanrun4=='x') echo " checked";
echo "> Slide parallel to sideline</td><td><input type=checkbox name=\"slinesmanrun3\" value=\"x\"";
if($slinesmanrun3=='x') echo " checked";
echo "> Move in for spot (Square off)</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $slinesmanrun</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"slinesmanrun\">$slinesmanrun</textarea></td></tr>";
echo "<tr align=left><td colspan=3><b>Pass Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"slinesmanpass1\" value=\"x\"";
if($slinesmanpass1=='x') echo " checked";
echo "> Pause until WR is 10/12 yd downfield</td><td><input type=checkbox name=\"slinesmanpass2\" value=\"x\"";
if($slinesmanpass2=='x') echo " checked";
echo "> Squeeze & clean up backside (To hash)</td></tr><tr align=left><td><input type=checkbox name=\"slinesmanpass4\" value=\"x\"";
if($slinesmanpass4=='x') echo " checked";
echo "> Slide parallel to sideline</td><td><input type=checkbox name=\"slinesmanpass3\" value=\"x\"";
if($slinesmanpass3=='x') echo " checked";
echo "> Move in for spot (Square off)</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $slinesmanpass</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"slinesmanpass\">$slinesmanpass</textarea></td></tr>";
echo "</table></td></tr>";
	//Line Judge
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Line Judge:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;\">";
echo "<tr align=left><td colspan=3><b>Run Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"slinejudgerun1\" value=\"x\"";
if($slinejudgerun1=='x') echo " checked";
echo "> Pause until ball crosses LOS</td><td><input type=checkbox name=\"slinejudgerun2\" value=\"x\"";
if($slinejudgerun2=='x') echo " checked";
echo "> Squeeze & clean up backside (To hash)</td></tr><tr align=left><td><input type=checkbox name=\"slinejudgerun4\" value=\"x\"";
if($slinejudgerun4=='x') echo " checked";
echo "> Slide parallel to sideline</td><td><input type=checkbox name=\"slinejudgerun3\" value=\"x\"";
if($slinejudgerun3=='x') echo " checked";
echo "> Move in for spot (Square off)</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $slinejudgerun</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"slinejudgerun\">$slinejudgerun</textarea></td></tr>";
echo "<tr align=left><td colspan=3><b>Pass Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"slinejudgepass1\" value=\"x\"";
if($slinejudgepass1=='x') echo " checked";
echo "> Pause until WR is 10/12 yd downfield</td><td><input type=checkbox name=\"slinejudgepass2\" value=\"x\"";
if($slinejudgepass2=='x') echo " checked";
echo "> Squeeze & clean up backside (To hash)</td></tr><tr align=left><td><input type=checkbox name=\"slinejudgepass4\" value=\"x\"";
if($slinejudgepass4=='x') echo " checked";
echo "> Slide parallel to sideline</td><td><input type=checkbox name=\"slinejudgepass3\" value=\"x\"";
if($slinejudgepass3=='x') echo " checked";
echo "> Move in for spot (Square off)</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $slinejudgepass</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"slinejudgepass\">$slinejudgepass</textarea></td></tr>";
echo "</table></td></tr>";
	//Back Judge
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Back Judge:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;\">";
echo "<tr align=left><td colspan=3><b>Run Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"sbackjudgerun1\" value=\"x\"";
if($sbackjudgerun1=='x') echo " checked";
echo "> Inside out Coverage</td><td><input type=checkbox name=\"sbackjudgerun2\" value=\"x\"";
if($sbackjudgerun2=='x') echo " checked";
echo "> Sideline to sideline coverage</td><td><input type=checkbox name=\"sbackjudgerun3\" value=\"x\"";
if($sbackjudgerun3=='x') echo " checked";
echo "> Assist with ball relay</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $sbackjudgerun</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"sbackjudgerun\">$sbackjudgerun</textarea></td></tr>";
echo "<tr align=left><td colspan=3><b>Pass Play</b></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"sbackjudgepass1\" value=\"x\"";
if($sbackjudgepass1=='x') echo " checked";
echo "> Stay deeper than all receivers</td><td><input type=checkbox name=\"sbackjudgepass2\" value=\"x\"";
if($sbackjudgepass2=='x') echo " checked";
echo "> Move quickly to ball</td><td><input type=checkbox name=\"sbackjudgepass3\" value=\"x\"";
if($sbackjudgepass3=='x') echo " checked";
echo "> Assist with ball relay</td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $sbackjudgepass</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"sbackjudgepass\">$sbackjudgepass</textarea></td></tr>";
echo "</table></td></tr></table></div>";

//MEASURING FOR FIRST DOWN
echo "<div class='evalbox'><h3>Measuring for First Down:</h3>";
echo "<table class='nine' cellspacing=0 cellpadding=4>";
echo "<tr align=left><td><b>Referee:</b></td><td><input type=checkbox name=\"referee\" value=\"x\"";
if($referee=='x') echo " checked";
echo "> Signal Linesman to bring the chains. Rotate ball so long axis is parallel to the sideline.</td></tr>";
echo "<tr align=left><td><b>Umpire:</b></td><td><input type=checkbox name=\"umpire\" value=\"x\"";
if($umpire=='x') echo " checked";
echo "> Take forward indicator from crewman at spot. Pull tight when Linesman says \"Ready.\"</td></tr>";
echo "<tr align=left><td><b>Linesman:</b></td><td><input type=checkbox name=\"linesman\" value=\"x\"";
if($linesman=='x') echo " checked";
echo "> Bring line-to-gain indicator to spot. Hold firmly, communicate to Umpire when \"Ready.\"</td></tr>";
echo "<tr align=left><td><b>Line Judge:</b></td><td><input type=checkbox name=\"linejudge\" value=\"x\"";
if($linejudge=='x') echo " checked";
echo "> Place foot behind clip to align Linesman and keep chains parallel.</td></tr>";
echo "<tr align=left><td><b>Back Judge:</b></td><td><input type=checkbox name=\"backjudge\" value=\"x\"";
if($backjudge=='x') echo " checked";
echo "> Clear players from measurement area. Tend ball at time of measurement.</td></tr>";
echo "</table><br>";
if($edit!=1 && $submitted==1)
   echo "Comments: $measuringfirstdown</div>";
else
   echo "Comments:<br><textarea style=\"width:700px;height:50px;\" name=\"measuringfirstdown\">$measuringfirstdown</textarea></div>";

//PUNT
echo "<div class='evalbox'><h3>Punt</h3>";
        //Referee
echo "<table class='nine' cellspacing=0 cellpadding=3 width='800px'><tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Referee:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td><input type=checkbox name=\"preferee1\" value=\"x\"";
if($preferee1=='x') echo " checked";
echo "> 3-5 yds outside TE, 2-3 yds behind kicker, kicking leg side&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"preferee2\" value=\"x\"";
if($preferee2=='x') echo " checked";
echo "> Signal to protect snapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"preferee3\" value=\"x\"";
if($preferee3=='x') echo " checked";
echo "> Protect kicker</td></tr>";
echo "</table></td></tr>";
        //Umpire
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Umpire:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td><input type=checkbox name=\"pumpire1\" value=\"x\"";
if($pumpire1=='x') echo " checked";
echo "> 10 yards deep on LJ side&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"pumpire2\" value=\"x\"";
if($pumpire2=='x') echo " checked";
echo "> Step to neutral zone, protect snapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"pumpire3\" value=\"x\"";
if($pumpire3=='x') echo " checked";
echo "> Pivot to view LJ side then cover downfield</td></tr>";
echo "</table></td></tr>";
	//Linesman
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Linesman:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td><input type=checkbox name=\"plinesman1\" value=\"x\"";
if($plinesman1=='x') echo " checked";
echo "> Align on LOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"plinesman2\" value=\"x\"";
if($plinesman2=='x') echo " checked";
echo "> Hold until ball crosses neutral zone; then move slowly downfield&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"plinesman3\" value=\"x\"";
if($plinesman3=='x') echo " checked";
echo "> Move chains after R signals</td></tr>";
echo "</table></td></tr>";
	//Line Judge
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Line Judge:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td><input type=checkbox name=\"plinejudge1\" value=\"x\"";
if($plinejudge1=='x') echo " checked";
echo "> Align on LOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"plinejudge2\" value=\"x\"";
if($plinejudge2=='x') echo " checked";
echo "> Release aggressively downfield on the snap&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"plinejudge3\" value=\"x\"";
if($plinejudge3=='x') echo " checked";
echo "> Help BJ with fair catch signal</td></tr>";
echo "</table></td></tr>";
	//Back Judge
echo "<tr align=left><td colspan=3>&nbsp;&nbsp;&nbsp;<b>Back Judge:</b><br>";
echo "<table class='nine' cellspacing=0 cellpadding=3 style=\"margin-left:25px;width:700px;\">";
echo "<tr align=left><td><input type=checkbox name=\"pbackjudge1\" value=\"x\"";
if($pbackjudge1=='x') echo " checked";
echo "> 10-12 yds wide, 2-3 yds deeper than returner, L side&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"pbackjudge2\" value=\"x\"";
if($pbackjudge2=='x') echo " checked";
echo "> Communicate with returner&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"pbackjudge3\" value=\"x\"";
if($pbackjudge3=='x') echo " checked";
echo "> Bag all punts</td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td colspan=3>Comments:";
if($edit!=1 && $submitted==1)
   echo " $punt</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"punt\">$punt</textarea></td></tr>";
echo "</table></div>";

//PAT/FG
echo "<div class='evalbox'><h3>PAT/FG</h3>";
echo "<div style='margin-left:25px;'>";
echo "<p><input type=checkbox name=\"patfg1\" value=\"x\"";
if($patfg1=='x') echo " checked";
echo "> Scrimmage play alignment vs Swinging Gate.</p>";
echo "<p><input type=checkbox name=\"patfg2\" value=\"x\"";
if($patfg2=='x') echo " checked";
echo "> Proper rotation for PAT/FG.</p>";
echo "<p><input type=checkbox name=\"patfg3\" value=\"x\"";
if($patfg3=='x') echo " checked";
echo "> Officials under goal posts step out and signal simultaneously.</p>";
echo "<p><input type=checkbox name=\"patfg4\" value=\"x\"";
if($patfg4=='x') echo " checked";
echo "> Referee whistle immediately on PAT kicks.</p>";
echo "<p><input type=checkbox name=\"patfg5\" value=\"x\"";
if($patfg5=='x') echo " checked";
echo "> Umpire widen over TE to open side (4 to 7 yds deep).</p>";
echo "</div><br>";
//echo "</td></tr>";
echo "Comments:";
if($edit!=1 && $submitted==1)
   echo " $patfg</div>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"patfg\">$patfg</textarea></div>";

//Goal-Line Play
echo "<div class='evalbox'><h3>Goal-Line Play</h3>";
echo "<div style='margin-left:25px;'>";
echo "<p><input type=checkbox name=\"goalline1\" value=\"x\"";
if($goalline1=='x') echo " checked";
echo "> Ball placed between 10 & 5 yd line - LJ and L release slowly, stay ahead of runner / BJ start on endline.</p>";
echo "<p><input type=checkbox name=\"goalline2\" value=\"x\"";
if($goalline2=='x') echo " checked";
echo "> Ball placed inside 5 yd line - LJ and L release to goal line officiate back.</p>";
echo "<p><input type=checkbox name=\"goalline3\" value=\"x\"";
if($goalline3=='x') echo " checked";
echo "> Only officials who actually see touchdown give touchdown signal.</p>";
echo "Comments:";
if($edit!=1 && $submitted==1)
   echo " $goalline</div>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"goalline\">$goalline</textarea></div>";

//GAME MANAGEMENT/COMMUNICATION
echo "<div class='evalbox'><h3>GAME MANAGEMENT/COMMUNICATION</h3>";
echo "<table class='nine' style=\"margin-left:14px;\">";
	//Administering Penalties
echo "<tr align=left><td><h4>Administering Penalties</h4>";
echo "<h5>Referee</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"apreferee\" value=\"x\"";
if($apreferee=='x') echo " checked";
echo "> Give preliminary signal & final signal. [Dead ball penalty (encroachment, false start, delay of game) - 1 signal]</p>";
echo "<h5>Umpire</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"apumpire\" value=\"x\"";
if($apumpire=='x') echo " checked";
echo "> Secure ball, briskly step off penalty yards.</p>";
echo "<h5>Linesman</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"aplinesman\" value=\"x\"";
if($aplinesman=='x') echo " checked";
echo "> Proceed to succeeding spot</p>";
echo "<h5>Line Judge</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"aplinejudge\" value=\"x\"";
if($aplinejudge=='x') echo " checked";
echo "> Hold enforcement spot</p>";
echo "<h5>Back Judge</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"apbackjudge\" value=\"x\"";
if($apbackjudge=='x') echo " checked";
echo "> Assist in holding spot of foul or recovering penalty marker</p>";
echo "</td></tr>";
echo "<tr align=left><td>Comments:";
if($edit!=1 && $submitted==1)
   echo " $adminpenalties</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"adminpenalties\">$adminpenalties</textarea></td></tr>";
	//Time-Out Procedure
echo "<tr align=left><td><h4>Time-Out Procedure</h4>";
echo "<h5>Referee</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"toreferee\" value=\"x\"";
if($toreferee=='x') echo " checked";
echo "> Take position away from other officials, observe team B.</p>";
echo "<h5>Umpire</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"toumpire\" value=\"x\"";
if($toumpire=='x') echo " checked";
echo "> Maintain position over ball, observe team A.</p>";
echo "<h5>Linesman</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"tolinesman\" value=\"x\"";
if($tolinesman=='x') echo " checked";
echo "> Position halfway between ball and sideline, observe team on your sideline.</p>";
echo "<h5>Line Judge</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"tolinejudge\" value=\"x\"";
if($tolinejudge=='x') echo " checked";
echo "> Position halfway between ball and sideline, observe team on your sideline.</p>";
echo "<h5>Back Judge</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"tobackjudge\" value=\"x\"";
if($tobackjudge=='x') echo " checked";
echo "> Take position away from other officials, keep time and notify at 45 and 60 seconds.</p>";
echo "</td></tr>";
echo "<tr align=left><td>Comments:";
if($edit!=1 && $submitted==1)
   echo " $timeout</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"timeout\">$timeout</textarea></td></tr>";

	//Between-Periods Procedure
echo "<tr align=left><td><h4>Between-Periods Procedure</h4>";
echo "<h5>Referee</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"btpreferee\" value=\"x\"";
if($btpreferee=='x') echo " checked";
echo "> Meet with Umpire and Linesman on Field. (Record down, distance, yard line)</p>";
echo "<h5>Umpire</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"btpumpire\" value=\"x\"";
if($btpumpire=='x') echo " checked";
echo "> Meet with R and L then quickly take ball to corresponding point on other half of field and reverse directions.</p>";
echo "<h5>Linesman</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"btplinesman\" value=\"x\"";
if($btplinesman=='x') echo " checked";
echo "> Meet with R and U then reset chain crew, then observe team on your sideline.</p>";
echo "<h5>Line Judge</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"btplinejudge\" value=\"x\"";
if($btplinejudge=='x') echo " checked";
echo "> Observe team on your sideline.</p>";
echo "<h5>Back Judge</h5>";
echo "<p style='margin-left:30px;'><input type=checkbox name=\"btpbackjudge\" value=\"x\"";
if($btpbackjudge=='x') echo " checked";
echo "> Observe team for Linesman until he returns, resume position away from officials. Notify officials of time.</p>";
echo "</td></tr>";
echo "<tr align=left><td>Comments:";
if($edit!=1 && $submitted==1)
   echo " $btperiods</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"btperiods\">$btperiods</textarea></td></tr>";
	//Between-Halves Procedure
echo "<tr align=left><td><h4>Between-Halves Procedure</h4>";
echo "<table class='nine' style='margin-left:20px;width:680px;'>";
echo "<tr align=left><td width='34%'><input type=checkbox name=\"bthalves1\" value=\"x\"";
if($bthalves1=='x') echo " checked";
echo "> Referee signal to start clock</td><td>&nbsp;</td>";
echo "<td><input type=checkbox name=\"bthalves3\" value=\"x\"";
if($bthalves3=='x') echo " checked";
echo "> Leave field together</td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"bthalves4\" value=\"x\"";
if($bthalves4=='x') echo " checked";
echo "> Return to field together - 5 minutes</td><td><input type=checkbox name=\"bthalves2\" value=\"x\"";
if($bthalves2=='x') echo " checked";
echo "> Determine 2nd Half Choices</td>";
echo "<td><input type=checkbox name=\"bthalves5\" value=\"x\"";
if($bthalves5=='x') echo " checked";
echo "> Resume pregame alignments</td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td>Comments:";
if($edit!=1 && $submitted==1)
   echo " $bthalves</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"bthalves\">$bthalves</textarea></td></tr>";
	//Controlling the Sideline and Team Box
echo "<tr align=left><td><h4>Controlling the Sideline and Team Box</h4>";
echo "<table class='nine' style='margin-left:20px;width:450px;'>";
echo "<tr align=left><td width='50%'><input type=checkbox name=\"sidelinebox1\" value=\"x\"";
if($sidelinebox1=='x') echo " checked";
echo "> 3 coaches in box between plays</td><td><input type=checkbox name=\"sidelinebox2\" value=\"x\"";
if($sidelinebox2=='x') echo " checked";
echo "> Clear box prior to snap</td></tr>";
echo "</table>";
echo "</td></tr>";
echo "<tr align=left><td>Comments:";
if($edit!=1 && $submitted==1)
   echo " $sidelinebox</td></tr>";
else
   echo "<br><textarea style=\"width:700px;height:50px;\" name=\"sidelinebox\">$sidelinebox</textarea></td></tr>";

echo "</table>";
echo "</div>";

//Game Tempo
echo "<div class='evalbox'><h3>Game Tempo:</h3>";
if($edit!=1 && $submitted==1)
   echo "$gametempo</div>";
else
   echo "<textarea style=\"width:700px;height:90px;\" name=\"gametempo\">$gametempo</textarea></div>";

//Recommendations
echo "<div class='evalbox'><h3>Recommendations for Improvement:</h3>";
if($edit!=1 && $submitted==1)
   echo "$recommendations</div>";
else
   echo "<textarea style=\"width:700px;height:90px;\" name=\"recommendations\">$recommendations</textarea></div>";

//Comments for NSAA Only
if($level!=2)	//if not an official, show comments for NSAA
{
   echo "<div class='evalbox'><h3>Comments for NSAA only:</h3>";
   if($edit!=1 && $submitted==1)
      echo "$comments</div>";
   else
      echo "<textarea style=\"width:700px;height:90px;\" name=comments>$comments</textarea></div>";

   //Post Season
   echo "<p><b>Recommendations for Post Season Assignments:&nbsp;&nbsp;&nbsp;</b>";
   if($edit!=1 && $submitted==1)
      echo strtoupper($postseason);
   else
   {
      echo "<input type=radio name=postseason value='yes'";
      if($postseason=='yes') echo " checked";
      echo ">Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<input type=radio name=postseason value='no'";
      if($postseason=='no') echo " checked";
      echo ">No";
   }
   echo "<br><b>If YES, at what level?&nbsp;&nbsp;&nbsp;</b>";
      $classes=array("A","B","C","D");
      for($i=0;$i<count($classes);$i++)
      {
	 $var="postlevel".$classes[$i];
         echo "<input type=checkbox name=\"$var\" value=\"x\"";
         if($$var=='x') echo " checked";
         echo ">$classes[$i]&nbsp;&nbsp;";
      }
   echo "</p>";
}

if($submitted!=1 && $print!=1)
{
   echo "<br><font style=\"color:blue\"><b>NOTE:</b> You may click \"Save & Keep Editing\" if you want to save your work and continue later.  Your evaluation will NOT be sent to the NSAA until you click \"Submit Evaluation\" below.</font><br>";
   echo "<input type=submit name=\"saveeval\" class='fancybutton' value=\"Save & Keep Editing\">";
   echo "<br><br><font style=\"color:blue\"><b>NOTE:</b> Once you click \"Submit Evaluation\", your submission of this evaluation is final.  YOU MUST CLICK \"Submit Evaluation\" FOR THE EVALUATION TO BE SENT!!!<br>You will be able to view your submitted evaluations,but you will NOT be able to edit them.<br><input type=submit name=\"submiteval\" class='fancybutton2' value=\"Submit Evaluation\">";
}
else if($edit==1 && $level==1)
{
   echo "<input type=\"submit\" name=\"savechanges\" class='fancybutton' value=\"Save Changes\">";
}
if($sample==0)
   echo "</form>";
echo "<br><br>";

if($print!=1) echo "<a class=small href=\"javascript:window.close();\">Close Window</a>";
}//end if gameid given

echo $end_html;

?>
