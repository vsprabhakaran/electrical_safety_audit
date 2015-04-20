<?php
$network=$zone=$region=$branchCode='';
$network=$_POST['network'];
$zone=$_POST['zone'];
$region=$_POST['region'];
$branchCode=$_POST['branchCode'];
$fromDate=$_POST['fromDate'];
$toDate=$_POST['toDate'];
if($fromDate==''){
  $fromDate='01/04/2014';
}
if($toDate==''){
  $toDate=date("d/m/Y");
}
//echo $branchCode." ".$network." ".$zone." ".$region." ".$fromDate." ".$toDate;
$fromDate = date("Y-m-d", strtotime($fromDate));
$toDate = date("Y-m-d", strtotime($toDate));

$con = new mysqli("localhost", "root", "", "branch_expenses");
if ($con->connect_errno) {
    die("Connection failed: " . $conn->connect_error);
}

$query=mysqli_query($con,"SELECT * from audit_information where branch_code IN (SELECT branch_code from branch_master where branch_code LIKE '%$branchCode%' AND network LIKE '%$network%' AND zone LIKE '%$zone%' AND region LIKE '%$region%' )");
if (!$query) {
  printf("Error: %s\n", mysqli_error($con));
  exit();
  }
if($_POST['submit']=='Export to Excel'){
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=testFile.xls");
header("Pragma: no-cache");
header("Expires: 0");
/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
//end of printing column names
//start while loop to get data
    while($row = mysqli_fetch_row($query))
    {
        $schema_insert = "";
        for($j=0; $j<mysqli_num_fields($query);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }
  mysqli_close($con);
}
else if($_POST['submit']=='Show Records'){
  ?>
  <html>
  <head>
    <link rel="stylesheet" href="../css/pure-min.css" type="text/css">
    <script type="text/javascript" src="../js/jquery-latest.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../css/jquery-ui.min.css">
    <link rel="stylesheet" href="../css/globalStyles.css">
  </head>
    <body>
      <div id="header"> </div>
  <table class="pure-table">
    <thead>
    <tr class="pure-table-odd" id='r5'>
      <th>Branch Code</th>
    <th>EB Date</th>
    <th>EB Units</th>
    <th>EB Amount paid</th>
    <th>Diesel Bill Date</th>
    <th>Diesel Purchased(in litres)</th>
    <th>Diesel Amount paid</th>
  </tr>
</thead>
<tbody style='text-align:right'>
  <?php
  while($row=mysqli_fetch_array($query)){
    $row['1'] = date("d/m/Y", strtotime($row['1']));
    $row['4'] = date("d/m/Y", strtotime($row['4']));
    echo "<tr>";
    echo "<td>".$row['0']."</td><td>".$row['1']."</td><td>".$row['2']."</td><td>".$row['3']."</td><td>".$row['4']."</td><td>".$row['5']."</td><td>".$row['6']."</td>";
    echo "</tr>";
  }
  ?>
</tbody>
</table>
</body>
</html>
  <?php
}
?>
