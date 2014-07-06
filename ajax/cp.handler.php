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
LOAD ADD CP DIALOG
***************************************************************/
if ($ajaxAction == 'loadCPAddDialog') {
	$cpObj = new CP();
	
	// Get data to populate dropdown lists
	$character = new Character();
	$characters = $character->getAllCharacters();
	
	$player = new Player();
	$players = $player->getAllPlayers();
	
	$categories = $cpObj->getCPCategories();
	
?>

<div id="cpAddMsg">
	<!--To be populated via AJAX-->
</div>

<p>You can add up to 5 CP assignments for a single character or player below. </p>

<form name="cpAddForm" id="cpAddForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
  
  <!--Choose character or player CP. This setting applies to all the CP additions. -->
  <?php cg_createRow('CPType'); ?>
      <div class="cell" id="charCPCell">
          <label>* Assign to</label>
          <input type="radio" name="CPType" id="CPType0" value="character" checked="checked" />
          <label for="CPType0">Character</label>
          <br class="clear" />
          <select name="characterID" id="characterID" data-placeholder="Select a character">
              <option value="0"></option>
              <?php
                  // Open characters loop
                  while ($charRow = $characters->fetch_assoc()) {
              ?>
                  <option value="<?php echo $charRow['characterID']; ?>"><?php echo $charRow['charName'] . ' (' . $charRow['firstName'] . ' ' . $charRow['lastName'] . ')'; ?></option>
              <?php 
                  } // end of characters loop
              ?>
          </select>
          <?php cg_showError('characterID'); ?>
          <br class="clear" />
      </div><!--.cell1-->
      <div class="cell" id="playerCPCell">
          <input type="radio" name="CPType" id="CPType1" value="player" />
          <label for="CPType1">Player</label>
          <br class="clear" />
          <select name="playerID" id="playerID" disabled="disabled" class="disabled" data-placeholder="Select a player">
              <option value="0"></option>
              <?php
                  // Open players loop
                  while ($playerRow = $players->fetch_assoc()) {
              ?>
                  <option value="<?php echo $playerRow['playerID']; ?>"><?php echo $playerRow['firstName'] . ' ' . $playerRow['lastName'] . ' (' . $playerRow['email'] . ')'; ?></option>
              <?php 
                  } // end of players loop
              ?>
          </select>
          <?php cg_showError('playerID'); ?>
          <br class="clear" />
      </div>
      
  </div><!--#CPType-->
  
  <table id="cpAddTable" cellpadding="5" cellspacing="0">
  	<thead>
    	<tr>
        	<th class="numCol">* Num</th>
            <th class="catCol">* Category</th>
            <th class="noteCol">Note</th>
        </tr>
    </thead>
    <tbody>
    	<tr id="cpAddRow1">
			<td class="numCol">
            	<input type="text" name="numberCP1" id="numberCP1" class="s3 numberCPFld" maxlength="5" value="" />
                <span class="fldModifier">CP</span>
            </td>
            <td class="catCol">
            	<select name="CPCatID1" id="CPCatID1" class="cpCatFld" data-placeholder="Select a category">
					<option value=""></option>
					<?php
                        // Open categories loop
                        while ($catRow = $categories->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $catRow['CPCatID']; ?>"><?php echo $catRow['CPCatName']; ?></option>
                    <?php 
                        } // end of categories loop
                    ?>
                </select>
            </td> 
            <td class="noteCol">
            	<input type="text" name="CPNote1" id="CPNote1" class="l cpNoteFld" maxlength="50" value="" />
            </td>
        </tr>
        <tr id="cpAddRow2">
			<td class="numCol">
            	<input type="text" name="numberCP2" id="numberCP2" class="s3 numberCPFld" maxlength="5" value="" />
                <span class="fldModifier">CP</span>
            </td>
            <td class="catCol">
            	<select name="CPCatID2" id="CPCatID2" class="cpCatFld" data-placeholder="Select a category">
					<option value=""></option>
					<?php
                        // Open categories loop
                        $categories->data_seek(0);
						while ($catRow2 = $categories->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $catRow2['CPCatID']; ?>"><?php echo $catRow2['CPCatName']; ?></option>
                    <?php 
                        } // end of categories loop
                    ?>
                </select>
            </td> 
            <td class="noteCol">
            	<input type="text" name="CPNote2" id="CPNote2" class="l cpNoteFld" maxlength="50" value="" />
            </td>
        </tr>
        <tr id="cpAddRow3">
			<td class="numCol">
            	<input type="text" name="numberCP3" id="numberCP3" class="s3 numberCPFld" maxlength="5" value="" />
                <span class="fldModifier">CP</span>
            </td>
            <td class="catCol">
            	<select name="CPCatID3" id="CPCatID3" class="cpCatFld" data-placeholder="Select a category">
					<option value=""></option>
					<?php
                        // Open categories loop
                        $categories->data_seek(0);
						while ($catRow3 = $categories->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $catRow3['CPCatID']; ?>"><?php echo $catRow3['CPCatName']; ?></option>
                    <?php 
                        } // end of categories loop
                    ?>
                </select>
            </td> 
            <td class="noteCol">
            	<input type="text" name="CPNote3" id="CPNote3" class="l cpNoteFld" maxlength="50" value="" />
            </td>
        </tr><!--#CPAddRow3-->
        <tr id="cpAddRow4">
			<td class="numCol">
            	<input type="text" name="numberCP4" id="numberCP4" class="s3 numberCPFld" maxlength="5" value="" />
                <span class="fldModifier">CP</span>
            </td>
            <td class="catCol">
            	<select name="CPCatID4" id="CPCatID4" class="cpCatFld" data-placeholder="Select a category">
					<option value=""></option>
					<?php
                        // Open categories loop
                        $categories->data_seek(0);
						while ($catRow4 = $categories->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $catRow4['CPCatID']; ?>"><?php echo $catRow4['CPCatName']; ?></option>
                    <?php 
                        } // end of categories loop
                    ?>
                </select>
            </td> 
            <td class="noteCol">
            	<input type="text" name="CPNote4" id="CPNote4" class="l cpNoteFld" maxlength="50" value="" />
            </td>
        </tr><!--#cpAddRow4-->
        <tr id="cpAddRow5">
			<td class="numCol">
            	<input type="text" name="numberCP5" id="numberCP5" class="s3 numberCPFld" maxlength="5" value="" />
                <span class="fldModifier">CP</span>
            </td>
            <td class="catCol">
            	<select name="CPCatID5" id="CPCatID5" class="cpCatFld" data-placeholder="Select a category">
					<option value=""></option>
					<?php
                        // Open categories loop
                        $categories->data_seek(0);
						while ($catRow5 = $categories->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $catRow5['CPCatID']; ?>"><?php echo $catRow5['CPCatName']; ?></option>
                    <?php 
                        } // end of categories loop
                    ?>
                </select>
            </td> 
            <td class="noteCol">
            	<input type="text" name="CPNote5" id="CPNote5" class="l cpNoteFld" maxlength="50" value="" />
            </td>
        </tr><!--#cpAddRow5-->
        <tr class="totals">
        	<td colspan="4"><span id="addCPTotal">0</span> CP Total</td>
        </tr>
    </tbody>
  
  </table>
  
</form>


<?php		

} // end of loadCPAddDialog

/***************************************************************
ADD CP
***************************************************************/
if ($ajaxAction == 'cpAdd' && (isset($_POST['characterID']) || isset($_POST['playerID']))) {
	cg_clearUIMessage(); // Clear any existing messages
	
	$cp = new CP();
	
	if ($cp->addMultipleCP($_POST)) {
		// If successful, dialog will close and success message display
		cg_showUIMessage();
		cg_clearUIMessage();
	} else {
		// If unsuccessful, return error
		// JS will handle outputting the error in the right place
		// echo 'error';
		/* Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Add CP',
												'<p>The CP could not be added. Please try again. </p>');
		*/	
	}
} // end of cpAdd

/***************************************************************
LOAD CP DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCPDeleteDialog' && isset($_POST['CPTrackID'])) {
	$allowCPDelete = 'no';
	$cp = new CP();
	$cpDetails = $cp->getCPDetails($_POST['CPTrackID']);
	
	while ($cpRow = $cpDetails->fetch_assoc()) {
		
		if ($cpRow['CPType'] == 'character') {
			// Figure out if this CP has been used already
			$character = new Character();
			$totalCharCP = $character->getTotalCharCP($cpRow['characterID']);
			$freeCP = $character->getCharFreeCP($cpRow['characterID']);
			$usedCharCP = $totalCharCP - $freeCP;
			
			/* DEBUG
			  echo 'Total CP: ' . $totalCharCP . '<br />';
			  echo 'Used CP: ' . $usedCharCP . '<br />';
			*/
			
			$totalCPAfterDelete = $totalCharCP - $cpRow['numberCP'];
			if ($usedCharCP > $totalCPAfterDelete) {
			 
            } else {
				$allowCPDelete = 'yes';	
			}
		} else {
			$allowCPDelete = 'yes';
		}
		
		if ($allowCPDelete == 'no') {
		?>
			<p>This CP record cannot be deleted because the CP has already been spent on the character <strong><?php echo $cpRow['charName']; ?></strong>.</p>
			<p>Please reduce the amount of CP used for the character by removing items and then try again. </p>	
		
		<?php
		   
		  } else {
		
		$dateString = strtotime($cpRow['CPDateStamp']);
		$displayDate = date('n/j/Y', $dateString);
	
?>
      <p>Are you sure you want to delete the following CP record:</p>
      <table id="cpConfirmList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="dateCol">Date</th>
                <th class="charCol">Character</th>
                <th class="playerCol">Player</th>
                <th class="numCol">Num</th>
                <th class="catCol">Category</th>
                <th class="noteCol">Note</th>
            </tr>
        </thead>
        <tbody>
            <tr> 
              <td class="dateCol"><?php echo $displayDate; ?></td>
              <td class="charCol"><?php echo $cpRow['charName']; ?></td>
              <td class="playerCol"><?php echo $cpRow['firstName'] . ' ' . $cpRow['lastName']; ?></td>
              <td class="numCol"><?php echo $cpRow['numberCP']; ?></td>
              <td class="staffCol"><?php echo $cpRow['CPCatName']; ?></td>
              <td class="catCol"><?php echo $cpRow['CPNote']; ?></td>
            </tr>
        </tbody>
      </table>
      
      <p>You will be able to restore the CP from the trash if necessary.</p>
      <input type="hidden" name="deleteCPTrackID" id="deleteCPTrackID" value="<?php echo $_POST['CPTrackID']; ?>" />
      
      <?php
		  } // end of allow to delete condition
	  ?>
      <input type="hidden" name="allowCPDelete" id="allowCPDelete" value="<?php echo $allowCPDelete; ?>" />
	
<?php
	
	} // end of cpDetails loop
} // end loadCPDeleteDialog

/***************************************************************
DELETE CP RECORD
***************************************************************/
if ($ajaxAction == 'deleteCP' && isset($_POST['CPTrackID'])) {
	$cp = new CP();
	
	if ($cp->deleteCP($_POST['CPTrackID'])) {
		$cpDetails = $cp->getCPDetails($_POST['CPTrackID']);
		
		while ($row = $cpDetails->fetch_assoc()) {
		  $html = array();
		  $dateStampDisplay = strtotime($row['CPDateStamp']);
		  $dateStampDisplay = date('n/j/Y', $dateStampDisplay);
		  
		  if ($row['CPType'] = 'player') {
			$html['firstName'] = htmlentities($row['firstName']);
			$html['lastName'] = htmlentities($row['lastName']);
			// If successful, set success message
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													  'CP Record Deleted Successfully',
													  '<p>The ' . $row['numberCP'] . ' CP for the player ' . $html['firstName'] . ' ' . $html['lastName'] . ' added on ' . $dateStampDisplay . ' has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
		  } else {
			$html['charName'] = htmlentities($row['charName']);

			// If successful, set success message
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													  'CP Record Deleted Successfully',
													  '<p>The ' . $row['numberCP'] . ' CP for the character ' . $html['charName'] . ' added on ' . $dateStampDisplay . ' has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');  
		  }
												
		} // end of cpDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												  'Failed to Delete CP Record',
												  '<p>The CP record could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteCP

/***************************************************************
LOAD CP PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCPPurgeDialog' && isset($_POST['CPTrackID'])) {
	$cp = new CP();
	$cpDetails = $cp->getCPDetails($_POST['CPTrackID']);
	
	while ($cpRow = $cpDetails->fetch_assoc()) {
	  $dateString = strtotime($cpRow['CPDateStamp']);
	  $displayDate = date('n/j/Y', $dateString);
?>
      <p>Are you sure you want to permanently delete the following CP record:</p>
	
      <table id="cpPurgeList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="dateCol">Date</th>
                <th class="charCol">Character</th>
                <th class="playerCol">Player</th>
                <th class="numCol">Num</th>
                <th class="catCol">Category</th>
                <th class="noteCol">Note</th>
            </tr>
        </thead>
        <tbody>
            <tr> 
              <td class="dateCol"><?php echo $displayDate; ?></td>
              <td class="charCol"><?php echo $cpRow['charName']; ?></td>
              <td class="playerCol"><?php echo $cpRow['firstName'] . ' ' . $cpRow['lastName']; ?></td>
              <td class="numCol"><?php echo $cpRow['numberCP']; ?></td>
              <td class="staffCol"><?php echo $cpRow['CPCatName']; ?></td>
              <td class="catCol"><?php echo $cpRow['CPNote']; ?></td>
            </tr>
        </tbody>
      </table>
      <p>You will not be able to undo this action.</p>
      <input type="hidden" name="purgeCPTrackID" id="purgeCPTrackID" value="<?php echo $_POST['CPTrackID']; ?>" />
	
<?php
	
	} // end of cpDetails loop
} // end loadCPPurgeDialog

/***************************************************************
PURGE CP
***************************************************************/
if ($ajaxAction == 'purgeCP' && isset($_POST['CPTrackID'])) {
	$cp = new CP();
	
	if ($cp->purgeCP($_POST['CPTrackID'])) {
		
	  // If successful, set success message
	  $_SESSION['UIMessage'] = new UIMessage(	'success', 
												'CP Purged Successfully',
												'<p>The CP record has been permanently deleted.</p>');
												
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												  'Failed to Purge CP Record',
												  '<p>The CP record could not be permanently deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeCP

/***************************************************************
UNDELETE CP
***************************************************************/
if ($ajaxAction == 'undeleteCP' && isset($_POST['CPTrackID'])) {
	$cp = new CP();
	
	if ($cp->undeleteCP($_POST['CPTrackID'])) {
		$cpDetails = $cp->getCPDetails($_POST['CPTrackID']);
		
		while ($row = $cpDetails->fetch_assoc()) {
		  $html = array();
		  $dateStampDisplay = strtotime($row['CPDateStamp']);
		  $dateStampDisplay = date('n/j/Y', $dateStampDisplay);
		  
		  if ($row['CPType'] = 'player') {
			$html['firstName'] = htmlentities($row['firstName']);
			$html['lastName'] = htmlentities($row['lastName']);
			// If successful, set success message
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													  'CP Record Undeleted Successfully',
													  '<p>The ' . $row['numberCP'] . ' CP for the player ' . $html['firstName'] . ' ' . $html['lastName'] . ' added on ' . $dateStampDisplay . ' has been restored from the trash. You can view it on the <a href="cp.php">CP page</a>.</p>');
		  } else {
			$html['charName'] = htmlentities($row['charName']);

			// If successful, set success message
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													  'CP Record Undeleted Successfully',
													  '<p>The ' . $row['numberCP'] . ' CP for the character ' . $html['charName'] . ' added on ' . $dateStampDisplay . ' has been restored from the trash. It will again be included in the character\'s available CP. You can view it on the <a href="cp.php">CP page</a>.</p>');  
		  }
												
		} // end of cpDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												  'Failed to Undelete CP',
												  '<p>The CP record could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteCP

/***************************************************************
CHANGE CP TAB
***************************************************************/
if ($ajaxAction == 'changeCPTab' && isset($_POST['tabName'])) {
	// Possible tab values are "showAll," "showCharacter," "showPlayer"
	
	$_SESSION['selectedCPTab'] = $_POST['tabName'];
	
	$cpObj = new CP();
	
	if ($_POST['tabName'] == 'showCharacter') {
		$cp = $cpObj->getCharCP();	
	} else if ($_POST['tabName'] == 'showPlayer') {
		$cp = $cpObj->getPlayerCP();
	} else {
		$cp = $cpObj->getRecentCP();	
	}
	
	$rowIndex = 0;
	while ($cpRow = $cp->fetch_assoc()) { // Loop through retrieved CP records
		if ($rowIndex % 2 == 0) {
			$rowClass = 'even';
		} else {
			$rowClass = 'odd';
		}
		
		// Format date
		$dateString = strtotime($cpRow['CPDateStamp']);
		$displayDate = date('n/j/Y', $dateString);

?>
        <tr class="<?php echo $rowClass; ?>"> 
          <td class="dateCol"><a href="cpAdmin.php?CPTrackID=<?php echo $cpRow['CPTrackID']; ?>" title="<?php echo $cpRow['CPNote']; ?>"><?php echo $displayDate; ?></a>
            <input type="hidden" name="CPTrackID[]" id="CPTrackID_<?php echo $cpRow['CPTrackID']; ?>" value="<?php echo $cpRow['CPTrackID']; ?>" />
    
          </td>
          <td class="charCol"><?php echo $cpRow['charName']; ?></td>
          <td class="playerCol"><?php echo $cpRow['firstName'] . ' ' . $cpRow['lastName']; ?></td>
          <td class="numCol"><?php echo $cpRow['numberCP']; ?></td>
          <td class="staffCol"><?php echo $cpRow['staffMember']; ?></td>
          <td class="catCol"><?php echo $cpRow['CPCatName']; ?></td>
          <td class="actionCol">
          	
            <div class="actionsContainer">
              <a href="#" title="CP actions" class="actionsLink">Actions</a>
              <div class="menu" style="display:none">
                  <ul>
                      <li><a href="cpAdmin.php?CPTrackID=<?php echo $cpRow['CPTrackID']; ?>" title="">Edit</a></li>
                  </ul>
              </div>
            </div><!--.actionsContainer-->
            
        </tr>
        
    <?php 
        $rowIndex++;
    } // end loop through CP records
	
	if ($cp->num_rows == 0) {
	  echo '<tr class="even"><td colspan="5"><p class="noResults">There are no CP records of this type.</p></td></tr>';  
	}

} // end of changeCPTab

/***************************************************************
SET CP FILTER EXPANDED
***************************************************************/
if ($ajaxAction == 'setCPFilterExpanded' && isset($_POST['cpFilterExpanded'])) {
	$_SESSION['cpFilterExpanded'] = $_POST['cpFilterExpanded'];
}

/***************************************************************
GET FILTERED CP
***************************************************************/
if ($ajaxAction == 'getFilteredCP' && isset($_POST)) {
	// echo 'CPType: ' . $_POST['CPType'] .  '<br />';
	
	$_SESSION['cpFilters'] = $_POST; // Put filter criteria in session for later use
	$_SESSION['selectedCPTab'] = $_POST['selectedCPTab'];
	
	$cpObj = new CP();
	$cpResults = $cpObj->getFilteredCP($_POST);
	
	if ($cpResults->num_rows == 100) {
		echo '<tr class="even"><td colspan="7"><p class="noResults">Showing the first 100 results. Set specific filter parameters to narrow the results. </td></tr>'; 	
	}
	
	$rowIndex = 0;
	while ($cpRow = $cpResults->fetch_assoc()) { // Loop through retrieved CP records
		if ($rowIndex % 2 == 0) {
			$rowClass = 'even';
		} else {
			$rowClass = 'odd';
		}
		
		// Format date
		$dateString = strtotime($cpRow['CPDateStamp']);
		$displayDate = date('n/j/Y', $dateString);

?>
        <tr class="<?php echo $rowClass; ?>"> 
          <td class="dateCol"><a href="cpAdmin.php?CPTrackID=<?php echo $cpRow['CPTrackID']; ?>" title="<?php echo $cpRow['CPNote']; ?>"><?php echo $displayDate; ?></a>
            <input type="hidden" name="CPTrackID[]" id="CPTrackID_<?php echo $cpRow['CPTrackID']; ?>" value="<?php echo $cpRow['CPTrackID']; ?>" />
    
          </td>
          <td class="charCol"><?php echo $cpRow['charName']; ?></td>
          <td class="playerCol"><?php echo $cpRow['firstName'] . ' ' . $cpRow['lastName']; ?></td>
          <td class="numCol"><?php echo $cpRow['numberCP']; ?></td>
          <td class="staffCol"><?php echo $cpRow['staffMember']; ?></td>
          <td class="catCol"><?php echo $cpRow['CPCatName']; ?></td>
          <td class="actionCol">
          	
            <div class="actionsContainer">
              <a href="#" title="CP actions" class="actionsLink">Actions</a>
              <div class="menu" style="display:none">
                  <ul>
                      <li><a href="cpAdmin.php?CPTrackID=<?php echo $cpRow['CPTrackID']; ?>" title="">Edit</a></li>
                      <li><a href="#" title="Delete this CP record" class="deleteLink">Delete</a></li>
                  </ul>
              </div>
            </div><!--.actionsContainer-->
            
        </tr>
        
    <?php 
        $rowIndex++;
    } // end loop through CP records
	
	if ($cpResults->num_rows == 0) {
	  echo '<tr class="even"><td colspan="7"><p class="noResults">There are no CP records that match your criteria.</p></td></tr>';  
	}
	
} // end of getFilteredCP

?>