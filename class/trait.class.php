<?php 
/************************************************************************
NAME: 	trait.class.php
NOTES:	This file holds all the classes for adding and maintaining spells. 
*************************************************************************/

class charTrait {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllTraits() {
		$query = 	'SELECT t.traitID, t.traitName, t.traitStaff, t.traitAccess
					FROM traits t
					WHERE t.traitDeleted IS NULL 
					ORDER BY t.traitName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getTrait($traitID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitID'] = db_escape($traitID, $this->dbh);

		$query = 	"SELECT t.traitID, t.traitName, t.traitStaff, t.traitAccess, traitDescriptionStaff, traitDescriptionPublic
					FROM traits t
					WHERE t.traitID = " . $mysql['traitID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getDeletedTraits() {
		$query = 	'SELECT t.traitID, t.traitName, t.traitStaff, t.traitAccess
					FROM traits t
					WHERE t.traitDeleted IS NOT NULL 
					ORDER BY t.traitName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getTraitCharacters($traitID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitID'] = db_escape($traitID, $this->dbh);

		$query = 	"SELECT ct.traitID, ct.characterID 
					FROM chartraits ct 
					WHERE ct.traitID = " . $mysql['traitID'];
		// echo $query;
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	private function getLastInsertedTrait() {
		$lastTraitQuery = 'SELECT MAX(traitID) AS lastTraitID FROM traits';
		if ($lastTraitResult = $this->dbh->query($lastTraitQuery)) {
			while ($lastTrait = $lastTraitResult->fetch_assoc()) {
				return $lastTrait['lastTraitID'];
			}
		} 
	}
	
	public function getTraitChars($traitID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitID'] = db_escape($traitID, $this->dbh);

		$query = 	"SELECT t.traitName, c.charName, ct.traitID, ct.characterID 
					FROM traits t, characters c, chartraits ct 
					WHERE t.traitID = ct.traitID
					AND c.characterID = ct.characterID
					AND t.traitID = " . $mysql['traitID'];
		// echo $query;
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	// Perform a logical delete of a trait
	public function deleteTrait($traitID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitID'] = db_escape($traitID, $this->dbh);

		$query =	"UPDATE traits t 
					SET t.traitDeleted = NOW() 
					WHERE t.traitID = " . $mysql['traitID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a logical undelete of a trait
	public function undeleteTrait($traitID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitID'] = db_escape($traitID, $this->dbh);

		$query =	"UPDATE traits t 
					SET t.traitDeleted = NULL  
					WHERE t.traitID = " . $mysql['traitID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a permanent database delete of a trait
	public function purgeTrait($traitID) {
		
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitID'] = db_escape($traitID, $this->dbh);
		$mysql['logMsg'] = ''; // Initialize blank
		$mysql['playerID'] = ''; // Set to blank because this action doesn't relate to a specific player
		$mysql['characterID'] = ''; // Set to blank because this action doesn't relate to a specific character

		// Escape values for insertion into DB
		$html = array(); // Initialize blank
		$html['traitID'] = htmlentities($traitID);
		
		// Delete from any characters who have this trait
		$deleteCharTraits = "DELETE FROM chartraits
							 WHERE chartraits.traitID = " . $mysql['traitID'];
							  
		if (!$deleteCharTraitsResult = $this->dbh->query($deleteCharTraits)) {
			$mysql['logMsg'] .= 'Failed to delete trait ID ' . $html['traitID'] . ' from one or more characters.';
		}
		
		// Only delete trait if log message is still blank 
		// (which means there have been no errors). 
		if ($mysql['logMsg'] == '') {
		
		  $deleteTraitQuery =	"DELETE FROM traits
								WHERE traits.traitID = " . $mysql['traitID'];
					
		  if ($result = $this->dbh->query($deleteTraitQuery)) {
			  return true;
		  }
		} else {
			echo $mysql['logMsg'] . '<br />';
			$log = new Log();
			$log->addLogEntry($mysql['logMsg'], $mysql['playerID'], $mysql['characterID']);	
		}
		
		return false;
	} // end of purgeTrait
	
	// $trait: Associative array of trait data
	public function addTrait($trait) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateTrait($trait) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitName'] = db_escape($trait['traitName'], $this->dbh);
		$mysql['traitStaff'] = db_escape($trait['traitStaff'], $this->dbh);
		$mysql['traitAccess'] = db_escape($trait['traitAccess'], $this->dbh);
		$mysql['traitDescriptionStaff'] = db_escape($trait['traitDescriptionStaff'], $this->dbh);
		$mysql['traitDescriptionPublic'] = db_escape($trait['traitDescriptionPublic'], $this->dbh);
		
		$html = array(); // Initialize blank
		$html['traitName'] = htmlentities($trait['traitName']);
		
		// Insert traits
		$query = 	"INSERT INTO traits (traitName, traitStaff, traitAccess, traitDescriptionStaff, traitDescriptionPublic)
					VALUES ('" . 
								$mysql['traitName'] . "','" . 
								$mysql['traitStaff'] . "','" . 
								$mysql['traitAccess'] . "','" . 
								$mysql['traitDescriptionStaff'] . "','" . 
								$mysql['traitDescriptionPublic'] . "')";
		
		// echo $query;
		
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($traitInsertResult = $this->dbh->query($query)) {
			// Get trait ID of last trait inserted, so we can use it to insert the access list
			$lastTraitID = $this->getLastInsertedTrait(); 
			
			// Insert a row for each character with this trait.  
			if ($trait['characterID'] && $trait['characterID'] != '') {
				// Insert character traits
				for ($i = 0; $i < count($trait['characterID']); $i++) {
					$charInsertQuery = 	'INSERT INTO charTraits (traitID, characterID) ' .
										'VALUES(' . $lastTraitID . ',' . $trait['characterID'][$i] . ')';
					$charInsertResult = $this->dbh->query($charInsertQuery);
				}
			} 
			
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Trait added successfully',
													'<p>The trait "' . $html['traitName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}

	}
	
	public function updateTrait($trait, $traitID) {
		// Validate data
		$validator = new Validator();
		if ($validator->validateTrait($trait) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['traitID'] = db_escape($traitID, $this->dbh);
		$mysql['traitName'] = db_escape($trait['traitName'], $this->dbh);
		$mysql['traitStaff'] = db_escape($trait['traitStaff'], $this->dbh);
		$mysql['traitAccess'] = db_escape($trait['traitAccess'], $this->dbh);
		$mysql['traitDescriptionStaff'] = db_escape($trait['traitDescriptionStaff'], $this->dbh);
		$mysql['traitDescriptionPublic'] = db_escape($trait['traitDescriptionPublic'], $this->dbh);
		
		$html = array(); // Initialize blank
		$html['traitName'] = htmlentities($trait['traitName']);
		
		$query = 	"UPDATE traits t 
					SET t.traitName = '" . $mysql['traitName'] . "', 
					t.traitStaff = '" . $mysql['traitStaff'] . "', 
					t.traitAccess = '" . $mysql['traitAccess'] . "', 
					t.traitDescriptionStaff = '" . $mysql['traitDescriptionStaff'] . "', 
					t.traitDescriptionPublic = '" . $mysql['traitDescriptionPublic'] . "' 
					WHERE t.traitID = " . $mysql['traitID'];
		
		// echo $query; 
		
		if ($traitUpdateResult = $this->dbh->query($query)) {
				
			// Delete the existing character list and recreate it. 
			$deleteQuery = "DELETE ct 
							FROM chartraits ct 
							WHERE ct.traitID = " . $mysql['traitID'];
			
			if ($deleteResult = $this->dbh->query($deleteQuery)) {
				// If the user has specified a new list of access characters, insert them.
				if ($trait['characterID'] && $trait['characterID'] != '') {
					for ($i = 0; $i < count($trait['characterID']); $i++) {
						$charInsertQuery = 	'INSERT INTO chartraits (traitID, characterID) ' .
											'VALUES(' . $mysql['traitID'] . ',' . $trait['characterID'][$i] . ')';
						$charInsertResult = $this->dbh->query($charInsertQuery);
					}
				}
			}
			
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Trait updated successfully',
													'<p>The trait "' . $html['traitName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		} // end of insert success condition
	}
	
	
} // end of class