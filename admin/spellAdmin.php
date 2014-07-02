<?php

	/**************************************************************
	NAME: 	spellAdmin.php
	NOTES: 	This page allows staff members to add or edit spells. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'spells';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['spellID']) && ctype_digit($_GET['spellID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/ 
	
	$html = array(); // Initialize array to hold data for display
	
	$spellObj = new Spell(); // Instantiate spell object
	
	// Get data to populate dropdown lists
	$skillObj = new Skill();
	$skills = $skillObj->getAllSkills();
	
	// Get data to populate dropdown lists
	$character = new Character();
	$characters = $character->getAllCharacters();
	
	if ($action == 'create') { 
		$html['spellName'] = isset($_POST['spellName']) ? htmlentities($_POST['spellName']) : '';
		$html['spellCost'] = isset($_POST['spellCost']) ? htmlentities($_POST['spellCost']) : '';
		$html['spellAccess'] = isset($_POST['spellAccess']) ? htmlentities($_POST['spellAccess']) : '';
		$html['PCAccess'] = isset($_POST['PCAccess']) ? htmlentities($_POST['PCAccess']) : array(); // Initialize as an empty array
		$html['skillID'] = isset($_POST['skillID']) ? htmlentities($_POST['skillID']) : '';
		$html['spellShortDescription'] = isset($_POST['spellShortDescription']) ? htmlentities($_POST['spellShortDescription']) : '';
		$html['spellDescription'] = isset($_POST['spellDescription']) ? htmlentities($_POST['spellDescription']) : '';
		$html['spellCheatSheetNote'] = isset($_POST['spellCheatSheetNote']) ? htmlentities($_POST['spellCheatSheetNote']) : '';
		$html['attributeCost1'] = isset($_POST['attributeCost1']) ? htmlentities($_POST['attributeCost1']) : '';
		$html['attribute1'] = isset($_POST['attribute1']) ? htmlentities($_POST['attribute1']) : '';
		$html['attributeCost2'] = isset($_POST['attributeCost2']) ? htmlentities($_POST['attributeCost2']) : '';
		$html['attribute2'] = isset($_POST['attribute2']) ? htmlentities($_POST['attribute2']) : '';
		$html['attributeCost3'] = isset($_POST['attributeCost3']) ? htmlentities($_POST['attributeCost3']) : '';
		$html['attribute3'] = isset($_POST['attribute3']) ? htmlentities($_POST['attribute3']) : '';
		
		$title = 'Add a Spell | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a Spell';
		$btnLabel = 'Add';
		
	} else if ($action == 'update' && isset($_GET['spellID'])) {
		$spellDetails = $spellObj->getSpell($_GET['spellID']);
		while ($savedSpellDetails = $spellDetails->fetch_assoc()) {
			$html['spellName'] = isset($_POST['spellName']) ? htmlentities($_POST['spellName']) : htmlentities($savedSpellDetails['spellName']);
			$html['spellCost'] = isset($_POST['spellCost']) ? htmlentities($_POST['spellCost']) : htmlentities($savedSpellDetails['spellCost']);
			$html['spellAccess'] = isset($_POST['spellAccess']) ? htmlentities($_POST['spellAccess']) : htmlentities($savedSpellDetails['spellAccess']);
			$html['PCAccess'] = isset($_POST['PCAccess']) ? htmlentities($_POST['PCAccess']) : array();
			$html['skillID'] = isset($_POST['skillID']) ? htmlentities($_POST['skillID']) : htmlentities($savedSpellDetails['skillID']);
			$html['spellShortDescription'] = isset($_POST['spellShortDescription']) ? htmlentities($_POST['spellShortDescription']) : htmlentities($savedSpellDetails['spellShortDescription']);
			$html['spellDescription'] = isset($_POST['spellDescription']) ? htmlentities($_POST['spellDescription']) : htmlentities($savedSpellDetails['spellDescription']);
			$html['spellCheatSheetNote'] = isset($_POST['spellCheatSheetNote']) ? htmlentities($_POST['spellCheatSheetNote']) : htmlentities($savedSpellDetails['spellCheatSheetNote']);
			
			// Set up array to pre-select characters
			$spell['characterID'] = array(); // Initialize to empty array
			$charResult = $spellObj->getSpellCharAccess($_GET['spellID']);
			while ($spellCharacters = $charResult->fetch_assoc()) {
				// Loop through retrieved characters and add to array
				$spell['characterID'][] = $spellCharacters['characterID'];
			}
			
			// Populate attribute costs
			$attrCosts = $spellObj->getAttributeCostsBySpell($_GET['spellID']);
			
			$attrCostResult1 = $attrCosts->fetch_assoc();
			
			$html['attributeCost1'] = isset($_POST['attributeCost1']) ? htmlentities($_POST['attributeCost1']) : htmlentities($attrCostResult1['attributeCost']);
			$html['attribute1'] = isset($_POST['attribute1']) ? htmlentities($_POST['attribute1']) : htmlentities($attrCostResult1['attributeNum']);
			
			$attrCosts->data_seek(1); // Advance to next row
			$attrCostResult2 = $attrCosts->fetch_assoc();
			
			$html['attributeCost2'] = isset($_POST['attributeCost2']) ? htmlentities($_POST['attributeCost2']) : htmlentities($attrCostResult2['attributeCost']);
			$html['attribute2'] = isset($_POST['attribute2']) ? htmlentities($_POST['attribute2']) : htmlentities($attrCostResult2['attributeNum']);
			
			$attrCosts->data_seek(2); // Advance to next row
			$attrCostResult3 = $attrCosts->fetch_assoc();
			
			$html['attributeCost3'] = isset($_POST['attributeCost3']) ? htmlentities($_POST['attributeCost3']) : htmlentities($attrCostResult3['attributeCost']);
			$html['attribute3'] = isset($_POST['attribute3']) ? htmlentities($_POST['attribute3']) : htmlentities($attrCostResult3['attributeNum']);
			
		} // end spellDetails loop
		
		$title = 'Update Spell | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update Spell';
		$btnLabel = 'Update';

	}

	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['spellAdminSubmitted']) && $_POST['spellAdminSubmitted'] == 1) {
			
		// Characters who have access
		$spell['characterID'] = array(); // Initialize to empty array
		if (isset($_POST['characterID'])) {
			$spell['characterID'] = $_POST['characterID'];
		}
		
		if ($action == 'create') {
			if ($spellObj->addSpell($_POST)) {
				session_write_close();
				header('Location: spells.php');
			}
		} else {
			if ($spellObj->updateSpell($_POST, $_GET['spellID'])) {
				session_write_close();
				header('Location: spells.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');

?>

<body id="spellAdminPage">
    
    <?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content">
      
	  <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="../images/helpArrowAdmin.png" alt="" style="display:none" />

		<?php cg_createHelp('spellNameHelp',
				'<p>Enter the spell\'s name as listed in the game rulebook.</p>'); ?>

		<?php cg_createHelp('spellCostHelp',
				'<p>Enter the spell\'s cost in CP.</p>'); ?>
        
        <?php cg_createHelp('accessHelp',
				'<p>A spell can have one of three different types of access:</p>
                <ul>
                    <li><strong>Anyone:</strong> All PCs and NPCs can purchase this spell. This is the default.</li>
                    <li><strong>Selected characters only:</strong> Use this option for hidden spells that only certain characters can purchase. You will be able to choose which PCs have access to the spells. NPCs automatically have access to all hidden spells. </li>
                    <li><strong>NPCs only:</strong> Only NPCs created by staff members will be able to view and purchase this spell.</li>
                </ul>'); ?>
        
        <?php cg_createHelp('skillIDHelp',
				'<p>Select the skill with which the spell is associated. The player will need to purchase this skill in order to buy the spell. </p>
                <p>In some games, this is known as a "sphere." The Generator does not have separate spheres.</p>'); ?>
     
        <?php cg_createHelp('spellShortDescriptionHelp',
				'<p>This is the brief blurb that appears under a spell during character creation.</p>
				<p>You may wish to include the attribute cost and/or damage call in the description: e.g. "Does 2 damage by fire. Attribute cost: 1 fire."</p>'); ?>
      
        <?php cg_createHelp('spellDescriptionHelp',
				'<p>This is a longer, more detailed description of the spell (usually the complete description found in the game rulebook).</p>
				<p>Players will not see this description by default, but may choose to show it if they want more details on the spell.</p>'); ?>
        
		<?php cg_createHelp('spellCheatSheetNoteHelp',
				'<p>This line appears on the character\'s cheat sheet next to the spell name. A player can print the cheat sheet and bring it to an event to help them remember their skill and attribute usage. </p>
				<p>The field is generally used to show the attribute cost to use the spell (e.g. "2 fire"), but you can enter any additional information that should appear on the cheat sheet.</p>'); ?>
		
        <?php cg_createHelp('attributeHelp',
				'<p>Enter up to three attribute cost(s) to use this spell. These entries are used to fuel the attribute usage meters in the character wizard. </p>
				<p>If this spell does not require attributes, leave these fields blank. If you do not enter attribute costs, this spell will not appear to have an attribute cost in the wizard.</p>
                <p>You can add rows for additional attributes by clicking the "+" button. You can remove all but the first row by clicking the "-" button.</p>'); ?>
        	  
	  </div><!--#help-->
	  
	  <!--no sidebar-->
	  
      <div id="main">
        <span class="reqFldIndicator">* Required Field</span>
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<h2><?php echo $pageHeader; ?></h2>
		
		<form name="spellAdmin" id="spellAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        
			<?php cg_createRow('spellName'); ?>
				<div class="cell">
					<label for="spellName"><span class="required">*</span>Spell name</label>
					<input type="text" name="spellName" id="spellName" class="xl" value="<?php echo $html['spellName']; ?>" />
					<?php cg_showError('spellName'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('spellCost'); ?>
				<div class="cell">
					<label for="spellCost"><span class="required">*</span>Spell cost</label>
					<input type="text" name="spellCost" id="spellCost" class="s" maxlength="3" value="<?php echo $html['spellCost']; ?>" />
					<p class="unit">CP</p>
					<?php cg_showError('spellCost'); ?>
					<br class="clear" />
				</div>
			</div>

			<?php cg_createRow('skillID'); ?>
				<div class="cell">
					<label for="skillID"><span class="required">*</span>Skill</label>
					<select name="skillID" id="skillID" data-placeholder="Select parent skill">
						<option value=""></option>
						<?php
							// Open skills loop
							while ($skillRow = $skills->fetch_assoc()) {
						?>
							<option value="<?php echo $skillRow['skillID']; ?>" <?php if ($html['skillID'] == $skillRow['skillID']) echo 'selected="selected"'; ?>><?php echo $skillRow['skillName'] . ' (' . $skillRow['headerName'] . ' header)'; ?></option>
						<?php 
							} // end of skills loop
						?>
						</select>
						<?php cg_showError('skillID'); ?>
					<br class="clear" />
				</div>
			</div>
            
            <?php cg_createRow('spellAccess'); ?>
                <div class="cell">
                    <label for="spellAccess"><span class="required">*</span>Who can buy?</label>
                    <select name="spellAccess" id="spellAccess" class="accessDropdown">
                        <option value="Public" <?php if ($html['spellAccess'] == 'Public') echo 'selected="selected"'; ?>>Anyone</option>
                        <option value="Hidden" <?php if ($html['spellAccess'] == 'Hidden') echo 'selected="selected"'; ?>>Selected characters only</option>
                        <option value="NPC" <?php if ($html['spellAccess'] == 'NPC') echo 'selected="selected"'; ?>>NPCs only</option>
                    </select>
                    <?php cg_showError('spellAccess'); ?>
                    <br class="clear" />
                </div>
            </div>
            
            <?php 
			if ($html['spellAccess'] != 'Hidden') {
				cg_createRow('PCAccess', 'display: none'); // If spell is public or NPC-only (or new), hide the access list
			} else {
				cg_createRow('PCAccess'); // If spell is hidden, show the access list
			}
		?>
            <div class="cell">
                <label for="PCAccess">Characters who can buy this spell<br /><span class="optional">(optional)</span></label>
                
				<div id="charSelectionBox">
					<div class="toolbar">
						<a href="#" id="checkAllAccess">check all</a>
						<a href="#" id="uncheckAllAccess">uncheck all</a>
						<br class="clear" />
					</div>
					
					<div class="container">
					
					<?php

						// Open characters loop
						while ($charRow = $characters->fetch_assoc()) {

					?>
					<div class="chkRow">
						<input type="checkbox" name="characterID[]" id="<?php echo 'characterID_' . $charRow['characterID']; ?>" value="<?php echo $charRow['characterID']; ?>" <?php if (isset($spell['characterID']) && in_array($charRow['characterID'], $spell['characterID'])) echo 'checked="checked"'; ?> />
						<label for="<?php echo 'characterID_' . $charRow['characterID']; ?>">
							<?php echo $charRow['charName'] . ' (' . $charRow['firstName'] . ' ' . $charRow['lastName'] . ')'; ?>
						</label>
						<br class="clear" />
					</div>
					
					<?php
						} // end characters loop
					?>
					
					</div><!--end of container-->
				
				</div>
				
				<?php cg_showError('PCAccess'); ?>
				<br class="clear" />
            </div>
        </div><!--end of spellAccess row-->
										
			<?php cg_createRow('spellShortDescription'); ?>
				<div class="cell">
					<label for="spellShortDescription">Short description<br /><span class="optional">(optional)</span></label>
					<textarea name="spellShortDescription" id="spellShortDescription" class="xl-textarea" rows="3"><?php echo $html['spellShortDescription']; ?></textarea>
					<?php cg_showError('spellShortDescription'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('spellDescription'); ?>
				<div class="cell">
					<label for="spellDescription">Long description<br /><span class="optional">(optional)</span></label>
					<textarea name="spellDescription" id="spellDescription" class="xl-textarea" rows="5"><?php echo $html['spellDescription']; ?></textarea>
					<?php cg_showError('spellDescription'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('spellCheatSheetNote'); ?>
				<div class="cell">
					<label for="spellCheatSheetNote">Cheat sheet note<br /><span class="optional">(optional)</span></label>
					<input type="text" maxlength="255" id="spellCheatSheetNote" name="spellCheatSheetNote" class="xl" value="<?php echo $html['spellCheatSheetNote']; ?>" />
					<?php cg_showError('spellCheatSheetNote'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('attributeCost1'); ?>
				<div class="cell">
					<label for="attributeCost1">Attribute Cost(s)<br /><span class="optional">(optional)</span></label>
					<input type="text" id="attributeCost1" name="attributeCost1" class="attributeCost s2" maxlength="3" value="<?php echo $html['attributeCost1']; ?>" />
					<select id="attribute1" name="attribute1" data-placeholder="Choose attribute">
						<option value="0"></option>
						<option value="1" <?php if ($html['attribute1'] == 1) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute1Label']; ?></option>
						<option value="2" <?php if ($html['attribute1'] == 2) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute2Label']; ?></option>
						<option value="3" <?php if ($html['attribute1'] == 3) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute3Label']; ?></option>
						<option value="4" <?php if ($html['attribute1'] == 4) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute4Label']; ?></option>
						<option value="5" <?php if ($html['attribute1'] == 5) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute5Label']; ?></option>
					</select>
					<a href="#" id="attribute1PlusLink" onClick="doOnClickAddAttrRow(1); return false"><img src="../images/adminPlus.png" class="plusMinus" alt="Add an attribute" /></a>
					<?php cg_showError('attributeCost1'); ?>
					<span class="hint">e.g. "2 fire"</span>
				</div>
			</div>
			
			<?php
				if ($html['attributeCost2'] != '') {
					cg_createRow('attributeCost2');
				} else {
					cg_createRow('attributeCost2', 'display: none');
				}
			?>
				<div class="cell">
					<input type="text" id="attributeCost2" name="attributeCost2" class="attributeCost s2" maxlength="3" value="<?php echo $html['attributeCost2']; ?>" />
					<select id="attribute2" name="attribute2" data-placeholder="Choose attribute">
						<option value="0"></option>
						<option value="1" <?php if ($html['attribute2'] == 1) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute1Label']; ?></option>
						<option value="2" <?php if ($html['attribute2'] == 2) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute2Label']; ?></option>
						<option value="3" <?php if ($html['attribute2'] == 3) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute3Label']; ?></option>
						<option value="4" <?php if ($html['attribute2'] == 4) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute4Label']; ?></option>
						<option value="5" <?php if ($html['attribute2'] == 5) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute5Label']; ?></option>
					</select>
					<a href="#" id="attribute2PlusLink" onClick="doOnClickAddAttrRow(2); return false"><img src="../images/adminPlus.png" class="plusMinus" alt="Add an attribute" /></a>
					<a href="#" onClick="doOnClickRemoveAttrRow(2); return false"><img src="../images/adminMinus.png" class="plusMinus" alt="Remove this attribute" /></a>
					<?php cg_showError('attributeCost2'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php
				if ($html['attributeCost3'] != '') {
					cg_createRow('attributeCost3');
				} else {
					cg_createRow('attributeCost3', 'display: none');
				}
			?>
				<div class="cell">
					<input type="text" id="attributeCost3" name="attributeCost3" class="attributeCost s2" maxlength="3" value="<?php echo $html['attributeCost3']; ?>" />
					<select id="attribute3" name="attribute3" data-placeholder="Choose attribute">
						<option value="0"></option>
						<option value="1" <?php if ($html['attribute3'] == 1) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute1Label']; ?></option>
						<option value="2" <?php if ($html['attribute3'] == 2) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute2Label']; ?></option>
						<option value="3" <?php if ($html['attribute3'] == 3) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute3Label']; ?></option>
						<option value="4" <?php if ($html['attribute3'] == 4) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute4Label']; ?></option>
						<option value="5" <?php if ($html['attribute3'] == 5) echo 'selected="selected"'; ?>><?php echo $_SESSION['attribute5Label']; ?></option>
					</select>
					<a href="#" onClick="doOnClickRemoveAttrRow(3); return false"><img src="../images/adminMinus.png" class="plusMinus" alt="Remove this attribute" /></a>
					<?php cg_showError('attributeCost3'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<div class="btnArea">
				<input type="hidden" id="spellAdminSubmitted" name="spellAdminSubmitted" value="1">
				<input type="submit" id="spellAdminSave" name="spellAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
				<input type="button" id="spellAdminCancel" name="spellAdminSave" value="Cancel" class="btn-secondary" onClick="window.location.href='spells.php'" />
				<br class="clear" />
			</div>
		
		</form>
        
      </div><!--/main-->
      <br class="clear" />
    </div><!--/content-->
    
<?php include('../includes/footer.php'); ?>
