<?php 

/********************************************************************
NAME: 	index.php
NOTES:	Character Generator login page. 
*********************************************************************/

session_start();

require_once('includes/config.php');
require_once(LOCATION . 'includes/library.php');
require(LOCATION . 'class/classloader.php');
	
$user = new Login();
$user->initSession();

$html = array(); // Initialize array to hold data for display
$html['login'] = isset($_POST['login']) ? htmlentities($_POST['login']) : '';
$html['campaignName'] = isset($_SESSION['campaignName']) ? htmlentities($_SESSION['campaignName']) : '';

if (isset($_POST['login']) && isset($_POST['password'])) {
	// User has just attempted to log in
	$_SESSION['isLoggedIn'] = 0;
	
	$login = new Login();
	 
	$_SESSION['isLoggedIn'] = $login->authenticateUser($_POST['login'], $_POST['password']);
	
	if ($_SESSION['isLoggedIn'] == 1) {	
		header('Location: main/index.php'); // Take user to main page
		exit();
	}
		
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $html['campaignName']; ?> Character Generator | <?php echo $html['campaignName']; ?> Character Generator</title>

<link href='http://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="js/ui/css/theme/ui.min.css" />
<link rel="stylesheet/less" type="text/css" href="theme/<?php echo THEME; ?>/main.less" />

<script type="text/javascript">
    // Set LESS parameters
	less = {
        env:"development",
        dumpLineNumbers: "all", // or "mediaQuery" or "all"
		relativeUrls: true // whether to adjust url's to be relative
                            // if false, url's are already relative to the
                            // entry less file
    };
	
</script>

<script type="text/javascript" src="js/less-1.3.3.min.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/ui/js/jquery-ui-1.10.3.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>


</head>

<body id="loginPage">

    <div id="content">
    	<div id="main">
			
        	<div id="loginArea">
            <h1><?php echo $html['campaignName']; ?> Character Generator</h1>
				
                <form id="loginForm" name="loginForm" method="post" action="index.php">
                    <div id="msg">
                        <?php cg_showUIMessage(); ?>
                    </div>
            
                    <div class="row">
                        <label>Email</label>
                        <input type="text" name="login" id="login" class="xl" value="<?php echo $html['login']; ?>" />
                        <br class="clear" />
                    </div>
                    
                    <div class="row">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="xl" autocomplete="off" />
                        <a href="lostPassword.php" id="forgotPwdLink">Forgot password?</a>
                    </div>
                    
                    <div class="btnArea">
                        <input type="submit" name="loginBtn" id="loginBtn" value="Log In" class="btn-primary" />
                        <a href="newPlayer.php" id="requestLoginLink">New user?</a>
                        <br class="clear" />
                    </div>
					
				</form>
            </div><!--end of loginArea-->
            
            <p class="legal">
                Game rules copyright &copy; <?php echo date('Y') . ' ' . $_SESSION['campaignName']; ?> (under license from Accelerant) <br />
                The <a href="http://larpcharactergenerator.com" target="_blank">Character Generator</a> is free software created by Allison B. Corbett <br />
                and licensed under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU Public License v3.0.</a>
            </p>

            
            <div id="faqLinkArea">
        		<!--<a href="faq.php" id="faqLink">Frequently Asked Questions (FAQs)</a>-->
        	</div>
        </div><!--end of main div-->
         
    </div><!--end of content div-->
    
    <?php cg_clearUIMessage(); ?>

</body>
</html>
