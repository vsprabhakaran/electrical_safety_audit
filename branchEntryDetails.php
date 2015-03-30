<!DOCTYPE html>
<head>
  <title>Your Branch Audit Data</title>
  <script type="text/javascript" src="js/jquery-latest.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.min.css">
  <link rel="stylesheet" href="css/pure-min.css">
  <?php
	$isEntryFinalized = false;
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
    $branch_code=$_SESSION["esa_brcode"];
    $query=mysqli_query($con,"select branch_name as 'branch_name' from branch_master where branch_code = '$branch_code'");
    $row = mysqli_fetch_array($query);
    $branch_name = $row['branch_name'];
	
	if(!alreadyExistingEntry($branch_code))
	{
		redirect("dataEntry.php");
		return;
	}
	else if(isFinalizedEntry($branch_code)){
		$isEntryFinalized = TRUE;
	}
	
	
	/*
	Please note that whatever the changes you do below should also be done in editDataEntry.php
	as its the hybrid of dataEntry.php for HTML compoenets and branchEntryDetails.php for retrieving the data from DB.
	*/
	$balancingAndDistribution_status= "";
	$balancingAndDistribution_date= "";
	$mccb_status= "";
	$mccb_date= "";
	$earthing_status= "";
	$earthing_date= "";
	$oldWireReplace_status= "";
	$oldWireReplace_date= "";
	$emergencyLamp_status= "";
	$emergencyLamp_date= "";
	$scrapsRemoval_status= "";
	$scrapsRemoval_date= "";
	$ventilation_status= "";
	$ventilation_date= "";
	$maintenance_status= "";
	$maintenance_date= "";
	$acTimers_status= "";
	$acTimers_date= "";
	$powerFactor_status= "";
	$powerFactor_date= "";
	$otherObservations_status= "";
	$otherObservations_comments= "";
	$submissionDate = "";
	
	$query=mysqli_query($con,"select * from audit_information where branch_code = '".$branch_code."'");
	$row = mysqli_fetch_array($query);
	$balancingAndDistribution_status = $row['balancing'];
	$balancingAndDistribution_date= $row['balancing_date'];
	$mccb_status= $row['mccb'];
	$mccb_date= $row['mccb_date'];
	$earthing_status= $row['earthing'];
	$earthing_date= $row['earthing_date'];
	$oldWireReplace_status= $row['wire_replacement'];
	$oldWireReplace_date= $row['wire_replacement_date'];
	$emergencyLamp_status= $row['emergency_lights'];
	$emergencyLamp_date= $row['emergency_lights_date'];
	$scrapsRemoval_status= $row['scrap_removal'];
	$scrapsRemoval_date= $row['scrap_removal_date'];
	$ventilation_status= $row['ventilation'];
	$ventilation_date= $row['ventilation_date'];
	$maintenance_status= $row['periodical_maintanance'];
	$maintenance_date= $row['periodical_maintanance_date'];
	$acTimers_status= $row['ac_timers'];
	$acTimers_date= $row['ac_timers_date'];
	$powerFactor_status= $row['power_factor'];
	$powerFactor_date= $row['power_factor_date'];
	$otherObservations_status= $row['other_pending_status'];
	$otherObservations_comments= $row['other_pending_observations'];
	$submissionDate = $row['date_of_entry'];
	
	//Reworking the dates
	$balancingAndDistribution_date = ($balancingAndDistribution_date === "0000-00-00")?"-":$balancingAndDistribution_date;
	$mccb_date = ($mccb_date === "0000-00-00")?"-":$mccb_date;
	$earthing_date = ($earthing_date === "0000-00-00")?"-":$earthing_date;
	$oldWireReplace_date = ($oldWireReplace_date === "0000-00-00")?"-":$oldWireReplace_date;
	$emergencyLamp_date = ($emergencyLamp_date === "0000-00-00")?"-":$emergencyLamp_date;
	$scrapsRemoval_date = ($scrapsRemoval_date === "0000-00-00")?"-":$scrapsRemoval_date;
	$ventilation_date = ($ventilation_date === "0000-00-00")?"-":$ventilation_date;
	$maintenance_date = ($maintenance_date === "0000-00-00")?"-":$maintenance_date;
	$acTimers_date = ($acTimers_date === "0000-00-00")?"-":$acTimers_date;
	$powerFactor_date = ($powerFactor_date === "0000-00-00")?"-":$powerFactor_date;
	
	
	//Reworking the Comments
	$pendingComments = "";
	if($otherObservations_status == "Yes"){
		foreach(explode('^',$otherObservations_comments) as $index=>$text){
			$pendingComments .= "<div><b>".($index+1)."</b>. ".$text."</div>";
		}
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
	function isFinalizedEntry($branch_code){
		global $con;
		$query=mysqli_query($con,"select finalized  from audit_information where branch_code = '".$branch_code."'");
		$row = mysqli_fetch_array($query);
		$is_finalized = $row['finalized'];
		if($is_finalized === "Yes")
			return true;
		else if($is_finalized === "No")
			return false;
		else
			die("processing error: invalid finalized entry found for ".$branch_code." branch");
	}
  ?>
  <style type="text/css">
  body{
	font-size:12px;
	text-align:left;
	margin-left:2em;
  }
  .pure-table td{
	padding: 0.25ex;
	border-bottom-width:1px;
	
  }
  .lineHead {
	text-align:left;
	font-weight:bold;
	font-size:0.5em;
  }
  .topHeader th{
	font-size:14px;
	color:white;
	background-color:rgb(80, 66, 172);
	border-bottom-width:1px;
  }
  .subHead,.p_text{
	font-size:12px;
  }
  .subHead{
	padding-left:1em
  }
  
  #branchCodeSpace,#branchNameSpace{
	margin:1em;
	font-weight:bold;
  }
  p{
	font-size:12px;
  }
  #header
  {
	padding-bottom:1ex;
  }
  #otherObservations_comments div{
	text-align:left;
	text-wrap:wrap;
	padding:0.2em;
  }
  </style>
</head>
<body style="font-family:verdana">
	<script type="text/javascript">
		$(document).ready(function () {
			$("#header").load("header.php");
			$("#printButton").click(function(){
				$("#printButton").hide();
				$(".headerDontPrint").hide();
				window.print();
				$("#printButton").show();
				$(".headerDontPrint").show();
			});
		});
		function finishEntry(){
			if(confirm("Note: The Audit Details entered would be final and cannot be changed.\n\n Press OK to continue or Press CANCEL to keep editing."))
				$("form").submit();
		}
	</script>
  <div id="header"> </div>
  
  <div style="margin-bottom:1em;font-size:14px">
    <div style="margin-left:2em; display:inline"><p id="branchCodeSpace" style="display:inline">Branch Code :  <?php echo $branch_code ?></p></div>
    <div style="margin-left:3em; display:inline;"><p id="branchNameSpace" style="display:inline">Branch Name : <?php echo $branch_name ?></p></div>
  </div>
  <form id="formid" action="finalizeEntry.php" method="post" >
  <input type="hidden" name="post_from" value="finalizeEntry" />
  <table border=1 style="width:95%;text-align:center" class="pure-table">
  <thead>
    <tr class="topHeader">
      <th style="width:5%">S.No</th>
      <th style="width:60%"> Major Observations</th>
      <th style="width:15%"> Whether Rectified/Replaced</th>
      <th style="width:15%"> Date of Completion</th>
    </tr>
   </thead>
   <tbody>
    <tr class="pure-table-odd">
      <td><p>1</p></td>
      <td class="lineHead"><p class="subHead"> Balancing and Distribution of Electrical loads</p> </td>
      <td><p id="balancingAndDistribution_status" class="p_text" ><?php echo $balancingAndDistribution_status ?></p></td>
      <td><p id="balancingAndDistribution_date" class="p_text" ><?php echo $balancingAndDistribution_date ?></p></td>
    </tr>
    <tr>
      <td><p>2</p></td>
      <td class="lineHead"><p class="subHead">MCCB/ELCB/MCB are in working condition, with proper rating/capacity </p></td>
	  <td><p id="mccb_status" class="p_text" ><?php echo $mccb_status ?></p></td>
      <td><p id="mccb_date" class="p_text"><?php echo $mccb_date ?></p></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>3</p></td>
      <td class="lineHead"><p class="subHead">Proper earthing carried out/connected and reports are in palce </p></td>
      <td><p id="earthing_status" class="p_text"><?php echo $earthing_status ?></p></td>
      <td><p id="earthing_date" class="p_text"> <?php echo  $earthing_date ?></p></td>
    </tr>
    <tr>
      <td><p>4</p></td>
      <td class="lineHead"><p class="subHead">Replacement of old wires, if any</p> </td>
      <td><p id="oldWireReplace_status" class="p_text" ><?php echo $oldWireReplace_status ?> </p></td>
      <td><p id="oldWireReplace_date" class="p_text" ><?php echo $oldWireReplace_date ?></p></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>5</p></td>
      <td class="lineHead"><p class="subHead">Provision of emergency lights</p> </td>
      <td><p id="emergencyLamp_status" class="p_text" > <?php echo $emergencyLamp_status ?></p></td>
      <td><p id="emergencyLamp_date" class="p_text"> <?php echo $emergencyLamp_date ?></p></td>
    </tr>
    <tr>
      <td><p>6</p></td>
      <td class="lineHead"><p class="subHead">Removal of scraps/old materials in electrical panel rooms/UPS/Batteries/DBs</p> </td>
      <td><p id="scrapsRemoval_status" class="p_text"><?php echo $scrapsRemoval_status ?></p></td>
      <td><p id="scrapsRemoval_date" class="p_text"><?php echo $scrapsRemoval_date ?></p></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>7</p></td>
      <td class="lineHead"><p class="subHead">Proper ventilation arrangements for panel room/UPS room/electrical room provided</p> </td>
      <td><p id="ventilation_status" class="p_text" > <?php echo $ventilation_status ?></p></td>
      <td><p id="ventilation_date" class="p_text"><?php echo $ventilation_date ?></p></td>
    </tr>
    <tr>
      <td><p>8</p></td>
      <td class="lineHead"><p class="subHead">Periodical Electrical maintenance is carried out </p></td>
	  <td><p id="maintenance_status" class="p_text"><?php echo $maintenance_status ?></p></td>
      <td><p id="maintenance_date" class="p_text"><?php echo $maintenance_date ?></p></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>9</p></td>
      <td class="lineHead"><p class="subHead">AC timers are working </p></td>
      <td><p id="acTimers_status" class="p_text"> <?php echo $acTimers_status ?></p></td>
      <td><p id="acTimers_date" class="p_text"> <?php echo $acTimers_date ?></p></td>
    </tr>
    <tr>
      <td><p>10</p></td>
      <td class="lineHead"><p class="subHead">Power factor correction panels are provided </p></td>
      <td><p id="powerFactor_status" class="p_text"><?php echo $powerFactor_status ?></p></td>
      <td><p id="powerFactor_date" class="p_text"><?php echo $powerFactor_date ?></p></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>11</p></td>
      <td class="lineHead"><p class="subHead">Any other observation made in the Electrical Safety Audit Report pending for compliance </p></td>
	  <td><p id="otherObservations_status" class="p_text"><?php echo $otherObservations_status ?></p></td>
      <td><div id="otherObservations_comments" class="p_text"><?php echo $pendingComments ?></div></td>
    </tr>
	</tbody>
  </table>
  <p>
    <b>NOTE</b>: The above are major observations based on the Electrical Safety Audit Report submitted by
    External Agency. These are only illustrative and the Branch has to rectify all the observations
    pointed out in the respective Branch Electrical Safety Audit Report.
  </p>
  <p>
    <input type="checkbox" name="acceptConfirmation" value="confirmed" checked="checked" readonly="readonly" disabled/>I, the branch manager, confirmed that all the observations pointed out in the Electrical Safety Audit report have been attended.
  </p>
  <div>
    <div style="display:inline;font-weight:bold">Date : <p id="submissionDate" style="display:inline"><?php echo $submissionDate ?></p></div>
    <div style="margin-right:3em; display:inline;float:right;" >(BRANCH MANAGER)</div>
  </div>
  <div style="text-align:center;padding:3em;">
	<?php 
		if(!$isEntryFinalized){
			echo '<button type="button" class="pure-button pure-button-primary" name ="backButton" id="backButton">Edit Data</button> ';
			echo '<button type="button" class="pure-button pure-button-primary" name ="finishButton" id="finishButton" onClick="javascript:finishEntry()">Finish</button>';
		}
		else{
			echo '<button type="button" class="pure-button pure-button-primary" name ="printButton" id="printButton">Print</button> ';
		}
	?>
    
  </div>
  </form>
</body>

</html>
