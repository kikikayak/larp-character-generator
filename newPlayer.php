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
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/
	
	$html = array(); // Initialize array to hold data for display
	
	$playerObj = new Player(); // Instantiate player object
	
	$html['firstName'] = isset($_POST['firstName']) ? htmlentities($_POST['firstName']) : '';
	$html['lastName'] = isset($_POST['lastName']) ? htmlentities($_POST['lastName']) : '';
	$html['email'] = isset($_POST['email']) ? htmlentities($_POST['email']) : '';
	$html['requestAccessReason'] = isset($_POST['requestAccessReason']) ? htmlentities($_POST['requestAccessReason']) : '';
	
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
			<img id="helpArrow" src="theme/<?php echo THEME; ?>/images/helpArrow.png" alt="" style="display:none" />
            
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

			<div id="requestAccessReasonHelp" class="help" style="display: none">
				<div class="helpTop"><a href="#" class="closeLink"></a></div>
				<div class="helpContent">
					<p>Please give a brief (1-2 sentence) explanation of why you're requesting access and/or how you heard about the Character Generator.</p>
					<p>We are much more likely to approve access for people who enter a meaningful reason. </p>
				</div>
				<div class="helpBottom"></div>
			</div>
            
        </div><!--#help-->
        
        <div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
        
        <?php
        	if ($_SESSION['autoGrantAccess'] == 0) {
		?>
        <h2>Request Access</h2>
		<p>It usually takes around 24 hours for access to be approved. We'll email you when your login is approved.</p>
		
		<?php
			} else {
		?>

		<h2>Sign Up</h2>

		<?php
			}
		?>
		
		<p>Privacy promise: We will never give or sell your information to third parties. </p>

		<p>If you already have an account, you can <a href="index.php">log in</a> or <a href="lostPassword.php">reset your password</a>.</p>

		<p>NOTE: If you play multiple games that use the Character Generator, you have separate logins for each game. </p>
		
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

				<?php cg_createRow('requestAccessReason'); ?>
					<div class="cell">
                        <?php
				        	if ($_SESSION['autoGrantAccess'] == 0) {
						?>
                        <label for="requestAccessReason"><span class="reqFld">* </span>Reason for Requesting Access</label>
                        <?php
				        	} else {
						?>
						<label for="requestAccessReason"><span class="reqFld">* </span>Reason for Using the Generator</label>
                        <?php
				        	}
						?>
                        <textarea id="requestAccessReason" name="requestAccessReason" class="xl2" rows="5"><?php echo $html['requestAccessReason']; ?></textarea>
                        <?php cg_showError('requestAccessReason'); ?>
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
	
	<?php require('includes/footer.php'); ?>
		
</body>

</html>