<?php
//forms.php: takes as input selected form(s) from welcome page
//	and either shows list of selected forms or redirects
//	to single selected form

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host","nsaa","scores");
mysql_select_db("$db_name",$db);

//get information from db about user using session id
$sql="SELECT t2.* FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school=$row[4];
$sport=$row[5];
$level=$row[6];
$header=GetHeader($session);

if($level==2)
{
   $school_array[0]=GetSchool($session);
}
else if($level==3)
{
   $activity_array[0]=GetActivity($session);
   $school_array[0]=GetSchool($session);
}

//get requested form(s)
   if(count($school_array)==1 && count($activity_array)==1 && $school_array[0]!="All Schools" && $activity_array[0]!="All Activities")
   {
   //go directly to that form
      $school_ch=$school_array[0];
      $sport=$activity_array[0];
      $sport_abb=GetActivityAbbrev2($sport);
      $sport_dir=GetActivityAbbrev($sport);
      header("Location:$sport_dir/view_$sport_abb.php?session=$session&school_ch=$school_ch");
      exit();
   }
   else if(count($school_array)==1 && $school_array[0]!="All Schools")
   {
   //list of forms for one school
?>
      <html>
      <head>
	 <title>NSAA Home</title>
	 <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
      </head>
      <body>
<?php
      echo $header;
      $school_ch=$school_array[0];
?>
      <center><br><br>
      <table width=100% cellspacing=0 cellpadding=2>
      <caption><b>District Forms for <?php echo $school_ch; ?>:</b></caption>
<?php
      if($activity_array[0]=="All Activities")
      {
         for($i=0;$i<count($act_long);$i++)
	 {
	    echo "<tr align=center><td>";
	    $dir=GetActivityAbbrev($act_long[$i]);
	    $file=GetActivityAbbrev2($act_long[$i]);
	    $file="view_$file.php";
	    echo "<a href=\"$dir/$file\">$act_long[$i]</a>";
	    echo "</td></tr>";
	 }
      }
      else
      {
         for($i=0;$i<count($activity_array);$i++)
         {
	    echo "<tr align=center><td>";
	    $dir=GetActivityAbbrev($activity_array[$i]);
	    $file=GetActivityAbbrev2($activity_array[$i]);
	    $file="view_$file.php";
	    echo "<a href=\"$dir/$file\">$activity_array[$i]</a>";
	    echo "</td></tr>";
         }
      }
?>
      </table>
      </center>
      </td><!--End Main Body-->
      </tr>
      </table>
      </body>
      </html>
<?php
   }
   else	//list of schools with list of forms
   {
?>
      <html>
      <head>
	 <title>NSAA Home</title>
	 <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
      </head>
      <body>
<?php
      echo $header;

      if($school_array[0]=="All Schools")
      {
	 $sql="SELECT school FROM headers";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result);
	 {
	    $school=$row[0];
	    echo "<tr align=left>
	    if($activity_array[0]=="All Activities")
	    {
	       
	 
?>
