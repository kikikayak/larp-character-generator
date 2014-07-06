<?php

	/**************************************************************
	NAME: 	communityAdmin.php
	NOTES: 	This page allows staff members to add or edit communities. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'communities';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['communityID']) && ctype_digit($_GET['communityID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/ 
	
	$html = array(); // Initialize array to hold data for display
	
	$communityObj = new Community(); // Instantiate community object
	
	if ($action == 'create') { 
		$html['communityName'] = isset($_POST['communityName']) ? htmlentities($_POST['communityName']) : '';
		
		$title = 'Add a ' . $_SESSION['communityLabel'] . ' | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a ' . $_SESSION['communityLabel'];
		$btnLabel = 'Add';
		
	} else if ($action == 'update' && isset($_GET['communityID'])) {
		$communityDetails = $communityObj->getCommunity($_GET['communityID']);
		while ($savedCommunityDetails = $communityDetails->fetch_assoc()) {
			$html['communityName'] = isset($_POST['communityName']) ? htmlentities($_POST['communityName']) : htmlentities($savedCommunityDetails['communityName']);			
			
		} // end communityDetails loop
		
		$title = 'Update ' . $_SESSION['communityLabel'] . ' | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update ' . $_SESSION['communityLabel'];
		$btnLabel = 'Update';
	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['communityAdminSubmitted']) && $_POST['communityAdminSubmitted'] == 1) {
			
		if ($action == 'create') {
			if ($communityObj->addCommunity($_POST)) {
				session_write_close();
				header('Location: communities.php');
			}
		} else {
			if ($communityObj->updateCommunity($_POST, $_GET['communityID'])) {
				session_write_close();
				header('Location: communities.php');	
			}
		}
	}

	include('../includes/header_admin.php');


?>

<body id="communityAdminPage">
    
    <?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content">
      <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="styles/images/helpArrowAdmin.png" alt="" style="display:none" />

		<?php cg_createHelp('communityNameHelp',
				'<p>Enter the ' . $_SESSION['communityLabel'] . '\'s name as listed in the game rulebook (or as created by players).</p>'); ?>
			  
	  </div><!--end of help-->
	  
      <div id="main">
        <span class="reqFldIndicator">* Required Field</span>
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<h2><?php echo $pageHeader; ?></h2>
        
        <form name="communityAdmin" id="communityAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            
			<?php cg_createRow('communityName'); ?>
                <div class="cell">
                    <label for="communityName">Name</label>
                    <input type="text" name="communityName" id="communityName" class="xl" value="<?php echo $html['communityName']; ?>" />
                    <?php cg_showError('communityName'); ?>
					<br class="clear" />
                </div>
            </div>
            
            <div class="btnArea">
                <input type="hidden" id="communityAdminSubmitted" name="communityAdminSubmitted" value="1">
                <input type="submit" id="communityAdminSave" name="communityAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
                <input type="button" id="communityAdminCancel" name="communityAdminCancel" value="Cancel" class="btn-secondary" onClick="window.location.href='communities.php'" />
				<br class="clear" />
            </div>
        </form>
        
      </div>
      <!--end of main div-->
      <br class="clear" />
    </div><!--end of content div-->
    
<?php include('../includes/footer.php'); ?>