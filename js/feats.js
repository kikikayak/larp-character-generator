/**********************************************************
NAME:	feats.js
NOTES:	Contains all the scripts for the feats section
		in the Admin area
***********************************************************/

$(document).ready(function () {
	
	$("#featName").focus(function () {
		showHelp(this, 'featNameHelp');
	});
	
	$("#featCost").focus(function () {
		showHelp(this, 'featCostHelp');
	});
	
	$("#featPrereq").focus(function () {
		showHelp(this, 'featPrereqHelp');
	});
	
	$("#featShortDescription").focus(function () {
		showHelp(this, 'featShortDescriptionHelp');
	});
	
	$("#featDescription").focus(function () {
		showHelp(this, 'featDescriptionHelp');
	});
	
	$("#featCheatSheetNote").focus(function () {
		showHelp(this, 'featCheatSheetNoteHelp');
	});
	
	$("#featAccess").focus(function () {
		showHelp(this, 'accessHelp');
	});
	
	$("#featAccess").change(function () {
		doOnClickAccessDropdown(this);
	});
	
	$("#checkAllAccess").click(function() {
		doOnClickCheckAllAccess();
		return false;
	});
	
	$("#uncheckAllAccess").click(function() {
		doOnClickUncheckAllAccess();
		return false;
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
	
$("#featName").blur(function () {
	if ( !isValidTextAdmin($(this).val()) ) {
		showError(this, 'Contains invalid characters');
	} else {
		removeErrors(this);
	}
});

$("#featCost").blur(function () {
	if ( !isNumeric($(this).val()) ) {
		showError(this, 'Must be numeric');
	} else {
		removeErrors(this);
	}
});

$("#featPrereq").blur(function () {
	if ( !isValidTextAdmin($(this).val()) ) {
		showError(this, 'Contains invalid characters');
	} else {
		removeErrors(this);
	}
});

$("#featShortDescription").blur(function () {
	convert_smart_quotes($(this));

	if ( !isValidTextArea($(this).val()) ) {
		showError(this, 'Contains invalid characters');
	} else {
		removeErrors(this);
	}
});

$("#featDescription").blur(function () {
	convert_smart_quotes($(this));

	if ( !isValidTextArea($(this).val()) ) {
		showError(this, 'Contains invalid characters');
	} else {
		removeErrors(this);
	}
});

$("#featCheatSheetNote").blur(function () {
	if ( !isValidTextAdmin($(this).val()) ) {
		showError(this, 'Contains invalid characters');
	} else {
		removeErrors(this);
	}
});
			
} // end of runValidations



