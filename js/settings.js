/**********************************************************
NAME:	headers.js
NOTES:	Contains all the scripts for the headers section
		in the Admin area
***********************************************************/

$(document).ready(function () {
	// init();
	 
	$("#campaignName").focus(function () {
		 showHelp(this, 'campaignNameHelp');
	});
	
	$("#baseCP").focus(function () {
		 showHelp(this, 'baseCPHelp');
	});
	
	$("#baseAttribute").focus(function () {
		 showHelp(this, 'baseAttributeHelp');
	});
	
	$("#useRaces").focus(function () {
		 showHelp(this, 'useRacesHelp');
	});
	
	$("#themeID").focus(function () {
		 showHelp(this, 'themeIDHelp');
	});
	
	$("#communityLabel").focus(function () {
		 showHelp(this, 'communityLabelHelp');
	});
	
	$("#attribute1Label").focus(function () {
		 showHelp(this, 'attributeLabelHelp');
	});
	
	$("#attribute2Label").focus(function () {
		 showHelp(this, 'attributeLabelHelp');
	});
	
	$("#attribute3Label").focus(function () {
		 showHelp(this, 'attributeLabelHelp');
	});
	
	$("#attribute4Label").focus(function () {
		 showHelp(this, 'attributeLabelHelp');
	});
	
	$("#attribute5Label").focus(function () {
		 showHelp(this, 'attributeLabelHelp');
	});
	
	$("#vitalityLabel").focus(function () {
		 showHelp(this, 'vitalityLabelHelp');
	});
	
	$("#contactName").focus(function () {
		 showHelp(this, 'contactHelp');
	});
	
	$("#contactEmail").focus(function () {
		 showHelp(this, 'contactHelp');
	});
	
	$("#webmasterName").focus(function () {
		 showHelp(this, 'webmasterHelp');
	});
	
	$("#webmasterEmail").focus(function () {
		 showHelp(this, 'webmasterHelp');
	});
	
	$("#paypalEmail").focus(function () {
		 showHelp(this, 'paypalEmailHelp');
	});
	
	$("#generatorLocation").focus(function () {
		 showHelp(this, 'generatorLocationHelp');
	});
	
	$(".help .closeLink").each(function() {
		$(this).click(function() {
			$(this).closest(".help").hide();
			$("#helpArrow").hide();
		});
	});
		
	runValidations();
	
});

/*******************************************
VALIDATIONS
********************************************/


function runValidations() {
	// alert('runValidations');
	
	$("#campaignName").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#baseCP").blur(function () {
		 if ( !isInteger($(this).val()) ) {
			showError(this, 'Must be a whole number'); 
		 } else {
			removeErrors(this); 
		 }
	});	
	
	$("#baseAttribute").blur(function () {
		 if ( !isInteger($(this).val()) ) {
			showError(this, 'Must be a whole number'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#communityLabel").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#communityLabelPlural").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#attribute1Label").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#attribute2Label").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#attribute3Label").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#attribute4Label").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#attribute5Label").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#vitalityLabel").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#contactName").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#contactEmail").blur(function () {
		 if ( !isEmail($(this).val()) ) {
			showError(this, 'Should be a valid email address <br /> in the format email@example.com'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#webmasterName").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#webmasterEmail").blur(function () {
		 if ( !isEmail($(this).val()) ) {
			showError(this, 'Should be a valid email address <br /> in the format email@example.com'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#paypalEmail").blur(function () {
		 if ( !isEmail($(this).val()) ) {
			showError(this, 'Should be a valid email address <br /> in the format email@example.com'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#generatorLocation").blur(function () {
		 if ( !isValidURL($(this).val()) ) {
			showError(this, 'Should be a valid Web address, <br /> e.g. http://yourgame.com/generator'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
}

