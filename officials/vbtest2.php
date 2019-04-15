<?php
/*******************************
vbtest2.php
Part 2 (Supervised) Online VB Test
THIS SCRIPT RUNS THE TIMER AND
CONTAINS A FRAME FOR THE ACTUAL
TEST. IT ALSO RANDOMLY ASSIGNS
QUESTIONS TO THE OFFICIAL.
Created 8/18/11
By Ann Gaffigan
********************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host2,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$offid=GetOffID($session);

/******SPORT AND TABLE NAMES******/
$sport='vb';
$sportname=GetSportName($sport);
$testtable=$sport."test2";
$categtable=$sport."test2_categ";
$resultstable=$sport."test2_results";
$answerstable=$sport."test2_answers";
$mchoicestable=$sport."test2_mchoices";

//check if already submitted this test
$sql="SELECT * FROM $resultstable WHERE offid='$offid' AND datetaken!=''";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)      //already taken
{
   header("Location:welcome.php?session=$session");
   exit();
}

//if NOT, then insert row into $results table if necessary and check if questions have been shuffled for this user yet
$sql="SELECT * FROM $resultstable WHERE offid='$offid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//NEED TO INSERT ROW FOR THIS USER
{
   $sql="INSERT INTO $resultstable (offid) VALUES ('$offid')";
   $result=mysql_query($sql);
   $sql="DELETE FROM $answerstable WHERE offid='$offid'";
   $result=mysql_query($sql);
}
else	//DO WE NEED TO MAKE THIS OFFICIAL START OVER?
{
   $row=mysql_fetch_array($result);
   if(time()-$row[starttime]>5)    //5 sec grace period, otherwise, wipe out test and make them start over
   {
      $sql="DELETE FROM $answerstable WHERE offid='$offid'";
      $result=mysql_query($sql);
   }
   $sql="UPDATE $resultstable SET starttime='".time()."' WHERE offid='$offid'";
   $result=mysql_query($sql);
}
//GET VITALS ABOUT THIS TEST
$sql="SELECT * FROM test2_duedates WHERE test='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$showdate=$row[showdate]; $duedate=$row[duedate]; $totalques=$row[totalques];

$sql="SELECT * FROM $answerstable WHERE offid='$offid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)  //if NOT, shuffle questions and assign them to this user
{
   //GET TOTAL # AVAILABLE QUESTIONS IN POOL
   $sql2="SELECT * FROM $testtable";
   $result2=mysql_query($sql2);
   $quesct=mysql_num_rows($result2);
   //GET RANDOMLY ARRANGED STRING OF NUMBERS FROM 1 To $quesct
   $numbers = range(1, $quesct);
   srand((float)microtime() * 1000000);
   shuffle($numbers);  
   //INITIALIZE COUNTERS & VARIABLES
   $newplace=1;         //placement the shuffled question will be in user's test
   $categplace=1;               //change every 10 questions
   $sql="SELECT * FROM $categtable WHERE place='$categplace'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $newcateg=$row[id];
   //ASSIGN QUESTIONS TO USER
   foreach ($numbers as $number)
   {
      if($newplace<=$totalques)        //only giving user $totalques questions
      {
         //get question with place=$number
         $sql="SELECT * FROM $testtable WHERE place='$number'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         //put question in place=$newplace in users test
         $sql="INSERT INTO $answerstable (offid,questionid,place,category) VALUES ('$offid','$row[id]','$newplace','$newcateg')";
         $result=mysql_query($sql);
         //increment place and possibly category
         if($newplace%10==0)
         {
            $categplace++;
            $sql="SELECT * FROM $categtable WHERE place='$categplace'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            $newcateg=$row[id];
         }
         $newplace++;
      }
   }
   //NOW THE USER HAS HIS OR HER OWN RANDOMLY ARRANGED TEST
}

//SHOW TIMER AND EMBED FRAME FOR THE TEST ITSELF:
echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Timer.js"></script>
</head>
<body onLoad="Timer.initialize(<?php echo $session; ?>,'<?php echo $sport?>');Timer.startClock();" style="background-color:#f8f8f8;">
<table class=nine width="100%"><tr align=center><td>
<form>
<input type=hidden id='time' name='time' value='0'>
<div id='timer' name='timer' style='float:left;width:150px;'><label style='font-size:13px;'>Time Remaining</label><br>60:00</div>
</form>
<iframe style="background-color:#ffffff;margin:0px;width:800px;border:none;height:500px;" src='vbtest2_frame.php?session=<?php echo $session; ?>'></iframe>
<br>
<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Scroll down to answer all 10 questions in this section and then click "Go to Next Section," or "Jump To..." a certain section.</i>
</td></tr></table>
<?php
echo $end_html;
?>
