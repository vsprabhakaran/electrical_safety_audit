<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="css/loginstyles.css">
  <link rel="stylesheet" href="css/pure-min.css">
  <script type="text/javascript" src="js/jquery-latest.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <style type="text/css">

  </style>
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
</head>
<body>
  <script type="text/javascript">
    $(document).ready(function () {
      $("#header").load("header.php");
    });
  </script>
  <div id="header"> </div>
  	  <input style="display:'inline-block'; background-color:#50689f;margin-left:1em;" class="pure-button pure-button-primary" type='button' name='back' value='<< Admin Page' onClick="javascript: window.top.location='adminPage.php'"/>
<div id="container">

  <br/><br/>
  
    <form class="pure-form pure-form-aligned" action="resetPwdAction.php" method="POST">

      <div id="formTitle" style='text-align:center;'> Reset Branch Password </div>
      <br/><br/>
    <div class="pure-control-group">
    <label id="labels" for="branchCode" >Branch Code</label>
    <input type="text" name="branchCode" />
    </div>
    <br/>
    <div id="lower" style="margin-top:0px">
    <input type="submit" value="Reset"/>
    </div>
    </form>
  </div>
  </body>
</html>
