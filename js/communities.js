/**********************************************************
NAME:	communities.js
NOTES:	Contains all the scripts for the communities section
		in the Admin area
***********************************************************/

/*******************************************
VALIDATIONS
********************************************/


function runValidations() {
	// alert('runValidations');
	
	$("#communityName").blur(function () {
		if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});
	
	$("#communityDescription").blur(function () {
		if ( !isValidTextArea($(this).val()) ) {
			showError(this, 'Contains invalid characters');
		} else {
			removeErrors(this);
		}
	});
		
} // end of runValidations
