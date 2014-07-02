<?php

	/**************************************************************
	NAME: 	adminNav.php
	NOTES: 	Masthead content and navigation for admin area. 
	**************************************************************/
	
	// session_start();

?>	
	
	<div id="mast">
      <h1><?php echo $_SESSION['campaignName']; ?> Character Generator</h1>
	  <a href="../main/index.php" id="homeLink">Main</a>
	  <a href="trash.php" id="trashLink">Trash</a>
      <a href="../logout.php" id="logoutLink">Log Out</a>
    </div>
    
    <div id="nav" class="<?php echo $navClass; ?>">
        <a href="index.php" id="home">Home</a>
        <a href="players.php" id="players">Players</a>
        <a href="characters.php" id="characters">Characters</a>
        <a href="cp.php" id="cp">CP</a>
        <a href="headers.php" id="gameWorld">Game World</a>
        <?php
			// Only show settings link to players and staff
			if ($_SESSION['userRole'] == 'Admin') {
		?>
        <a href="settings.php" id="settings">Settings</a>
        <?php
			}
		?>
    </div>
