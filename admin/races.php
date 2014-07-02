<?php

	/**************************************************************
	NAME: 	races.php
	NOTES: 	Main page of races section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'races';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = 'Races | ' . $_SESSION['campaignName'] . ' Character Generator';

	include('../includes/header_admin.php');

?>	

<body>

	<?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>
		
    <div id="content">

	<!-- ************************ UTILITY CONTENT *********************** -->
    
    <div id="warning">
    
    
    </div>


  <div id="sidebar">
    <div id="actionPanel">
  		<div id="actionPanelContents">
  			<ul>
  				<li><a href="raceAdmin.php" class="addLink">Add a race</a></li>
  			</ul>
  		</div>
    </div>
  </div>
  <div id="main">
    
    <div id="msg">
		<?php cg_showUIMessage(); ?>
    </div>
    
    <h2>Races</h2>
    <!--******************************************
        LIST OF RACES
        ****************************************** -->
    
    <table id="raceList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="col1">Name</th>
                <th class="col2"></th>
            </tr>
        </thead>
        <tbody>
			<?php
				$raceObj = new Race();
				$races = $raceObj->getAllRaces();
				
				$rowIndex = 1;
				while ($race = $races->fetch_assoc()) { // Loop through retrieved countries
					if ($rowIndex % 2 == 0) {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
			?>

            <tr class="<?php echo $rowClass; ?>"> 
                <td class="col1">
                	<a href="raceAdmin.php?raceID=<?php echo $race['raceID']; ?>"><?php echo $race['raceName']; ?></a>
                    <input type="hidden" name="raceID[]" id="raceID_<?php echo $race['raceID']; ?>" value="<?php echo $race['raceID']; ?>" />
                </td>
                <td class="col2">
                	<div class="actionsContainer">
                      <a href="#" title="Race actions" class="actionsLink">Actions</a>
                      <div class="menu" style="display:none">
                          <ul>
                              <li><a href="raceAdmin.php?raceID=<?php echo $race['raceID']; ?>" title="Modify this race">Edit</a></li>
                              <li><a href="#" class="deleteLink" title="Delete this race">Delete</a></li>
                          </ul>
                      </div>
                    </div><!--.actionsContainer-->
            </tr>
			
			  <?php 
			  		$rowIndex++;
				} // end loop through races
			  ?>

        </tbody>
    </table>
      
    <!-- ********************************************************
        END OF RACES TABLE
        ******************************************************* -->
  </div>
  <!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="raceDeleteDialog" class="deleteDialog" style="display:none"></div>

<?php include('../includes/footer.php'); ?>