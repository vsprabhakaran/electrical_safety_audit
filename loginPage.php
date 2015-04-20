<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="css/loginstyles.css">
	<link rel="stylesheet" href="css/pure-min.css">
	<script type="text/javascript" src="js/jquery-latest.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<style type="text/css">

	</style>
	<title>Login</title>
</head>
<body>
	<script type="text/javascript">
		$(document).ready(function () {
			$("#header").load("header.php");
		});
	</script>
	<div id="header"> </div>
<div id="container">
	<br/><br/>
		<form class="pure-form pure-form-aligned" action="authorize.php" method="POST">
		<div class="pure-control-group">
		<label id="labels" for="branchCode" >Branch Code</label>
		<input type="text" name="branchCode" />
		</div>
		<br/>
		<div class="pure-control-group">
		<label id="labels" for="password">Password</label>
		<input type="password" name="password"/>
		</div>
		<div id="lower">
		<input type="submit" value="Login"/>
		</div>
		</form>
	</div>
	</body>
</html>