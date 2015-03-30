<?php

  /**************************************************************
	NAME: 	lostPassword.php
	NOTES: 	This page allows the user to request a reset of their Character Generator password. 
  **************************************************************/
	
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
  
  if (isset($_POST['lostPwdSubmitted']) && $_POST['lostPwdSubmitted'] == 1) {
	  $html['email'] = isset($_POST['email']) ? htmlentities($_POST['email']) : '';
	  
	  $data = array(); // Initialize as blank array
	  $data['email'] = $_POST['email'];
		  
	  if ($playerObj->sendLostPassword($data['email'])) {
		  // Do anything? 
	  }
  }
	
  $title = "Reset Lost Password";
  $stylesheet = "ua.css";
  
  require('includes/uaHeader.php');

?>

<body id="lostPwdPage">

    <div id="mast">
    	<h1><?php echo $_SESSION['campaignName']; ?> Character Generator</h1>
        <a href="index.php" id="loginLink">Log In</a>
	</div>
    
    <div id="content">
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
        
        <h2>Reset Lost Password</h2>
		
		<p>To reset your Character Generator password, please enter the email address you use for your Character Generator account. </p>

		<p>You will receive an email with instructions on how to reset your password.</p>
		
		<form name="resetPwd" id="resetPwd" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<fieldset id="resetPwdSection" title="">
				<p class="reqFldKey"><span class="reqFld">*</span> Required field</p> 
				
				<?php cg_createRow('email'); ?>
					<label for="email"><span class="reqFld">*</span> Email</label>
	                <input id="email" name="email" type="text" class="xl2" maxlength="50" />
	                <?php cg_showError('email'); ?>
	                <br class="clear" />
	            </div>
				
				<div class="btnArea">
					<input type="hidden" id="lostPwdSubmitted" name="lostPwdSubmitted" value="1">
	                <input type="submit" name="resetPasswordBtn" id="resetPasswordBtn" class="btn-primary" value="Reset Password" />
				</div>
			
			</fieldset>
			
		</form>
	</div><!--end of content div-->
	
	<?php require('includes/footer.php'); ?>

</body>
</html>
