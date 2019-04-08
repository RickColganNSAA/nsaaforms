<?php
/*********************************
adduser.php
Add new Assessor to Database
Created 8/24/09
Author: Ann Gaffigan
**********************************/

require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidAdmin($session))
{
   header("Location:../wrassessor.php?error=3");
   exit();
}

$titles=array("Certified Athletic Trainer","Exercise Physiologist","Health Educator","Licensed Practical Nurse","Nutritionist","Physical Therapist","Physician","Physician's Assistant","Registered Nurse");

if($hiddensave || $save)
{
   //ERROR CHECK: USER ID ALREADY IN USE?
   $sql="SELECT * FROM wrassessors WHERE userid='$userid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $useriderror=1;
   }
   else
   {
      $first=addslashes($first); $last=addslashes($last);
      $address=ereg_replace("\"","'",$address);
      $address=addslashes($address); $city=addslashes($city);
      $hphone=$harea."-".$hpre."-".$hpost;
      $wphone=$warea."-".$wpre."-".$wpost."-".$wext;
      $cphone=$carea."-".$cpre."-".$cpost;
      if($title=="Other" && $titleother!='') $title=addslashe($titleother);
      $sql="INSERT INTO wrassessors (datecreated,first,last,userid,password,email,hphone,wphone,cphone,bestphone,address,city,state,zip,title) VALUES ('".time()."','$first','$last','$userid','$password','$email','$hphone','$wphone','$cphone','$bestphone','$address','$city','$state','$zip','$title')";
      $result=mysql_query($sql);
      if(mysql_error()) $error=urlencode(mysql_error());
      else $error="";

      if($error=="")
      {
         $sql="SELECT * FROM wrassessors WHERE userid='$userid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         header("Location:manageuser.php?session=$session&added=1&userid=$userid");
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
   if(Utilities.getElement('first').value=="" || Utilities.getElement('last').value=="")
   {
      errors+="<tr align=left><td><b>NAME:</b> You must enter the first AND last name.</td></tr>";
      Utilities.getElement('nameerror').style.display='';
   }
   else
      Utilities.getElement('nameerror').style.display='none';
   if(Utilities.getElement('email').value=="")   
   {      
      errors+="<tr align=left><td><b>E-MAIL:</b> You must enter the e-mail address.</td></tr>";      
      Utilities.getElement('emailerror').style.display='';   
   }   
   else      
      Utilities.getElement('emailerror').style.display='none';
   if(Utilities.getElement('userid').value=="")  
   {     
      errors+="<tr align=left><td><b>USER ID:</b> You must enter the USER ID.</td></tr>";
      Utilities.getElement('useriderror').style.display='';
   }  
   else     
      Utilities.getElement('useriderror').style.display='none';
   if(Utilities.getElement('password').value=="")  
   {     
      errors+="<tr align=left><td><b>PASSWORD:</b> You must enter the PASSWORD.</td></tr>";
      Utilities.getElement('passworderror').style.display='';
   }  
   else     
      Utilities.getElement('passworderror').style.display='none';
   
   if(errors!="")
   {
      Utilities.getElement('errordiv').style.display="";
      Utilities.getElement('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in the form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"Utilities.getElement('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      Utilities.getElement('hiddensave').value="Save";
      document.forms.infoform.submit();
   }
}
</script>
<?php
echo GetAdminHeader($session);

//WR ASSESSOR UPDATE INFORMATION FORM
echo "<br><br><form method=post action=\"adduser.php\" name=\"infoform\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=hiddensave id=hiddensave>";

//FORM TITLE
echo "<table cellspacing=3 cellpadding=3><caption><a class=small href=\"admin.php?session=$session\">Wrestling Assessor Admin HOME</a><br><br><b>ADD NEW WRESTLING ASSESSOR ACCOUNT:</b><br>";
if($useriderror==1)
   echo "<div class=error>The userid \"$userid\" is already in use (See <a href=\"manageuser.php?session=$session&userid=$userid\" style=\"color:#e0e0e0;\">this account</a>). Please check that you are entering the correct User ID below.</div>";
else if($error!='')
   echo "<div class=error>UNEXPECTED ERROR: $error.<br><br>Please send a screenshot of this error to the programmer.</div>";
else
   echo "<i>Please complete the form below and click \"Add Account\" below.</i>";
echo "<br><br></caption>";
//NAME
echo "<tr align=left><td><b>Full Name (First, Last):</b><div id='nameerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value=\"$first\" type=text name=\"first\" id=\"first\" size=15>&nbsp;<input value=\"$last\" type=text name=\"last\" id=\"last\" size=25></td></tr>";
//ASSESSOR ID & PASSWORD
echo "<tr align=left><td><b>Assessor ID:</b><div id='useriderror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value=\"$userid\" type=text name=\"userid\" id=\"userid\" size=15></td></tr>";
echo "<tr align=left><td><b>Password:</b><div id='passworderror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value=\"$password\" type=text name=\"password\" id=\"password\" size=15></td></tr>";
//E-mail Address
echo "<tr align=left><td><b>Email Address:</b><div id='emailerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value=\"$email\" type=text name=\"email\" id=\"email\" size=40></td></tr>";
//Phone
echo "<tr valign=top align=left><td><b>Phone Number(s):</b><div id='phoneerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>";
echo "Home: (<input value=\"$harea\" type=text name=\"harea\" id=\"harea\" size=4 maxlength=3>)<input value=\"$hpre\" type=text name=\"hpre\" id=\"hpre\" size=4 maxlength=3>-<input value=\"$hpost\" type=text name=\"hpost\" id=\"hpost\" size=5 maxlength=4>&nbsp;<input type=radio name=\"bestphone\" value=\"hphone\"";
if($bestphone=="hphone") echo " checked";
echo "> Best phone number <br>Work: (<input value=\"$warea\" type=text name=\"warea\" id=\"warea\" size=4 maxlength=3>)<input value=\"$wpre\" type=text name=\"wpre\" id=\"wpre\" size=4 maxlength=3>-<input value=\"$wpost\" type=text name=\"wpost\" id=\"wpost\" size=5 maxlength=4>&nbsp;ext.<input value=\"$wext\" type=text name=\"wext\" id=\"wext\" size=4>&nbsp;<input type=radio name=\"bestphone\" value=\"wphone\"";
if($bestphone=="wphone") echo " checked";	
echo "> Best phone number <br>Cell: (<input value=\"$carea\" type=text name=\"carea\" id=\"carea\" size=4 maxlength=3>)<input value=\"$cpre\" type=text name=\"cpre\" id=\"cpre\" size=4 maxlength=3>-<input value=\"$cpost\" type=text name=\"cpost\" id=\"cpost\" size=5 maxlength=4>&nbsp;<input type=radio name=\"bestphone\" value=\"cphone\"";
if($bestphone=="cphone") echo " checked";
echo "> Best phone number </td></tr>";
//Mailing Address
echo "<tr align=left><td colspan=2><b>Mailing Address:</b><div id='addresserror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td></tr>";
echo "<tr align=left><td align=right><b>Address:</b></td><td><input value=\"$address\" type=text name=\"address\" id=\"address\" size=30></td></tr>";
echo "<tr align=left><td align=right><b>City, State Zip:</b></td><td><input value=\"$city\" type=text name=\"city\" id=\"city\" size=20>, <select name=\"state\" id=\"state\"><option value=''>~</option>";
for($i=0;$i<count($states);$i++)
{
   echo "<option value=\"$states[$i]\"";
   if($state==$states[$i]) echo " selected";
   echo ">$states[$i]</option>";
}
echo "</select>&nbsp;&nbsp;<input value=\"$zip\" type=text size=6 maxlength=5 name=zip id=zip></td></tr>";
//ERROR DIV (HIDDEN UNLESS ERROR)
echo "<tr align=center><td colspan=2><div id=\"errordiv\" class=\"searchresults\" style=\"left:45%px;width:400px;display:none;\"></div></td></tr>";
//What area qualifies them 
echo "<tr align=left><td colspan=2><b><br>Check the area below that qualifies you to be a registered assessor:</b></td></tr>";
echo "<tr align=left valign=top><td><div id='titleerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>";
for($i=0;$i<count($titles);$i++)
{
   echo "<input type=radio name=\"title\" id=\"title".$i."\" value=\"$titles[$i]\"";
   if($title==$titles[$i]) echo " checked";
   echo "> $titles[$i]<br>";
}
echo "<input type=radio name=\"title\" id=\"title".$i."\" value=\"Other\"";
if($title=="Other") echo " checked";
echo "> Other (please specify: <input type=text name=\"titleother\" value=\"$titleother\">)";
echo "</td></tr><tr align=center><td colspan=2><input class='fancybutton' type=button name=\"save\" id=\"save\" value=\"Create Account\" onClick=\"ErrorCheck();\"></td></tr></table></form>";

echo $end_html;
?>
