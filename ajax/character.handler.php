<?php

$pageAccessLevel = 'Staff';

// Load required libraries
require_once('../includes/config.php');
require_once(LOCATION . 'includes/library.php');
require(LOCATION . 'class/classloader.php');
require_once(LOCATION . 'includes/authenticate.php');

// Make sure the user's browser doesn't cache the result
header('Expires: Wed, 23 Dec 1980 00:30:00 GMT'); // time in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// read the action parameter
$ajaxAction = $_GET['ajaxAction'];

/***************************************************************
LOAD CHARACTER DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCharDeleteDialog' && isset($_POST['characterID'])) {
	$character = new Character();
	$charBasics = $character->getCharBasics($_POST['characterID']);
	
	while ($row = $charBasics->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following character:</p>
      <p class="charName"><?php echo $row['charName']; ?></p>
      <p>Belonging to</p>
      <p class="playerName"><?php echo $row['firstName'] . ' ' . $row['lastName']; ?>?</p>
      <p>You will be able to restore the character from the trash if necessary.</p>
      <input type="hidden" name="deleteCharID" id="deleteCharID" value="<?php echo $_POST['characterID']; ?>" />
	
<?php
	
	} // end of charDetails loop
} // end deleteCharacter

/***************************************************************
DELETE CHARACTER
***************************************************************/
if ($ajaxAction == 'deleteCharacter' && isset($_POST['characterID'])) {
	$character = new Character();
	
	if ($character->deleteCharacter($_POST['characterID'])) {
		$charBasics = $character->getCharBasics($_POST['characterID']);
		
		while ($row = $charBasics->fetch_assoc()) {
		  $html = array();
		  $html['charName'] = htmlentities($row['charName']);
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Character Deleted Successfully',
													'<p>The character "' . $html['charName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of charDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												  'Failed to Delete Character',
												  '<p>The character could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteCharacter

/***************************************************************
LOAD CHARACTER PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCharPurgeDialog' && isset($_POST['characterID'])) {
	$character = new Character();
	$charBasics = $character->getCharBasics($_POST['characterID']);
	
	while ($row = $charBasics->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following character:</p>
      <p class="charName"><?php echo $row['charName']; ?></p>
      <p>Belonging to</p>
      <p class="playerName"><?php echo $row['firstName'] . ' ' . $row['lastName']; ?>?</p>
      <p>All data on this character (including headers, skills, spells, traits, and CP records) will be permanently deleted.</p> 
      <p>You will not be able to undo this action.</p>
      <input type="hidden" name="purgeCharID" id="purgeCharID" value="<?php echo $_POST['characterID']; ?>" />
	
<?php
	
	} // end of charDetails loop
} // end loadCharPurgeDialog

/***************************************************************
PURGE CHARACTER
***************************************************************/
if ($ajaxAction == 'purgeCharacter' && isset($_POST['characterID'])) {
	$character = new Character();
	
	if ($character->purgeCharacter($_POST['characterID'])) {
		$charBasics = $character->getCharBasics($_POST['characterID']);
		
		while ($row = $charBasics->fetch_assoc()) {
		  $html = array();
		  $html['charName'] = htmlentities($row['charName']);
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Character Purged Successfully',
													'<p>The character "' . $html['charName'] . '" has been permanently deleted.</p>');
												
		} // end of charDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												  'Failed to Purge Character',
												  '<p>The character could not be permanently deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeCharacter

/***************************************************************
UNDELETE CHARACTER
***************************************************************/
if ($ajaxAction == 'undeleteCharacter' && isset($_POST['characterID'])) {
	$character = new Character();
	
	if ($character->undeleteCharacter($_POST['characterID'])) {
		$charBasics = $character->getCharBasics($_POST['characterID']);
		
		while ($row = $charBasics->fetch_assoc()) {
		  $html = array();
		  $html['charName'] = htmlentities($row['charName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Character Undeleted Successfully',
													'<p>The character "' . $html['charName'] . '" has been restored from the trash. You can view it on the <a href="characters.php" title="Go to characters page">Characters</a> page.</p>');
												
		} // end of charDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												  'Failed to Undelete Character',
												  '<p>The character could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteCharacter

/***************************************************************
CHANGE CHARACTERS TAB
***************************************************************/
if ($ajaxAction == 'changeCharactersTab' && isset($_POST['tabName'])) {
	// Possible tab values are "showAll," "showPCs," "showNPCs"
	
	$_SESSION['charTabName'] = $_POST['tabName'];
	
	$charObj = new Character();
	
	if ($_POST['tabName'] == 'showPCs') {
		$charResults = $charObj->getAllPCs();	
	} else if ($_POST['tabName'] == 'showNPCs') {
		$charResults = $charObj->getAllNPCs();
	} else {
		$charResults = $charObj->getAllCharacters();
	}
	
	$rowIndex = 1;
	while ($character = $charResults->fetch_assoc()) { // Loop through retrieved countries
		// $rowClass = '';
		if ($rowIndex % 2 == 0) {
			$rowClass = 'even';
		} else {
			$rowClass = 'odd';
		}
		$charTotalCP = $charObj->getTotalCharCP($character['characterID']);
		$charFreeCP = $charObj->getCharFreeCP($character['characterID']);
        
        ?>
      <tr class="<?php echo $rowClass; ?>"> 
        <td class="chkboxCol"><input type="checkbox" id="<?php echo 'characterID_' . $character['characterID']; ?>" name="characterID[]" value="<?php echo $character['characterID']; ?>" /></td>
        <td class="charNameCol"><a href="charDetails.php?characterID=<?php echo $character['characterID']; ?>"><?php echo $character['charName']; ?></a></td>
        <td class="playerNameCol"><?php echo $character['firstName'] . ' ' . $character['lastName']; ?></td>
        <td class="typeCol"><?php echo $character['charType']; ?></td>
        <td class="totalCPCol"><?php echo $charTotalCP; ?></td>
        <td class="freeCPCol"><?php echo $charFreeCP; ?></td>
        <td class="actionsCol">
            <div class="actionsContainer">
              <a href="#" title="Character actions" class="actionsLink">Actions</a>
              <div class="menu" style="display:none">
                  <ul>
                      <li><a href="charAdmin.php?characterID=<?php echo $character['characterID']; ?>" title="">Edit</a></li>
                      <li><a href="#" title="Delete this character" class="deleteLink">Delete</a></li>
                  </ul>
              </div>
            </div><!--.actionsContainer-->
        </td>
      </tr>
      <?php 
           $rowIndex++;
        } // end loop through characters
		
		if ($charResults->num_rows == 0) {
		  echo '<tr class="odd"><td colspan="7"><p class="noResults">There are no characters of this type.</p></td></tr>';  
		}

} // end of changeCharactersTab

/***************************************************************
GET FILTERED CHARACTERS
***************************************************************/
if ($ajaxAction == 'getFilteredCharacters' && isset($_POST)) {
	
	$charObj = new Character();
	
	if ($charResults = $charObj->getFilteredCharacters($_POST)) {
	
		$_SESSION['charFilters'] = $_POST; // Put filter criteria in session for later use
		$_SESSION['selectedCharTab'] = $_POST['selectedCharTab'];
		
		$rowIndex = 1;
		while ($character = $charResults->fetch_assoc()) { // Loop through retrieved characters
			if ($rowIndex % 2 == 0) {
				$rowClass = 'even';
			} else {
				$rowClass = 'odd';
			}
			$charTotalCP = $charObj->getTotalCharCP($character['characterID']);
			$charFreeCP = $charObj->getCharFreeCP($character['characterID']);
	        
	?>
	      <tr class="<?php echo $rowClass; ?>"> 
	        <td class="chkboxCol"><input type="checkbox" id="<?php echo 'characterID_' . $character['characterID']; ?>" name="characterID[]" value="<?php echo $character['characterID']; ?>" /></td>
	        <td class="charNameCol"><a href="charDetails.php?characterID=<?php echo $character['characterID']; ?>"><?php echo $character['charName']; ?></a></td>
	        <td class="playerNameCol"><?php echo $character['firstName'] . ' ' . $character['lastName']; ?></td>
	        <td class="typeCol"><?php echo $character['charType']; ?></td>
	        <td class="totalCPCol"><?php echo $charTotalCP; ?></td>
	        <td class="freeCPCol"><?php echo $charFreeCP; ?></td>
	        <td class="actionsCol">
	            <div class="actionsContainer">
	              <a href="#" title="Character actions" class="actionsLink">Actions</a>
	              <div class="menu" style="display:none">
	                  <ul>
	                      <li><a href="charAdmin.php?characterID=<?php echo $character['characterID']; ?>" title="">Edit</a></li>
	                      <li><a href="#" title="Delete this character" class="deleteLink">Delete</a></li>
	                  </ul>
	              </div>
	            </div><!--.actionsContainer-->
	        </td>
	      </tr>
	      <?php 
	           $rowIndex++;
	        } // end loop through characters
			
			if ($charResults->num_rows == 0) {
			  echo '<tr class="odd"><td colspan="7"><p class="noResults">There are no characters that match your criteria.</p></td></tr>';  
			}
		} else {
			// Query failed
			echo '<tr class="odd"><td colspan="7"><p class="noResults">Unable to retrieve characters. Please contact your administrator if you continue to experience this problem. </p></td></tr>'; 
		}
	
} // end of getFilteredCharacters

/***************************************************************
SET CHARACTER FILTER EXPANDED
***************************************************************/
if ($ajaxAction == 'setCharFilterExpanded' && isset($_POST['charFilterExpanded'])) {
	$_SESSION['charFilterExpanded'] = $_POST['charFilterExpanded'];
}

/***************************************************************
GET CHARACTER SUGGESTIONS
This method can be used to populate character autosuggest fields. 
***************************************************************/
if ($ajaxAction == 'getCharacterSuggestions' && isset($_GET['term'])) {
	$characters = array();
	
	$charObj = new Character();
	$charResult = $charObj->getCharSuggestions($_GET['term']);
	
	while ($charRow = $charResult->fetch_assoc()) {
		$arr = array('label' => $charRow['charName'] . ' (' . $charRow['firstName'] . ' ' . $charRow['lastName'] . ')', 
					'value' => $charRow['charName']);
		$characters[] = $arr;	
	}

	echo json_encode($characters);
} // end of getCharacterSuggestions

?>
