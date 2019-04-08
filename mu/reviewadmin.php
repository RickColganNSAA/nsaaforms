<?php
/*********************************************
reviewadmin.php
Admin Report for NSAA 
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session) || GetLevel($session)!=1)
{
   header("Location:../index.php?error=1");
   exit();
}
$sport='mu';
$sportname=GetActivityName($sport);

if($delete>0)
{
   $sql="DELETE FROM contentreviews WHERE id='$delete' AND sport='$sport'";
   $result=mysql_query($sql);
}
if($reset==1)
{
	$date=date("F_j_Y"); 
	$time=date("g_ia");
	$tab='contentreviews'.'_'.$date.'_'.$time ;
	$sql="SELECT * FROM contentreviews WHERE sport='mu'";
	$result=mysql_query($sql);
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$tab.'.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('School','Administrator Signature','Date Submitted'));
	while ($row = mysql_fetch_assoc($result)) 
	{
	$sql1="SELECT school FROM headers  WHERE id=$row[schoolid]"; 
	$result1=mysql_query($sql1);
	$row1 = mysql_fetch_assoc($result1);
	$row[schoolid]=$row1[school];
	date_default_timezone_set('Canada/Saskatchewan');
	$row[datesub]=date("F j, Y",$row[datesub])." at ".date("g:ia T",$row[datesub]);
	unset($row[id]);
	unset($row[sport]);
	fputcsv($output, $row); 
	} exit;
}
$sql="SELECT * FROM contentreview WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$wording=$row[wording];

echo $init_html;
echo GetHeader($session);

echo "<br><form method=post action=\"reviewadmin.php\">
	<input type=hidden name=\"session\" value=\"$session\">";
echo "<h2>$sportname Proof of Licensing for Performance  Report</h2>";

echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption>";

//FILTER
echo "<div class=\"alert\"><h3>Toggle Report:</h3>";
if(!$schools || $schools=="") $schools="submitted";
if($schools=="submitted")
   echo "<p>Below are all schools who HAVE SUBMITTED the $sportname Proof of Licensing for Performance Form.</p><p><a href=\"reviewadmin.php?session=$session&schools=notsubmitted\">View schools who have NOT SUBMITTED the form</a></p>";
else
   echo "<p>Below are all schools who have NOT SUBMITTED the $sportname Content Review Form.</p><p><a href=\"reviewadmin.php?session=$session&schools=submitted\">View schools who HAVE SUBMITTED the form</a></p>";
echo "</div><br />";
echo "<a href=\"reviewadmin.php?session=$session&schools=submitted&reset=1\" ><input type=\"button\" value=\"Export list\"></a>&nbsp&nbsp";
echo "<a href=\"reviewadmin.php?session=$session&schools=submitted&reset=2\" onclick=\"return confirm('Do you want to reset below list?')\"><input type=\"button\" value=\"Reset below list\"></a><br /><br />";
if($delete>0)
{
   echo "<div class='error'><i>The form has been deleted.</i></div>";
}
echo "</caption>";

if($reset==2)
{
	$date=date("F_j_Y"); 
	$time=date("g_ia");
	$table='contentreviews'.'_'.$date.'_'.$time ;
	$sql="CREATE TABLE ".$table." LIKE contentreviews";
	$result=mysql_query($sql);
	echo mysql_error();

	$sql_1="SELECT * FROM contentreviews WHERE sport='mu'";
	$result_1=mysql_query($sql_1);
	while($row=mysql_fetch_array($result_1))
	{
	$sql_2="INSERT INTO ".$table." (sport,schoolid,adminsig,datesub) VALUES ('$row[sport]','$row[schoolid]','$row[adminsig]','$row[datesub]')";
	$result_2=mysql_query($sql_2);
	}

    $sql_3="DELETE FROM contentreviews WHERE sport='mu'";
    $result_3=mysql_query($sql_3);
	
	header("Location:reviewadmin.php?session=$session&schools=submitted");
}
if($schools=="submitted")
{
   echo "<tr align=center>";
   if(!$sort) $sort="t2.datesub DESC";
   if($sort=="t1.school ASC")
   {
      $cursort="t1.school ASC"; $curimg="arrowdown.png";
   }
   else if($sort=="t1.school DESC")
   {
      $cursort="t1.school ASC"; $curimg="arrowup.png";
   }
   else
   {
      $cursort="t1.school ASC"; $curimg="";
   }
   echo "<td><a class=\"small\" href=\"reviewadmin.php?session=$session&schools=$schools&sort=$cursort\">School";
   if($curimg!='') echo "<img src=\"../$curimg\" style=\"height:10px;margin:0 0 0 5px;\">";
   echo "</a></td>";
   echo "<td><b>Administrator Signature</b></td>";
   if($sort=="t2.datesub DESC")
   {
      $cursort="t2.datesub ASC"; $curimg="arrowup.png";
   }
   else if($sort=="t2.datesub ASC")
   {
      $cursort="t2.datesub DESC"; $curimg="arrowdown.png";
   }
   else 
   {
      $cursort="t2.datesub DESC"; $curimg="";
   }
   echo "<td><a class=\"small\" href=\"reviewadmin.php?session=$session&schools=$schools&sort=$cursort\">Date Submitted";
   if($curimg!='') echo "<img src=\"../$curimg\" style=\"height:10px;margin:0 0 0 5px;\">";
   echo "</a></td>";
   echo "<td><b>Delete</b></td>";
   echo "</tr>";
}
else
 echo "<tr align=center><td><b>School</b></td><td><b>Notify Administrator</b></td></tr>";

//MAKE QUERY
if($schools!="submitted")	//NOT SUBMITTEd
{
    $sql_submitted="SELECT * FROM contentreviews WHERE sport='mu'";
	$result_submitted=mysql_query($sql_submitted);
    $sql="SELECT * FROM headers";
	if(mysql_num_rows($result_submitted)!=0){
	$sql.=" WHERE id !=0";
	while($row_submitted=mysql_fetch_array($result_submitted))
	{
    $sql.=" AND id !=$row_submitted[schoolid]";
	}
	}
	$sql.=" ORDER BY school";
}
else	//SUBMITTED 
{
   $sql="SELECT t1.school,t2.* FROM headers AS t1, contentreviews AS t2 WHERE t1.id=t2.schoolid AND t2.sport='$sport' AND t2.datesub>0 ORDER BY $sort";
}
$result=mysql_query($sql);
echo mysql_error();
while($row=mysql_fetch_array($result))
{
   $proceed=1;
   if($schools=='notsubmitted')	//FILTER
   {
      if(IsRegistered2011($row[schoolid],$sport))
      {
	    $sql2="SELECT * FROM contentreviews WHERE sport='$sport' AND schoolid='$row[schoolid]'";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)==0) $proceed=1;
      }
   }
   else $proceed=1;
   if($proceed=1)
   {
      if($schools=='submitted')
      {
         echo "<tr align=left><td>$row[school]</td>";
         //echo "<td>$row[adminsig]</td><td>".date("F j, Y",$row[datesub])." at ".date("g:ia T",$row[datesub])."</td>
		 date_default_timezone_set('Canada/Saskatchewan');
         echo "<td>$row[adminsig]</td><td>".date("F j, Y",$row[datesub])." at ".date("g:ia T",$row[datesub])."</td>
	           <td><a href=\"reviewadmin.php?session=$session&schools=$schools&sort=$cursort&delete=$row[id]\" onClick=\"return confirm('Are you sure you want to delete this submitted form?');\" class=\"small\">Delete</a></td></tr>";
      }
      else
      {
         $sql2="SELECT email,name FROM logins WHERE school='".addslashes($row[school])."' AND level=2";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
		 $email[]= $row2[email];
         echo "<tr align=left><td>$row[school]</td>";
         echo "<td>$row2[name]: <a class=\"small\" href=\"mailto:$row2[email]\">$row2[email]</a></td></tr>";
      }
   }	//end if proceed
}

echo "</table>";
echo "</form>";
if($schools=='notsubmitted'){
echo "<br>";
echo "<br>";
echo "<b style=\"font-size:11px\">Email addresses of Notify Administrators are available altogether in below</b>";
echo "<br>";
echo "<br>";
echo "<textarea rows=\"16\" cols=\"90\">";
echo implode(", ",$email); 
echo "</textarea>";
}
echo $end_html;


?>
