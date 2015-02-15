<?php 
/************************************************************************
NAME: 	settings.class.php
NOTES:	This file holds all the methods for updating settings. 
*************************************************************************/

class Settings {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getSettings() {
		$query = 	'SELECT * 
					FROM settings s
					LIMIT 1';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
	}
	
	
	// $settings: Associative array of settings data
	public function updateSettings($settings) {
		// Validate data.
		$validator = new Validator();
		if ($validator->validateSettings($settings) == false) {
			return false;
		}
		
		$mysql = array(); // Initialize blank
		$mysql['baseCP'] = db_escape($settings['baseCP'], $this->dbh);
		$mysql['baseAttribute'] = db_escape($settings['baseAttribute'], $this->dbh);
		$mysql['useRaces'] = db_escape($settings['useRaces'], $this->dbh);
		$mysql['communityLabel'] = db_escape($settings['communityLabel'], $this->dbh);
		$mysql['communityLabelPlural'] = db_escape($settings['communityLabelPlural'], $this->dbh);
		$mysql['attribute1Label'] = db_escape($settings['attribute1Label'], $this->dbh);
		$mysql['attribute2Label'] = db_escape($settings['attribute2Label'], $this->dbh);
		$mysql['attribute3Label'] = db_escape($settings['attribute3Label'], $this->dbh);
		$mysql['attribute4Label'] = db_escape($settings['attribute4Label'], $this->dbh);
		$mysql['attribute5Label'] = db_escape($settings['attribute5Label'], $this->dbh);
		$mysql['vitalityLabel'] = db_escape($settings['vitalityLabel'], $this->dbh);
		$mysql['campaignName'] = db_escape($settings['campaignName'], $this->dbh);
		$mysql['contactName'] = db_escape($settings['contactName'], $this->dbh);
		$mysql['contactEmail'] = db_escape($settings['contactEmail'], $this->dbh);
		$mysql['webmasterName'] = db_escape($settings['webmasterName'], $this->dbh);
		$mysql['webmasterEmail'] = db_escape($settings['webmasterEmail'], $this->dbh);
		$mysql['paypalEmail'] = db_escape($settings['paypalEmail'], $this->dbh);
		$mysql['generatorLocation'] = db_escape($settings['generatorLocation'], $this->dbh);
		
		$query = 	"UPDATE settings s 
					SET s.baseCP = " . $mysql['baseCP'] . ", 
					s.baseAttribute = " . $mysql['baseAttribute'] . ", 
					s.useRaces = '" . $mysql['useRaces'] . "', 
					s.communityLabel = '" . $mysql['communityLabel'] . "', 
					s.communityLabelPlural = '" . $mysql['communityLabelPlural'] . "', 
					s.attribute1Label = '" . $mysql['attribute1Label'] . "', 
					s.attribute2Label = '" . $mysql['attribute2Label'] . "', 
					s.attribute3Label = '" . $mysql['attribute3Label'] . "', 
					s.attribute4Label = '" . $mysql['attribute4Label'] . "', 
					s.attribute5Label = '" . $mysql['attribute5Label'] . "', 
					s.vitalityLabel = '" . $mysql['vitalityLabel'] . "', 
					s.campaignName = '" . $mysql['campaignName'] . "', 
					s.contactName = '" . $mysql['contactName'] . "', 
					s.contactEmail = '" . $mysql['contactEmail'] . "', 
					s.webmasterName = '" . $mysql['webmasterName'] . "', 
					s.webmasterEmail = '" . $mysql['webmasterEmail'] . "', 
					s.paypalEmail = '" . $mysql['paypalEmail'] . "',
					s.generatorLocation = '" . $mysql['generatorLocation'] . "' 
					WHERE s.settingsID = 1";
		
		if ($updateResult = $this->dbh->query($query)) {
			// Create success message to display at top of page. 
			$_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Settings updated successfully',
													'<p>Your settings have been updated.</p>');
			return true;
		} else {
			return false;
		}
	}
	
	
} // end of class