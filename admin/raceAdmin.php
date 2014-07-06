<?php

	/**************************************************************
	NAME: 	raceAdmin.php
	NOTES: 	This page allows staff members to add or edit races. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'races';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['raceID']) && ctype_digit($_GET['raceID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/
	
	$html = array(); // Initialize array to hold data for display
	
	$raceObj = new Race(); // Instantiate race object
	
	if ($action == 'create') { 
		$html['raceName'] = isset($_POST['raceName']) ? htmlentities($_POST['raceName']) : '';
		
		$title = 'Add a Race | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a Race';
		$btnLabel = 'Add';
		
	} else if ($action == 'update') {
		$raceDetails = $raceObj->getRace($_GET['raceID']);
		while ($savedRaceDetails = $raceDetails->fetch_assoc()) {
			$html['raceName'] = isset($_POST['raceName']) ? htmlentities($_POST['raceName']) : htmlentities($savedRaceDetails['raceName']);
		}
		
		$title = 'Update Race | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update Race';
		$btnLabel = 'Update';
	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/
	
	// If user has submitted page, process data
	if (isset($_POST['raceAdminSubmitted']) && $_POST['raceAdminSubmitted'] == 1) {
	
		if ($action == 'create') {
			if ($raceObj->addRace($_POST)) {
				session_write_close();
				header('Location: races.php');
			}
		} else {
			if ($raceObj->updateRace($_POST, $_GET['raceID'])) {
				session_write_close();
				header('Location: races.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');


?>	
<body id="raceAdminPage" class="oneCol">

	<?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>    

    <div id="content">
      <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="styles/images/helpArrowAdmin.png" alt="" style="display:none" />

		<?php cg_createHelp('raceNameHelp',
				'<p>Enter the race name as listed in the game rulebook.</p>'); ?>
        
	  </div><!--#help-->
	  
      <div id="main">
	  
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<span class="reqFldIndicator">* Required Field</span>
	  
        <h2><?php echo $pageHeader; ?></h2>
        
		<form name="raceAdmin" id="raceAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			
			<?php cg_createRow('raceName'); ?>
				<div class="cell">
					<label for="raceName">* Race Name</label>
					<input type="text" name="raceName" id="raceName" class="xl" value="<?php echo $html['raceName']; ?>" />
					<?php cg_showError('raceName'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<div class="btnArea">
				<input type="hidden" id="raceAdminSubmitted" name="raceAdminSubmitted" value="1">
				<input type="submit" id="raceAdminSave" name="raceAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
				<input type="button" id="raceAdminCancel" name="raceAdminCancel" value="Cancel" class="btn-secondary" onClick="window.location.href='races.php'" />
				<br class="clear" />
			</div>
		
		</form>
        
      </div><!--end of main div-->
      <br class="clear" />
    </div><!--end of content div-->
	
	<?php 
		cg_clearUIMessage(); 
	
		include('../includes/footer.php'); 
	?>