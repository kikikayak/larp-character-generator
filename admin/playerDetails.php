<?php

	/**************************************************************
	NAME: 	playerDetails.php
	NOTES: 	Displays detailed information on a player and his/her characters. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'players';

	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	if (!isset($_GET['playerID'])) {
	  header('Location: players.php'); // Take user to main players page
	  exit();   
	}
	
	$player = new Player();
	$playerDetails = $player->getPlayerProfile($_GET['playerID']);

	$cp = new CP();
			
	$title = 'Player Details: Allison Corbett | ' . $_SESSION['campaignName'] . ' Character Generator';

	include('../includes/header_admin.php');

?>

<body id="playerDetailsPage">

    <?php include('../includes/adminNav.php'); ?>
    
    <div id="content" class="oneCol">
        
		<div id="main">
        
        	<a href="players.php" class="backLink">&lt; Back</a>
            
            <?php
				$playerDetails->data_seek(0);
				while ($row = $playerDetails->fetch_assoc()) {
			?>
            <h2><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></h2>

            <?php
              if ($_SESSION['userRole'] == 'Admin') {
            ?>
	            <div class="toolbar">
	              <a href="playerAdmin.php?playerID=<?php echo $row['playerID']; ?>" title="Edit this player" class="editLink">Edit this player</a>
	              <a href="playerAdmin.php" title="" class="addLink">Add a new player</a>
	              <br class="clear" />
	            </div><!--.toolbar-->
            <?php
              }
            ?>
			
			<div class="row">
				<div class="cell" id="emailCell">
					<p class="lbl">Email</p>
					<p class="data">
						<a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a>
                    </p>
					<br class="clear" />
				</div>
			</div>
			
			<div class="row" id="loginsCell">
				<div class="cell">
					<p class="lbl">Access Level</p>
					<p class="data"><?php echo $row['userRole']; ?></p>
					<br class="clear" />
				</div>
			</div>
			
			<?php
				$character = new Character();
				$playerCharacters = $character->getCharactersByPlayer($row['playerID']);
			?>
            
			<div class="row" id="charactersCell">
				<div class="cell">
					<p class="lbl">Characters</p>
					<p class="data">
                    	<?php
						  if ($playerCharacters->num_rows > 0) {
							while ($charRow = $playerCharacters->fetch_assoc()) {
							  echo $charRow['charName'] . '<br />';  
							}
						  } else {
							echo '<span class="noResults">None</span>';  
						  }
						?>
                    </p>
					<br class="clear" />
				</div>
			</div>
			
            <?php
				// Loop through characters for this player
				$playerCharacters->data_seek(0);
				while ($charRow = $playerCharacters->fetch_assoc()) {
				
				// Get CP info for CP table
				$cpDetails = $cp->getCPByCharacter($charRow['characterID']);
			?>

			<h3>CP for <?php echo '<a href="charDetails.php?characterID=' . $charRow['characterID'] . '">' . $charRow['charName'] . '</a>'; ?></h3>
			<table cellspacing="0" class="charCPList">
				<thead>
					<tr> 
						<th class="col1">Date</th>
						<th class="col2">CP</th>
						<th class="col3">Category</th>
						<th class="col4">Note</th>
						<th class="col5">Staff member</th>
					</tr>
				</thead>
              	<tbody>
					<?php
						$rowIndex = 1;
						$charCPTotal = 0;

						while ($cpRow = $cpDetails->fetch_assoc()) {
							
						  $dateString = strtotime($cpRow['CPDateStamp']);
						  $displayDate = date('n/j/Y', $dateString);
						  
						  if ($rowIndex % 2 == 0) {
							  $rowClass = 'even';
						  } else {
							  $rowClass = 'odd';
						  }

						  $charCPTotal = $charCPTotal + $cpRow['numberCP'];
					?>
                    <tr class="<?php echo $rowClass; ?>"> 
						<td class="col1"><?php echo $displayDate; ?></td>
						<td class="col2"><?php echo $cpRow['numberCP']; ?></td>
						<td class="col3"><?php echo $cpRow['CPCatName']; ?></td>
						<td class="col4"><?php echo $cpRow['CPNote']; ?></td>
						<td class="col5"><?php echo $cpRow['staffMember']; ?></td>
					</tr>
                    <?php
						$rowIndex++;
						} // end of CP loop
					?>
					<tr class="total">
						<td class="col1">Total:</td>
						<td class="col2"><?php echo $charCPTotal; ?></td>
						<td class="col3"></td>
						<td class="col4"></td>
						<td class="col5"></td>
					</tr>
				</tbody>
			</table>
			
		<?php
			} // end of characters loop
			$playerCPDetails = $cp->getCPByPlayer($row['playerID']);
		?>

		<h3>Unassigned (Player) CP</h3>
			
		<?php
			if ($playerCPDetails->num_rows > 0) {
		?>
		<p>This CP will be automatically assigned to the next character the player adds or edits. </p>
		<table cellspacing="0" class="charCPList">
			<thead>
				<tr> 
					<th class="col1">Date</th>
					<th class="col2">CP</th>
					<th class="col3">Category</th>
					<th class="col4">Note</th>
					<th class="col5">Staff member</th>
				</tr>
			</thead>
		  	<tbody>
				<?php
					$rowIndex = 1;
					$playerCPTotal = 0;

					while ($playerCPRow = $playerCPDetails->fetch_assoc()) {
						
					  $dateString = strtotime($playerCPRow['CPDateStamp']);
					  $displayDate = date('n/j/Y', $dateString);
					  
					  if ($rowIndex % 2 == 0) {
						  $rowClass = 'even';
					  } else {
						  $rowClass = 'odd';
					  }

					  $playerCPTotal = $playerCPTotal + $playerCPRow['numberCP'];
				?>
		        <tr class="<?php echo $rowClass; ?>"> 
					<td class="col1"><?php echo $displayDate; ?></td>
					<td class="col2"><?php echo $playerCPRow['numberCP']; ?></td>
					<td class="col3"><?php echo $playerCPRow['CPCatName']; ?></td>
					<td class="col4"><?php echo $playerCPRow['CPNote']; ?></td>
					<td class="col5"><?php echo $playerCPRow['staffMember']; ?></td>
				</tr>
		        <?php
					$rowIndex++;
					} // end of Player CP loop
				?>
				<tr class="total">
					<td class="col1">Total:</td>
					<td class="col2"><?php echo $playerCPTotal; ?></td>
					<td class="col3"></td>
					<td class="col4"></td>
					<td class="col5"></td>
				</tr>
			</tbody>
		</table>
		<?php
				} else {
					echo '<p class="noResults">This player does not have any unassigned CP.</p>';
				}

			} // end of player details loop
		?>
        
          
        </div> <!--end of main div-->
        
        <br class="clear" />
    </div><!--end of content div-->
    
<?php include('../includes/footer.php'); ?>
