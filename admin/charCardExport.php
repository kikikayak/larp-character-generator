<?php

	/**************************************************************
	NAME: 	charCardExport.php
	NOTES: 	Displays details about a specific character.
	TO DO:	  
	**************************************************************/
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$title = "Character Details | " . $_SESSION['campaignName'] . " Character Generator";
	
	/* Export to MS Word */
	header("Content-Type: application/vnd.ms-word");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=charCards.doc");
	
	
	if (!isset($_POST['characterID'])) {
		session_write_close();
		header('Location: characters.php');
		exit;	
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<style type="text/css">

  body {
	  margin: 0;
	  padding: 0;
	  font-family: Verdana, Arial, Helvetica, sans-serif;
	  color: #333;
  }
  
  .page {
	  clear: both;
	  border: 1px dashed #F0F;
	  margin-bottom: 25px;  
  }
  
  .card {
	  padding: 15px;
	  border-right: 1px dashed #eee;
	  float: left;
	  width: 350px;
	  margin-bottom: 10px;
  }
  
  h2 {
	  margin-top: 20px;
	  font-family: "Trebuchet MS", Trebuchet, Tahoma, sans-serif;
	  font-size: 16pt;
  }
  
  h3 {
	  font-family: "Trebuchet MS", Trebuchet, Tahoma, sans-serif;
	  font-size: 12pt;
	  margin-bottom: 0;
  }
  
  h4 {
	  font-size: 11pt;
  }
  
  p, li {
	  font-size: 10pt;
	  margin: 0;
  }
  
  ul {
	  margin: 0;
	  padding: 0 0 0 25px;	
  }
  
  li {
	  list-style-type: none;
	  margin: 0;
	  padding: 0;
  }
  
  h2 .playerName {
	  font-size: 11pt;		
  }
  
  .clear {
	  clear: both;
  }
  
  td, th {
	  font-size: 10pt;
	  padding: 2px;
	  color: #333;
	  vertical-align: top;
  }
  
  th {
	  text-align: left;
	  text-align: right;
	  padding-right: 10px;
  }
  
  .even {
	  background-color: #eee;
  }
  
  .even th,
  .even td {
	  background-color: #eee;
  }
  
  .col1 {
	  width: 20%;
  }
  
  .col2 {
	  width: 20%;
  }
  
  .col3 {
	  width: 30%;
  }
  
  #basics {
	  margin-bottom: 20px;
  }
  
  #cardContainer {
	  width: 100%;
	  border-collapse: collapse;
  }
  
  #cardContainer td {
	  /* width: 40%; */
	  border: 1px dashed #0FF; 
	  padding: 15px 15px 30px 15px;
	  vertical-align: top;
  }
  
  #basicsTable {
	  border-collapse: collapse;
	  width: 100%;
	  border: 1px solid #ccc;
  }
  
  #basicsTable td {
	  border: 0px none;
	  padding: 0;  
  }
  
  .header {
	  padding-right: 10px;	
  }
  
  .header .empty {
	  margin-left: 25px;
  }
  
  .header .skills .empty {
	  margin-left: 0;
  }
  
  .skills {
	  margin-left: 25px;
  }
  
  .spells {
	  margin-left: 50px;
  }
  
  #printInstructions li,
  #printInstructions p {
	  color: #999;  
  }
  
  #printInstructions li {
	  list-style-type: circle;  
  }

</style>

<body id="charCardPage">

<div id="printInstructions">
  <p>NOTE: Before printing, we recommend the following: </p>
  <ol>
      <li>Switch to landscape mode</li>
      <li>Change margins to .5" all around</li>
      <li>Insert page breaks where necessary</li>
      <li>Check entire file</li>
      <li>Delete these instructions</li>
  </ol>
</div>

<!--Just to test...-->
<br clear="all" style="page-break-before:always" />
    
    <table id="cardContainer">
      <tbody>
      <tr>

	  <?php
		$counter = 1;
		foreach ($_POST['characterID'] as $charID) {
          $character = new Character();
          
          $charDetails = $character->getCharDetails($charID);
          $charHeaders = $character->getCharHeaders($charID);
          $charTraits = $character->getCharTraits($charID);
          
          $charTotalCP = $character->getTotalCharCP($charID);
          $charFreeCP = $character->getCharFreeCP($charID);
      
      ?>
      
        <td valign="top">
			
			<?php 
				// Insert a page break for odd-numbered records (which should always start a page)
				if ($counter % 2 != 0) {
					echo '<br clear="all" style="page-break-before:always" />';
				}
				
				while ($row = $charDetails->fetch_assoc()) {
			?>
			
			<h2>
			  <?php echo $row['charName']; ?><br />
              <span class="playerName">Player: <?php echo $row['firstName'] . ' ' . $row['lastName']; ?></span>
            </h2>
            
			<div id="basics">
				<table id="basicsTable" cellpadding="0" cellspacing="0">
					<tbody>
						<tr class="odd">
							<th class="col1"><?php echo $_SESSION['attribute1Label']; ?></th>
							<td class="col2"><?php echo $row['attribute1']; ?></td>
							<th class="col3">Native Country</th>
							<td class="col4"><?php echo $row['countryName']; ?></td>
						</tr>
						<tr class="even">
							<th class="col1"><?php echo $_SESSION['attribute2Label']; ?></th>
							<td class="col2"><?php echo $row['attribute2']; ?></td>
							<th class="col3"><?php echo $_SESSION['communityLabel']; ?></th>
							<td class="col4"><?php echo $row['communityName']; ?></td>
						</tr>
						<tr class="odd">
							<th class="col1"><?php echo $_SESSION['attribute3Label']; ?></th>
							<td class="col2"><?php echo $row['attribute3']; ?></td>
							<th class="col3">Age</th>
							<td class="col4"><?php echo $row['charAge']; ?></td>
						</tr>
						<tr class="even">
							<th class="col1"><?php echo $_SESSION['attribute4Label']; ?></th>
							<td class="col2"><?php echo $row['attribute4']; ?></td>
							<?php
								if ($_SESSION['useRaces'] == 'yes') {
							?>
								<th class="col3">Race</th>
								<td class="col4"><?php echo $row['raceName']; ?></td>
							<?php 
								} else {
							?>
								<th class="col3">&nbsp;</th>
								<td class="col4">&nbsp;</td>
							<?php
								} // end races condition
							?>
						</tr>
						<tr class="odd">
							<th class="col1"><?php echo $_SESSION['attribute5Label']; ?></th>
							<td class="col2"><?php echo $row['attribute5']; ?></td>
							<th class="col3">Total CP</th>
							<td class="col4"><?php echo $charTotalCP; ?></td>
						</tr>
						<tr class="even">
							<th class="col1"><?php echo $_SESSION['vitalityLabel']; ?></th>
							<td class="col2"><?php echo $row['vitality']; ?></td>
							<th class="col3">Free CP</th>
							<td class="col4"><?php echo $charFreeCP; ?></td>
						</tr>
					</tbody>	
				</table>
			</div><!--/basics-->
            
            <div id="summaryView">
                
                <?php 
                    while ($header = $charHeaders->fetch_assoc()) { // Loop through headers
                ?>
                <div class="header">
                    <h3><?php echo $header['headerName']; ?> Header</h3>
                    <div class="skills">
                        
                        <?php 
                            $headerSkills = $character->getCharSkillsByHeader($header['headerID'], $charID);
                            while ($skill = $headerSkills->fetch_assoc()) { // Loop through skills for this header
                        ?>
                        <p><?php echo $skill['skillName']; ?> <?php if ($skill['quantity'] > 1) echo 'x ' . $skill['quantity']; ?></p>
                        <div class="spells">
                            <?php
                                $skillSpells = $character->getCharSpellsBySkill($skill['skillID'], $charID);
                                while ($spell = $skillSpells->fetch_assoc()) { // Loop through spells for this skill
                            ?>
                            
                            <p><?php echo $spell['spellName']; ?></p>
                            <?php 
                                } // end spells loop
                            ?>
                        </div><!--/spells-->
                        
                        <?php
                            } // end skill loop
                            if ($headerSkills->num_rows == 0) {
                                echo '<p class="empty">No skills</p>';
                            }
                        ?>
                    </div><!--/skills-->
                </div><!--/header div-->
                
                <?php 
                    } // Close headers loop
                    if ($charHeaders->num_rows == 0) {
                        echo '<div class="header"><h3>Headers &amp; Skills</h3><p class="empty">None</p></div>';
                    }
                ?>
                
            </div><!--/summaryView-->
				
            <!--**************************************************
            	TRAITS
                **************************************************-->
            
            <div id="traitsView">
                <h3>Traits</h3>
                <ul>
                <?php 
                    while ($trait = $charTraits->fetch_assoc()) { // Loop through traits for this character
                ?>
                    <li><?php echo $trait['traitName']; ?></li>
                <?php
                    } // Close traits loop
					echo '</ul>';
                    if ($charTraits->num_rows == 0) {
                        echo '<p class="empty">None</p>';
                    }
                ?>
                
            </div><!--end of traits-->
			
			<?php 
				} // end of result loop
			?>
        
    </td>
    
	<?php
		if ($counter % 2 == 0) {
		  // end row and start again
		  echo '</tr>
				<tr>';	
		} else {
			// create spacer column
			echo '<td width="20px" style="width: 20px">&nbsp;</td>';	
		}
		$counter++;
	  } // end of card loop
	?>
    </tr>
    </tbody>
    </table>
    
</body>
</html>
