<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
session_start();
$request = $_POST['type'];
switch($request)
{
    case 'checkOldPassword':
    {
        checkOldPassword($_POST['pwd']);
        break;
    }
    case 'updateNewPassword':
    {
        updateNewPassword($_POST['pwd']);
        break;
    }
    
}
function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "electrical_audit");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}
function checkOldPassword($old)
{
    $con=NULL;
    db_prelude($con);
	if(isset($_SESSION["esa_brcode_temp"]) && $_SESSION["esa_brcode_temp"] != "")
		$branchCode = $_SESSION["esa_brcode_temp"];
	else if($_SESSION["esa_brcode"] != "")
		$branchCode = $_SESSION["esa_brcode"];
	else{
		echo json_encode(FALSE);
		mysqli_close($con);
		return;
	}
		
    $query=mysqli_query($con,"select pwd_hash from branch_login where  branch_code = '$branchCode'");
    $row = mysqli_fetch_array($query);
    if($row["pwd_hash"]== md5($old))
		echo json_encode(TRUE);
    else
		echo json_encode(FALSE);
    mysqli_close($con);
}

function updateNewPassword($new)
{
    $con=NULL;
    db_prelude($con);

   	if(isset($_SESSION["esa_brcode_temp"]) && $_SESSION["esa_brcode_temp"] != "")
		$branchCode = $_SESSION["esa_brcode_temp"];
	else if($_SESSION["esa_brcode"] != "")
		$branchCode = $_SESSION["esa_brcode"];
	else{
		echo json_encode(FALSE);
		mysqli_close($con);
		return;
	}
	
    $newHash=md5($new);
    $udpate=mysqli_query($con,"update branch_login set pwd_hash='$newHash' where branch_code = '$branchCode'");
	$rowcount=mysqli_affected_rows($con);
    if($rowcount>0)
		echo json_encode(TRUE);
    else
		echo json_encode(FALSE);
	changeTempSessionIfAny();
    mysqli_close($con);
}
//When the user logins using default password "esa_brcode_temp" is set, 
//which should be changed to "esa_brcode" when he changes to new password
function changeTempSessionIfAny()
{
	if(isset($_SESSION["esa_brcode_temp"]) && $_SESSION["esa_brcode_temp"] != "" && $_SESSION["esa_brcode"] == "") {
		$_SESSION["esa_brcode"] = $_SESSION["esa_brcode_temp"];
		$_SESSION["esa_brcode_temp"] = "";
	}
}