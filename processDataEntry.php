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
/*foreach ($_POST as $key => $value)
{
 echo htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
}*/

//Concatenating the Pending Comments
$comments = "";
if($_POST["otherObservations_r"] === "Yes"){
	$comments .= htmlspecialchars($_POST["otherObservations_pending_1"])."^".
				htmlspecialchars($_POST["otherObservations_pending_2"])."^".
				htmlspecialchars($_POST["otherObservations_pending_3"])."^".
				htmlspecialchars($_POST["otherObservations_pending_4"])."^".
				htmlspecialchars($_POST["otherObservations_pending_5"]);
}

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
		return;
	}
}
$sqlQuery = "insert into audit_information(branch_code, balancing, balancing_date,mccb,mccb_date,earthing,earthing_date,wire_replacement,wire_replacement_date,emergency_lights,emergency_lights_date,scrap_removal,scrap_removal_date,ventilation,ventilation_date,periodical_maintanance,periodical_maintanance_date,ac_timers,ac_timers_date,power_factor,power_factor_date,other_pending_status,other_pending_observations,finalized) values("
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
			."'". $_POST["otherObservations_r"]."',"
			."'". $comments ."','No')";

//echo $sqlQuery;
$queryStatus = mysqli_query($con,$sqlQuery);
if(!$queryStatus)
{
	echo $sqlQuery;
	echo "<br><h2>Error creating the record!!!</h2><br>";
}
else
{
	redirect("branchEntryDetails.php");
}
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