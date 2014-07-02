<?php

	/**************************************************************
	NAME: 	index.php
	NOTES: 	This is the main page of the user area, the first page
			the user sees after logging in. 
	**************************************************************/

	$pageAccessLevel = 'User';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
		
	$player = new Player();
	$characters = $player->getCharactersForPlayer($_SESSION['playerID']);
	
	$title = "Home | Character Generator";
	
	include('../includes/header.php');
	
?>

<body id="homePage">

    <?php include('../includes/mastNav.php'); ?>
    
    <div id="content">
        
        <div id="main">
			
			<div id="charArea">
				
				<?php 
					if (isset($_SESSION['UIMessage']) && $_SESSION['UIMessage'] != '') {
						$_SESSION['UIMessage']->displayMessage();
						unset($_SESSION['UIMessage']); // Unset after display so it doesn't show after refresh/navigation. 
					}
				?>
				
				<p class="intro">Hi, <?php echo $_SESSION['firstName']; ?>!</p>
				<p>Would you like to <a href="wizard.php">create a character?</a></p>
				
				<div id="charList" class="section">
					<h3>Your Characters</h3>

					<div class="inner">
						<?php 
							if ($characters->num_rows > 0) {
						?>
						<table cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th class="charNameCol">Name</th>
									<th class="typeCol">Type</th>
									<th class="totalCol">Total CP</th>
									<th class="freeCol">Free CP</th>
									<th class="cheatSheetCol"></th>
									<th class="editCol"></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$rowIndex = 1;
									while ($row = $characters->fetch_assoc()) {
										if ($rowIndex % 2 == 0) {
											$rowClass = 'even';
										} else {
											$rowClass = 'odd';
										}
										
										$curCharacter = new Character();
										$charCPTotal = $curCharacter->getTotalCharCP($row['characterID']);
										$charFreeCP = $curCharacter->getCharFreeCP($row['characterID']);
								?>
								<tr class="<?php echo $rowClass; ?>">					
									<td class="charNameCol"><a href="charDetails.php?characterID=<?php echo $row['characterID']; ?>"><?php echo $row['charName']; ?></a></td>
									<td class="typeCol"><?php echo $row['charType']; ?></td>
									<td class="totalCol"><?php echo $charCPTotal; ?></td>
									<td class="freeCol"><?php echo $charFreeCP ?></td>
									<td class="cheatSheetCol"><a href="cheatSheet.php?characterID=<?php echo $row['characterID']; ?>">cheat sheet</a></td>
									<td class="editCol"><a href="wizard.php?characterID=<?php echo $row['characterID']; ?>" title="Edit character">edit</a></td>
								</tr>	
								<?php 
									$rowIndex++;
									} // end characters loop
								?>			
							</tbody>		
						</table>
						<?php 
							} else {
						?>
						<p class="noResults">You currently have no characters.</p>
						<?php
							}
						?>
					</div>
				</div>
			</div><!--end of charArea-->
			
			<br class="clear" />
            
        </div> <!--end of main div-->
        
        <br class="clear" />
    </div>
<!--end of content div-->
    
<?php include('../includes/footer.php'); ?>
