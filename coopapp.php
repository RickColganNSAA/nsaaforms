<?php
/********************************************************
coopapp.php
Agreement for Cooperative Sponsorship (new co-op app,
to be completed by Head School)
Created 7/13/12
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level>2)
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolid || $level!=1)
{
   $schoolid=GetSchoolID($session);
   if($level==1) 
   {
      //WAS $coopappid GIVEN?
      if($coopappid)
      {
	 $sql="SELECT schoolid1 FROM coopapp WHERE id='$coopappid'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0) $schoolid=1616;
	 else
	 {
	    $row=mysql_fetch_array($result);
	    $schoolid=$row[schoolid1];
	 }
      }
      else $schoolid=1616;        //Test's School
   }
}
$school=GetSchool2($schoolid);
$school2=ereg_replace("\'","\'",$school);
//GET SCHOOL YEAR - Since Spring activities due by January 1, $year = date("Y") always, except for ON January 1
$year1=date("Y");
if(date("m")==1 && date("j")==1) $year1--;
$year2=$year1+1;
$year3=$year2+1;
$year4=$year3+1;
$fallyear=GetFallYear();

if($execsubmit || $savechanges || $submittonsaa || $continue)	//SAVE INITIAL FORM AND CONTINUE TO SCHOOL FORM OR SUBMIT TO NSAA OR TAKE EXEC ACTION OR SAVE CHANGES
{
   //CLEAN DATA:
   	//ACTIVITIES - if ALL is checked, check ALL
   if($allactivities=='x')
   {
      for($i=0;$i<count($coopsports);$i++)
      {
	 $var=$coopsports[$i];
	 $$var='x';
      }
   }
	//YEARS
      if($year0check=='x')
      {
	 $year1value=$year1-1;
	 if($year1check=='x') $year2value=$year1;
	 else $year2value=0;
      }
      else if($year1check=='x') 
      {
	 $year1value=$year1;
         if($year2check=='x') $year2value=$year2;
	 else $year2value=0;
      }
      else if($year2check=='x')
      {
         $year1value=$year2;
         if($year3check=='x') $year2value=$year3;
         else $year2value=0;
      }
      else if($year3check=='x') 
      {
	 $year1value=$year3;
	 $year2value=0;
      }
      else 
      {
	 $year1value=0; $year2value=0;
      }

   //UPDATE DATABASE
   if($coopappid)	//UPDATE
   {
      $sql="UPDATE coopapp SET dist1='$dist1',dist2='$dist2',dist3='$dist3',dist4='$dist4',schoolid2='$schoolid2',schoolid3='$schoolid3',schoolid4='$schoolid4', ";
      for($i=0;$i<count($coopsports);$i++)
      {
 	 $var=$coopsports[$i];
	 $sql.="$var='".$$var."', ";
      }
      $sql.="year1='$year1value',year2='$year2value', ";
      if($renewal=='x')
      {
         $sql.="renewal='x'";
      }
      else
      {
      for($i=1;$i<=4;$i++)
      {
	 $var="purpose".$i;
	 $sql.="$var='".addslashes($$var)."', ";
      }
      $sql.="teamname='".addslashes($teamname)."',mascot='".addslashes($mascot)."',colors='".addslashes($colors)."',headcoachdist='$headcoachdist',reimschoolid='$reimschoolid',";
      for($i=1;$i<=10;$i++)
      {
         $var="allocation".$i;
	 $sql.="$var='".addslashes($$var)."', ";
      }
      $sql.="gatereceipts1='".addslashes($gatereceipts1)."',gatereceipts2='".addslashes($gatereceipts2)."',";
      for($i=1;$i<=5;$i++)
      {
         $var1="position".$i; $var2="employer".$i;
   	 $sql.="$var1='".addslashes($$var1)."',$var2='".addslashes($$var2)."', ";
      }
      $sql.="claimantins='$claimantins',claimins='$claimins'";
      }//END IF NOT RENEWAL
      $sql.=" WHERE id='$coopappid'";
      $result=mysql_query($sql);
   }//END IF coopappid
   else	//INSERT NEW FORM
   {
      $sql="INSERT INTO coopapp (schoolid1,schoolid2,schoolid3,schoolid4,dist1,dist2,dist3,dist4, ";
      for($i=0;$i<count($coopsports);$i++)
      {
	 $sql.=$coopsports[$i].", ";
      }
      $sql.="year1,year2,";
      if($renewal=='x')
      {
         $sql.="renewal,teamname,mascot,colors,";
      }
      else
      {
      $sql.="purpose1,purpose2,purpose3,purpose4,teamname,mascot,colors,headcoachdist,reimschoolid, ";
      for($i=1;$i<=10;$i++)
      {
         $sql.="allocation".$i.", ";
      }
      $sql.="gatereceipts1,gatereceipts2, ";
      for($i=1;$i<=5;$i++)
      {
         $sql.="position".$i.",employer".$i.", ";
      }
      $sql.="claimantins,claimins,";
      } //END IF NOT RENEWAL
      $sql.="datesubtoschools) VALUES ('$schoolid','$schoolid2','$schoolid3','$schoolid4','$dist1','$dist2','$dist3','$dist4',";
      for($i=0;$i<count($coopsports);$i++)
      {
	 $var=$coopsports[$i];
         $sql.="'".$$var."', ";
	 if($$var=='x')
	 {
            //GET teamname, mascot, colors from current co-op
            $sql2="SELECT * FROM ".GetSchoolsTable($coopsports[$i])." WHERE mainsch='$schoolid'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    $teamname=$row2['school']; $mascot=$row2['mascot']; $colors=$row2['colors'];
	 }
      }
      $sql.="'$year1value','$year2value',";
      if($renewal=='x')
         $sql.="'x','".mysql_real_escape_string($teamname)."','".mysql_real_escape_string($mascot)."','".mysql_real_escape_string($colors)."',";
      else
      {
      $sql.="'".addslashes($purpose1)."','".addslashes($purpose2)."','".addslashes($purpose3)."','".addslashes($purpose4)."','".mysql_real_escape_string($teamname)."','".mysql_real_escape_string($mascot)."','".mysql_real_escape_string($colors)."','$headcoachdist','$reimschoolid', ";
      for($i=1;$i<=10;$i++)
      {
         $var="allocation".$i;
         $sql.="'".addslashes($$var)."', ";
      }
      $sql.="'".addslashes($gatereceipts1)."', '".addslashes($gatereceipts2)."', ";
      for($i=1;$i<=5;$i++)
      {
         $var1="position".$i; $var2="employer".$i;
 	 $sql.="'".addslashes($$var1)."','".addslashes($$var2)."', ";
      }
      $sql.="'$claimantins','$claimins',";
      } //END IF NOT RENEWAL
      $sql.="'".time()."')";
      $result=mysql_query($sql);
      $coopappid=mysql_insert_id();
   }
   //CHECK FOR SQL ERROR
   if(mysql_error())
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br>";
      echo "<div class='error' style='width:700px;'><p>The following query:</p><p>$sql</p><p>had the following ERROR:</p><p>".mysql_error()."</p><p>Please <a href=\"javascript:history.go(-1);\">Go Back</a> and try again or report this problem to the NSAA/programmer.</p></div>";
      echo $end_html;
      exit();
   }
   else if($continue)
   {
      header("Location:coopschoolapp.php?session=$session&schoolid=$schoolid&coopappid=$coopappid&continue=1");
      exit();
   }
   else if($submittonsaa)
   {
      //CHECK FOR ERRORS
      if(GetCoopAppErrors($coopappid)!='')
      {
         header("Location:coopapp.php?session=$session&coopappid=$coopappid&founderrors=1");
      }
      else
      {
         $sql="UPDATE coopapp SET datesubtoNSAA='".time()."' WHERE id='$coopappid'";
         $result=mysql_query($sql);

         header("Location:coopapp.php?session=$session&coopappid=$coopappid&submitted=1");
      }
      exit();
   }
   else if($execsubmit || $approvedeny)
   { 
      $approved=''; $denied=''; $now=time();
      if($execmo!='00' && $execday!='00')
         $now=mktime(8,0,0,$execmo,$execday,$execyr);
      if($approvedeny=="approved")
         $approved='x';
      else if($approvedeny=='denied')
         $denied='x';
      else if($approvedeny=="reset")
      {
	 $approved=''; $denied=''; $now=0;
      }
      else $now=0;
      $sql="UPDATE coopapp SET approved='$approved',denied='$denied',execdate='$now' WHERE id='$coopappid'";
      $result=mysql_query($sql);

      if($approved=='x')
      {
        //GO AHEAD AND ADD THIS COOP TO MANAGE SCHOOLS
        $sql="SELECT * FROM coopapp WHERE id='$coopappid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $mainsch=$row[schoolid1];
         if($row[schoolid2]>0) $othersch1=$row[schoolid2];
         else $othersch1=0;
         if($row[schoolid3]>0) $othersch2=$row[schoolid3];
         else $othersch2=0;
         if($row[schoolid4]>0) $othersch3=$row[schoolid4];
         else $othersch3=0;
         for($i=0;$i<count($coopsports);$i++)
         {
            if($row[$coopsports[$i]]=='x')
            {
               if(GetSeason($coopsports[$i])=="Fall")   //EXPIRE 12/1 (of $row[year2] or $row[year1] if $row[year2]==0)
               {
                  if($row[year2]>0) $expdate=$row[year2]."-12-01";
                  else $expdate=$row[year1]."-12-01";
               }
               else if(GetSeason($coopsports[$i])=="Winter")    //EXPIRE 4/1 of $row[year2]+1 (or $row[year1]+1...)
               {
                  $yr3=$row[year2]+1; $yr2=$row[year1]+1;
                  if($row[year2]>0) $expdate=$yr3."-04-01";
                  else $expdate=$yr2."-04-01";
               }
               else     //EXPIRE 6/1 of $row[year2]+1 (or $row[year1]+1...)
               {
                  $yr3=$row[year2]+1; $yr2=$row[year1]+1;
                  if($row[year2]>0) $expdate=$yr3."-06-01";
                  else $expdate=$yr2."-06-01";
               }
	       //We KNOW WHICH TABLE, BUT FOR WHICH YEAR?
	       //As of Dec 2015, WE NOW CREATE A TABLE FOR NEXT YEAR IF THAT IS WHERE THE
	       //CO-OP NEEDS TO GO, SO AS NOT TO DISRUPT THIS YEAR'S LIST.
               $table=GetSchoolTable($coopsports[$i]);
	       if($row['year1']>$fallyear)	//IT IS A CO-OP FOR a FUTURE YEAR
	       {
		   //NEED TO SEE IF NEXT YEAR'S TABLE EXISTS FOR THIS LIST OF SCHOOLS
		   $springyr=$row['year1']+1;
	  	   $schooltblNEXT=$table.$row['year1'].$springyr;
		   $sql2="SHOW TABLES LIKE '$schooltblNEXT'";
		   $result2=mysql_query($sql2);
		   if(mysql_num_rows($result2)==0)	//NEED TO CREATE THIS TABLE AND COPY OVER CURRENT LIST
		   {
   			$sql="CREATE TABLE $schooltblNEXT SELECT * FROM $table";
   			$result=mysql_query($sql);
   			$sql="ALTER TABLE `".$schooltblNEXT."` ADD PRIMARY KEY(`sid`)";
   			$result=mysql_query($sql);
   			$sql="ALTER TABLE  `".$schooltblNEXT."` CHANGE  `sid`  `sid` INT( 11 ) NOT NULL AUTO_INCREMENT";
   			$result=mysql_query($sql);
	   	   }
		   $table=$schooltblNEXT;
	       }

               $sql2="SELECT * FROM $table WHERE (mainsch='$mainsch' OR othersch1='$mainsch' OR othersch2='$mainsch' OR othersch3='$mainsch')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)==0)  //INSERT
               {
                  $sql2="INSERT INTO $table (school,mainsch,othersch1,othersch2,othersch3,mascot,colors,coopexpdate) VALUES ('".addslashes($row[teamname])."','$mainsch','$othersch1','$othersch2','$othersch3','".addslashes($row[mascot])."','".addslashes($row[colors])."','$expdate')";
                  if($mainsch!=1616) $result2=mysql_query($sql2);
               }
               else     //UPDATE
               {
                  $row2=mysql_fetch_array($result2); $sid=$row2[sid];
                  $sql2="UPDATE $table SET ";
                  if(trim($row[teamname])!='') $sql2.="school='".addslashes($row[teamname])."',";
                  if(trim($row[mascot])!='') $sql2.="mascot='".addslashes($row[mascot])."',";
                  if(trim($row[colors])!='') $sql2.="colors='".addslashes($row[colors])."',";
                  $sql2.="mainsch='$mainsch',othersch1='$othersch1',othersch2='$othersch2',othersch3='$othersch3',coopexpdate='$expdate' WHERE sid='$sid'";
                  if($mainsch!=1616) $result2=mysql_query($sql2);
               }
              //NOW REMOVE ANY OTHER ENTRIES FOR THESE SCHOOLS IN $table
               for($j=1;$j<=4;$j++)
               {
                  $schvar="schoolid".$j; $schid=$row[$schvar];
                  if($schid>0)
                  {
                     $sql2="SELECT * FROM $table WHERE mainsch!='$mainsch' AND (mainsch='$schid' OR othersch1='$schid' OR othersch2='$schid' OR othersch3='$schid')";
                     $result2=mysql_query($sql2);
                     while($row2=mysql_fetch_array($result2))
                     {
                        $sql3="DELETE FROM $table WHERE sid='$row2[sid]'";
                        if($mainsch!=1616) $result3=mysql_query($sql3);
                     }
                  }
               }
			   
			   if($row[year2]!=0){
				
				if(GetSeason($coopsports[$i])=="Fall")   //EXPIRE 12/1 (of $row[year2] or $row[year1] if $row[year2]==0)
				   {
					  $expdate=$row[year2]."-12-01";
					  
				   }
				   else if(GetSeason($coopsports[$i])=="Winter")    //EXPIRE 4/1 of $row[year2]+1 (or $row[year1]+1...)
				   {
					  $yr3=$row[year2]+1; $yr2=$row[year1]+1;
					   $expdate=$yr3."-04-01";
					  
				   }
				   else     //EXPIRE 6/1 of $row[year2]+1 (or $row[year1]+1...)
				   {
					  $yr3=$row[year2]+1; $yr2=$row[year1]+1;
					   $expdate=$yr3."-06-01";
					  
				   }
				   
				  if($row['year2']>$fallyear)	//IT IS A CO-OP FOR a FUTURE YEAR
			   {
			   //NEED TO SEE IF NEXT YEAR'S TABLE EXISTS FOR THIS LIST OF SCHOOLS
			   $springyr=$row['year2']+1;
			    $schooltblNEXT=$table.$row['year2'].$springyr; 
			   $sql2="SHOW TABLES LIKE '$schooltblNEXT'";
			   $result2=mysql_query($sql2);
			   if(mysql_num_rows($result2)==0)	//NEED TO CREATE THIS TABLE AND COPY OVER CURRENT LIST
			   {
				$sql="CREATE TABLE $schooltblNEXT SELECT * FROM $table";
				$result=mysql_query($sql);
				$sql="ALTER TABLE `".$schooltblNEXT."` ADD PRIMARY KEY(`sid`)";
				$result=mysql_query($sql);
				$sql="ALTER TABLE  `".$schooltblNEXT."` CHANGE  `sid`  `sid` INT( 11 ) NOT NULL AUTO_INCREMENT";
				$result=mysql_query($sql);
			   }
			   $table=$schooltblNEXT;
			   }
					   
				   
				   $sql2="SELECT * FROM $table WHERE (mainsch='$mainsch' OR othersch1='$mainsch' OR othersch2='$mainsch' OR othersch3='$mainsch')";
				   $result2=mysql_query($sql2);
				   if(mysql_num_rows($result2)==0)  //INSERT
				   {
					  ECHO $sql2="INSERT INTO $table (school,mainsch,othersch1,othersch2,othersch3,mascot,colors,coopexpdate) VALUES ('".addslashes($row[teamname])."','$mainsch','$othersch1','$othersch2','$othersch3','".addslashes($row[mascot])."','".addslashes($row[colors])."','$expdate')";
					  if($mainsch!=1616) $result2=mysql_query($sql2);
				   }
				   else     //UPDATE
				   {
					  $row2=mysql_fetch_array($result2); $sid=$row2[sid];
					  $sql2="UPDATE $table SET ";
					  if(trim($row[teamname])!='') $sql2.="school='".addslashes($row[teamname])."',";
					  if(trim($row[mascot])!='') $sql2.="mascot='".addslashes($row[mascot])."',";
					  if(trim($row[colors])!='') $sql2.="colors='".addslashes($row[colors])."',";
					  ECHO $sql2.="mainsch='$mainsch',othersch1='$othersch1',othersch2='$othersch2',othersch3='$othersch3',coopexpdate='$expdate' WHERE sid='$sid'";
					  if($mainsch!=1616) $result2=mysql_query($sql2);
				   }
				  //NOW REMOVE ANY OTHER ENTRIES FOR THESE SCHOOLS IN $table
				   for($j=1;$j<=4;$j++)
				   {
					  $schvar="schoolid".$j; $schid=$row[$schvar];
					  if($schid>0)
					  {
						 $sql2="SELECT * FROM $table WHERE mainsch!='$mainsch' AND (mainsch='$schid' OR othersch1='$schid' OR othersch2='$schid' OR othersch3='$schid')";
						 $result2=mysql_query($sql2);
						 while($row2=mysql_fetch_array($result2))
						 {
							$sql3="DELETE FROM $table WHERE sid='$row2[sid]'";
							if($mainsch!=1616) $result3=mysql_query($sql3);
						 }
					  }
				   }
				   
			   
			   
			}
			   
            }   //END IF coopsport='x'
         }      //END FOR each coop sport
      }	//END IF APPROVED

      if($now==0 && $approvedeny!='reset')
         header("Location:coopapp.php?session=$session&coopappid=$coopappid&error=1#execaction");
      else
         header("Location:coopapp.php?session=$session&coopappid=$coopappid&execaction=1");
      exit();
   }
   else
   {
      if($approvedeny)
      {
         if($approvedeny=="approved")
            $approved='x';
         else if($approvedeny=='denied')
            $denied='x';
         $sql="UPDATE coopapp SET approved='$approved',denied='$denied' WHERE id='$coopappid'";
         $result=mysql_query($sql);
      }
      header("Location:coopapp.php?session=$session&coopappid=$coopappid");
      exit();
   }
}


if($coopappid)	//APP ID GIVEN
{
   //ERRORCHECK: IF NOT EVERY SCHOOL HAS SUBMITTED PAPERWORK, THIS CANNOT BE SUBMITTED TO THE NSAA YET
   $sql="SELECT * FROM coopapp WHERE id='$coopappid'";
   $result=mysql_query($sql);
   $app=mysql_fetch_array($result);
   $allcomplete=1;
   for($i=1;$i<=4;$i++)
   {
      $var="schoolid".$i;
      if($app[$var]>0)
      {
         $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='".$app[$var]."'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if($row[datesub]==0) $allcomplete=0;
      }
   }
   if($allcomplete==0)
   {
      $sql="UPDATE coopapp SET datesubtoNSAA=0 WHERE id='$coopappid'";
      $result=mysql_query($sql);
   }

   $sql="SELECT * FROM coopapp WHERE id='$coopappid'";
   $result=mysql_query($sql);
   $app=mysql_fetch_array($result);
   if($level!=1 && $schoolid!=$app[schoolid1])  //NOT HEAD SCHOOL - PRINT ONLY
      $print=1;
   if(($level!=1 || $edit!=1) && $app[datesubtoNSAA]>0)	//SUBMITTED TO NSAA - PRINT ONLY
      $print=1;
}

if($level==1 || ($print==1 && $submitted!=1)) $header="<table width='100%'><tr align=center><td>";
else $header=GetHeader($session);

echo $init_html;
echo $header;

echo "<a name='top'>&nbsp;</a>";

if($print!=1)
{
   if($level!=1)
      echo "<br><a href=\"coopappindex.php?session=$session\">Return to Coops Main Menu</a>";
   echo "<form method=post action=\"coopapp.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"schoolid\" value=\"$schoolid\">";
   echo "<input type=hidden name=\"coopappid\" value=\"$coopappid\">";
}
else if($submitted==1)
   echo "<br><a href=\"coopappindex.php?session=$session\">Return to Coops Main Menu</a><br>";
else
{
   if($level==1 && $edit!=1)
      echo "<p><a href=\"coopapp.php?session=$session&coopappid=$coopappid&edit=1\">EDIT THIS FORM</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript:window.close();\">CLOSE WINDOW</a></p>";
   else
      echo "<br><a href=\"javascript:window.close();\">Close Window</a><br>";
}

$sql="SELECT * FROM headers ORDER BY school";
$result=mysql_query($sql);
$ix=0; $schools=array(); $schools[id]=array(); $schools[name]=array();
while($row=mysql_fetch_array($result))
{
   $schools[id][$ix]=$row[id];
   $schools[name][$ix]=$row[school];
   $ix++;
}

echo "<a name='top'>&nbsp;</a><br><table style=\"width:750px;\" class='nine' cellspacing=0 cellpadding=3><caption><b>AGREEMENT FOR COOPERATIVE SPONSORSHIP:</b>";

//CHECK AND SEE IF THE CO-OPING SCHOOLS HAVE ALL FILLED OUT THEIR INDIVIDUAL FORMS
if($coopappid && $schoolid==$app[schoolid1])    //SHOW STATUS OF SCHOOL AGREEMENT FORMS - HEAD SCHOOL ONLY
{
   $allcomplete=1;
   for($i=1;$i<=4;$i++)
   {
      $var="schoolid".$i;
      if($app[$var]>0)
      {
         $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='".$app[$var]."'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
	 if($row[datesub]==0) $allcomplete=0;
      }
   }
   if($allcomplete==1 && $app[datesubtoNSAA]==0)
   {
      //ARE THERE ERRORS?
      if(GetCoopAppErrors($coopappid)!='')
      {
	//YES:
	 echo "<div class='error' style='width:650px;'>The following errors must be fixed before you can submit this agreement to the NSAA:<br><br>".GetCoopAppErrors($coopappid)."</div>";
	 $highlighterrors=1;
      }
      else
      { 
      	//NO:
         echo "<div class='alert' style='width:700px;background-color:#99ffff;margin:10px;padding:10px;'><b>Your form is ready to submit to the NSAA!</b><p>Please double check that all information is correct and then click \"Submit Agreement to the NSAA\" at the bottom of this screen.</div><br>";
	 $highlighterrors=0;
      }
   }
}
if($submitted==1)
   echo "<div class='alert' style='width:600px;'><b>Congratulations!</b> This Agreement for Cooperative Sponsorship has been submitted to the NSAA.</div>";
echo "<p style='margin:10px;'><a href=\"CoopAppInfo.pdf\" target=\"_blank\">Guidelines for Cooperative Sponsorships (PDF)</a></p>";
echo "</caption>";

//RENEWAL OR BRAND NEW APPLICATION?
echo "<tr align=left><td><br><p>Is this a <b><u>NEW</b></u> Cooperative Sponsorship or a <b><u>RENEWAL</b></u> of an existing Cooperative Sponsorship?</p>";
echo "<p><input type=radio name='renewal' value='z' id='newform' onClick=\"if(this.checked) { document.getElementById('newformdiv').style.display=''; }\"";
if($app[renewal]!='x') echo " checked";
if($print==1) echo " disabled";
echo "> NEW&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name='renewal' value='x' id='renewalform' onClick=\"if(this.checked) { document.getElementById('newformdiv').style.display='none'; }\"";
if($app[renewal]=='x') echo " checked";
if($print==1) echo " disabled";
echo "> RENEWAL</p>";

//AGREEMENT IS BETWEEN UP TO 4 SCHOOLS, INCLUDING SCHOOL FILLING OUT THIS FORM (HEAD SCHOOL)
echo "<tr align=left><td><br><p><b>This Agreement is made between/among the School Boards of:</b></p>";
	//Head School
if($print==1)
{
   echo "<div style='padding-left:20px;'><p>School District No. $app[dist1], ".GetSchool2($app[schoolid1]).", Nebraska and</p>";
   echo "<p>School District No. $app[dist2], ".GetSchool2($app[schoolid2]).", Nebraska";
   if($app[schoolid3]>0)
      echo " and</p><p>School District No. $app[dist3], ".GetSchool2($app[schoolid3]).", Nebraska";
   if($app[schoolid4]>0)
      echo " and</p><p>School District No. $app[dist4], ".GetSchool2($app[schoolid4]).", Nebraska";
   echo ".</p></div>";
}
else
{
echo "<p>School District No. <input type=text size=3 name=\"dist1\" id=\"dist1\" value=\"$app[dist1]\" onBlur=\"document.getElementById('distnum').value=this.value; document.getElementById('distnum2').value=this.value;\"";
if($highlighterrors==1 && $app[dist1]==0) echo " style=\"background-color:#ff0000;color:#ffffff;\"";
echo ">, <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$school&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>, Nebraska and</p>";
	//Other Schools
for($ix=2;$ix<=4;$ix++)
{
   $distvar="dist".$ix; $schvar="schoolid".$ix;
   echo "<p>School District No. <input type=text size=3 name=\"$distvar\" id=\"$distvar\" value=\"".$app[$distvar]."\"";
   if($highlighterrors==1 && $app[$distvar]==0 && $app[$schvar]>0) echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">, <select name=\"$schvar\"><option value=\"0\">Select School</option>";
   for($i=0;$i<count($schools[id]);$i++)
   {
      echo "<option value=\"".$schools[id][$i]."\"";
      if($app[$schvar]==$schools[id][$i]) echo " selected";
      echo ">".$schools[name][$i]."</option>";
   }
   echo "</select>, Nebraska";
   if($ix==4) echo ".";
   else echo " and";
   echo "</p>";
}
}//END IF NOT PRINT

if($app[datesubtoNSAA]>0)
   echo "<p><b>Date of Agreement:</b> ".date("F j, Y",$app[datesubtoNSAA])."</p>";

//ACTIVITIES
echo "<p>The parties agree as follows:</p>
	<ol><li>
	<p><u><b>Joint Application</u>.</b> The above-named governing boards shall jointly make an application to the Nebraska School Activities Association (NSAA) Board of Directors before (July 1 for fall activities, September 1 for winter activities or January 1 for spring activities) <b>$year1</b>, for approval for cooperative sponsorship of a joint high school program.</p>
	<p><b><i>Please check the activity or activities for which the above-named governing boards are applying for cooperative sponsorship.</i></b></p>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
$cursection="";
for($i=0;$i<count($coopsports);$i++)
{
   if($cursection!=$coopsections[$i])
   {
      if($i>0) echo "</tr>";
      echo "<tr align=left><td><b>".strtoupper($coopsections[$i])."</b></td>";
      $cursection=$coopsections[$i];
   }
   echo "<td title=\"".GetActivityName($coopsports[$i])."\"><input type=checkbox name=\"$coopsports[$i]\" value=\"x\"";
   if($app[$coopsports[$i]]=='x') echo " checked";
   if($print==1) echo " disabled";
   echo "> ".strtoupper($coopsports2[$i])."</td>";
}
echo "</tr></table><br>";
echo "<p>hereinafter \"combined program,\" for students attending the above-named schools for years:";
echo "<ul style=\"list-style-type:none;\">";
if($coopappid && $app[year1]>0 && $app[year1]<$year1)	//NEED TO MAKE SURE THE YEAR THEY CHECKED IS STILL SHOWING
{
        echo "<li><input type=checkbox id=\"year0check\" name=\"year0check\" value=\"x\" onClick=\"if(this.checked) { document.getElementById('year1').value='$year1'; } else { document.getElementById('year1').value=''; }\" checked";
        if($print==1) echo " disabled";
	$year0=$app[year1];
	echo "> $year0-$year1</li>";
}
	echo "<li";
if($highlighterrors==1 && $app[year1]==0) echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	echo "><input type=checkbox id=\"year1check\" name=\"year1check\" value=\"x\" onClick=\"if(this.checked) { document.getElementById('year1').value='$year1'; document.getElementById('year3check').checked=false; if(document.getElementById('year2check').checked) { document.getElementById('year2').value='$year2'; } } else { document.getElementById('year1').value=''; }\"";
     	if(!$coopappid || $app[year1]==$year1 || $app[year2]==$year1) echo " checked";
   	if($print==1) echo " disabled";
echo "> $year1-$year2</li>
	<li><input type=checkbox id=\"year2check\" name=\"year2check\" value=\"x\" onClick=\"if(this.checked && document.getElementById('year1check').checked) { document.getElementById('year2').value='$year2'; } else if(this.checked) { document.getElementById('year1').value='$year2'; } else { document.getElementById('year2').value=''; }\"";
	if(!$coopappid || $app[year1]==$year2 || $app[year2]==$year2) echo " checked";
        if($print==1) echo " disabled";
echo "> $year2-$year3</li>
	<li><input type=checkbox id=\"year3check\" name=\"year3check\" value=\"x\" onClick=\"if(this.checked) { document.getElementById('year2').value='$year3'; document.getElementById('year1check').checked=false; if(document.getElementById('year2check').checked) { document.getElementById('year1').value='$year2'; } } else { document.getElementById('year2').value=''; }\"";
        if($app[year2]==$year3) echo " checked";
	if($print==1) echo " disabled";
echo "> $year3-$year4</li>
	(Check all school years to be covered.)</ul></p>";
echo "</li>";

/***** BEGIN NEW FORM DIV - FOR NON-RENEWALS *****/
echo "<div id='newformdiv'";
if($app[renewal]=='x') echo " style=\"display:none;\"";
echo ">";

//PURPOSE
echo "<li><p><b><u>Purpose</u>.</b> The purposes for the above-named boards agreeing to apply for authority to cooperatively sponsor the combined program are as follows: (Specify conditions which have prompted the Boards to agree.)</p>";
	echo "<p><ol style=\"list-style-type:lower-alpha;\">";
	for($i=1;$i<=4;$i++)
	{
	   $varname="purpose".$i;
	   if($print==1)
		echo "<li style='color:#444444;'>".$app[$varname]."</li>";
	   else
	   {
	      echo "<li><br><textarea rows=3 cols=90 name=\"$varname\"";
	     if($highlighterrors==1 && trim($app[$varname])=="" && $i==1) echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	      echo ">".$app[$varname]."</textarea></li>";
	   }
	}
	echo "</ol></p>";
echo "</li>";

//AGREEMENT TO COOPERATE
echo "<li><p><b><u>Agreement to Cooperate</u>.</b> If the joint application is approved by the NSAA Board of Directors, the above-named governing boards agree that they will cooperatively sponsor the combined program in the school years specified, provided that nothing in this provision shall be deemed to require that the governing boards offer that combined program at all in any particular year.</p></li>";

//TERMS & CONDITIONS OF COOPERATIVE SPONSORSHIP
echo "<li><p><b><u>Terms and Conditions of Cooperative Sponsorship</u>.</b> Any combined program shall be cooperatively sponsored upon the following terms and conditions:</p>
	<ol style=\"list-style-type:lower-alpha;\">";
	//Team Name, Mascot and Colors
        if($print==1)
	   echo "<li><p><u>Team Name, Mascot and Team Colors</u>. The team shall be known as <u>$app[teamname]</u> (Name), <u>$app[mascot]</u> (Mascot), with School District No. <u>$app[dist1]</u> serving as host school district. The team colors are <u>$app[colors]</u>.</p></li>";
	else
        {
	   echo "<li><p><u>Team Name, Mascot and Team Colors</u>. The team shall be known as (Name) <input type=text size=30 name=\"teamname\" id=\"teamname\" value=\"$app[teamname]\"";
	   if($highlighterrors==1 && trim($app[teamname])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	   echo ">, (Mascot) <input type=text size=20 name=\"mascot\" id=\"mascot\" value=\"$app[mascot]\"";
	   if($highlighterrors==1 && trim($app[mascot])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	   echo ">, with School District No. <input type=text size=3 name=\"distnum\" id=\"distnum\" value=\"$app[dist1]\" readOnly=true> serving as host school district. The team colors are <input type=text size=25 name=\"colors\" id=\"colors\" value=\"$app[colors]\"";
	   if($highlighterrors==1 && trim($app[colors])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	   echo ">.</p></li>";
	}
	//Contracts
	echo "<li><p><u>Contracts</u>. Except as otherwise provided herein, contracts related to the cooperatively sponsored team with groups such as referee associations, with individuals, or with other schools or school districts, shall be made by the governing board of School District No. ";
	if($print==1) echo "<u>$app[dist1]</u>";
	else echo "<input type=text size=3 name=\"distnum2\" id=\"distnum2\" readOnly=true value=\"$app[dist1]\">";
	echo ", after consultation with the governing board of the cooperating school district. <b><u>In the event this co-op qualifies for reimbursement for any state championships, the check should be written to the head school.</b></u></p></li>";
	//Allocation of Costs
	$alloctext=array("Expenses for transportation, including daily transportation of participants to and from practice sessions and contests.","Expenses for transportation to \"away contests.\"","Expenses for spectator buses.","Expenses for facilities, lights, heating, showers, towels, laundry, etc., of the host school, including maintenance of practice and competitive facilities.","Expenses for banquets and awards.","Expenses for scouting, coaches' meetings and workshops.","Expenses for payment of referees and other personnel necessary to stage the event.","Expenses for purchasing of supplies and equipment.","Expenses for salary and fringe benefit costs for coaches and other activity personnel.","Other expenses.");
	echo "<li><p><u>Allocation of Costs</u>. All costs of the combined program shall be allocated between/among the parties in the manner indicated below for each expenditure category listed:<ol style=\"list-style-type:upper-roman;\">";
	for($i=0;$i<count($alloctext);$i++)
	{
	   $i2=$i+1; $varname="allocation".$i2;
	   echo "<li><p>".$alloctext[$i]." (Specify method of allocation.)</p>";
           if($print==1)
                echo "<p style='color:#444444;'>".$app[$varname]."</p>";
           else
	   {
	      echo "<p><textarea rows=3 cols=90 name=\"$varname\" id=\"$varname\"";
              if($highlighterrors==1 && trim($app[$varname])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	      echo ">".$app[$varname]."</textarea></p>";
	   }
	}
	echo "</ol><p>In the event that the allocation of an expenditure item is not specified above, the costs of that item shall be shared EQUALLY between/among the cooperating parties.</p></li>";
	//Allocation of Gate Receipts
	echo "<li><p><u>Allocation of Gate Receipts</u>. Funds from gate receipts shall be divided by the parties after payment of referees and other personnel in the following manner: (Specify method of allocation.)</p>";
           if($print==1)
                echo "<p style='color:#444444;'>".$app[gatereceipts1]."</p>";
           else
   	   {
	      echo "<p><textarea name=\"gatereceipts1\" id=\"gatereceipts1\" rows=3 cols=90";
              if($highlighterrors==1 && trim($app[gatereceipts1])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	      echo ">$app[gatereceipts1]</textarea></p>";
	   }
	echo "<p>In the event the gate receipts are insufficient to make the payments, the parties shall make up the difference in the following manner: (Specify method of allocation.)</p>";
           if($print==1)
                echo "<p style='color:#444444;'>".$app[gatereceipts2]."</p>";
           else
	   {
	        echo "<p><textarea name=\"gatereceipts2\" id=\"gatereceipts2\" rows=3 cols=90";
	        if($highlighterrors==1 && trim($app[gatereceipts2])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
	        echo ">$app[gatereceipts2]</textarea></p>";
	   }
	echo "</li>";
	//Concessions
	echo "<li><p><u>Concessions</u>. The provision of concessions at home contests shall be the responsibility of the home location school, and concession revenues shall not be covered by the provisions of this Agreement unless the parties specifically agree to the contrary herein.</p>";
	//Utilization of Resources
	echo "<li><p><u>Utilization of Resources</u>. Personnel in charge of the program shall make every attempt to utilize the resources of each of the cooperating schools, such as equipment and uniforms.</p></li>";
	//Employment of Personnel
	echo "<li><p><u>Employment of Personnel</u>.</p><ol style=\"list-style-type:upper-roman\">";
		echo "<li><p>The head coach of the combined program shall be employed by the school board of School District No. ";
	if($print==1) echo "<u>$app[headcoachdist]</u>";
	else 
	{
	   echo "<input type=text size=3 name=\"headcoachdist\" id=\"headcoachdist\" value=\"$app[headcoachdist]\"";
           if($highlighterrors==1 && $app[headcoachdist]==0) echo " style=\"background-color:#ff0000;color:#ffffff;\"";
           echo ">";
 	}
	echo ".</p></li>";
		echo "<li><p>Other joint program personnel, if any, shall be employed as follows:</p><table cellspacing=0 cellpadding=3><tr align=center><td>POSITION</td><td>EMPLOYER</td></tr>";
		for($i=1;$i<=5;$i++)
	 	{
		   $pvar="position".$i; $evar="employer".$i;
		   if($print==1)
			echo "<tr align=center><td>".$app[$pvar]."</td><td>".$app[$evar]."</td></tr>";
		   else
		   {
		      echo "<tr align=center><td><input type=text size=30 name=\"$pvar\" id=\"$pvar\" value=\"".$app[$pvar]."\"></td>
			<td><input type=text size=30 name=\"$evar\" id=\"$evar\" value=\"".$app[$evar]."\"></td></tr>";
		   }
	  	}
		echo "</table></li><br>";
		echo "<li><p>Recommendations for employment of personnel by each board shall be in accordance with the board's policies.</p></li>";
		echo "<li><p>Coaches and other personnel employed by a school district shall meet applicable state requirements.</p></li>";
	echo "</ol></li>";	
	//Control and Supervision
	echo "<li><p><u>Control and Supervision of Programs and Participants</u>. The control and supervision of a combined program, and of the behavior of student participants in the program, shall be the responsibility of the host school district.</p><p>The control and supervision of student participants while in transport to and from the host school district shall be the responsibility of the home school district.</p></li>";
	echo "</ol>";
echo "</li>";

//INTERDISTRICT ADVISORY BOARD
echo "<li><p><b><u>Interdistrict Advisory Board</u>.</b> An Interdistrict Advisory Board may be formed from members of the schools to work on the improvement of the various co-sponsored programs.</p></li>";

//RESOLUTION OF DISPUTES
echo "<li><p><b><u>Resolution of Disputes</u>.</b> Any disputes relating to this Agreement, or items in this Agreement requiring clarification, will be investigated by the school superintendents from each school, and they will present their findings and recommendations to their respective boards.</p></li>";

//TERM, DISSOLUTION
if(!$coopappid) 
{
   $showyear1=$year1; $showyear2=$year2;
}
else
{
   $showyear1=$app[year1]; $showyear2=$app[year2];
}
echo "<li><p><b><u>Term, Dissolution</u>.</b> The term of this Agreement shall be for school years <input type=text size=5 name=\"year1\" readOnly=true id=\"year1\" value=\"$showyear1\"> and <input type=text size=5 readOnly=true name=\"year2\" id=\"year2\" value=\"$showyear2\">. The Agreement shall terminate at the end of the last school year specified, unless extended by mutual agreement. If the parties determine to extend the Agreement beyond the period specified, they agree to submit a \"Cooperative Program Renewal Agreement\" form to the NSAA Board of Directors prior to July 1 for fall activities, September 1 for winter activities and January 1 for spring activities, preceding the school year or season in which the coop program is to be implemented. If the parties determine to dissolve the Agreement at an earlier date, they agree to submit an application requesting dissolution by April 1 of the school year prior to the school year in which dissolution is requested, i.e., April 1, $year1, for dissolution for the $year1-$year2 school year. If the early dissolution of the Agreement is not approved, the combined program must be offered cooperatively, or not at all, during the remaining terms of the Agreement.</p></li>";

//LIABILITY, INSURANCE
echo "<li><p><b><u>Liability Insurance</u>.</b> 

Nothing contained in this Agreement shall relieve any party to this Agreement from liability for its negligence or that of its officer, agents and employees.  Each party shall carry a minimum liability insurance limit in the amount of $";
if($print==1) echo "<u>$app[claimantins]</u>";
else 
{
   echo "<input type=text size=10 name=\"claimantins\" id=\"claimantins\" value=\"$app[claimantins]\"";
   if($highlighterrors==1 && trim($app[claimantins])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">";
}
echo " for any one liability occurrence and carry a minimum aggregate liability insurance limit of $";
if($print==1) echo "<u>$app[claimins]</u>";
else 
{
   echo "<input type=text size=10 name=\"claimins\" id=\"claimins\" value=\"$app[claimins]\"";
   if($highlighterrors==1 && trim($app[claimins])=="") echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">";
}
echo " for any accumulation of separate liability occurrences that may occur during the insured policy period.  The policy shall name the officers, agents and employees of the other party as named insured.  Each party shall provide the other party with a certificate evidencing such insurance coverage. </p></li>";

echo "</ol>";

/***** END NEW FORM DIV *****/
echo "</div>";

if($coopappid && $schoolid==$app[schoolid1])	//SHOW STATUS OF SCHOOL AGREEMENT FORMS - HEAD SCHOOL ONLY
{
   echo "<p><br><b>Status of Cooperative Sponsorship Agreement Forms required of each school:</b></p>";
   echo "<ol>";
   $allcomplete=1;
   for($i=1;$i<=4;$i++)
   {
      $var="schoolid".$i;
      if($app[$var]>0)
      {
	 $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='".$app[$var]."'";
   	 $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if($row[datesub]>0)
	    echo "<li><a href=\"coopschoolapp.php?session=$session&coopschoolappid=$row[id]&coopappid=$coopappid&print=1&schoolid=$row[schoolid]\" target=\"_blank\">".GetSchool2($row[schoolid])." submitted their Cooperative Sponsorship Agreement Form on ".date("F j, Y",$row[datesub])."</a></li><br>";
         else
         {
	    $allcomplete=0;
	    echo "<li>".GetSchool2($app[$var])." has not yet submitted their Cooperative Sponsorship Agreement Form</li><br>";
	 }
      }
   }
   echo "</ol>";
   if(!$allcomplete)
   {
      echo "<p><i>Once all of the schools have completed their individual Cooperative Sponsorship Agreement Forms, the final portion of this application will be shown below for you to complete and submit to the NSAA office.</i></p>";
   }
   else	//FINAL PORTION OF THIS FORM AND NSAA PORTION
   {
      echo "<p>IN WITNESS WHEREOF, the Parties, by their respective officers on the dates indicated, have executed said Agreement.</p><ol>";
      for($i=1;$i<=4;$i++)
      {
	 $var="schoolid".$i; $var2="dist".$i;
	 if($app[$var]>0)
	 {
	    $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='".$app[$var]."'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    echo "<li><p>School District No. ".$app[$var2].", <b>".GetSchool2($app[$var])."</b>, Nebraska.</p>";
		if($app[renewal]=='x') echo "<p>By: $row[boardmember1] (Superintendent)</p>";
	   	else
		{
	    		echo "<p>By: $row[boardchair] (Chair)</p>
			<p>By: $row[boardclerk] (Clerk)</p>";
	        }
		echo "<p>Dated: ".date("F j, Y",$row[datesub])."</p></li><br>";
	 }
      }  
      echo "</ol>";
   }
}

if($app[datesubtoNSAA]>0)	//FORM HAS BEEN SUBMITTED TO THE NSAA - NSAA BOARD CAN TAKE ACTION
{
   echo "<a name='execaction'>&nbsp;</a><hr style='background-color:#000000;'>";
   echo "<h2 style='text-align:center;'>OFFICIAL ACTION BY BOARD OF DIRECTORS</h2>";
   echo "<p>The Agreement between/among</p>";
   echo "<div style='padding-left:20px;'><p>School District No. $app[dist1], ".GetSchool2($app[schoolid1]).", Nebraska and</p>";
   echo "<p>School District No. $app[dist2], ".GetSchool2($app[schoolid2]).", Nebraska";
   if($app[schoolid3]>0)
      echo " and</p><p>School District No. $app[dist3], ".GetSchool2($app[schoolid3]).", Nebraska";
   if($app[schoolid4]>0)
      echo " and</p><p>School District No. $app[dist4], ".GetSchool2($app[schoolid4]).", Nebraska";
   echo "</p></div>";
   echo "<p>to cooperatively sponsor an interscholastic program in ";
   $actlist="";
      for($i=0;$i<count($coopsports);$i++)
      {
	 $var=$coopsports[$i];
         if($app[$var]=='x') $actlist.=GetActivityName($var).", ";
      }
   $actlist=substr($actlist,0,strlen($actlist)-2);
   echo "<u>$actlist</u></p>";
   if($app[execdate]==0) $execdate=time();
   else $execdate=$app[execdate];
   if($level!=1 && $app[execdate]==0)	//HASN'T BEEN ACTED UPON YET - SCHOOLS JUST SEE A NOTE SAYING SO
   {
      echo "<p>is <b>awaiting consideration by the Nebraska School Activities Association Board of Directors</b>.</p>";
   }
   else
   {
      echo "<p>was considered by the Nebraska School Activities Association Board of Directors on ";
      if($edit==1 && $level==1)
      {
	 $exec=date("Y-m-d",$execdate);
	 $exec=explode("-",$exec);
	 echo "<select name=\"execmo\"><option value=\"00\">MM</option>";
	 for($i=1;$i<=12;$i++)
	 {
	    if($i<10) $m="0".$i;
	    else $m=$i;
	    echo "<option value=\"$m\"";
	    if($exec[1]==$m) echo " selected";
	    echo ">$m</option>";
	 }
	 echo "</select>/";
         echo "<select name=\"execday\"><option value=\"00\">DD</option>";
         for($i=1;$i<=31;$i++)
         {
            if($i<10) $d="0".$i;
            else $d=$i;
            echo "<option value=\"$d\"";
            if($exec[2]==$d) echo " selected";
            echo ">$d</option>";
         }
         echo "</select>/";
         echo "<select name=\"execyr\"><option value=\"00\">YYYY</option>";
	 $yr1=date("Y")-1; $yr2=date("Y");
         for($i=$yr1;$i<=$yr2;$i++)
         {
            echo "<option value=\"$i\"";
            if($exec[0]==$i) echo " selected";
            echo ">$i</option>";
         }
         echo "</select>.</p>";
      }
      else
         echo "<u>".date("F j, Y",$execdate)."</u>.</p>";
      if($level==1 && $edit==1)
      {
         echo "<p>The Board of Directors <input type=radio name='approvedeny' value='approved'";
         if($app[approved]=='x') echo " checked";
         echo "> Approved   <input type=radio name='approvedeny' value='denied'";
         if($app[denied]=='x') echo " checked";
         echo "> Denied <input type=radio name='approvedeny' value='reset'";
         echo "> RESET";
	 if($error==1 && $execsubmit)
	    echo "<label class='red'>PLEASE CHECK \"Approved\" OR \"Denied.\"</label>";
         echo "</p>";
      }
      else
      {
         echo "<p>The Board of Directors ";
         if($app[approved]=='x') echo "<b><u>APPROVED</b></u>";
         else if($app[denied]=='x') echo "<b><u>DENIED</b></u>";
         else echo "have yet to approve or deny";
         echo " this Agreement";
         if($app[execdate]>0) echo " as of ".date("F j, Y",$app[execdate]).".</p>";
         else echo ".</p>";
         if($level==1 && $app[approved]!='x' && $app[denied]!='x')
            echo "<p style='text-align:center;'><b><i>To take official action on this form, please click</i></b> <a href=\"coopapp.php?session=$session&coopappid=$coopappid&edit=1#execaction\">Edit this Form</a></p>";
      }
      if($app[execdate]>0)	//SIGNATURE
	 echo "<img src=\"../images/tenopirsig.png\" style=\"height:50px;\"><br>Executive Director";
   } //END IF LEVEL 1 OR EXEC ACTION HAS BEEN TAKEN
   
   echo "<hr style='background-color:#000000;'>";
}

if($print!=1)
{
   if($allcomplete!=1)
   {
      echo "<input type=submit class=\"continuebutton\" name=\"continue\" value=\"Save & Continue\" style=\"float:right;\">";
      echo "<div style='clear:both;'></div>";
      if($app[datesubtoschools]==0) echo "<div class='alert' style='width:700px;background-color:#99ffff;margin:10px;padding:10px;'><b><u>NOTE:</u></b> <i>Clicking <b>Save & Continue</b> will take you to your school's Resolution Form that will need to be completed <B><u>AND</b></u> will alert the <b>schools you selected above for this co-op</b> that they need to review this form and complete their portion as well.</i></div>";
   }
}

echo "</td></tr></table>";

if($app[datesubtoNSAA]==0 && $allcomplete==1 && $print!=1)
   echo "<input type=submit class=\"fancybutton2\" name=\"submittonsaa\" value=\"Submit Agreement to the NSAA!\">";
else if($allcomplete==1 && $app[execdate]==0 && $level==1 && $print!=1)
   echo "<input type=submit class=\"fancybutton2\" name=\"execsubmit\" value=\"Commit Executive Action\">";
else if($allcomplete==1 && $level==1 && $print!=1)
   echo "<input type=submit class=\"fancybutton2\" name=\"savechanges\" value=\"Save Changes\">";

if($print!=1)
   echo "</form><br><br><a href=\"#top\">Return to Top</a>";
else
{
   echo "<br><br>";
   if($level==1)
      echo "<a href=\"coopapp.php?session=$session&coopappid=$coopappid&edit=1\">Edit this Form</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   echo "<a href=\"#top\">Return to Top</a>";
   if($submitted!=1)
      echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript:window.close();\">Close Window</a>";
   else
      echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"coopappindex.php?session=$session\">Return to Coops Main Menu</a>";
}

echo $end_html;
?>
