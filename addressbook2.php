<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
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
if(!$schoolch || $level==5)
{
   $school=GetSchool($session);
}
else
{
   $school=$schoolch;
}
$school2=addslashes($school);

//get array of schools
$sql="SELECT school FROM headers ORDER BY school";
if($level==5)
   $sql="SELECT school FROM largeschools WHERE schgroup='$school2' ORDER BY school";
$result=mysql_query($sql);
$ix=0;
$schools=array();
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0]; $ix++;
}

$notfound="";
$missingemail=0;
if($addrecip)
{
   $onlyregistered='x';
   $csv="\"School\",\"Staff\",\"Name\",\"E-mail\"\r\n";
   $recipients="";
   if($school_list[0]=="All")
   {
      $school_list=$schools;
   }
   for($i=0;$i<count($school_list);$i++)
   {
      $school3=addslashes($school_list[$i]);
      if($staff_list[0]=="All")
      {
	 $staff_list=$staffs;
      }
      //if "onlyregistered" is checked, then only add info for non-coaches (such as AD's)
      //if a coach is listed for that school (meaning they were registered for that sport)
      $othercsv="";	//save info for insertion if necessary
      $othernotfound="";
      $otherrecipients="";
      $otherproceed=0;
      for($j=0;$j<count($staff_list);$j++)
      {
	 if($staff_list[$j]!="Home Page" && $staff_list[$j]!="Sup Fax" && $staff_list[$j]!="Orchestra")
	 {
	    if($staff_list[$j]=="Athletic Director") 
	       $sql="SELECT email, name, sport, school, level FROM logins WHERE school='$school3' AND level=2";
	    else
	       $sql="SELECT email,name,sport,school,level FROM logins WHERE school='$school3' AND sport LIKE '$staff_list[$j]%'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $abbrev=GetActivityAbbrev2($staff_list[$j]);
	    $proceed=1;
	    if(IsSportOrAct($abbrev)) //if staff member is coach/dir of an activity
	    {
	       //check registration
	       if($registration=='either') 
	       {
	          if(IsRegistered($school_list[$i],$abbrev,'x') || IsRegistered($school_list[$i],$abbrev,'w')) 
	          {
	   	     $proceed=1; $otherproceed=1;
	          }
	          else $proceed=0;
	       }
	       else if($registration=='x' || $registration=='w')
	       {
	          if(IsRegistered($school_list[$i],$abbrev,$registration)) 
		  {
		     $proceed=1; $otherproceed=1;
	          }
	          else $proceed=0;
	       }
	       else   //no registration constraint, proceed stays at 1
	       {
	          $proceed=1; $otherproceed=1;
	       } 
            }
            else if($onlyregistered=='x')	//save (non-coach) info for later addition
	    {
	       if(($row[email]=="none" || trim($row[email])==""))
	       {
		  if($row[sport]=='' && $row[level]=='2') $row[sport]="Athletic Director";
	      	  if($row[sport]!='')
		     $othernotfound.=$school_list[$i].": ".$staff_list[$j]."<br>";
	       }
	       else
	       {
	    	  if(!preg_match("/$row[email]/",$recipients))	
		  {
		     $otherrecipients.=$row['email'].", "; $otherproceed=1;
		     $othercsv.="\"$row[school]\",\"$staff_list[$j]\",\"$row[name]\",\"$row[email]\"\r\n";
		  }
	       }
	    }
	    if($proceed==1)
	    {
               if($onlyregistered=='x' && $otherproceed==1 && $othernotfound!='')
               {
                  //onlyreg is checked, this is a sport/activity, we have non-coach info to be added
                  $notfound.=$othernotfound; $othernotfound="";
               }
               if($onlyregistered=='x' && $otherproceed==1 && $otherrecipients!='' && $othercsv!='')
               {
                  $recipients.=$otherrecipients;  $otherrecipients='';
                  $csv.=$othercsv; $othercsv='';
               }
            }
	    if(($row[0]=="none" || trim($row[0])=="") && $proceed==1)
	    {
	       if($row[2]=="" && $row[4]==2) $row[2]="Athletic Director";
	       if($row[2]!="" && ($onlyregistered!='x' || IsSportOrAct($abbrev)))
	       {
		  $missingemail=1;
	          $notfound.=$school_list[$i].": ".$staff_list[$j]."<br>";
	       }
	       
	    }
	    else if($proceed==1)
	    {
	       if(!preg_match("/$row[0]/",$recipients) && ($onlyregistered!='x' || IsSportOrAct($abbrev))) 
    	       {
		  $recipients.=$row[0].", ";
		  $csv.="\"$row[school]\",\"$staff_list[$j]\",\"$row[name]\",\"$row[email]\"\r\n";
	       }
	    }
	 }
      }
      //$notfound=substr($notfound,0,strlen($notfound)-2);
      //$notfound.="<br>";
   }
   $open=fopen(citgf_fopen("attachments/recipients.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("attachments/recipients.csv");
   $temp=split(",",$recipients);
   $recipct=count($temp);
   //if(substr($recipients,strlen($recipients)-2,1)==',') $recipients=substr($recipients,0,strlen($recipients)-2);
}
else if($save)
{
  $recipients=substr($recipients,0,strlen($recipients)-2); 
?>
<script language="javascript">
window.opener.document.forms.emailform.email.value = "<?php echo $recipients; ?>";
window.close()
</script>
<?php
   exit();
}

echo $init_html;
echo GetHeader($session);
echo "<br><form method=post action=\"addressbook2.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=form value=\"$form\">";
echo "<input type=hidden name=school value=\"$school\">";
echo "<table><caption><b>NSAA Address Book<hr></b></caption>";
echo "<tr align=left valign=top><th width=150 align=left>School(s):<br><br><font size=1>(Hold down CTRL(PC) or Apple(Mac) to make multiple selections)</th><td>";
echo "<select name=school_list[] multiple size=5>";
echo "<option value='All'";
if($school_list[0]=="All") echo " selected";
echo ">All Schools</option>";
for($i=0;$i<count($schools);$i++)
{
   echo "<option";
   for($j=0;$j<count($school_list);$j++)
   {
      if($school_list[$j]==$schools[$i]) echo " selected";
   }
   echo ">$schools[$i]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left valign=top><th width=150 align=left>Staff Member(s):<br><br><font size=1>(Hold down CTRL(PC) or Apple(Mac) to make multiple selections)</th><td>";
echo "<select name=staff_list[] multiple size=5>";
echo "<option value='All'";
if($staff_list[0]=="All") echo " selected";
echo ">All Staff Members</option>";
for($i=0;$i<count($staffs);$i++)
{
   if($staffs[$i]!="Home Page" && $staffs[$i]!="Sup Fax" && $staffs[$i]!="Orchestra")
   {
      $staffmem=$staffs[$i];
      if($staffs[$i]=="") $staffmem="Athletic Director";
      echo "<option value=\"$staffmem\"";
      for($j=0;$j<count($staff_list);$j++)
      {
	 if($staff_list[$j]==$staffs[$i]) echo " selected";
      }
      echo ">";
      if($staffs[$i]=="Debate" || preg_match("/Music/",$staffs[$i]) || $staffs[$i]=="Journalism" || $staffs[$i]=="Play Production" || $staffs[$i]=="Speech")
	 $staffmem.=" Director";
      else if($staffs[$i]!="Trainer" && $staffs[$i]!="Superintendent" && $staffs[$i]!="Student Council Sponsor" && $staffs[$i]!="Principal" && $staffs[$i]!="Board President" && $staffs[$i]!="Activities Director" && $staffmem!="Athletic Director" && $staffmem!="Assistant Athletic Director" && $staffmem!="AD Secretary")
	 $staffmem.=" Coach";
      echo "$staffmem";
   }
}
echo "</select></td></tr>";
echo "<tr align=left><td colspan=2><b>Only show coaches' info if their school has an...</td></tr>";
echo "<tr align=center><td colspan=2><b><input type=radio name=registration value='x'";
if($registration=='x') echo " checked";
echo "><font style=\"font-size:10pt\">\"x\"</font> or a <input type=radio name=registration value='w'";
if($registration=='w') echo " checked";;
echo "><font style=\"font-size:10pt\">\"w\"</font>  or <input type=radio name=registration value='either'";
if($registration=="either") echo " checked";
echo "><font style=\"font-size:10pt\">either</font></td></tr>";
echo "<tr align=right><td colspan=2><b>...in their Registration field for that sport.</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td><input type=submit value=\"Add Recipient(s)\" name=\"addrecip\"><br>(Clicking this button will add the staff members you selected above to the textbox below.)</td></tr>";
echo "<tr align=center><td colspan=2><table width=400><tr align=left><th>Recipient(s): ";
if($recipct && $recipients!='') 
{
   echo "($recipct Total)";
   echo "<br><font style=\"font-size:8pt;font-weight:normal\">(Copy and paste the list of e-mails below into the Recipients box in your e-mail OR <a target=\"_blank\" class=small href=\"attachments/recipients.csv\">Download an Excel file with these Names and E-mails</a>)</font><br><br>";
   echo "<a href=\"#\" class=small onclick=\"recipients.value='';\">Reset Recipients List</a><br>";
}
echo "<textarea cols=70 rows=20 name=recipients>$recipients</textarea></th></tr>";
if($missingemail==1)
{
   echo "<tr align=left><td><b>The following e-mail addresses were not found in our system:</b><br>$notfound</td></tr>";
}
else
{
   echo "<tr align=left><td><b>[All e-mail addresses were found for the school(s) and staff member(s) specified.]</b></td></tr>";
}
echo "</table>";
echo "</td></tr>";
echo "<tr align=center><td colspan=2><a href=\"welcome.php?session=$session\">Home</a></td></tr>";

echo "</table></td></tr></table></form>";

echo $end_html;
?>
