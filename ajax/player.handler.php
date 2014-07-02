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

/*****************************************************************
GET PLAYER SUGGESTIONS
This method can be used to populate autosuggest fields. 
******************************************************************/
if ($ajaxAction == 'getPlayerSuggestions' && isset($_GET['term'])) {
	$players = array();
	
	$playerObj = new Player();
	$playerResult = $playerObj->getPlayerSuggestions($_GET['term']);
	
	while ($playerRow = $playerResult->fetch_assoc()) {
		$players[] = $playerRow['fullName'];	
	}

	echo json_encode($players);
} // end of getPlayerSuggestions

/***************************************************************
GET STAFF SUGGESTIONS
This method can be used to populate staff autosuggest fields. 
***************************************************************/
if ($ajaxAction == 'getStaffSuggestions' && isset($_GET['term'])) {
	$staff = array();
	
	$staffObj = new Player();
	$staffResult = $staffObj->getStaffSuggestions($_GET['term']);
	
	while ($staffRow = $staffResult->fetch_assoc()) {
		$staff[] = $staffRow['fullName'];	
	}

	echo json_encode($staff);
} // end of getStaffSuggestions

/***************************************************************
LOAD PLAYER DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadPlayerDeleteDialog' && isset($_POST['playerID'])) {
	$allowPlayerDelete = 'no';
	$player = new Player();
	$playerChars = $player->getCharactersForPlayer($_POST['playerID']);
	
	if ($playerChars->num_rows > 0) {
?>
<p>This player cannot be deleted because it has the following characters associated with it: </p>
<ul>

<?php
	while ($charRow = $playerChars->fetch_assoc()) {
?>
<li><?php echo $charRow['charName']; ?></li>

<?php
	} // end characters loop
?>
</ul>

<p>Please delete the characters or transfer them to another player and then try again. </p>

<?php
	} else {
	  $allowPlayerDelete = 'yes';
	  $playerDetails = $player->getPlayerProfile($_POST['playerID']);
	  while ($row = $playerDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following player?</p>
      <p class="playerName"><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></p>
      <p>You will be able to restore the player from the trash if necessary.</p>
      <input type="hidden" name="deletePlayerID" id="deletePlayerID" value="<?php echo $_POST['playerID']; ?>" />
	
<?php
	  } // end of playerDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowPlayerDelete" id="allowPlayerDelete" value="<?php echo $allowPlayerDelete; ?>" />
       
<?php
} // end loadPlayerDelete

/***************************************************************
DELETE PLAYER
***************************************************************/

if ($ajaxAction == 'deletePlayer' && isset($_POST['playerID'])) {
	$player = new Player();
	
	if ($player->deletePlayer($_POST['playerID'])) {
		$playerDetails = $player->getPlayerProfile($_POST['playerID']);
		
		while ($row = $playerDetails->fetch_assoc()) {
		  $html = array();
		  $html['firstName'] = htmlentities($row['firstName']);
		  $html['lastName'] = htmlentities($row['lastName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Player Deleted Successfully',
													'<p>The player "' . $html['firstName'] . ' ' . $html['lastName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of playerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Player',
												'<p>The player could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
cg_clearUIMessage();
} // end of deletePlayer

/***************************************************************
UNDELETE PLAYER
***************************************************************/

if ($ajaxAction == 'undeletePlayer' && isset($_POST['playerID'])) {
	$player = new Player();
	
	if ($player->undeletePlayer($_POST['playerID'])) {
		$playerDetails = $player->getPlayerProfile($_POST['playerID']);
		
		while ($row = $playerDetails->fetch_assoc()) {
		  $html = array();
		  $html['firstName'] = htmlentities($row['firstName']);
		  $html['lastName'] = htmlentities($row['lastName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Player Undeleted Successfully',
													'<p>The player "' . $html['firstName'] . ' ' . $html['lastName'] . '" has been restored from the trash. You can view it from the <a href="players.php" title="Go to players page">Players</a> page.</p>');
												
		} // end of playerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Player',
												'<p>The player could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
cg_clearUIMessage();
} // end of undeletePlayer

/***************************************************************
LOAD PLAYER PURGE DIALOG
***************************************************************/

if ($ajaxAction == 'loadPlayerPurgeDialog' && isset($_POST['playerID'])) {
	$player = new Player();
	$playerDetails = $player->getPlayerProfile($_POST['playerID']);
	while ($row = $playerDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following player?</p>
      <p class="playerName"><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></p>
      <p>All characters that belong to this player (and their associated data, including CP records) will be deleted. </p>
      <p>You will not be able to undo this action.</p>
      <input type="hidden" name="purgePlayerID" id="purgePlayerID" value="<?php echo $_POST['playerID']; ?>" />
	
<?php
	} // end of playerDetails loop
} // end loadPlayerPurgeDialog

/***************************************************************
PURGE PLAYER
***************************************************************/

if ($ajaxAction == 'purgePlayer' && isset($_POST['playerID'])) {
	$player = new Player();
	
	$playerDetails = $player->getPlayerProfile($_POST['playerID']);

	if ($player->purgePlayer($_POST['playerID'])) {
		
		while ($row = $playerDetails->fetch_assoc()) {
		  $html = array();
		  $html['firstName'] = htmlentities($row['firstName']);
		  $html['lastName'] = htmlentities($row['lastName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Player Purged Successfully',
													'<p>The player "' . $html['firstName'] . ' ' . $html['lastName'] . '" has been permanently deleted.</p>');
												
		} // end of playerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Player',
												'<p>The player could not be purged. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgePlayer

/***************************************************************
LOAD PLAYER APPROVE DIALOG
***************************************************************/

if ($ajaxAction == 'loadPlayerApproveDialog' && isset($_POST['playerID'])) {
	$player = new Player();
	$playerDetails = $player->getPlayerProfile($_POST['playerID']);
	while ($row = $playerDetails->fetch_assoc()) {
?>
      <p>Are you sure you want to approve the following request for access?</p>
      <p class="playerName"><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></p>
      <p>The player will be sent a confirmation email and will be allowed to log in. </p>
      <input type="hidden" name="approvePlayerID" id="approvePlayerID" value="<?php echo $_POST['playerID']; ?>" />
	
<?php
	} // end of playerDetails loop
} // end loadPlayerApproveDialog

/***************************************************************
APPROVE PLAYER
***************************************************************/

if ($ajaxAction == 'approvePlayer' && isset($_POST['playerID'])) {
	$player = new Player();
	
	$player->approvePlayer($_POST['playerID']);

	cg_showUIMessage();
	cg_clearUIMessage();
} // end of approvePlayer

/***************************************************************
LOAD APPROVE MULTIPLE PLAYERS DIALOG
***************************************************************/

if ($ajaxAction == 'loadPlayerApproveMultiDialog' && isset($_POST['playerIDArr'])) {
	$playerIDList = implode (',', $_POST['playerIDArr']);
	$player = new Player();
	$playerList = $player->getSelectedPlayers($playerIDList);
?>
      <p>Are you sure you want to approve the following requests for access?</p>
      <ul class="playerList">
      	<?php
			while ($row = $playerList->fetch_assoc()) {
		?>
      	<li><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></li>
        <?php
			}
		?>
      </ul>
      <p>The players will be sent confirmation emails and will be allowed to log in. </p>
      <input type="hidden" name="approvedPlayers" id="approvedPlayers" value="<?php echo $playerIDList; ?>" />
	
<?php
} // end loadPlayerApproveMultiDialog

/***************************************************************
APPROVE MULTIPLE PLAYERS
***************************************************************/

if ($ajaxAction == 'approveMultiplePlayers' && isset($_POST['playerIDList'])) {
	$player = new Player();
	
	$player->approveMultiplePlayers($_POST['playerIDList']);

	cg_showUIMessage();
	cg_clearUIMessage();
} // end of approveMultiplePlayers

/***************************************************************
LOAD PLAYER REJECT DIALOG
***************************************************************/

if ($ajaxAction == 'loadPlayerRejectDialog' && isset($_POST['playerID'])) {
	$player = new Player();
	$playerDetails = $player->getPlayerProfile($_POST['playerID']);
	while ($row = $playerDetails->fetch_assoc()) {
?>
      <p>Are you sure you want to reject the following request for access to the Character Generator?</p>
      <p class="playerData">
      	<?php echo '<strong>' . $row['firstName'] . ' ' . $row['lastName'] . '</strong> (' . $row['email'] . ')'; ?> <br />
      </p>
      <p>The player record will be deleted and the user will not be able to log in. You can permanently delete the player from the Trash. </p>
      <input type="hidden" name="rejectPlayerID" id="rejectPlayerID" value="<?php echo $_POST['playerID']; ?>" />
	
<?php
	} // end of playerDetails loop
} // end loadPlayerRejectDialog

/***************************************************************
REJECT PLAYER
***************************************************************/

if ($ajaxAction == 'rejectPlayer' && isset($_POST['playerID'])) {
	$player = new Player();
	
	$player->rejectPlayer($_POST['playerID']);

	cg_showUIMessage();
	cg_clearUIMessage();
} // end of rejectPlayer

/***************************************************************
LOAD REJECT MULTIPLE PLAYERS DIALOG
***************************************************************/

if ($ajaxAction == 'loadPlayerRejectMultiDialog' && isset($_POST['playerIDArr'])) {
	$playerIDList = implode (',', $_POST['playerIDArr']);
	$player = new Player();
	$playerList = $player->getSelectedPlayers($playerIDList);
?>
      <p>Are you sure you want to reject the following requests for access?</p>
      <ul class="playerList">
      	<?php
			while ($row = $playerList->fetch_assoc()) {
		?>
      	<li><?php echo $row['firstName'] . ' ' . $row['lastName'] . '(' . $row['email'] . ')'; ?></li>
        <?php
			}
		?>
      </ul>
      <p>The players will be deleted and will not be allowed to log in. </p>
      <input type="hidden" name="rejectedPlayers" id="rejectedPlayers" value="<?php echo $playerIDList; ?>" />
	
<?php
} // end loadPlayerRejectMultiDialog

/***************************************************************
REJECT MULTIPLE PLAYERS
***************************************************************/

if ($ajaxAction == 'rejectMultiplePlayers' && isset($_POST['playerIDList'])) {
	$player = new Player();
	
	$player->rejectMultiplePlayers($_POST['playerIDList']);

	cg_showUIMessage();
	cg_clearUIMessage();
} // end of rejectMultiplePlayers

/***************************************************************
GET PENDING PLAYERS
***************************************************************/
if ($ajaxAction == 'getPendingUsers') {
	
	$playerObj = new Player();
	
	$playerResults = $playerObj->getPendingUsers();
	
	$rowIndex = 1;
	while ($player = $playerResults->fetch_assoc()) { // Loop through retrieved players
		if ($rowIndex % 2 == 0) {
			$rowClass = 'odd';
		} else {
			$rowClass = 'even';
		}
        
        ?>
      <tr class="<?php echo $rowClass; ?>"> 
        <td class="chkboxCol"><input type="checkbox" id="<?php echo 'playerID_' . $player['playerID']; ?>" name="playerID[]" value="<?php echo $player['playerID']; ?>" /></td>
        <td class="nameCol"><a href="playerDetails.php?playerID=<?php echo $player['playerID']; ?>"><?php echo $player['firstName'] . ' ' . $player['lastName']; ?></a></td>
        <td class="emailCol"><a href="mailto:<?php echo $player['email']; ?>"><?php echo $player['email']; ?></a></td>
        <td class="actionCol">
            <div class="actionsContainer">
              <a href="#" title="Pending user actions" class="actionsLink">Actions</a>
              <div class="menu" style="display:none">
                  <ul>
                      <li><a href="#" class="approveUserLink">Approve User</a></li>
                      <li><a href="#" class="rejectUserLink">Reject User</a></li>
                  </ul>
              </div>
            </div><!--.actionsContainer-->
        </td>
      </tr>
      <?php 
           $rowIndex++;
        } // end loop through characters
		
		if ($playerResults->num_rows == 0) {
		  echo '<tr class="odd"><td colspan="4"><p class="noResults">There are currently no pending requests.</p></td></tr>';  
		}
	
} // end of getPendingUsers

?>