<?php
//directory.php: Schools enter their staff's e-mails, phones, etc. here
//error_reporting(0);
require 'functions.php';
require 'variables.php';
require '../calculate/functions.php'; //Wildcard Functions

$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
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
$school2=preg_replace("/\'/","\'",$school);

//if user clicked on printable passcode list link:
if($passcodelist==1)
{
   echo $init_html;
   $today=date("M d, Y", time());
   echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<caption><b>$school's Passcode List</b><br>(as of $today)</caption>";
   echo "<tr align=center><th>Title</th><th>Name</th><th>Passcode</th></tr>";

   for($i=0;$i<count($staff);$i++)
   {
   $sql="SELECT * FROM logins WHERE school='$school2' AND sport LIKE '$staff[$i]%' AND level<8";
   $abb=GetActivityAbbrev2($staff[$i]);
   if($abb=="ad")
      $sql="SELECT * FROM logins WHERE school='$school2' AND level='2'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left><td>";
      if($row[5]==2) //AD
      {
	 echo "Athletic Director";
      }
      else echo $staff[$i];
      if($row[5]==3 && $row[4]!="Play Production" && $row[4]!="Journalism" && !ereg("Music",$row[4]) && $row[4]!="Debate" && $row[4]!="Speech" && $row[4]!="Orchestra" && $row[4]!="Assistant Athletic Director")	//coach of a sport
      {
	 echo " Coach";
      }
      else if($row[5]==3 && !preg_match("/Director/",$row[4]))	//director of an activity
      {
	 echo " Director";
      }
      echo "</td><td>$row[1]&nbsp;</td><td>$row[6]&nbsp;</td></tr>";
      if($staff[$i]=="Football")
	 $row=mysql_fetch_array($result);	//skip over next Football (there are 6/8 and 11 in DB)
   }
   }
   echo "</table></center>";
   echo $end_html;
   exit();
}

//if user clicked Save, update database (IF they have either checked NO CHANGE or they have MADE A CHANGE)
if($save  || $upload || $hiddensave)
{
   $error=0; $alerted=0;
   for($i=0;$i<count($staff);$i++)
   {
      $abb=GetActivityAbbrev2($staff[$i]);
      if($nochange[$abb]=="")	//did NOT check NO CHANGE--must have made a change to name/phone/email
      {
         if($oldname[$abb]==$name[$abb] && $oldphonepre[$abb]==$phonepre[$abb] && $oldphonepost[$abb]==$phonepost[$abb] && $oldphoneext[$abb]==$phoneext[$abb] && $oldemail[$abb]==$email[$abb] && (($oldnoemail[$abb]!='y' && $noemail[$abb]!='y') || $oldnoemail[$abb]==$noemail[$abb]))
         {
	    //everything is the same--give pop-up alert, if not already given:
	    if($alerted==0 && $level!=1)
	    {
	       $alerted=1;
?>
<script language="javascript">
alert("You must either make a change to each staff member's Name, Phone #, or E-mail, OR check the NO CHANGE box next to their entry, EVEN IF your school is no participating in that activity.\r\n\r\nPlease check your work and try again.");
</script>
<?php
	    }
            $error=1;
         }
         else	//change was made--put in time it was changed
 	 {
	    $nochange[$abb]=time();
         }
      }
      else	//ALREADY UPDATED or NO CHANGE checked--but if they DID make a change, change nochange value
      {
         if(!($oldname[$abb]==$name[$abb] && $oldphonepre[$abb]==$phonepre[$abb] && $oldphonepost[$abb]==$phonepost[$abb] && $oldphoneext[$abb]==$phoneext[$abb] && $oldemail[$abb]==$email[$abb] && (($oldnoemail[$abb]!='y'&& $noemail[$abb]!='y') || $oldnoemail[$abb]==$noemail[$abb])))
	    $nochange[$abb]=time();
      }
   }
}

$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$id=$row[0];
for($i=0;$i<count($staff);$i++)
{ 
if (($i>5 && $i<26) && ($i!=27 && $i!=28 && $i!=29)){
$sql_reg="SELECT school FROM ".GetSchoolsTable(GetActivityAbbrev2($staff[$i]))." WHERE mainsch=$id OR othersch1=$id OR othersch2=$id OR othersch3=$id";
$result_reg=mysql_query($sql_reg);
$row_reg=mysql_fetch_array($result_reg);
if (empty($row_reg)) $reg[]=GetActivityAbbrev2($staff[$i]);
if (empty($row_reg)) $registration[]=$staff[$i];
}
}
foreach ($reg as $key => $value){
    if ($value == 'orc') {
        unset($reg[$key]);
    }
}
//echo '<pre>'; print_r($reg); exit;
for($i=0;$i<count($staff);$i++)
{ 
if (($i>5 && $i<32) && ($i!=27 && $i!=28 && $i!=29 )){
 $sql_coop="SELECT * FROM ".GetSchoolsTable(GetActivityAbbrev2($staff[$i]))." WHERE  othersch1=$id OR othersch2=$id OR othersch3=$id";
$result_coop=mysql_query($sql_coop);
$row_coop=mysql_fetch_array($result_coop);
if (!empty($row_coop)) $co_op[]=GetActivityAbbrev2($staff[$i]);
if (!empty($row_coop)) $co_reg[]=$staff[$i];
if (!empty($row_coop)) $coop[GetActivityAbbrev2($staff[$i])][]=$row_coop[mainsch];
if (!empty($row_coop) && $row_coop[othersch1]!=$id && $row_coop[othersch1]!=0) $coop[GetActivityAbbrev2($staff[$i])][]=$row_coop[othersch1];
if (!empty($row_coop) && $row_coop[othersch2]!=$id && $row_coop[othersch2]!=0) $coop[GetActivityAbbrev2($staff[$i])][]=$row_coop[othersch2];
if (!empty($row_coop) && $row_coop[othersch3]!=$id && $row_coop[othersch3]!=0) $coop[GetActivityAbbrev2($staff[$i])][]=$row_coop[othersch3];
}
} 
$change=array();
for($i=0;$i<count($staff);$i++)
{
$staff_abb=GetActivityAbbrev2($staff[$i]);
if (!empty($passcode[$staff_abb]) && ($oldpasscode[$staff_abb]!=$passcode[$staff_abb])){
    $containsLetter  = preg_match('/[a-zA-Z]/',$passcode[$staff_abb]);
	$containsDigit   = preg_match('/\d/',$passcode[$staff_abb]);
		
    if(strlen($passcode[$staff_abb])<8 || $containsLetter==false || $containsDigit==false ){
    $passcode[$staff_abb]= $oldpasscode[$staff_abb]; 
	}else{
	$change[]= $staff_abb;
	}
  }
} 
//echo '<pre>'; print_r($registration);  
// echo '<pre>'; print_r($co_reg); 

if($save  || $upload || $hiddensave)
{   //echo '<pre>'; print_r($_POST);
	//foreach($email as $mail){ $emaill[]=$mail;} 
	//foreach($emaill as $key=>$value){ if(empty($value))$emailErr[]=$staff[$key];}
	//echo '<pre>'; print_r($emailErr);
	//$emailErr=array_diff($emailErr,$registration);
	//echo '<pre>'; print_r($emailErr); 
	//$emailErr=array_diff($emailErr,$co_reg);
	//echo '<pre>'; print_r($emailErr);
	
    foreach($phonearea as $parea){ $area[]=$parea;}
    foreach($phonepre as $ppre){ $pre[]=$ppre;}
    foreach($phonepost as $ppost){ $post[]=$ppost;}
	
	for($i=0;$i<count($staff);$i++)
	{ $phonenum[]=$area[$i].$pre[$i].$post[$i]; }
	
	foreach($phonenum as $key=>$value){ if(!empty($value) && !is_numeric($value))$phoneErr[]=$staff[$key]; }
	foreach($phonenum as $key=>$value){ if(!empty($value) && is_numeric($value) && strlen($value)<4)$lengthErr[]=$staff[$key]; }
	foreach($phonenum as $key=>$value){ if(!empty($value) && (!is_numeric($value) || strlen($value)<4))$lengthErr1[]=GetActivityAbbrev2($staff[$key]); }

	foreach($name as $s_name){ $staff_n[]=$s_name;} 
    foreach($staff_n as $key=>$value){ if(!empty($value) && strlen($value)<4)$nameErr[]=$staff[$key]; }
    foreach($name as $key=>$value){ if(!empty($value) && strlen($value)<4)$nameErr1[]=$key; }

/* 	if(empty($err) && empty($emailErr) && empty($phoneErr) && empty($lengthErr) && empty($nameErr) && empty($confirm)){

	  header("Location:directory.php?session=$session&err=$err&colortext=$colortext&confirm=1&school_ch=$school_ch");
      exit();
	} */
	
	      if(trim($name[$abb])!="" && !preg_match("/@/",$email[$abb]) && $noemail[$abb]!="y" && $abb!='trn' && $abb!='bp' && $abb!='scs' && $abb!='gc')
      {
	 //they must either enter an e-mail or check No E-mail
	 $err="email";
      }
	//if(empty($err) && empty($emailErr) && empty($phoneErr) && empty($lengthErr) && empty($nameErr) && ($confirm==1)){
	if(empty($err) && empty($emailErr) && empty($phoneErr) && empty($lengthErr) && empty($nameErr) ){

   $address1=preg_replace("/\'/","\'",$address1);
   $address1=preg_replace("/\"/","\'",$address1);
   $address2=preg_replace("/\'/","\'",$address2);
   $address2=preg_replace("/\"/","\'",$address2);
   $city_state=preg_replace("/\'/","\'",$city_state);
   $city_state=preg_replace("/\"/","\'",$city_state);
   $website=preg_replace("/\'/","\'",$website);
   $website=preg_replace("/\"/","\'",$website);
   $colors=preg_replace("/\'/","\'",$color_names);
   $colors=preg_replace("/\"/","\'",$colors);
   $colors=preg_replace("/,/","/",$colors);
   $colors=preg_replace("/;/","/",$colors);
   $mascot=preg_replace("/\'/","\'",$mascot);
   $mascot=preg_replace("/\"/","\'",$mascot);
   $conference=preg_replace("/\'/","\'",$conference);
   $conference=preg_replace("/\"/","\'",$conference);
   $phone_sch=$phone_sch_area."-".$phone_sch_pre."-".$phone_sch_post;
   $mainareacode=$phone_sch_area;
   $fax=$fax_area."-".$fax_pre."-".$fax_post;

   //get today's date for last update value
   $today=time();
   $sql="UPDATE headers SET nsaadist='$nsaadist',address1='$address1', address2='$address2', city_state='$city_state',zip='$zip', website='$website', color1='$color1', color2='$color2', color3='$color3', color_names='$colors', mascot='$mascot', conference='$conference', phone='$phone_sch', fax='$fax'";
   if($level!=1) $sql.=", dirupdate='$today'";
   $sql.=" WHERE school='$school2'";
   $result=mysql_query($sql);

   if(is_uploaded_file($_FILES['logo']['tmp_name']))	//user uploaded new logo
   {
      $newext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
      $logofile=preg_replace("/[^0-9a-zA-Z]/","",$school)."Logo.".$newext;
      if(!citgf_copy($_FILES['logo']['tmp_name'],"../images/$logofile"))	//replace old logo with new
      {
	 echo $init_html."<table><tr><td><div class='error'>ERROR: Could not copy to $logofile. Please report this issue to the NSAA.</div>".$end_html;
	 exit();
      }
      else
      {
         $sql="UPDATE headers SET logo='$logofile' WHERE school='$school2'";
         $result=mysql_query($sql);
         $Text="$school has uploaded a new logo!\r\n\r\nIt has been uploaded to nsaahome.org/images/$logofile.\r\n\r\nIt is also attached to this e-mail.\r\n\r\nThank you!";
         $Html="$school has uploaded a new logo!<br><br>It has been uploaded to <a href=\"https://secure.nsaahome.org/images/$logofile\">https://secure.nsaahome.org/images/$logofile</a><br><br>It is also attached to this e-mail.<br><br>Thank you!";
         $AttmFiles=array("../images/$logofile");
          updateLogoMail($school, $Html, $AttmFiles);
      }
   }

   //for each staff member, update listing in logins table
   for($i=0;$i<count($staff);$i++)
   {
      $abb=GetActivityAbbrev2($staff[$i]);
      if($abb=="ad")
	 $sql="SELECT * FROM logins WHERE level=2 AND school='$school2'";
      else
      {
	 $sql="SELECT * FROM logins WHERE level<8 AND school='$school2' AND (sport LIKE '%$staff[$i]%'";
	 if($abb=="fb")
	 {
	    $sql.=" OR sport LIKE '%Football 6/8%' OR sport LIKE '%Football 11%'";
	 }
	 $sql.=")";
      }
      $result=mysql_query($sql);

      $name[$abb]=preg_replace("/\'/","\'",$name[$abb]);
      $name[$abb]=preg_replace("/\"/","\'",$name[$abb]);

      $phone[$abb].=$phonearea[$abb]."-".$phonepre[$abb]."-".$phonepost[$abb]."-".$phoneext[$abb];

      $email[$abb]=preg_replace("/\'/","\'",$email[$abb]);
      $email[$abb]=preg_replace("/\"/","\'",$email[$abb]);
	  
       if(trim($name[$abb])!="" && !preg_match("/@/",$email[$abb]) && $noemail[$abb]!="y" && $abb!='trn' && $abb!='bp' && $abb!='scs' && $abb!='gc')
      {
	 //they must either enter an e-mail or check No E-mail
	 $err="email";
      } 
      else if($name[$abb]!="" && $noemail[$abb]=="y")
      {
	 //checked staff member as No Email
	 $email[$abb]="none";
      }
      $hours[$abb]=preg_replace("/\'/","\'",$hours[$abb]);
      $hours[$abb]=preg_replace("/\"/","\'",$hours[$abb]);

      $sendmail=0;
      if(mysql_num_rows($result)==0)	//INSERT
      {
	 //get level of new user
	 $newlevel=0;
	 if($abb=="ad") $newlevel=2;
	 else if($abb!="acd" && $abb!="su" && $abb!="pr" && $abb!="np" && $abb!="yb" && $abb!="scs" && $abb!="bp" && $abb!="trn" && $abb!='gc')
	    $newlevel=3;
	 else $newlevel=0;
	 if($abb!="ad" && $abb!="acd") $hours[$abb]="";
	 else
	 {
	    $hours[$abb]=$cellarea[$abb]."-".$cellpre[$abb]."-".$cellpost[$abb];
	 }
	 if($contact==$abb) $maincontact='y';
	 else $maincontact='';
	 $sql2="INSERT INTO logins (sport,school,name,phone,email,hours,level,passcode,maincontact,nochange";
	 $sql2.=") VALUES ('$staff[$i]','$school2','$name[$abb]','$phone[$abb]','$email[$abb]','$hours[$abb]','$newlevel','$passcode[$abb]','$maincontact','$nochange[$abb]')";
	 $text="$school has added a $staff[$i] coach:\r\n\r\nName: $name[$abb]\r\nE-mail: $email[$abb]\r\n\r\nThank You!";
	 $html=ereg_replace("\r\n","<br>",$text);
	 $sendmail=1;
      }
      else				//UPDATE
      {
	 if($contact==$abb) $maincontact='y';
	 else $maincontact='';
	 $row=mysql_fetch_array($result);
	 $oldname=$row['name']; $oldemail=$row['email'];
         $sql2="UPDATE logins SET nochange='$nochange[$abb]',";
	 $sql2.="maincontact='$maincontact',name='$name[$abb]', phone='$phone[$abb]', email='$email[$abb]',passcode='$passcode[$abb]'";
         if($level==1) $sql2.=",rulesmeeting='$rulesmeeting[$abb]'";
		 if (in_array($abb, $change))
		 $sql2.=", changepass=".time();
         if($abb=="ad" || $abb=="acd") 
	 {
            $cell[$abb]=$cellarea[$abb]."-".$cellpre[$abb]."-".$cellpost[$abb];
	    $sql2.=", hours='$cell[$abb]'";
	 }
         if($abb=="ad") $sql2.=" WHERE level=2 AND school='$school2'";
         else $sql2.=" WHERE sport LIKE '$staff[$i]%' AND school='$school2' AND level<8";
	 if($oldname!=$name[$abb] || $oldemail!=$email[$abb])
	 {
	    $text="$school has changed their $staff[$i] coach information:\r\n\r\nOld Name: $oldname\r\nOld E-mail: $oldemail\r\n\r\nNew Name: $name[$abb]\r\nNew E-mail: $email[$abb]\r\n\r\nThank You!";
	    $html=ereg_replace("\r\n","<br>",$text);
	    $sendmail=1;
	 }
      }
      $result2=mysql_query($sql2);
	 $sql3="SELECT duedate FROM misc_duedates WHERE sport='Directory'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 $duedate=$row3[0];
   }
   if($error!=1)
   {
      header("Location:directory.php?session=$session&err=$err&colortext=$colortext&school_ch=$school_ch&save_data=1");
      exit();
   }
}
}
echo $init_html_ajax;
?>
<script language="javascript">
function ErrorCheck()
{
   var errors="";
 
   if(!Utilities.getElement('contact0').checked && !Utilities.getElement('contact1').checked && !Utilities.getElement('contact2').checked && !Utilities.getElement('contact3').checked)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Main Contact:</b></font> You must select either your Superintendent, Principal, Athletic Director or Activities Director as your Main Contact Person.</td></tr>";
   //if(Utilities.getElement('suname').value=='' || Utilities.getElement('suemail').value=='')
   //   errors+="<tr align=left><td><font style=\"color:red\"><b>Superintendent:</b></font> You need to enter the name AND email for the Superintendent.</td></tr>";
   //if(Utilities.getElement('prname').value=='' || Utilities.getElement('premail').value=='')
   //   errors+="<tr align=left><td><font style=\"color:red\"><b>Principal:</b></font> You need to enter the name AND email for the Principal.</td></tr>";
   //if(Utilities.getElement('adname').value=='' || Utilities.getElement('ademail').value=='')
   //   errors+="<tr align=left><td><font style=\"color:red\"><b>Athletic Director:</b></font> You need to enter the name AND email for the Athletic Director.</td></tr>";
   if(errors!="")
   {
      Utilities.getElement('errordiv').style.display="";
      Utilities.getElement('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in your form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"Utilities.getElement('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {

	  Utilities.getElement('hiddensave').value="Save";
      document.forms.dirform.submit();
   }
}

function myFunction() {
    confirm("Press a button!");
}
</script>
<script language="JavaScript" src="pcjscolorchooser.js"></script>
</head>
<?php

if($print!=1 && $header!="no") 
{
   echo GetHeader($session);
   //if($err=="email")
    // echo "<font style=\"color:red\">For each staff member you enter, you must also either include their e-mail address or check \"No E-mail\" for that staff member!</font>";
}

//Get any info already in the database for this school:
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$id=$row[0];
$logo=$row[2];
$color1=$row[3];
$color2=$row[4];
$color3=$row[17];
$color_names=$row[5];
$mascot=$row[6];
$address1=$row[7];
$address2=$row[8];
$city_state=$row[9];
$zip=$row[10];
$phone_sch=$row[11];
$temp=explode("-",$phone_sch);
$phone_sch_area=$temp[0];
$phone_sch_pre=$temp[1];
$phone_sch_post=$temp[2];
$conference=$row[13];
$fax=$row[14];
$temp=explode("-",$fax);
$fax_area=$temp[0];
$fax_pre=$temp[1];
$fax_post=$temp[2];
$website=$row[15];
$nsaadist=$row["nsaadist"];
if(trim($row[16])=="") $update="NEVER";
else
   $update=date("F d, Y",$row[16]);

echo "<form name=dirform method=post enctype=\"multipart/form-data\" action=\"directory.php#staff\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=confirm value=\"$confirm\">";
if($print!=1 && $header!="no")
{
   if($level==1)
      echo "<a class=small href=\"diradmin.php?session=$session\">Return to Directory Admin</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"directory.php?session=$session&print=1&school_ch=$school_ch\" target=new>Printer-Friendly Version</a>";
   echo "&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"directory.php?session=$session&school_ch=$school_ch&passcodelist=1\" target=new>Passcode List (Printer-Friendly)</a>";
}
elseif($header=="no")
{
   echo "<a class=small href=\"directory.php?session=$session&school_ch=$school_ch&passcodelist=1\" target=new2>Passcode List (Printer-Friendly)</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"javascript:window.close();\">Close this Window</a>";
}
echo "<table cellspacing=0 cellpadding=0>";
echo "<caption><b><br>$school's School Directory Information:<br><font style=\"font-size:8pt\">Last Update: $update</font><br>";

//check if past directory due date
$sql="SELECT duedate FROM misc_duedates WHERE sport='Directory'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=explode("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

echo "</b><br></caption>";
//1st row: school info
echo "<tr align=center><td>";
echo "<table cellspacing=0 cellpadding=2 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<tr align=left><th align=left class=smaller>School:</th>";
echo "<td><font size=2>$school</font></td>";
echo "<td rowspan=11>";
echo "<table><tr align=center><td colspan=3><b>Current Logo:</b></td></tr><tr align=center><td width=75>&nbsp;</td><td width=150><img width='150px' border=1 bordercolor=#000000 src=\"/images/$logo\"></td><td width=75>&nbsp;</td></tr>";
if($print!=1) 
{
   echo "<tr align=left><td width=250 colspan=3>(If you have uploaded a new logo and you do not see the new logo above, try <a class=small href=\"javascript:location.reload()\">reloading</a> your page.)</td></tr><tr align=center><td colspan=3><br><b>Upload a New Logo:</b><br><input type=file name=logo></td></tr><tr align=left><td colspan=3 width=250>(NOTE: Your uploaded logo must be small enough to fit within a <b>100 pixel by 100 pixel square</b>, as shown in the upper left corner of this page, in order for your page headers to display correctly!!  Your logo must also be at <b>300dpi resolution</b>!)</td></tr>";
   echo "<tr align=center><td colspan=3><input type=submit name=upload value=\"Upload Logo Image\"></td></tr>";
   //echo "<tr align=center><td colspan=3><input type=button onclick=\"window.open('uploadlogo.php?session=$session','uploadlogo','width=350,height=250,location=no,menubar=no,titlebar=no');\" name=upload value=\"Upload New Logo\"></td></tr>";
}
echo "</table></td></tr>";
echo "<tr align=left><th align=left class=smaller>NSAA District:</th>";
if($print!=1)
{
   echo "<td><input type=text size=2 ";
   if($level!=1) echo "readOnly=true ";
   echo "name=nsaadist value=\"$nsaadist\">";
}
else
{
   echo "<td>$nsaadist</td></tr>";
}
echo "<tr align=left><th align=left class=smaller>Address:</th>";
if($print!=1)
{
   echo "<td><input type=text size=40 ";
   if($level!=1) echo "readOnly=true ";
   echo "name=address1 value=\"$address1\"><br>";
   echo "<input type=text size=40 name=address2 ";
   if($level!=1) echo "readOnly=true ";
   echo "value=\"$address2\"></td></tr>";
}
else echo "<td>$address1<br>$address2&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>City, State:</th>";
if($print!=1) 
{
   echo "<td><input type=text ";
   if($level!=1) echo "readOnly=true ";
   echo "name=city_state size=40 value=\"$city_state\"></td></tr>";
}
else echo "<td>$city_state&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>Zip Code:</th>";
if($print!=1) 
{
   echo "<td><input type=text name=zip ";
   if($level!=1) echo "readOnly=true ";
   echo "value=\"$zip\" size=10></td></tr>";
}
else echo "<td>$zip&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>Phone:</th>";
if($print!=1)
{
   echo "<td>(<input type=text name=phone_sch_area value=\"$phone_sch_area\" size=3>)&nbsp;";
   echo "<input type=text name=phone_sch_pre value=\"$phone_sch_pre\" size=3> - ";
   echo "<input type=text name=phone_sch_post value=\"$phone_sch_post\" size=4></td></tr>";
}
else echo "<td>$phone_sch&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>Fax:</th>";
if($print!=1)
{
   echo "<td>(<input type=text name=fax_area value=\"$fax_area\" size=3>)&nbsp;";
   echo "<input type=text name=fax_pre value=\"$fax_pre\" size=3> - ";
   echo "<input type=text name=fax_post value=\"$fax_post\" size=4></td></tr>";
}
else echo "<td>$fax&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>Website:</th>";
if($print!=1) echo "<td><input type=text name=website value=\"$website\" size=50></td></tr>";
else echo "<td>$website&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>Colors:</th>";
if($print!=1) 
{
   //echo "<td><input type=text size=40 name=color_names value=\"$color_names\"><br>(Please separate multiple colors with a \" <b>/</b> \")</td></tr>";
   echo "<td>";
   echo "<table>";
   echo "<tr align=left><td colspan=2>1) Please <b>select at least 2 but up to 3 school colors</b> by clicking on \"Change Color\" and<br>selecting a color from the palette.</td></tr>";
   echo "<tr align=left><td>Main Color:</td>";
   echo "<td><input type=text name=color1 id=\"color1\" value=\"$color1\" size=8><input type=button id=\"button1\" name=button onClick=\"PcjsColorChooser('button1','color1','value');\" value=\"Change Color\"></td></tr>";
   echo "<tr align=left><td>Secondary Color:</td>";
   echo "<td><input type=text name=color2 id=\"color2\" value=\"$color2\" size=8><input type=button id=\"button2\" name=button2 onClick=\"PcjsColorChooser('button2','color2','value');\" value=\"Change Color\"></td></tr>";
   echo "<tr align=left valign=top><td>Additional Color:<br>(will not show up on page headers)</td>";
   echo "<td><input type=text name=color3 id=\"color3\" value=\"$color3\" size=8><input type=button id=\"button3\" name=button3 onClick=\"PcjsColorChooser('button3','color3','value');\" value=\"Change Color\"></td></tr>";
   echo "<tr align=left valign=top><td colspan=2><br>2) Please enter your school colors <b>as you wish them to appear in the school directory</b>.</td></tr>";
   echo "<tr align=left><td colspan=2><input type=text name=color_names size=40 value=\"$color_names\"><br>(ex: \"Red/Black/White\")</td></tr>";
   echo "</table>";
   echo "</td></tr>";
}
else echo "<td>$color_names&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>Mascot:</th>";
if($print!=1) echo "<td><input type=text name=mascot value=\"$mascot\" size=40></td></tr>";
else echo "<td>$mascot&nbsp;</td></tr>";
echo "<tr align=left><th align=left class=smaller>Conference:</th>";
if($print!=1) echo "<td><input type=text name=conference value=\"$conference\" size=40></td></tr>";
else echo "<td>$conference&nbsp;</td></tr>";
echo "</table>";
echo "</td></tr>";

//2nd row is for staff info
echo "<tr align=center><td><a href=\"#staff\" name=\"staff\"></a>";
echo "<br><table width=100% cellspacing=0 cellpadding=1 frame=all rules=all style=\"border:#808080 1px solid;\">";
if($print!=1)
{
   echo "<caption align=left><p><font size=1>NOTES:<ul><li>Please do not type ALL CAPS in the Staff member field.</li>";
   echo "<li>Only the CERTIFIED coach needs to be listed in the coach's field.</li>";
   echo "<li>Only put the coach's phone number if it is different from the main school phone number.</li>";
   echo "<li><font color=\"red\"><b>Make sure you check the box in the \"NO CHANGE\" column if there is not a change in that staff member's name, phone number or e-mail address OR if your school is not participating in that activity.</b></font></li>";
   echo "<li><font color=\"red\"><b>Password must be at least 8 characters long and must include a combination of letters and numbers only. Otherwise Password will not be updated. </b></font></li>"; 
 if(!empty($emailErr)){
   foreach($emailErr as $err)
   echo "<li><font color=\"red\"><b style=\"background-color:yellow;font-size: 14px\">Please insert email address for $err</b></font></li>";
   }

   if(!empty($phoneErr)){
   foreach($phoneErr as $err)
   echo "<li><font color=\"red\"><b style=\"background-color:yellow;font-size: 14px\">Please insert numeric value for phone number of $err</b></font></li>";
   }
   if(!empty($lengthErr)){
   foreach($lengthErr as $err)
   echo "<li><font color=\"red\"><b style=\"background-color:yellow;font-size: 14px\">Please insert at least 4 digit for phone number of $err</b></font></li>";
   }
   if(!empty($nameErr)){
   foreach($nameErr as $err)
   echo "<li><font color=\"red\"><b style=\"background-color:yellow;font-size: 14px\">Please insert full name for $err</b></font></li>";
   }
   if($err=="email")
     echo "<li><font style=\"color:red\"\"><b  style=\"background-color:yellow;font-size: 14px\">For each staff member you enter, you must also either include their e-mail address or check \"No E-mail\" for that staff member!</b></font></li>";
/*    if($confirm)
     echo "<li><font style=\"color:red\"\"><b  style=\"background-color:yellow;font-size: 16px\">Please go back and double check that ALL listed emails are correct before hitting the save again</b></font></li>"; 
   echo "</ul></font></p></caption>"; */
   if($save_data=='1')
     echo "<li><font style=\"color:green\"\"><b  style=\"background-color:yellow;font-size: 14px\">Your details have been saved successfully</b></font></li>";
}
$colheaders="<tr align=center>";
if($print!=1) $colheaders.="<th class=smaller><font color=\"red\">NO CHANGE</font></th>";
//$colheaders.="<th class=smaller>Main<br>School<br>Contact</th><th class=smaller>Staff Member<br>(Designate if Dr.)</th><th class=smaller>Phone/Extension<br><font style=\"font-size:8pt\">(ex: 489-0386 x 123)</font></th><th class=smaller>E-mail</th><th class=smaller>Other</th><th class=smaller>Rules<br>Meeting</th>";
$colheaders.="<th class=smaller>Main<br>School<br>Contact</th><th class=smaller>Staff Member<br>(Designate if Dr.)</th><th class=smaller>Phone<br><font style=\"font-size:8pt\">(ex: xxx-xxx-xxxx)</font></th><th class=smaller>E-mail</th><th class=smaller>Other</th><th class=smaller>Rules<br>Meeting</th>";
if($print!=1) $colheaders.="<th class=smaller>Passcode</th>";
$colheaders.="<th class=smaller>Other Schools<br>(For coop school)</th>";
$colheaders.="</tr>";
echo $colheaders;

//get staff info from DB:
$sql="SELECT * FROM logins WHERE school='$school2' AND level<8 ORDER BY sport";
$result=mysql_query($sql);
$name=array(); $phone=array(); $hours=array(); $email=array(); $noemail=array();
$passcode=array(); $nochange=array();
$savedcorrectly=1;	//will be 0 if a nochange doesn't have a value for a staff member
while($row=mysql_fetch_array($result))
{  
   if($row[5]==2)	//athletic director
   {
      $row[4]="Athletic Director";
   }
   $sport=GetActivityAbbrev2($row[4]);
   if(preg_match("/Football/",$row[4])) $sport="fb";
   if($sport!="fb" || $name[$sport]=="")
   {
      $nochange[$sport]=$row[nochange];
      if($nochange[$sport]=='') $savedcorrectly=0;
      $name[$sport]=$row[1];
      $phone[$sport]=$row[8]; 
      $temp=explode("-",$phone[$sport]);
	  
      if(count($temp)==4 || strlen($temp[1])==3)
      {
         $phonearea[$sport]=$temp[0];
         //if(trim($phonearea[$sport])=="") $phonearea[$sport]=$mainareacode;
         $phonepre[$sport]=$temp[1];
         $phonepost[$sport]=$temp[2];
         $phoneext[$sport]=$temp[3];
      }
      else if(count($temp)==3)
      {
	 $phonearea[$sport]=$mainareacode;
	 $phonepre[$sport]=$temp[0];
	 $phonepost[$sport]=$temp[1];
	 $phoneext[$sport]=$temp[2];
      }
	  if(empty($phonearea[$sport]) && !empty($phoneext[$sport]))$phonearea[$sport]=$phone_sch_area;
	  if(empty($phonepre[$sport]) && !empty($phoneext[$sport]))$phonepre[$sport]=$phone_sch_pre;
	  if(empty($phonepost[$sport]) && !empty($phoneext[$sport]))$phonepost[$sport]=$phone_sch_post;
      $email[$sport]=$row[2];
      if($email[$sport]=="none")
      {
	 $noemail[$sport]="y";
	 $email[$sport]="";
      }
      else $noemail[$sport]="n";
      $hours[$sport]=$row[hours];
      if($sport=="ad" || $sport=="acd")
      {
	 $cellphone=explode("-",$hours[$sport]);
 	 $cellarea[$sport]=$cellphone[0];
	 $cellpre[$sport]=$cellphone[1];
	 $cellpost[$sport]=$cellphone[2];
      }
      $passcode[$sport]=$row[6];
      $rulesmeeting[$sport]=$row[rulesmeeting];
      $main[$sport]=$row[10];
   }
}

if (strpos($name[su],',') !== false){ 
$su = explode(",",$name[su]);
$name[su]=$su[0];
}
if (strpos($name[pr],',') !== false){ 
$pr = explode(",",$name[pr]);
$name[pr]=$pr[0];
}
if (strpos($name[ad],',') !== false){ 
$ad = explode(",",$name[ad]);
$name[ad]=$ad[0];
}

//get list of all schools for dropdown menu
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$ix=0;
$schools=array();
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}

//one row per staff member:
for($i=0;$i<count($staff);$i++)
{
   $staff_abb=GetActivityAbbrev2($staff[$i]);
   if(($i%11)==0 && $i!=0 && $print!=1) echo $colheaders;
   echo "<tr align=center>";
   if($print!=1)
   {
      //NO Change checkbox: must be checked OR there must be something in this staff member's field
      if($nochange[$staff_abb]=='x' || trim($nochange[$staff_abb])=='') 
      {
         if ($nochange[$staff_abb]!='x') echo "<td bgcolor=red>";
         else echo "<td>";
         echo "<input type=checkbox name=\"nochange[$staff_abb]\" value='x'";
         if($nochange[$staff_abb]=='x') echo " checked";
         if($savedcorrectly==1) echo " disabled";
         echo "></td>";
      }
      else	//UPDATED entry
      {
         echo "<td><font style=\"font-size:10px;\">Updated<br>".date("n/j/y",$nochange[$staff_abb])."</td>";
         echo "<input type=hidden name=\"nochange[$staff_abb]\" value=\"$nochange[$staff_abb]\">";
      }
   }
   if($i<=3)	//if Superindtendent, AD, Act Dir, or Principal
   {
      echo "<td><input type=radio name=contact id='contact".$i."' value=$staff_abb";
      if($main[$staff_abb]=='y') echo " checked";
      echo "></td>";
   }
   else echo "<td>&nbsp;</td>";
   echo "<th align=left class=smaller>$staff[$i]:<br>";
   if($print!=1) 
   {  //if(empty($email[$staff_abb]) && !empty($_POST[email][$staff_abb])) $email[$staff_abb]=$_POST[email][$staff_abb];
      if( !empty($_POST[email][$staff_abb])) $email[$staff_abb]=$_POST[email][$staff_abb];
      if( !empty($_POST[phonearea][$staff_abb])) $phonearea[$staff_abb]=$_POST[phonearea][$staff_abb];
      if( !empty($_POST[phonepost][$staff_abb])) $phonepost[$staff_abb]=$_POST[phonepost][$staff_abb];
      if( !empty($_POST[phonepre][$staff_abb])) $phonepre[$staff_abb]=$_POST[phonepre][$staff_abb]; 
      if( !empty($_POST[name][$staff_abb])) $name[$staff_abb]=$_POST[name][$staff_abb]; 
     // echo "<input type=text class=tiny size=25 id=\"".$staff_abb."name\" name=\"name[$staff_abb]\" value=\"$name[$staff_abb]\"";if (!empty($reg)){if (in_array($staff_abb, $reg))echo'style="background-color:#d3d3d3"  readonly'; }if (!empty($co_op)){if (in_array($staff_abb, $co_op))echo'style="background-color:#d3d3d3"  readonly'; }if (in_array($staff_abb, $nameErr1)){echo'style="border-color:red"'  ; }echo"></th><td>";
      echo "<input type=text class=tiny size=25 id=\"".$staff_abb."name\" name=\"name[$staff_abb]\" value=\"$name[$staff_abb]\"";if (!empty($reg)){if (in_array($staff_abb, $reg))echo'style="background-color:#d3d3d3"  readonly'; } if (in_array($staff_abb, $nameErr1)){echo'style="border-color:red"'  ; }echo"></th><td>";
	echo "<input type=hidden name=\"oldname[$staff_abb]\" value=\"$name[$staff_abb]\">";
      echo "<input type=text class=tiny maxlength=3 size=3 name=\"phonearea[$staff_abb]\"  value=\"$phonearea[$staff_abb]\" ";if (!empty($reg)){if (in_array($staff_abb, $reg))echo'style="background-color:#d3d3d3"  readonly'; } if (in_array($staff_abb, $lengthErr1)){echo'style="border-color:red"  '; } echo">-";
      echo "<input type=text class=tiny maxlength=3 size=3 name=\"phonepre[$staff_abb]\" value=\"$phonepre[$staff_abb]\"  ";if (!empty($reg)){if (in_array($staff_abb, $reg))echo'style="background-color:#d3d3d3"  readonly'; } if (in_array($staff_abb, $lengthErr1)){echo'style="border-color:red"  '; } echo">-";
	echo "<input type=hidden name=\"oldphonepre[$staff_abb]\" value=\"$phonepre[$staff_abb]\">";
      echo "<input type=text class=tiny size=4 maxlength=4 name=\"phonepost[$staff_abb]\" value=\"$phonepost[$staff_abb]\" ";if (!empty($reg)){if (in_array($staff_abb, $reg))echo'style="background-color:#d3d3d3"  readonly'; }  if (in_array($staff_abb, $lengthErr1)){echo'style="border-color:red"  '; } echo">";
        echo "<input type=hidden name=\"oldphonepost[$staff_abb]\" value=\"$phonepost[$staff_abb]\">";
      //echo "-<input type=text class=tiny size=3 name=\"phoneext[$staff_abb]\" value=\"$phoneext[$staff_abb]\"></td>";
	echo "<input type=hidden name=\"oldphoneext[$staff_abb]\" value=\"$phoneext[$staff_abb]\">";
      echo "<td align=left><input type=text size=25 class=tiny id=\"".$staff_abb."email\" name=\"email[$staff_abb]\" value=\"$email[$staff_abb]\" ";if (!empty($reg)){if (in_array($staff_abb, $reg))echo'style="background-color:#d3d3d3"  readonly'; } echo"><br>";
        echo "<input type=hidden name=\"oldemail[$staff_abb]\" value=\"$email[$staff_abb]\">";
      echo "<input type=checkbox name=noemail[$staff_abb] value='y'";
      if($noemail[$staff_abb]=="y") echo " checked";
      echo ">No E-mail</td>";
        echo "<input type=hidden name=\"oldnoemail[$staff_abb]\" value=\"$noemail[$staff_abb]\">";
   }
   else 
   {
      $phone[$staff_abb]="";
      if($phonepre[$staff_abb]!="")
	 $phone[$staff_abb].="(".$phonearea[$staff_abb].")".$phonepre[$staff_abb]."-".$phonepost[$staff_abb];
      if($phoneext[$staff_abb]!="")
	 $phone[$staff_abb].=" x".$phoneext[$staff_abb];
      echo "$name[$staff_abb]&nbsp;</td><td>$phone[$staff_abb]&nbsp;</td><td>$email[$staff_abb]&nbsp;</td>";
   }
   if($staff_abb=="ad" || $staff_abb=="acd")	
   {
      echo "<td align=left><b>Emergency Contact #:</b><br>";
      if($print!=1) 
      {
         echo "(<input type=text size=3 maxlength=3 class=tiny name=\"cellarea[$staff_abb]\" value=\"$cellarea[$staff_abb]\">)";
 	 echo "<input type=text size=3 maxlength=3 class=tiny name=\"cellpre[$staff_abb]\" value=\"$cellpre[$staff_abb]\">-";
	 echo "<input type=text size=4 maxlength=4 class=tiny name=\"cellpost[$staff_abb]\" value=\"$cellpost[$staff_abb]\">";
	 echo "</td>";
      }
      else 
      {
         if(trim($cellarea[$staff_abb])!='' || trim($cellpre[$staff_abb])!='' || trim($cellpost[$staff_abb])!='')
            echo "($cellarea[$staff_abb])$cellpre[$staff_abb]-$cellpost[$staff_abb]</td>";	
	 else
	    echo "</td>";
      }
   }
   else echo "<td>&nbsp;</td>";
   /*
   else if(ereg("Music",$staff[$i]))	//allow them to check main music person
   {
      echo "<td align=left><input type=radio name=music value=$staff_abb";
      if($hours[$staff_abb]=="main") echo " checked";
      echo ">Main Music Contact</td>";
   }
   else if($staff_abb=="np" || $staff_abb=="yb") //check main journalism person
   {
      echo "<td align=left><input type=radio name=journalism value=$staff_abb";
      if($name[jo]==$name[$staff_abb]) echo " checked";
      echo ">Main Journalism Contact</td>";
   }
   */
   if($staff[$i]=="Boys Golf" || $staff[$i]=="Girls Golf" || $staff[$i]=="Athletic Director" || $staff[$i]=="Speech" || $staff[$i]=="Unified Bowling" || $staff[$i]=="Play Production" || preg_match("/Football/",$staff[$i]) || $staff[$i]=="Softball" || $staff[$i]=="Volleyball" || preg_match("/Basketball/",$staff[$i]) || $staff[$i]=="Wrestling" || preg_match("/Swimming/",$staff[$i]) || $staff[$i]=="Baseball" || preg_match("/Soccer/",$staff[$i]) || preg_match("/Track & Field/",$staff[$i]) || preg_match("/Tennis/",$staff[$i]))
   {
      echo "<td align=center><input type=checkbox name=\"rulesmeeting[$staff_abb]\" value='x'";
      if($rulesmeeting[$staff_abb]=='x') echo " checked";
      if($level!=1) echo " disabled";
      echo "></td>";
   }
   else echo "<td>N/A</td>";
   if($passcode[$staff_abb]=='0') $passcode[$staff_abb]="";
   if($staff[$i]=="Activities Director" && $print!=1)
   {
      echo "<td>See AD<br>Listing</td>";
   }
   else if($print!=1 && $staff[$i]!='Guidance Counselor' && $staff[$i]!="Student Council Sponsor" && $staff[$i]!="AD Secretary" && $staff[$i]!="Assistant Athletic Director" && $staff[$i]!="Board President" && $staff[$i]!="Trainer" && $staff[$i]!="Superintendent" && $staff[$i]!="Principal")
      {echo "<td><input type=hidden name=\"oldpasscode[$staff_abb]\" value=\"$passcode[$staff_abb]\"><input type=text size=8 class=tiny name=passcode[$staff_abb] value=\"$passcode[$staff_abb]\" ";if (!empty($reg)){if (in_array($staff_abb, $reg))echo'style="background-color:#d3d3d3"  readonly'; } echo"></td>";}
   else if($print!=1)
      echo "<td>none</td>";
	  //echo '<pre>'; print_r(GetActivityAbbrev2($staff[$i]));
   if(in_array(GetActivityAbbrev2($staff[$i]),$co_op))
   {   
      $a= implode(",",$coop[GetActivityAbbrev2($staff[$i])]);
      echo "<td>";
	  foreach ($coop[GetActivityAbbrev2($staff[$i])] as $coop_school)
	  {echo GetSchool2($coop_school);echo '<br>';}
	  echo "</td>";
   }
   else 
      echo "<td>---</td>";	  
   echo "</tr>";
}

echo "</table></td></tr>";

if($print!=1 && $header!="no") 
{
   echo "<tr align=center><td><div id=\"errordiv\" class=\"searchresults\" style=\"left:400px;width:400px;display:none;\"></div></td></tr>";
}
if($level==1 && $print!=1)
      echo "<tr align=center><td><br><br><input type=submit name=\"save\" value=\"SAVE DIRECTORY INFO\"><br><br>";
else if($print!=1)
      echo "<tr align=center><td><br><br><input type=button onclick=\"ErrorCheck();\" name=save value=\"SAVE DIRECTORY INFO\"><input type=hidden name=\"hiddensave\" id=\"hiddensave\"><br><br>";
if($print!=1 && $header!='no')
{
   if($level==1)
      echo "<a class=small href=\"diradmin.php?session=$session\">Return to Directory Admin</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"directory.php?session=$session&print=1&school_ch=$school_ch\" target=new>Printer-Friendly Version</a>";
   echo "&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"directory.php?session=$session&school_ch=$school_ch&passcodelist=1\" target=new>Passcode List (Printer-Friendly)</a>";
   echo "&nbsp;&nbsp;&nbsp;<a href=\"welcome.php?session=$session\" class=small>Home</a></td></tr>";
}
elseif($header=="no")
{
   echo "<tr align=center><td><br>";
   echo "<a class=small href=\"directory.php?session=$session&school_ch=$school_ch&passcodelist=1\" target=new2>Passcode List (Printer-Friendly)</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"javascript:window.close();\">Close this Window</a>";
   echo "</td></tr>";
}

echo "</table>";
echo "</form>";

echo $end_html;
?>
<script>

</script>