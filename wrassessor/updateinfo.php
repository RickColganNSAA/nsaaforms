<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidAssessor($session))
{
   header("Location:../wrassessor.php?error=3");
   exit();
}

$titles=array("Certified Athletic Trainer","Exercise Physiologist","Health Educator","Licensed Practical Nurse","Nutritionist","Physical Therapist","Physician","Physician's Assistant","Registered Nurse");

if($hiddensave || $save)
{
   $first=addslashes($first); $last=addslashes($last);
   $address=addslashes($address); $city=addslashes($city);
   $hphone=$harea."-".$hpre."-".$hpost;
   $wphone=$warea."-".$wpre."-".$wpost."-".$wext;
   $cphone=$carea."-".$cpre."-".$cpost;
   if($title=="Other" && $titleother!='') $title=$titleother;
   $sql="UPDATE wrassessors SET first='$first',last='$last',email='$email',hphone='$hphone',wphone='$wphone',cphone='$cphone',bestphone='$bestphone',address='$address',city='$city',state='$state',zip='$zip',title='$title' WHERE session='$session'";
   $result=mysql_query($sql);

   //IF FIRST LOGIN, DIRECT TO POWERPOINT
   $sql="SELECT * FROM wrassessors WHERE session='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(IsPaid($row[userid]))
   {
      header("Location:updateinfo.php?session=$session&updated=1");
      exit();
   }
   else
   {
      header("Location:powerpoint.php?session=$session");
      exit();
   }
}

echo $init_html_ajax;
?>
<script language="javascript">
function ErrorCheck()
{
   var errors="";
   var ix=<?php echo count($titles); ?>;
   ix++;
   var titleselected=0;
   for(var i=0;i<ix;i++)
   {
      if(Utilities.getElement('title'+ i).checked) titleselected=1;
   }
   if(titleselected==0)
   {
      errors+="<tr align=left><td>You must check the area qualifying you to be a Registered Assessor.</td></tr>";
      Utilities.getElement('titleerror').style.display='';
   }
   else
      Utilities.getElement('titleerror').style.display='none'; 
   if(Utilities.getElement('first').value=="" || Utilities.getElement('last').value=="")
   {
      errors+="<tr align=left><td><b>NAME:</b> You must enter your first AND last name.</td></tr>";
      Utilities.getElement('nameerror').style.display='';
   }
   else
      Utilities.getElement('nameerror').style.display='none';
   if(Utilities.getElement('email').value=="")   
   {      
      errors+="<tr align=left><td><b>E-MAIL:</b> You must enter your e-mail address.</td></tr>";      
      Utilities.getElement('emailerror').style.display='';   
   }   
   else      
      Utilities.getElement('emailerror').style.display='none';
   var hphone = Utilities.getElement('harea').value + Utilities.getElement('hpre').value + Utilities.getElement('hpost').value;
   var wphone = Utilities.getElement('warea').value + Utilities.getElement('wpre').value + Utilities.getElement('wpost').value;
   var cphone = Utilities.getElement('carea').value + Utilities.getElement('cpre').value + Utilities.getElement('cpost').value;
   if(hphone.length!=10 && wphone.length!=10 && cphone.length!=10)
   {
      errors+="<tr align=left><td><b>PHONE:</b> You must enter at least one phone number, complete with area code.</td></tr>";
      Utilities.getElement('phoneerror').style.display='';
   }
   else
      Utilities.getElement('phoneerror').style.display='none';
   if(Utilities.getElement('address').value=="" || Utilities.getElement('city').value=="" || Utilities.getElement('state').selectedIndex==0 || Utilities.getElement('zip').value=="")
   {
      errors+="<tr align=left><td><b>ADDRESS:</b> You must enter your address, city, state and zip code.</td></tr>";
      Utilities.getElement('addresserror').style.display='';
   }
   else
      Utilities.getElement('addresserror').style.display='none';
   
   if(errors!="")
   {
      Utilities.getElement('errordiv').style.display="";
      Utilities.getElement('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in your form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"Utilities.getElement('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      Utilities.getElement('hiddensave').value="Save";
      document.forms.infoform.submit();
   }
}
</script>
<?php
echo GetAssessorHeader($session);

//WR ASSESSOR UPDATE INFORMATION FORM
echo "<br><br><form method=post action=\"updateinfo.php\" name=\"infoform\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=hiddensave id=hiddensave>";

//GET INFO CURRENT IN DATABASE FOR THIS USER
$sql="SELECT * FROM wrassessors WHERE session='$session'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

//FORM TITLE
if(IsPaid($row[userid]))
{
   echo "<table cellspacing=3 cellpadding=3><caption><b>ACCOUNT PROFILE FOR ".GetWRAUserName($row[userid]).":</b><br>";
   if($updated==1)
      echo "<div class=alert style='width:400px;text-align:center;'>Your changes have been saved.&nbsp;&nbsp;<a class=small href=\"index.php?session=$session\">Return Home</a></div>";
   else
      echo "<i>Please make any updates or changes and click \"Save Changes\" below.</i>";
   echo "<br><br></caption>";
}
else if($createdaccount && $row[userid]!='')
   echo "<table cellspacing=3 cellpadding=3><caption><b>STEP 1 of the Registration Process:</b><br><br><div class=\"alert\"><p>Thank you for creating a new account. Please double-check that the information below is COMPLETE and ACCURATE. All fields are required. Then click \"Update Info & Continue to Powerpoint.\"</div><br><br></caption>";
else if($row[userid]!='')
   echo "<table cellspacing=3 cellpadding=3><caption><b>STEP 1 of the Registration Process:<br><br>PLEASE UPDATE THE FOLLOWING INFORMATION - All Fields Required</b><br><br></caption>";
else	//FIRST TIME SIGNING UP
   echo "<table cellspacing=3 cellpadding=3><caption><b>PLEASE COMPLETE THE FOLLOWING INFORMATION - All Fields Required</b><br><br></caption>";

if($save || $hiddensave)
{
   echo "<tr align=center><td colspan=2><div class=alert><b>Thank you! Your information has been updated.</b>";
   echo "</div></td></tr>";
}

//NAME
echo "<tr align=left><td><b>Full Name (First, Last):</b><div id='nameerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input type=text name=\"first\" id=\"first\" value=\"$row[first]\" size=15>&nbsp;<input type=text name=\"last\" id=\"last\" value=\"$row[last]\" size=25></td></tr>";

//ASSESSOR ID & PASSWORD
if($row[userid]=="") $row[password]="";
echo "<tr align=left><td><b>NSAA & Track Wrestling Assessor ID:</b><div id='useriderror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>$row[userid]</td></tr>";
echo "<tr align=left><td><b>NSAA & Track Wrestling Password:</b><div id='passworderror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>$row[password]</td></tr>";
if($row[password]=='')
   echo "<tr align=left><td colspan=2><div class=alert style=\"width:500px;\"><B>NOTE:</b> If you do NOT have an NSAA & Track Wrestling Assessor ID and Password, you must contact Ron Higdon atthe NSAA with this information.</td></tr>";
else
   echo "<tr align=left><td colspan=2><i>(You cannot change your NSAA & Track Wrestling Assessor ID or Password.)</i></td></tr>";

//E-mail Address
echo "<tr align=left><td><b>Email Address:</b><div id='emailerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input type=text name=\"email\" id=\"email\" value=\"$row[email]\" size=40></td></tr>";

//Phone
echo "<tr valign=top align=left><td><b>Phone Number(s):</b><div id='phoneerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>";
	//Home Phone
$hphone=split("-",$row[hphone]);
echo "Home: (<input type=text name=\"harea\" id=\"harea\" value=\"$hphone[0]\" size=4 maxlength=3>)<input type=text name=\"hpre\" id=\"hpre\" value=\"$hphone[1]\" size=4 maxlength=3>-<input type=text name=\"hpost\" id=\"hpost\" value=\"$hphone[2]\" size=5 maxlength=4>&nbsp;<input type=radio name=\"bestphone\" value=\"hphone\"";
if($row[bestphone]=="hphone") echo " checked";
echo "> Best phone number<br>";
	//Work Phone
$wphone=split("-",$row[wphone]);
echo "Work: (<input type=text name=\"warea\" id=\"warea\" value=\"$wphone[0]\" size=4 maxlength=3>)<input type=text name=\"wpre\" id=\"wpre\" value=\"$wphone[1]\" size=4 maxlength=3>-<input type=text name=\"wpost\" id=\"wpost\" value=\"$wphone[2]\" size=5 maxlength=4>&nbsp;ext.<input type=text name=\"wext\" id=\"wext\" size=4 value=\"$wphone[3]\">&nbsp;<input type=radio name=\"bestphone\" value=\"wphone\"";
if($row[bestphone]=="wphone") echo " checked";	
echo "> Best phone number<br>";
	//Cell Phone
$cphone=split("-",$row[cphone]);
echo "Cell: (<input type=text name=\"carea\" id=\"carea\" value=\"$cphone[0]\" size=4 maxlength=3>)<input type=text name=\"cpre\" id=\"cpre\" value=\"$cphone[1]\" size=4 maxlength=3>-<input type=text name=\"cpost\" id=\"cpost\" value=\"$cphone[2]\" size=5 maxlength=4>&nbsp;<input type=radio name=\"bestphone\" value=\"cphone\"";
if($row[bestphone]=="cphone") echo " checked";
echo "> Best phone number";
echo "</td></tr>";

//Mailing Address
echo "<tr align=left><td colspan=2><b>Mailing Address:</b><div id='addresserror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td></tr>";
echo "<tr align=left><td align=right><b>Address:</b></td><td><input type=text name=\"address\" id=\"address\" value=\"$row[address]\" size=30></td></tr>";
echo "<tr align=left><td align=right><b>City, State Zip:</b></td><td><input type=text name=\"city\" id=\"city\" value=\"$row[city]\" size=20>, <select name=\"state\" id=\"state\"><option value=''>~</option>";
for($i=0;$i<count($states);$i++)
{
   echo "<option value=\"$states[$i]\"";
   if(trim($row[state])==$states[$i]) echo " selected";
   echo ">$states[$i]</option>";
}
echo "</select>&nbsp;&nbsp;<input type=text size=6 maxlength=5 name=zip id=zip value=\"$row[zip]\">";
echo "</td></tr>";

//ERROR DIV (HIDDEN UNLESS ERROR)
echo "<tr align=center><td colspan=2><div id=\"errordiv\" class=\"searchresults\" style=\"left:45%px;width:400px;display:none;\"></div></td></tr>";

//What area qualifies them 
echo "<tr align=left><td colspan=2><b><br>Check the area below that qualifies you to be a registered assessor:</b></td></tr>";
echo "<tr align=left valign=top><td><div id='titleerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>";
$titlefound=0;
for($i=0;$i<count($titles);$i++)
{
   echo "<input type=radio name=\"title\" id=\"title".$i."\" value=\"$titles[$i]\"";
   if($row[title]==$titles[$i]) { echo " checked"; $titlefound=1; }
   echo "> $titles[$i]<br>";
}
echo "<input type=radio name=\"title\" id=\"title".$i."\" value=\"Other\"";
if($row[title]!='' && $titlefound==0) 
{
   echo " checked"; $titleother=$row[title];
}
echo "> Other (please specify: <input type=text size=25 name=\"titleother\" value=\"$titleother\">)";
echo "</td></tr>";

if(IsPaid($row[userid]))
   $update="Save Changes";
else  
   $update="Update Info & Continue to PowerPoint >>";
echo "<tr align=center><td colspan=2><input class='fancybutton' type=button name=\"save\" id=\"save\" value=\"$update\" onClick=\"ErrorCheck();\"></td></tr>";

echo "</table>";
echo "</form>";

echo $end_html;
?>
