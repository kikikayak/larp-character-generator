<?php 
/************************************************************************
NAME: 	player.class.php
NOTES:	This file holds all the classes for adding and maintaining players. 
*************************************************************************/

// load configuration
// require_once('../includes/config.php');

class Player {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	/*******************************************************
	GET CHARACTERS FOR PLAYER
	********************************************************/
	public function getCharactersForPlayer($playerID) {
		$log = new Log();

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($playerID, $this->dbh);

		$query = 	"SELECT * 
					FROM characters c 
					WHERE c.charDeleted IS NULL 
					AND c.playerID = " . $mysql['playerID'] . " 
					ORDER BY c.charName";
		
		if ($result = $this->dbh->query($query)) {
			return $result; // Return result 
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Player', 'getCharactersForPlayer', 'Error');
		}
		return false;
	} // end of getCharactersForPlayer
	
	/*******************************************************
	GET PLAYER PROFILE
	********************************************************/
	public function getPlayerProfile($playerID) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		
		$query = 	"SELECT p.playerID, p.firstName, p.lastName, p.email, p.password, p.userRole, p.requestAccessReason  
					FROM players p
					WHERE p.playerID = " . $mysql['playerID'];
				
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$_SESSION['UIMessage'] = new UIMessage(	'error', 
													'Profile Not Found',
													'<p>We were unable to retrieve a profile for you.</p>', array());
			return false;
		}
	} // end of getPlayerProfile
	
	/*******************************************************
	GET RECENT LOG ENTRIES
	Get recent log entries for a given player. 
	********************************************************/
	public function getRecentLogEntries($playerID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($playerID, $this->dbh);

		$query = 	'SELECT l.logID, l.logTimestamp, l.logMessage, l.playerID 
					FROM log l
					WHERE l.playerID = ' . $mysql['playerID'] .
					' ORDER BY l.logTimestamp DESC
					LIMIT 5';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	/*******************************************************
	GET ALL PLAYERS
	********************************************************/
	public function getAllPlayers() {
		$query = 	"SELECT p.playerID, p.firstName, p.lastName, p.email, p.userRole
					FROM players p 
					WHERE p.playerDeleted IS NULL
					AND p.userStatus = 'active'
					ORDER BY p.firstName, p.lastName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	/*******************************************************
	GET SELECTED PLAYERS
	********************************************************/
	public function getSelectedPlayers($playerIDList) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['playerIDList'] = db_escape($playerIDList, $this->dbh);
		
		$query = 	"SELECT p.playerID, p.firstName, p.lastName, p.email, p.password, p.userRole 
					FROM players p
					WHERE p.playerID IN (" . $mysql['playerIDList'] . ")
					ORDER BY p.firstName, p.lastName";
				
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$_SESSION['UIMessage'] = new UIMessage(	'error', 
													'Players Not Found',
													'<p>We were unable to find the selected players.</p>', array());
			return false;
		}
	} // end of getSelectedPlayers
	
	/*******************************************************
	GET DELETED PLAYERS
	********************************************************/
	public function getDeletedPlayers() {
		$query = 	'SELECT p.playerID, p.firstName, p.lastName, p.email, p.userRole
					FROM players p 
					WHERE p.playerDeleted IS NOT NULL 
					ORDER BY p.firstName, p.lastName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	/*******************************************************
	GET ALL STAFF MEMBERS
	Get all players with a role of "staff"
	********************************************************/
	public function getAllStaff() {
		$query = 	"SELECT p.playerID, p.firstName, p.lastName, p.email, p.userRole
					FROM players p
					WHERE p.userRole = 'Admin'
					OR p.userRole = 'Staff'
					ORDER BY p.firstName, p.lastName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	/*******************************************************
	GET PENDING USERS
	Get all player records with a status of "pending." This
	occurs when a user fills out the Request Access form. 
	********************************************************/
	public function getPendingUsers() {
		$query = 	"SELECT p.playerID, p.firstName, p.lastName, p.email, p.requestAccessReason 
					FROM players p
					WHERE p.userStatus = 'pending' 
					AND p.playerDeleted IS NULL 
					ORDER BY p.firstName, p.lastName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	/*******************************************************
	GET TOTAL PENDING USERS
	Return a total number of users with a status of pending. 
	Used for reporting. 
	********************************************************/
	public function getTotalPendingUsers() {
		$query = 	"SELECT p.playerID
					FROM players p
					WHERE p.userStatus = 'pending' 
					AND p.playerDeleted IS NULL";
		
		if ($result = $this->dbh->query($query)) {
			return $result->num_rows;
		}
		return false;
	}
	
	/*******************************************************
	GET PLAYER SUGGESTIONS
	Retrieve a list of players matching a search term. 
	Used to populate autosuggest fields. 
	********************************************************/
	public function getPlayerSuggestions($term) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['term'] = db_escape($term, $this->dbh);
		
		$query = 	"SELECT DISTINCT CONCAT(p.firstName, ' ', p.lastName) AS fullName
					FROM players p
					WHERE p.playerDeleted IS NULL
					AND CONCAT(p.firstName, ' ', p.lastName) LIKE '%" . $mysql['term'] . "%'  
					ORDER BY fullName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	/*******************************************************
	GET STAFF SUGGESTIONS
	Retrieve a list of staff members matching a search term. 
	Used to populate autosuggest fields. 
	********************************************************/
	public function getStaffSuggestions($term) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['term'] = db_escape($term, $this->dbh);
		
		$query = 	"SELECT p.playerID, CONCAT(p.firstName, ' ', p.lastName) AS fullName, p.email, p.userRole
					FROM players p
					WHERE (p.userRole = 'Admin'
					OR p.userRole = 'Staff')
					AND p.playerDeleted IS NULL
					AND CONCAT(p.firstName, ' ', p.lastName) LIKE '%" . $mysql['term'] . "%'  
					ORDER BY fullName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	/*******************************************************
	DELETE PLAYER
	Perform a "logical delete" of a player record, which sends
	it to the Trash. This sets the deleted flag in the DB, 
	but does not actually remove the player's row in the DB.  
	********************************************************/
	// Perform a logical delete of a player
	public function deletePlayer($playerID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($playerID, $this->dbh);

		$query =	"UPDATE players p 
					SET p.playerDeleted = NOW() 
					WHERE p.playerID = " . $mysql['playerID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	/*******************************************************
	UNDELETE PLAYER
	Perform a "logical undelete" of a player, which removes it
	from the Trash. This method unsets the deleted flag in the DB. 
	********************************************************/
	// Perform a logical undelete of a player
	public function undeletePlayer($playerID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($playerID, $this->dbh);

		$query =	"UPDATE players p 
					SET p.playerDeleted = NULL 
					WHERE p.playerID = " . $mysql['playerID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	/*******************************************************
	PURGE PLAYER
	Permanently delete a player by removing its row from the DB.
	This method also removes all dependent data: characters, 
	character skills, CP records, etc. Purging results in
	permanent loss of DB data. 
	********************************************************/
	public function purgePlayer($playerID) {
		// TODO: Need to wrap all of this in a transaction so can roll back
		// if any of the deletes fail. 

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		
		$logMsg = ''; // Initialize blank
				
		/************* DELETE DEPENDENT CHARACTERS **************/
		$playerChars = $this->getCharactersForPlayer($playerID);
		
		// Loop through characters and delete each one (and associated data)
		while ($charRow = $playerChars->fetch_assoc()) {
		  // Delete headers for this character
		  $deleteCharHeaders = "DELETE FROM charheaders
								WHERE charheaders.characterID = " . $charRow['characterID'];
								
		  if (!$deleteCharHeadersResult = $this->dbh->query($deleteCharHeaders)) {
			  $logMsg .= 'Failed to delete headers for character ID ' . $charRow['characterID']  . '. ';
		  }
		  
		  // Delete skills for this character
		  $deleteCharSkills = "DELETE FROM charskills
							   WHERE charskills.characterID = " . $charRow['characterID'];
							   
		  if (!$deleteCharSkillsResult = $this->dbh->query($deleteCharSkills)) {
			  $logMsg .= 'Failed to delete skills for character ID ' . $charRow['characterID']  . '. ';
		  }
		  
		  // Delete spells for this character
		  $deleteCharSpells = "DELETE FROM charspells
							   WHERE charspells.characterID = " . $charRow['characterID'];
							   
		  if (!$deleteCharSpellsResult = $this->dbh->query($deleteCharSpells)) {
			  $logMsg .= 'Failed to delete spells for character ID ' . $charRow['characterID']  . '. ';
		  }
		  
		  // Delete traits for this character
		  $deleteCharTraits = "DELETE FROM chartraits
							   WHERE chartraits.characterID = " . $charRow['characterID'];
							   
		  if (!$deleteCharTraitsResult = $this->dbh->query($deleteCharTraits)) {
			  $logMsg .= 'Failed to delete traits for character ID ' . $charRow['characterID'] . '. ';
		  }	
		  
		  // Delete hidden header access for this character
		  $deleteCharHiddenHeaders = "DELETE FROM hiddenheadersaccess
									  WHERE hiddenheadersaccess.characterID = " . $charRow['characterID'];
									  
		  if (!$deleteCharHiddenHeadersResult = $this->dbh->query($deleteCharHiddenHeaders)) {
			  $logMsg .= 'Failed to delete hidden header access for character ID ' . $charRow['characterID'] . '. ';
		  }
		  
		  // Delete hidden skill access for this character
		  $deleteCharHiddenSkills = "DELETE FROM hiddenskillsaccess
									  WHERE hiddenskillsaccess.characterID = " . $charRow['characterID'];
									  
		  if (!$deleteCharHiddenSkillsResult = $this->dbh->query($deleteCharHiddenSkills)) {
			  $logMsg .= 'Failed to delete hidden skill access for character ID ' . $charRow['characterID'] . '. ';
		  }	
		  
		  // Delete CP records for this character
		  $deleteCharCP = "DELETE FROM cp
						  WHERE cp.characterID = " . $charRow['characterID'];
						  
		  if (!$deleteCharCPResult = $this->dbh->query($deleteCharCP)) {
			  $logMsg .= 'Failed to delete CP records for character ID ' . $charRow['characterID'] . '. ';
		  }	
		  
		  // Only delete character if log message is still blank 
		  // (which means there have been no errors). 
		  if ($logMsg == '') {
		  
			$deleteCharQuery =  "DELETE FROM characters
								WHERE characters.characterID = " . $charRow['characterID'];
						
			if ($deleteCharResult = $this->dbh->query($deleteCharQuery)) {
				$logMsg .= 'Successfully deleted character ID ' . $charRow['characterID'] . '. ';
				// $logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'deleteCharacter'
			}
		  } else {
			  // echo $logMsg . '<br />';
			  $log = new Log();
			  $log->addLogEntry($logMsg, $_SESSION['playerID'], $playerID, $charRow['characterID'], 'Player', 'purgePlayer');	
		  }
		} // end of characters loop
		
		// Delete player CP
		$deletePlayerCP = 	"DELETE FROM cp
							WHERE cp.playerID = " . $mysql['playerID'];
						
		if (!$deletePlayerCPResult = $this->dbh->query($deletePlayerCP)) {
			$logMsg .= 'Failed to delete CP records for player ID ' . $mysql['playerID'] . '. ';
		}	
		
		// Delete player
		$deletePlayer = "DELETE FROM players
						WHERE players.playerID = " . $mysql['playerID'];
						
		if ($deletePlayerResult = $this->dbh->query($deletePlayer)) {
		  $logMsg .= 'Successfully deleted player ID ' . $mysql['playerID'] . '. ';
		} else {
		  $logMsg .= 'Failed to delete player ID ' . $mysql['playerID'] . '. ';	
		}
		
		if ($logMsg != '') {
		  $log = new Log();
		  $log->addLogEntry($logMsg, $_SESSION['playerID'], $mysql['playerID'], '', 'Player', 'purgePlayer');
		}
		
		return true;
	} // end of purgePlayer
	
	/*******************************************************
	ADD PLAYER
	********************************************************/
	// $player: Associative array of player data
	public function addPlayer($player) {
		
		// Validate data.
		$validator = new Validator();
		if ($validator->validatePlayer($player) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['firstName'] = db_escape($player['firstName'], $this->dbh);
		$mysql['lastName'] = db_escape($player['lastName'], $this->dbh);
		$mysql['email'] = db_escape($player['email'], $this->dbh);
		$mysql['password'] = db_escape($player['newPassword'], $this->dbh);
		$mysql['userRole'] = db_escape($player['userRole'], $this->dbh);
		$mysql['userStatus'] = 'active';
		
		// Escape values for display in UI
		$html = array();
		$html['firstName'] = htmlentities($player['firstName']);
		$html['lastName'] = htmlentities($player['lastName']);
		
		$duplicateQuery = 	"SELECT p.playerID, p.email
							FROM players p
							WHERE p.email = '" . $mysql['email'] . "'";
		
		if ($duplicateResult = $this->dbh->query($duplicateQuery)) {
			if ($duplicateResult->num_rows > 0) {
				$_SESSION['UIMessage'] = new UIMessage(	'error', 
													'Duplicate player',
													'<p>The email address you specified already exists in the system.</p>');
				return false;
			}
		}
		
		// Hash password supplied by user for security
		// Salt will be automatically generated by the function
		$mysql['hashPassword'] = generateHash($player['newPassword']);
		
		// Insert player
		$query = 	"INSERT INTO players (firstName, lastName, email, password, userRole, userStatus)
					VALUES ('" . 
					$mysql['firstName'] . "','" . 
					$mysql['lastName'] . "','" . 
					$mysql['email'] . "','" . 
					$mysql['hashPassword'] . "','" .
					$mysql['userRole'] . "','" .
					$mysql['userStatus'] . "')";
		
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($playerInsertResult = $this->dbh->query($query)) {
			
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Player added successfully',
													'<p>The player ' . $html['firstName'] . ' ' . $html['lastName'] . ' was added successfully.</p>');
						
			if (isset($player['sendUserEmail']) && $player['sendUserEmail'] == 'Yes') {
				$this->sendLoginEmail($player);	
			}
			
			return true;
		} else {
			return false;
		}

	} // end of addPlayer method
	
	/*******************************************************
	REQUEST LOGIN
	********************************************************/
	// $player: Associative array of player data
	public function requestLogin($player) {
		
		// Validate data.
		$validator = new Validator();
		if ($validator->validateRequestLogin($player) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['firstName'] = db_escape($player['firstName'], $this->dbh);
		$mysql['lastName'] = db_escape($player['lastName'], $this->dbh);
		$mysql['email'] = db_escape($player['email'], $this->dbh);
		$mysql['password'] = db_escape($player['password'], $this->dbh);
		$mysql['requestAccessReason'] = db_escape($player['requestAccessReason'], $this->dbh);
		$mysql['userRole'] = 'User';
		$mysql['userStatus'] = 'pending';
		
		// Escape values for display in UI
		$html = array();
		$html['firstName'] = htmlentities($player['firstName']);
		$html['lastName'] = htmlentities($player['lastName']);
		
		$duplicateQuery = 	"SELECT p.playerID, p.email
							FROM players p
							WHERE p.email = '" . $mysql['email'] . "'";
		
		if ($duplicateResult = $this->dbh->query($duplicateQuery)) {
			if ($duplicateResult->num_rows > 0) {
				$errorList = array();
				$errorList['email']['fldLbl'] = 'Email';
				$errorList['email']['error'] = 'Please enter a unique email address';
				$_SESSION['UIMessage'] = new UIMessage(	'error', 
														'Email Address Already Registered',
														'<p>The email address you specified already exists in the system.</p> 
														 <p>If you have forgotten your password, you can <a href="lostPassword.php">reset your password</a>.</p>', $errorList);
				return false;
			}
		}
		
		// Hash password supplied by user for security
		// Salt will be automatically generated by the function
		$mysql['hashPassword'] = generateHash($player['password']);
		
		// Insert player
		$query = 	"INSERT INTO players (firstName, lastName, email, password, userRole, userStatus, requestAccessReason)
					VALUES ('" . 
					$mysql['firstName'] . "','" . 
					$mysql['lastName'] . "','" . 
					$mysql['email'] . "','" . 
					$mysql['hashPassword'] . "','" .
					$mysql['userRole'] . "','" .
					$mysql['userStatus'] . "','" .
					$mysql['requestAccessReason'] . "')";
		
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($playerInsertResult = $this->dbh->query($query)) {
			
			if ($_SESSION['autoGrantAccess'] == 0) { // Staff needs to approve the login request
				
				$this->sendLoginRequestEmail($player);
				
				// Display success message at top of page. 
				$_SESSION['UIMessage'] = new UIMessage(	'success', 
														'Request Submitted',
														'<p>Your request for access has been sent to the game staff. You will receive an email once your access is approved. </p>');
				return true;
			} else { // Automatically approve player
				
				// Find player ID of last-inserted player so we can use it to approve access
				$lastPlayerQuery = 'SELECT MAX(playerID) AS lastPlayerID FROM players';
				if ($lastPlayerResult = $this->dbh->query($lastPlayerQuery)) {
					while ($lastPlayer = $lastPlayerResult->fetch_assoc()) {
						$lastPlayerID = $lastPlayer['lastPlayerID'];
					}
				}

				// Approve player
				$this->approvePlayer($lastPlayerID);

				// Display a different success message
				$_SESSION['UIMessage'] = new UIMessage(	'success', 
														'Login Created',
														'<p>Your request for access has been granted. You can now log in using your email address and the password you selected. </p>');
				return true;
			}
		} else {
			return false;
		}

	} // end of requestLogin method
	
	/*******************************************************
	SEND LOGIN REQUEST EMAIL
	********************************************************/
	public function sendLoginRequestEmail($player) {
	  $html = array();
	  $html['firstName'] = htmlentities($player['firstName']);
	  $html['lastName'] = htmlentities($player['lastName']);
	  $html['email'] = htmlentities($player['email']);
	  
	  $to = htmlentities($_SESSION['contactEmail']);
	  $totalPendingRequests = $this->getTotalPendingUsers();
	  
	  // subject
	  $subject = $_SESSION['campaignName'] . ' Character Generator access request';
		  
	  // message
	  $msg = '
	  <html>
	  <head>
		<title>Character Generator access request</title>
	  </head>
	  <body>
		<p>You have received a new request for access in the ' . $_SESSION['campaignName'] . ' Character Generator:';
	  $msg .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Name: ' . $html['firstName'] . ' ' . $html['lastName'] . '<br />';
	  $msg .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Email: ' . $html['email'] . '<br />';
	  $msg .= '<p>You have <strong>' . $totalPendingRequests . ' </strong> total pending requests for access. You can approve or reject requests from the Home page of the admin section of the Generator:</p>';
	  $msg .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $_SESSION['generatorLocation'] . '</p>';
	  $msg .= '
	  </body>
	  </html>
	  ';
	  
	  // DEBUG
	  // echo $msg;
	  
	  // To send HTML mail, the Content-type header must be set
	  $headers  = 'MIME-Version: 1.0' . "\r\n";
	  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  
	  // Additional headers
	  $headers .= 'From: ' . $_SESSION['campaignName'] . ' Character Generator <' . $_SESSION['contactEmail'] . '>' . "\r\n";
	  
	  // Send email
	  mail($to, $subject, $msg, $headers);
	}
	
	/*******************************************************
	APPROVE MULTIPLE PLAYERS
	********************************************************/
	public function approveMultiplePlayers($playerIDList) {

		$playerIDArr = explode (',', $playerIDList);
		$errors = 0; // counts number of approvals that failed
		
		foreach ($playerIDArr as $playerID) {
			// Call approval method for each playerID
			if (!$this->approvePlayer($playerID)) {
				$errors++; // If failed, increment error counter
			}
		}
		
		if ($errors == 0) {
		  // If successful, set success message		  
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Players Approved',
													'<p>All of the selected players have been approved. They will be sent confirmation emails and will be allowed to log in.</p>');
		  return true;
		} else {
		  $_SESSION['UIMessage'] = new UIMessage( 'error', 
												  'Failed to Approve Some Players',
												  '<p>' . $errors . ' players could not be approved. Please try again. </p>');	
		  return false;
		}
	} // end approveMultiplePlayers
	
	/*******************************************************
	APPROVE PLAYER
	********************************************************/
	public function approvePlayer($playerID) {
		
		$mysql = array(); // Initialize to blank for storing DB-safe values
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		
		$playerDetails = $this->getPlayerProfile($mysql['playerID']);
		
		while ($row = $playerDetails->fetch_assoc()) {
		  $html = array();
		  $html['firstName'] = htmlentities($row['firstName']);
		  $html['lastName'] = htmlentities($row['lastName']);
		  $html['email'] = htmlentities($row['email']);
		} // end of playerDetails loop

		$updateQuery =  "UPDATE players p " .
						"SET p.userStatus = 'active' 
						WHERE p.playerID = " . $mysql['playerID'];

		if ($updateResult = $this->dbh->query($updateQuery)) {
		  // If successful, send confirmation email to player and set success message
		  $this->sendApprovalEmail($html);
		  
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Player Approved Successfully',
													'<p>The player "' . $html['firstName'] . ' ' . $html['lastName'] . '" has been approved and will be allowed to log in.</p>');
			return true;
		}
		
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Approve Player',
												'<p>The player could not be approved. Please try again. </p>');	
		return false;
	} // end approvePlayer
	
	/*******************************************************
	SEND APPROVAL EMAIL
	********************************************************/
	public function sendApprovalEmail($player) {
		
	  $html = array();
	  $html['firstName'] = htmlentities($player['firstName']);
	  $html['email'] = htmlentities($player['email']);
	  
	  $to = $html['email'];
	  
	  // subject
	  $subject = $_SESSION['campaignName'] . ' Character Generator login information';
		  
	  // message
	  $msg = '
	  <html>
	  <head>
		<title>Character Generator login information</title>
	  </head>
	  <body>
		<p>Dear ' . $html['firstName'] . ',</p>
		<p>You have been approved for access to the ' . $_SESSION['campaignName'] . ' Character Generator. The Character Generator will allow you to create and update a character and track your CP assignments. </p>';
	  $msg .= '<p>Your login is your email address: <strong>' . $html['email'] . '</strong>';
	  $msg .= '<p>Use the password you entered when you requested access. If you have forgotten your password, you can <a href="lostPassword.php">reset your password.</a></p>';
	  $msg .= '<p>You can log in at the following URL:</p>';
	  $msg .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $_SESSION['generatorLocation'] . '</p>';
	  $msg .= '<p>Thanks!</p>';
	  $msg .= '<p>' . $_SESSION['campaignName'] . ' Staff</p>';
	  
	  $msg .= '
	  </body>
	  </html>
	  ';
	  
	  // DEBUG
	  // echo $msg;
	  
	  // To send HTML mail, the Content-type header must be set
	  $headers  = 'MIME-Version: 1.0' . "\r\n";
	  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  
	  // Additional headers
	  $headers .= 'From: ' . $_SESSION['campaignName'] . ' Staff <' . $_SESSION['contactEmail'] . '>' . "\r\n";
	  
	  // Send email
	  mail($to, $subject, $msg, $headers);
		
	} // end of sendApprovalEmail
	
	/*******************************************************
	REJECT MULTIPLE PLAYERS
	********************************************************/
	public function rejectMultiplePlayers($playerIDList) {
		$playerIDArr = explode (',', $playerIDList);
		$errors = 0; // counts number of rejections that failed
		
		foreach ($playerIDArr as $playerID) {
			// Call reject method for each playerID
			if (!$this->rejectPlayer($playerID)) {
				$errors++; // If failed, increment error counter
			}
		}
		
		if ($errors == 0) {
		  // If successful, set success message		  
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Players Rejected',
													'<p>All of the selected requests for access have been rejected. The users will not be allowed to log in.</p>
													<p>You can permanently delete the player records from the <a href="trash.php">trash</a> if necessary.</p>');
		  return true;
		} else {
		  $_SESSION['UIMessage'] = new UIMessage( 'error', 
												  'Failed to Reject Some Players',
												  '<p>' . $errors . ' players could not be rejected. Please try again. </p>');	
		  return false;
		}
	} // end rejectMultiplePlayers
	
	/*******************************************************
	REJECT PLAYER
	********************************************************/
	public function rejectPlayer($playerID) {
		
		$mysql = array(); // Initialize to blank for storing DB-safe values
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		
		$playerDetails = $this->getPlayerProfile($mysql['playerID']);
		
		while ($row = $playerDetails->fetch_assoc()) {
		  $html = array();
		  $html['firstName'] = htmlentities($row['firstName']);
		  $html['lastName'] = htmlentities($row['lastName']);
		  $html['email'] = htmlentities($row['email']);
		} // end of playerDetails loop

		$updateQuery =  "UPDATE players p 
						SET p.playerDeleted = NOW() 
						WHERE p.playerID = " . $mysql['playerID'];

		if ($updateResult = $this->dbh->query($updateQuery)) {
		  // Set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Access Request Rejected',
													'<p>The access request for "' . $html['firstName'] . ' ' . $html['lastName'] . '" has been rejected. The user will not be allowed to log in. You can permanently delete the player record from the <a href="trash.php">trash</a>.</p>');
			return true;
		}
		
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Reject Player',
												'<p>The player could not be rejected. Please try again. </p>');	
		return false;
	} // end rejectPlayer
	
	/*******************************************************
	UPDATE PLAYER PROFILE
	This method is used when a player updates his or her own
	profile. It does not allow changing the user role.  
	********************************************************/
	public function updatePlayerProfile($player) {
		
		$validator = new Validator();
		if ($validator->validatePlayer($player) == false) {
			return false;
		}
		
		$mysql = array(); // Initialize to blank for storing DB-safe values
		$mysql['firstName'] = db_escape($player['firstName'], $this->dbh);
		$mysql['lastName'] = db_escape($player['lastName'], $this->dbh);
		$mysql['email'] = db_escape($player['email'], $this->dbh);
		$mysql['newPassword'] = db_escape($player['newPassword'], $this->dbh);
		$mysql['curPassword'] = db_escape($player['curPassword'], $this->dbh);
		
		// Hash password supplied by user for security
		// Salt will be automatically generated by the function
		$mysql['hashPassword'] = generateHash($player['newPassword']);
		
		$authenticateQuery = 	"SELECT p.playerID, p.password 
								FROM players p
								WHERE p.playerID = " . $_SESSION['playerID'] .  
								" AND p.playerDeleted IS NULL";

		if ($authenticateResult = $this->dbh->query($authenticateQuery)) {

			while ($row = $authenticateResult->fetch_assoc()) {

			  // Generate salted hash of password user entered
			  // Use salt retrieved from password stored in the DB
			  $hashedPassword = generateHash($mysql['curPassword'], $row['password']);
			  
			  // Compare the hashed password retrieved from the DB
			  // to the hashed version of the password the user entered. 
			  if ($row['password'] != $hashedPassword) { // Passwords don't match!
				  $_SESSION['UIMessage'] = new UIMessage('error', 
				  										'Login Information Incorrect', 
														'<p>We could not authenticate you. You may have mistyped your password. Please try again.</p>');
				  return false;
			  }
			}
			
			$updateQuery =  "UPDATE players p " .
							"SET p.firstName = '" . $mysql['firstName'] . "', " .
							"p.lastName = '" . $mysql['lastName'] . "', " .
							"p.email = '" . $mysql['email'] . "', " .
							"p.password = '" . $mysql['hashPassword'] . "' " .
							"WHERE p.playerID = " . $_SESSION['playerID'];
	
			if ($updateResult = $this->dbh->query($updateQuery)) {
				$_SESSION['UIMessage'] = new UIMessage('success', 
													'Thank you!', 
													'<p>Your profile was successfully updated.</p>');
				return true;
			}
		}
		
		$_SESSION['UIMessage'] = new UIMessage('error', 
												'An Error Has Occurred', 
												'<p>Please try again. If you continue to receive this message, contact your administrator.</p>');
		return false;
	} // end updatePlayerProfile

	
	/*******************************************************
	UPDATE PLAYER
	This method is used for updates performed by staff members
	and administrators from the admin section. It allows changing
	the user role. 
	
	TO DO: Consider consolidating the updatePlayer and 
	updatePlayerProfile methods. 
	********************************************************/
	public function updatePlayer($player, $playerID) {
		
		$validator = new Validator();
		if ($validator->validatePlayer($player) == false) {
			return false;
		}
		
		$mysql = array(); // Initialize to blank for storing DB-safe values
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		$mysql['firstName'] = db_escape($player['firstName'], $this->dbh);
		$mysql['lastName'] = db_escape($player['lastName'], $this->dbh);
		$mysql['email'] = db_escape($player['email'], $this->dbh);
		$mysql['newPassword'] = db_escape($player['newPassword'], $this->dbh);
		$mysql['userRole'] = db_escape($player['userRole'], $this->dbh);
		
		// Escape values for display in UI
		$html = array();
		$html['firstName'] = htmlentities($player['firstName']);
		$html['lastName'] = htmlentities($player['lastName']);
		
		$updateQuery = 	"UPDATE players p " .
						"SET p.firstName = '" . $mysql['firstName'] . "', " .
						"p.lastName = '" . $mysql['lastName'] . "', " .
						"p.email = '" . $mysql['email'] . "', ";

		if (!is_empty($mysql['newPassword'])) {
			// Hash password supplied by user for security
			// Salt will be automatically generated by the function
			$mysql['hashPassword'] = generateHash($player['newPassword']);
			$updateQuery .= " p.password = '" . $mysql['hashPassword'] . "', ";
		}
		
		$updateQuery .=	"p.userRole = '" . $mysql['userRole'] . "' " .
						"WHERE p.playerID = " . $mysql['playerID'];

		if ($updateResult = $this->dbh->query($updateQuery)) {
			$_SESSION['UIMessage'] = new UIMessage('success', 
													'Player Updated Successfully', 
													'<p>The player ' . $html['firstName'] . ' ' . $html['lastName'] . ' was updated successfully.</p>');
			return true;
		}
		$_SESSION['UIMessage'] = new UIMessage('error', 
												'An Error Has Occurred', 
												'<p>Please try again. If you continue to receive this message, contact your administrator.</p>');
		return false;
	
	} // end updatePlayer
		
	/*******************************************************
	SEND LOGIN EMAIL
	********************************************************/
	public function sendLoginEmail($player) {
		
	  $html = array();
	  $html['firstName'] = htmlentities($player['firstName']);
	  $html['lastName'] = htmlentities($player['lastName']);
	  $html['email'] = htmlentities($player['email']);
	  $html['password'] = htmlentities($player['newPassword']);
	  
	  $to = $html['email'];
	  
	  // subject
	  $subject = $_SESSION['campaignName'] . ' Character Generator login information';
		  
	  // message
	  $msg = '
	  <html>
	  <head>
		<title>Character Generator login information</title>
	  </head>
	  <body>
		<p>Dear ' . $html['firstName'] . ',</p>
		<p>A user login has been created for you in the ' . $_SESSION['campaignName'] . ' Character Generator. The Character Generator will allow you to create and update a character and track your CP assignments. Here is your login information:';
	  $msg .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Login: ' . $html['email'] . '<br />';
	  $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Password: ' . $html['password'] . '</p>';
	  $msg .= '<p>You can log in at the following URL:</p>';
	  $msg .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $_SESSION['generatorLocation'] . '</p>';
	  $msg .= '<p>Please change your password to something secure and memorable as soon as you log in, using the Profile link at the upper right.</p>';
	  $msg .= '<p>Thanks,</p>';
	  $msg .= '<p>' . $_SESSION['campaignName'] . ' Staff</p>';
	  
	  $msg .= '
	  </body>
	  </html>
	  ';
	  
	  // DEBUG
	  // echo $msg;
	  
	  // To send HTML mail, the Content-type header must be set
	  $headers  = 'MIME-Version: 1.0' . "\r\n";
	  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  
	  // Additional headers
	  $headers .= 'From: ' . $_SESSION['campaignName'] . ' Staff <' . $_SESSION['contactEmail'] . '>' . "\r\n";
	  
	  // Send email
	  mail($to, $subject, $msg, $headers);
		
	} // end of sendLoginEmail
	
	/*******************************************************
	SEND LOST PASSWORD
	********************************************************/
	public function sendLostPassword($email) {
		
		$user = array();
		$user['email'] = $email;
		
		// Validate data.
		$validator = new Validator();
		if ($validator->validateLostPassword($user) == false) {
			return false;
		}
		
		// Escape data for use in DB
		$mysql = array();
		$mysql['email'] = db_escape($email, $this->dbh);
		
		// Escape data for use in UI or email
		$html = array();
		$html['email'] = htmlentities($email);
		
		// Find user in system
		$query = 	"SELECT p.playerID, p.email, p.firstName 
					FROM players p
					WHERE p.email = '" . $mysql['email'] . "'";
					
		if ($result = $this->dbh->query($query)) {
			if ($this->dbh->affected_rows == 1) { // Found the user record
				
				while ($row = $result->fetch_assoc()) {
					$html['firstName'] = $row['firstName'];
				}

				// Generate random alphanumeric temp password
				$tmpPwd = generateTmpPassword();
				
				// Insert tmp password in DB
				$insertQuery = 	"UPDATE players p
								SET p.tmpPassword = '" . $tmpPwd . "' 
								WHERE p.email = '" . $mysql['email'] ."'";
								
				if ($tmpPwdInsertResult = $this->dbh->query($insertQuery)) {
					// If inserting the temp password succeeded,
					// send email to user with temporary password
					$msg = "Dear " . $html['firstName'] . ", \n\n";
					$msg .= "Your " . CAMPAIGN_NAME . " Character Generator password has been reset.\n\n";
					$msg .= "Your temporary password is as follows: \n\n" . $tmpPwd . "\n\n";
					$msg .= "To reset your password, please visit the following Web address and follow the instructions. If clicking the link doesn't work, try cutting and pasting the address into your Web browser's address bar: \n\n";
					$msg .= GENERATOR_LOCATION . "/resetPassword.php?e=" . $html['email'] . "\n\n";
					$msg .= "If you do not wish to reset your password, do nothing. Your password will not be changed. \n\n";
					$msg .= "Thank you,\n\n";
					$msg .= CAMPAIGN_NAME . " Staff";
					
					$subject = "Reset your " . CAMPAIGN_NAME . " Character Generator password";
					
					// TODO: Need to make FROM address a variable
					$headers = 'From: ' . CONTACT_NAME;
					
					mail($html['email'], $subject, $msg, $headers);
					
					// Create success message to display at top of page. 
					$_SESSION['UIMessage'] = new UIMessage(	'success', 
															'Password successfully reset',
															'<p>Please check the email associated with your account for instructions for completing the reset.</p>'); 
				}
				
				return $result;
			} else {
				$_SESSION['UIMessage'] = new UIMessage(	'error', 
														'User Not Found',
														'<p>We could not find that email address in the system.</p>');
				return false;	
			}
		} else {
			echo $mysqli->error;
			return false;
		}
	} // end of sendLostPassword
	
	/*******************************************************
	RESET PASSWORD
	********************************************************/
	public function resetPassword($user) {
		
		$validator = new Validator();
		if ($validator->validatePasswordReset($user) == false) {
			return false;
		}
		
		// Escape data for use with DB
		$mysql = array();
		$mysql['email'] = db_escape($user['email'], $this->dbh);
		$mysql['tmpPassword'] = db_escape($user['tmpPassword'], $this->dbh);
		
		// Make sure there's a user who matches this email and temp password
		$query = 	"SELECT p.playerID 
					FROM players p
					WHERE p.email = '" . $mysql['email'] . "' 
					AND p.tmpPassword = '" . $mysql['tmpPassword'] . "'";
		
		if ($result = $this->dbh->query($query)) {
			if ($this->dbh->affected_rows == 1) {
				// Hash password supplied by user for security
				// Salt will be automatically generated by the function
				$mysql['hashPassword'] = generateHash($user['newPassword']);
				
				// Insert new password
				$updateQuery = 	"UPDATE players p
								SET p.password = '" . $mysql['hashPassword'] . "', 
								p.tmpPassword = NULL 
								WHERE p.email = '" . $mysql['email'] ."'";
				
				if ($pwdInsertResult = $this->dbh->query($updateQuery)) {
					// Create success message to display at top of page. 
					$_SESSION['UIMessage'] = new UIMessage(	'success', 
															'Password successfully reset',
															'<p>Your password has been successfully reset. You can <a href="index.php">log in</a> with your new password now. </p>');
					
					return $result;
				}
				
			} else {
				$_SESSION['UIMessage'] = new UIMessage(	'error', 
													'Reset Failed',
													'<p>Either your email address or temporary password are incorrect.</p>');
				return false;	
			}
		} else {
			echo $mysqli->error;
			return false;	
		}
		return false;
	} // end of resetPassword
	
	
} // end of class