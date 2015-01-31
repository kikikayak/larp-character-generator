<?php

$pageAccessLevel = 'User';

// Load required libraries
require_once('../includes/config.php');
require_once(LOCATION . 'includes/library.php');
require(LOCATION . 'class/classloader.php');
require_once(LOCATION . 'includes/authenticate.php');

// Make sure the user's browser doesn't cache the result
header('Expires: Wed, 23 Dec 1980 00:30:00 GMT'); // time in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// read the action parameter
$ajaxAction = $_GET['ajaxAction'];
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
} else {
	$mode = 'wizard';	
}

$imgPath = '../theme/' . THEME . '/images/';

/***********************************************************
GET FEATS
************************************************************/

if ($ajaxAction == 'getFeats') {
	
	// echo 'mode: ' . $mode . '<br />';

	/* DEBUG 
	if (isset($_POST['selectedFeats'])) { 
		foreach ($_POST['selectedFeats'] as $featID) {
			echo 'Selected feat: ' . $featID . '<br />';
		}
	}*/
	
	$wizard = new Wizard(); // Create new instance of the Wizard class
?>

	<p class="intro">All players may purchase ONE of the below feats.</p>

	<?php
	    if ($mode != 'adminWizard' && $_SESSION['pageAction'] == 'update' && isset($_SESSION['savedCharacter']['charFeats']) && count($_SESSION['savedCharacter']['charFeats']) > 0) {
	    	// Don't display "No feat" row if we're in update mode and user already has a feat
	    } else {
	?>
	<div id="feat0Row" class="row feat">
      <div class="cell1">
      		<input
                  type="radio" 
                  class="featFld" 
                  name="featID[]" 
                  id="featID_0" 
                  value=""
                  checked="checked"  
                  onclick="selectFeat(this, 0);"
          	/>

          	<input 	
                  type="hidden" 
                  name="featID_0_cost" 
                  id="featID_0_cost" 
                  value="0" 
          	/>
      </div><!--.cell1-->
      <div class="cell2">
          <label>No feat</label>
      </div>
      <div class="cpCost"></div>
      <div class="description"></div>
	</div><!--.row-->
	
	<?php
	} // end of "no feat" row condition

    $feats = $wizard->getFeats();
    if ($feats->num_rows > 0) {
        // Open feats loop
        while ($featRow = $feats->fetch_assoc()) {
?>
                
  <div id="<?php echo 'feat' . $featRow['featID'] . 'Row'; ?>" class="row feat">
      <div class="cell1">
          <?php
            if ($mode != 'adminWizard' && $_SESSION['pageAction'] == 'update' && isset($_SESSION['savedCharacter']['charFeats']) && in_array($featRow['featID'], $_SESSION['savedCharacter']['charFeats'])) {
          ?>
              <img src="<?php echo $imgPath; ?>checked_circle.png" height="20" width="20" alt="checked box" class="updateCheckedBox" title="Already purchased" />
              <input
                  type="radio" 
                  class="featFld" 
                  name="featID[]" 
                  id="<?php echo 'featID_' . $featRow['featID']; ?>" 
                  value="<?php echo $featRow['featID']; ?>" 
                  checked="checked"
                  style="display:none"  
              />
          <?php
              } else if ($mode != 'adminWizard' && $_SESSION['pageAction'] == 'update' && isset($_SESSION['savedCharacter']['charFeats']) && count($_SESSION['savedCharacter']['charFeats']) > 0) {
              	// Do not display radio button if we're in update mode and user already has a feat selected
              	echo '&nbsp';
              } else { 
              	// User is adding a new character or does not already have a feat
          ?>
          
          <input
                  type="radio" 
                  class="featFld" 
                  name="featID[]" 
                  id="<?php echo 'featID_' . $featRow['featID']; ?>" 
                  value="<?php echo $featRow['featID']; ?>" 
                  onclick="selectFeat(this, <?php echo $featRow['featCost']; ?>);" 
                  <?php if (isset($_POST['selectedFeats']) && in_array($featRow['featID'], $_POST['selectedFeats']) || isset($_SESSION['character']['charFeats']) && in_array($featRow['featID'], $_SESSION['character']['charFeats'])) echo 'checked="checked"'; ?>  
          />
          <?php
              } // end update condition
          ?>
          <input 	
                  type="hidden" 
                  name="<?php echo 'featID_' . $featRow['featID'] . '_cost'; ?>" 
                  id="<?php echo 'featID_' . $featRow['featID'] . '_cost'; ?>" 
                  value="<?php echo $featRow['featCost']; ?>" 
          />
      </div>
      <div class="cell2">
          <label><?php echo $featRow['featName']; ?></label>
      </div>
      <div class="cpCost">
          <span class="costHdr">Cost:</span> <?php echo $featRow['featCost']; ?> CP
      </div>
      <div class="description">
          <p><?php echo $featRow['featShortDescription']; ?></p>
      </div>
  </div><!--.row-->

<?php
        } // end of feats loop
    } else {
        echo '<p class="noData">There are currently no feats available for purchase.</p>';
    } // end of results if condition
			
} // end of getFeats


/***********************************************************
Get spheres and associated spells
************************************************************/

if ($ajaxAction == 'getSpheres' && isset($_POST['selectedSkills'])) {
	$skillIDList = implode(', ', $_POST['selectedSkills']);
	/* DEBUG
	if (isset($_POST['selectedSpells'])) { 
		foreach ($_POST['selectedSpells'] as $spellID) {
			echo 'Selected spell: ' . $spellID . '<br />';
		}
	}
	*/
	
	$wizard = new Wizard(); // Create new instance of the Wizard class
	
	$spheres = $wizard->getSpheres($skillIDList);
		if ($spheres->num_rows > 0) {
			// Open spheres loop
			while ($sphereRow = $spheres->fetch_assoc()) {
	?>
	
	<div id="<?php echo 'skill' . $sphereRow['skillID'] . 'Sphere'; ?>" class="sphere">
		<div class="row">
			<div class="cell0">
				<img src="<?php echo $imgPath; ?>arrowDown.png" id="<?php echo 'sphereID_' . $sphereRow['skillID'] . '_arrow'; ?>" alt="Click to expand or contract header contents" title="Click to expand or contract header contents" onclick="doOnclickHeaderArrow(this, <?php echo '\'skill' . $sphereRow['skillID'] . 'Spells\''; ?>, 'admin');" />
			</div>
			<div class="cell1">
			</div>
			<div class="cell2">
				<label><?php echo $sphereRow['skillName']; ?></label> 
			</div>
		</div>
	</div> <!--end of sphere div-->
	
	<div id="<?php echo 'skill' . $sphereRow['skillID'] . 'Spells'; ?>" class="spellGrp">
		<?php 
			// Get spells for this sphere
			$sphereSpells = $wizard->getSpellsForSphere($sphereRow['skillID']);
			
			// Loop through skills, one row per skill
			while ($spellRow = $sphereSpells->fetch_assoc()) {
		?>
		
		<div class="row">
			<div class="cell1">
				<?php
				  if ($_SESSION['pageAction'] == 'update' && isset($_SESSION['savedCharacter']['charSpells']) && in_array($spellRow['spellID'], $_SESSION['savedCharacter']['charSpells']) && $mode == 'wizard') {
				?>
					<img src="<?php echo $imgPath; ?>checked_circle.png" height="20" width="20" alt="checked box" class="updateCheckedBox" title="Already purchased" />
					<input
                 		type="checkbox" 
						class="spellFld" 
						name="spellID[]" 
						id="<?php echo 'spellID_' . $spellRow['spellID']; ?>" 
						value="<?php echo $spellRow['spellID']; ?>" 
						checked="checked"
                        style="display:none"  
                    />
				<?php
					} else { // Non-update state
				?>
                
                <input
                 		type="checkbox" 
						class="spellFld" 
						name="spellID[]" 
						id="<?php echo 'spellID_' . $spellRow['spellID']; ?>" 
						value="<?php echo $spellRow['spellID']; ?>" 
						onclick="selectSpell(this, <?php echo $spellRow['spellCost']; ?>);" 
						<?php if (isset($_POST['selectedSpells']) && in_array($spellRow['spellID'], $_POST['selectedSpells']) || isset($_SESSION['character']['charSpells']) && in_array($spellRow['spellID'], $_SESSION['character']['charSpells'])) echo 'checked="checked"'; ?>  
				/>
                <?php
					} // end update condition
				?>
				<input 	
						type="hidden" 
						name="<?php echo 'spellID_' . $spellRow['spellID'] . '_cost'; ?>" 
						id="<?php echo 'spellID_' . $spellRow['spellID'] . '_cost'; ?>" 
						value="<?php echo $spellRow['spellCost']; ?>" 
				/>
			</div>
			<div class="cell2">
				<label><?php echo $spellRow['spellName']; ?></label>
			</div>
			<div class="cpCost">
				<span class="costHdr">Cost:</span> <?php echo $spellRow['spellCost']; ?> CP
			</div>
			<div class="description">
				<p><?php echo $spellRow['spellShortDescription']; ?></p>
			</div>
		</div>
		
		<?php
			} // end of sphereSpells loop
		?>
	
	</div><!--end of spellGrp-->
	
	<?php
			} // end of spheres loop
		} else {
			echo '<p class="noData">Please select at least one header and/or skill that enables you to purchase spells.</p>';
		} // end of results if condition
} else if ($ajaxAction == 'getSpheres') {
	echo '<p class="noData">Please select at least one header and/or skill that enables you to purchase spells.</p>';
} // end of "get spheres" ajaxAction condition
	
/***********************************************************
GET ATTRIBUTE USAGE
************************************************************/

else if ($ajaxAction == 'getAttributeUsage' && isset($_POST['attributeName'])) {
	switch ($_POST['attributeName']) {
		case 'attribute1':
			$attribute = $_SESSION['attribute1Label'];
			break;
		case 'attribute2':
			$attribute = $_SESSION['attribute2Label'];
			break;
		case 'attribute3':
			$attribute = $_SESSION['attribute3Label'];
			break;
		case 'attribute4':
			$attribute = $_SESSION['attribute4Label'];
			break;
		case 'attribute5':
			$attribute = $_SESSION['attribute5Label'];
			break;
		default: 
			$attribute = 'Attribute';
	}
	
	$attributeNumArr = explode('attribute', $_POST['attributeName']);
	$attributeNum = $attributeNumArr[1];
	
	if (isset($_POST['selectedSkills'])) {
		$skillIDList = implode(', ', $_POST['selectedSkills']);
	} else {
		$skillIDList = '';
	}
	if (isset($_POST['selectedSpells'])) {
		$spellIDList = implode(', ', $_POST['selectedSpells']);
	} else {
		$spellIDList = '';
	}
	
	$skill = new Skill();
	$skills = $skill->getSkillAttributeCosts($attributeNum, $skillIDList);
	
	$spell = new Spell();
	$spells = $spell->getSpellAttributeCosts($attributeNum, $spellIDList);
	
	echo '<h2>' . $attribute . ' Usage Info</h2>';
	
	if ((!isset($skills) && !isset($spells)) || ((isset($skills) && $skills->num_rows == 0) && (isset($spells) && $spells->num_rows == 0))) {
		echo '<p class="noResults">No skills or spells that use this attribute are selected.</p>';
	} else {
	
	?>
	
	<p class="availablePts"><?php echo $attribute; ?> Points</p>
	<div class="attributePts">
		<?php
			// Display proper number of attribute ticks
			for ($i = 0; $i < $_POST['attributeValue']; $i++) {
				echo '<span class="attributePt"></span>';
			}
		?>
	</div>
	<br class="clear" />
	
	<h3>Skills</h3>
	
	<?php
	
	if (isset($skills) && $skills->num_rows > 0) {
	
?>

	<!--<p>The following skills and spells use this attribute:</p>-->
	
	<?php
		while ($skillRow = $skills->fetch_assoc()) { // Loop through retrieved skills
	?>
	<p class="skillName"><?php echo $skillRow['skillName']; ?><br />
	<span class="numPts"><?php echo '(' . $skillRow['attributeCost'] . ')'; ?></span></p>
	<div class="skillAttributeUsage">
		<?php 
			$skillUses = $_POST['attributeValue'] / $skillRow['attributeCost']; 
			$skillUsesMod = $_POST['attributeValue'] % $skillRow['attributeCost'];
			for ($j = 0; $j < $skillUses; $j++) {
		?>
		<div class="skillUse <?php if (($skillUsesMod != 0) && ($j >= $skillUses - 1)) echo 'unavailable'; ?>">
			<?php
				for ($i = 0; $i < $skillRow['attributeCost']; $i++) {
					echo '<span class="skillTick"></span>';
				}
			?>
		</div>
		<?php
			} // End of loop through skill uses
		
		?>
	</div>
	<br class="clear" />
	
	<?php
		} // end skills loop
	} else { // end of numRows condition
		echo '<p class="noResults">No skills that use this attribute are selected.</p>';
	} 
	?>
	
	<h3>Spells</h3>
	
	<?php
		
		if (isset($spells) && $spells->num_rows > 0) {
	
	?>
	
	<!--<p>The following spells use this attribute:</p>-->
	<?php
		while ($spellRow = $spells->fetch_assoc()) { // Loop through retrieved skills
	?>
	<p class="skillName"><?php echo $spellRow['spellName']; ?><br />
	<span class="numPts"><?php echo '(' . $spellRow['attributeCost'] . ')'; ?></span></p>
	<div class="skillAttributeUsage">
		<?php 
			$spellUses = $_POST['attributeValue'] / $spellRow['attributeCost']; 
			$spellUsesMod = $_POST['attributeValue'] % $spellRow['attributeCost'];
			for ($k = 0; $k < $spellUses; $k++) {
		?>
		<div class="skillUse <?php if (($spellUsesMod != 0) && ($j >= $spellUses - 1)) echo 'unavailable'; ?>">
			<?php
				for ($l = 0; $l < $spellRow['attributeCost']; $l++) {
					echo '<span class="skillTick"></span>';
				}
			?>
		</div>
		<?php
			} // End of loop through spell uses
		
		?>
	</div>
	<br class="clear" />
	
	<?php
		} // end spells loop
	} else { // end of numRows condition
		echo '<p class="noResults">No spells that use this attribute are selected.</p>';
	}
	
	} // end overall condition for number of results 
	?>

	<br class="clear" />


<?php
	} // end of getAttributeUsage condition

	/***********************************************************
	GET FREE CP
	************************************************************/

	else if ($ajaxAction == 'getFreeCP' && isset($_POST['playerID'])) {
		$char = new Character();
		$freeCP = $char->getWizardFreeCP($_POST['playerID']);
		echo $freeCP;
	}

?>
