/*******************************************
NAME:	library.js
NOTES:	This is the function utility library for the character generator.
***********************************************************/

/**********************************************************
VALIDATION FUNCTIONS
**********************************************************/

// Check a value against a regex
// Return true if there's a match, otherwise false
// String val: the value to check for a match
// String regex: the regex pattern to use for evaluation
function checkRegex(val, regex) {
	val += ''; // Convert to string if necessary to make sure search() method works
	if (val.search(regex) >= 0) {
		return true;
	} else {
		return false;
	}
}

function isEmpty(val) {
	if ($.trim(val) == '') {
		return true;
	} else {
		return false;
	}
}

function isNumeric(val) {
	var regex = "^(\-)?[0-9]*(\.[0-9]*)?$";
	return checkRegex(val, regex);
}

function isNegativeNum(num) {
	// alert("Is numeric? " + isNumeric(num));
	if (isNumeric(num) && num < 0 ) {
		return true;
	} else {
		return false;
	}
}

function isInteger(val) {
	var regex = "^[0-9]*$";
	return checkRegex(val, regex);
}

function isValidText(val) {
	var regex = /^[A-Za-z0-9 \.\,\#\"\'\(\)\-\_]*$/;
	return checkRegex(val, regex);
}

function isValidTextAdmin(val) {
	var regex = /^[A-Za-z0-9 \.\,\#\"\'\(\)\-\_\!]*$/;
	return checkRegex(val, regex);
}

function isValidTextArea(val) {
	var regex = /^[A-Za-z0-9 \(\)\.\,\"\'\?\!\:\;\-\_\<\>\*\/\n\r]*$/;
	return checkRegex(val, regex);
}

/* This function validates email addresses. It allows alphanumeric characters, plus hyphens, underscores, and periods in the user and domain names. It allows email addresses in the following formats (and combinations thereof):
	name@domain.com
	name@subdomain.domain.com
	name.name@domain.com
	name_name@domain.com
Rules:
	--There must be a user name of 1 or more characters before the @ sign
	--Address must include an @ sign
	--There must be a domain name of one or more characters
	--The domain extension must have 2 or more characters (.com, .net, .org, .tv, .au are all okay)
*/
function isEmail(val) {
	var regex = /^(([A-Za-z0-9\-\_]+\.)*[A-Za-z0-9\-\_]+@([A-Za-z0-9\-\_]+\.)+[A-Za-z]{2,6})?$/;
	return checkRegex(val, regex);
}

function isPassword(val) {
	var regex = /^[A-Za-z0-9\-\_\!\@\#\$]{6,20}$/;
	return checkRegex(val, regex);
}

function checkPasswordsMatch(password1, password2) {
	if (password1 == password2) {
		return true;
	} else {
		return false;
	}
}

function isValidURL(val) {
	var regex = /^[A-Za-z0-9\.\%\/\-\_\:]*$/;
	return checkRegex(val, regex);
}

function isValidHTMLTextArea(val) {
	var regex = /^[A-Za-z0-9 \(\)\.\,\;\"\'\?\!\<\>\%\&\/\n\r]*$/;
	return checkRegex(val, regex);
}

// passwordFld1: jquery object of password field
// passwordFld2: jquery object of password field
function comparePasswords(passwordFld1, passwordFld2) {
	if ((passwordFld1.val() != "") && (passwordFld2.val() != "")) {
		if (!checkPasswordsMatch(passwordFld1.val(), passwordFld2.val())) {
			showError(passwordFld2, 'New password and confirmation must match');
		} else {
			removeErrors(passwordFld2);
		}
	}
}

// This function adapted for jQuery from Dan Hersam
// http://dan.hersam.com/tools/smart-quotes.html
// Character codes can be found here:
// http://en.wikipedia.org/wiki/Windows-1252#Codepage_layout
// fldObj: jQuery object
function convert_smart_quotes(fldObj) {
	
	var s = fldObj.val();
	
	s = s.replace( /\u2018|\u2019|\u201A|\uFFFD/g, "'" );
	s = s.replace( /\u201c|\u201d|\u201e/g, '"' );
	s = s.replace( /\u02C6/g, '^' );
	s = s.replace( /\u2039/g, '<' );
	s = s.replace( /\u203A/g, '>' );
	s = s.replace( /\u2013/g, '-' );
	s = s.replace( /\u2014/g, '--' );
	s = s.replace( /\u2026/g, '...' );
	s = s.replace( /\u00A9/g, '(c)' );
	s = s.replace( /\u00AE/g, '(r)' );
	s = s.replace( /\u2122/g, 'TM' );
	s = s.replace( /\u00BC/g, '1/4' );
	s = s.replace( /\u00BD/g, '1/2' );
	s = s.replace( /\u00BE/g, '3/4' );
	s = s.replace(/[\u02DC|\u00A0]/g, " ");

	fldObj.val(s);
}

/**********************************************************
ERROR HANDLER FUNCTIONS
**********************************************************/

// fld: jquery object
function showError(fld, msg) {
	removeErrors(fld);
	
	fld = $(fld); // extend for jQuery if necessary
	
	// Add a class to the parent row
	fld.closest('.row').addClass('error');
	
	if (fld.next('.fldModifier').length > 0) {
		// If there is a text modifier after the field (e.g. [qty] people)
		// Put the error message after that. 
		var fldModifier = fld.next('.fldModifier');
		fldModifier.after("<span class='errorMsg'>" + msg + "</span>");
	} else if (fld.next('.ui-datepicker-trigger').length > 0) {
		// If there is a calendar image next to the field,
		// Put the error message after that. 
		var calendarImg = fld.next('.ui-datepicker-trigger');
		calendarImg.after("<span class='errorMsg'>" + msg + "</span>");
	} else if (fld.next('.unit').length > 0) {
		var unitElem = fld.next('.unit');
		unitElem.after("<span class='errorMsg'>" + msg + "</span>");
	} else if (fld.nextAll('.hint').length > 0) {
		// If there is a text modifier after the field (e.g. [qty] people)
		// Put the error message after that. 
		var fldHint = fld.nextAll('.hint');
		fldHint.before("<span class='errorMsg'>" + msg + "</span>");
	} else {
		// Otherwise, insert error message immediately after field
		fld.after("<span class='errorMsg'>" + msg + "</span>");
	}
}

// fld: jquery object
function showErrorAndFade(fld, msg) {
	// Insert error message immediately after field, and then fade out error
	fld.after("<span class='errorMsg'>" + msg + "</span>");
	
	// Show error without fading for 5 sec, then fade out over 5 sec
	var error = fld.next('.errorMsg');
	var t = setTimeout( function() {
		error.fadeOut(5000);
	}, 5000);
}

function showTableError(fld, msg) {
	removeTableErrors(fld);
	
	fld = $(fld); // extend for jQuery if necessary
	
	// Add a class to the parent row
	fld.closest('td').addClass('error');
	
	if (fld.next('.fldModifier').length > 0) {
		// If there is a text modifier after the field (e.g. [qty] people)
		// Put the error message after that. 
		var fldModifier = fld.next('.fldModifier');
		fldModifier.after("<span class='errorMsg'>" + msg + "</span>");
	} else {
		// Otherwise, insert error message immediately after field
		fld.after("<span class='errorMsg'>" + msg + "</span>");
	}
}

// fld: jquery object
function removeErrors(fld) {
	
	fld = $(fld); // extend for jQuery if necessary
	
	// Remove error class from parent row
	var row = fld.closest('.row');
	row.removeClass('error');
	
	// Find all error messages under the field's parent row
	row.find('.errorMsg').each(function() {
		$(this).remove();
	});
}

// fld: jquery object
function removeTableErrors(fld) {
	
	fld = $(fld); // extend for jQuery if necessary
	
	// Remove error class from parent row
	var cell = fld.closest('td');
	cell.removeClass('error');
	
	// Find all error messages under the field's parent row
	cell.find('.errorMsg').each(function() {
		$(this).remove();
	});
}

/* OLD VERSIONS 
// fld: jquery input fld object
function showError(fld, msg) {
	removeError(fld); // Remove all previous errors to prevent errors from piling up
	$(fld).closest('.cell').addClass('error'); // put error style class on parent cell
	$(fld).closest('.cell').append('<span class="errorMsg">' + msg + "</span>"); // Show error message
}

// fld: jquery input fld object
function removeError(fld) {
	$(fld).closest('.cell').removeClass('error'); // Remove error style class from parent cell
	$(fld).closest('.cell').find('span.errorMsg').remove(); // Remove error message from DOM
}
*/

/***********************************************
SHOW/HIDE FUNCTIONS
***********************************************/

// tabObj: jQuery object
function changeTab(tabObj, sectionID) {
	// Find all sections and hide them
	$('.section-tabbed').each(function() {
		$(this).hide();
	});
	// tabLink = $('#' + tabObj.id);

	$('#' + sectionID).show();
	// Find parent tabPanel
	tabObj.closest('.tabPanel').find('a').each(function() {
		$(this).removeClass('selected');
	});
	tabObj.addClass('selected');
}

// Animate and remove a table row
function fadeAndRemoveRow(elem, tableID) {
	var parentRow = $(elem).closest('tr');
	parentRow.animate({'backgroundColor':'#fb6c6c'},300);
	parentRow.fadeOut(300,function() {
		parentRow.remove();
		stripeTableRows(tableID);
	});
	return true;
}

function hideAllMenus() {
  $('.menu').each(function() {
		$(this).hide();
	});
}


/***********************************************
HELP FUNCTIONS
***********************************************/

// helpDiv: string id of help div to be shown
function showHelp(fld, helpDiv) {
	// Start by hiding all existing help
	hideAllHelp();
	
	// Find current position of field
	fld = $('#' + fld.id);
	var curPosition = fld.position();
	
	// alert('top: ' + curPosition.top);
	$('#' + helpDiv).show();
	$('#' + helpDiv).css('top', curPosition.top - 15);
	
	// Show arrow pointing left
	$('#helpArrow').show();
	$('#helpArrow').css('top', curPosition.top);
}

// helpDiv: string id of help div to be shown
function hideHelp(helpDiv) {
	$('#' + helpDiv).hide();
}

function hideAllHelp() {
	$('.help').each(function() {
		$(this).hide();
	});
	$('#helpArrow').hide();
}

// lnk: object (passed as "this" from link
// popup: string id of help div to be shown
function showInfoPopup(lnk, popup) {
	popup = $('#' + popup); // extend for jquery
	// Find current position of link
	lnk = $('#' + lnk.id);
	var curPosition = lnk.position();
	
	// alert('top: ' + curPosition.top);
	popup.show();
	popup.css('top', curPosition.top);
	popup.css('left', curPosition.left + 180);
	
}

/***********************************************
MISC FUNCTIONS
***********************************************/

// tableID: ID of the table to stripe
function stripeTableRows(tableID) {
	// alert('stripeTableRows: ' + tableID);
	var table = $('#' + tableID);
	var rowIndex = 0;
	table.find('tbody tr').each(function() {
		if (rowIndex % 2 == 0) {
			$(this).removeClass('odd');
			$(this).addClass('even');
		} else {
			$(this).removeClass('even');
			$(this).addClass('odd');
		}
		rowIndex++;
	});
}

function closeAllMenus() {
	$('.menu').hide();
}

// filename: string name of page including extension, e.g. 'index.php'
function goToPage(filename) {
	window.location.href=filename;
}


/* Stolen from Google--modify for CG 
var myxmlhttp;
var isBrowserCompatible;
ratingMsgs = new Array(6);
ratingMsgColors = new Array(6);
barColors = new Array(6);

ratingMsgs[0] = "Too short";
ratingMsgs[1] = "Weak";
ratingMsgs[2] = "Fair";
ratingMsgs[3] = "Good";
ratingMsgs[4] = "Strong";
ratingMsgs[5] = "Not rated";

ratingMsgColors[0] = "#676767";
ratingMsgColors[1] = "#aa0033";
ratingMsgColors[2] = "#f5ac00";
ratingMsgColors[3] = "#6699cc";
ratingMsgColors[4] = "#008000";
ratingMsgColors[5] = "#676767";

barColors[0] = "#dddddd";
barColors[1] = "#aa0033";
barColors[2] = "#ffcc33";
barColors[3] = "#6699cc";
barColors[4] = "#008000";
barColors[5] = "#676767"; 

function CreateRatePasswdReq(formKey) {
	if (!isBrowserCompatible) {
		return;
	}
	
	var passwd = document.forms[formKey].Passwd.value;
	
	if (document.forms[formKey].Email) {
		var email = escape(document.forms[formKey].Email.value);
	} else {
		var email = escape("kikikayak@gmail.com");
	}
	
	if (document.forms[formKey].LastName) {
		var lastname = escape(document.forms[formKey].LastName.value);
	}
	
	if (document.forms[formKey].FirstName) {
		var firstname = escape(document.forms[formKey].FirstName.value);
	}
	
	if (document.forms[formKey].Birthday) {
		var birthday = escape(document.forms[formKey].Birthday.value);
	}
	
	var min_passwd_len = 8;
	var passwdKey = "Passwd";
	var emailKey = "Email";
	var FirstNameKey = "FirstName";
	var LastNameKey = "LastName";
	var BirthdayKey = "Birthday";
	
	if (passwd.length < min_passwd_len) {
		if (passwd.length > 0) {
			DrawBar(0);
		} else {
			resetBar();
		}
	} else {
		passwd = escape(passwd);
		var params = passwdKey + "=" + passwd + "&" +
		emailKey + "=" + email + "&" +
		FirstNameKey + "=" + firstname + "&" +
		LastNameKey + "=" + lastname + "&" +
		BirthdayKey + "=" + birthday;
		myxmlhttp = CreateXmlHttpReq(RatePasswdXmlHttpHandler);
		XmlHttpPOST(myxmlhttp, "RatePassword", params);
	}
}

function RatePasswdXmlHttpHandler() {
	if (myxmlhttp.readyState != 4) {
		return;
	}
	rating = parseInt(myxmlhttp.responseText);
	DrawBar(rating);
}

function DrawBar(rating) {
	var posbar = getElement('posBar');
	var negbar = getElement('negBar');
	var passwdRating = getElement('passwdRating');
	var barLength = getElement('passwdBarDiv').width;
	if (rating >= 0 && rating <= 4) {
		posbar.style.width = barLength / 4 * rating;
		negbar.style.width = barLength / 4 * (4 - rating);
	} else {
		posbar.style.width = 0;
		negbar.style.width = barLength;
		rating = 5;
	}
	posbar.style.background = barColors[rating];
	passwdRating.innerHTML = "<font color='" + ratingMsgColors[rating] + "'>" + ratingMsgs[rating] + "</font>";
}

function resetBar() {
	var posbar = getElement('posBar');
	var negbar = getElement('negBar');
	var passwdRating = getElement('passwdRating');
	var barLength = getElement('passwdBar').width;
	posbar.style.width = "0px";
	negbar.style.width = barLength + "px";
	passwdRating.innerHTML = "";
} 

End of Google code */
