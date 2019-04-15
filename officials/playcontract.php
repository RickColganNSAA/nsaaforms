<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

$temp=split("-",$sport);
$sport=$temp[0];
$disttimes=$sport."districts";
$sql="SELECT type FROM $disttimes WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[type]=="State" || $temp[1]=="state") $state=1;
$thisyear=GetSchoolYear(date("Y"),date("m"));
$temp=split("-",$thisyear);
if($sport=='sp')
   $curyear=$temp[1];
else
   $curyear=$temp[0];

$level=GetLevelJ($session);
if(!$givenoffid) $offid=GetJudgeID($session);
else $offid=$givenoffid;
if($level!=1) { $sample=0; $edit=0; }
if($edit==1) $sample=1;
if($sample==1) $offid="327";

if($sport=='sp')	//GET LODGING DATES
{
   //FIRST MAKE SURE WE HAVE ALL THE FIELDS WE NEED FOR LODGING ON STATE AND STATE DUAL
   $sql2="SELECT * FROM sptourndates WHERE lodgingdate='x' ORDER BY tourndate";
   $result2=mysql_query($sql2);
   $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      //CHECK THAT THERE IS A FIELD FOR THIS IN $contacts
      $num=$i+1; $field="date".$num;
      $sql3="SHOW COLUMNS FROM spcontracts WHERE Field='$field'";
      $result3=mysql_query($sql3);
      if(mysql_num_rows($result3)==0)      //ADD FIELD
      {
         $sql3="ALTER TABLE spcontracts ADD `$field` VARCHAR(5) NOT NULL";
         $result3=mysql_query($sql3);
      }
      $i++;
   }

   $sql2="SELECT * FROM sptourndates WHERE lodgingdate='x' AND label LIKE '%State%' ORDER BY tourndate";
   $result2=mysql_query($sql2);
   $splodging=array(); $splodging_sm=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $splodging[$i]=date("l, F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $splodging_sm[$i]=$date[1]."/".$date[2];
      $i++;
   }
}//END IF SPEECH

$sportname=GetSportName($sport);
$disttimes=$sport."districts";
$contracts=$sport."contracts";
$declines=$sport."declines";
$contracttext=$sport."contracttext";
if($sample==1 && $state==1 && $sport=='pp') { $distid=38; $textid=0; }
else if($sample==1 && $state==1 && $sport=='sp') { $distid=44; $textid=0; }
else if($sample==1) { $distid=3; $textid=1; }
if($state==1) $textid=0;
else $textid=1;

if($edit==1 && $savechanges)
{
   $text1=ereg_replace("\r\n","<br>",$text1);
   $text1=addslashes($text1);
   $text2=ereg_replace("\r\n","<br>",$text2);
   $text2=addslashes($text2);
   $text3=ereg_replace("\r\n","<br>",$text3);
   $text3=addslashes($text3);
   $text4=ereg_replace("\r\n","<br>",$text4);
   $text4=addslashes($text4);
   $sql="UPDATE $contracttext SET text1='$text1',text2='$text2',text3='$text3'";
   if($sport=='sp' && $state==1) $sql.=",text4='$text4'";
   $sql.=" WHERE district='$textid'";
   $result=mysql_query($sql);
}

//get contract text
$sql="SELECT * FROM $contracttext WHERE district='$textid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2]; $text3=$row[text3];
$text4=$row[text4];

if($submit)
{
   if($level!=1)
   {
      $sql="SELECT * FROM $disttimes WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[type]=="State") 
      {
         $state=1;
	 if($sport=='sp')	//also get distid for OTHER day of State
	 {
	    $sql2="SELECT id FROM $disttimes WHERE type='State' AND id!='$distid'";
	    $result2=mysql_query($sql2);
  	    $row2=mysql_fetch_array($result2);
	    $otherstateid=$row2[0];
	 }
      }
      else $state=0;

      if($sport=='pp' && $state==1 && !$lodging && $accept=='y') $accept='';
      else if($sport=='sp' && $state!=1 && !$mileage && $accept=='y') $accept='';

      $sql="UPDATE $contracts SET accept='$accept'";
      if($sport=='pp' && $state==1) $sql.=",lodging='$lodging'";
      else if($sport=='sp' && $state!=1) $sql.=",mileage='$mileage'";
      $sql.=" WHERE offid='$offid' AND distid='$distid'";
      $result=mysql_query($sql);
      if($accept=='n')	//if DECLINE, add to $declines table
      {
         $sql="SELECT id FROM $declines WHERE offid='$offid' AND distid='$distid'";
         $result=mysql_query($sql);
	 if(mysql_num_rows($result)==0)
	 {
	    $sql2="INSERT INTO $declines (offid,distid) VALUES ('$offid','$distid')";
	    $result2=mysql_query($sql2);
	 }
      }
      if($sport=='sp' && $row[type]=="State" && $empty==1)	//need to update preferences
      {
         $sql="UPDATE $contracts SET ";
         for($i=0;$i<count($prefs_sm);$i++)
         {
	    $sql.="$prefs_sm[$i]='$pref[$i]', ";
         }
         $schrep=addslashes($schrep); 
         $conflict=addslashes($conflict);
	 $classconf=""; $classprefs="";
         for($i=0;$i<count($classes);$i++)
         {
	    if($classconflict[$i]!="") $classconf.="$classconflict[$i], ";
	    if($classpref[$i]!="") $classprefs.="$classpref[$i], ";
         }
	 $classconf=substr($classconf,0,strlen($classconf)-2);
	 $classprefs=substr($classprefs,0,strlen($classprefs)-2);
	 $schconf="";
         for($i=0;$i<8;$i++)
	 {
	    if($schconflict[$i]!='') $schconf.="$schconflict[$i]/";
         }
	 $schconf=substr($schconf,0,strlen($schconf)-1);
         $schconf=addslashes($schconf);
         $sql.="schrep='$schrep',classrep='$classrep',classpref='$classprefs',classconflict='$classconf',schconflict='$schconf',conflict='$conflict',date1='$date1',date2='$date2' WHERE offid='$offid' AND ";
         $basesql=$sql;	//use for other state day, if applicable (see below)
	 $sql.="distid='$distid'";
         $result=mysql_query($sql); 
	 //echo "$sql<br>";
	 if($state==1 && $accept=='y')
	 {
	    //put same preferences in for other state day (if judge assigned to both days)
	    $sql=$basesql."distid='$otherstateid'";
	    $result=mysql_query($sql);
	    //echo "$sql<br>";
         } 
      }
   }
   else
   {
      $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND distid='$distid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td><br>";
//echo "<a class=small href=\"javascript:window.close()\">Close</a>";
echo "<form method=post action=\"playcontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=edit value=$edit>";
echo "<input type=hidden name=state value=$state>";
echo "<table cellspacing=3 cellpadding=3 width=600>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"playcontract.php?session=$session&sport=$sport&state=$state&sample=1\">Preview this Contract</a><br><br><b>YOU ARE EDITING THE WORDING OF THIS CONTRACT.</b>";
else if($sample==1)
   echo "<br><a class=small href=\"playcontract.php?session=$session&sport=$sport&state=$state&edit=1\">Edit this Contract</a><br><br><b>THIS IS A SAMPLE CONTRACT</b>";
echo "</td></tr>";
if($edit==1)
{
   echo "<tr align=left><td><font style=\"color:blue;font-size:9pt;\"><b>PLEASE NOTE:</b><br>";
   echo "Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>. <br>";
   echo "Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.<br>";
   echo "Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br>";
   echo "Words between &lt;font style='color:red'&gt; and &lt;/font&gt; will be <font style=\"color:red\">RED</font>.";
   echo "</td></tr>";
}
$sql="SELECT t2.accept AS acc,t2.confirm AS conf,t2.post AS posted";
if($state!=1 && $sport=='sp') $sql.=", t2.mileage";
$sql.=", t1.* FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.distid AND t2.offid='$offid' AND t2.distid='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result); 
echo mysql_error();
$confirm=$row[conf]; $accept=$row[acc]; $mileage=$row[mileage];
//echo "<tr><td>".mysql_error()."$sql".$confirm." ".$accept."</td></tr>";
echo "<tr align=center><td><table width=80%>";
if($row[acc]=='y' && !$submit)
{
   echo "<tr align=left><td>";
   if($level!=1) echo "You have ";
   else echo GetJudgeName($offid)." has ";
   echo "<b>accepted</b> the following contract.<br>";
   if($row[conf]=='y')
   {
      echo "The NSAA has <b>confirmed</b> the following contract.";
   }
   else if($row[conf]=='n')
   {
      echo "The NSAA has <b>rejected</b> the following contract.";
   }
   else if($level!=1)
   {
      echo "Please check back later to see if the NSAA has <b>confirmed</b> your contract.";
   }
   else
   {
      echo "The NSAA has not yet confirmed this contract.";
   }
   echo "<br>";
}
else if($row[acc]=='n' && !$submit)
{
   if($level!=1)
      echo "<tr align=left><td>You have <b>declined</b> the following contract.<br>";
   else 
      echo "<tr align=left><td>This officials has <b>declined</b> the following contract.<br>";
   if($confirm=='y')
      echo "The NSAA has <b>acknowledged</b> this contract.<br>";
   else if($confirm=='')
      echo "The NSAA has <b>not yet acknowledged</b> this contract.<br>";
}
echo "<br><br></td></tr></table></td></tr>";

if($submit)
{
   if($level!=1)
   {
      if($accept=='y')
      {
	 if($row[type]!='State')
            echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.  Once the NSAA confirms this contract, the District Director will contact you with the specific information.  Please do not announce your selection as a judge.<br><br></td></tr></table></td></tr>";
	 else
	    echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.  Once the NSAA confirms this contract, your home page will say \"NSAA Accepted\" next to the state contract link.  Please check back later.  Please do not announce your selection as a judge.<br><br></td></tr></table></td></tr>";
      }
      else if($accept=='n')
      {
         echo "<tr align=center><td>This confirms that you are not accepting this contract.<br><br></td></tr>";
      }
   }
   else
   {
      if($confirm=='y' && $accept=='y')
      {
	 echo "<tr align=center><td>You have <b>confirmed</b> the following contract.<br><br></td></tr>";
      }
      else if($confirm=='y' && $accept=='n')
      {
	 echo "<tr align=center><td>You have <b>acknowledged</b> the following contract.<br><br></td></tr>";
      }
   }
}
echo "<tr align=left><td>".date("F j, Y");
if($edit==1) echo "<font style=\"color:red\">&nbsp;&nbsp;[Today's Date]</font>";
echo "</td></tr>";
   $sql="SELECT * FROM judges WHERE id='$offid'";
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>".GetJudgeName($offid)."<br>$row[address]<br>$row[city], $row[state] $row[zip]";    
   if($edit==1)
      echo "<br><font style=\"color:red\">[Judge's Info comes from the database]</font>";
   echo "</td></tr>";
$sql="SELECT * FROM $disttimes WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($sport=='pp')
{
   if($row[type]!='State')
   {
      echo "<tr align=left><td>";
      if($edit==1)
      {
	 $text1=ereg_replace("<br>","\r\n",$text1);
	 echo "<textarea name=\"text1\" cols=70 rows=3>$text1</textarea></td></tr>";
	 echo "<tr align=left><td><font style=\"color:red\">[The following information comes from the database. Edit this information where you assign DISTRICT HOSTS for Play Production (under Contracts -- Host Contracts -- Play Production).]</font>";
      }
      else
    	 echo $text1;
      echo "</td></tr><tr align=left><td>";
      $temp=split("-",$row[dates]);
      $dates=date("F j, Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
      if($row[dates]=="")  $dates="TBA";
      echo "<b>$row[type] $row[class]-$row[district]<br></b>";
      echo "<b>Dates(s):</b> $dates<br><b>Starting Time:</b> $row[time]<br><b>Host (Site):</b> $row[hostschool] ($row[site])<br>";
      echo "<b>Director:</b> $row[director]<br>";
      echo "<b>Schools assigned:</b> $row[schools]<br>";
      $state=0;
   }
   else	//STATE
   {
      echo "<tr align=left><td>";
      if($edit==1)
      {  
         $text1=ereg_replace("<br>","\r\n",$text1);
         echo "<textarea name=\"text1\" cols=70 rows=3>$text1</textarea></td></tr>";
	 echo "<tr align=left><td><font style=\"color:red\">[The following information comes from the database. Go to the same place where you assign district hosts for Play (under Contracts -- Host Contracts -- Play Production). Click on \"View/Assign District Hosts: One at a Time\" and on that screen select State and then the Class. Ignore most of it (the host school assignment, etc.) but enter the DATE, SITE and REPORTING TIME. This information will show up on the state judges contract below.]</font>";
      }
      else
         echo $text1;
      echo "</td></tr><tr align=left><td>";
      $date=split("-",$row[dates]);
      echo "<b>Class $row[class]</b><br>";
      if($row[dates]!='')
      {
         echo "<b>Date(s):</b> ";
         $day=split("/",$row[dates]);
         for($i=0;$i<count($day);$i++)    
         {
            $cur=split("-",$day[$i]);
            echo date("l, F j,",mktime(0,0,0,$cur[1],$cur[2],$cur[0]))." ";
         }
         echo "$cur[0]<br>";
      }
      else
         echo "<b>Date:</b> TBA<br>";
      echo "<b>Time to Report:</b> $row[time]<br>";
      echo "<b>Site:</b> $row[site]";
      $state=1;
   }
   echo "</td></tr>";
   if($state==0)
   {
      $sql="SELECT offid FROM $contracts WHERE offid!='$offid' AND distid='$distid' AND post='y' AND accept!='n' AND confirm!='n'";
      $result=mysql_query($sql);
      $partners="";
      while($row=mysql_fetch_array($result))
      {
         $partners.=GetJudgeName($row[offid]).", ";
      }
      $partners=substr($partners,0,strlen($partners)-2);
      if($sample==1) $partners="John Doe, Jane Smith, Tom Johnson, Sherri Baby";
      echo "<tr align=left><td><b>Judges selected for this district are:</b> $partners</td></tr>";
   }//end if not state
   echo "<tr align=left><td>";
   if($edit==1)
   {
      $text2=ereg_replace("<br>","\r\n",$text2);
      echo "<textarea name=\"text2\" cols=70 rows=5>$text2</textarea>";
   }
   else
      echo $text2;
   echo "</td></tr>";
   if($state==1)
   {
      echo "<tr align=left><td><b>Need Lodging?</b>&nbsp;";
      if($accept!='')  echo $lodging;
      else
         echo "<input type=radio name=\"lodging\" value=\"Yes\">Yes&nbsp;&nbsp;<input type=radio name=\"lodging\" value=\"No\">No";
      if($submit=="Submit" && !$lodging)
         echo "<br><font style=\"color:red\">[You MUST check \"Yes\" or \"No\".]</font>";
      echo "</td></tr>";
   }
   echo "<tr align=left><td><i>This contract shall be null and void if a judge has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";
}
else	//SPEECH
{
   $date=split("-",$row[dates]);
   $distdate=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   if($row[dates]=='') $distdate="TBA";
   if($row[type]=='State') $state=1;
   else $state=0;
   echo "<tr align=left><td>";
   if($edit==1)
   {
      $text1=ereg_replace("<br>","\r\n",$text1);
      echo "<textarea cols=70 rows=2 name=\"text1\">$text1</textarea>";   
   }
   else
      echo $text1;
   echo "</td></tr><tr align=left><td>";
   if($edit==1)
      echo "<font style=\"color:red\">[The following information comes from the database.]</font><br>";
   if($state==0)	//DISTRICT
   {
      echo "<p><b>$row[type] $row[class]-$row[district]</b></p>";
      echo "<p><b>Date & Time:</b> $distdate";
      if($row[time]!='') echo ": $row[time]";
      echo "</p><p>";
      echo "<b>Director:</b> $row[director]</p><p>";
      echo "<b>Host/Site:</b> $row[hostschool]/$row[site]</p><p>";
      echo "<b>Schools Assigned:</b> $row[schools]</p>";
   }
   else 
   {
      $date=split("-",$row[dates]);
      echo "<p><b>".date("l, F j",mktime(0,0,0,$date[1],$date[2],$date[0]))."</b></p>";
   }
   echo "</td></tr>";

   if($state==0)	//show others assigned to same district
   {
      $sql2="SELECT offid FROM $contracts WHERE offid!='$offid' AND distid='$distid' AND post='y' AND accept!='n' AND confirm!='n'";
      $result2=mysql_query($sql2);
      $partners="";
      while($row2=mysql_fetch_array($result2))
      {
         $partners.=GetJudgeName($row2[offid]).", ";
      }
      $partners=substr($partners,0,strlen($partners)-2);
      if($sample==1) $partners="Jane Doe, John Smith, Tom Johnson, Sherri Baby";
      echo "<tr align=left><td><b>Other judges contracted for this district:</b> $partners</td></tr>";
   }
   echo "<tr align=left><td>";
   if($edit==1)
   {
      $text2=ereg_replace("<br>","\r\n",$text2);
      echo "<textarea rows=8 cols=70 name=\"text2\">$text2</textarea>";
   }
   else
      echo $text2;
   echo "</td></tr>";
   if($state==1)
   {
      //ADDITIONAL FIELDS: lodging, event preferences, etc.
      //get info from state contract, if already entered
      $sql2="SELECT * FROM $contracts WHERE offid='$offid' AND distid='$distid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($row2[$prefs_sm[0]]=='0' && $row2[$prefs_sm[1]]=='0' && $row2[$prefs_sm[2]]=='0' && $row2[$prefs_sm[3]]=='0' && $row2[$prefs_sm[4]]=='0' && $row2[$prefs_sm[5]]=='0' && $row2[$prefs_sm[6]]=='0' && $row2[$prefs_sm[7]]=='0' && $row2[$prefs_sm[8]]=='0' && $row2[schrep]=='' && $row2[classconflict]=='' && $row2[schconflict]=='')
      {
         //nothing in database yet for their preferences
         $empty=1;
      }
      else
      {
         //preferences stored (possibly from other day they were assigned to for state SP)
         $empty=0;
      }
      echo "<input type=hidden name=empty value=\"$empty\">";
      if($accept!='y' && $accept!='n' && $empty==0) 
         echo "<tr align=left><td><i>(You have already submitted your preferences, shown below)</i></td></tr>";
      //Lodging:
      echo "<tr align=left><td><u>Please check the night(s) you will need LODGING</u>, if any:&nbsp;";
      echo "<input type=checkbox name=\"date1\" value='x'";
      if($row2[date1]=='x') echo " checked";
      if($sample!=1 && ($empty==0 || $accept=='y')) echo " disabled";
      echo ">$splodging[0]&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"date2\" value='x'";
      if($row2[date2]=='x') echo " checked";
      if($sample!=1 && ($empty==0 || $accept=='y')) echo " disabled";
      echo ">$splodging[1]";
      echo "</td></tr>";
      echo "<tr align=left><td>";
      echo "<u>Please indicate</u>, in order of preference, the events you feel most qualified to judge:<br>";
      for($i=0;$i<count($prefs_sm);$i++)
      {
         if($sample!=1 && ($empty==0 || $accept=='y'))	//show what they've entered
            echo "<u>&nbsp;&nbsp;".$row2[$prefs_sm[$i]]."&nbsp;&nbsp;</u>&nbsp;&nbsp;$prefs_lg[$i]<br>";
         else
         {
	    echo "<select name=\"pref[$i]\"><option value='0'>~</option>";
	    for($j=1;$j<=count($prefs_sm);$j++)
	    {
	       echO "<option";
	       if($row2[$prefs_sm[$i]]==$j) echo " selected";
	       echo ">$j</option>";
	    }
	    echo "</select>&nbsp;&nbsp;$prefs_lg[$i]<br>";
         }
      }
      //get information from app to judge state:
      $sql3="SELECT * FROM spapply WHERE offid='$offid'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      if(!($sample!=1 && ($empty==0 || $accept=='y')) && $edit!=1) 
         echo "<br><br><b>$text4</b><br>";
      else if($edit==1)
      {
         $text4=ereg_replace("<br>","\r\n",$text4);
         echo "<textarea name=\"text4\" cols=70 rows=5>$text4</textarea>";
      }
      echo "<br>I represent ";
      if($sample!=1 && ($empty==0 || $accept=='y')) 
      {
         $sql4="SELECT school FROM $db_name.spschool WHERE sid='$row2[schrep]'";
         $result4=mysql_query($sql4);
         $row4=mysql_fetch_array($result4);
         echo "<b>$row4[school]</b>";
      }
      else
      {
         echo "<select name=schrep><option value=''>~</option>";
         $sql4="SELECT sid,school FROM $db_name.spschool ORDER BY school";
         $result4=mysql_query($sql4);
         $schids=array(); $schs=array(); $ix=0;
         while($row4=mysql_fetch_array($result4))
         {
            echo "<option value=\"$row4[sid]\">$row4[school]</option>";
  	    $schids[$ix]=$row4[sid];
	    $schs[$ix]=$row4[school];
	    $ix++;
         }
         echo "</select>";
      } 
      echo " High School.<br>";
      echo "I represent Class&nbsp;";
      if($sample!=1 && ($empty==0 || $accept=='y')) echo "<b>$row2[classrep]</b>";
      else
      {   
         for($i=0;$i<count($classes);$i++)
         {
	    echo "<input type=radio name=\"classrep\" value=\"$classes[$i]\"";
 	    if($row3[classrep]==$classes[$i]) echo " checked";
            echo ">$classes[$i]&nbsp;&nbsp;";
         }
      }
      echo "<br>";
      echo "I prefer to judge Class(es) ";
      if($sample!=1 && ($empty==0 || $accept=='y')) echo "<b>$row2[classpref]</b>";
      else
      {
         for($i=0;$i<count($classes);$i++)
         {
            echo "<input type=checkbox name=\"classpref[$i]\" value=\"$classes[$i]\"";
            if(ereg($classes[$i],$row3[classpref])) echo " checked";
            echo ">$classes[$i]&nbsp;&nbsp;";
         }
      }
      echo "<br>I have a conflict judging Class(es) ";
      if($sample!=1 && ($empty==0 || $accept=='y')) echo "<b>$row2[classconflict]</b>";
      else
      {
         for($i=0;$i<count($classes);$i++)
         {
            echo "<input type=checkbox name=\"classconflict[$i]\" value=\"$classes[$i]\"";
	    if(ereg($classes[$i],$row3[classconflict])) echo " checked";
	    echo ">$classes[$i]&nbsp;&nbsp;";
         }
      }
      echo "<br>I have a conflict judging the following schools:<br>";
      if($sample!=1 && ($empty==0 || $accept=='y'))
      {
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         echo "<i>";
         $temp=split("/",$row2[schconflict]);
         for($i=0;$i<count($temp);$i++)
         {
            $sql4="SELECT school FROM $db_name.spschool WHERE sid='$temp[$i]'";
            $result4=mysql_query($sql4);
	    $row4=mysql_fetch_array($result4);
	    if($temp[$i]!='')
	    {
	       echo $row4[school];
	       if($i<(count($temp)-1)) echo ", ";
            }
         }
         echo "</i>";
      }   
      else
      { 
         $temp=split("/",$row3[schconflict]);
         echo "<table>";
         for($i=0;$i<8;$i++)
         {
            if($i%2==0) echo "<tr align=left>";
            echo "<td><select name=\"schconflict[$i]\"><option value=''>~</option>";
 	    for($j=0;$j<count($schs);$j++)
	    {
	       echo "<option value=\"".$schids[$j]."\"";
	       for($k=0;$k<count($temp);$k++)
                  if($temp[$k]==$schids[$j]) echo " selected";
	       echo ">$schs[$j] $schids[$j]</option>";
	    }
	    echo "</select></td>";
	    if(($i%2)!=0) echo "</tr>";
         }
         echo "</table>";
      }
      echo "<br>Please note any other comments below:<br>";
      if($sample!=1 && ($empty==0 || $accept=='y'))
      {   
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         echo "<i>$row2[conflict]</i>";
      }
      else
      {
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         echo "<textarea name=conflict cols=50 rows=3>$row3[conflict]</textarea>";
      } 
      echo "</td></tr>";
   }//end if state=1
   else
   {
      echo "<tr align=left><td><b>Mileage:</b><br>";  
      $statements=array("I am traveling with a team which is competing at this district and DO NOT require mileage.",
			"I will be providing my own transportation to the contest and DO require mileage.");
      if($accept!='')  
      {
	 if($mileage=="Yes") echo $statements[1];
	 else if($mileage=="No") echo $statements[0];
	 else echo "?";
      }
      else
      {
         echo "<input type=radio name=\"mileage\" value=\"No\">$statements[0]<br>";
         echo "<input type=radio name=\"mileage\" value=\"Yes\">$statements[1]";
      }
      if($submit=="Submit" && !$mileage)
         echo "<br><font style=\"color:red\">[You MUST indicate whether or not you require MILEAGE.]</font>";
      echo "</td></tr>";
   }
   echo "<tr align=left><td><i>This contract shall be null and void if a judge has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";
}//end if speech
if(($accept!='y' && $accept!='n' && $level!=1) || ($sample==1 && $level==1))		//official hasn't responded yet OR sample
{
   echo "<tr align=left><td><p>";
   echo "<input type=radio name=accept value='y'>&nbsp;";
   if($edit==1)
      echo "<textarea name=\"text3\" cols=70 rows=3>$text3</textarea>";
   else
      echo "$text3";
   echo "</p><p>";
   echo "<input type=radio name=accept value='n'>&nbsp;";
   echo "DECLINE</p></td></tr>";
   if($edit==1)
   {
      echo "<tr align=center><td><br><br><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   }
   else
   {
      echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"";
      if($sample==1) echo " disabled=TRUE";
      echo "></td></tr>";
   }
}
if($accept=='y' && $confirm!='y' && $confirm!='n' && $level==1)	//NSAA needs to confirm an accepted contract
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms this contract.</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y' && $level==1)
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the official's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"?</td></tr>";
}
echo "</table></form>";
echo "<a class=small href=\"javascript:window.close()\">Close</a>";

echo $end_html;
?>
