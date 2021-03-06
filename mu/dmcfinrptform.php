<?php

require '../functions.php';
require '../variables.php';
require 'mufunctions.php';
require 'dmcfinrpthelper.php';

$header = GetHeader($session);
$level 	= GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if ($level != 1) 
{
   $schoolid=GetSchoolID($session); $loginid=GetUserID($session);
   if($level==5 || $level==4)	//COLLEGE
   {
      $musiteid=GetMusicSiteID(0,$loginid);
      $mudistid=GetMusicDistrictID(0,$loginid);
   }
   else
   {
      $musiteid=GetMusicSiteID($schoolid);
      $mudistid=GetMusicDistrictID($schoolid,$loginid);
   }
}

// User validation.
$isValidUser = ValidUser($session);

if (false === $isValidUser) {
	header("Location:../index.php");
	exit();
}

if($editid)
{
   $sql="SELECT * FROM finrpt_entry WHERE id='$editid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0) $distid=$row[district_id];
}
else if($musiteid)
   $distid=$musiteid;
if($musiteid && !$editid)
{
   $sql="SELECT * FROM finrpt_entry WHERE district_id='$musiteid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0) $editId=$row[id];
}

$dmcFinRptDb 	= new DmcFinRptDb();
$districtInfo	= $dmcFinRptDb->getDistrict($distid);

if (!empty($districtInfo)) {
	$districtNum	= $districtInfo['districtNumber'] . ' - ' . $districtInfo['classes'];
	$contestSite	= $districtInfo['site'];
	$contestDates	= $districtInfo['contestDates'];	
} 

$formProcessor = new FormProcessor();
$showPrintView = 0;

// Container for the values if pulled from somewhere else.
$values = array();

if ($editId > 0 || (isset($_GET['editid']) && !empty($_GET['editid']))) {
	// Retrieve the saved financial report using the edit id passed.
	if (!$editId) {
		$editId = (int)$_GET['editid'];
	}
	
	$values 			= $dmcFinRptDb->getFinancialReport($editId);
	$financialReport 	= new DmcFinRptModel($values);
	
	$coordOK = 0;
	
	// For security purposes, make sure any non-Level 1 user has permission to see this form
	// COORDINATOR: Check that Site is within their District
    if ($mudistid > 0) {
		$sql = "SELECT t1.id FROM mudistricts AS t1, mubigdistricts AS t2 WHERE t1.distnum=t2.distnum AND t2.id='$mudistid'";
		$result = mysql_query($sql);
		while ($row=mysql_fetch_array($result)) {
			if ($financialReport->getDistrictId() == $row[id]) {
	 	 		$coordOK = 1;
			}
		}
			
			// SITE WAS NOT FOUND IN THIS COORDINATOR'S DISTRICT
			if (!$coordOK && $musiteid!=$financialReport->getDistrictId()) {
				echo "We're sorry, we could not find this financial report.";
				exit();
			}
		
    } else if($musiteid && $financialReport->getDistrictId() != $musiteid) {
	   echo "We're sorry, you are not the director of this Music District Contest site.";
	   exit();
 	}

	// Check to see if the "datesub" has been set greater than zero.  If it has, that means this school has already 
	// submitted the financial report for the final time and may not edit it. If the coordinator is viewing this, or
	// the adminuser is viewing it, the date submitted does not matter, and they have the privilege to edit.
	if (!empty($financialReport) && $financialReport->getDateSubmitted() > 0 &&
		$coordOK != 1 && 
		$level != 1 || 
		(isset($_GET['print']) && $_GET['print'] == 1)) {
		
		$showPrintView 	= 1;
		$balance 		= number_format($financialReport->getBalance(), 2);
		if(($mudistid==0 || $musiteid == $financialReport->getDistrictId()) && $level!=1)
		{
			$alert = "<font style=\"font-size:10pt;color:blue\"><b>You submitted this form to the NSAA on ".date("m/d/Y",$financialReport->getDateSubmitted())." at ".date("g:ia T",$financialReport->getDateSubmitted()).".</b></font> Be sure to "
			. '<a href="javascript:printEntry();">PRINT this screen</a> and MAIL the printed form and a check for <strong><u>$' . $balance
			. '</u></strong> to your NSAA District Treasurer. Then click <a href="../welcome.php?session=' . $session . '">Return Home</a>';
		}
	}
	
	// When a successul save has happened.
	if (isset($_GET['saved']) && $_GET['saved'] == 1) {
		$alert = 'This form has been saved but it has NOT been submitted to the NSAA or the District Music Coordinator. Please continue working '
			   . 'on your form (now or later) until you are finished, at which time you can click "Submit as Final District Financial Report." '
			   . '<br /><a href="../welcome.php?session=' . $session . '">Return Home</a>';
	}
        else if (isset($_GET['saved']) && $_GET['saved'] == 2) {
                $alert = '<div class="alert" style="width:300px">This form has been saved.</div><br /><a href="../welcome.php?session=' . $session . '">Return Home</a>';
        }
} 

if ($_POST) {
	$validData 		 = $formProcessor->getDmcFinRpt($_POST);
	$financialReport = new DmcFinRptModel($validData);
	
	$submitType = trim(strtolower($_POST['submit_type']));
	
	// If they used the "final" submit button, set the date submitted in the report object.
	if ($submitType == 'final_submittal') {
		$financialReport->date_submitted = time();
	}
	else if($submitType == 'post_submittal') {
		$financialReport->date_submitted = $_POST['date_submitted'];
	}

	try {
		$saved = $dmcFinRptDb->save($financialReport);
		
		if ($saved) {
			if ($submitType == 'post_submittal') {
				//This is a save AFTER the initial "final" submission.
				header("Location:dmcfinrptform.php?session=" . $session . "&editid=" . $saved . "&saved=2");
				exit;
			} else if($submitType == 'final_submittal') {
				//Initial Final Submission
				header("Location:dmcfinrptform.php?session=".$session."&editid=".$saved."&print=1");
				exit();
			} else {
				header("Location:dmcfinrptform.php?session=" . $session . "&editid=" . $saved . "&saved=1");
				exit;
			}
		}
	} catch (Exception $e)  {
		var_dump($e->getMessage());
		var_dump($e->getTraceAsString());
	}
}

/*
 * Initialize the HTML.
 */
echo $init_html;
	
if ($showPrintView == 0) {
	echo $header . "<br>";
        if($level==1)
           echo "<a href=\"dmcfinrptlist.php?session=$session\">Return to Music Financial Reports</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
href=\"muadmin.php?session=$session\">Return to Main Music Menu</a><br>";
}
else {
 	echo "<table class='nine' width='100%'><tr align=center><td><br>";
}

?>

<!-- jQuerification -->
<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.8.16.custom.css">
<style>
<!--
div { font-size:12px; }
div.formSection
{
   width:800px;
}
#receiptContainer h3, 
#judgesfeeContainer h3 	{ text-align: center; }

#formHeading 			{ width: 60%; margin: auto; }
#submit 				{ width: 200px; }
#signature 				{ width: 50%; }
#submitContainer		{ border: 1px dotted #000; padding: 6px; margin:6px; }
#submitContainer input[type="button"] { width: 300px; margin: 4px; }
#submitContainer p		{ width: 90%; margin: auto; }
 
.center 				{ text-align: center; } 
.label 					{ width: 300px; float: left; text-align: right; padding-right: 10px; font-weight:bold;}
.value 					{ clear: right; text-align: left; }
.strong 				{ font-weight: bold; font-size: 16px; }

-->
</style>
<?php

$alertAttr = '';
if (empty($alert)) {
        $alertAttr = 'style="display:none;width:500px;"';
}
else if($editId > 0) {
        $alertAttr = 'style="width:90%;"';
}
else {
        $alertAttr = 'style="width:500px;"';
}
?>
<div id="alertContainer" <?php echo $alertAttr; ?>>
        <?php echo $alert; ?>
</div>

<div id="formHeading">
	<h3 class="center"><?php echo str_replace('%y', date('Y'), Constants::FORM_HEADING); ?></h3>
</div>

<div class="formSection">
	<div class="label"><label for="district_number">District #:</label></div>
    <div class="value"><?php echo $districtNum; ?></div>
    <div class="label"><label for="contest_site">Contest Site:</label></div>
    <div class="value"><?php echo $contestSite; ?></div>
    <div class="label"><label for="submission_date">Date of Contest:</label></div>
    <div class="value"><?php echo $contestDates; ?></div>
    <?php if($editId > 0): ?>
	<?php if($financialReport->getDateSubmitted() >0): ?>
	<div class="label"><label for="submission_date">Date Report Submitted:</label></div>
	<div class="value"><?php echo date("F j, Y, g:ia", $financialReport->getDateSubmitted()); ?></div>
	<?php endif; ?>
    <?php endif; ?>
</div>

<?php 

$errorAttr = '';

if (empty($error)) {
	$errorAttr = 'style="display:none;width:500px;"';
}
else {
	$errorAttr = 'style="width:500px;"';
}

?>

<div id="errorContainer" class="error" <?php echo $errorAttr; ?>>
	<?php echo $error; ?>
</div>
<form method="post" action="dmcfinrptform.php?session=<?php echo $session; ?>">

	<div class="formSection">
		<h3>RECEIPTS</h3>

		<table cellspacing="0" cellpadding="2" class="nine">
		<tr>	
		<td align="right">Regular Entry Fees</td>
			<td align="left">$<input type="text" class="receipt" id="reg_entry_fees" name="receipts[reg_entry_fees]" 
				value="<?php echo isset($financialReport) ? $financialReport->getReceiptAmount('reg_entry_fees') : null; ?>" /></td>
		</tr>
		<tr align="left"><td align="right">Added Entry Fees after Deadline</td>
			<td>$<input type="text" class="receipt" id="added_entry_fees" name="receipts[added_entry_fees]" 
				value="<?php echo isset($financialReport) ? $financialReport->getReceiptAmount('added_entry_fees') : null; ?>" /></td></tr>
                <tr align="left"><td align="right">$50 Late Admin Fee after Deadline</td>
                        <td>$<input type="text" class="receipt" id="late_admin_fee" name="receipts[late_admin_fee]"
                                value="<?php echo isset($financialReport) ? $financialReport->getReceiptAmount('late_admin_fee') : null; ?>" /></td></tr>
		<tr align="left"><td align="right">Admission Receipts</td>
			<td>$<input type="text" class="receipt" id="admission_receipts" name="receipts[admission_receipts]" 
				value="<?php echo isset($financialReport) ? $financialReport->getReceiptAmount('admission_receipts') : null; ?>" /></td></tr>
		<tr valign="top" align="left"><td align="right">Other Receipts</td>
			<td>$<input type="text" class="receipt" id="other_receipts" name="receipts[other_receipts]" 
				value="<?php echo isset($financialReport) ? $financialReport->getReceiptAmount('other_receipts') : null; ?>" />
				<br><i>Please specify the source of Other Receipts, if applicable:</i> <br>
				<textarea rows=3 cols=50 id="other_receipts_notes" name="notes[other_receipts]"><?php echo isset($financialReport) ? $financialReport->getReceiptNotes('other_receipts') : null; ?></textarea>
			</td>
		</tr>
		<tr align="left"><td align="right">TOTAL RECEIPTS</td>
			<td>$<input type="text" class="total" id="receipt_total" name="receipts[receipt_total]" readonly="true" /></td></tr>
		</table>
	</div>
		
	
	<div>
		<h3>EXPENSES</h3>
		
		<h4>Judges' Fees:</h4>
		
		<table id="judgeFeeTable" class="nine" cellspacing="0" cellpadding="3">
			<thead>
				<tr align=left>
					<th></th>
					<th>Name of Judge</th>
					<th>&nbsp;</th>
					<th>Fee</th>
					<th>&nbsp;</th>
					<th>Mileage</th>
					<th>&nbsp;</th>
					<th>Lodging</th>
					<th>&nbsp;</th>
					<th>Other</th>
					<th>&nbsp;</th>
					<th>Other Desc</th>
					<th>&nbsp;</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$i 			= 1; 
					$j 			= 0;
					$maxRows 	= Constants::DEFAULT_JUDGE_FEE_ROWS;
					
					/*
					 * If a financial report is set (editing), use the count of the judge fee records to 
					 * determine how many rows to show on editing.  Otherwise the default set above is used.
					 * Using this method also makes for setting the values for purposes of editing much more
					 * "coder friendly." There is ever only one actual row's worth of HTML to maintain.
					 */
					if (isset($financialReport) && count($financialReport->getJudgeFees()) > 0) {
						$judgeFees 	= $financialReport->getJudgeFees();
						$maxRows 	= count($judgeFees);
					}
				?>
				<?php while ($i <= $maxRows) { ?>
					
					<tr class="judgeRow" valign="center" align=left>
						<td valign="top"><?php echo $i; ?>.<input type="hidden" class="iterator" id="row_<?php echo $i; ?>" name="row_<?php echo $i; ?>" value="<?php echo $i; ?>" /></td>
						<td valign="top">
							<?php $curvalue=isset($judgeFees) ? $judgeFees[$j]->name : null; ?>
							<input type="text" class="judgename" id="judge_name_<?php echo $i; ?>" name="judges[<?php echo $i; ?>][name]" value="<?php echo $curvalue;?>" />
						</td>
						<td align="right" valign="top" width='20px'>$</td>
						<td valign="top">
							<?php $curvalue=isset($judgeFees) ? $judgeFees[$j]->fee : null; ?>
							<input type="text" class="judgefee feetype" id="judge_fee_<?php echo $i; ?>" name="judges[<?php echo $i; ?>][fee]" value="<?php echo $curvalue; ?>" />
						</td>
						<td width='20px' align=right valign="top">$</td>
						<td valign="top">
							<?php $curvalue=isset($judgeFees) ? $judgeFees[$j]->mileage : null; ?>
							<input type="text" class="judgefee mileagetype" id="judge_mileage_<?php echo $i; ?>" name="judges[<?php echo $i; ?>][mileage]" value="<?php echo $curvalue; ?>" />
						</td>
						<td width='20px' align=right valign="top">$</td>
						<td valign="top">
							<?php $curvalue=isset($judgeFees) ? $judgeFees[$j]->lodging : null; ?>
							<input type="text" class="judgefee lodgingtype" id="judge_lodging_<?php echo $i; ?>" name="judges[<?php echo $i; ?>][lodging]" value="<?php echo $curvalue; ?>" />
						</td>
						<td width='20px' align=right valign="top">$</td>
						<td valign="top">
							<?php $curvalue=isset($judgeFees) ? $judgeFees[$j]->other : null; ?>
							<input type="text" class="judgefee othertype" id="judge_other_<?php echo $i; ?>" name="judges[<?php echo $i; ?>][other]" value="<?php echo $curvalue; ?>" />
						</td>
						<td>&nbsp;</td>
						<td>
							<?php 
								if (isset($judgeFees) && !empty($judgeFees[$j]->otherdesc)) {
									$otherdesc = $judgeFees[$j]->otherdesc;
								} else {
									$otherdesc = '';	
								}
							?>
							<textarea id="judge_otherdesc_<?php echo $i; ?>" class="otherdesc" name="judges[<?php echo $i; ?>][otherdesc]"><?php echo $otherdesc; ?></textarea>
						</td>
						<td valign="top" width='20px' align=right>$</td>
						<td valign="top">
							<input type="text" class="judgefee totaltype" id="judge_total_<?php echo $i; ?>" name="judges[<?php echo $i; ?>][total]" readonly="readonly" /></td>
					</tr>
					
				<?php $i++; $j++; ?>		
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td align=right>TOTALS:</td>
					<td width='20px' align=right>$</td>
					<td><input type="text" class="total" id="judge_fee_total" name="judge_fee_total" readonly="readonly" /></td>
					<td width='20px' align=right>$</td>
					<td><input type="text" class="total" id="judge_mileage_total" name="judge_mileage_total" readonly="readonly" /></td>
					<td width='20px' align=right>$</td>
					<td><input type="text" class="total" id="judge_lodging_total" name="judge_lodging_total" readonly="readonly" /></td>
					<td width='20px' align=right>$</td>
					<td><input type="text" class="total" id="judge_other_total" name="judge_other_total" readonly="readonly" /></td>
					<td></td>
					<td></td>
					<td width='20px' align=right>$</td>
					<td><input type="text" class="total" id="judge_grand_total" name="judge_grand_total" readonly="readonly" /></td>
				</tr>
				<tr>
					<td><input type="hidden" id="maxRows" name="maxRows" value="<?php echo $maxRows; ?>" /></td>
					<td colspan="10">
						<button id="addAdditionalJudge">Add Additional Judge Fee</button>
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	</div>
		

	
	<div class="formSection">
	<h4>Host Site's Expenses:</h4>
		<table cellspacing=0 cellpadding=4 class='nine'>
		<tr><td align=right>Programs</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_programs" name="misc[programs]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('programs') : null; ?>" />
		</td></tr>
		<tr><td align=right>Telephone &amp; Fax</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_phonefax" name="misc[phonefax]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('phonefax') : null; ?>" />
		</td></tr>
		<tr><td align=right>Postage</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_postage" name="misc[postage]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('postage') : null; ?>" />
		</td></tr>
		<tr><td align=right>Supplies</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_supplies" name="misc[supplies]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('supplies') : null; ?>" />
		</td></tr>
		<tr><td align=right>Piano rental/delivery</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_pianorent" name="misc[pianorent]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('pianorent') : null; ?>" />
		</td></tr>
		<tr><td align=right>Piano tuning</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_pianotune" name="misc[pianotune]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('pianotune') : null; ?>" />
		</td></tr>
		<tr><td align=right>Hospitality judges/workers</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_hospitality" name="misc[hospitality]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('hospitality') : null; ?>" />
		</td></tr>
		<tr><td align=right>Site rental/expense</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_siterental" name="misc[siterental]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('siterental') : null; ?>" />
		</td></tr>
		<tr valign=top><td align=right>Event Administrator fees</td><td align=left>
			<table style="margin-top:0px;" cellspacing=0 cellpadding=2>
				<thead>
					<tr align=lrft>
						<th>&nbsp;</th>
						<td>Name</td>
						<th>&nbsp;</th>
						<td>Amount</td>
					</tr>
				</thead>
				<tbody>
				<?php
					$i 			= 1; 
					$j 			= 0;
					$maxRows 	= Constants::DEFAULT_DIRECTOR_FEE_ROWS;
					
					/*
					 * If a financial report is set (editing), use the count of the director fee records to 
					 * determine how many rows to show on editing.  Otherwise the default set above is used.
					 * Using this method also makes for setting the values for purposes of editing much more
					 * "coder friendly." There is ever only one actual row's worth of HTML to maintain.
					 */
					if (isset($financialReport) && count($financialReport->getDirectorFees()) > 0) {
						$directorFees 	= $financialReport->getDirectorFees();
						$maxRows 		= count($directorFees);
					}
				?>
				<?php while ($i <= $maxRows) { ?>
					<tr class="directorRow" valign="center" align=left>
						<td valign="top"><?php echo $i; ?>.<input type="hidden" class="dir_iterator" id="dir_row_<?php echo $i; ?>" name="dir_row_<?php echo $i; ?>" value="<?php echo $i; ?>" /></td>
						<td valign="top">
							<?php $curvalue=isset($directorFees) ? $directorFees[$j]->name : null; ?>
							<input type="text" class="directorname" id="director_name_<?php echo $i; ?>" name="director[<?php echo $i; ?>][name]" value="<?php echo $curvalue;?>" />
						</td>
						<td width='20px' align=right valign="top">$</td>
						<td valign="top">
							<?php $curvalue=isset($directorFees) ? $directorFees[$j]->amount : null; ?>
							<input type="text" class="directoramount" id="directot_amount_<?php echo $i; ?>" name="director[<?php echo $i; ?>][amount]" value="<?php echo $curvalue; ?>" />
						</td>
					</tr>						
				<?php $i++; $j++; ?>
				<?php } ?>
				</tbody>
			</table>
		</td></tr>
		<tr><td align=right>Other</td><td align=left>
			$<input type="text" class="miscellaneous" id="misc_other" name="misc[other]" 
				value="<?php echo isset($financialReport) ? $financialReport->getMiscAmount('other') : null; ?>" />
		</td></tr>
		<tr><td>&nbsp;</td><td align=left><i>Please specify the source of Other Host Site Expenses, if applicable:</i> <br>
			<textarea rows=3 cols=50 id="other_receipts_notes" name="notes[other]"><?php echo isset($financialReport) ? $financialReport->getMiscNotes('other') : null; ?></textarea>
		</td></tr>
		<tr><td align=right>Host Site's Total</td><td align=left>
			$<input type="text" class="total" id="misc_grand_total" name="misc[grand_total]" readonly="readonly" />
		</td></tr>
		</table>
		<br>
		
		<div class="label strong"><label for="expense_total">TOTAL EXPENSES</label></div>
		<div class="value">
			$<input type="text" class="total" id="expense_total" name="expense_total" readonly="readonly" />
		</div>
		
		<br>
		
		<div class="label strong"><label for="balance">BALANCE</label></div>
		<div class="value">
			$<input type="text" class="total" id="balance" name="balance" readonly="readonly" />
		</div>
		
		<br>
	</div>

        <div class="formSection">

	
	<p id="mailNote" class="formSection" style='text-align:left'>
		(You will mail a check for the surplus amount or a statement of the deficit to your NSAA District Treasurer 
		with this report. You will be given a printer-friendly version of this form to print and mail to the Treasurer 
		once you complete this report and click "Submit as Final District Financial Report" below.)
	</p>
	
		<label for="signature">
			<b>District Music Contest Director Electronic Signature</b> (Please type the name of the Director):
		</label>
		<br />
		<input type="text" id="signature" name="signature" 
			value="<?php echo isset($financialReport) ? $financialReport->getDirectorSignature() : null; ?>" />
	</div>
	
	<br clear="all" />
	
	<?php if ($showPrintView == 0) { ?>
		<?php if(!empty($financialReport) && $financialReport->getDateSubmitted() > 0)	//THIS IS BEING EDITED AFTER SUBMISSION
		{
		?>
                <input type="button" id="postSubmittal" name="postSubmittal" value="Save as Final District Financial Report" class='fancybutton2' />
		<?php
		} else {
		?>
	<div id="submitContainer" class="formSection">
		<input type="button" id="save" name="save" value="Save &amp; Return Later" class='fancybutton2' />
		<p style="margin-bottom:10px;text-align:left;">
			You can save this form and return to it as often as you would like.  <b><u>When you are ready to submit this form
			as the <em>FINAL</em> financial report</b></u> for your district site, click <b>"Submit as Final District Financial Report"</b> below.
		</p>
	</div>        <br>
	<div id="submitContainer" class="formSection">
		<input type="button" id="finalSubmittal" name="finalSubmittal" value="Submit as Final District Financial Report" class='fancybutton2' />
		<p style='color:#ff0000;font-weight:bold;'>You have not yet submitted this financial report to your music coordinator and NSAA office.</p>
		<p style='text-align:left;margin-top:5px;'>
			Click this button when you are ready to submit this report to the NSAA and the District Music Coordinator.
			 <b><u>You will no longer be able to make changes to this form once you submit the final report.</b></u>
		</p>
	</div>
	<?php }  } ?>
	
	<input type="hidden" id="submit_type" name="submit_type" value="submit" />
	<input type="hidden" id="districtid" name="districtid" value="<?php echo $distid; ?>" />
	<input type="hidden" id="session" name="session" value="<?php echo $session; ?>" />
	<input type="hidden" id="id" name="id" value="<?php echo isset($editId) ? $editId : null; ?>" />
	<input type="hidden" id="secret" name="secret" value="<?php echo isset($secret) ? $secret : null; ?>" />
	<input type="hidden" id="printing" name="printing" value="<?php echo $showPrintView; ?>" />
	<input type="hidden" id="date_submitted" name="date_submitted" value="<?php echo isset($editId) ? $financialReport->date_submitted : null; ?>" />	
	<div style="display:none;"><input type="submit" id="realSubmit" name="realSubmit" /></div>
</form>

<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>

<script type="text/javascript">
<!--

function calculateReceipts()
{
	var totalReceipts = getTotal('receipt');
	$('#receipt_total').val(parseFloat(totalReceipts).toFixed(2));
}

function calculateJudgeRowTotals()
{
	$('.iterator').each(function() {
		var iterator 	= $(this).val();
		var fee 		= isNaN(parseFloat($('#judge_fee_' + iterator).val())) ? 0 : parseFloat($('#judge_fee_' + iterator).val());
		var mileage 	= isNaN(parseFloat($('#judge_mileage_' + iterator).val())) ? 0 : parseFloat($('#judge_mileage_' + iterator).val());
		var lodging 	= isNaN(parseFloat($('#judge_lodging_' + iterator).val())) ? 0 : parseFloat($('#judge_lodging_' + iterator).val());
		var other		= isNaN(parseFloat($('#judge_other_' + iterator).val())) ? 0 : parseFloat($('#judge_other_' + iterator).val());
		var judgeTotal 	= fee + mileage + lodging + other;
		
		$('#judge_total_' + iterator).val(parseFloat(judgeTotal).toFixed(2));
	});
}

function calculateJudgesFees()
{
	calculateJudgeRowTotals();
	
	var totalFees = getTotal('feetype');
	$('#judge_fee_total').val(parseFloat(totalFees).toFixed(2));

	var totalMileage = getTotal('mileagetype');
	$('#judge_mileage_total').val(parseFloat(totalMileage).toFixed(2));

	var totalLodging = getTotal('lodgingtype');
	$('#judge_lodging_total').val(parseFloat(totalLodging).toFixed(2));

	var totalOther = getTotal('othertype');
	$('#judge_other_total').val(parseFloat(totalOther).toFixed(2));
	
	var totalJudgesFees = getTotal('totaltype');
	$('#judge_grand_total').val(parseFloat(totalJudgesFees).toFixed(2));
}

function calculateMiscellaneous()
{
	var totalMiscellaneous 	= getTotal('miscellaneous');
	var totalDirector		= getTotal('directoramount');
	var total				= totalMiscellaneous + totalDirector;
	$('#misc_grand_total').val(parseFloat(total).toFixed(2));
}

function calculateExpenses()
{
	var judgeValue 		= parseFloat($('#judge_grand_total').val());
	var miscValue		= parseFloat($('#misc_grand_total').val());
	var judgeGrandTotal = 0;
	var miscGrandTotal 	= 0;	

	if (isNaN(judgeValue)) {
		judgeValue = 0;
	}

	if (isNaN(miscValue)) {
		miscValue = 0;
	}

	judgeGrandTotal = judgeValue;
	miscGrandTotal 	= miscValue;
	
	var totalExpenses = judgeGrandTotal + miscGrandTotal;
	$('#expense_total').val(parseFloat(totalExpenses).toFixed(2));
}

function getTotal(type)
{
	var total = 0;
	
	$('.' + type).each(function() {
		var value = parseFloat($(this).val());

		if (isNaN(value)) {
			value = 0;
		}

		total = total + value;
	});

	return total;
}

function calculateBalance()
{
	var receiptValue = parseFloat($('#receipt_total').val());
	var expenseValue = parseFloat($('#expense_total').val());
	var totalReceipts = 0;
	var totalExpenses = 0;

	if (isNaN(receiptValue)) {
		receiptValue = 0;
	}

	if (isNaN(expenseValue)) {
		expenseValue = 0;
	}

	totalReceipts = receiptValue;
	totalExpenses = expenseValue;

	var balance	= totalReceipts - totalExpenses;

	$('#balance').val(parseFloat(balance).toFixed(2));
}

function calculateAll()
{
	calculateReceipts();
	calculateJudgesFees();
	calculateMiscellaneous();
	calculateExpenses();
	calculateBalance();
	calculateJudgeRowTotals();
}

function checkFloat(value)
{
	if (isNaN(parseFloat(value)) && value != '') {
		return false;
	}

	return true;
}

function validateEntry()
{
	var message = '';
	
	// First make sure that some values have been entered.
	var hasValue = false;

	$('.receipt, .judgefee, .miscellaneous, .directoramount').each(function() {
		var value = $(this).val();

		if (value.length > 0 && value != 0) {
			hasValue = true;
		}
	});

	if (false === hasValue) {
		message += "<p>No values were entered.  The form will not be submitted.  Please enter at least one value.</p>";
	}

	// There must be a receipts total.
	var totalReceipts = getTotal('receipt');

	/*
	if (totalReceipts <= 0) {
		message += "<p>The 'Receipts' total must be greater than zero.</p>";
	}
	*/

	// Check to make sure at least one judge was entered.
	var hasJudgeName = false;
	
	$('.judgename').each(function() {
		if ($(this).val().length > 0) {
			hasJudgeName = true;

			// If the judge has a name, there must be at least one amount.
			var iterator 	= $(this).attr('id').split('_')[2];
			var judgeTotal 	= parseFloat($('#judge_total_' + iterator).val());

			if (judgeTotal <= 0) {
				message += "<p>Judge row '" + iterator + "' has a name entered, but no amounts. "
						+ "Please either remove the name, or enter the amounts.</p>";
			}
		}	
	});

	if (false === hasJudgeName) {
		message += "<p>You must enter information for at least one judge.</p>";
	}

	// Check that the entry has been "signed".
	var signature = $('#signature').val();

	if (signature.length == 0) {
		message += "<p>You must electronically sign the report by typing the name of the Host Director into the "
				+ "'District Music Contest Director Electronic Signature' field.</p>";
	}

	// If any errors were found, render the message.
	if (message.length > 0) {
		renderErrorMessage(message);
		return false;
	}	
	
	return true;
}

function clearErrorMessage()
{
	$('#errorContainer').html('').hide();
}

function renderErrorMessage(message)
{
	$('#errorContainer').html(message).show();
	scroll(0, 0);
}

function submitForm()
{
	clearErrorMessage();
	var valid = validateEntry();

	if (true === valid) {
		$('#realSubmit').click();
	} else {
		return false;
	}
}

function renderPrintView()
{
	$('input').each(function() {
                var value = $(this).val();
		var type = $(this).attr('type');
		if(type!="hidden")
                   $(this).replaceWith(value);
	});
	$('input').css("color", "#00000");
        $('input').css("font-size", "12px");
        $('input').css("backgroundColor", "FFF");
	$('#submitContainer').remove();
	$('#addAdditionalJudge').remove();
	$('#mailNote').remove();

	$('textarea').each(function() {
		var value = $(this).val();
		$(this).replaceWith('<p style="width:200px;">' + value + '</p>');
	});
}

function printEntry()
{
	window.print();
}

function setupBlurEvents()
{
	$('.receipt, .judgefee, .miscellaneous, .directoramount').blur(function() {
		var value 	= $(this).val().replace(',', '');
		var isFloat = checkFloat(value);

		if (value.length == 0) {
			$(this).val(0.00);
			calculateAll();
		} else if (true === isFloat) {
			$(this).val(parseFloat(value).toFixed(2));
			calculateAll();
		} else {
			alert("Please enter a valid dollar amount.");
			$(this).val(0.00);
			$(this).focus();
			return false;
		}
	});
}

function addJudgeRow()
{
	var rowHtml = $('.judgeRow:last').html();
	var maxRows = parseInt($('#maxRows').val());
	var newRow 	= maxRows + 1;

	// Replace the old number with the new.
	var regEx = new RegExp(maxRows, "g");
	rowHtml = rowHtml.replace(regEx, newRow);

	// Append the new HTML table row to the table.
	$('#judgeFeeTable tbody').append('<tr class="judgeRow">' + rowHtml + '</tr>');

	// Update the value of "maxRows" to reflect the added row.
	$('#maxRows').val(newRow);

	// Clear the values for the inputs on the copied row. (Don't clear the iterator input!)
	$('.judgeRow:last input').each(function() {
		if (!$(this).hasClass('iterator')) {
			$(this).val('');
		}
	});

	// Because the additional row was not initially in the DOM, reinitialize the blur events.
	setupBlurEvents();

	// For good measure.
	calculateAll();
}

function fitTextareaContent()
{
	var elements = [];
	var textarea;
	var scrollHeight = 0;
	
    $('textarea').each(function() {
        elements.push($(this).attr('id'));
    });

    for (var i = 0; i < elements.length; i++) {
    	textarea = document.getElementById(elements[i]);
    	scrollHeight = textarea.scrollHeight + 10;
        textarea.style.height = (scrollHeight) + 'px';
    }
}

$(document).ready(function() {
	fitTextareaContent();
	
	calculateAll();

	var printing = $('#printing').val();

	if (printing > 0) {
		renderPrintView();
	}

	$('#save').click(function() {
		$('#submit_type').val('submit');
		calculateAll();
		submitForm();
	});

	$('#finalSubmittal').click(function() {
		$('#submit_type').val('final_submittal');
		calculateAll();
		submitForm();
	});

        $('#postSubmittal').click(function() {
                $('#submit_type').val('post_submittal');
                calculateAll();
                submitForm();
        });

	setupBlurEvents();

	$('#addAdditionalJudge').click(function() {
		addJudgeRow();
		return false;
	});

});

//-->
</script>

<?php
 
echo $end_html; 

?>
