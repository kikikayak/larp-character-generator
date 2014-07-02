<?php

	/**************************************************************
	NAME: 	traitAdmin.php
	NOTES: 	This page allows staff members to add or edit traits. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'traits';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['traitID']) && ctype_digit($_GET['traitID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/ 
	
	$html = array(); // Initialize array to hold data for display
	
	$traitObj = new charTrait(); // Instantiate trait object
	
	// Get data to populate dropdown lists
	$character = new Character();
	$characters = $character->getAllPCs();
	
	$player = new Player();
	$players = $player->getAllStaff();
	
	if ($action == 'create') { 
		$html['traitName'] = isset($_POST['traitName']) ? htmlentities($_POST['traitName']) : '';
		$html['traitStaff'] = isset($_POST['traitStaff']) ? htmlentities($_POST['traitStaff']) : '';
		$html['traitAccess'] = isset($_POST['traitAccess']) ? htmlentities($_POST['traitAccess']) : '';
		$html['traitDescriptionStaff'] = isset($_POST['traitDescriptionStaff']) ? htmlentities($_POST['traitDescriptionStaff']) : '';
		$html['traitDescriptionPublic'] = isset($_POST['traitDescriptionPublic']) ? htmlentities($_POST['traitDescriptionPublic']) : '';
		
		$title = 'Add a Trait | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a Trait';
		$btnLabel = 'Add';
		
	} else if ($action == 'update' && isset($_GET['traitID'])) {
		$traitDetails = $traitObj->getTrait($_GET['traitID']);
		while ($savedTraitDetails = $traitDetails->fetch_assoc()) {
			$html['traitName'] = isset($_POST['traitName']) ? htmlentities($_POST['traitName']) : htmlentities($savedTraitDetails['traitName']);
			$html['traitStaff'] = isset($_POST['traitStaff']) ? htmlentities($_POST['traitStaff']) : htmlentities($savedTraitDetails['traitStaff']);
			$html['traitAccess'] = isset($_POST['traitAccess']) ? htmlentities($_POST['traitAccess']) : htmlentities($savedTraitDetails['traitAccess']);
			$html['traitDescriptionStaff'] = isset($_POST['traitDescriptionStaff']) ? htmlentities($_POST['traitDescriptionStaff']) : htmlentities($savedTraitDetails['traitDescriptionStaff']);
			$html['traitDescriptionPublic'] = isset($_POST['traitDescriptionPublic']) ? htmlentities($_POST['traitDescriptionPublic']) : htmlentities($savedTraitDetails['traitDescriptionPublic']);
						
			// Set up array to pre-select characters
			$trait['characterID'] = array(); // Initialize to empty array
			$charResult = $traitObj->getTraitCharacters($_GET['traitID']);
			while ($traitCharacters = $charResult->fetch_assoc()) {
				// Loop through retrieved characters and add to array
				$trait['characterID'][] = $traitCharacters['characterID'];
			}
		}
		
		$title = 'Update Trait | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update Trait';
		$btnLabel = 'Update';

	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['traitAdminSubmitted']) && $_POST['traitAdminSubmitted'] == 1) {
		$trait = array(); // Initialize as blank array
		$trait['traitName'] = $_POST['traitName'];
		$trait['traitStaff'] = $_POST['traitStaff'];
		$trait['traitAccess'] = $_POST['traitAccess'];
		$trait['traitDescriptionStaff'] = $_POST['traitDescriptionStaff'];
		$trait['traitDescriptionPublic'] = $_POST['traitDescriptionPublic'];
		
		// Characters who have access
		$trait['characterID'] = array(); // Initialize to empty array
		if (isset($_POST['characterID'])) {
			$trait['characterID'] = $_POST['characterID'];
		}
	
		if ($action == 'create') {
			if ($traitObj->addTrait($trait)) {
				session_write_close();
				header('Location: traits.php');
			}
		} else {
			if ($traitObj->updateTrait($trait, $_GET['traitID'])) {
				session_write_close();
				header('Location: traits.php');	
			}
		}
	}

	include('../includes/header_admin.php');

?>

<body id="traitAdminPage">
    
    <?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content">
      
      <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="../images/helpArrowAdmin.png" alt="" style="display:none" />

		<?php cg_createHelp('traitNameHelp','<p>Enter the trait name as you want it to appear on the character card and cheat sheet.</p>
                <p>Only players whose characters have the trait will be able to see it. </p>'); ?>
		
		<?php cg_createHelp('traitStaffHelp','<p>Select the name of the staff person who created the trait or whose plotline includes the trait. </p>
                <p>If the trait is game-wide, you may wish to use the name of the game director or head of staff. </p>'); ?>

        <?php cg_createHelp('traitAccessHelp','<p>There are two types of traits: </p>
                <ul>
                	<li><strong>Appears on character card</strong>: The player will be able to see the trait on the character card or cheat sheet. Players whose characters do not have the trait will not be able to see it in the system. </li>
                    <li><strong>Only staff can see</strong>: Staff members will be able to see the trait in the admin section, but players will not see the trait on their character card. Use this setting for hidden traits or situations when a player is not yet aware of the trait. </li>
                </ul>'); ?>
        
        <?php cg_createHelp('traitDescriptionStaffHelp','<p>This is an internal (staff-only) description. Players will not be able to see this description.</p>'); ?>
        
        <?php cg_createHelp('traitDescriptionPublicHelp','<p>This is a player-oriented description of the trait. A player whose character has the trait will be able to see this description. Players whose characters do not have the trait will not see the trait name or description. </p>'); ?>
		
	  </div><!--#help-->
	  
      <div id="main">
        <span class="reqFldIndicator">* Required Field</span>
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<h2><?php echo $pageHeader; ?></h2>
        
        <form name="traitAdmin" id="traitAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        
          <?php cg_createRow('traitName'); ?>
              <div class="cell">
                  <label for="traitName">Trait Name</label>
                  <input type="text" name="traitName" id="traitName" class="xl" value="<?php echo $html['traitName']; ?>" />
				  <?php cg_showError('traitName'); ?>
                  <br class="clear" />
              </div>
          </div>
          
          <?php cg_createRow('traitStaff'); ?>
              <div class="cell">
                  <label for="traitStaff">Staff Member</label>
                  <select name="traitStaff" id="traitStaff">
					<?php
					  // Open characters loop
					  while ($staffRow = $players->fetch_assoc()) {
                    ?>
                      <option value="<?php echo $staffRow['firstName'] . ' ' . $staffRow['lastName']; ?>" <?php if ($html['traitStaff'] == $staffRow['firstName'] . ' ' . $staffRow['lastName']) echo 'selected="selected"'; ?>><?php echo $staffRow['firstName'] . ' ' . $staffRow['lastName'] . ' (' . $staffRow['email'] . ')'; ?></option>
					<?php 
					  }
					?>
                  </select>
                  <?php cg_showError('traitStaff'); ?>
                  <br class="clear" />
              </div>
          </div>
                  
          <?php cg_createRow('traitAccess'); ?>
              <div class="cell">
                  <label for="traitAccess">Visibility</label>
                  <select name="traitAccess" id="traitAccess">
                      <option value="Public" <?php if ($html['traitAccess'] == 'Public') echo 'selected="selected"'; ?>>Appears on character card</option>
                      <option value="Hidden" <?php if ($html['traitAccess'] == 'Hidden') echo 'selected="selected"'; ?>>Only staff can see</option>
                  </select>
                  <?php cg_showError('traitAccess'); ?>
                  <br class="clear" />
              </div>
          </div>
          
          <?php cg_createRow('traitDescriptionStaff'); ?>
              <div class="cell">
                  <label for="traitDescriptionStaff">Staff-only Description</label>
                  <textarea name="traitDescriptionStaff" rows="5" class="xl-textarea" id="traitDescriptionStaff"><?php echo $html['traitDescriptionStaff']; ?></textarea>
                  <?php cg_showError('traitDescriptionStaff'); ?>
                  <br class="clear" />
              </div>
          </div>
          
          <?php cg_createRow('traitDescriptionPublic'); ?>
              <div class="cell">
                  <label for="traitDescriptionPublic">Public description</label>
                  <textarea name="traitDescriptionPublic" rows="5" class="xl-textarea" id="traitDescriptionPublic"><?php echo $html['traitDescriptionPublic']; ?></textarea>
                  <?php cg_showError('traitDescriptionPublic'); ?>
                  <br class="clear" />
              </div>
          </div>
          
		  <?php cg_createRow('PCAccess'); ?>
          <div class="cell">
              <label for="PCAccess">Characters who have this trait<br /><span class="optional">(optional)</span></label>
          
              <div id="charSelectionBox">
                  <div class="toolbar">
                      <a href="#" id="checkAllAccess">check all</a>
                      <a href="#" id="uncheckAllAccess">uncheck all</a>
                      <br class="clear" />
                  </div>
                  
                  <div class="container">
                  
                  <?php
  
                      // Open characters loop
                      while ($charRow = $characters->fetch_assoc()) {
  
                  ?>
                  <div class="chkRow">
                      <input type="checkbox" name="characterID[]" id="<?php echo 'characterID_' . $charRow['characterID']; ?>" value="<?php echo $charRow['characterID']; ?>" <?php if (isset($trait['characterID']) && in_array($charRow['characterID'], $trait['characterID'])) echo 'checked="checked"'; ?> />
                      <label for="<?php echo 'characterID_' . $charRow['characterID']; ?>">
                          <?php echo $charRow['charName'] . ' (' . $charRow['firstName'] . ' ' . $charRow['lastName'] . ')'; ?>
                      </label>
                      <br class="clear" />
                  </div>
                  
                  <?php
                      } // end characters loop
                  ?>
                  
                  </div><!--end of container-->
              
              </div>
              <?php cg_showError('PCAccess'); ?>
              <br class="clear" />
            </div>
          </div>
          
          <div class="btnArea">
          		<input type="hidden" id="traitAdminSubmitted" name="traitAdminSubmitted" value="1">
				<input type="submit" id="traitAdminSave" name="traitAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
				<input type="button" id="traitAdminCancel" name="traitAdminCancel" value="Cancel" class="btn-secondary" onClick="window.location.href='traits.php'" />          
              <br class="clear" />
          </div>
        
        </form>
        
      </div><!--#main-->
      <br class="clear" />
    </div><!--#content-->
    
<?php include('../includes/footer.php'); ?>
