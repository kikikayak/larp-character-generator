<?php 
/************************************************************************
NAME: 	spell.class.php
NOTES:	This file holds all the classes for adding and maintaining spells. 
*************************************************************************/

class Spell {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getTotalSpells() {
		$totalSpells = 0; // Initialize to 0
		
		$query = 	'SELECT COUNT(sp.spellID) as totalSpells
					FROM spells sp
					WHERE sp.spellDeleted IS NULL';
		
		if ($result = $this->dbh->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$totalSpells = $row['totalSpells'];
			}
		}
		return $totalSpells;
	}
	
	public function getAllSpells() {
		$query = 	'SELECT sp.spellID, sp.spellName, sp.spellCost, sp.spellAccess, s.skillID, s.skillName, ss.spellID, ss.skillID
					FROM spells sp, skills s, spellskills ss
					WHERE sp.spellID = ss.spellID
					AND s.skillID = ss.skillID
					AND sp.spellDeleted IS NULL 
					ORDER BY sp.spellName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getPublicSpells() {
		$query = 	"SELECT sp.spellID, sp.spellName, sp.spellCost, sp.spellAccess, s.skillID, s.skillName, ss.spellID, ss.skillID
					FROM spells sp, skills s, spellskills ss
					WHERE sp.spellID = ss.spellID
					AND s.skillID = ss.skillID
					AND sp.spellDeleted IS NULL 
					AND sp.spellAccess = 'Public'  
					ORDER BY sp.spellName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHiddenSpells() {
		$query = 	"SELECT sp.spellID, sp.spellName, sp.spellCost, sp.spellAccess, s.skillID, s.skillName, ss.spellID, ss.skillID
					FROM spells sp, skills s, spellskills ss
					WHERE sp.spellID = ss.spellID
					AND s.skillID = ss.skillID
					AND sp.spellDeleted IS NULL 
					AND sp.spellAccess = 'Hidden' 
					ORDER BY sp.spellName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getNPCSpells() {
		$query = 	"SELECT sp.spellID, sp.spellName, sp.spellCost, sp.spellAccess, s.skillID, s.skillName, ss.spellID, ss.skillID
					FROM spells sp, skills s, spellskills ss
					WHERE sp.spellID = ss.spellID
					AND s.skillID = ss.skillID
					AND sp.spellDeleted IS NULL 
					AND sp.spellAccess = 'NPC' 
					ORDER BY sp.spellName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSpellSuggestions($term) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['term'] = db_escape($term, $this->dbh);
		
		$query = 	"SELECT DISTINCT sp.spellName
					FROM spells sp
					WHERE sp.spellDeleted IS NULL 
					AND sp.spellName LIKE '%" . $mysql['term'] . "%'  
					ORDER BY sp.spellName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSpell($spellID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);

		$query = 	'SELECT sp.spellID, sp.spellName, sp.spellCost, sp.spellAccess, sp.spellShortDescription, sp.spellDescription, sp.spellCheatSheetNote, s.skillID, s.skillName, ss.spellID, ss.skillID
					FROM spells sp, skills s, spellskills ss
					WHERE sp.spellID = ss.spellID
					AND s.skillID = ss.skillID 
					AND sp.spellID = ' . $mysql['spellID'] .
					' ORDER BY sp.spellName';

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getDeletedSpells() {
		$query = 	'SELECT sp.spellID, sp.spellName, sp.spellCost, sp.spellAccess, s.skillID, s.skillName, ss.spellID, ss.skillID
					FROM spells sp, skills s, spellskills ss
					WHERE sp.spellID = ss.spellID
					AND s.skillID = ss.skillID
					AND sp.spellDeleted IS NOT NULL 
					ORDER BY sp.spellName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSpellAttributeCosts($attributeNum, $spellIDList) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['attributeNum'] = db_escape($attributeNum, $this->dbh);

		$query = 	"SELECT sa.spellID, sa.attributeNum, sa.attributeCost, s.spellID, s.spellName, s.spellCost
					FROM spellattributecosts sa, spells s
					WHERE sa.spellID = s.spellID
					AND sa.attributeNum = " . $mysql['attributeNum'] .
					" AND sa.spellID IN (" . $spellIDList . ")" .
					" ORDER BY s.spellName, s.spellID";
					
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getAttributeCostsBySpell($spellID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);

		$query = 	"SELECT sa.spellID, sa.attributeNum, sa.attributeCost, sp.spellID
					FROM spellattributecosts sa, spells sp
					WHERE sa.spellID = sp.spellID
					AND sp.spellID = " . $mysql['spellID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	private function getLastInsertedSpell() {
		$lastSpellQuery = 'SELECT MAX(spellID) AS lastSpellID FROM spells';
		if ($lastSpellResult = $this->dbh->query($lastSpellQuery)) {
			while ($lastSpell = $lastSpellResult->fetch_assoc()) {
				return $lastSpell['lastSpellID'];
			}
		} 
	}
	
	public function getSpellCharAccess($spellID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);

		$query = 	"SELECT hs.characterID, hs.spellID 
					FROM hiddenspellsaccess hs 
					WHERE hs.spellID = " . $mysql['spellID'];

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSpellChars($spellID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);

		$query = 	"SELECT sp.spellID, sp.spellName, c.charName, c.characterID, cs.spellID, cs.characterID 
					FROM spells sp, characters c, charspells cs 
					WHERE sp.spellID = cs.spellID
					AND c.characterID = cs.characterID
					AND sp.spellID = " . $mysql['spellID'];

		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	// Perform a logical delete of a spell
	public function deleteSpell($spellID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);

		$query =	"UPDATE spells sp 
					SET sp.spellDeleted = NOW() 
					WHERE sp.spellID = " . $mysql['spellID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a logical undelete of a spell
	public function undeleteSpell($spellID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);

		$query =	"UPDATE spells sp 
					SET sp.spellDeleted = NULL  
					WHERE sp.spellID = " . $mysql['spellID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a permanent database delete of a spell
	public function purgeSpell($spellID) {
		$logMsg = ''; // Initialize blank
		$playerID = ''; // Set to blank because this action doesn't relate to a specific player

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);
		
		// Delete from any characters who have this spell
		$deleteCharSpells = "DELETE FROM charspells
							 WHERE charspells.spellID = " . $mysql['spellID'];
							  
		if (!$deleteCharSpellsResult = $this->dbh->query($deleteCharSpells)) {
			$logMsg .= 'Failed to delete spell ID ' . $mysql['spellID'] . ' from one or more characters.';
		}
		
		// Delete skill-spell associations
		$deleteSpellSkills = "DELETE FROM spellskills
							  WHERE spellskills.spellID = " . $mysql['spellID'];
							  
		if (!$deleteSpellSkillsResult = $this->dbh->query($deleteSpellSkills)) {
			$logMsg .= 'Failed to remove spell ID ' . $mysql['spellID'] . ' from one or more skills. ';
		}
		
		// Delete spell attribute costs
		$deleteSpellAttributes = "DELETE FROM spellattributecosts
								  WHERE spellattributecosts.spellID = " . $mysql['spellID'];
							  
		if (!$deleteSpellAttributesResult = $this->dbh->query($deleteSpellAttributes)) {
			$logMsg .= 'Failed to remove one or more attribute costs from spell ID ' . $mysql['spellID'] . ' . ';
		}
		
		// Only delete spell if log message is still blank 
		// (which means there have been no errors). 
		if ($logMsg == '') {
		
		  $deleteSpellQuery =	"DELETE FROM spells
								WHERE spells.spellID = " . $mysql['spellID'];
					
		  if ($result = $this->dbh->query($deleteSpellQuery)) {
			  return true;
		  }
		} else {
			echo $logMsg . '<br />';
			$log = new Log();
			$log->addLogEntry($logMsg, $playerID, $characterID);	
		}
		
		return false;
	} // end of purgeSpell
	
	// $spell: Associative array of spell data
	public function addSpell($spell) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateSpell($spell) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellName'] = db_escape($spell['spellName'], $this->dbh);
		$mysql['spellCost'] = db_escape($spell['spellCost'], $this->dbh);
		$mysql['spellAccess'] = db_escape($spell['spellAccess'], $this->dbh);
		$mysql['skillID'] = db_escape($spell['skillID'], $this->dbh);
		$mysql['spellShortDescription'] = db_escape($spell['spellShortDescription'], $this->dbh);
		$mysql['spellDescription'] = db_escape($spell['spellDescription'], $this->dbh);
		$mysql['spellCheatSheetNote'] = db_escape($spell['spellCheatSheetNote'], $this->dbh);

		$html = array(); // Initialize blank
		$html['spellName'] = htmlentities($spell['spellName']);
		
		// Insert spells
		$query = 	"INSERT INTO spells (spellName, spellCost, spellAccess, spellShortDescription, spellDescription, spellCheatSheetNote)
					VALUES ('" . 
								$mysql['spellName'] . "'," . 
								$mysql['spellCost'] . ",'" . 
								$mysql['spellAccess'] . "','" .
								$mysql['spellShortDescription'] . "','" . 
								$mysql['spellDescription'] . "','" . 
								$mysql['spellCheatSheetNote'] . "')";
		
		
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($spellInsertResult = $this->dbh->query($query)) {
			// Get spell ID of last spell inserted, so we can use it to insert the access list
			$lastSpellID = $this->getLastInsertedSpell(); 
			
			$spellInsertQuery = 	'INSERT INTO spellskills (spellID, skillID) ' .
									'VALUES(' . $lastSpellID . ',' . $mysql['skillID'] . ')';
			$spellInsertResult = $this->dbh->query($spellInsertQuery);
			
			// If this is a hidden spell and the user has selected specific characters to access it,
			// insert a row for each character.  
			if (isset($spell['characterID']) && $spell['characterID'] != '' && $spell['spellAccess'] == 'Hidden') {
				// Insert character spells
				for ($i = 0; $i < count($spell['characterID']); $i++) {
					$charInsertQuery = 	'INSERT INTO hiddenspellsaccess (spellID, characterID) ' .
										'VALUES(' . $lastSpellID . ',' . $spell['characterID'][$i] . ')';
					$charInsertResult = $this->dbh->query($charInsertQuery);
				}
			}
			
			// Loop through the three attribute values and insert attributes for this spell
			for ($j = 1; $j <= 3; $j++) {
				
				$curCostFld = 'attributeCost' . $j;
				$curAttrNumFld = 'attribute' . $j;
				
				// Only do insert if both the attribute number and cost fields exist and have values
				if (isset($_POST[$curAttrNumFld]) && $_POST[$curAttrNumFld] != '' && isset($_POST[$curCostFld]) && $_POST[$curCostFld] != '') {
					$mysql['curAttrNumFld'] = db_escape($_POST[$curAttrNumFld], $this->dbh);
					$mysql['curCostFld'] = db_escape($_POST[$curCostFld], $this->dbh);

					$attributeQuery = 	"INSERT INTO spellattributecosts (spellID, attributeNum, attributeCost) 
										VALUES (" . $lastSpellID . "," . $mysql['curAttrNumFld'] . "," . $mysql['curCostFld'] . ")";
					// echo $attributeQuery . '<br />';
					$attributeResult = $this->dbh->query($attributeQuery);
				}
			
			}
			
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Spell Added Successfully',
													'<p>The spell "' . $html['spellName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}

	}
	
	public function updateSpell($spell, $spellID) {
		// Validate data
		$validator = new Validator();
		if ($validator->validateSpell($spell) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['spellID'] = db_escape($spellID, $this->dbh);
		$mysql['spellName'] = db_escape($spell['spellName'], $this->dbh);
		$mysql['spellCost'] = db_escape($spell['spellCost'], $this->dbh);
		$mysql['spellAccess'] = db_escape($spell['spellAccess'], $this->dbh);
		$mysql['skillID'] = db_escape($spell['skillID'], $this->dbh);
		$mysql['spellShortDescription'] = db_escape($spell['spellShortDescription'], $this->dbh);
		$mysql['spellDescription'] = db_escape($spell['spellDescription'], $this->dbh);
		$mysql['spellCheatSheetNote'] = db_escape($spell['spellCheatSheetNote'], $this->dbh);
		
		$query = 	"UPDATE spells sp 
					SET sp.spellName = '" . $mysql['spellName'] . "', 
					sp.spellCost =" . $mysql['spellCost'] . ", 
					sp.spellAccess = '" . $mysql['spellAccess'] . "',
					sp.spellShortDescription = '" . $mysql['spellShortDescription'] . "', 
					sp.spellDescription = '" . $mysql['spellDescription'] . "', 
					sp.spellCheatSheetNote = '" . $mysql['spellCheatSheetNote'] . "'" .
					" WHERE sp.spellID = " . $mysql['spellID'];
		
		// echo $query; 
		
		if ($spellUpdateResult = $this->dbh->query($query)) {
			// Delete any existing skills for this spell
			$deleteSkillQuery = "DELETE ss FROM spellskills ss 
								WHERE ss.spellID = " . $mysql['spellID'];
			
			if ($deleteSkillResult = $this->dbh->query($deleteSkillQuery)) {
				//Insert new skill(s) for this spell
				$skillInsertQuery = 	'INSERT INTO spellskills (spellID, skillID) ' .
										'VALUES(' . $mysql['spellID'] . ',' . $mysql['skillID'] . ')';

				$skillInsertResult = $this->dbh->query($skillInsertQuery);
			}
			
			// If this is a hidden header, delete the existing access list and recreate it. 
			if ($spell['spellAccess'] == 'Hidden') {
				$deleteCharQuery = "DELETE hs FROM hiddenspellsaccess hs 
									WHERE hs.spellID = " . $mysql['spellID'];
				
				if ($deleteCharResult = $this->dbh->query($deleteCharQuery)) {
					// If the user has specified a new list of access characters, insert them.
					if ($spell['characterID'] && $spell['characterID'] != '') {
						for ($i = 0; $i < count($spell['characterID']); $i++) {
							$charInsertQuery = 	'INSERT INTO hiddenspellsaccess (spellID, characterID) ' .
												'VALUES(' . $mysql['spellID'] . ',' . $spell['characterID'][$i] . ')';
							$charInsertResult = $this->dbh->query($charInsertQuery);
						}
					}
				}
			}
							
			// Delete spell attribute costs and re-insert
			$deleteCostsQuery = "DELETE sa
								FROM spellattributecosts sa
								WHERE sa.spellID = " . $mysql['spellID'];
								
			// echo $deleteCostsQuery . '<br />';
									
			if ($deleteCostsResult = $this->dbh->query($deleteCostsQuery)) {
				// If delete succeeds, insert new values
				// Loop through the three attribute values and insert attributes for this skill
				for ($j = 1; $j <= 3; $j++) {
					
					$curCostFld = 'attributeCost' . $j;
					$curAttrNumFld = 'attribute' . $j;
					
					// Only do insert if both the attribute number and cost fields exist and have values
					if (isset($_POST[$curAttrNumFld]) && $_POST[$curAttrNumFld] != '' && isset($_POST[$curCostFld]) && $_POST[$curCostFld] != '') {
						$mysql['curAttrNumFld'] = db_escape($_POST[$curAttrNumFld], $this->dbh);
						$mysql['curCostFld'] = db_escape($_POST[$curCostFld], $this->dbh);

						$attributeQuery = 	"INSERT INTO spellattributecosts (spellID, attributeNum, attributeCost) 
											VALUES (" . $mysql['spellID'] . "," . $mysql['curAttrNumFld'] . "," . $mysql['curCostFld'] . ")";
						// echo $attributeQuery . '<br />';
						$attributeResult = $this->dbh->query($attributeQuery);
					}
				}
			}
			
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Spell Updated Successfully',
													'<p>The spell "' . $html['spellName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		} // end of insert success condition
	} // end of updateSpell
	
	
} // end of class
