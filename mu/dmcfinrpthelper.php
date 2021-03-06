<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');


class Constants
{
	const FORM_HEADING = 'NEBRASKA SCHOOL ACTIVITIES ASSOCIATION %y DISTRICT MUSIC CONTEST FINANCIAL REPORT';
	const DEFAULT_JUDGE_FEE_ROWS = 12;
	const DEFAULT_DIRECTOR_FEE_ROWS = 3;
}

class DmcFinRptDb
{
	/**
	 * Grab a single district record.
	 * 
	 * @param 	int 	$districtId
	 * @return 	array
	 */
	public function getDistrict($districtId = null)
	{
		$result = $this->getDistricts($districtId);
		
		if (!empty($result)) {
			return $result[0];
		}
		
		return array();
	}
	
	/**
	 * Grab multiple district records.
	 * 
	 * @param 	int 		$districtId
	 * @throws 	Exception
	 * @return 	array
	 */
	public function getDistricts($districtId = null)
	{
		$sql = "SELECT * "
			 . "FROM mudistricts ";

		if (!empty($districtId)) {
			$sql .= " WHERE id = " . (int)$districtId;
		}
		
		$result = mysql_query($sql);
		
		if (mysql_error()) {
			throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error());
		}
		
		$cleanResult = $this->cleanDistrictResult($result);
		
		return $cleanResult;
	}
	
	/**
	 * Cleans up the results from querying for districts.
	 * 
	 * @param 	array $result
	 * @return 	array  
	 */
	public function cleanDistrictResult($result)
	{
		$clean = array();
		
		while ($row = mysql_fetch_array($result)) {
			$formattedDates = '';
			$contestDates 	= explode('/', $row['dates']);
			
			foreach ($contestDates as $date) {
				$formattedDates .= date('F d, Y', strtotime($date)) . ' / ';
			}
			
			$clean[] = array(
				'id' 				=> $row['id'],
				'districtNumber' 	=> $row['distnum'],
				'classes' 			=> $row['classes'],
				'classList' 		=> $row['classlist'],
				'dates' 			=> $row['dates'],
				'site' 				=> $row['site'],
				'director' 			=> $row['director'],
				'email' 			=> $row['email'],
				'districtId1' 		=> $row['distid1'],
				'districtId2' 		=> $row['distid2'],
				'contestDates'		=> rtrim($formattedDates, ' / '),
				'distnum'		=> $row['distnum']
			);
		}
		
		return $clean;
	}
	
	/**
	 * Insert or update a financial report.
	 * Both processes save a report entry first and use the returned id to
	 * delete all sub records and save new sub records.  
	 * 
	 * @param 	DmcFinRptModel $dmcFinRpt
	 * @return 	int
	 */
	public function save(DmcFinRptModel $dmcFinRpt)
	{
		$id = $dmcFinRpt->getId();
		
		if (!empty($id)) {
			$saved = $this->update($id, $dmcFinRpt);
		} else {
			$saved = $this->insert($dmcFinRpt);
		}
		
		if ($saved) {
			$reportId 		= $saved;
			$receipts 		= $dmcFinRpt->getReceipts();
			$judgeFees 		= $dmcFinRpt->getJudgeFees();
			$miscFees 		= $dmcFinRpt->getMiscFees();
			$directorFees 	= $dmcFinRpt->getDirectorFees();
			
			// Delete any prior receipts and save the new data.
			$this->deleteReceipts($reportId);
			
			foreach ($receipts as $receipt) {
				$this->saveReceipt($reportId, $receipt);
			}
			
			// Delete any prior judge fees and save the new data.
			$this->deleteJudgeFees($reportId);
			
			foreach ($judgeFees as $judgeFee) {
				$this->saveJudgeFee($reportId, $judgeFee);
			}
			
			// Delete any prior miscellaneous fees and save the new data.
			$this->deleteMiscFees($reportId);
			
			foreach ($miscFees as $miscFee) {
				$this->saveMiscFee($reportId, $miscFee);
			}
			
			// Delete any prior director fees and save the new data.
			$this->deleteDirectorFee($reportId);
			
			foreach ($directorFees as $directorFee) {
				$this->saveDirectorFee($reportId, $directorFee);
			}
			
			return $saved;
		}
		
		return false;
	}
	
	/**
	 * Update a financial report.
	 * 
	 * @param 	int 			$reportId
	 * @param 	DmcFinRptModel 	$dmcFinRpt
	 * @throws 	Exception
	 * @return 	int
	 */
	private function update($reportId, DmcFinRptModel $dmcFinRpt)
	{
		$districtId 	= $dmcFinRpt->getDistrictId();
		$directorSig 	= trim(addslashes($dmcFinRpt->getDirectorSignature()));
		$dateSubmitted	= $dmcFinRpt->getDateSubmitted();
		
		if (empty($dateSubmitted)) {
			$dateSubmitted = 0;
		}
		
		$sql = "UPDATE finrpt_entry SET district_id = {$districtId}, director_signature = '{$directorSig}', "
			 . " datesub = {$dateSubmitted} WHERE id = {$reportId};";
		
		$result = mysql_query($sql);
		
		if (mysql_error()) {
			throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error());
		}
		
		return $reportId;
	}
	
	/**
	 * Insert a financial report.
	 * 
	 * @param 	DmcFinRptModel $dmcFinRpt
	 * @throws 	Exception
	 * @return 	int
	 */
	private function insert(DmcFinRptModel $dmcFinRpt)
	{
		$districtId 	= $dmcFinRpt->getDistrictId();
		$directorSig 	= trim(addslashes($dmcFinRpt->getDirectorSignature()));
		$dateSubmitted	= $dmcFinRpt->getDateSubmitted();
		
		if (empty($dateSubmitted)) {
			$dateSubmitted = 0;
		}

		//Check to make sure they didn't hit refresh on their browser - we should only have one report per district
		$sql = "SELECT * FROM finrpt_entry WHERE district_id='$districtId'";
	 	$result=mysql_query($sql);
		if(mysql_num_rows($result)==0)
		{
		
			$sql = "INSERT INTO finrpt_entry (district_id, director_signature, created, datesub) VALUES "
			 . "({$districtId}, '{$directorSig}', CURRENT_TIMESTAMP, $dateSubmitted);";
		
			$result = mysql_query($sql);
		}
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		$id = mysql_insert_id();
		
		return $id;
	}
	
	/**
	 * Save a receipt object for the financial report.
	 * 
	 * @param 	int 		$reportId
	 * @param 	Receipt 	$receipt
	 * @throws 	Exception
	 */
	private function saveReceipt($reportId, Receipt $receipt)
	{
		$type 	= trim(addslashes($receipt->type));
		$amount = number_format($receipt->amount, 2,'.','');
		$notes	= trim(addslashes($receipt->notes));
		
		$sql = "INSERT INTO finrpt_receipts (entry_id, type, amount, notes) "
			 . "VALUES ({$reportId}, '{$type}', '{$amount}', '{$notes}');";
		
		$result = mysql_query($sql);
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error().'<br>'.$sql); }
	}
	
	/**
	 * Save a judge fee object for the financial report.
	 * 
	 * @param 	int 		$reportId
	 * @param 	JudgeFee 	$judgeFee
	 * @throws 	Exception
	 */
	private function saveJudgeFee($reportId, JudgeFee $judgeFee)
	{
		$name 		= trim(addslashes($judgeFee->name));
		$fee 		= number_format((float)$judgeFee->fee, 2,'.','');
		$mileage 	= number_format((float)$judgeFee->mileage, 2,'.','');
		$lodging 	= number_format((float)$judgeFee->lodging, 2,'.','');
		$other		= number_format((float)$judgeFee->other, 2, '.', '');
		$otherdesc 	= trim(addslashes($judgeFee->otherdesc));

		if ($name != '' && ($fee > 0 || $mileage > 0 || $lodging > 0)) {
			$sql = "INSERT INTO finrpt_expense_judge (entry_id, name, fee, mileage, lodging, other, otherdesc) "
			 . "VALUES ($reportId, '{$name}', {$fee}, {$mileage}, {$lodging}, {$other}, '{$otherdesc}');";
		
			$result = mysql_query($sql);
			if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error().'<br>'.$sql); }
		}
	}
	
	/**
	 * Save a miscellaneous fee for the financial report.
	 * 
	 * @param 	int 		$reportId
	 * @param 	MiscFee 	$miscFee
	 * @throws 	Exception
	 */
	private function saveMiscFee($reportId, MiscFee $miscFee)
	{	
		$type 	= trim(addslashes($miscFee->type));
		$amount = number_format((float)$miscFee->amount, 2,'.','');
		$notes	= trim(addslashes($miscFee->notes));
		
		$sql = "INSERT INTO finrpt_expense_misc (entry_id, type, amount, notes) "
			 . "VALUES ($reportId, '{$type}', {$amount}, '{$notes}');";
		
		$result = mysql_query($sql);
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
	}
	
	/**
	 * Save a director fee for the financial report.
	 * 
	 * @param 	int 		$reportId
	 * @param 	DirectorFee $directorFee
	 * @throws 	Exception
	 */
	private function saveDirectorFee($reportId, DirectorFee $directorFee)
	{
		$name 	= trim(addslashes($directorFee->name));
		$amount = number_format((float)$directorFee->amount, 2, '.', '');
		
		$sql = "INSERT INTO finrpt_expense_director (entry_id, name, amount) "
			 . "VALUES($reportId, '{$name}', {$amount});";
		
		$result = mysql_query($sql);
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
	}
	
	/**
	 * Delete all receipts for the report id given.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 */
	public function deleteReceipts($reportId)
	{
		$sql = "DELETE FROM finrpt_receipts WHERE entry_id = {$reportId};";
		$result = mysql_query($sql);
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
	}
	
	/**
	 * Delete all judge fees for the report id given.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 */
	public function deleteJudgeFees($reportId)
	{
		$sql = "DELETE FROM finrpt_expense_judge WHERE entry_id = {$reportId};";
		$result = mysql_query($sql);
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }		
	}
	
	/**
	 * Delete all miscellaneous fees for the report id given.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 */
	public function deleteMiscFees($reportId)
	{
		$sql = "DELETE FROM finrpt_expense_misc WHERE entry_id = {$reportId};";
		$result = mysql_query($sql);
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
	}
	
	/**
	 * Delete all director fees for the report id given.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 */
	public function deleteDirectorFee($reportId)
	{
		$sql = "DELETE FROM finrpt_expense_director WHERE entry_id = {$reportId};";
		$result = mysql_query($sql);
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
	}
	
	/**
	 * Grab a single financial report.
	 * 
	 * @param 	int 	$id
	 * @param	int		$districtId
	 * @return 	array
	 */
	public function getFinancialReport($id = null, $districtId = null)
	{
		$result = $this->getFinancialReports($id, $districtId);
		
		if (!empty($result)) {
			return $result[0];
		}
		
		return array();
	}
	
	/**
	 * Grab multiple financial reports.
	 * 
	 * @param 	int 		$id
	 * @param 	int 		$districtId
	 * @throws 	Exception
	 * @return 	array
	 */
	public function getFinancialReports($id = null, $districtId = null)
	{
		$sql = "SELECT * FROM finrpt_entry ";
		
		if (!empty($id)) {
			$sql .= "WHERE id = {$id} ";
			
			if (!empty($districtId)) {
				$sql .= "AND district_id = {$districtId} ";
			}
		} else if (!empty($districtId)) {
			$sql .= "WHERE district_id = {$districtId} ";	
		}
		
		$result = mysql_query($sql);
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		$cleanResult = $this->cleanFinRptResult($result);
		
		// Combine the other sections.  Receipts, and Expenses.
		foreach ($cleanResult as &$report) {
			$report['receipts'] 	= $this->getReceipts($report['id']);
			$report['judges'] 		= $this->getJudgeFees($report['id']);
			$report['misc'] 		= $this->getMiscellaneousFees($report['id']);
			$report['director'] 	= $this->getDirectorFees($report['id']); 
		}
		
		return $cleanResult;
	}
	
	public function getFinancialReportsByDistNum($districtNumber)
	{
		/*
                $districtNumber = strtoupper($districtNumber);
                $sql                    = "SELECT id FROM mudistricts WHERE distnum = '{$districtNumber}';";
                $result                 = mysql_query($sql);

                if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }

                $reports = array();

                while ($row = mysql_fetch_array($result)) {
			$tmpReports = $this->getFinancialReports(null, $row['id']);
                        $reports        = array_merge($reports, $tmpReports);
                }

                return $reports;
			*/
		$districtNumber = strtoupper($districtNumber);
		$sql 			= "SELECT id FROM mudistricts ";
		if($districtNumber!='') $sql.="WHERE distnum = {$districtNumber};";
		$result 		= mysql_query($sql);
		//echo "SQL: ".$sql;
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		$reports = array();
		
		while ($row = mysql_fetch_array($result)) {
			$tmpReports = $this->getFinancialReports(null, $row['id']);
			$reports = array_merge($reports, $tmpReports);
		}
		
		return $reports;
	}
	
	/**
	 * Returns reports that have been submitted.
	 * 
	 * @param 	int 	$id
	 * @param 	int 	$districtId
	 * @return 	array
	 */
	public function getSubmittedFinancialReports($id = null, $districtId = null)
	{
		$financialReports 	= array();
		$tmpReports 		= $this->getFinancialReports($id, $districtId);
		
		foreach ($tmpReports as $report) {
			if ($report['date_submitted'] > 0) {
				$financialReports[] = $report;
			}
		}
		
		return $financialReports;
	}
	
	/**
	 * Returns reports that have been submitted for all sites for the passed district.
	 * 
	 * @param 	string 		$districtNumber
	 * @throws 	Exception
	 * @return 	array
	 */
	public function getSubmittedByDistNum($districtNumber)
	{
		$districtNumber = strtoupper($districtNumber);
		$sql 			= "SELECT id FROM mudistricts WHERE distnum = '{$districtNumber}';";
		$result 		= mysql_query($sql);
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		$reports = array();
		
		while ($row = mysql_fetch_array($result)) {
			$tmpReports = $this->getSubmittedFinancialReports(null, $row['id']);
			$reports 	= array_merge($reports, $tmpReports);
		}
		
		return $reports;
	}

	public function ResetReport($entryid)
	{
		$sql = "UPDATE finrpt_entry SET datesub='0' WHERE id='$entryid'";
		$result=mysql_query($sql);
	}
	
	/**
	 * Retrieve full list of sites and their respective district music financial reports.
	 * If a districtNumber is provided, the list only pulls the sites for that district.
	 * 
	 * @param 	string 		$districtNumber
	 * @throws 	Exception
	 * @return 	array
	 */
	public function getFullReport($districtNumber = null)
	{
		$districtNumber = strtoupper($districtNumber);
		$sql 			= "SELECT * FROM mudistricts WHERE multiplesite!='x'";
		$order			= " ORDER BY distnum";
		
		if (!empty($districtNumber)) {
			$sql .= " AND distnum = '{$districtNumber}'";
		}
		
		$sql .= $order;
		
		$result = mysql_query($sql);
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		$reports = array();
		while ($row = mysql_fetch_array($result)) {
			$reportRow = $row;
			$tmpReport = $this->getFinancialReport(null, $row['id']);
			$reportRow['financialReport'] = $tmpReport;
			$reports[] = $reportRow;
			unset($tmpReport);
		}
		
		return $reports;
	}
	
	public function getDistrictNumbers()
	{
		return array('I', 'II', 'III', 'IV', 'V', 'VI');
	}

	/**
	 * Retrieve the receipts for the specified financial report.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 * @return 	array  
	 */
	public function getReceipts($reportId)
	{
		$sql = "SELECT * FROM finrpt_receipts WHERE entry_id = {$reportId};";
		$result = mysql_query($sql);
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		return $this->cleanReceiptResult($result);
	}
	
	/**
	 * Retrieve the judge fees for the specified financial report.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 * @return 	array 
	 */
	public function getJudgeFees($reportId)
	{
		$sql = "SELECT * FROM finrpt_expense_judge WHERE entry_id = {$reportId};";
		$result = mysql_query($sql);
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		return $this->cleanJudgeFeeResult($result);
	}
	
	/**
	 * Retrieve the miscellaneous fees for the specified financial report.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 * @return 	array  
	 */
	public function getMiscellaneousFees($reportId)
	{
		$sql = "SELECT * FROM finrpt_expense_misc WHERE entry_id = {$reportId};";
		$result = mysql_query($sql);
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		return $this->cleanMiscellaneousFeeResult($result);
	}
	
	/**
	 * Retrieve the director fees for the specified financial report.
	 * 
	 * @param 	int 		$reportId
	 * @throws 	Exception
	 * @return 	array
	 */
	public function getDirectorFees($reportId)
	{
		$sql = "SELECT * FROM finrpt_expense_director WHERE entry_id= {$reportId};";
		$result = mysql_query($sql);
		
		if (mysql_error()) { throw new Exception(__METHOD__ . ' : UNEXPECTED ERROR: ' . mysql_error()); }
		
		return $this->cleanDirectorFeeResult($result);
	}
	
	/**
	 * Format the results of the financial report query.
	 * 
	 * @param 	array $result
	 * @return 	array  
	 */
	private function cleanFinRptResult($result)
	{
		$clean = array();
		
		while ($row = mysql_fetch_array($result)) {
			$clean[] = array(
				'id' 					=> $row['id'],
				'district_id' 			=> $row['district_id'],
				'director_signature' 	=> $row['director_signature'],
				'modified' 				=> $row['modified'],
				'created' 				=> $row['created'],
				'date_submitted'		=> $row['datesub']	
			);
		}
		
		return $clean;
	}
	
	/**
	 * Format the result of the receipts query.
	 * 
	 * @param 	array $result
	 * @return 	array  
	 */
	private function cleanReceiptResult($result)
	{
		$clean = array();
		
		while ($row = mysql_fetch_array($result)) {
			$clean[] = array(
// 				'entry_id' 	=> $row['entry_id'],
				'type' 		=> stripslashes($row['type']),
				'amount' 	=> number_format((float)$row['amount'], 2,'.',''),
				'notes'		=> stripslashes($row['notes'])
			);
		}
		
		return $clean;
	}
	
	/**
	 * Format the results of the judge fee query.
	 * 
	 * @param	array $result
	 * @return 	array  
	 */
	private function cleanJudgeFeeResult($result)
	{
		$clean = array();
		
		while ($row = mysql_fetch_array($result)) {
			$clean[] = array(
// 				'entry_id' 	=> $row['entry_id'],
				'name' 		=> stripslashes($row['name']),
				'fee' 		=> number_format((float)$row['fee'], 2,'.',''),
				'mileage' 	=> number_format((float)$row['mileage'], 2,'.',''),
				'lodging' 	=> number_format((float)$row['lodging'], 2,'.',''),
				'other'		=> number_format((float)$row['other'], 2, '.', ''),
				'otherdesc' => stripslashes($row['otherdesc'])	
			);
		}
		
		return $clean;
	}
	
	/**
	 * Format the result of the miscellaneous fee query.
	 * 
	 * @param 	array $result
	 * @return 	array
	 */
	private function cleanMiscellaneousFeeResult($result)
	{
		$clean = array();
		
		while ($row = mysql_fetch_array($result)) {
			$clean[] = array(
// 				'entry_id' 	=> $row['entry_id'],
				'type' 		=> stripslashes($row['type']),
				'amount' 	=> number_format((float)$row['amount'], 2,'.',''),
				'notes'		=> stripslashes($row['notes'])
			);
		}
		
		return $clean;
	}
	
	/**
	 * Format the result of the director fee query.
	 * 
	 * @param 	array $result
	 * @return 	array
	 */
	private function cleanDirectorFeeResult($result)
	{
		$clean = array();
		
		while ($row = mysql_fetch_array($result)) {
			$clean[] = array(
				'name' 		=> stripslashes($row['name']),
				'amount' 	=> number_format((float)$row['amount'], 2, '.', '')		
			);
		}
		
		return $clean;
	}
}

class FormProcessor
{
	/**
	 * Takes in the posted data and reformats it to a logical array.
	 * 
	 * @param 	array 			$data
	 * @return 	array
	 */
	public function getDmcFinRpt($data)
	{
		$financialReport = array(
			'id' 					=> $data['id'],
			'district_id' 			=> $data['districtid'],
			'director_signature' 	=> $data['signature'],
			'receipts' 				=> array(),
			'judges' 				=> array(),
			'misc' 					=> array()		
		);
		
		if (isset($data['receipts']) && !empty($data['receipts'])) {
			foreach ($data['receipts'] as $key => $value) {
				if ($key != 'receipt_total') {
					// Search for a matching note for the receipt.
					$notes = $this->getMatchingNotes($data, $key);
					
					$financialReport['receipts'][] = array(
						'type' 		=> $key,
						'amount' 	=> $value,
						'notes'		=> $notes
					);
				}
			}
		}
		
		if (isset($data['judges']) && !empty($data['judges'])) {
			foreach ($data['judges'] as $key => $judge) {
				if ($key != 'judge_grand_total') {
					$financialReport['judges'][] = array(
						'name' 		=> $judge['name'],
						'fee' 		=> $judge['fee'],
						'mileage' 	=> $judge['mileage'],
						'lodging' 	=> $judge['lodging'],
						'other'		=> $judge['other'],
						'otherdesc' => $judge['otherdesc']	
					);
				}
			}
		}
				
		if (isset($data['misc']) && !empty($data['misc'])) {
			foreach ($data['misc'] as $key => $value) {
				if ($key != 'grand_total') {
					// Search for a matching note for the miscellaneous fee.
					$notes = $this->getMatchingNotes($data, $key);
					
					$financialReport['misc'][] = array(
						'type' 		=> $key,
						'amount' 	=> $value,
						'notes'		=> $notes	
					);
				}
			}
		}
		
		if (isset($data['director']) && !empty($data['director'])) {
			foreach ($data['director'] as $key => $director) {
				$financialReport['director'][] = array(
					'name' 		=> $director['name'],
					'amount' 	=> $director['amount']	
				);
			}
		}
		
		return $financialReport;
	}
	
	public function getMatchingNotes($data, $type)
	{
		$note = '';
		
		if (isset($data['notes']) && !empty($data['notes'])) {
			foreach ($data['notes'] as $key => $value) {
				if ($key == $type) {
					$note = $value;
				}
			}
		}
		
		return $note;
	}
}

class Receipt
{
	public $type 	= '';
	public $amount 	= 0;
	public $notes	= '';
}

class JudgeFee
{
	public $name 		= '';
	public $fee			= 0;
	public $mileage		= 0;
	public $lodging 	= 0;
	public $other		= 0;
	public $otherdesc	= '';
	public $total		= 0;
}

class MiscFee
{
	public $type	= '';
	public $amount	= 0;
	public $notes	= '';
}

class DirectorFee
{
	public $name	= '';
	public $amount	= 0;
}

class DmcFinRptModel
{
	public $id					= null;
	public $receipts 			= array();
	public $judgeFees			= array();
	public $miscFees			= array();
	public $directorFees		= array();
	public $district_id			= null;
	public $date_submitted		= 0;
	public $director_signature 	= '';
	private $balance			= 0;
	
	public function __construct(array $data = array())
	{
		if (!empty($data)) {
			$this->setFromArray($data);
		}
	}
	
	public function addReceipt(Receipt $receipt) 				{ $this->receipts[] 	= $receipt; }
	public function addJudgeFee(JudgeFee $judgeFee) 			{ $this->judgeFees[] 	= $judgeFee; }
	public function addMiscFee(MiscFee $miscFee) 				{ $this->miscFees[] 	= $miscFee; }
	public function addDirectorFee(DirectorFee $directorFee) 	{ $this->directorFees[] = $directorFee; }
	
	public function getId() 				{ return $this->id; }
	public function getReceipts() 			{ return $this->receipts; }
	public function getJudgeFees() 			{ return $this->judgeFees; }
	public function getMiscFees() 			{ return $this->miscFees; }
	public function getDirectorFees()		{ return $this->directorFees; }
	public function getDistrictId() 		{ return $this->district_id; }
	public function getDateSubmitted()		{ return $this->date_submitted; }
	public function getDirectorSignature() 	{ return $this->director_signature; }
	
	public function getBalance()
	{
		$this->calculateBalance();
		return $this->balance;	
	}
	
	private function calculateBalance()
	{
		$totalReceipts 		= $this->getTotalReceipts();
		$totalJudgeFees 	= $this->getTotalJudgeFees();
		$totalMiscFees 		= $this->getTotalMiscFees();
		$totalDirectorFees 	= $this->getTotalDirectorFees();
		$totalExpenses 		= $totalJudgeFees + $totalMiscFees + $totalDirectorFees;
		$balance 			= $totalReceipts - $totalExpenses;
		$this->balance 		= $balance;
	}
	
	public function getTotalReceipts()
	{
		$total = 0;
		
		foreach ($this->getReceipts() as $receipt) {
			$total += $receipt->amount;
		}
		
		return $total;
	}
	
	public function getTotalJudgeFees()
	{
		$total = 0;
		
		foreach ($this->getJudgeFees() as $judgeFee) {
			$judgeTotal = 	$judgeFee->fee +
							$judgeFee->lodging +
							$judgeFee->mileage +
							$judgeFee->other;
			
			$total += $judgeTotal;
		}
		
		return $total;
	}
	
	public function getTotalMiscFees()
	{
		$total = 0;
		
		foreach ($this->getMiscFees() as $miscFee) {
			$total += $miscFee->amount;
		}
		
		return $total;
	}
	
	public function getTotalDirectorFees()
	{
		$total = 0;
		
		foreach ($this->getDirectorFees() as $directorFee) {
			$total += $directorFee->amount;
		}
		
		return $total;
	}
	
	/**
	 * Populates the object instance with the details passed.
	 * 
	 * @param 	array 			$data
	 * @return 	DmcFinRptModel
	 */
	public function setFromArray(array $data)
	{
		$this->id 					= $data['id'];
		$this->district_id 			= $data['district_id'];
		$this->director_signature 	= $data['director_signature'];
		$this->date_submitted		= $data['date_submitted'];
		if(empty($data['date_submitted']) && !empty($data['datesub']))
			$this->date_submitted = $data['datesub'];
		
		if (isset($data['receipts']) && !empty($data['receipts'])) {
			foreach ($data['receipts'] as $r) {
				$receipt 			= new Receipt();
				$receipt->type 		= $r['type'];
				$receipt->amount 	= $r['amount'];
				$receipt->notes		= $r['notes'];
		
				$this->addReceipt($receipt);
			}
		}
		
		if (isset($data['judges']) && !empty($data['judges'])) {
			foreach ($data['judges'] as $judge) {
				// Will ignore any empty judge values.
				if (!empty($judge['name'])) {
					$judgeFee 			= new JudgeFee();
					$judgeFee->name 	= $judge['name'];
					$judgeFee->fee		= $judge['fee'];
					$judgeFee->mileage 	= $judge['mileage'];
					$judgeFee->lodging 	= $judge['lodging'];
					$judgeFee->other	= $judge['other'];
					$judgeFee->otherdesc= $judge['otherdesc'];
					$judgeFee->total	= $judge['total'];
		
					$this->addJudgeFee($judgeFee);
				}
			}
		}
		
		if (isset($data['misc']) && !empty($data['misc'])) {
			foreach ($data['misc'] as $m) {
				$miscFee 			= new MiscFee();
				$miscFee->type 		= $m['type'];
				$miscFee->amount 	= $m['amount'];
				$miscFee->notes		= $m['notes'];
		
				$this->addMiscFee($miscFee);
			}
		}
		
		if (isset($data['director']) && !empty($data['director'])) {
			foreach ($data['director'] as $d) {
				if (!empty($d['name'])) {
					$directorFee 			= new DirectorFee();
					$directorFee->name		= $d['name'];
					$directorFee->amount 	= $d['amount'];
					
					$this->addDirectorFee($directorFee);
				}
			}
		}
		
		return $this;
	}
	
	public function getReceiptAmount($type)
	{
		foreach ($this->getReceipts() as $receipt) {
			if ($receipt->type == $type) {
				return $receipt->amount;
			}
		}
		
		return null;
	}
	
	public function getReceiptNotes($type)
	{
		foreach ($this->getReceipts() as $receipt) {
			if ($receipt->type == $type) {
				return $receipt->notes;
			}
		}
	}
	
	public function getMiscAmount($type)
	{
		foreach ($this->getMiscFees() as $misc) {
			if ($misc->type == $type) {
				return $misc->amount;
			}
		}
		
		return null;
	}
	
	public function getMiscNotes($type)
	{
		foreach ($this->getMiscFees() as $misc) {
			if ($misc->type == $type) {
				return $misc->notes;
			}
		}
	}

	public function isSubmitted()
	{
		return $this->date_submitted > 0;
	}
}

