<?php 
/************************************************************************
NAME: 	feat.class.php
NOTES:	This file holds all the classes for adding and maintaining feats. 
*************************************************************************/

class Feat {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getTotalFeats() {
		$totalFeats = 0; // Initialize to 0
		
		$query = 	'SELECT COUNT(f.featID) as totalFeats
					FROM feats f
					WHERE f.featDeleted IS NULL';
		
		if ($result = $this->dbh->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$totalFeats = $row['totalFeats'];
			}
		}
		return $totalFeats;
	}
	
	public function getAllFeats() {
		$query = 	'SELECT f.featID, f.featName, f.featCost
					FROM feats f
					WHERE f.featDeleted IS NULL 
					ORDER BY f.featName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
		
	public function getFeat($featID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['featID'] = db_escape($featID, $this->dbh);

		$query = 	'SELECT f.featID, f.featName, f.featCost, f.featPrereq, f.featShortDescription, f.featDescription, f.featCheatSheetNote
					FROM feats f
					WHERE f.featID = ' . $mysql['featID'];

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}

	public function getFeatSuggestions($term) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['term'] = db_escape($term, $this->dbh);
		
		$query = 	"SELECT DISTINCT f.featName
					FROM feats f
					WHERE f.featDeleted IS NULL 
					AND f.featName LIKE '%" . $mysql['term'] . "%'  
					ORDER BY f.featName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getDeletedFeats() {
		$query = 	'SELECT f.featID, f.featName, f.featCost, f.featPrereq
					FROM feats f
					WHERE f.featDeleted IS NOT NULL 
					ORDER BY f.featName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	
	private function getLastInsertedFeat() {
		$lastFeatQuery = 'SELECT MAX(featID) AS lastFeatID FROM feats';
		if ($lastFeatResult = $this->dbh->query($lastFeatQuery)) {
			while ($lastFeat = $lastFeatResult->fetch_assoc()) {
				return $lastFeat['lastFeatID'];
			}
		} 
	}
	
	public function getFeatChars($featID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['featID'] = db_escape($featID, $this->dbh);

		$query = 	"SELECT f.featID, f.featName, c.charName, c.characterID, cf.featID, cf.characterID 
					FROM feats f, characters c, charfeats cf 
					WHERE f.featID = cf.featID
					AND c.characterID = cf.characterID
					AND f.featID = " . $mysql['featID'];

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
		return false;
	}
	
	// Perform a logical delete of a feat
	public function deleteFeat($featID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['featID'] = db_escape($featID, $this->dbh);

		$query =	"UPDATE feats f 
					SET f.featDeleted = NOW() 
					WHERE f.featID = " . $mysql['featID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a logical undelete of a feat
	public function undeleteFeat($featID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['featID'] = db_escape($featID, $this->dbh);

		$query =	"UPDATE feats f 
					SET f.featDeleted = NULL  
					WHERE f.featID = " . $mysql['featID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a permanent database delete of a feat
	public function purgeFeat($featID) {
		$mysql['logMsg'] = ''; // Initialize blank
		$mysql['playerID'] = ''; // Set to blank because this action doesn't relate to a specific player
		$mysql['characterID'] = ''; // Set to blank because this action doesn't relate to a specific character

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['featID'] = db_escape($featID, $this->dbh);
		
		// Delete from any characters who have this feat
		$deleteCharFeats = "DELETE FROM charfeats
							WHERE charfeats.featID = " . $mysql['featID'];
							  
		if (!$deleteCharFeatsResult = $this->dbh->query($deleteCharFeats)) {
			$mysql['logMsg'] .= 'Failed to delete feat ID ' . $mysql['featID'] . ' from one or more characters.';
		}
				
		// Only delete feat if log message is still blank 
		// (which means there have been no errors). 
		if ($mysql['logMsg'] == '') {
		
		  $deleteFeatQuery =	"DELETE FROM feats
								WHERE feats.featID = " . $mysql['featID'];
					
		  if ($result = $this->dbh->query($deleteFeatQuery)) {
			  return true;
		  }
		} else {
			echo $mysql['logMsg'] . '<br />';
			$log = new Log();
			$log->addLogEntry($mysql['logMsg'], $mysql['playerID'], $mysql['characterID']);	
		}
		
		return false;
	} // end of purgeFeat
	
	// $feat: Associative array of feat data
	public function addFeat($feat) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateFeat($feat) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['featName'] = db_escape($feat['featName'], $this->dbh);
		$mysql['featCost'] = db_escape($feat['featCost'], $this->dbh);
		$mysql['featPrereq'] = db_escape($feat['featPrereq'], $this->dbh);
		$mysql['featShortDescription'] = db_escape($feat['featShortDescription'], $this->dbh);
		$mysql['featDescription'] = db_escape($feat['featDescription'], $this->dbh);
		$mysql['featCheatSheetNote'] = db_escape($feat['featCheatSheetNote'], $this->dbh);

		$html = array(); // Initialize blank
		$html['featName'] = htmlentities($feat['featName']);
		
		// Insert feats
		$query = 	"INSERT INTO feats (featName, featCost, featPrereq, featShortDescription, featDescription, featCheatSheetNote)
					VALUES ('" . 
								$mysql['featName'] . "'," . 
								$mysql['featCost'] . ",'" . 
								$mysql['featPrereq'] . "','" . 
								$mysql['featShortDescription'] . "','" . 
								$mysql['featDescription'] . "','" . 
								$mysql['featCheatSheetNote'] . "')";
				
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($featInsertResult = $this->dbh->query($query)) {
						
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Feat Added Successfully',
													'<p>The feat "' . $html['featName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}

	}
	
	public function updateFeat($feat, $featID) {
		// Validate data
		$validator = new Validator();
		if ($validator->validateFeat($feat) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['featID'] = db_escape($featID, $this->dbh);
		$mysql['featName'] = db_escape($feat['featName'], $this->dbh);
		$mysql['featCost'] = db_escape($feat['featCost'], $this->dbh);
		$mysql['featPrereq'] = db_escape($feat['featPrereq'], $this->dbh);
		$mysql['featShortDescription'] = db_escape($feat['featShortDescription'], $this->dbh);
		$mysql['featDescription'] = db_escape($feat['featDescription'], $this->dbh);
		$mysql['featCheatSheetNote'] = db_escape($feat['featCheatSheetNote'], $this->dbh);

		$html = array(); // Initialize blank
		$html['featName'] = htmlentities($feat['featName']);
		
		$query = 	"UPDATE feats f 
					SET f.featName = '" . $mysql['featName'] . "', 
					f.featCost =" . $mysql['featCost'] . ", 
					f.featPrereq = '" . $mysql['featPrereq'] . "',
					f.featShortDescription = '" . $mysql['featShortDescription'] . "', 
					f.featDescription = '" . $mysql['featDescription'] . "', 
					f.featCheatSheetNote = '" . $mysql['featCheatSheetNote'] . "'" .
					" WHERE f.featID = " . $mysql['featID'];
		
		// echo $query; 
		
		if ($featUpdateResult = $this->dbh->query($query)) {
									
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Feat Updated Successfully',
													'<p>The feat "' . $html['featName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		} // end of insert success condition
	} // end of updateFeat
	
	
} // end of class