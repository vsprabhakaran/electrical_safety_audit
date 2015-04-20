<!doctype html>
<head>
<?php
    session_start();
    if(!(isset($_SESSION["esa_brcode_temp"])  || isset ($_SESSION["esa_brcode"])))	//checking for active sessions
    {
       ?>
			<script type="text/javascript">
				alert("Invalid Session!!!");
			</script>
			<meta http-equiv="refresh" content="0;URL=loginPage.php">
		<?php
    }
?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Change Password</title>
	<link rel="stylesheet" href="css/pure-min.css">
    <script type="text/javascript" src="js/jquery-latest.min.js"></script>
	<style type="text/css">
		#formid{
			margin:auto;
			margin-left:30%;
			margin-top:10em;
		}
		#formTitle{
			font-weight:bold;
			font-size:20px;
			color: rgb(5, 110, 165);
			padding-bottom:2em;
			//text-align:center;
		}
	</style>
    <script type="text/javascript">

        function doPOST_Request(phpURL, password, typeCall) {
            var returnmsg;
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { pwd: password, type: typeCall },
                success: function (msg) {
                    returnmsg = msg;
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnmsg;
        }
        function resetForm() {
            $("#oldPassword").val("");
            $("#newPassword").val("");
            $("#confirmPassword").val("");
        }

        function submitFunction() {
            var oldPwd = $("#oldPassword").val();
            var newPwd = $("#newPassword").val();
            var confirmPwd = $("#confirmPassword").val();
            var oldPwdMsg = doPOST_Request('db/dbQueries.php', oldPwd, 'checkOldPassword');
            if (oldPwdMsg == "true") {
                if (newPwd == confirmPwd) {
                    if (newPwd.length > 5) {
                        var changePwdMsg = doPOST_Request('db/dbQueries.php', newPwd, 'updateNewPassword');
                        if (changePwdMsg == "true") {
                            alert("Password changed successfully!");
                            window.location.href = "dataEntry.php";
                        }
                        else
                            alert("Something went wrong. Can't change your password!");
                    }
                    else {
                        alert("Password should contain atleast 6 characters");
                        resetForm();
                    }

                }
                else {
                    alert("New passwords didn't match.");
                    resetForm();
                }
            }
            else {
                alert("Old password didn't match.");
                resetForm();
            }

        }
    </script>
</head>

<body>
	<script type="text/javascript">
	    $(document).ready(function () {
			$("#header").load("header.php");
            $('#formid').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $('#formid').bind('submit', function () {
                submitFunction();
            });
        });
		
	</script>
	<div id="header"> </div>
	<div id="container">
		<form id="formid" class="pure-form pure-form-aligned" style="padding-left: 40px;">
		<div id="formTitle"> Change Branch Password </div>
		<div class="pure-control-group">
		<label id="labels" for="oldPassword" >Old Password</label>
		<input id="oldPassword" type="password" name="oldPassword"/>
		</div>
		<div class="pure-control-group">
		<label id="labels" for="newPassword">New Password</label>
		<input id="newPassword" type="password" name="newPassword" />
		</div>
        <div class="pure-control-group">
		<label id="labels" for="confirmPassword">Confirm Password</label>
		<input id="confirmPassword" type="password" name="confirmPassword"/>
		</div>
		<br>

		<div  class="pure-controls" style="padding-bottom:20px;">
            <button class="pure-button pure-button-primary" type="button" onClick="submitFunction()" id="formButton">Change Password</button>
        </div>
		</div>
		</form>
		
	</div>
</body>

</html>

	
	
	
		
	