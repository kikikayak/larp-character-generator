<?php

	/**************************************************************
	NAME: 	cheatSheet.php
	NOTES: 	This is the main page of the user area, the first page
			the user sees after logging in. 
	**************************************************************/

	$pageAccessLevel = 'User';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');

	if (!isset($_GET['characterID'])) {
	  header('Location: index.php'); // Take user to main page
	  exit();   
	}
	
	$character = new Character();
	
	if (!$character->checkCharacterAccess($_GET['characterID'], $_SESSION['playerID'])) {
	  header('Location: index.php'); // Take user to main page
	  exit();	
	}
	
	$charDetails = $character->getCharDetails($_GET['characterID']);
	
	// Get skills and feats
	$charSkills = $character->getCheatSheetSkills($_GET['characterID']);
	$charFeats = $character->getCharFeats($_GET['characterID']);
	
	// Get CP totals
	$charTotalCP = $character->getTotalCharCP($_GET['characterID']);
	$charFreeCP = $character->getCharFreeCP($_GET['characterID']);
	
	$title = "Character Cheat Sheet | " . $_SESSION['campaignName'] . " Character Generator";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo 'Character Cheat Sheet | ' . $_SESSION['campaignName'] . ' Character Generator'; ?></title>

	<link href='http://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../js/ui/css/theme/ui.min.css" />
	<link rel="stylesheet/less" type="text/css" href="../theme/classic/cheatSheet.less" />

	<script type="text/javascript">
	    // Set LESS parameters
		less = {
	        env:"development",
	        dumpLineNumbers: "all", // or "mediaQuery" or "all"
			relativeUrls: true // whether to adjust url's to be relative
	                            // if false, url's are already relative to the
	                            // entry less file
	    };
		
	</script>

	<script type="text/javascript" src="../js/less-1.3.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/ui/js/jquery-ui-1.10.3.min.js"></script>
	<script type="text/javascript" src="../js/library.js"></script>
	<script type="text/javascript" src="../js/main.js"></script>

</head>

<body id="cheatSheetPage">

<?php 
	while ($character = $charDetails->fetch_assoc()) {
?>

<h1><?php echo $character['charName']; ?></h1>
<h2>Player: <?php echo $character['firstName'] . ' ' . $character['lastName']; ?></h2>

<div id="attributes" class="section">
	<div id="charStats">
		<div class="statBox">
			<p><?php echo $charTotalCP; ?></p>
			<p class="lbl">Total CP</p>
		</div>
		<div class="statBox">
			<p><?php echo $charFreeCP; ?></p>
			<p class="lbl">Unspent CP</p>
		</div>
		<div class="statBox">
			<p><?php echo $character['vitality']; ?></p>
			<p class="lbl"><?php echo $_SESSION['vitalityLabel']; ?></p>
		</div>
	</div>
</div>

<div id="costs" class="section">
	<h3>Attribute Costs and Guidelines</h3>
	
	<?php 
		if ($charSkills->num_rows > 0) {
	?>

	<table class="costTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="col1">Skill</th>
				<th class="col2">Notes</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$rowIndex = 0;
				while ($skill = $charSkills->fetch_assoc()) {
					if ($rowIndex % 2 == 0) {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
			?>
			<tr class="<?php echo $rowClass; ?>">
				<td class="col1">
					<?php 
						echo $skill['skillName']; 
						if ($skill['quantity'] > 1) echo ' x ' . $skill['quantity']; 
					?>
				</td>
				<td class="col2"><?php echo $skill['cheatSheetNote']; ?></td>
			</tr>
			<?php
				$rowIndex++;
				}
			?>
		</tbody>
	</table>
	<?php 
		} else {
			echo '<p class="noResults">None</p>';
		}
	?>
</div>

<div id="usage" class="section">
	<h3>Attribute Usage</h3>

	<p>Check off your attributes as you use them throughout the event. </p>
	
	<?php 
		for ($i = 1; $i<=$character['attribute5'] + 1; $i++) {
	?>
	<table class="usageTable" cellspacing="0" cellpadding="4">
		<thead>
			<tr> 
				<th colspan="2">Void refresh <?php echo $i; ?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="odd"> 
				<th><?php echo $_SESSION['attribute1Label']; ?></th>
				<td>
					<?php 
						for ($j = 1; $j <= $character['attribute1']; $j++) {
					?>
					<input name="attr1" type="checkbox" value="<?php echo $j; ?>" />
					&nbsp;&nbsp;
					<?php
						}
					?>
				</td>
			</tr>
			<tr class="even"> 
				<th><?php echo $_SESSION['attribute2Label']; ?></th>
				<td> 
					<?php 
						for ($k = 1; $k <= $character['attribute2']; $k++) {
					?>
					<input name="attr2" type="checkbox" value="<?php echo $k; ?>" />
					&nbsp;&nbsp;
					<?php
						}
					?>
				</td>
			</tr>
			<tr class="odd"> 
				<th><?php echo $_SESSION['attribute3Label']; ?></th>
				<td> 
					<?php 
						for ($l = 1; $l <= $character['attribute3']; $l++) {
					?>
					<input name="attr3" type="checkbox" value="<?php echo $l; ?>" />
					&nbsp;&nbsp;
					<?php
						}
					?>
				</td>
			</tr>
			<tr class="even"> 
				<th><?php echo $_SESSION['attribute4Label']; ?></th>
				<td> 
					<?php 
						for ($m = 1; $m <= $character['attribute1']; $m++) {
					?>
					<input name="attr4" type="checkbox" value="<?php echo $m; ?>" />
					&nbsp;&nbsp;
					<?php
						}
					?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
		}
	?>

</div><!--#usage-->

<?php
	} // end of main character result loop
?>

<!-- ****************************************************************************
	FEATS
	************************************************************************* -->

<div id="feats" class="section">
	<h3>Feat</h3>
	<?php 
		if ($charFeats->num_rows > 0) {
	?>
	<table class="costTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="col1">Feat</th>
				<th class="col2">Notes</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$rowIndex = 0;
				while ($feat = $charFeats->fetch_assoc()) {
					if ($rowIndex % 2 == 0) {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
			?>
			<tr class="<?php echo $rowClass; ?>">
				<td class="col1">
					<?php 
						echo $feat['featName'];
					?>
				</td>
				<td class="col2"><?php echo $feat['featCheatSheetNote']; ?></td>
			</tr>
			<?php
				$rowIndex++;
				}
			?>
		</tbody>
	</table>
	<?php 
		} else {
			echo '<p class="noResults">None</p>';
		}
	?>

</div><!--#feats-->



</body>
</html>
