<?php
//edit_categ.php: Edit Page for Music Online Entry Form (iframe src)

require '../functions.php';
require '../../calculate/functions.php';
require 'mufunctions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

$level=GetLevel($session);

if(($oldcateg && $oldcateg!=$categ || $go=="Go") && !$savesolos)
{
   unset($event);
   unset($studentid);
   unset($soloorder);
   unset($accompanist);
   //echo $oldcateg."=old<br>$categ=new<br>$go=go";
}

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch || $level>1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];
$distid=$row[distid];
$year1=GetFallYear('mu');
$year2=$year1+1;
if($delete)
{
   $sql="DELETE FROM muentries WHERE id='$delete'";
   $result=mysql_query($sql);
   $sql="DELETE FROM mustudentries WHERE entryid='$delete'";
   $result=mysql_query($sql);
}
if($save || $hiddensave)
{
   $sql="UPDATE muschools SET classch='$classch',studcount='$studcount',";
   if($member=='n')
      $sql.="homedistrict='$homedist',";
   else if($member=='y')
      $sql.="homedistrict='',";
   for($i=0;$i<count($mudirs);$i++)
   {
      $name=$mudirs_sm[$i]; $email=$name."email"; $school=$name."school"; $home=$name."home";
      $schoolf=$name."schoolf"; $ext=$name."ext";
      if($$ext!='')
         $$school.="X".$$ext;
      $$name=addslashes($$name);
      $sql.="$name='".$$name."',$email='".$$email."',$school='".$$school."',$home='".$$home."',$schoolf='".$$schoolf."',";
   }
   $sql=substr($sql,0,strlen($sql)-1);
   $sql.=" WHERE school='$school2'";
   $result=mysql_query($sql);
   //echo mysql_error();
   unset($categ);
   if($nextcateg) $categ=$nextcateg;
}
if($savesolos)	//save solos
{
   $sql="DELETE FROM muentries WHERE ensembleid='$ensembleid' AND schoolid='$schid'";
   $result=mysql_query($sql);

   for($i=0;$i<count($studentid);$i++)
   {
      if($studentid[$i]!="")
      {
	 $percabbrev[$i]=ereg_replace("\r\n","<br>",$percabbrev[$i]);
	 $otherchk[$i]=''; $percchk[$i]='';
         if($noaccomp[$i]=='x') $accompanist[$i]="none";
  	 $accompanist[$i]=addslashes($accompanist[$i]);
         if($piano[$i]=='y') $event[$i]="Piano";
         if($piano[$i]=='s')
	 {
	    $string='x'; 
	    if($event0[$i]=="Other")
            {   $event[$i]=addslashes($other_2[$i]); $otherchk[$i]='x'; }
            else 
	       $event[$i]=$event0[$i]; 
	 }
	 else
	    $string='';
  	 if($piano[$i]=='n' && $event1[$i]=="Other")
	 {   $event[$i]=addslashes($other[$i]); $otherchk[$i]='x'; }
	 else if($piano[$i]=='n' && $event1[$i]=="Percussion")
	 {   $event[$i]=addslashes($percabbrev[$i]); $percchk[$i]='x'; }
	 else if($piano[$i]=='n')
	    $event[$i]=$event1[$i];
	 //see if they are about to go over their limit for Piano Solos (2 is max)
	 $pianoct=CountPianoSolos($schid);	//event[$i]=Piano and piano[$i]=y
	 if($pianoct>=2 && $event[$i]=='Piano' && $piano[$i]=='y')	//can't add any more
	 {
	    $pianoerror=1; $pianoerr[$i]=1;
	 }
	 else
	 {
	    $sql="INSERT INTO muentries (ensembleid,schoolid,studentid,event,accompanist,strings,soloorder,piano,other,percussion) VALUES ('$ensembleid','$schid','$studentid[$i]','$event[$i]','$accompanist[$i]','$string','$soloorder[$i]','$piano[$i]','$otherchk[$i]','$percchk[$i]')";
	    $result=mysql_query($sql);
	    //echo "$sql<br>";
	 }
         if(($piano[$i]=='s' || $piano[$i]=='n') && (trim($event[$i])=="" || trim($event[$i])=="[Enter Instrument]"))
	 {
	    $soloerror=1; $soloerr[$i]=1;
	 }
	 else $soloerr[$i]=0;
	 if($accompanist[$i]=="")
	 {
	    $accerror=1; $accerr[$i]=1;
	 }
	 $accerror=0;
      }
   }
   unset($piano); unset($event);
}          
echo $init_html_ajax;
$entrystatus=GetEntryStatus($schid);
?>  
<script type="text/javascript" src="/javascript/Music.js"></script>
<script language="javascript">
function UpdateEntryStatus(status)
{
   parent.document.getElementById('entrystatus').innerHTML=status;
}
UpdateEntryStatus("<?php echo $entrystatus; ?>");
function ErrorCheck()
{
   var errors="";
   if(Utilities.getElement('classch').options.selectedIndex==0)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Class:</b></font> You must select your class.</td></tr>";
   if(Utilities.getElement('imainname').value=="")
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Instrumental Contact:</b></font> You must enter the <b>Name</b> of your main instrumental contact.</td></tr>";
   if(Utilities.getElement('imainemail').value=="")
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Instrumental Contact:</b></font> You must enter the <b>E-mail</b> of your main instrumental contact.</td></tr>";
   var sphone=Utilities.getElement('imainsphone').value.replace(/\D/g,"");
   var sfax=Utilities.getElement('imainsfax').value.replace(/\D/g,"");
   var hphone=Utilities.getElement('imainhphone').value.replace(/\D/g,"");
   if(sphone.length!=10)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Instrumental Contact:</b></font> You must enter the <b>School Phone</b>, including area code, for your main instrumental contact.</td></tr>";
   if(sfax.length!=10)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Instrumental Contact:</b></font> You must enter the <b>School Fax</b>, including area code, for your main instrumental contact.</td></tr>";
   if(hphone.length!=10)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Instrumental Contact:</b></font> You must enter the <b>Home Phone</b>, including area code, for your main instrumental contact.</td></tr>";
   if(Utilities.getElement('vmainname').value=="")
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Vocal Contact:</b></font> You must enter the <b>Name</b> of your main vocal contact.</td></tr>";
   if(Utilities.getElement('vmainemail').value=="")
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Vocal Contact:</b></font> You must enter the <b>E-mail</b> of your main vocal contact.</td></tr>";
   sphone=Utilities.getElement('vmainsphone').value.replace(/\D/g,"");
   sfax=Utilities.getElement('vmainsfax').value.replace(/\D/g,"");
   hphone=Utilities.getElement('vmainhphone').value.replace(/\D/g,"");
   if(sphone.length!=10)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Vocal Contact:</b></font> You must enter the <b>School Phone</b>, including area code, for your main vocal contact.</td></tr>";
   if(sfax.length!=10)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Vocal Contact:</b></font> You must enter the <b>School Fax</b>, including area code, for your main vocal contact.</td></tr>";
   if(hphone.length!=10)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Vocal Contact:</b></font> You must enter the <b>Home Phone</b>, including area code, for your main vocal contact.</td></tr>";
   if(errors!="")
   {
      Utilities.getElement('errordiv').style.display="";
      Utilities.getElement('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in your form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"Utilities.getElement('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      Utilities.getElement('hiddensave').value="Save";
      document.forms.muform.submit();
   }
}
</script>
</head>
<body onload="Music.initialize('<?php echo $school2; ?>');">
<?php
echo "<table width=100%><tr align=center><td>";

   echo "<form method=post action=\"edit_categ.php\" name=\"muform\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=school_ch value=\"$school_ch\">";
   echo "<input type=hidden name=categ value=\"$categ\" id=\"categ\">";
   echo "<input type=hidden name=\"nextcateg\" id=\"nextcateg\">";
   echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
   echo "<table>";
   if(!$categ || $categ=='') //enter school/directors info
   {
      $sql="SELECT * FROM mudistricts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $bigdist=$row[distnum];
      $classlist=split("/",$row[classlist]);
      echo "<tr align=center><td colspan=2>";
      echo "<ul width=600><li><font style=\"color:blue;font-size:9pt;\"><b><i>Please complete the information below.  You MUST enter your school's Class.  Also, please enter the name and contact info for your Music Directors below.  You MUST complete ALL fields for your Vocal Department Main Contact AND your Instrumental Department Main Contact.</b></i></font></li></ul>";
      echo "<table>";
      $sql2="SELECT * FROM muschools WHERE id='$schid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<tr align=left><td width=250><b>Your School:</b></td><td>$row2[school]</td></tr>";
      //Ask if they are a member of the district they've selected:
      echo "<tr valign=top align=left><td width=250><a name=\"radio\"></a>Is your school a member of <b>NSAA District <u>$bigdist</u></b> according to the current NSAA Music Manual?</td>";
      if($row2[homedistrict]=='')
	 $member='y';
      else if($row2[homedistrict]!=$bigdist)
	 $member='n';
      echo "<td><input type=radio name=member value='y' ";
      if($member=='y') echo " checked";
      echo ">Yes&nbsp;<input type=radio name=member value='n' ";
      if($member=='n') echo " checked";
      echo ">No<br>";
      //if NO, ask for home district
         echo "<b>If NO, </b>Please select your home district:&nbsp;";
         echo "<select name=homedist><option value=''>~</option>";
         $sql3="SELECT distnum FROM mubigdistricts WHERE distnum!='$bigdist' ORDER BY id";
         $result3=mysql_query($sql3);
         while($row3=mysql_fetch_array($result3))
         {
            echo "<option";
            if($row2[homedistrict]==$row3[distnum]) echo " selected";
            echo ">$row3[distnum]</option>";
         }
         echo "</select></td></tr>";
      echo "<tr align=left valign=top><td width=200>";
      if($classerror==1) echo "<font style=\"color:red\">";
      echo "<b>Class:</b>";
      if($classerror==1) echo "</font>";
      echo "<br><font style=\"color:blue\">(REQUIRED)</font></td><td><select id=\"classch\" name=classch><option value=''>~</option>";
      for($i=0;$i<count($classlist);$i++)
      {
	 echo "<option";
	 if($row2[classch]==$classlist[$i]) echo " selected";
	 echo ">$classlist[$i]</option>";
      }
      echo "</select></td></tr>";
      echo "<tr valign=top align=left><td><b>";
      //if($studerror==1) echo "<font style=\"color:red\">";
      echO "Total Number of Students Entered:</b>";
      //if($studerror==1) echo "</font>";
      echo "<br></b><font style=\"color:blue\">(REQUIRED)</font>";
      echo "</td>";
      echo "<td><input type=text id=\"studcount\" name=\"studcount\" size=3 class=tiny value=\"$row2[studcount]\">&nbsp;(Count each student only once)</td></tr>";
      echo "<tr align=left><td colspan=2><b>MUSIC DIRECTORS:</b><br>";
      if($contacterror==1) $color='red';
      else $color='blue';
      echo "<font style=\"color:$color\">***NOTE: You must enter ALL of the information for the Instrumental - Main Contact AND the Vocal - Main Contact.***</font></td></tr>";
      echo "<tr align=center><td colspan=2><table cellspacing=0 cellpadding=4>";
      echo "<tr align=left valign=top><td><b>Director of:</b></td><td><b>Director's Name:</b></td>";
      echo "<td><b>E-mail:</b></td><td><b>School Phone (Ext. #):</b></td><td><b>School Fax:</b></td>";
      echo "<td><b>Home Phone:</b></td></tr>";
      for($i=0;$i<count($mudirs);$i++)
      {
	 echo "<tr align=left valign=top";
	 if($i%2==0) echo " bgcolor=#E0E0E0";
	 echo ">";
	 echo "<td>";
         if(ereg("Main",$mudirs[$i]) && $contacterror==1) echo "<font style=\"color:red\">";
         echo "<b>$mudirs[$i]:</b>";
         if(ereg("Main",$mudirs[$i]) && $contacterror==1) echo "</font>";
         if(ereg("Main",$mudirs[$i])) echo "<br><font style=\"color:blue\">(REQUIRED)</font>";
         echo "</td>";
	 $name=$mudirs_sm[$i]; $email=$name."email"; $school=$name."school"; $home=$name."home";
	 $schoolf=$name."schoolf"; $ext=$name."ext";
	 $temp=split("X",$row2[$school]);
         $curschool=$temp[0]; $curext=$temp[1];
	 if(ereg("Main",$mudirs[$i]) && ereg("Instrumental",$mudirs[$i]))
	 {
            echo "<td><input type=text class=tiny size=20 id=\"imainname\" name=\"$name\" value=\"".$row2[$name]."\"></td>";
            echo "<td><input type=text class=tiny size=20 id=\"imainemail\" name=\"$email\" value=\"".$row2[$email]."\">";
            echo "<td><input type=text class=tiny size=12 id=\"imainsphone\" name=\"$school\" value=\"".$curschool."\">";
            echo "(<input type=text class=tiny size=3 name=\"$ext\" value=\"$curext\">)</td>";
            echo "<td><input type=text class=tiny size=12 id=\"imainsfax\" name=\"$schoolf\" value=\"".$row2[$schoolf]."\"></td>";
            echo "<td><input type=text class=tiny size=12 id=\"imainhphone\" name=\"$home\" value=\"".$row2[$home]."\"></td>";
	    echo "</tr><tr align=center><td colspan=5><div id=\"errordiv\" class=\"searchresults\" style=\"left:300px;width:400px;display:none;\"></div></td>";
         }
         else if(ereg("Main",$mudirs[$i]) && ereg("Vocal",$mudirs[$i]))
         {
            echo "<td><input type=text class=tiny size=20 id=\"vmainname\" name=\"$name\" value=\"".$row2[$name]."\"></td>";
            echo "<td><input type=text class=tiny size=20 id=\"vmainemail\" name=\"$email\" value=\"".$row2[$email]."\">";
            echo "<td><input type=text class=tiny size=12 id=\"vmainsphone\" name=\"$school\" value=\"".$curschool."\">";
            echo "(<input type=text class=tiny size=3 name=\"$ext\" value=\"$curext\">)</td>";
            echo "<td><input type=text class=tiny size=12 id=\"vmainsfax\" name=\"$schoolf\" value=\"".$row2[$schoolf]."\"></td>";
            echo "<td><input type=text class=tiny size=12 id=\"vmainhphone\" name=\"$home\" value=\"".$row2[$home]."\"></td>";
            echo "</tr><tr align=center><td colspan=5><div id=\"errordiv\" class=\"searchresults\" style=\"left:300px;width:400px;display:none;\"></div></td>";
         }
	 else
	 {
	    echo "<td><input type=text class=tiny size=20 name=\"$name\" value=\"".$row2[$name]."\"></td>";
	    echo "<td><input type=text class=tiny size=20 name=\"$email\" value=\"".$row2[$email]."\"></td>";
            echo "<td><input type=text class=tiny size=12 name=\"$school\" value=\"".$curschool."\">";
	    echo "(<input type=text class=tiny size=3 name=\"$ext\" value=\"$curext\">)</td>";
            echo "<td><input type=text class=tiny size=12 name=\"$schoolf\" value=\"".$row2[$schoolf]."\"></td>";
	    echo "<td><input type=text class=tiny size=12 name=\"$home\" value=\"".$row2[$home]."\"></td>";
         }
	 echo "</tr>";
      } 
      echo "</table></td></tr>";
      echo "<tr align=center><td colspan=2><input type=button name=save onclick=\"ErrorCheck();\" style=\"font-size:16pt\" value=\"Save\"></td></tr>";
   }
   if(IsCooping($school,"Vocal") && !IsHeadCoopSchool($school,"Vocal") && ereg("Vocal",$curcatname))
   {
      //If co-oping for Vocal, and this school is not the head school, they cannot edit vocal entries
      echo "<tr align=center><td><table width=500><tr align=left><td>".GetCoopStatus($school)."<br><br>";
      echo "You can preview the progress of your co-op's Vocal entries (entered by ".GetHeadCoopSchool($school,"Vocal").") by returning to the <a class=small target=\"_top\" href=\"view_mu.php?session=$session&school_ch=$school_ch\">NSAA District Music Contest Online Entry Form Home Page</a>.<br><br>";
      echo "To work on your school's Instrumental entries, select a specific Instrumental category above.";
      echo "</td></tr></table></td></tr>";
      exit();
   }
   else if(IsCooping($school,"Instrumental") && !IsHeadCoopSchool($school,"Instrumental") && ereg("Instrumental",$curcatname))
   {
      echo "<tr align=center><td><table width=500><tr align=left><td>".GetCoopStatus($school)."<br><br>";
      echo "You can preview the progress of your co=op's Instrumental entries (entered by ".GetHeadCoopSchool($school,"Instrumental").") by returning to the <a class=small target=\"_top\" href=\"view_mu.php?session=$session&school_ch=$school_ch\">NSAA District Music Contest Online Entry Form Home Page</a>.<br><br>";
      echO "To work on your school's Vocal entries, select a specific Vocal category above.";
      echO "</td></tr></table></td></tr>";
      exit();
   }
   if(!ereg("Solo",$curcatname) && $categ)
      echo "<tr align=center><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style=\"color:red\"><b><u>PLEASE NOTE:</u></b>  TO COMPLETE THIS SECTION, YOU MAY NEED TO DISABLE THE \"BLOCK POP-UPS\" SECURITY FEATURE ON YOUR COMPUTER.</font>";
   echo "</td></tr>";
   echo "</table></td></tr>";
   if($categ)
   {
      echo "<input type=hidden name=oldcateg value=\"$categ\">";
      $sql="SELECT * FROM mucategories WHERE id='$categ'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $smlg=$row[smlg]; $vocinst=$row[vocinst];
      $category=$row[category];
      echo "<tr align=center><td colspan=2><table cellspacing=1 cellpadding=2>";
      if(!ereg("Solo",$category))	//Everything but Solos 
      {
	 //Instructions:
	 echo "<tr align=center><td colspan=3><table><tr align=left><td colspan=3><i><b><a name=\"top\">INSTRUCTIONS:</a></b></td></tr>";
	 echo "<tr align=left><td colspan=3>1) <b>To work on a particular type of ensemble, click on the name</b> of that type.</td></tr>";
         echo "<tr align=left><td colspan=3>2) To <b>add a new ensemble</b>, click the \"Add New ...\" link.</td></tr>";
         echo "<tr align=left><td colspan=3>3) To <b>view or edit an ensemble</b> you have already entered, click the \"Edit\" link.</td></tr>";
 	 echO "<tr align=left><td colspan=3>4) To <b>delete an ensemble</b> you have already entered, click the \"Delete\" link.</td></tr>";
 	 echo "<tr align=left><td colspan=3><b>NOTE:</b> The number of ensembles you have entered for each type is listed in brackets next to the name of the ensemble category.<br>There is <b>no \"Save\" button</b> on this screen.  Your entries are saved in the pop-up window that opens when you click on \"Add New...\", \"Edit\" or \"Delete\".</td></tr>";
	 echo "</table></td></tr>";
         $sql="SELECT * FROM muensembles WHERE categid='$categ'";
	 if($categ==1 || $categ==2)	//Small Vocal/Instrumental Ensemble - show option for Small Vocal AND Inst Misc Ensemble too
	    $sql.=" OR categid=0";
	 $sql.=" ORDER BY orderby,id";
         $result=mysql_query($sql);
         $total=mysql_num_rows($result);
         $percol=$total/3;
         $curcol=0;
         echo "<tr align=left valign=top><td>";
         while($row=mysql_fetch_array($result))
         {
            if($curcol>=$percol)
	    {
	       $curcol=0; echo "</td><td>";
	    }
            if($open==$row[id]) $thisopen="";
	    else $thisopen=$row[id];
            echo "<a href=\"edit_categ.php?categ=$categ&session=$session&school_ch=$school_ch&open=$thisopen\">";
            //SMALL ENS: check if any have >24 students checked
	    $toomany=0; $noentries=0;
            if($smlg=="Small" || $row[ensemble]=="Jazz Band" || $row[ensemble]=="Madrigal" || $row[ensemble]=="Show Choir")
   	    {
	       $sql2="SELECT id FROM muentries WHERE schoolid='$schid' AND ensembleid='$row[id]'";
	       $result2=mysql_query($sql2);
	       while($row2=mysql_fetch_array($result2))
	       {
	          if(CountStudentsInEntry($row2[0])>24 && $smlg=="Small")
		     $toomany=1;
	          else if(CountStudentsInEntry($row2[0])==0)
		     $noentries=1;
	       }
	    }
	    if($toomany==1 || $noentries==1)
	       echo "<font style=\"color:red\">";
   	    if($open==$row[id]) echo "&nabla; ";
	    echo "$row[ensemble]";
	    if($toomany==1) echo "<br>(Too many students!)</font>";
   	    else if($noentries==1) echo "<br>(No students checked!)</font>";
	    echo "</a>";
	    $curct=CountEnsembles($schid,$row[id]);
	    echo " [$curct]";
	    if($open==$row[id])	//THIS ENSEMBLE IS CURRENTLY OPEN
	    {
	       echo "<br><table width=100% cellspacing=2 cellpadding=3 bgcolor=#E0E0E0>";
	       //Show Current:
               $sql1="SELECT * FROM muentries WHERE ensembleid='$row[id]' AND schoolid='$schid' ORDER BY id";
	       $result1=mysql_query($sql1);
	       $ct=1;
	       while($row1=mysql_fetch_array($result1))
	       {
	          $sql2="SELECT t2.first,t2.last FROM mustudentries AS t1, eligibility AS t2 WHERE t1.studentid=t2.id AND t1.entryid='$row1[id]' ORDER BY t2.last,t2.first";
	          $result2=mysql_query($sql2);
	          echo "<tr align=left><td>#$ct)&nbsp;";
                  if($row1[event]=="") $row1[event]="[No Title Given]";
	          if(ereg("Misc",$row[ensemble])) echo "$row1[event]<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	 	  echo "<a class=small href=\"#\" onclick=\"window.open('mupick.php?session=$session&school_ch=$school2&categ=$categ&ensembleid=$row[id]&entryid=$row1[id]','mupick','location=no,scrollbars=yes,width=500,height=590');\">[Edit]</a>";
	          $ens2=addslashes($row[ensemble]);
	          echo "&nbsp;<a class=small href=\"edit_categ.php?session=$session&school_ch=$school_ch&categ=$categ&open=$open&delete=$row1[id]\" onclick=\"return confirm('Are you sure you want to delete this $ens2?');\">[Delete]</a><br>";
	 	  if($smlg=="Small" && CountStudentsInEntry($row1[id])>24)
		     echo "<font style=\"color:red\"><b>".CountStudentsInEntry($row1[id])." students entered: You may only have 24 students!!<br>Please click \"Edit\" and remove extra students.<br></b></font>";
	          else if(($smlg=="Small" || $row[ensemble]=="Jazz Band" || $row[ensemble]=="Madrigal" || $row[ensemble]=="Show Choir") && CountStudentsInEntry($row1[id])==0)
		     echo "<font style=\"color:red\"><b>No students entered!!<br>Please click \"Edit\" and check the students in this $row[ensemble].<br></b></font>";
	   	  else if($smlg=="Small")
	  	     echo CountStudentsInEntry($row1[id])." students entered.<br>";
	          if($smlg=="Large")
	          {
	             if(CountStudentsInEntry($row1[id])>0) //count students (only if they've been entered)
		        $groupsize=CountStudentsInEntry($row1[id]);
	             else
		        $groupsize=$row1[groupsize]; 
   		     if($groupsize=="") $groupsize=0;
	             echo "No. in Group: $groupsize<br>";
	          } 
		  if(ereg("Misc",$row[ensemble]))
		  {
                     echo "&nbsp;&nbsp;&nbsp;<b>String Ensemble:</b> ";
                     if($row[strings]=='x') echo "YES<br>";
                     else echo "NO<br>";
		  }
	          if(!($smlg=='Large' && $vocinst=='Instrumental') && $row1[accompanist]!='')
	          {
	             echo "Accompanist: $row1[accompanist]<br>";
	          } 
	          $studs="";
	          while($row2=mysql_fetch_array($result2))
	          {
	             $studs.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row2[first] $row2[last]<br>"; 
	          }
	          $studs=substr($studs,0,strlen($studs)-4);
	          if(trim($studs)=="") $studs="[No students entered]";
	          echo $studs."</td></tr>";
	          $ct++;
	       }
	       //Add New:
	       echo "<tr align=left><td><a class=small href=\"#\" onclick=\"window.open('mupick.php?session=$session&school_ch=$school2&categ=$categ&ensembleid=$row[id]&entryid=0','mupick','location=no,scrollbars=yes,width=500,height=590');\">Add New ".$row[ensemble]."</a></td></tr>";
	       echo "</table>";
	    }//end if open
            else echo "<br><br>";
	    $curcol++;
         }
         echo "</td></tr>";
      }//end if NOT Solos 
      else		//SOLOS
      {
	 $sql="SELECT * FROM muensembles WHERE categid='$categ'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 echo "<input type=hidden name=ensembleid value=\"$row[id]\">";
	 //Instructions:
         echo "<tr align=left><td colspan=2><table width=100%><tr align=left><td colspan=3><font style=\"font-size:9pt;color:blue\"><b>INSTRUCTIONS:</b></font><table width='850px'><tr align=left><td>";
         if(ereg("Instrumental",$category))
   	 {
	    echo "1) Please check either <b>Piano, String, or Band</b>.</td></tr><tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) If you select <b>String or Band</b>, you will then be able to select the <b>specific type of solo</b> OR <b>(for band only) you may select Other and then type in the event.</b> If you select <b>Percussion</b>, a window will pop open and you will need to <b>list all of the percussion instruments to be used, including how many of each.</b></td></tr><tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) If you select <b>Piano</b>, you may then proceed to step (2).</td></tr>";
            echo "<tr align=left><td>2) Select the <b>name of the Soloist</b>.  If you checked <b>String or Band</b>, you will only be able to select from the students on your school's <b>Instrumental Music</b> eligibility list.  If you checked <b>Piano</b>, you will be able to choose from students on both your school's <b>Instrumental AND Vocal Music</b> eligibility lists.  To <b>jump to a student's name in the dropdown list</b>, click on the dropdown list and type the first letter (or more) of his/her last name.</td></tr>";
	    echo "<tr align=left><td>3) Enter the <b>name of the Accompanist</b> OR check <b>\"None\"</b> if there is no accompanist for that solo.</td></tr>";
	    echo "<tr align=left><td>4) Click the <b>\"Save Solos\"</b> button at the bottom of the screen.  This will save the solo(s) you've entered and then give you a new line to add another solo.</td></tr>";
	    echo "<tr align=left><td><b>NOTE: You may enter 1 new solo at a time</b> (the last row before the \"Save Solos\" button) and you may edit your existing solos at any time.</td></tr>";
	    echO "<tr align=left><td><b><u>To REMOVE an existing solo</b></u>, simply select \"Choose Student\" from the dropdown menu of students for that solo and click \"Save Solos\".<a name=\"top\"><br><br></a>";
	 }
	 else
	 {
	    echo "1) Please select the <b>Event</b> for the solo you are entering.</td></tr>";
 	    echo "<tr align=left><td>2) Select the <b>name of the Soloist</b>.  You will be choosing from students on your school's <b>Vocal Music</b> eligibility list.  To <b>jump to a student's name in the dropdown list</b>, click on the dropdown list and type the first letter (or more) of his/her last name.</td></tr>";
            echo "<tr align=left><td>3) Type in the <b>Accompanist</b> OR check <b>\"None\"</b> if there is no accompanist for that solo.</td></tr>";
	    echo "<tr align=left><td>4) Click the <b>\"Save Solos\"</b> button at the bottom of the screen.  This will save the solo(s) you've entered and then give you a new line to add another solo.</td></tr>";
	    echo "<tr align=left><td><b>NOTE: You may enter 1 new solo at a time</b> (in the last row before the \"Save Solos\" button) and you may edit your existing solos at any time.</td></tr>";
	    echo "<tr align=left><td><b><u>To REMOVE an existing solo</b></u>, simply select \"Choose Student\" from the dropdown menu of students for that solo.<a name=\"top\"><br><br></a>";
	 }
	 echo "</td></tr></table></td></tr>";
         if($soloerror==1)
	 {
	    echo "<tr align=left><td colspan=3><div class='error'>Where you select \"String\" or \"Band\", you must indicate a specific instrument.<br><br>Additionally, if you select \"Other\" under String or Band or \"Percussion\" under Band, you must enter the specific instrument(s) in the provided textbox. Please do so and click \"Save Solos\" again.</div></td></tr>";
	 }
	 if($accerror==1)
	 {
	    echo "<tr align=left><td colspan=3><div class='error'>You must enter your accompanist for this entry or check \"none\" to allow proper scheduling by your contest manager.</div></td></tr>";
	 }
	 if($pianoerror==1)
	 {
	    echo "<tr align=left><td colspan=3><div class='error'>You may only enter <b><u>2 Piano Solos</b></u>.  Any additional piano solos entered were not saved below.</div></td></tr>";
	 }
         if(ereg("Instrumental",$category)) $width="450";
         else $width="200";
	 echo "<tr align=left valign=top>";
	 echo "<td width=$width><b>Event:</b></td>";
	 echo "<td><b>Soloist:</b></td><td><b>Accompanist:</b><br>(Please type name or check \"None\")</td></tr>";

	 //get list of mu students for this school:
         if(ereg("Public Schools",$school) || ereg("College",$school))
	    $school2=addslashes("Test's School");
         $allstuds=array(); 
         $vmstuds=array(); 
         $imstuds=array();
	 //IM students:
	 $ix=0;
         if(IsCooping($school,"Instrumental"))
         {
            $schools=GetMusicCoopSchools($school,"Instrumental");
	    $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND (";
  	    for($i=0;$i<count($schools);$i++)
	    {
	       $cursch=addslashes($schools[$i]);
	       $sql2.="school='$cursch' OR ";
	    }
	    $sql2=substr($sql2,0,strlen($sql2)-4);
	    $sql2.=") AND im='x' ORDER BY school,last,first"; 
	    $result2=mysql_query($sql2);
	    while($row2=mysql_fetch_array($result2))
	    {
               $imstuds[name][$ix]="$row2[last], $row2[first] ($row2[school])";
	       $imstuds[id][$ix]=$row2[id];
	       $ix++;
	    }
	 }
	 else
	 {
	    $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND school='$school2' AND im='x' ORDER BY last,first";
	    $result2=mysql_query($sql2); 
            while($row2=mysql_fetch_array($result2))
            {
               $imstuds[name][$ix]="$row2[last], $row2[first]";
               $imstuds[id][$ix]=$row2[id];
               $ix++;
            }
         }
         //VM students:
         $ix=0;
         if(IsCooping($school,"Vocal")) 
         {
            $schools=GetMusicCoopSchools($school,"Vocal");
            $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND (";
            for($i=0;$i<count($schools);$i++)
            {
               $cursch=addslashes($schools[$i]);
               $sql2.="school='$cursch' OR ";
            }
            $sql2=substr($sql2,0,strlen($sql2)-4);
            $sql2.=") AND vm='x' ORDER BY school,last,first";
            $result2=mysql_query($sql2);
            while($row2=mysql_fetch_array($result2))
            {
               $vmstuds[name][$ix]="$row2[last], $row2[first] ($row2[school])";
               $vmstuds[id][$ix]=$row2[id];
               $ix++;
            }
         }
         else
         {
            $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND school='$school2' AND vm='x' ORDER by last,first";
            $result2=mysql_query($sql2);
            while($row2=mysql_fetch_array($result2))
            {
               $vmstuds[name][$ix]="$row2[last], $row2[first]";
               $vmstuds[id][$ix]=$row2[id];
               $ix++;
            }
         }
	 //ALL students:
	 $ix=0;
         if(IsCooping($school,"Vocal") && !IsCooping($school,"Instrumental"))
         {
            $schools=GetMusicCoopSchools($school,"Vocal");
            $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (((";
            for($i=0;$i<count($schools);$i++)
            {
               $cursch2=addslashes($schools[$i]);
               $sql2.="school='$cursch2' OR ";
            }
            $sql2=substr($sql2,0,strlen($sql2)-4);
            $sql2.=") AND vm='x') OR (school='$school2' AND im='x'))";
         }
         else if(IsCooping($school,"Instrumental") && !IsCooping($school,"Vocal"))
         {
            $schools=GetMusicCoopSchools($school,"Instrumental");
            $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (((";
            for($i=0;$i<count($schools);$i++)
            {
               $cursch2=addslashes($schools[$i]);
               $sql2.="school='$cursch2' OR ";
            }
            $sql2=substr($sql2,0,strlen($sql2)-4);
            $sql2.=") AND im='x') OR (school='$school2' AND vm='x'))";
         }
         else if(IsCooping($school,"Instrumental") && IsCooping($school,"Vocal"))
         {
            $schools=GetMusicCoopSchools($school,"Instrumental"); //would be same schools if used "Vocal"
            $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (";
            for($i=0;$i<count($schools);$i++)
            {
               $cursch2=addslashes($schools[$i]);
               $sql2.="school='$cursch2' OR ";
            }
            $sql2=substr($sql2,0,strlen($sql2)-4);
            $sql2.=") AND (vm='x' OR im='x')";
         }
         else //not co-oping
            $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND school='$school2' AND (vm='x' OR im='x')";
         $sql2.=" ORDER BY school,last,first";
	 $result2=mysql_query($sql2);
	 while($row2=mysql_fetch_array($result2))
	 {
	    $allstuds[name][$ix]="$row2[last], $row2[first]";
	    if(IsCooping($school,"Vocal") || IsCooping($school,"Instrumental"))
	       $allstuds[name][$ix].=" ($row2[school])";
	    $allstuds[id][$ix]=$row2[id];
	    $ix++;
	 }

	 //show existing solos:
	 $sql2="SELECT t1.*,t2.first,t2.last FROM muentries AS t1, eligibility AS t2 WHERE t1.studentid=t2.id AND t1.ensembleid='$row[id]' AND t1.schoolid='$schid' ORDER BY t1.soloorder";
	 //echo $sql2;
	 $result2=mysql_query($sql2);
	 $ix=0;
	 while($row2=mysql_fetch_array($result2))
	 {
   	    $place=$ix+1;
	    echo "<tr valign=top align=left><td width=$width>$place) ";
	    echo "<input type=hidden name=\"soloorder[$ix]\" value=\"$place\">";
            if(!$event[$ix]) $event[$ix]=$row2[event];
	    if(ereg("Instrumental",$category))
	    {
	       if($row2[other]=='x') $isother=1; 
	       else $isother=0;
	       if($row2[percussion]=='x') $isperc=1;
	       else $isperc=0;
	       if($event[$ix]=="Piano" && $row2[piano]=='y') { $isother=0; $isperc=0; }
	       if($isother==1 && $event[$ix]!='')
	       {
	          if($row2[piano]=='n') 
	          {
	   	     $other[$ix]=$event[$ix]; $event[$ix]="Other";
	          }
		  else
	          {
		     $other_2[$ix]=$event[$ix]; $event[$ix]="Other";
	          }
	       }
               else if($isperc==1 && $event[$ix]!='')
               {
                     $perc[$ix]=$event[$ix]; $event[$ix]="Percussion";
               }
	    }
	    if(!$studentid[$ix]) $studentid[$ix]=$row2[studentid];
	    if(!$accompanist[$ix]) $accompanist[$ix]=$row2[accompanist];
            if(ereg("Instrumental",$category))
	    {
               if($piano[$ix]=='n' && $event[$ix]=="Piano")       //Other checked, reset event to "" if Piano
                  $event[$ix]="";
               else if($piano[$ix]=='y')
                  $event[$ix]="Piano";
	       else if(!$piano[$ix] && $row2[strings]=='x') $piano[$ix]='s';
	       else if(!$piano[$ix] && $event[$ix]!='Piano' && $row2[strings]!='x') $piano[$ix]='n';
               if($event[$ix]=="Piano" && $row2[piano]=='y') $piano[$ix]='y';
	       /***** PIANO *****/
 	       echo "<input type=radio id=\"piano".$ix."0\" name=\"piano[$ix]\" value='y' onclick=\"Music.setupISolo('$ix');\"";
	       if($event[$ix]=='Piano') echo " checked";
	       echo ">Piano&nbsp;&nbsp;";
	       /***** STRING *****/
	       echo "<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."1\" value='s' onclick=\"Music.setupISolo('$ix');\"";
	       if($piano[$ix]=='s') echo " checked";
	       echo ">String";
               $otherix_2=count($stringens); //index of "Other" in <select>
               $othervar_2="other".$ix."_2";    //id of Other text box
 	       echo "&nbsp;<select name=\"event0[$ix]\" id=\"event".$ix."0\" onclick=\"if(this.options.selectedIndex==".$otherix_2.") { Utilities.getElement('".$othervar_2."').style.display=''; } else { ".$othervar_2.".style.display='none'; }\"";
               if($piano[$ix]!='s')
                  echo " style=\"display:none;visibility:hidden;\"";
               else
                  echo " style=\"display:'';visibility:visible;\"";
               echo "><option value=''>Please Select</option>";
               for($i=0;$i<count($stringens);$i++)
               {
                  echo "<option";
                  if($event[$ix]==$stringens[$i]) echo " selected";
                  echo ">$stringens[$i]</option>";
               }
               echo "</select>";
               if(!$other_2[$ix] || $other_2[$ix]=="") $other_2[$ix]="[Enter Instrument]";
               echo " <input type=text class=tiny size=15 name=\"other_2[$ix]\" id=\"$othervar_2\" value=\"".$other_2[$ix]."\" onfocus=\"if(this.value=='[Enter Instrument]') this.value='';\"";
               if(!($piano[$ix]=='s' && $event[$ix]=='Other'))
                  echo " style=\"display:none;\"";
               echo ">";
	       echo "&nbsp;&nbsp;";
	       /***** BAND *****/
	       echo "<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."2\" value='n' onclick=\"Music.setupISolo('$ix');\"";
	       if($piano[$ix]=='n') echo " checked";
	       echo ">Band";
	       $otherix=count($bandens); //index of "Other" in <select>
               $othervar="other".$ix;    //id of Other text box
	       $percix=$otherix-1; 	//index of "Percussion" in <select>
	       $percvar="perc".$ix;	//id of Percussion div containing textarea
	       $percabbrevvar="percabbrev".$ix; //id of Percussion readonly textarea
	       echo " <select name=\"event1[$ix]\" id=\"event".$ix."1\" onclick=\"if(this.options.selectedIndex==".$otherix.") { ".$othervar.".style.display=''; } else { ".$othervar.".style.display='none'; } if(this.options.selectedIndex==".$percix.") { ".$percvar.".style.display=''; ".$percabbrevvar.".style.display=''; } else { ".$percvar.".style.display='none'; ".$percabbrevvar.".style.display='none'; }\"";
	       if($piano[$ix]!='n')
		  echo " style=\"display:none;\"";
	       echo "><option value=''>Please Select</option>";
	       for($i=0;$i<count($bandens);$i++)
	       {
	          echo "<option";
	          if($event[$ix]==$bandens[$i]) echo " selected";
	          echo ">$bandens[$i]</option>";
	       }
	       echo "</select>";
	       //OTHER: textbox
	       if(!$other[$ix] || $other[$ix]=="") $other[$ix]="[Enter Instrument]";
               echo " <input type=text class=tiny size=15 name=\"other[$ix]\" id=\"other".$ix."\" value=\"$other[$ix]\" onfocus=\"if(this.value=='[Enter Instrument]') this.value='';\"";
	       if(!($piano[$ix]=='n' && $event[$ix]=='Other'))
	   	  echo " style=\"display:none;\"";
	       echo ">";
               //PERCUSSION: readOnly textarea and absolute-positioned DIV containing textarea
               //Percussion TEXTAREA:
	       $perc[$ix]=ereg_replace("<br>","\r\n",$perc[$ix]);
               echo "<textarea rows=2 cols=15 name=\"percabbrev[$ix]\" id=\"percabbrev".$ix."\"";
               if($event[$ix]=='Percussion')
                  echo " style=\"font-size:10px;float:right;display:'';\"";
               else
                  echo " style=\"font-size:10px;float:right;display:none;\"";
               echo " readOnly=true onClick=\"perc".$ix.".style.display='';\">$perc[$ix]</textarea>";   //onClick readOnly textbox, show DIV
               //Percussion DIV:
               echo "<div id=\"perc".$ix."\" style=\"z-index:100;position:absolute;top:30%;left:30%;background-color:#f0f0f0;padding:5px;width:auto;border:#333333 1px dotted;display:none;\">";
                   //Contents of DIV:
                   echo "<div style=\"float:right;\"><img src=\"../../close.gif\" border=0 onClick=\"perc".$ix.".style.display='none';\"></div><div style=\"clear:both;\"></div>";
                   echo "<b>Please list percussion instruments, including how many of each will be used for this solo:</b><br>";
                   echo "<textarea name=\"perc[$ix]\" id=\"perctext".$ix."\" rows=8 cols=60>$perc[$ix]</textarea>";
                   echo "<div style=\"float:right;\"><input type=button name=\"percbutton\" onClick=\"percabbrev".$ix.".style.display='';percabbrev".$ix.".value=perctext".$ix.".value;perc".$ix.".style.display='none';\" value=\"Save\"></div><div style=\"clear:both;\"></div>";
                   //(onClick Save, put text into readOnly textbox, close DIV)
                   //END Contents of DIV
               echo "</div>";
	       if($soloerr[$ix]==1)
		  echo "<br><div style=\"width:100%;text-align:right\"><font style=\"color:red\"><b>You must select the specific instrument!<br>If you select \"Other\" or \"Percussion,\" you must<br>list the instrument(s) in the provided text box.</b></font></div>";
	       if($event[$ix]=="Percussion")
		  echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOTE: <i>For larger percussion instruments, contact your contest host to see if specific instruments will be provided by the contest site.";
	       echo "</td>";
	    }//end if instrumental
	    else	//vocal
	    {
	       //echo "<input type=text class=tiny size=20 name=\"event[$ix]\" value=\"$event[$ix]\"></td>";  
	       echo "<select id=\"event".$ix."\" name=\"event[$ix]\"><option value=''>Choose Event</option>";
	       for($i=0;$i<count($vocalsolos);$i++)
	       {
	          echo "<option";
	  	  if($event[$ix]==$vocalsolos[$i]) echo " selected";
	          echo ">$vocalsolos[$i]</option>";
	       }
	       echo "</select>";
	       if(!$event[$ix] || $event[$ix]=='') 
	          echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style=\"color:red\"><b>Please select an event!</b></font>";
	       echo "</td>";
	    }
	    echo "<td><select name=\"studentid[$ix]\" id=\"studentid".$ix."\"><option value=''>Choose Student</option>";
            if(ereg("Instrumental",$category) && $piano[$ix]=='y')	//all students
            {
	       for($i=0;$i<count($allstuds[id]);$i++)
	       {
	           echo "<option value=\"".$allstuds[id][$i]."\"";
	           if($studentid[$ix]==$allstuds[id][$i]) echo " selected";
	           echo ">".$allstuds[name][$i]."</option>";
	       }
	    }
	    else if(ereg("Instrumental",$category))	//instrumental students only
   	    {
               for($i=0;$i<count($imstuds[id]);$i++)
               {
                   echo "<option value=\"".$imstuds[id][$i]."\"";
                   if($studentid[$ix]==$imstuds[id][$i]) echo " selected";
                   echo ">".$imstuds[name][$i]."</option>";
               }
	    }
	    else //vocal students only
 	    {
               for($i=0;$i<count($vmstuds[id]);$i++)
               {
                   echo "<option value=\"".$vmstuds[id][$i]."\"";
                   if($studentid[$ix]==$vmstuds[id][$i]) echo " selected";
                   echo ">".$vmstuds[name][$i]."</option>";
               }
	    }
	    echo "</select></td>";
	    if($accompanist[$ix]!='none')  $accomp=$accompanist[$ix];	
	    else $accomp="";
	    echo "<td><input type=text class=tiny size=20 name=\"accompanist[$ix]\" value=\"$accomp\">&nbsp;";
	    echo "<input type=checkbox name=\"noaccomp[$ix]\" value='x'";
	    if($accompanist[$ix]=='none') echo " checked";
	    echo ">None";
            if($accerr[$ix]==1)                  
	       echo "<br><font style=\"color:red\"><b>You must enter your accompanist for this entry or check \"none\"<br>to allow proper scheduling by your contest manager.</b></font>";
	    echo "</td>";
	    echo "</tr>";
	    $ix++;
	 }
	 //add one blank spot:
	 $place=$ix+1;
	 echo "<tr valign=top align=left><td>$place) ";
	 echo "<input type=hidden name=\"soloorder[$ix]\" value=\"$place\">";
	 if(ereg("Instrumental",$category))
	 {
	    /***** PIANO *****/
	    echo "<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."0\" onclick=\"Music.setupISolo('$ix');\" value='y'";
	    if($piano[$ix]=='y') echo " checked";
	    echo ">Piano&nbsp;";
	    /***** STRING *****/
	    echo "<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."1\" onclick=\"Music.setupISolo('$ix');\" value='s'";
	    if($piano[$ix]=='s') echo " checked";
	    echo ">String";
            $otherix_2=count($stringens); //index of "Other" in STRING <select>
            $othervar_2="other".$ix."_2";    //id of Other text box
            echo "<select name=\"event0[$ix]\" id=\"event".$ix."0\" onclick=\"if(this.options.selectedIndex==".$otherix_2.") { ".$othervar_2.".style.display=''; } else { ".$othervar_2.".style.display='none'; }\"";
	    if($piano[$ix]!='s')
	       echo " style=\"display:none;visibility:hidden;\"";
            else
	       echo " style=\"display:'';visibility:visible;\"";
	    echo "><option value=''>Please Select</option>";
            for($i=0;$i<count($stringens);$i++)
            {
               echo "<option";
	       if($event[$ix]==$stringens[$i]) echo " selected";
               echo ">$stringens[$i]</option>";
            }
            echo "</select>";
	    //STRING - OTHER
            if(!$other_2[$ix] || $other_2[$ix]=="") $other_2[$ix]="[Enter Instrument]";
            echo "<input type=text class=tiny name=\"other_2[$ix]\" id=\"other".$ix."_2\" value=\"$other_2[$ix]\" size=15 onfocus=\"if(this.value=='[Enter Instrument]') this.value='';\"";
            if($event[$ix]!='Other')
               echo " style=\"display:none;\"";
            echo ">";
	    /***** BAND *****/
	    echo "&nbsp;<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."2\" onclick=\"Music.setupISolo('$ix');\" value='n'";
	    if($piano[$ix]=='n') echo " checked";
            echo ">Band";
            $otherix=count($bandens); //index of "Other" in BAND <select>
            $othervar="other".$ix;    //id of Other text box
	    $percix=$otherix-1;		//index of "Percussion" in BAND <select>
	    $percvar="perc".$ix;	//id of Perc div containing textarea
	    $percabbrevvar="percabbrev".$ix; //id of Perc readonly textarea
	    echo " <select onclick=\"if(this.options.selectedIndex==".$otherix.") { ".$othervar.".style.display=''; } else { ".$othervar.".style.display='none'; } if(this.options.selectedIndex==".$percix.") { ".$percvar.".style.display=''; ".$percabbrevvar.".style.display=''; } else { ".$percvar.".style.display='none'; ".$percabbrevvar.".style.display='none'; }\" name=\"event1[$ix]\" id=\"event".$ix."1\"";	// IF Other selected, display Other textbox. IF Percussion selected, display Percussion pop up div
	    if($piano[$ix]=='n')
	       echo " style=\"display:;\"";
	    else
	       echo " style=\"display:none;\"";
	    echo "><option value=''>Please Select</option>";
	    for($i=0;$i<count($bandens);$i++)
	    {
	       echo "<option";
	       if($event[$ix]==$bandens[$i]) echo " selected";
	       echo ">$bandens[$i]</option>";
	    }
	    echo "</select>";
	    //Other textbox
	    if(!$other[$ix] || $other[$ix]=="") $other[$ix]="[Enter Instrument]";
	    echo "<input type=text class=tiny name=\"other[$ix]\" id=\"other".$ix."\" value=\"$other[$ix]\" size=15 onfocus=\"if(this.value=='[Enter Instrument]') this.value='';\"";
	    if($event[$ix]=='Other')
	       echo " style=\"display:'';\"";
	    else
	       echo " style=\"display:none;\"";
	    echo ">";
	    //PERCUSSION: readOnly textbox and absolute-positioned DIV containing textarea
            //Percussion TEXTBOX:
            echo "<textarea rows=2 cols=15 name=\"percabbrev[$ix]\" id=\"percabbrev".$ix."\"";
            if($event[$ix]=='Percussion')
               echo " style=\"float:right;font-size:10px;display:'';\"";
            else
                echo " style=\"float:right;font-size:10px;display:none;\"";
            echo " readOnly=true onClick=\"perc".$ix.".style.display='';\">$perc[$ix]</textarea>";   //onClick readOnly textbox, show DIV
	    //Percussion DIV:
            echo "<div id=\"perc".$ix."\" style=\"z-index:100;position:absolute;top:30%;left:30%;background-color:#f0f0f0;padding:5px;width:auto;border:#333333 1px dotted;";
            if($event[$ix]!='Percussion')
               echo "display:none;";
            echo "\">";
	        //Contents of DIV:
	    	echo "<div style=\"float:right;\"><img src=\"../../close.gif\" border=0 onClick=\"perc".$ix.".style.display='none';\"></div><div style=\"clear:both;\"></div>";
	 	echo "<b>Please list percussion instruments, including how many of each will be used for this solo:</b><br>";
                echo "<textarea name=\"perc[$ix]\" id=\"perctext".$ix."\" rows=8 cols=60>$perc[$ix]</textarea>";
		echo "<div style=\"float:right;\"><input type=button name=\"percbutton\" onClick=\"percabbrev".$ix.".style.display='';percabbrev".$ix.".value=perctext".$ix.".value;perc".$ix.".style.display='none';\" value=\"Save\"></div><div style=\"clear:both;\"></div>";
		//(onClick Save, put text into readOnly textbox, close DIV)
	        //END Contents of DIV
            echo "</div>";
	    echo "</td>";
	 }
	 else	//vocal
	 {
	    //echo "<input type=text class=tiny size=20 name=\"event[$ix]\"></td>";
            echo "<select name=\"event[$ix]\"><option value=''>Choose Event</option>";
            for($i=0;$i<count($vocalsolos);$i++)
            {
               echo "<option";
               echo ">$vocalsolos[$i]</option>";
            }
            echo "</select>";
            echo "</td>";
	 }
	 echo "<td><select id=\"studentid".$ix."\" name=\"studentid[$ix]\"><option value=''>Choose Student</option>";
         if(ereg("Instrumental",$category) && $piano[$ix]=='y')      //all students
         {
            for($i=0;$i<count($allstuds[id]);$i++)
            {
                echo "<option value=\"".$allstuds[id][$i]."\"";
                echo ">".$allstuds[name][$i]."</option>";
            }
         }
         else if(ereg("Instrumental",$category))     //instrumental students only
         {
            for($i=0;$i<count($imstuds[id]);$i++)
            {
                echo "<option value=\"".$imstuds[id][$i]."\"";
                echo ">".$imstuds[name][$i]."</option>";
            }
         }
         else //vocal students only
         {
            for($i=0;$i<count($vmstuds[id]);$i++)
            {
                echo "<option value=\"".$vmstuds[id][$i]."\"";
                echo ">".$vmstuds[name][$i]."</option>";
            }
         }
	 echo "</select></td>";
	 echo "<td><input type=text name=\"accompanist[$ix]\" size=20 class=tiny>&nbsp;";
	 echo "<input type=checkbox name=\"noaccomp[$ix]\" value='x'>None</td>";
	 echo "</tr>";
	 echo "<tr align=center><td colspan=3><font style=\"color:blue\"><b>(Click \"Save Solos\" to save your entries above AND to be able to add another solo)</b></font><br><input type=submit name=savesolos style=\"font-size:16pt\" value=\"Save Solos\"></td></tr>";
	 echo "</table><div class=alert name=debug id=debug style=\"display:none;\"></div></td></tr>";
      }
      echo "</table></td></tr>";
   }
   echo "</table></form>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
