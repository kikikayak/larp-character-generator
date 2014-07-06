<?php

	/**************************************************************
	NAME: 	index.php
	NOTES: 	Home page of Admin section. This page provides a
			landing page and notifications for users and staff members. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'home';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = 'Administration | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>	

<body id="homePage">

    <?php include('../includes/adminNav.php'); ?>
    
    <div id="content" class="oneCol">
        
        <!--no sidebar-->
        
        <div id="main">
			
            <div id="msg">
            	<?php cg_showUIMessage(); ?>
            </div>
            
            <h2>Administration</h2>
            
            <p>The Character Generator administration section allows you to manage your game and your player base. </p>
            
            <ul>
            	<li><a href="players.php">Manage Players</a>: Set up new players or edit existing players, including changing passwords. </li>
                <li><a href="characters.php">Manage Characters</a>: Create new characters. View or edit existing characters. Filter characters using multiple criteria. </li>
                <li><a href="cp.php">Administer CP</a>: Assign CP to players and characters and view existing CP records using a variety of criteria.</li>
                <li><strong>Set up game world</strong>: Manage the headers, skills, and other features of your game world. 
                	<div class="leftCol">
                      <ul>
                          <li><a href="headers.php">Headers</a></li>
                          <li><a href="skills.php">Skills</a></li>
                          <li><a href="spells.php">Spells</a></li>
                          <li><a href="countries.php">Countries</a></li>
                      </ul>
                    </div><!--.leftCol-->
                    <div class="rightCol">
                      <ul>
                          <li><a href="communities.php"><?php echo $_SESSION['communityLabelPlural']; ?></a></li>
                          <li><a href="races.php">Races</a></li>
                          <li><a href="traits.php">Traits</a></li>
                      </ul>
                    </div><!--.rightCol-->
                  <br class="clear" />
                  </li>
                <li><a href="settings.php">Settings</a>: Configure the Character Generator for your game. </li>
            </ul>
            
            <form name="pendingUsersForm" id="pendingUsersForm" action="#" method="post">
            
            <!--******************************************
            	LIST OF PENDING USERS
                ****************************************** -->
            
            <div id="pendingUserSection" class="section">    
              <h3>Pending User Requests</h3>
              
              <fieldset>
              <p>The below people have requested logins to the Character Generator. Users will not be able to log into the Character Generator until they have been approved. Rejected users will be sent to the <a href="trash.php">Trash</a>.</p>
              
              <div id="toolbar">
                  <input type="button" name="approveUsersBtn" id="approveUsersBtn" value="Approve Selected" class="btn-toolbar" />
                  <input type="button" name="rejectUsersBtn" id="rejectUsersBtn" value="Reject Selected" class="btn-toolbar" />
                  <br class="clear" />
              </div><!--#toolbar-->
               
              <!--Contents of table will be populated by AJAX call-->  
              <table id="pendingUserList" class="sortName" cellpadding="5" cellspacing="0">
              <thead>
                  <tr>
                    <th class="chkboxCol"><input type="checkbox" id="selectAll" name="selectAll" /></th>
                    <th class="nameCol">Name</th>
                    <th class="emailCol">Email</th>
                    <th class="actionCol">&nbsp;</th>
                  </tr>
              </thead>
              <tbody> 
                  <tr class="odd">
                    <td colspan="4" class="loading">
                      <img src="styles/images/spinner.gif" height="32" width="32" alt="Loading..." />
                      <p>Loading table contents...</p>
                      </td>
                  </tr>
              </tbody>
              </table>
            
            </fieldset>
            
          </div>
          
          <!-- ********************************************************
          		END OF PENDING USERS TABLE
                ******************************************************* -->
                
          </form>
        </div> <!--end of main div-->
        
        <br class="clear" />
    </div><!--end of content div-->
    
    <div id="playerApproveDialog" class="approveDialog" style="display:none"></div>
    <div id="playerRejectDialog" class="rejectDialog" style="display:none"></div>
    <div id="playerApproveMultiDialog" class="approveMultiDialog" style="display:none"></div>
    <div id="playerRejectMultiDialog" class="rejectMultiDialog" style="display:none"></div>
    
    <?php include('../includes/footer.php'); ?>
