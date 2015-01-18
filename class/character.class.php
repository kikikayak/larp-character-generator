<?php 
/************************************************************************
NAME: 	character.class.php
NOTES:	This file holds all the classes for adding and maintaining characters. 
*************************************************************************/

// load configuration
// require_once('../includes/config.php');

class Character {
	
	private $dbh; // Database handle
	public $charCPTotal;
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllCharacters() {
		$log = new Log();

		$query = 	'SELECT c.characterID, c.playerID, c.charName, c.charType, p.playerID, p.firstName, p.lastName 
					FROM characters c, players p 
					WHERE c.playerID = p.playerID
					AND c.charDeleted IS NULL 
					ORDER BY c.charName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getAllCharacters', 'Error');
			return false;
		}
	} // getAllCharacters
	
	public function getCharactersByPlayer($playerID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		
		$query = 	'SELECT c.characterID, c.charName 
					FROM characters c 
					WHERE c.charDeleted IS NULL
					AND c.playerID = ' . $mysql['playerID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $mysql['playerID'], '', 'Character', 'getCharactersByPlayer', 'Error');
			return false;
		}
	} // getCharactersByPlayer
	
	public function getCharSuggestions($term) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['term'] = db_escape($term, $this->dbh);
		
		$query = 	"SELECT c.charName, p.firstName, p.lastName 
					FROM characters c, players p 
					WHERE c.playerID = p.playerID
					AND c.charDeleted IS NULL
					AND c.charName LIKE '%" . $mysql['term'] . "%' 
					ORDER BY c.charName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getCharSuggestions', 'Error');
			return false;
		}
	} // getCharSuggestions
	
	public function getFilteredCharacters($filters = array()) {
		$filterParams = ''; // Initialize blank
		
		$mysql = array(); // Set up array for escaping values for DB
		$log = new Log();
		
		// Build dynamic filters list based on params the user selected
		if (isset($filters['charType']) && $filters['charType'] != '') {
			$mysql['charType'] = db_escape($filters['charType'], $this->dbh);
			$filterParams .= "AND c.charType = '" . $mysql['charType'] . "' ";	
		}
		
		if (isset($filters['charName']) && $filters['charName'] != '') {
			$mysql['charName'] = db_escape($filters['charName'], $this->dbh);
			$filterParams .= "AND c.charName LIKE '%" . $mysql['charName'] . "%' ";	
		}
		
		if (isset($filters['countryID']) && $filters['countryID'] != '') {
			$mysql['countryID'] = db_escape($filters['countryID'], $this->dbh);
			$filterParams .= 'AND c.countryID = ' . $mysql['countryID'] . ' ';	
		}
		
		if (isset($filters['communityID']) && $filters['communityID'] != '') {
			$mysql['communityID'] = db_escape($filters['communityID'], $this->dbh);
			$filterParams .= 'AND c.communityID = ' . $mysql['communityID'] . ' ';	
		}
		
		if (isset($filters['raceID']) && $filters['raceID'] != '') {
			$mysql['raceID'] = db_escape($filters['raceID'], $this->dbh);
			$filterParams .= 'AND c.raceID = ' . $mysql['raceID'] . ' ';	
		}
		
		if (isset($filters['playerName']) && $filters['playerName'] != '') {
			$mysql['playerName'] = db_escape($filters['playerName'], $this->dbh);
			$filterParams .= "AND CONCAT(p.firstName, ' ', p.lastName) LIKE '%" . $mysql['playerName'] . "%' ";	
		}
		
		if (isset($filters['headerName']) && $filters['headerName'] != '') {
			$mysql['headerName'] = db_escape($filters['headerName'], $this->dbh);
			$filterParams .= "AND c.characterID IN (
													SELECT ch.characterID
													FROM charheaders ch
													WHERE ch.headerID IN (
																SELECT h.headerID
																FROM headers h
																WHERE h.headerName LIKE '%" . $mysql['headerName'] . "%') 
													) ";	
		}
		
		if (isset($filters['skillName']) && $filters['skillName'] != '') {
			$mysql['skillName'] = db_escape($filters['skillName'], $this->dbh);
			$filterParams .= "AND c.characterID IN (
													SELECT cs.characterID
													FROM charskills cs
													WHERE cs.skillID IN (
																SELECT sk.skillID
																FROM skills sk
																WHERE sk.skillName LIKE '%" . $mysql['skillName'] . "%') 
													) ";	
		}
		
		if (isset($filters['spellName']) && $filters['spellName'] != '') {
			$mysql['spellName'] = db_escape($filters['spellName'], $this->dbh);
			$filterParams .= "AND c.characterID IN (
													SELECT cs.characterID
													FROM charspells cs
													WHERE cs.spellID IN (
																SELECT sp.spellID
																FROM spells sp
																WHERE sp.spellName LIKE '%" . $mysql['spellName'] . "%') 
													) ";	
		}

		if (isset($filters['featName']) && $filters['featName'] != '') {
			$mysql['featName'] = db_escape($filters['featName'], $this->dbh);
			$filterParams .= "AND c.characterID IN (
													SELECT cf.characterID
													FROM charfeats cf
													WHERE cf.featID IN (
																SELECT f.featID
																FROM feats f
																WHERE f.featName LIKE '%" . $mysql['featName'] . "%') 
													) ";	
		}
		
		if (isset($filters['traitID']) && $filters['traitID'] != '') {
			$mysql['traitID'] = db_escape($filters['traitID'], $this->dbh);
			$filterParams .= "AND c.characterID IN (
													SELECT ct.characterID
													FROM chartraits ct
													WHERE ct.traitID = " . $mysql['traitID'] . ") ";	
		}
		
		$query = 	'SELECT c.characterID, c.playerID, c.charName, c.charType, p.playerID, p.firstName, p.lastName 
					FROM characters c, players p
					WHERE c.playerID = p.playerID
					AND c.charDeleted IS NULL ';
					
		// Add any filter params to the basic query 
		if ($filterParams != '') {
			$query .= $filterParams;	
		}
		
		// Add ORDER BY clause to query (regardless of whether there are any dynamic filter params)			
		$query .= 'ORDER BY c.charName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getFilteredCharacters', 'Error');
			return false;
		}
	} // end getFilteredCharacters
	
	public function getAllPCs() {
		$log = new Log();

		$query = 	"SELECT c.characterID, c.playerID, c.charName, c.charType, p.playerID, p.firstName, p.lastName 
					FROM characters c, players p 
					WHERE c.playerID = p.playerID 
					AND c.charType = 'PC' 
					ORDER BY c.charName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getAllPCs', 'Error');
			return false;
		}
	}
	
	public function getAllNPCs() {
		$log = new Log();

		$query = 	"SELECT c.characterID, c.playerID, c.charName, c.charType, p.playerID, p.firstName, p.lastName 
					FROM characters c, players p 
					WHERE c.playerID = p.playerID 
					AND c.charType = 'NPC' 
					ORDER BY c.charName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getAllNPCs', 'Error');
			return false;
		}
	}
	
	public function getDeletedCharacters() {
		$log = new Log();

		$query = 	'SELECT c.characterID, c.playerID, c.charName, c.charType, p.playerID, p.firstName, p.lastName 
					FROM characters c, players p 
					WHERE c.playerID = p.playerID
					AND c.charDeleted IS NOT NULL 
					ORDER BY c.charName ASC';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getDeletedCharacters', 'Error');
			return false;
		}
	}
	
	public function checkCharacterAccess($characterID, $playerID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		$mysql['playerID'] = db_escape($playerID, $this->dbh);

		$query = 	"SELECT c.characterID, c.playerID
					FROM characters c, players p
					WHERE c.playerID = p.playerID
					AND c.characterID = " . $mysql['characterID'] .
					" AND p.playerID = " . $mysql['playerID'];
					
		if ($result = $this->dbh->query($query)) {
			if ($result->num_rows > 0) {
				return true;
			}
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'checkCharacterAccess', 'Error');
		}

		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Unauthorized Character Access',
												'<p>The character you tried to view does not belong to you.</p>');
		$logMsg = 'Unauthorized character access: Player ID ' . $mysql['playerID'] . ' attempted to view character ID ' . $mysql['characterID'] . ', which does not belong to them.';
		$log->addLogEntry($logMsg, $_SESSION['playerID'], $mysql['playerID'], $mysql['characterID'], 'Character', 'checkCharacterAccess', 'Warning');
		return false;
	}
	
	public function getCharDetails($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query = 	'SELECT c.playerID, c.characterID, c.charName, c.countryID, c.communityID, c.raceID, c.charAge, c.charType, c.vitality, c.keyRelationships, c.notes, ' .
					'c.attribute1, c.attribute2, c.attribute3, c.attribute4, c.attribute5, ' .
					'co.countryID, co.countryName, ' .
					'r.raceID, r.raceName, ' .
					'com.communityID, com.communityName, ' .
					'p.firstName, p.lastName ' .
					'FROM characters c, countries co, races r, communities com, players p ' .
					'WHERE c.countryID = co.countryID ' .
					'AND c.raceID = r.raceID ' .
					'AND c.communityID = com.communityID ' .
					'AND c.playerID = p.playerID ' .
					'AND c.characterID = ' . $mysql['characterID'] .
					' AND c.charDeleted IS NULL';

		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getCharDetails', 'Error');
			return false;
		}
	} // getCharDetails
	
	public function getCharBasics($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query = 	"SELECT c.characterID, c.charName, p.playerID, p.firstName, p.lastName 
					FROM characters c, players p
					WHERE c.playerID = p.playerID
					AND c.characterID = " . $mysql['characterID'];

		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharBasics', 'Error');
			return false;
		}
	} // getCharBasics
	
	public function getCharHeaders($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query = 	'SELECT h.headerID, h.headerName, h.headerCost, h.headerAccess, h.headerDescription ' .
					'FROM headers h, charheaders ch ' .
					'WHERE h.headerID = ch.headerID ' .
					'AND h.headerDeleted IS NULL ' .
					'AND ch.characterID = ' . $mysql['characterID'] .
					' ORDER BY h.headerName';

		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharHeaders', 'Error');
			return false;
		}
	} // getCharHeaders
	
	public function getCharSkills($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query = 	'SELECT s.skillID, cs.quantity ' .
					'FROM skills s, charskills cs ' .
					'WHERE s.skillID = cs.skillID ' .
					'AND s.skillDeleted IS NULL ' .
					'AND cs.characterID = ' . $mysql['characterID'] .
					' ORDER BY s.skillID';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharSkills', 'Error');
			return false;
		}
	} // getCharSkills
	
	public function getCharSpells($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query =	'SELECT sp.spellID, cs.spellID, cs.characterID ' .
					'FROM spells sp, charspells cs ' .
					'WHERE sp.spellID = cs.spellID ' .
					'AND cs.characterID = ' . $mysql['characterID'] .
					' ORDER BY sp.spellName';
					
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getCharSpells', 'Error');
			return false;
		}
	} // getCharSpells

	public function getCharFeats($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		
		$query =	'SELECT f.featID, f.featName, f.featCheatSheetNote, cf.featID, cf.characterID ' .
					'FROM feats f, charfeats cf ' .
					'WHERE f.featID = cf.featID ' .
					'AND cf.characterID = ' . $mysql['characterID'] .
					' ORDER BY f.featName';
					
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharFeats', 'Error');
			return false;
		}
	} // getCharFeats
	
	public function getCharSkillsByHeader($headerID, $characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		$mysql['headerID'] = db_escape($headerID, $this->dbh);

		$query = 	'SELECT s.skillID, s.skillName, s.skillCost, s.skillAccess, s.shortDescription, s.skillDescription, cs.quantity ' .
					'FROM skills s, charskills cs, skillsheaders sh ' .
					'WHERE s.skillID = cs.skillID ' .
					'AND s.skillID = sh.skillID ' .
					'AND sh.headerID = ' . $mysql['headerID'] .
					' AND s.skillDeleted IS NULL ' .
					'AND cs.characterID = ' . $mysql['characterID'] .
					' ORDER BY s.skillName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharSkillsByHeader', 'Error');
			return false;
		}
	}
	
	public function getCharSpellsBySkill($skillID, $characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['skillID'] = db_escape($skillID, $this->dbh);
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query =	'SELECT * ' .
					'FROM spells sp, spellskills ss, charspells cs ' .
					'WHERE sp.spellID = cs.spellID ' .
					'AND sp.spellID = ss.spellID ' .
					'AND cs.characterID = ' . $mysql['characterID'] .
					' AND ss.skillID = ' . $mysql['skillID'] .
					' ORDER BY sp.spellName';
					
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharSpellsBySkill', 'Error');
			return false;
		}
	}
	
	public function getCheatSheetSkills($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query = 	'SELECT s.skillID, s.skillName, s.cheatSheetNote, cs.skillID, cs.characterID, cs.quantity 
					FROM skills s, charskills cs 
					WHERE s.skillID = cs.skillID 
					AND cs.characterID = ' . $mysql['characterID'];
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCheatSheetSkills', 'Error');
			return false;
		}
		return false;
	}

	public function getCharSkillsByType($characterID, $type) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		$mysql['type'] = db_escape($type, $this->dbh);

		$query = 	"SELECT s.skillID, s.skillName, s.cheatSheetNote, cs.skillID, cs.characterID, cs.quantity 
					FROM skills s, charskills cs 
					WHERE s.skillID = cs.skillID 
					AND s.skillType = '" . $mysql['type'] . "' 
					AND cs.characterID = " . $mysql['characterID'];
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharSkillsByType', 'Error');
			return false;
		}
		return false;
	}
	
	public function getCharTraits($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query =	'SELECT t.traitID, t.traitName ' .
					'FROM traits t, chartraits ct ' .
					'WHERE t.traitID = ct.traitID ' .
					'AND ct.characterID = ' . $mysql['characterID'] .
					' ORDER BY t.traitName';

		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharTraits', 'Error');
			return false;
		}
	}

	public function transferCharacter($data) {
		$validator = new Validator();
		if ($validator->validateCharTransfer($data) == false) {
			return false;
		}
		
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($data['playerID'], $this->dbh);
		$mysql['characterID'] = db_escape($data['characterID'], $this->dbh);
		
		$query =	"UPDATE characters c 
					SET c.playerID = " . $mysql['playerID'] . " 
					WHERE c.characterID = " . $mysql['characterID'];
					
		if ($result = $this->dbh->query($query)) {
			// If the update was successful, set the success message
			$character = new Character();
			$charBasics = $character->getCharBasics($data['characterID']);
		
			while ($row = $charBasics->fetch_assoc()) {
			  $html = array();
			  $html['charName'] = htmlentities($row['charName']);
			  
			  // Set success message
			  $_SESSION['UIMessage'] = new UIMessage(	'success', 
														'Character transferred successfully',
														'<p>The character "' . $html['charName'] . '" has been transferred.</p>');
													
			} // end of charDetails loop
			return true;
		}
		return false;
	}
	
	public function deleteCharacter($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query =	"UPDATE characters c 
					SET c.charDeleted = NOW() 
					WHERE c.characterID = " . $mysql['characterID'];
					
		if ($result = $this->dbh->query($query)) {
			$logMsg = 'Moved character ID ' . $mysql['characterID'] . ' to trash.';
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'deleteCharacter', 'Information');
			return true;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'deleteCharacter', 'Error');
			return false;
		}
		return false;
	}
	
	public function undeleteCharacter($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query =	"UPDATE characters c 
					SET c.charDeleted = NULL 
					WHERE c.characterID = " . $mysql['characterID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'undeleteCharacter', 'Error');
			return false;
		}
		return false;
	}
	
	public function purgeCharacter($characterID) {
		// TODO: Need to wrap all of this in a transaction so can roll back
		// if any of the deletes fail. 
		
		// Initialize logging
		$log = new Log();
		$logMsg = ''; // Initialize blank

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		
		// Get info on this character
		$charDetails = $this->getCharBasics($characterID);
		while ($row = $charDetails->fetch_assoc()) {
			$playerID = $row['playerID'];
			$charName = $row['charName'];
		}
		
		
		// Delete headers for this character
		$deleteCharHeaders = "DELETE FROM charheaders
							  WHERE charheaders.characterID = " . $mysql['characterID'];
							  
		if (!$deleteCharHeadersResult = $this->dbh->query($deleteCharHeaders)) {
			$logMsg .= 'Failed to delete headers for character ID ' . $mysql['characterID']  . '. ';
		}
		
		// Delete skills for this character
		$deleteCharSkills = "DELETE FROM charskills
							 WHERE charskills.characterID = " . $mysql['characterID'];
							 
		if (!$deleteCharSkillsResult = $this->dbh->query($deleteCharSkills)) {
			$logMsg .= 'Failed to delete skills for character ID ' . $mysql['characterID']  . '. ';
		}
		
		// Delete spells for this character
		$deleteCharSpells = "DELETE FROM charspells
							 WHERE charspells.characterID = " . $mysql['characterID'];
							 
		if (!$deleteCharSpellsResult = $this->dbh->query($deleteCharSpells)) {
			$logMsg .= 'Failed to delete spells for character ID ' . $mysql['characterID']  . '. ';
		}
		
		// Delete traits for this character
		$deleteCharTraits = "DELETE FROM chartraits
							 WHERE chartraits.characterID = " . $mysql['characterID'];
							 
		if (!$deleteCharTraitsResult = $this->dbh->query($deleteCharTraits)) {
			$logMsg .= 'Failed to delete traits for character ID ' . $mysql['characterID'] . '. ';
		}	
		
		// Delete hidden header access for this character
		$deleteCharHiddenHeaders = "DELETE FROM hiddenheadersaccess
							 		WHERE hiddenheadersaccess.characterID = " . $mysql['characterID'];
									
		if (!$deleteCharHiddenHeadersResult = $this->dbh->query($deleteCharHiddenHeaders)) {
			$logMsg .= 'Failed to delete hidden header access for character ID ' . $mysql['characterID'] . '. ';
		}
		
		// Delete hidden skill access for this character
		$deleteCharHiddenSkills = "DELETE FROM hiddenskillsaccess
							 		WHERE hiddenskillsaccess.characterID = " . $mysql['characterID'];
									
		if (!$deleteCharHiddenSkillsResult = $this->dbh->query($deleteCharHiddenSkills)) {
			$logMsg .= 'Failed to delete hidden skill access for character ID ' . $mysql['characterID'] . '. ';
		}	
		
		// Delete CP records for this character
		$deleteCharCP = "DELETE FROM cp
						WHERE cp.characterID = " . $mysql['characterID'];
						
		if (!$deleteCharCPResult = $this->dbh->query($deleteCharCP)) {
			$logMsg .= 'Failed to delete CP records for character ID ' . $mysql['characterID'] . '. ';
		}	
		
		// Only delete character if log message is still blank 
		// (which means there have been no errors). 
		if ($logMsg == '') {
		
		  $deleteCharQuery =  "DELETE FROM characters
							  WHERE characters.characterID = " . $mysql['characterID'];
					  
		  if ($deleteCharResult = $this->dbh->query($deleteCharQuery)) {
			  return true;
		  }
		} else {
			$log = new Log();
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $playerID, $mysql['characterID'], 'Character', 'purgeCharacter');	
		}
		
		return false;
	} // end of purgeCharacter
	
	/************************************************
	GET TOTAL CHARACTER CP
	Get total of all CP records assigned to a saved character in CP table
	$characterID: integer ID
	******************************************************/
	public function getTotalCharCP($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$query = 	'SELECT SUM(cp.numberCP) as cpTotal ' .
					'FROM cp ' .
					'WHERE cp.characterID = ' . $mysql['characterID'];
		
		if ($result = $this->dbh->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$cpTotal = $row['cpTotal'];
				$this->charCPTotal = $cpTotal;
			}
			
			return $cpTotal;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getTotalCharCP', 'Error');
			return false;
		}
		return false;
	} // end of getTotalCharCP
	
	
	// Calculate the character's available CP
	// This method is intended to be used on a character already saved in the DB
	// $characterID: integer ID
	public function getCharFreeCP($characterID) {
		$log = new Log();

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['characterID'] = db_escape($characterID, $this->dbh);

		$totalCharCP = $this->getTotalCharCP($characterID);
		$totalUsedCP = 0;
		$totalFreeCP = 0;
		
		$totalAttributeCost = 0;
		$totalHeaderCost = 0;
		$totalSkillCost = 0;
		$totalSpellCost = 0;
		
		/* ATTRIBUTE CP */
		$getAttributes = 	'SELECT c.attribute1, c.attribute2, c.attribute3, c.attribute4, c.attribute5
							FROM characters c
							WHERE c.characterID = ' . $mysql['characterID'];
									
		if ($attrResult = $this->dbh->query($getAttributes)) {
			while ($attributes = $attrResult->fetch_assoc()) {
				$totalAttributeCost = 	$this->calcAttributeCP($attributes['attribute1']) + 
										$this->calcAttributeCP($attributes['attribute2']) + 
										$this->calcAttributeCP($attributes['attribute3']) + 
										$this->calcAttributeCP($attributes['attribute4']) + 
										$this->calcAttributeCP($attributes['attribute5']);
			}
		} else {
			$logMsg = 'Error running query getAttributes: ' . $this->dbh->error . '. Query: ' . $getAttributes;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharFreeCP', 'Error');
			return false;
		}
		
		/* HEADER CP */
		$getHeaderCosts = 	'SELECT SUM(h.headerCost) as totalHeaderCP, ch.headerID, ch.characterID ' .
							'FROM headers h, charheaders ch ' .
							'WHERE h.headerID = ch.headerID ' .
							'AND ch.characterID = ' . $mysql['characterID'];
				
		if ($headerResult = $this->dbh->query($getHeaderCosts)) {
			while ($headers = $headerResult->fetch_assoc()) {
				$totalHeaderCost = $headers['totalHeaderCP'];
			}
		} else {
			$logMsg = 'Error running query getHeaderCosts: ' . $this->dbh->error . '. Query: ' . $getHeaderCosts;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharFreeCP', 'Error');
			return false;
		}
		
		/* SKILL CP */
		$getSkillCosts = 	'SELECT s.skillID, s.skillCost, s.costIncrement, cs.skillID, cs.characterID, cs.quantity ' .
							'FROM skills s, charskills cs ' .
							'WHERE s.skillID = cs.skillID ' .
							'AND cs.characterID = ' . $mysql['characterID'];
		
		if ($skillCostResult = $this->dbh->query($getSkillCosts)) {
			while ($skill = $skillCostResult->fetch_assoc()) {
				$totalSkillCost = $totalSkillCost + $skill['skillCost'];
				
				if ($skill['quantity'] > 1) {
					$curCost = $skill['skillCost'];
					for ($i = 2; $i <= $skill['quantity']; $i++) {
						$curCost = $curCost + $skill['costIncrement'];
						$totalSkillCost = $totalSkillCost + $curCost;
					}
				}
			}
		} else {
			$logMsg = 'Error running query getSkillCosts: ' . $this->dbh->error . '. Query: ' . $getSkillCosts;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharFreeCP', 'Error');
			return false;
		}
		
		/* SPELL CP */
		$spellCostQuery = 	'SELECT SUM(sp.spellCost) as totalSpellCP, cs.spellID, cs.characterID ' .
							'FROM spells sp, charspells cs ' .
							'WHERE sp.spellID = cs.spellID ' .
							'AND cs.characterID = ' . $mysql['characterID'];
					
		if ($spellCostResult = $this->dbh->query($spellCostQuery)) {
			while ($spells = $spellCostResult->fetch_assoc()) {
				$totalSpellCost = $spells['totalSpellCP'];
			}
		} else {
			$logMsg = 'Error running query spellCostQuery: ' . $this->dbh->error . '. Query: ' . $spellCostQuery;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharFreeCP', 'Error');
			return false;
		}

		/* FEAT CP */
		$featCostQuery = 	'SELECT SUM(f.featCost) as totalFeatCP, cf.featID, cf.characterID ' .
							'FROM feats f, charfeats cf ' .
							'WHERE f.featID = cf.featID ' .
							'AND cf.characterID = ' . $mysql['characterID'];
					
		if ($featCostResult = $this->dbh->query($featCostQuery)) {
			while ($feats = $featCostResult->fetch_assoc()) {
				$totalFeatCost = $feats['totalFeatCP'];
			}
		} else {
			$logMsg = 'Error running query featCostQuery: ' . $this->dbh->error . '. Query: ' . $featCostQuery;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', $mysql['characterID'], 'Character', 'getCharFreeCP', 'Error');
			return false;
		}
	
		// FINAL CALCULATIONS
		$totalUsedCP = $totalAttributeCost + $totalHeaderCost + $totalSkillCost + $totalSpellCost + $totalFeatCost;
		$totalFreeCP = $totalCharCP - $totalUsedCP;
		
		return $totalFreeCP;
	} // end getCharFreeCP
	
	// Calculate the character's available CP for display in the wizard
	// This method is intended to be used on new or already-saved characters
	// $characterID: integer ID
	public function getWizardFreeCP($playerID) {
		$log = new Log();

		$freeCP = 0;
		$totalPlayerCP = 0;
		
		// Figure out if there's any available player CP for this player
		$cp = new CP();
		$totalPlayerCP = $cp->getTotalPlayerCP($playerID);
		
		$freeCP = $_SESSION['baseCP'] + $totalPlayerCP;

		// DEBUG
		$_SESSION['debug']->debugItems[] = '<strong>character->getWizardFreeCP</strong>: Free CP for display: ' . $freeCP;

		return $freeCP;
	}
	
	public function getWizardSavedCharFreeCP($characterID, $playerID) {
		$log = new Log();

		$freeCP = 0;
		$totalPlayerCP = 0;
		$savedCharCP = $this->getCharFreeCP($characterID);
		
		// Figure out if there's any available player CP for this player
		$cp = new CP();
		$totalPlayerCP = $cp->getTotalPlayerCP($playerID);
		
		$freeCP = $savedCharCP + $totalPlayerCP;

		return $freeCP;
	}

	public function getWizardSavedCharTotalCP($characterID, $playerID) {
		$log = new Log();

		$totalCharCP = 0;
		$totalPlayerCP = 0;

		$totalCharCP = $this->getTotalCharCP($characterID);

		// Figure out if there's any available player CP for this player
		$cp = new CP();
		$totalPlayerCP = $cp->getTotalPlayerCP($playerID);
		
		// Set total CP appropriately, including player CP if applicable
		$totalCharCP = $totalCharCP + $totalPlayerCP;

		return $totalCharCP;
	}
	
	
	// Calculate the total number of CP used by this character
	// This method is intended to be used on a character not yet saved to the DB
	// (e.g. in the character wizard). 
	// $character: array of character attributes
	public function getWizardUsedCP($character) {
		$log = new Log();

		$totalCPCost = 0; // Initialize total to 0
	
		/* ATTRIBUTE CP */
		$totalAttributeCost = 0;
		
		$totalAttributeCost = 	$this->calcAttributeCP($character['attribute1']) + 
								$this->calcAttributeCP($character['attribute2']) + 
								$this->calcAttributeCP($character['attribute3']) + 
								$this->calcAttributeCP($character['attribute4']) + 
								$this->calcAttributeCP($character['attribute5']);
		
		$_SESSION['debug']->debugItems[] = '<strong>character->getWizardUsedCP</strong>: totalAttributeCost: ' . $totalAttributeCost;
		
		/* HEADER CP */
		$totalHeaderCost = 0;
		if (!empty($character['charHeaders'])) {
			$headerIDList = implode(',', $character['charHeaders']); // Build list of selected headers
			$headerCostQuery = 'SELECT SUM(h.headerCost) as totalHeaderCP ' .
						'FROM headers h ' .
						'WHERE h.headerID IN (' . $headerIDList . ')';
						
			if ($headerCostResult = $this->dbh->query($headerCostQuery)) {
				while ($row = $headerCostResult->fetch_assoc()) {
					$totalHeaderCost = $row['totalHeaderCP'];
				}
			} else {
				$logMsg = 'Error running query headerCostQuery. Error: ' . $this->dbh->error . '. Query: ' . $headerCostQuery;
				$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getWizardUsedCP', 'Error');
				return false;
			}
		}
		
		/* SKILL CP */
		
		// TODO: Better way to do this? Ugly code and LOTS of DB hits
		// IDEA: Build list of skill IDs and query once (using IN) to get ALL costs,
		// Then loop through all results (using IDs to match to quantities?) and do calcs. 
		// This would save a lot of DB hits. 
		$totalSkillCost = 0;
		
		foreach ($character['charSkills'] as $curID => $curValue) { // Loop through skill list and get cost for each
			$skillCostQuery = 	'SELECT s.skillName, s.skillCost, s.costIncrement, s.maxQuantity FROM skills s ' .
								'WHERE s.skillID = ' . $character['charSkills'][$curID]['id'];
			
			if ($skillCostResult = $this->dbh->query($skillCostQuery)) {
				while ($skill = $skillCostResult->fetch_assoc()) {
					$totalSkillCost = $totalSkillCost + $skill['skillCost'];
					
					/* DEBUG */
					$_SESSION['debug']->debugItems[] = '<strong>' . $skill['skillName'] . '</strong>: Base cost: ' . $skill['skillCost'];
					$_SESSION['debug']->debugItems[] = 'Increment: ' . $skill['costIncrement'];
					$_SESSION['debug']->debugItems[] = 'Total cost: ' . $totalSkillCost;
					

					if ($character['charSkills'][$curID]['qty'] > 1) {
						$curCost = $skill['skillCost'];
						for ($j = 2; $j <= $character['charSkills'][$curID]['qty']; $j++) {
							$curCost = $curCost + $skill['costIncrement'];
							$totalSkillCost = $totalSkillCost + $curCost;
							
							/* DEBUG */
							$_SESSION['debug']->debugItems[] = 'curCost: ' . $curCost;
							$_SESSION['debug']->debugItems[] = 'totalSkillCost: ' . $totalSkillCost;
							
							
						}
					}
				}
			} else {
				$logMsg = 'Error running query skillCostQuery. Error: ' . $this->dbh->error . '. Query: ' . $skillCostQuery;
				$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getWizardUsedCP', 'Error');
				return false;
			}
		}
		
		/* SPELL CP */
		$totalSpellCost = 0;
		$spellIDList = implode(',', $character['charSpells']); // Build list of selected spells
		$spellCostQuery = 'SELECT SUM(sp.spellCost) as totalSpellCP ' .
					'FROM spells sp ' .
					'WHERE sp.spellID IN (' . $spellIDList . ')';
					
		if ($spellCostResult = $this->dbh->query($spellCostQuery)) {
			while ($row = $spellCostResult->fetch_assoc()) {
				$totalSpellCost = $row['totalSpellCP'];
			}
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getWizardUsedCP', 'Error');
			return false;
		}

		/* Feat CP */
		$totalFeatCost = 0;
		if (!empty($character['charFeats']) && $character['charFeats'][0] != '') {			
			$featIDList = implode(',', $character['charFeats']); // Build list of selected feats
			$featCostQuery = 'SELECT SUM(f.featCost) as totalFeatCP ' .
						'FROM feats f ' .
						'WHERE f.featID IN (' . $featIDList . ')';
						
			if ($featCostResult = $this->dbh->query($featCostQuery)) {
				while ($row = $featCostResult->fetch_assoc()) {
					$totalFeatCost = $row['totalFeatCP'];
				}
			} else {
				$logMsg = 'Error running query featCostQuery. Error: ' . $this->dbh->error . '. Query: ' . $featCostQuery;
				$log->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Character', 'getWizardUsedCP', 'Error');
				return false;
			}
		}
		
		$totalCPCost = $totalAttributeCost + $totalHeaderCost + $totalSkillCost + $totalSpellCost + $totalFeatCost;
		
		return $totalCPCost;
	
	} // end getWizardUsedCP
	
	
	private function calcAttributeCP($attributeVal) {
		$attributeCost = 0;
		for ($i = $_SESSION['baseAttribute'] + 1; $i<=$attributeVal; $i++) {
			$attributeCost = $attributeCost + $i;
		}
		return $attributeCost;
	}
	
	
} // end of class
