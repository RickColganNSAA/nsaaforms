<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidAssessor($session))
{
   header("Location:../wrassessor.php?error=3");
   exit();
}

//MARK THAT HE/SHE VIEWED THIS SLIDE
$sql="UPDATE wrassessors SET slide".$slide."='x' WHERE session='$session'";
$result=mysql_query($sql);

$sql="SELECT * FROM wrassessors WHERE session='$session'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

$prev=$slide-1; $next=$slide+1;
?>
<script language='javascript'>
parent.document.getElementById('whichslide').innerHTML="<b>Slide <?php echo $slide; ?> of <?php echo $slidect; ?></b>";
</script>
<?php
if($slide==1)
{
?>
<script language="javascript">
parent.document.getElementById('prevslide').style.display='none';
</script>
<?php
}
else
{
?>
<script language="javascript">
parent.document.getElementById('prevslide').style.display='';
parent.document.getElementById('prevslide').innerHTML="<input type=button class=fancybutton value='<< Previous' onClick=\"document.all.slideframe.src='showslide.php?slide=<?php echo $prev; ?>&session=<?php echo $session; ?>';\">";
</script>
<?php
}
if(WatchedAllSlides(GetWRAUserID($session)) && $slide==$slidect)
{
?>
<script language="javascript">
parent.document.getElementById('nextslide').style.display='';
parent.document.getElementById('nextslide').innerHTML="<input type=button class=fancybutton value='Next Step: Complete Payment >>' onClick=\"parent.location.href='payment.php?session=<?php echo $session; ?>';\">";
</script>
<?php
}
else if($slide==$slidect)
{
?>
<script language="javascript">
parent.document.getElementById('nextslide').style.display='none';
</script>
<?php
}
else
{
?>
<script language="javascript">
parent.document.getElementById('nextslide').style.display='';
parent.document.getElementById('nextslide').innerHTML="<input type=button class=fancybutton value='Next >>' onClick=\"document.all.slideframe.src='showslide.php?slide=<?php echo $next; ?>&session=<?php echo $session; ?>';\">";
</script>
<?php
}
if($slide<10) $imagefile="Slide0".$slide.".jpg";
else $imagefile="Slide".$slide.".jpg";
echo "<img src=\"slides/$imagefile\" border=0 width='700px'>";

?>
