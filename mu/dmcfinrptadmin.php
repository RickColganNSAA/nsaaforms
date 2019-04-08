<?php

require '../functions.php';
require '../variables.php';
require 'dmcfinrpthelper.php';

$header = GetHeader($session);
$level 	= GetLevel($session);

//connect to db:
$db = mysql_connect("$db_host", $db_user, $db_pass);
mysql_select_db($db_name, $db);

//verify user
$schoolid 	= GetSchoolID($session); 
$loginid 	= GetUserID($session);

if ($level==4) {
	$schoolid=0;
}

$mudistid = GetMusicDistrictID($schoolid, $loginid);

if (!ValidUser($session) || $mudistid==0) {
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header;

echo "<br>";

$sql		= "SELECT * FROM mubigdistricts WHERE id='$mudistid'";
$result		= mysql_query($sql);
$row		= mysql_fetch_array($result);
$distnum	= trim($row[distnum]);
$distid		= $row['id'];

$dmcFinRptDb 		= new DmcFinRptDb();
$tmpReports 		= $dmcFinRptDb->getSubmittedByDistNum($distnum);
$financialReports	= array();

// Convert the result set into intelligent models.
foreach ($tmpReports as $report) {
	$financialReports[] = new DmcFinRptModel($report);
}

?>

<style>
	.css_right { float: right }
	#reportTable { width: 100%; }
	#financialReportContainer { width: 90%; margin: auto; }
</style>

<?php if (!empty($financialReports)) { ?>
	<!-- jQuery UI CSS -->
	<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.8.16.custom.css">
	<p><b><u>Submitted</u> District Financial Reports for District <?php echo $distnum; ?>:</b><br><br></p>	
<?php } ?>

<div id="financialReportContainer">
<table cellspacing="0" cellpadding="5" id="reportTable" class="nine" frame="all" rules="all" style="border:#d0d0d0 1px solid;">
<?php if (empty($financialReports)) { ?>
	<tr align=center>
		<td>
			<br><br><i>No financial reports have been submitted yet for District <?php echo $distnum; ?>.  Please check back later.</i><br><br><div style='width:600px;text-align:left;'><i>Financial reports will not show up on this screen until the Site Directors have SUBMITTED them. To work on a financial report BEFORE a site director submits it, you must ask them for their login and login to the NSAA School Login as that site director.</i></div><br>
			<a href="../welcome.php?session=<?php echo $session; ?>">Home</a>
		</td>
	</tr>
<?php } else { ?>
	<thead>
		<tr>
			<th>District Site (click to View/Print)</th>
			<th>(click to Edit)</th>
			<th>Site Director</th>
			<th>Balance</th>
			<th>Date Submitted</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($financialReports as $financialReport) { ?>
		<tr>
			<?php $href = 'dmcfinrptform.php?session=' . $session . '&editid=' . $financialReport->getId() . '&print=1'; ?>
			<?php $editHref = 'dmcfinrptform.php?session=' . $session . '&editid=' . $financialReport->getId(); ?>
			<?php $district = $dmcFinRptDb->getDistrict($financialReport->getDistrictId()); ?>
			<td><a target="_blank" href="<?php echo $href; ?>"><?php echo $distnum." -- ".$district['classes'].", ".$district['site']; ?></a></td>
			<td><a href="<?php echo $editHref; ?>">Edit</a></td>
			<td><?php echo $financialReport->getDirectorSignature(); ?></td>
			<td><?php echo '$' . number_format($financialReport->getBalance(), 2); ?></td>
			<td><?php echo date('F j, Y, \a\t H:ia', $financialReport->getDateSubmitted()); ?></td>
		</tr>
	<?php } ?>
	</tbody>
	<tfoot></tfoot>
</table>
<?php } ?>
</div>

<?php 

echo $end_html;

?>

<?php if (!empty($financialReports)) { ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
	<!--
	
	$(document).ready(function() {
		var columns = [
			{ "bSortable": false },
			{ "bSortable": false },
	       	null,
	       	null,
	       	null
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
