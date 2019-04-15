<?php
//hostappsearch.php: site surveys for NSAA side

require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"contractadmin");
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session) || ($level!=1 && level!=4))
{
   header("Location:index.php?error=1");
   exit();
}

//$hostacts=array("ba","bb_b","bb_g","cc","go_b","go_g","pp","sb","so","sp","tr","vb","wr");
//$hostactslong=array("Baseball","Boys Basketball","Girls Basketball","Cross-Country","Boys Golf","Girls Golf","Play Production","Softball","Soccer","Speech","Track & Field","Volleyball","Wrestling");
$sql="USE $db_name";
$result=mysql_query($sql);
$sql="SHOW TABLES LIKE 'hostapp_%'";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   $temp=split("hostapp_",$row[0]);
   $hostacts[$i]=$temp[1];
   $hostactslong[$i]=GetSportName($hostacts[$i]);
   $i++;
}
$sql="USE $db_name2";
$result=mysql_query($sql);

if($submit=="Go" && $interested)
{
   echo $init_html;
   echo GetHeader($session,"contractadmin");
   echo "<br>";
   $schools=array(); $i=0;
   if($level==1)
   {
   $sql="SELECT school FROM $db_name.logins WHERE level='4' ORDER BY name";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $schools[$i]=$row[0];
      $i++;
   }
   $sql="SELECT school FROM $db_name.headers ORDER BY school";
   }
   else
      $sql="SELECT school FROM $db_name.largeschools WHERE schgroup='$schgroup' ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $schools[$i]=$row[0];
      $i++;
   }
   echo "<a class=small href=\"hostappsearch.php?session=$session\">Apps to Host Advanced Search</a><br><br>";
   if($activity_ch[0]=="all" && ($level==1 && $school_ch[0]="all") && $interested!="x")
   {
      if($interested=="y") 
	 echo "<b>Schools who have applied to host the following activities:<br></b>";
      else 
	 echo "<b>Schools who have declined to host the following activities:<br></b>";
      for($i=0;$i<count($hostacts);$i++)
      {
	 echo "<br><table><caption>";
	 echo "<b>".$hostactslong[$i].":</b>";
	 echo "</caption>";
	 $table="hostapp_".$hostacts[$i];
	 $sql="SELECT school FROM $db_name.$table WHERE interested='$interested' ORDER BY school";
	 $result=mysql_query($sql);
	 $x=0;
	 while($row=mysql_fetch_array($result))
	 {
            if($x%4==0) echo "<tr align=left>";
	    echo "<td>";
	    echo "<a class=small href=\"../$table.php?nsaa=1&school_ch=$row[0]&session=$session\" target=new>$row[0]</a><br>";
	    echo "</td>";
	    if(($x+1)%4==0) echo "</tr>";
	    $x++;
	 }
	 echo "</table>";
      }
   }
   else if($activity_ch[0]=="all" && ($level==1 && $school_ch[0]=="all") && $interested=="x")
   {
      echo "<b>Schools who have not completed the applications for the following activities:<br></b>";
      for($ix=0;$ix<count($schools);$ix++)
      {
	 for($i=0;$i<count($hostacts);$i++)
	 {
	    echo "<br><table><caption>";
	    echo "<b>$hostactslong[$i]:</b></caption>";
	    $table="hostapp_".$hostacts[$i];
	    $schools2[$ix]=ereg_replace("\'","\'",$schools[$ix]);
	    $sql2="SELECT school FROM $db_name.$table WHERE school='$schools2[$ix]'";
	    $result2=mysql_query($sql2);
	    $x=0;
	    if(mysql_num_rows($result2)==0)
	    {
	       if($x%4==0) echo "<tr align=left>";
	       echo "<td>";
	       echo "<a class=small href=\"../$table.php?nsaa=1&school_ch=$schools[$ix]&session=$session\" target=new>$schools[$ix]</a><br>";
	       echo "</td>";
	       if(($x+1)%4==0) echo "</tr>";
	       $x++;
	    }
	    echo "</table>";
	 }
      }
   }
   else if($activity_ch[0]=="all" && $interested!="y")
   {
      if($school_ch[0]=="all" &&  $level==5)
      {
	 $x=0;
	 $sql="SELECT school FROM $db_name.largeschools WHERE schgroup='$schgroup' ORDER BY school";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
	 {
	    $school_ch[$x]=$row[0];
	    $x++;
	 }
      }
      if($interested=="y") echo "<b>Schools who have applied to host the following activities:<br></b>";
      else echo "<b>Schools who have declined to host the following activities:<br></b>";
      for($i=0;$i<count($hostacts);$i++)
      {
	 echo "<br><table><caption>";
	 echo "<b>$hostactslong[$i]:</b></caption>";
	 $table="hostapp_".$hostacts[$i];
	 $x=0;
	 for($j=0;$j<count($school_ch);$j++)
	 {
	    $school_ch2[$j]=ereg_replace("\'","\'",$school_ch[$j]);
	    $sql="SELECT school FROM $db_name.$table WHERE interested='$interested' AND school='$school_ch2[$j]'";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       if($x%4==0) echo "<tr align=left>";
	       echo "<td>";
	       echo "<a class=small href=\"../$table.php?nsaa=1&school_ch=$row[0]&session=$session\" target=new>$row[0]</a><br>";
	       echo "</td>";
	       if(($x+1)%4==0) echo "</tr>";
	       $x++;
	    }
	 }
	 echo "</table>";
      }
   }
   else if($activity_ch[0]=="all" && $interested=="x")
   {
      if($school_ch[0]=="all" &&  $level==5)
      {
         $x=0;
         $sql="SELECT school FROM $db_name.largeschools WHERE schgroup='$schgroup' ORDER BY school";
         $result=mysql_query($sql);
  	 while($row=mysql_fetch_array($result))
	 {
	    $school_ch[$x]=$row[0];
	    $x++;
	 }
      }
      echo "<b>Schools who have not completed the applications for the following activities:<br></b>";
      for($j=0;$j<count($hostacts);$j++)
      {
	 echo "<br><table><caption>";
	 echo "<b>".$hostactslong[$j].":</b></caption>";
	 $table="hostapp_".$hostacts[$j];
	 $x=0;
         for($i=0;$i<count($school_ch);$i++)
         {
	    $schools_ch2[$i]=ereg_replace("\'","\'",$schools_ch[$i]);
	    $sql="SELECT school FROM $db_name.$table WHERE school='$school_ch2[$i]'";
	    $result=mysql_query($sql);
	    if(mysql_num_rows($result)==0)
	    {
	       if($x%4==0) echo "<tr align=left>";
	       echo "<td>";
	       echo "<a class=small href=\"../$table.php?nsaa=1&school_ch=$school_ch[$i]&session=$session\" target=new>$school_ch[$i]</a><br>";
	       echo "</td>";
	       if(($x+1)%4==0) echo "</tr>";
	    }
	 }
	 echo "</table>";
      }
   }
   else if($school_ch[0]=="all" && $interested!="x")
   {
      $ct=0;
      if($interested=="y") echo "<b>Schools who have applied to host the following activities:<br></b>";
      else echo "<b>Schools who have declined to host the following activities:<br></b>";
      for($i=0;$i<count($activity_ch);$i++)
      {
	 echo "<br><table><caption>";
	 echo "<b>".strtoupper($activity_ch[$i]).":</b>";
         //if($activity_ch[$i]=='tr')
         $table="hostapp_".$activity_ch[$i];
            echo "<p><a href=\"../".$table.".php?session=$session&nsaa=1\" target=\"_blank\">Print ALL Applications to Host</a> (for schools interested in hosting only)</p>";
         echo "</caption>";
	 $x=0;
         for($j=0;$j<count($schools);$j++)
	 {
	    $schools2[$j]=addslashes($schools[$j]);
	    $sql="SELECT school FROM $db_name.$table WHERE school='$schools2[$j]' AND interested='$interested'";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       if($x%4==0) echo "<tr align=left>";
	       echo "<td>";
	       echo "<a class=small href=\"../$table.php?nsaa=1&school_ch=$schools[$j]&session=$session\" target=new>$schools[$j]</a><br>";
	       echo "</td>";
	       if(($x+1)%4==0) echo "</tr>";
	       $x++;
	       $ct++;
	    }
	 } 
      }
      echo "</table><b><br>Total Count: $ct<br></b>";
   }
   else if($school_ch[0]=="all" && $interested=="x")
   {
      echo "<b>Schools who have not submitted applications to host for the following activities:<br></b>";
      for($i=0;$i<count($activity_ch);$i++)
      {
	 $table="hostapp_".$activity_ch[$i];
	 echo "<br><table><caption>";
	 echo "<b>".strtoupper($activity_ch[$i]).":</b></caption>";
	 $x=0;
	 for($j=0;$j<count($schools);$j++)
	 {
	    $schools2[$j]=ereg_replace("\'","\'",$schools[$j]);
	    $sql="SELECT school FROM $db_name.$table WHERE school='$schools2[$j]' AND interested!=''";
	    $result=mysql_query($sql);
	    if(mysql_num_rows($result)==0)
	    {
	       if($x%4==0) echo "<tr align=left>";
	       echo "<td>";
	       echo "<a class=small href=\"../$table.php?nsaa=1&school_ch=$schools[$j]&session=$session\" target=new>$schools[$j]</a><br>";
	       echo "</td>";
	       if(($x+1)%4==0) echo "</tr>";
	       $x++;
	    }
	 }
	 echo "</table>";
      }
   }
   echo "<br><br><a href=\"hostappsearch.php?session=$session\" class=small>Apps to Host Advanced Search</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"contractadmin.php?session=$session\" class=small>Contracts Home</a>";
   echo $end_html;
   exit();
}

echo $init_html;
echo $header;

echo "<br><br><form method=post action=\"hostappsearch.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width=450><caption align=center><b>Applications to Host Advanced Search:<hr></b></caption>";
echo "<tr align=left valign=top><th align=left>Activity/Activities:</th>";
echo "<td><select multiple size=8 name=activity_ch[]>";
echo "<option value=\"all\" selected>All Activites";
for($i=0;$i<count($hostacts);$i++)
{
   echo "<option value=\"".$hostacts[$i]."\">".$hostactslong[$i]."</option>";
}
echo "</select></td></tr>";
echo "<tr valign=top align=center><td colspan=2>AND</td></tr>";
echo "<tr align=left valign=top><th align=left>School(s):</th>";
echo "<td><select multiple size=8 name=school_ch[]>";
echo "<option value=\"all\" selected>All Schools";
if($level==1) echo " & Colleges</option>";
if($level==1)
{
$sql="SELECT school FROM $db_name.logins WHERE level='4' ORDER BY name";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option>$row[0]";
}
$sql="SELECT school FROM $db_name.headers ORDER BY school";
}
else
   $sql="SELECT school FROM $db_name.largeschools WHERE schgroup='$schgroup' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option>$row[0]";
}
echo "</select></td></tr>";
echo "<tr align=left><td colspan=2>";
echo "<input type=radio selected name=interested value='y'>Schools <i><u><b>interested</u></i></b> in hosting the selected activities<br>";
echo "<input type=radio name=interested value='n'>Schools <i><u><b>NOT interested</u></i></b> in hosting the selected activities<br>";
echo "<input type=radio name=interested value='x'>Schools who have <i><u><b>NOT completed</i></u></b> the applications for the selected activities";
echo "<tr align=center><td colspan=2>";
if($submit=="Go" && !$interested)
   echo "<div class='error'>Please select one of the options above and click \"Go.\"</div><br>";
echo "<input type=submit name=submit value=\"Go\"></td></tr>";
echo "</table></form>";

echo $end_html;
?>
