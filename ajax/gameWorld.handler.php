<?php

$pageAccessLevel = 'Staff';

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

/***************************************************************
GET FEATS
***************************************************************/
if ($ajaxAction == 'getFeats') {
	
	$featObj = new Feat();
	
	$featResults = $featObj->getAllFeats();
	
	$rowIndex = 1;
	while ($feat = $featResults->fetch_assoc()) { // Loop through retrieved feats
		if ($rowIndex % 2 == 0) {
			$rowClass = 'even';
		} else {
			$rowClass = 'odd';
		}
        
        ?>
      <tr class="<?php echo $rowClass; ?>"> 
        <td class="nameCol">
          <a href="featAdmin.php?featID=<?php echo $feat['featID']; ?>"><?php echo $feat['featName']; ?></a>
          <input type="hidden" name="featID[]" id="featID_<?php echo $feat['featID']; ?>" value="<?php echo $feat['featID']; ?>" />
        </td>
        <td class="costCol"><?php echo $feat['featCost']; ?></a></td>
        <td class="actionCol">
            <div class="actionsContainer">
              <a href="#" title="Feat actions" class="actionsLink">Actions</a>
              <div class="menu" style="display:none">
                  <ul>
                      <li><a href="featAdmin.php?featID=<?php echo $feat['featID']; ?>" title="">Edit</a></li>
                      <li><a href="#" title="Delete this feat" class="deleteLink">Delete</a></li>
                  </ul>
              </div>
            </div><!--.actionsContainer-->
        </td>
      </tr>
      <?php 
           $rowIndex++;
        } // end loop through feats
		
		if ($featResults->num_rows == 0) {
		  echo '<tr class="even"><td colspan="4"><p class="noResults">There are currently no feats.</p></td></tr>';  
		}
	
} // end of getFeats

/***************************************************************
LOAD FEAT DELETE DIALOG
***************************************************************/
if ($ajaxAction == 'loadFeatDeleteDialog' && isset($_POST['featID'])) {
	$allowFeatDelete = 'no';
	$feat = new Feat();
	$featChars = $feat->getFeatChars($_POST['featID']);
	
	if ($featChars->num_rows > 0) {
?>
<p>This feat cannot be deleted because the following <span class="numDeleteItems"><?php echo $featChars->num_rows; ?></span> characters have purchased it: </p>

<div class="deleteList">
  <ul>
  
  <?php
      while ($charRow = $featChars->fetch_assoc()) {
  ?>
  <li><?php echo $charRow['charName']; ?></li>
  
  <?php
      } // end characters loop
  ?>
  </ul>
</div><!--.deleteList-->

<p>Please remove this feat from these characters and then try again. </p>

<?php
	} else {
	  $allowFeatDelete = 'yes';
	  $featDetails = $feat->getFeat($_POST['featID']);
	  while ($row = $featDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to delete the following feat?</p>
      <p class="deleteItemName"><?php echo $row['featName']; ?></p>
      <p>You will be able to restore the feat from the trash if necessary.</p>
      <input type="hidden" name="deleteFeatID" id="deleteFeatID" value="<?php echo $_POST['featID']; ?>" />
	
<?php
	  } // end of featDetails loop
	} // end of num_rows condition
?>
	   <input type="hidden" name="allowFeatDelete" id="allowFeatDelete" value="<?php echo $allowFeatDelete; ?>" />
       
<?php
} // end loadFeatDelete

/***************************************************************
DELETE FEAT
***************************************************************/
if ($ajaxAction == 'deleteFeat' && isset($_POST['featID'])) {
	$feat = new Feat();
	
	if ($feat->deleteFeat($_POST['featID'])) {
		$featDetails = $feat->getFeat($_POST['featID']);
		
		while ($row = $featDetails->fetch_assoc()) {
		  $html = array();
		  $html['featName'] = htmlentities($row['featName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Feat Deleted Successfully',
													'<p>The feat "' . $html['featName'] . '" has been deleted. You can recover it from the <a href="trash.php">Trash</a> if necessary.</p>');
												
		} // end of headerDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Delete Feat',
												'<p>The feat could not be deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of deleteFeat

/***************************************************************
LOAD FEAT PURGE DIALOG
***************************************************************/
if ($ajaxAction == 'loadFeatPurgeDialog' && isset($_POST['featID'])) {
	$feat = new Feat();
	$featDetails = $feat->getFeat($_POST['featID']);
	while ($row = $featDetails->fetch_assoc()) {
	
?>
      <p>Are you sure you want to permanently delete the following feat?</p>
      <p class="deleteItemName"><?php echo $row['featName']; ?></p>
      <p>The feat will be removed from all characters and all associated data will be removed. You will not be able to undo this action.</p>
      <input type="hidden" name="purgeFeatID" id="purgeFeatID" value="<?php echo $_POST['featID']; ?>" />
	
<?php
	} // end of featDetails loop
} // end loadFeatPurge

/***************************************************************
PURGE FEAT
***************************************************************/
if ($ajaxAction == 'purgeFeat' && isset($_POST['featID'])) {
	$feat = new Feat();
	
	if ($feat->purgeFeat($_POST['featID'])) {
		$featDetails = $feat->getFeat($_POST['featID']);
		
		while ($row = $featDetails->fetch_assoc()) {
		  $html = array();
		  $html['featName'] = htmlentities($row['featName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Feat Purged Successfully',
													'<p>The feat "' . $html['featName'] . '" has been permanently deleted. </p>');
												
		} // end of featDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Purge Feat',
												'<p>The feat could not be permanently deleted. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of purgeFeat

/***************************************************************
UNDELETE FEAT
***************************************************************/
if ($ajaxAction == 'undeleteFeat' && isset($_POST['featID'])) {
	$feat = new Feat();
	
	if ($feat->undeleteFeat($_POST['featID'])) {
		$featDetails = $feat->getFeat($_POST['featID']);
		
		while ($row = $featDetails->fetch_assoc()) {
		  $html = array();
		  $html['featName'] = htmlentities($row['featName']);
		  
		  // If successful, set success message
		  $_SESSION['UIMessage'] = new UIMessage(	'success', 
													'Feat Undeleted Successfully',
													'<p>The feat "' . $html['featName'] . '" has been restored from the trash. You can view it from the <a href="feats.php" title="Go to feats page">Feats</a> page. </p>');
												
		} // end of featDetails loop
	} else {
		// Set failure message
		$_SESSION['UIMessage'] = new UIMessage(	'error', 
												'Failed to Undelete Feat',
												'<p>The feat could not be restored from the trash. Please try again. </p>');	
	}
	cg_showUIMessage();
	cg_clearUIMessage();
} // end of undeleteFeat

