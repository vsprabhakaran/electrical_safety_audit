<?php
$network=$zone=$region=$branchCode='';
$network=$_POST['network'];
$zone=$_POST['zone'];
$region=$_POST['region'];
$branchCode=$_POST['branchCode'];
$compliance = $_POST['compliance'];

$sqlQuery = "SELECT bm.branch_code from branch_master bm,audit_information ai where bm.branch_code LIKE '%$branchCode%' AND bm.network LIKE '%$network%' AND bm.zone LIKE '%$zone%' AND bm.region LIKE '%$region%' and bm.branch_code = ai.branch_code order by bm.network,bm.zone,bm.region";
//echo $sqlQuery;
createConnection($con);
$query = mysqli_query($con,$sqlQuery);
if (!$query) { printf("Error: %s\n", mysqli_error($con));exit();	}
$selectedBranchCodes = "";
while($row=mysqli_fetch_array($query)){
	$selectedBranchCodes[] = $row[0];
}
//print_r($selectedBranchCodes);
  ?>
  <html>
  <head>
    <link rel="stylesheet" href="../css/pure-min.css" type="text/css">
    <script type="text/javascript" src="../js/jquery-latest.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../css/jquery-ui.min.css">
	<style type="text/css">
	.branch_name{
		text-align:left;
	}
	.pure-table th{
		background-color:rgb(191, 184, 236);
	}
	td.branchName {
	  font-style: italic;
	  text-decoration: underline;
	  color: blue;
	}
	</style>
  </head>
    <body>
      <div id="header"> </div>
  <table class="pure-table" style="margin: 0px auto;">
    <thead>
    <tr class="pure-table-odd" id='r5'>
	<th>S.No</th>
      <th>Branch Code</th>
    <th>Branch Name</th>
	<th>Network</th>
    <th>Zone</th>
    <th>Region</th>
	<th><?php echo ($compliance === "Completed")?"Finishing Date":"No. of Pending Observations";?></th>
  </tr>
</thead>
<tbody style='text-align:right'>
  <?php
  if($selectedBranchCodes != "")
  {
	  $currentSerial = 1;
	  foreach($selectedBranchCodes as $currentBranchCode)
	  {
		$noPending = getPendingObservationsofBranchNumber($currentBranchCode);
		if($compliance === "Pending")
		{
			if($noPending > 0)
			{
				getBranchDetails($currentBranchCode,$cbranchName, $cnetwork, $czone, $cregion);
				echo ($currentSerial % 2 == 0)?"<tr>":"<tr class='pure-table-odd'>";
				echo "
					<td>$currentSerial</td>
					<td>
						<a href='http://localhost/BrIdent.php?brcd=$currentBranchCode&modes=DB' target='_blank'>$currentBranchCode</a>
					</td>
					<td class='branchName'>
						<a href='../viewBranchEntry.php?branchCode=$currentBranchCode' target='_blank'>$cbranchName</a>
					</td>
					<td>$cnetwork</td>
					<td>$czone</td>
					<td>$cregion</td>
					<td>$noPending</td>";
				echo "</tr>";
				$currentSerial++;
			}
		}
		else if($compliance === "Completed")
		{
			$lastDateTime = getBranchLastEditDateTime($currentBranchCode);
			if($noPending == 0)
			{
				getBranchDetails($currentBranchCode,$cbranchName, $cnetwork, $czone, $cregion);
				echo ($currentSerial % 2 == 0)?"<tr>":"<tr class='pure-table-odd'>";
				echo   "<td>$currentSerial</td>
						<td>
							<a href='http://localhost/BrIdent.php?brcd=$currentBranchCode&modes=DB' target='_blank'>$currentBranchCode</a>
						</td>
						<td class='branchName'>
							<a href='../viewBranchEntry.php?branchCode=$currentBranchCode' target='_blank'>$cbranchName</a>
						</td>
						<td>$cnetwork</td>
						<td>$czone</td>
						<td>$cregion</td>
						<td>$lastDateTime</td>";
				echo "</tr>";
				$currentSerial++;
			}
		}
		
	  }
	}
	else
	{
		echo "<tr>";
		echo "<td colspan='7' style='text-align:center'> No Records Found </td>";
		echo "</tr>";
	}
  //echo getBranchNumberOfPendingObservations('978');
  ?>
</tbody>
</table>
</body>
<?php
function createConnection(&$con)
{
	$con = new mysqli("localhost", "root", "", "electrical_audit");
	if ($con->connect_errno) {
		die("Connection failed: " . $conn->connect_error);
	}
}
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
		die("processing error: invalid other observations found for ".$branchCode." branch on observation no ".$serial_no);
	}

}
function getBranchDetails($branchCode, &$branchName, &$network, &$zone, &$region)
{
	global $con;
	$query=mysqli_query($con,"select *  from branch_master where branch_code = '".$branchCode."'");
	$row = mysqli_fetch_array($query);
	$branchName = $row['branch_name'];
	$network = $row['network'];
	$zone = $row['zone'];
	$region  = $row['region'];
	if($branchName === "")
	{
		die("processing error: invalid details found for ".$branchCode." branch ");
	}
}
function getBranchLastEditDateTime($branchCode)
{
	global $con;
	$sqlQuery = "select date_of_entry from audit_information where branch_code = '".$branchCode."'";
	$query=mysqli_query($con,$sqlQuery);
	$row = mysqli_fetch_array($query);
	$lastDateTime = ($row[0] === "0000-00-00")?"":date("d/m/Y",strtotime($row[0]));
	return $lastDateTime;
}
function getPendingObservationsofBranchNumber($branchCode)
{
	global $con;
	$sqlQuery = "select balancing,mccb,earthing,wire_replacement,emergency_lights,scrap_removal,ventilation,periodical_maintanance,ac_timers,power_factor,other_observations_count from audit_information where branch_code = '".$branchCode."'";
	$query=mysqli_query($con,$sqlQuery);
	$row = mysqli_fetch_array($query);
	$NoPendingItems = 0; $ObsCtr = 0;
	
	while($ObsCtr < 10)
	{
		if( $row[$ObsCtr] === 'No') $NoPendingItems++;
		++$ObsCtr;
	}
	$otherObservationsCount = $row['other_observations_count'];
	for($currentObservation = 1; $currentObservation <= $otherObservationsCount;$currentObservation++)
	{
		getObservationEntry($branchCode,$currentObservation,$obsText,$obsStatus,$obsDate);
		if($obsStatus === 'No') $NoPendingItems++;
	}
	return $NoPendingItems;
}
?>
</html>