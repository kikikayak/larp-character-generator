<?php
	/************************************************************************
	NAME: 	logout.php
	NOTES:	Logs out the currently logged in user. 
	*************************************************************************/

	require_once('includes/config.php');
	require_once('includes/library.php');
	require_once('class/login.class.php');
	require_once('includes/authenticate.php');
	
	$login = new Login();
	
	$login->logOutUser();
	
?>