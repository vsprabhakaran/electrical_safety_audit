<?php
session_start();

	if(!(isset($_SESSION["esa_brcode"]) && $_SESSION["esa_brcode"] != "")){
		redirect("loginPage.php");
		return;
	}
	if($_SESSION["esa_brcode"] != "admin"){
		redirect("logout.php");
	}
	function redirect($url, $statusCode = 303)
	{
	   header('Location: ' . $url, true, $statusCode);
	   die();
	}

$branchCode=$_POST['branchCode'];
$con = new mysqli("localhost", "root", "", "electrical_audit");
if ($con->connect_errno) {
    die("Connection failed: " . $conn->connect_error);
}
$defaultHash=md5('sbi@1234');
$query=mysqli_query($con,"UPDATE branch_login set pwd_hash='$defaultHash' where branch_code='$branchCode'");
if($query){
  ?>
  <
  <script type="text/javascript">
    alert("Password Reset  Successful!");
  </script>
  <meta http-equiv="refresh" content="0;URL=adminPage.php">
  <?php
}
?>
