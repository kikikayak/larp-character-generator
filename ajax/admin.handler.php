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
DISPLAY UI MESSAGE
***************************************************************/
if ($ajaxAction == 'displayUIMessage') {
	cg_showUIMessage();
	cg_clearUIMessage();	
} // end of displayUIMessage

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
LOAD COUNTRY DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCountryDeleteDialog' && isset($_POST['countryID'])) {
	$allowCountryDelete = 'no';
	$country = new Country();
	$countryChars = $country->getCountryChars($_POST['countryID']);
	
	// If any characters are from this country, disallow delete
	if ($countryChars->num_rows > 0) {
?>
<p>This country cannot be deleted because it has the following <span class="numDeleteItems"><?php echo $countryChars->num_rows; ?></span> characters associated with it: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($charRow = $countryChars->fetch_assoc()) {
  ?>
  <li><?php echo $charRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please change these characters to a different country and then try again. </p>

<?php
	} else {
	  // If no characters are from this country, proceed with delete
	  $allowCountryDelete = 'yes';
	  $countryDetails = $country->getCountry($_POST['countryID']);
	  while ($row = $countryDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following country?</p>
      <p class="deleteItemName"><?php echo $row['countryName']; ?></p>
      <p>You will be able to restore the country from the trash if necessary.</p>
      <input type="hidden" name="deleteCountryID" id="deleteCountryID" value="<?php echo $_POST['countryID']; ?>" />
	
<?php
	  } // end of countryDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowCountryDelete" id="allowCountryDelete" value="<?php echo $allowCountryDelete; ?>" />
       
<?php
} // end loadCountryDelete

/***************************************************************
DELETE COUNTRY
***************************************************************/
if ($ajaxAction == 'deleteCountry' && isset($_POST['countryID'])) {
	$country = new Country();
	
	if ($country->deleteCountry($_POST['countryID'])) {
		$countryDetails = $country->getCountry($_POST['countryID']);
		
		while ($row = $countryDetails->fetch_assoc()) {
		  $html = array();
		  $html['countryName'] = htmlentities($row['countryName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Country Deleted Successfully',
													'<p>The country "' . $html['countryName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of countryDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Country',
												'<p>The country could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteCountry

/***************************************************************
UNDELETE COUNTRY
***************************************************************/
if ($ajaxAction == 'undeleteCountry' && isset($_POST['countryID'])) {
	$country = new Country();
	
	if ($country->undeleteCountry($_POST['countryID'])) {
		$countryDetails = $country->getCountry($_POST['countryID']);
		
		while ($row = $countryDetails->fetch_assoc()) {
		  $html = array();
		  $html['countryName'] = htmlentities($row['countryName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Country Undeleted Successfully',
													'<p>The country "' . $html['countryName'] . '" has been restored from the trash. You can view it from the <a href="countries.php" title="Go to countries page">Countries</a> page.</p>');
												
		} // end of countryDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Country',
												'<p>The country could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteCountry

/***************************************************************
LOAD HEADER DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadHeaderDeleteDialog' && isset($_POST['headerID'])) {
	$allowHeaderDelete = 'no';
	$header = new Header();
	$headerChars = $header->getHeaderChars($_POST['headerID']);
	
	$headerSkills = $header->getHeaderSkills($_POST['headerID']);
	
	if ($headerChars->num_rows > 0) {
?>
<p>This header cannot be deleted because the following <span class="numDeleteItems"><?php echo $headerChars->num_rows; ?></span> characters have purchased it: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($charRow = $headerChars->fetch_assoc()) {
  ?>
  <li><?php echo $charRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please remove this header from these characters and then try again. </p>

<?php
	} else if ($headerSkills->num_rows > 0) {
?>
    <p>This header cannot be deleted because the following <span class="numDeleteItems"><?php echo $headerSkills->num_rows; ?></span> skills belong to the header: </p>
    
    <div class="deleteList">
      <ul>
      
      <?php
          while ($skillRow = $headerSkills->fetch_assoc()) {
      ?>
      <li><?php echo $skillRow['skillName']; ?></li>
      
      <?php
          } // end skills loop
      ?>
      </ul>
    </div><!--.deleteList-->
    
    <p>Please remove these skills from the header and then try again. </p>
<?php
	} else {
	  $allowHeaderDelete = 'yes';
	  $headerDetails = $header->getHeader($_POST['headerID']);
	  while ($row = $headerDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following header?</p>
      <p class="deleteItemName"><?php echo $row['headerName']; ?></p>
      <p>You will be able to restore the header from the trash if necessary.</p>
      <input type="hidden" name="deleteHeaderID" id="deleteHeaderID" value="<?php echo $_POST['headerID']; ?>" />
	
<?php
	  } // end of headerDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowHeaderDelete" id="allowHeaderDelete" value="<?php echo $allowHeaderDelete; ?>" />
       
<?php
} // end loadHeaderDelete

/***************************************************************
DELETE HEADER
***************************************************************/
if ($ajaxAction == 'deleteHeader' && isset($_POST['headerID'])) {
	$header = new Header();
	
	if ($header->deleteHeader($_POST['headerID'])) {
		$headerDetails = $header->getHeader($_POST['headerID']);
		
		while ($row = $headerDetails->fetch_assoc()) {
		  $html = array();
		  $html['headerName'] = htmlentities($row['headerName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Header Deleted Successfully',
													'<p>The header "' . $html['headerName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of headerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Header',
												'<p>The header could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteHeader

/***************************************************************
UNDELETE HEADER
***************************************************************/
if ($ajaxAction == 'undeleteHeader' && isset($_POST['headerID'])) {
	$header = new Header();
	
	if ($header->undeleteHeader($_POST['headerID'])) {
		$headerDetails = $header->getHeader($_POST['headerID']);
		
		while ($row = $headerDetails->fetch_assoc()) {
		  $html = array();
		  $html['headerName'] = htmlentities($row['headerName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Header Undeleted Successfully',
													'<p>The header "' . $html['headerName'] . '" has been restored from the trash. You can view it on the <a href="headers.php" title="Go to headers page">headers</a> page.</p>');
												
		} // end of headerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Header',
												'<p>The header could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteHeader

/***************************************************************
LOAD HEADER PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadHeaderPurgeDialog' && isset($_POST['headerID'])) {
	$header = new Header();
	$headerChars = $header->getHeaderChars($_POST['headerID']);
	$headerDetails = $header->getHeader($_POST['headerID']);
	while ($row = $headerDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following header?</p>
      <p class="deleteItemName"><?php echo $row['headerName']; ?></p>
      <p>This header will be removed from any character that currently has it. All skills will also be removed from this header. You will not be able to undo this action.</p>
      <input type="hidden" name="purgeHeaderID" id="purgeHeaderID" value="<?php echo $_POST['headerID']; ?>" />
	
<?php
	} // end of headerDetails loop
} // end loadHeaderPurgeDialog

/***************************************************************
PURGE HEADER
***************************************************************/
if ($ajaxAction == 'purgeHeader' && isset($_POST['headerID'])) {
	$header = new Header();
	
	if ($header->purgeHeader($_POST['headerID'])) {
		$headerDetails = $header->getHeader($_POST['headerID']);
		
		while ($row = $headerDetails->fetch_assoc()) {
		  $html = array();
		  $html['headerName'] = htmlentities($row['headerName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Header Purged Successfully',
													'<p>The header "' . $html['headerName'] . '" has been permanently deleted.</p>');
												
		} // end of headerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Header',
												'<p>The header could not be purged. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeHeader

/***************************************************************
LOAD SKILL DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadSkillDeleteDialog' && isset($_POST['skillID'])) {
	$allowSkillDelete = 'no';
	$skill = new Skill();
	$skillChars = $skill->getSkillChars($_POST['skillID']);
	
	if ($skillChars->num_rows > 0) {
?>
<p>This skill cannot be deleted because the following <span class="numDeleteItems"><?php echo $skillChars->num_rows; ?></span> characters have purchased it: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($charRow = $skillChars->fetch_assoc()) {
  ?>
  <li><?php echo $charRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please remove this skill from these characters and then try again. </p>

<?php
	} else {
	  $allowSkillDelete = 'yes';
	  $skillDetails = $skill->getSkill($_POST['skillID']);
	  while ($row = $skillDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following skill?</p>
      <p class="deleteItemName"><?php echo $row['skillName']; ?></p>
      <p>You will be able to restore the skill from the trash if necessary.</p>
      <input type="hidden" name="deleteSkillID" id="deleteSkillID" value="<?php echo $_POST['skillID']; ?>" />
	
<?php
	  } // end of skillDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowSkillDelete" id="allowSkillDelete" value="<?php echo $allowSkillDelete; ?>" />
       
<?php
} // end loadSkillDelete

/***************************************************************
DELETE SKILL
***************************************************************/
if ($ajaxAction == 'deleteSkill' && isset($_POST['skillID'])) {
	$skill = new Skill();
	
	if ($skill->deleteSkill($_POST['skillID'])) {
		$skillDetails = $skill->getSkill($_POST['skillID']);
		
		while ($row = $skillDetails->fetch_assoc()) {
		  $html = array();
		  $html['skillName'] = htmlentities($row['skillName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Skill Deleted Successfully',
													'<p>The skill "' . $html['skillName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of headerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Skill',
												'<p>The skill could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteSkill

/***************************************************************
LOAD SKILL PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadSkillPurgeDialog' && isset($_POST['skillID'])) {
	$skill = new Skill();
	$skillDetails = $skill->getSkill($_POST['skillID']);
	while ($row = $skillDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following skill?</p>
      <p class="deleteItemName"><?php echo $row['skillName']; ?></p>
      <p>The skill will be removed from all characters, and all other associated data will be removed. You will not be able to undo this action.</p>
      <input type="hidden" name="purgeSkillID" id="purgeSkillID" value="<?php echo $_POST['skillID']; ?>" />
	
<?php
	} // end of skillDetails loop
} // end loadSkillPurge

/***************************************************************
PURGE SKILL
***************************************************************/
if ($ajaxAction == 'purgeSkill' && isset($_POST['skillID'])) {
	$skill = new Skill();
	
	if ($skill->purgeSkill($_POST['skillID'])) {
		$skillDetails = $skill->getSkill($_POST['skillID']);
		
		while ($row = $skillDetails->fetch_assoc()) {
		  $html = array();
		  $html['skillName'] = htmlentities($row['skillName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Skill Purged Successfully',
													'<p>The skill "' . $html['skillName'] . '" has been permanently deleted. </p>');
												
		} // end of skillDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Skill',
												'<p>The skill could not be permanently deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeSkill

/***************************************************************
UNDELETE SKILL
***************************************************************/
if ($ajaxAction == 'undeleteSkill' && isset($_POST['skillID'])) {
	$skill = new Skill();
	
	if ($skill->undeleteSkill($_POST['skillID'])) {
		$skillDetails = $skill->getSkill($_POST['skillID']);
		
		while ($row = $skillDetails->fetch_assoc()) {
		  $html = array();
		  $html['skillName'] = htmlentities($row['skillName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Skill Undeleted Successfully',
													'<p>The skill "' . $html['skillName'] . '" has been restored from the trash. You can view it from the <a href="skills.php">Skills</a> page.</p>');
												
		} // end of skillDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Skill',
												'<p>The skill could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteSkill

/***************************************************************
LOAD SPELL DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadSpellDeleteDialog' && isset($_POST['spellID'])) {
	$allowSpellDelete = 'no';
	$spell = new Spell();
	$spellChars = $spell->getSpellChars($_POST['spellID']);
	
	if ($spellChars->num_rows > 0) {
?>
<p>This spell cannot be deleted because the following <span class="numDeleteItems"><?php echo $spellChars->num_rows; ?></span> characters have purchased it: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($charRow = $spellChars->fetch_assoc()) {
  ?>
  <li><?php echo $charRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please remove this spell from these characters and then try again. </p>

<?php
	} else {
	  $allowSpellDelete = 'yes';
	  $spellDetails = $spell->getSpell($_POST['spellID']);
	  while ($row = $spellDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following spell?</p>
      <p class="deleteItemName"><?php echo $row['spellName']; ?></p>
      <p>You will be able to restore the spell from the trash if necessary.</p>
      <input type="hidden" name="deleteSpellID" id="deleteSpellID" value="<?php echo $_POST['spellID']; ?>" />
	
<?php
	  } // end of spellDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowSpellDelete" id="allowSpellDelete" value="<?php echo $allowSpellDelete; ?>" />
       
<?php
} // end loadSpellDelete

/***************************************************************
DELETE SPELL
***************************************************************/
if ($ajaxAction == 'deleteSpell' && isset($_POST['spellID'])) {
	$spell = new Spell();
	
	if ($spell->deleteSpell($_POST['spellID'])) {
		$spellDetails = $spell->getSpell($_POST['spellID']);
		
		while ($row = $spellDetails->fetch_assoc()) {
		  $html = array();
		  $html['spellName'] = htmlentities($row['spellName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Spell Deleted Successfully',
													'<p>The spell "' . $html['spellName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of headerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Spell',
												'<p>The spell could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteSpell

/***************************************************************
LOAD SPELL PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadSpellPurgeDialog' && isset($_POST['spellID'])) {
	$spell = new Spell();
	$spellDetails = $spell->getSpell($_POST['spellID']);
	while ($row = $spellDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following spell?</p>
      <p class="deleteItemName"><?php echo $row['spellName']; ?></p>
      <p>The spell will be removed from all characters and all associated data will be removed. You will not be able to undo this action.</p>
      <input type="hidden" name="purgeSpellID" id="purgeSpellID" value="<?php echo $_POST['spellID']; ?>" />
	
<?php
	} // end of spellDetails loop
} // end loadSpellPurge

/***************************************************************
PURGE SPELL
***************************************************************/
if ($ajaxAction == 'purgeSpell' && isset($_POST['spellID'])) {
	$spell = new Spell();
	
	if ($spell->purgeSpell($_POST['spellID'])) {
		$spellDetails = $spell->getSpell($_POST['spellID']);
		
		while ($row = $spellDetails->fetch_assoc()) {
		  $html = array();
		  $html['spellName'] = htmlentities($row['spellName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Spell Purged Successfully',
													'<p>The spell "' . $html['spellName'] . '" has been permanently deleted. </p>');
												
		} // end of spellDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Spell',
												'<p>The spell could not be permanently deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeSpell

/***************************************************************
UNDELETE SPELL
***************************************************************/
if ($ajaxAction == 'undeleteSpell' && isset($_POST['spellID'])) {
	$spell = new Spell();
	
	if ($spell->undeleteSpell($_POST['spellID'])) {
		$spellDetails = $spell->getSpell($_POST['spellID']);
		
		while ($row = $spellDetails->fetch_assoc()) {
		  $html = array();
		  $html['spellName'] = htmlentities($row['spellName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Spell Undeleted Successfully',
													'<p>The spell "' . $html['spellName'] . '" has been restored from the trash. You can view it from the <a href="spells.php" title="Go to spells page">Spells</a> page. </p>');
												
		} // end of spellDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Spell',
												'<p>The spell could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteSpell

/***************************************************************
LOAD TRAIT DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadTraitDeleteDialog' && isset($_POST['traitID'])) {
	$allowTraitDelete = 'no';
	$trait = new charTrait();
	$traitChars = $trait->getTraitChars($_POST['traitID']);
	
	if ($traitChars->num_rows > 0) {
?>
<p>This trait cannot be deleted because the following <span class="numDeleteItems"><?php echo $traitChars->num_rows; ?></span> characters have the trait: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($charRow = $traitChars->fetch_assoc()) {
  ?>
  <li><?php echo $charRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please remove the trait from these characters and then try again. </p>

<?php
	} else {
	  $allowTraitDelete = 'yes';
	  $traitDetails = $trait->getTrait($_POST['traitID']);
	  while ($row = $traitDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following trait?</p>
      <p class="deleteItemName"><?php echo $row['traitName']; ?></p>
      <p>You will be able to restore the trait from the trash if necessary.</p>
      <input type="hidden" name="deleteTraitID" id="deleteTraitID" value="<?php echo $_POST['traitID']; ?>" />
	
<?php
	  } // end of traitDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowTraitDelete" id="allowTraitDelete" value="<?php echo $allowTraitDelete; ?>" />
       
<?php
} // end loadTraitDelete

/***************************************************************
DELETE TRAIT
***************************************************************/
if ($ajaxAction == 'deleteTrait' && isset($_POST['traitID'])) {
	$trait = new charTrait();
	
	if ($trait->deleteTrait($_POST['traitID'])) {
		$traitDetails = $trait->getTrait($_POST['traitID']);
		
		while ($row = $traitDetails->fetch_assoc()) {
		  $html = array();
		  $html['traitName'] = htmlentities($row['traitName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Trait Deleted Successfully',
													'<p>The trait "' . $html['traitName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of traitDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Trait',
												'<p>The trait could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteTrait

/***************************************************************
LOAD TRAIT PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadTraitPurgeDialog' && isset($_POST['traitID'])) {
	$trait = new charTrait();
	$traitDetails = $trait->getTrait($_POST['traitID']);
	while ($row = $traitDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following trait?</p>
      <p class="deleteItemName"><?php echo $row['traitName']; ?></p>
      <p>This trait will be removed from all characters. You will not be able to undo this action.</p>
      <input type="hidden" name="purgeTraitID" id="purgeTraitID" value="<?php echo $_POST['traitID']; ?>" />
	
<?php
	} // end of traitDetails loop
} // end loadTraitPurge

/***************************************************************
PURGE TRAIT
***************************************************************/
if ($ajaxAction == 'purgeTrait' && isset($_POST['traitID'])) {
	$trait = new charTrait();
	
	if ($trait->purgeTrait($_POST['traitID'])) {
		$traitDetails = $trait->getTrait($_POST['traitID']);
		
		while ($row = $traitDetails->fetch_assoc()) {
		  $html = array();
		  $html['traitName'] = htmlentities($row['traitName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Trait Purged Successfully',
													'<p>The trait "' . $html['traitName'] . '" has been permanently deleted.</p>');
												
		} // end of traitDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Trait',
												'<p>The trait could not be permanently deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeTrait

/***************************************************************
UNDELETE TRAIT
***************************************************************/
if ($ajaxAction == 'undeleteTrait' && isset($_POST['traitID'])) {
	$trait = new charTrait();
	
	if ($trait->undeleteTrait($_POST['traitID'])) {
		$traitDetails = $trait->getTrait($_POST['traitID']);
		
		while ($row = $traitDetails->fetch_assoc()) {
		  $html = array();
		  $html['traitName'] = htmlentities($row['traitName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Trait Undeleted Successfully',
													'<p>The trait "' . $html['traitName'] . '" has been restored from the trash. You can view it from the <a href="traits.php">Traits</a> page.</p>');
												
		} // end of traitDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Trait',
												'<p>The trait could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteTrait

/***************************************************************
LOAD COMMUNITY DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCommunityDeleteDialog' && isset($_POST['communityID'])) {
	$allowCommunityDelete = 'no';
	$community = new Community();
	$communityChars = $community->getCommunityChars($_POST['communityID']);
	
	if ($communityChars->num_rows > 0) {
?>
<p>This <?php echo $_SESSION['communityLabel']; ?> cannot be deleted because the following <span class="numDeleteItems"><?php echo $communityChars->num_rows; ?></span> characters belong to it: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($charRow = $communityChars->fetch_assoc()) {
  ?>
  <li><?php echo $charRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please move the characters to a different <?php echo $_SESSION['communityLabel']; ?> and then try again. </p>

<?php
	} else {
	  $allowCommunityDelete = 'yes';
	  $communityDetails = $community->getCommunity($_POST['communityID']);
	  while ($row = $communityDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following <?php echo $_SESSION['communityLabel']; ?>?</p>
      <p class="deleteItemName"><?php echo $row['communityName']; ?></p>
      <p>You will be able to restore it from the trash if necessary.</p>
      <input type="hidden" name="deleteCommunityID" id="deleteCommunityID" value="<?php echo $_POST['communityID']; ?>" />
	
<?php
	  } // end of communityDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowCommunityDelete" id="allowCommunityDelete" value="<?php echo $allowCommunityDelete; ?>" />
       
<?php
} // end loadCommunityDelete

/***************************************************************
DELETE COMMUNITY
***************************************************************/
if ($ajaxAction == 'deleteCommunity' && isset($_POST['communityID'])) {
	$community = new Community();
	
	if ($community->deleteCommunity($_POST['communityID'])) {
		$communityDetails = $community->getCommunity($_POST['communityID']);
		
		while ($row = $communityDetails->fetch_assoc()) {
		  $html = array();
		  $html['communityName'] = htmlentities($row['communityName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Community Deleted Successfully',
													'<p>The community "' . $html['communityName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of communityDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Community',
												'<p>The community could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteCommunity

/***************************************************************
LOAD COMMUNITY PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCommunityPurgeDialog' && isset($_POST['communityID'])) {
	$community = new Community();
	$communityDetails = $community->getCommunity($_POST['communityID']);
	while ($row = $communityDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following <?php echo $_SESSION['communityLabel']; ?>?</p>
      <p class="deleteItemName"><?php echo $row['communityName']; ?></p>
      <p>You will not be able to undo this action.</p>
      <input type="hidden" name="purgeCommunityID" id="purgeCommunityID" value="<?php echo $_POST['purgeCommunityID']; ?>" />
	       
<?php
	} // end of communityDetails loop
} // end loadCommunityPurgeDialog

/***************************************************************
PURGE COMMUNITY
***************************************************************/
if ($ajaxAction == 'purgeCommunity' && isset($_POST['communityID'])) {
	$community = new Community();
	
	if ($community->purgeCommunity($_POST['communityID'])) {
		$communityDetails = $community->getCommunity($_POST['communityID']);
		
		while ($row = $communityDetails->fetch_assoc()) {
		  $html = array();
		  $html['communityName'] = htmlentities($row['communityName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Community Purged Successfully',
													'<p>The community "' . $html['communityName'] . '" has been permanently deleted. </p>');
												
		} // end of communityDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Community',
												'<p>The community could not be permanently ]deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeCommunity

/***************************************************************
UNDELETE COMMUNITY
***************************************************************/
if ($ajaxAction == 'undeleteCommunity' && isset($_POST['communityID'])) {
	$community = new Community();
	
	if ($community->undeleteCommunity($_POST['communityID'])) {
		$communityDetails = $community->getCommunity($_POST['communityID']);
		
		while ($row = $communityDetails->fetch_assoc()) {
		  $html = array();
		  $html['communityName'] = htmlentities($row['communityName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Community Undeleted Successfully',
													'<p>The community "' . $html['communityName'] . '" has been restored from the trash. You can view it from the <a href="communities.php" title="Go to communities page">Communities</a> page.</p>');
												
		} // end of communityDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Community',
												'<p>The community could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteCommunity

/***************************************************************
LOAD COUNTRY PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadCountryPurgeDialog' && isset($_POST['countryID'])) {
	$country = new Country();
	$countryDetails = $country->getCountry($_POST['countryID']);
	while ($row = $countryDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following country?</p>
      <p class="deleteItemName"><?php echo $row['countryName']; ?></p>
      <p>You will not be able to undo this action.</p>
      <input type="hidden" name="purgeCountryID" id="purgeCountryID" value="<?php echo $_POST['countryID']; ?>" />
	       
<?php
	} // end of countryDetails loop
} // end loadCountryPurgeDialog

/***************************************************************
PURGE COUNTRY
***************************************************************/
if ($ajaxAction == 'purgeCountry' && isset($_POST['countryID'])) {
	$country = new Country();
	
	if ($country->purgeCountry($_POST['countryID'])) {
		$countryDetails = $country->getCountry($_POST['countryID']);
		
		while ($row = $countryDetails->fetch_assoc()) {
		  $html = array();
		  $html['countryName'] = htmlentities($row['countryName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Country Purged Successfully',
													'<p>The country "' . $html['countryName'] . '" has been permanently deleted. </p>');
												
		} // end of countryDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Country',
												'<p>The country could not be permanently deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeCountry

/***************************************************************
LOAD RACE DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadRaceDeleteDialog' && isset($_POST['raceID'])) {
	$allowRaceDelete = 'no';
	$race = new Race();
	$raceChars = $race->getRaceChars($_POST['raceID']);
	
	// If any characters have, disallow delete
	if ($raceChars->num_rows > 0) {
?>
<p>This race cannot be deleted because the following <span class="numDeleteItems"><?php echo $raceChars->num_rows; ?></span> characters are of this race: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($raceRow = $raceChars->fetch_assoc()) {
  ?>
  		<li><?php echo $raceRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please change these characters to a different race and then try again. </p>

<?php
	} else {
	  // If no characters are of this race, proceed with delete
	  $allowRaceDelete = 'yes';
	  $raceDetails = $race->getRace($_POST['raceID']);
	  while ($row = $raceDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following race?</p>
      <p class="deleteItemName"><?php echo $row['raceName']; ?></p>
      <p>You will be able to restore the race from the trash if necessary.</p>
      <input type="hidden" name="deleteRaceID" id="deleteRaceID" value="<?php echo $_POST['raceID']; ?>" />
	
<?php
	  } // end of raceDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowRaceDelete" id="allowRaceDelete" value="<?php echo $allowRaceDelete; ?>" />
       
<?php
} // end loadRaceDelete

/***************************************************************
DELETE RACE
***************************************************************/
if ($ajaxAction == 'deleteRace' && isset($_POST['raceID'])) {
	$race = new Race();
	
	if ($race->deleteRace($_POST['raceID'])) {
		$raceDetails = $race->getRace($_POST['raceID']);
		
		while ($row = $raceDetails->fetch_assoc()) {
		  $html = array();
		  $html['raceName'] = htmlentities($row['raceName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Race Deleted Successfully',
													'<p>The race "' . $html['raceName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of raceDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Race',
												'<p>The race could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteRace

/***************************************************************
LOAD RACE PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadRacePurgeDialog' && isset($_POST['raceID'])) {
	$race = new Race();
	$raceDetails = $race->getRace($_POST['raceID']);
	while ($row = $raceDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following race?</p>
      <p class="deleteItemName"><?php echo $row['raceName']; ?></p>
      <p>You will not be able to undo this action.</p>
      <input type="hidden" name="purgeRaceID" id="purgeRaceID" value="<?php echo $_POST['raceID']; ?>" />
	       
<?php
	} // end of raceDetails loop
} // end loadRacePurgeDialog

/***************************************************************
PURGE RACE
***************************************************************/
if ($ajaxAction == 'purgeRace' && isset($_POST['raceID'])) {
	$race = new Race();
	
	if ($race->purgeRace($_POST['raceID'])) {
		$raceDetails = $race->getRace($_POST['raceID']);
		
		while ($row = $raceDetails->fetch_assoc()) {
		  $html = array();
		  $html['raceName'] = htmlentities($row['raceName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Race Purged Successfully',
													'<p>The race "' . $html['raceName'] . '" has been permanently deleted. </p>');
												
		} // end of raceDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Race',
												'<p>The race could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeRace

/***************************************************************
UNDELETE RACE
***************************************************************/
if ($ajaxAction == 'undeleteRace' && isset($_POST['raceID'])) {
	$race = new Race();
	
	if ($race->undeleteRace($_POST['raceID'])) {
		$raceDetails = $race->getRace($_POST['raceID']);
		
		while ($row = $raceDetails->fetch_assoc()) {
		  $html = array();
		  $html['raceName'] = htmlentities($row['raceName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Race Undeleted Successfully',
													'<p>The race "' . $html['raceName'] . '" has been restored from the trash. You can view it on the <a href="races.php" title="Go to races page">Races</a> page. </p>');
												
		} // end of raceDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Race',
												'<p>The race could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteRace

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
CHANGE HEADER TABS
***************************************************************/
if ($ajaxAction == 'changeHeadersTab' && isset($_POST['tabName'])) {
	// Possible tab values are "showAll," "showPublic," "showHidden," "showNPCOnly"
	
	$_SESSION['selectedHeaderTab'] = $_POST['tabName'];
	
	$headerObj = new Header();
	
	if ($_POST['tabName'] == 'showPublic') {
		$headers = $headerObj->getPublicHeaders();	
	} else if ($_POST['tabName'] == 'showHidden') {
		$headers = $headerObj->getHiddenHeaders();
	} else if ($_POST['tabName'] == 'showNPCOnly') {
		$headers = $headerObj->getNPCHeaders();
	} else {
		$headers = $headerObj->getAllHeaders();
	}
	
	$rowIndex = 0;
	while ($header = $headers->fetch_assoc()) { // Loop through retrieved countries
		if ($rowIndex % 2 == 0) {
			$rowClass = 'even';
		} else {
			$rowClass = 'odd';
		}

?>
        <tr class="<?php echo $rowClass; ?>"> 
          <td class="col1"><a href="headerAdmin.php?headerID=<?php echo $header['headerID']; ?>" title="Modify this header"><?php echo $header['headerName']; ?></a>
            <input type="hidden" name="headerID[]" id="headerID_<?php echo $header['headerID']; ?>" value="<?php echo $header['headerID']; ?>" />
    
          </td>
          <td class="col2"><?php echo $header['headerCost']; ?> CP</td>
          <td class="col3"><?php echo $header['headerAccess']; ?></td>
          <td class="col4">
          	
            <div class="actionsContainer">
              <a href="#" title="Header actions" class="actionsLink">Actions</a>
              <div class="menu" style="display:none">
                  <ul>
                      <li><a href="headerAdmin.php?headerID=<?php echo $header['headerID']; ?>" title="">Edit</a></li>
                      <li><a href="#" title="Delete this header" class="deleteLink">Delete</a></li>
                  </ul>
              </div>
            </div><!--.actionsContainer-->
            
        </tr>
        
    <?php 
        $rowIndex++;
    } // end loop through headers
	
	if ($headers->num_rows == 0) {
	  echo '<tr class="even"><td colspan="5"><p class="noResults">There are no headers of this type.</p></td></tr>';  
	}

} // end of changeHeadersTab

/***************************************************************
CHANGE SKILLS TAB 
***************************************************************/
if ($ajaxAction == 'changeSkillsTab' && isset($_POST['tabName'])) {
	// Possible tab values are "showAll," "showPublic," "showHidden," "showNPCOnly"
	
	$_SESSION['selectedSkillTab'] = $_POST['tabName'];
	
	$skillObj = new Skill();
	
	if ($_POST['tabName'] == 'showPublic') {
		$skills = $skillObj->getPublicSkills();	
	} else if ($_POST['tabName'] == 'showHidden') {
		$skills = $skillObj->getHiddenSkills();
	} else if ($_POST['tabName'] == 'showNPCOnly') {
		$skills = $skillObj->getNPCSkills();
	} else {
		$skills = $skillObj->getAllSkills();
	}
                
	$rowIndex = 0;
	while ($skill = $skills->fetch_assoc()) { // Loop through retrieved skills
		if ($rowIndex % 2 == 0) {
			$rowClass = 'even';
		} else {
			$rowClass = 'odd';
		}

?>

        <tr class="<?php echo $rowClass; ?>">
          <td class="nameCol"><a href="skillAdmin.php?skillID=<?php echo $skill['skillID']; ?>"><?php echo $skill['skillName']; ?></a>
            <input type="hidden" name="skillID[]" id="skillID_<?php echo $skill['skillID']; ?>" value="<?php echo $skill['skillID']; ?>" />
          </td>
          <td class="costCol"><?php echo $skill['skillCost']; ?> CP</td>
          <td class="stackCol">
              <?php 
                  if ($skill['maxQuantity'] > 1) {
                      echo 'Yes';
                  } else {
                      echo 'No';
                  }
              ?>
          </td>
          <td class="headerCol"><?php echo $skill['headerName']; ?></td>
          <td class="accessCol"><?php echo $skill['skillAccess']; ?></td>
          <td class="actionsCol">
            <div class="actionsContainer">
                <a href="#" title="Skill actions" class="actionsLink">Actions</a>
                <div class="menu" style="display:none">
                    <ul>
                        <li><a href="skillAdmin.php?skillID=<?php echo $skill['skillID']; ?>" title="">Edit</a></li>
                        <li><a href="#" title="Delete this skill" class="deleteLink">Delete</a></li>
                    </ul>
                </div>
              </div><!--.actionsContainer-->
        </tr>
        
        <?php 
              $rowIndex++;
          } // end loop through skills
		  
		  if ($skills->num_rows == 0) {
			echo '<tr class="even"><td colspan="5"><p class="noResults">There are no skills of this type.</p></td></tr>';  
		  }

} // end of changeSkillsTab

/***************************************************************
CHANGE SPELLS TAB 
***************************************************************/
if ($ajaxAction == 'changeSpellsTab' && isset($_POST['tabName'])) {
	// Possible tab values are "showAll," "showPublic," "showHidden," "showNPCOnly"
	
	$_SESSION['selectedSpellTab'] = $_POST['tabName'];
	
	$spellObj = new Spell();
	
	if ($_POST['tabName'] == 'showPublic') {
		$spells = $spellObj->getPublicSpells();	
	} else if ($_POST['tabName'] == 'showHidden') {
		$spells = $spellObj->getHiddenSpells();
	} else if ($_POST['tabName'] == 'showNPCOnly') {
		$spells = $spellObj->getNPCSpells();
	} else {
		$spells = $spellObj->getAllSpells();
	}
	
	$rowIndex = 0;
	while ($spell = $spells->fetch_assoc()) { // Loop through retrieved countries
	  if ($rowIndex % 2 == 0) {
		  $rowClass = 'even';
	  } else {
		  $rowClass = 'odd';
	  }
	?>

	<tr class="<?php echo $rowClass; ?>">
	  <td class="col1"><a href="spellAdmin.php?spellID=<?php echo $spell['spellID']; ?>"><?php echo $spell['spellName']; ?></a>
	  <input type="hidden" name="spellID[]" id="spellID_<?php echo $spell['spellID']; ?>" value="<?php echo $spell['spellID']; ?>" />
	  </td>
	  <td class="col2"><?php echo $spell['skillName']; ?></td>
	  <td class="col3"><?php echo $spell['spellCost']; ?> CP</td>
	  <td class="col4"><?php echo $spell['spellAccess']; ?></td>
	  <td class="col5">
      	<div class="actionsContainer">
          <a href="#" title="Spell actions" class="actionsLink">Actions</a>
          <div class="menu" style="display:none">
              <ul>
                  <li><a href="spellAdmin.php?spellID=<?php echo $spell['spellID']; ?>" title="">Edit</a></li>
                  <li><a href="#" title="Delete this spell" class="deleteLink">Delete</a></li>
              </ul>
          </div>
        </div><!--.actionsContainer-->
      </td>
	</tr>
	<?php 
		  $rowIndex++;
	  } // end loop through spells
	  
	  if ($spells->num_rows == 0) {
		echo '<tr class="even"><td colspan="5"><p class="noResults">There are no spells of this type.</p></td></tr>';  
	  }

} // end of changeSpellsTab

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

/***************************************************************
GET SKILL SUGGESTIONS
This method can be used to populate skill autosuggest fields. 
***************************************************************/
if ($ajaxAction == 'getSkillSuggestions' && isset($_GET['term'])) {
	$skills = array();
	
	$skillObj = new Skill();
	$skillResult = $skillObj->getSkillSuggestions($_GET['term']);
	
	while ($skillRow = $skillResult->fetch_assoc()) {
		$skills[] = $skillRow['skillName'];	
	}

	echo json_encode($skills);
} // end of getSkillSuggestions

/***************************************************************
GET HEADER SUGGESTIONS
This method can be used to populate header autosuggest fields. 
***************************************************************/
if ($ajaxAction == 'getHeaderSuggestions' && isset($_GET['term'])) {
	$headers = array();
	
	$headerObj = new Header();
	$headerResult = $headerObj->getHeaderSuggestions($_GET['term']);
	
	while ($headerRow = $headerResult->fetch_assoc()) {
		$headers[] = $headerRow['headerName'];	
	}

	echo json_encode($headers);
} // end of getHeaderSuggestions

/***************************************************************
GET SPELL SUGGESTIONS
This method can be used to populate spell autosuggest fields. 
***************************************************************/
if ($ajaxAction == 'getSpellSuggestions' && isset($_GET['term'])) {
	$spells = array();
	
	$spellObj = new Spell();
	$spellResult = $spellObj->getSpellSuggestions($_GET['term']);
	
	while ($spellRow = $spellResult->fetch_assoc()) {
		$spells[] = $spellRow['spellName'];	
	}

	echo json_encode($spells);
} // end of getSpellSuggestions

/***************************************************************
GET FEAT SUGGESTIONS
This method can be used to populate feat autosuggest fields. 
***************************************************************/
if ($ajaxAction == 'getFeatSuggestions' && isset($_GET['term'])) {
  $feats = array();
  
  $featObj = new Feat();
  $featResult = $featObj->getFeatSuggestions($_GET['term']);
  
  while ($featRow = $featResult->fetch_assoc()) {
    $feats[] = $featRow['featName']; 
  }

  echo json_encode($feats);
} // end of getFeatSuggestions

?>