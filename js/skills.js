/**********************************************************
NAME:	skills.js
NOTES:	Contains all the scripts for the headers section
		in the Admin area
***********************************************************/

$(document).ready(function () {
	
	$("#skillName").focus(function () {
		showHelp(this, 'nameHelp');
	});
	
	$("#skillCost").focus(function () {
		showHelp(this, 'costHelp');
	});
	
	$("#stackable").focus(function () {
		showHelp(this, 'stackableHelp');
	});
	
	$("#stackable").change(function () {
		doOnChangeStackable(this);
	});
	
	$("#stackableOptions").focus(function () {
		showHelp(this, 'maxQuantityHelp');
	});
	
	$("#maxQuantity").focus(function () {
		showHelp(this, 'maxQuantityHelp');
	});
	
	$("#costIncrement").focus(function () {
		showHelp(this, 'costIncrementHelp');
	});
	
	$("#headerID").focus(function () {
		showHelp(this, 'headerHelp');
	});
	
	$("#skillAccess").focus(function () {
		showHelp(this, 'accessHelp');
	});
	
	$("#skillAccess").change(function () {
		doOnClickAccessDropdown(this);
	});
	
	$("#checkAllAccess").click(function() {
		doOnClickCheckAllAccess();
	});
	
	$("#uncheckAllAccess").click(function() {
		doOnClickUncheckAllAccess();
	});
	
	$("#shortDescription").focus(function () {
		showHelp(this, 'shortDescriptionHelp');
	});

	$("#shortDescription").simplyCountable({
		maxCount: 950
	});
	
	$("#skillDescription").focus(function () {
		showHelp(this, 'skillDescriptionHelp');
	});
	
	$("#cheatSheetNote").focus(function () {
		showHelp(this, 'cheatSheetNoteHelp');
	});
	
	$("#attributeCost1").focus(function () {
		 showHelp(this, 'attributeHelp');
	});
	
	$("#attribute1").focus(function () {
		 showHelp(this, 'attributeHelp');
	});
	
	$("#attributeCost2").focus(function () {
		 showHelp(this, 'attributeHelp');
	});
	
	$("#attribute2").focus(function () {
		 showHelp(this, 'attributeHelp');
	});
	
	$("#attributeCost3").focus(function () {
		 showHelp(this, 'attributeHelp');
	});
	
	$("#attribute3").focus(function () {
		 showHelp(this, 'attributeHelp');
	});
	
	$(".help .closeLink").each(function() {
		$(this).click(function() {
			$(this).closest(".help").hide();
			$("#helpArrow").hide();
			return false;
		});
	});
	
	runValidations();
	
});

/*******************************************
VALIDATIONS
********************************************/


function runValidations() {
	// alert('runValidations');
	
	$("#skillName").blur(function () {
		if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});
	
	$("#skillCost").blur(function () {
		if ( !isNumeric($(this).val()) ) {
			showError(this, 'Must be numeric');
		} else {
			removeErrors(this);
		}
	});
	
	$("#maxQuantity").blur(function () {
		if ( !isInteger($(this).val()) ) {
			showError(this, 'Must be a whole number');
		} else {
			removeErrors(this);
		}
	});
	
	$("#costIncrement").blur(function () {
		if ( !isInteger($(this).val()) ) {
			showError(this, 'Must be a whole number');
		} else {
			removeErrors(this);
		}
	});

	$("#shortDescription").blur(function () {
		convert_smart_quotes($(this));

		if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});

	$("#skillDescription").blur(function () {
		convert_smart_quotes($(this));

		if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});

	$("#cheatSheetNote").blur(function () {
		if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});

	$(".attributeCostNum").blur(function () {
		if ( !isInteger($(this).val()) ) {
			showError(this, 'Must be a whole number');
		} else {
			removeErrors(this);
		}
	});
		
} // end of runValidations