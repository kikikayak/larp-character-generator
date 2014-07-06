<?php

	/**************************************************************
	NAME: 	headerAdmin.php
	NOTES: 	This page allows staff members to add or edit headers. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'headers';
	$scriptLink = 'headers.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$headerObj = new Header(); // Instantiate header object
	
	// Get data to populate dropdown lists
	$character = new Character();
	$characters = $character->getAllCharacters();

	// Determine page action ("add" or "update")
	if (isset($_GET['headerID']) && ctype_digit($_GET['headerID'])) {
		$action = 'update';
		$html = $headerObj->initUpdateHeader($_POST, $_GET['headerID']);
	} else {
		$action = 'create';
		$html = $headerObj->initAddHeader($_POST);
	}
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	
	/************************************************************************************
	PROCESS SUBMISSION
	If user has submitted page, process data. 
	************************************************************************************/ 
	if (isset($_POST['headerAdminSubmitted']) && $_POST['headerAdminSubmitted'] == 1) {
	
		if ($action == 'create') {
			if ($headerObj->addHeader($_POST)) {
				session_write_close();
				header('Location: headers.php');
			}
		} else {
			if ($headerObj->updateHeader($_POST, $_GET['headerID'])) {
				session_write_close();
				header('Location: headers.php');	
			}
		}
	}
	
	include('../includes/header_admin.php');

?>

<body id="headerAdminPage">
    
    <?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content">
      <!--no sidebar -->
	  
		<div id="help">
	  	
			<img id="helpArrow" src="styles/images/helpArrowAdmin.png" alt="" style="display:none" />
			
			<?php cg_createHelp('nameHelp','<p>Enter the header\'s name as listed in the game rulebook.</p>'); ?>

			<?php cg_createHelp('costHelp','<p>Enter the header\'s cost in CP.</p>'); ?>
			
			<?php cg_createHelp('headerDescriptionHelp','<p>Enter the header description as it appears in the game rulebook (if desired). The player will be able to view this information during character creation. </p>'); ?>
					
		</div><!--end of help div-->

	  
      <div id="main">
		
		<span class="reqFldIndicator">* Required Field</span>
		
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
	  		
        <h2><?php echo $html['pageHeader']; ?></h2>
        
		<form name="headerAdmin" id="headerAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		        
        <?php cg_createRow('headerName'); ?>
            <div class="cell">
                <label for="headerName"><span class="required">*</span>Header Name</label>
                <input type="text" name="headerName" id="headerName" class="xl" value="<?php echo $html['headerName']; ?>" />
				<?php cg_showError('headerName'); ?>
				<br class="clear" />
            </div>
        </div>
        
        <?php cg_createRow('headerCost'); ?>
            <div class="cell">
                <label for="headerCost"><span class="required">*</span>Header Cost</label>
                <input type="text" name="headerCost" id="headerCost" class="s2" maxlength="3" value="<?php echo $html['headerCost']; ?>" />
				<p class="unit">CP</p>
				<?php cg_showError('headerCost'); ?>
				<br class="clear" />
            </div>
        </div>
		
		<?php cg_createRow('headerDescription'); ?>
            <div class="cell">
                <label for="headerDescription">Description</label>
                <textarea name="headerDescription" class="xl" rows="12" id="headerDescription"><?php echo $html['headerDescription']; ?></textarea>
				<?php cg_showError('headerDescription'); ?>
				<br class="clear" />
            </div>
        </div>
                        
        <?php cg_createRow('headerAccess'); ?>
            <div class="cell">
                <label for="headerAccess"><span class="required">*</span>Who can buy?</label>
                <select name="headerAccess" id="headerAccess">
                    <option value="Public" <?php if ($html['headerAccess'] == 'Public') echo 'selected="selected"'; ?>>Anyone</option>
                    <option value="Hidden" <?php if ($html['headerAccess'] == 'Hidden') echo 'selected="selected"'; ?>>Selected characters only</option>
                    <option value="NPC" <?php if ($html['headerAccess'] == 'NPC') echo 'selected="selected"'; ?>>NPCs only</option>
                </select>
				<?php cg_showError('headerAccess'); ?>
				<br class="clear" />
            </div>
        </div>
        
		<?php 
			if ($html['headerAccess'] != 'Hidden') {
				cg_createRow('PCAccess', 'display: none'); // If header is public or NPC-only (or new), hide the access list
			} else {
				cg_createRow('PCAccess'); // If header is hidden, show the access list
			}
		?>
            <div class="cell">
                <label for="PCAccess">Characters who can buy this header<br /><span class="optional">(optional)</span></label>
                
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
						<input type="checkbox" name="characterID[]" id="<?php echo 'characterID_' . $charRow['characterID']; ?>" value="<?php echo $charRow['characterID']; ?>" <?php if (isset($html['characterID']) && in_array($charRow['characterID'], $html['characterID'])) echo 'checked="checked"'; ?> />
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
        
        <div class="btnArea">
            <input type="hidden" id="headerAdminSubmitted" name="headerAdminSubmitted" value="1">
			<input type="submit" id="headerAdminSave" name="headerAdminSave" value="<?php echo $html['btnLabel']; ?>" class="btn-primary" />
			<input type="button" id="headerAdminCancel" name="headerAdminSave" value="Cancel" class="btn-secondary" onClick="window.location.href='headers.php'" />
            <br class="clear" />
        </div>
		
		</form>
        
      </div> <!--end of main div-->
      <br class="clear" />
    </div><!--end of content div-->
	
	<?php
		cg_clearUIMessage();
    
		include('../includes/footer.php'); 
	?>
