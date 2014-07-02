/*******************************************
NAME: 	players.js
NOTES: 	This file holds all the scripts for the players section. 
***********************************************************/

$(document).ready(function () {
	 
	$("#firstName").focus(function () {
		 hideAllHelp();
	});
	
	$("#lastName").focus(function () {
		 hideAllHelp();
	});
	
	$("#email").focus(function () {
		 showHelp(this, 'emailHelp');
	});
	
	$("#newPassword").focus(function () {
		 showHelp(this, 'passwordHelp');
	});
	
	$("#confirmPassword").focus(function () {
		 showHelp(this, 'passwordHelp');
	});
	
	$("#curPassword").focus(function () {
		 showHelp(this, 'curPasswordHelp');
	});
	
	$("#userRoleUser").click(function () {
		 showHelp(this, 'userRoleHelp');
	});
	
	$("#userRoleUser").focus(function () {
		 showHelp(this, 'userRoleHelp');
	});
	
	$("#userRoleStaff").click(function () {
		 showHelp(this, 'userRoleHelp');
	});
	
	$("#userRoleStaff").focus(function () {
		 showHelp(this, 'userRoleHelp');
	});
	
	$("#userRoleAdmin").click(function () {
		 showHelp(this, 'userRoleHelp');
	});
	
	$("#userRoleAdmin").focus(function () {
		 showHelp(this, 'userRoleHelp');
	});
	
	$("#sendUserEmail").click(function () {
		 showHelp(this, 'sendUserEmailHelp');
	});
	
	$("#sendUserEmail").focus(function () {
		 showHelp(this, 'sendUserEmailHelp');
	});
	
	
	
	runValidations();
	
});


/*******************************************
VALIDATIONS
********************************************/

function runValidations() {
	
	$("#firstName").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#lastName").blur(function () {
		 if ( !isValidText($(this).val()) ) {
			showError(this, 'Contains invalid characters'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#email").blur(function () {
		 if ( !isEmail($(this).val()) ) {
			showError(this, 'Should be a valid email address <br /> in the format email@example.com'); 
		 } else {
			removeErrors(this); 
		 }
	});
	
	$("#curPassword").change(function () {
		 if (!isPassword($(this).val())) {
			 return false;
		 } else {
			 removeErrors($(this));
		 }
	});
	
	$("#newPassword").change(function () {
		 if (!isPassword($(this).val())) {
			showError($(this), "Please enter a valid password");
			return false;
		 } else {
			 removeErrors($(this));
		 }
		 comparePasswords($('#newPassword'), $('#confirmPassword'));
	});
	
	$("#confirmPassword").change(function () {
		 comparePasswords($('#newPassword'), $('#confirmPassword'));
	});
}
