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
echo GetHeader($session);


echo "<form method=post action=\"obsadmin2.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<br><table width=600 bordercolor=#000000 border=1 cellspacing=1 cellpadding=2>";
echo "<caption><b>";
if(!$num) $num=0;
echo "Show Me <select class=small name=sport><option value=''>Sport</option>";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value='$activity[$i]'";
   if($sport==$activity[$i]) echo " selected";
   echo ">".strtoupper($activity[$i])."</option>";
}
echo "</select> Officials who have <select name=ineq class=small>";
echo "<option";
if($ineq=='=') echo " selected";
echo ">=</option><option";
if($ineq=='<=') echo " selected";
echo "><=</option><option";
if($ineq=='>=') echo " selected";
echo ">>=</option></select> <input type=text size=2 class=tiny name=num value=$num>";
echo " Observations Submitted About Them ";
echo "<input type=submit name=submit value=\"Go\">";
echo "</b></caption>";

//create query
if($sport!='' && $sport)
{
   $sql="SELECT * FROM officials WHERE $sport='x' ORDER BY last,first,middle";
   $result=mysql_query($sql);
   $obstable=$sport."observe";
   echo "<tr align=center><td><b>Official</b></td><td><b>Observations</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT count(offid) FROM $obstable WHERE offid='$row[0]' GROUP BY offid";
      $result2=mysql_query($sql2);
      echo mysql_error();
      $row2=mysql_fetch_array($result2);
      if(($ineq=='=' && $row2[0]==$num) || ($ineq=='<=' && $row2[0]<=$num) || ($ineq=='>=' && $row2[0]>=$num))
      {
         echo "<tr align=left><td>$row[first] $row[middle] $row[last]</td>";
         if($row2[0]=="") 
            echo "<td align=center>NONE</td>";
         else
         {
	    echo "<td align=left>";
  	    $sql3="SELECT t2.offid,t2.obsid,t2.gameid,t2.home,t2.visitor,t2.dateeval,t1.name";
            if($sport=='bb') $sql3.=",t2.postseasongame";
	    $sql3.=" FROM logins AS t1,$obstable AS t2 WHERE t1.id=t2.obsid AND t2.offid='$row[0]' ORDER BY t2.gameid";
            $result3=mysql_query($sql3);
	    echo mysql_error();
            while($row3=mysql_fetch_array($result3))
 	    {
	       echo "<a class=small target=new href=\"".$sport."observe.php?session=$session&gameid=$row3[2]&offid=$row[0]&obsid=$row3[1]";
 	       if($sport=='bb' && $row3[postseasongame]==1) echo "&postseasongame=1";
    	       echo "\">$row3[name] (".date("m/d/Y",$row3[dateeval]).")</a><br>";
            }
	 }
         echo "</tr>";
      }
   }
}
echo "</table></form>";
?>
