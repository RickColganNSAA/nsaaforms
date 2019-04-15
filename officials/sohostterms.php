<?php
require 'variables.php';
require 'functions.php';
//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

if($session) $level=GetLevel($session);

echo $init_html;
if($save)
{
   $body=addslashes($body);
   $sql="SELECT * FROM hostterms WHERE sport='so'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
      $sql="INSERT INTO hostterms (sport,body) VALUES ('so','$body')";
   else
      $sql="UPDATE hostterms SET body='$body' WHERE sport='so'";
   $result=mysql_query($sql);
   if(mysql_error()) echo "<div class=error>ERROR: ".mysql_error()."</div>";
   else echo "<div class=alert>Your changes have been saved.</div>";
}

$sql="SELECT * FROM hostterms WHERE sport='so'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
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
<form method=post action="sohostterms.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<?php
}
echo "<table width=100%><tr align=center><td><table width=500>";

$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

echo "<tr align=center><td><img src=\"nsaacontract.png\"></td></tr>";
echo "<tr align=center><th align=center>Terms & Conditions--SOCCER";
if($level==1 && $edit!=1) echo "&nbsp;&nbsp;[<a href=\"sohostterms.php?session=$session&edit=1\">Edit</a>]";
echo "</th></tr>";
if($edit==1 && $level==1)
{
   echo "<tr align=center><td>";
   echo "<textarea cols=80 rows=30 name=\"body\">$row[body]</textarea>";
   echo "<br><input type=submit name=\"save\" value=\"Save Changes\">";
   echo "</td></tr>";
}
else
{
   echo "<tr align=left><td>";
   echo $row[body];
   echo "</td></tr>";
}
/*
echo "
<b>Receipts.</b>  The gate receipts shall be used to pay tournament expenses including officials fees and mileage.
<br><br>
Prior to deducting tournament expenses, ten (10%) percent of the gross receipts shall be sent to the NSAA for catastrophic insurance premium fees.
<br><br>
The NSAA shall be sent 25% of the remaining receipts.  If the receipts are not sufficient to pay the expenses, each school participating in the tournament shall be assessed a prorated share, based on the number of games played.  The insurance premium is not to be deducted.
<br><br><b>
Admission.</b>  Admission prices shall be $5.00 for adults and $4.00 for students.  The host school will provide passes for (or arrange for admittance) 22 players, 2 coaches, 2 student managers, and 1 trainer.  Eight additional passes will be provided for administrative and supervisory personnel.
<br><br><b>
Seeding/Pairings.</b>  The NSAA is responsible for seeding and pairings. Using wildcard point averages, the Class A tournament will be assigned and seeded the Wednesday of Week 43.  The Class B tournaments will be seeded on Wednesday of Week 43. This information will be posted on the NSAA website on the Soccer page.
<br><br><b>
Site.</b>  The director shall select the sites of the tournament.  Each game must be completed on the field in which it was started.  If the facility is also used for other activities (i.e. track) arrangements should be made such that the only activity permitted at the specified time is the district soccer match.  Sites should have adequate restroom facilities available for athletes, coaches, officials and spectators. Facilities must provide reasonable accommodations for those with special needs.
<br><br><b>
Officials.</b>  The NSAA will assign 3 officials for each district match.  The district director is responsible for paying them from the receipts.  The names and contact information of the assigned officials will be posted on the AD login section of the NSAA website as soon as it is available.<br>
The fee is $45 per official per match.  Mileage should also be paid.  Please review the officials contract for more specific information.
<br><br><b>
Inclement Weather.</b>  Inclement weather may force the postponement of a district tournament.  The meet director has the sole authority to determine the postponement and rescheduling of the tournament.  If the tournament director feels a postponement is necessary, he/she must contact the NSAA before postponing the day's activities.
<br><br>
Once the tournament is postponed, the director shall be responsible for setting the new date, time, site, and notifying officials and competing teams.  The previously agreed upon schedule may need to be changed to complete the contest(s) by the required date.  The district director should confer with the officials regarding their availability for the postponed dates and times.
<br><br><b>
Additional Information.</b>  Additional information may be found in the Soccer Manual.";
*/
echo "</table>";
if($edit==1 && $level==1) echo "</form>";
echo $end_html;
?>
