<?php 
/*******************************************************************************
NAME: 	log.class.php
NOTES:	This file holds all the classes for adding and maintaining log entries. 
*******************************************************************************/

// load configuration
// require_once('../includes/config.php');

class Log {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}

	// Default to last 30 days of logs
	// TODO: Accept a date range
	public function getRecentLogEntries() {

		$mysql = array(); // Set up array for escaping values for DB
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		
		$query = 	'SELECT l.logTimestamp, l.logMessage, l.className, l.methodName
					FROM logs l 
					WHERE l.logTimestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
		
		if ($result = $this->dbh->query($query)) {
			return $result;
		} else {
			$logMsg = 'Error running query: ' . $this->dbh->error . '. Query: ' . $query;
			$this->addLogEntry($logMsg, $_SESSION['playerID'], '', '', 'Log', 'getRecentLogEntries');
			return false;
		}
	} // getRecentLogEntries
	
	public function addLogEntry($msg, $loggedInPlayerID, $playerID = '', $characterID = '', $className = '', $methodName = '') {
		// Deal with optional fields
		if ($playerID == '') {
			$playerID = 'NULL';
		}
		if ($characterID == '') {
			$characterID = 'NULL';
		}
		if ($className == '') {
			$className = 'NULL';
		}
		if ($methodName == '') {
			$methodName = 'NULL';
		}

		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['msg'] = db_escape($msg, $this->dbh);
		$mysql['loggedInPlayerID'] = db_escape($loggedInPlayerID, $this->dbh);
		$mysql['characterID'] = db_escape($characterID, $this->dbh);
		$mysql['playerID'] = db_escape($playerID, $this->dbh);
		$mysql['className'] = db_escape($className, $this->dbh);
		$mysql['methodName'] = db_escape($methodName, $this->dbh);
		
		$query = 	"INSERT INTO log (logTimestamp, logMessage, loggedInPlayerID, playerID, characterID, className, methodName)
					VALUES (
							NOW(), '" . 
							$mysql['msg'] . "', " . 
							$mysql['loggedInPlayerID'] . ", " .
							$mysql['playerID'] . ", " . 
							$mysql['characterID'] . ", '" .
							$mysql['className'] . "', '" .
							$mysql['methodName'] . "')";
		// echo 'addLogEntry query: ' . $query . '<br />';
		
		if ($result = $this->dbh->query($query)) {
			
			return true;
		}
		return false;
	} // end of addLogEntry
	
} // end of class