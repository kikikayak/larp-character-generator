<?php

  /**************************************************************
  NAME: 	resetPassword.php
  NOTES: 	This page allows the user to finish resetting their password. 
  **************************************************************/

  if (!isset($_GET['e'])) {
	header('Location: index.php'); // Take user to main page
	exit();   
  }
	
  session_start();
  
  require_once('includes/config.php');
  require_once(LOCATION . 'includes/library.php');
  require(LOCATION . 'class/classloader.php');
  
  $user = new Login();
  $user->initSession();

  /************************************************************************************
  PROCESS SUBMISSION
  If user has submitted page, process reset. 
  ************************************************************************************/ 
  
  $html = array(); // Initialize array to hold data for display
  
  $playerObj = new Player(); // Instantiate show object
  
  if (isset($_POST['resetPwdSubmitted']) && $_POST['resetPwdSubmitted'] == 1) {
	  $html['newPassword'] = isset($_POST['newPassword']) ? htmlentities($_POST['newPassword']) : '';
	  $html['confirmPassword'] = isset($_POST['confirmPassword']) ? htmlentities($_POST['confirmPassword']) : '';
	  $html['tmpPassword'] = isset($_POST['tmpPassword']) ? htmlentities($_POST['tmpPassword']) : '';
	  $html['email'] = isset($_GET['e']) ? htmlentities($_GET['e']) : '';
	  
	  $user = array(); // Initialize as blank array
	  $user['newPassword'] = $_POST['newPassword'];
	  $user['confirmPassword'] = $_POST['confirmPassword'];
	  $user['tmpPassword'] = $_POST['tmpPassword'];
	  $user['email'] = $_GET['e'];
		  
	  if ($playerObj->resetPassword($user)) {
		  // Do anything? 
	  }
  }
	
  $title = "Reset Lost Password";
  $stylesheet = "ua.css";
  
  require('includes/uaHeader.php');

?>

<body id="resetPwdPage">

    <div id="mast">
    	<h1><?php echo $_SESSION['campaignName']; ?> Character Generator</h1>
        <a href="index.php" id="loginLink">Log In</a>
	</div>
    
    <div id="content">
    	
        <div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
    
		<h2>Reset Your Character Generator Password</h2>
		
		<p>To finish resetting your password, please enter a new password below.</p> 
		
		<p class="reqFldKey"><span class="reqFld">*</span> Required field</p> 
		
		<form name="resetPwd" id="resetPwd" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<fieldset id="resetPwdSection" title="">
			
			<?php cg_createRow('tmpPassword'); ?>
                <label for="tmpPassword"><span class="reqFld">*</span> Temporary password</label>
                <input id="tmpPassword" name="tmpPassword" type="text" class="m2" maxlength="15" autocomplete="off" />
                <?php cg_showError('tmpPassword'); ?>
                <br class="clear" />
            </div>
			
			<?php cg_createRow('newPassword'); ?>
				<label for="newPassword"><span class="reqFld">*</span> Enter a new password</label>
				<input id="newPassword" name="newPassword" type="password" class="m2" />
				<?php cg_showError('newPassword'); ?>
                <span class="hint">Guidelines: 6-20 characters; can contain numbers, letters, and the following characters: ! - @ _ # $</span>
			</div>
			
			<?php cg_createRow('confirmPassword'); ?>
				<label for="confirmPassword"><span class="reqFld">*</span> Confirm the password</label>
				<input id="confirmPassword" name="confirmPassword" type="password" class="m2" />
				<?php cg_showError('confirmPassword'); ?>
                <br class="clear" />
			</div>
			
			<div class="btnArea">
				<input type="hidden" id="resetPwdSubmitted" name="resetPwdSubmitted" value="1">
                <input type="submit" name="resetPasswordBtn" id="resetPasswordBtn" class="btn-primary" value="Reset" />
			</div>
			
			</fieldset>
			
		</form>
	</div><!--end of content div-->
	
	<?php require('includes/footer.php'); ?>

</body>
</html>
