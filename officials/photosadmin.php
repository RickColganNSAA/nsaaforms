<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($delete && $delete!='' && $level==1)
{
   $sql="UPDATE officials SET photofile='',photoapproved='' WHERE id='$delete'";
   $result=mysql_query($sql);
   $sql="SELECT * FROM officials WHERE id='$delete'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(citgf_file_exists("photos/$row[photofile]"))
      citgf_unlink("photos/$row[photofile]");
}
if($save && $level==1)	//mark checked photos as APPROVED
{
   for($i=0;$i<count($offid);$i++)
   {
      $sql="UPDATE officials SET photoapproved='$approve[$i]' WHERE id='$offid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html_ajax."</head>";
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','photoform');">
<?php
echo GetHeader($session);

echo "<form method=post action=\"photosadmin.php\" name=\"photoform\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<br><table cellspacing=3 cellpadding=3 class=nine>";
echo "<caption><b>";
if($level==1) echo "Officials Profile Pictures Admin:</b>";
else echo "NSAA Officials Headshot Search:</b>";
if($save && $level==1)
   echo "<div class=alert style=\"width:350px\">The checkmarks you've changed have been saved.</div>";
if($delete && $delete!='' && $level==1)
   echo "<div class=alert style=\"width:350px\">The photo for <i>".GetOffName($delete)."</i> has been deleted.</div>";
echo "</caption>";
if(!$offset) $offset=0;
$limit=0;
if($nameid)
{
   $sql="SELECT * FROM officials WHERE id='$nameid'";
}
else if($sport!='')
{
   $sql="SELECT t1.* FROM officials AS t1,".$sport."off AS t2 WHERE t1.inactive!='x' AND t1.id=t2.offid AND photofile!=''";
   $sql.=" ORDER BY t1.last,t1.first";
   $temp=mysql_query($sql);
   $total=mysql_num_rows($temp);
   $sql.=" LIMIT $offset,16";
   $limit=16;
}
else if($level==1)
   $sql="SELECT * FROM officials WHERE photofile!='' AND photoapproved='' ORDER BY last,first";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && !$nameid && $sport=='' && $level==1)
   echo "<tr align=center><td><br>There are no profile pictures that need to be approved.<br><br></td></tr>";
else if(!$nameid && $sport=='' && $level==1)
{
   echo "<tr align=center><td colspan=4><b>You are viewing Profile Pictures that Need Approval.</b></td></tr>";
}
else if($level==1)
{
   echo "<tr align=center><td colspan=4>To view ALL Profile Pictures that Need Approval, <a href=\"photosadmin.php?session=$session\" class=small>Click Here</a>.</td></tr>";
}
//SEARCH BY SPORT
if($level==1)
{
   echo "<tr align=left><td colspan=4>To view pictures by SPORT, select a sport: <select name=\"sport\"><option value=''>Select a Sport</option>";
   $sql2="SHOW TABLES LIKE '%off'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $cur=preg_replace("/(off)/","",$row2[0]);
      echo "<option value='$cur'";
      if($sport==$cur) echo " selected";
      echo ">".GetSportName($cur)."</option>";
   }
   echo "</select> <input type=submit name=go value=\"Go\"></td></tr>";
}
else
{
   $obsid=GetObsID($session);

   //get sport(s) this observer is listed for
   $sql2="SELECT * FROM observers WHERE id='$obsid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $ct=0; $obs_sp=array(); $ix=0;
   for($i=0;$i<count($activity);$i++)
   {
      if($row2[$activity[$i]]=='x')
      {
         $sportname=$act_long[$i];
         if($sportname=="Swimming") $sportname.="/Diving";
         $obs_sp[$ix]=$activity[$i]; 
	 $obs_sp_long[$ix]=$sportname;
         $ix++;
      }
   }
   if(count($obs_sp)==1)
   {
      $sport=$obs_sp[0]; $cursport=$obs_sp_long[0];
   }
   else
   {
      echo "<tr align=left><td colspan=4>To view pictures by SPORT, select a sport: <select name=\"sport\"><option value=''>Select a Sport</option>";
      for($i=0;$i<count($obs_sp);$i++)
      {
         echo "<option value=\"".$obs_sp[$i]."\"";
         if($sport==$obs_sp[$i]) echo " selected";
         echo ">".$obs_sp_long[$i]."</option>";
      }
      echo "</select> <input type=submit name=go value=\"Go\"></td></tr>";
   }
}
//SEARCH BY OFFICIAL
echo "<tr align=left><td colspan=4><table><tr><td>To view the picture for a specific official, begin typing their name:</td>";
echo "<input type=hidden name=\"nameid\" id=\"nameid\">";
echo "<td><input type=text class=tiny size=35 name=\"name\" id=\"name\" value=\"$offname\" onkeyup=\"UserLookup.lookup('name',this.value,'$sport','official');\"><br><div class=\"list\" id=\"nameList\" style=\"position:absolute; z-index:100;\"></div></td></tr></table>";
echo "</td></tr>";
if($sport!='' && citgf_file_exists("vote_$sport.php") && $level==1)
{
   echo "<tr align=left><td colspan=4><a href=\"vote_".$sport.".php?session=$session&nsaa=1&sample=1\" target=\"_blank\">Preview Ballot for State ".GetSportName($sport)."</a></td></tr>";
}

/*** NAVIGATION ***/
$navhtml="<tr align=center><td colspan=4>";
if(($offset-16) >= 0) 	//SHOW PREVIOUS ARROW
{
   $prevoffset=$offset-16;
   $navhtml.="<div style=\"float:left;\"><a href=\"photosadmin.php?session=$session&sport=$sport&offset=$prevoffset\"><img src=\"../arrowleft.png\" style=\"width:20px;\"><br>Previous</a></div>";
}
$start=$offset+1;
$end=$offset+16;
if($end>$total) $end=$total;
$navhtml.="<i>Showing <b>$start</b> to <b>$end</b> of <b>$total</b> results. Click on photo to enlarge it.</i>";
if(($offset+16) < $total)    //SHOW NEXT ARROW
{
   $nextoffset=$offset+16;
   $navhtml.="<div style=\"float:right;\"><a href=\"photosadmin.php?session=$session&sport=$sport&offset=$nextoffset\"><img src=\"../arrowright.png\" style=\"width:20px;\"><br>Next</a></div>";
}
$navhtml.="<div style=\"clear:both;\"></div></td></tr>";
if($limit>0) echo $navhtml;
/*** END NAVIGTAION ***/

$ix=0;
while($row=mysql_fetch_array($result))
{
   if($ix%4==0) echo "<tr align=center valign=top>";
   echo "<td>";
   if($level==1)
   {
      echo "<table frame=all rules=all style=\"border:#808080 1px solid\" cellspacing=0 cellpadding=3>";
      echo "<tr align=center><td><b>Official</b>";
      echo "<br>(click to email)";
      echo "</td><td><b>Approve</b></td><td><b>Delete</b></td></tr>";
      echo "<tr align=center valign=center><td>";
   }
   if($level==1) 
   {
      echo "<a href=\"mailto:$row[email]\">".GetOffName($row[id])."</a><br>";
      $picw=75;
   }
   else 
   {
      echo "<h3>".GetOffName($row[id])."</h3>";
      $picw=100;
   }
   if(citgf_file_exists("photos/$row[photofile]") && $row[photofile]!='' && ($level==1 || $row[photoapproved]=='x'))
   {
      $found=1;
      echo "<a href=\"photos/$row[photofile]\" target=\"_blank\"><img src=\"photos/$row[photofile]\" style=\"width:".$picw."px;border:#e0e0e0 3px solid;margin:2px 5px 5px 5px;\"></a>";
      if($level!=1) echo "<br><i>Click to enlarge</i>";
   }
   else
   {
      echo "<br>[Photo not found]";
      $found=0;
   }
   if($level==1)
   {
      echo "</td>";
      echo "<td><input type=checkbox name=\"approve[$ix]\" value=\"x\"";
      if($row[photoapproved]=='x') echo " checked";
      if($found==0) echo " disabled=TRUE";
      echo "></td>";
      echo "<td><a href=\"photosadmin.php?session=$session&delete=$row[id]";
      if($nameid) echo "&nameid=$nameid";
      echo "\" onclick=\"return confirm('Are you sure you want to delete this photo?');\">X</a></td>";
      echo "<input type=hidden name=\"offid[$ix]\" value=\"$row[id]\">";
      echo "</tr></table>";
   }
   echo "</td>";
   if(($ix+1)%4==0) echo "</tr>";
   $ix++;
}
$fill=0;
while($ix%4>0)
{
   echo "<td>&nbsp;</td>";
   $fill=1;
   $ix++;
}
if($fill==1)
   echo "</tr>";
if($limit>0) echo $navhtml;
echo "</table><br>";
if(!$nameid && mysql_num_rows($result)>0 && $level==1)
   echo "<input type=submit name=\"save\" value=\"Approve Checked Photos\">";
else if($found==1 && $level==1)
   echo "<input type=submit name=\"save\" value=\"Save Checkmark\">";
echo "</form>";
?>
<div id="debug"></div>   
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
