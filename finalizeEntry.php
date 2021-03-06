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

if(($_POST["post_from"] === "finalizeEntry") && !alreadyExistingEntry($branch_code)) {
	echo "<body>Something seems to be wrong!!!. :( .</body>";
	return;
}

//Finalize the entry
$sqlQuery = "update audit_information set finalized='Yes' , date_of_entry=now() where branch_code='$branch_code'";

$queryStatus = mysqli_query($con,$sqlQuery);
if(!$queryStatus)
{
	echo "<br><h2>Error Updating database!!!</h2><br>";
	return;
}
else{
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