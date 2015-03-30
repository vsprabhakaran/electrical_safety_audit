    <?php
            session_start();
            $_SESSION["esa_brcode"]="";
			session_unset();
			session_destroy();
			redirect("loginPage.php");
			return;
			
	function redirect($url, $statusCode = 303)
	{
	   header('Location: ' . $url, true, $statusCode);
	   die();
	}
    ?>

