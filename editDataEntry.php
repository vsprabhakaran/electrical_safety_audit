<!DOCTYPE html>
<head>
  <title>Edit your Branch Audit Details</title>
  <script type="text/javascript" src="js/jquery-latest.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.min.css">
  <script type="text/javascript" src="js/otherObservationUtils.js"></script>
  <link rel="stylesheet" href="css/pure-min.css">
  <?php
  
	/* IMPORTANT NOTE for this page
		This page hybrid of dataEntry.php for HTML compoenets and branchEntryDetails.php for retrieving the data from DB.
		so whenever the respective components in other page gets modified, there should be changes in this page as well.
	*/
	
    session_start();
    $con = new mysqli("localhost", "root", "", "electrical_audit");
    if ($con->connect_errno) {
      die("Connection failed: " . $conn->connect_error);
    }
    //Session Validation
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
		redirect("branchEntryDetails.php");
		return;
	}
	
	function redirect($url, $statusCode = 303)
	{
	   header('Location: ' . $url, true, $statusCode);
	   die();
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
	
	
	//Copy Paste from branchEntryDetails.php
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
	$balancingAndDistribution_date = ($balancingAndDistribution_date === "0000-00-00")?"": date("d/m/Y",strtotime($balancingAndDistribution_date));
	$mccb_date = ($mccb_date === "0000-00-00")?"":date("d/m/Y",strtotime($mccb_date));
	$earthing_date = ($earthing_date === "0000-00-00")?"":date("d/m/Y",strtotime($earthing_date));
	$oldWireReplace_date = ($oldWireReplace_date === "0000-00-00")?"":date("d/m/Y",strtotime($oldWireReplace_date));
	$emergencyLamp_date = ($emergencyLamp_date === "0000-00-00")?"":date("d/m/Y",strtotime($emergencyLamp_date));
	$scrapsRemoval_date = ($scrapsRemoval_date === "0000-00-00")?"":date("d/m/Y",strtotime($scrapsRemoval_date));
	$ventilation_date = ($ventilation_date === "0000-00-00")?"":date("d/m/Y",strtotime(ventilation_date));
	$maintenance_date = ($maintenance_date === "0000-00-00")?"":date("d/m/Y",strtotime($maintenance_date));
	$acTimers_date = ($acTimers_date === "0000-00-00")?"":date("d/m/Y",strtotime($acTimers_date));
	$powerFactor_date = ($powerFactor_date === "0000-00-00")?"":date("d/m/Y",strtotime($powerFactor_date));
	
	
	function getObservationEntry($branch_code, $serial_no, &$obsText, &$obsStatus, &$obsDate)
	{
		global $con;
		$query=mysqli_query($con,"select *  from other_observations where branch_code = '".$branch_code."' and observation_serial='".$serial_no."'");
		$row = mysqli_fetch_array($query);
		$obsText = $row['observation_text'];
		$obsStatus = $row['rectified_status'];
		$obsDate = $row['rectified_date'];
		$obsDate = ($obsDate === "0000-00-00")?"":date("d/m/Y",strtotime($obsDate));
		if($obsText === "" || $obsStatus === "")
		{
			die("processing error: invalid other observations found for ".$branch_code." branch on observation no ".$serial_no);
		}

	}
	
  ?>
  <style type="text/css">
  .topHeader th{
	font-size:14px;
	color:white;
	background-color:rgb(80, 66, 172);
	border-bottom-width:1px;
  }
  body{
	//padding-left:2em;
  }
  td {
	text-align:center;
  }
  p{
	font-size:12px;
  }
  td p{
	margin:0;
  }
  .subHead{
	text-align:left;
	font-weight:bold;
  }
  .pageTitle{
	text-align:center;
	font-size:2em;
	font-weight:bold;
	padding:0.5em 0 1em 0;
  }
  .date_picker{
	background: white url(img/calendar.png) right no-repeat;
	padding-right: 17px;
  }
  </style>
</head>
<body style="font-family:verdana">
	<script type="text/javascript">
		$(document).ready(function () {
			$("#header").load("header.php");
			//Changing the size of the data picker text and date format
			$( ".date_picker" ).datepicker({ autoSize: true,
				dateFormat: "dd/mm/yy",
				beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 12) 
				}	
			});
			$('.date_picker').attr('readonly', true);
			$('#formid').bind("keyup keypress", function (e) {
				var code = e.keyCode || e.which;
				if (code == 13) {
					e.preventDefault();
					return false;
				}
			});
		});
		function submitForm(){
			var all_radio_checked = true;
			var no_checked_fields = 0;
			var all_date_chosen = true;
			var otherObservations_text_present = true;
			
			//Checking whether all the radio buttons groups are checked and 
			// if checked corresponding dates should be given.
			$('input:radio:checked').each(function() {
				no_checked_fields++;
				var radio_name = $(this).attr('name');
				if(radio_name == "otherObservations_r") return; //No date fields for this hence returning.
				var date_name = radio_name.slice(0, -2);
				date_name = date_name + "_d";
				var date_obj = $("input[name='"+ date_name +"']")
				if($(this).val() == "Yes")
				{
				  if($(date_obj).val() == '') {
					all_date_chosen = false;
				  }
				}
				else{
					$(date_obj).val('');
				}
			});
			
			//Checking whether the required number of radio buttons are checked
			var observationCount = parseInt($('input[name=observationCountHidden]').val());
			if(no_checked_fields < (10 + observationCount)) all_radio_checked = false;
			
			//Verifying whether the other observations are all filled
			$('.otherComp_text').each(function() {
				if($(this).val() === "") otherObservations_text_present = false;
			});
			
			
			//Alerting the user when he forgets the fields
			if(!all_radio_checked || !all_date_chosen || !otherObservations_text_present) {
				alert("Please fill all the options and dates");
				return false;
			}
			else if (!$("input[name='acceptConfirmation']").is(':checked'))
			{
				alert("Please accept the CONDITION given at the bottom");
				$(".conditionBox").effect("highlight",{color: 'yellow'}, 500);
			}
			else{
				document.getElementById("formid").submit();
			}
			
		}
	</script>
<?php
	//Copy Paste from dataEntry.php
?>
  <div id="header"> </div>
  <div style="padding-left:2em;">
  <div style="margin-bottom:1em;font-size:14px;font-weight:bold">
    <div style="margin-left:2em; display:inline"><p id="branchCodeSpace" style="display:inline">Branch Code :  <?php echo $branch_code ?></p></div>
    <div style="margin-left:3em; display:inline;"><p id="branchNameSpace" style="display:inline">Branch Name : <?php echo $branch_name ?></p></div>
  </div>
  <form id="formid" action="processDataEntry.php" method="post" >
  <input type="hidden" name="post_from" value="oldEntry"/>
    <input type="hidden" name="observationCountHidden" value="<?php echo $otherObservationsCount; ?>"/>
  <table border=1 class = "pure-table" id="table_id">
    <tr class="topHeader">
      <th  rowspan="2">S.No</th>
      <th rowspan="2"> Major Observations</th>
      <th colspan="3"> Whether Rectified/Replaced</th>
      <th rowspan="2"> Date of Completion</th>
    </tr>
    <tr class="OptionsHeader">
      <th>Yes</th>
      <th>No</th>
      <th>NA</th>
    </tr>
    <tr class="pure-table-odd">
      <td>1</td>
      <td><p class="subHead">Balancing and Distribution of Electrical loads </td>
      <td><input type="radio" name="balancingAndDistribution_r" value="Yes" <?php echo ($balancingAndDistribution_status ==="Yes")?"checked='checked'":"''"; ?> /></td>
      <td><input type="radio" name="balancingAndDistribution_r" value="No"  <?php echo ($balancingAndDistribution_status ==="No")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="balancingAndDistribution_r" value="NA"  <?php echo ($balancingAndDistribution_status ==="NA")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="text" name="balancingAndDistribution_d" id="balancingAndDistribution_d" class="date_picker" value="<?php echo $balancingAndDistribution_date; ?>"/></td>
    </tr>
    <tr>
      <td><p>2</p></td>
      <td><p class="subHead">MCCB/ELCB/MCB are in working condition, with proper rating/capacity </p></td>
      <td><input type="radio" name="mccb_r" value="Yes" <?php echo ($mccb_status ==="Yes")?"checked='checked'":"''"; ?>/></td>
      <td><input type="radio" name="mccb_r" value="No"  <?php echo ($mccb_status ==="No")?"checked='checked'":"''"; ?> /></td>
      <td><input type="radio" name="mccb_r" value="NA"  <?php echo ($mccb_status ==="NA")?"checked='checked'":"''"; ?> /></td>
      <td><input type="text" name="mccb_d" id="mccb_d" class="date_picker" value="<?php echo $mccb_date; ?>"/></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>3</p></td>
      <td><p class="subHead">Proper earthing carried out/connected and reports are in palce</p> </td>
      <td><input type="radio" name="earthing_r" value="Yes" <?php echo ($earthing_status ==="Yes")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="earthing_r" value="No"  <?php echo ($earthing_status ==="No")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="earthing_r" value="NA"  <?php echo ($earthing_status ==="NA")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="text" name="earthing_d" id="earthing_d" class="date_picker" value="<?php echo $earthing_date; ?>"/></td>
    </tr>
    <tr>
      <td><p>4</p></td>
      <td><p class="subHead">Replacement of old wires, if any</p> </td>
      <td><input type="radio" name="oldWireReplace_r" value="Yes" <?php echo ($oldWireReplace_status ==="Yes")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="oldWireReplace_r" value="No"  <?php echo ($oldWireReplace_status ==="No")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="oldWireReplace_r" value="NA"  <?php echo ($oldWireReplace_status ==="NA")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="text" name="oldWireReplace_d" id="oldWireReplace_d" class="date_picker" value="<?php echo $oldWireReplace_date; ?>"/></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>5</p></td>
      <td><p class="subHead">Provision of emergency lights</p> </td>
      <td><input type="radio" name="emergencyLamp_r" value="Yes" <?php echo ($emergencyLamp_status ==="Yes")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="emergencyLamp_r" value="No"  <?php echo ($emergencyLamp_status ==="No")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="emergencyLamp_r" value="NA"  <?php echo ($emergencyLamp_status ==="NA")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="text" name="emergencyLamp_d" id="emergencyLamp_d" class="date_picker" value="<?php echo $emergencyLamp_date; ?>"/></td>
    </tr>
    <tr>
      <td><p>6</p></td>
      <td><p class="subHead">Removal of scraps/old materials in electrical panel rooms/UPS/Batteries/DBs</p> </td>
      <td><input type="radio" name="scrapsRemoval_r" value="Yes" <?php echo ($scrapsRemoval_status ==="Yes")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="scrapsRemoval_r" value="No"  <?php echo ($scrapsRemoval_status ==="No")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="scrapsRemoval_r" value="NA"  <?php echo ($scrapsRemoval_status ==="NA")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="text" name="scrapsRemoval_d" id="scrapsRemoval_d" class="date_picker" value="<?php echo $scrapsRemoval_date; ?>"/></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>7</p></td>
      <td><p class="subHead">Proper ventilation arrangements for panel room/UPS room/electrical room provided</p> </td>
      <td><input type="radio" name="ventilation_r" value="Yes" <?php echo ($ventilation_status ==="Yes")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="ventilation_r" value="No"  <?php echo ($ventilation_status ==="No")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="ventilation_r" value="NA"  <?php echo ($ventilation_status ==="NA")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="text" name="ventilation_d" id="ventilation_d" class="date_picker" value="<?php echo $ventilation_date; ?>"/></td>
    </tr>
    <tr>
      <td><p>8</p></td>
      <td><p class="subHead">Periodical Electrical maintenance is carried out </p></td>
      <td><input type="radio" name="maintenance_r" value="Yes" <?php echo ($maintenance_status ==="Yes")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="maintenance_r" value="No"  <?php echo ($maintenance_status ==="No")?"checked='checked'":"''"; ?>    /></td>
      <td><input type="radio" name="maintenance_r" value="NA"  <?php echo ($maintenance_status ==="NA")?"checked='checked'":"''"; ?>    /></td>
      <td><input type="text" name="maintenance_d" id="maintenance_d" class="date_picker" value="<?php echo $maintenance_date; ?>"/></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>9</p></td>
      <td><p class="subHead">AC timers are working </p></td>
      <td><input type="radio" name="acTimers_r" value="Yes" <?php echo ($acTimers_status ==="Yes")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="acTimers_r" value="No"  <?php echo ($acTimers_status ==="No")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="acTimers_r" value="NA"  <?php echo ($acTimers_status ==="NA")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="text" name="acTimers_d" id="acTimers_d" class="date_picker" value="<?php echo $acTimers_date; ?>"/></td>
    </tr>
    <tr>
      <td><p>10</p></td>
      <td><p class="subHead">Power factor correction panels are provided</p> </td>
      <td><input type="radio" name="powerFactor_r" value="Yes" <?php echo ($powerFactor_status ==="Yes")?"checked='checked'":"''"; ?>  /></td>
      <td><input type="radio" name="powerFactor_r" value="No"  <?php echo ($powerFactor_status ==="No")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="radio" name="powerFactor_r" value="NA"  <?php echo ($powerFactor_status ==="NA")?"checked='checked'":"''"; ?>   /></td>
      <td><input type="text" name="powerFactor_d" id="powerFactor_d" class="date_picker" value="<?php echo $powerFactor_date; ?>"/></td>
    </tr>
    <tr class="pure-table-odd">
      <td><p>11</p></td>
      <td colspan="5">
		  <p class="subHead" style="display:table-cell;">
			Any other observation made in the Electrical Safety Audit Report pending for compliance
		  </p>
		  <div style="display:table-cell; padding-left:1em;">
			  <button id="otherComp_button_add" class="otherComp_button" type="button" onClick="javascript:addNewObservation('table_id');"> Add new </button>
			  <button id="otherComp_button_add" class="otherComp_button" type="button" onClick="javascript:deleteLastObservation('table_id');"> Delete Last Entry </button>
		  <div>
	  </td>
    </tr>
	<?php
	$currentObservation = 1;
	$obsText = "";
	$obsStatus = "";
	$obsDate = "";
	while($currentObservation <= $otherObservationsCount){
		getObservationEntry($branch_code,$currentObservation,$obsText,$obsStatus,$obsDate);
		echo '<tr class="pure-table-odd">
			   <td>
				  <p>&nbsp;</p>
			   </td>
			   <td>'.$currentObservation.'. <input type="text" name="otherComp_text_'.$currentObservation.'" id="otherComp_text_'.$currentObservation.'" style="width:90%" class="otherComp_text" value="'.$obsText.'"> </td>' .
				'<td><input type="radio" name="otherComp_'.$currentObservation.'_r" id="otherComp_'.$currentObservation.'_r" value="Yes" '.(($obsStatus === 'Yes')?'checked="checked"':'').'></td>'.
				'<td><input type="radio" name="otherComp_'.$currentObservation.'_r" id="otherComp_'.$currentObservation.'_r" value="No" '.(($obsStatus === 'No')?'checked="checked"':'').'></td>'.
				'<td></td>'.
				'<td><input type="text" name="otherComp_'.$currentObservation.'_d" id="otherComp_'.$currentObservation.'_d" class="date_picker" value="'.$obsDate.'" /></td>'.
			'</tr>';
		$currentObservation++;
	}
	?>	
  </table>
  <p>
    NOTE: The above are major observations based on the Electrical Safety Audit Report submitted by
    External Agency. These are only illustrative and the Branch has to rectify all the observations
    pointed out in the respective Branch Electrical Safety Audit Report.
  </p>
  <p class="conditionBox">
    <input type="checkbox" name="acceptConfirmation" value="confirmed"/> I, the branch manager, confirm that all the observations pointed out in the
    Electrical Safety Audit report have been attended.
  </p>
  <div style="text-align:center">
      <button type="button" class="pure-button pure-button-primary" name ="submitBtn" onClick="submitForm()">Save and Preview</button>
  </div>
  <br/>
  </form>
  </div>
</body>

</html>
