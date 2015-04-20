<!DOCTYPE html>
<?php
session_start();
if(!(isset($_SESSION["esa_brcode"]) && $_SESSION["esa_brcode"] != "")){
	redirect("loginPage.php");
	return;
}
if($_SESSION["esa_brcode"] != "admin"){
	redirect("logout.php");
}
$branch_code=$_GET["branchCode"];

?>
<head>
  <title>Branch Audit Data</title>
  <script type="text/javascript" src="js/jquery-latest.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.min.css">
  <link rel="stylesheet" href="css/pure-min.css">
  <link rel="stylesheet" href="css/css-table.css">
  <?php
	createConnection($con);
    $query = mysqli_query($con,"select branch_name as 'branch_name' from branch_master where branch_code = '$branch_code'");
    $row = mysqli_fetch_array($query);
    $branch_name = $row['branch_name'];
	
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
	$otherObservationsCount = "";
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
	$otherObservationsCount = $row['other_observations_count'];
	$submissionDate = $row['date_of_entry'];
	
	//Reworking the dates
	$balancingAndDistribution_date = ($balancingAndDistribution_date === "0000-00-00")?"-":date("d/m/Y",strtotime($balancingAndDistribution_date));
	$mccb_date = ($mccb_date === "0000-00-00")?"-":date("d/m/Y",strtotime($mccb_date));
	$earthing_date = ($earthing_date === "0000-00-00")?"-":date("d/m/Y",strtotime($earthing_date));
	$oldWireReplace_date = ($oldWireReplace_date === "0000-00-00")?"-":date("d/m/Y",strtotime($oldWireReplace_date));
	$emergencyLamp_date = ($emergencyLamp_date === "0000-00-00")?"-":date("d/m/Y",strtotime($emergencyLamp_date));
	$scrapsRemoval_date = ($scrapsRemoval_date === "0000-00-00")?"-":date("d/m/Y",strtotime($scrapsRemoval_date));
	$ventilation_date = ($ventilation_date === "0000-00-00")?"-":date("d/m/Y",strtotime($ventilation_date));
	$maintenance_date = ($maintenance_date === "0000-00-00")?"-":date("d/m/Y",strtotime($maintenance_date));
	$acTimers_date = ($acTimers_date === "0000-00-00")?"-":date("d/m/Y",strtotime($acTimers_date));
	$powerFactor_date = ($powerFactor_date === "0000-00-00")?"-":date("d/m/Y",strtotime($powerFactor_date));
	
	
	function redirect($url, $statusCode = 303)
	{
	   header('Location: ' . $url, true, $statusCode);
	   die();
	}

	function getObservationEntry($branch_code, $serial_no, &$obsText, &$obsStatus, &$obsDate)
	{
		global $con;
		$query=mysqli_query($con,"select *  from other_observations where branch_code = '".$branch_code."' and observation_serial='".$serial_no."'");
		$row = mysqli_fetch_array($query);
		$obsText = $row['observation_text'];
		$obsStatus = $row['rectified_status'];
		$obsDate = $row['rectified_date'];
		$obsDate = ($obsDate === "0000-00-00")?"-":date("d/m/Y",strtotime($obsDate));
		if($obsText === "" || $obsStatus === "")
		{
			die("processing error: invalid other observations found for ".$branch_code." branch on observation no ".$serial_no);
		}
	}
	function createConnection(&$con)
	{
		$con = new mysqli("localhost", "root", "", "electrical_audit");
		if ($con->connect_errno) {
			die("Connection failed: " . $conn->connect_error);
		}
	}
  ?>
  <style type="text/css">
  body{
	font-size:12px;
	text-align:left;
  }
  .pure-table td{
	padding: 0.25ex;
	border-bottom-width:1px;
	white-space: nowrap;
	word-wrap: wrap;
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
	text-align:center;
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
	margin: 5px 0 5px 0;
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
  .pure-table caption {
	  color: black;
	  padding: 1em 0;
	  text-align: center;
	  font-size: 18px;
	  font-weight: bold;
	  background: rgb(242, 242, 242);
	}
  </style>
</head>
<body style="font-family:verdana">
	<script type="text/javascript">
		$(document).ready(function () {
			//$("#header").load("header.php");
		});
	</script>
  <div style="padding-left:2em;">
  <div style="margin-bottom:1em;font-size:14px">
    <div style="margin-left:2em; display:inline"><p id="branchCodeSpace" style="display:inline">Branch Code :  <?php echo $branch_code ?></p></div>
    <div style="margin-left:3em; display:inline;"><p id="branchNameSpace" style="display:inline">Branch Name : <?php echo $branch_name ?></p></div>
  </div>
  <table border=1 style="width:70%;text-align:center" class="pure-table">
  <caption> Branch Audit Details </caption>
  <thead>
    <tr class="topHeader">
      <th >S.No</th>
      <th >Observations</th>
      <th >Rectified</th>
      <th > Date </th>
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
      <td colspan="3" class="lineHead">
		  <p class="subHead" style="display:table-cell;">
			Any other observation made in the Electrical Safety Audit Report pending for compliance
		  </p>
	  </td>
    </tr>
	<?php
	$currentObservation = 1;
	$obsText = "";
	$obsStatus = "";
	$obsDate = "";
	while($currentObservation <= $otherObservationsCount){
		getObservationEntry($branch_code,$currentObservation,$obsText,$obsStatus,$obsDate);
		echo '<tr class="pure-table-odd">' .
				'<td><p>&nbsp;</p></td>' .
				'<td class="lineHead"><p class="subHead">'.$currentObservation.'. '.$obsText.'</p></td>' .
				'<td><p  class="p_text">'.$obsStatus.'</p></td>'.
				'<td><p  class="p_text">'.$obsDate.'</p></td>'.
			'</tr>';
		$currentObservation++;
	}
	?>
	</tbody>
  </table>
  </div>
</body>

</html>
