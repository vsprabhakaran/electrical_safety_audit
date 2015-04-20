<!DOCTYPE html>
  <head>
    <link rel="stylesheet" href="../css/pure-min.css" type="text/css">
    <script type="text/javascript" src="../js/jquery-latest.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../css/jquery-ui.min.css">
    <link rel="stylesheet" href="../css/globalStyles.css">
	<title>Branches not using the Compliance Portal</title>
	<style type="text/css">
	.branch_name{
		text-align:left;
	}
	.pure-table th{
		background-color:rgb(191, 184, 236);
	}
	</style>
  </head>
    <body style="">
		<script type="text/javascript">
		$(document).ready(function () {
				$("#header").load("../header.php");
			});
				
		</script>
      <div id="header"> </div>
	    <input style="display:'inline-block'; background-color:#50689f;margin-left:1em;" class="pure-button pure-button-primary" type='button' name='back' value='<< Admin Page' onClick="javascript: window.top.location='../adminPage.php'"/>
	  <div style="font-size:1.25em;padding:1em;font-weight:bold;color:rgb(108, 92, 216);font-style:italic;text-align:center">Branches not using the portal</div>
  <table class="pure-table" style="margin: 0px auto;">
    <thead>
    <tr class="pure-table-odd" id='r5'>
	<th>S.No</th>
      <th>Branch Code</th>
    <th>Branch Name</th>
	<th>Network</th>
    <th>Zone</th>
    <th>Region</th>
  </tr>
</thead>
<tbody style='text-align:right'>
  <?php
  createConnection($con);
  $sqlQuery = "SELECT * FROM `branch_master` bm WHERE bm.branch_code not in (select branch_code  from audit_information) and branch_code != 'admin' order by network,zone,region";
  $query=mysqli_query($con,$sqlQuery);
  $ctr = 1;
  while($row=mysqli_fetch_array($query)){
    echo ($ctr % 2 == 1)?"<tr>":"<tr class='pure-table-odd'>";
    echo "
			<td>$ctr</td>
			<td>
				<a href='http://localhost/BrIdent.php?brcd=".$row['0']."&modes=DB' target='_blank'>".$row['0']."</a>
			</td>
			<td class='branch_name'>".$row['1']."</td>
			<td>".$row['2']."</td>
			<td>".$row['3']."</td>
			<td>".$row['4']."</td>";
    echo "</tr>";
	$ctr++;
  }
  mysqli_close($con);
function createConnection(&$con)
{
	$con = new mysqli("localhost", "root", "", "electrical_audit");
	if ($con->connect_errno) {
		die("Connection failed: " . $conn->connect_error);
	}
}
  ?>
</tbody>
</table>
</body>
</html>