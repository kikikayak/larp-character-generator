/**********************************************************
NAME:	cp.js
NOTES:	Contains all the scripts for the CP section
		in the Admin area
***********************************************************/

$(document).ready(function () {
		
	$("#CPType0").click(function () {
		// showHelp(this, 'CPTypeHelp');
		enableDisableCPType();
	});
	
	$("#CPType1").click(function () {
		// showHelp(this, 'CPTypeHelp');
		enableDisableCPType();
	});
	
	// Make "Assign to" row red if there's an error
	// (Doesn't happen automatically because the validations run on two separate fields)
	if ($('#CPTypeRow .errorMsg').length > 0) {
		$('#CPTypeRow').addClass('error');
	}
	
	$('#fromDate').datepicker({
		showOn: "button",
		buttonImage: "../admin/styles/images/calendar_brankic.png",
		buttonImageOnly: true
	});
	
	$('#toDate').datepicker({
		showOn: "button",
		buttonImage: "../admin/styles/images/calendar_brankic.png",
		buttonImageOnly: true
	});
	
	$("#staffMember").autocomplete({
		source: '../ajax/player.handler.php?ajaxAction=getStaffSuggestions'
	});
	
	$("#charName").autocomplete({
		source: '../ajax/admin.handler.php?ajaxAction=getCharacterSuggestions'
	});
	
	$("#playerName").autocomplete({
		source: '../ajax/player.handler.php?ajaxAction=getPlayerSuggestions'
	});
	
	enableDisableCPType();
	runValidations();
	
});

function enableDisableCPType() {
	// alert('enableDisableCPType: cp.js')
	// alert('enableDisableCPType: Character checked? ' + $('#CPType0').is(':checked') + '\n ' + 'Player checked: ' + $('#CPType1').is(':checked'));

	if ($('#CPType0').is(':checked')) {
		// alert('Character is selected');
		$('#characterID').attr('disabled', false).removeClass('disabled').trigger("chosen:updated");
		$('#playerID').val(0).attr('disabled', true).addClass('disabled').trigger("chosen:updated");
			
	} else if ($('#CPType1').is(':checked')) {
		// alert('Player is selected');
		$('#playerID').attr('disabled', false).removeClass('disabled').trigger("chosen:updated");
		$('#characterID').val(0).attr('disabled', true).addClass('disabled').trigger("chosen:updated");
	}
}

function calcTotalAddedCP() {
	var totalCP = 0;
	var numberCP1 = 0;
	var numberCP2 = 0;
	var numberCP3 = 0;
	var numberCP4 = 0;
	var numberCP5 = 0;
	
	if ($('#numberCP1').val() != '') {
		numberCP1 = parseFloat($('#numberCP1').val());
	}
	
	if ($('#numberCP2').val() != '') {
		numberCP2 = parseFloat($('#numberCP2').val());
	}
	
	if ($('#numberCP3').val() != '') {
		numberCP3 = parseFloat($('#numberCP3').val());
	}
	
	if ($('#numberCP4').val() != '') {
		numberCP4 = parseFloat($('#numberCP4').val());
	}
	
	if ($('#numberCP5').val() != '') {
		numberCP5 = parseFloat($('#numberCP5').val());
	}
	
	totalCP = numberCP1 + numberCP2 + numberCP3 + numberCP4 + numberCP5;
	return totalCP;
}

function displayTotalAddedCP(totalCP) {
	if (isNumeric(totalCP)) {
		$('#addCPTotal').html(totalCP);
	}
}

/*******************************************
VALIDATIONS
********************************************/

// fld: jQuery object
function validateNumberCP(fldObj) {
	if (!isNumeric(fldObj.val()) ) {
		showError(fldObj, 'Must be a number');
		return false;
	} else {
		removeErrors(fldObj);
		return true;
	}
}

function validateNumberCPTable(fldObj) {
	if (!isNumeric(fldObj.val()) ) {
		showTableError(fldObj, 'Must be a number');
		return false;
	} else {
		removeTableErrors(fldObj);
		return true;
	}
}

function validateCPNote(fldObj) {
	if (!isValidTextArea(fldObj.val())) {
		showError(fldObj, 'Contains invalid characters');
		return false;
	} else {
		removeErrors(fldObj);
		return true;
	}
}

function validateCPNoteTable(fldObj) {
	if (!isValidTextArea(fldObj.val())) {
		showTableError(fldObj, 'Contains invalid characters');
		return false;
	} else {
		removeTableErrors(fldObj);
		return true;
	}
}

function validateCPCatIDTable(fldObj) {
	if (!isInteger(fldObj.val())) {
		showTableError(fldObj, 'Invalid category');
		return false;
	} else {
		removeTableErrors(fldObj);
		return true;
	}
}

function removeAssignToErrors() {
	if ($('#characterID').val() != 0 || $('#playerID').val() != 0) {
		removeErrors($('#characterID'));
		$('#CPTypeRow').removeClass('error');
	}
}

function setupAddCPValidations() {
	$(".numberCPFld").blur(function () {
		if (validateNumberCPTable($(this)) && checkCPFldsPopulated()) {
			removeErrors($('#numberCP1'));
			$('#cpAddRow1').removeClass('error');
		}
	});
	
	$(".cpNoteFld").blur(function () {
		validateCPNoteTable($(this));
	});
}

function runAddCPValidations() {
	
	// Validate character ID field
	if ($('#cpAddDialog input:radio[name=CPType]:checked').val() == 'Character' && $('#characterID').val() == 0) {
		showError($('#characterID'), 'Please select a character');
	} else if ($('#cpAddDialog input:radio[name=CPType]:checked').val() == 'Player' && $('#playerID').val() == 0) {
		showError($('#playerID'), 'Please select a player');
	} else if ($('#cpAddDialog input:radio[name=CPType]:checked').length == 0) {
		showError($('#characterID'), 'Please select either "Character" or "Player"');
	} else {
		removeErrors($('#characterID'));
	}
	
	if (!isEmpty($('#numberCP1').val()) && isEmpty($('#CPCatID1').val())) {
		showError($('#CPCatID1'), 'Please select a category');
	}

	for (var i=1; i<=5; i++) {
		var curNumFld = '#numberCP' + i;
		var curCatFld = '#CPCatID' + i;
		var curNoteFld = '#CPNote' + i;

		validateNumberCPTable($(curNumFld));
		validateCPCatIDTable($(curCatFld));
		validateCPNoteTable($(curNoteFld));

	}
	
	if (!checkCPFldsPopulated()) {
		showError($('#numberCP1'), 'Please fill in at least one row');
		$('#cpAddRow1').addClass('error');
	} else {
		removeErrors($('#numberCP1'));
		$('#cpAddRow1').removeClass('error');
	}
	
} // end of runAddCPValidations

// Check if any fields in the add CP dialog are populated
function checkCPFldsPopulated() {
	var fldPopulated = 0;
	
	for (var i=1; i<=5; i++) {
		var curNumFld = '#numberCP' + i;
		var curCatFld = '#CPCatID' + i;
		var curNoteFld = '#CPNote' + i;

		if (!isEmpty($(curNumFld).val()) || $(curCatFld).val() != 0 || !isEmpty($(curNoteFld).val())) {
			fldPopulated = 1;
		}
	}
	
	if (fldPopulated == 1) {
		return true;
	} else {
		return false;
	}
}



