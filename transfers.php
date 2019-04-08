<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

$states=array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WV","WA","WI","WY","DC");

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch || $level!=1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
if(($school=="All" || trim($school)=="") && $level==1)
{
   header("Location:transfersadmin.php?session=$session&selectstud=1");
   exit();
}
$school2=ereg_replace("\'","\'",$school);

//Figure out what the last year archived was.  Will show link to that list of transfer students
$yearthis=date("Y"); $year1=$yearthis+1; $year0=$yearthis-1;
$archivedb="$db_name".$year0.$yearthis;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedb="$db_name".$year00.$year0;
   $curyear="$year0-$yearthis";
   $lastyear="$year00-$year0";
   $sql="SHOW DATABASES LIKE '$archivedb'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archive=0;
   else $archive=1;
}
else
{
   $archive=1;
   $curyear="$yearthis-$year1";
   $lastyear="$year0-$yearthis";
}

if($delete>0)
{
   $sql="DELETE FROM transfers WHERE id='$delete' AND school='$school2'";
   $result=mysql_query($sql);
}
if($save || $hiddensave)
{
   $ndeerror="";
   for($i=0;$i<5;$i++)
   {
      if($first[$i]!='' && $last[$i]!='' && $first[$i]!='[first]' && $last[$i]!='[last]')
      {
	 $first[$i]=addslashes($first[$i]);
         $last[$i]=addslashes($last[$i]);
	 $dob="$year[$i]-$month[$i]-$day[$i]";
         $otherschool[$i]=addslashes($otherschool[$i]);
         $type[$i]=addslashes($type[$i]);
         $otherschool[$i]=ereg_replace("\[name of school\]","",$otherschool[$i]);
         $othercity[$i]=addslashes($othercity[$i]);
         $othercity[$i]=ereg_replace("\[city\]","",$othercity[$i]);
	 $comments[$i]=addslashes($comments[$i]);
         $comments[$i]=ereg_replace("\[comments\]","",$comments[$i]);
	 $ndenumber[$i]=preg_replace("/[^0-9]/","",$ndenumber[$i]);
	 $sql="SELECT * FROM transfers WHERE ndenumber='$ndenumber[$i]'";
  	 $result=mysql_query($sql);
	 
	 $presentyear=date("Y"); $preyear1= $presentyear-1; $preyear2= $preyear1-1; $preyear3= $preyear2-1; $preyear4= $preyear3-1;

	 $sql16="SELECT * FROM nsaascores".$preyear2.$preyear1.".transfers WHERE ndenumber='$ndenumber[$i]'"; 
  	 $result16=mysql_query($sql16);
	 
	 $sql15="SELECT * FROM nsaascores".$preyear3.$preyear2.".transfers WHERE ndenumber='$ndenumber[$i]'";
  	 $result15=mysql_query($sql15);
	 
	 $sql14="SELECT * FROM nsaascores".$preyear4.$preyear3.".transfers WHERE ndenumber='$ndenumber[$i]'"; 
  	 $result14=mysql_query($sql14);
	 
	 if((mysql_num_rows($result)==0 && mysql_num_rows($result15)==0 && mysql_num_rows($result16)==0 && mysql_num_rows($result14)==0) || $level==1)
	 { 
	    if($level!=1 && ((strlen($ndenumber[$i])!=9 && strlen($ndenumber[$i])!=10) || $ndenumber[$i]=="000000000" || $ndenumber[$i]=="0000000000"))	//NDE # ERROR
	    {
	 	$ndeerror.="$first[$i] $last[$i]: INVALID NDE NUMBER. The Nebraska Department of Education number must be a 10-digit number. You must enter this number for each transfer student.<br><br>";
	    }
	    else
	    {
	       $sql="INSERT INTO transfers (school,first,last,dob,grade,ndenumber,otherschool,othercity,otherstate,publicprivate,comments,type) VALUES ('$school2','$first[$i]','$last[$i]','$dob','$grade[$i]','$ndenumber[$i]','$otherschool[$i]','$othercity[$i]','$otherstate[$i]','$publicprivate[$i]','$comments[$i]','$type[$i]')";
               //echo $sql."<br>".mysql_error()."<br>";
	       $result=mysql_query($sql);
	       if(mysql_error())
	          echo "ERROR: ".mysql_error()."<br>$sql<br>";
	    }
	 }
	 else
	 {
	    $ndeerror.="$first[$i] $last[$i] - NDE # $ndenumber[$i]: This number has already been entered for a student in our system. Each student has a unique NDE #, so please double-check the number for the following student, and re-enter their information.<br><br>";
	 }
      }
   }
   header("Location:transfers.php?session=$session&school_ch=$school_ch&ndeerror=".urlencode($ndeerror));
   exit();
}

echo $init_html_ajax;
?>
<script language="javascript">
function ErrorCheck()
{
   var errors="";
   for(var i=0;i<5;i++)
   {
      var first="first"+ i; var last="last"+ i; var month="month"+ i; var day="day"+ i; var year="year"+ i; var ndenumber="ndenumber"+ i;
      var grade="grade"+ i; var otherschool="otherschool"+ i; var othercity="othercity"+ i;
      var private="private"+ i; var public="public"+ i;
      if(Utilities.getElement(first).value!='' && Utilities.getElement(first).value!='[first]' && Utilities.getElement(last).value!='' && Utilities.getElement(last).value!='[last]')
      {
         var name=Utilities.getElement(first).value +" "+ Utilities.getElement(last).value;
         if(Utilities.getElement(month).options.selectedIndex==0 || Utilities.getElement(day).options.selectedIndex==0 || Utilities.getElement(year).options.selectedIndex==0)
            errors+="<tr align=left><td><font style=\"color:red\"><b>Date of Birth:</b></font> You need to enter the full birthdate for <b>"+ name +"</b>.</td></tr>";
         if(Utilities.getElement(grade).options.selectedIndex==0)
	    errors+="<tr align=left><td><font style=\"color:red\"><b>Next year’s grade:</b></font> You must select <b>"+ name +"</b>'s grade.</td></tr>";
	 var curndenumber=Utilities.getElement(ndenumber).value.replace(/[^0-9]/g, '');
	 if(Utilities.getElement('userlevel').value!='1' && ((curndenumber.match(/\d{9}/)==-1 && curndenumber.match(/\d{10}/)==-1) || (curndenumber.length!=9 && curndenumber.length!=10) || curndenumber=='0000000000' || curndenumber=='000000000'))
	    errors+="<tr align=left><td><font style=\"color:red\"><b>Nebraska Department of Education #:</b></font> You must enter the <u>10-DIGIT</u> Nebraska Department of Education number for <b>"+ name +"</b>.</td></tr>";
         if(Utilities.getElement(otherschool).selectedIndex==0)
            errors+="<tr align=left><td><font style=\"color:red\"><b>School Transferring From:</b></font> You must select the Nebraska school from which <b>"+ name +"</b> is transferring.</td></tr>";
      }
   }
   if(errors!="")
   {
      Utilities.getElement('errordiv').style.display="";
      Utilities.getElement('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in your form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"Utilities.getElement('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      Utilities.getElement('hiddensave').value="Save";
      document.forms.transferform.submit();
   }
}
</script>
</head>
<?php
echo $header;

echo "<form method=post action=\"transfers.php\" name=\"transferform\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=hiddensave id=hiddensave>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name='userlevel' id='userlevel' value=\"$level\">";

$duedate=GetMiscDueDate("Transfers");
$duedate2=date("F j, Y",strtotime($duedate));
$nextyear=date("Y",strtotime($duedate));

if($level==1)
   echo "<br><a class=small href=\"transfersadmin.php?session=$session\">Return to Transfer Students Admin</a><br>";

echo "<br><div style=\"position:relative;width:800px;text-align:center;\"><table class=nine cellspacing=0 cellpadding=5 frame=all rules=all style=\"width:800px;border:#808080 1px solid;\">";
echo "<caption><b>$nextyear INCOMING TRANSFER STUDENTS FOR <label style=\"color:#ff0000;\"><u>$school</u></label></b><br>(Due $duedate2)<br>";
if($archive==1)
{
   $temp=split("-",$lastyear); $prelast=$temp[1]-1; $prelast1=$temp[1]-2;
   //echo "<br><a target=\"_blank\" href=\"viewtransferlist.php?session=$session&database=$archivedb\">Your School's $temp[1] List of Transfer Students</a><br><br>";
   echo "<br><a target=\"_blank\" href=\"viewtransferlist.php?session=$session&database=$archivedb\">Your School's $nextyear List of Transfer Students</a><br><br>";
   //if($school=="Test's School") 
   echo "<a target=\"_blank\" href=\"viewfulltransferlist.php?session=$session\">Full List of $temp[1] Transfer Students (All Schools)</a><br><br>";
   echo "<a target=\"_blank\" href=\"viewfulltransferlist.php?session=$session&year=$prelast\">Full List of $prelast Transfer Students (All Schools)</a><br><br>";
   echo "<a target=\"_blank\" href=\"viewfulltransferlist.php?session=$session&year=$prelast1\">Full List of $prelast1 Transfer Students (All Schools)</a><br><br>";
}
echo "<table class=nine><tr align=left><td>";
if($ndeerror!='')
{
   echo "<div class=error>ERROR:<br><br>$ndeerror</div>";
}
if(!PastDue($duedate,0))
   echo "<i><b>INSTRUCTIONS:</b><br>Please complete the information below for each student who is transferring to your school from another school and click \"Add Transfers to List\".  You may continue to add students to this form until the due date above.  After that date, you will no longer be able to make any changes or additions to this form.</i>";
else	//form is past due; LOCK
   echo "<i>This form is <u>past due</u>.  The transfer students your school submitted to the NSAA for the $nextyear school year are shown below.</i>";
if($delete>0)
   echo "<br><br><font style=\"color:red\">The transfer record has been deleted.</font>";
echo "</td></tr></table></caption>";
$sql="SELECT * FROM transfers WHERE school='$school2' ORDER BY last,first";
$result=mysql_query($sql);
echo "<tr align=center><td colspan=2><b>Student Transferring</b></td>";
echo "<td><b>School Transferred From</b></td><td><b>Comments</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left valign=top><td width=25 align=center><a href=\"transfers.php?session=$session&delete=$row[id]&school_ch=$school_ch\" onclick=\"return confirm('Are you sure you want to delete this transfer record?');\">X</a></td><td><b>$row[first] $row[last]</b><br>";
   $date=split("-",$row[dob]);
   echo "DOB: $date[1]/$date[2]/$date[0]<br>";
   echo "Next year’s grade: $row[grade]<br>";
   echo "Nebraska Department of Education #: $row[ndenumber]";
   echo "</td>";
   echo "<td>$row[otherschool]<br><br>$row[type]</td>";
   echo "<td>$row[comments]</td>";
   echo "</tr>";
}
$ix=0;
//check if due date is past
if($level!=1 && PastDue($duedate,0)) $ix=5;
while($ix<5)
{
   echo "<tr align=center valign=top";
   if($ix%2==0) echo " bgcolor='#f0f0f0'";
   echo ">";
   echo "<td align=left colspan=2><b>Name:</b>&nbsp;";
   echo "<input type=text id=\"first".$ix."\" name=\"first[$ix]\" value=\"[first]\" class=tiny onfocus=\"if(this.value=='[first]') this.value='';\" size=15>&nbsp;";
   echo "<input type=text id=\"last".$ix."\" name=\"last[$ix]\" value=\"[last]\" class=tiny onfocus=\"if(this.value=='[last]') this.value='';\" size=15><br>";
   echo "<b>Date of Birth:&nbsp;</b><select id=\"month".$ix."\" name=\"month[$ix]\"><option value='00'>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option>$m</option>";
   }
   echo "</select>/<select id=\"day".$ix."\" name=\"day[$ix]\"><option value='00'>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option>$d</option>";
   }
   echo "</select>/<select id=\"year".$ix."\" name=\"year[$ix]\"><option value='0000'>YYYY</option>";
   $year1=date("Y")-20; $year2=date("Y")-12;
   for($i=$year1;$i<=$year2;$i++)
   {
      echo "<option>$i</option>";
   }
   echo "</select><br>";
   echo "<b>Next year’s grade:</b>&nbsp;<select id=\"grade".$ix."\" name=\"grade[$ix]\"><option value=''>~</option>";
   for($i=10;$i<=12;$i++)
   {
      echo "<option>$i</option>";
   }
   echo "</select><br>";
   echo "<b>Nebraska Department of Education #:</b>&nbsp;<input type=text name=\"ndenumber[$ix]\" id=\"ndenumber".$ix."\" value=\"[10-digit NDE #]\" maxlength=10 size=15 onFocus=\"if(this.value=='[10-digit NDE #]') this.value='';\">";
   echo "</td>";
   echo "<td align=left><b>School <span style=\"color:red\">(no out-of-state schools):</span></b>&nbsp;";
   //AS OF 1/21/14 - Using dropdown for Nebraska schools + "Nebraska Non-Member School" - no longer reporting out of state transfers
   echo "<select id=\"otherschool".$ix."\" name=\"otherschool[$ix]\"><option value=\"\">Select School or NE Non-Member School</option>";
   echo "<option value=\"NE Non-Member School\">NE Non-Member School</option>";
   $sql3="SELECT * FROM headers ORDER BY school";
   $result3=mysql_query($sql3);
   while($row3=mysql_fetch_array($result3))
   {
      echo "<option value=\"$row3[school]\">$row3[school]</option>";
   }
   echo "</select>";
	/*
   echo "<input type=text id=\"otherschool".$ix."\" name=\"otherschool[$ix]\" size=25 class=tiny value=\"[name of school]\" onfocus=\"if(this.value=='[name of school]') this.value='';\"><br>";
   echo "<b>City, State:</b>&nbsp;<input type=text id=\"othercity".$ix."\" name=\"othercity[$ix]\" size=15 class=tiny value=\"[city]\" onfocus=\"if(this.value=='[city]') this.value='';\">,&nbsp;";
   echo "<select id=\"otherstate".$ix."\" name=\"otherstate[$ix]\">";
   for($i=0;$i<count($states);$i++)
   {
      echo "<option>$states[$i]</option>";
   }
   echo "</select><br>";
   echo "<input type=radio id=\"public".$ix."\" name=\"publicprivate[$ix]\" value=\"Public\">Public&nbsp;&nbsp;";
   echo "&nbsp;<input type=radio id=\"private".$ix."\" name=\"publicprivate[$ix]\" value=\"Private\">Private";
	*/
   if($ix==2)
   {
      echo "<div id=\"errordiv\" class=\"searchresults\" style=\"left:200px;width:400px;display:none;\"></div>";
   }
   echo "<br><br><span id=\"type".$ix."\"><b>Type school attended here:</b><br><input type=text id=\"typee".$ix."\" name=\"type[$ix]\" size=48 class=tiny value=\"\" ></span><br>";
   echo "</td>";
   echo "<td align=left><b>Comments:</b><br><textarea name=\"comments[$ix]\" rows=5 cols=25 onfocus=\"if(this.value=='[comments]') this.value='';\">[comments]</textarea></td>";
   echo "</tr>";
   $ix++;
}
echo "</table></div>";
if(!PastDue($duedate,0) || $level==1)
   echo "<br><input type=button onclick=\"ErrorCheck();\" name=\"save\" value=\"Add Transfers to List\">";
echo "</form>";
echo $end_html;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
<?php for ($i=0; $i<5;$i++) { ?>
$("#type<?php echo $i;?>").hide();
<?php } ?>
<?php for ($i=0; $i<5;$i++) { ?>
$("#otherschool<?php echo $i;?>").change(function() { 
	if ( $(this).val() == "NE Non-Member School") {
    $("#type<?php echo $i;?>").show();
}
    else{
        $("#type<?php echo $i;?>").hide();
    }
});
<?php } ?>
</script>