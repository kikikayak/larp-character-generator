<?php

	/**************************************************************
	NAME: 	feats.php
	NOTES: 	Main page of feats section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'feats';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = 'Feats | ' . $_SESSION['campaignName'] . ' Character Generator';
	
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
    
      <h2>Feats</h2>

      <div class="toolbar">
        <a href="featAdmin.php" class="addLink">Add Feat</a>
        <br class="clear" />
      </div><!--.toolbar-->

    <!--******************************************
        LIST OF FEATS
        ****************************************** -->
    
        <table id="featList" class="sortName" cellpadding="5" cellspacing="0">
            <thead>
                <tr> 
                    <th class="nameCol">Name</th>
                    <th class="costCol">Cost</th>
                    <th class="actionCol">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <tr class="odd">
                <td colspan="5" class="loading">
                  <img src="styles/images/spinner.gif" height="32" width="32" alt="Loading..." />
                  <p>Loading table contents...</p>
                  <!--Content is populated by getFeats method in gameWorld.handler.php-->
                  </td>
              </tr>
			</tbody>
		</table>
    <!-- ********************************************************
        END OF FEATS TABLE
        ******************************************************* -->
  </div>
  <!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="featDeleteDialog" class="deleteDialog" style="display:none">
    <!--Contents to be populated by AJAX call--> 
</div><!--#featDeleteDialog-->

<?php include('../includes/footer.php'); ?>