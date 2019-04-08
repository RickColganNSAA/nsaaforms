<?php
/*********************************
cuppublic.php
NSAA can preview public links for
the website showing Cup standings
and also edit the header for
these pages
Author: Ann Gaffigan
Created: 9/10/15
*********************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
/* $db_name='nsaascores20172018';
$db=mysql_connect($db_host,$db_user,$db_pass); */
/****** GENERATE A FILE FOR A CLASS (TOP 10 PAGE)******/
if($class && $class!='')
{
   if($edit==1)		/****** EDIT HEADER FOR A CLASS's TOP 10 PAGE ******/
   {
      if($saveheader)
      {
	 if($gender && $gender!='') $field=substr($gender,0,1)."headertext";
         else $field="headertext";
         $sql="UPDATE cupclasssettings SET $field='".addslashes($headertext)."' WHERE class='$class'";
         $result=mysql_query($sql);
         echo mysql_error();
      }
      echo $init_htmlNEWTINYMCE.GetHeader($session);
?>
<script type="text/javascript">
tinyMCE.init({
        mode : 'textareas',
    	toolbar: [
        	"undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright"
    	],
 	plugins: [ "link image" ]
        });
</script>
<?php

      echo "<p style=\"text-align:left;\"><a href=\"cuppublic.php?session=$session\">&larr; Return to Manage NSAA Cup Public Links</a></p>";

      echo "<h1>NSAA Cup: Edit Header for CLASS $class";
      if($gender && $gender!='') { echo " ".strtoupper($gender); $type=strtoupper($gender); }
      else { echo " OVERALL"; $type="OVERALL"; }
      echo " Public Link</h1>";

      echo "<div style=\"width:600px;text-align:left;\">";

      echo "<form method='post' action='cuppublic.php'>	
		<input type=hidden name='session' value='$session'>
		<input type=hidden name='class' value='$class'>
	 	<input type=hidden name='gender' value='$gender'>
		<input type=hidden name='edit' value='$edit'>";

      $header=GetCupHeader($class,FALSE,$gender);
      if($saveheader) echo "<div class='alert'>Your changes have been saved. <a href=\"cuppublic.php?session=$session&gender=$gender&class=$class\" target=\"_blank\">Generate a New Preview of Class $class Cup $type Standings</a></div>";
      echo "<p>Use the text box below to create and manage the heading that will display on the <a href=\"cuppublic.php?session=$session&gender=$gender&class=$class\" target=\"_blank\">Class $class Cup $type Standings</a> on the NSAA website.</p>";
      echo "<textarea name=\"headertext\" style=\"width:100%;height:400px;\">$header</textarea>";
      echo "<p><input type='submit' name='saveheader' value='Save Header' class='fancybutton'></p>";

      echo "</form></div>";
      echo $end_html;
      exit();
   } //END IF EDIT
   else /****** GENERATE A FILE FOR A CLASS ******/
   {
      $html="<style>th, td { font-size:14px; }</style><table width='100%'><tr align=center><td>";
      $html.=GetCupHeader($class,FALSE,$gender);

      //Top 10 Overall
      if($gender && $gender!='') { $sortby=$gender."points"; $type=strtoupper($gender); }
      else { $sortby="allpoints"; $type="OVERALL"; }
      $sql="SELECT t1.*,t2.school FROM cupschools AS t1, headers AS t2 WHERE t1.schoolid=t2.id AND t1.cupclass='$class' ORDER BY t1.$sortby DESC,school ASC";
      $result=mysql_query($sql);
      $html.="<table cellspacing=0 cellpadding=10 style=\"width:500px;\"><tr align=left><th>School</th><th align='center'>$type Cup Points</th></tr>";
      $place=0; $ct=0; $prevpts=0;
      while($row=mysql_fetch_array($result))
      {
         $ct++;
         if($row[$sortby]!=$prevpts) $place=$ct;
         else if(!preg_match("/=/",$place)) $place="=".$place;
         if($place>10) break;
         $html.="<tr><td align='left'>$place. $row[school]</td><td align='center'>".$row[$sortby]."</td></tr>"; 
         $prevpts=$row[$sortby];
      }
      $html.="</table>";
   
      $html.=$end_html;

      $partialhtml=$html;
      $html=$init_html.$html;

      if(!$publishhtml && !$unpublish)
      {
        echo "<form method=\"post\" action=\"cuppublic.php\">
                <input type=hidden name=\"session\" value=\"$session\">
                <input type=hidden name=\"gender\" value=\"$gender\">
                <input type=hidden name=\"class\" value=\"$class\">";
        echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";

        echo "<div class=\"alert\" style=\"width:800px;text-align:center;\"><p>To publish the following page to the public website, please click the \"Publish\" button.&nbsp;&nbsp;&nbsp;";
        echo "<input type=submit class=\"fancybutton\" name=\"publishhtml\" value=\"Publish\"></p>";
        echo "<p>To RESET the public version of this page to say \"Information not available,\" click \"Unpublish.\"&nbsp;&nbsp;&nbsp;<input type=submit class=\"fancybutton\" name=\"unpublish\" value=\"Unpublish\"></p></div></form>";

        echo $partialhtml;
      }
      else      //WRITE THE FILE
      {
         if($unpublish) $html=$init_html."<table width='100%'><tr align=center><td><br><h3>Information not available.</h3>".$end_html;
         $filename="Class".$class."CupRankings".strtoupper(trim($gender)).".html";
         if(!$open=fopen(citgf_fopen("../cup/$filename"),"w")) { echo "<div class='error'>ERROR: Could not open $filename</div>"; exit(); }
         if(!fwrite($open,$html)) { echo "<div class='error'>ERROR: Could not write to $filename</div>"; exit(); }
         fclose($open); 
 citgf_makepublic("../cup/$filename");

         echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";
         echo "<br><br><p style=\"text-align:center;\">The rankings have been published for public viewing. Click the link below to view it. Copy the link below to link to it from the NSAA website.<br><br><br><a href=\"/cup/$filename\">https://secure.nsaahome.org/cup/$filename</a></p>";

         echo $end_html;
      }


      exit();
   }
} //END IF $class

/****** PREVIEW AND EDIT PUBLIC LINKS ******/

echo $init_html;

echo GetHeader($session)."<br>";

echo "<p style=\"text-align:left;\"><a href=\"cupadmin.php?session=$session\">&larr; Return to NSAA Cup Main Menu</a></p>";

echo "<h1>NSAA Cup: Preview & Manage Public Links</h1>";

echo "<br><div style=\"width:900px;text-align:left;\">
	<ul><li><b>Cup Classifications:</b> <a href=\"../cup/NSAACupClasses.html\" target=\"_blank\">Current Public Link</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a target=\"_blank\" href=\"cupclasses.php?session=$session\">Generate List of Classifications</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"cupclasses.php?session=$session&edit=1\">Edit Header</a></li></ul>
	<table cellspacing=0 cellpadding=8 frame='all' rules='all' style=\"border:#808080 1px solid;\"
	<tr align=center><th>&nbsp;</th><th>Top 10 OVERALL List</th><th>Top 10 GIRLS List</th><th>Top 10 BOYS List</th><th>Spreadsheet</th></tr>";
//CLASS LINKS:
$sql="SELECT class FROM cupclasssettings WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	//OVERALL
   echo "<tr align='left'><th>Class $row[class]</th><td><p><a href=\"/cup/Class".$row['class']."CupRankings.html\" target=\"_blank\">Current Public Link</a></p><p><a href=\"cuppublic.php?session=$session&class=$row[class]\" target=\"_blank\">Generate & Preview Latest</a></p><p><a href=\"cuppublic.php?session=$session&class=$row[class]&edit=1\">Edit Header</a></p></td>";
	//GIRLS
   echo "<td><p><a href=\"/cup/Class".$row['class']."CupRankingsGIRLS.html\" target=\"_blank\">Current Public Link</a></p><p><a href=\"cuppublic.php?session=$session&class=$row[class]&gender=girls\" target=\"_blank\">Generate & Preview Latest</a></p><p><a href=\"cuppublic.php?session=$session&gender=girls&class=$row[class]&edit=1\">Edit Header</a></p></td>";
	//BOYS
   echo "<td><p><a href=\"/cup/Class".$row['class']."CupRankingsBOYS.html\" target=\"_blank\">Current Public Link</a></p><p><a href=\"cuppublic.php?session=$session&class=$row[class]&gender=boys\" target=\"_blank\">Generate & Preview Latest</a></p><p><a href=\"cuppublic.php?session=$session&gender=boys&class=$row[class]&edit=1\">Edit Header</a></p></td>";
	//SPREADSHEET
   echo "<td><p><a href=\"/cup/Class".$row['class']."CupSpreadsheet.html\" target=\"_blank\">Current Public Link</a></p><p><a href=\"cupspreadsheet.php?session=$session&class=$row[class]\" target=\"_blank\">Generate & Preview Latest</a></p><p><a href=\"cupspreadsheet.php?session=$session&class=$row[class]&edit=1\">Edit Header</a></p></td></tr>";
}
echo "</table>";
echo "</div>";

?>
