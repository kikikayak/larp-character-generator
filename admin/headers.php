<?php

	/**************************************************************
	NAME: 	headers.php
	NOTES: 	Main page of headers section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'headers';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	if (isset($_SESSION['selectedHeaderTab'])) {
	  $tabName = $_SESSION['selectedHeaderTab'];
	} else {
	  $tabName = 'showAll';  
	}
	
	$title = 'Headers | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>

<body id="headersPage">
    
	<?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
	
    <div id="content" class="oneCol">

	<!-- ************************ UTILITY CONTENT *********************** -->
    
    <div id="warning"></div>
  
  <div id="main">
	
    <div id="msg">
	  <?php cg_showUIMessage(); ?>
    </div>
	
  	<h2>Headers</h2>

      <div class="toolbar">
        <a href="headerAdmin.php" class="addLink">Add Header</a>
        <br class="clear" />
      </div><!--.toolbar-->
      
      <!--******************************************
          LIST OF HEADERS
          ****************************************** -->
    
    <div id="headerListContainer" class="tabbedTable <?php echo $tabName; ?>">
    	<a href="#" id="showAll">View All</a>
        <a href="#" id="showPublic">Public</a>
        <a href="#" id="showHidden">Hidden</a>
        <a href="#" id="showNPCOnly">NPC Only</a>
    </div>
        <table id="headerList" class="sortName" cellpadding="5" cellspacing="0">
            <thead>
                <tr> 
                    <th class="col1">Name</th>
                    <th class="col2">Cost</th>
        					  <th class="col3">Access</th>
                    <th class="col4"></th>
                </tr>
            </thead>
            <tbody>
            	<tr class="odd">
                  <td colspan="4" class="loading">
                  	<img src="../images/spinner.gif" height="32" width="32" alt="Loading..." />
                    <p>Loading table contents...</p>
                  </td>
                </tr>
            </tbody>
        </table>
        <!-- ********************************************************
            END OF HEADERS TABLE
            ******************************************************* -->
  </div>
  <!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="headerDeleteDialog" class="deleteDialog" style="display:none"></div>

<?php
	include('../includes/footer.php'); 
?>
