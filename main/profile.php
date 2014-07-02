<?php

	/**************************************************************
	NAME: 	profile.php
	NOTES: 	This is the page the player uses to change his or her 
			password and other account information. 
	**************************************************************/
	
	$pageAccessLevel = 'User';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$_SESSION['UIMessage'] = ''; // Initialize empty
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/
	
	$html = array(); // Initialize array to hold data for display
	
	$player = new Player();
	
	if (isset($_POST['profileSaved']) && $_POST['profileSaved'] == 1) { // User has submitted page
		
		// Validate data and then insert it (if all is okay)
		$player->updatePlayerProfile($_POST);
		
		// Set up data for insert/display. 
		$html['firstName'] = htmlentities($_POST['firstName']);
		$html['lastName'] = htmlentities($_POST['lastName']);
		$html['email'] = htmlentities($_POST['email']);
		
	} else {
		// Set up values for display in form fields
		$profileResult = $player->getPlayerProfile($_SESSION['playerID']);
		
		while ($profile = $profileResult->fetch_assoc()) { // Loop through player result
			$html['firstName'] = htmlentities($profile['firstName']);
			$html['lastName'] = htmlentities($profile['lastName']);
			$html['email'] = htmlentities($profile['email']);
		}
	}
	
	$title = 'Your Profile | Character Generator';
	$pageHeader = 'Your Profile';
	$scriptLink = 'players.js';

?>

<?php include('../includes/header.php'); ?>

<body id="profilePage">

    <?php include('../includes/mastNav.php'); ?>
    
    <div id="content">
	
		<div id="help">
			<img id="helpArrow" src="../images/helpArrow.png" alt="" style="display:none" />
			
			<div id="emailHelp" class="help" style="display: none">
				<a href="#" class="helpClose" onClick="hideAllHelp(); return false"></a>
				<p>Your email address is also used as your login.</p>
				<p>Please enter a valid email address in the format email@example.com. </p>
				<p>You must have a unique, valid email address to use the Generator. Two players cannot share an email address in the Generator.</p>
			</div>
			
			<div id="curPasswordHelp" class="help" style="display: none">
				<a href="#" class="helpClose" onClick="hideAllHelp(); return false"></a>
				<p>For your protection, please enter your current password. </p>
			</div>
			
			<div id="passwordHelp" class="help" style="display: none">
				<a href="#" class="helpClose" onClick="hideAllHelp(); return false"></a>
				<p>If you would like to change your password, enter the new password here. You must also re-enter the password in the "Confirm password" field.</p>
				<p>Passwords must be between 6 and 20 characters long. </p>
				<p>Enter letters, numbers, and/or these characters: ! @ # $ - _</p>
				<p>Do not enter spaces or other special characters. </p>
			</div>
			
			<div id="confirmPasswordHelp" class="help" style="display: none">
				<a href="#" class="helpClose" onClick="hideAllHelp(); return false"></a>
				<p>For your protection, please re-enter your new password. </p>
			</div>
						
	  </div><!--end of help div-->
        
        <div id="main">
		
            <h2><?php echo $pageHeader; ?></h2>		
			<form name="myProfile" id="myProfile" action="profile.php" method="post">
				
				<?php cg_showUIMessage(); ?>
			
				<div id="contactInfoSection" class="section">
					<h3>Contact Information</h3>
					<fieldset>
						<?php cg_createRow('firstName'); ?>
							<label for="firstName">First Name</label>
							<input type="text" id="firstName" name="firstName" class="xl2" value="<?php echo $html['firstName']; ?>" />
							<?php cg_showError('firstName'); ?>
						</div>
						
						<?php cg_createRow('lastName'); ?>
							<label for="lastName">Last Name</label>
							<input type="text" id="lastName" name="lastName" class="xl2" value="<?php echo $html['lastName']; ?>" />
							<?php cg_showError('lastName'); ?>
						</div>
						
						<?php cg_createRow('email'); ?>
							<label for="email">Email<br /></label>
							<input type="text" id="email" name="email" class="xl2" value="<?php echo $html['email']; ?>" />
							<?php cg_showError('email'); ?>
						</div>
					</fieldset>
				</div><!--/section-->
				
				<div id="userInfoSection" class="section">
					<h3>Login Information</h3>
					
					<fieldset>	
						<?php cg_createRow('curPassword'); ?>
							<label for="curPassword">Current Password</label>
							<input type="password" id="curPassword" name="curPassword" class="xl2" autocomplete="off" />
							<?php cg_showError('curPassword'); ?>
						</div>	
						
						<?php cg_createRow('newPassword'); ?>
							<label for="newPassword">New Password <span class="opt">(optional)</span></label>
							<input type="password" id="newPassword" name="newPassword" class="xl2" autocomplete="off" />
							<?php cg_showError('newPassword'); ?>
						</div>
						
						<?php cg_createRow('confirmPassword'); ?>
							<label for="confirmPassword">Confirm Password</label>
							<input type="password" id="confirmPassword" name="confirmPassword" class="xl2" autocomplete="off" />
							<?php cg_showError('confirmPassword'); ?>
						</div>
						
					</fieldset>
				</div> <!--/section-->
				
				<div id="btnArea">
					<input type="hidden" name="profileSaved" id="profileSaved" value="1" />
					<input type="submit" id="saveProfile" class="btn-primary" value="Save Changes" />
					<br class="clear" />
				</div>
				
			</form>
            
        </div> <!--end of main div-->
        
        <br class="clear" />
    </div>
<!--end of content div-->
    
<?php include('../includes/footer.php'); ?>
