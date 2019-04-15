<?php
$sport='sb';

require 'functions.php';
require 'variables.php';
$thisyear=GetSchoolYear(date("Y"),date("m"));

//make sure AD is logged
//connect to $db_name db
mysql_close();
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

$sql="SELECT * FROM sessions WHERE session_id='$session'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   //CHECK IF NSAA USER
   $sql="USE $db_name2";
   $result=mysql_query($sql);
   if(!ValidUser($session) || GetLevel($session)!=1) 
   {
      header("Location:index.php?error=1");
      exit();
   }
   else
   {
      $level=1;
   }
}

//connect to $db_name2
mysql_close();
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";
$sportname=GetSportName($sport);

if($level==1 && $save)
{
   $sql="UPDATE showtoad SET body='$body' WHERE sport='$sport'";
   $result=mysql_query($sql);
}

//get this district's info
$sql="SELECT * FROM $districts WHERE id='$id'";
if($level==1)
   $sql="SELECT * FROM $districts LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($level==1) $id=$row[id];
$class=$row['class']; $dist=$row[district];
$type=$row[type]; 
$dates="";
$days=split("/",$row[dates]);
for($i=0;$i<count($days);$i++)
{
   $cur=split("-",$days[$i]);
   $dates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0]));
   $dates.=", ";
}
$dates.=$cur[0];
$director="$row[director]";
if($director==" ")
{
   $hostid=$row[hostid];
   $sql2="SELECT name FROM $db_name.logins WHERE id='$hostid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $director=$row2[0];
}
$hostschool=$row[hostschool];
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
echo "<center><br>";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($level==1) echo "<br><font style='color:#8b0000;font-size:11pt;'><b>SAMPLE</b></font><br><br><a href=\"".$sport."showtoad.php?session=$session&edit=1#editbody\">Edit this Page</a><br>";
echo "</td></tr>";
echo "<tr align=left><td>Date:&nbsp;".date("F j, Y")."</td></tr>";
echo "<tr align=left><td>District Director: $director</td></tr>";
echo "<tr align=left><td>The following officials have been assigned to work the $thisyear NSAA $type $sportname Tournament";
echo ", which you are hosting:</td></tr>";


$sql="SELECT * FROM $disttimes WHERE distid='$id' ORDER BY day,time";
$result=mysql_query($sql);
echo "<tr align=left><td><table>";
echo "<tr align=left><td><table>";
echo "<tr align=left><td colspan=2><b><u>$type $class-$dist:</u></b><br><br>";
if($dates!='') echo "<b>Dates:</b> $dates<br>";
echo "<b>Host School (Site):</b> $hostschool ($site)<br>";
if($schools!='') echo "<b>Schools Competing:</b> $schools<br>";
echo "</td></tr>";
while($row=mysql_fetch_array($result))
{
   $date=split("-",$row[day]);
   $curday=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<tr align=left valign=top><td width=110><b>$curday";
   if($row[time]!='') echo " @ $row[time]";
   echo ":</td>";
   $sql2="SELECT t1.first,t1.last,t1.middle,t2.crewchief FROM officials AS t1,$contracts AS t2 WHERE t1.id=t2.offid AND t2.disttimesid='$row[id]' ORDER BY t2.crewchief DESC,t1.last,t1.first,t1.middle";
   $result2=mysql_query($sql2);
   echo "<td>";
   while($row2=mysql_fetch_array($result2))
   {
      echo "$row2[first] $row2[middle] $row2[last]";
      if($row2[crewchief]=='x') echo " (Crew Chief)";
      echo "<br>";
   }
   echo "</td></tr>"; 
}
echo "</table></td></tr>";
echo "</table></td></tr>";

$sql="SELECT DISTINCT t1.offid FROM $contracts AS t1,$disttimes AS t2,officials AS t3 WHERE t1.disttimesid=t2.id AND t2.distid='$id' AND t1.offid=t3.id ORDER BY t3.last,t3.first,t3.middle";
$result=mysql_query($sql);
echo "<tr align=left><td><b>Officials' Contact Info:</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM officials WHERE id='$row[0]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2); 
   echo "<tr align=left><td>$row2[first] $row2[middle] $row2[last]<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;";
   echo "Phone: ";
   if($row2[homeph]!='') 
      echo "(H) (".substr($row2[homeph],0,3).")".substr($row2[homeph],3,3)."-".substr($row2[homeph],6,4)."&nbsp;&nbsp;";
   if($row2[workph]!='') 
      echo "(W) (".substr($row2[workph],0,3).")".substr($row2[workph],3,3)."-".substr($row2[workph],6,4)."&nbsp;&nbsp;";
   if($row2[cellph]!='') 
      echo "(C) (".substr($row2[cellph],0,3).")".substr($row2[cellph],3,3)."-".substr($row2[cellph],6,4)."&nbsp;&nbsp;";
   echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "E-mail: $row2[email]</td></tr>";
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

echo "</table></form>";
echo "<a class=small href=\"javascript:window.close()\">Close</a>";
echo $end_html;
?>
