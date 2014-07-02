<?php 
/************************************************************************
NAME: 	community.class.php
NOTES:	This file holds all the classes for adding and maintaining communities. 
*************************************************************************/

class Community {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllCommunities() {
		$query = 	'SELECT c.communityID, c.communityName  
					FROM communities c
					WHERE c.communityDeleted IS NULL 
					ORDER BY c.communityName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getCommunity($communityID) {
		$query = 	"SELECT c.communityID, c.communityName, c.communityDescription  
					FROM communities c
					WHERE c.communityID = " . $communityID;

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getDeletedCommunities() {
		$query = 	'SELECT c.communityID, c.communityName  
					FROM communities c 
					WHERE c.communityDeleted IS NOT NULL 
					ORDER BY c.communityName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getCommunityChars($communityID) {
		$query = 	"SELECT co.communityID, co.communityName, c.characterID, c.communityID, c.charName
					FROM communities co, characters c 
					WHERE co.communityID = c.communityID
					AND co.communityID = " . $communityID;
		// echo $query;
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	// $community: Associative array of community data
	public function addCommunity($community) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateCommunity($community) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['communityName'] = db_escape($community['communityName'], $this->dbh);
		$mysql['communityDescription'] = db_escape($community['communityDescription'], $this->dbh);
		
		// Insert communities
		$query = 	"INSERT INTO communities (communityName, communityDescription)
					VALUES ('" . 
								$mysql['communityName'] . "','" . 
								$mysql['communityDescription'] . "')";
		
		
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($communityInsertResult = $this->dbh->query($query)) {
			
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													$_SESSION['communityLabel'] . ' added successfully',
													'<p>The ' . $_SESSION['communityLabel'] . ' "' . $community['communityName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}

	} // end of addCommunity
	
	public function updateCommunity($community, $communityID) {
		
		// Validate data
		$validator = new Validator();
		if ($validator->validateCommunity($community) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['communityName'] = db_escape($community['communityName'], $this->dbh);
		$mysql['communityDescription'] = db_escape($community['communityDescription'], $this->dbh);
		
		$query = 	"UPDATE communities c 
					SET c.communityName = '" . $mysql['communityName'] . "', 
					c.communityDescription = '" . $mysql['communityDescription'] . "'
					WHERE c.communityID = " . $communityID;
		
		if ($communityUpdateResult = $this->dbh->query($query)) {
											
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													$_SESSION['communityLabel'] . ' updated successfully',
													'<p>The ' . $_SESSION['communityLabel'] . ' "' . $community['communityName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		} // end of insert success condition
	} // end of updateCommunity
	
	
	// Perform a logical delete of a community
	public function deleteCommunity($communityID) {
		$query =	"UPDATE communities c 
					SET c.communityDeleted = NOW() 
					WHERE c.communityID = " . $communityID;
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	} // end of deleteCommunity
	
	// Perform a logical undelete of a community
	public function undeleteCommunity($communityID) {
		$query =	"UPDATE communities c 
					SET c.communityDeleted = NULL 
					WHERE c.communityID = " . $communityID;
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	} // end of undeleteCommunity
	
	// Perform a permanent database delete of a community
	public function purgeCommunity($communityID) {
		$query =	"DELETE FROM communities
					WHERE communities.communityID = " . $communityID;
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	} // end of purgeCommunity
	
} // end of class