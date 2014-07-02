/**********************************************************
NAME:	charCP.js
NOTES:	Contains all the scripts for the headers section
		in the Admin area
***********************************************************/

$(document).ready(function () {
	/* 
	 
	$("#headerName").focus(function () {
		 showHelp(this, 'nameHelp');
	});
	*/
	
	attachCPEvents();
	
});

function attachCPEvents() {
	$(".cpFld").change(function () {
		if (!isNumeric($(this).val())) {
			showError($(this), 'Please enter a number');					
		} else {
			removeError($(this));	
		}
	});
}

function addCPRow(addLink) {
	row = $('#' + addLink.id).parents('.cpRow'); // find parent row
	row.clone().appendTo('#addCPSection fieldset');
	var newRow = row.next();
	newRow.find('.deleteRowLink img').show();
	// Get new incremented index number
	var indexNum = addLink.id.split("_")[1];
	var newIndex = parseInt(indexNum) + 1;
	// Change ids of newly created form elements
	newRow.attr('id', 'addCPRow_' + newIndex);
	newRow.find('.cell input').attr('id', 'cp_' + newIndex);
	newRow.find('.cell input').attr('name', 'cp_' + newIndex);
	newRow.find('.cell2 select').attr('id', 'cat_' + newIndex);
	newRow.find('.cell2 select').attr('name', 'cat_' + newIndex);
	newRow.find('.cell3 input').attr('id', 'note_' + newIndex);
	newRow.find('.cell3 input').attr('name', 'note_' + newIndex);
	newRow.find('.cell4 .addRowLink').attr('id', 'addRowLink_' + newIndex);
	newRow.find('.cell4 .deleteRowLink').attr('id', 'deleteRowLink_' + newIndex);
	// Make scripts work on new row
	attachCPEvents();
}

// deleteLink: object (non-jquery object) of link user clicked
function deleteCPRow(deleteLink) {
	$('#' + deleteLink.id).parents('.cpRow').remove();
	$('#addCPRow1').find('.deleteRowLink img').hide();
}

/*******************************************
VALIDATIONS
********************************************/

function runValidations() {
	// alert('runValidations');
}

