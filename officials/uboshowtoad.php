<?php
$sport='ubo';

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

echo $init_html."<table width=\"100%\"><tr align=center><td>";
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
echo "<img src=\"/images/NSAAlogo.gif\">";
if($level==1) 
{
   $sql="SELECT id FROM $districts ORDER BY dates DESC LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $id=$row[id];
   echo "<p style='color:#8b0000;'><b>SAMPLE</b></p><p><a href=\"".$sport."showtoad.php?session=$session&edit=1#editbody\">Edit this Page</a></p>";
}
echo "<div style=\"text-align:left;width:600px;\">";
echo "<p>Date:&nbsp;".date("F j, Y")."</p>";
echo "<p>District Director: $director</p>";
echo "<p>The following officials have been assigned to work the $thisyear NSAA $type $sportname Tournament, which you are hosting:</p>";

$sql="SELECT * FROM $disttimes WHERE distid='$id' ORDER BY day,time";
$result=mysql_query($sql);
echo "<h3>$type $class-$dist:</h3>";
if($dates!='') echo "<p><b>Dates:</b> $dates</p>";
echo "<p><b>Host School (Site):</b> $hostschool ($site)</p>";
if($schools!='') echo "<p><b>Schools Competing:</b> $schools</p>";
while($row=mysql_fetch_array($result))
{
   $date=split("-",$row[day]);
   $curday=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<p><b>$curday";
   if($row[time]!='') echo " @ $row[time]";
   echo ":</b></p>";
   $sql2="SELECT t1.first,t1.last,t1.middle,t2.crewchief FROM officials AS t1,$contracts AS t2 WHERE t1.id=t2.offid AND t2.disttimesid='$row[id]' ORDER BY t2.crewchief DESC,t1.last,t1.first,t1.middle";
   $result2=mysql_query($sql2);
   echo "<p>";
   while($row2=mysql_fetch_array($result2))
   {
      echo "$row2[first] $row2[middle] $row2[last]";
      if($row2[crewchief]=='x') echo " (Crew Chief)";
      echo "<br>";
   }
   echo "</p>"; 
}

$offinfo=GetAssignedOfficials($sport,$id);
if($offinfo!='') echo "<h3>Officials' Contact Info:</h3>$offinfo";

echo "<a name='editbody'><br></a>";

$sql="SELECT * FROM showtoad WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($level==1 && $edit==1)
{
   echo "<p><form method=post action='".$sport."showtoad.php'><input type=hidden name='session' value='$session'><textarea cols=80 rows=25 name='body'>$row[body]</textarea><br><input type=submit name='save' value='Save Changes'></form></p>";
}
else
{
   echo "<p>$row[body]</p>";
}

echo "</div><p><a class=small href=\"javascript:window.close()\">Close</a></p>";
echo $end_html;
?>
