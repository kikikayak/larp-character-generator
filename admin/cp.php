<?php

	/**************************************************************
	NAME: 	cp.php
	NOTES: 	Main page of cp section. 
	**************************************************************/
	
	$pageAccessLevel = 'Staff';
	$navClass = 'cp';
	$scriptLink = 'cp.js';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	// Get data for populating filter dropdowns
	$catObj = new CP();
	$categories = $catObj->getCPCategories();
	
	// Initialize filters
	// Figure out dates
	$today = date('n/j/Y');
  $tomorrow = date('n/j/Y', strtotime("$today + 1 day"));
	// echo date("Y-m-j", strtotime("1998-08-14 -3 days"));
	$oneMonthAgo = date('n/j/Y', strtotime("$today - 1 month"));
	
	$html['fromDate'] = isset($_SESSION['cpFilters']['fromDate']) ? htmlentities($_SESSION['cpFilters']['fromDate']) : $oneMonthAgo;
	$html['toDate'] = isset($_SESSION['cpFilters']['toDate']) ? htmlentities($_SESSION['cpFilters']['toDate']) : $tomorrow;
	$html['charName'] = isset($_SESSION['cpFilters']['charName']) ? htmlentities($_SESSION['cpFilters']['charName']) : '';
	$html['playerName'] = isset($_SESSION['cpFilters']['playerName']) ? htmlentities($_SESSION['cpFilters']['playerName']) : '';
	$html['CPCatID'] = isset($_SESSION['cpFilters']['CPCatID']) ? htmlentities($_SESSION['cpFilters']['CPCatID']) : '';
	$html['staffMember'] = isset($_SESSION['cpFilters']['staffMember']) ? htmlentities($_SESSION['cpFilters']['staffMember']) : '';
	$html['CPNote'] = isset($_SESSION['cpFilters']['CPNote']) ? htmlentities($_SESSION['cpFilters']['CPNote']) : '';

	// Initialize tab and filter display
	if (isset($_SESSION['cpFilterExpanded']) && $_SESSION['cpFilterExpanded'] == 'Yes') {
	  $cpFiltersClass = 'expanded';
	  $cpFiltersDisplay = 'display: block';
	} else {
	  $cpFiltersClass = 'contracted';
	  $cpFiltersDisplay = 'display: none';
	}
	
	if (isset($_SESSION['selectedCPTab'])) {
	  $tabName = $_SESSION['selectedCPTab'];
	} else {
	  $tabName = 'showAll';  
	}
	
	$title = 'CP | ' . $_SESSION['campaignName'] . ' Character Generator';
	
	include('../includes/header_admin.php');

?>

<body id="cpPage">
    
	<?php include('../includes/adminNav.php'); ?>
	
    <div id="content" class="oneCol">

	<!-- ************************ UTILITY CONTENT *********************** -->
    
    <div id="warning"></div>
  
  <div id="main">
	
    <div id="msg">
	  <?php cg_showUIMessage(); ?>
    </div>
	
  	<h2>CP Records</h2>

    <div class="toolbar">
      <a href="#" class="addLink">Add CP</a>
      <br class="clear" />
    </div><!--.toolbar-->
    <!--******************************************
        LIST OF CP RECORDS
        ****************************************** -->
    
    <div id="cpListContainer" class="tabbedTable <?php echo $tabName; ?>">
        <a href="#" id="showAll">All</a>
        <a href="#" id="showCharacter">Character CP</a>
        <a href="#" id="showPlayer">Player CP</a>
        <br class="clear" />
    </div>
    <div id="cpListFilters" class="filters <?php echo $cpFiltersClass; ?>">
      <h3><a href="#" id="cpFiltersExpand" class="filtersExpandContract">Filter CP Records</a></h3>
      <div id="filterContainer" style="<?php echo $cpFiltersDisplay; ?>">
        
        <div id="cpFilterRow1" class="row">
          <div class="cell1">
            <p class="lbl">Added Between</p>
            <p class="data">
                <input type="text" id="fromDate" name="fromDate" value="<?php echo $html['fromDate']; ?>" class="m" />
            </p>
            <br class="clear" />
          </div><!--.cell1-->
          <div class="cell2">
            <p class="lbl">and</p>
            <p class="data">
                <input type="text" id="toDate" name="toDate" value="<?php echo $html['toDate']; ?>" class="m" />
            </p>
            <br class="clear" />
          </div><!--.cell2-->
          <br class="clear" />
        </div><!--#cpFilterRow2-->
        
        <div id="cpFilterRow2" class="row">
          <div class="cell1">
            <p class="lbl">Character</p>
            <p class="data">
                <input type="text" id="charName" name="charName" class="l autocomplete" value="<?php echo $html['charName']; ?>" />
            </p>
            <br class="clear" />
          </div><!--.cell1-->
          <br class="clear" />
        </div><!--#cpFilterRow2-->
        
        <div id="cpFilterRow3" class="row">
          <div class="cell1">
            <p class="lbl">Player</p>
            <p class="data">
                <input type="text" id="playerName" name="playerName" class="l autocomplete" value="<?php echo $html['playerName']; ?>" />
            </p>
            <br class="clear" />
          </div><!--.cell1-->
          <br class="clear" />
        </div><!--#cpFilterRow3-->
                        
        <div id="cpFilterRow4" class="row">
            <div class="cell1">
              <p class="lbl">Category</p>
              <p class="data">
                  <select id="CPCatID" name="CPCatID">
                      <option value="">All</option>
                    <?php
                      while ($catRow = $categories->fetch_assoc()) { // Loop through retrieved players
                    ?>
                    <option value="<?php echo $catRow['CPCatID']; ?>" <?php if ($html['CPCatID'] == $catRow['CPCatID']) echo 'selected="selected"'; ?>><?php echo $catRow['CPCatName']; ?></option>
                    <?php
                      } // end categories loop
                    ?>
                  </select>
              </p>
              <br class="clear" />
            </div><!--.cell1-->
            <div class="cell2">
              <p class="lbl">Staff Member</p>
              <p class="data">
                  <input type="text" id="staffMember" name="staffMember" class="autocomplete m4" value="<?php echo $html['staffMember']; ?>" />
              </p>
              <br class="clear" />
            </div><!--.cell2-->
            <br class="clear" />
        </div><!--/cpFilterRow4-->
        
        <div id="cpFilterRow5" class="row">
            <div class="cell1">
              <p class="lbl">Note</p>
              <p class="data">
                  <input type="text" id="CPNote" name="CPNote" class="l" value="<?php echo $html['CPNote']; ?>" />
              </p>
              <br class="clear" />
            </div><!--.cell1-->
            <br class="clear" />
        </div><!--/cpFilterRow5-->
        
        <div class="btnArea">
            <input type="submit" name="cpFiltersBtn" id="cpFiltersBtn" value="Filter" class="btn-primary short" />
            <a href="#" class="clearFilters">clear filters</a>
            <br class="clear" />
        </div>
      </div><!--#filtersContainer-->
  </div><!--/cpListFilters-->
    <table id="cpList" class="sortName" cellpadding="5" cellspacing="0">
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
            <tr class="odd">
              <td colspan="7" class="loading">
                <img src="styles/images/spinner.gif" height="32" width="32" alt="Loading..." />
                <p>Loading table contents...</p>
              </td>
            </tr>
        </tbody>
    </table>
    <!-- ********************************************************
        END OF CP TABLE
        ******************************************************* -->
  </div><!--end of main div-->
  <br class="clear" />
</div><!--end of content div-->

<div id="cpAddDialog" class="addDialog" style="display:none"></div>
<div id="cpDeleteDialog" class="deleteDialog" style="display:none"></div>

<?php
	include('../includes/footer.php'); 
?>
