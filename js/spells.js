/**********************************************************
NAME:	spells.js
NOTES:	Contains all the scripts for the spells section
		in the Admin area
***********************************************************/

$(document).ready(function () {
		
	runValidations();
	
});

/*******************************************
VALIDATIONS
********************************************/


function runValidations() {
	// alert('runValidations');
	
	$("#spellName").blur(function () {
		if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});
	
	$("#spellCost").blur(function () {
		if ( !isNumeric($(this).val()) ) {
			showError(this, 'Must be numeric');
		} else {
			removeErrors(this);
		}
	});
		
	$("#spellShortDescription").blur(function () {
		if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});
	
	$("#spellDescription").blur(function () {
		if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});
	
	$("#spellCheatSheetNote").blur(function () {
		if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});
	
	$(".attributeCost").blur(function () {
		if ( !isInteger($(this).val()) ) {
			showError(this, 'Must be a whole number');
		} else {
			removeErrors(this);
		}
	});
		
} // end of runValidations



