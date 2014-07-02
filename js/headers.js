/**********************************************************
NAME:	headers.js
NOTES:	Contains all the scripts for the headers section
		in the Admin area
***********************************************************/

$(document).ready(function () {
	// init();
	 
	$("#headerName").focus(function () {
		showHelp(this, 'nameHelp');
	});
	
	$("#headerCost").focus(function () {
		showHelp(this, 'costHelp'); 
	});
	
	/* $("#headerAccess").focus(function () {
		showHelp(this, 'accessHelp'); 
	}); */
	
	$('#headerAccess').change(function () {
		doOnClickAccessDropdown(this);
	});

	$('#headerAccess').chosen({disable_search_threshold: 10});
	
	$("#headerDescription").focus(function () {
		showHelp(this, 'headerDescriptionHelp');
	});
	
	$("#checkAllAccess").click(function() {
		doOnClickCheckAllAccess();
		return false;
	});
	
	$("#uncheckAllAccess").click(function() {
		doOnClickUncheckAllAccess();
		return false;
	});
	
	runValidations();
	
});

/*******************************************
VALIDATIONS
********************************************/


function runValidations() {

$("#headerName").blur(function () {
	if ( !isValidText($(this).val()) ) {
		showError(this, 'Contains invalid characters');
	} else {
		removeErrors(this);
	}
});

$("#headerCost").change(function () {
	if ( !isNumeric($(this).val()) ) {
		showError(this, 'Please enter a number');
	} else {
		removeErrors(this);
	}
});

$("#headerDescription").blur(function () {
	convert_smart_quotes($(this));

	if ( !isValidTextArea($(this).val()) ) {
		showError(this, 'Contains invalid characters');
	} else {
		removeErrors(this);
	}
});
	
}

