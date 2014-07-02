<?php

	/**************************************************************
	NAME: 	cp.php
	NOTES: 	This page allows players to track their CP assignments. 
	**************************************************************/
	
	$pageAccessLevel = 'User';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$cp = new CP();
	
	$player = new Player();
	$characters = $player->getCharactersForPlayer($_SESSION['playerID']);
	
	$title = "My CP | Character Generator";

	include('../includes/header.php');
	
?>

<body id="cpPage">

    <?php include('../includes/mastNav.php'); ?>
    
    <div id="content">
        
        <div id="main">
			<h2>Your CP</h2>
			<p>Character Points (CP) are used to purchase skills for your character. You can accumulate CP by attending events or by contributing to the game in other ways.</p>

			<p>All the CP assigned to your character(s) appears below. All characters automatically start with <?php echo $_SESSION['baseCP']; ?> CP.</p>
			
			<?php 
				if ($characters->num_rows > 0) {
					while ($row = $characters->fetch_assoc()) {
			?>
			<div class="section">
				<h3><?php echo $row['charName']; ?></h3>
			
				<table class="cpTable" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="dateCol">Date</th>
							<th class="cpCol">CP</th>
							<th class="catCol">For</th>
							<th class="noteCol">Note</th>
							<th class="staffCol">Staff Member</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						$cpRecords = $cp->getCPByCharacter($row['characterID']);
						
						$rowIndex = 1;
						$charCPTotal = 0;
						while ($cpRow = $cpRecords->fetch_assoc()) {
							if ($rowIndex % 2 == 0) {
								$rowClass = 'even';
							} else {
								$rowClass = 'odd';
							}
							
							$charCPTotal = $charCPTotal + $cpRow['numberCP'];
					
					?>
					
						<tr class="<?php echo $rowClass; ?>">
							<td class="dateCol">
								<?php 
									// echo $cpRow['CPDateStamp'];
									echo date('n/j/Y', strtotime($cpRow['CPDateStamp'])); 
								?>
							</td>
							<td class="cpCol"><?php echo $cpRow['numberCP']; ?></td>
							<td class="catCol"><?php echo $cpRow['CPCatName']; ?></td>
							<td class="noteCol"><?php echo $cpRow['CPNote']; ?></td>
							<td class="staffCol"><?php echo $cpRow['staffMember']; ?></td>
						</tr>
					<?php 
						$rowIndex++;
						} // end CP loop
					?>

						<tr class="total">
							<td>Total:</td>
							<td colspan="4"><?php echo $charCPTotal; ?></td>
						</tr>
					</tbody>
				</table>
			
			</div><!--end of section-->
			<?php 
					} // end of characters loop
				} // end of num_rows condition
			?>
            
			<?php 
				$playerCP = $cp->getCPByPlayer($_SESSION['playerID']);
				if ($playerCP->num_rows > 0) {
			?>
			<p>Unassigned CP will be automatically applied towards the next character you create. 
			<div class="section">
				<h3>Unassigned CP</h3>
			
				<table class="cpTable" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="dateCol">Date</th>
							<th class="cpCol">CP</th>
							<th class="catCol">For</th>
							<th class="noteCol">Note</th>
							<th class="staffCol">Staff Member</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						$pRowIndex = 1;
						$pCPTotal = 0;
						while ($pCPRow = $playerCP->fetch_assoc()) {
							if ($pRowIndex % 2 == 0) {
								$rowClass = 'even';
							} else {
								$rowClass = 'odd';
							}
							
							$pCPTotal = $pCPTotal + $pCPRow['numberCP'];
					
					?>
					
						<tr class="<?php echo $rowClass; ?>">
							<td class="dateCol">
								<?php 
									// echo $cpRow['CPDateStamp'];
									echo date('n/j/Y', strtotime($pCPRow['CPDateStamp'])); 
								?>
							</td>
							<td class="cpCol"><?php echo $pCPRow['numberCP']; ?></td>
							<td class="catCol"><?php echo $pCPRow['CPCatName']; ?></td>
							<td class="noteCol"><?php echo $pCPRow['CPNote']; ?></td>
							<td class="staffCol"><?php echo $pCPRow['staffMember']; ?></td>
						</tr>
					<?php 
						$pRowIndex++;
						} // end CP loop
					?>

						<tr class="total">
							<td>Total:</td>
							<td colspan="4"><?php echo $pCPTotal; ?></td>
						</tr>
					</tbody>
				</table>

			</div><!--end of section-->
			<?php
				}
				if ($playerCP->num_rows == 0 && $characters->num_rows == 0) {
					echo '<p class="noData">You currently have no CP.</p>';
				}
			?>

        </div> <!--end of main div-->
        
        <br class="clear" />
    </div><!--end of content div-->
    
<?php include('../includes/footer.php'); ?>
