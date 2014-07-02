<?php 
/************************************************************************
NAME: 	race.class.php
NOTES:	This file holds all the classes for adding and maintaining spells. 
*************************************************************************/

class Race {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllRaces() {
		$query = 	'SELECT r.raceID, r.raceName
					FROM races r
					WHERE r.raceDeleted IS NULL 
					ORDER BY r.raceName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getDeletedRaces() {
		$query = 	'SELECT r.raceID, r.raceName
					FROM races r 
					WHERE r.raceDeleted IS NOT NULL 
					ORDER BY r.raceName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getRace($raceID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['raceID'] = db_escape($raceID, $this->dbh);

		$query = 	'SELECT r.raceID, r.raceName 
					FROM races r
					WHERE r.raceID = ' . $mysql['raceID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getRaceChars($raceID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['raceID'] = db_escape($raceID, $this->dbh);

		$query = 	'SELECT r.raceID, r.raceName, c.characterID, c.charName
					FROM races r, characters c
					WHERE r.raceID = c.raceID 
					AND r.raceID = ' . $mysql['raceID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	// $race: Associative array of race data
	public function addRace($race) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateRace($race) == false) {
			return false;
		}

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['raceName'] = db_escape($race['raceName'], $this->dbh);

		// Format for HTML display
		$html = array(); // Initialize blank
		$html['raceName'] = htmlentities($race['raceName']);
		
		$query = 	"INSERT INTO races (raceName)
					VALUES ('" . $mysql['raceName'] . "')";
		// echo $query;


		
		if ($raceInsertResult = $this->dbh->query($query)) {
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Race Added Successfully',
													'<p>The race "' . $html['raceName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}
	} // end of addRace
	
	// $race: Associative array of race data
	public function updateRace($race, $raceID) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateRace($race) == false) {
			return false;
		}

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['raceName'] = db_escape($race['raceName'], $this->dbh);
		$mysql['raceID'] = db_escape($raceID, $this->dbh);

		// Format for HTML display
		$html = array();
		$html['raceName'] = htmlentities($race['raceName']);
		
		$query = 	"UPDATE races r 
					SET r.raceName = '" . $mysql['raceName'] . "' 
					WHERE r.raceID = " . $mysql['raceID'];
		
		if ($raceUpdateResult = $this->dbh->query($query)) {
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Race Updated Successfully',
													'<p>The race "' . $html['raceName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		}
	} // end of updateRace
	
	// Perform a logical delete of a race
	public function deleteRace($raceID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['raceID'] = db_escape($raceID, $this->dbh);

		$query =	"UPDATE races r 
					SET r.raceDeleted = NOW() 
					WHERE r.raceID = " . $mysql['raceID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a logical undelete of a race
	public function undeleteRace($raceID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['raceID'] = db_escape($raceID, $this->dbh);

		$query =	"UPDATE races r 
					SET r.raceDeleted = NULL  
					WHERE r.raceID = " . $mysql['raceID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a permanent database delete of a race
	public function purgeRace($raceID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['raceID'] = db_escape($raceID, $this->dbh);

		$query =	"DELETE FROM races
					WHERE races.raceID = " . $mysql['raceID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	} // end of purgeRace
	
	
	
} // end of class