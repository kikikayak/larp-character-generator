<?php

	/**************************************************************
	NAME: 	traits.php
	NOTES: 	Main page of traits section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'gameWorld';
	$secNavClass = 'traits';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = 'Traits | ' . $_SESSION['campaignName'] . ' Character Generator';

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
      
      <h2>Traits</h2>

      <div class="toolbar">
        <a href="traitAdmin.php" class="addLink">Add Trait</a>
        <br class="clear" />
      </div><!--.toolbar-->

      <!--******************************************
          LIST OF TRAITS
          ****************************************** -->
      
      <table id="traitList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="col1">Name</th>
                <th class="col2">Staff Member</th>
        				<th class="col3">Access</th>
                <th class="col4"></th>
            </tr>
        </thead>
        <tbody>
			<?php
				$traitObj = new charTrait();
				$traits = $traitObj->getAllTraits();
				
				$rowIndex = 1;
				while ($trait = $traits->fetch_assoc()) { // Loop through retrieved countries
					if ($rowIndex % 2 == 0) {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
			?>

            <tr class="<?php echo $rowClass; ?>"> 
                <td class="col1"><a href="traitAdmin.php?traitID=<?php echo $trait['traitID']; ?>"><?php echo $trait['traitName']; ?></a>
                <input type="hidden" name="traitID[]" id="traitID_<?php echo $trait['traitID']; ?>" value="<?php echo $trait['traitID']; ?>" />
                </td>
                <td class="col2"><?php echo $trait['traitStaff']; ?></td>
				<td class="col3"><?php echo $trait['traitAccess']; ?></td>
                <td class="col4">
                	<div class="actionsContainer">
                      <a href="#" title="Trait actions" class="actionsLink">Actions</a>
                      <div class="menu" style="display:none">
                          <ul>
                              <li><a href="traitAdmin.php?traitID=<?php echo $trait['traitID']; ?>" title="Edit this trait">Edit</a></li>
                              <li><a href="#" title="Delete this trait" class="deleteLink">Delete</a></li>
                          </ul>
                      </div>
                    </div><!--.actionsContainer-->
                </td>
            </tr>
			
			  <?php 
			  		$rowIndex++;
				} // end loop through traits
			  ?>

        </tbody>
    </table>
      
    <!-- ********************************************************
        END OF TRAITS TABLE
        ******************************************************* -->
  </div>
  <!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="traitDeleteDialog" class="deleteDialog" style="display:none">
    <!--Contents to be populated by AJAX call--> 
</div><!--#traitDeleteDialog-->

<?php include('../includes/footer.php'); ?>
