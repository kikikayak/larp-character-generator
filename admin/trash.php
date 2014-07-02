<?php

	/**************************************************************
	NAME: 	trash.php
	NOTES: 	Trash page. This page displays all items that have been
			logically deleted. The player can restore or permanently
			delete from this page.  
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = '';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');

	$title = 'Trash | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>	

<body id="trashPage">

	<script type="text/javascript">
		$(document).ready(function () {
			
			// Enable sorting of country list
			$("#deletedCountryList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					1:{sorter: false},
					2:{sorter: false}
				}
			});
			
			// Enable sorting of character list
			$("#deletedCharList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					3:{sorter: false}
				}
			});
			
			// Enable sorting of community list
			$("#deletedCommunityList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					1:{sorter: false}
				}
			});

      // Enable sorting of deleted feats
      $("#deletedFeatList").tablesorter({
        sortList: [[0,0]],
        headers: { 
          2:{sorter: false}
        }
      });
			
			// Enable sorting of header list
			$("#deletedHeaderList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					3:{sorter: false}
				}
			});
			
			// Enable sorting of deleted players
			$("#deletedPlayerList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					3:{sorter: false}
				}
			});
			
			// Enable sorting of deleted races
			$("#deletedRaceList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					1:{sorter: false}
				}
			});
			
			// Enable sorting of deleted skills
			$("#deletedSkillList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					4:{sorter: false}
				}
			});
			
			// Enable sorting of deleted spells
			$("#deletedSpellList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					3:{sorter: false}
				}
			});
			
			// Enable sorting of deleted traits
			$("#deletedTraitList").tablesorter({
				sortList: [[0,0]],
				headers: { 
					3:{sorter: false}
				}
			});
			
		});
	</script>
    
	<?php include('../includes/adminNav.php'); ?>
	
    <div id="content">

	<!-- ************************ UTILITY CONTENT *********************** -->
    
    <div id="warning">
    
    
    </div>

  <!--NO SIDEBAR ON THIS PAGE-->

  <div id="main" class="wide">
    
    <div id="msg">
    	<?php cg_showUIMessage(); ?>
    </div>
	
	<h2>Trash</h2>
    <p>Below are all of the deleted items in the system. You can undelete an item to restore it from the trash, or permanently delete it. </p>
    
    <!--******************************************
	DELETED CHARACTERS
	****************************************** -->
    
    <h3>Deleted Characters</h3>
	<?php
	  $charObj = new Character();
	  $characters = $charObj->getDeletedCharacters();
	  if ($characters->num_rows > 0) {
    ?>
    <table id="deletedCharList" class="sortName" cellpadding="5" cellspacing="0">
      <thead>
          <tr>
            <th class="col1">Character</th>
            <th class="col2">Player</th>
            <th class="col3">Type</th>
            <th class="col4">&nbsp;</th>
          </tr>
      </thead>
      <tbody> 
          <?php
              
              $rowIndex = 1;
              while ($character = $characters->fetch_assoc()) { // Loop through retrieved countries
                  // $rowClass = '';
                  if ($rowIndex % 2 == 0) {
                      $rowClass = 'even';
                  } else {
                      $rowClass = 'odd';
                  }
          
          ?>
        <tr class="<?php echo $rowClass; ?>"> 
          <td class="col1">
            <a href="charAdmin.php?characterID=<?php echo $character['characterID']; ?>"><?php echo $character['charName']; ?></a>
            <input type="hidden" id="<?php echo 'characterID_' . $character['characterID']; ?>" name="characterID[]" value="<?php echo $character['characterID']; ?>" />
          </td>
          <td class="col2"><?php echo $character['firstName'] . ' ' . $character['lastName']; ?></td>
          <td class="col3"><?php echo $character['charType']; ?></td>
          <td class="col4">
              <div class="actionsContainer">
                <a href="#" title="Character actions" class="actionsLink">Actions</a>
                <div class="menu" style="display:none">
                    <ul>
                        <li><a href="charAdmin.php?characterID=<?php echo $character['characterID']; ?>" title="Modify this character">Edit</a></li>
                        <li><a href="#" class="undeleteCharLink" title="Undelete this character">Undelete</a></li>
                        <li><a href="#" class="purgeCharLink" title="Permanently delete this character">Purge</a></li>
                    </ul>
                </div>
              </div><!--.actionsContainer-->
          </td>
        </tr>
        <?php 
              $rowIndex++;
          } // end loop through characters
        ?>
      </tbody>
      </table>
      <?php
		} else {
		  echo '<p class="noResults">There are currently no deleted characters.</p>';
		}
	  ?>
    
    <!-- END OF CHARACTERS TABLE -->
    
    <!--******************************************
        DELETED COMMUNITIES
        ****************************************** -->
        
    <h3>Deleted <?php echo $_SESSION['communityLabelPlural']; ?></h3>
    
    <?php
	  $communityObj = new Community();
	  $communities = $communityObj->getDeletedCommunities();
	  if ($communities->num_rows > 0) {
	?>
    
    <table id="deletedCommunityList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="col1">Name</th>
                <th class="col2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
			<?php
				
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
                            <li><a href="communityAdmin.php?communityID=<?php echo $community['communityID']; ?>" title="Modify this community">Edit</a></li>
                            <li><a href="#" class="undeleteCommunityLink" title="Undelete this <?php echo $_SESSION['communityLabel']; ?>">Undelete</a></li>
                            <li><a href="#" class="purgeCommunityLink" title="Permanently delete this <?php echo $_SESSION['communityLabel']; ?>">Purge</a></li>
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
	  <?php
		} else {
		  echo '<p class="noResults">There are currently no deleted countries.</p>';
		}
	  ?>
          
    <!-- END OF COMMUNITIES TABLE -->
    
    <!--******************************************
	DELETED COUNTRIES
	****************************************** -->
    
    <h3>Deleted Countries</h3>
    
    <?php
	  $countryObj = new Country();
	  $countries = $countryObj->getDeletedCountries();
	  if ($countries->num_rows > 0) {
	?>
    <table id="deletedCountryList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="col1">Name</th>
                <th class="col2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
			<?php
				
				$rowIndex = 1;
				while ($country = $countries->fetch_assoc()) { // Loop through retrieved countries
					if ($rowIndex % 2 == 0) {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
			?>
            <tr class="<?php echo $rowClass; ?>">
                <td class="col1">
					<?php echo $country['countryName']; ?>
                    <input type="hidden" name="countryID[]" id="countryID_<?php echo $country['countryID']; ?>" value="<?php echo $country['countryID']; ?>" />
                </td>
                <td class="col2">
                  <div class="actionsContainer">
                    <a href="#" title="Country actions" class="actionsLink">Actions</a>
                    <div class="menu" style="display:none">
                        <ul>
                            <li><a href="countryAdmin.php?countryID=<?php echo $country['countryID']; ?>" title="Modify this country">Edit</a></li>
                            <li><a href="#" class="undeleteCountryLink" title="Undelete this country">Undelete</a></li>
                            
                            <li><a href="#" class="purgeCountryLink" title="Permanently delete this country">Purge</a></li>
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
    <?php
	  } else {
		echo '<p class="noResults">There are currently no deleted countries.</p>';
	  }
	?>
    <!-- END OF COUNTRIES TABLE -->

    <!--******************************************
        DELETED FEATS
        ****************************************** -->
        
      <h3>Deleted Feats</h3>
      
	  <?php
		$featObj = new Feat();
		$feats = $featObj->getDeletedFeats();
		
		if ($feats->num_rows > 0) {
	  ?>
      
      <table id="deletedFeatList" class="sortName" cellpadding="5" cellspacing="0">
          <thead>
              <tr> 
                  <th class="col1">Name</th>
                  <th class="col2">Cost</th>
                  <th class="col3">&nbsp;</th>
              </tr>
          </thead>
          <tbody>
              <?php
                  
                  $rowIndex = 1;
                  while ($feat = $feats->fetch_assoc()) { // Loop through retrieved countries
                      if ($rowIndex % 2 == 0) {
                          $rowClass = 'even';
                      } else {
                          $rowClass = 'odd';
                      }
              ?>

            <tr class="<?php echo $rowClass; ?>">
              <td class="col1"><a href="featAdmin.php?featID=<?php echo $feat['featID']; ?>"><?php echo $feat['featName']; ?></a>
              <input type="hidden" name="featID[]" id="featID_<?php echo $feat['featID']; ?>" value="<?php echo $feat['featID']; ?>" />
              </td>
              <td class="col2"><?php echo $feat['featCost']; ?> CP</td>
              <td class="col3">
              	
                <div class="actionsContainer">
                  <a href="#" title="Feat actions" class="actionsLink">Actions</a>
                  <div class="menu" style="display:none">
                      <ul>
                          <li><a href="featAdmin.php?featID=<?php echo $feat['featID']; ?>" title="Modify this feat">Edit</a></li>
                          <li><a href="#" class="undeleteFeatLink" title="Undelete this feat">Undelete</a></li>
                          <li><a href="#" class="purgeFeatLink" title="Permanently delete this feat">Purge</a></li>
                      </ul>
                  </div>
                </div><!--.actionsContainer-->
              
              </td>
            </tr>
            <?php 
                  $rowIndex++;
              } // end loop through feats
            ?>

          </tbody>
      </table>
      <?php
		} else {
		  echo '<p class="noResults">There are currently no deleted feats.</p>';	
		}
	  ?>
    <!--  END OF FEATS TABLE -->
    
    <!--******************************************
	DELETED HEADERS
	****************************************** -->
    
    <h3>Deleted Headers</h3>
    
    <?php
	  $headerObj = new Header();
	  $headers = $headerObj->getDeletedHeaders();
	  if ($headers->num_rows > 0) {
    ?>
    
    <table id="deletedHeaderList" class="sortName" cellpadding="5" cellspacing="0">
      <thead>
          <tr> 
              <th class="col1">Name</th>
              <th class="col2">Cost</th>
              <th class="col3">Access</th>
              <th class="col4"></th>
          </tr>
      </thead>
      <tbody>
          <?php
              
              $rowIndex = 1;
              while ($header = $headers->fetch_assoc()) { // Loop through retrieved countries
                  if ($rowIndex % 2 == 0) {
                      $rowClass = 'even';
                  } else {
                      $rowClass = 'odd';
                  }
          
          ?>
              <tr class="<?php echo $rowClass; ?>"> 
                <td class="col1"><a href="headerAdmin.php?headerID=<?php echo $header['headerID']; ?>" title="Modify this header"><?php echo $header['headerName']; ?></a>
                  <input type="hidden" name="headerID[]" id="headerID_<?php echo $header['headerID']; ?>" value="<?php echo $header['headerID']; ?>" />
  
                </td>
                <td class="col2"><?php echo $header['headerCost']; ?> CP</td>
                <td class="col3"><?php echo $header['headerAccess']; ?></td>
                <td class="col4">
                  <div class="actionsContainer">
                    <a href="#" title="Header actions" class="actionsLink">Actions</a>
                    <div class="menu" style="display:none">
                        <ul>
                            <li><a href="headerAdmin.php?headerID=<?php echo $header['headerID']; ?>" title="Modify this header">Edit</a></li>
                            <li><a href="#" class="undeleteHeaderLink" title="Undelete this header">Undelete</a></li>
                            <li><a href="#" class="purgeHeaderLink" title="Permanently delete this header">Purge</a></li>
                        </ul>
                    </div>
                  </div><!--.actionsContainer-->
                </td>
              </tr>
              
        <?php 
              $rowIndex++;
          } // end loop through headers
        ?>
  
          </tbody>
      </table>
      <?php
	  } else {
		echo '<p class="noResults">There are currently no deleted headers.</p>';
	  }
	  ?>
      <!-- END OF HEADERS TABLE -->
      
      <!--	******************************************
            DELETED PLAYERS
            ****************************************** -->
            
      <h3>Deleted Players</h3>
      
      <?php
		$playerObj = new Player();
		$players = $playerObj->getDeletedPlayers();
		if ($players->num_rows > 0) {
	  ?>
            
      <table id="deletedPlayerList" class="sortName" cellpadding="5" cellspacing="0">
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
                
                $rowIndex = 1;
                while ($player = $players->fetch_assoc()) { // Loop through retrieved countries
                    if ($rowIndex % 2 == 0) {
                        $rowClass = 'even';
                    } else {
                        $rowClass = 'odd';
                    }
            
            ?>
          <tr class="<?php echo $rowClass; ?>"> 
            <td class="col1"><a href="playerAdmin.php?playerID=<?php echo $player['playerID']; ?>" title="View details on this player"><?php echo $player['firstName'] . ' ' . $player['lastName']; ?></a>
              <input type="hidden" name="playerID[]" id="playerID_<?php echo $player['playerID']; ?>" value="<?php echo $player['playerID']; ?>" />
            </td>
            <td class="col2"><?php echo $player['userRole']; ?></td>
            <td class="col3"><a href="mailto:<?php echo $player['email']; ?>" title="Send an email to this player"><?php echo $player['email']; ?></a></td>
            <td class="col4">
              
              <div class="actionsContainer">
                <a href="#" title="Player actions" class="actionsLink">Actions</a>
                <div class="menu" style="display:none">
                    <ul>
                        <li><a href="playerAdmin.php?playerID=<?php echo $player['playerID']; ?>" title="Modify this player">Edit</a></li>
                        <li><a href="#" class="undeletePlayerLink" title="Undelete this player">Undelete</a></li>
                        <li><a href="#" class="purgePlayerLink" title="Permanently delete this player">Purge</a></li>
                    </ul>
                </div>
              </div><!--.actionsContainer-->
            
            </td>
          </tr>
          
          <?php 
                $rowIndex++;
            } // end loop through players
            ?>
      </tbody>
      </table>
      
      <?php
		} else {
			echo '<p class="noResults">There are currently no deleted players.</p>';	
		}
	  ?>
          
      <!-- END OF PLAYERS TABLE -->
          
      <!--******************************************
        DELETED RACES
        ****************************************** -->
    
    <h3>Deleted Races</h3>
	
	<?php
	  $raceObj = new Race();
	  $races = $raceObj->getDeletedRaces();
	  if ($races->num_rows > 0) {
	?>
    
    <table id="deletedRaceList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="col1">Name</th>
                <th class="col2"></th>
            </tr>
        </thead>
        <tbody>
			<?php
				
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
                            <li><a href="#" class="undeleteRaceLink" title="Undelete this race">Undelete</a></li>
                            <li><a href="#" class="purgeRaceLink" title="Permanently delete this race">Purge</a></li>
                        </ul>
                    </div>
                  </div><!--.actionsContainer-->
                </td>
            </tr>
			
			  <?php 
			  		$rowIndex++;
				} // end loop through races
			  ?>

        </tbody>
    </table>
    <?php
	  } else {
		  echo '<p class="noResults">There are currently no deleted races.</p>';	
	  }
	?>
      
    <!-- END OF RACES TABLE -->
    
    <!--******************************************
        DELETED SKILLS
        ****************************************** -->
        
      <h3>Deleted Skills</h3>
      
      <?php
		$skillObj = new Skill();
		$skills = $skillObj->getDeletedSkills();
		if ($skills->num_rows > 0) {
	  ?>
    
      <table id="deletedSkillList" class="sortName" cellpadding="5" cellspacing="0">
          <thead>
              <tr class="even"> 
                  <th class="col1">Name</th>
                  <th class="col2">Cost</th>
                  <th class="col3">Stackable?</th>
                  <th class="col4">Header</th>
                  <th class="col5">&nbsp;</th>
              </tr>
          </thead>
          <tbody>
              <?php
                  
                  $rowIndex = 1;
                  while ($skill = $skills->fetch_assoc()) { // Loop through retrieved countries
                      if ($rowIndex % 2 == 0) {
                          $rowClass = 'even';
                      } else {
                          $rowClass = 'odd';
                      }
              
              ?>

            <tr class="<?php echo $rowClass; ?>">
              <td class="col1"><a href="skillAdmin.php?skillID=<?php echo $skill['skillID']; ?>"><?php echo $skill['skillName']; ?></a>
                <input type="hidden" name="skillID[]" id="skillID_<?php echo $skill['skillID']; ?>" value="<?php echo $skill['skillID']; ?>" />
              </td>
              <td class="col2"><?php echo $skill['skillCost']; ?> CP</td>
              <td class="col3">
                  <?php 
                      if ($skill['maxQuantity'] > 1) {
                          echo 'Yes';
                      } else {
                          echo 'No';
                      }
                  ?>
              </td>
              <td class="col4"><?php echo $skill['headerName']; ?></td>
              <td class="col5">
                
                <div class="actionsContainer">
                  <a href="#" title="Skill actions" class="actionsLink">Actions</a>
                  <div class="menu" style="display:none">
                      <ul>
                          <li><a href="skillAdmin.php?skillID=<?php echo $skill['skillID']; ?>" title="Modify this skill">Edit</a></li>
                          <li><a href="#" class="undeleteSkillLink" title="Undelete this skill">Undelete</a></li>
                          <li><a href="#" class="purgeSkillLink" title="Permanently delete this skill">Purge</a></li>
                      </ul>
                  </div>
                </div><!--.actionsContainer-->
              
              </td>
            </tr>
            
            <?php 
                  $rowIndex++;
              } // end loop through skills
            ?>

            </tbody>
          </table>
          <?php
			} else {
			  echo '<p class="noResults">There are currently no deleted skills.</p>';	
			}
		  ?>

    <!-- END OF SKILLS TABLE -->
    
    <!--******************************************
        DELETED SPELLS
        ****************************************** -->
        
      <h3>Deleted Spells</h3>
      
	  <?php
		$spellObj = new Spell();
		$spells = $spellObj->getDeletedSpells();
		
		if ($spells->num_rows > 0) {
	  ?>
      
      <table id="deletedSpellList" class="sortName" cellpadding="5" cellspacing="0">
          <thead>
              <tr> 
                  <th class="col1">Name</th>
                  <th class="col2">Associated Skill</th>
                  <th class="col3">Cost</th>
                  <th class="col4">&nbsp;</th>
              </tr>
          </thead>
          <tbody>
              <?php
                  
                  $rowIndex = 1;
                  while ($spell = $spells->fetch_assoc()) { // Loop through retrieved countries
                      if ($rowIndex % 2 == 0) {
                          $rowClass = 'even';
                      } else {
                          $rowClass = 'odd';
                      }
              ?>

            <tr class="<?php echo $rowClass; ?>">
              <td class="col1"><a href="spellAdmin.php?spellID=<?php echo $spell['spellID']; ?>"><?php echo $spell['spellName']; ?></a>
              <input type="hidden" name="spellID[]" id="spellID_<?php echo $spell['spellID']; ?>" value="<?php echo $spell['spellID']; ?>" />
              </td>
              <td class="col2"><?php echo $spell['skillName']; ?></td>
              <td class="col3"><?php echo $spell['spellCost']; ?> CP</td>
              <td class="col4">
              	
                <div class="actionsContainer">
                  <a href="#" title="Spell actions" class="actionsLink">Actions</a>
                  <div class="menu" style="display:none">
                      <ul>
                          <li><a href="spellAdmin.php?spellID=<?php echo $spell['spellID']; ?>" title="Modify this spell">Edit</a></li>
                          <li><a href="#" class="undeleteSpellLink" title="Undelete this spell">Undelete</a></li>
                          <li><a href="#" class="purgeSpellLink" title="Permanently delete this spell">Purge</a></li>
                      </ul>
                  </div>
                </div><!--.actionsContainer-->
              
              </td>
            </tr>
            <?php 
                  $rowIndex++;
              } // end loop through spells
            ?>

          </tbody>
      </table>
      <?php
		} else {
		  echo '<p class="noResults">There are currently no deleted spells.</p>';	
		}
	  ?>
    <!--  END OF SPELLS TABLE -->
    
    <!--******************************************
        DELETED TRAITS
        ****************************************** -->
        
    <h3>Deleted Traits</h3>
    
    <?php
	  $traitObj = new charTrait();
	  $traits = $traitObj->getDeletedTraits();
	  if ($traits->num_rows > 0) {
	?>
    
    <table id="deletedTraitList" class="sortName" cellpadding="5" cellspacing="0">
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
				
				$rowIndex = 1;
				while ($trait = $traits->fetch_assoc()) { // Loop through retrieved traits
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
                            <li><a href="traitAdmin.php?traitID=<?php echo $trait['traitID']; ?>" title="Modify this trait">Edit</a></li>
                            <li><a href="#" class="undeleteTraitLink" title="Undelete this trait">Undelete</a></li>
                            <li><a href="#" class="purgeTraitLink" title="Permanently delete this trait" class="purgeLink">Purge</a></li>
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
    <?php
	  } else {
		echo '<p class="noResults">There are currently no deleted traits.</p>';	
	  }
	?>
      
    <!-- END OF TRAITS TABLE -->
    
    <!--******************************************
        DELETED CP
        ****************************************** -->
        
    <h3>Deleted CP</h3>
    
    <?php
	  $cpObj = new CP();
	  $cp = $cpObj->getDeletedCP();
	  if ($cp->num_rows > 0) {
	?>
    
    <table id="deletedCPList" class="sortName" cellpadding="5" cellspacing="0">
        <thead>
            <tr> 
                <th class="dateCol">Date</th>
                <th class="charCol">Character</th>
                <th class="playerCol">Player</th>
                <th class="numCol">Num</th>
                <th class="staffCol">Staff</th>
                <th class="catCol">Category</th>
                <th class="actionCol"></th>
            </tr>
        </thead>
        <tbody>
<?php        
    $rowIndex = 1;
	while ($cpRow = $cp->fetch_assoc()) { // Loop through retrieved CP records
		if ($rowIndex % 2 == 0) {
			$rowClass = 'even';
		} else {
			$rowClass = 'odd';
		}
		
		// Format date
		$dateString = strtotime($cpRow['CPDateStamp']);
		$displayDate = date('n/j/Y', $dateString);

?>
        <tr class="<?php echo $rowClass; ?>"> 
          <td class="dateCol"><a href="cpAdmin.php?CPTrackID=<?php echo $cpRow['CPTrackID']; ?>" title="<?php echo $cpRow['CPNote']; ?>"><?php echo $displayDate; ?></a>
            <input type="hidden" name="CPTrackID[]" id="CPTrackID_<?php echo $cpRow['CPTrackID']; ?>" value="<?php echo $cpRow['CPTrackID']; ?>" />
    
          </td>
          <td class="charCol"><?php echo $cpRow['charName']; ?></td>
          <td class="playerCol"><?php echo $cpRow['firstName'] . ' ' . $cpRow['lastName']; ?></td>
          <td class="numCol"><?php echo $cpRow['numberCP']; ?></td>
          <td class="staffCol"><?php echo $cpRow['staffMember']; ?></td>
          <td class="catCol"><?php echo $cpRow['CPCatName']; ?></td>
          <td class="actionCol">
          	
            <div class="actionsContainer">
              <a href="#" title="CP actions" class="actionsLink">Actions</a>
              <div class="menu" style="display:none">
                  <ul>
                      <li><a href="cpAdmin.php?CPTrackID=<?php echo $cpRow['CPTrackID']; ?>" title="Modify this CP Record">Edit</a></li>
                      <li><a href="#" class="undeleteCPLink" title="Undelete this CP record">Undelete</a></li>
                      <li><a href="#" class="purgeCPLink" title="Permanently delete this CP record" class="purgeLink">Purge</a></li>
                  </ul>
              </div>
            </div><!--.actionsContainer-->
            
        </tr>
        
    <?php 
        $rowIndex++;
    	} // end loop through CP records
	
	?>
        </tbody>
    </table>
    <?php
	  } else {
		echo '<p class="noResults">There are currently no deleted CP records.</p>';	
	  }
	?>
      
    <!-- END OF CP TABLE -->
    
    
  </div><!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<!--Contents of all of these dialogs will be populated by AJAX calls-->
<div id="purgeDialog" class="purgeDialog" style="display:none"></div>

<div id="charPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="communityPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="featPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="headerPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="playerPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="racePurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="skillPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="spellPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="traitPurgeDialog" class="purgeDialog" style="display:none"></div>

<div id="cpPurgeDialog" class="purgeDialog" style="display:none"></div>

<?php include('../includes/footer.php'); ?>

</body>
</html>
