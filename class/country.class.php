<?php 
/************************************************************************
NAME: 	country.class.php
NOTES:	This file holds all the classes for adding and maintaining spells. 
*************************************************************************/

class Country {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllCountries() {
		$query = 	'SELECT c.countryID, c.countryName, c.countryDefault
					FROM countries c 
					WHERE c.countryDeleted IS NULL  
					ORDER BY c.countryName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getCountry($countryID) {
		
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryID'] = db_escape($countryID, $this->dbh);

		$query = 	'SELECT * FROM countries c
					WHERE c.countryID = ' . $mysql['countryID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	
	}
	
	public function getDeletedCountries() {
		$query = 	'SELECT c.countryID, c.countryName 
					FROM countries c 
					WHERE c.countryDeleted IS NOT NULL  
					ORDER BY c.countryName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getCountryChars($countryID) {
		
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryID'] = db_escape($countryID, $this->dbh);

		$query = 	'SELECT co.countryID, co.countryName, c.characterID, c.charName
					FROM countries co, characters c
					WHERE co.countryID = c.countryID 
					AND co.countryID = ' . $mysql['countryID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	
	}
	
	// Perform a logical delete of a country
	public function deleteCountry($countryID) {
		
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryID'] = db_escape($countryID, $this->dbh);

		$query =	"UPDATE countries c 
					SET c.countryDeleted = NOW() 
					WHERE c.countryID = " . $mysql['countryID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a logical undelete of a country
	public function undeleteCountry($countryID) {
		
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryID'] = db_escape($countryID, $this->dbh);

		$query =	"UPDATE countries c 
					SET c.countryDeleted = NULL 
					WHERE c.countryID = " . $mysql['countryID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a permanent database delete of a country
	public function purgeCountry($countryID) {
		
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryID'] = db_escape($countryID, $this->dbh);

		$query =	"DELETE FROM countries
					WHERE countries.countryID = " . $mysql['countryID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// $country: Associative array of country data
	public function addCountry($country) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateCountry($country) == false) {
			return false;
		}

		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryName'] = db_escape($country['countryName'], $this->dbh);
		$mysql['countryDefault'] = db_escape($country['countryDefault'], $this->dbh);
		
		$query = 	"INSERT INTO countries (countryName, countryDefault)
					VALUES ('" . $mysql['countryName'] . "', " . 
								$mysql['countryDefault']. ")";
		
		if ($countryInsertResult = $this->dbh->query($query)) {
			// If insert was successful, 
			// set all other countries to default = 0
			if ($country['countryDefault'] == 1) {
				$lastCountryQuery = 'SELECT MAX(countryID) AS lastCountryID FROM countries';
				if ($lastCountryResult = $this->dbh->query($lastCountryQuery)) {
					while ($lastCountry = $lastCountryResult->fetch_assoc()) {
						$lastCountryID = $lastCountry['lastCountryID'];
					}
					$this->resetDefaultCountry($lastCountryID);
				}
			}
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Country added successfully',
													'<p>The country "' . $country['countryName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}
	
	}
	
	// $country: Associative array of country data
	public function updateCountry($country, $countryID) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateCountry($country) == false) {
			return false;
		}

		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryID'] = db_escape($countryID, $this->dbh);
		$mysql['countryName'] = db_escape($country['countryName'], $this->dbh);
		$mysql['countryDefault'] = db_escape($country['countryDefault'], $this->dbh);
		
		$query = 	"UPDATE countries c 
					SET c.countryName = '" . $mysql['countryName'] . "', 
						c.countryDefault = " . $mysql['countryDefault'] . 
						" WHERE c.countryID = " . $mysql['countryID'];
		
		if ($countryUpdateResult = $this->dbh->query($query)) {
			// Set all other countries to default = 0
			if ($country['countryDefault'] == 1) {
				$this->resetDefaultCountry($countryID);
			}
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Country updated successfully',
													'<p>The country "' . $country['countryName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		}
	}
	
	private function resetDefaultCountry($countryID) {

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['countryID'] = db_escape($countryID, $this->dbh);
		
		$query = 	"UPDATE countries c 
					SET c.countryDefault = 0 
					WHERE c.countryID != " . $mysql['countryID'];
		
		$countryDefaultResult = $this->dbh->query($query);
	}

		
} // end of class