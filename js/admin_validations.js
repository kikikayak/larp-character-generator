/**********************************************************
NAME:	admin_validations.js
NOTES:	Contains all the client-side validation logic for the Admin area
***********************************************************/

function runValidations() {
	// alert('runValidations');
	
	/* SPELL VALIDATIONS */

	if ($('#spellAdminPage')) {
		
		$("#spellName").blur(function () {
			if ( !isValidText($(this).val()) ) {
				showError(this, 'Contains invalid characters');
			} else {
				removeErrors(this);
			}
		});
		
		$("#spellCost").blur(function () {
			if ( !isNumeric($(this).val()) ) {
				showError(this, 'Must be numeric');
			} else {
				removeErrors(this);
			}
		});
			
		$("#spellShortDescription").blur(function () {
			if ( !isValidTextArea($(this).val()) ) {
				showError(this, 'Contains invalid characters');
			} else {
				removeErrors(this);
			}
		});
		
		$("#spellDescription").blur(function () {
			if ( !isValidTextArea($(this).val()) ) {
				showError(this, 'Contains invalid characters');
			} else {
				removeErrors(this);
			}
		});
		
		$("#spellCheatSheetNote").blur(function () {
			if ( !isValidText($(this).val()) ) {
				showError(this, 'Contains invalid characters');
			} else {
				removeErrors(this);
			}
		});
		
		$(".attributeCost").blur(function () {
			if ( !isInteger($(this).val()) ) {
				showError(this, 'Must be a whole number');
			} else {
				removeErrors(this);
			}
		});
	} // end of spells page condition

	/* COUNTRY VALIDATIONS */

	if ($('#countryAdminPage')) {

		$("#countryName").blur(function () {
			if ( !isValidText($(this).val()) ) {
				showError(this, 'Contains invalid characters');
			} else {
				removeErrors(this);
			}
		});
		
		$("#countryDescription").blur(function () {
			if ( !isValidTextArea($(this).val()) ) {
				showError(this, 'Contains invalid characters');
			} else {
				removeErrors(this);
			}
		});
		
	} // end of countries page condition

	/* COMMUNITY VALIDATIONS */

	if ($('#communityAdminPage')) {

		$("#communityName").blur(function () {
			if ( !isValidText($(this).val()) ) {
				showError(this, 'Contains invalid characters');
			} else {
				removeErrors(this);
			}
		});

	} // end of communities validations

	/* TRAIT VALIDATIONS */

	if ($('#traitAdminPage')) {

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

	} // end of trait validations

	/* RACE VALIDATIONS */

	if ($('#raceAdminPage')) {

		$("#raceName").blur(function () {
			if ( !isValidText($(this).val()) ) {
				showError(this, 'Contains invalid characters'); 
			} else {
				removeErrors(this); 
			}
		});

	} // end of communities validations

	/* VALIDATIONS FOR ADD CP DIALOG */
	if ($('#cpAddDialog')) {

		$("#numberCP").blur(function () {
			validateNumberCP($(this));
		});
		
		$("#CPNote").blur(function () {
			validateCPNote($(this));
		});
		
		$("#characterID").change(function () {
			removeAssignToErrors();
		});
		
		$("#playerID").change(function () {
			removeAssignToErrors();
		});

	} // end of add CP dialog validations
		
} // end of runValidations

