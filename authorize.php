<html>
<head>
<?php
session_start();
$db_pwdHash=null;
$_SESSION["esa_brcode"]="";
$con = new mysqli("localhost", "root", "", "electrical_audit");
if ($con->connect_errno) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST["branchCode"]))
	$branchCode=$_POST["branchCode"];
$defaultHash=md5('sbi@1234');
$adminPwdHash=md5('admin@1234');
if(isset($_POST["password"]))
	$password=$_POST["password"];
$passwordHash= md5($password);
$query=mysqli_query($con,"select pwd_hash from branch_login where branch_code = '$branchCode'");
if(mysqli_affected_rows($con)>0){
$row = mysqli_fetch_array($query);
$db_pwdHash = $row['pwd_hash'];
}
else{
  $query2=mysqli_query($con,"INSERT into branch_login (branch_code,pwd_hash) values ('$branchCode','$defaultHash')");
  if(mysqli_affected_rows($con)>0){
    $query=mysqli_query($con,"select pwd_hash from branch_login where branch_code = '$branchCode'");
    $row = mysqli_fetch_array($query);
    $db_pwdHash = $row['pwd_hash'];
  }
}
	if($branchCode === "" || $passwordHash === "")
	{
		?>
			<script type="text/javascript">
				alert("Invalid Submission");
			</script>
			<meta http-equiv="refresh" content="0;URL=loginPage.php">
		<?php
	}
	else if($passwordHash != $db_pwdHash)
	{
		?>
			<script type="text/javascript">
				alert("Invalid Branch Code / Password");
			</script>
			<meta http-equiv="refresh" content="0;URL=loginPage.php">
		<?php
	}
	else if($db_pwdHash === $passwordHash)
	{
		if($passwordHash === $defaultHash)
		{
			//storing temporarily so that user has to change the password,
			//after which it will be moved to proper session variable
			$_SESSION["esa_brcode_temp"] = $branchCode;
		?>
			<meta http-equiv="refresh" content="0;URL=changePassword.php">
		<?php
		}
//redirected  to billDetails. IF  it is admin, bill details will redirect it to reset page
		else{
			$_SESSION["esa_brcode"] = $branchCode;	//proper login.
		?>
			<meta http-equiv="refresh" content="0;URL=dataEntry.php">
		<?php
		}
	}

$con->close();
?>

</head>
    <body>

</body>
</html>
