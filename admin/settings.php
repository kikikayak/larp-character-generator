<?php

	/**************************************************************
	NAME: 	settings.php
	NOTES: 	This page allows staff members to edit the Generator settings. 
	**************************************************************/
	
	$pageAccessLevel = 'Admin';
	$navClass = 'settings';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	$_SESSION['UIMessage'] = ''; // Initialize blank
	$html = array(); // Initialize array to hold data for display
	
	$settingsObj = new Settings(); // Instantiate country object
	
	// If user has submitted page, process data
	if (isset($_POST['settingsSubmitted']) && $_POST['settingsSubmitted'] == 1) {
	
		if ($settingsObj->updateSettings($_POST)) {
			// Everything is good. Do not redirect. 	
		}
	}
	
	// Initialize values for populating UI
	$settingsDetails = $settingsObj->getSettings();
	while ($savedSettings = $settingsDetails->fetch_assoc()) {
		$html['baseCP'] = isset($_POST['baseCP']) ? htmlentities($_POST['baseCP']) : htmlentities($savedSettings['baseCP']);
		$html['baseAttribute'] = isset($_POST['baseAttribute']) ? htmlentities($_POST['baseAttribute']) : htmlentities($savedSettings['baseAttribute']);
		$html['useRaces'] = isset($_POST['useRaces']) ? htmlentities($_POST['useRaces']) : htmlentities($savedSettings['useRaces']);
        $html['autoGrantAccess'] = isset($_POST['autoGrantAccess']) ? htmlentities($_POST['autoGrantAccess']) : htmlentities($savedSettings['autoGrantAccess']);
		$html['communityLabel'] = isset($_POST['communityLabel']) ? htmlentities($_POST['communityLabel']) : htmlentities($savedSettings['communityLabel']);
		$html['communityLabelPlural'] = isset($_POST['communityLabelPlural']) ? htmlentities($_POST['communityLabelPlural']) : htmlentities($savedSettings['communityLabelPlural']);
		$html['attribute1Label'] = isset($_POST['attribute1Label']) ? htmlentities($_POST['attribute1Label']) : htmlentities($savedSettings['attribute1Label']);
		$html['attribute2Label'] = isset($_POST['attribute2Label']) ? htmlentities($_POST['attribute2Label']) : htmlentities($savedSettings['attribute2Label']);
		$html['attribute3Label'] = isset($_POST['attribute3Label']) ? htmlentities($_POST['attribute3Label']) : htmlentities($savedSettings['attribute3Label']);
		$html['attribute4Label'] = isset($_POST['attribute4Label']) ? htmlentities($_POST['attribute4Label']) : htmlentities($savedSettings['attribute4Label']);
		$html['attribute5Label'] = isset($_POST['attribute5Label']) ? htmlentities($_POST['attribute5Label']) : htmlentities($savedSettings['attribute5Label']);
		$html['vitalityLabel'] = isset($_POST['vitalityLabel']) ? htmlentities($_POST['vitalityLabel']) : htmlentities($savedSettings['vitalityLabel']);
		$html['campaignName'] = isset($_POST['campaignName']) ? htmlentities($_POST['campaignName']) : htmlentities($savedSettings['campaignName']);
		$html['contactName'] = isset($_POST['contactName']) ? htmlentities($_POST['contactName']) : htmlentities($savedSettings['contactName']);
		$html['contactEmail'] = isset($_POST['contactEmail']) ? htmlentities($_POST['contactEmail']) : htmlentities($savedSettings['contactEmail']);
		$html['webmasterName'] = isset($_POST['webmasterName']) ? htmlentities($_POST['webmasterName']) : htmlentities($savedSettings['webmasterName']);
		$html['webmasterEmail'] = isset($_POST['webmasterEmail']) ? htmlentities($_POST['webmasterEmail']) : htmlentities($savedSettings['webmasterEmail']);
		$html['paypalEmail'] = isset($_POST['paypalEmail']) ? htmlentities($_POST['paypalEmail']) : htmlentities($savedSettings['paypalEmail']);
		$html['generatorLocation'] = isset($_POST['generatorLocation']) ? htmlentities($_POST['generatorLocation']) : htmlentities($savedSettings['generatorLocation']);
	}
	
	$title = 'Settings | ' . $_SESSION['campaignName'] . ' Character Generator';
	$pageHeader = 'Settings';
	$btnLabel = 'Save';
	$scriptLink = 'settings.js';
		
	include('../includes/header_admin.php');

?>	
<body id="settingsAdminPage">

	<?php include('../includes/adminNav.php'); ?>

    <div id="content">
      <!--no sidebar-->
      
      <div id="help">
	  	
        <img id="helpArrow" src="styles/images/helpArrowAdmin.png" alt="" style="display:none" />
        
        <div id="campaignNameHelp" class="help" style="display: none">
            <div class="helpTop">
            	<a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This campaign name will be used in headers, emails, and site text. </p>
                <p>Enter the name of your campaign without any modifiers (e.g. "My Game," <strong>not</strong> "My Game Campaign" or "My Game Character Generator").</p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        
        <div id="baseCPHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This is the amount of CP that a new character will be automatically assigned. </p>
                <p>You can increase it from year to year, if desired. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="baseAttributeHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This is the starting value for attributes (e.g. Air, Void, etc). The player does not need to spend CP to achieve this value. </p>
                <p>All attributes must start at the same value. </p>
                <p>The player will not be able to decrease an attribute below this value. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="useRacesHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This setting determines whether or not this game has a concept of races. </p>
                <p>If you set this to "no," players will not see any race selection options in the character wizard.</p>
                <p>You will still be able to configure races in the Admin section, but players will not be able to use them. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="communityLabelHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>Use this field for religions, pirate crews, houses, or other in-game groups.</p>
                <p>Please provide both a single and a plural version for correct labelling.</p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="attributeLabelHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>Players use attributes to power their characters' skills. For instance, a player might need to spend 1 Fire each time her character casts a Lightning Bolt spell.</p>
                <p>The custom attribute name fields allow you to use your own labels for your game's attributes. Players will see the custom name when creating or updating a character in the character wizard.</p>
                <p>Leave these set to the default if your game uses the standard Air, Earth, Fire, Water, Void. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="vitalityLabelHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>Vitality controls how healthy a character is. When a character reaches 0 vitality, he or she will be incapacitated. </p>
                <p>This field allows you to use your own label for vitality. Players will see the custom name when creating or updating a character in the character wizard.</p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="contactHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This person will be listed as the general "information" contact in the Generator footer. </p>
                <p>The email address will also be used as the "reply to" for any Generator emails. </p>
                <p>Set this to a person who should receive general (non-technical) questions about the game. This is generally the campaign director or a designated staff contact person. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="webmasterHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This person will be listed as the "webmaster" contact in the Generator footer. </p>
                <p>Set this to a person who should receive technical questions about the Generator. This is generally the Generator administrator. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="paypalEmailHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This email address will be used to receive Paypal payments. </p>
                <p>Paypal event payments will be supported in a future release. The field is provided for forward compatibility. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
        <div id="generatorLocationHelp" class="help" style="display: none">
            <div class="helpTop">
              <a href="#" class="closeLink">X</a>
            </div>
            <div class="helpContent">
                <p>This URL is used in email and other communications. </p>
                <p>Set this to the full URL (including http://) for your game's Character Generator, e.g. "http://yourgame.com/generator"</p>
                <p>Do not include a trailing (final) slash. </p>
            </div>
            <div class="helpBottom"></div>
        </div>
        
      </div><!--end of help div-->
	  
      <div id="main" class="wide">
	  
		<div id="msg">
			<?php cg_showUIMessage(); ?>
        </div>
		
		<span class="reqFldIndicator">* Required Field</span>
	  
        <h2><?php echo $pageHeader; ?></h2>
        
		<form name="settingsAdmin" id="settingsAdmin" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			
			<div id="generalSettings" class="section">
              <h3>General</h3>
              
              <fieldset>
              
                <?php cg_createRow('campaignName'); ?>
                    <div class="cell">
                        <label for="campaignName">* Campaign name</label>
                        <input type="text" name="campaignName" id="campaignName" class="l" value="<?php echo $html['campaignName']; ?>" />
                        <?php cg_showError('campaignName'); ?>
                        <br class="clear" />
                    </div>
                </div>
                            
                <?php cg_createRow('baseCP'); ?>
                    <div class="cell">
                        <label for="baseCP">* Base starting CP</label>
                        <input type="text" name="baseCP" id="baseCP" class="s2" value="<?php echo $html['baseCP']; ?>" />
                        <?php cg_showError('baseCP'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
                <?php cg_createRow('baseAttribute'); ?>
                    <div class="cell">
                        <label for="baseAttribute">* Base attribute value</label>
                        <input type="text" name="baseAttribute" id="baseAttribute" class="s2" value="<?php echo $html['baseAttribute']; ?>" />
                        <?php cg_showError('baseAttribute'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
              
                <?php cg_createRow('useRaces'); ?>
                    <div class="cell">
                        <label for="useRaces">* Use races?</label>
                        <select name="useRaces" id="useRaces">
                          <option value="Yes" <?php if ($html['useRaces'] == 'Yes') echo 'selected="selected"'; ?>>Yes</option>
                          <option value="No" <?php if ($html['useRaces'] == 'No') echo 'selected="selected"'; ?>>No</option>
                        </select>
                        <?php cg_showError('useRaces'); ?>
                        <br class="clear" />
                    </div>
                </div>

                <?php cg_createRow('autoGrantAccess'); ?>
                    <div class="cell">
                        <label for="autoGrantAccess">* Automatically grant access?</label>
                        <select name="autoGrantAccess" id="autoGrantAccess">
                          <option value="1" <?php if ($html['autoGrantAccess'] == '1') echo 'selected="selected"'; ?>>Yes</option>
                          <option value="0" <?php if ($html['autoGrantAccess'] == '0') echo 'selected="selected"'; ?>>No</option>
                        </select>
                        <?php cg_showError('useRaces'); ?>
                        <br class="clear" />
                    </div>
                </div>

              </fieldset>
            </div><!--#generalSettings-->
            
            <div id="gameWorldSettings" class="section">
              <h3>Game World</h3>
              
              <fieldset>
                            
                <?php cg_createRow('communityLabel'); ?>
                    <div class="cell">
                        <label for="communityLabel">* Custom name for communities</label>
                        <input type="text" name="communityLabel" id="communityLabel" class="l" value="<?php echo $html['communityLabel']; ?>" />
                        <?php cg_showError('communityLabel'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
                <?php cg_createRow('communityLabelPlural'); ?>
                    <div class="cell">
                        <label for="communityLabelPlural">* Plural name for communities</label>
                        <input type="text" name="communityLabelPlural" id="communityLabelPlural" class="l" value="<?php echo $html['communityLabelPlural']; ?>" />
                        <?php cg_showError('communityLabelPlural'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
                <?php cg_createRow('attribute1Label'); ?>
                    <div class="cell">
                        <label for="attribute1Label">* Custom name for attribute 1</label>
                        <input type="text" name="attribute1Label" id="attribute1Label" class="l" value="<?php echo $html['attribute1Label']; ?>" />
                        <?php cg_showError('attribute1Label'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
                <?php cg_createRow('attribute2Label'); ?>
                    <div class="cell">
                        <label for="attribute2Label">* Custom name for attribute 2</label>
                        <input type="text" name="attribute2Label" id="attribute2Label" class="l" value="<?php echo $html['attribute2Label']; ?>" />
                        <?php cg_showError('attribute2Label'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
                <?php cg_createRow('attribute3Label'); ?>
                    <div class="cell">
                        <label for="attribute3Label">* Custom name for attribute 3</label>
                        <input type="text" name="attribute3Label" id="attribute3Label" class="l" value="<?php echo $html['attribute3Label']; ?>" />
                        <?php cg_showError('attribute3Label'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
                <?php cg_createRow('attribute4Label'); ?>
                    <div class="cell">
                        <label for="attribute4Label">* Custom name for attribute 4</label>
                        <input type="text" name="attribute4Label" id="attribute4Label" class="l" value="<?php echo $html['attribute4Label']; ?>" />
                        <?php cg_showError('attribute4Label'); ?>
                        <br class="clear" />
                    </div>
                </div>
                
                <?php cg_createRow('attribute5Label'); ?>
                    <div class="cell">
                        <label for="attribute5Label">* Custom name for attribute 5</label>
                        <input type="text" name="attribute5Label" id="attribute5Label" class="l" value="<?php echo $html['attribute5Label']; ?>" />
                        <?php cg_showError('attribute5Label'); ?>
                        <br class="clear" />
                    </div>
                </div>
              
              <?php cg_createRow('vitalityLabel'); ?>
                  <div class="cell">
                      <label for="vitalityLabel">* Custom name for vitality</label>
                      <input type="text" name="vitalityLabel" id="vitalityLabel" class="l" value="<?php echo $html['vitalityLabel']; ?>" />
                      <?php cg_showError('vitalityLabel'); ?>
                      <br class="clear" />
                  </div>
              </div>
            
            </fieldset>
            </div><!--#gameWorldSettings-->
                        
            <div id="contactInfoSettings" class="section">
              <h3>Contact Information</h3>
              
              <fieldset>
            
      			  <?php cg_createRow('contactName'); ?>
                  <div class="cell">
                      <label for="contactName">* Information contact</label>
                      <input type="text" name="contactName" id="contactName" class="l" value="<?php echo $html['contactName']; ?>" />
                      <?php cg_showError('contactName'); ?>
                      <br class="clear" />
                  </div>
              </div>
              
              <?php cg_createRow('contactEmail'); ?>
                  <div class="cell">
                      <label for="contactEmail">* Information email address</label>
                      <input type="text" name="contactEmail" id="contactEmail" class="l" value="<?php echo $html['contactEmail']; ?>" />
                      <?php cg_showError('contactEmail'); ?>
                      <br class="clear" />
                  </div>
              </div>
              
              <?php cg_createRow('webmasterName'); ?>
                  <div class="cell">
                      <label for="webmasterName">* Webmaster name</label>
                      <input type="text" name="webmasterName" id="webmasterName" class="l" value="<?php echo $html['webmasterName']; ?>" />
                      <?php cg_showError('webmasterName'); ?>
                      <br class="clear" />
                  </div>
              </div>
              
                 <?php cg_createRow('webmasterEmail'); ?>
                    <div class="cell">
                        <label for="webmasterEmail">* Webmaster email address</label>
                        <input type="text" name="webmasterEmail" id="webmasterEmail" class="l" value="<?php echo $html['webmasterEmail']; ?>" />
                        <?php cg_showError('webmasterEmail'); ?>
                      <br class="clear" />
                    </div>
                </div>
              
              <?php cg_createRow('paypalEmail'); ?>
                  <div class="cell">
                      <label for="paypalEmail">* Paypal payment email</label>
                      <input type="text" name="paypalEmail" id="paypalEmail" class="l" value="<?php echo $html['paypalEmail']; ?>" />
                      <?php cg_showError('paypalEmail'); ?>
                      <br class="clear" />
                  </div>
              </div>
            
            </fieldset>
            </div><!--#contactInfoSettings-->
            
            <div id="configSettings" class="section"> 
              <h3>Configuration</h3>
              
              <fieldset>
            
              <?php cg_createRow('generatorLocation'); ?>
                  <div class="cell">
                      <label for="generatorLocation">* Generator URL</label>
                      <input type="text" name="generatorLocation" id="generatorLocation" class="l" value="<?php echo $html['generatorLocation']; ?>" />
                      <?php cg_showError('generatorLocation'); ?>
                      <br class="clear" />
                  </div>
              </div>
              
              </fieldset>
            </div><!--#configSettings-->
            

			
			<div class="btnArea">
				<input type="hidden" id="settingsSubmitted" name="settingsSubmitted" value="1">
				<input type="submit" id="settingsSave" name="Save" value="<?php echo $btnLabel; ?>" class="btn-primary" />
				<br class="clear" />
			</div>
		
		</form>
        
      </div><!--end of main div-->
      <br class="clear" />
    </div><!--end of content div-->
	
	<?php 
		cg_clearUIMessage(); 
	
		include('../includes/footer.php'); 
	?>
