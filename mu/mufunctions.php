<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//Percussion Instruments to be specificed in mupick.php upon entering a Percission Ensemble
$percinst=array("Bass Drum(s)","Snare Drum(s)","Conga Drum(s)","Timbales","Tom-tom(s)","Bongos","2 Timpani","4 Timpani","Drum Set","Hand Cymbals","Suspended Cymbal(s)","Gong","Steel Drum(s)","Hi-hat","Chimes","Xylophone","Marimba","Vibraphone","Orchestra Bells","Glockenspiel","Celeste","Piano","Electronic Keyboard","Other");

$mudirs=array("Instrumental - Main Contact","Instrumental - Band","Instrumental - Orchestra","Instrumental - Jazz Band","Vocal - Main Contact","Vocal - Chorus","Vocal - Show Choir","Vocal - Jazz Choir");
$mudirs_sm=array("imain","iband","iorch","ijband","vmain","vchorus","vschoir","vjchoir");

//$vocalsolos=array("Female Voice","Male Voice");
$vocalsolos=array("Soprano/Alto Voice","Tenor/Bass Voice"); 

$stringens=array("Violin","Viola","Cello","String Bass","Other");

$bandens=array("Piccolo","Flute","Oboe","English Horn","Bassoon","Clarinet--Soprano","Clarinet--Alto","Clarinet--Bass","Clarinet--ContraBass","Saxophone--Soprano","Saxophone--Alto","Saxophone--Tenor","Saxophone--Baritone","Horn","Cornet/Trumpet","Trombone","Bass Trombone","Baritone/Euphonium","Tuba","Percussion","Other");

$classchoices=array("AA","A","B","C","D");

function GetMusicSchoolName($schid)
{
   $sql="SELECT school FROM muschools WHERE id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[school];
}
function CountEnsembles($schid,$ensembleid)
{
   $sql="SELECT DISTINCT id FROM muentries WHERE schoolid='$schid' AND ensembleid='$ensembleid'";
   $result=mysql_query($sql);
   return mysql_num_rows($result);
}
function CountStudentsInEntry($entryid)
{
   $sql="SELECT DISTINCT studentid FROM mustudentries WHERE entryid='$entryid'";
   $result=mysql_query($sql);
   $count=mysql_num_rows($result);
   return $count;
}
function GetEnsembleFee($distid,$ensembleid)
{
   $sql="SELECT fee FROM mufees WHERE distid='$distid' AND ensembleid='$ensembleid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)	//check if this is a split district
   {
      $sql="SELECT * FROM mudistricts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[multiplesite]=='x')
      {
         $sql2="SELECT subdistid FROM mumultiplesiteensembles WHERE distid='$distid' AND ensembleid='$ensembleid'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $usedistid=$row2[0];
         return GetEnsembleFee($usedistid,$ensembleid);
      }
      else return FALSE;
   } 
   $fee=number_format($row[0],2,'.','');
   return $fee;
}
function CountStringEntries($schid)
{
   //first count entries that are non-solo and non-miscellaneous
   $sql="SELECT t2.id FROM muensembles AS t1, muentries AS t2 WHERE t1.id=t2.ensembleid AND t2.schoolid='$schid' AND (t1.ensemble LIKE '%String%' OR t1.ensemble='Violin Quartet' OR t1.ensemble LIKE 'Piano Trio%' OR t1.ensemble='Orchestra')";
   $result=mysql_query($sql);
   $count=mysql_num_rows($result);

   //now count solo and miscellaneous entries
   $sql="SELECT id FROM muentries WHERE strings='x' AND schoolid='$schid'";
   $result=mysql_query($sql);
   $count+=mysql_num_rows($result);

   return $count;
}
function CountPianoSolos($schid)
{
   //count piano solos
   $sql="SELECT id FROM muentries WHERE schoolid='$schid' AND event='Piano' AND piano='y'";
   $result=mysql_query($sql);
   $count=mysql_num_rows($result);

   return $count;
}
function CountEntries($schid)
{
   //count entries in muentries
   $sql="SELECT id FROM muentries WHERE schoolid='$schid'";
   $result=mysql_query($sql);
   $count=mysql_num_rows($result);

   $count=$count-CountStringEntries($schid)-CountPianoSolos($schid);
   return $count;
}
function TooManyEntries($schid)
{
   $count=CountEntries($schid);
   $sql="SELECT classch FROM muschools WHERE id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row[classch];

   if($class=="AA" && $count>48) return TRUE;
   else if($class=="A" && $count>36) return TRUE;
   else if(($class=="B" || $class=="C") && $count>30) return TRUE;
   else if($class=="D" && $count>24) return TRUE;
   else return FALSE;
}
function TooManyStringEntries($schid)
{
   $count=CountStringEntries($schid);
   $sql="SELECT classch FROM muschools WHERE id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row[classch];

   if($class=="AA" && $count>20) return TRUE;
   else if(($class=="A" || $class=="B" || $class=="C" || $class=="D") && $count>16) return TRUE;
   else return FALSE;
}
function GetMaxEntries($schid)
{
   $sql="SELECT classch FROM muschools WHERE id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row[classch];
   if($class=="AA") return '48';
   else if($class=="A") return '36';   
   else if($class=="B" || $class=="C") return '30';
   else if($class=="D") return '24';
   else return 0;
}
function GetMaxStringEntries($schid)
{
   $sql="SELECT classch FROM muschools WHERE id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row[classch];
   if($class=="AA") return '20';
   else if($class=="A" || $class=="B" || $class=="C" || $class=="D") return '16';
   else return 0;
}
function GetEntryStatus($schid)
{
   $sql="SELECT t1.distnum FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t2.id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $bigdistrict=$row[0];

   $regular=CountEntries($schid);
   if($regular==1) $entries="Entry";
   else $entries="Entries";
   if(TooManyEntries($schid)) $regularcolor='red';
   else $regularcolor='black';
   $string=CountStringEntries($schid);
   if($string==1) $strings="String";
   else $strings="Strings";
   if(TooManyStringEntries($schid)) $stringcolor='red';
   else $stringcolor='black';
   $piano=CountPianoSolos($schid);
   if($piano==1) $pianos="Piano Solo";
   else $pianos="Piano Solos";
   if($piano>2) $pianocolor='red';
   else $pianocolor='black';
   if($bigdistrict=='II')	//In Dist II, can only enter 1 of each Large Ens (except for Omaha B-T, which can enter 2 jazz bands)
   {
      $sql="SELECT t1.id,t1.ensemble FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Large' ORDER BY t1.ensemble";
      $result=mysql_query($sql);
      $substring="";
      while($row=mysql_fetch_array($result))
      {
         $curct=CountEnsembles($schid,$row[0]);
	 if(GetMusicSchoolName($schid)=="Test's School" || GetMusicSchoolName($schid)=="Omaha Brownell-Talbot")
	 {
	    if($row[ensemble]=="Jazz Band")
	       $max=2;
	    else
	       $max=1;
	 }
	 else
	    $max=1;
         if($curct>$max) $substring.="$curct $row[1] ensembles, ";
      }
      if($substring!='')
      {
	 $substring1=substr($substring,0,strlen($substring)-2);
         $substring="<font style='color:red'>In District II, you may only enter 1 of each Large Ensemble";
	 if(GetMusicSchoolName($schid)=="Test's School" || GetMusicSchoolName($schid)=="Omaha Brownell-Talbot")
		$substring.=" (except for Jazz Band, where you may enter 2)";
	 $substring.=".  You have entered <b>".$substring1."</b>.";
      }
   }
   $string="<font style='font-size:9pt;'><i>You have entered <font style='color:$regularcolor'><b><u>$regular $entries</u></b></font> plus <font style='color:$stringcolor'><b><u>$string $strings</u></b></font> and <font style='color:$pianocolor'><b><u>$piano $pianos</u></b></font>.</i>";
   if($regularcolor=='red')
      $string.="<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style='color:red;'>You can only have <b>".GetMaxEntries($schid)."</b> entries.</font>";
   if($stringcolor=='red')
      $string.="<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style='color:red;'>You can only have <b>".GetMaxStringEntries($schid)."</b> string entries.</font>";
   if($pianocolor=='red')
      $string.="<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style='color:red;'>You can only have <b>2</b> piano solos.</font>";
   if($bigdistrict=='II' && $substring!='')
      $string.="<br><br><b>NOTE:</b> ".$substring;
   $string.="</font>";
   return $string;
}
function DistIILargeError($schid)
{
   $sql="SELECT t2.distnum FROM muschools AS t1, mudistricts AS t2 WHERE t1.distid=t2.id AND t1.id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $bigdist=$row[0];
   if($bigdist!="II") return FALSE;
   $sql="SELECT t1.id,t1.ensemble FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Large' ORDER BY t1.ensemble";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
         $curct=CountEnsembles($schid,$row[0]);
         if(GetMusicSchoolName($schid)=="Test's School" || GetMusicSchoolName($schid)=="Omaha Brownell-Talbot")
         {
            if($row[ensemble]=="Jazz Band")
               $max=2;
            else
               $max=1;
         }
         else
            $max=1;
         if($curct>$max)
	   return TRUE;
         else
	   return FALSE;
   }
   return FALSE;
}
function GetCoopStatus($school)
{
   $string="";
   $school2=addslashes($school);
   $sql="SELECT * FROM mucoops WHERE (mainsch='$school2' OR othersch1='$school2' OR othersch2='$school2')";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return $string;
   $string.="<font style=\"font-size:9pt;\"><font style=\"color:blue;\">";
   $row=mysql_fetch_array($result);
   if($school==$row[mainsch])	//school is head school
   {
      $string.="You are co-oping with $row[othersch1]";
      if($row[othersch2]!='') $string.=" and $row[othersch2]";
      $string.=" for ";
      if($row[vocal]=='x' && $row[instrumental]=='x')
      {
	 $string.="Vocal & Instrumental Music:<br></font><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You are the <b>head school</b>, so it is your responsibility to complete and submit the Music Entry Form for your school and $row[othersch1]";
         if($row[othersch2]!='') $string.=" and $row2[othersch2]";
         $string.=".";  
      }
      else
      {
         if($row[vocal]=='x')
         {
	    $string.="Vocal Music:<br></font><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You are the <b>head school</b>, so it is your responsibility to complete the Vocal Music entries for <b>your school and $row[othersch1]";
	    if($row[othersch2]!='') $string.=" and $row[othersch2]";
            $string.="</b>, complete the Instrumental entries for <b>your school only</b>, and to <b>submit this form</b>.";
         }
         else
	 {
	    $string.="Instrumental Music:<br></font><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You are the <b>head school</b>, so it is your responsibility to complete the Instrumental entries for <b>your school and $row[othersch1]";
	    if($row[othersch2]!='') $string.=" and $row[othersch2]";
 	    $string.="</b>, complete the Vocal entries for <b>your school only</b>, and to <b>submit this form</b>.";
         }
      }
   }
   else	//school is not head school
   {
      $string.="You are co-oping with $row[mainsch]";
      if($row[othersch1]==$school && $row[othersch2]!='') $string.=" and $row[othersch2]";
      else if($row[othersch2]==$school && $row[othersch1]!='') $string.=" and $row[othersch1]";
      $string.=" for ";
      if($row[vocal]=='x' && $row[instrumental]=='x')
      {
         $string.="Vocal & Instrumental Music:<br></font><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$row[mainsch] is the head school</b>, so it is their responsibility to complete and submit the Music Entry Form for this co-op.  You can view the progress of the form by clicking on the links listed below.";
      }
      else if($row[vocal]=='x')
      {
         $string.="Vocal Music:<br></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><b>$row[mainsch] is the head school</b>, so it is their responsibility to complete the Vocal Music entries for this co-op.  It is <b>your responsibility</b> to complete the <b>Instrumental Music entries for your school only</b>, and to <b>submit this form</b>.";
      }
      else
      {
         $string.="Instrumental Music:<br></font><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$row[mainsch] is the head school</b>, so it is their responsibility to complete the Instrumental Music entries for this co-op.  Is it <b>your responsibility</b> to complete the <b>Vocal Music entries for your school only</b>, and to <b>submit this form</b>.";
      }
   }
   $string.="</i></font>";
   return $string;
}
function SubmittedForm($school)
{
   $school2=addslashes($school);
   $sql="SELECT submitted FROM muschools WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) return FALSE;
   $row=mysql_fetch_array($result);
   if($row[0]=="") return FALSE;
   else return $row[0];
}
function IsCooping($school,$vocinst)
{   
   $school2=addslashes($school);
   $sql="SELECT * FROM mucoops WHERE (mainsch='$school2' OR othersch1='$school2' OR othersch2='$school2') AND ".strtolower($vocinst)."='x'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) return TRUE;
   else return FALSE;
}
function IsHeadCoopSchool($school,$vocinst)
{
   $school2=addslashes($school);
   $sql="SELECT * FROM mucoops WHERE mainsch='$school2' AND ".strtolower($vocinst)."='x'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) return TRUE;
   else return FALSE;
}
function GetHeadCoopSchool($school,$vocinst)
{
   $school2=addslashes($school);
   $sql="SELECT * FROM mucoops WHERE (mainsch='$school2' OR othersch1='$school2' OR othersch2='$school2') AND ".strtolower($vocinst)."='x'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $mainsch=$row[mainsch];
   return $mainsch;
}
function GetOtherCoopSchool($school,$vocinst)
{
   //return school this school is co-oping with 
   $school2=addslashes($school);
   $sql="SELECT * FROM mucoops WHERE (mainsch='$school2' OR othersch1='$school2' OR othersch2='$school2') AND
".strtolower($vocinst)."='x'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[mainsch]==$school) return $row[othersch1];
   else return $row[mainsch];
}
function GetMusicCoopSchools($school,$vocinst)
{
   $school2=addslashes($school);
   $sql="SELECT * FROM mucoops WHERE (mainsch='$school2' OR othersch1='$school2' OR othersch2='$school2') AND ".strtolower($vocinst)."='x'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $schools=array($row[mainsch],$row[othersch1]);
   if($row[othersch2]!='') $schools[2]=$row[othersch2];
   return $schools;
}
function GetEntryLinks($session,$schoolch,$vocinst,$distid,$be)
{
   $sql="SELECT * FROM mudistricts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
 
   $string="<ul><font style=\"font-size:8pt;\"><b>The following ";
   if($vocinst!='') $string.=$vocinst." ";
   $string.="Music Entry items $be sent to the <i>$row[distnum] -- $row[classes] ($row[site])</i> site director(s), your district music coordinator, and the NSAA Music Director upon final submission of the form by <u>$schoolch</u>:<br><br></b></font>";
   $string.="<li>A copy of the <a target=\"_blank\" class=small href=\"summary.php?session=$session&school_ch=$schoolch\">Summary</a> of the ";
   if($vocinst!='') $string.=$vocinst." ";
   $string.="Music Entry</li>";
   $string.="<li>A copy of the <a target=\"_blank\" class=small href=\"viewfull.php?session=$session&school_ch=$schoolch\">Full Version</a> of the ";
   if($vocinst!='') $string.=$vocinst." ";
   $string.="Music Entry</li>";
   $string.="<li>The school(s)'s list(s) of <a target=\"_blank\" class=small href=\"eliglist.php?session=$session&school=$schoolch\">Eligible Music Students</a></li>";
   $string.="<li>The <a target=\"_blank\" class=small href=\"payment.php?session=$session&school_ch=$schoolch&showdistid=$distid\">Payment Summary</a> for the ";
   if($vocinst!='') $string.=$vocinst." ";
   $string.="Music Entry fees<br>(NOTE: If your district fees are computed after the contest or using a formula which cannot be completed prior to the contest, this feature will not show entry fees information.)</li>";
   $string.="</ul>";
   return $string;
}
function PayingForEnsemble($maindistid,$subdistid,$ensid)
{
   $sql="SELECT * FROM mumultiplesiteensembles WHERE distid='$maindistid' AND subdistid='$subdistid' AND ensembleid='$ensid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) return FALSE;
   else return TRUE;
}
?>
