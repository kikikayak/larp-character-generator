/**********************************************************
NAME:	wizard.js
NOTES:	Contains all the scripts specific to the character creation wizard. 
		This file is used by both the main character wizard (wizard.php)
		and the admin wizard (charAdmin.php).
***********************************************************/

/************************************************
RUN ON PAGE LOAD
*************************************************/

function init() {
	checkNegativeCP(); // Display negative CP warning if necessary

	var doClose = function() {
		//close the dialog
		$("#aboutDialog").dialog("close");
	};

	var dialogOpts = {
		modal: true,
		title: "About the Character Generator",
		buttons: {
			"Close": doClose
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$("#aboutDialog").dialog(dialogOpts);

	//define click handler for the button
	$("#aboutLink").click(function() {
		$("#aboutDialog").dialog("open");
	});

	$('#playerID').change(function() {
		doOnChangePlayer();
	});

	if ($('#charType').is(':visible')) {
		$('#charType').chosen({disable_search_threshold: 10}).change(function() {
			// alert('charType changed!');
			changeRequiredDropdown('charType');
		});
	}

	$('#playerID').chosen({disable_search_threshold: 2});

	$('#countryID').chosen({disable_search_threshold: 10});

	$('#communityID').chosen({disable_search_threshold: 10});

	if ($('#raceID').is(":visible")) {
		$('#raceID').chosen({disable_search_threshold: 10});
	}

	$('#skills select').chosen({
		width: '50px',
		disable_search_threshold: 20
	});

	// $('.skillQtyFld').chosen({disable_search_threshold: 25, width: "50px"});
	// $('.skillQtyFld').trigger('liszt:updated');
/*
	$('#charType').change(function() {
		// alert('charType changed!');
		changeRequiredDropdown('charType');
	}); */

	loadSpells();
	loadFeats();
	runValidations();

}

function runValidations() {
	
	$('#charName').blur(function () {
		if (!isValidText($(this).val())) {
			showError($(this), "Contains invalid characters");
		} else {
			removeErrors($(this));
		}
	});

}


/************************************************
GENERAL UTILITY FUNCTIONS
*************************************************/

// Automatically move div down as user scrolls down
function scrollDiv(div) {
	var top = document.body.parentNode.scrollTop;
	$('#' + div).css({'top' : top + "px" });
}

/************************************************
VALIDATIONS
*************************************************/

// fldId: String id of attribute field
function validateAttribute(fldId, mode) {
	if (!mode) {
		mode = 'standard';
	}
	// alert('validateAttribute: ' + mode)

	fld = $('#' + fldId); // extend for jquery
	fldVal = fld.val();
	savedFldVal = $('#saved_' + fldId).val();
	if (parseInt(fldVal) < parseInt(savedFldVal)) {
		removeErrors(fld);
		showErrorAndFade(fld, 'Must be ' + savedFldVal + ' or greater');
		return false;
	} else if (!isInteger(fldVal)) {
		removeErrors(fld);
		showErrorAndFade(fld, 'Must be a whole number');
		return false;
	} else {
		removeErrors(fld);
		return true;
	}
}

/************************************************
ERROR HANDLING AND DISPLAY
*************************************************/

function displaySubmitWarning(msg) {
	if (!msg) {
		msg = "Please fix all errors before submitting.";
	}
	$('#submitWarning').show();
	$('#submitWarning').html(msg);
	$('#submit').prop('disabled', true);
	$('#submit').addClass('disabled');
}

function removeSubmitWarning() {
	$('#submitWarning').hide();
	$('#submit').prop('disabled', false);
	$('#submit').removeClass('disabled');
}

/************************************************
SHOW AND HIDE FUNCTIONS
*************************************************/

function expandContract(aLink, divName) {
	var header = $(aLink).closest('h3');
	if (header.hasClass('expanded')) {
		header.removeClass('expanded');
		header.addClass('contracted');
		$('#' + divName).hide();
	} else if (header.hasClass('contracted')) {
		header.removeClass('contracted');
		header.addClass('expanded');
		$('#' + divName).show();
	}
}

function showHideSkillsSection(chkbox, section, mode) {
	var imgPath = '../images/';
	chkbox = $(chkbox); // extend

	// Find the header's arrow image
	arrow = $(chkbox).closest('.row').find('.cell0 img');
	if (chkbox.is(':checked')) {
		$('#' + section).show();
		arrow.attr('src', imgPath + 'arrowDown.png');
	} else {
		$('#' + section).hide();
		arrow.attr('src', imgPath + 'arrowRight.png');
	}
}

function doOnclickHeaderArrow(arrow, section, mode) {
	var imgPath = '../images/';

	// find checkbox to send to show/hide function
	if ($('#' + section).is(':visible')) {
		$('#' + section).hide();
		$(arrow).attr('src', imgPath + 'arrowRight.png');
	} else {
		$('#' + section).show();
		$(arrow).attr('src', imgPath + 'arrowDown.png');
	}
}

/* 
TODO: Implementation of passing link is kludgy. Currently the link needs to have an id, which is undesirable since the links are dynamic. 
Figure out a better way to do this with "this" or jquery event handling? */
function showLongDescription(aLink) {
	aLink = $('#' + aLink); // extend
	var shortDescriptionDiv = aLink.closest('.description');
	var longDescriptionDiv = shortDescriptionDiv.nextAll('.longDescription');
	// shortDescriptionDiv.toggle();
	shortDescriptionDiv.find('.shortDescLink').hide();
	longDescriptionDiv.toggle();
}

function hideLongDescription(aLink) {
	aLink = $('#' + aLink); // extend
	var longDescriptionDiv = aLink.closest('.longDescription');
	var shortDescriptionDiv = longDescriptionDiv.prevAll('.description');
	// shortDescriptionDiv.toggle();
	shortDescriptionDiv.find('.shortDescLink').show();
	longDescriptionDiv.toggle();
}

// attribute: String attribute name, e.g. "attribute1" or "vitality"
function showAttributeUsage(attribute) {
	var ajaxPath = '../ajax/';
	var mode;
	if ($('#adminWizardPage').length > 0){
		// Admin version of wizard
		mode = 'adminWizard';
	} else {
		mode = 'wizard';
	}
	// Find position of attribute visualization
	attributeVis = $('#' + attribute + 'Vis');
	var curPosition = attributeVis.position();
	var curValue = $('#' + attribute + 'Display').html();
	
	var selectedSkillsArr = new Array();
	$('input.skillFld[type="checkbox"]:checked').each(function() {
		var skillID = $(this).attr('id').split('_')[1]; // Add current skill ID to an array
		selectedSkillsArr.push(skillID);
	});
	
	var selectedSpellsArr = new Array();
	$('input.spellFld[type="checkbox"]:checked').each(function() {
		var spellID = $(this).attr('id').split('_')[1]; // Find spellID and add it to the array
		selectedSpellsArr.push(spellID);
	});
	// alert('Skills: ' + selectedSkillsArr + '\r Spells: ' + selectedSpellsArr);
	
	var data = {attributeName: attribute, attributeValue: curValue, selectedSkills:selectedSkillsArr, selectedSpells:selectedSpellsArr}; // Create object/map of data to pass in AJAX call
	// Load appropriate spell spheres and their spells via AJAX call
	$('#attributeInfoContent').load(ajaxPath + 'wizard.handler.php?ajaxAction=getAttributeUsage&mode=' + mode, data, function() {
		$('#attributeInfo').fadeIn(500);
		$('#attributeInfo').css('top', curPosition.top - 20);
		/* $('#attributeInfo').css('left', curPosition.left + 100); */
		$('#attributeInfoArrow').fadeIn(350);
		$('#attributeInfoArrow').css('top', curPosition.top);
		/* $('#attributeInfoArrow').css('left', curPosition.left + 81); */
	});

}

function hideAttributeUsage(attribute) {
	$('#attributeInfoArrow').hide();
	$('#attributeInfo').hide();
}

function doOnChangePlayer() {
	var ajaxPath = '../ajax/';
	var mode = 'adminWizard';
	var playerID = $('#playerID').val();
	
	var data = {playerID: playerID}; // Create object/map of data to pass in AJAX call
	// Load appropriate spell spheres and their spells via AJAX call
	$('#origCPTotalVal').load(ajaxPath + 'wizard.handler.php?ajaxAction=getFreeCP&mode=' + mode, data, function() {
		var totalCP = $('#origCPTotalVal').html();
		$('#origCPTotal').val(totalCP);
		calcAllUsedCP();
	});

}

/****************************************************
CP CALCULATION AND SUMMARY UPDATE FUNCTIONS
****************************************************/

function changeCharName(fldId) {
	updateCharSummary();

	fld = $('#' + fldId); // extend for jquery
	fldVal = fld.val();
	if (isEmpty(fldVal)) {
		removeErrors(fld);
		showErrorAndFade(fld, 'Please enter a name');
	} else {
		removeErrors(fld);
	}
}

function changeRequiredDropdown(fldId) {
	fld = $('#' + fldId); // extend for jquery
	fldVal = fld.val();
	if (isEmpty(fldVal)) {
		removeErrors(fld);
		showErrorAndFade(fld, 'Please select a value');
		return false;
	} else {
		removeErrors(fld);
		return true;
	}
}

// attribute: string attribute label, e.g. 'attribute1'
// direction: string 'up' or 'down'
// mode: 'standard' or 'admin'
function incrementAttribute(attribute, direction, mode) {
	if (!mode) {
		mode = 'standard';
	}
	// alert('IncrementAttribute: ' + mode);
	// alert('incrementAttribute:' + attribute + ', ' + direction);
	var newVal = '';
	if (direction == 'up') {
		newVal = parseFloat($('#' + attribute).val()) + 1;
	} else if (direction == 'down') {
		newVal = parseFloat($('#' + attribute).val()) - 1;
	}
	if (newVal != '') {
		$('#' + attribute).val(newVal);
		$('#' + attribute + 'Display').html(newVal);
	}
	changeAttribute(attribute, mode);
}

function changeAttribute(attribute, mode) {
	if (!mode) {
		mode = 'standard';
	}
	// alert('ChangeAttribute: ' + mode);
	if (!validateAttribute(attribute, mode)) {
		$('#' + attribute).val($('#prev_' + attribute).val());
		$('#' + attribute + 'Display').html($('#prev_' + attribute).val());
		return false;
	}
	$('#prev_' + attribute).val($('#' + attribute).val());
	calcAllUsedCP();
	updateCharSummary();
	buildAttributeBlocks(attribute);
	buildAttributeBlocks('vitality');
	return true;
}

function buildAttributeBlocks(attribute) {
	newHTML = '';
	for (var i = 0; i < $('#' + attribute).val(); i++) {
		newHTML += '<div class="attributeTick"></div>';
	}
	$('#' + attribute + 'Vis').html(newHTML);
}

function selectHeader(fld, headerCost) {
	fld = $('#' + fld.id);
	if (fld.is(':checked') === false) {
		// User is unchecking this header
		removeHeaderSkills(fld); // Remove all skills under this header		
	}
	calcAllUsedCP();
	updateCharSummary();
}

function selectSkill(fld, skillCost) {
	// Check parent header (regardless of whether or not it's already checked)
	$(fld).parents('.skillGrp').prev('.header').find('.headerFld').prop('checked', true);
	calcAllUsedCP();
	updateCharSummary();
	loadSpells();
	loadFeats();
}

function selectFeat(fld, featCost) {
	// Check parent header (regardless of whether or not it's already checked)
	// $(fld).parents('.skillGrp').prev('.header').find('.headerFld').attr('checked', 'checked');
	calcAllUsedCP();
	updateCharSummary();
	loadSpells();
	loadFeats();
}

// This function figures out all the currently selected skills,
// spells, and feats and loads spells and feats into their tabs
function loadSpells() {
	var ajaxPath = '../ajax/';
	var mode;
	if ($('#adminWizardPage').length > 0){
		// Admin version of wizard
		mode = 'adminWizard';
	} else {
		mode = 'wizard';
	}

	// Get all selected skills
	var selectedSkillsArr = new Array();
	$('input.skillFld[type="checkbox"]:checked').each(function() {
		// Add current skill ID to an array
		var skillID = $(this).attr('id').split('_')[1];
		// alert("Current skill ID: " + skillID);
		selectedSkillsArr.push(skillID);
	});
	var selectedSpellsArr = new Array();
	$('input.spellFld[type="checkbox"]:checked').each(function() {
		// Find spellID and add it to the array
		var spellID = $(this).attr('id').split('_')[1];
		selectedSpellsArr.push(spellID);
	});
	var data = {selectedSkills: selectedSkillsArr, selectedSpells: selectedSpellsArr}; // Create object/map of data to pass in AJAX call
	// Load appropriate spell spheres and their spells via AJAX call
	$('#spells').load(ajaxPath + 'wizard.handler.php?ajaxAction=getSpheres&mode=' + mode, data, function() {
		calcAllUsedCP(); // Run this on success to re-calculate CP correctly if the user has selected/unselected spells
		updateCharSummary();
	});
}

// This function figures out all the currently selected skills
// and feats and loads the correct spheres and feats into the feats tab
function loadFeats() {
	var ajaxPath = '../ajax/';
	var mode;
	if ($('#adminWizardPage').length > 0){
		// Admin version of wizard
		mode = 'adminWizard';
	} else {
		mode = 'wizard';
	}

	var selectedFeatsArr = new Array();
	$('input.featFld[type="radio"]:checked').each(function() {
		// Find featID and add it to the array
		var featID = $(this).attr('id').split('_')[1];
		selectedFeatsArr.push(featID);
	});

	// alert('loadFeats: Selected feats: ' + selectedFeatsArr.length);
	var data = {selectedFeats: selectedFeatsArr}; // Create object/map of data to pass in AJAX call
	// Load feats via AJAX call
	$('#feats').load(ajaxPath + 'wizard.handler.php?ajaxAction=getFeats&mode=' + mode, data, function() {
		calcAllUsedCP(); // Run this on success to re-calculate CP correctly if the user has selected/unselected feats
		updateCharSummary();
	});
}

function changeStackableSkill(fld, skillCost) {
	skillID = fld.id.split('_')[1];

	// If value is > 0, check accompanying checkbox so form submit will work
	if ($('#' + fld.id).val() > 0) {
		// This stackable skill is selected
		$('#skillID_' + skillID).prop('checked', true);
		selectSkill(fld);
	} else {
		// $('#skillID_' + skillID).removeAttr('checked');
		$('#skillID_' + skillID).prop('checked', false);
		calcAllUsedCP();
		updateCharSummary();
	}
}

function selectSpell(fld, spellCost) {
	calcAllUsedCP();
	updateCharSummary();
}

function selectFeat(fld, featCost) {
	calcAllUsedCP();
	updateCharSummary();
}

function calcAllUsedCP() {
	var newTotalCP, totalUsedCP;
	var origTotalCP = $('#origCPTotal').val();

	var totalAttributeCost = calcAllAttributeCP();
	var totalHeaderCP = calcAllHeaderCP();
	var totalSkillCP = calcAllSkillCP();
	var totalSpellCP = calcAllSpellCP();
	var totalFeatCP = calcAllFeatCP();

	// alert('Attribute CP: ' + totalAttributeCost + '\n Header CP: ' + totalHeaderCP + '\n Skill CP: ' + totalSkillCP + '\n Spell CP: ' + totalSpellCP);

	totalUsedCP = parseFloat(totalAttributeCost) + parseFloat(totalHeaderCP) + parseFloat(totalSkillCP) + parseFloat(totalSpellCP) + parseFloat(totalFeatCP);


	// Calculate and display grand total
	newTotalCP = parseFloat(origTotalCP) - parseFloat(totalUsedCP);
	newTotalCP = parseFloat(newTotalCP.toFixed(2));
	// alert("origTotalCP: " + parseFloat(origTotalCP) + " - totalUsedCP: " + parseFloat(totalUsedCP) + "\n = newTotalCP: " + parseFloat(newTotalCP));
	updateTotalCP(newTotalCP);
}

// Update the total CP displayed with the new total
function updateTotalCP(newTotalCP) {
	/* Store previous CP total in hidden field, just in case
	$('#prevCPTotal').val($('#cpNum').html());
	*/
	// Update CP total to new total
	$('#cpNum').html(newTotalCP);

	checkNegativeCP(); // Check whether CP is negative and, if so, display warning and disable submit button. 
}

function checkNegativeCP() {
	if (isNegativeNum(parseFloat($('#cpNum').html()))) {
		displaySubmitWarning('Please ensure CP total is above zero before submitting.');
		$('#cpNum').addClass('negWarning');
		$('#cpNum').attr('title', "You have exceeded your available CP. Please fix before saving your character.");
		$('#submitBtn').prop('disabled', true);
		$('#submitBtn').removeClass('btn-primary');
		$('#submitBtn').addClass('btn-disabled');
		// TODO: also disable submit button. 
	} else {
		removeSubmitWarning();
		$('#cpNum').removeClass('negWarning');
		$('#submitBtn').prop('disabled', false);
		$('#submitBtn').removeClass('btn-disabled');
		$('#submitBtn').addClass('btn-primary');
	}
}

function calcAllAttributeCP() {
	var totalAttributeCP = calcAttributeCP('attribute1') + calcAttributeCP('attribute2') + calcAttributeCP('attribute3') + calcAttributeCP('attribute4') + calcAttributeCP('attribute5');
	// Set new attribute cost and CP total
	$('#totalAttributeCost').html(totalAttributeCP);
	// Vitality = average of earth and void, rounded down
	newVitality = parseInt((parseFloat($('#attribute2').val()) + parseFloat($('#attribute5').val())) / 2); 
	$('#curVitality').html(newVitality);
	$('#vitality').val(newVitality);
	$('#vitalityRow .attributeNumDisplay').html(newVitality);
	// alert('Total attribute CP is: ' + totalAttributeCP);
	return totalAttributeCP;
}

function calcAttributeCP(attribute) {
	var attributeCost = 0;
	var baseAttribute = parseInt($('#baseAttribute').val());
	var newValue = parseInt($('#' + attribute).val());

	for (var i = baseAttribute + 1; i <= newValue; i++) {
		attributeCost = attributeCost + i;
	}
	return attributeCost;
}


function oldCalcAttributeCP(attribute) {
	// validateAttribute(attribute);
	var baseAttribute = parseInt($('baseAttribute').val());
	var oldValue = parseInt($('#prev_' + attribute).val());
	var newValue = parseInt($('#' + attribute).val());
	var totalCost = 0;
	var totalCredit = 0;
	var newTotalCost;

	if (newValue > oldValue) {
		// User is raising an attribute
		for (var i = oldValue; i < newValue; i++) {
			totalCost = totalCost + parseInt(i) + 1;
		}
		// newTotalCost = parseInt($('#totalAttributeCost').html()) + totalCost;
	} else {
		// User is lowering an attribute in the same session
		for (var i = newValue; i < oldValue; i++) {
			totalCredit = totalCredit + parseInt(i) + 1;
		}
		// newTotalCost = parseInt($('#totalAttributeCost').html()) - totalCredit;
	}
	// Set new attribute cost and CP total
	$('#totalAttributeCost').html(newTotalCost);
	$('#prev_' + attribute).val(newValue);
	newVitality = parseInt((parseFloat($('#attribute2').val()) + parseFloat($('#attribute5').val())) / 2); // Vitality = average of earth and void, rounded down
	$('#curVitality').html(newVitality);
	$('#vitality').val(newVitality); // Set vitality to value of Earth attribute
}

// Version for Pirates
function calcAttributeCPPirates(attribute) {
	validateAttribute(attribute);
	var oldValue = parseInt($('#prev_' + attribute).val());
	var newValue = parseInt($('#' + attribute).val());
	var totalCost = 0;
	var totalCredit = 0;
	var newTotalCost;

	if (newValue > oldValue) {
		// User is raising an attribute
		for (var i = oldValue; i < newValue; i++) {
			totalCost = totalCost + (parseInt(i) + (parseInt(i) + 1));
		}
		newTotalCost = parseInt($('#totalAttributeCost').html()) + totalCost;
	} else {
		// User is lowering an attribute in the same session
		for (var i = newValue; i < oldValue; i++) {
			totalCredit = totalCredit + (parseInt(i) + (parseInt(i) + 1));
		}
		newTotalCost = parseInt($('#totalAttributeCost').html()) - totalCredit;
	}
	// Set new attribute cost and CP total
	$('#totalAttributeCost').html(newTotalCost);
	$('#prev_' + attribute).val(newValue);
	$('#curVitality').html($('#attribute2').val());
	$('#vitality').val($('#attribute2').val()); // Set vitality to value of Earth attribute
	
}

// Figure out all CP used for headers and return the value
function calcAllHeaderCP() {
	var totalHeaderCP = 0;
	// Loop through all checked header checkboxes and add their cost to total
	$('input.headerFld[type="checkbox"]:checked').each(function() {
		var curHeaderId = $(this).attr('id');
		var curHeaderCost = $('#' + curHeaderId + '_cost').val(); // Use id to construct the id of the hidden field that holds the CP cost
		totalHeaderCP = parseFloat(totalHeaderCP) + parseFloat(curHeaderCost);
	});
	return totalHeaderCP; // Return total number of CP used for headers
}

// Uncheck all the skills under a particular header that has just been unselected
// fld: object (header's checkbox field)
function removeHeaderSkills(fld) {
	// Find all non-stackable skills under this header and loop through them
	$(fld).closest('.header').next('.skillGrp').find('input.skillFld[type=checkbox]:checked').each(function() {
		$(this).prop('checked', false); // Uncheck each skill
	});

	// Find all stackable skill quantity dropdowns and set them back to their original quantities
	$(fld).closest('.header').next('.skillGrp').find('select.skillQtyFld').each(function() {
		var fldId = $(this).attr('id');
		var curSkillNum = fldId.slice(fldId.indexOf('_') + 1); // Find skill number to use
		var origQuantity = $('#orig_quantity_' + curSkillNum).val(); // Find original selected quantity
		$(this).val(origQuantity); // Set this skill back to its original quantity
	});
}

// Calculate all CP used for stackable and non-stackable skills and return the value
/* NOTE: This function does not yet support "patterned" skills (e.g. skills whose costs increase in a 1, 2, 3 or 2, 4, 6 pattern) */
function calcAllSkillCP() {
	var totalSkillCP = 0;
	// Calculate total CP for non-stackable skills
	// Loop through all checked skill checkboxes and add their cost to total
	$('input.skillFld[type="checkbox"]:checked').each(function() {
		if (!$(this).hasClass('stackableSkillFld')) {
			// Only do this for non-stackable skill checkboxes, because stackable skills have their own calculations
			var curSkillId = $(this).attr('id');
			var curSkillCost = $('#' + curSkillId + "_cost").val(); // Use id to construct the id of the hidden field that holds the CP cost
			totalSkillCP = parseFloat(totalSkillCP) + parseFloat(curSkillCost);
		}
	});

	// Calculate total CP for stackable skills (including "patterned" skills)
	$('select.skillQtyFld').each(function() {  // Find all stackable skills and loop through them
		var fldId = $(this).attr('id');
		var curSkillNum = fldId.slice(fldId.indexOf('_') + 1); // Find skill number to use
		var selectedQty = $(this).val();

		if (selectedQty > 0) {
			// alert('stackable!');
			var curCost = $('#skillID_' + curSkillNum + '_cost').val(); // Find base skill cost
			var costIncrement = $('#skillID_' + curSkillNum + '_costIncrement').val(); // Find base skill cost
			// alert('selectedQty: ' + selectedQty + '\n curCost: ' + curCost + '\n costIncrement: ' + costIncrement);

			totalSkillCP = parseFloat(totalSkillCP) + parseFloat(curCost); // Subtract base cost for the first purchase.
			for (var i = 2; i <= selectedQty; i++) {
				curCost = parseFloat(curCost) + parseFloat(costIncrement);
				totalSkillCP = parseFloat(totalSkillCP) + parseFloat(curCost);
				// alert(i + ': curCost: ' + curCost + '\n totalSkillCP: ' + totalSkillCP);
			}
		}
	});

	return totalSkillCP; // Return total number of CP used for skills
}

// Calculate all CP used for spells and return the total
function calcAllSpellCP() {
	var totalSpellCP = 0;
	$('input.spellFld[type="checkbox"]:checked').each(function() {
		var curSpellId = $(this).attr('id');
		var curSpellCost = $('#' + curSpellId + "_cost").val(); // Use id to construct the id of the hidden field that holds the CP cost
		totalSpellCP = parseFloat(totalSpellCP) + parseFloat(curSpellCost);
		// alert('Current spell CP: ' + totalSpellCP);
	});
	return totalSpellCP; // Return total number of CP used for spells
}

// Calculate all CP used for feats and return the total
function calcAllFeatCP() {
	var totalFeatCP = 0;
	$('input.featFld[type="radio"]:checked').each(function() {
		var curFeatId = $(this).attr('id');
		// alert(curFeatId);
		var curFeatCost = $('#' + curFeatId + "_cost").val(); // Use id to construct the id of the hidden field that holds the CP cost
		totalFeatCP = parseFloat(totalFeatCP) + parseFloat(curFeatCost);
		// alert('Total feat CP: ' + totalFeatCP);
	});
	return totalFeatCP; // Return total number of CP used for feats
}

// Find character name, attribute values, and currently selected headers, skills, spells and feats
// and update character summary
function updateCharSummary() {
	if ($('#charName').val() != '') {
		$('#summaryName').html($('#charName').val());
	} else {
		$('#summaryName').html('<em>None</em>');
	}
	$('#summaryAttribute1').html($('#attribute1').val());
	$('#summaryAttribute2').html($('#attribute2').val());
	$('#summaryAttribute3').html($('#attribute3').val());
	$('#summaryAttribute4').html($('#attribute4').val());
	$('#summaryAttribute5').html($('#attribute5').val());
	$('#summaryVitality').html($('#curVitality').html()); // Readonly field, so get the innerHTML
	getSelectedHeaders();
	getSelectedSpells();
	getSelectedFeats();
}

// Find all selected headers and skills and add them to character summary
// Don't do any CP calculation
function getSelectedHeaders() {
	$('#summaryHeaderList').html(''); // Initialize header list to be blank
	$('#skills .header input[type="checkbox"]:checked').each(function() {
		// Find selected headers and add them to list
		var headerName = $(this).closest('.row').find('label').html();
		var newHeaderName = $('#summaryHeaderList').html() + headerName + '<br />';
		$('#summaryHeaderList').html(newHeaderName);

		// Find selected skills under this header and add them to skills list
		var skillGrp = $(this).closest('.header').next('.skillGrp');
		var skillList = skillGrp.find('input[type="checkbox"]:checked').each(function() {
			var skillName = $(this).closest('.row').find('label').html();
			var newSkillName = $('#summaryHeaderList').html();

			if (!$(this).hasClass('stackableSkillFld')) { // Non-stackable skill
				newSkillName += "&nbsp;&nbsp;&nbsp;" + skillName + "<br />";
			} else { // stackable skill
				var qty = $(this).closest('.row').find('.skillQtyFld').val();
				if (qty) {
					newSkillName += "&nbsp;&nbsp;&nbsp;" + skillName + " x " + qty + "<br />";
				}
			}
			$('#summaryHeaderList').html(newSkillName);
		});

	}); // end of loop through checked checkboxes

	// If no headers or skills selected, print "None"
	if ($('#summaryHeaderList').html() == "") {
		$('#summaryHeaderList').html('<em>None</em>');
	}
}

// Find all selected spells and add them to summary
// Don't do any CP calculations
function getSelectedSpells() {
	var newSpellList;
	$('#summarySpellList').html(''); // Spell list starts out blank
	// Build array of all non-stackable checked spell checkboxes
	var spellList = $('#spells input[type="checkbox"]:checked').each(function() {
		var spellName = $(this).closest('.row').find('label').html();
		var newSpellList = $('#summarySpellList').html() + spellName + "<br />";
		$('#summarySpellList').html(newSpellList);
	});

	if ($('#summarySpellList').html() == '') {
		$('#summarySpellList').html('<em>None</em>');
	}
}

// Find all selected feats and add them to summary
// Don't do any CP calculations
function getSelectedFeats() {
	var newFeatList;
	$('#summaryFeatList').html(''); // Feat list starts out blank
	// Build array of all feat radio buttons
	var featList = $('#feats input[type="radio"]:checked').each(function() {
		var featName = $(this).closest('.row').find('label').html();
		var newFeatList = $('#summaryFeatList').html() + featName;
		$('#summaryFeatList').html(newFeatList);
	});

	if ($('#summaryFeatList').html() == 'No feat') {
		$('#summaryFeatList').html('<em>None</em>');
	}
}

/**************************************************************
RETIRED COST CALCULATION FUNCTIONS
These are the old versions that update the CP total relative
to the current total. They have been retired in favor of the
more robust "absolute" calculation scripts above. 
*************************************************************

// fld: native DOM field object
// skillCost: string number
function calcHeaderCP(fld, headerCost) {
	var curCPTotal = parseFloat($('#cpNum').html());
	var newTotalCP;
	if (fld.checked) {
		// User is adding this header to their character. 
		// subtract CP for this header from total
		newTotalCP = curCPTotal - headerCost;
	} else {
		// User is removing this header from their character
		// add CP for this header back to total
		removeHeaderSkills(fld);
		newTotalCP = curCPTotal + headerCost;
	}
	updateTotalCP(newTotalCP);
}

// fld: native DOM field object
// skillCost: string number
function calcSkillCP(fld, skillCost) {
	// alert('calcSkillCP ' + fld + ', ' + skillCost);
	var curCPTotal = parseFloat($('#cpNum').html());
	var newTotalCP;
	if (fld.checked) {
		// User is adding this skill to their character. 
		// subtract CP for this skill from total
		newTotalCP = curCPTotal - skillCost;
	} else {
		// User is removing this skill from their character
		// add CP for this skill back to total
		newTotalCP = curCPTotal + skillCost;
	}
	// alert('curCPTotal: ' + curCPTotal + '\n' + 'newTotalCP: ' + newTotalCP);
	updateTotalCP(newTotalCP);
}

// fld: native DOM field object
// skillCost: string number
function calcStackableSkillCP(fld, skillCost) {
	var diff;
	var totalCost = 0;
	var totalCredit = 0;
	var fldId = fld.id;
	var curCPTotal = parseFloat($('#cpNum').html());
	var newQuantity = $(fld).val();
	
	var skillNum = fldId.slice(fldId.indexOf('_') +1); // Find skill number to use
	var oldQuantityFld = $(fld).nextAll('#prev_quantity_' + skillNum);
	var oldQuantity = oldQuantityFld.val();
	if (newQuantity > oldQuantity) {
		// User is increasing the number of this skill s/he has
		// Get difference between old value and new one
		diff = newQuantity - oldQuantity;
		totalCost = skillCost * diff;
		updateTotalCP(curCPTotal - totalCost);
	} else if (oldQuantity > newQuantity) {
		// User is reducing the number of this skill s/he has
		// Get difference between old value and new one
		diff = oldQuantity - newQuantity;
		totalCredit = skillCost * diff;
		updateTotalCP(curCPTotal + totalCredit);
	}
	oldQuantityFld.val($(fld).val());
}

function calcSpellCP(fld, spellCost) {
	var curCPTotal = parseFloat($('#cpNum').html());
	var newTotalCP;
	if (fld.checked) {
		// User is adding this skill to their character. 
		// subtract CP for this skill from total
		newTotalCP = curCPTotal - spellCost;
	} else {
		// User is removing this skill from their character
		// add CP for this skill back to total
		newTotalCP = curCPTotal + spellCost;
	}
	updateTotalCP(newTotalCP);
}
*/
