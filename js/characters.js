/**********************************************************
NAME:	characters.js
NOTES:	Contains all the scripts for the characters section
		in the Admin area
***********************************************************/

$(document).ready(function () {

	$("#playerName").autocomplete({
		source: '../ajax/player.handler.php?ajaxAction=getPlayerSuggestions'
	});

	$("#charName").autocomplete({
		source: '../ajax/admin.handler.php?ajaxAction=getCharacterSuggestions'
	});

	$("#skillName").autocomplete({
		source: '../ajax/admin.handler.php?ajaxAction=getSkillSuggestions'
	});

	$("#headerName").autocomplete({
		source: '../ajax/admin.handler.php?ajaxAction=getHeaderSuggestions'
	});

	$("#spellName").autocomplete({
		source: '../ajax/admin.handler.php?ajaxAction=getSpellSuggestions'
	});

	$("#featName").autocomplete({
		source: '../ajax/admin.handler.php?ajaxAction=getFeatSuggestions'
	});

});

/*******************************************
VALIDATIONS
********************************************/

function setupCharTransferValidations() {
	$('#playerID').change(function() {
		if (!isEmpty($(this).val()) && isInteger($(this).val())) {
			removeErrors($(this));
		}
	});
}

function runCharTransferValidations() {
	playerFld = $('#transferCharForm #playerID');
	if (isEmpty(playerFld.val())) {
		showError(playerFld, 'Please select a player');
		return false;
	}
	if (!isInteger(playerFld.val())) {
		showError(playerFld, 'Invalid player');
		return false;
	} else {
		removeErrors(playerFld);
		return true;
	}
}
