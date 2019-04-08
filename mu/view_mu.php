<?php
//view_mu.php: Music Online Entry Form

require '../functions.php';
require '../../calculate/functions.php';
require 'mufunctions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
$sql="SELECT id FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[0];
$year1=GetFallYear('mu');
$year2=$year1+1;
$duedate=$year2."-03-20";    //March 20 of this year

if($resetdist==1)
{
   $sql="UPDATE muschools SET distid='0' WHERE school='$school2'";
   $return=mysql_query($sql);
}

if($distid)
{
   $sql="SELECT * FROM muschools WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO muschools (school,distid) VALUES ('$school2','$distid')";
   } 
   else
   {
      $sql2="UPDATE muschools SET distid='$distid' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
}
if($submit=="Submit Final Entry" && $send=='x')
{
   //ERROR-CHECKERS:
   //1) Have they entered the number of students they've entered?
      $error=0;
   $sql="SELECT studcount,id FROM muschools WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(!ereg("[0-9]",$row[0]) || $row[0]=='0')
   {   $studerror=1; $error=1;  }
   else
      $studerror=0;
   //2) Check for overage on strings, piano solos, or regular entries:
   if(TooManyEntries($schid))
   {   $entryerror=1; $error=1;  }
   else $entryerror=0;
   if(TooManyStringEntries($schid))
   {   $stringerror=1; $error=1;  }
   else $stringerror=0;
   if(CountPianoSolos($schid)>2)
   {   $pianoerror=1; $error=1;  } 
   else $pianoerror=0;
   //3) Have they checked more than 24 students for any small ensemble?
   $sql="SELECT t1.id,t2.category FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Small' ORDER BY t2.id";
   $result=mysql_query($sql);
   $sminserror=0; $smvocerror=0; $smmiscerror=0;
   $sminserror2=0; $smvocerror2=0; $smmiscerror2=0;
   while($row=mysql_fetch_array($result))
   {
      if(ereg("Vocal",$row[1])) { $curerror="smvocerror"; $curerror1="smvocerror2"; }
      else if(ereg("Instrumental",$row[1])) { $curerror="sminserror"; $curerror2="sminserror2"; }
      else { $curerror="smmiscerror"; $curerror2="smmiscerror2"; }
      $sql2="SELECT id FROM muentries WHERE schoolid='$schid' AND ensembleid='$row[id]'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 if(CountStudentsInEntry($row2[id])>24)
	 {
	    $error=1; $$curerror=1;
         }
	 else if(CountStudentsInEntry($row2[id])==0)
	 {
	    $error=1; $$curerror2=1;
	 }
      } 
   }
   //Have they forgotten to include any accompanists?
   $sql="SELECT t1.id,t2.category,t2.id AS categid FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND ((t2.smlg='Large' AND t2.vocinst='Vocal') OR t2.smlg='Small' OR t2.smlg IS NULL) ORDER BY t2.id";
   $result=mysql_query($sql);
   $accerror1=0; $accerror2=0; $accerror3=0; $accerror5=0; $accerror7=0;
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT id FROM muentries WHERE schoolid='$schid' AND accompanist='' AND ensembleid='$row[id]'";
      $result2=mysql_query($sql2);
      if($row2=mysql_fetch_array($result2)) 
      {
         $curerror="accerror".$row[categid]; $$curerror=1; $error=1;
      }
   }
   //4) Have the checked students for all small ens as well as Jazz Bands, Madrigals, and Show Choirs?
   //	(NOTE: small ense taken care of above along with (3))
   $jbanderror=0; $maderror=0; $schoirerror=0;
   $sql="SELECT t1.id,t2.ensemble FROM muentries AS t1, muensembles AS t2 WHERE t1.ensembleid=t2.id AND (t2.ensemble='Jazz Band' OR t2.ensemble='Madrigal' OR t2.ensemble='Show Choir') AND t1.schoolid='$schid' ORDER BY t2.ensemble";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if($row[1]=="Jazz Band") $curerror="jbanderror";
      else if($row[1]=="Madrigal") $curerror="maderror";
      else $curerror="schoirerror";
      if(CountStudentsInEntry($row[id])==0)
      {
	 $error=1; $$curerror=1;
      }
   }
   //5) Was a specific instrument chosen for all Instrumental Solos?
   $sql="SELECT t1.* FROM muentries AS t1, muensembles AS t2, mucategories AS t3 WHERE t1.ensembleid=t2.id AND t2.categid=t3.id AND t3.category='Instrumental Solo' AND t1.schoolid='$schid' AND t1.event=''";
   $result=mysql_query($sql);
   $soloerror=0;
   if(mysql_num_rows($result)>0)
   {
      $soloerror=1; $error=1;
   }
   //6) For District II, were there more than 1 entry for any large ensemble?
   if(DistIILargeError($schid))
   {
      $distIIerror=1; $error=1;
   }
   else $distIIerror=0;
   //6) Have they entered their main contact information (Instrumental and Vocal)? AND the class?
   $sql="SELECT * FROM muschools WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $imain=$row[imain]; $imainschool=$row[imainschool]; $imainemail=$row[imainemail];
   $imainhome=$row[imainhome]; $imainschoolf=$row[imainschoolf];
   $vmain=$row[vmain]; $vmainschool=$row[vmainschool]; $vmainemail=$row[vmainemail];
   $vmainhome=$row[vmainhome]; $vmainschoolf=$row[vmainschoolf];
   if($imain!='' && $imain==$vmain)	//same person, only check for one set of fields filled out
   {
      if(!(($imainschool!='' && $imainemail!='' && $imainhome!='' && $imainschoolf!='') || ($vmainschool!='' && $vmainemail!='' && $vmainhome!='' && $vmainschoolf!='')))
      {
	 $error=1; $contacterror=1;
      }
   }
   else //different people, need all info filled out for both sets of fields
   {
      if($imain=='' || $imainschool=='' || $imainemail=='' || $imainhome=='' || $imainschoolf=='' || $vmain=='' || $vmainschool=='' || $vmainemail=='' || $vmainhome=='' || $vmainschoolf=='')
      {
    	 $error=1; $contacterror=1;
      }
   }
   if($row[classch]=='')
   {
      $error=1; $classerror=1;
   }
   //END ERROR-CHECKERS
   if($error==0)
   { 
      //e-mail form to nsaamusic@nsaahome.org, dist director & coordinator
      $To1="nsaamusic@nsaahome.org"; $ToName1="Bud Dahlstrom";
      $sql="SELECT t1.* FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t2.school='$school2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $distnum=$row[distnum];
      $classes=$row[classes];
      $distid=$row[id];
      $To2=$row[email]; $ToName2=$row[director];
      if($row[multiplesite]=='x') 	//sending to 2 sites
      {
	 $distid1=$row[distid1]; $distid2=$row[distid2];
         $sql="SELECT * FROM mudistricts WHERE id='$distid1'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $distnum1=$row[distnum];
	 $classes1=$row[classes];
	 $To2=$row[email]."/"; $ToName2=$row[director]."/";
         $sql="SELECT * FROM mudistricts WHERE id='$distid2'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $distnum2=$row[distnum];
         $classes2=$row[classes];
         $To2.=$row[email]; $ToName2.=$row[director];
      }
      $sql="SELECT * FROM mubigdistricts WHERE distnum='$distnum'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $To3=$row[email]; $ToName3=$row[coordinator];

      $From="nsaamusic@nsaahome.org"; $FromName="NSAA";

      $Subject="$school District $distnum -- $classes Music Entry Form";

      //Create Attachments:
      $summary=strtolower($school);
      $summary=ereg_replace(" ","",$summary);
      $summary=ereg_replace("[.]","",$summary);
      $summary=ereg_replace("\'","",$summary);
      $summary=ereg_replace("-","",$summary);
      $summary.="summary";
      $schoolch=ereg_replace("\'","`",$school_ch);
      citgf_exec("/usr/local/bin/php summary.php $session '$schoolch' > /home/nsaahome/attachments/$summary.html");

      $full=ereg_replace("summary","full",$summary);
      citgf_exec("/usr/local/bin/php viewfull.php $session '$schoolch' > /home/nsaahome/attachments/$full.html");

      $eliglist=ereg_replace("summary","eliglist",$summary);
      citgf_exec("/usr/local/bin/php eliglist.php $session '$schoolch' > /home/nsaahome/attachments/$eliglist.html");

      $payment=ereg_replace("summary","payment",$summary);
      citgf_exec("/usr/local/bin/php payment.php $session '$schoolch' > /home/nsaahome/attachments/$payment.html");

      $Text="$school has submitted their District $distnum -- $classes Music Entry Form.\r\n\r\nAttached are their:\r\n\r\n1.) Summary of entries ($summary.html, $summary.csv)\r\n2.) Full version of the entry form ($full.html, $full.csv)\r\n3.) List of eligible music students ($eliglist.html, $eliglist.csv";
      if(IsCooping($school,"Vocal") && IsCooping($school,"Instrumental") && IsHeadCoopSchool($school,"Vocal"))
      {
         $othersch=GetOtherCoopSchool($school,"Vocal");
         $eliglist2=strtolower($othersch);
         $eliglist2=ereg_replace(" ","",$eliglist2);
         $eliglist2=ereg_replace("[.]","",$eliglist2);
         $eliglist2=ereg_replace("\'","",$eliglist2);
         $eliglist2=ereg_replace("-","",$eliglist2);
         $eliglist2.="eliglist";
         citgf_exec("/usr/local/bin/php eliglist.php $session \"$othersch\" > /home/nsaahome/attachments/$eliglist2.html");
         $Text.=", $eliglist2.html, $eliglist2.csv";
      }
      $Text.=")\r\n4.) and Payment Summary ($payment.html, $payment.csv)\r\n(NOTE: If your district fees are computed after the contest or using a formula which cannot be completed prior to the contest, this feature will not show entry fees information.)\r\n\r\nPLEASE NOTE: You will notice that each file is included as both an HTML (.html) and a CSV (.csv) attachment.  The attachments in HTML (.html) format will be the easiest formats to use to gather your district entry information.  IF, however,  you need to edit your entries or manipulate the data for scheduling purposes, you may open the CSV (.csv) attachments with Microsoft Excel.  These .csv attachments will open in spreadsheet format in Excel.  If you do not have Excel, these files can be opened with a standard text editor but will be harder to read.\r\n\r\nPlease keep in mind that changes made to one attachment will NOT be reflected in other attachments containing the same information.\r\n\r\nThank You!";
      $Html=ereg_replace("\r\n","<br>",$Text);

      if(IsCooping($school,"Vocal") && IsCooping($school,"Instrumental") && IsHeadCoopSchool($school,"Vocal"))
      {
         $Attm=array("/home/nsaahome/attachments/$summary.html","/home/nsaahome/attachments/$summary.csv","/home/nsaahome/attachments/$full.html","/home/nsaahome/attachments/$full.csv","/home/nsaahome/attachments/$eliglist.html","/home/nsaahome/attachments/$eliglist.csv","/home/nsaahome/attachments/$eliglist2.html","/home/nsaahome/attachments/$eliglist2.csv","/home/nsaahome/attachments/$payment.html","/home/nsaahome/attachments/$payment.csv");
      }
      else
      {
         $Attm=array("/home/nsaahome/attachments/$summary.html","/home/nsaahome/attachments/$summary.csv","/home/nsaahome/attachments/$full.html","/home/nsaahome/attachments/$full.csv","/home/nsaahome/attachments/$eliglist.html","/home/nsaahome/attachments/$eliglist.csv","/home/nsaahome/attachments/$payment.html","/home/nsaahome/attachments/$payment.csv");
      }

      //SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$Attm);
      SendMail($From,$FromName,"bdahlstrom@nsaahome.org","Bud Dahlstrom",$Subject,$Text,$Html,$Attm);
      SendMail($From,$FromName,$To1,$ToName1,$Subject,$Text,$Html,$Attm);	//nsaamusic@nsaahome.org
      if(trim($To2)!='')
      { 
         if(ereg("/",$To2) || ereg(",",$To2))
         {
            $To2s=split("[/,]",$To2);
	    for($i=0;$i<count($To2s);$i++)
	    {  
	       $To2s[$i]=trim($To2s[$i]);
	       if($To2s[$i]!='')
	       {
	          //if($school=="Test's School")
	             //SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html."<br><br>(This message would have been sent to $To2s[$i] if it was not a test)",$Attm);
	          //else
	             SendMail($From,$FromName,$To2s[$i],$To2s[$i],$Subject,$Text,$Html,$Attm);
	       }
	    }
         }
         else
         {
            //if($school=="Test's School")
               //SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html."<br><br>(This message would have been sent to $To2 if it was not a test)",$Attm);
	    //else
               SendMail($From,$FromName,$To2,$ToName2,$Subject,$Text,$Html,$Attm);
         }
      }
      //if($school=="Test's School")
         //SendMail($From,$FromName,"bdahlstrom@nsaahome.org","Bud Dahlstrom",$Subject,$Text,$Html."<br><br>(This message would have been sent to $To3 if it was not a test)",$Attm);
      //else
         SendMail($From,$FromName,$To3,$ToName3,$Subject,$Text,$Html,$Attm);
   
      $now=time();
      $sql="UPDATE muschools SET submitted='$now' WHERE school='$school2'";
      $result=mysql_query($sql);

      header("Location:view_mu.php?session=$session&school_ch=$school_ch");
      exit();
   }//end if no errors
   else	//errors
   {
?>
<script language="javascript">
alert("You have some errors in your entry form.  Please review the errors (in red) on the screen, fix them, and submit your form again.\r\n\r\nThank you!");
</script>
<?php
      $errormsg="<font style=\"color:red;font-size:8pt\">";
      if($studerror==1) 
   	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You must enter the number of students you're entering in the District Music Contest.  Please enter this number <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&studerror=1\">Here</a> (remember to click \"Save\"!)  and then submit your form again.<br><br>";
      if($entryerror==1)
         $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have entered <b><u>".CountEntries($schid)." Entries</u></b>.  You may only enter a maximum of <b><u>".GetMaxEntries($schid)." Entries</b></u>.  Please fix this error and submit your form again.<br><br>";
      if($stringerror==1)
         $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have entered <b><u>".CountStringEntries($schid)." String Entries</u></b>.  You may only enter a maximum of <b><u>".GetMaxStringEntries($schid)." String Entries</b></u>.  Please fix this error and submit your form again.<br><br>";
      if($pianoerror==1)
   	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have entered <b><u>".CountPianoSolos($schid)." Piano Solos</u></b>.  You may only enter a maximum of <b><u>2 Piano Solos</u></b>.  Please <a class=small href=\"edit_mu.phpschool_ch=$school_ch&?session=$session&categ=7\">Go to the Instrumental Solos section</a> and fix this error.  Then submit your form again.<br><br>";
      if($smvocerror==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have at least 1 Small Vocal Ensemble that has more than 24 students listed.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=1\">Go to the Small Vocal Ensembles section</a> and fix this error.  Then submit your form again.<br><br>";
      if($sminserror==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have at least 1 Small Instrumental Ensemble that has more than 24 students listed.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=2\">Go to the Small Instrumental Ensembles section</a> and fix this error.  Then submit your form again.<br><br>";
      if($smvocerror2==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have not listed any students in at least 1 Small Vocal Ensemble.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=1\">Go to the Small Vocal Ensembles section</a> and fix this error.  Then submit your form again.<br><br>";
      if($sminserror2==1)
  	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have not listed any students in at least 1 Small Instrumental Ensemble.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=2\">Go to the Small Instrumental Ensembles section</a> and fix this error.  Then submit your form again.<br><br>";
      if($jbanderror==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have not listed any students in at least 1 Jazz Band entry.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=4\">Go to the Large Instrumental Ensembles section</a> and fix this error.  Then submit your form again.<br>";
      if($maderror==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have not listed any students in at least 1 Madrigal entry.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=3\">Go to the Large Vocal Ensembles section</a> and fix this error.  Then submit your form again.<br>";
      if($schoirerror==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have not listed any students in at least 1 Show Choir entry.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=3\">Go to the Large Vocal Ensembles section</a> and fix this error.  Then submit your form again.<br>";
      if($soloerror==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You did not choose a specific instrument for at least 1 Instrumental Solo.  Please <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=7\">Go to the Instrumental Solos section</a> and fix this error.  Then submit your form again.<br>";
      if($accerror1==1 || $accerror2==1 || $accerror3==1 || $accerror5==1 || $accerror7==1)
      {
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You have not entered an accompanist (or have not checked \"None\") for at least 1 Solo.  Please go to the following section(s) and fix this error.  Then submit your form again:<br>";
	 if($accerror1==1) $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=1\">Small Vocal Ensembles section</a><br>";
         if($accerror2==1) $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=2\">Small Instrumental Ensembles section</a><br>"; 
         if($accerror3==1) $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=3\">Large Vocal Ensembles section</a><br>"; 
         if($accerror5==1) $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=5\">Vocal Solos section</a><br>"; 
         if($accerror7==1) $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=7\">Instrumental Solos section</a><br>"; 
      }
      if($distIIerror==1)
	 $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;In District II, you may only enter 1 entry for each Large Ensemble. You have entered more than 1 entry for at least 1 Large Ensemble.  Please check the <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=3\">Large Vocal Ensemble section</a> and the <a class=small href=\"edit_mu.php?school_ch=$school_ch&session=$session&categ=4\">Large Instrumental Ensemble section</a> and delete the extra entry or entries.  Then submit this form again.<br>";
      if($contacterror==1)
         $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You must enter ALL of the contact information for your Main Instrumental Contact AND your Main Vocal Contact.  Please <a class=small href=\"edit_mu.php?contacterror=1&school_ch=$school_ch&session=$session\">complete this information</a> and submit this form again.<br>";
      if($classerror==1)
         $errormsg.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(!)</b>&nbsp;&nbsp;You must select your school's CLASS.  Please <a class=small href=\"edit_mu.php?classerror=1&school_ch=$school_ch&session=$session\">select your school's class</a> and submit this form again.<br>";
      $errormsg.="</font><br>";
   }
}
echo $init_html;
echo GetHeader($session);

//Check if school is in muschools table yet:
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$unlocked=$row[unlocked];
if((mysql_num_rows($result)==0 || $row[distid]=='0') && ($unlocked=='x' || !PastDue($duedate,0)))
{
   echo "<br><a class=small href=\"muhome.php?school_ch=$school_ch&session=$session\">Music Contest Entry Form Main Menu</a><br><br>";
   if($level==1)
      echo "<a class=small href=\"muadmin.php?school_ch=$school_ch&session=$session\">Return to Music Entry Forms Admin</a><br><br>";
   echo "<table cellspacing='0' cellpadding='5' frame='all' rules='all' style='border:#d0d0d0 1px solid;'>";
   echo "<caption><b>$year1-$year2 NSAA District Music Contests</b><br>";
   echo "<font style=\"font-size:9pt;\"><font style=\"color:blue\"><b>Please click on the district</b></font> in which your school will be participating this year.<br>(You will not have to select your district again once you have selected it the first time.)</font></caption>";
   echo "<tr align=center><th>District # -- Class</th>";
   echo "<th>Date(s)</th><th>Site</th><th>Director(s)</th></tr>";
   $sql="SELECT * FROM mudistricts ORDER BY distnum,classlist,multiplesite DESC";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left>";
      echo "<td><a class=small onclick=\"return confirm('Are you sure you want to select District $row[distnum] -- $row[classes]?  You will not be able to change your selection later.');\" href=\"view_mu.php?school_ch=$school_ch&session=$session&school_ch=$school_ch&distid=$row[id]\">$row[distnum] -- $row[classes]</a>";
      if($row[notes]!='') echo "<br>$row[notes]";
      echo "</td>";
      if($row[multiplesite]=='x' && $row[distid1]>0 && $row[distid2]>0)
      {
   	 $sql2="SELECT * FROM mudistricts WHERE id='$row[distid1]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
         $date=split("/",$row2[dates]);
         $dates1="";
         for($i=0;$i<count($date);$i++)
         {
            $cur=split("-",$date[$i]);
            $dates1.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
         }
         $dates1.=$cur[0];
	 $site1=$row2[site]; $director1=$row2[director];
         $sql2="SELECT * FROM mudistricts WHERE id='$row[distid2]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $date=split("/",$row2[dates]);
         $dates2="";
         for($i=0;$i<count($date);$i++)
         {
            $cur=split("-",$date[$i]);
            $dates2.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
         }
         $dates2.=$cur[0];
         $site2=$row2[site]; $director2=$row2[director];
         echo "<td>$dates1<hr>$dates2</td>";
         echo "<td>$site1<hr>$site2</td><td>$director1<hr>$director2</td></tr>";
      }
      else
      {
      $date=split("/",$row[dates]);
      $dates="";
      for($i=0;$i<count($date);$i++)
      {
         $cur=split("-",$date[$i]);
	 $dates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
      }
      $dates.=$cur[0];
      echo "<td>$dates</td>";
      echo "<td>$row[site]</td><td>$row[director]</td></tr>";
      }
   }
   echo "</table>";
   echo "</form>";
}//end if no entry in muschools table
else	//show summary of school's current MU entry:
{
   $schid=$row[id];
   $distid=$row[distid];
   $submitted=$row[submitted];
   $supervisor=$row[supervisor];
   $phone=$row[phone]; $email=$row[email];

   $sql2="SELECT t1.director,t1.distnum,t1.classes,t2.coordinator,t1.multiplesite,t1.distid1,t1.distid2 FROM mudistricts AS t1, mubigdistricts AS t2 WHERE t1.distnum=t2.distnum AND t1.id='$distid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $director=$row2[director];
   $coordinator=$row2[coordinator];
   $district="$row2[distnum]--$row2[classes]";
   $bigdistrict=$row2[distnum];
   $multiplesite=$row2[multiplesite]; $distid1=$row2[distid1]; $distid2=$row2[distid2];

   //Show summary of school's entry that looks like old printout version of music form (pdf).
   //NOT editable (show links to Edit, View Full, Eligibility list for MU, Payment Summary, E-mail/Print
   if($school=="Test's School" || ereg("Public Schools",$school) || ereg("College",$school))
   {
      echo "<a class=small href=\"muhome.php?school_ch=$school_ch&session=$session&reset=y\"><font style=\"color:red\">Click Here to Reset this Music Form</a><br>";
   }
   echo "<br><a class=small href=\"muhome.php?school_ch=$school_ch&session=$session&school_ch=$school_ch\">NSAA District Music Contest Entry Form MAIN MENU</a><br>";
   if($level==1)
      echo "<br><a class=small href=\"muadmin.php?school_ch=$school_ch&session=$session\">Return to Music Entry Forms Admin</a><br>";
   echo "<br><table><caption><b><u>$year1-$year2 NSAA DISTRICT MUSIC CONTEST ONLINE ENTRY HOME PAGE";
   if($level==1) echo "&nbsp;<font style=\"color:#8B0000\">for $school_ch</font>";
   echo ":</u></b><br><br>";
   //Instructions:
   if(($level!=1 && $unlocked!='x' && PastDue($duedate,0)) || $submitted!='') //school has submitted this form or it's past due
   //if(($level!=1 && $unlocked!='x' && PastDue($duedate,0)) ) //school has submitted this form or it's past due
   {
      echo "<table width=700><tr align=left><td>";
      if(PastDue($duedate,0))
         echo "<font style=\"color:red;font-size:9pt;\">This form was due on <b>March 20, $year2.</b></font><br>";
      if($submitted!='')
      {
         echo "<table border=1 bordercolor=\"blue\" cellspacing=0 cellpadding=4><tr align=left><td>";
         echo "<font style=\"font-size:10pt;color:blue\"><b>You submitted this form to the NSAA on ".date("m/d/Y",$submitted)." at ".date("h:i a T",$submitted).".</font></b>";
         echo "<br><br><font style=\"font-size:10pt;\"><b>To COMPLETE your District Music Contest Registration</b></font><font style=\"font-size:9pt\">, please submit your school's <b><u>CONTEST ENTRY FEES</b></u> to your Host Contest Site (not the NSAA) immediately, if required by your District.  Send your fees to the address below.<br><br>If available for your contest site, the total amount due will be shown on your contest form <a target=\"_blank\" href=\"payment.php?school_ch=$school_ch&session=$session\">Payment Summary</a>.<br><br>";
	 if($multiplesite!='x')
	 {
         $sql3="SELECT * FROM mudistricts WHERE id='$distid'";
         $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
         if($row3[feeaddress1]=='')
         {
	    $feename=$row3[director];
	    $feeaddress1=$row3[address1]; $feeaddress2=$row3[address2];
	    $feecity=$row3[city]; $feestate=$row3[state]; $feezip=$row3[zip];
	 }
	 else
	 {
	    $feename=$row3[feename];
	    $feeaddress1=$row3[feeaddress1]; $feeaddress2=$row3[feeaddress2];
	    $feecity=$row3[feecity]; $feestate=$row3[feestate]; $feezip=$row3[feezip];
         }
         echo "<b>Please send your fees to:</b><br>";
         echo "$feename<br>$feeaddress1<br>";
         if($feeaddress2!='') echo "$feeaddress2<br>";
	 echo "$feecity, $feestate $feezip<br>";
	 echo "<b>Make checks payable to:</b> $row3[checks]<br><br>";
	 }
	 else
	 {
	    $sql3="SELECT * FROM mudistricts WHERE (id='$distid1' OR id='$distid2')";
	    $result3=mysql_query($sql3);
	    $index=0;
	    while($row3=mysql_fetch_array($result3))
	    {
               if($row3[feeaddress1]=='')
               {
                  $feename=$row3[director];
                  $feeaddress1=$row3[address1]; $feeaddress2=$row3[address2];
                  $feecity=$row3[city]; $feestate=$row3[state]; $feezip=$row3[zip];
               }
               else
               {
                  $feename=$row3[feename];
                  $feeaddress1=$row3[feeaddress1]; $feeaddress2=$row3[feeaddress2];
                  $feecity=$row3[feecity]; $feestate=$row3[feestate]; $feezip=$row3[feezip];
               }
	       if($row3[id]==$distid1) $director1=$row3[director];
	       else $director2=$row3[director];
               echo "<b>Please send fees for the contest at $row3[site] to:</b><br>";
               echo "$feename<br>$feeaddress1<br>";
               if($feeaddress2!='') echo "$feeaddress2<br>";
               echo "$feecity, $feestate $feezip<br>";
               echo "<b>Make checks payable to:</b> $row3[checks]<br><br>";
	       $index++;
	    }
	 }
         echo "</td></tr></table><br>";
         echo "A copy of this form, including:";
         echo "<ol><li>A <a class=small target=\"_blank\" href=\"summary.php?school_ch=$school_ch&session=$session&school_ch=$school_ch\">Summary</a> of your entries</li>";
         echo "<li>The <a target=\"_blank\" class=small href=\"viewfull.php?school_ch=$school_ch&session=$session&school_ch=$school_ch\">Full Version</a> of your entry form</li>"; 
	 echo "<li>Your school's list of <a target=\"_blank\" class=small href=\"eliglist.php?school_ch=$school_ch&session=$session&school_ch=$school_ch\">Eligible Music Students</a></li>";
	 echo "<li>and Your <a target=\"_blank\" class=small href=\"payment.php?school_ch=$school_ch&session=$session&school_ch=$school_ch\">Payment Summary</a> (NOTE: If your district fees are computed after the contest or using a formula which cannot be completed prior to the contest, this feature will not show entry fees information.)</li>";
	 if($multiplesite!='x')
            echo "</ol>... was sent to the NSAA as well as the District $district Director ($director) and District $bigdistrict Coordinator ($coordinator).";
	 else
	    echo "</ol>... was sent to the NSAA as well as the District Site Directors ($director1 and $director2) and District $bigdistrict Coordinator ($coordinator).";
      }
      else	//Past due date AND no entry submitted (nothing to show them)
      {
         echo "<font style=\"font-size:9pt\">You did NOT submit a $year1-$year2 NSAA District Music Contest Entry.</font>";
	 echo "</td></tr></table></caption></table>";
	 echo $end_html;
	 exit();
      }
      echo "</td></tr></table>";
   }
   else	//have NOT submitted and form is NOT past due
   {
      echo "<table width=800><tr align=center><td>";
      echo "<table width=600 border=1 bordercolor=\"blue\" cellspacing=0 cellpadding=4><tr align=left><td>";
      echo "<font style=\"color:blue;font-size:10pt\"><b>";
      if(!(IsCooping($school,"Vocal") && IsCooping($school,"Instrumental") && !IsHeadCoopSchool($school,"Vocal")))
      {
         //NOT: co-oping in V, co-oping in I, and NOT head V school: 
	 echo "You have"; $youhave="You have";
      }
      else
      {
         echo GetHeadCoopSchool($school,"Vocal")." has"; $youhave=GetHeadCoopSchool($school,"Vocal")." has";
      }
      echo " NOT officially submitted this form yet.</font></b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".ereg_replace("You have",$youhave,GetEntryStatus($schid));
      echo "<br>".GetCoopStatus($school);
      //IF school is co-oping in V & I and is not the head school, they don't get to edit form; else they can edit (editpower)
      if(IsCooping($school,"Vocal") && IsCooping($school,"Instrumental") && !IsHeadCoopSchool($school,"Vocal"))
         $editpower=0;
      else
         $editpower=1;
      if($editpower==1)
      {
         echo "<br><font style=\"font-size:9pt;\"><font style=\"color:blue\">You must complete and submit this form to the NSAA by Midnight Central Time on <b>March 20, $year2.</font>";
         echo "<br><br><u>TO SUBMIT THIS FORM</u>, please follow the detailed instructions below.</font>";
      }
      echo "</td></tr></table><br>";
      //ENTRY COUNT:
      if($editpower==1)
      {
         echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=4><tr align=left bgcolor=#F0F0F0><td><font style=\"font-size:9pt\"><b><u>TO INPUT/EDIT ENTRIES TO YOUR NSAA DMC ENTRY FORM</u>,  </font></b><a href=\"edit_mu.php?school_ch=$school_ch&session=$session&school_ch=$school_ch\"><font style=\"color:red\">Click Here</font></a><br></td></tr></table></td></tr>";
      
         echo "<tr align=left><td><font style=\"font-size:9pt;\"><b>INSTRUCTIONS:</b> (Please read carefully.)";
         echo "<br><ol><li class=nine> Complete your NSAA Music District Contest Entry Form by clicking the \"Click Here\" link in the box above and following the on-screen instructions.</li>";
         echo "<br><li class=nine>Make sure ALL information is complete and accurate on your form.  You can preview your form by clicking on the links below:<br>";
         if(IsCooping($school,"Vocal") && !IsCooping($school,"Instrumental") && !IsHeadCoopSchool($school,"Vocal"))
         {
	    //Co-oping V, NOT co-oping I, NOT head V school:
            if(SubmittedForm($school)) $be="were";
            else $be="will be";
	    //show links to preview head school's Vocal entries & links to preview own Instrumental entries:
 	    echo "<table width=100%><tr valign=top align=left><td width=50%>";
            echo "<ul><font style=\"font-size:8pt;\"><b>The following Instrumental Music Entry items $be sent to your contest site director(s), your district music coordinator, and the NSAA Music Director upon final submission of <u>your form</u>:<br><br></b></font>";
 	    echo "<li>A copy of the <a target=\"_blank\" class=small href=\"summary.php?session=$session&school_ch=$school_ch\">Summary</a> of your Instrumental Music Entry</li>";
	    echo "<li>A copy of the <a target=\"_blank\" class=small href=\"viewfull.php?session=$session&school_ch=$school_ch\">Full Version</a> of your Instrumental Music Entry</li>";
	    echo "<li>Your school's list of <a target=\"_blank\" class=small href=\"eliglist.php?session=$session&school=$school_ch\">Eligible Music Students</a></li>";
            echo "<li>Your <a target=\"_blank\" class=small href=\"payment.php?session=$session&school_ch=$school_ch\">Payment Summary</a> for your Instrumental Music Entry fees<br>(NOTE: If your district fees are computed after the contest or using a formula which cannot be completed prior to the contest, this feature will not show entry fees information.)</li>";
            echo "</ul></td><td width=50%>";
	    $headsch=GetHeadCoopSchool($school,"Vocal");
            echo "<ul><font style=\"font-size:8pt;\"><b>The following items include the Vocal Music Entries currently entered <u>by $headsch for your co-op</u>*:<br><br></b></font>";
	    echo "<li><a target=\"_blank\" class=small href=\"summary.php?session=$session&school_ch=$headsch\">Summary</a> of $headsch's Music Entry Form</li>";
	    echo "<li><a target=\"_blank\" class=small href=\"viewfull.php?session=$session&school_ch=$headsch\">Full Version</a> of $headsch's Music Entry Form</li>";
	    echo "<li><a target=\"_blank\" class=small href=\"payment.php?session=$session&school_ch=$headsch\">Payment</a> of $headsch's Music Entry Form</li>";
            echo "<br><font style=\"font-size:8pt;\"><b>* $headsch ";
            if(SubmittedForm($headsch)) echo "submitted this form on ".date("m/d/y",SubmittedForm($headsch)).".";
            else echo "has NOT submitted this form yet.";
            echo "</ul></td></tr></table>";
         }
         else if(IsCooping($school,"Instrumental") && !IsCooping($school,"Vocal") && !IsHeadCoopSchool($school,"Instrumental"))
         {
            if(SubmittedForm($school)) $be="were";
            else $be="will be";
            //show links to preview head school's Instrumental  entries & links to preview own Vocal entries:
            echo "<table width=100%><tr valign=top align=left><td width=50%>";
            echo "<ul><font style=\"font-size:8pt;\"><b>The following Vocal Music Entry items $be sent to your contest site director(s), your district music coordinator, and the NSAA Music Director upon final submission of <u>your form</u>:<br><br></b></font>";
            echo "<li>A copy of the <a target=\"_blank\" class=small href=\"summary.php?session=$session&school_ch=$schoool_ch\">Summary</a> of your Vocal Music Entry</li>";
            echo "<li>A copy of the <a target=\"_blank\" class=small href=\"viewfull.php?session=$session&school_ch=$school_ch\">Full Version</a> of your Vocal Music Entry</li>";
            echo "<li>Your school's list of <a target=\"_blank\" class=small href=\"eliglist.php?session=$session&school=$school_ch\">Eligible Music Students</a></li>";
            echo "<li>Your <a target=\"_blank\" class=small href=\"payment.php?session=$session&school_ch=$school_ch\">Payment Summary</a> for your Vocal Music Entry fees<br>(NOTE: If your district fees are computed after the contest or using a formula which cannot be completed prior to the contest, this feature will not show entry fees information.)</li>";
            echo "</ul></td><td width=50%>";
            $headsch=GetHeadCoopSchool($school,"Instrumental");
            echo "<ul><font style=\"font-size:8pt;\"><b>The following items include the Instrumental Music Entries currently entered <u>by $headsch for your co-op</u>*:<br><br></b></font>";
            echo "<li><a target=\"_blank\" class=small href=\"summary.php?session=$session&school_ch=$headsch\">Summary</a> of $headsch's Music Entry Form</li>";
            echo "<li><a target=\"_blank\" class=small href=\"viewfull.php?session=$session&school_ch=$headsch\">Full Version</a> of $headsch's Music Entry Form</li>";
            echo "<br><font style=\"font-size:8pt;\"><b>* $headsch ";
            if(SubmittedForm($headsch)) echo "submitted this form on ".date("m/d/y",SubmittedForm($headsch)).".";
            else echo "has NOT submitted this form yet.";
            echo "</ul></td></tr></table>";
         }
         else
         {
	    if(SubmittedForm($school)) $be="were";
	    else $be="will be";
	    /*
	    if($multiplesite=='x')
	    {
	       echo GetEntryLinks($session,$school,'',$distid1,$be);
	       echo "<br>";
	       echo GetEntryLinks($session,$school,'',$distid2,$be);
	    }
	    else
	    */
             //  echo GetEntryLinks($session,$school,'',$distid,$be);
            echo "<ul><font style=\"font-size:8pt;\"><b>The following items $be sent to your contest site director(s), your district music coordinator,<br>&nbsp;&nbsp;&nbsp;&nbsp;and the NSAA Music Director upon final submission of your form:</b></font>";
            echo "<li>A copy of the <a target=\"_blank\" class=small href=\"summary.php?session=$session&school_ch=$school_ch\">Summary</a></li>";
            echo "<li>A copy of the <a target=\"_blank\" class=small href=\"viewfull.php?session=$session&school_ch=$school_ch\">Full Version</a> of your entry form</li>";
            echo "<li>Your school's list of <a target=\"_blank\" class=small href=\"eliglist.php?session=$session&school_ch=$school_ch\">Eligible Music Students</a></li>";
            echo "<li>Your <a target=\"_blank\" class=small href=\"payment.php?session=$session&school_ch=$school_ch\">Payment Summary</a> <br>(NOTE: If your district fees are computed after the contest or using a formula which cannot be completed prior to the contest, this feature will not show entry fees information.)";
	    if(IsCooping($school,"Vocal") && IsCooping($school,"Instrumental") && !IsHeadCoopSchool($school,"Vocal"))
	    {
	       $headsch=GetHeadCoopSchool($school,"Vocal");
	       echo "<br><font style=\"font-size:8pt\"><b>* $headsch ";
               if(SubmittedForm($headsch)) echo "submitted this form on ".date("m/d/y",SubmittedForm($headsch)).".";
	       else echo "has NOT submitted this form yet.";
	       echo "</b></font>";
	    }	
            echo "</li></ul>";
         }
         echo "<br><li class=nine>Once you are sure that your entry form is complete and accurate, you must check the box below labeled \"We verify that our entry is complete.  We would like to submit this as our final entry for the NSAA District Music Contest.\".  Then click the \"Submit Final Entry\" button.  By checking this box, you are certifying that the form is complete and accurate.  <b><u>You will not be able to make further changes to your form.</b></u></li>";
         echo "<br><li class=nine>If your form has any detectable errors, such as too many entries, you will receive error messages and instructions on fixing these errors.  You will then need to correct or supply the missing information before attempting to re-submit the form as in Step 3.</li></ol>";
      
         echo "<b>NOTE:</b> You must complete and submit your NSAA District Music Contest Entry Form by Midnight Central Time on <b>March 20, $year2</b>.";
         echo "</font></td></tr></table><br>";

         echo "<form method=post action=\"view_mu.php\">";
         echo "<input type=hidden name=session value=\"$session\">";
         echo "<input type=hidden name=school_ch value=\"$school_ch\">";
         echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3><tr align=left><td>";
         echo "<font style=\"color:blue;font-size:9pt;\"><b>IF YOU ARE READY TO SUBMIT YOUR FORM, please <u>double-check your form for accuracy</u> and check the box (below) to verify that your District Music Contest Entry Form is complete:<br></b></font>";
         echo "<input type=checkbox ";
         if(ereg("College",$school) || ereg("Public Schools",$school)) echo "disabled ";
         echo "name=send value='x'> <font style=\"font-size:9pt\"<b>We verify that our entry is complete.  We would like to submit this as our <u>final entry</u> for the NSAA District Music Contest.</b></font><br>";
         if($errormsg!='') echo $errormsg;
         else echo "<br><font style=\"color:red\"><b>CLICK ON THE \"Submit Final Entry\" BUTTON <u>ONCE</u> AND <u>WAIT FOR THE CONFIRMATION SCREEN TO APPEAR BEFORE TOUCHING ANY OTHER KEYS OR COMMANDS WITH YOUR INPUT DEVICE.</u></b></font><br><br>";
         echo "<input type=submit ";
         if(ereg("College",$school) || ereg("Public Schools",$school)) echo "disabled ";
         echO "name=submit value=\"Submit Final Entry\"></form></td></tr></table><br>";
         echo "</td></tr></table>";
      }//end if editpower==1
      else	//editpower==0
      {
         $headsch=GetHeadCoopSchool($school,"Vocal");
         if(SubmittedForm($headsch)) $be="were";
         else $be="will be";
         echo "<ul><font style=\"font-size:8pt;\"><b>The following items $be sent to your contest site director(s), your district music coordinator,<br>&nbsp;&nbsp;&nbsp;&nbsp;and the NSAA Music Director upon final submission of your form by $headsch:</b></font>";
         echo "<li>A copy of the <a target=\"_blank\" class=small href=\"summary.php?session=$session&school_ch=$headch\">Summary</a></li>";
         echo "<li>A copy of the <a target=\"_blank\" class=small href=\"viewfull.php?session=$session&school_ch=$headsch\">Full Version</a> of your entry form</li>";
         echo "<li>Your school's list of <a target=\"_blank\" class=small href=\"eliglist.php?session=$session&school_ch=$school_ch\">Eligible Music Students</a></li>";
         echo "<li>Your <a target=\"_blank\" class=small href=\"payment.php?session=$session&school_ch=$headsch\">Payment Summary</a><br>(NOTE: If your district fees are computed after the contest or using a formula which cannot be completed prior to the contest, this feature will not show entry fees information.)";
         echo "<br><font style=\"font-size:8pt\"><b>* $headsch ";
         if(SubmittedForm($headsch)) echo "submitted this form on ".date("m/d/y",SubmittedForm($headsch)).".";
         else echo "has NOT submitted this form yet.";
         echo "</b></font>";
         echo "</li></ul>";
     }
   }
   echo "</caption>";
   //Show SUMMARY of school's current entry form:
      //District Info at Top:
   $sql="SELECT * FROM mudistricts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $multiplesite=$row[multiplesite];
   echo "<tr align=center><td colspan=2>";
   echo "<table>";
   if($submitted!='') 	//entry submitted already
      $be="was";
   else $be="will be";
   echo "<tr align=center><td colspan=2><font style=\"font-size:9pt;\"><i>Your entry $be submitted for the following NSAA District Music Contest:</i></font></td></tr>";
   echo "<tr align=left><td colspan=2><b>District $row[distnum] -- $row[classes]</b>";
   if($submitted=='')
   {
      echo "&nbsp;&nbsp;<a class=small href=\"view_mu.php?session=$session&resetdist=1&school_ch=$school_ch\" onclick=\"alert('You will now be taken to a screen showing all the district contest sites. Please click on the correct district contest site.');\">[This is not the correct district contest for my school]</a>";
   }
   echo "</td></tr>";
   if($multiplesite!='x')
   {
   $date=split("/",$row[dates]);
   $dates="";
   for($i=0;$i<count($date);$i++)
   {
      $cur=split("-",$date[$i]);
      $dates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
   }
   $dates.=$cur[0]; 
   echo "<tr align=left><td><b>Date(s):</b></td><td>$dates</td></tr>";
   echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
   echo "<tr align=left><td><b>Director(s):</b></td><td>$row[director]</td></tr>";
   echO "<tr align=left valign=top><td><b>Address:</b></td>";
   echO "<td>$row[address1]<br>";
   if($row[address2]!='') echo "$row[address2]<br>";
   echo "$row[city], $row[state]  $row[zip]</td></tr>";
   $sql2="SELECT * FROM mubigdistricts WHERE distnum='$row[distnum]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2); 
   echo "<tr align=left><td><b>District $row[distnum] Coordinator(s):</b></td><td>$row2[coordinator] ($row2[school])</td></tr>";
   }
   else
   {
      $sql="SELECT * FROM mudistricts WHERE (id='$row[distid1]' OR id='$row[distid2]')";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $date=split("/",$row[dates]);
         $dates="";   
         for($i=0;$i<count($date);$i++)
         {
            $cur=split("-",$date[$i]);
            $dates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
         }
         $dates.=$cur[0];
	 echo "<tr align=left><td colspan=2><b>$row[site] Site:</b></td></tr>";
         echo "<tr align=left><td><b>Date(s):</b></td><td>$dates</td></tr>";
         echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
         echo "<tr align=left><td><b>Director(s):</b></td><td>$row[director]</td></tr>";
         echO "<tr align=left valign=top><td><b>Address:</b></td>";
         echO "<td>$row[address1]<br>";
         if($row[address2]!='') echo "$row[address2]<br>";
         echo "$row[city], $row[state]  $row[zip]</td></tr>";
	 $distnum=$row[distnum];
      }
      $sql2="SELECT * FROM mubigdistricts WHERE distnum='$distnum'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<tr align=left><td><b>District $distnum Coordinator(s):</b></td><td>$row2[coordinator] ($row2[school])</td></tr>";
   }
   echo "</table></td></tr>";
   echo "</table>";
}//end if entry in muschools table

echo $end_html;
?>
