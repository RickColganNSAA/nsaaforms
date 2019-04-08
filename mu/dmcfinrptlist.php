<?php

require '../functions.php';
require '../variables.php';
require 'mufunctions.php';
require 'dmcfinrpthelper.php';

$header	= GetHeader($session);
$level	= GetLevel($session);

//connect to db:
$db	= mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

// Must be the admin!
if (!ValidUser($session) || $level != 1){
   header("Location:../index.php");
   exit();
}

$dmcFinRptDb = new DmcFinRptDb();

if(isset($_GET['resetid']) && $_GET['resetid']>0)
{
    $dmcFinRptDb->ResetReport($_GET['resetid']);
}

if($_GET['resetall']=='yes')
{
   $resetall="yes";
   $sql="DELETE FROM finrpt_entry";
   $result=mysql_query($sql);
   $sql="DELETE FROM finrpt_expense_director";
   $result=mysql_query($sql);
   $sql="DELETE FROM finrpt_expense_judge";
   $result=mysql_query($sql);
   $sql="DELETE FROM finrpt_expense_misc";
   $result=mysql_query($sql);
   $sql="DELETE FROM finrpt_receipts";
   $result=mysql_query($sql);
}

echo $init_html;
echo $header;

$distnum = '';

if ($_POST) {
	if (isset($_POST['distnum']) && !empty($_POST['distnum'])) {
		$distnum = trim($_POST['distnum']);
	}
}

$fullReport = $dmcFinRptDb->getFullReport($distnum);
$districts	= $dmcFinRptDb->getDistrictNumbers();


$heading = 'District Music Contest Financial Reports';

if (isset($distnum) && !empty($distnum)) {
	$heading .= ' for District ' . $distnum;
}

?>
<br />

<style>
	.css_right { float: right }
	#reportTable { width: 100%; }
	#financialReportContainer { width: 90%; margin: auto; }
        label { font-size:12px; }
</style>

	<!-- jQuery UI CSS -->
	<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.8.16.custom.css">
        <p><a href="muadmin.php?session=<?php echo $session; ?>">Return to Main Music Menu</a><br><br></p>
	<p><b><?php echo $heading; ?>:</b></p>
	<form id="filterForm" method="post" action="dmcfinrptlist.php">
	<input type=hidden name="session" value="<?php echo $session; ?>">
		<label for="distnum">Show reports for district:</label>
		<select id="distnum" name="distnum">
			<option value="" <?php echo (empty($distnum)) ? '' : 'selected="selected"'; ?>>All Districts</option>
		<?php foreach ($districts as $district) { ?>
			<option value="<?php echo $district; ?>" <?php echo ($distnum == $district) ? 'selected="selected"' : ''; ?>>
				<?php echo "District ".$district; ?>
			</option>
		<?php } ?>	
		</select>
		<input type="submit" id="submit" name="submit" value="Go" />
	</form>
        <?php 
	$sql="SELECT * FROM finrpt_entry";
	$result=mysql_query($sql);
	if(mysql_num_rows($result)>0): 
	?>
	<div class='help' style='width:400px;text-align:center;'>
	<p><a href="dmcfinrptlist.php?session=<?php echo $session?>&resetall=yes" onClick="return confirm('Are you sure you want to reset all data in the Music Financial Reports?');">Click Here to Reset the Financial Reports for the New Year</a></p>
	</div>
	<?php elseif($resetall=="yes"): ?>
        <div class='help' style='width:400px;text-align:center;'>
        <p>The financial reports have been reset.</p>
	</div>
	<?php endif; ?>

<br />

<div id="financialReportContainer">
<table cellspacing="0" cellpadding="5" id="reportTable" class='nine' frame=all rules=all style="border:#d0d0d0 1px solid;">
	<?php if (empty($fullReport)) { ?>
		<tbody>
			<tr align="center">
				<td><br><br><i>No entries were found.  Please check back at a later time.</i><br><br><br></td>
			</tr>
		</tbody>
	<?php } else { ?>
		<thead>
			<tr>
				<th>District</th>
				<th>Classes</th>
				<th>Site</th>
				<th>Director</th>
				<th>Date Submitted</th>
				<th>View/Print, Edit or Unlock</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($fullReport as $report) { ?>
			<tr>
				<td><?php echo $report['distnum']; ?></td>
				<td><?php echo $report['classes']; ?></td>
				<td><?php echo $report['site']; ?></td>
				<?php if(isset($report['financialReport']['date_submitted']) && $report['financialReport']['date_submitted']>0): ?>
		                         <td><?php echo $report['financialReport']['director_signature']; ?></td>
                        		<td><?php echo date('F j, Y, \a\t H:ia', $report['financialReport']['date_submitted']); ?></td>
                        		<?php $href = 'dmcfinrptform.php?session=' . $session . '&editid=' . $report['financialReport']['id']; ?>
                        		<td><a target="_blank" href="<?php echo $href; ?>&print=1">View/Print Form</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $href?>">Edit</a><p style='margin:3px 1px 1px 0px;padding:0;text-align:right;'>[<a class=small href="dmcfinrptlist.php?session=<?php echo $session; ?>&resetid=<?php echo $report['financialReport']['id']?>">Unlock this Form</a>]</p></td>
				<?php else: ?>
					<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
				<?php endif; ?>
			</tr>
		<?php } ?>
		</tbody>
	<?php } ?>
</table>
</div>
        <br><p><a href="muadmin.php?session=<?php echo $session; ?>">Return to Main Music Menu</a><br><br></p>
<?php 

echo $end_html;

?>

<?php if (!empty($fullReport)) { ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
	<!--
	
	$(document).ready(function() {
		var columns = [
	       	null,
	       	null,
	       	null,
	       	null,
	       	null,
			{ "bSortable": false }
		];
		
	    var oTable = $('#reportTable').dataTable({
	    	"bLengthChange": false,
	       	"iDisplayLength": -1,
	       	"bJQueryUI": true,
	       	"bFilter": false,
	       	"bPaginate": false,
			"aoColumns": columns
		});
	});
	//-->
	</script>
<?php } ?>
