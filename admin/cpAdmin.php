<?php

	/**************************************************************
	NAME: 	cpAdmin.php
	NOTES: 	This page allows staff members to add or edit CP records. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'cp';
	$scriptLink = 'cp.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['CPTrackID']) && ctype_digit($_GET['CPTrackID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/ 
	
	$html = array(); // Initialize array to hold data for display
	
	$cpObj = new CP(); // Instantiate header object
	
	// Get data to populate dropdown lists
	$character = new Character();
	$characters = $character->getAllCharacters();
	
	$player = new Player();
	$players = $player->getAllPlayers();
	
	$categories = $cpObj->getCPCategories();
	
	if ($action == 'create') { 
		$html['CPType'] = isset($_POST['CPType']) ? htmlentities($_POST['CPType']) : 'Character';
		$html['characterID'] = isset($_POST['characterID']) ? htmlentities($_POST['characterID']) : '';
		$html['playerID'] = isset($_POST['playerID']) ? htmlentities($_POST['playerID']) : '';
		$html['numberCP'] = isset($_POST['numberCP']) ? htmlentities($_POST['numberCP']) : '';
		$html['CPCatID'] = isset($_POST['CPCatID']) ? htmlentities($_POST['CPCatID']) : '';
		$html['CPNote'] = isset($_POST['CPNote']) ? htmlentities($_POST['CPNote']) : '';
		$html['CPDateStamp'] = '';
		$html['staffMember'] = '';
		
		$title = 'Add CP | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add CP';
		$btnLabel = 'Add';
		
	} else if ($action == 'update' && isset($_GET['CPTrackID'])) {
		$cpDetails = $cpObj->getCPDetails($_GET['CPTrackID']);
		while ($savedCPDetails = $cpDetails->fetch_assoc()) {
			$html['CPType'] = isset($_POST['CPType']) ? htmlentities($_POST['CPType']) : htmlentities($savedCPDetails['CPType']);
			$html['characterID'] = isset($_POST['characterID']) ? htmlentities($_POST['characterID']) : htmlentities($savedCPDetails['characterID']);
			$html['playerID'] = isset($_POST['playerID']) ? htmlentities($_POST['playerID']) : htmlentities($savedCPDetails['playerID']);
			$html['numberCP'] = isset($_POST['numberCP']) ? htmlentities($_POST['numberCP']) : htmlentities($savedCPDetails['numberCP']);
			$html['CPCatID'] = isset($_POST['CPCatID']) ? htmlentities($_POST['CPCatID']) : htmlentities($savedCPDetails['CPCatID']);
			$html['CPNote'] = isset($_POST['CPNote']) ? htmlentities($_POST['CPNote']) : htmlentities($savedCPDetails['CPNote']);
			$html['CPDateStamp'] = htmlentities($savedCPDetails['CPDateStamp']);
			// Set correct date format
			$dateStamp = strtotime($html['CPDateStamp']);
			$html['displayDate'] = date('n/j/Y', $dateStamp);
			
			$html['staffMember'] = htmlentities($savedCPDetails['staffMember']);
		}
		
		$title = 'Update CP Record | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update CP Record';
		$btnLabel = 'Update';
	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['cpAdminSubmitted']) && $_POST['cpAdminSubmitted'] == 1) {
		// createCPRecord($characterID, $playerID, $numberCP, $CPCatID, $CPNote, $staffMember = 'System')
		
		if ($action == 'create') {
			if ($cpObj->addCP($_POST)) {
				session_write_close();
				header('Location: cp.php');
			}
		} else {
			if ($cpObj->updateCP($_POST, $_GET['CPTrackID'])) {
				session_write_close();
				header('Location: cp.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');

?>

<body id="cpAdminPage">
    
    <?php include('../includes/adminNav.php'); ?>
	
    <div id="content">
      
      <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="../images/helpArrowAdmin.png" alt="" style="display:none" />

		<?php cg_createHelp('numberCPHelp',
				'<p>Enter the number of CP to assign to a player or character.</p>'); ?>

		<?php cg_createHelp('CPTypeHelp',
				'<p>CP can be assigned to either a player or a character. </p>
                <ul>
                	<li><strong>Character CP</strong>: Character CP immediately becomes available for the character to spend. Character CP is the best type to use when the character already exists. </li>
                    <li><strong>Player CP</strong>: Player CP is useful if the player has not yet created a character in the system. Player CP will automatically be transferred to the next character the player creates. </li>
                </ul>'); ?>
	
		<?php cg_createHelp('CPCatIDHelp',
				'<p>Please choose a category for this CP assignment. You can filter CP assignments by category on the main CP page.</p>'); ?>
        
        <?php cg_createHelp('CPNoteHelp',
				'<p>You can enter an optional note for this CP assignment. </p>'); ?>
			  
	  </div><!--#help-->
	  
      <div id="main">
		
		<span class="reqFldIndicator">* Required Field</span>
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
	  		
        <h2><?php echo $pageHeader; ?></h2>
        
		<form name="cpAdmin" id="cpAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		        
        <?php cg_createRow('numberCP'); ?>
            <div class="cell">
                <label for="numberCP"><span class="required">*</span>Number</label>
                <input type="text" name="numberCP" id="numberCP" class="s" maxlength="3" value="<?php echo $html['numberCP']; ?>" />
				<p class="unit">CP</p>
				<?php cg_showError('numberCP'); ?>
				<br class="clear" />
            </div>
        </div>
        
        <?php cg_createRow('CPType'); ?>
            <div class="cell" id="charCPCell">
            	<label>* Assign to</label>
            	<input type="radio" name="CPType" id="CPType0" value="character" <?php if ($html['CPType'] == 'character') echo 'checked="checked"'; ?> />
                <label for="CPType0">Character</label>
                <br class="clear" />
                <select name="characterID" id="characterID">
                	<option value="0"></option>
					<?php
						// Open characters loop
						while ($charRow = $characters->fetch_assoc()) {
					?>
						<option value="<?php echo $charRow['characterID']; ?>" <?php if ($html['characterID'] == $charRow['characterID']) echo 'selected="selected"'; ?>><?php echo $charRow['charName'] . ' (' . $charRow['firstName'] . ' ' . $charRow['lastName'] . ')'; ?></option>
					<?php 
						} // end of characters loop
					?>
                </select>
                <?php cg_showError('characterID'); ?>
                <br class="clear" />
            </div><!--.cell1-->
            <div class="cell" id="playerCPCell">
            	<input type="radio" name="CPType" id="CPType1" value="player" <?php if ($html['CPType'] == 'player' && $html['characterID'] == '') echo 'checked="checked"'; ?> />
                <label for="CPType1">Player</label>
                <br class="clear" />
                <select name="playerID" id="playerID" <?php if ($html['characterID'] != '') echo 'disabled="yes" class="disabled"'; ?> >
                	<option value="0"></option>
					<?php
						// Open players loop
						while ($playerRow = $players->fetch_assoc()) {
					?>
						<option value="<?php echo $playerRow['playerID']; ?>" <?php if ($html['playerID'] == $playerRow['playerID']) echo 'selected="selected"'; ?>><?php echo $playerRow['firstName'] . ' ' . $playerRow['lastName'] . ' (' . $playerRow['email'] . ')'; ?></option>
					<?php 
						} // end of players loop
					?>
                </select>
                <?php cg_showError('playerID'); ?>
                <br class="clear" />
            </div>
            
        </div><!--#CPType-->
		
        <?php cg_createRow('CPCatID'); ?>
            <div class="cell">
                <label for="CPCatID"><span class="required">*</span>Category</label>
                <select name="CPCatID" id="CPCatID">
                	<?php
						// Open categories loop
						while ($catRow = $categories->fetch_assoc()) {
					?>
						<option value="<?php echo $catRow['CPCatID']; ?>" <?php if ($html['CPCatID'] == $catRow['CPCatID']) echo 'selected="selected"'; ?>><?php echo $catRow['CPCatName']; ?></option>
					<?php 
						} // end of categories loop
					?>
                </select>
				<?php cg_showError('CPCatID'); ?>
				<br class="clear" />
            </div>
        </div>
        
        <?php cg_createRow('CPNote'); ?>
            <div class="cell">
                <label for="CPNote">Note<br /><span class="optional">(optional)</span></label>
                <textarea name="CPNote" id="CPNote" class="xl-textarea" rows="3"><?php echo $html['CPNote']; ?></textarea>
                <?php cg_showError('CPNote'); ?>
                <br class="clear" />
            </div>
        </div>
        
        <?php
			if ($action == "update") {
		?>
		<?php cg_createRow('displayDate'); ?>
            <div class="cell">
                <p class="lbl">Date</p>
                <p class="data"><?php echo $html['displayDate']; ?></p>
                <br class="clear" />
            </div>
        </div>
        
        <?php cg_createRow('staffMember'); ?>
            <div class="cell">
                <p class="lbl">Assigned By</p>
                <p class="data"><?php echo $html['staffMember']; ?></p>
                <br class="clear" />
            </div>
        </div>
        
        <?php
		  }
		?>
		
        
        
        <div class="btnArea">
            <input type="hidden" id="cpAdminSubmitted" name="cpAdminSubmitted" value="1">
			<input type="submit" id="cpAdminSave" name="cpAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
			<input type="button" id="cpAdminCancel" name="cpAdminCancel" value="Cancel" class="btn-secondary" onClick="window.location.href='cp.php'" />
            <br class="clear" />
        </div>
		
		</form>
        
      </div> <!--end of main div-->
      <br class="clear" />
    </div><!--end of content div-->
	
	<?php
		cg_clearUIMessage();
    
		include('../includes/footer.php'); 
	?>
