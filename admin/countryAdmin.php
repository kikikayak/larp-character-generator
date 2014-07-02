<?php

	/**************************************************************
	NAME: 	countryAdmin.php
	NOTES: 	This page allows staff members to add or edit countries. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'countries';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['countryID']) && ctype_digit($_GET['countryID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/
	
	$html = array(); // Initialize array to hold data for display
	
	$countryObj = new Country(); // Instantiate country object
	
	if ($action == 'create') { 
		$html['countryName'] = isset($_POST['countryName']) ? htmlentities($_POST['countryName']) : '';
		$html['countryDefault'] = isset($_POST['countryDefault']) ? htmlentities($_POST['countryDefault']) : '';
		
		$title = 'Add a Country | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a Country';
		$btnLabel = 'Add';
		
	} else if ($action == 'update') {
		$countryDetails = $countryObj->getCountry($_GET['countryID']);
		while ($savedCountryDetails = $countryDetails->fetch_assoc()) {
			$html['countryName'] = isset($_POST['countryName']) ? htmlentities($_POST['countryName']) : htmlentities($savedCountryDetails['countryName']);
			$html['countryDefault'] = isset($_POST['countryDefault']) ? htmlentities($_POST['countryDefault']) : htmlentities($savedCountryDetails['countryDefault']);
		}
		
		$title = 'Update Country | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update Country';
		$btnLabel = 'Update';
	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/
	
	// If user has submitted page, process data
	if (isset($_POST['countryAdminSubmitted']) && $_POST['countryAdminSubmitted'] == 1) {
		$country = array(); // Initialize as blank array
		$country['countryName'] = $_POST['countryName'];
		$country['countryDefault'] = $_POST['countryDefault'];
	
		if ($action == 'create') {
			if ($countryObj->addCountry($country)) {
				session_write_close();
				header('Location: countries.php');
			}
		} else {
			if ($countryObj->updateCountry($country, $_GET['countryID'])) {
				session_write_close();
				header('Location: countries.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');


?>	
<body id="countryAdminPage">

	<?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>    

    <div id="content">
      <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="../images/helpArrowAdmin.png" alt="" style="display:none" />
		
		<?php cg_createHelp('countryNameHelp',
				'<p>Enter the country name as listed in the game rulebook.</p>'); ?>

		<?php cg_createHelp('countryDefaultHelp',
				'<p>If this field is set to "Yes," the country will become the default selection in the character wizard. </p>
                <p>Setting this to "Yes" for this country will set it to "No" for all other countries.</p>'); ?>
			  
	  </div><!--#help-->
	  
      <div id="main">
	  
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<span class="reqFldIndicator">* Required Field</span>
	  
        <h2><?php echo $pageHeader; ?></h2>
        
		<form name="countryAdmin" id="countryAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			
			<?php cg_createRow('countryName'); ?>
				<div class="cell">
					<label for="countryName">* Country Name</label>
					<input type="text" name="countryName" id="countryName" class="xl" value="<?php echo $html['countryName']; ?>" />
					<?php cg_showError('countryName'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('countryDefault'); ?>
				<div class="cell">
					<label for="countryDefault">* Set as Default?</label>
					<select name="countryDefault" id="countryDefault">
						<option value="0" <?php if ($html['countryDefault'] == 0) echo 'selected'; ?>>No</option>
						<option value="1" <?php if ($html['countryDefault'] == 1) echo 'selected'; ?>>Yes</option>
					</select>
					<?php cg_showError('countryDefault'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<div class="btnArea">
				<input type="hidden" id="countryAdminSubmitted" name="countryAdminSubmitted" value="1">
				<input type="submit" id="countryAdminSave" name="countryAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
				<input type="button" id="countryAdminCancel" name="countryAdminCancel" value="Cancel" class="btn-secondary" onClick="window.location.href='countries.php'" />
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