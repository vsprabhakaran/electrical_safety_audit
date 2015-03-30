<!DOCTYPE html>
<head>
<style type="text/css">
#one{
	width: 32%;
}
#two
{
	text-align:left;
	vertical-align:middle;
	width: 48%;
	padding-left:3%;

}
#two span
{
	font-size:3ex;
	text-align:center;
	font-weight:bold;
	display:inline-block;
	font-family:helvetica;
}
#three
{
	width:15%;
}
#three img
{
	float:right;
	padding:0ex 2ex 0 0;
	max-width:100%;
	max-height:90%;
}
.headerDivs
{
	float:left;
	width:32%;
	display:inline;
	height:5em;
	font-family:helvetica;
	color:rgb(5, 110, 165);
	line-height:2.5em;
	margin-bottom:2ex;
	padding-top:1ex;
	background-color:rgb(242, 242, 242);
}
.headerImg{
	max-width:100%;
	max-height:100%;
}
.headerParentDiv
{
	
}
</style>
</head>
<body>
<div style="width:97%" class="headerParentDiv" >
<div id="one" class="headerDivs"><img src="img/header_sbi.png" alt="sbi_logo" class="headerImg"/>
</div>
<div id="two" class="headerDivs"><span>ELECTRICAL AND SAFETY AUDIT <br>2014-2015</span>
</div>
<div id="three" class="headerDivs">
<?php
session_start();
if(isset($_SESSION["esa_brcode"]) && $_SESSION["esa_brcode"] != "")
{ ?> 
<a href="logout.php" ><img src="img/logout.png" alt="Logout" class="headerImg headerDontPrint"/></a>
<a href="changePassword.php" ><img src="img/changepassword.png" alt="change password" class="headerImg headerDontPrint"/></a>
<?php
}

?>
</div>
<div>
</body>
</html>