<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="../css/pure-min.css" type="text/css">
  <script type="text/javascript" src="../js/jquery-latest.min.js"></script>
  <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="../css/jquery-ui.min.css">
  <script>
  $(document).ready(function () {
    $("#header").load("../header.php");
    var i=1;
    for(i=1;i<10;i++){
    var o = new Option(i, i);
    var p = new Option(i, i);
    $(o).html(i);
    $(p).html(i);
    $("#region").append(o);
    $("#zone").append(p);
  }
    $('#branchCode').css("width","10%");

  });
  </script>
</head>
<body>
  <div id="header"> </div>
  <form style='text-align:center' action='fetchReport.php' method='POST'  class="pure-form" target='report'>
  <div>
  <input style="display:'inline-block'; background-color:#50689f;margin-right:1em;" class="pure-button pure-button-primary" type='button' name='back' value='<< Admin Page' onClick="javascript: window.top.location='../adminPage.php'"/>
  <label for='branchCode'>Branch Code</label>
  <input type='number' name='branchCode' id='branchCode'/>
  <label for='network'>&nbsp &nbsp Network</label>
  <select name='network' id='network'>
    <option value=''>All</option>
    <option value='1'>1</option>
    <option value='2'>2</option>
  </select>
  <label for='zone'>&nbsp &nbsp Zone</label>
  <select name='zone' id='zone'>
    <option value=''>All</option>
  </select>
  <label for='region'>&nbsp &nbsp Region</label>
  <select name='region' id='region'>
    <option value=''>All</option>
  </select>
  <label for='compliance'>&nbsp &nbsp Compliance</label>
  <select name='compliance' id='compliance'>
	<option value='Completed'>Finished</option>
	<option value='Pending'>Pending</option>
  </select>
    <input style="display:'inline-block'; background-color:#50689f;margin-left:1em;" class="pure-button pure-button-primary" type='submit' name='submit' value='Show Records'/>
</div>

</form>
<br/><br/>
<div>
  <iframe name='report' style="width: 70%;height: 25em;display:table; margin:auto;" marginheight="0" marginwidth="0" frameborder="0"></iframe>
</div>
</body>
</html>
