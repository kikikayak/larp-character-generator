<?php

	/**************************************************************
	NAME: 	skills.php
	NOTES: 	Main page of skills section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'skills';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	if (isset($_SESSION['selectedSkillTab'])) {
	  $tabName = $_SESSION['selectedSkillTab'];
	} else {
	  $tabName = 'showAll';  
	}
		
	$title = 'Skills | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>

<body>

	<?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content" class="oneCol">

    	<!-- ************************ UTILITY CONTENT *********************** -->
      
      <div id="warning"></div>

      <div id="main">
  	
        <div id="msg">
        	<?php cg_showUIMessage(); ?>
        </div>
    	
      	<h2>Skills</h2>
          
        <div class="toolbar">
          <a href="skillAdmin.php" class="addLink">Add Skill</a>
          <br class="clear" />
        </div><!--.toolbar-->
  		
        <!--******************************************
            LIST OF SKILLS
            ****************************************** -->
        
        <div id="skillListContainer" class="tabbedTable <?php echo $tabName; ?>">
        	<a href="#" id="showAll">View All</a>
            <a href="#" id="showPublic">Public</a>
            <a href="#" id="showHidden">Hidden</a>
            <a href="#" id="showNPCOnly">NPC Only</a>
        </div>
      
      <table id="skillList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr class="even"> 
                <th class="nameCol">Name</th>
                <th class="costCol">Cost</th>
                <th class="stackCol">Stack?</th>
                <th class="headerCol">Parent Header</th>
                <th class="accessCol">Access</th>
                <th class="actionsCol">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
         <tr class="odd">
            <td colspan="6" class="loading">
              <img src="../images/spinner.gif" height="32" width="32" alt="Loading..." />
              <p>Loading table contents...</p>
              </td>
          </tr>   

        </tbody>
      </table>

    <!-- ********************************************************
        END OF SKILLS TABLE
        ******************************************************* -->
  </div>
  <!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="skillDeleteDialog" class="deleteDialog" style="display:none">
    <!--Contents to be populated by AJAX call--> 
</div><!--#skillDeleteDialog-->

<?php include('../includes/footer.php'); ?>
