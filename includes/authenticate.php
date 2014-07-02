<?php 

/********************************************************************
NAME: 	authenticate.php
NOTES:	Check whether or not user is logged in.  
*********************************************************************/

// require_once('../class/login.class.php');

session_start();

if ($_SESSION['isLoggedIn'] == 0) {
	// User is not logged in.  
	$_SESSION['lastPage'] = $_SERVER['SCRIPT_NAME'];
	header('Location: ' . LOCATION . 'index.php'); // Redirect user to login page.
} else if  ($pageAccessLevel == 'Admin' && ($_SESSION['userRole'] == 'User' || $_SESSION['userRole'] == 'Staff')) {
	// User is trying to visit a page they shouldn't have access to
	header('Location: index.php'); // Redirect user to home page.
} else if  ($pageAccessLevel == 'Staff' && $_SESSION['userRole'] == 'User') {
	// User is trying to visit a page they shouldn't have access to
	header('Location: index.php'); // Redirect user to home page.	
}

// Otherwise, everything is okay. Continue processing. 

?>