<?php
error_reporting(E_ALL);
/* Include PHPExcel */
require_once './Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("SBI Estate and Premisses Department")
							 ->setLastModifiedBy("SBI Estate and Premisses Department")
							 ->setTitle("Pending Compliance Details")
							 ->setSubject("Document")
							 ->setDescription("excel report")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("report");

$curCol = "A";
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($curCol.'1', 'Branch Code')
            ->setCellValue(++$curCol.'1', 'Load Balancing & Distribution')
			->setCellValue(++$curCol.'1', 'Date')
            ->setCellValue(++$curCol.'1', 'MCCB/ELCB/MCB condition')
			->setCellValue(++$curCol.'1', 'Date')
            ->setCellValue(++$curCol.'1', 'Proper earthing')
			->setCellValue(++$curCol.'1', 'Date')
			->setCellValue(++$curCol.'1', 'Replacement of old wires')
			->setCellValue(++$curCol.'1', 'Date')
			->setCellValue(++$curCol.'1', 'Emergency lights provision')
			->setCellValue(++$curCol.'1', 'Date')
			->setCellValue(++$curCol.'1', 'Scraps/old materials removal')
			->setCellValue(++$curCol.'1', 'Date')
			->setCellValue(++$curCol.'1', 'Proper ventilation')
			->setCellValue(++$curCol.'1', 'Date')
			->setCellValue(++$curCol.'1', 'Periodical maintenance')
			->setCellValue(++$curCol.'1', 'Date')
			->setCellValue(++$curCol.'1', 'AC timers condition')
			->setCellValue(++$curCol.'1', 'Date')
			->setCellValue(++$curCol.'1', 'Power factor correction')
			->setCellValue(++$curCol.'1', 'Date');

createConnection($con);
//Finding the maximum number of other observations found in branch
$sqlQuery = "select count(*) as topper from other_observations group by branch_code order by topper desc limit 0,1";
$query=mysqli_query($con,$sqlQuery);
$row = mysqli_fetch_row($query);
$max_observation_number = $row[0];

for ($ctr = 0; $ctr < $max_observation_number ; $ctr++){
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue(++$curCol.'1','Observation '.($ctr + 1).' Value')
				->setCellValue(++$curCol.'1','Observation '.($ctr + 1).' Status')
				->setCellValue(++$curCol.'1','Observation '.($ctr + 1).' Date');
}

$sqlQuery = "select * from audit_information";
$query=mysqli_query($con,$sqlQuery);

$curRow = 2;
while($row = mysqli_fetch_array($query))
{
	$branch_code = $row['branch_code'];
	$balancingAndDistribution_status = $row['balancing'];
	//echo $balancingAndDistribution_status;
	$balancingAndDistribution_date= $row['balancing_date'];
	$mccb_status= $row['mccb'];
	//echo $mccb_status;
	$mccb_date= $row['mccb_date'];
	$earthing_status= $row['earthing'];
	$earthing_date= $row['earthing_date'];
	$oldWireReplace_status= $row['wire_replacement'];
	$oldWireReplace_date= $row['wire_replacement_date'];
	$emergencyLamp_status= $row['emergency_lights'];
	$emergencyLamp_date= $row['emergency_lights_date'];
	$scrapsRemoval_status= $row['scrap_removal'];
	$scrapsRemoval_date= $row['scrap_removal_date'];
	$ventilation_status= $row['ventilation'];
	$ventilation_date= $row['ventilation_date'];
	$maintenance_status= $row['periodical_maintanance'];
	$maintenance_date= $row['periodical_maintanance_date'];
	$acTimers_status= $row['ac_timers'];
	$acTimers_date= $row['ac_timers_date'];
	$powerFactor_status= $row['power_factor'];
	$powerFactor_date= $row['power_factor_date'];
	$otherObservationsCount = $row['other_observations_count'];
	$submissionDate = $row['date_of_entry'];
	
	//Since this page generated the pending compliance report, we individually check the status for value 'No',
	//which means still audit compliance is pending
	if($balancingAndDistribution_status != "No" && $mccb_status != "No" && $earthing_status != "No"
	   && $oldWireReplace_status != "No" && $emergencyLamp_status != "No" && $scrapsRemoval_status != "No"
	   && $ventilation_status != "No" && $maintenance_status != "No" && $acTimers_status != "No" && $powerFactor_status != "No")
	{
		//Checking for 'No' in other observations
		$hasPedningOtherObservation = false;
		for($currentObservation = 1; $currentObservation <= $otherObservationsCount;$currentObservation++)
		{
			getObservationEntry($branch_code,$currentObservation,$obsText,$obsStatus,$obsDate);
			if($obsStatus === 'No') $hasPedningOtherObservation = true;
		}
		if($hasPedningOtherObservation) continue;
	}
	else
	{
		continue;
	}
	//Reworking the dates
	$balancingAndDistribution_date = ($balancingAndDistribution_date === "0000-00-00")?"":date("d/m/Y",strtotime($balancingAndDistribution_date));
	$mccb_date = ($mccb_date === "0000-00-00")?"":date("d/m/Y",strtotime($mccb_date));
	$earthing_date = ($earthing_date === "0000-00-00")?"":date("d/m/Y",strtotime($earthing_date));
	$oldWireReplace_date = ($oldWireReplace_date === "0000-00-00")?"":date("d/m/Y",strtotime($oldWireReplace_date));
	$emergencyLamp_date = ($emergencyLamp_date === "0000-00-00")?"":date("d/m/Y",strtotime($emergencyLamp_date));
	$scrapsRemoval_date = ($scrapsRemoval_date === "0000-00-00")?"":date("d/m/Y",strtotime($scrapsRemoval_date));
	$ventilation_date = ($ventilation_date === "0000-00-00")?"":date("d/m/Y",strtotime($ventilation_date));
	$maintenance_date = ($maintenance_date === "0000-00-00")?"":date("d/m/Y",strtotime($maintenance_date));
	$acTimers_date = ($acTimers_date === "0000-00-00")?"":date("d/m/Y",strtotime($acTimers_date));
	$powerFactor_date = ($powerFactor_date === "0000-00-00")?"":date("d/m/Y",strtotime($powerFactor_date));

	$curCol = "A";	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($curCol.$curRow,$branch_code)
				->setCellValue(++$curCol.$curRow,$balancingAndDistribution_status)
				->setCellValue(++$curCol.$curRow,$balancingAndDistribution_date)
				->setCellValue(++$curCol.$curRow,$mccb_status)
				->setCellValue(++$curCol.$curRow,$mccb_date)
				->setCellValue(++$curCol.$curRow,$earthing_status)
				->setCellValue(++$curCol.$curRow,$earthing_date)
				->setCellValue(++$curCol.$curRow,$oldWireReplace_status)
				->setCellValue(++$curCol.$curRow,$oldWireReplace_date)
				->setCellValue(++$curCol.$curRow,$emergencyLamp_status)
				->setCellValue(++$curCol.$curRow,$emergencyLamp_date)
				->setCellValue(++$curCol.$curRow,$scrapsRemoval_status)
				->setCellValue(++$curCol.$curRow,$scrapsRemoval_date)
				->setCellValue(++$curCol.$curRow,$ventilation_status)
				->setCellValue(++$curCol.$curRow,$ventilation_date)
				->setCellValue(++$curCol.$curRow,$maintenance_status)
				->setCellValue(++$curCol.$curRow,$maintenance_date)
				->setCellValue(++$curCol.$curRow,$acTimers_status)
				->setCellValue(++$curCol.$curRow,$acTimers_date)
				->setCellValue(++$curCol.$curRow,$powerFactor_status)
				->setCellValue(++$curCol.$curRow,$powerFactor_date);
	for($currentObservation = 1; $currentObservation <= $otherObservationsCount;$currentObservation++)
	{
		getObservationEntry($branch_code,$currentObservation,$obsText,$obsStatus,$obsDate);
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue(++$curCol.$curRow,$obsText)
				->setCellValue(++$curCol.$curRow,$obsStatus)
				->setCellValue(++$curCol.$curRow,$obsDate);
	}
	$curRow++;
}

//Formatting the Excel 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet(0)
			->getStyle('A1:AZ1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '80E080'))));
$objPHPExcel->getActiveSheet(0)->getRowDimension('1')->setRowHeight(-1);
$objPHPExcel->getActiveSheet()->getStyle('A1:AZ1')
    ->getAlignment()->setWrapText(true); 
//calling set auto size twice to for single character columns and double character seperately
foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        //->setAutoSize(true)
		->setWidth(20);
}
foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension('A'.$columnID)
        //->setAutoSize(true);
		->setWidth(20);
}
$objPHPExcel->getActiveSheet()->getStyle('A1:AZ1')->getAlignment()->setShrinkToFit(true);
/*foreach($objPHPExcel->getActiveSheet()->getColumnDimensions() as $col) {
    $col->setColumnWidth(1000);
}*/
$objPHPExcel->getActiveSheet()->setTitle('Completed Status');
$objPHPExcel->setActiveSheetIndex(0);
mysqli_close($con);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Completed Branch Electrical and Safety Audit Compliance.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

function createConnection(&$con)
{
	$con = new mysqli("localhost", "root", "", "electrical_audit");
	if ($con->connect_errno) {
		die("Connection failed: " . $conn->connect_error);
	}
}
function getObservationEntry($branch_code, $serial_no, &$obsText, &$obsStatus, &$obsDate)
{
	global $con;
	$query=mysqli_query($con,"select *  from other_observations where branch_code = '".$branch_code."' and observation_serial='".$serial_no."'");
	$row = mysqli_fetch_array($query);
	$obsText = $row['observation_text'];
	$obsStatus = $row['rectified_status'];
	$obsDate = $row['rectified_date'];
	$obsDate = ($obsDate === "0000-00-00")?"":date("d/m/Y",strtotime($obsDate));
	if($obsText === "" || $obsStatus === "")
	{
		die("processing error: invalid other observations found for ".$branch_code." branch on observation no ".$serial_no);
	}

}
/*
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Branch Code')
            ->setCellValue('B1', 'Balancing and Distribution of Electrical loads')
            ->setCellValue('C1', 'MCCB/ELCB/MCB are in working condition, with proper rating/capacity')
            ->setCellValue('D1', 'Proper earthing carried out/connected and reports are in palce')
			->setCellValue('E1', 'Replacement of old wires, if any')
			->setCellValue('F1', 'Provision of emergency lights')
			->setCellValue('G1', 'Removal of scraps/old materials in electrical panel rooms/UPS/Batteries/DBs')
			->setCellValue('H1', 'Proper ventilation arrangements for panel room/UPS room/electrical room provided')
			->setCellValue('I1', 'Periodical Electrical maintenance is carried out')
			->setCellValue('J1', 'AC timers are working')
			->setCellValue('K1', 'Power factor correction panels are provided');
*/
?>