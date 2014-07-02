<?php 
/************************************************************************
NAME: 	cp.class.php
NOTES:	This file holds all the classes for tracking and displaying CP assignments. 
*************************************************************************/

// load configuration
// require_once('../includes/config.php');

class CP {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getRecentCP() {
		$query = 	"SELECT cp.CPTrackID, cp.CPType, cp.CPDateStamp, cp.playerID, cp.numberCP, cp.CPNote, cp.staffMember, 
					p.firstName, p.lastName, cpc.CPCatName, c.charName
					FROM cp 
						LEFT JOIN players p ON cp.playerID = p.playerID
						JOIN cpcategories cpc ON cp.CPCatID = cpc.CPCatID
						LEFT JOIN characters c ON cp.characterID = c.characterID
					WHERE cp.CPDeleted IS NULL
					ORDER BY cp.CPTrackID DESC, cp.CPDateStamp DESC
					LIMIT 100";

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return result 
		}
		return false;
	}
	
	public function getCharCP() {
		$query = 	"SELECT cp.CPTrackID, cp.CPType, cp.CPDateStamp, cp.playerID, cp.numberCP, cp.CPNote, cp.staffMember, 
					p.firstName, p.lastName, cpc.CPCatName, c.charName
					FROM cp 
						LEFT JOIN players p ON cp.playerID = p.playerID
						JOIN cpcategories cpc ON cp.CPCatID = cpc.CPCatID
						LEFT JOIN characters c ON cp.characterID = c.characterID
					WHERE cp.CPDeleted IS NULL
					AND cp.CPType = 'player' 
					ORDER BY cp.CPTrackID DESC, cp.CPDateStamp DESC
					LIMIT 100";

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return result 
		}
		return false;
	}
	
	public function getPlayerCP() {
		$query = 	"SELECT cp.CPTrackID, cp.CPType, cp.CPDateStamp, cp.playerID, cp.numberCP, cp.CPNote, cp.staffMember, 
					p.firstName, p.lastName, cpc.CPCatName, c.charName
					FROM cp 
						LEFT JOIN players p ON cp.playerID = p.playerID
						JOIN cpcategories cpc ON cp.CPCatID = cpc.CPCatID
						LEFT JOIN characters c ON cp.characterID = c.characterID
					WHERE cp.CPDeleted IS NULL
					AND cp.CPType = 'player' 
					ORDER BY cp.CPTrackID DESC, cp.CPDateStamp DESC
					LIMIT 100";

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return query result set
		}
		return false;
	}

	public function getCPByPlayer($playerID) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['playerID'] = db_escape($playerID, $this->dbh);

		$query = 	"SELECT cp.CPTrackID, cp.CPType, cp.CPDateStamp, cp.playerID, cp.numberCP, cp.CPNote, cp.staffMember, 
					p.firstName, p.lastName, cpc.CPCatName, c.charName
					FROM cp 
						LEFT JOIN players p ON cp.playerID = p.playerID
						JOIN cpcategories cpc ON cp.CPCatID = cpc.CPCatID
						LEFT JOIN characters c ON cp.characterID = c.characterID
					WHERE cp.CPDeleted IS NULL
					AND cp.CPType = 'player' 
					AND cp.playerID = " . $mysql['playerID'] . 
					" ORDER BY cp.CPTrackID DESC, cp.CPDateStamp DESC";
	
		// echo $query . '<br />';

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return query result set
		}
		return false;
	}
	
	/*******************************************************
	GET PLAYER CP FOR A PLAYER
	Retrieve a total number of all available playerCP for 
	the specified player. 
	********************************************************/
	public function getTotalPlayerCP($playerID) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		$totalCP = 0;
		
		$query = 	"SELECT SUM(cp.numberCP) as cpTotal
					FROM cp 
					WHERE cp.CPDeleted IS NULL
					AND cp.CPType = 'player'
					AND cp.playerID = " . $mysql['playerID'];

		if ($result = $this->dbh->query($query)) {			
			while ($row = $result->fetch_assoc()) {
				$totalCP =  $row['cpTotal'];
			}
			
		}
		return $totalCP; 
	}
	
	public function getDeletedCP() {
		$query = 	"SELECT cp.CPTrackID, cp.CPType, cp.CPDateStamp, cp.playerID, cp.numberCP, cp.CPNote, cp.staffMember, 
					p.firstName, p.lastName, cpc.CPCatName, c.charName
					FROM cp 
						LEFT JOIN players p ON cp.playerID = p.playerID
						JOIN cpcategories cpc ON cp.CPCatID = cpc.CPCatID
						LEFT JOIN characters c ON cp.characterID = c.characterID
					WHERE cp.CPDeleted IS NOT NULL
					ORDER BY cp.CPTrackID DESC, cp.CPDateStamp DESC";

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return result 
		}
		return false;
	}
	
	public function getCPDetails($CPTrackID) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['CPTrackID'] = db_escape($CPTrackID, $this->dbh);

		$query = 	"SELECT cp.CPTrackID, cp.CPType, cp.characterID, cp.playerID, cp.numberCP, cp.cpCatID, cp.CPNote, cp.CPDateStamp, cp.staffMember, c.characterID, c.charName, p.firstName, p.lastName, cpc.CPCatID, cpc.CPCatName, c.charName 
					FROM cp
						LEFT OUTER JOIN characters c ON cp.characterID = c.characterID
						LEFT OUTER JOIN players p ON cp.playerID = p.playerID
						JOIN cpcategories cpc ON cp.CPCatID = cpc.CPCatID
					WHERE cp.CPTrackID = " . $mysql['CPTrackID'];

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return result 
		}
		return false;	
	}
	
	public function getCPByCharacter($characterID) {
		
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		
		$query = 	'SELECT * FROM cp, cpcategories ' .
					'WHERE cp.CPCatID = cpcategories.CPCatID ' .
					'AND cp.CPDeleted IS NULL ' .
					'AND cp.characterID = ' . $mysql['characterID'] .
					' ORDER BY cp.CPTrackID';

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return result 
		}
		return false;
	} // end of getCPByCharacter
	
	public function getCPCategories() {
		$query = 	"SELECT cpc.CPCatID, cpc.CPCatName 
					FROM cpcategories cpc
					WHERE cpc.CPCatDeleted IS NULL";

		if ($result = $this->dbh->query($query)) {			
			return $result; // Return result 
		}
		return false;	
	} // end of getCPCategories
	
	public function getFilteredCP($filters = array()) {
		$filterParams = ''; // Initialize blank
		
		$mysql = array(); // Set up array for escaping values for DB
		
		// Build dynamic filters list based on params the user selected
		if (isset($filters['CPType']) && $filters['CPType'] != '') {
			$mysql['CPType'] = db_escape($filters['CPType'], $this->dbh);
			$filterParams .= " AND cp.CPType = '" . $mysql['CPType'] . "' ";	
		}
		
		if (isset($filters['fromDate']) && $filters['fromDate'] != '') {
			$fromDateStamp = strtotime($filters['fromDate']);
			$fromDateDB = date('Y-m-d', $fromDateStamp);
			
			$mysql['fromDate'] = db_escape($fromDateDB, $this->dbh);
			$filterParams .= " AND cp.CPDateStamp >= '" . $mysql['fromDate'] . "' ";	
		}
		
		if (isset($filters['toDate']) && $filters['toDate'] != '') {
			$toDateStamp = strtotime($filters['toDate']);
			$toDateDB = date('Y-m-d H:i:s', $toDateStamp);
			// TO DO: Need to automatically make hours 23:59:59 for the specified date to make sure you get whole day
			// echo 'To date: ' . $toDateDB . '<br /><br />';
			// echo 'From date: ' . $fromDateDB . '<br />';
			
			$mysql['toDate'] = db_escape($toDateDB, $this->dbh);
			$filterParams .= " AND cp.CPDateStamp <= '" . $mysql['toDate'] . "' ";	
		}
		
		if (isset($filters['charName']) && $filters['charName'] != '') {
			$mysql['charName'] = db_escape($filters['charName'], $this->dbh);
			$filterParams .= " AND c.charName LIKE '%" . $mysql['charName'] . "%'";	
		}
		
		/* if (isset($filters['playerID']) && $filters['playerID'] != '') {
			$mysql['playerID'] = db_escape($filters['playerID'], $this->dbh);
			$filterParams .= " AND cp.playerID = " . $mysql['playerID'];	
		} */
		
		if (isset($filters['playerName']) && $filters['playerName'] != '') {
			$mysql['playerName'] = db_escape($filters['playerName'], $this->dbh);
			$filterParams .= "AND CONCAT(p.firstName, ' ', p.lastName) LIKE '%" . $mysql['playerName'] . "%' ";	
		}
		
		if (isset($filters['CPCatID']) && $filters['CPCatID'] != '') {
			$mysql['CPCatID'] = db_escape($filters['CPCatID'], $this->dbh);
			$filterParams .= " AND cp.CPCatID = " . $mysql['CPCatID'];	
		}
		
		if (isset($filters['staffMember']) && $filters['staffMember'] != '') {
			$mysql['staffMember'] = db_escape($filters['staffMember'], $this->dbh);
			$filterParams .= " AND cp.staffMember LIKE '%" . $mysql['staffMember'] . "%' ";	
		}
		
		if (isset($filters['CPNote']) && $filters['CPNote'] != '') {
			$mysql['CPNote'] = db_escape($filters['CPNote'], $this->dbh);
			$filterParams .= " AND cp.CPNote LIKE '%" . $mysql['CPNote'] . "%' ";	
		}
		
		$query = 	'SELECT cp.CPTrackID, cp.CPType, cp.CPDateStamp, cp.playerID, cp.numberCP, cp.CPNote, cp.staffMember, 
					p.firstName, p.lastName, cpc.CPCatName, c.charName
					FROM cp 
						LEFT JOIN players p ON cp.playerID = p.playerID
						JOIN cpcategories cpc ON cp.CPCatID = cpc.CPCatID
						LEFT JOIN characters c ON cp.characterID = c.characterID
					WHERE cp.CPDeleted IS NULL ';
					
		// Add any filter params to the basic query 
		if ($filterParams != '') {
			$query .= $filterParams;	
		}
		
		// Add ORDER BY clause to query (regardless of whether there are any dynamic filter params)			
		$query .= 	' ORDER BY cp.CPTrackID DESC, cp.CPDateStamp DESC 
					LIMIT 100';
		
		// DEBUG 
		// echo $query . '<br />';
		
		if ($result = $this->dbh->query($query)) {
			// echo '<p>Returned ' . $result->num_rows . ' results. </p>';
			return $result;
		} else {
			echo $mysqli->error;
		}
	} // end getFilteredCP
	
	// $cp: Associative array of community data
	public function addCP($cp) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateCP($cp) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['CPType'] = isset($cp['CPType']) ? db_escape($cp['CPType'], $this->dbh) : 0;
		$mysql['characterID'] = isset($cp['characterID']) ? db_escape($cp['characterID'], $this->dbh) : 0;
		$mysql['playerID'] = isset($cp['playerID']) ? db_escape($cp['playerID'], $this->dbh) : 0;
		$mysql['numberCP'] = db_escape($cp['numberCP'], $this->dbh);
		$mysql['CPCatID'] = db_escape($cp['CPCatID'], $this->dbh);
		$mysql['CPNote'] = db_escape($cp['CPNote'], $this->dbh);
		$mysql['staffMember'] = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
		
		$html = array();
		$html['numberCP'] = htmlentities($cp['numberCP']);
		
		if ($mysql['CPType'] == 'character' && $mysql['playerID'] == 0) {
			// Look up player ID 
			$playerQuery = "SELECT c.playerID
							FROM characters c
							WHERE c.characterID = " . $mysql['characterID'];
			
			if ($playerResult = $this->dbh->query($playerQuery)) {
				while ($playerRow = $playerResult->fetch_assoc()) {
					$mysql['playerID'] = $playerRow['playerID'];
				}
			}
		} // end of CPType condition
		
		// Insert CP record
		$query = 	"INSERT INTO cp (CPType, characterID, playerID, numberCP, CPCatID, CPNote, CPDateStamp, staffMember)
					VALUES ('" . 
					$mysql['CPType'] . "', " .
					$mysql['characterID'] . ", " .
					$mysql['playerID'] . ", " .
					$mysql['numberCP'] . ", " .
					$mysql['CPCatID'] . ", '" .
					$mysql['CPNote'] . "', " .
					"NOW(), '" .
					$mysql['staffMember'] . "')";
		
		// echo $query . "<br />"; 
		
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($cpInsertResult = $this->dbh->query($query)) {
			
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'CP added successfully',
													'<p>' . $html['numberCP'] . ' CP have been added.</p>');
			return true;
		} else {
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'error', 
													'Failed to Add CP',
													'<p>Unable to add ' . $html['numberCP'] . ' ' . $mysql['CPType'] . ' CP. Please try again.</p>');
			$log = new Log();
			$mysql['logError'] = db_escape($this->dbh->error, $this->dbh);
			$mysql['logMsg'] = 'Unable to add ' . $mysql['numberCP'] . ' ' . $mysql['CPType'] . ' CP due to the following error: ' . $mysql['logError'];
			$log->addLogEntry($mysql['logMsg'], $mysql['playerID'], $mysql['characterID']);
			return false;
		}

	} // end of addCP
	
	public function addMultipleCP($cp) {

		$validator = new Validator();
		if ($validator->validateMultipleCP($cp) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['CPType'] = isset($cp['CPType']) ? db_escape($cp['CPType'], $this->dbh) : 0;
		$mysql['characterID'] = isset($cp['characterID']) ? db_escape($cp['characterID'], $this->dbh) : 0;
		$mysql['playerID'] = isset($cp['playerID']) ? db_escape($cp['playerID'], $this->dbh) : 0;
		$mysql['staffMember'] = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
		
		$html = array();
		$html['totalCP'] = htmlentities($cp['totalCP']);
		$html['CPType'] = strtolower(htmlentities($cp['CPType']));
		
		// If user is adding character CP, look up corresponding playerID for insert
		if ($mysql['CPType'] == 'Character' && $mysql['playerID'] == 0) {

			$playerQuery = "SELECT c.playerID
							FROM characters c
							WHERE c.characterID = " . $mysql['characterID'];
			
			if ($playerResult = $this->dbh->query($playerQuery)) {
				while ($playerRow = $playerResult->fetch_assoc()) {
					$mysql['playerID'] = $playerRow['playerID'];
				}
			}
		} // end of CPType condition
		
		// Loop through the five CP rows and insert attributes for this skill
		for ($i = 1; $i <= 5; $i++) {
			
			$curNumFld = 'numberCP' . $i;
			$curCatFld = 'CPCatID' . $i;
			$curNoteFld = 'CPNote' . $i;
			
			$mysql[$curNumFld] = db_escape($cp[$curNumFld], $this->dbh);
			$mysql[$curCatFld] = db_escape($cp[$curCatFld], $this->dbh);
			$mysql[$curNoteFld] = db_escape($cp[$curNoteFld], $this->dbh);
			
			// Only do insert if both the number and category fields exist and have values
			if (isset($cp[$curNumFld]) && $cp[$curNumFld] != '' && isset($cp[$curCatFld]) && $_POST[$curCatFld] != '') {
				$query = 	"INSERT INTO cp (CPType, characterID, playerID, numberCP, CPCatID, CPNote, CPDateStamp, staffMember)
							VALUES ('" . 
							$mysql['CPType'] . "', " .
							$mysql['characterID'] . ", " .
							$mysql['playerID'] . ", " .
							$mysql[$curNumFld] . ", " .
							$mysql[$curCatFld] . ", '" .
							$mysql[$curNoteFld] . "', " .
							"NOW(), '" .
							$mysql['staffMember'] . "')";
				// echo $query . '<br />';
				
				if (!$result = $this->dbh->query($query)) {
					echo 'Insert failed';
					return false;	
				}
			} // end of insert condition
		
		} // end of 5 row loop
		
		// If we got this far, all is well
		// Create success message to display at top of page. 
		$_SESSION['UIMessage'] = new UIMessage(	'success', 
												'CP Added Successfully',
												'<p>' . $html['totalCP'] . ' ' . $html['CPType'] . ' CP have been added.</p>');
		
		return true;
	} // end of addMultipleCP
	
	public function createCPRecord($CPType, $characterID, $playerID, $numberCP, $CPCatID, $CPNote, $staffMember = 'System') {
		
		// Escape for MySQL
		$mysql = array(); // Initialize blank
		$mysql['CPType'] = db_escape($CPType, $this->dbh);
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		$mysql['numberCP'] = db_escape($numberCP, $this->dbh);
		$mysql['CPCatID'] = db_escape($CPCatID, $this->dbh);
		$mysql['CPNote'] = db_escape($CPNote, $this->dbh);
		$mysql['staffMember'] = db_escape($staffMember, $this->dbh);

		$query = 	"INSERT INTO cp (CPType, characterID, playerID, numberCP, CPCatID, CPNote, CPDateStamp, staffMember)
					VALUES ('" . 
					$mysql['CPType'] . "'," .
					$mysql['characterID'] . ", " .
					$mysql['playerID'] . ", " .
					$mysql['numberCP'] . ", " .
					$mysql['CPCatID'] . ", '" .
					$mysql['CPNote'] . "', " .
					"NOW()," . 
					"'" . $mysql['staffMember'] . "')";

		if ($result = $this->dbh->query($query)) {
			
			return $result; // Return result 
		}
	} // end of createCPRecord
	
	public function updateCP($cp, $CPTrackID) {
		
		// Validate data
		$validator = new Validator();
		if ($validator->validateCP($cp) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['CPTrackID'] = db_escape($CPTrackID, $this->dbh);
		$mysql['CPType'] = db_escape($cp['CPType'], $this->dbh);
		$mysql['characterID'] = isset($cp['characterID']) ? db_escape($cp['characterID'], $this->dbh) : 0;
		$mysql['playerID'] = isset($cp['playerID']) ? db_escape($cp['playerID'], $this->dbh) : 0;
		$mysql['numberCP'] = db_escape($cp['numberCP'], $this->dbh);
		$mysql['CPCatID'] = db_escape($cp['CPCatID'], $this->dbh);
		$mysql['CPNote'] = db_escape($cp['CPNote'], $this->dbh);
		$mysql['staffMember'] = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
		
		if ($mysql['CPType'] == 'character' && $mysql['playerID'] == 0) {
			// Look up player ID 
			$playerQuery = "SELECT c.playerID
							FROM characters c
							WHERE c.characterID = " . $mysql['characterID'];
			
			if ($playerResult = $this->dbh->query($playerQuery)) {
				while ($playerRow = $playerResult->fetch_assoc()) {
					$mysql['playerID'] = $playerRow['playerID'];
				}
			}
		} // end of CPType condition
		
		$query = 	"UPDATE cp 
					SET 
						cp.CPType = '" . $mysql['CPType'] . "', 
						cp.characterID = " . $mysql['characterID'] . ",
						cp.playerID = " . $mysql['playerID'] . ",
						cp.numberCP = " . $mysql['numberCP'] . ",
						cp.CPCatID = " . $mysql['CPCatID'] . ",
						cp.CPNote = '" . $mysql['CPNote'] . "',
						cp.staffMember = '" . $mysql['staffMember'] . "' 
					WHERE cp.CPTrackID = " . $mysql['CPTrackID'];
		
		echo $query . '<br />';
		
		if ($cpUpdateResult = $this->dbh->query($query)) {
											
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'CP Record Updated Successfully',
													'<p>The CP record has been updated.</p>');
			return true;
		} else {
			$log = new Log();
			$mysql['logError'] = db_escape($this->dbh->error, $this->dbh);
			$mysql['logMsg'] = 'Unable to update CP due to the following error: ' . $mysql['logError'];
			$log->addLogEntry($mysql['logMsg'], $mysql['playerID'], $mysql['characterID']);
			return false;
		} // end of insert success condition
	} // end of updateCP
	
	// Perform a logical delete of a CP record
	public function deleteCP($CPTrackID) {
		$mysql = array(); // Initialize blank
		$mysql['CPTrackID'] = db_escape($CPTrackID, $this->dbh);
		
		$query =	"UPDATE cp 
					SET cp.CPDeleted = NOW() 
					WHERE cp.CPTrackID = " . $mysql['CPTrackID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	} // end of deleteCP
	
	// Perform a logical undelete of a CP record
	public function undeleteCP($CPTrackID) {
		$mysql = array(); // Initialize blank
		$mysql['CPTrackID'] = db_escape($CPTrackID, $this->dbh);
		
		$query =	"UPDATE cp 
					SET cp.CPDeleted = NULL 
					WHERE cp.CPTrackID = " . $mysql['CPTrackID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	} // end undeleteCP
	
	// Perform a permanent database delete of a country
	public function purgeCP($CPTrackID) {
		$mysql = array(); // Initialize blank
		$mysql['CPTrackID'] = db_escape($CPTrackID, $this->dbh);
		
		$query =	"DELETE FROM cp
					WHERE cp.CPTrackID = " . $mysql['CPTrackID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	} // end purgeCP
	
} // end of class
