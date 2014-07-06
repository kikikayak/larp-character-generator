<?php

	/**************************************************************
	NAME: 	characters.php
	NOTES: 	Main page of characters section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'characters';
	$scriptLink = 'characters.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Get data for populating filter dropdowns
	$countryObj = new Country();
	$countries = $countryObj->getAllCountries();
	
	$communityObj = new Community();
	$communities = $communityObj->getAllCommunities();
	
	$raceObj = new Race();
	$races = $raceObj->getAllRaces();
	
	$headerObj = new Header();
	$headers = $headerObj->getAllHeaders();

	$skillObj = new Skill();
	$skills = $skillObj->getAllSkills();
	
	$spellObj = new Spell();
	$spells = $spellObj->getAllSpells();
	
	$traitObj = new charTrait();
	$traits = $traitObj->getAllTraits();

	$featObj = new Feat();
	$feats = $featObj->getAllFeats();
	
	$title = 'Character Administration | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	// Initialize filters
	$html['charType'] = isset($_SESSION['charFilters']['charType']) ? htmlentities($_SESSION['charFilters']['charType']) : '';
	$html['charName'] = isset($_SESSION['charFilters']['charName']) ? htmlentities($_SESSION['charFilters']['charName']) : '';
	$html['countryID'] = isset($_SESSION['charFilters']['countryID']) ? htmlentities($_SESSION['charFilters']['countryID']) : '';	
	$html['communityID'] = isset($_SESSION['charFilters']['communityID']) ? htmlentities($_SESSION['charFilters']['communityID']) : '';
	$html['raceID'] = isset($_SESSION['charFilters']['raceID']) ? htmlentities($_SESSION['charFilters']['raceID']) : '';
	$html['headerName'] = isset($_SESSION['charFilters']['headerName']) ? htmlentities($_SESSION['charFilters']['headerName']) : '';
  $html['headerID'] = isset($_SESSION['charFilters']['headerID']) ? htmlentities($_SESSION['charFilters']['headerID']) : '';
	$html['skillName'] = isset($_SESSION['charFilters']['skillName']) ? htmlentities($_SESSION['charFilters']['skillName']) : '';
  $html['skillID'] = isset($_SESSION['charFilters']['skillID']) ? htmlentities($_SESSION['charFilters']['skillID']) : '';
	$html['spellName'] = isset($_SESSION['charFilters']['spellName']) ? htmlentities($_SESSION['charFilters']['spellName']) : '';
  $html['spellID'] = isset($_SESSION['charFilters']['spellID']) ? htmlentities($_SESSION['charFilters']['spellID']) : '';
	$html['traitID'] = isset($_SESSION['charFilters']['traitID']) ? htmlentities($_SESSION['charFilters']['traitID']) : '';
	$html['featName'] = isset($_SESSION['charFilters']['featName']) ? htmlentities($_SESSION['charFilters']['featName']) : '';
	$html['playerName'] = isset($_SESSION['charFilters']['playerName']) ? htmlentities($_SESSION['charFilters']['playerName']) : '';
	
	// Initialize tab and filter display
	if (isset($_SESSION['charFilterExpanded']) && $_SESSION['charFilterExpanded'] == 'Yes') {
	  $charFiltersClass = 'expanded';
	  $charFiltersDisplay = 'display: block';
	} else {
	  $charFiltersClass = 'contracted';
	  $charFiltersDisplay = 'display: none';
	}
	
	if (isset($_SESSION['selectedCharTab'])) {
	  $tabName = $_SESSION['selectedCharTab'];
	} else {
	  $tabName = 'showAll';  
	}
	
	include('../includes/header_admin.php');

?>	

<body>

    <?php include('../includes/adminNav.php'); ?>
    
    <div id="content" class="oneCol">
        <div id="main">
			
            <div id="msg">
            	<?php cg_showUIMessage(); ?>
            </div>
            
            <h2>Character Administration</h2>

            <a href="charAdmin.php" title="" class="addLink">Add Character</a>
            
            <form name="charCardForm" id="charCardForm" action="charCardExport.php" method="post">
            
              <div id="toolbar" style="margin-bottom: 20px;">
                <input type="submit" name="exportCardBtn" id="exportCardBtn" value="Export Selected Character Cards" />
                <br class="clear" />
              </div><!--#toolbar-->
              
            <!--******************************************
            	LIST OF CHARACTERS
                ****************************************** -->
			<div id="charListContainer" class="tabbedTable <?php echo $tabName; ?>">
				<a href="#" id="showAll">View All</a>
				<a href="#" id="showPCs">PCs Only</a>
				<a href="#" id="showNPCs">NPCs Only</a>
                <br class="clear" />
			</div>
            <div id="charListFilters" class="filters <?php echo $charFiltersClass; ?>">
            	
                <h3><a href="#" id="charFiltersExpand" class="filtersExpandContract">Filter Characters</a></h3>
                <div id="filterContainer" style="<?php echo $charFiltersDisplay; ?>">
                  <div id="charFilterRow1" class="row">
                      <div class="cell1">
                        <p class="lbl">Name</p>
                        <p class="data"><input type="text" name="charName" id="charName" value="<?php echo $html['charName']; ?>" class="l autocomplete"/></p>
                        <br class="clear" />
                    </div><!--.cell1-->
                      <br class="clear" />
                  </div><!--#charFilterRow1-->
                  
                  <div id="charFilterRow2" class="row">
                    <div class="cell1">
                      <p class="lbl">Country</p>
                      <p class="data">
                          <select id="countryID" name="countryID">
                              <option value="">All</option>
							  <?php
							  	while ($countryRow = $countries->fetch_assoc()) { // Loop through retrieved countries
							  ?>
                              <option value="<?php echo $countryRow['countryID']; ?>" <?php if ($html['countryID'] == $countryRow['countryID']) echo 'selected="selected"'; ?>><?php echo $countryRow['countryName']; ?></option>
                              <?php
								} // end countries loop
							  ?>
                          </select>
                      </p>
                      <br class="clear" />
                    </div><!--.cell1-->
                    <div class="cell2">
                      <p class="lbl"><?php echo $_SESSION['communityLabel']; ?></p>
                      <p class="data">
                          <select id="communityID" name="communityID">
                              <option value="">All</option>
                              <?php
							  	while ($communityRow = $communities->fetch_assoc()) { // Loop through retrieved communities
							  ?>
                              <option value="<?php echo $communityRow['communityID']; ?>" <?php if ($html['communityID'] == $communityRow['communityID']) echo 'selected="selected"'; ?>><?php echo $communityRow['communityName']; ?></option>
                              <?php
								} // end communities loop
							  ?>
                          </select>
                      </p>
                      <br class="clear" />
                    </div><!--.cell2-->
                    <br class="clear" />
                  </div><!--#charFilterRow2-->
                  
                  
                  <div id="charFilterRow3" class="row" 
				  <?php
				  	if ($_SESSION['useRaces'] == 'No') {
				  ?>
                  	style="display: none"
                    <?php } ?>
                  >
                    <div class="cell1">
                      <p class="lbl">Race</p>
                      <p class="data">
                          <select id="raceID" name="raceID">
                              <option value="">All</option>
							  <?php
							  	while ($raceRow = $races->fetch_assoc()) { // Loop through retrieved countries
							  ?>
                              <option value="<?php echo $raceRow['raceID']; ?>" <?php if ($html['raceID'] == $raceRow['raceID']) echo 'selected="selected"'; ?>><?php echo $raceRow['raceName']; ?></option>
                              <?php
								} // end races loop
							  ?>
                          </select>
                      </p>
                      <br class="clear" />
                    </div><!--.cell1-->
                    <br class="clear" />
                  </div><!--#charFilterRow3-->
                  
                  <div id="charFilterRow4" class="row">
                    <div class="cell1">
                      <p class="lbl">Header</p>
                      <p class="data">
                          <input type="text" name="headerName" id="headerName" value="<?php echo $html['headerName']; ?>" class="m2 autocomplete" />
                          
                          <select id="headerID2" name="headerID2" style="display:none">
                              <option value="">All</option>
							  <?php
							  	while ($headerRow = $headers->fetch_assoc()) { // Loop through retrieved headers
							  ?>
                              <option value="<?php echo $headerRow['headerID']; ?>" <?php if ($html['headerID'] == $headerRow['headerID']) echo 'selected="selected"'; ?>><?php echo $headerRow['headerName']; ?></option>
                              <?php
								} // end headers loop
							  ?>
                          </select>
                      </p>
                      <br class="clear" />
                    </div>
                    <div class="cell2">
                      <p class="lbl">Skill</p>
                      <p class="data">
                          <input type="text" name="skillName" id="skillName" value="<?php echo $html['skillName']; ?>" class="m3 autocomplete" />
                          
                          <select id="skillID2" name="skillID2" style="display: none">
                              <option value="">All</option>
							  <?php
							  	while ($skillRow = $skills->fetch_assoc()) { // Loop through retrieved skills
							  ?>
                              <option value="<?php echo $skillRow['skillID']; ?>" <?php if ($html['skillID'] == $skillRow['skillID']) echo 'selected="selected"'; ?>><?php echo $skillRow['skillName']; ?></option>
                              <?php
								} // end skills loop
							  ?>
                          </select>
                      </p>
                      <br class="clear" />
                    </div>
                    <br class="clear" />
                  </div><!--/charFilterRow4-->

                  <div id="charFilterRow5" class="row">
                    <div class="cell1">
                      <p class="lbl">Spell</p>
                      <p class="data">
                          <input type="text" name="spellName" id="spellName" value="<?php echo $html['spellName']; ?>" class="m2 autocomplete" />
                          
                          <select id="spellID2" name="spellID2" style="display:none">
                              <option value="">All</option>
							  <?php
							  	while ($spellRow = $spells->fetch_assoc()) { // Loop through retrieved spells
							  ?>
                              <option value="<?php echo $spellRow['spellID']; ?>" <?php if ($html['spellID'] == $spellRow['spellID']) echo 'selected="selected"'; ?>><?php echo $spellRow['spellName']; ?></option>
                              <?php
								} // end spells loop
							  ?>
                          </select>
                      </p>
                      <br class="clear" />
                    </div><!--.cell1-->
                    <div class="cell2">
                      <p class="lbl">Trait</p>
                      <p class="data">
                          <select id="traitID" name="traitID">
                              <option value="">All</option>
							  <?php
							  	while ($traitRow = $traits->fetch_assoc()) { // Loop through retrieved traits
							  ?>
                              <option value="<?php echo $traitRow['traitID']; ?>" <?php if ($html['traitID'] == $traitRow['traitID']) echo 'selected="selected"'; ?>><?php echo $traitRow['traitName']; ?></option>
                              <?php
								} // end traits loop
							  ?>
                          </select>
                      </p>
                      <br class="clear" />
                    </div><!--.cell2-->
                    <br class="clear" />
                  </div><!--#charFilterRow5-->

				<div id="charFilterRow6" class="row">
                      <div class="cell1">
                        <p class="lbl">Feat</p>
                        <p class="data">
                            <input type="text" id="featName" name="featName" class="l autocomplete" value="<?php echo $html['featName']; ?>" />
                        </p>
                        <br class="clear" />
                      </div>
                      <br class="clear" />
                  </div><!--/charFilterRow6-->
                  
                  <div id="charFilterRow7" class="row">
                      <div class="cell1">
                        <p class="lbl">Player</p>
                        <p class="data">
                            <input type="text" id="playerName" name="playerName" class="l autocomplete" value="<?php echo $html['playerName']; ?>" />
                        </p>
                        <br class="clear" />
                      </div>
                      <br class="clear" />
                  </div><!--/charFilterRow7-->
                  
                  <div class="btnArea">
                    <input type="submit" name="charFiltersBtn" id="charFiltersBtn" value="Filter" class="btn-primary short" />
                    <a href="#" class="clearFilters">clear filters</a>
                    <br class="clear" />
                  </div>
                </div><!--#filtersContainer-->
            </div><!--/charListFilters-->
             
            <!--Contents of table will be populated by AJAX call-->  
			<table id="charList" class="sortName" cellpadding="5" cellspacing="0">
			<thead>
				<tr>
				  <th class="chkboxCol"><!--<input type="checkbox" id="selectAll" name="selectAll" />--></th>
				  <th class="charNameCol">Character</th>
				  <th class="playerNameCol">Player</th>
				  <th class="typeCol">Type</th>
          <th class="totalCPCol">Total CP</th>
          <th class="freeCPCol">Unspent CP</th>
				  <th class="actionsCol">&nbsp;</th>
				</tr>
			</thead>
			<tbody> 
            	<tr class="odd">
                  <td colspan="5" class="loading">
                  	<img src="styles/images/spinner.gif" height="32" width="32" alt="Loading..." />
                    <p>Loading table contents...</p>
                    </td>
                </tr>
			</tbody>
			</table>
          
          <!-- ********************************************************
          		END OF CHARACTERS TABLE
                ******************************************************* -->
                
          </form>
        </div> <!--end of main div-->
        
        <br class="clear" />
    </div><!--end of content div-->
    
    <div id="charDeleteDialog" class="deleteDialog" style="display:none">
    	<!--Contents to be populated by AJAX call--> 
    </div><!--#charDeleteDialog-->
    
    <div id="charDeathsDialog">
    	<!--Contents to be populated by AJAX call-->
    </div>
    
    <?php include('../includes/footer.php'); ?>
