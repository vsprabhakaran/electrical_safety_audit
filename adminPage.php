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
	?>
<!DOCTYPE html>

<head>
  <title>Reports</title>
  <script type="text/javascript" src="js/jquery-latest.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/otherObservationUtils.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.min.css">
  <link rel="stylesheet" href="css/pure-min.css">
  <style type="text/css">
	.links{
		text-align:center;
	}
	.links div{
		text-align:left;
		//background-color:grey;
		color: rgb(5, 110, 165);
		padding:1em 1em 0 20%;
		display:block;
	}
	#navlist
	{
		background: gray;
		font: bold 12px Verdana, sans-serif;
		margin-left: 10em;
		padding: 0 1px 1px;
		width: 60em;
		line-height: 2.5em;
	}
	#navlist li
	{
		border-top: 1px solid gray;
		list-style: none;
		margin: 0;
		text-align: left;
	}
	#navlist li a
	{
		background: white;
		border-left: 1em solid #AAB;
		display: block;
		padding: 0.25em 0.5em 0.25em 0.75em;
		text-decoration: none;
	}
	#navlist li a:link
	{
		color: #448;
	}
	#navlist li a:visited
	{
		color: #667;
	}
	#navlist li a:hover
	{
		background: #CCD;
		border-color: #FE3;
	}
	#titleDiv
	{
		margin-left:5em;
		color: black;
		padding: 1em 0;
		font-size: 18px;
		font-weight: bold;
		
	}
  </style>
</head>
<body style="font-family:verdana">
	<script type="text/javascript">
	$(document).ready(function () {
			$("#header").load("header.php");
		});
	</script>
	<div id="header"> </div>
	<div id="titleDiv">Welcome Administrator</div>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="passwordReset.php">1. Reset Branch Password</a></li>
			<li><a href="reports/pendingBranchCompliance.php">2. Pending Branch Compliance Report (Download)</a></li>
			<li><a href="reports/completedBranchCompliance.php">3. Completed Branch Compliance Report (Download)</a></li>
			<li><a href="reports/branchesNotUsingThePortal.php">4. Branches not using the portal</a></li>
			<li><a href="reports/showBranchFilter.php">5. Browse Branch Status</a></li>
		</ul>
	</div>
	<!--div class="links">
		<div><a href="passwordReset.php">Reset Branch Password</a></div>
		<div><a href="reports/pendingBranchCompliance.php">Pending Branch Compliance Report (Download)</a></div>
		<div><a href="reports/completedBranchCompliance.php">Completed Branch Compliance Report (Download)</a></div>
		<div><a href="reports/branchesNotUsingThePortal.php">Branches not using the portal</a></div>
		<div><a href="reports/showBranchFilter.php">Browse Branch Status</a></div>
	<div-->
</body>
</html>