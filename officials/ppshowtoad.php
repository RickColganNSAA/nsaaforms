<?php
require 'functions.php';
require 'variables.php';

//make sure AD is logged
//connect to $db_name db
mysql_close();
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

$sql="SELECT * FROM sessions WHERE session_id='$session'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   //CHECK IF NSAA USER
   $sql="USE $db_name2";
   $result=mysql_query($sql);
   if(!ValidUser($session) || GetLevelJ($session)!=1)
   {
      header("Location:index.php?error=1");
      exit();
   }
   else
   {
      $level=1;
   }
}
//if($level!=4)
//{
   $sql="SELECT t1.school FROM logins AS t1,sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
//}
//else
//{
   //College host
   //$sql="SELECT t1.name FROM logins AS t1, sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
//}
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school=$row[0];

//connect to $db_name2
mysql_close();
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$districts=$sport."districts";
$contracts=$sport."contracts";
$sport='pp';
$sportname=GetSportName($sport);

if($level==1 && $save)
{
   $sql="UPDATE showtoad SET body='$body' WHERE sport='$sport'";
   $result=mysql_query($sql);
}

//get this district's info
$school2=ereg_replace("\'","\'",$school);
$sql="SELECT * FROM $districts WHERE id='$id' AND hostschool='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0 && $level!=1)
{
   echo $init_html;
   echo "<table><tr align=center><td>";
   echo "<img src=\"nsaacontract.png\"><br><br>";
   echo "<b>No match for selected host school/district</b><br><br>";
   echo $end_html;
   exit();
}
$name="$row[first] $row[last]";
$class=$row['class']; $dist=$row[district]; $type=$row[type];
$time=$row[time]; $date=$row[dates];
$site=$row[site];
$schools=$row[schools];

echo $init_html;
if($edit==1 && $level==1)
{
?>
<script type="text/javascript">
tinyMCE.init({
        mode : 'textareas',
        theme : 'advanced',
        skin : 'o2k7',
        skin_variant : 'black',
        convert_urls : false,
        relative_urls : false,
        plugins : 'safari,iespell,preview,media,searchreplace,paste,',
        theme_advanced_buttons1 : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,pastetext,pasteword,|,undo,redo,|,link,unlink,image,media,|,code,preview',
        theme_advanced_buttons2 : '',
        theme_advanced_toolbar_location : 'top',
        theme_advanced_toolbar_align : 'left',
        theme_advanced_statusbar_location : 'bottom',
        theme_advanced_resizing : true,
        // Example content CSS (should be your site CSS)
        content_css : '../css/plain.css',
        // Drop lists for link/image/media/template dialogs
        template_external_list_url : 'lists/template_list.js',
        external_link_list_url : 'lists/link_list.js',
        external_image_list_url : 'lists/image_list.js',
        media_external_list_url : 'lists/media_list.js'
        });
        </script>
<?php
}
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($level==1) echo "<br><font style='color:#8b0000;font-size:11pt;'><b>SAMPLE</b></font><br><br><a href=\"".$sport."showtoad.php?session=$session&edit=1#editbody\">Edit this Page</a><br>";
echo "</td></tr>";

echo "<tr align=left><td>The following judges have been assigned to work the $type $class-$dist $sportname contest you are hosting.</td></tr>";

$sql="SELECT t1.first,t1.middle,t1.last,t1.homeph,t1.workph,t1.cellph,t1.email,t2.* FROM judges AS t1,$contracts AS t2 WHERE t2.distid='$id' and t2.offid=t1.id AND t2.confirm='y' AND t2.accept='y' ORDER BY t1.last, t1.first, t1.middle";
$result=mysql_query($sql);
$num=1;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td><b>$num)&nbsp;<i>$row[first] $row[middle] $row[last]</b></i>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<table cellpadding=2 cellspacing=2>";
   echo "<tr align=left><td><b>Contact Info:</b></td>";
   if($sport=='sp')
   {
      echo "<td><b>Event Preferences:</b></td>";
      echo "<td><b>Other:</b></td>";
   }
   echo "</tr><tr valign=top align=left><td>";
   if($row[homeph]!="")
   {
      echo "Home Phone:&nbsp;(";
      echo substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>";
   }
   if($row[workph]!="")
   {
      echo "Work Phone:&nbsp;(";
      echo substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[homeph],6,4)."<br>";
   }
   if($row[cellph]!="")
   {
      echo "Cell Phone:&nbsp;(";
      echo substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4)."<br>";
   }
   if($row[email]!="")
   {
      echo "E-mail:&nbsp;$row[email]<br>";
   }
   echo "</td>";
   if($sport=='sp')
   {
      echo "<td>";
      for($i=0;$i<count($prefs_sm);$i++)
      {
         if(trim($row[$prefs_sm[$i]])=='') $row[$prefs_sm[$i]]="&nbsp;";
         echo "<u>&nbsp;".$row[$prefs_sm[$i]]."&nbsp;</u>&nbsp;".$prefs_lg[$i]."<br>";
      }
      echo "</td><td width=200>";
      echo "<b>I represent: </b>$row[schrep]<br>";
      echo "<b>Conflict w/ Class(es): </b>$row[classconflict]<br>";
      echo "<b>Conflict w/ School(s):</b> $row[schconflict]</td></tr>";
      echo "</td>";
   }
   echo "</tr></table></td></tr>";
   $num++;
}
if(mysql_num_rows($result)==0)
{
   echo "<tr align=center><td><font style=\"color:red\">[No judges have been confirmed for your district yet.  Please check back at a later date.]</font></td></tr>";
}

echo "<tr><td><a name='editbody'><br></a></td></tr>";

$sql="SELECT * FROM showtoad WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($level==1 && $edit==1)
{
   echo "<tr align=left><td><form method=post action='".$sport."showtoad.php'><input type=hidden name='session' value='$session'><textarea cols=80 rows=25 name='body'>$row[body]</textarea><br><input type=submit name='save' value='Save Changes'></form></td></tr>";
}
else
{
   echo "<tr align=left><td>$row[body]</td></tr>";
}

echo "</table>";

echo "<br><br><a class=small href=\"javascript:window.close();\">Close Window</a><br><br>";
echo $end_html;
?>
