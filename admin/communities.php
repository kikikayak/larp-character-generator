<?php

	/**************************************************************
	NAME: 	communities.php
	NOTES: 	Main page of communities section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'communities';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = $_SESSION['communityLabel'] . ' | ' . $_SESSION['campaignName'] . ' Character Generator';

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
        
        <h2><?php echo $_SESSION['communityLabelPlural']; ?></h2>

        <div class="toolbar">
          <a href="communityAdmin.php" class="addLink" id="communityAddLink">Add <?php echo $_SESSION['communityLabel']; ?></a>
          <br class="clear" />
        </div><!--.toolbar-->

      <!--******************************************
          LIST OF COMMUNITIES
          ****************************************** -->
      
      <table id="communityList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="col1">Name</th>
                <th class="col2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
			<?php
				$communityObj = new Community();
				$communities = $communityObj->getAllCommunities();
				
				$rowIndex = 1;
				while ($community = $communities->fetch_assoc()) { // Loop through retrieved countries
					if ($rowIndex % 2 == 0) {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
			?>

            <tr class="<?php echo $rowClass; ?>"> 
                <td class="col1"><?php echo $community['communityName']; ?>
                  <input type="hidden" name="communityID[]" id="communityID_<?php echo $community['communityID']; ?>" value="<?php echo $community['communityID']; ?>" />
                </td>
                <td class="col2">
                	<div class="actionsContainer">
                      <a href="#" title="<?php echo $_SESSION['communityLabel']; ?> actions" class="actionsLink">Actions</a>
                      <div class="menu" style="display:none">
                          <ul>
                              <li><a href="communityAdmin.php?communityID=<?php echo $community['communityID']; ?>" title="Edit this country">Edit</a></li>
                              <li><a href="#" title="Delete this <?php echo $_SESSION['communityLabel']; ?>" class="deleteLink">Delete</a></li>
                          </ul>
                      </div>
                    </div><!--.actionsContainer-->
                </td>
            </tr>
			
			  <?php 
			  		$rowIndex++;
				} // end loop through communities
			  ?>

         </tbody>
      </table>
          
    <!-- 	********************************************************
			END OF COMMUNITIES TABLE
			******************************************************* -->
  </div>
  <!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="communityDeleteDialog" class="deleteDialog" style="display:none">
    <!--Contents to be populated by AJAX call--> 
</div><!--#communityDeleteDialog-->

<?php include('../includes/footer.php'); ?>
