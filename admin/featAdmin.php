<?php

	/**************************************************************
	NAME: 	featAdmin.php
	NOTES: 	This page allows staff members to add or edit feats. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'feats';
	$scriptLink = 'feats.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['featID']) && ctype_digit($_GET['featID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/ 
	
	$html = array(); // Initialize array to hold data for display
	
	$featObj = new Feat(); // Instantiate feat object
	
	if ($action == 'create') { 
		$html['featName'] = isset($_POST['featName']) ? htmlentities($_POST['featName']) : '';
		$html['featCost'] = isset($_POST['featCost']) ? htmlentities($_POST['featCost']) : 4;
		$html['featPrereq'] = isset($_POST['featPrereq']) ? htmlentities($_POST['featPrereq']) : '';
		$html['featShortDescription'] = isset($_POST['featShortDescription']) ? htmlentities($_POST['featShortDescription']) : '';
		$html['featDescription'] = isset($_POST['featDescription']) ? htmlentities($_POST['featDescription']) : '';
		$html['featCheatSheetNote'] = isset($_POST['featCheatSheetNote']) ? htmlentities($_POST['featCheatSheetNote']) : '';

		$title = 'Add a Feat | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a Feat';
		$btnLabel = 'Add';
		
	} else if ($action == 'update' && isset($_GET['featID'])) {
		$featDetails = $featObj->getFeat($_GET['featID']);
		while ($savedFeatDetails = $featDetails->fetch_assoc()) {
			$html['featName'] = isset($_POST['featName']) ? htmlentities($_POST['featName']) : htmlentities($savedFeatDetails['featName']);
			$html['featCost'] = isset($_POST['featCost']) ? htmlentities($_POST['featCost']) : htmlentities($savedFeatDetails['featCost']);
			$html['featPrereq'] = isset($_POST['featPrereq']) ? htmlentities($_POST['featPrereq']) : htmlentities($savedFeatDetails['featPrereq']);
			$html['featShortDescription'] = isset($_POST['featShortDescription']) ? htmlentities($_POST['featShortDescription']) : htmlentities($savedFeatDetails['featShortDescription']);
			$html['featDescription'] = isset($_POST['featDescription']) ? htmlentities($_POST['featDescription']) : htmlentities($savedFeatDetails['featDescription']);
			$html['featCheatSheetNote'] = isset($_POST['featCheatSheetNote']) ? htmlentities($_POST['featCheatSheetNote']) : htmlentities($savedFeatDetails['featCheatSheetNote']);
			
		} // end featDetails loop
		
		$title = 'Update Feat | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update Feat';
		$btnLabel = 'Update';

	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['featAdminSubmitted']) && $_POST['featAdminSubmitted'] == 1) {
			
		if ($action == 'create') {
			if ($featObj->addFeat($_POST)) {
				session_write_close();
				header('Location: feats.php');
			}
		} else {
			if ($featObj->updateFeat($_POST, $_GET['featID'])) {
				session_write_close();
				header('Location: feats.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');

?>

<body id="featAdminPage" class="oneCol">
    
    <?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content">
      
	  <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="styles/images/helpArrowAdmin.png" alt="" style="display:none" />

		<?php cg_createHelp('featNameHelp',
				'<p>Enter the feat\'s name as listed in the game rulebook.</p>'); ?>
		
		<?php cg_createHelp('featCostHelp',
				'<p>Enter the feat\'s cost in CP.</p>'); ?>
        
        <?php cg_createHelp('featPrereqHelp',
				'<p>Specify a header, skill, or other game item that a character must purchase before buying this feat. Enter free text, e.g. "Medic Header."</p>
                <p>Prerequisites are currently informational only. The Generator does not enforce them.</p>'); ?>
        
        <?php cg_createHelp('featShortDescriptionHelp',
				'<p>This is the brief blurb that appears under a feat during character creation.</p>
				<p>You may wish to include any call in the description: e.g. "Does 2 damage."</p>'); ?>
        
        <?php cg_createHelp('featDescriptionHelp',
				'<p>This is a longer, more detailed description of the feat (usually the complete description found in the game rulebook).</p>
				<p>Players will not see this description by default, but may choose to show it if they want more details on the feat.</p>'); ?>
		
		<?php cg_createHelp('featCheatSheetNoteHelp',
				'<p>This line appears on the character\'s cheat sheet next to the feat name. A player can print the cheat sheet and bring it to an event to help them remember their skill and attribute usage. </p>
				<p>It\'s helpful to include effects (e.g. "2 damage by fire"), but you can enter anything you think will be helpful to the player.</p>'); ?>
	  
	  </div><!--#help-->
	  
	  <!--no sidebar-->
	  
      <div id="main">
        <span class="reqFldIndicator">* Required Field</span>
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<h2><?php echo $pageHeader; ?></h2>
		
		<form name="featAdmin" id="featAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        
			<?php cg_createRow('featName'); ?>
				<div class="cell">
					<label for="featName">Name</label>
					<input type="text" name="featName" id="featName" class="xl" value="<?php echo $html['featName']; ?>" />
					<?php cg_showError('featName'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('featCost'); ?>
				<div class="cell">
					<label for="featCost">Cost</label>
					<input type="text" name="featCost" id="featCost" class="s2" maxlength="3" value="<?php echo $html['featCost']; ?>" />
					<p class="unit">CP</p>
					<?php cg_showError('featCost'); ?>
					<br class="clear" />
				</div>
			</div>
            
            <?php cg_createRow('featPrereq'); ?>
				<div class="cell">
					<label for="featPrereq">Prerequisite<br /><span class="optional">(optional)</span></label>
					<input type="text" name="featPrereq" id="featPrereq" class="xl" value="<?php echo $html['featPrereq']; ?>" />
					<?php cg_showError('featPrereq'); ?>
					<br class="clear" />
				</div>
			</div>
										
			<?php cg_createRow('featShortDescription'); ?>
				<div class="cell">
					<label for="featShortDescription">Short description<br /><span class="optional">(optional)</span></label>
					<textarea rows="3" cols="39" name="featShortDescription" id="featShortDescription" class="xl-textarea"><?php echo $html['featShortDescription']; ?></textarea>
					<?php cg_showError('featShortDescription'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('featDescription'); ?>
				<div class="cell">
					<label for="featDescription">Long description<br /><span class="optional">(optional)</span></label>
					<textarea name="featDescription" cols="39" rows="12" id="featDescription" class="xl-textarea"><?php echo $html['featDescription']; ?></textarea>
					<?php cg_showError('featDescription'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('featCheatSheetNote'); ?>
				<div class="cell">
					<label for="featCheatSheetNote">Cheat sheet note<br /><span class="optional">(optional)</span></label>
					<input type="text" maxlength="255" id="featCheatSheetNote" name="featCheatSheetNote" class="xl" value="<?php echo $html['featCheatSheetNote']; ?>" />
					<?php cg_showError('featCheatSheetNote'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<div class="btnArea">
				<input type="hidden" id="featAdminSubmitted" name="featAdminSubmitted" value="1">
				<input type="submit" id="featAdminSave" name="featAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
				<input type="button" id="featAdminCancel" name="featAdminSave" value="Cancel" class="btn-secondary" onClick="window.location.href='feats.php'" />
				<br class="clear" />
			</div>
		
		</form>
        
      </div><!--/main-->
      <br class="clear" />
    </div><!--/content-->
    
<?php include('../includes/footer.php'); ?>