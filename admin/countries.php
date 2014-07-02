<?php

	/**************************************************************
	NAME: 	countries.php
	NOTES: 	Main page of countries section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'countries';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = 'Countries | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>	

<body id="countriesPage">

	<?php include('../includes/adminNav.php'); ?>
	<?php include('../includes/gameWorldSecNav.php'); ?>

	<div id="content" class="oneCol">

	<div id="warning"></div>

	  <div id="main">
	    
	    <div id="msg">
	    	<?php cg_showUIMessage(); ?>
	    </div>
		
		<h2>Countries</h2>

		<div class="toolbar">
			<a href="countryAdmin.php" class="addLink">Add Country</a>
			<br class="clear" />
		</div><!--.toolbar-->

    <!--******************************************
	LIST OF COUNTRIES
	****************************************** -->
    <table id="countryList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="nameCol">Name</th>
                <th class="defaultCol">Default</th>
                <th class="actionsCol">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
			<?php
				$countryObj = new Country();
				$countries = $countryObj->getAllCountries();
				
				$rowIndex = 1;
				while ($country = $countries->fetch_assoc()) { // Loop through retrieved countries
					if ($rowIndex % 2 == 0) {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
			?>
            <tr class="<?php echo $rowClass; ?>">
                <td class="nameCol">
					<?php echo $country['countryName']; ?>
                    <input type="hidden" name="countryID[]" id="countryID_<?php echo $country['countryID']; ?>" value="<?php echo $country['countryID']; ?>" />
                </td>
                <td class="defaultCol">
                	<?php 
	                	if ($country['countryDefault'] == 1) {
	                		echo 'Yes';
	                	}
                	?>
                </td>
                <td class="actionsCol">
                	<div class="actionsContainer">
                      <a href="#" title="Country actions" class="actionsLink">Actions</a>
                      <div class="menu" style="display:none">
                          <ul>
                              <li><a href="countryAdmin.php?countryID=<?php echo $country['countryID']; ?>" title="Edit this country">Edit</a></li>
                              <li><a href="#" title="Delete this country" class="deleteLink">Delete</a></li>
                          </ul>
                      </div>
                    </div><!--.actionsContainer-->
                </td>
            </tr>
			  <?php 
			  		$rowIndex++;
				} // end loop through countries
			  ?>

        </tbody>
    </table>
    <!-- ********************************************************
        END OF COUNTRIES TABLE
        ******************************************************* -->
  </div><!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="countryDeleteDialog" class="deleteDialog" style="display:none">
    <!--Contents to be populated by AJAX call--> 
</div><!--#countryDeleteDialog-->

<?php include('../includes/footer.php'); ?>

</body>
</html>
