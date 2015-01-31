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
	
	/* addLogEntry: Add an entry to the logs table. 
	$msg: String. The log message. It should include enough information to help in debugging. 
	$loggedInPlayerID: Integer. The player who was logged in when the problem occurred. 
	$playerID: Integer. The player that the system was attempting to take action on. 
				e.g. if an administrator is attempting to delete a character, 
				the loggedInPlayerID is the administrator, and the $playerID is the player 
				that the character belongs to. 
	$className & methodName: String Class and method in which the problem occurred, to aid in tracking down problems. 
	 			NOTE: If the error originated outside a class, you can provide the filename (e.g. "wizard.php")
	 			instead of a class name
	
	Log entries should have an accompanying severity level: 
		Critical: 	An urgent condition that requires immediate attention, e.g. a system-level problem or a breakdown in key functionality. 
		Error: 		An operation did not complete successfully, or completed in an error state. 
		Warning: 	Something unexpected occurred, but it did not necessarily prevent the operation from completing. 
		Information: Messages that do not indicate a problem or require action, but allow administrators to trace 
						the actions of the system (e.g. success messages)
	*/
	public function addLogEntry($msg, $loggedInPlayerID = '', $playerID = '', $characterID = '', $className = '', $methodName = '', $severity = 'Information') {
		// Deal with optional fields
		if ($loggedInPlayerID == '') {
			$loggedInPlayerID = 'NULL';
		}

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
		$mysql['severity'] = db_escape($severity, $this->dbh);
		
		$query = 	"INSERT INTO log (logTimestamp, logMessage, loggedInPlayerID, playerID, characterID, className, methodName, severity)
					VALUES (
							NOW(), '" . 
							$mysql['msg'] . "', " . 
							$mysql['loggedInPlayerID'] . ", " .
							$mysql['playerID'] . ", " . 
							$mysql['characterID'] . ", '" .
							$mysql['className'] . "', '" .
							$mysql['methodName'] . "', '" .
							$mysql['severity'] . "')";
		// echo 'addLogEntry query: ' . $query . '<br />';
		
		if ($result = $this->dbh->query($query)) {
			
			return true;
		}
		return false;
	} // end of addLogEntry
	
} // end of class