<?php
/*********************************
cupclasses.php
NSAA can preview and print public link
for list of schools in each Cup class
Author: Ann Gaffigan
Created: 9/18/15
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
   if($edit==1)		/****** EDIT HEADER ******/
   {
      if($saveheader)
      {
         $sql="UPDATE cupclasssettings SET headertext='".addslashes($headertext)."' WHERE class=''";
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

      echo "<h1>NSAA Cup: Edit Header for Cup Classifications Public Link</h1>";

      echo "<div style=\"width:100%;max-width:1000px;text-align:left;\">";

      echo "<form method='post' action='cupclasses.php'>	
		<input type=hidden name='session' value='$session'>
		<input type=hidden name='edit' value='$edit'>";

      $header=GetCupHeader('');
      if($saveheader) echo "<div class='alert'>Your changes have been saved. <a href=\"cupclasses.php?session=$session\" target=\"_blank\">Preview Classifications Link</a></div>";
      echo "<p>Use the text box below to create and manage the heading that will display on the <a href=\"cupclasses.php?session=$session&\" target=\"_blank\">Classifications Link</a> on the NSAA website.</p>";
      echo "<textarea name=\"headertext\" style=\"width:100%;height:200px;\">$header</textarea>";
      echo "<p><input type='submit' name='saveheader' value='Save Header' class='fancybutton'></p>";

      echo "</form></div>";
      echo $end_html;
      exit();
   } //END IF EDIT
   else /****** GENERATE A FILE ******/
   {
      $html="<table style=\"width:100%;\"><tr align=center><td><div style=\"max-width:800px;\">";
      $html.=GetCupHeader('')."</div><div style=\"margin-bottom:10px;clear:both;\"></div>";

      $sql="SELECT DISTINCT cupclass FROM cupschools WHERE cupclass!='' ORDER BY cupclass";
      $result=mysql_query($sql); 
      $html.="<style>table td { border-right: #d0d0d0 1px solid; }</style><table cellspacing=0 cellpadding=10><tr valign='top' align='left'>";
      while($row=mysql_fetch_array($result))
      {
          if($row[cupclass]=='A'){
              $html.="<td colspan='3'><h3 style=\"margin:0; padding-left:8px;\">Class $row[cupclass]</h3></td>";
          }else{
              $html.="<td colspan='2'><h3 style=\"margin:0; padding:0;\">Class $row[cupclass]</h3></td>";
          }
      }
      $html.="</tr><tr valign='top' align='left'>";
      $sql="SELECT DISTINCT cupclass FROM cupschools ORDER BY cupclass";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $sql2="SELECT t1.school,t2.* FROM headers AS t1, cupschools AS t2 WHERE t1.id=t2.schoolid AND t2.cupclass='$row[cupclass]' AND t1.school!='Test\'s School' ORDER BY t1.school";
	 $result2=mysql_query($sql2);
	 //2 columns for classes C and D
         if($row[0]=='A' || $row[0]=='B') 
         {
	    $percol=mysql_num_rows($result2)+10;
            $html.="<td colspan=2>";
    	 }
         else 
	 {
	    $percol=ceil(mysql_num_rows($result2)/2);
	    $html.="<td>";
	 }
         $curcol=0;
	 while($row2=mysql_fetch_array($result2))
   	 {
	    if($row2[gender]=="girls") $html.="<p style=\"color:#CC3399;margin-right:20px;\">";
	    else if($row2[gender]=="boys") $html.="<p style=\"color:#3333FF;margin-right:20px;\">";
	    else $html.="<p style=\"margin-right:20px;\">";
	    $html.="$row2[school]</p>";
	    $curcol++;
	    if($curcol==$percol) $html.="</td><td>";
	 }
         $html.="</td>";
      }
      $html.="</tr></table>";
      $html.=$end_html;
   
      $partialhtml=$html;
      $html=$init_html.$html;

      if(!$publishhtml && !$unpublish)
      {
        echo "<form method=\"post\" action=\"cupclasses.php\">
		<input type=hidden name=\"session\" value=\"$session\">";
	echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";

	echo "<div class=\"alert\" style=\"width:800px;text-align:center;\"><p>To publish the following page to the public website, please click the \"Publish\" button.&nbsp;&nbsp;&nbsp;";
	echo "<input type=submit class=\"fancybutton\" name=\"publishhtml\" value=\"Publish\"></p>";
	echo "<p>To RESET the public version of this page to say \"Information not available,\" click \"Unpublish.\"&nbsp;&nbsp;&nbsp;<input type=submit class=\"fancybutton\" name=\"unpublish\" value=\"Unpublish\"></p></div></form>";

        echo $partialhtml;
      }
      else	//WRITE THE FILE 
      {
         if($unpublish) $html=$init_html."<table width='100%'><tr align=center><td><br><h3>Information not available.</h3>".$end_html;
	 $filename="NSAACupClasses.html";
  	 if(!$open=fopen(citgf_fopen("../cup/$filename"),"w")) { echo "<div class='error'>ERROR: Could not open $filename</div>"; exit(); }
         if(!fwrite($open,$html)) { echo "<div class='error'>ERROR: Could not write to $filename</div>"; exit(); }
         fclose($open); 
 citgf_makepublic("../cup/$filename");

         echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";
	 echo "<br><br><p style=\"text-align:center;\">The classifications have been published for public viewing. Click the link below to view it. Copy the link below to link to it from the NSAA website.<br><br><br><a href=\"/cup/$filename\">https://secure.nsaahome.org/cup/$filename</a></p>";

	 echo $end_html;
      }
      exit();
   }
?>
