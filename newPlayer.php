<?php

	/**************************************************************
	NAME: 	newPlayer.php
	NOTES: 	This page allows the user to request a CG login. 
	**************************************************************/
	
	session_start();
	
	require_once('includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	
	$user = new Login();
	$user->initSession();
	
	$title = "Request Access to the " . $_SESSION['campaignName'] . "Character Generator";
	$stylesheet = "ua.css";
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/
	
	$html = array(); // Initialize array to hold data for display
	
	$playerObj = new Player(); // Instantiate player object
	
	$html['firstName'] = isset($_POST['firstName']) ? htmlentities($_POST['firstName']) : '';
	$html['lastName'] = isset($_POST['lastName']) ? htmlentities($_POST['lastName']) : '';
	$html['email'] = isset($_POST['email']) ? htmlentities($_POST['email']) : '';
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['requestLoginSubmitted']) && $_POST['requestLoginSubmitted'] == 1) {
	  if ($playerObj->requestLogin($_POST)) {
		  session_write_close();
		  // header('Location: index.php');
	  }
	}
	
	require('includes/uaHeader.php');

?>

<body id="requestLoginPage">

    <div id="mast">
    	<h1><?php echo $_SESSION['campaignName']; ?> Character Generator</h1>
        <a href="index.php" id="loginLink">Log In</a>
    </div><!--#mast-->
    
    <div id="content">
		<div id="help">
			<img id="helpArrow" src="images/helpArrow.png" alt="" style="display:none" />
            
            <div id="emailHelp" class="help" style="display: none">
				<div class="helpTop"><a href="#" class="closeLink"></a></div>
				<div class="helpContent">
					<p>Please enter a unique, valid email address. This email address will be used as your login. </p>
                    <p>Your email will be used if you forget your password, to notify you of new CP assignments, and for all other Generator-related communications.</p>
					<p>Two players cannot share an email address in the Generator.</p>
				</div>
				<div class="helpBottom"></div>
			</div>
			
			<div id="passwordHelp" class="help" style="display: none">
				<div class="helpTop"><a href="#" class="closeLink"></a></div>
				<div class="helpContent">
					<p>Please enter a password between 6 and 20 characters.</p>
                    <p>Passwords may include letters, numbers, and the following characters: - _ ! @ # $
                    <p>Do not enter spaces.</p>
				</div>
				<div class="helpBottom"></div>
			</div>
            
        </div><!--#help-->
        
        <div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
        
        <h2>Request Access</h2>
		<p>You can use this form to request access to the <?php echo $_SESSION['campaignName']; ?> Character Generator. We'll email you when your login is approved.</p>
		
		<p>If you already have access, you can <a href="index.php">log in</a> or <a href="lostPassword.php">reset your password</a>.</p>
		
		<form name="requestLogin" id="requestLogin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<fieldset id="playerInformation">
				<p class="reqFldKey"><span class="reqFld">*</span> Required field</p> 
			
				<?php cg_createRow('firstName'); ?>
					<div class="cell">
                      <label><span class="reqFld">*</span> First Name</label>
                      <input type="text" id="firstName" name="firstName" class="xl2" maxlength="50" value="<?php echo $html['firstName']; ?>" />
                      <?php cg_showError('firstName'); ?>
                      <br class="clear" />
                    </div>
				</div>
				
				<?php cg_createRow('lastName'); ?>
					<div class="cell">
                        <label for="lastName"><span class="reqFld">* </span>Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="xl2" maxlength="50" value="<?php echo $html['lastName']; ?>" />
                        <?php cg_showError('lastName'); ?>
                        <br class="clear" />
                    </div>
				</div>
				
				<?php cg_createRow('email'); ?>
					<div class="cell">
                        <label for="email"><span class="reqFld">* </span>Email</label>
                        <input type="text" id="email" name="email" class="xl2" maxlength="50" value="<?php echo $html['email']; ?>" />
                        <?php cg_showError('email'); ?>
                        <br class="clear" />
                    </div>
				</div>
                
                <?php cg_createRow('password'); ?>
					<div class="cell">
                        <label for="password"><span class="reqFld">* </span>Password</label>
                        <input type="password" id="password" name="password" class="xl2" autocomplete="off" value="" />
                        <?php cg_showError('password'); ?>
                        <br class="clear" />
                    </div>
				</div>
                
                <?php cg_createRow('confirmPassword'); ?>
					<div class="cell">
                        <label for="confirmPassword"><span class="reqFld">* </span>Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="xl2" autocomplete="off" value="" />
                        <?php cg_showError('confirmPassword'); ?>
                        <br class="clear" />	
                    </div>
   				</div>
				
				<div class="btnArea">
					<input type="hidden" id="requestLoginSubmitted" name="requestLoginSubmitted" value="1">
                    <input type="submit" name="requestLoginBtn" id="requestLoginBtn" class="btn-primary" value="Request Access" />
				
				</div><!--.btnArea-->
				
			</fieldset>
		</form>
	</div>
	
	<?php require('includes/uaFooter.php'); ?>
		
</body>

</html>