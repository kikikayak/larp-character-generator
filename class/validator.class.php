<?php 
/************************************************************************
NAME: 	validator.class.php
NOTES:	This file holds all the classes for validating form fields. 
*************************************************************************/

// load configuration
// require_once('../includes/config.php');

class Validator {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function validateLogin($user) {
	  $errorList = array(); 
	  
	  if (is_empty($user['email'])) {
		  $errorList['email']['fldLbl'] = 'Email';
		  $errorList['email']['error'] = 'Please enter your email address';
	  } else if (!isValidEmail($user['email'])) {
		  $errorList['email']['fldLbl'] = 'Email';
		  $errorList['email']['error'] = 'Should be a valid email address in the format email@example.com';
	  } else if (strlen($user['email']) > 64) {
		  $errorList['email']['fldLbl'] = 'Email';
		  $errorList['email']['error'] = 'Must be 64 characters or less';
	  }
	  
	  if (is_empty($user['password'])) {
		  $errorList['password']['fldLbl'] = 'Password';
		  $errorList['password']['error'] = 'Please enter a password';
	  } else if (!isValidPassword($user['password'])) { // Password validation includes maxlength
		  $errorList['password']['fldLbl'] = 'Password';
		  $errorList['password']['error'] = 'Invalid password';
	  }
	  
	  // If data doesn't pass validation, set error message for display in the UI and return false. 
	  if (count($errorList) > 0) {
		  $_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
		  return false;
	  }
			  
	  return true;
  	} // end of validateLogin
	
	public function validateLostPassword($user) {
	  $errorList = array(); 
	  
	  if (is_empty($user['email'])) {
		  $errorList['email']['fldLbl'] = 'Email';
		  $errorList['email']['error'] = 'Please enter an email address';
	  } else if (!isValidEmail($user['email'])) {
		  $errorList['email']['fldLbl'] = 'Email';
		  $errorList['email']['error'] = 'Should be a valid email address in the format email@example.com';
	  } else if (strlen($user['email']) > 50) {
		  $errorList['email']['fldLbl'] = 'Email';
		  $errorList['email']['error'] = 'Must be 50 characters or less';
	  }
	  
	  // If data doesn't pass validation, set error message for display in the UI and return false. 
	  if (count($errorList) > 0) {
		  $_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
		  return false;
	  }
			  
	  return true;
  	} // end of validateLostPassword
	
	public function validatePasswordReset($user) {
	  $errorList = array(); 
	  
	  if (is_empty($user['tmpPassword'])) {
		  $errorList['tmpPassword']['fldLbl'] = 'Temporary Password';
		  $errorList['tmpPassword']['error'] = 'Please enter your temporary password';
	  } else if (!isValidTmpPassword($user['tmpPassword'])) {
		  $errorList['tmpPassword']['fldLbl'] = 'Temporary Password';
		  $errorList['tmpPassword']['error'] = 'Invalid temporary password';
	  }
	  
	  if (is_empty($user['newPassword'])) {
		  $errorList['newPassword']['fldLbl'] = 'Password';
		  $errorList['newPassword']['error'] = 'Please enter a password';
	  } else if (!isValidPassword($user['newPassword'])) { // Includes maxlength validation
		  $errorList['newPassword']['fldLbl'] = 'Password';
		  $errorList['newPassword']['error'] = 'Invalid password';
	  }
	  
	  if (is_empty($user['confirmPassword'])) {
		  $errorList['confirmPassword']['fldLbl'] = 'Confirm Password';
		  $errorList['confirmPassword']['error'] = 'Please confirm your password';
	  } else if ($user['newPassword'] != $user['confirmPassword']) {
		  $errorList['confirmPassword']['fldLbl'] = 'Confirm Password';
		  $errorList['confirmPassword']['error'] = 'Does not match password';
	  }
	  
	  // If data doesn't pass validation, set error message for display in the UI and return false. 
	  if (count($errorList) > 0) {
		  $_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
		  return false;
	  }
			  
	  return true;
  	} // end of validatePasswordReset
	
	public function validateCharacter($character) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (isset($character['playerID']) && !is_empty($character['playerID']) && !ctype_digit($character['playerID'])) { // Check if all characters are digits (ID must be an integer)
			$errorList['playerID']['fldLbl'] = 'Player';
			$errorList['playerID']['error'] = 'Invalid player';
		}

		if (is_empty($character['charName'])) {
			$errorList['charName']['fldLbl'] = 'Character Name';
			$errorList['charName']['error'] = 'Please enter a name';
		} else if (!isValidText($character['charName'])) {
			$errorList['charName']['fldLbl'] = 'Character Name';
			$errorList['charName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($character['charType'])) {
			$errorList['charType']['fldLbl'] = 'Character Type';
			$errorList['charType']['error'] = 'Please select a character type';
		} else if ($character['charType'] != 'PC' && $character['charType'] != 'NPC') {
			$errorList['charType']['fldLbl'] = 'Character Type';
			$errorList['charType']['error'] = 'Please specify PC or NPC';
		}
				
		if (is_empty($character['countryID'])) {
			$errorList['countryID']['fldLbl'] = 'Country of Origin';
			$errorList['countryID']['error'] = 'Please select a country';
		} else if (!ctype_digit($character['countryID'])) { // Check if all characters are digits (ID must be an integer)
			$errorList['countryID']['fldLbl'] = 'Country of Origin';
			$errorList['countryID']['error'] = 'Invalid country';
		}
		
		if (is_empty($character['communityID'])) {
			$errorList['communityID']['fldLbl'] = $_SESSION['communityLabel'];
			$errorList['communityID']['error'] = 'Please select a ' . $_SESSION['communityLabel'];
		} else if (!ctype_digit($character['communityID'])) { // Check if all characters are digits (ID must be an integer)
			$errorList['communityID']['fldLbl'] = $_SESSION['communityLabel'];
			$errorList['communityID']['error'] = 'Invalid ' . $_SESSION['communityLabel'];
		}
		
		if (isset($character['raceID']) && is_empty($character['raceID'])) {
			$errorList['raceID']['fldLbl'] = 'Race';
			$errorList['raceID']['error'] = 'Please select a race';
		} else if (!ctype_digit($character['raceID'])) { // Check if all characters are digits (ID must be an integer)
			$errorList['raceID']['fldLbl'] = 'Race';
			$errorList['raceID']['error'] = 'Invalid race';
		}
		
		if (is_empty($character['attribute1'])) {
			$errorList['attribute1']['fldLbl'] = $_SESSION['attribute1Label'];
			$errorList['attribute1']['error'] = 'Please enter a value';
		} else if (!ctype_digit($character['attribute1'])) { // Check if all characters are digits (must be a whole number)
			$errorList['attribute1']['fldLbl'] = $_SESSION['attribute1Label'];
			$errorList['attribute1']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($character['attribute2'])) {
			$errorList['attribute2']['fldLbl'] = $_SESSION['attribute2Label'];
			$errorList['attribute2']['error'] = 'Please enter a value';
		} else if (!ctype_digit($character['attribute2'])) { // Check if all characters are digits (must be a whole number)
			$errorList['attribute2']['fldLbl'] = $_SESSION['attribute2Label'];
			$errorList['attribute2']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($character['attribute3'])) {
			$errorList['attribute3']['fldLbl'] = $_SESSION['attribute3Label'];
			$errorList['attribute3']['error'] = 'Please enter a value';
		} else if (!ctype_digit($character['attribute3'])) { // Check if all characters are digits (must be a whole number)
			$errorList['attribute3']['fldLbl'] = $_SESSION['attribute3Label'];
			$errorList['attribute3']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($character['attribute4'])) {
			$errorList['attribute4']['fldLbl'] = $_SESSION['attribute4Label'];
			$errorList['attribute4']['error'] = 'Please enter a value';
		} else if (!ctype_digit($character['attribute4'])) { // Check if all characters are digits (must be a whole number)
			$errorList['attribute4']['fldLbl'] = $_SESSION['attribute4Label'];
			$errorList['attribute4']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($character['attribute5'])) {
			$errorList['attribute5']['fldLbl'] = $_SESSION['attribute5Label'];
			$errorList['attribute5']['error'] = 'Please enter a value';
		} else if (!ctype_digit($character['attribute5'])) { // Check if all characters are digits (must be a whole number)
			$errorList['attribute5']['fldLbl'] = $_SESSION['attribute5Label'];
			$errorList['attribute5']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($character['vitality'])) {
			$errorList['vitality']['fldLbl'] = $_SESSION['vitalityLabel'];
			$errorList['vitality']['error'] = 'Required field';
		} else if (!is_numeric($character['vitality'])) { // Check if value is a number (can be whole or decimal number)
			$errorList['vitality']['fldLbl'] = 'Vitality';
			$errorList['vitality']['error'] = 'Must be numeric';
		} else if (!isCorrectVitality($character['attribute2'], $character['attribute5'], $character['vitality'])) {
			$errorList['vitality']['fldLbl'] = 'Vitality';
			$errorList['vitality']['error'] = 'Vitality should be the average of ' . $_SESSION['attribute2Label'] . ' and ' . $_SESSION['attribute5Label'] . ', rounded down';
		}
		
		// newVitality = parseInt((parseFloat($('#attribute2').val()) + parseFloat($('#attribute5').val())) / 2); 
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	}
	
	// This function validates data specific to character updates: 
	// Trying to remove previously-purchased skills, headers, etc. 
	public function validateUpdateCharacter($character, $characterID) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		$savedCharacter = array(); // Array to hold values retrieved from DB
		
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		
		// Verify that user isn't setting attributes below their saved values
		$query = 	"SELECT c.attribute1, c.attribute2, c.attribute3, c.attribute4, c.attribute5, c.vitality
					FROM characters c
					WHERE c.characterID = " . $mysql['characterID'];
					
		if ($result = $this->dbh->query($query)) {
			while ($row = $result->fetch_assoc()) {
				
				if ($character['attribute1'] < $row['attribute1']) {
					$errorList['attribute1']['fldLbl'] = $_SESSION['attribute1Label'];
					$errorList['attribute1']['error'] = 'Cannot be lower than previously saved value of ' . $row['attribute1'];	
				}
				
				if ($character['attribute2'] < $row['attribute2']) {
					$errorList['attribute2']['fldLbl'] = $_SESSION['attribute2Label'];
					$errorList['attribute2']['error'] = 'Cannot be lower than previously saved value of ' . $row['attribute2'];	
				}
				
				if ($character['attribute3'] < $row['attribute3']) {
					$errorList['attribute3']['fldLbl'] = $_SESSION['attribute3Label'];
					$errorList['attribute3']['error'] = 'Cannot be lower than previously saved value of ' . $row['attribute3'];	
				}
				
				if ($character['attribute4'] < $row['attribute4']) {
					$errorList['attribute4']['fldLbl'] = $_SESSION['attribute4Label'];
					$errorList['attribute4']['error'] = 'Cannot be lower than previously saved value of ' . $row['attribute4'];	
				}
				
				if ($character['attribute5'] < $row['attribute5']) {
					$errorList['attribute5']['fldLbl'] = $_SESSION['attribute5Label'];
					$errorList['attribute5']['error'] = 'Cannot be lower than previously saved value of ' . $row['attribute5'];	
				}
				
				if ($character['vitality'] < $row['vitality']) {
					$errorList['vitality']['fldLbl'] = $_SESSION['vitalityLabel'];
					$errorList['vitality']['error'] = 'Cannot be lower than previously saved value of ' . $row['vitality'];	
				}
			} // end result loop
		} // end attribute result
		
		// Verify that user isn't removing previously saved headers	
		$headerQuery = 	"SELECT h.headerName, ch.characterID, ch.headerID
						FROM headers h, charheaders ch
						WHERE h.headerID = ch.headerID
						AND ch.characterID = " . $mysql['characterID'];
		
		if ($headerResult = $this->dbh->query($headerQuery)) {
			while ($savedHeaders = $headerResult->fetch_assoc()) {
				if (!in_array($savedHeaders['headerID'], $character['charHeaders'])) {
					$errorList['header_' . $savedHeaders['headerID']]['fldLbl'] = $savedHeaders['headerName'];
					$errorList['header_' . $savedHeaders['headerID']]['error'] = 'Cannot remove previously purchased header';
				}
			}
		} // end header result
		
		// Verify that user isn't removing previously saved skills
		$skillQuery = 	"SELECT s.skillName, cs.characterID, cs.skillID, cs.quantity
						FROM skills s, charskills cs
						WHERE s.skillID = cs.skillID
						AND cs.characterID = " . $mysql['characterID'];
		
		if ($skillResult = $this->dbh->query($skillQuery)) {
			while ($savedSkills = $skillResult->fetch_assoc()) {
				$curSkillID = $savedSkills['skillID'];
				if (!array_key_exists($curSkillID, $character['charSkills'])) {
					// User is trying to remove a previously purchased skill
					$errorList['skill_' . $savedSkills['skillID']]['fldLbl'] = $savedSkills['skillName'];
					$errorList['skill_' . $savedSkills['skillID']]['error'] = 'Cannot remove previously purchased skill';
				} else if ($character['charSkills'][$curSkillID]['qty'] < $savedSkills['quantity']) {
					// User is trying to reduce quantity of a stackable skill below previously saved value
					$errorList['skill_' . $savedSkills['skillID']]['fldLbl'] = $savedSkills['skillName'];
					$errorList['skill_' . $savedSkills['skillID']]['error'] = 'Cannot reduce quantity below previously saved value of ' . $savedSkills['quantity'];
				}
			}
		}
		
		// Verify that user isn't removing previously saved spells
		$spellQuery = 	"SELECT sp.spellName, cs.characterID, cs.spellID
						FROM spells sp, charspells cs
						WHERE sp.spellID = cs.spellID
						AND cs.characterID = " . $mysql['characterID'];
		
		if ($spellResult = $this->dbh->query($spellQuery)) {
			while ($savedSpells = $spellResult->fetch_assoc()) {
				if (!in_array($savedSpells['spellID'], $character['charSpells'])) {
					$errorList['spell_' . $savedSpells['spellID']]['fldLbl'] = $savedSpells['spellName'];
					$errorList['spell_' . $savedSpells['spellID']]['error'] = 'Cannot remove previously purchased spell';
				}
			}
		} // end spell result
		
				
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	}
	
	/*********************************************
	VALIDATE PLAYER
	**********************************************/
	
	public function validatePlayer($player) {

		$errorList = array(); // entries look like this: $errorList[fieldname] = errorMsg
		
		if (is_empty($player['firstName'])) {
			$errorList['firstName']['fldLbl'] = 'First Name';
			$errorList['firstName']['error'] = 'Please enter a first name';
		} else if (!isValidText($player['firstName'])) {
			$errorList['firstName']['fldLbl'] = 'First Name';
			$errorList['firstName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($player['lastName'])) {
			$errorList['lastName']['fldLbl'] = 'Last Name';
			$errorList['lastName']['error'] = 'Please enter a last name';
		} else if (!isValidText($player['lastName'])) {
			$errorList['lastName']['fldLbl'] = 'Last Name';
			$errorList['lastName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($player['email'])) {
			$errorList['email']['fldLbl'] = 'Email';
			$errorList['email']['error'] = 'Please enter an email address';
		} else if (!isValidEmail($player['email'])) {
			$errorList['email']['fldLbl'] = 'Email';
			$errorList['email']['error'] = 'Should be a valid email address in the format email@example.com';
		}
		
		if (isset($player['curPassword']) && is_empty($player['curPassword'])) {
			$errorList['curPassword']['fldLbl'] = 'Current Password';
			$errorList['curPassword']['error'] = 'Please enter your current password';
		}
				
		if (!is_empty($player['confirmPassword']) && is_empty($player['newPassword'])) {
			$errorList['newPassword']['fldLbl'] = 'Password';
			$errorList['newPassword']['error'] = 'Please enter a password';
		} else if (!is_empty($player['newPassword']) && !isValidPassword($player['newPassword'])) {
			$errorList['newPassword']['fldLbl'] = 'Password';
			$errorList['newPassword']['error'] = 'Please enter a valid password';
		}
		
		if (!is_empty($player['newPassword']) && is_empty($player['confirmPassword'])) {
			$errorList['confirmPassword']['fldLbl'] = 'Confirm Password';
			$errorList['confirmPassword']['error'] = 'Please confirm the new password';
		} else if (!is_empty($player['confirmPassword']) && $player['newPassword'] != $player['confirmPassword']) {
			$errorList['confirmPassword']['fldLbl'] = 'Confirm Password';
			$errorList['confirmPassword']['error'] = 'New password and confirmation must match';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validatePlayer
	
	public function validateRequestLogin($player) {

		$errorList = array(); // entries look like this: $errorList[fieldname] = errorMsg
		
		if (is_empty($player['firstName'])) {
			$errorList['firstName']['fldLbl'] = 'First Name';
			$errorList['firstName']['error'] = 'Please enter a first name';
		} else if (!isValidText($player['firstName'])) {
			$errorList['firstName']['fldLbl'] = 'First Name';
			$errorList['firstName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($player['lastName'])) {
			$errorList['lastName']['fldLbl'] = 'Last Name';
			$errorList['lastName']['error'] = 'Please enter a last name';
		} else if (!isValidText($player['lastName'])) {
			$errorList['lastName']['fldLbl'] = 'Last Name';
			$errorList['lastName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($player['email'])) {
			$errorList['email']['fldLbl'] = 'Email';
			$errorList['email']['error'] = 'Please enter an email address';
		} else if (!isValidEmail($player['email'])) {
			$errorList['email']['fldLbl'] = 'Email';
			$errorList['email']['error'] = 'Should be a valid email address in the format email@example.com';
		}
		
		if (is_empty($player['password'])) {
			$errorList['password']['fldLbl'] = 'Password';
			$errorList['password']['error'] = 'Please enter a password';
		} else if (!isValidPassword($player['password'])) {
			$errorList['password']['fldLbl'] = 'Password';
			$errorList['password']['error'] = 'Please enter a valid password';
		}
		
		if (is_empty($player['confirmPassword'])) {
			$errorList['confirmPassword']['fldLbl'] = 'Confirm Password';
			$errorList['confirmPassword']['error'] = 'Please re-enter your password to confirm';
		} else if ($player['password'] != $player['confirmPassword']) {
			$errorList['confirmPassword']['fldLbl'] = 'Confirm Password';
			$errorList['confirmPassword']['error'] = 'New password and confirmation must match';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateRequestLogin
	
	public function validateCountry($country) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($country['countryName'])) {
			$errorList['countryName']['fldLbl'] = 'Country Name';
			$errorList['countryName']['error'] = 'Please enter a name';
		} else if (!isValidText($country['countryName'])) {
			$errorList['countryName']['fldLbl'] = 'Country Name';
			$errorList['countryName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($country['countryDefault'])) {
			$errorList['countryDefault']['fldLbl'] = 'Default Country';
			$errorList['countryDefault']['error'] = 'Please make a selection';
		} else if ($country['countryDefault'] != 1 && $country['countryDefault'] != 0) {
			$errorList['countryDefault']['fldLbl'] = 'Default Country';
			$errorList['countryDefault']['error'] = 'Invalid value';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateCountry
	
		public function validateCommunity($community) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($community['communityName'])) {
			$errorList['communityName']['fldLbl'] = 'Name';
			$errorList['communityName']['error'] = 'Please enter a name';
		} else if (!isValidText($community['communityName'])) {
			$errorList['communityName']['fldLbl'] = 'Name';
			$errorList['communityName']['error'] = 'Contains invalid characters';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateCountry

	
	public function validateHeader($header) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($header['headerName'])) {
			$errorList['headerName']['fldLbl'] = 'Header Name';
			$errorList['headerName']['error'] = 'Please enter a name';
		} else if (!isValidText($header['headerName'])) {
			$errorList['headerName']['fldLbl'] = 'Header Name';
			$errorList['headerName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($header['headerCost'])) {
			$errorList['headerCost']['fldLbl'] = 'Header Cost';
			$errorList['headerCost']['error'] = 'Please enter a cost';
		} else if (!is_numeric($header['headerCost'])) { // Check if value is a number (can be whole or decimal number)
			$errorList['headerCost']['fldLbl'] = 'Header Cost';
			$errorList['headerCost']['error'] = 'Must be numeric';
		}
		
		if (is_empty($header['headerAccess'])) {
			$errorList['headerAccess']['fldLbl'] = 'Who can buy';
			$errorList['headerAccess']['error'] = 'Please select an option';
		}
		
		if (!is_empty($header['headerDescription']) && !isValidTextArea($header['headerDescription'])) {
			$errorList['headerDescription']['fldLbl'] = 'Description';
			$errorList['headerDescription']['error'] = 'Contains invalid characters';
		}
				
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateHeader
	
	public function validateRace($race) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($race['raceName'])) {
			$errorList['raceName']['fldLbl'] = 'Race Name';
			$errorList['raceName']['error'] = 'Please enter a name';
		} else if (!isValidText($race['raceName'])) {
			$errorList['raceName']['fldLbl'] = 'Race Name';
			$errorList['raceName']['error'] = 'Contains invalid characters';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateRace
	
	public function validateSkill($skill) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($skill['skillName'])) {
			$errorList['skillName']['fldLbl'] = 'Skill name';
			$errorList['skillName']['error'] = 'Please enter a name';
		} else if (!isValidText($skill['skillName'])) {
			$errorList['skillName']['fldLbl'] = 'Skill name';
			$errorList['skillName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($skill['skillCost'])) {
			$errorList['skillCost']['fldLbl'] = 'Skill cost';
			$errorList['skillCost']['error'] = 'Please enter a cost';
		} else if (!is_numeric($skill['skillCost'])) { // Check if value is a number (can be whole or decimal number)
			$errorList['skillCost']['fldLbl'] = 'Skill cost';
			$errorList['skillCost']['error'] = 'Must be numeric';
		}
		
		if (!ctype_digit($skill['maxQuantity'])) { // Must be an integer
			$errorList['maxQuantity']['fldLbl'] = 'Max purchases';
			$errorList['maxQuantity']['error'] = 'Must be a whole number';
		}
		
		if (!ctype_digit($skill['costIncrement'])) { // Must be an integer
			$errorList['costIncrement']['fldLbl'] = 'Max purchases';
			$errorList['costIncrement']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($skill['headerID'])) {
			$errorList['headerID']['fldLbl'] = 'Header';
			$errorList['headerID']['error'] = 'Please select a header';
		} else if (!isValidText($skill['headerID'])) {
			$errorList['headerID']['fldLbl'] = 'Header';
			$errorList['headerID']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($skill['skillAccess'])) {
			$errorList['skillAccess']['fldLbl'] = 'Who can buy';
			$errorList['skillAccess']['error'] = 'Please select an option';
		} else if ($skill['skillAccess'] != 'Public' && $skill['skillAccess'] != 'Hidden' && $skill['skillAccess'] != 'NPC') {
			$errorList['skillAccess']['fldLbl'] = 'Who can buy';
			$errorList['skillAccess']['error'] = 'Invalid option';
		}

		if (is_empty($skill['skillType'])) {
			$errorList['skillType']['fldLbl'] = 'Skill type';
			$errorList['skillType']['error'] = 'Please select a type';
		} else if (($skill['skillType'] != 'Always Active') && ($skill['skillType'] != 'Per Battle') && ($skill['skillType'] != 'Per Event') && ($skill['skillType'] != 'Standard')) {
			$errorList['skillType']['fldLbl'] = 'Skill type';
			$errorList['skillType']['error'] = 'Invalid type';
		}
		
		if (!is_empty($skill['shortDescription']) && !isValidTextArea($skill['shortDescription'])) {
			$errorList['shortDescription']['fldLbl'] = 'Short description';
			$errorList['shortDescription']['error'] = 'Contains invalid characters';
		}
		
		if (!is_empty($skill['skillDescription']) && !isValidTextArea($skill['skillDescription'])) {
			$errorList['skillDescription']['fldLbl'] = 'Description';
			$errorList['skillDescription']['error'] = 'Contains invalid characters';
		}
		
		if (!isValidTextArea($skill['cheatSheetNote'])) {
			$errorList['cheatSheetNote']['fldLbl'] = 'Cheat sheet note';
			$errorList['cheatSheetNote']['error'] = 'Contains invalid characters';
		}
				
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateSkill

	
	public function validateSpell($spell) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($spell['spellName'])) {
			$errorList['spellName']['fldLbl'] = 'Spell name';
			$errorList['spellName']['error'] = 'Please enter a name';
		} else if (!isValidText($spell['spellName'])) {
			$errorList['spellName']['fldLbl'] = 'Spell name';
			$errorList['spellName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($spell['spellCost'])) {
			$errorList['spellCost']['fldLbl'] = 'Spell cost';
			$errorList['spellCost']['error'] = 'Please enter a cost';
		} else if (!is_numeric($spell['spellCost'])) { // Check if value is a number (can be whole or decimal number)
			$errorList['spellCost']['fldLbl'] = 'Spell cost';
			$errorList['spellCost']['error'] = 'Must be numeric';
		}
		
		if (is_empty($spell['skillID'])) {
			$errorList['skillID']['fldLbl'] = 'Skill under which this spell appears';
			$errorList['skillID']['error'] = 'Please select an option';
		} else if (!isValidText($spell['skillID'])) {
			$errorList['skillID']['fldLbl'] = 'Skill under which this spell appears';
			$errorList['skillID']['error'] = 'Contains invalid characters';
		}
		
		if (!is_empty($spell['spellShortDescription']) && !isValidTextArea($spell['spellShortDescription'])) {
			$errorList['spellShortDescription']['fldLbl'] = 'Short description';
			$errorList['spellShortDescription']['error'] = 'Contains invalid characters';
		}
		
		if (!is_empty($spell['spellDescription']) && !isValidTextArea($spell['spellDescription'])) {
			$errorList['spellDescription']['fldLbl'] = 'Description';
			$errorList['spellDescription']['error'] = 'Contains invalid characters';
		}
		
		if (!isValidText($spell['spellCheatSheetNote'])) {
			$errorList['spellCheatSheetNote']['fldLbl'] = 'Cheat sheet note';
			$errorList['spellCheatSheetNote']['error'] = 'Contains invalid characters';
		}
				
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateSpell
	
	public function validateFeat($feat) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($feat['featName'])) {
			$errorList['featName']['fldLbl'] = 'Feat name';
			$errorList['featName']['error'] = 'Please enter a name';
		} else if (!isValidTextAdmin($feat['featName'])) {
			$errorList['featName']['fldLbl'] = 'Feat name';
			$errorList['featName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($feat['featCost'])) {
			$errorList['featCost']['fldLbl'] = 'Feat cost';
			$errorList['featCost']['error'] = 'Please enter a cost';
		} else if (!is_numeric($feat['featCost'])) { // Check if value is a number (can be whole or decimal number)
			$errorList['featCost']['fldLbl'] = 'Feat cost';
			$errorList['featCost']['error'] = 'Must be numeric';
		}
		
		if (!is_empty($feat['featPrereq']) && !isValidTextAdmin($feat['featPrereq'])) {
			$errorList['featPrereq']['fldLbl'] = 'Prerequisite';
			$errorList['featPrereq']['error'] = 'Contains invalid characters';
		}
		
		if (!is_empty($feat['featShortDescription']) && !isValidTextArea($feat['featShortDescription'])) {
			$errorList['featShortDescription']['fldLbl'] = 'Short description';
			$errorList['featShortDescription']['error'] = 'Contains invalid characters';
		}
		
		if (!is_empty($feat['featDescription']) && !isValidTextArea($feat['featDescription'])) {
			$errorList['featDescription']['fldLbl'] = 'Description';
			$errorList['featDescription']['error'] = 'Contains invalid characters';
		}
		
		if (!isValidTextAdmin($feat['featCheatSheetNote'])) {
			$errorList['featCheatSheetNote']['fldLbl'] = 'Cheat sheet note';
			$errorList['featCheatSheetNote']['error'] = 'Contains invalid characters';
		}
				
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateFeat
	
	public function validateTrait($trait) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($trait['traitName'])) {
			$errorList['traitName']['fldLbl'] = 'Trait name';
			$errorList['traitName']['error'] = 'Please enter a name';
		} else if (!isValidText($trait['traitName'])) {
			$errorList['traitName']['fldLbl'] = 'Trait name';
			$errorList['traitName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($trait['traitStaff'])) {
			$errorList['traitStaff']['fldLbl'] = 'Staff member';
			$errorList['traitStaff']['error'] = 'Please enter a cost';
		} else if (!isValidText($trait['traitStaff'])) {
			$errorList['traitStaff']['fldLbl'] = 'Staff member';
			$errorList['traitStaff']['error'] = 'Must be numeric';
		}
		
		if (is_empty($trait['traitAccess'])) {
			$errorList['traitAccess']['fldLbl'] = 'Visibility';
			$errorList['traitAccess']['error'] = 'Please select an option';
		} else if ($trait['traitAccess'] != 'Public' && $trait['traitAccess'] != 'Hidden') {
			$errorList['traitAccess']['fldLbl'] = 'Visibility';
			$errorList['traitAccess']['error'] = 'Invalid option';
		}
		
		if (!is_empty($trait['traitDescriptionStaff']) && !isValidTextArea($trait['traitDescriptionStaff'])) {
			$errorList['traitDescriptionStaff']['fldLbl'] = 'Internal description';
			$errorList['traitDescriptionStaff']['error'] = 'Contains invalid characters';
		}
		
		if (!is_empty($trait['traitDescriptionPublic']) && !isValidTextArea($trait['traitDescriptionPublic'])) {
			$errorList['traitDescriptionPublic']['fldLbl'] = 'Public Description';
			$errorList['traitDescriptionPublic']['error'] = 'Contains invalid characters';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateTrait
	
	
	public function validateSettings($settings) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($settings['campaignName'])) {
			$errorList['campaignName']['fldLbl'] = 'Campaign Name';
			$errorList['campaignName']['error'] = 'Please enter a campaign name';
		} else if (!isValidText($settings['campaignName'])) {
			$errorList['campaignName']['fldLbl'] = 'Campaign Name';
			$errorList['campaignName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['baseCP'])) {
			$errorList['baseCP']['fldLbl'] = 'Base Starting CP';
			$errorList['baseCP']['error'] = 'Please enter a base CP value';
		} else if (!ctype_digit($settings['baseCP'])) {
			$errorList['baseCP']['fldLbl'] = 'Base Starting CP';
			$errorList['baseCP']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($settings['baseAttribute'])) {
			$errorList['baseAttribute']['fldLbl'] = 'Base Attribute Value';
			$errorList['baseAttribute']['error'] = 'Please enter a base attribute value';
		} else if (!ctype_digit($settings['baseAttribute'])) {
			$errorList['baseAttribute']['fldLbl'] = 'Base Attribute Value';
			$errorList['baseAttribute']['error'] = 'Must be a whole number';
		}
		
		if (is_empty($settings['useRaces'])) {
			$errorList['useRaces']['fldLbl'] = 'Use Races';
			$errorList['useRaces']['error'] = 'Please select a value';
		} else if ($settings['useRaces'] != 'Yes' && $settings['useRaces'] != 'No') {
			$errorList['useRaces']['fldLbl'] = 'Use Races';
			$errorList['useRaces']['error'] = 'Please specify Yes or No';
		}
		
		if (is_empty($settings['communityLabel'])) {
			$errorList['communityLabel']['fldLbl'] = 'Custom name for communities';
			$errorList['communityLabel']['error'] = 'Please enter a name';
		} else if (!isValidText($settings['communityLabel'])) {
			$errorList['communityLabel']['fldLbl'] = 'Custom name for communities';
			$errorList['communityLabel']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['communityLabelPlural'])) {
			$errorList['communityLabelPlural']['fldLbl'] = 'Plural name for communities';
			$errorList['communityLabelPlural']['error'] = 'Please enter a plural name';
		} else if (!isValidText($settings['communityLabelPlural'])) {
			$errorList['communityLabelPlural']['fldLbl'] = 'Plural name for communities';
			$errorList['communityLabelPlural']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['attribute1Label'])) {
			$errorList['attribute1Label']['fldLbl'] = 'Custom name for attribute 1';
			$errorList['attribute1Label']['error'] = 'Please enter a value';
		} else if (!isValidText($settings['attribute1Label'])) {
			$errorList['attribute1Label']['fldLbl'] = 'Custom name for attribute 1';
			$errorList['attribute1Label']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['attribute2Label'])) {
			$errorList['attribute2Label']['fldLbl'] = 'Custom name for attribute 2';
			$errorList['attribute2Label']['error'] = 'Please enter a value';
		} else if (!isValidText($settings['attribute2Label'])) {
			$errorList['attribute2Label']['fldLbl'] = 'Custom name for attribute 2';
			$errorList['attribute2Label']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['attribute3Label'])) {
			$errorList['attribute3Label']['fldLbl'] = 'Custom name for attribute 3';
			$errorList['attribute3Label']['error'] = 'Please enter a value';
		} else if (!isValidText($settings['attribute3Label'])) {
			$errorList['attribute3Label']['fldLbl'] = 'Custom name for attribute 3';
			$errorList['attribute3Label']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['attribute4Label'])) {
			$errorList['attribute4Label']['fldLbl'] = 'Custom name for attribute 4';
			$errorList['attribute4Label']['error'] = 'Please enter a value';
		} else if (!isValidText($settings['attribute4Label'])) {
			$errorList['attribute4Label']['fldLbl'] = 'Custom name for attribute 4';
			$errorList['attribute4Label']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['attribute5Label'])) {
			$errorList['attribute5Label']['fldLbl'] = 'Custom name for attribute 5';
			$errorList['attribute5Label']['error'] = 'Please enter a value';
		} else if (!isValidText($settings['attribute5Label'])) {
			$errorList['attribute5Label']['fldLbl'] = 'Custom name for attribute 5';
			$errorList['attribute5Label']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['vitalityLabel'])) {
			$errorList['vitalityLabel']['fldLbl'] = 'Custom name for vitality';
			$errorList['vitalityLabel']['error'] = 'Please enter a value';
		} else if (!isValidText($settings['vitalityLabel'])) {
			$errorList['vitalityLabel']['fldLbl'] = 'Custom name for vitality';
			$errorList['vitalityLabel']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['contactName'])) {
			$errorList['contactName']['fldLbl'] = 'Information contact';
			$errorList['contactName']['error'] = 'Please enter a name';
		} else if (!isValidText($settings['contactName'])) {
			$errorList['contactName']['fldLbl'] = 'Information contact';
			$errorList['contactName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['contactEmail'])) {
			$errorList['contactEmail']['fldLbl'] = 'Information email address';
			$errorList['contactEmail']['error'] = 'Please enter an email';
		} else if (!isValidEmail($settings['contactEmail'])) {
			$errorList['contactEmail']['fldLbl'] = 'Information email address';
			$errorList['contactEmail']['error'] = 'Should be a valid email address in the format email@example.com';
		} 
		
		if (is_empty($settings['webmasterName'])) {
			$errorList['webmasterName']['fldLbl'] = 'Webmaster name';
			$errorList['webmasterName']['error'] = 'Please enter a name';
		} else if (!isValidText($settings['webmasterName'])) {
			$errorList['webmasterName']['fldLbl'] = 'Webmaster name';
			$errorList['webmasterName']['error'] = 'Contains invalid characters';
		}
		
		if (is_empty($settings['webmasterEmail'])) {
			$errorList['webmasterEmail']['fldLbl'] = 'Webmaster email address';
			$errorList['webmasterEmail']['error'] = 'Please enter an email';
		} else if (!isValidEmail($settings['webmasterEmail'])) {
			$errorList['webmasterEmail']['fldLbl'] = 'Webmaster email address';
			$errorList['webmasterEmail']['error'] = 'Should be a valid email address in the format email@example.com';
		}
		
		if (is_empty($settings['paypalEmail'])) {
			$errorList['paypalEmail']['fldLbl'] = 'Paypal payment email';
			$errorList['paypalEmail']['error'] = 'Please enter an email';
		} else if (!isValidEmail($settings['paypalEmail'])) {
			$errorList['paypalEmail']['fldLbl'] = 'Paypal payment email';
			$errorList['paypalEmail']['error'] = 'Should be a valid email address in the format email@example.com';
		}
		
		if (is_empty($settings['generatorLocation'])) {
			$errorList['generatorLocation']['fldLbl'] = 'Generator URL';
			$errorList['generatorLocation']['error'] = 'Please enter a Web address';
		} else if (!isValidURL($settings['generatorLocation'])) {
			$errorList['generatorLocation']['fldLbl'] = 'Generator URL';
			$errorList['generatorLocation']['error'] = 'Should be a valid Web address, <br /> e.g. http://yourgame.com/generator';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateSettings
	
	public function validateCharTransfer($data) {
	  $errorList = array(); 
	  
	  if (is_empty($data['playerID'])) {
		  $errorList['email']['fldLbl'] = 'Transfer To';
		  $errorList['email']['error'] = 'Please select a player';
	  } else if (!ctype_digit($data['playerID'])) {
		  $errorList['email']['fldLbl'] = 'Transfer To';
		  $errorList['email']['error'] = 'Invalid player';
	  }
	  
	  // If data doesn't pass validation, set error message for display in the UI and return false. 
	  if (count($errorList) > 0) {
		  $_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
		  return false;
	  }
			  
	  return true;
  	} // end of validateCharTransfer
	
	public function validateCP($cp) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		if (is_empty($cp['numberCP'])) {
			$errorList['numberCP']['fldLbl'] = 'Number';
			$errorList['numberCP']['error'] = 'Please enter a number';
		} else if (!is_numeric($cp['numberCP'])) { // Check if value is a number (can be whole or decimal number)
			$errorList['numberCP']['fldLbl'] = 'Number';
			$errorList['numberCP']['error'] = 'Must be a number';	
		}
		
		if (!isset($cp['CPType']) || is_empty($cp['CPType'])) {
			$errorList['CPType']['fldLbl'] = 'Assign to';
			$errorList['CPType']['error'] = 'Please select either "Character" or "Player"';
		} else if ($cp['CPType'] != 'character' && $cp['CPType'] != 'player') {
			$errorList['CPType']['fldLbl'] = 'Assign to';
			$errorList['CPType']['error'] = 'Please select either "Character" or "Player"';
		}
		
		/* DEBUG
		echo 'Character ID is set? ' . isset($cp['characterID']) . '<br />';
		echo 'Character ID is empty? ' . is_empty($cp['characterID']) . '<br />';
		echo 'Player ID is set? ' . isset($cp['playerID']) . '<br />';
		echo 'Player ID is empty? ' . isset($cp['playerID']) . '<br />';
		echo 'Character ID:' . $cp['characterID'] . '<br />';
		echo 'Player ID:' . $cp['playerID'] . '<br />';
		*/
		
		if ((!isset($cp['characterID']) || is_empty($cp['characterID']) || $cp['characterID'] == 0) && (!isset($cp['playerID']) || is_empty($cp['playerID']) || $cp['playerID'] == 0)) {
			$errorList['characterID']['fldLbl'] = 'Assign to';
			$errorList['characterID']['error'] = 'Please select a character or player';
		} 
		
		if (isset($cp['characterID']) && !is_empty($cp['characterID']) && !ctype_digit($cp['characterID'])) {
			$errorList['characterID']['fldLbl'] = 'Character';
			$errorList['characterID']['error'] = 'Invalid character';
		}
		
		if (isset($cp['playerID']) && !is_empty($cp['playerID']) && !ctype_digit($cp['playerID'])) {
			$errorList['playerID']['fldLbl'] = 'Player';
			$errorList['playerID']['error'] = 'Invalid player';
		}
		
		if (is_empty($cp['CPCatID'])) {
			$errorList['CPCatID']['fldLbl'] = 'Category';
			$errorList['CPCatID']['error'] = 'Please select a category';
		} else if (!ctype_digit($cp['CPCatID'])) {
			$errorList['CPCatID']['fldLbl'] = 'Category';
			$errorList['CPCatID']['error'] = 'Invalid category';	
		}
		
		if (!is_empty($cp['CPNote']) && !isValidTextArea($cp['CPNote'])) {
			$errorList['CPNote']['fldLbl'] = 'Note';
			$errorList['CPNote']['error'] = 'Contains invalid characters';
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateCP
	
	public function validateMultipleCP($cp) {
		$errorList = array(); // entries look like this: $errorList[fieldname]['fldLbl'] = label, $errorList[fieldname]['error'] = error message
		
		// Validate CP type, character, and player fields
		if (!isset($cp['CPType']) || is_empty($cp['CPType'])) {
			$errorList['CPType']['fldLbl'] = 'Assign to';
			$errorList['CPType']['error'] = 'Please select either "Character" or "Player"';
		} else if ($cp['CPType'] != 'character' && $cp['CPType'] != 'player') {
			$errorList['CPType']['fldLbl'] = 'Assign to';
			$errorList['CPType']['error'] = 'Please select either "Character" or "Player"';
		} else if (isset($cp['CPType']) && $cp['CPType'] == 'character' && $cp['characterID'] == 0) {
			$errorList['characterID']['fldLbl'] = 'Character';
			$errorList['characterID']['error'] = 'Please select a character';
		} else if (isset($cp['CPType']) && $cp['CPType'] == 'player' && $cp['playerID'] == 0) {
			$errorList['playerID']['fldLbl'] = 'Player';
			$errorList['playerID']['error'] = 'Please select a player';
		} else if (isset($cp['playerID']) && !is_empty($cp['playerID']) && !ctype_digit($cp['playerID'])) {
			$errorList['playerID']['fldLbl'] = 'Player';
			$errorList['playerID']['error'] = 'Invalid player';
		} else if ((!isset($cp['characterID']) || is_empty($cp['characterID']) || $cp['characterID'] == 0) && (!isset($cp['playerID']) || is_empty($cp['playerID']) || $cp['playerID'] == 0)) {
			$errorList['characterID']['fldLbl'] = 'Assign to';
			$errorList['characterID']['error'] = 'Please select a character or player';
		} else if (isset($cp['characterID']) && !is_empty($cp['characterID']) && !ctype_digit($cp['characterID'])) {
			$errorList['characterID']['fldLbl'] = 'Character';
			$errorList['characterID']['error'] = 'Invalid character';
		}
		
		$cpAssigned = 0; // Track whether user has filled in at least one row
		
		// Loop through CP rows and validate
		for ($i = 1; $i <= 5; $i++) {
			
			$curNumFld = 'numberCP' . $i;
			$curCatFld = 'CPCatID' . $i;
			$curNoteFld = 'CPNote' . $i;
		
			if (is_empty($cp[$curNumFld]) && (!is_empty($cp[$curCatFld]) || !is_empty($cp[$curNoteFld]))) {
				$errorList[$curNumFld]['fldLbl'] = 'Row ' . $i . ': Number';
				$errorList[$curNumFld]['error'] = 'Please enter a number';
			}
			
			if (!is_empty($cp[$curNumFld]) && !is_numeric($cp[$curNumFld])) { // Check if value is a number (can be whole or decimal number)
				$errorList[$curNumFld]['fldLbl'] = 'Row ' . $i . ': Number';
				$errorList[$curNumFld]['error'] = 'Must be a number';	
			}
						
			if ((is_empty($cp[$curCatFld]) || $cp[$curCatFld] == 0) && (!is_empty($cp[$curNumFld]) || !is_empty($cp[$curNoteFld]))) {
				$errorList[$curCatFld]['fldLbl'] = 'Row ' . $i . ': Category';
				$errorList[$curCatFld]['error'] = 'Please select a category';
			}
			
			if (!is_empty($cp[$curCatFld]) && !ctype_digit($cp[$curCatFld])) {
				$errorList[$curCatFld]['fldLbl'] = 'Row ' . $i . ': Category';
				$errorList[$curCatFld]['error'] = 'Invalid category';	
			}
			
			if (!is_empty($cp[$curNoteFld]) && !isValidTextArea($cp[$curNoteFld])) {
				$errorList[$curNoteFld]['fldLbl'] = 'Row ' . $i . ': Note';
				$errorList[$curNoteFld]['error'] = 'Contains invalid characters';
			}
			
			if (!is_empty($cp[$curNumFld]) || !is_empty($cp[$curCatFld]) || !is_empty($cp[$curNoteFld])) {
				$cpAssigned = 1;	
			}
		} // end of loop through rows
		
		if ($cpAssigned == 0) {
			// User has not filled in at least one row
			$errorList['cpAssignment']['fldLbl'] = 'CP assignment';
			$errorList['cpAssignment']['error'] = 'Please fill in at least one row';	
		}
		
		// If data doesn't pass validation, set error message for display in the UI and return false. 
		if (count($errorList) > 0) {
			$_SESSION['UIMessage'] = new UIMessage('error', 'Oops! There was a problem', '<p>Please fix the following problems and try again:</p>', $errorList);
			return false;
		}
				
		return true;
	} // end of validateMultipleCP
	
	
	

} // end of class
