<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);

if(!ValidUser($session) || $level!=1)
{
   header("Location:jindex.php?error=1");
   exit();
}

if($delete && $delete!='')
{
   $sql="UPDATE judges SET photofile='',photoapproved='' WHERE id='$delete'";
   $result=mysql_query($sql);
   $sql="SELECT * FROM judges WHERE id='$delete'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(citgf_file_exists("photos/$row[photofile]"))
      citgf_unlink("photos/$row[photofile]");
}

if($save)	//mark checked photos as APPROVED
{
   for($i=0;$i<count($offid);$i++)
   {
      $sql="UPDATE judges SET photoapproved='$approve[$i]' WHERE id='$offid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html_ajax."</head>";
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','photoform');">
<?php
echo GetHeaderJ($session,"managejudge");

echo "<form method=post action=\"jphotosadmin.php\" name=\"photoform\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<br><table cellspacing=3 cellpadding=3>";
echo "<caption><b>Judges Profile Pictures Admin:</b>";
if($save)
   echo "<div class=alert style=\"width:350px\">The checkmarks you've changed have been saved.</div>";
if($delete && $delete!='')
   echo "<div class=alert style=\"width:350px\">The photo for <i>".GetJudgeName($delete)."</i> has been deleted.</div>";
echo "</caption>";
if(!$nameid)
{
   if($viewall==1)
      $sql="SELECT * FROM judges WHERE photofile!='' ORDER BY last,first";
   else
     $sql="SELECT * FROM judges WHERE photofile!='' AND photoapproved='' ORDER BY last,first";
}
else
   $sql="SELECT * FROM judges WHERE id='$nameid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && !$nameid)
{
   if($viewall==1)
      echo "<tr align=center><td><br>No pictures were found.<br><br></td></tr>";
   else
      echo "<tr align=center><td><br>There are no profile pictures that need to be approved.<br><br></td></tr>";
}
else if(!$nameid)
{
   if($viewall==1)
      echo "<tr align=center><td colspan=4><b>You are viewing ALL Judges' Profile Pictures.</b>  <a class=small href=\"jphotosadmin.php?session=$session\">View only those that need APPROVAL</a></td>";
   else
      echo "<tr align=center><td colspan=4><b>You are viewing Profile Pictures that Need Approval.</b>  <a class=small href=\"jphotosadmin.php?session=$session&viewall=1\">View ALL Judges' Profile Pictures</a></td></tr>";
}
else
{
   echo "<tr align=center><td colspan=4>To view ALL Profile Pictures that Need Approval, <a href=\"jphotosadmin.php?session=$session\" class=small>Click Here</a>.</td></tr>";
}
echo "<tr align=center><td colspan=4><table><tr><td>To view the the picture for a specific judge, begin typing their name:</td>";
echo "<input type=hidden name=\"nameid\" id=\"nameid\">";
echo "<td><input type=text class=tiny size=35 name=\"name\" id=\"name\" value=\"$offname\" onkeyup=\"UserLookup.lookup('name',this.value,'','judge');\"><br><div class=\"list\" id=\"nameList\" style=\"position:absolute; z-index:100;\"></div></td></tr></table>";
echo "</td></tr>";
echo "<tr align=left><td colspan=4><a href=\"vote_pp.php?session=$session&nsaa=1&sample=1\" target=\"_blank\">Preview Ballot for State Play Production</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"vote_sp.php?session=$session&nsaa=1&sample=1\" target=\"_blank\">Preview Ballot for State Speech</a></td></tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($ix%4==0) echo "<tr align=center valign=top>";
   echo "<td>";
   echo "<table frame=all rules=all style=\"border:#808080 1px solid\" cellspacing=0 cellpadding=3>";
   echo "<tr align=center><td><b>Judge</b><br>(click to email)</td><td><b>Approve</b></td><td><b>Delete</b></td></tr>";
   echo "<tr align=center valign=center><td><a href=\"mailto:$row[email]\">".GetJudgeName($row[id])."</a><br>";
   if(citgf_file_exists("photos/$row[photofile]") && $row[photofile]!='')
   {
      $found=1;
      echo "<a href=\"photos/$row[photofile]\" target=\"_blank\"><img src=\"photos/$row[photofile]\" width=\"75px\" border=0></a>";
    }
   else
   {
      echo "<br>Photo not found";
      $found=0;
   }
   echo "</td>";
   echo "<td><input type=checkbox name=\"approve[$ix]\" value=\"x\"";
   if($row[photoapproved]=='x') echo " checked";
   if($found==0) echo " disabled=TRUE";
   echo "></td>";
   echo "<td><a href=\"jphotosadmin.php?session=$session&delete=$row[id]";
   if($nameid) echo "&nameid=$nameid";
   echo "\" onclick=\"return confirm('Are you sure you want to delete this photo?');\">X</a></td>";
   echo "<input type=hidden name=\"offid[$ix]\" value=\"$row[id]\">";
   echo "</tr></table></td>";
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
echo "</table><br>";
if(!$nameid && mysql_num_rows($result)>0)
   echo "<input type=submit name=\"save\" value=\"Approve Checked Photos\">";
else if($found==1)
   echo "<input type=submit name=\"save\" value=\"Save Checkmark\">";
echo "</form>";
?>
<div id="debug"></div>   
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
