<?php 
/************************************************************************
NAME: 	login.class.php
NOTES:	This file holds all the classes for login and authentication. 
*************************************************************************/

class Login {
	
	private $dbh; // Database handle

	function __construct() {
		// Open database connection using params from the config file
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		
		if ($this->dbh->connect_errno) {
			$_SESSION['UIMessage'] = new UIMessage('error', 
												'System Error',
												'<p>Unable to contact Character Generator system. Please try again later.</p>
												<p>If you continue to encounter problems, please contact the <a href="mailto:' . WEBMASTER_EMAIL . '">System Administrator</a> for assistance.</p>');
		}
	}
	
	public function authenticateUser($email, $password) {
		// echo $email . ', ' . $password;
		$log = new Log();
		
		$user = array();
		$user['email'] = $email;
		$user['password'] = $password;
		
		// Validate data.
		$validator = new Validator();
		if ($validator->validateLogin($user) == false) {
			return false;
		}
		
		// Create safe values for DB
		$mysql = array();
		$mysql['email'] = db_escape($email, $this->dbh);
		
		/* Hard-code login to succeed 
		$this->initSession(); // Call method to initialize settings
		$this->initUser($mysql['email']); // Call method to initialize user data
		return 1;
		*/
		
		/* New (encrypted) version */
		$authenticateQuery = 	"SELECT p.email, p.password, p.playerID 
								FROM players p
								WHERE p.email = '" . $mysql['email'] . "' 
								AND p.userStatus = 'active' 
								AND p.playerDeleted IS NULL";
								
		if ($authenticateResult = $this->dbh->query($authenticateQuery)) {
			if ($authenticateResult->num_rows > 0) {
				while ($row = $authenticateResult->fetch_assoc()) {

					// Generate salted hash of password user entered
					$hashedPassword = generateHash($password, $row['password']);

					// Compare the hashed password retrieved from the DB
					// to the hashed version of the password the user entered. 
					if ($row['password'] == $hashedPassword) { // Passwords match!
						$this->initSession(); // Call method to initialize settings
						$this->initUser($mysql['email']); // Call method to initialize user data
						return 1;
					} else { // Passwords don't match
						$logMsg = 'User ' . $mysql['email'] . ' failed to log in; password incorrect.';
						$log->addLogEntry($logMsg, '', '', '', 'Login', 'authenticateUser', 'Warning');
					}
				} // end while loop
			} else { // zero rows returned
				$logMsg = 'User attempted to log in using an email address not in the system: ' . $mysql['email'];
				$log->addLogEntry($logMsg, '', '', '', 'Login', 'authenticateUser', 'Warning');
			}
		} else {
			$logMsg = 'Error authenticating user with email address ' . $mysql['email'] . '. Error: ' . $this->dbh->error . '. authenticateQuery: ' . $authenticateQuery;
			$log->addLogEntry($logMsg, '', '', '', 'Login', 'authenticateUser', 'Error');
		}
		$_SESSION['UIMessage'] = new UIMessage('error', 
												'Invalid user name and/or password',
												'<p>Please try again, or <a href="lostPassword.php">reset your password</a>.</p>');
		return 0;
		
	}
	
	// Initialize new login session
	public function initSession() {
		$_SESSION['userRole'] = 'User';
		
		$query = 	'SELECT * FROM settings s ' .
					'WHERE s.settingsID = 1';
		
		if ($result = $this->dbh->query($query)) {
			// echo 'Settings retrieved: ' . $this->dbh->affected_rows . '<br />';
			
			// Initialize session variables
			while ($row = $result->fetch_assoc()) {
				$_SESSION['baseCP'] = $row['baseCP'];
				$_SESSION['baseAttribute'] = $row['baseAttribute'];
				$_SESSION['useRaces'] = $row['useRaces'];
				$_SESSION['communityLabel'] = $row['communityLabel'];
				$_SESSION['communityLabelPlural'] = $row['communityLabelPlural'];
				$_SESSION['attribute1Label'] = $row['attribute1Label'];
				$_SESSION['attribute2Label'] = $row['attribute2Label'];
				$_SESSION['attribute3Label'] = $row['attribute3Label'];
				$_SESSION['attribute4Label'] = $row['attribute4Label'];
				$_SESSION['attribute5Label'] = $row['attribute5Label'];
				$_SESSION['vitalityLabel'] = $row['vitalityLabel'];
				$_SESSION['campaignName'] = $row['campaignName'];
				$_SESSION['theme'] = THEME;
				$_SESSION['contactName'] = $row['contactName'];
				$_SESSION['contactEmail'] = $row['contactEmail'];
				$_SESSION['webmasterName'] = $row['webmasterName'];
				$_SESSION['webmasterEmail'] = $row['webmasterEmail'];
				$_SESSION['paypalEmail'] = $row['paypalEmail'];
				$_SESSION['copyrightYear'] = $row['copyrightYear'];
				$_SESSION['generatorLocation'] = $row['generatorLocation'];
				$_SESSION['UIMessage'] = '';
			}
			
			return $result; 
		}
		return false;
	}
	
	private function initUser($email) {
		// Escape values for insertion into DB
		$mysql = array(); // Initialize blank
		$mysql['email'] = db_escape($email, $this->dbh);

		// Retrieve player info
		$playerQuery = 	'SELECT p.playerID, p.firstName, p.lastName, p.email, p.userRole ' . 
						'FROM players p ' .
						'WHERE p.email = \'' . $mysql['email'] . '\'';
		
		if ($playerResult = $this->dbh->query($playerQuery)) {
			// Initialize player session variables
			while ($playerRow = $playerResult->fetch_assoc()) {
				$_SESSION['playerID'] = $playerRow['playerID'];
				$_SESSION['firstName'] = $playerRow['firstName'];
				$_SESSION['lastName'] = $playerRow['lastName'];
				$_SESSION['email'] = $playerRow['email'];
				$_SESSION['userRole'] = $playerRow['userRole'];
			
			} // end of player loop
		
		} // end of playerResult condition
	}
	
	public function logOutUser() {
		$_SESSION = array(); // Clear all session variables
		$_SESSION['isLoggedIn'] = 0;
		session_destroy();
		
		header('Location: index.php'); // Take user to main page
		exit();
	}
	
} // end of class
