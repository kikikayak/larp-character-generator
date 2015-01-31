<?php 
/************************************************************************
NAME: 	wizard.class.php
NOTES:	This file holds all the classes for the character creation wizard. 
*************************************************************************/

// load configuration
// require_once('../includes/config.php');

class Wizard {
	
	private $dbh; // Database handle
	public $baseAttribute1, $baseAttribute2, $baseAttribute3, $baseAttribute4, $baseAttribute5, $baseVitality, $startingCP;
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	// Retrieve all headers from the DB
	public function getHeaders() {
		$log = new Log();

		$query = 	'SELECT * FROM headers h ' .
					'WHERE h.headerAccess = \'Public\'' .
					'AND h.headerDeleted IS NULL ' . 
					'ORDER BY headerName';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Headers retrieved: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getHeaders query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getHeaders', 'Error');
		}
		return false;
	}
	
	// Retrieve all countries from the DB
	public function getCountries() {
		$log = new Log();

		$query = 	'SELECT * FROM countries c ' .
					'WHERE c.countryDeleted IS NULL ' . 
					'ORDER BY c.countryName';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Countries retrieved: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getCountries query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getCountries', 'Error');
		}
		return false;
	}
	
	// Retrieve all communities from the DB
	public function getCommunities() {
		$log = new Log();

		$query = 	'SELECT * FROM communities c ' .
					'WHERE c.communityDeleted IS NULL ' . 
					'ORDER BY c.communityName';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Communities retrieved: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getCommunities query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getCommunities', 'Error');
		}
		return false;
	}
	
	// Retrieve all races from the DB
	public function getRaces() {
		$log = new Log();

		$query = 	'SELECT * FROM races r ' .
					'WHERE r.raceDeleted IS NULL ' . 
					'ORDER BY r.raceName';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Communities retrieved: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getRaces query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getRaces', 'Error');
		}
		return false;
	}
	
	// Retrieve all headers from the DB
	public function getSkillsForHeader($headerID) {
		$log = new Log();

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['headerID'] = db_escape($headerID, $this->dbh);

		$query = 	'SELECT * FROM skills s, headers h, skillsheaders sh ' .
					'WHERE h.headerID = ' . $mysql['headerID'] .
					' AND s.skillID = sh.skillID ' .
					'AND h.headerID = sh.headerID ' .
					'AND s.skillDeleted IS NULL ' . 
					'ORDER BY skillName';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Skills retrieved for this header: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getSkillsForHeader query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getSkillsForHeader', 'Error');
		}
		return false;
	}
	
	// Retrieve all spell spheres from the DB
	public function getSpheres($skillIDList) {
		$log = new Log();

		$query = 	'SELECT DISTINCT s.skillID, s.skillName, sk.skillID ' .
					'FROM skills s, spellskills sk ' .
					'WHERE s.skillID = sk.skillID ' .
					'AND s.skillID IN (' . $skillIDList . ')' .
					'ORDER BY s.skillName';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Spheres retrieved: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getSpheres query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getSpheres', 'Error');
		}
		return false;
	}
	
	// Retrieve all spells available based on the currently selected skills
	public function getSelectableSpells() {
		$log = new Log();

		$query = 	'SELECT s.skillID, s.skillName, sp.spellID, sp.spellName, sp.spellCost, sp.spellShortDescription, sp.spellDescription, sp.spellAttributeCost, sp.spellDeleted ' .
					'FROM spells sp, skills s, spellskills sk ' .
					'WHERE sp.spellID = sk.spellID ' .
					'AND s.skillID = sk.skillID ' .
					'AND sp.spellDeleted IS NULL ' . 
					'ORDER BY s.skillName, sp.spellName';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Spells retrieved: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getSelectableSpells query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getSelectableSpells', 'Error');
		}
		return false;
	}
	
	public function getSpellsForSphere($skillID) {
		$log = new Log();

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$query = 	'SELECT * FROM skills s, spells sp, spellskills ss ' .
					'WHERE s.skillID = ' . $mysql['skillID'] .
					' AND s.skillID = ss.skillID ' .
					'AND sp.spellID = ss.spellID ' .
					'AND sp.spellDeleted IS NULL ' . 
					'ORDER BY spellName';

		if ($result = $this->dbh->query($query)) {
			// echo 'Skills retrieved for this header: ' . $this->dbh->affected_rows . '<br />';
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getSpellsForSphere query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getSpellsForSphere', 'Error');
		}
		return false;
	}
	
	// Retrieve all feats from the DB
	public function getFeats() {
		$log = new Log();

		$query = 	'SELECT f.featID, f.featName, f.featCost, f.featShortDescription, f.featDescription
					FROM feats f
					ORDER BY f.featName';
		
		if ($result = $this->dbh->query($query)) {
			return $result; // Return result 
		} else {
			$logMsg = 'Error running getFeats query: ' . $this->dbh->error . '. Query: ' . $query;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'getFeats', 'Error');
		}
		return false;
	}
	
	// $character: Array of character values. 
	public function checkCharacterCP($character) {
		$log = new Log();

		$totalCPCost = 0; // Total cost of wizard items character is trying to purchase
		$totalPlayerCP = 0; // Total CP assigned to player. Will be transferred to character during creation process.
		$totalAvailableCP = 0; // Total CP available to be spent on wizard items, including player CP. 
		// $_SESSION['character']['freeCP'] = 0; // Total CP remaining for purchase. freeCP = totalAvailableCP - totalCPCost. 

		if (!isset($character['playerID']) || is_empty($character['playerID'])) {
			$character['playerID'] = $_SESSION['playerID'];
		}

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['charName'] = db_escape($character['charName'], $this->dbh);
		$mysql['playerID'] = db_escape($character['playerID'], $this->dbh);
		
		$charCheck = new Character();
		
		// Calculate total CP cost of wizard items. 
		$totalCPCost = $charCheck->getWizardUsedCP($character);

		// Calculate available player CP. 
		$cp = new CP();
		$totalPlayerCP = $cp->getTotalPlayerCP($character['playerID']);
				
		// Calculate total available CP
		if (isset($character['characterID'])) {
			// Update
			$totalAvailableCP = $charCheck->getTotalCharCP($character['characterID']) + $totalPlayerCP;
			$mysql['characterID'] = db_escape($character['characterID'], $this->dbh);
		} else {
			// New character
			$totalAvailableCP = $_SESSION['baseCP'] + $totalPlayerCP;
			$mysql['characterID'] = '';
		}
		$cpDifference = $totalAvailableCP - $totalCPCost;
		
		// $_SESSION['character']['freeCP'] = $totalAvailableCP - $totalCPCost;
		
		/* DEBUG */
		$_SESSION['debug']->debugItems[] = '<strong>wizard->checkCharacterCP</strong>: Total CP cost: ' . $totalCPCost;
		$_SESSION['debug']->debugItems[] = '<strong>wizard->checkCharacterCP</strong>: Total player CP: ' . $totalPlayerCP;
		$_SESSION['debug']->debugItems[] = '<strong>wizard->checkCharacterCP</strong>: Total available CP: ' . $totalAvailableCP;
		// $_SESSION['debug']->debugItems[] = '<strong>wizard->checkCharacterCP</strong>: Free CP: ' . $_SESSION['character']['freeCP'];		
		
		// TODO: Make the numbers and email addresses dynamic
		if ($totalCPCost > $totalAvailableCP) {
			$_SESSION['UIMessage'] = new UIMessage(	'error', 
													'Exceeded Available CP',
													'<p>The character you built adds up to ' . $totalCPCost . ' CP. You only have ' . 
													$totalAvailableCP . ' CP available. </p>
													<p>Please verify your character below. If there is an error in the Generator\'s calculations, please email the <a href="mailto:' . $_SESSION['webmasterEmail'] . '">Webmaster</a>.</p>');
			$logMsg = 'Warning: Player ID ' . $mysql['playerID'] . ' attempted to save character with ' . $cpDifference . ' CP. Repeated attempts may indicate an attempt to hack the Generator.';
			if (isset($mysql['characterID'])) {
				$logMsg .= ' Character ID ' . $mysql['characterID'] . ' (' . $mysql['charName'] . ').';
			}
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $mysql['playerID'], $mysql['characterID'], 'Wizard', 'checkCharacterCP', 'Warning');
			return false;
		}
		
		return true;

			
	} // end checkCharacterCP

	
	// $character: Associative array of character data
	// TODO: Add transaction-handling around all inserts; we need to roll back if any part of the inserts fails. 
	public function createCharacter($character) {

		/* DEBUG */
		$_SESSION['debug'] = new Debug('debug', '');
		$log = new Log();

		// Check whether character CP values pass.
		if ($this->checkCharacterCP($character) == false) {
			$_SESSION['debug']->outputDebug();
			return false;
		}

		if (!isset($character['playerID']) || is_empty($character['playerID'])) {
			$character['playerID'] = $_SESSION['playerID'];
		}
		
		// Validate data.
		// TODO: Validate that character has only 1 feat
		$validator = new Validator();
		if ($validator->validateCharacter($character) == false) {
			$_SESSION['debug']->outputDebug();
			return false;
		}
						
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($character['playerID'], $this->dbh);
		$mysql['charName'] = db_escape($character['charName'], $this->dbh);
		$mysql['countryID'] = db_escape($character['countryID'], $this->dbh);
		$mysql['communityID'] = db_escape($character['communityID'], $this->dbh);
		$mysql['raceID'] = db_escape($character['raceID'], $this->dbh);
		$mysql['charType'] = db_escape($character['charType'], $this->dbh);
		$mysql['attribute1'] = db_escape($character['attribute1'], $this->dbh);
		$mysql['attribute2'] = db_escape($character['attribute2'], $this->dbh);
		$mysql['attribute3'] = db_escape($character['attribute3'], $this->dbh);
		$mysql['attribute4'] = db_escape($character['attribute4'], $this->dbh);
		$mysql['attribute5'] = db_escape($character['attribute5'], $this->dbh);
		$mysql['vitality'] = db_escape($character['vitality'], $this->dbh);
								
		$charInsertQuery = "INSERT INTO characters 
					(playerID, charName, countryID, communityID, raceID, charType, attribute1, attribute2, attribute3, attribute4, attribute5, vitality) 
					VALUES (" . $mysql['playerID'] . ",
							'" . $mysql['charName'] . "', " .
							$mysql['countryID'] . ", " .
							$mysql['communityID'] . ", " .
							$mysql['raceID'] . ", '" . 
							$mysql['charType'] . "', " . 
							$mysql['attribute1'] . ", " .
							$mysql['attribute2'] . ", " .
							$mysql['attribute3'] . ", " .
							$mysql['attribute4'] . ", " .
							$mysql['attribute5'] . ", " .
							$mysql['vitality'] . ")";
		// echo $charInsertQuery . '<br />';
		
		if ($charInsertResult = $this->dbh->query($charInsertQuery)) {
			// Get character ID of last character inserted, so we can use it to insert headers/skills/spells
			$lastCharacterQuery = 'SELECT MAX(characterID) AS lastCharacterID FROM characters';
			if ($lastCharacterResult = $this->dbh->query($lastCharacterQuery)) {
				while ($lastCharacter = $lastCharacterResult->fetch_assoc()) {
					$lastCharacterID = $lastCharacter['lastCharacterID'];
				}
			
				// Insert character headers
				for ($i = 0; $i < count($character['charHeaders']); $i++) {
					$headerInsertQuery = 	'INSERT INTO charheaders (characterID, headerID) ' .
											'VALUES(' . $lastCharacterID . ', ' . $character['charHeaders'][$i] . ')';
					$headerInsertResult = $this->dbh->query($headerInsertQuery);
				}
				
				// Insert character skills
				foreach ($character['charSkills'] as $curID => $curValue) {
					$skillInsertQuery = 	'INSERT INTO charskills (characterID, skillID, quantity) ' .
											'VALUES(' . $lastCharacterID . ', ' . $character['charSkills'][$curID]['id'] . ', ' . $character['charSkills'][$curID]['qty'] . ')';
					// echo 'skillInsertQuery: ' . $skillInsertQuery . '<br />';
					$skillInsertResult = $this->dbh->query($skillInsertQuery);
				}
				
				// Insert character spells
				for ($k = 0; $k < count($character['charSpells']); $k++) {
					$spellInsertQuery = 	'INSERT INTO charspells (characterID, spellID) ' .
											'VALUES(' . $lastCharacterID . ', ' . $character['charSpells'][$k] . ')';
					$spellInsertResult = $this->dbh->query($spellInsertQuery);
				}

				// Insert character feats
				for ($m = 0; $m < count($character['charFeats']); $m++) {
					$featInsertQuery = 	'INSERT INTO charfeats (characterID, featID) ' .
										'VALUES(' . $lastCharacterID . ', ' . $character['charFeats'][$m] . ')';
					$featInsertResult = $this->dbh->query($featInsertQuery);
				}
				
				// Add CP record with correct number of starting CP
				$cp = new CP();

				$cpRecord = $cp->createCPRecord('character', $lastCharacterID, $mysql['playerID'], $_SESSION['baseCP'], 14, 'Starting CP for ' . $mysql['charType'] . ' character "' . $mysql['charName'] . '."');

				// Transfer player CP to this character
				$cpTransferQuery = 	"UPDATE cp
									SET 
										cp.CPType = 'character',
										cp.characterID = " . $lastCharacterID . 
									" WHERE cp.CPType = 'player'
									AND cp.playerID = " . $mysql['playerID'];
				
				if ($cpTransferResult = $this->dbh->query($cpTransferQuery)) {
					$log->addLogEntry('Transferred player CP to character "' . $mysql['charName'] . '."', $_SESSION['playerID'], $mysql['playerID'], $lastCharacterID, 'Wizard', 'createCharacter');
				}

				// Find information for player this character belongs to
				$playerQuery = "SELECT p.firstName, p.lastName 
								FROM players p 
								WHERE p.playerID = " . $mysql['playerID'];

				if ($playerResult = $this->dbh->query($playerQuery)) {
					while ($player = $playerResult->fetch_assoc()) {
						$playerName = $player['firstName'] . ' ' . $player['lastName'];
					}
				} else {
					$playerName = '';
					$log->addLogEntry('Error retrieving player information: ' . $mysqli->error . '. Query: ' . $playerQuery, $_SESSION['playerID'], $mysql['playerID'], $lastCharacterID, 'Wizard', 'createCharacter');
				}
							
				// Add tracking entry to log for this player/character
				$log->addLogEntry('Created character "' . $character['charName'] . '" for player ' . $playerName . ' (ID: ' . $mysql['playerID'] . ').', $_SESSION['playerID'], $mysql['playerID'], $lastCharacterID, 'Wizard', 'createCharacter');
				
				// Add CP info to log for this player/character
				$log->addLogEntry('Automatically granted starting CP of ' . $_SESSION['baseCP'] . ' to character "' . $mysql['charName'] . '."', $_SESSION['playerID'], $mysql['playerID'], $lastCharacterID, 'Wizard', 'createCharacter');
				
				// Create success message to display at top of page. 
				$_SESSION['UIMessage'] = new UIMessage(	'success', 
														'Character created successfully',
														'<p>The character "' . $character['charName'] . '" has been created.</p>');
				
				$_SESSION['debug']->outputDebug();	
				return true;
			} else {
				// Problem retrieving last character ID
				// Create partial success message to display at top of page. 
				// TODO: This is a bad state and causes CP problems. Need to add transaction handling to deal with this. 
				$_SESSION['UIMessage'] = new UIMessage(	'error', 
														'Character created with warnings',
														'<p>The character "' . $character['charName'] . '" has been created, but we encountered problems
														while adding supporting information.</p>
														<p>Please verify that your character has all the expected headers, skills, etc. You can edit your character and re-select them if necessary. </p>
														<p>If you continue to encounter problems, please contact the <a href="mailto:' . $_SESSION['webmasterEmail'] . '">Administrator</a>.</p>');

				$logMsg = 'Error during character creation: Unable to retrieve last character ID. Error: ' . $this->dbh->error . '. Query: ' . $lastCharacterQuery;
				$log->addLogEntry($logMsg, $_SESSION['playerID'], $_SESSION['playerID'], '', 'Wizard', 'createCharacter', 'Error');
			}
		} else {
			// Problem inserting character
			$_SESSION['UIMessage'] = new UIMessage(	'error', 
														'Unable to add character',
														'<p>We weren\'t able to add the new character "' . $character['charName'] . '" to the system. </p>
														<p>Please try again. If you continue to encounter problems, please contact the <a href="mailto:' . $_SESSION['webmasterEmail'] . '">Administrator</a>.</p>');

			$logMsg = 'Error during character creation: Unable to insert new character into database. Error: ' . $this->dbh->error . '. Query: ' . $charInsertQuery;
			$log->addLogEntry($logMsg, $_SESSION['playerID'], $mysql['playerID'], '', 'Wizard', 'createCharacter', 'Error');
			return false;
		}
		// If we got to this point, we encountered some problems along the way. Return false. 
		$_SESSION['debug']->outputDebug();	
		return false;
	}
	
	// $character: Associative array of character data
	// TODO: Add transaction-handling around all inserts; we need to roll back if any part of the inserts fails. 
	public function updateCharacter($character, $characterID, $mode = 'wizard') {
		$log = new Log();
		$character['characterID'] = $characterID;

		if (!isset($character['playerID']) || is_empty($character['playerID'])) {
			$character['playerID'] = $_SESSION['playerID'];
		}

		// Check whether character CP values pass.
		if ($this->checkCharacterCP($character) == false) {
			return false;
		}
		
		// Validate data.
		// TODO: Add validation to make sure character has only one feat
		$validator = new Validator();
		if ($validator->validateCharacter($character) == false) {
			return false;
		}
		
		if ($mode != 'adminWizard') {
		  // Run update-specific validations.
		  // These validations do not apply in the admin wizard. 
		  if ($validator->validateUpdateCharacter($character, $characterID) == false) {
			  return false;
		  }
		}
						
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['playerID'] = db_escape($character['playerID'], $this->dbh);
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		$mysql['charName'] = db_escape($character['charName'], $this->dbh);
		$mysql['countryID'] = db_escape($character['countryID'], $this->dbh);
		$mysql['communityID'] = db_escape($character['communityID'], $this->dbh);
		$mysql['raceID'] = db_escape($character['raceID'], $this->dbh);
		$mysql['charType'] = db_escape($character['charType'], $this->dbh);
		$mysql['attribute1'] = db_escape($character['attribute1'], $this->dbh);
		$mysql['attribute2'] = db_escape($character['attribute2'], $this->dbh);
		$mysql['attribute3'] = db_escape($character['attribute3'], $this->dbh);
		$mysql['attribute4'] = db_escape($character['attribute4'], $this->dbh);
		$mysql['attribute5'] = db_escape($character['attribute5'], $this->dbh);
		$mysql['vitality'] = db_escape($character['vitality'], $this->dbh);
								
		$charUpdateQuery = "UPDATE characters c 
							SET c.playerID = " . $mysql['playerID'] . ",
							c.charName = '" . $mysql['charName'] . "', 
							c.countryID = " . $mysql['countryID'] . ", 
							c.communityID = " . $mysql['communityID'] . ", 
							c.raceID = " . $mysql['raceID'] . ", 
							c.charType = '" . $mysql['charType'] . "', 
							c.attribute1 = " . $mysql['attribute1'] . ", 
							c.attribute2 = " . $mysql['attribute2'] . ", 
							c.attribute3 = " . $mysql['attribute3'] . ", 
							c.attribute4 = " . $mysql['attribute4'] . ", 
							c.attribute5 = " . $mysql['attribute5'] . ", 
							c.vitality = " . $mysql['vitality'] . " 
							WHERE c.characterID = " . $mysql['characterID'];
		echo $charUpdateQuery . '<br />';
		
		if ($charUpdateResult = $this->dbh->query($charUpdateQuery)) {
			
			// DELETE AND RE-ADD HEADERS
			// Delete existing character headers
			$deleteHeaderQuery = 	"DELETE ch FROM charheaders ch 
									WHERE ch.characterID = " . $mysql['characterID'];
			
			if ($headerDeleteResult = $this->dbh->query($deleteHeaderQuery)) {
			  // If delete succeeded, insert character headers
			  for ($i = 0; $i < count($character['charHeaders']); $i++) {
				  $headerInsertQuery = 	'INSERT INTO charheaders (characterID, headerID) ' .
										  'VALUES(' . $mysql['characterID'] . ', ' . $character['charHeaders'][$i] . ')';
				  $headerInsertResult = $this->dbh->query($headerInsertQuery);
			  }
			} // end header delete
			
			// DELETE AND RE-ADD SKILLS
			// Delete existing character skills
			$deleteSkillQuery = 	"DELETE cs FROM charskills cs 
									WHERE cs.characterID = " . $mysql['characterID'];
			
			if ($skillDeleteResult = $this->dbh->query($deleteSkillQuery)) {
			  // If delete succeeded, insert character skills
			  foreach ($character['charSkills'] as $curID => $curValue) {
				  $skillInsertQuery = 	'INSERT INTO charskills (characterID, skillID, quantity) ' .
										  'VALUES(' . $mysql['characterID'] . ', ' . $character['charSkills'][$curID]['id'] . ', ' . $character['charSkills'][$curID]['qty'] . ')';
				  $skillInsertResult = $this->dbh->query($skillInsertQuery);
			  }
			} // end skill delete
			
			// DELETE AND RE-ADD SPELLS
			// Delete existing character spells
			$deleteSpellQuery = 	"DELETE cs FROM charspells cs 
									WHERE cs.characterID = " . $mysql['characterID'];
			
			if ($spellDeleteResult = $this->dbh->query($deleteSpellQuery)) {
			  // If delete succeeded, insert character spells
			  for ($k = 0; $k < count($character['charSpells']); $k++) {
				  $spellInsertQuery = 	'INSERT INTO charspells (characterID, spellID) ' .
										  'VALUES(' . $mysql['characterID'] . ', ' . $character['charSpells'][$k] . ')';
				  $spellInsertResult = $this->dbh->query($spellInsertQuery);
			  }
			} // end spell delete
			
			// DELETE AND RE-ADD FEATS
			// Delete existing character feats
			$deleteFeatQuery = 	"DELETE cf FROM charfeats cf
								WHERE cf.characterID = " . $mysql['characterID'];
			
			if ($featDeleteResult = $this->dbh->query($deleteFeatQuery)) {
			  // If delete succeeded, insert character feats
			  for ($k = 0; $k < count($character['charFeats']); $k++) {
				  $featInsertQuery = 	'INSERT INTO charfeats (characterID, featID) ' .
										'VALUES(' . $mysql['characterID'] . ', ' . $character['charFeats'][$k] . ')';
				  $featInsertResult = $this->dbh->query($featInsertQuery);
			  }
			} // end feat delete

			$log = new Log();

			// Transfer player CP to this character
			$cpTransferQuery = 	"UPDATE cp
								SET 
									cp.CPType = 'character',
									cp.characterID = " . $mysql['characterID'] . 
								" WHERE cp.CPType = 'player'
								AND cp.playerID = " . $mysql['playerID'];
			
			if ($cpTransferResult = $this->dbh->query($cpTransferQuery)) {
				$log->addLogEntry('Transferred player CP to character "' . $mysql['charName'] . '."', $_SESSION['playerID'], $mysql['playerID'], $mysql['characterID']);
			}
			
			// If everything succeeded, add tracking entry to log for this player/character
			$log->addLogEntry('Updated character "' . $character['charName'] .'."', $_SESSION['playerID'], $mysql['playerID'], $mysql['characterID']);
						
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Character updated successfully',
													'<p>The character "' . $character['charName'] . '" has been updated.</p>');
			
			return true;
		}

		return false;
	} // end of updateCharacter
	
	
	
	function __destruct() {
		$this->dbh->close(); // Close DB connection
	}
} // end of class


?>
