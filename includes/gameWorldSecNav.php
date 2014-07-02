<?php

	/**************************************************************
	NAME: 	gameWorldSecNav.php
	NOTES: 	Secondary navigation for game world section of admin area. 
	**************************************************************/

?>		
	
	<div id="secondaryNav" class="<?php echo $secNavClass; ?>">
		<a href="headers.php" id="headersLink">Headers</a>
		<a href="skills.php" id="skillsLink">Skills</a>
		<a href="spells.php" id="spellsLink">Spells</a>
		<a href="feats.php" id="featsLink">Feats</a>
        <a href="countries.php" id="countriesLink">Countries</a>
		<a href="communities.php" id="communitiesLink"><?php echo $_SESSION['communityLabelPlural']; ?></a>
		<?php if ($_SESSION['useRaces'] == 'Yes') { ?>
			<a href="races.php" id="racesLink">Races</a>
		<?php } // end useRaces condition ?>
		<a href="traits.php" id="traitsLink">Traits</a>
	</div>
