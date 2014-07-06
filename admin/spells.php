<?php

	/**************************************************************
	NAME: 	spells.php
	NOTES: 	Main page of spells section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'spells';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	if (isset($_SESSION['selectedSpellTab'])) {
	  $tabName = $_SESSION['selectedSpellTab'];
	} else {
	  $tabName = 'showAll';  
	}
	
	$title = 'Spells | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>

<body>

	<?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content" class="oneCol">
    
      <div id="warning"></div>

      <div id="main">
      
    	<div id="msg">
      	<?php cg_showUIMessage(); ?>
      </div>
    
      <h2>Spells</h2>

      <div class="toolbar">
        <a href="spellAdmin.php" class="addLink">Add Spell</a>
        <br class="clear" />
      </div><!--.toolbar-->
      
      <!--******************************************
          LIST OF SPELLS
          ****************************************** -->
      
      <div id="spellListContainer" class="tabbedTable <?php echo $tabName; ?>">
      	<a href="#" id="showAll">View All</a>
          <a href="#" id="showPublic">Public</a>
          <a href="#" id="showHidden">Hidden</a>
          <a href="#" id="showNPCOnly">NPC Only</a>
      </div>
        <table id="spellList" class="sortName" cellpadding="5" cellspacing="0">
            <thead>
                <tr> 
                    <th class="col1">Name</th>
                    <th class="col2">Parent Skill</th>
                    <th class="col3">Cost</th>
                    <th class="col4">Access</th>
                    <th class="col5">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <tr class="odd">
                <td colspan="5" class="loading">
                  <img src="styles/images/spinner.gif" height="32" width="32" alt="Loading..." />
                  <p>Loading table contents...</p>
                  </td>
              </tr>
			</tbody>
		</table>
    <!-- ********************************************************
        END OF SPELLS TABLE
        ******************************************************* -->
  </div>
  <!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="spellDeleteDialog" class="deleteDialog" style="display:none">
    <!--Contents to be populated by AJAX call--> 
</div><!--#spellDeleteDialog-->

<?php include('../includes/footer.php'); ?>
