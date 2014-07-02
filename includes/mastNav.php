<?php

	/**************************************************************
	NAME: 	mastNav.php
	NOTES: 	Masthead content and navigation for main area. 
	**************************************************************/

?>	
	<div id="mast">
    	<h1><?php echo $_SESSION['campaignName']; ?> Character Generator</h1>
        <a href="../main/profile.php" id="profileLink" title="Edit your profile">Profile</a>
		<?php
			// Only show admin link to players and staff
			if ($_SESSION['userRole'] == 'Staff' || $_SESSION['userRole'] == 'Admin') {
		?>
        	<a href="../admin/index.php" id="adminLink" title="Go to admin area">Admin</a>
        <?php
			}
		?>
        <a href="../logout.php" id="logoutLink">Log Out</a>
            
    </div>
    <div id="nav">
    	<a href="../main/index.php" id="home">Characters</a>
        <a href="../main/cp.php" id="cp">Your CP</a>
    </div>
