/**********************************************************
NAME:	traits.js
NOTES:	Contains all the scripts for the traits section
		in the Admin area
***********************************************************/

$(document).ready(function () {
	
	$("#traitName").focus(function () {
		 showHelp(this, 'traitNameHelp');
	});
	
	$("#traitStaff").focus(function () {
		 showHelp(this, 'traitStaffHelp');
	});
	
	$("#traitAccess").focus(function () {
		 showHelp(this, 'traitAccessHelp');
	});
	
	$("#traitDescriptionStaff").focus(function () {
		 showHelp(this, 'traitDescriptionStaffHelp');
	});
	
	$("#traitDescriptionPublic").focus(function () {
		 showHelp(this, 'traitDescriptionPublicHelp');
	});
	
	$("#checkAllAccess").click(function() {
		doOnClickCheckAllAccess();
		return false;
	});
	
	$("#uncheckAllAccess").click(function() {
		doOnClickUncheckAllAccess();
		return false;
	});
	
	$("#.help .closeLink").each(function() {
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
	
	$("#traitName").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#traitStaff").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#traitDescriptionStaff").blur(function () {
		 if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#traitDescriptionPublic").blur(function () {
		 if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
			
} // end of runValidations



