<?php
//obs_query.php: Advanced Search Tool for observers list

require 'variables.php';
require 'functions.php';

$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

if($search)
{
   $city=ereg_replace("\'","\'",$city);
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);

   $sql="SELECT DISTINCT * FROM observers WHERE ";
   if(trim($city)!="") $sql.="city LIKE '$city%' AND ";
   if(trim($lastname)!="") $sql.="last LIKE '$lastname%' AND ";
   if(trim($first)!="") $sql.="first LIKE '$first%' AND ";
   if(trim($zip)!="") $sql.="zip LIKE '$zip%' AND ";
   if(trim($area)!="") $sql.="(homeph LIKE '$area%' OR cellph LIKE '$area%' OR workph LIKE '$area%' OR fax LIKE '$area%') AND ";
   if(trim($email)!="") $sql.="email LIKE '$email%' AND ";
   if($sport!="All Sports") $sql.="$sport='x' AND ";
   if(ereg("AND",$sql))
   {
      $sql=substr($sql,0,strlen($sql)-5);
   }
   else
   {
      $sql=substr($sql,0,strlen($sql)-7);
   }
  
   $sql2=ereg_replace(",",";",$sql);
   header("Location:observers.php?session=$session&query=$sql2&sport=$sport");
}

echo $init_html;
$header=GetHeader($session,"obshome");
echo $header; 
?>

<form method="post" action="obs_query.php">
<br><font style="font-size:9pt;">
<b>Observers Advanced Search:<br></b></font>
<font style=\"font-size:8pt\">
<i>Please indicate your search criteria below:<br>(You can put in just the first part of the criteria you are looking for,<br> such as "685" in the Zip field for all zip codes beginning with 685.)</i></font><br><br>
<input type=hidden name=session value=<?php echo $session; ?>>
<table cellspacing=0 cellpadding=2>
<?php
echo "<tr align=left><th class=smaller align=left>Sport(s):</th><td align=left>";
echo "<select class=small name=sport>";
echo "<option ";
if(!$sport || $sport=="All Sports")
   echo "selected";
echo ">All Sports";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value=\"$activity[$i]\"";
   if($sport==$activity[$i]) echo " selected";
   echo ">$act_long[$i]";
}
echo "</select></td></tr>";
echo "<tr align=left><th class=smaller align=left>Last Name:</th>";
echo "<td align=left><input type=text name=lastname size=30></td></tr>";
echo "<tr align=left><th class=smaller align=left>First Name:</th>";
echo "<td align=left><input type=text name=first size=30></td></tr>";
echo "<tr align=left><th class=smaller align=left>City:</th>";
echo "<td align=left><input type=text name=city size=30></td></tr>";
echo "<tr align=left><th class=smaller align=left>Zip:</th>";
echo "<td align=left><input type=text name=zip size=10></td></tr>";
echo "<tr align=left><th class=smaller align=left>Area Code:</th>";
echo "<td align=left><input type=text name=area size=5></td></tr>";
echo "<tr align=left><th class=smaller align=left>E-mail:</th>";
echo "<td align=left><input type=text name=email size=30></td></tr>";
?>
<tr align=center>
<td colspan=2><br>
<input type=submit name=search value="Search">
</td>
</tr>
</table>
</form>
</center>

</td>
</tr>
</table>
</body>
</html>
