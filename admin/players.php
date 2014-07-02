<?php

	/**************************************************************
	NAME: 	players.php
	NOTES: 	Main page of players section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'players';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = 'Player Administration | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>

<body>

    <?php include('../includes/adminNav.php'); ?>
    
    <div id="content" class="oneCol">
        <div id="main">
            
            <div id="msg">
            	<?php cg_showUIMessage(); ?>
            </div>
            
            <h2>Player Administration</h2>

            <?php
              if ($_SESSION['userRole'] == 'Admin') {
            ?>
            <div class="toolbar">
              <a href="playerAdmin.php" class="addLink">Add Player</a>
              <br class="clear" />
            </div><!--.toolbar-->
            <?php
              }
            ?>
            
            <!--******************************************
            	LIST OF PLAYERS
                ****************************************** -->
                
          <table id="playerList" class="sortName" cellpadding="5" cellspacing="0">
            <thead>
                <tr> 
                  <th class="col1">Name</th>
                  <th class="col2">Permissions</th>
                  <th class="col3">Email</th>
                  <th class="col4"></th>
                </tr>
            </thead>
            <tbody> 
				<?php
					$playerObj = new Player();
					$players = $playerObj->getAllPlayers();
					
					$rowIndex = 1;
					while ($player = $players->fetch_assoc()) { // Loop through retrieved countries
						if ($rowIndex % 2 == 0) {
							$rowClass = 'even';
						} else {
							$rowClass = 'odd';
						}
				
				?>
              <tr class="<?php echo $rowClass; ?>"> 
                <td class="col1"><a href="playerDetails.php?playerID=<?php echo $player['playerID']; ?>" title="View details on this player"><?php echo $player['firstName'] . ' ' . $player['lastName']; ?></a>
                  <input type="hidden" name="playerID[]" id="playerID_<?php echo $player['playerID']; ?>" value="<?php echo $player['playerID']; ?>" />
                </td>
                <td class="col2"><?php echo $player['userRole']; ?></td>
                <td class="col3"><a href="mailto:<?php echo $player['email']; ?>" title="Send an email to this player"><?php echo $player['email']; ?></a></td>
                <td class="col4">
                  <div class="actionsContainer">
                    <a href="#" title="Player actions" class="actionsLink">Actions</a>
                    <div class="menu" style="display:none">
                        <ul>
                            <?php
								if ($_SESSION['userRole'] == 'Admin') {
							?>
                            <li><a href="playerAdmin.php?playerID=<?php echo $player['playerID']; ?>" title="Edit this player">Edit</a></li>
                            <?php
								}
							?>
                            <li><a href="#" title="Delete this player" class="deleteLink">Delete</a></li>
                        </ul>
                    </div>
                  </div><!--.actionsContainer-->
              </tr>
			  
			  <?php 
			  		$rowIndex++;
				} // end loop through players
				?>
          </tbody>
      </table>
          
          <!-- ********************************************************
          		END OF PLAYERS TABLE
                ******************************************************* -->
        </div> <!--end of main div-->
        
        <br class="clear" />
    </div><!--end of content div-->
    
    <div id="playerDeleteDialog" class="deleteDialog" style="display:none">
    	<!--Contents to be populated by AJAX call--> 
    </div><!--#playerDeleteDialog-->
    
    <?php include('../includes/footer.php'); ?>
