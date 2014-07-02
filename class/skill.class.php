<?php 
/************************************************************************
NAME: 	skill.class.php
NOTES:	This file holds all the classes for adding and maintaining skills. 
*************************************************************************/

// load configuration
// require_once('../includes/config.php');

class Skill {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllSkills() {
		$query = 	'SELECT s.skillID, s.skillName, s.skillCost, s.skillAccess, s.skillType, s.maxQuantity, h.headerID, h.headerName, sh.headerId, sh.skillID
					FROM skills s, headers h, skillsheaders sh
					WHERE s.skillID = sh.skillID
					AND h.headerID = sh.headerID 
					AND s.skillDeleted IS NULL 
					ORDER BY s.skillName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getPublicSkills() {
		$query = 	"SELECT s.skillID, s.skillName, s.skillCost, s.skillAccess, s.skillType, s.maxQuantity, h.headerID, h.headerName, sh.headerId, sh.skillID
					FROM skills s, headers h, skillsheaders sh
					WHERE s.skillID = sh.skillID
					AND h.headerID = sh.headerID 
					AND s.skillDeleted IS NULL 
					AND s.skillAccess = 'Public' 
					ORDER BY s.skillName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getHiddenSkills() {
		$query = 	"SELECT s.skillID, s.skillName, s.skillCost, s.skillAccess, s.maxQuantity, h.headerID, h.headerName, sh.headerId, sh.skillID
					FROM skills s, headers h, skillsheaders sh
					WHERE s.skillID = sh.skillID
					AND h.headerID = sh.headerID 
					AND s.skillDeleted IS NULL 
					AND s.skillAccess = 'Hidden' 
					ORDER BY s.skillName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getNPCSkills() {
		$query = 	"SELECT s.skillID, s.skillName, s.skillCost, s.skillAccess, s.maxQuantity, h.headerID, h.headerName, sh.headerId, sh.skillID
					FROM skills s, headers h, skillsheaders sh
					WHERE s.skillID = sh.skillID
					AND h.headerID = sh.headerID 
					AND s.skillDeleted IS NULL 
					AND s.skillAccess = 'NPC' 
					ORDER BY s.skillName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSkillSuggestions($term) {
		$mysql = array(); // Set up array for escaping values for DB
		$mysql['term'] = db_escape($term, $this->dbh);
		
		$query = 	"SELECT DISTINCT s.skillName
					FROM skills s
					WHERE s.skillDeleted IS NULL 
					AND s.skillName LIKE '%" . $mysql['term'] . "%'  
					ORDER BY s.skillName";
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSkill($skillID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$query = 	'SELECT s.skillID, s.skillName, s.skillCost, s.skillAccess, s.skillType, s.maxQuantity, s.costIncrement, s.shortDescription, s.skillDescription, s.cheatSheetNote, h.headerID, h.headerName, sh.headerId, sh.skillID
					FROM skills s, headers h, skillsheaders sh
					WHERE s.skillID = sh.skillID
					AND h.headerID = sh.headerID 
					AND s.skillID = ' . $mysql['skillID'] .
					' ORDER BY s.skillName';
		// echo $query;
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getDeletedSkills() {
		$query = 	'SELECT s.skillID, s.skillName, s.skillCost, s.skillAccess, s.skillType, s.maxQuantity, h.headerID, h.headerName, sh.headerId, sh.skillID
					FROM skills s, headers h, skillsheaders sh
					WHERE s.skillID = sh.skillID
					AND h.headerID = sh.headerID 
					AND s.skillDeleted IS NOT NULL 
					ORDER BY s.skillName';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSkillCharacters($skillID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$query = 	"SELECT hs.characterID, hs.skillID 
					FROM hiddenskillsaccess hs 
					WHERE hs.skillID = " . $mysql['skillID'];
		// echo $query;
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSkillChars($skillID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$query = 	"SELECT s.skillID, s.skillName, c.charName, c.characterID 
					FROM skills s, characters c, charskills cs 
					WHERE s.skillID = cs.skillID
					AND c.characterID = cs.characterID
					AND s.skillID = " . $mysql['skillID'];
		// echo $query;
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getSkillAttributeCosts($attributeNum, $skillIDList) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['attributeNum'] = db_escape($attributeNum, $this->dbh);

		$query = 	"SELECT sa.skillID, sa.attributeNum, sa.attributeCost, s.skillID, s.skillName, s.cheatSheetNote
					FROM skillattributecosts sa, skills s
					WHERE sa.skillID = s.skillID
					AND sa.attributeNum = " . $mysql['attributeNum'] .
					" AND sa.skillID IN (" . $skillIDList . ")" .
					" ORDER BY s.skillName, s.skillID";
					
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	public function getAttributeCostsBySkill($skillID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$query = 	"SELECT sa.skillID, sa.attributeNum, sa.attributeCost, s.skillID
					FROM skillattributecosts sa, skills s
					WHERE sa.skillID = s.skillID
					AND s.skillID = " . $mysql['skillID'];
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	// Perform a logical delete of a skill
	public function deleteSkill($skillID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$query =	"UPDATE skills s 
					SET s.skillDeleted = NOW() 
					WHERE s.skillID = " . $mysql['skillID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a logical delete of a skill
	public function undeleteSkill($skillID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$query =	"UPDATE skills s 
					SET s.skillDeleted = NULL 
					WHERE s.skillID = " . $mysql['skillID'];
					
		if ($result = $this->dbh->query($query)) {
			return true;
		}
		return false;
	}
	
	// Perform a permanent database delete of a skill
	public function purgeSkill($skillID) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);

		$logMsg = ''; // Initialize blank
		$playerID = ''; // Set to blank because this action doesn't relate to a specific player
		
		// Delete from any characters who have this skill
		$deleteCharSkills = "DELETE FROM charskills
							 WHERE charskills.skillID = " . $mysql['skillID'];
							  
		if (!$deleteCharSkillsResult = $this->dbh->query($deleteCharSkills)) {
			$logMsg .= 'Failed to delete skill ID ' . $mysql['skillID'] . ' from one or more characters.';
		}
		
		// Delete header-skill associations
		$deleteHeaderSkills = "DELETE FROM skillsheaders
							  WHERE skillsheaders.skillID = " . $mysql['skillID'];
							  
		if (!$deleteHeaderSkillsResult = $this->dbh->query($deleteHeaderSkills)) {
			$logMsg .= 'Failed to remove skill ID ' . $mysql['skillID'] . ' from one or more headers. ';
		}
		
		// Delete skill-spell associations
		$deleteSpellSkills = "DELETE FROM spellskills
							  WHERE spellskills.skillID = " . $mysql['skillID'];
							  
		if (!$deleteSpellSkillsResult = $this->dbh->query($deleteSpellSkills)) {
			$logMsg .= 'Failed to remove one or more spells from skill ID ' . $mysql['skillID'] . ' . ';
		}
		
		// Delete skill attribute costs
		$deleteSkillAttributes = "DELETE FROM skillattributecosts
								  WHERE skillattributecosts.skillID = " . $mysql['skillID'];
							  
		if (!$deleteSkillAttributesResult = $this->dbh->query($deleteSkillAttributes)) {
			$logMsg .= 'Failed to remove one or more attribute costs from skill ID ' . $mysql['skillID'] . ' . ';
		}
		
		// Delete hidden header access for this character
		$deleteHiddenSkillAccess = "DELETE FROM hiddenskillsaccess
							 		WHERE hiddenskillsaccess.skillID = " . $mysql['skillID'];
									
		if (!$deleteHiddenSkillsResult = $this->dbh->query($deleteHiddenSkillAccess)) {
			$logMsg .= 'Failed to delete hidden header access for skill ID ' . $mysql['skillID'] . '. ';
		}
		
		// Only delete skill if log message is still blank 
		// (which means there have been no errors). 
		if ($logMsg == '') {
		
		  $deleteSkillQuery =	"DELETE FROM skills
								WHERE skills.skillID = " . $mysql['skillID'];
					
		  if ($result = $this->dbh->query($deleteSkillQuery)) {
			  return true;
		  }
		} else {
			echo $logMsg . '<br />';
			$log = new Log();
			$log->addLogEntry($logMsg, $playerID, $characterID);	
		}
		
		return false;
	} // end of purgeSkill
	
	// $skill: Associative array of skill data
	public function addSkill($skill) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateSkill($skill) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillName'] = db_escape($skill['skillName'], $this->dbh);
		$mysql['skillCost'] = db_escape($skill['skillCost'], $this->dbh);
		$mysql['headerID'] = db_escape($skill['headerID'], $this->dbh);
		$mysql['skillAccess'] = db_escape($skill['skillAccess'], $this->dbh);
		$mysql['skillType'] = db_escape($skill['skillType'], $this->dbh);
		$mysql['maxQuantity'] = $skill['maxQuantity'] != '' ? db_escape($skill['maxQuantity'], $this->dbh) : 'NULL'; // Set age to null for correct insert
		$mysql['costIncrement'] = db_escape($skill['costIncrement'], $this->dbh);
		$mysql['shortDescription'] = db_escape($skill['shortDescription'], $this->dbh);
		$mysql['skillDescription'] = db_escape($skill['skillDescription'], $this->dbh);
		$mysql['cheatSheetNote'] = db_escape($skill['cheatSheetNote'], $this->dbh);

		$html = array(); // Initialize blank
		$html['skillName'] = htmlentities($skill['skillName']);
		
		// Insert skills
		$query = 	"INSERT INTO skills (skillName, skillCost, skillAccess, skillType, maxQuantity, costIncrement, shortDescription, skillDescription, cheatSheetNote)
					VALUES ('" . 
						$mysql['skillName'] . "'," . 
						$mysql['skillCost'] . ",'" . 
						$mysql['skillAccess'] . "','" . 
						$mysql['skillType'] . "'," . 
						$mysql['maxQuantity'] . "," . 
						$mysql['costIncrement'] . ",'" . 
						$mysql['shortDescription'] . "','" . 
						$mysql['skillDescription'] . "','" . 
						$mysql['cheatSheetNote'] . "')";
		
		
		// TODO: Wrap this in a transaction so that we can roll back if part of the insert fails. 
		if ($skillInsertResult = $this->dbh->query($query)) {
			// Get skill ID of last skill inserted, so we can use it to insert the access list
			$lastSkillID = $this->getLastInsertedSkill(); 
			
			$headerInsertQuery = 	'INSERT INTO skillsheaders (skillID, headerID) ' .
									'VALUES(' . $lastSkillID . ',' . $mysql['headerID'] . ')';
			$headerInsertResult = $this->dbh->query($headerInsertQuery);
			
			// Loop through the three attribute values and insert attributes for this skill
			for ($j = 1; $j <= 3; $j++) {
				
				$curCostFld = 'attributeCost' . $j;
				$curAttrNumFld = 'attribute' . $j;
				
				// Only do insert if both the attribute number and cost fields exist and have values
				if (isset($_POST[$curAttrNumFld]) && $_POST[$curAttrNumFld] != '' && isset($_POST[$curCostFld]) && $_POST[$curCostFld] != '') {
					$attributeQuery = 	"INSERT INTO skillattributecosts (skillID, attributeNum, attributeCost) 
										VALUES (" . $lastSkillID . "," . $_POST[$curAttrNumFld] . "," . $_POST[$curCostFld] . ")";
					// echo $attributeQuery . '<br />';
					$attributeResult = $this->dbh->query($attributeQuery);
				}
			
			}
			
			// If this is a hidden skill and the user has selected specific characters to access it,
			// insert a row for each character.  
			if ($skill['characterID'] && $skill['characterID'] != '' && $skill['skillAccess'] == 'Hidden') {
				// Insert character skills
				for ($i = 0; $i < count($skill['characterID']); $i++) {
					$charInsertQuery = 	'INSERT INTO hiddenskillsaccess (skillID, characterID) ' .
										'VALUES(' . $lastSkillID . ',' . $skill['characterID'][$i] . ')';
					$charInsertResult = $this->dbh->query($charInsertQuery);
				}
			} 
			
			// If all went well, create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Skill added successfully',
													'<p>The skill "' . $html['skillName'] . '" has been created.</p>');
			return true;
		} else {
			return false;
		}

	}
	
	public function updateSkill($skill, $skillID) {
		// Validate data
		$validator = new Validator();
		if ($validator->validateSkill($skill) == false) {
			return false;
		}
		
		// If we made it this far, escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['skillID'] = db_escape($skillID, $this->dbh);
		$mysql['skillName'] = db_escape($skill['skillName'], $this->dbh);
		$mysql['skillCost'] = db_escape($skill['skillCost'], $this->dbh);
		$mysql['headerID'] = db_escape($skill['headerID'], $this->dbh);
		$mysql['skillAccess'] = db_escape($skill['skillAccess'], $this->dbh);
		$mysql['skillType'] = db_escape($skill['skillType'], $this->dbh);
		$mysql['maxQuantity'] = $skill['maxQuantity'] != '' ? db_escape($skill['maxQuantity'], $this->dbh) : 'NULL'; // Set max quantity to null for correct insert
		$mysql['costIncrement'] = db_escape($skill['costIncrement'], $this->dbh);
		$mysql['shortDescription'] = db_escape($skill['shortDescription'], $this->dbh);
		$mysql['skillDescription'] = db_escape($skill['skillDescription'], $this->dbh);
		$mysql['cheatSheetNote'] = db_escape($skill['cheatSheetNote'], $this->dbh);

		$html = array(); // Initialize blank
		$html['skillName'] = htmlentities($skill['skillName']);
		
		$query = 	"UPDATE skills s 
					SET s.skillName = '" . $mysql['skillName'] . "', 
					s.skillCost =" . $mysql['skillCost'] . ", 
					s.skillAccess = '" . $mysql['skillAccess'] . "', 
					s.skillType = '" . $mysql['skillType'] . "',
					s.maxQuantity = " . $mysql['maxQuantity'] . ", 
					s.costIncrement = " . $mysql['costIncrement'] . ", 
					s.shortDescription = '" . $mysql['shortDescription'] . "', 
					s.skillDescription = '" . $mysql['skillDescription'] . "', 
					s.cheatSheetNote = '" . $mysql['cheatSheetNote'] . "'" . " 
					WHERE s.skillID = " . $mysql['skillID'];
		
		// echo $query; 
		
		if ($skillUpdateResult = $this->dbh->query($query)) {
			// Delete any existing headers for this skill
			$deleteHeaderQuery = "DELETE sh FROM skillsheaders sh 
								WHERE sh.skillID = " . $mysql['skillID'];
			
			if ($deleteHeaderResult = $this->dbh->query($deleteHeaderQuery)) {
				//Insert new header(s) for this skill
				$headerInsertQuery = 	'INSERT INTO skillsheaders (skillID, headerID) ' .
										'VALUES(' . $mysql['skillID'] . ',' . $mysql['headerID'] . ')';
				// echo 'Header insert query: ' . $headerInsertQuery . '<br />';
				$headerInsertResult = $this->dbh->query($headerInsertQuery);
			}
				
			// If this is a hidden header, delete the existing access list and recreate it. 
			if ($skill['skillAccess'] == 'Hidden') {
				$deleteQuery = "DELETE hs FROM hiddenskillsaccess hs 
								WHERE hs.skillID = " . $mysql['skillID'];
				
				if ($deleteResult = $this->dbh->query($deleteQuery)) {
					// If the user has specified a new list of access characters, insert them.
					if ($skill['characterID'] && $skill['characterID'] != '') {
						for ($i = 0; $i < count($skill['characterID']); $i++) {
							$charInsertQuery = 	'INSERT INTO hiddenskillsaccess (skillID, characterID) ' .
												'VALUES(' . $mysql['skillID'] . ',' . $skill['characterID'][$i] . ')';
							$charInsertResult = $this->dbh->query($charInsertQuery);
						}
					}
				}
			}
			
			// Delete skill attribute costs and re-insert
			$deleteCostsQuery = "DELETE sa
								FROM skillattributecosts sa
								WHERE sa.skillID = " . $mysql['skillID'];
									
			if ($deleteCostsResult = $this->dbh->query($deleteCostsQuery)) {
				// If delete succeeds, insert new values
				// Loop through the three attribute values and insert attributes for this skill
				for ($j = 1; $j <= 3; $j++) {
					
					$curCostFld = 'attributeCost' . $j;
					$curAttrNumFld = 'attribute' . $j;
					
					// Only do insert if both the attribute number and cost fields exist and have values
					if (isset($_POST[$curAttrNumFld]) && $_POST[$curAttrNumFld] != '' && isset($_POST[$curCostFld]) && $_POST[$curCostFld] != '') {
						$attributeQuery = 	"INSERT INTO skillattributecosts (skillID, attributeNum, attributeCost) 
											VALUES (" . $mysql['skillID'] . "," . $_POST[$curAttrNumFld] . "," . $_POST[$curCostFld] . ")";
						echo $attributeQuery . '<br />';
						$attributeResult = $this->dbh->query($attributeQuery);
					}
				}
			}
			
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Skill updated successfully',
													'<p>The skill "' . $html['skillName'] . '" has been updated.</p>');
			return true;
		} else {
			return false;
		} // end of insert success condition
	}
	
	private function getLastInsertedSkill() {
		$lastSkillQuery = 'SELECT MAX(skillID) AS lastSkillID FROM skills';
		if ($lastSkillResult = $this->dbh->query($lastSkillQuery)) {
			while ($lastSkill = $lastSkillResult->fetch_assoc()) {
				return $lastSkill['lastSkillID'];
			}
		} 
	}
	
} // end of class