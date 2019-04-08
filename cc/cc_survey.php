<?php

require '../functions.php';
require '../variables.php';

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
//see if user cancelled
if($submit=="Cancel")
{
   header("Location:../welcome.php?session=$session");
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

echo $init_html;
echo $header;
echo "<br><br><table width=500><caption><b>Class D Cross-Country Questionnaire<hr></b></caption>";

//check that user is AD of class D CC school:
$level=GetLevel($session);
//$sql="SELECT school FROM cc_classd WHERE school='$school2'";
$sql="SELECT school FROM ccbschool WHERE mainsch='".GetSchoolID($session)."' AND class='D'";
$result=mysql_query($sql);
$sql2="SELECT school FROM ccgschool WHERE mainsch='".GetSchoolID($session)."' AND class='D'";
$result2=mysql_query($sql2);
if((mysql_num_rows($result)==0 && mysql_num_rows($result2)==0) || $level!=2)
{
   echo "<tr align=left><td>You are either not from a Class D Cross-Country school or you are not the Athletic Director of a Class D Cross-Country school.  <br><br>Only the Athletic Directors of Class D Cross-Country schools need to complete this form.  If you are part of a co-op, the head school's AD must complete this form.  Thank You.</th></tr>";
   echo "<tr align=center><td><br><a href=\"../welcome.php?session=$session\">Home</a></td></tr></table></center>";
   echo $end_html;
   exit();
}

//check if information was submitted
if($submit=="Save")
{
  
 /* if(!$reg_b || !$reg_g || !$full_b || !$full_g)
   {
      echo "<tr align=left><td><br>You must answer <b>both parts</b> to <b>both questions</b>.  Please <a href=\"javascript:history.go(-1)\">Go Back</a> and do so.  Thank you!</td></tr></table></center>";
      echo $end_html;
      exit();
   }
*/

	if(!$full_b || !$full_g)
   {
      echo "<tr align=left><td><br>You must answer  question.  Please <a href=\"javascript:history.go(-1)\">Go Back</a> and do so.  Thank you!</td></tr></table></center>";
      echo $end_html;
      exit();
   }
   //else save info in database
   
   $how_many=$_REQUEST['how_many'];
	$how_many_boys = ($full_b == 'y')? $_REQUEST['how_many_boys_y']:$_REQUEST['how_many_boys_n'];
    $how_many_girls =($full_g == 'y')? $_REQUEST['how_many_girls_y']:$_REQUEST['how_many_girls_n'];
//   if($full_b =='y') $how_many_boys='';
//   if($full_g=='y') $how_many_girls='';
   
   $sql2="SELECT * FROM cc_classd WHERE school='$school2'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
      $sql="UPDATE cc_classd SET reg_b='$reg_b', reg_g='$reg_g', full_b='$full_b', full_g='$full_g',how_many_girls='$how_many_girls',how_many_boys='$how_many_boys',how_many='$how_many' WHERE school='$school2'";
   else
      $sql="INSERT INTO cc_classd (reg_b,reg_g,full_b,full_g,school,how_many_girls,how_many_boys,how_many) VALUES ('$reg_b','$reg_g','$full_b','$full_g','$school2','$how_many_girls','$how_many_boys','$how_many')";

   $result=mysql_query($sql); 
   
   echo "<tr align=center><td><br>Thank you for completing our survey!<br><br><a href=\"../welcome.php?session=$session\">Home</a></td></tr>";
   echo "</table></center>";
   echo $end_html;
   exit();
}
$how_many='';
//if user is AD of class D CC school, ask questions:
   //get already-submitted info
   $sql="SELECT * FROM cc_classd WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $regb=$row[reg_b]; $regg=$row[reg_g];
   $fullb=$row[full_b]; $fullg=$row[full_g];
   $how_many=$row[how_many]; ;
   $how_many_boys=$row[how_many_boys]; ;
   $how_many_girls=$row[how_many_girls]; ;
echo "<form method=post action=\"cc_survey.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
if($row[schoolname]=="") $row[schoolname]=$row[school];
$sql2="SELECT duedate FROM misc_duedates WHERE sport='cc_classd'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$date=split("-",$row2[0]);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=center><td colspan=2><div class=alert>You have already filled out this form.  What you've entered is shown below.<br><br>If you need to make CHANGES to what you entered below, you may do so up through the <b>DUE DATE of ".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."</b>.</div></td></tr>";
}
echo "<tr align=left><td colspan=2><b>School:&nbsp;$row[schoolname]</b><br><br>The Class D Cross-Country district assignments are not made until after September 1.<br><br>With the information you provide us on this survey, we can better distribute those schools that will be competing in cross-country with a full team (at least 4 runners).<br><br>Please provide the following information based on your present situation:<br><br></td></tr>";
//Ques 1
/*
echo "<tr align=left><th colspan=2>1) We are or plan to be registered for:</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=center><td colspan=2>Boys Cross-Country</td></tr>";
echo "<tr align=center><td><input type=radio name=reg_b value='y'";
if($regb=='y') echo " checked";
echo "> Yes</td>";
echo "<td><input type=radio name=reg_b value='n'";
if($regb=='n') echo " checked";
echo "> No</td></tr></table></td>";
echo "<td><table><tr align=center><td colspan=2>Girls Cross-Country</td></tr>";
echo "<tr align=center><td><input type=radio name=reg_g value='y'";
if($regg=='y') echo " checked";
echo "> Yes</td>";
echo "<td><input type=radio name=reg_g value='n'";
if($regg=='n') echo " checked";
echo "> No</td></tr>";
echo "</table></td></tr>";
*/
//Ques 2

echo "<tr align=left><th colspan=2><br>1) We anticipate competing with a full team (<i>at least 4 runners</i>):</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=center><td colspan=2>Boys Cross-Country</td></tr>";
echo "<tr align=center><td><input type=radio name=full_b value='y' onclick='displaynewcloseb();'";
if($fullb=='y') {$display=2; echo " checked";}
echo "> Yes</td>";
echo "<td><input  type=radio name=full_b value='n' onclick='displaynewb();' ";
if($fullb=='n') {$display=1; echo " checked";}
echo "> No</td></tr>";?>
<tr align="center" id="newqestionb" style="<?php if($display!=1) echo "display:none;"; ?>">
	<td  colspan=2>If you don't have a full team <br /> how many boys will you have competing?: 
	<br />
	<select name="how_many_boys_n">
	<option value="0" <?php if ($how_many_boys==0) echo "selected"; ?>>0</option>
	<option value="1" <?php if ($how_many_boys==1) echo "selected"; ?>>1</option>
	<option value="2" <?php if ($how_many_boys==2) echo "selected"; ?>>2</option>
	<option value="3" <?php if ($how_many_boys==3) echo "selected"; ?>>3</option>
	</select>	
	</td>

</tr>
<tr align="center" id="newqestionby" style="<?php if($display!=2) echo "display:none;"; ?>">
	<td  colspan=2>How many boys will you have competing?: 
	<br />
	<select name="how_many_boys_y">
	<option value="4" <?php if ($how_many_boys==4) echo "selected"; ?>>4</option>
	<option value="5" <?php if ($how_many_boys==5) echo "selected"; ?>>5</option>
	<option value="6" <?php if ($how_many_boys==6) echo "selected"; ?>>6</option>
	</select>	
	</td>

</tr>
<?php echo "</table></td>";
?>

<?php $display=0;
echo "<td><table><tr align=center><td colspan=2>Girls Cross-Country</td></tr>";
echo "<tr align=center><td><input type=radio name=full_g value='y' onclick='displaynewcloseg();'";
if($fullg=='y') {$display=2; echo " checked";}
echo "> Yes</td>";
echo "<td><input type=radio name=full_g value='n' onclick='displaynewg();' ";
if($fullg=='n') {$display=1; echo " checked";}
echo "> No</td></tr>";
 

?>
<tr align="center" id="newqestiong" style="<?php if($display!=1) echo "display:none;"; ?>">
	<td colspan=2 >If you don't have a full team, <br />how many girls will you have competing?: <br />
	<select name="how_many_girls_n">
	<option value="0" <?php if ($how_many_girls==0) echo "selected"; ?>>0</option>
	<option value="1" <?php if ($how_many_girls==1) echo "selected"; ?>>1</option>
	<option value="2" <?php if ($how_many_girls==2) echo "selected"; ?>>2</option>
	<option value="3" <?php if ($how_many_girls==3) echo "selected"; ?>>3</option>
	</select>
	</td>

</tr>
<tr align="center" id="newqestiongy" style="<?php if($display!=2) echo "display:none;"; ?>">
	<td colspan=2 >How many girls will you have competing?: <br />
	<select name="how_many_girls_y">
	<option value="4" <?php if ($how_many_girls==4) echo "selected"; ?>>4</option>
	<option value="5" <?php if ($how_many_girls==5) echo "selected"; ?>>5</option>
	<option value="6" <?php if ($how_many_girls==6) echo "selected"; ?>>6</option>
	</select>
	</td>

</tr>
<script type="text/javascript">

    function displaynewg() {
	
       var e = document.getElementById('newqestiong');
       
          e.style.display = 'block';
		  
	   var f = document.getElementById('newqestiongy');
       
          f.style.display = 'none';
    }
function displaynewcloseg() {
	
       var e = document.getElementById('newqestiong');
       
          e.style.display = 'none';
		  
	  var f = document.getElementById('newqestiongy');
       
          f.style.display = 'block';
    }
	

</script>
<script type="text/javascript">

    function displaynewb() {
	
       var e = document.getElementById('newqestionb');
       
          e.style.display = 'block';
		  
	   var f = document.getElementById('newqestionby');
       
          f.style.display = 'none';
    }
function displaynewcloseb() {
	
       var e = document.getElementById('newqestionb');
       
          e.style.display = 'none';
		  
		var f = document.getElementById('newqestionby');
       
          f.style.display = 'block';  
    }

</script>

<?php 

echo "</table></td></tr>";
echo "<tr align=center><td colspan=2><br><br><input type=submit name=submit value=\"Save\"></td></tr>";

echo "</table></form>";

echo $end_html;
?>
