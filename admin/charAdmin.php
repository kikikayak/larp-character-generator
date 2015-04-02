<?php 
	
	/**************************************************************
	NAME: 	charAdmin.php
	NOTES: 	Admin version of character wizard. This page allows administrators and staff
			to add and remove headers, skills, spells, feats, etc.  
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'characters';
	$scriptLink = 'wizard.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');	
	
	$wizardChar = new Character();
	
	if (isset($_GET['characterID']) && ctype_digit($_GET['characterID'])) {
		$action = 'update';
		$_SESSION['pageAction'] = 'update';
		
	} else {
		$action = 'create';
		$_SESSION['pageAction'] = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	$_SESSION['debug'] = ''; // Initialize blank
	
	$html = array(); // Initialize array to hold data for display
	
	$wizard = new Wizard(); // Initialize basic wizard object
	
	// Remove any existing character data in session
	if (isset($_SESSION['character'])) {
		unset($_SESSION['character']); 
	}
	
	// Get data to populate UI
	$headers = $wizard->getHeaders();
	$communities = $wizard->getCommunities();
	$countries = $wizard->getCountries();
	$races = $wizard->getRaces();
	
	$player = new Player();
	$playerList = $player->getAllPlayers();
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	NOTE: This section has to go before the initialization section so that the data is
	in session by the time we get to the initialization section. 
	************************************************************************************/
	if (isset($_POST['submitted']) && $_POST['submitted'] == 1) {
	  // Build character associative array to pass to class
	  $character = array(); // Initialize as blank array
	  $character['playerID'] = $_POST['playerID'];
	  $character['charName'] = $_POST['charName'];
	  $character['countryID'] = $_POST['countryID'];
	  $character['communityID'] = $_POST['communityID'];
	  $character['raceID'] = $_POST['raceID'];
	  $character['charType'] = $_POST['charType'];
	  $character['attribute1'] = $_POST['attribute1'];
	  $character['attribute2'] = $_POST['attribute2'];
	  $character['attribute3'] = $_POST['attribute3'];
	  $character['attribute4'] = $_POST['attribute4'];
	  $character['attribute5'] = $_POST['attribute5'];
	  $character['vitality'] = $_POST['vitality'];
	  
	  // Headers
	  $character['charHeaders'] = array(); // Initialize to empty array
	  if (isset($_POST['headerID'])) {
		  $character['charHeaders'] = $_POST['headerID'];
	  }
	  
	  // Skills
	  $character['charSkills'] = array(); // Initialize as empty array
	  if (isset($_POST['skillID'])) {
		  foreach ($_POST['skillID'] as $curID) {
			 // echo 'Current ID: ' . $curID . '<br />';
			  if (isset($_POST["quantity_$curID"]) && $_POST["quantity_$curID"] > 0) {
				  $character['charSkills'][$curID]['id'] = $curID;
				  $character['charSkills'][$curID]['qty'] = $_POST["quantity_$curID"];
			  } else if (isset($_POST["quantity_$curID"])) {
				  continue;
			  } else {
				  $character['charSkills'][$curID]['id'] = $curID;
				  $character['charSkills'][$curID]['qty'] = 1;
			  }
		  }
	  }

	  /* DEBUG
	  foreach ($character['charSkills'] as $curID => $curValue) {
	  	echo 'Skill ' . $curID . ': ' . $curValue . '<br />';
	  } */
	  
	  // Spells
	  $character['charSpells'] = array(); // Initialize to empty array
	  if (isset($_POST['spellID'])) {
		  $character['charSpells'] = $_POST['spellID'];
	  }

	  // Feats
	  $character['charFeats'] = array(); // Initialize to empty array
	  if (isset($_POST['featID'])) {
		  $character['charFeats'] = $_POST['featID'];
	  }
	  
	  // Put all values into session for reconstructing the data in the page
	  $_SESSION['character'] = $character;
	  
	  if ($action == 'create') {
		  // If character insert succeeds, redirect to main page		
		  if ($wizard->createCharacter($character)) {
			  session_write_close();
			  header('Location: characters.php');
		  }
	  } else { // Update
		  if ($wizard->updateCharacter($character, $_GET['characterID'], 'adminWizard')) {
			  session_write_close();
			  header('Location: characters.php');	
		  }
	  }
	} // end of processing condition
		
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/
		
	if ($action == 'create') { 
		// $html['prevCP'] = htmlentities($_SESSION['baseCP']);
		$html['origCP'] = $wizardChar->getWizardFreeCP($_SESSION['playerID']);
		$html['freeCP'] = $wizardChar->getWizardFreeCP($_SESSION['playerID']);
		$html['playerID'] = isset($_SESSION['character']['playerID']) ? htmlentities($_SESSION['character']['playerID']) : $_SESSION['playerID'];
		$html['charName'] = isset($_SESSION['character']['charName']) ? htmlentities($_SESSION['character']['charName']) : '';
		$html['countryID'] = isset($_SESSION['character']['countryID']) ? htmlentities($_SESSION['character']['countryID']) : 1;
		$html['communityID'] = isset($_SESSION['character']['communityID']) ? htmlentities($_SESSION['character']['communityID']) : 1;
		$html['raceID'] = isset($_SESSION['character']['raceID']) ? htmlentities($_SESSION['character']['raceID']) : 1;
		$html['charType'] = isset($_SESSION['character']['charType']) ? htmlentities($_SESSION['character']['charType']) : 'PC';
		$html['attribute1'] = isset($_SESSION['character']['attribute1']) ? htmlentities($_SESSION['character']['attribute1']) : htmlentities($_SESSION['baseAttribute']);
		$html['attribute2'] = isset($_SESSION['character']['attribute2']) ? htmlentities($_SESSION['character']['attribute2']) : htmlentities($_SESSION['baseAttribute']);
		$html['attribute3'] = isset($_SESSION['character']['attribute3']) ? htmlentities($_SESSION['character']['attribute3']) : htmlentities($_SESSION['baseAttribute']);
		$html['attribute4'] = isset($_SESSION['character']['attribute4']) ? htmlentities($_SESSION['character']['attribute4']) : htmlentities($_SESSION['baseAttribute']);
		$html['attribute5'] = isset($_SESSION['character']['attribute5']) ? htmlentities($_SESSION['character']['attribute5']) : htmlentities($_SESSION['baseAttribute']);
		$html['vitality'] = isset($_SESSION['character']['vitality']) ? htmlentities($_SESSION['character']['vitality']) : htmlentities($_SESSION['baseAttribute']);
		// Initialize all saved attribute values to base attribute value
		// Value will be used to calculate whether a user has set the attribute value too low
		$html['saved_attribute1'] =  htmlentities($_SESSION['baseAttribute']);
		$html['saved_attribute2'] =  htmlentities($_SESSION['baseAttribute']);
		$html['saved_attribute3'] =  htmlentities($_SESSION['baseAttribute']);
		$html['saved_attribute4'] =  htmlentities($_SESSION['baseAttribute']);
		$html['saved_attribute5'] =  htmlentities($_SESSION['baseAttribute']);
		
		$title = 'Create a Character | Character Generator';
		$pageHeader = 'Create a Character';
		$pageIntro = '<p class="intro">Welcome! Let\'s get started. </p>';
		// $pageOutro = '<p>Ready? Click "Create Character" to save your character. </p>';
		$pageOutro = '';
		$btnLabel = 'Create Character';
		$finalNote = '';

	} else if ($action == 'update' && isset($_GET['characterID'])) {
		$character = array(); // Initialize as blank array
		$savedCharacter = array();
		$savedChar = new Character();
		$charDetails = $savedChar->getCharDetails($_GET['characterID']);
		while ($savedCharDetails = $charDetails->fetch_assoc()) {
			$html['playerID'] = isset($_SESSION['character']['playerID']) ? htmlentities($_SESSION['character']['playerID']) : htmlentities($savedCharDetails['playerID']);
			$html['charName'] = isset($_SESSION['character']['charName']) ? htmlentities($_SESSION['character']['charName']) : htmlentities($savedCharDetails['charName']);
			$html['charType'] = isset($_SESSION['character']['charType']) ? htmlentities($_SESSION['character']['charType']) : htmlentities($savedCharDetails['charType']);
			$html['countryID'] = isset($_SESSION['character']['countryID']) ? htmlentities($_SESSION['character']['countryID']) : htmlentities($savedCharDetails['countryID']);
			$html['communityID'] = isset($_SESSION['character']['communityID']) ? htmlentities($_SESSION['character']['communityID']) : htmlentities($savedCharDetails['communityID']);
			$html['raceID'] = isset($_SESSION['character']['raceID']) ? htmlentities($_SESSION['character']['raceID']) : htmlentities($savedCharDetails['raceID']);
			$html['attribute1'] = isset($_SESSION['character']['attribute1']) ? htmlentities($_SESSION['character']['attribute1']) : htmlentities($savedCharDetails['attribute1']);
			$html['attribute2'] = isset($_SESSION['character']['attribute2']) ? htmlentities($_SESSION['character']['attribute2']) : htmlentities($savedCharDetails['attribute2']);
			$html['attribute3'] = isset($_SESSION['character']['attribute3']) ? htmlentities($_SESSION['character']['attribute3']) : htmlentities($savedCharDetails['attribute3']);
			$html['attribute4'] = isset($_SESSION['character']['attribute4']) ? htmlentities($_SESSION['character']['attribute4']) : htmlentities($savedCharDetails['attribute4']);
			$html['attribute5'] = isset($_SESSION['character']['attribute5']) ? htmlentities($_SESSION['character']['attribute5']) : htmlentities($savedCharDetails['attribute5']);
			$html['vitality'] = isset($_SESSION['character']['vitality']) ? htmlentities($_SESSION['character']['vitality']) : htmlentities($savedCharDetails['vitality']);
			// Initialize all saved attribute values to base attribute values
			// Value will be used to calculate whether a user has set the attribute value too low
			// User can reduce attribute value below saved value, but not below the base level. 
			$html['saved_attribute1'] = htmlentities($_SESSION['baseAttribute']);
			$html['saved_attribute2'] = htmlentities($_SESSION['baseAttribute']);
			$html['saved_attribute3'] = htmlentities($_SESSION['baseAttribute']);
			$html['saved_attribute4'] = htmlentities($_SESSION['baseAttribute']);
			$html['saved_attribute5'] = htmlentities($_SESSION['baseAttribute']);
		} // end of savedCharDetails loop
		
			$html['freeCP'] = $savedChar->getWizardSavedCharFreeCP($_GET['characterID'], $html['playerID']);
			$html['origCP'] = $savedChar->getWizardSavedCharTotalCP($_GET['characterID'], $html['playerID']); // Used for client-side CP calculations. Initialize to same as free CP value. 
			
			// Set up array to pre-select headers
			$character['charHeaders'] = array(); // Initialize to empty array
			$headerResult = $savedChar->getCharHeaders($_GET['characterID']);
			while ($charHeaders = $headerResult->fetch_assoc()) {
				// Loop through retrieved headers and add to array
				$character['charHeaders'][] = $charHeaders['headerID'];
			}
			
			// SAVED CHARACTER HEADERS
			$savedCharacter['charHeaders'] = array(); // Initialize to empty array
			$savedHeaderResult = $savedChar->getCharHeaders($_GET['characterID']);
			while ($savedCharHeaders = $savedHeaderResult->fetch_assoc()) {
				// Loop through retrieved headers and add to array
				$savedCharacter['charHeaders'][] = $savedCharHeaders['headerID'];
			}
			
			
			// Set up array to pre-select skills
			$character['charSkills'] = array(); // Initialize as empty array
			$skillResult = $savedChar->getCharSkills($_GET['characterID']);
			while ($charSkills = $skillResult->fetch_assoc()) {
				// Loop through retrieved skills and add to array
				$curID = $charSkills['skillID'];
				$character['charSkills'][$curID]['id'] = $curID;
				$character['charSkills'][$curID]['qty'] = $charSkills['quantity'];
			}
			
			// SAVED CHARACTER SKILLS
			$savedCharacter['charSkills'] = array(); // Initialize as empty array
			$savedSkillResult = $savedChar->getCharSkills($_GET['characterID']);
			while ($savedCharSkills = $savedSkillResult->fetch_assoc()) {
				// Loop through retrieved skills and add to array
				$savedCurID = $savedCharSkills['skillID'];
				$savedCharacter['charSkills'][$savedCurID]['id'] = $savedCurID;
				$savedCharacter['charSkills'][$savedCurID]['qty'] = $savedCharSkills['quantity'];
			}
			
			// Set up array to pre-select spells
			$character['charSpells'] = array(); // Initialize to empty array
			$spellResult = $savedChar->getCharSpells($_GET['characterID']);
			while ($charSpells = $spellResult->fetch_assoc()) {
				// Loop through retrieved headers and add to array
				$character['charSpells'][] = $charSpells['spellID'];
			}
			
			// SAVED CHARACTER SPELLS
			$savedCharacter['charSpells'] = array(); // Initialize to empty array
			$savedSpellResult = $savedChar->getCharSpells($_GET['characterID']);
			while ($savedCharSpells = $savedSpellResult->fetch_assoc()) {
				// Loop through retrieved headers and add to array
				$savedCharacter['charSpells'][] = $savedCharSpells['spellID'];
			}

			// Set up array to pre-select feats
			$character['charFeats'] = array(); // Initialize to empty array
			$featResult = $savedChar->getCharFeats($_GET['characterID']);
			while ($charFeats = $featResult->fetch_assoc()) {
				// Loop through retrieved headers and add to array
				$character['charFeats'][] = $charFeats['featID'];
			}
			
			// SAVED CHARACTER FEATS
			$savedCharacter['charFeats'] = array(); // Initialize to empty array
			$savedFeatResult = $savedChar->getCharFeats($_GET['characterID']);
			while ($savedCharFeats = $savedFeatResult->fetch_assoc()) {
				// Loop through retrieved feats and add to array
				$savedCharacter['charFeats'][] = $savedCharFeats['featID'];
			}
			
			// Put all values into session for reconstructing the data in the page
			$_SESSION['character'] = $character;
			$_SESSION['savedCharacter'] = $savedCharacter;
			
			$title = 'Update Character ' . $html['charName'] . ' | Character Generator';
			$pageHeader = 'Update Character ' . $html['charName'];
			$pageIntro = '<p class="intro">You can add or remove items using this administrative version of the wizard. </p>';
			$pageOutro = '<p>Click "Update Character" to save your changes. </p>';
			$btnLabel = 'Update Character';
			$finalNote = '';
	}
	
	$scriptLink = 'wizard.js';
	// Include page header
	include('../includes/header_admin.php');
?>

<body id="adminWizardPage" class="home">

    <?php include('../includes/adminNav.php'); ?>
	
    <div id="content">
		<form name="charWizard" id="charWizard" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

		<div id="container">
			<div id="sidebar">
				<div id="cpCount">
					<h2>CP Remaining</h2>
					<div class="inner">
						<p id="cpNum"><?php echo $html['freeCP']; ?></p>
						<input type="hidden" name="origCPTotal" id="origCPTotal" value="<?php echo $html['origCP']; ?>" />
						<span id="origCPTotalVal" style="display:none"></span>
					</div>
				</div><!--/cpCount-->
				
				<div id="charSummary">
					<h2>Character Summary</h2>
					<div id="charDetails" class="inner">
						<div class="summarySection">
							<span class="label">Name:</span><br />
							<span id="summaryName"><em>None</em></span>
						</div>
						
						<div class="summarySection">
							<span class="label"><?php echo $_SESSION['attribute1Label']; ?>:</span>
							<span id="summaryAttribute1" class="summaryAttribute"> <?php echo $html['attribute1']; ?></span><br />

							<span class="label"><?php echo $_SESSION['attribute2Label']; ?>:</span>
							<span id="summaryAttribute2" class="summaryAttribute"> <?php echo $html['attribute2']; ?></span><br />

							<span class="label"><?php echo $_SESSION['attribute3Label']; ?>:</span>
							<span id="summaryAttribute3" class="summaryAttribute"> <?php echo $html['attribute3']; ?></span><br />

							<span class="label"><?php echo $_SESSION['attribute4Label']; ?>:</span> 
							<span id="summaryAttribute4" class="summaryAttribute"><?php echo $html['attribute4']; ?></span><br />

							<span class="label"><?php echo $_SESSION['attribute5Label']; ?>:</span>
							<span id="summaryAttribute5" class="summaryAttribute"> <?php echo $html['attribute5']; ?></span><br />

							<span class="label"><?php echo $_SESSION['vitalityLabel']; ?>:</span>
							<span id="summaryVitality" class="summaryAttribute"> <?php echo $html['vitality']; ?></span><br />
						</div>

						<div class="summarySection">
							<span class="label">Headers &amp; Skills</span><br />
							<div id="summaryHeaderList">
								Open Skills
	                        </div>
	                    </div>
						
						<div class="summarySection">
							<span class="label">Spells</span><br />
							<div id="summarySpellList">
								<em>None</em>
	                        </div>
	                    </div>

	                    <div class="summarySection">
							<span class="label">Feat</span><br />
							<div id="summaryFeatList">
								<em>None</em>
	                        </div>
	                    </div>

					</div><!--/charDetails-->
				</div><!--/charSummary-->
			</div><!--/sidebar-->
        
        
			<div id="main">
				<?php cg_showUIMessage(); ?>
				
				<h2><?php echo $pageHeader; ?></h2>
				<?php echo $pageIntro; ?>
				
				<div id="wizardTabPanel" class="tabPanel">
					<a href="#" id="basics_tab" class="selected">Basics</a>
					<a href="#" id="attributes_tab">Attributes</a>
					<a href="#" id="skills_tab">Headers &amp; Skills</a>
					
                    <?php
						// Show spells tab if there are any spells in the system
						$spellObj = new Spell();
						$totalSpells = $spellObj->getTotalSpells();
						if ($totalSpells > 0) {
					?>
                    	<a href="#" id="spells_tab">Spells</a>
                    <?php
						}
						// Show feats tab if there are any feats in the system
						$featObj = new Feat();
						$totalFeats = $featObj->getTotalFeats();
						if ($totalFeats > 0) {
					?>
                    	<a href="#" id="feats_tab">Feats</a>
                    <?php
					  }
					?>
				</div>
				
				<!--****************************************************
                	BASICS TAB
                    ****************************************************-->
                
                <div id="basics" class="section-tabbed">
					
					<!--Character name-->
					<?php cg_createRow('player'); ?>
						<label>Player</label>
						<select name="playerID" id="playerID">
							<?php
								while ($playerRow = $playerList->fetch_assoc()) { // Loop through retrieved players
							?>
								<option value="<?php echo $playerRow['playerID']; ?>" <?php if ($playerRow['playerID'] == $html['playerID']) echo 'selected="selected"'; ?>><?php echo $playerRow['firstName'] . ' ' . $playerRow['lastName'] . ' (' . $playerRow['email'] . ')'; ?></option>
							<?php
								} // End of players loop
							?>
						</select>
						<?php cg_showError('playerID'); ?>
						<br class="clear" />
					</div><!--.row-->

					<!--Character name-->
					<?php cg_createRow('charName'); ?>
						<label>Character Name</label>
						<input type="text" name="charName" id="charName" class="xl2" value="<?php echo $html['charName']; ?>" onChange="changeCharName('charName')" />
						<?php cg_showError('charName'); ?>
						<br class="clear" />
					</div><!--.row-->
					
                    <?php 
						if ($_SESSION['userRole'] == 'Staff' || $_SESSION['userRole'] == 'Admin') {
					?>
					<!--Character type-->
					<?php cg_createRow('charType'); ?>
						<label>Type</label>
						<select name="charType" id="charType">
							<option value="PC" <?php if ($html['charType'] == 'PC') echo 'selected="selected"'; ?>>PC</option>
							<option value="NPC" <?php if ($html['charType'] == 'NPC') echo 'selected="selected"'; ?>>NPC</option>
						</select>
						<?php cg_showError('charType'); ?>
						<br class="clear" />
					</div>
                    <?php
						} else {
							echo '<input type="hidden" id="charType" name="charType" value="PC" />';	
						}
					?><!--.row-->
					
					<!--Country-->
					<?php cg_createRow('countryID'); ?>
						<label>Country of Origin</label>
						<select name="countryID" id="countryID" onChange="changeRequiredDropdown('countryID')">
							<?php
								while ($countryRow = $countries->fetch_assoc()) { // Loop through retrieved countries
							?>
								<option value="<?php echo $countryRow['countryID']; ?>" <?php if ($countryRow['countryID'] == $html['countryID']) echo 'selected="selected"'; ?>><?php echo $countryRow['countryName']; ?></option>
							<?php
								} // End of countries loop
							?>
						</select>
						<?php cg_showError('countryID'); ?>
						<br class="clear" />
					</div>
					
					<!--Community-->
					<?php cg_createRow('communityID'); ?>
						<label><?php echo $_SESSION['communityLabel']; ?></label>
						<select name="communityID" id="communityID" onChange="changeRequiredDropdown('communityID')">
							<?php
								while ($communityRow = $communities->fetch_assoc()) { // Loop through retrieved groups
							?>
								<option value="<?php echo $communityRow['communityID']; ?>" <?php if ($communityRow['communityID'] == $html['communityID']) echo 'selected="selected"'; ?>><?php echo $communityRow['communityName']; ?></option>
							<?php
								} // End of communities loop
							?>
						</select>
						<?php cg_showError('communityID'); ?>
	
						<br class="clear" />
					</div>
					
                    <!--Race-->
					<?php
						if ($_SESSION['useRaces'] == 'Yes') {
					?>			
					<?php cg_createRow('raceID'); ?>
						<label>Race</label>
						<select name="raceID" id="raceID" onChange="changeRequiredDropdown('raceID')">
							<?php
								while ($race = $races->fetch_assoc()) { // Loop through retrieved races
							?>
								<option value="<?php echo $race['raceID']; ?>" <?php if ($race['raceID'] == $html['raceID']) echo 'selected="selected"'; ?>><?php echo $race['raceName']; ?></option>
							<?php
								} // End of races loop
							?>
						</select>
						<?php cg_showError('raceID'); ?>
						<br class="clear" />
					</div>
                    <?php
						} else {
							echo '<input type="hidden" id="raceID" name="raceID" value="1" />';	
						}// end races condition
					?>
					
				</div><!--end of basics div-->
				
				<!--************************************************************************************
					ATTRIBUTES SECTION
					************************************************************************************ -->
				
				 <div id="attributes" class="section-tabbed" style="display:none">
					<p class="intro">Attributes are used to power many of the skills your character will use in-game.</p>
					<input type="hidden" name="baseAttribute" id="baseAttribute" value="<?php echo $_SESSION['baseAttribute']; ?>" />
					
					<div id="attributeInfoArrow" style="display:none"></div>
					<div id="attributeInfo" style="display:none">
						<!--<div id="attributeInfoArrowBorder"></div>-->
						<div id="attributeInfoContent">
							<!--Will be populated by AJAX call-->
						</div><!-- end of attributeInfoContent-->
					
					</div>
					
					<!--ATTRIBUTE 1: AIR-->
					<div class="row">
						<label><?php echo $_SESSION['attribute1Label']; ?><br />
							<a href="#" class="attributeIncrease" id="attribute1_increase" title="Increase <?php echo $_SESSION['attribute1Label']; ?> by 1"></a>
							<a href="#" class="attributeDecrease" id="attribute1_decrease" title="Decrease <?php echo $_SESSION['attribute1Label']; ?> by 1"></a>
						</label>
						<div id="attribute1Display" class="attributeNumDisplay"><?php echo $html['attribute1']; ?></div>
						<div id="attribute1Vis" class="attributeVis" onMouseOver="showAttributeUsage('attribute1')" onMouseOut="hideAttributeUsage('attribute1')">
							<?php
								// Display proper number of attribute ticks
								for ($i = 0; $i < $html['attribute1']; $i++) {
									echo '<div class="attributeTick"></div>';
								}
							?>
						</div>
						<input type="hidden" name="attribute1" id="attribute1" value="<?php echo $html['attribute1']; ?>" maxlength="2" class="attributeFld" onChange="changeAttribute('attribute1', 'admin');" />
						<input type="hidden" name="prev_attribute1" id="prev_attribute1" value="<?php echo $html['attribute1']; ?>" maxlength="2" />
						<input type="hidden" name="saved_attribute1" id="saved_attribute1" value="<?php echo $html['saved_attribute1']; ?>" maxlength="2" />
						<br class="clear" />
					</div>
					
					<!--ATTRIBUTE 2: EARTH-->
					<div class="row">
						<label><?php echo $_SESSION['attribute2Label']; ?><br />
							<a href="#" class="attributeIncrease" id="attribute2_increase" title="Increase <?php echo $_SESSION['attribute2Label']; ?> by 1"></a>
							<a href="#" class="attributeDecrease" id="attribute2_decrease" title="Decrease <?php echo $_SESSION['attribute2Label']; ?> by 1"></a>
						</label>
						<div id="attribute2Display" class="attributeNumDisplay"><?php echo $html['attribute2']; ?></div>
						<div id="attribute2Vis" class="attributeVis" onMouseOver="showAttributeUsage('attribute2')" onMouseOut="hideAttributeUsage('attribute2')">
							<?php
								// Display proper number of attribute ticks
								for ($i = 0; $i < $html['attribute2']; $i++) {
									echo '<div class="attributeTick"></div>';
								}
							?>
						</div>
                        <input type="hidden" name="attribute2" id="attribute2" value="<?php echo $html['attribute2']; ?>" maxlength="2" class="attributeFld" onChange="changeAttribute('attribute2');" />
						<input type="hidden" name="prev_attribute2" id="prev_attribute2" value="<?php echo $html['attribute2']; ?>" maxlength="2" />
						<input type="hidden" name="saved_attribute2" id="saved_attribute2" value="<?php echo $html['saved_attribute2']; ?>" maxlength="2" />
						<br class="clear" />
					</div>
					
					<!--ATTRIBUTE 3: FIRE-->  
					<div class="row">
						<label><?php echo $_SESSION['attribute3Label']; ?><br />
							<a href="#" class="attributeIncrease" id="attribute3_increase" title="Increase <?php echo $_SESSION['attribute3Label']; ?> by 1"></a>
							<a href="#" class="attributeDecrease" id="attribute3_decrease" title="Decrease <?php echo $_SESSION['attribute3Label']; ?> by 1"></a>
						</label>
						<div id="attribute3Display" class="attributeNumDisplay"><?php echo $html['attribute3']; ?></div>
						<div id="attribute3Vis" class="attributeVis" onMouseOver="showAttributeUsage('attribute3')" onMouseOut="hideAttributeUsage('attribute3')">
							<?php
								// Display proper number of attribute ticks
								for ($i = 0; $i < $html['attribute3']; $i++) {
									echo '<div class="attributeTick"></div>';
								}
							?>
							
						</div>
                        <input type="hidden" name="attribute3" id="attribute3" value="<?php echo $html['attribute3']; ?>" maxlength="2" class="attributeFld" onChange="changeAttribute('attribute3');" />
                        <input type="hidden" name="prev_attribute3" id="prev_attribute3" value="<?php echo $html['attribute3']; ?>" maxlength="2" />
						<input type="hidden" name="saved_attribute3" id="saved_attribute3" value="<?php echo $html['saved_attribute3']; ?>" maxlength="2" />
						<br class="clear" />
					</div>
					
					<!--ATTRIBUTE 4: WATER-->
					<div class="row">
						<label><?php echo $_SESSION['attribute4Label']; ?><br />
							<a href="#" class="attributeIncrease" id="attribute4_increase" title="Increase <?php echo $_SESSION['attribute4Label']; ?> by 1"></a>
							<a href="#" class="attributeDecrease" id="attribute4_decrease" title="Decrease <?php echo $_SESSION['attribute4Label']; ?> by 1"></a>
						</label>
						<div id="attribute4Display" class="attributeNumDisplay"><?php echo $html['attribute4']; ?></div>
						<div id="attribute4Vis" class="attributeVis" onMouseOver="showAttributeUsage('attribute4')" onMouseOut="hideAttributeUsage('attribute4')">
							<?php
								// Display proper number of attribute ticks
								for ($i = 0; $i < $html['attribute4']; $i++) {
									echo '<div class="attributeTick"></div>';
								}
							?>
							
						</div>
						<input type="hidden" name="attribute4" id="attribute4" value="<?php echo $html['attribute4']; ?>" maxlength="2" class="attributeFld" onChange="changeAttribute('attribute4');" />
                        <input type="hidden" name="prev_attribute4" id="prev_attribute4" value="<?php echo $html['attribute4']; ?>" maxlength="2" />
						<input type="hidden" name="saved_attribute4" id="saved_attribute4" value="<?php echo $html['saved_attribute4']; ?>" maxlength="2" />
						<br class="clear" />
					</div>
					
					<!--ATTRIBUTE 5: VOID-->  
					<div class="row">
						<label><?php echo $_SESSION['attribute5Label']; ?><br />
							<a href="#" class="attributeIncrease" id="attribute5_increase" title="Increase <?php echo $_SESSION['attribute5Label']; ?> by 1"></a>
							<a href="#" class="attributeDecrease" id="attribute5_decrease" title="Decrease <?php echo $_SESSION['attribute5Label']; ?> by 1"></a>
						</label>
						<div id="attribute5Display" class="attributeNumDisplay"><?php echo $html['attribute5']; ?></div>
						<div id="attribute5Vis" class="attributeVis" onMouseOver="showAttributeUsage('attribute5')" onMouseOut="hideAttributeUsage('attribute5')">
							<?php
								// Display proper number of attribute ticks
								for ($i = 0; $i < $html['attribute5']; $i++) {
									echo '<div class="attributeTick"></div>';
								}
							?>
							
						</div>
						<input type="hidden" name="attribute5" id="attribute5" value="<?php echo $html['attribute5']; ?>" maxlength="2" class="attributeFld" onChange="changeAttribute('attribute5');" />
                        <input type="hidden" name="prev_attribute5" id="prev_attribute5" value="<?php echo $html['attribute5']; ?>" maxlength="2" />
						<input type="hidden" name="saved_attribute5" id="saved_attribute5" value="<?php echo $html['saved_attribute5']; ?>" maxlength="2" />
						<br class="clear" />
					</div>
					
					<div id="vitalityRow" class="row">
						<label>Vitality</label>
						<span id="curVitality" class="data" style="display:none"><?php echo $html['vitality']; ?></span>
						<div id="vitalityDisplay" class="attributeNumDisplay"><?php echo $html['vitality']; ?></div>
						<div id="vitalityVis" class="attributeVis">
							<?php
								// Display proper number of attribute ticks
								for ($i = 0; $i < $html['vitality']; $i++) {
									echo '<div class="attributeTick"></div>';
								}
							?>
						</div>
						<input type="hidden" name="vitality" id="vitality" value="<?php echo $html['vitality']; ?>" />
						<br class="clear" />
					</div>
					<div class="row" style="display:none">
						<label>Total CP for Attributes:</label>
						<span id="totalAttributeCost" class="data">0</span>
						<span class="units">CP</span>
						<br class="clear" />
					</div>
				 </div><!--end of attributes div-->
				 
				<!--************************************************************************************
					SKILLS TAB
					************************************************************************************ -->
				
				<div id="skills" class="section-tabbed" style="display:none">
				
				<?php
				// Open headers loop
				while ($row = $headers->fetch_assoc()) {
				?>
					
					<div id="<?php echo 'headerID_' . $row['headerID'] . 'Hdr'; ?>" class="header">
						<div class="row">
							<div class="cell0">
								<img src="../theme/<?php echo THEME; ?>/images/arrowRight.png" class="expandContractArrow" id="<?php echo 'headerID_' . $row['headerID'] . '_arrow'; ?>" alt="Click to expand or contract header contents" title="Click to expand or contract header contents" /></div>
							<div class="cell1">
								
                                <input 	type="checkbox" 
                                		class="headerFld" 
                                        name="headerID[]" 
                                        id="<?php echo 'headerID_' . $row['headerID']; ?>" 
                                        value="<?php echo $row['headerID']; ?>" 
										<?php if ((isset($_SESSION['character']) && in_array($row['headerID'], $_SESSION['character']['charHeaders'])) || ($action == 'update' && isset($_SESSION['savedCharacter']) && in_array($row['headerID'], $_SESSION['savedCharacter']['charHeaders']))) echo 'checked="checked"'; ?> 
								/>
                                        
								<input type="hidden" name="<?php echo 'headerID_' . $row['headerID'] . '_cost'; ?>" id="<?php echo 'headerID_' . $row['headerID'] . '_cost'; ?>" value="<?php echo $row['headerCost']; ?>" />
							</div>
							<div class="cell2">
								<label><?php echo $row['headerName']; ?></label> 
							</div>
							<div class="cpCost">
								<span class="costHdr">Cost:</span> <?php echo $row['headerCost']; ?> CP
							</div>
							<div class="description">
								<p><?php echo $row['headerDescription']; ?></p>
							</div>
						</div><!--.row-->
					</div> <!--.header-->
						
					<div id="<?php echo 'header' . $row['headerID'] . 'Skills'; ?>" class="skillGrp" style="display: none">
				
					<?php 
						$headerSkills = $wizard->getSkillsForHeader($row['headerID']); // Get skills for this header
						
						while ($skillRow = $headerSkills->fetch_assoc()) { // Loop through skills and create one row per skill
						?>			
						<div class="row">
							
							<div class="cell1">
								<?php 
									// Display checkbox or dropdown depending on whether or not skill is stackable
									if ($skillRow['maxQuantity'] <= 1) {
									  // Skill is not stackable. Display a checkbox. 
								?>
                                	
								<input 
                                		type="checkbox" 
                                        class="skillFld" 
                                        name="skillID[]" 
                                        id="<?php echo 'skillID_' . $skillRow['skillID']; ?>" 
                                        value="<?php echo $skillRow['skillID']; ?>" 
                                        onclick="selectSkill(this, 1);" 
										<?php if ((isset($_SESSION['character']) && array_key_exists($skillRow['skillID'], $_SESSION['character']['charSkills'])) || ($action == 'update' && isset($_SESSION['savedCharacter']) && array_key_exists($skillRow['skillID'], $_SESSION['savedCharacter']['charSkills']))) echo 'checked="checked"'; ?> 
                                />
                                
								<input type="hidden" name="<?php echo 'skillID_' . $skillRow['skillID'] . '_cost'; ?>" id="<?php echo 'skillID_' . $skillRow['skillID'] . '_cost'; ?>" value="<?php echo $skillRow['skillCost']; ?>" />
							
							<?php 
								} else {
									// Skill is stackable. Display dropdown.
							?>
									<select class="skillQtyFld" name="<?php echo 'quantity_' . $skillRow['skillID']; ?>" id="<?php echo 'quantity_' . $skillRow['skillID']; ?>" onchange="changeStackableSkill(this, <?php echo $skillRow['skillCost']; ?>);" >
										<?php 
											// Automatically generate appropriate quantities for this skill
											for ($i = 0; $i <= $skillRow['maxQuantity']; $i++) {
										?>
										<option 
											value="<?php echo $i; ?>" 
											<?php if (isset($_SESSION['character']) && array_key_exists($skillRow['skillID'], $_SESSION['character']['charSkills']) && $_SESSION['character']['charSkills'][$skillRow['skillID']]['qty'] == $i) echo 'selected="selected"'; ?> ><?php echo $i; ?></option>
										<?php
											} // End quantity loop
										?>
									</select>
									<input type="checkbox" class="skillFld stackableSkillFld" name="skillID[]" id="<?php echo 'skillID_' . $skillRow['skillID']; ?>" value="<?php echo $skillRow['skillID']; ?>" <?php if (isset($_SESSION['character']) && array_key_exists($skillRow['skillID'], $_SESSION['character']['charSkills'])) echo 'checked="checked"'; ?> style="display:none" />
									<input type="hidden" name="<?php echo 'prev_quantity_' . $skillRow['skillID']; ?>" id="<?php echo 'prev_quantity_' . $skillRow['skillID']; ?>" value="0" />
									<input type="hidden" name="<?php echo 'orig_quantity_' . $skillRow['skillID']; ?>" id="<?php echo 'orig_quantity_' . $skillRow['skillID']; ?>" value="0" />
									<input type="hidden" name="<?php echo 'skillID_' . $skillRow['skillID'] . '_cost'; ?>" id="<?php echo 'skillID_' . $skillRow['skillID'] . '_cost'; ?>" value="<?php echo $skillRow['skillCost']; ?>" />
									<input type="hidden" name="<?php echo 'skillID_' . $skillRow['skillID'] . '_costIncrement'; ?>" id="<?php echo 'skillID_' . $skillRow['skillID'] . '_costIncrement'; ?>" value="<?php echo $skillRow['costIncrement']; ?>" />
								
								<?php
									} // end of stackable/non-stackable condition
								?>
							</div><!--.cell1-->
							<div class="cell2">
								<label><?php echo $skillRow['skillName']; ?></label>
							</div>                  
							<div class="cpCost"><span class="costHdr">Cost:</span> <?php echo $skillRow['skillCost']; ?> CP</div>
							 <?php 
								// Display max levels only if skill is stackable
								if ($skillRow['maxQuantity'] > 1) {
							?>
							<div class="maxLevels">
								<span class="maxLvlHdr">Max Levels:</span> <?php echo $skillRow['maxQuantity']; ?></div>
							<?php
								} // end of "if stackable" condition
							?>
							<div class="description">
								<p><?php echo $skillRow['shortDescription']; ?> 
									<?php
										if (!is_empty($skillRow['skillDescription'])) {
									?>
									&nbsp;
									<a href="#" class="shortDescLink" id="<?php echo 'shortDescLink_' . $skillRow['skillID']; ?>">Details &gt;&gt;</a>
									<?php
										}
									?>
								</p>
							</div>
							<div class="longDescription" style="display:none">
								<p><?php echo $skillRow['skillDescription']; ?> &nbsp;
									<a href="#" class="longDescLink" id="<?php echo 'longDescLink_' . $skillRow['skillID']; ?>">&lt;&lt; Hide Details</a>
								</p>
							</div>
						</div><!--.row-->
											
					<?php 
						// Close skills for this header loop
						}
					?>
					</div><!--.skillGrp-->
	
					<?php
					// Close headers loop
					}
					?>
				</div><!--#skills-->
				
				<!--************************************************************************************
					SPELLS TAB
                    Data is populated via AJAX call in wizard.js
					************************************************************************************ -->
				
				<div id="spells" class="section-tabbed" style="display:none">
					<p class="noData">Please select at least one header and/or skill that enables you to purchase spells. </p>
				</div><!--#spells-->
                
                <!--************************************************************************************
					FEATS TAB
                    Data is populated via AJAX call in wizard.js
					************************************************************************************ -->
                    
                <div id="feats" class="section-tabbed" style="display:none">
                	<!--Will be populated via AJAX call-->
				</div><!--#feats-->
				
				<!--************************************************************************************
					BUTTONS
					************************************************************************************ -->
                
                <div id="btnArea">
					<input type="hidden" name="submitted" id="submitted" value="1" />

					<?php 
						echo $pageOutro;
					?>
						<input type="submit" name="submit" id="submitBtn" class="btn-primary" value="<?php echo $btnLabel; ?>" />
						<input type="button" name="cancel" id="cancelBtn" class="btn-secondary" value="Cancel" />
					
					<div id="submitWarning" class="warning" style="display:none"></div>
					<br class="clear" />
					<?php echo $finalNote; ?>
				</div><!--#btnArea-->
			</div> <!--#main-->
        
        	<br class="clear" />
		</div><!--#container-->
		</form>
    </div><!--#content-->
	<?php 
		// Clear UI message if necessary
		if (isset($_SESSION['UIMessage'])) {
			unset($_SESSION['UIMessage']);
		}
	?>
    
<?php include('../includes/footer.php'); ?>
