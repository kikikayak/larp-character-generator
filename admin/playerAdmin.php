<?php

	/**************************************************************
	NAME: 	playerAdmin.php
	NOTES: 	This page allows staff members to add and edit players,
			including login and access information. 
	**************************************************************/
	
	$pageAccessLevel = 'Admin';
	$navClass = 'players';
	$scriptLink = 'players.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['playerID']) && ctype_digit($_GET['playerID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/
	
	$html = array(); // Initialize array to hold data for display
	
	$playerObj = new Player(); // Instantiate player object
	
	if ($action == 'create') { 
		$html['firstName'] = isset($_POST['firstName']) ? htmlentities($_POST['firstName']) : '';
		$html['lastName'] = isset($_POST['lastName']) ? htmlentities($_POST['lastName']) : '';
		$html['email'] = isset($_POST['email']) ? htmlentities($_POST['email']) : '';
		$html['userRole'] = isset($_POST['userRole']) ? htmlentities($_POST['userRole']) : 'User';
		
		$title = 'Add a Player | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a Player';
		$btnLabel = 'Add';
		
	} else if ($action == 'update' && isset($_GET['playerID'])) {
		$playerDetails = $playerObj->getPlayerProfile($_GET['playerID']);
		
		while ($savedPlayerDetails = $playerDetails->fetch_assoc()) {
			$html['firstName'] = isset($_POST['firstName']) ? htmlentities($_POST['firstName']) : htmlentities($savedPlayerDetails['firstName']);
			$html['lastName'] = isset($_POST['lastName']) ? htmlentities($_POST['lastName']) : htmlentities($savedPlayerDetails['lastName']);
			$html['email'] = isset($_POST['email']) ? htmlentities($_POST['email']) : htmlentities($savedPlayerDetails['email']);
			$html['userRole'] = isset($_POST['userRole']) ? htmlentities($_POST['userRole']) : htmlentities($savedPlayerDetails['userRole']);
		}
		
		$title = 'Update Player | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update Player';
		$btnLabel = 'Update';
	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['playerAdminSubmitted']) && $_POST['playerAdminSubmitted'] == 1) {
		if ($action == 'create') {
			if ($playerObj->addPlayer($_POST)) {
				session_write_close();
				header('Location: players.php');
			}
		} else {
			if ($playerObj->updatePlayer($_POST, $_GET['playerID'])) {
				session_write_close();
				header('Location: players.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');

?>

<body id="playerAdminPage">

	<?php include('../includes/adminNav.php'); ?>
    
    <div id="content" class="oneCol">
	
		<div id="help">
			<img id="helpArrow" src="../images/helpArrowAdmin.png" alt="" style="display:none" />

			<?php cg_createHelp('emailHelp',
				'<p>This email address will be used as the player\'s login. The email address will also be used if the player forgets their password. </p>
					<p>Each player needs a unique, valid email address. Two players cannot share an email address in the Generator.</p>'); ?>
			
			<?php cg_createHelp('passwordHelp',
				'<p>Please enter a password between 6 and 20 characters.</p>
                    <p>Passwords may include letters, numbers, and the following characters: - _ ! @ # $
                    <p>Do not enter spaces.</p>'); ?>
			
			<?php cg_createHelp('userRoleHelp',
				'<p>Users can have one of three access levels in the Generator:</p>
                    <ul>
                    	<li><strong>User</strong>: Users can see the player-facing section and create characters. The number of characters they can create is limited by the Character Generator settings.</li>
                    	<li><strong>Staff</strong>: Staff members can access the Admin section of the Generator. They are not able to perform some functions in the Admin section (e.g. creating and editing users, permanently deleting items). </li>
                        <li><strong>Admin</strong>: Admins can access the Admin section of the Generator and have access to all functions.</li>
                    </ul>'); ?>
            
            <?php cg_createHelp('sendUserEmailHelp',
				'<p>If this option is checked, the player will receive an email notifying them that a login has been created in the Generator. The email will also contain their password. </p>
                    <p>Generally, you should check this option when creating a player, but not when you edit the player without changing the password. </p>'); ?>
            
		</div><!--#help-->

		<div id="main">
        	
            <div id="msg">
				<?php cg_showUIMessage(); ?>
            </div>
        
			<h2><?php echo $pageHeader; ?></h2>		
			<form name="playerAdmin" id="playerAdmin" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			
				<div id="contactInfoSection" class="section">
					<h3>Contact Information</h3>
					<fieldset>
						<?php cg_createRow('firstName'); ?>
							<div class="cell">
								<label for="firstName"><span class="reqFld">* </span>First Name</label>
								<input type="text" id="firstName" name="firstName" class="m" maxlength="50" value="<?php echo $html['firstName']; ?>" />
                                <?php cg_showError('firstName'); ?>
								<br class="clear" />	
							</div>
						</div>
						
						<?php cg_createRow('lastName'); ?>
							<div class="cell">
								<label for="lastName"><span class="reqFld">* </span>Last Name</label>
								<input type="text" id="lastName" name="lastName" class="m" maxlength="50" value="<?php echo $html['lastName']; ?>" />
                                <?php cg_showError('lastName'); ?>
								<br class="clear" />
							</div>	
						</div>
						
						<?php cg_createRow('email'); ?>
							<div class="cell">
								<label for="email"><span class="reqFld">* </span>Email</label>
								<input type="text" id="email" name="email" class="l" maxlength="50" value="<?php echo $html['email']; ?>" />
                                <?php cg_showError('email'); ?>
								<br class="clear" />
							</div>
						</div>	
					
					</fieldset>
				</div><!--#contactInfoSection-->
				
				<div id="userInfoSection" class="section">
					<h3>User Information</h3>
					<fieldset>
						<?php cg_createRow('newPassword'); ?>
							<div class="cell">
								<label for="newPassword"><span class="reqFld">* </span>Password</label>
								<input type="password" id="newPassword" name="newPassword" class="m" autocomplete="off" value="" />
                                <?php cg_showError('newPassword'); ?>
								<br class="clear" />
							</div>
						</div>
						
						<?php cg_createRow('confirmPassword'); ?>
							<div class="cell">
								<label for="confirmPassword"><span class="reqFld">* </span>Confirm Password</label>
								<input type="password" id="confirmPassword" name="confirmPassword" class="m" autocomplete="off" value="" />
                                <?php cg_showError('confirmPassword'); ?>
								<br class="clear" />	
							</div>
						</div>
						
						<?php cg_createRow('userRole'); ?>
							<div class="cell">
								<label><span class="reqFld">* </span>Access Level:</label>
								<input type="radio" value="User" name="userRole" id="userRoleUser" class="radioBtn" <?php if ($html['userRole'] == 'User') echo 'checked="checked"'; ?> />
								<p id="userLbl" class="chkboxLbl">User</p>
								<input type="radio" value="Staff" name="userRole" id="userRoleStaff" class="radioBtn" <?php if ($html['userRole'] == 'Staff') echo 'checked="checked"'; ?> />
								<p id="staffLbl" class="chkboxLbl">Staff</p>
								<input type="radio" value="Admin" name="userRole" id="userRoleAdmin" class="radioBtn" <?php if ($html['userRole'] == 'Admin') echo 'checked="checked"'; ?> />
								<p id="adminLbl" class="chkboxLbl">Administrator</p>
                                <?php cg_showError('userRole'); ?>
							</div>
						</div>
					
					</fieldset>
				</div><!--#userInfoSection-->
				
				<div id="btnArea">
					<div class="row" id="sendEmailRow">
						<input type="checkbox" value="Yes" name="sendUserEmail" id="sendUserEmail" />
						<label>Send  email to user with their login information?</label>
						<br class="clear" />
					</div>
					<input type="hidden" id="playerAdminSubmitted" name="playerAdminSubmitted" value="1">
                    <input type="submit" id="saveBtn" class="btn-primary" value="<?php echo $btnLabel; ?>" />
                    
				</div><!--#btnArea-->
				
			</form>
            
        </div> <!--#main-->
        
        <br class="clear" />
    </div><!--#content-->
    
<?php include('../includes/footer.php'); ?>
