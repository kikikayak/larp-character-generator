<?php 
/************************************************************************
NAME: 	header.class.php
NOTES:	This file holds all the classes for adding and maintaining headers. 
*************************************************************************/

// load configuration
// require_once('../includes/config.php');

class Header {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllHeaders() {
		$query = 	'SELECT h.headerID, h.headerName, h.headerCost, h.headerAccess
					FROM headers h 
					WHERE h.headerDeleted IS NULL
					ORDER BY h.headerName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getPublicHeaders() {
		$query = 	"SELECT h.headerID, h.headerName, h.headerCost, h.headerAccess
					FROM headers h 
					WHERE h.headerAccess = 'Public' 
					ORDER BY h.headerName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHiddenHeaders() {
		$query = 	"SELECT h.headerID, h.headerName, h.headerCost, h.headerAccess
					FROM headers h 
					WHERE h.headerAccess = 'Hidden' 
					ORDER BY h.headerName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getNPCHeaders() {
		$query = 	"SELECT h.headerID, h.headerName, h.headerCost, h.headerAccess
					FROM headers h 
					WHERE h.headerAccess = 'NPC' 
					ORDER BY h.headerName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHeaderSuggestions($term) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['term'] = db_escape($term, $this->dbh);
		
		$query = 	"SELECT h.headerName
					FROM headers h
					WHERE h.headerDeleted IS NULL 
					AND h.headerName LIKE '%" . $mysql['term'] . "%'  
					ORDER BY h.headerName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHeader($headerID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['headerID'] = db_escape($headerID, $this->dbh);

		$query = 	'SELECT h.headerID, h.headerName, h.headerCost, h.headerAccess, h.headerDescription 
					FROM headers h
					WHERE h.headerID = ' . $mysql['headerID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getDeletedHeaders() {
		$query = 	'SELECT h.headerID, h.headerName, h.headerCost, h.headerAccess
					FROM headers h 
					WHERE h.headerDeleted IS NOT NULL  
					ORDER BY h.headerName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHeaderCharacters($headerID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['headerID'] = db_escape($headerID, $this->dbh);

		$query = 	"SELECT hh.characterID, hh.headerID 
					FROM hiddenheadersaccess hh 
					WHERE hh.headerID = " . $mysql['headerID'];

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHeaderChars($headerID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['headerID'] = db_escape($headerID, $this->dbh);

		$query = 	"SELECT h.headerID, h.headerName, c.characterID, c.charName, ch.headerID, ch.characterID 
					FROM headers h, characters c, charheaders ch 
					WHERE h.headerID = ch.headerID
					AND c.characterID = ch.characterID
					AND h.headerID = " . $mysql['headerID'];

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHeaderSkills($headerID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['headerID'] = db_escape($headerID, $this->dbh);

		$query = 	"SELECT h.headerID, h.headerName, s.skillID, s.skillName, sh.headerID, sh.skillID 
					FROM headers h, skills s, skillsheaders sh 
					WHERE h.headerID = sh.headerID
					AND s.skillID = sh.skillID
					AND h.headerID = " . $headerID;

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	// Perform a logical delete of a header
	public function deleteHeader($headerID) {
		$query =	"UPDATE headers h 
					SET h.headerDeleted = NOW() 
					WHERE h.headerID = " . $headerID;
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a logical undelete of a header
	public function undeleteHeader($headerID) {
		$query =	"UPDATE headers h 
					SET h.headerDeleted = NULL 
					WHERE h.headerID = " . $headerID;
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}

	// $data: array of data (e.g. $_POST)
	public function initAddHeader($data) {
		$html = array(); // Initialize array to hold data for display

		$html['headerName'] = isset($data['headerName']) ? htmlentities($data['headerName']) : '';
		$html['headerCost'] = isset($data['headerCost']) ? htmlentities($data['headerCost']) : '';
		$html['headerAccess'] = isset($data['headerAccess']) ? htmlentities($data['headerAccess']) : '';
		$html['headerDescription'] = isset($data['headerDescription']) ? htmlentities($data['headerDescription']) : '';
		
		$html['title'] = 'Add a Header | ' . $_SESSION['campaignName'] . ' Character Generator';
		$html['pageHeader']  = 'Add a Header';
		$html['btnLabel'] = 'Add';

		return $html;
	}

	public function initUpdateHeader($data, $headerID) {
		$html = array(); // Initialize array to hold data for display
		
		$headerDetails = $this->getHeader($headerID);
		while ($savedHeaderDetails = $headerDetails->fetch_assoc()) {
			$html['headerName'] = isset($data['headerName']) ? htmlentities($data['headerName']) : htmlentities($savedHeaderDetails['headerName']);
			$html['headerCost'] = isset($data['headerCost']) ? htmlentities($data['headerCost']) : htmlentities($savedHeaderDetails['headerCost']);
			$html['headerAccess'] = isset($data['headerAccess']) ? htmlentities($data['headerAccess']) : htmlentities($savedHeaderDetails['headerAccess']);
			$html['headerDescription'] = isset($data['headerDescription']) ? htmlentities($data['headerDescription']) : htmlentities($savedHeaderDetails['headerDescription']);
		}

		// Set up array to pre-select characters
		$html['characterID'] = array(); // Initialize to empty array
		$charResult = $this->getHeaderCharacters($_GET['headerID']);
		while ($headerCharacters = $charResult->fetch_assoc()) {
			// Loop through retrieved characters and add to array
			$html['characterID'][] = $headerCharacters['characterID'];
		}
		
		$html['title'] = 'Update Header | ' . $_SESSION['campaignName'] . ' Character Generator';
		$html['pageHeader'] = 'Update Header';
		$html['btnLabel'] = 'Update';

		return $html;
	}
	
	// Perform a permanent database delete of a header
	public function purgeHeader($headerID) {
		$logMsg = ''; // Initialize blank
		$playerID = ''; // Set to 0 because this action doesn't relate to a specific player
		
		// Delete from any characters who have this header
		$deleteCharHeaders = "DELETE FROM charheaders
							  WHERE charheaders.headerID = " . $headerID;
							  
		if (!$deleteCharHeadersResult = $this->dbh->query($deleteCharHeaders)) {
			$logMsg .= 'Failed to delete header ID ' . $headerID . ' from one or more characters.';
		}
		
		// Delete header-skill associations
		$deleteHeaderSkills = "DELETE FROM skillsheaders
							  WHERE skillsheaders.headerID = " . $headerID;
							  
		if (!$deleteHeaderSkillsResult = $this->dbh->query($deleteHeaderSkills)) {
			$logMsg .= 'Failed to delete one or more skill associations with header ID ' . $headerID . ' . ';
		}
		
		// Delete hidden header access for this character
		$deleteHiddenHeaderAccess = "DELETE FROM hiddenheadersaccess
							 		WHERE hiddenheadersaccess.headerID = " . $headerID;
									
		if (!$deleteHiddenHeadersResult = $this->dbh->query($deleteHiddenHeaderAccess)) {
			$logMsg .= 'Failed to delete hidden header access for header ID ' . $headerID . '. ';
		}
		
		// Only delete header if log message is still blank 
		// (which means there have been no errors). 
		if ($logMsg == '') {
		
		  $deleteHeaderQuery =	"DELETE FROM headers
								WHERE headers.headerID = " . $headerID;
					
		  if ($result = $this->dbh->query($deleteHeaderQuery)) {
			  return true;
		  }
		} else {
			echo $logMsg . '<br />';
			$log = new Log();
			$log->addLogEntry($logMsg, $playerID, $characterID);	
		}
		
		return false;
	} // end of purgeHeader
	
	// $header: Associative array of header data
	public function addHeader($header) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateHeader($header) == false) {
			return false;
		}

		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['headerName'] = db_escape($header['headerName'], $this->dbh);
		$mysql['headerCost'] = db_escape($header['headerCost'], $this->dbh);
		$mysql['headerAccess'] = db_escape($header['headerAccess'], $this->dbh);
		$mysql['headerDescription'] = db_escape($header['headerDescription'], $this->dbh);
		
		// Insert headers
		$query = 	"INSERT INTO headers (headerName, headerCost, headerAccess, headerDescription)
					VALUES ('" . 
								$mysql['headerName'] . "'," . 
								$mysql['headerCost'] . ",'" . 
								$mysql['headerAccess'] . "','" .
								$mysql['headerDescription'] . "')";
		// echo $query;

		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($headerInsertResult = $this->dbh->query($query)) {
			
			// If this is a hidden header and the user has selected specific characters to access it,
			// insert a row for each character.  
			if ($header['characterID'] && $header['characterID'] != '' && $header['headerAccess'] == 'Hidden') {
				// Get header ID of last header inserted, so we can use it to insert the access list
				$lastHeaderQuery = 'SELECT MAX(headerID) AS lastHeaderID FROM headers';
				if ($lastHeaderResult = $this->dbh->query($lastHeaderQuery)) {
					while ($lastHeader = $lastHeaderResult->fetch_assoc()) {
						$lastHeaderID = $lastHeader['lastHeaderID'];
					}
					// Insert character headers
					for ($i = 0; $i < count($header['characterID']); $i++) {
						$charInsertQuery = 	'INSERT INTO hiddenheadersaccess (headerID, characterID) ' .
											'VALUES(' . $lastHeaderID . ',' . $header['characterID'][$i] . ')';
						$charInsertResult = $this->dbh->query($charInsertQuery);
					}
				}
			} 
			
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Header added successfully',
													'<p>The header "' . $header['headerName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}

	}
	
	public function updateHeader($header, $headerID) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateHeader($header) == false) {
			return false;
		}

		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['headerName'] = db_escape($header['headerName'], $this->dbh);
		$mysql['headerCost'] = db_escape($header['headerCost'], $this->dbh);
		$mysql['headerAccess'] = db_escape($header['headerAccess'], $this->dbh);
		$mysql['headerDescription'] = db_escape($header['headerDescription'], $this->dbh);
		$mysql['headerID'] = db_escape($headerID, $this->dbh);
		
		$query = 	"UPDATE headers h 
					SET h.headerName = '" . $mysql['headerName'] . "', 
					h.headerCost =" . $mysql['headerCost'] . ", 
					h.headerAccess = '" . $mysql['headerAccess'] . "', 
					h.headerDescription = '" . $mysql['headerDescription'] . "' " .
					" WHERE h.headerID = " . $mysql['headerID'];
		echo $query;

		if ($headerUpdateResult = $this->dbh->query($query)) {
			
			// If this is a hidden header, delete the existing access list and recreate it. 
			if ($header['headerAccess'] == 'Hidden') {
				$deleteQuery = "DELETE hh FROM hiddenheadersaccess hh 
								WHERE hh.headerID = " . $headerID;
				
				if ($deleteResult = $this->dbh->query($deleteQuery)) {
					// If the user has specified a new list of access characters, insert them.
					if ($header['characterID'] && $header['characterID'] != '') {
						for ($i = 0; $i < count($header['characterID']); $i++) {
							$charInsertQuery = 	'INSERT INTO hiddenheadersaccess (headerID, characterID) ' .
												'VALUES(' . $headerID . ',' . $header['characterID'][$i] . ')';
							$charInsertResult = $this->dbh->query($charInsertQuery);
						}
					}
				}
			}
			
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Header updated successfully',
													'<p>The header "' . $header['headerName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		}
	}
	
	
	
} // end of class
