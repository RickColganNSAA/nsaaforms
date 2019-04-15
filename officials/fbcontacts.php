<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo "<center><table width=100%><tr align=center><td>";
echo "<a class=small href='#' onclick='window.close();'>Close</a><br><br>";
echo "<table><caption><b>Football Contact Information, by City:</b></caption>";
//get list of cities for contacts from fbapps
$sql="SELECT DISTINCT(t1.city) FROM officials AS t1,fbapply AS t2 WHERE t1.id=t2.contact ORDER BY t1.city";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td><b>$row[city]:</b></td></tr>";
   echo "<tr align=left><td><table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
   echo "<tr align=center><td><b>Crew Chief<br>(click for Application)</b></td>";
   echo "<td><b>Contact</b></td><td><b>Contact Phone</b></td><td><b>Contact E-mail</b></td></tr>";
   $sql2="SELECT t1.last,t1.first,t2.* FROM officials AS t1,fbapply AS t2 WHERE t1.id=t2.contact AND t1.city='$row[city]' ORDER BY t1.last,t1.first";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      //get contact's contact info from officials table
      $sql3="SELECT email,homeph,workph,cellph FROM officials WHERE id='$row2[contact]'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      //get crew chief's name from officials table
      $sql4="SELECT first,last FROM officials WHERE id='$row2[offid]'";
      $result4=mysql_query($sql4);
      $row4=mysql_fetch_array($result4);

      echo "<tr valign=top align=left>";
      echo "<td><a class=small href=\"#\" onclick=\"window.open('fbapp.php?session=$session&givenoffid=$row2[offid]&header=no','fbapp','menubar=no,titlebar=no,scrollbars=yes,resizable=yes,height=600,width=600');\">$row4[first] $row4[last]</a></td>";
      echo "<td>$row2[first] $row2[last]</td>";
      echo "<td>";
      if($row2[homeph]=='x') 
	 echo "Home: (".substr($row3[homeph],0,3).")".substr($row3[homeph],3,3)."-".substr($row3[homeph],6,4)."<br>";
      if($row2[workph]=='x') 
	 echo "Work: (".substr($row3[workph],0,3).")".substr($row3[workph],3,3)."-".substr($row3[workph],6,4)."<br>";
      if($row2[cellph]=='x')
	 echo "Cell: (".substr($row3[cellph],0,3).")".substr($row3[cellph],3,3)."-".substr($row3[cellph],6,4)."<br>";
      if(trim($row2[otherph])!="")
	 echo "Other: (".substr($row2[otherph],0,3).")".substr($row2[otherph],3,3)."-".substr($row2[otherph],6,4);
      echo "</td>";
      echo "<td>";
      if($row2[email]=='x')
	 echo "$row3[email]<br>";
      if(trim($row2[otheremail])!="")
	 echo "Other: $row2[otheremail]";
      echo "</td></tr>";
   }
   echo "</table></td></tr>";
}
echo "</table><br><br><a class=small href=\"#\" onclick=\"javascript:window.close();\">Close</a>";

echo $end_html;
?>
