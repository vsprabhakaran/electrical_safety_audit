<?php
session_start();
$con = new mysqli("localhost", "root", "", "electrical_audit");
if ($con->connect_errno) {
  die("Connection failed: " . $conn->connect_error);
}

if(!(isset($_SESSION["esa_brcode"]) && $_SESSION["esa_brcode"] != "")){
	redirect("loginPage.php");
	return;
}
if($_SESSION["esa_brcode"] === "admin")
	redirect("adminPage.php");
		
$branch_code = $_SESSION["esa_brcode"];
$con->autocommit(FALSE);
if((($_POST["post_from"] === "newEntry") && alreadyExistingEntry($branch_code)) || (($_POST["post_from"] === "oldEntry") && !alreadyExistingEntry($branch_code))){
	echo "<body>Something seems to be wrong!!!. :( .</body>";
	return;
}
else if($_POST["post_from"] === "oldEntry"){
	
	$delQuery = "delete from audit_information where branch_code = '".$branch_code."'";
	$queryStatus = mysqli_query($con,$delQuery);
	if(!$queryStatus)
	{
		echo "<br><h2>Database Error!!!</h2><br>";
		$con->rollback();
		return;
	}
	$delQuery = "delete from other_observations where branch_code = '".$branch_code."'";
	$queryStatus = mysqli_query($con,$delQuery);
	if(!$queryStatus)
	{
		echo "<br><h2>Database Error!!!</h2><br>";
		$con->rollback();
		return;
	}
	
}
$sqlQuery = "insert into audit_information(branch_code, balancing, balancing_date,mccb,mccb_date,earthing,earthing_date,wire_replacement,wire_replacement_date,emergency_lights,emergency_lights_date,scrap_removal,scrap_removal_date,ventilation,ventilation_date,periodical_maintanance,periodical_maintanance_date,ac_timers,ac_timers_date,power_factor,power_factor_date,other_observations_count,finalized) values("
			//."'"+ +"'".
			."'". $branch_code ."',"
			."'". $_POST["balancingAndDistribution_r"]."',"
			."STR_TO_DATE('". $_POST["balancingAndDistribution_d"] ."','%d/%m/%Y'),"
			."'". $_POST["mccb_r"]."',"
			."STR_TO_DATE('". $_POST["mccb_d"] ."','%d/%m/%Y'),"
			."'". $_POST["earthing_r"]."',"
			."STR_TO_DATE('". $_POST["earthing_d"] ."','%d/%m/%Y'),"
			."'". $_POST["oldWireReplace_r"]."',"
			."STR_TO_DATE('". $_POST["oldWireReplace_d"] ."','%d/%m/%Y'),"
			."'". $_POST["emergencyLamp_r"]."',"
			."STR_TO_DATE('". $_POST["emergencyLamp_d"] ."','%d/%m/%Y'),"
			."'". $_POST["scrapsRemoval_r"]."',"
			."STR_TO_DATE('". $_POST["scrapsRemoval_d"] ."','%d/%m/%Y'),"
			."'". $_POST["ventilation_r"]."',"
			."STR_TO_DATE('". $_POST["ventilation_d"] ."','%d/%m/%Y'),"
			."'". $_POST["maintenance_r"]."',"
			."STR_TO_DATE('". $_POST["maintenance_d"] ."','%d/%m/%Y'),"
			."'". $_POST["acTimers_r"]."',"
			."STR_TO_DATE('". $_POST["acTimers_d"] ."','%d/%m/%Y'),"
			."'". $_POST["powerFactor_r"]."',"
			."STR_TO_DATE('". $_POST["powerFactor_d"] ."','%d/%m/%Y'),"
			."'". $_POST["observationCountHidden"]."',"
			."'No')";

//echo $sqlQuery;
$queryStatus = mysqli_query($con,$sqlQuery);
if(!$queryStatus)
{
	echo $sqlQuery;
	echo "<br><h2>Error creating the record!!!</h2><br>";
	$con->rollback();
	return;
}
$observartionCount = $_POST["observationCountHidden"];
$currentObservation = 1;

while($currentObservation <= $observartionCount)
{
	$obsText = $_POST["otherComp_text_".$currentObservation];
	$obsStatus = $_POST["otherComp_".$currentObservation."_r"];
	$obsDate = ($obsStatus === "Yes")?$_POST["otherComp_".$currentObservation."_d"]:"";
	$sqlQuery = "insert into other_observations values('$branch_code','$currentObservation','$obsText','$obsStatus',STR_TO_DATE('$obsDate','%d/%m/%Y'))";
	$queryStatus = mysqli_query($con,$sqlQuery);
	if(!$queryStatus)
	{
		echo "<br><h2>Error creating the observation record!!!</h2><br>";
		$con->rollback();
		return;
	}
	$currentObservation++;
}
$con->commit();
redirect("branchEntryDetails.php");
$con->close();

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
function alreadyExistingEntry($branch_code)
{
	global $con;
	$query=mysqli_query($con,"select count(*) as 'branch_code' from audit_information where branch_code = '".$branch_code."'");
    $row = mysqli_fetch_array($query);
    $no_entries = $row['branch_code'];
	if($no_entries <= 0)
		return false;
	else if($no_entries == 1)
		return true;
	else
		die("processing error: more than one entry found for ".$branch_code." branch");
}
?>