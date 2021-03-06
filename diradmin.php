<?php
/*************************************
diradmin.php
Significantly updated 9/12/11 to 
integrate new online activities
registration data
Author: Ann Gaffigan
**************************************/

require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

/* CHECK FOR SPECIAL REPORTS */
if($rmsearch && $rmsport!='')	// SPECIAL REPORT - MISSING RULES MEETING ATTENDANCE
{
   echo $init_html."<br><table width='100%'><tr align=center><td><br>";
   echo "<form method='post' action='diradmin.php'>";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"rmsearch\" value=\"$rmsearch\">";
   echo "<input type=hidden name=\"rmsport\" value=\"$rmsport\">";
   echo "<table frame=all rules=all style='border:#d0d0d0 1px solid;width:800px;' cellspacing=0 cellpadding=5><caption>";
   if($save)	//Just saved some attendance check marks
   {
      for($i=0;$i<count($loginid);$i++)
      {
         if($check[$i]=='x')
   	 {
	    $sql="UPDATE logins SET rulesmeeting='x' WHERE id='$loginid[$i]'";
	    $result=mysql_query($sql);
	    //echo $sql."<br>";
	 }
      }
      echo "<div class='alert'>The checkmarks have been saved and those people have been removed from the list of people missing attendance below.</div><br><br>";
   }

      if($rmsport=="ad") $rmpeople="AD's";
      else $rmpeople=GetActivityName($rmsport)." Coaches";
      echo "<b>The following <b>$rmpeople</b> are missing rules meeting attendance as of ".date("F j, Y").".</b><br>NOTE: Only schools who have DECLARED (fall sports) or are REGISTERED will show below.<br><br></caption>";
      echo "<tr align=center><th class=smaller>School</th><th class=smaller>Sport/Activity</th><th class=smaller>AD or Coach's Name</th><th class=smaller>Attended</th><th class=smaller>Notes</th></tr>";
      if($rmsport=='ad') $sql="SELECT * FROM logins WHERE level=2 AND rulesmeeting!='x' ORDER BY school";
      else $sql="SELECT * FROM logins WHERE sport LIKE '%".GetActivityName($rmsport)."%' AND rulesmeeting!='x' ORDER BY school";
      $result=mysql_query($sql);
      $ix=0;
	   $coach_id= array();
      while($row=mysql_fetch_array($result))
      {
         $showperson=0;
         if(ereg("@",$row[email])) $row[name].="<br><a class=small href=\"mailto:$row[email]\">$row[email]</a>";
	//For fall sports, only show results for schools who Declared in the sport
	//For other sports, only show results for schools Registered for that sport
         if($rmsport=="fb" && IsDeclared($row[school],'fb'))
         {
     	    //We need to check that neither a Football 11 NOR a Football 6/8 coach has been marked as attending
	    //As of this point, we only know if one or the other has been marked as NOT attending.
            $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND sport LIKE 'Football%' AND rulesmeeting='x'";
            $result2=mysql_query($sql2);
			$samescholl=array(); /// logic upated to check that now school can print twice using the variable
            if(mysql_num_rows($result2)==0)	//This means we found no checkmark for any FB coach for this school - they're missing attendance
            {
				
				if(!in_array($row[school],$samescholl)){ 
					echo "<tr align=left><td>$row[school]</td><td>$row[sport]</td><td>$row[name]</td>"; $showperson=1;
					$samescholl[]=$row[school];
				}
            }
         }
         else if($rmsport=="sb" || $rmsport=="vb")         
         {            
	    if(IsDeclared($row[school],$rmsport))
            {
               echo "<tr align=left><td>$row[school]</td><td>$row[sport]</td><td>$row[name]</td>"; $showperson=1;
            }
         }
         else if(IsRegistered($row[school],$rmsport) || $rmsport=='AD')
         {
            if($row[level]==2) $row[sport]="Athletic Director";
            echo "<tr align=left><td>$row[school]</td><td>$row[sport]</td><td>$row[name]</td>"; $showperson=1;
         }
         if($showperson==1) //CHECK TO SEE IF THEY HAVE A COMPLETED RULES MEETING ON RECORD (meaning they were for some reason not checked in DB but should have been)
         {
	    //ADDED 8/14/12 - Check box so Jen can quickly mark their attendance on this screen
	    echo "<td align=center><input type=checkbox name=\"check[$ix]\" value=\"x\"><input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"></td>";
            $sql2="SELECT * FROM ".$rmsport."rulesmeetings WHERE coachid='$row[id]' AND datepaid>0";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)>0) {
				$coach_id[]= $row[id];
				echo "<td><div class=error style='width:300px'>OOPS: This person has a completed rules meeting in the database but the box for \"Rules Meeting\" is not checked in their record.</div><a class=small target=\"_blank\" href=\"directory.php?session=$session&school_ch=$row[school]&header=no\">Edit this Person's Record in $row[school]'s Directory</a><br>(After checking the box, saving and closing the window, reload this screen.)</td>";
				
			}	
            else echo "<td>&nbsp;</td>";
            echo "</tr>";
	    $ix++;
	    if($ix%10==0) 
	       echo "<tr align=center><td colspan=5><input type=submit name=\"save\" value=\"Save Checkmarks\"></td></tr>";	
         }
      }
	  /*
	  foreach($coach_id as $coach)
		{
			$sql="UPDATE logins SET rulesmeeting='x' WHERE id='$coach'";
	        $result=mysql_query($sql);
		}*/
   echo "</table><br><input type=submit name=\"save\" value=\"Save Checkmarks\"></form><br><br><a href=\"javascript:window.close()\" class=small>Close Window</a>".$end_html;
   exit();
}
else if($diffads=='x') // SPECIAL REPORT - DIFFERENT AD/ActDir THAN LAST YEAR
{
   echo $init_html."<table width='100%'><tr align=center><td><br><table style='width:700px;' cellspacing=0 cellpadding=5><caption>";

      if(date("m")<6) $thisyr=date("Y")-1;
      else $thisyr=date("Y");
      $lastyr=$thisyr-1;
      $lastyrdb=GetDatabase($lastyr);
      $sql="SELECT t1.*,t2.name AS oldname,t2.school AS oldschool FROM $db_name.logins AS t1,$lastyrdb.logins AS t2 WHERE t1.id=t2.id AND (t1.level='2' OR t1.sport='Activities Director') AND t1.name!=t2.name ORDER by t1.school";
      $result=mysql_query($sql);
      echo "<b>Report of Schools with Different AD's/Activities Directors than Last Year:</b><br>As of ".date("F j, Y")."</caption>";
      echo "<tr align=center><td><b>School</b></td><td><b>Staff Member</b></td><td><b>This Year</b></td><td><b>Last Year</b></td></tr>";
      while($row=mysql_fetch_array($result))
      {
         if($row[level]==2) $row[sport]="Athletic Director";
         echo "<tr align=left><td>$row[school]</td><td>$row[sport]</td><td>$row[name]&nbsp;</td><td>$row[oldname]&nbsp;</td></tr>";
      }
      echo "</table>";
      echo "<br><br><a href=\"javascript:window.close()\" class=small>Close Window</a>";
      echo $end_html;
      exit();
}
/* END CHECK FOR SPECIAL REPORTS */

$schoolfields=array("school","nsaadist","enrollment","class","address1","address2","city_state","zip","phone","fax","website","color_names","mascot","conference");
$schoolfields2=array("School Name","NSAA District","Enrollment","Class","Address 1","Address 2","City & State","Zip Code","Phone","Fax","Website","Colors","Mascot","Conference");

/* UPLOAD TEAM FILE */
if($uploadteamfile && is_uploaded_file($_FILES['teamfile']['tmp_name']) && ($decactivity!='' || $regactivity!=''))
{
   if($decactivity!='') $curactivity=$decactivity;
   else $curactivity=$regactivity;
   //USER UPLOADED A FILE CONTAINING THE CLASSIFICATIONS FOR EACH TEAM IN AN ACTIVITY (Update __school Table)
   $destination="../../reports/".$curactivity."classifications.csv";
   if(!citgf_copy($_FILES['teamfile']['tmp_name'],$destination))	//COPY ERROR
   {
      echo $init_html;
	echo GetHeader($session);
      echo "<br><br><div class=error style='width:400px;'>ERROR: The uploaded file couldn't be copied to \"".basename($destination)."\" on the server.<br><a href=\"javascript:history.go(-1);\" class=white>Go Back</a> and try again or report this error to the programmer.</div><br><br>";
      echo $end_html;
      exit();
   }
   $open=fopen(citgf_fopen($destination),"r");
   $lines=file(getbucketurl($destination));
   fclose($open);
   for($i=0;$i<count($lines);$i++)
   {
      $line=split(",",$lines[$i]);
      $sid=preg_replace("/[^0-9]/","",$line[0]);
      if($sid>0)
      {
         $class=$line[3];
         $sql="UPDATE ".GetSchoolsTable($curactivity)." SET class='$class' WHERE sid='$sid'";
	 $result=mysql_query($sql);
      }
   }
   $search=1; 
}
/* END UPLOAD TEAM FILE */

/* OTHER SEARCH SUBMITTED */
if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}
else if($search)
{
      echo $init_html;
      echo GetHeader($session);
      echo "<br>";
      echo "<a href=\"diradmin.php?session=$session\" class=small>Back to School Directory Admin</a><br><br>";
      echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\"><caption>";

   if($decactivity!='' || $regactivity!='')		//SEARCH INCLUDES REGISTRATION - ADDITIONAL OPTIONS
   { 
      $showclass="";
      if($decactivity!='') 
      { 
	 $curactivity=$decactivity; $registered="declared"; 
	 if($decclass!='') $showclass=$decclass;
      }
      else 
      {
	 $curactivity=$regactivity; $registered="registered"; 
	 if($regclass!='') $showclass=$regclass;
      }
      echo "<b>School Directory Advanced Search Results:</b><br>";
      /* REGISTRATION DOWNLOADS - CLASSIFICATIONS & MAILING LABELS */
      echo "<table><tr align=left><td><div class=alert>The schools listed below have <u><b>$registered for ".GetActivityName($curactivity)."</b></u>";
      if($showclass!='') echo " and are in <u><b>Class $showclass</b></u>";
      echo ".<br><br>";
      $schfile=$curactivity."classifications_".date("mdY").".csv";
      $dirfile=$curactivity."mailinglabels_".date("mdY").".csv";
      echo "<i>Once this screen has finished loading, you can download the following:</i><ul>";
      echo "<li><a href=\"exports.php?filename=$schfile&session=$session\">Classifications Export</a><br>Download this export if you wish to update the classifications for ".GetActivityName($curactivity)." in an Excel file and then upload them to the database.";
      //SHOW SCHOOLS THAT HAVE DECLARED BUT ARE NOT IN TEAM LIST:
	echo "<div id='warning' class='error' style='display:none;'></div>";

	echo "<div class='normalwhite' style=\"width:400px;padding:10px;font-size:12px;margin:10px;\">";
	if($classupdate==1)
	   echo "<div class='help' style='width:350px;'>The ".GetActivityName($curactivity)." classifications have been successfully updated!</div>";
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"diradmin.php\">
		<input type=hidden name=\"session\" value=\"$session\">
		<input type=hidden name=\"regactivity\" value=\"$regactivity\">
	  	<input type=hidden name=\"decactivity\" value=\"$decactivity\">
	 	<input type=hidden name=\"schtype\" value=\"$schtype\">
		<p>Once you have updated the CLASS column in this export, <b><u>save it as a Comma-Delimited (.csv) File</b></u> and <b>upload it below:</b></p><p><input type=file name=\"teamfile\"></p><input type=submit class=fancybutton name=\"uploadteamfile\" value=\"Upload Team Classifications\"></form></div></li>";
      echo "<li><a href=\"exports.php?filename=$dirfile&session=$session\">Mailing Labels Export</a><br>Download this export if you wish to print mailing labels for schools registered for ".GetActivityName($curactivity).".</li>";
      echo "</ul>";
      echo "</div>";
      echo "</td></tr></table><br>";
      /* END REGISTRATION OPTIONS */
   
      /* GENERATE SPECIAL REGISTRATION EXPORTS */
      $table=GetSchoolTable($curactivity);
      $sql="SELECT * FROM $table";
      if($regactivity==$curactivity && $regclass!='') $sql.=" WHERE class='$regclass'";
      else if($decactivity==$curactivity && $decclass!='') $sql.=" WHERE class='$decclass'";
      $sql.=" ORDER BY school";
      $result=mysql_query($sql);
      //code by robin to add total enrollment , total boys , total girls
      //newly added Total girls enrollment and Total boys enrollment
      //TEAM EXPORT: for filling in classifications (based on enrollment)
      $teamcsv="\"Team ID\",\"Team Name\",\"Total Team Enrollment\",\"Total Boys Enrollment\",\"Total Girls Enrollment\",\"CLASS\",\"Head School\",\"Enrollment\",\"Boys Enrollment\",\"Girls Enrollment\",\"Co-oping School #1\",\"Enrollment\",\"Boys Enrollment\",\"Girls Enrollment\",\"Co-oping School #2\",\"Enrollment\",\"Boys Enrollment\",\"Girls Enrollment\",\"Co-oping School #3\",\"Enrollment\",\"Boys Enrollment\",\"Girls Enrollment\"\r\n";
      //MAILING LABELS EXPORT: for printing labels
      $dircsv="\"School\",\"Class\",\"Address 1\",\"Address 2\",\"City and State\",\"Zip\",\"Phone\",\"Fax\",\"Enrollment\"\r\n";
      while($row=mysql_fetch_array($result))
      {
    	 if(GetSchool2($row[mainsch]) && (($regactivity!='' && IsRegistered2011($row[mainsch],$regactivity,'',TRUE)) || ($decactivity!='' && IsDeclared(GetSchool2($row[mainsch]),$decactivity))))
   	 {
          //total team enrollment
          $totalenroll=0; 
          //total boys enrollment
          $totalboysenroll=0;
          //total girls enrollment
          $totalgirlsenroll=0;
          $coopteamcsv="";
	    $sql2="SELECT * FROM headers WHERE id='$row[mainsch]'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
          $totalenroll+=$row2[enrollment];
          //code added by robin to find total girls enrollment and total boys enrollment
          $totalboysenroll+=(int)$row2[boysenrollment];
          $totalgirlsenroll+=(int)$row2[girlsenrollment];
          //end of code by robin
          $coopteamcsv.="\"$row2[school]\",\"$row2[enrollment]\",\"$row2[boysenrollment]\",\"$row2[girlsenrollment]\",";
	    $dircsv.="\"$row2[school]\",\"$row[class]\",\"$row2[address1]\",\"$row2[address2]\",\"$row2[city_state]\",\"$row2[zip]\",\"$row2[phone]\",\"$row2[fax]\",\"$row2[enrollment]\"\r\n";
   	    if($row[othersch1]>0)
	    {
               $sql2="SELECT * FROM headers WHERE id='$row[othersch1]'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $totalenroll+=$row2[enrollment];
               //code added by robin to find total girls enrollment and total boys enrollment
               $totalboysenroll+=(int)$row2[boysenrollment];
               $totalgirlsenroll+=(int)$row2[girlsenrollment];
               $coopteamcsv.="\"$row2[school]\",\"$row2[enrollment]\",\"$row2[boysenrollment]\",\"$row2[girlsenrollment]\",";
	         $dircsv.="\"$row2[school]\",\"$row[class]\",\"$row2[address1]\",\"$row2[address2]\",\"$row2[city_state]\",\"$row2[zip]\",\"$row2[phone]\",\"$row2[fax]\",\"$row2[enrollment]\"\r\n";
	    }
            if($row[othersch2]>0)
            {
               $sql2="SELECT * FROM headers WHERE id='$row[othersch2]'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $totalenroll+=$row2[enrollment];
               //code added by robin to find total girls enrollment and total boys enrollment
               $totalboysenroll+=(int)$row2[boysenrollment];
               $totalgirlsenroll+=(int)$row2[girlsenrollment];
               //end of code by robin
               $coopteamcsv.="\"$row2[school]\",\"$row2[enrollment]\",\"$row2[boysenrollment]\",\"$row2[girlsenrollment]\",";
	         $dircsv.="\"$row2[school]\",\"$row[class]\",\"$row2[address1]\",\"$row2[address2]\",\"$row2[city_state]\",\"$row2[zip]\",\"$row2[phone]\",\"$row2[fax]\",\"$row2[enrollment]\"\r\n";
	    }
            if($row[othersch3]>0)
            {
               $sql2="SELECT * FROM headers WHERE id='$row[othersch3]'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $totalenroll+=$row2[enrollment];
               //code added by robin to find total girls enrollment and total boys enrollment
               $totalboysenroll+=(int)$row2[boysenrollment];
               $totalgirlsenroll+=(int)$row2[girlsenrollment];
               //end of code by robin
               $coopteamcsv.="\"$row2[school]\",\"$row2[enrollment]\",\"$row2[boysenrollment]\",\"$row2[girlsenrollment]\",";
	       $dircsv.="\"$row2[school]\",\"$row[class]\",\"$row2[address1]\",\"$row2[address2]\",\"$row2[city_state]\",\"$row2[zip]\",\"$row2[phone]\",\"$row2[fax]\",\"$row2[enrollment]\"\r\n";
          }
            //total Team enrollment is being added here
            //newly added girls enrollemt and boys enrollment
            //code by robin
            $totalboysenroll=($totalboysenroll===0)? '': $totalboysenroll;
            $totalgirlsenroll=($totalgirlsenroll===0)? '': $totalgirlsenroll;            
            //end of code by robin
            $teamcsv.="\"$row[sid]\",\"$row[school]\",\"$totalenroll\",\"$totalboysenroll\",\"$totalgirlsenroll\",\"$row[class]\",".$coopteamcsv."\r\n";
	 }
      }
      if(!$open=fopen(citgf_fopen("../../reports/".$schfile),"w")) echo "COULD NOT OPEN $schfile";
      fwrite($open,$teamcsv);
      fclose($open); 
 citgf_makepublic("../../reports/".$schfile);
      if(!$open=fopen(citgf_fopen("../../reports/".$dirfile),"w")) echo "COULD NOT OPEN $dirfile";
      fwrite($open,$dircsv);
      fclose($open); 
 citgf_makepublic("../../reports/".$dirfile);
      /* END SPECIAL REGISTRATION EXPORTS */
   }	//END IF REGISTRATION ACTIVITY SELECTED

   /* AND NOW, THE REGULAR SEARCHES */
   if(!($schtype=="reg" && $regactivity!='') && !($schtype=="dec" && $decactivity!='')) echo "<b>School Directory Advanced Search Results:</b>";
   $direxpfile="DirectoryExport".strtoupper($curactivity).date("mjY").".csv";
   if($nsaadist!="SELECTDIST" && $schtype=="dist") $direxpfile="District".$nsaadist.$direxpfile;
   echo "<div class=alert style='margin:10px;padding:10px;width:650px;'><b>The FULL DATA EXPORT - including all the fields you checked on the previous screen - is included in the export file, to be downloaded at the link below.</b> What you see on the screen below is a quick view of the schools meeting your search criteria.<br><br><a href=\"exports.php?session=$session&filename=$direxpfile\">Download Full Data Export</a> - Please wait until this screen <u>finishes loading</u> so that your export will be complete.</div><br>";
   echo "</caption>";
   //PREP CSV EXPORT
   $csv="";
   for($i=0;$i<count($schoolfields);$i++)
   {
      if($field[$i]=='x') $csv.="\"$schoolfields2[$i]\",";
   }
   $ix=$i;
   for($i=0;$i<count($staff);$i++)
   {
      if($field[$ix][name]=='x') $csv.="\"$staff[$i] Name\",";
      if($field[$ix][phone]=='x') $csv.="\"$staff[$i] Phone\",";
      if($field[$ix][email]=='x') $csv.="\"$staff[$i] Email\",";
      if($field[$ix][passcode]=='x') $csv.="\"$staff[$i] Passcode\",";
      if($i==5)	//MAIN CONTACT
      {
         if($field[$ix][mainname]=='x') $csv.="\"Main Contact Name\",";
         if($field[$ix][maintitle]=='x') $csv.="\"Main Contact Title\",";
         if($field[$ix][mainphone]=='x') $csv.="\"Main Contact Phone\",";
         if($field[$ix][mainemail]=='x') $csv.="\"Main Contact Email\",";
         if($field[$ix][mainpasscode]=='x') $csv.="\"Main Contact Passcode\",";
      }
      $ix++;
   }
   $csv.="\r\n";
   //PREP SCREEN OUTPUT
   echo "<tr align=center><td><b>School Name, Address</b><br>(Click to See Full School Directory)</td><td><b>School Phone & Fax</b></td><td><b>Main Contact Info</b></td><td><b>Enrollment</b></td>";
   if(($schtype=="reg" && $regactivity!='') || ($schtype=="dec" && $decactivity!=''))
   {
        $table=GetSchoolsTable($curactivity);
        $manageteamsport=preg_replace("/school/","",$table);
	echo "<td><b>".GetActivityName($curactivity)." Team</b></td>";
   }
   else if($schtype=="dist" && $nsaadist!="SELECTDIST")
      echo "<td><b>District</b></td>";
   echo "</tr>";
   $sql="SELECT * FROM headers ORDER BY school";
   $result=mysql_query($sql);
   $warninghtml="";
   while($row=mysql_fetch_array($result))
   {
      $proceed=1;	//DECIDE IF WE SHOULD PROCEED WITH PUTTING THIS SCHOOL IN THE SEARCH RESULTS (DEFAULT = YES)

      //WHICH SCHOOLS?
      if($schtype=="reg" && $regactivity!='' && !IsRegistered2011($row[id],$regactivity,"",TRUE))	//NOT REGISTERED - DON'T PROCEED
         $proceed=0;	
      $curclass=GetClass(GetSID2(GetSchool2($row[id]),$regactivity),$regactivity,date("Y"),GetSchoolTable($regactivity));
      if($schtype=="reg" && $regactivity!='' && $regclass!='' && $regclass!=$curclass)
	 $proceed=0;
      if($schtype=="dec" && $decactivity!='' && !IsDeclared($row[school],$decactivity))	//NOT DECLARED
	 $proceed=0;
      $curclass=GetClass(GetSID2(GetSchool2($row[id]),$decactivity),$decactivity,date("Y"),GetSchoolTable($decactivity));
      if($schtype=="dec" && $decactivity!='' && $decclass!='' && $decclass!=$curclass)
	 $proceed=0;
      if($schtype=="dist" && $nsaadist!="SELECTDIST" && $row[nsaadist]!=$nsaadist)
	 $proceed=0;
      else if($lutype=="yes" && $month!='00' && $day && $year)	//CHECK IF SCHOOL MEETS LAST UPDATE CRITERIA
      { 
         $update=mktime(0,0,0,$month,$day,$year);
         if($beforeafter=="on or before" && $row[dirupdate]>$update) 
            $proceed=0;
         else if($beforeafter=="on or after" && $row[dirupdate]<$update)
	    $proceed=0;
      }
      else if($schtype=='spec' && $specschool>0 && $specschool!=$row[id])
	 $proceed=0;

      if($proceed)	//INCLUDE THIS SCHOOL
      {
	 //IF NO ENTRY IN SCHOOL TABLE, SHOW FLAG UNDER Classifications Export
         $table=GetSchoolsTable($curactivity);
	 $sql2="SELECT * FROM $table WHERE (mainsch='$row[id]' OR othersch1='$row[id]' OR othersch2='$row[id]' OR othersch3='$row[id]')";
	 $result2=mysql_query($sql2);
	 if(mysql_num_rows($result2)==0)
	 {
	    $warninghtml.=GetSchool2($row[id])."<br>";
	 }
	 //SCHOOL INFORMATION
         for($i=0;$i<count($schoolfields);$i++)
         {
            if($field[$i]=='x') 
            {
   	       if($schoolfields[$i]=='class')
	       {
	          if(($schtype=="reg" && $regactivity!='')  || ($schtype=="dec" && $decactivity!=''))
		  {
		     $sid=GetSID2(GetSchool2($row[id]),$manageteamsport);
		     $csv.="\"".GetClass($sid,$manageteamsport)."\",";
	    	  }
	          else $csv.="\"MUST SELECT AN ACTIVITY\",";
	       }
	       else
	          $csv.="\"".$row[$schoolfields[$i]]."\",";
	    }
         }
	 //ALSO SHOW MAIN SCHOOL INFORMATION ON THE SCREEN
	 echo "<tr align=left><td><a href=\"directory.php?school_ch=$row[school]&header=no&session=$session\" target=\"_blank\">$row[school]</a><br>$row[address1]<br>";
	 if($row[address2]!='') echo "$row[address2]<br>";
	 echo "$row[city_state] $row[zip]</td><td>Phone: $row[phone]<br>Fax: $row[fax]</td>";
         $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND maincontact='y'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 echo "<td>";
	 if($row2[level]==2) echo "<b>Athletic Director</b><br>";
	 else echo "<b>$row2[sport]</b><br>";
	 echo "$row2[name]<br>Phone: ".GetPhone($row2[id])."<br>";
	 echo "<a class=small href=\"mailto:$row2[email]\">$row2[email]</a></td>";
	 echo "<td align=center>$row[enrollment]</td>";
	 if(($regactivity!='' && $schtype=="reg") || ($decactivity!='' && $schtype=="dec"))
	 {
	    $sid=GetSID2(GetSchool2($row[id]),$manageteamsport);
	    echo "<td";
            if($schtype=="reg" && $regactivity!='' && !IsRegistered2011($row[id],$regactivity))  //HAS NOT PAID YET
	       echo " bgcolor='#ff0000'";
	    echo ">";
            echo GetSchoolName($sid,$manageteamsport)." (Class ".GetClass($sid,$manageteamsport).")<br>";
      	    $coopschs=GetCoopSchools($row[id],$manageteamsport);
            $coopsch=split(";",$coopschs); $coopheader=0;
            for($c=0;$c<count($coopsch);$c++)
            {
               if(GetSchool2($coopsch[$c])!="" && $coopsch[$c]>0 && $row[id]!=$coopsch[$c]) 
               {
                  if($coopheader==0)
                  {
                     echo "<b>Co-oping Schools:</b><br>"; $coopheader=1;
                  }
                  echo GetSchool2($coopsch[$c])."<br>";
	       }
	    }
	    echo "</td>";
         }
	 else if($schtype=="dist" && $nsaadist!="SELECTDIST")
	 {
	    echo "<td align=center>$row[nsaadist]</td>";
	 }
	 echo "</tr>";
         $ix=$i;
         for($i=0;$i<count($staff);$i++)
         {
	    if($field[$ix][name]=='x' || $field[$ix][phone]=='x' || $field[$ix][email]=='x' || $field[$ix][passcode]=='x')
	    {
	       $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND ";
	       if($staff[$i]=="Athletic Director") $sql2.="level=2";
	       else $sql2.="sport LIKE '$staff[$i]%'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
               if($field[$ix][name]=='x') $csv.="\"$row2[name]\",";
      	       if($field[$ix][phone]=='x') $csv.="\"".GetPhone($row2[id])."\",";
      	       if($field[$ix][email]=='x') $csv.="\"$row2[email]\",";
      	       if($field[$ix][passcode]=='x') $csv.="\"$row2[passcode]\",";
	    }
	    if($i==5)	//MAIN CONTACT
	    {
               $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND maincontact='y'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               if($field[$ix][mainname]=='x') $csv.="\"$row2[name]\",";
               if($field[$ix][maintitle]=='x' && $row2[level]==2) $csv.="\"Athletic Director\",";
	       else if($field[$ix][maintitle]=='x') $csv.="\"$row2[sport]\",";
               if($field[$ix][mainphone]=='x') $csv.="\"".GetPhone($row2[id])."\",";
               if($field[$ix][mainemail]=='x') $csv.="\"$row2[email]\",";
               if($field[$ix][mainpasscode]=='x') $csv.="\"$row2[passcode]\",";
	    }
      	    $ix++;
         }
         $csv.="\r\n";
      }	//END IF PROCEED
   }
   echo "</table>";
   //IF WARNING TO SHOW, SHOW IT
   if($warninghtml!='')
   {
      $warninghtml="The following schools are included in the search results below but will NOT be included in the Classifications Export,<br>since they do not yet have a record in the <a class='white' href='../calculate/wildcard/schools.php?session=$session&sport=$manageteamsport'>".strtoupper(GetActivityName($manageteamsport))." TABLE OF SCHOOLS</a>:<br><br>".$warninghtml;
?>
<script language="javascript">
document.getElementById('warning').innerHTML="<?php echo $warninghtml; ?>";
document.getElementById('warning').style.display='';
</script>
<?php
   }
   //WRITE EXPORT
      $open=fopen(citgf_fopen("../../reports/".$direxpfile),"w");
      fwrite($open,$csv);
      fclose($open); 
 citgf_makepublic("../../reports/".$direxpfile);

   echo $end_html;
   exit();
}

echo $init_html;
echo $header;

echo "<br><br>";
echo "<form method=post action=\"diradmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=5 class='nine'><caption><b>School Directory ADVANCED SEARCH & SPECIAL REPORTS:<br><a href=\"#specialreports\">Jump to Special Reports</a>&nbsp;|&nbsp;<a href=\"lookup.php?session=$session\">Quick Search/Passcode Lookup</a><br><br></b></caption>";
/* ADVANCED SEARCH CRITERIA */
	//WHICH SCHOOLS SHOULD BE INCLUDED IN THE SEARCH?
echo "<tr align=\"left\" bgcolor='#f0f0f0'><td><b>Which schools should be included in your search?</b><br><br>";
	//ALL
echo "<input type=radio id=\"schtypeall\" name=\"schtype\" value=\"all\"";
if(!$schtype || $schtype=="all") echo " checked";
echo "> ALL schools (provided they meet any additional criteria indicated below.)<br>";
	//SCHOOLS DECLARED IN A CERTAIN ACTIVITY
echo "<input type=radio id=\"schtypedec\" name=\"schtype\" value=\"dec\"";
if($schtype=="dec") echo " checked";
echO "> Schools DECLARED in: <select name=\"decactivity\" onChange=\"if(this.options.selectedIndex>0) { document.getElementById('schtypedec').checked=true; } submit();\"><option value=\"\">Select an Activity</option>";
//IsDeclared($school,$abbrev)
for($i=0;$i<count($decacts);$i++)
{
   echo "<option value=\"$decacts[$i]\"";
   if($decactivity==$decacts[$i]) echo " selected";
   echo ">".GetActivityName($decacts[$i])."</option>";
}
echo "</select><br>";
                //CLASS
   if($decactivity!='')
   {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<i>Optional - Select a Class:</i> <select name=\"decclass\"><option value=''>ANY Class</option>";
        $sql2="SELECT DISTINCT class FROM ".GetSchoolTable($decactivity)." ORDER BY class";
        $result2=mysql_query($sql2);
        while($row2=mysql_fetch_array($result2))
        {
           echo "<option value=\"$row2[class]\"";
           if($decclass==$row2['class']) echo " selected";
           echo ">Class $row2[class]</option>";
        }
        echo "</select><br>";
   }
	//THOSE REGISTERED FOR A CERTAIN ACTIVITY
echo "<input type=radio id=\"schtypereg\" name=\"schtype\" value=\"reg\"";
if($schtype=="reg") echo " checked";
echo "> Schools REGISTERED for: <select name=\"regactivity\" onChange=\"if(this.options.selectedIndex>0) { document.getElementById('schtypereg').checked=true; } submit();\"><option value=\"\">Select an Activity</option>";
for($i=0;$i<count($regacts);$i++)
{
   echo "<option value=\"$regacts[$i]\"";
   if($regactivity==$regacts[$i]) echo " selected";
   echo ">".GetActivityName($regacts[$i])."</option>";
}
echo "</select><br>";
		//CLASS
   if($regactivity!='')
   {
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<i>Optional - Select a Class:</i> <select name=\"regclass\"><option value=''>ANY Class</option>";
	$sql2="SELECT DISTINCT class FROM ".GetSchoolTable($regactivity)." ORDER BY class";
	$result2=mysql_query($sql2);
	while($row2=mysql_fetch_array($result2))
	{
	   echo "<option value=\"$row2[class]\"";
	   if($regclass==$row2['class']) echo " selected";
	   echo ">Class $row2[class]</option>";
	}
	echo "</select><br>";
   }
	//SCHOOLS IN A CERTAIN DISTRICT
echo "<input type=radio id=\"schtypedist\" name=\"schtype\" value=\"dist\"";
if($schtype=="dist") echo " checked";
echo "> Schools in NSAA DISTRICT: <select name=\"nsaadist\" onChange=\"if(this.options.selectedIndex>0) { document.getElementById('schtypedist').checked=true; }\"><option value=\"SELECTDIST\">Select a District</option>";
$sql="SELECT DISTINCT nsaadist FROM headers ORDER BY nsaadist";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[nsaadist]\"";
   if($nsaadist==$row[nsaadist]) echo " selected";
   if(trim($row[nsaadist])=="") $row[nsaadist]="[None]";
   echo ">$row[nsaadist]</option>";
}
echo "</select><br>";
	//A SPECIFIC SCHOOL
echo "<input type=radio id=\"schtypespec\"  name=\"schtype\" value=\"spec\"";
if($schtype=="spec") echo " checked";
echo ">A SPECIFIC school: <select name=\"specschool\" onChange=\"if(this.options.selectedIndex>0) { document.getElementById('schtypespec').checked=true; }\"><option value=\"\">Select a School</option>";
$sql="SELECT id,school FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($spechschool==$row[id]) echo " selected";
   echo ">$row[school]</option>";
}
echo "</select></td></tr>";
	//DOES THE LAST UPDATE MATTER?
echo "<tr align=left><td><b>Does it matter when the schools <u>last updated</u> their directory?</b><br>";
	//NO
echo "<input type=radio name=\"lutype\" id=\"lutypeno\" value=\"no\"";
if(!$lutype || $lutype=="no") echo " checked";
echo "> No<br>";
	//YES
echo "<input type=radio name=\"lutype\" id=\"lutypeyes\" value=\"yes\"";
if($lutype=="yes") echo " checked";
echo "> Only show schools who LAST UPDATED their directory <select name=\"beforeafter\"><option>on or before<option selected>on or after</select>";
echo "&nbsp;<select name=\"month\" onChange=\"if(this.options.selectedIndex>0 && document.getElementById('day').value!='' && document.getElementById('year').value!='') { document.getElementById('lutypeyes').checked=true; }\"><option value=\"00\">Month</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if($month==$m) echo " selected";
   echo ">".date("F",mktime(0,0,0,$m,1,date("Y")))."</option>";
}
echo "</select>&nbsp;<input type=text name='day' id='day' value='1' size=2>&nbsp;";
if(!$year) $year=date("Y");
echo "<input type=text name='year' id='year' size=4 value='$year'></td></tr>";
	//WHICH FIELDS SHOULD BE INCLUDED IN THE EXPORT
echo "<tr align=left bgcolor='#f0f0f0'><td><b>Which DIRECTORY FIELDS would you like included in the export of these search results?</b><br><br>";
	//SCHOOL INFO: 
echo "<b>SCHOOL INFORMATION:</b><br>";
echo "<table cellspacing=0 cellpadding=3>";
echo "<tr align=left valign=top><td>";
for($i=0;$i<count($schoolfields);$i++)
{
   echo "<input type=checkbox name=\"field[$i]\" value=\"x\"";
   if($schoolfields[$i]=="school")
      echo " checked";
   echo "> $schoolfields2[$i]";
   if($schoolfields[$i]=="class") echo "*";
   echo "<br>";
   if($i==3 || $i==7 || $i==9) echo "</td><td>";
}
$ix=$i;
echo "</td></tr></table>* If you want to export the CLASS, you must select an ACTIVITY above (either \"Declared in\" or \"Registered for\").<br>";
echo "<br><b>STAFF & COACHES INFORMATION:</b><br>";
for($i=0;$i<count($staff);$i++)
{
   if($i==0) 
      echo "<table width='100%' cellspacing=5><tr align=left><td colspan=6><b>Front Office:</b></td></tr><tr align=left valign=top>";
   else if($i==6) 
      echo "</tr></table><table width='100%' cellspacing=5><tr align=left><td colspan=7><b>Fall Sports:</b></td></tr><tr align=left valign=top>";
   else if($i==13) 
      echo "</tr></table><table width='100%' cellspacing=5><tr align=left><td colspan=5><b>Winter Sports:</b></td></tr><tr align=left valign=top>";
   else if($i==18) 
      echo "</tr></table><table width='100%' cellspacing=5><tr align=left><td colspan=7><b>Spring Sports:</b></td></tr><tr align=left valign=top>";
   else if($i==25)
      echo "</tr></table><table width='100%' cellspacing=5><tr align=left><td colspan=10><b>Activities:</b></td></tr><tr align=left valign=top>";
   else if($i==34)
      echo "</tr></table><table width='100%' cellspacing=5><tr align=left><td colspan=4><b>Other:</b></td></tr><tr align=left valign=top>";
   //NAME, PHONE, EMAIL, PASSCODE
   echo "<td>$staff[$i]<br>";
   echo "<input type=checkbox name=\"field[$ix][name]\" value=\"x\"> Name<br>";
   echo "<input type=checkbox name=\"field[$ix][phone]\" value=\"x\"> Phone<br>";
   echo "<input type=checkbox name=\"field[$ix][email]\" value=\"x\"> Email<br>";
   echo "<input type=checkbox name=\"field[$ix][passcode]\" value=\"x\"> Passcode</td>";
   if($i==5)	//ADD SPOT FOR MAIN CONTACT AT END OF FRONT OFFICE ROW
   {
      echo "<td>MAIN CONTACT<br>";
      echo "<input type=checkbox name=\"field[$ix][mainname]\" value=\"x\"> Name<br>";
      echo "<input type=checkbox name=\"field[$ix][maintitle]\" value=\"x\"> Title<br>";
      echo "<input type=checkbox name=\"field[$ix][mainphone]\" value=\"x\"> Phone<br>";
      echo "<input type=checkbox name=\"field[$ix][mainemail]\" value=\"x\"> Email<br>";
      echo "<input type=checkbox name=\"field[$ix][mainpasscode]\" value=\"x\"> Passcode</td>";
   }
   $ix++;
}
echo "</table>";
echo "</td></tr>";
echo "<tr align=center><td><input type=submit class='fancybutton' name=\"search\" value=\"Search\"></td></tr>";
echo "</table></form>";
/* END ADVANCED SEARCH */

/* SPECIAL REPORTS */
echo "<form method=post action=\"diradmin.php\" target=\"_blank\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<a name='specialreports'><br><br></a><table cellspacing=0 cellpadding=10 class='nine'><caption><b>SPECIAL REPORTS</b></caption>";
echo "<tr align=left><td><ul>";
echo "<li><a href=\"diradmin.php?diffads=x&session=$session\" target=\"_blank\">Schools that have <i>different AD's or Activities Director's</i> than they did last year</a> (This report will open in a new window.)</li><br>";
//RULES MEETINGS (COACH ATTENDANCE) - added 8/12/09
echo "<li>Schools with a Coach of the following activity (or an AD) who is <b>missing RULES MEETING ATTENDANCE</b>:<br><br>";
echo "<select name=\"rmsport\"><option value=''>Select Activity or AD</option>";
$sql="SHOW TABLES LIKE '%rulesmeetings'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $rmsp=preg_replace("/rulesmeetings/","",$row[0]);
   echo "<option value='$rmsp'>".GetActivityName($rmsp)."</option>";
}
echo "</select>&nbsp;";
echo "<input type=submit name=rmsearch value=\"Search\"></li></ul>";
echo "</form></center>";

echo $end_html;
?>
