<?php

	/**************************************************************
	NAME: 	skillAdmin.php
	NOTES: 	This page allows staff members to add or edit skills. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'skills';
	$scriptLink = 'skills.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Determine page action ("add" or "update")
	if (isset($_GET['skillID']) && ctype_digit($_GET['skillID'])) {
		$action = 'update';
	} else {
		$action = 'create';
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	INITIALIZE VALUES FOR POPULATING UI
	************************************************************************************/ 
	
	$html = array(); // Initialize array to hold data for display
	
	$skillObj = new Skill(); // Instantiate skill object
	
	// Get data to populate dropdown lists
	$character = new Character();
	$characters = $character->getAllPCs();
	$headerObj = new Header();
	$headers = $headerObj->getAllHeaders();
	
	if ($action == 'create') { 
		$html['skillName'] = isset($_POST['skillName']) ? htmlentities($_POST['skillName']) : '';
		$html['skillCost'] = isset($_POST['skillCost']) ? htmlentities($_POST['skillCost']) : '';
		$html['skillAccess'] = isset($_POST['skillAccess']) ? htmlentities($_POST['skillAccess']) : '';
		$html['stackable'] = isset($_POST['stackable']) ? htmlentities($_POST['stackable']) : 'no';
		$html['headerID'] = isset($_POST['headerID']) ? htmlentities($_POST['headerID']) : '';
		$html['skillType'] = isset($_POST['skillType']) ? htmlentities($_POST['skillType']) : '';
		$html['maxQuantity'] = isset($_POST['maxQuantity']) ? htmlentities($_POST['maxQuantity']) : '1';
		$html['costIncrement'] = isset($_POST['costIncrement']) ? htmlentities($_POST['costIncrement']) : '0';
		$html['shortDescription'] = isset($_POST['shortDescription']) ? htmlentities($_POST['shortDescription']) : '';
		$html['skillDescription'] = isset($_POST['skillDescription']) ? htmlentities($_POST['skillDescription']) : '';
		$html['cheatSheetNote'] = isset($_POST['cheatSheetNote']) ? htmlentities($_POST['cheatSheetNote']) : '';
		$html['attributeCost1'] = isset($_POST['attributeCost1']) ? htmlentities($_POST['attributeCost1']) : '';
		$html['attribute1'] = isset($_POST['attribute1']) ? htmlentities($_POST['attribute1']) : '';
		$html['attributeCost2'] = isset($_POST['attributeCost2']) ? htmlentities($_POST['attributeCost2']) : '';
		$html['attribute2'] = isset($_POST['attribute2']) ? htmlentities($_POST['attribute2']) : '';
		$html['attributeCost3'] = isset($_POST['attributeCost3']) ? htmlentities($_POST['attributeCost3']) : '';
		$html['attribute3'] = isset($_POST['attribute3']) ? htmlentities($_POST['attribute3']) : '';
		
		$title = 'Add a Skill | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Add a Skill';
		$btnLabel = 'Add';
		
	} else if ($action == 'update' && isset($_GET['skillID'])) {
		$skillDetails = $skillObj->getSkill($_GET['skillID']);
		while ($savedSkillDetails = $skillDetails->fetch_assoc()) {
			$html['skillName'] = isset($_POST['skillName']) ? htmlentities($_POST['skillName']) : htmlentities($savedSkillDetails['skillName']);
			$html['skillCost'] = isset($_POST['skillCost']) ? htmlentities($_POST['skillCost']) : htmlentities($savedSkillDetails['skillCost']);
			$html['stackable'] = isset($_POST['stackable']) ? htmlentities($_POST['stackable']) : $savedSkillDetails['maxQuantity'] > 1 ? 'yes' : 'no';
			$html['headerID'] = isset($_POST['headerID']) ? htmlentities($_POST['headerID']) :  htmlentities($savedSkillDetails['headerID']);
			$html['skillType'] = isset($_POST['skillType']) ? htmlentities($_POST['skillType']) :  htmlentities($savedSkillDetails['skillType']);
			$html['skillAccess'] = isset($_POST['skillAccess']) ? htmlentities($_POST['skillAccess']) : htmlentities($savedSkillDetails['skillAccess']);
			$html['maxQuantity'] = isset($_POST['maxQuantity']) ? htmlentities($_POST['maxQuantity']) : htmlentities($savedSkillDetails['maxQuantity']);
			$html['costIncrement'] = isset($_POST['costIncrement']) ? htmlentities($_POST['costIncrement']) : htmlentities($savedSkillDetails['costIncrement']);
			$html['shortDescription'] = isset($_POST['shortDescription']) ? htmlentities($_POST['shortDescription']) : htmlentities($savedSkillDetails['shortDescription']);
			$html['skillDescription'] = isset($_POST['skillDescription']) ? htmlentities($_POST['skillDescription']) : htmlentities($savedSkillDetails['skillDescription']);
			$html['cheatSheetNote'] = isset($_POST['cheatSheetNote']) ? htmlentities($_POST['cheatSheetNote']) : htmlentities($savedSkillDetails['cheatSheetNote']);
			
			$attrCosts = $skillObj->getAttributeCostsBySkill($_GET['skillID']);
			/* $costCount = 1;
			while ($attrCostRow = $attrCosts->fetch_assoc()) {
			  // TODO: Get saved attribute values and use to populate fields 
			  $curCostFld = 'attributeCost' . $costCount;
			  $curAttrNum = 'attribute' . $costCount;
			  $html[$curCostFld] = isset($_POST[$curCostFld]) ? htmlentities($_POST[$curCostFld]) : '';
			  $html[$curAttrNum] = isset($_POST[$curAttrNum]) ? htmlentities($_POST[$curAttrNum]) : $attrCostRow['attributeNum'];
			  echo 'Cost: ' . $html[$curCostFld] . '<br />';
			  echo 'Attribute Number: ' . $html[$curAttrNum] . '<br />';
			  $costCount++;
			} */
			
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
			
			// Set up array to pre-select characters
			$skill['characterID'] = array(); // Initialize to empty array
			$charResult = $skillObj->getSkillCharacters($_GET['skillID']);
			while ($skillCharacters = $charResult->fetch_assoc()) {
				// Loop through retrieved characters and add to array
				$skill['characterID'][] = $skillCharacters['characterID'];
			}
		}
		
		$title = 'Update Skill | ' . $_SESSION['campaignName'] . ' Character Generator';
		$pageHeader = 'Update Skill';
		$btnLabel = 'Update';

	}
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['skillAdminSubmitted']) && $_POST['skillAdminSubmitted'] == 1) {
		echo 'skillType: ' . $_POST['skillType'] . '<br />';

		$skill = array(); // Initialize as blank array
		$skill['skillName'] = $_POST['skillName'];
		$skill['skillCost'] = $_POST['skillCost'];
		$skill['headerID'] = $_POST['headerID'];
		$skill['skillType'] = $_POST['skillType'];
		$skill['skillAccess'] = $_POST['skillAccess'];
		$skill['maxQuantity'] = $_POST['maxQuantity'];
		$skill['costIncrement'] = $_POST['costIncrement'];
		$skill['shortDescription'] = $_POST['shortDescription'];
		$skill['skillDescription'] = $_POST['skillDescription'];
		$skill['cheatSheetNote'] = $_POST['cheatSheetNote'];
		
		// Characters who have access
		$skill['characterID'] = array(); // Initialize to empty array
		if (isset($_POST['characterID'])) {
			$skill['characterID'] = $_POST['characterID'];
		}
	
		if ($action == 'create') {
			if ($skillObj->addSkill($skill)) {
				session_write_close();
				header('Location: skills.php');
			}
		} else {
			if ($skillObj->updateSkill($skill, $_GET['skillID'])) {
				session_write_close();
				header('Location: skills.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');

?>

<body id="skillAdminPage">
    
    <?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content">
      
	  <!-- This div holds all the help text, which is hidden by default. -->
	  <div id="help">
	  	
		<img id="helpArrow" src="styles/images/helpArrowAdmin.png" alt="" style="display:none" />
		
		<?php cg_createHelp('nameHelp','<p>Enter the skill\'s name as listed in the game rulebook.</p>'); ?>

		<?php cg_createHelp('costHelp',
				'<p>Enter the skill\'s initial cost in CP.</p>
				<p>If this skill\'s cost will increase as the character purchases additional instances (e.g. a 2,4,6 cost pattern), enter the cost for purchasing the <em>first</em> instance of the skill. </p>'); ?>

		<?php cg_createHelp('stackableHelp','<p>Stackable skills can be bought multiple times. The player will be able to choose how many instances to buy during character creation. </p>'); ?>

		<?php cg_createHelp('maxQuantityHelp','<p>Use this field to limit the number of times a character can buy a stackable skill.</p>
				<p>If you do not want to limit the number of instances a character can buy, set the maxQuantity to a high value (e.g. 100).
			</p>'); ?>

		<?php cg_createHelp('costIncrementHelp','<p>Use this field to set up skills that increase in cost as a character buys additional instances, e.g. skills whose costs have a 1,2,3... or 2,4,6... pattern.</p>
				<p>Leave the increment set to the default of 0 if you want all instances of the skill to cost the same.</p>
				<p><strong>Example 1:</strong> A skill has a cost of 1 and an increment of 1. The first time a character buys the skill, it will cost 1 CP. The second time, it will cost 2 CP. The third time, it will cost 3 CP. And so on. </p>
				<p><strong>Example 2:</strong> A skill has a cost of 2 and an increment of 2. The first instance will cost 2, the second 4, the third 6, the fourth 8, etc.</p>
				<p><strong>NOTE:</strong> The Generator does not currently support skills that increase cost in varying increments (skills that double in cost each time, for instance). For these skills, you will need to add a separate skill for each "level."</p>'); ?>
		
		<?php cg_createHelp('headerHelp','<p>This is the header to which the skill belongs. The skill will appear under this header during character creation.</p>
				<p>If a skill appears under more than one header, add the skill multiple times, selecting a different header each time. This will also allow the skill to have different costs under different headers. </p>'); ?>

		<?php cg_createHelp('accessHelp','<p>A skill can have one of three different types of access:</p>
				<ul>
					<li><strong>Anyone:</strong> All PCs and NPCs can purchase this skill. Skills are available to anyone by default.</li>
					<li><strong>Selected characters only:</strong> Use this option for hidden skills that only certain characters can purchase. You will be able to choose which PCs have access to the skill. NPCs automatically have access to all hidden skills. </li>
					<li><strong>NPCs only:</strong> Only NPCs created by staff members will be able to view and purchase this skill.</li>
				</ul>
				<p>NOTE: If you choose "selected characters only" but do not select any characters, no PCs will be allowed to see or purchase this skill.</p>'); ?>

		<?php cg_createHelp('shortDescriptionHelp','<p>This is the brief blurb that appears under a skill during character creation.</p>
				<p>You may wish to include the attribute cost and/or damage call in the description: e.g. "Does 2 damage by fire. Attribute cost: 1 fire."</p>'); ?>

		<?php cg_createHelp('skillDescriptionHelp','<p>This is a longer, more detailed description of the skill (usually the complete description found in the game rulebook).</p>
				<p>Players will not see this description by default, but may choose to show it if they want more details on the skill.</p>'); ?>

		<?php cg_createHelp('cheatSheetNoteHelp','<p>This line appears on the character\'s cheat sheet next to the skill name. A player can print the cheat sheet and bring it to an event to help them remember their skill and attribute usage. </p>
				<p>The field is generally used to show the attribute cost to use the skill (e.g. "2 fire"), but you can enter any additional information that should appear on the cheat sheet.</p>'); ?>

		<?php cg_createHelp('attributeHelp','<p>Enter up to three attribute cost(s) to use this skill. These entries are used to fuel the attribute usage meters in the character wizard. </p>
				<p>If this skill does not require attributes, leave these fields blank. If you do not enter attribute costs, this skill will not appear to have an attribute cost in the wizard.</p>
                <p>You can add rows for additional attributes by clicking the "+" button. You can remove all but the first row by clicking the "-" button.</p>'); ?>
	  
	  </div><!--#help-->
	  
	  <!--no sidebar-->
	  
      <div id="main">
        <span class="reqFldIndicator">* Required Field</span>
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<h2><?php echo $pageHeader; ?></h2>
		
		<form name="skillAdmin" id="skillAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        
			<?php cg_createRow('skillName'); ?>
				<div class="cell">
					<label for="skillName"><span class="required">*</span>Skill name</label>
					<input type="text" name="skillName" id="skillName" class="xl" value="<?php echo $html['skillName']; ?>" />
					<?php cg_showError('skillName'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('skillCost'); ?>
				<div class="cell">
					<label for="skillCost"><span class="required">*</span>Skill cost</label>
					<input type="text" name="skillCost" id="skillCost" class="s2" maxlength="3" value="<?php echo $html['skillCost']; ?>" />
					<p class="unit">CP</p>
					<?php cg_showError('skillCost'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('stackable'); ?>
				<div class="cell">
					<label for="stackable"><span class="required">*</span>Stackable?</label>
					<select name="stackable" id="stackable">
						<option value="no" <?php if ($html['stackable'] == 'no') echo 'selected="selected"'; ?>>No</option>
						<option value="yes" <?php if ($html['stackable'] == 'yes') echo 'selected="selected"'; ?>>Yes</option>
					</select>
					<?php cg_showError('stackable'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<div id="stackableOptions" <?php if ($html['stackable'] == 'no') echo 'style="display:none"'; ?>>
				<?php cg_createRow('maxQuantity'); ?>
					<div class="cell">
						<label for="maxQuantity">Max purchases</label>
						<input type="text" name="maxQuantity" id="maxQuantity" class="s2" maxlength="3" value="<?php echo $html['maxQuantity']; ?>" />
						<?php cg_showError('maxQuantity'); ?>
						<br class="clear" />
					</div>
				</div>
				
				<?php cg_createRow('costIncrement'); ?>
					<div class="cell">
						<label for="costIncrement">* Increment by which cost increases</label>
						<input type="text" name="costIncrement" id="costIncrement" class="s" maxlength="3" value="<?php echo $html['costIncrement']; ?>" />
						<?php cg_showError('costIncrement'); ?>
						<br class="clear" />
					</div>
				</div>
			</div><!-- end stackableOptions -->
			
			<?php cg_createRow('headerID'); ?>
				<div class="cell">
					<label for="headerID"><span class="required">*</span>Header</label>
					<select name="headerID" id="headerID" data-placeholder="Select parent header">
						<option value=""></option>
						<?php
							// Open headers loop
							while ($headerRow = $headers->fetch_assoc()) {
						?>
							<option value="<?php echo $headerRow['headerID']; ?>" <?php if ($html['headerID'] == $headerRow['headerID']) echo 'selected="selected"'; ?>><?php echo $headerRow['headerName']; ?></option>
						<?php 
							} // end of headers loop
						?>
						</select>
						<?php cg_showError('headerID'); ?>
					<br class="clear" />
				</div>
			</div>			
							
			
			<?php cg_createRow('skillType', 'display: none'); ?>
				<div class="cell">
					<label for="skillType"><span class="required">*</span>Type</label>
					<select name="skillType" id="skillType">
						<option value="Standard" <?php if ($html['skillType'] == 'Standard') echo 'selected="selected"'; ?>>Standard</option>
					</select>
					<?php cg_showError('skillType'); ?>
					<br class="clear" />
				</div>
			</div>

			<?php cg_createRow('skillAccess'); ?>
				<div class="cell">
					<label for="skillAccess"><span class="required">*</span>Who can buy?</label>
					<select name="skillAccess" id="skillAccess" class="accessDropdown">
						<option value="Public" <?php if ($html['skillAccess'] == 'Public') echo 'selected="selected"'; ?>>Anyone</option>
						<option value="Hidden" <?php if ($html['skillAccess'] == 'Hidden') echo 'selected="selected"'; ?>>Selected characters only</option>
						<option value="NPC" <?php if ($html['skillAccess'] == 'NPC') echo 'selected="selected"'; ?>>NPCs only</option>
					</select>
					<?php cg_showError('skillAccess'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php 
				if ($html['skillAccess'] != 'Hidden') {
					cg_createRow('PCAccess', 'display: none'); // If header is public or NPC-only (or new), hide the access list
				} else {
					cg_createRow('PCAccess'); // If header is hidden, show the access list
				}
			?>
            <div class="cell">
                <label for="PCAccess">Characters who can buy this skill<br /><span class="optional">(optional)</span></label>
			
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
						<input type="checkbox" name="characterID[]" id="<?php echo 'characterID_' . $charRow['characterID']; ?>" value="<?php echo $charRow['characterID']; ?>" <?php if (isset($skill['characterID']) && in_array($charRow['characterID'], $skill['characterID'])) echo 'checked="checked"'; ?> />
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
			</div>
			
			<?php cg_createRow('shortDescription'); ?>
				<div class="cell">
					<label for="shortDescription">Short description<br /><span class="optional">(optional)</span></label>
					<textarea rows="3" class="xl-textarea" name="shortDescription" id="shortDescription"><?php echo $html['shortDescription']; ?></textarea>
					<span class="charCount"><span id="counter"></span> / 950 chars remaining</span>
					<?php cg_showError('shortDescription'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('skillDescription'); ?>
				<div class="cell">
					<label for="skillDescription">Long description<br /><span class="optional">(optional)</span></label>
					<textarea name="skillDescription" class="xl-textarea" rows="12" id="skillDescription"><?php echo $html['skillDescription']; ?></textarea>
					<?php cg_showError('skillDescription'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<?php cg_createRow('cheatSheetNote'); ?>
				<div class="cell">
					<label for="cheatSheetNote">Cheat sheet note<br /><span class="optional">(optional)</span></label>
					<input type="text" maxlength="255" id="cheatSheetNote" name="cheatSheetNote" class="xl" value="<?php echo $html['cheatSheetNote']; ?>" />
					<?php cg_showError('cheatSheetNote'); ?>
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
					<a href="#" id="attribute1PlusLink" onClick="doOnClickAddAttrRow(1); return false"><img src="styles/images/adminPlus.png" class="plusMinus" alt="Add an attribute" /></a>
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
					<a href="#" id="attribute2PlusLink" onClick="doOnClickAddAttrRow(2); return false"><img src="styles/images/adminPlus.png" class="plusMinus" alt="Add an attribute" /></a>
					<a href="#" onClick="doOnClickRemoveAttrRow(2); return false"><img src="styles/images/adminMinus.png" class="plusMinus" alt="Remove this attribute" /></a>
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
					<a href="#" onClick="doOnClickRemoveAttrRow(3); return false"><img src="styles/images/adminMinus.png" class="plusMinus" alt="Remove this attribute" /></a>
					<?php cg_showError('attributeCost3'); ?>
					<br class="clear" />
				</div>
			</div>
			
			<div class="btnArea">
				<input type="hidden" id="skillAdminSubmitted" name="skillAdminSubmitted" value="1" />
				<input type="submit" id="skillAdminSave" name="skillAdminSave" value="<?php echo $btnLabel; ?>" class="btn-primary" />
				<input type="button" id="skillAdminCancel" name="skillAdminSave" value="Cancel" class="btn-secondary" onClick="window.location.href='skills.php'" />
				<br class="clear" />
			</div>
		
		</form>
        
      </div><!--/main-->
      <br class="clear" />
    </div><!--/content-->
    
<?php include('../includes/footer.php'); ?>
