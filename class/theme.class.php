<?php 
/************************************************************************
NAME: 	theme.class.php
NOTES:	This file holds all the classes for adding and maintaining themes. 
*************************************************************************/

class Theme {
	
	private $dbh; // Database handle
	
	function __construct() {
		$this->dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); // Open database connection using params from the config file
	}
	
	public function getAllThemes() {
		$query = 	'SELECT t.themeID, t.themeName 
					FROM themes t 
					ORDER BY t.themeName';
		
		// echo $query;
		if ($result = $this->dbh->query($query)) {
			return $result;
		}
		// echo 'Succeeded!';
	}
	
} // end of class