/**********************************************************
NAME:	admin.js
NOTES:	Contains all the scripts for the admin section
***********************************************************/

/***********************************************
INITIALIZATION FUNCTIONS
***********************************************/


function init() {
 
	runValidations();

	stripeTableRows('playerList');
	stripeTableRows('deletedCharList');

	// Make all select boxes pretty
	$('select:visible').chosen({disable_search_threshold: 2});

	/********************************
	ABOUT DIALOG
	*********************************/

	var doClose = function() {
		//close the dialog
		$("#aboutDialog").dialog("close");
	};

	var dialogOpts = {
		modal: true,
		title: "About the Character Generator",
		buttons: {
			"Close": doClose
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$("#aboutDialog").dialog(dialogOpts);

	//define click handler for the button
	$("#aboutLink").click(function() {
		$("#aboutDialog").dialog("open");
	});

	/********************************
	CHARACTER ACCESS
	*********************************/

	$(".accessDropdown").change(function () {
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

	/********************************
	HELP
	*********************************/

	$("#help .closeLink").each(function() {
		$(this).click(function() {
			$(this).closest(".help").hide();
			$("#helpArrow").hide();
			return false;
		});
	});

	/* SPELLS HELP */

	$("#spellName").focus(function () {
		showHelp(this, 'spellNameHelp');
	});
	
	$("#spellCost").focus(function () {
		showHelp(this, 'spellCostHelp');
	});
	
	$("#skillID").focus(function () {
		showHelp(this, 'skillIDHelp');
	});
	
	$("#spellShortDescription").focus(function () {
		showHelp(this, 'spellShortDescriptionHelp');
	});
	
	$("#spellDescription").focus(function () {
		showHelp(this, 'spellDescriptionHelp');
	});
	
	$("#spellCheatSheetNote").focus(function () {
		showHelp(this, 'spellCheatSheetNoteHelp');
	});
	
	$("#attributeCost1").focus(function () {
		showHelp(this, 'attributeHelp');
	});
	
	$("#attribute1").focus(function () {
		showHelp(this, 'attributeHelp');
	});
	
	$("#attributeCost2").focus(function () {
		showHelp(this, 'attributeHelp');
	});
	
	$("#attribute2").focus(function () {
		showHelp(this, 'attributeHelp');
	});
	
	$("#attributeCost3").focus(function () {
		showHelp(this, 'attributeHelp');
	});
	
	$("#attribute3").focus(function () {
		showHelp(this, 'attributeHelp');
	});
	
	$("#spellAccess").focus(function () {
		showHelp(this, 'accessHelp');
	});

	/* COUNTRY HELP */

	$("#countryName").focus(function () {
		showHelp(this, 'countryNameHelp');
	});
	
	$("#countryDefault").focus(function () {
		showHelp(this, 'countryDefaultHelp');
	});

	/* COMMUNITIES HELP */

	$("#communityName").focus(function () {
		showHelp(this, 'communityNameHelp');
	});
	
	$("#communityDescription").focus(function () {
		showHelp(this, 'communityDescriptionHelp');
	});

	/* TRAIT HELP */

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

	/* RACE HELP */
	$("#raceName").focus(function () {
		showHelp(this, 'raceNameHelp');
	});

	/********************************
	PLAYER DELETE DIALOG
	*********************************/

	var playerDeleteDialogOpts = {
		modal: true,
		title: "Delete Player?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				// Figure out current character ID
				var playerID = $('#deletePlayerID').val();
				var data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/player.handler.php?ajaxAction=deletePlayer', data, function() {
					$('#playerDeleteDialog').dialog('close');
					fadeAndRemoveRow('#playerID_' + playerID, 'playerList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#playerDeleteDialog').dialog(playerDeleteDialogOpts);

	//define click handler for the button
	$('#playerList .deleteLink').click(function() {
		// Find characterID of clicked row
		var playerID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load character info into dialog div
		var data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#playerDeleteDialog').load('../ajax/player.handler.php?ajaxAction=loadPlayerDeleteDialog', data, function() {
			// On success, open dialog
			$('#playerDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowPlayerDelete').val() == 'no') {
				$('#playerDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			}
		});
		hideAllMenus();
		return false;

	}); // end deleteLink click handler

	/* END PLAYER DELETE DIALOG */

	/********************************
	PLAYER PURGE DIALOG
	*********************************/

	var playerPurgeDialogOpts = {
		modal: true,
		title: "Purge Player?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current player ID
				var playerID = $('#purgePlayerID').val();
				var data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/player.handler.php?ajaxAction=purgePlayer', data, function() {
					$('#playerPurgeDialog').dialog('close');
					fadeAndRemoveRow('#playerID_' + playerID, 'deletedPlayerList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#playerPurgeDialog').dialog(playerPurgeDialogOpts);

	//define click handler for the button
	$('.purgePlayerLink').click(function() {
		// Find playerID of clicked row
		var playerID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load player info into dialog div
		var data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#playerPurgeDialog').load('../ajax/player.handler.php?ajaxAction=loadPlayerPurgeDialog', data, function() {
			$('#playerPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgePlayerLink click handler

	/* END PLAYER PURGE DIALOG */

	/********************************
	UNDELETE PLAYER DIALOG
	*********************************/

	//define click handler for the button
	$('.undeletePlayerLink').click(function() {
		// Find playerID of clicked row
		var playerID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load player info into dialog div
		var data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#msg').load('../ajax/player.handler.php?ajaxAction=undeletePlayer', data, function() {
			fadeAndRemoveRow('#playerID_' + playerID, 'deletedPlayerList');
		});
		hideAllMenus();
		return false;
	}); // end undeletePlayerLink click handler

	/* END PLAYER UNDELETE DIALOG */

	/********************************
	UNDELETE CHARACTER
	*********************************/

	// define click handler for the button
	$('.undeleteCharLink').click(function() {
		closeAllMenus();
		// Find characterID of clicked row
		var charID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load character info into dialog div
		var data = {characterID: charID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#msg').load('../ajax/character.handler.php?ajaxAction=undeleteCharacter', data, function() {
			fadeAndRemoveRow('#characterID_' + charID, 'deletedCharList');
		});
		return false;
	}); // end undeleteCharacterLink click handler

	/********************************
	CHARACTER PURGE DIALOG
	*********************************/

	var charPurgeDialogOpts = {
		modal: true,
		title: "Purge Character?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current character ID
				var charID = $('#purgeCharID').val();
				var data = {characterID: charID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/character.handler.php?ajaxAction=purgeCharacter', data, function() {
					$('#charPurgeDialog').dialog('close');
					fadeAndRemoveRow('#characterID_' + charID, 'deletedCharList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#charPurgeDialog').dialog(charPurgeDialogOpts);

	//define click handler for the button
	$('.purgeCharLink').click(function() {
		// Find characterID of clicked row
		var charID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load character info into dialog div
		var data = {characterID: charID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#charPurgeDialog').load('../ajax/character.handler.php?ajaxAction=loadCharPurgeDialog', data, function() {
			$('#charPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeCharLink click handler

	/* END PURGE DIALOG */

	/********************************
	CP PURGE DIALOG
	*********************************/

	var CPPurgeDialogOpts = {
		modal: true,
		title: "Purge CP Record?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current CP ID
				var CPTrackID = $('#purgeCPTrackID').val();
				var data = {CPTrackID: CPTrackID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/cp.handler.php?ajaxAction=purgeCP', data, function() {
					$('#cpPurgeDialog').dialog('close');
					fadeAndRemoveRow('#CPTrackID_' + CPTrackID, 'deletedCPList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#cpPurgeDialog').dialog(CPPurgeDialogOpts);

	//define click handler for the button
	$('.purgeCPLink').click(function() {
		// Find CPTrackID of clicked row
		var CPTrackID = $(this).closest('tr').find('.dateCol input[type=hidden]').val();

		// Load CP info into dialog div
		var data = {CPTrackID: CPTrackID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#cpPurgeDialog').load('../ajax/cp.handler.php?ajaxAction=loadCPPurgeDialog', data, function() {
			$('#cpPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeCPLink click handler

	/* END CP PURGE DIALOG */

	/********************************
	UNDELETE CP DIALOG
	*********************************/

	//define click handler for the button
	$('.undeleteCPLink').click(function() {
		// Find CPTrackID of clicked row
		var CPTrackID = $(this).closest('tr').find('.dateCol input[type=hidden]').val();
		// alert(CPTrackID);

		// Load CP info into dialog div
		var data = {CPTrackID: CPTrackID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#msg').load('../ajax/cp.handler.php?ajaxAction=undeleteCP', data, function() {
			fadeAndRemoveRow('#CPTrackID_' + CPTrackID, 'deletedCPList');
		});
		hideAllMenus();
		return false;
	}); // end undeleteCPLink click handler

	/* END CP UNDELETE DIALOG */

	/********************************
	COUNTRY DELETE DIALOG
	*********************************/

	var countryDeleteDialogOpts = {
		modal: true,
		title: "Delete Country?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteCountry();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteCountry() {
		// Figure out current country ID
		var countryID = $('#deleteCountryID').val();
		var data = {countryID: countryID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/admin.handler.php?ajaxAction=deleteCountry', data, function() {
			$('#countryDeleteDialog').dialog('close');
			fadeAndRemoveRow('#countryID_' + countryID, 'countryList');
		});
	}

	//create the dialog
	$('#countryDeleteDialog').dialog(countryDeleteDialogOpts);

	//define click handler for the button
	$('#countryList .deleteLink').click(function() {
		// Find countryID of clicked row
		var countryID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load country info into dialog div
		var data = {countryID: countryID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#countryDeleteDialog').load('../ajax/admin.handler.php?ajaxAction=loadCountryDeleteDialog', data, function() {
			// On success, open dialog
			$('#countryDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowCountryDelete').val() == 'no') {
				$('#countryDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#countryDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteCountry();
					}
				});
			}

		});
		hideAllMenus();
		return false;
	}); // end deleteLink click handler

	/* END COUNTRY DELETE */

	/********************************
	COUNTRY PURGE DIALOG
	*********************************/

	var countryPurgeDialogOpts = {
		modal: true,
		title: "Purge Country?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				purgeCountry();
			}
		},
		autoOpen: false,
		width: 500
	};

	// create the dialog
	$('#purgeDialog').dialog(countryPurgeDialogOpts);

	// define click handler for the button
	$('.purgeCountryLink').click(function() {
		closeAllMenus();
		// Find countryID of clicked row
		var countryID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load country info into dialog div
		var data = {countryID: countryID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#purgeDialog').load('../ajax/admin.handler.php?ajaxAction=loadCountryPurgeDialog', data, function() {
			// On success, open dialog
			$('#purgeDialog').dialog('open');

		});
		return false;
	}); // end purgeCountryLink click handler

	// Actually perform the purge
	function purgeCountry() {
		// Figure out current country ID
		var countryID = $('#purgeCountryID').val();
		var data = {countryID: countryID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/gameWorld.handler.php?ajaxAction=purgeCountry', data, function() {
			$('#purgeDialog').dialog('close');
			fadeAndRemoveRow('#countryID_' + countryID, 'deletedCountryList');
		});
	}

	/* END PERMANENT COUNTRY DELETE */

	/********************************
	UNDELETE COUNTRY
	*********************************/

	// define click handler for the button
	$('.undeleteCountryLink').click(function() {
		closeAllMenus();
		// Find countryID of clicked row
		var countryID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load country info into dialog div
		var data = {countryID: countryID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#msg').load('../ajax/gameWorld.handler.php?ajaxAction=undeleteCountry', data, function() {
			fadeAndRemoveRow('#countryID_' + countryID, 'countryList');
		});
		return false;
	}); // end undeleteCountryLink click handler

	/********************************
	HEADER PURGE DIALOG
	*********************************/

	var headerPurgeDialogOpts = {
		modal: true,
		title: "Purge Header?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current header ID
				var headerID = $('#purgeHeaderID').val();
				var data = {headerID: headerID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/admin.handler.php?ajaxAction=purgeHeader', data, function() {
					$('#headerPurgeDialog').dialog('close');
					fadeAndRemoveRow('#headerID_' + headerID, 'deletedHeaderList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#headerPurgeDialog').dialog(headerPurgeDialogOpts);

	//define click handler for the button
	$('.purgeHeaderLink').click(function() {
		// Find headerID of clicked row
		var headerID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load header info into dialog div
		var data = {headerID: headerID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#headerPurgeDialog').load('../ajax/admin.handler.php?ajaxAction=loadHeaderPurgeDialog', data, function() {
			$('#headerPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeHeaderLink click handler

	/* END HEADER PURGE DIALOG */

	/********************************
	HEADER UNDELETE
	*********************************/

	// define click handler for the button
	$('.undeleteHeaderLink').click(function() {
		// Find ID of clicked row
		var headerID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load header info into dialog div
		var data = {headerID: headerID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#msg').load('../ajax/admin.handler.php?ajaxAction=undeleteHeader', data, function() {
			fadeAndRemoveRow('#headerID_' + headerID, 'deletedHeaderList');
		});
		return false;
	}); // end undeleteHeaderLink click handler

	/* END HEADER UNDELETE */

	/********************************
	RACE DELETE DIALOG
	*********************************/

	var raceDeleteDialogOpts = {
		modal: true,
		title: "Delete Race?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteRace();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteRace() {
		// Figure out current race ID
		var raceID = $('#deleteRaceID').val();
		var data = {raceID: raceID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/admin.handler.php?ajaxAction=deleteRace', data, function() {
			$('#raceDeleteDialog').dialog('close');
			fadeAndRemoveRow('#raceID_' + raceID, 'raceList');
		});
		hideAllMenus();
	}

	//create the dialog
	$('#raceDeleteDialog').dialog(raceDeleteDialogOpts);

	//define click handler for the button
	$('#raceList .deleteLink').click(function() {
		// Find ID of clicked row
		var raceID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load race info into dialog div
		var data = {raceID: raceID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#raceDeleteDialog').load('../ajax/admin.handler.php?ajaxAction=loadRaceDeleteDialog', data, function() {
			// On success, open dialog
			$('#raceDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowRaceDelete').val() == 'no') {
				$('#raceDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#raceDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteRace();
					}
				});
			}

		});
		return false;
	}); // end deleteLink click handler

	/* END RACE DELETE */

	/********************************
	SKILL PURGE DIALOG
	*********************************/

	var skillPurgeDialogOpts = {
		modal: true,
		title: "Purge Skill?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current skill ID
				var skillID = $('#purgeSkillID').val();
				var data = {skillID: skillID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/admin.handler.php?ajaxAction=purgeSkill', data, function() {
					$('#skillPurgeDialog').dialog('close');
					fadeAndRemoveRow('#skillID_' + skillID, 'deletedSkillList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	// Create the dialog
	$('#skillPurgeDialog').dialog(skillPurgeDialogOpts);

	// Define click handler for the button
	$('.purgeSkillLink').click(function() {
		// Find ID of clicked row
		var skillID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load skill info into dialog div
		var data = {skillID: skillID}; // Create object/map of data to pass in AJAX call

		// Load skill info into dialog
		$('#skillPurgeDialog').load('../ajax/admin.handler.php?ajaxAction=loadSkillPurgeDialog', data, function() {
			$('#skillPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeSkillLink click handler

	/* END PURGE SKILL */

	/********************************
	UNDELETE SKILL
	*********************************/

	// Define click handler for the button
	$('.undeleteSkillLink').click(function() {
		// Find ID of clicked row
		var skillID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load skill info into dialog div
		var data = {skillID: skillID}; // Create object/map of data to pass in AJAX call

		// Undelete the skill and display success/failure
		$('#msg').load('../ajax/admin.handler.php?ajaxAction=undeleteSkill', data, function() {
			fadeAndRemoveRow('#skillID_' + skillID, 'deletedSkillList');
		});
		hideAllMenus();
		return false;
	}); // end deletedSkillList click handler

	/* END UNDELETE SKILL */

	/********************************
	SPELL PURGE DIALOG
	*********************************/

	var spellPurgeDialogOpts = {
		modal: true,
		title: "Purge Spell?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current spell ID
				var spellID = $('#purgeSpellID').val();
				var data = {spellID: spellID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/admin.handler.php?ajaxAction=purgeSpell', data, function() {
					$('#spellPurgeDialog').dialog('close');
					fadeAndRemoveRow('#spellID_' + spellID, 'deletedSpellList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	// Create the dialog
	$('#spellPurgeDialog').dialog(spellPurgeDialogOpts);

	// Define click handler for the button
	$('.purgeSpellLink').click(function() {
		// Find ID of clicked row
		var spellID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load spell info into dialog div
		var data = {spellID: spellID}; // Create object/map of data to pass in AJAX call

		// Load spell info into dialog
		$('#spellPurgeDialog').load('../ajax/admin.handler.php?ajaxAction=loadSpellPurgeDialog', data, function() {
			$('#spellPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeSpellLink click handler

	/* END PURGE SPELL */

	/********************************
	UNDELETE SPELL
	*********************************/

	// Define click handler for the button
	$('.undeleteSpellLink').click(function() {
		// Find ID of clicked row
		var spellID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load spell info into dialog div
		var data = {spellID: spellID}; // Create object/map of data to pass in AJAX call

		// Load spell info into dialog
		$('#msg').load('../ajax/admin.handler.php?ajaxAction=undeleteSpell', data, function() {
			fadeAndRemoveRow('#spellID_' + spellID, 'deletedSpellList');
		});
		hideAllMenus();
		return false;
	}); // end undeleteSpell click handler

	/* END UNDELETE SPELL */

	/********************************
	TRAIT DELETE DIALOG
	*********************************/

	var traitDeleteDialogOpts = {
		modal: true,
		title: "Delete Trait?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteTrait();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteTrait() {
		// Figure out current ID
		var traitID = $('#deleteTraitID').val();
		var data = {traitID: traitID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/admin.handler.php?ajaxAction=deleteTrait', data, function() {
			$('#traitDeleteDialog').dialog('close');
			fadeAndRemoveRow('#traitID_' + traitID, 'traitList');
		});
	}

	// create the dialog
	$('#traitDeleteDialog').dialog(traitDeleteDialogOpts);

	// define click handler for the button
	$('#traitList .deleteLink').click(function() {
		// Find ID of clicked row
		var traitID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load character info into dialog div
		var data = {traitID: traitID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#traitDeleteDialog').load('../ajax/admin.handler.php?ajaxAction=loadTraitDeleteDialog', data, function() {
			// On success, open dialog
			$('#traitDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowTraitDelete').val() == 'no') {
				$('#traitDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#traitDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteTrait();
					}
				});
			}

		});
		return false;

	}); // end deleteLink click handler

	/********************************
	TRAIT PURGE DIALOG
	*********************************/

	var traitPurgeDialogOpts = {
		modal: true,
		title: "Purge Trait?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current trait ID
				var traitID = $('#purgeTraitID').val();
				var data = {traitID: traitID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/admin.handler.php?ajaxAction=purgeTrait', data, function() {
					$('#traitPurgeDialog').dialog('close');
					fadeAndRemoveRow('#traitID_' + traitID, 'deletedTraitList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	// Create the dialog
	$('#traitPurgeDialog').dialog(traitPurgeDialogOpts);

	// Define click handler for the button
	$('.purgeTraitLink').click(function() {
		// Find ID of clicked row
		var traitID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load trait info into dialog div
		var data = {traitID: traitID}; // Create object/map of data to pass in AJAX call

		// Load spell info into dialog
		$('#traitPurgeDialog').load('../ajax/admin.handler.php?ajaxAction=loadTraitPurgeDialog', data, function() {
			$('#traitPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeTraitLink click handler

	/* END PURGE TRAIT */

	/********************************
	TRAIT UNDELETE DIALOG
	*********************************/

	// Define click handler for the button
	$('.undeleteTraitLink').click(function() {
		// Find ID of clicked row
		var traitID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load trait info into dialog div
		var data = {traitID: traitID}; // Create object/map of data to pass in AJAX call

		// Load spell info into dialog
		$('#msg').load('../ajax/admin.handler.php?ajaxAction=undeleteTrait', data, function() {
			fadeAndRemoveRow('#traitID_' + traitID, 'deletedTraitList');
		});
		hideAllMenus();
		return false;
	}); // end undeleteTraitLink click handler

	/* END UNDELETE TRAIT */

	/********************************
	COMMUNITY DELETE DIALOG
	*********************************/

	var communityDeleteDialogOpts = {
		modal: true,
		title: "Delete Community?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteCommunity();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteCommunity() {
		// Figure out current ID
		var communityID = $('#deleteCommunityID').val();
		var data = {communityID: communityID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/admin.handler.php?ajaxAction=deleteCommunity', data, function() {
			$('#communityDeleteDialog').dialog('close');
			fadeAndRemoveRow('#communityID_' + communityID, 'communityList');
		});
	}

	// create the dialog
	$('#communityDeleteDialog').dialog(communityDeleteDialogOpts);

	// define click handler for the button
	$('#communityList .deleteLink').click(function() {
		// Find ID of clicked row
		var communityID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load character info into dialog div
		var data = {communityID: communityID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#communityDeleteDialog').load('../ajax/admin.handler.php?ajaxAction=loadCommunityDeleteDialog', data, function() {
			// On success, open dialog
			$('#communityDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowCommunityDelete').val() == 'no') {
				$('#communityDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#communityDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteCommunity();
					}
				});
			}

		});
		hideAllMenus();
		return false;

	}); // end deleteLink click handler

	/* END COMMUNITY DELETE */

	/********************************
	COMMUNITY PURGE DIALOG
	*********************************/

	var communityPurgeDialogOpts = {
		modal: true,
		title: "Purge Community?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				// Figure out current community ID
				var communityID = $('#purgeCommunityID').val();
				var data = {communityID: communityID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/admin.handler.php?ajaxAction=purgeCommunity', data, function() {
					$('#communityPurgeDialog').dialog('close');
					fadeAndRemoveRow('#communityID_' + communityID, 'deletedCommunityList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#communityPurgeDialog').dialog(communityPurgeDialogOpts);

	// define click handler for the button
	$('.purgeCommunityLink').click(function() {
		// Find communityID of clicked row
		var communityID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load community info into dialog div
		var data = {communityID: communityID}; // Create object/map of data to pass in AJAX call

		// Load community info into dialog
		$('#communityPurgeDialog').load('../ajax/admin.handler.php?ajaxAction=loadCommunityPurgeDialog', data, function() {
			$('#communityPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeCommunityLink click handler

	/* END PURGE COMMUNITY */

	/********************************
	UNDELETE COMMUNITY
	*********************************/

	// define click handler for the button
	$('.undeleteCommunityLink').click(function() {
		closeAllMenus();
		// Find communityID of clicked row
		var communityID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load community info into dialog div
		var data = {communityID: communityID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#msg').load('../ajax/admin.handler.php?ajaxAction=undeleteCommunity', data, function() {
			fadeAndRemoveRow('#communityID_' + communityID, 'deletedCommunityList');
		});
		return false;
	}); // end undeleteCommunityLink click handler

	/********************************
	RACE PURGE DIALOG
	*********************************/

	var racePurgeDialogOpts = {
		modal: true,
		title: "Purge Race?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current race ID
				var raceID = $('#purgeRaceID').val();
				var data = {raceID: raceID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/admin.handler.php?ajaxAction=purgeRace', data, function() {
					$('#racePurgeDialog').dialog('close');
					fadeAndRemoveRow('#raceID_' + raceID, 'deletedRaceList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	// Create the dialog
	$('#racePurgeDialog').dialog(racePurgeDialogOpts);

	// Define click handler for the button
	$('.purgeRaceLink').click(function() {
		// Find raceID of clicked row
		var raceID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load race info into dialog div
		var data = {raceID: raceID}; // Create object/map of data to pass in AJAX call

		// load race info into dialog
		$('#racePurgeDialog').load('../ajax/admin.handler.php?ajaxAction=loadRacePurgeDialog', data, function() {
			$('#racePurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeRaceLink click handler

	/* END PURGE RACE */

	/*****************************
	UNDELETE RACE
	******************************/

	// Define click handler for the button
	$('.undeleteRaceLink').click(function() {
		// Find raceID of clicked row
		var raceID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load race info into dialog div
		var data = {raceID: raceID}; // Create object/map of data to pass in AJAX call

		// Perform undelete and display success/error
		$('#msg').load('../ajax/admin.handler.php?ajaxAction=undeleteRace', data, function() {
			fadeAndRemoveRow('#raceID_' + raceID, 'deletedRaceList');
		});
		hideAllMenus();
		return false;
	}); // end undeleteRaceLink click handler

	/* END UNDELETE RACE */

	/********************************
	FEAT PURGE DIALOG
	*********************************/

	var featPurgeDialogOpts = {
		modal: true,
		title: "Purge Feat?",
		buttons: {
			"Cancel": function() {
				$(this).dialog('close');
			},
			"Purge": function() {
				// Figure out current feat ID
				var featID = $('#purgeFeatID').val();
				var data = {featID: featID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/gameWorld.handler.php?ajaxAction=purgeFeat', data, function() {
					$('#featPurgeDialog').dialog('close');
					fadeAndRemoveRow('#featID_' + featID, 'deletedFeatList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	// Create the dialog
	$('#featPurgeDialog').dialog(featPurgeDialogOpts);

	// Define click handler for the button
	$('.purgeFeatLink').click(function() {
		// Find ID of clicked row
		var featID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load feat info into dialog div
		var data = {featID: featID}; // Create object/map of data to pass in AJAX call

		// Load spell info into dialog
		$('#featPurgeDialog').load('../ajax/gameWorld.handler.php?ajaxAction=loadFeatPurgeDialog', data, function() {
			$('#featPurgeDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end purgeFeatLink click handler

	/* END PURGE FEAT */

	/********************************
	UNDELETE FEAT
	*********************************/

	// Define click handler for the button
	$('.undeleteFeatLink').click(function() {
		// Find ID of clicked row
		var featID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load feat info into dialog div
		var data = {featID: featID}; // Create object/map of data to pass in AJAX call

		// Load spell info into dialog
		$('#msg').load('../ajax/gameWorld.handler.php?ajaxAction=undeleteFeat', data, function() {
			fadeAndRemoveRow('#featID_' + featID, 'deletedFeatList');
		});
		hideAllMenus();
		return false;
	}); // end undeleteFeat click handler

	/* END UNDELETE FEAT */

	/*****************************
	MISCELLANEOUS
	******************************/

	$(".actionsLink").click(function() {
		$(this).next('.menu').toggle();
		return false;
	});

	$('.filtersExpandContract').click(function() {
		// alert('clicked');
		var data;
		var parent = $(this).parents('.filters');
		// alert(parent.attr('id'));
		if (parent.hasClass('expanded')) {
			// alert('expanded');
			parent.removeClass('expanded');
			parent.addClass('contracted');
			parent.find('#filterContainer').slideUp();

			if ($(this).attr('id') == 'charFiltersExpand') {
				data = {charFilterExpanded: 'No'};
				// Set expanded/contracted in session
				$.post('../ajax/character.handler.php?ajaxAction=setCharFilterExpanded', data);
			} else if ($(this).attr('id') == 'cpFiltersExpand') {
				data = {cpFilterExpanded: 'No'};
				// Set expanded/contracted in session
				$.post('../ajax/cp.handler.php?ajaxAction=setCPFilterExpanded', data);
			}
		} else if (parent.hasClass('contracted')) {
			// alert('contracted');
			parent.removeClass('contracted');
			parent.addClass('expanded');
			parent.find('#filterContainer').slideDown();

			if ($(this).attr('id') == 'charFiltersExpand') {
				data = {charFilterExpanded: 'Yes'};
				// Set expanded/contracted in session
				$.post('../ajax/character.handler.php?ajaxAction=setCharFilterExpanded', data);
			} else if ($(this).attr('id') == 'cpFiltersExpand') {
				data = {cpFilterExpanded: 'Yes'};
				// Set expanded/contracted in session
				$.post('../ajax/cp.handler.php?ajaxAction=setCPFilterExpanded', data);
			}

		}
		return false;
	});

	$("#summaryViewLink").click(function() {
		$("#summaryView").show();
		$("#summaryViewText").show();
		$("#summaryViewLink").hide();

		$("#detailedView").hide();
		$("#detailedViewText").hide();
		$("#detailedViewLink").show();
	});

	$("#detailedViewLink").click(function() {
		$("#summaryView").hide();
		$("#summaryViewText").hide();
		$("#summaryViewLink").show();

		$("#detailedView").show();
		$("#detailedViewText").show();
		$("#detailedViewLink").hide();
	});

	$('#selectAll').click(function() {
		if ($(this).is(':checked')) {
			$(this).closest('table').find('td input[type=checkbox]').each(function() {
				$(this).attr('checked', 'checked');
			});
		} else {
			$(this).closest('table').find('td input[type=checkbox]').each(function() {
				$(this).attr('checked', false);
			});
		}
	});

	// Filter characters
	$('#charFiltersBtn').click(function() {
		var tabName;
		// Figure out selected tab
		if ($('#charListContainer').hasClass('showPCs')) {
			tabName = 'showPCs';
		} else if ($('#charListContainer').hasClass('showNPCs')) {
			tabName = 'showNPCs';
		} else {
			tabName = 'showAll'; // Default to blank (all)
		}

		filterCharacters(tabName);

		return false;
	}); // end of charFiltersBtn click event

	// Filter CP records
	$('#cpFiltersBtn').click(function() {
		var tabName;
		// Figure out selected tab
		if ($('#cpListContainer').hasClass('showCharacter')) {
			tabName = 'showCharacter';
		} else if ($('#cpListContainer').hasClass('showPlayer')) {
			tabName = 'showPlayer';
		} else {
			tabName = 'showAll'; // Default to blank (all)
		}

		filterCP(tabName);

		return false;
	}); // end of cpFiltersBtn click event

	// Events for character tabs
	$('#charListContainer #showAll').click(function() {
		doOnClickCharacterTab('showAll');
		return false;
	});

	$('#charListContainer #showPCs').click(function() {
		doOnClickCharacterTab('showPCs');
		return false;
	});

	$('#charListContainer #showNPCs').click(function() {
		doOnClickCharacterTab('showNPCs');
		return false;
	});

	// EVENTS FOR CP TABS
	$('#cpListContainer #showAll').click(function() {
		doOnClickCPTab('showAll');
		return false;
	});

	$('#cpListContainer #showCharacter').click(function() {
		doOnClickCPTab('showCharacter');
		return false;
	});

	$('#cpListContainer #showPlayer').click(function() {
		doOnClickCPTab('showPlayer');
		return false;
	});

	// EVENTS FOR HEADER TABS
	$('#headerListContainer #showAll').click(function() {
		doOnClickHeaderTab('showAll');
		return false;
	});

	$('#headerListContainer #showPublic').click(function() {
		doOnClickHeaderTab('showPublic');
		return false;
	});

	$('#headerListContainer #showHidden').click(function() {
		doOnClickHeaderTab('showHidden');
		return false;
	});

	$('#headerListContainer #showNPCOnly').click(function() {
		doOnClickHeaderTab('showNPCOnly');
		return false;
	});

	// EVENTS FOR SKILL TABS
	$('#skillListContainer #showAll').click(function() {
		doOnClickSkillTab('showAll');
		return false;
	});

	$('#skillListContainer #showPublic').click(function() {
		doOnClickSkillTab('showPublic');
		return false;
	});

	$('#skillListContainer #showHidden').click(function() {
		doOnClickSkillTab('showHidden');
		return false;
	});

	$('#skillListContainer #showNPCOnly').click(function() {
		doOnClickSkillTab('showNPCOnly');
		return false;
	});

	// Events for spell tabs
	$('#spellListContainer #showAll').click(function() {
		doOnClickSpellTab('showAll');
		return false;
	});

	$('#spellListContainer #showPublic').click(function() {
		doOnClickSpellTab('showPublic');
		return false;
	});

	$('#spellListContainer #showHidden').click(function() {
		doOnClickSpellTab('showHidden');
		return false;
	});

	$('#spellListContainer #showNPCOnly').click(function() {
		doOnClickSpellTab('showNPCOnly');
		return false;
	});

	$('.clearFilters').click(function() {
		var container = $(this).parents('#filterContainer');
		container.find('.row input[type=text]').val('');
		container.find('.row select').val('');

		// Set from and to dates to defaults
		var fromDateDefault = $('#fromDateDefault').val();
		var toDateDefault = $('#toDateDefault').val();

		$('#fromDate').val(fromDateDefault);
		$('#toDate').val(toDateDefault);


		return false;
	});

/*******************************************************
INITIALIZE LIST VIEWS
Sorting, striping, etc
********************************************************/

	if ($('#pendingUserList').length > 0) {

		// Load results into table
		$('#pendingUserList tbody').load('../ajax/player.handler.php?ajaxAction=getPendingUsers', function() {

			if ($('#pendingUserList tbody tr').length > 1) {
				$('#pendingUserList').tablesorter({
					sortList: [[1,0]],
					headers: {
						0:{sorter: false},
						3:{sorter: false}
					}
				});
			}

			$('#pendingUserList').bind('sortEnd',function() {
				stripeTableRows('pendingUserList');
			});

			setupMenuEvents();
			setupPendingUserEvents(); // Attach menu events

		});
	} // end of pendingUserList conditional

	if ($('#playerList').length > 0) {

		$('#playerList').tablesorter({
			sortList: [[0,0]],
			headers: {
				3:{sorter: false},
				4:{sorter: false}
			}
		});

		$('#playerList').bind('sortEnd',function() {
			stripeTableRows('playerList');
		});

	}

	if ($('#charList').length > 0) {
		var charType, tabName;

		// Figure out selected tab
		if ($('#charListContainer').hasClass('showPCs')) {
			charType = 'PC';
			tabName = 'showPCs';
		} else if ($('#charListContainer').hasClass('showNPCs')) {
			charType = 'NPC';
			tabName = 'showNPCs';
		} else {
			charType = ''; // Default to blank (all)
			tabName = 'showAll';
		}

		var charName = $('#charName').val();
		var countryID = $('#countryID').val();
		var communityID = $('#communityID').val();
		var raceID = $('#raceID').val();
		var headerName = $('#headerName').val();
		var skillName = $('#skillName').val();
		var spellName = $('#spellName').val();
		var featName = $('#featName').val();
		var traitID = $('#traitID').val();
		var playerName = $('#playerName').val();
		var selectedCharTab = tabName;

		// Create object/map of filters to pass in AJAX call
		var data = {charType: charType,
					charName: charName,
					countryID: countryID,
					communityID: communityID,
					raceID: raceID,
					headerName: headerName,
					skillName: skillName,
					spellName: spellName,
					featName: featName,
					traitID: traitID,
					playerName: playerName,
					selectedCharTab: selectedCharTab};

		// var data = {}; // Blank map: no filters (show all characters)

		// Load results into table
		$('#charList tbody').load('../ajax/character.handler.php?ajaxAction=getFilteredCharacters', data, function() {

			$('#charList').tablesorter({
				sortList: [[1,0]],
				headers: {
					0:{sorter: false},
					4:{sorter: false},
					5:{sorter: false},
					6:{sorter: false}
				}
			});

			$('#charList').bind('sortEnd',function() {
				stripeTableRows('charList');
			});

			stripeTableRows('charList');
			setupMenuEvents();
			setupCharacterEvents(); // Re-attach event handlers (mostly for menu contents)

		});
	} // end of charList conditional

	if ($('#cpList').length > 0) {
		var CPType, tabName;

		// Figure out selected tab
		if ($('#cpListContainer').hasClass('showCharacter')) {
			CPType = 'Character';
			tabName = 'showCharacter';
		} else if ($('#cpListContainer').hasClass('showPlayer')) {
			CPType = 'Player';
			tabName = 'showPlayer';
		} else {
			CPType = '';
			tabName = 'showAll';
		}

		var fromDate = $('#fromDate').val();
		var toDate = $('#toDate').val();
		var charName = $('#charName').val();
		var playerName = $('#playerName').val();
		var CPCatID = $('#CPCatID').val();
		var staffMember = $('#staffMember').val();
		var CPNote = $('#CPNote').val();
		var selectedCPTab = tabName;

		// Create object/map of filters to pass in AJAX call
		var data = {
			CPType: CPType,
			fromDate: fromDate,
			toDate: toDate,
			charName: charName,
			playerName: playerName,
			CPCatID: CPCatID,
			staffMember: staffMember,
			CPNote: CPNote,
			selectedCPTab: selectedCPTab
		};

		// Load results into table
		$('#cpList tbody').load('../ajax/cp.handler.php?ajaxAction=getFilteredCP', data, function() {

			$('#cpList').tablesorter({
				sortList: [[0,1]],
				headers: {
					7:{sorter: false}
				}
			});

			$('#cpList').bind('sortEnd',function() {
				stripeTableRows('cpList');
			});

			stripeTableRows('cpList');
			setupMenuEvents(); // Attach menu events
			setupCPEvents();

		});
	} // end of cpList conditional

	if ($('#communityList').length > 0) {

		$('#communityList').tablesorter({
			sortList: [[0,0]],
			headers: {
				1:{sorter: false},
				2:{sorter: false}
			}
		});

		$('#communityList').bind('sortEnd',function() {
			stripeTableRows('communityList');
		});

	} // end of communityList conditional

	if ($('#countryList').length > 0) {

		$("#countryList").tablesorter({
			sortList: [[0,0]],
			headers: {
				1:{sorter: false},
				2:{sorter: false}
			}
		});

		$('#countryList').bind('sortEnd',function() {
			stripeTableRows('countryList');
		});

	} // end of countryList conditional

	if ($('#headerList').length > 0) {

		// Figure out selected tab
		if ($('#headerListContainer').hasClass('showPublic')) {
			var tabName = 'showPublic';
		} else if ($('#headerListContainer').hasClass('showHidden')) {
			var tabName = 'showHidden';
		} else if ($('#headerListContainer').hasClass('showNPCOnly')) {
			var tabName = 'showNPCOnly';
		} else {
			var tabName = 'showAll'; // Default to showAll
		}

		var data = {tabName: tabName}; // Create object/map of data to pass in AJAX call

		// Load results into table
		$('#headerList tbody').load('../ajax/admin.handler.php?ajaxAction=changeHeadersTab', data, function() {

			$('#headerList').tablesorter({
				sortList: [[0,0]],
				headers: {
					3:{sorter: false},
					4:{sorter: false}
				}
			});

			$('#headerList').bind('sortEnd',function() {
				stripeTableRows('headerList');
			});

			stripeTableRows('headerList');
			setupMenuEvents(); // Attach menu events
			setupHeaderEvents(); // Re-attach event handlers (mostly for menu contents)

		});
	} // end of headerList conditional

	if ($('#skillList').length > 0) {

		// Figure out selected tab
		if ($('#skillListContainer').hasClass('showPublic')) {
			var tabName = 'showPublic';
		} else if ($('#skillListContainer').hasClass('showHidden')) {
			var tabName = 'showHidden';
		} else if ($('#skillListContainer').hasClass('showNPCOnly')) {
			var tabName = 'showNPCOnly';
		} else {
			var tabName = 'showAll'; // Default to showAll
		}

		var data = {tabName: tabName}; // Create object/map of data to pass in AJAX call

		// Load results into table
		$('#skillList tbody').load('../ajax/admin.handler.php?ajaxAction=changeSkillsTab', data, function() {

			$('#skillList').tablesorter({
				sortList: [[0,0]],
				headers: {
					5:{sorter: false}
				}
			});

			$('#skillList').bind('sortEnd',function() {
				stripeTableRows('skillList');
			});

			stripeTableRows('skillList');
			setupMenuEvents();
			setupSkillEvents(); // Re-attach event handlers (mostly for menu contents)

		});
	} // end of skillList conditional

	if ($('#spellList').length > 0) {

		// Figure out selected tab
		if ($('#spellListContainer').hasClass('showPublic')) {
			var tabName = 'showPublic';
		} else if ($('#spellListContainer').hasClass('showHidden')) {
			var tabName = 'showHidden';
		} else if ($('#spellListContainer').hasClass('showNPCOnly')) {
			var tabName = 'showNPCOnly';
		} else {
			var tabName = 'showAll'; // Default to showAll
		}

		var data = {tabName: tabName}; // Create object/map of data to pass in AJAX call

		// Load results into table
		$('#spellList tbody').load('../ajax/admin.handler.php?ajaxAction=changeSpellsTab', data, function() {

			$('#spellList').tablesorter({
				sortList: [[0,0]],
				headers: {
					4:{sorter: false}
				}
			});

			$('#spellList').bind('sortEnd',function() {
				stripeTableRows('spellList');
			});

			stripeTableRows('spellList');
			setupMenuEvents();
			setupSpellEvents(); // Re-attach event handlers (mostly for menu contents)

		});
	} // end of spellList conditional

	if ($('#raceList').length > 0) {

		$('#raceList').tablesorter({
			sortList: [[0,0]],
			headers: {
				2:{sorter: false},
				3:{sorter: false}
			}
		});

		$('#raceList').bind('sortEnd',function() {
			stripeTableRows('raceList');
		});

	} // end of raceList conditional

	if ($('#traitList').length > 0) {

		$("#traitList").tablesorter({
			sortList: [[0,0]],
			headers: {
				2:{sorter: false},
				3:{sorter: false}
			}
		});

		$('#traitList').bind('sortEnd',function() {
			stripeTableRows('traitList');
		});

	} // end of traitList conditional

	// FEAT LIST
	if ($('#featList').length > 0) {

		// Load results into table
		$('#featList tbody').load('../ajax/gameWorld.handler.php?ajaxAction=getFeats', function() {

			if ($('#featList tbody tr').length > 1) {
				$('#featList').tablesorter({
					sortList: [[0,0]],
					headers: {
						2:{sorter: false}
					}
				});
			}

			$('#featList').bind('sortEnd',function() {
				stripeTableRows('featList');
			});

			stripeTableRows('headerList');
			setupMenuEvents();
			setupFeatEvents(); // Attach menu events

		});
	} // end of featList conditional

} // end of init

/***********************************************
EVENT HANDLER FUNCTIONS
***********************************************/

function doOnClickCharacterTab(tabName) {
	// alert('doOnClickCharacterTab');
	$('#charListContainer').removeClass('showAll showPCs showNPCs');
	$('#charListContainer').addClass(tabName);

	filterCharacters(tabName);
}

function filterCharacters(tabName) {
	// alert(filterCharacters);
	var charType;

	$('#charList tbody tr').fadeOut();

	// Set charType correctly
	if (tabName == 'showPCs') {
		charType = 'PC';
	} else if (tabName == 'showNPCs') {
		charType = 'NPC';
	} else {
		charType = ''; // Default to blank (all)
	}

	var charName = $('#charName').val();
	var countryID = $('#countryID').val();
	var communityID = $('#communityID').val();
	var raceID = $('#raceID').val();
	var headerName = $('#headerName').val();
	var skillName = $('#skillName').val();
	var spellName = $('#spellName').val();
	var featName = $('#featName').val();
	var traitID = $('#traitID').val();
	var playerName = $('#playerName').val();
	var selectedCharTab = tabName;

	// Create object/map of filters to pass in AJAX call
	var data = {
		charType: charType,
		charName: charName,
		countryID: countryID,
		communityID: communityID,
		raceID: raceID,
		headerName: headerName,
		skillName: skillName,
		spellName: spellName,
		featName: featName,
		traitID: traitID,
		playerName: playerName,
		selectedCharTab: selectedCharTab
	};

	$('#charList tbody').load('../ajax/character.handler.php?ajaxAction=getFilteredCharacters', data, function() {
		$('#charList').trigger('update'); // Notify tablesorter that table has changed

		// Only do sorting if there's more than one row (to prevent JS errors)
		if ($('#charList tbody tr').length > 1) {
			var sorting = [[1,0]]; // Set sorting to second column ASC
			$('#charList').trigger('sorton',[sorting]); // Perform sort	
		}

		setupMenuEvents();
		setupCharacterEvents(); // Re-attach event handlers (mostly for menu contents)
	});
} // end of filterCharacters

function doOnClickCPTab(tabName) {
	// alert('doOnClickCPTab');
	$('#cpListContainer').removeClass('showAll showCharacter showPlayer');
	$('#cpListContainer').addClass(tabName);

	filterCP(tabName);
}

function filterCP(tabName) {
	// alert('filterCP');
	var CPType;

	$('#cpList tbody tr').fadeOut();

	// Set charType correctly
	if (tabName == 'showCharacter') {
		CPType = 'Character';
	} else if (tabName == 'showPlayer') {
		CPType = 'Player';
	} else {
		CPType = ''; // Default to blank (all)
	}

	var fromDate = $('#fromDate').val();
	var toDate = $('#toDate').val();
	var charName = $('#charName').val();
	var playerName = $('#playerName').val();
	var CPCatID = $('#CPCatID').val();
	var staffMember = $('#staffMember').val();
	var CPNote = $('#CPNote').val();
	var selectedCPTab = tabName;

	// Create object/map of filters to pass in AJAX call
	var data = {CPType: CPType,
				fromDate: fromDate,
				toDate: toDate,
				charName: charName,
				playerName: playerName,
				CPCatID: CPCatID,
				staffMember: staffMember,
				CPNote: CPNote,
				selectedCPTab: selectedCPTab};

	$('#cpList tbody').load('../ajax/cp.handler.php?ajaxAction=getFilteredCP', data, function() {
		// alert('Beginning of AJAX callback');

		$('#cpList').trigger('update'); // Notify tablesorter that table has changed

		// Only do sorting if there's more than one row (to prevent JS errors)
		if ($('#cpList tbody tr').length > 1) {
			var sorting = [[0,1]]; // Set sorting to second column ASC
			$('#cpList').trigger('sorton',[sorting]); // Perform sort	
		}

		setupMenuEvents();
		setupCPEvents();
	});
} // end of filterCP

function doOnClickHeaderTab(tabName) {
	$('#headerListContainer').removeClass('showAll showPublic showHidden showNPCOnly');
	$('#headerListContainer').addClass(tabName);

	$('#headerList tbody tr').fadeOut();

	var data = {tabName: tabName}; // Create object/map of data to pass in AJAX call

	// Load results into table
	$('#headerList tbody').load('../ajax/admin.handler.php?ajaxAction=changeHeadersTab', data, function() {
		$('#headerList').trigger('update'); // Notify tablesorter that table has changed
		if ($('#headerList tbody tr').length > 1) {
			var sorting = [[0,0]]; // Set sorting to first column ASC
			$('#headerList').trigger('sorton',[sorting]); // Perform sort
		}

		stripeTableRows('headerList');
		setupMenuEvents(); // Re-attach actions to menus
		setupHeaderEvents(); // Re-attach event handlers (mostly for menu contents)

	});
}

function doOnClickSkillTab(tabName) {
	$('#skillListContainer').removeClass('showAll showPublic showHidden showNPCOnly');
	$('#skillListContainer').addClass(tabName);

	$('#skillList tbody tr').fadeOut();

	var data = {tabName: tabName}; // Create object/map of data to pass in AJAX call

	// Load results into table
	$('#skillList tbody').load('../ajax/admin.handler.php?ajaxAction=changeSkillsTab', data, function() {
		$('#skillList').trigger('update'); // Notify tablesorter that table has changed
		var sorting = [[0,0]]; // Set sorting to first column ASC
		$('#skillList').trigger('sorton',[sorting]); // Perform sort 

		stripeTableRows('skillList');
		setupMenuEvents(); // Re-attach actions to menus
		setupSkillEvents(); // Re-attach event handlers (mostly for menu contents)

	});
}

function doOnClickSpellTab(tabName) {
	$('#spellListContainer').removeClass('showAll showPublic showHidden showNPCOnly');
	$('#spellListContainer').addClass(tabName);

	$('#spellList tbody tr').fadeOut();

	var data = {tabName: tabName}; // Create object/map of data to pass in AJAX call

	// Load results into table
	$('#spellList tbody').load('../ajax/admin.handler.php?ajaxAction=changeSpellsTab', data, function() {
		$('#spellList').trigger('update'); // Notify tablesorter that table has changed
		var sorting = [[0,0]]; // Set sorting to first column ASC
		$('#spellList').trigger('sorton',[sorting]); // Perform sort 

		stripeTableRows('spellList');
		setupMenuEvents(); // Re-attach actions to menus
		setupSpellEvents(); // Re-attach event handlers (mostly for menu contents)

	});
}

function doOnClickAccessDropdown(dropdown) {
	if (dropdown.value == "Hidden") {
		$('#PCAccessRow').show();
	} else {
		$('#PCAccessRow').hide();
	}
}

function doOnChangeStackable(stackableFld) {
	if (stackableFld.value == "yes") {
		$('#stackableOptions').show();
		$('#maxQuantity').val('100');
	} else {
		$('#stackableOptions').hide();
		$('#maxQuantity').val('1');
	}
}

function doOnClickSendEmailRadio() {
	if ($('#sendEmailYes').attr('checked')) {
		$('#playerMessageRow').show();
	} else if ($('#sendEmailNo').attr('checked')) {
		$('#playerMessageRow').hide();
	}
}

function doOnClickCheckAllAccess() {
	$('#charSelectionBox input[type="checkbox"]').each(function() {
		$(this).attr('checked', true);
	});
}

function doOnClickUncheckAllAccess() {
	$('#charSelectionBox input[type="checkbox"]').each(function() {
		$(this).attr('checked', false);
	});
}

// rowIndex: Number of current (clicked) row 
function doOnClickAddAttrRow(rowIndex) {
	var nextIndex = rowIndex + 1;
	$('#attribute' + rowIndex + 'PlusLink').hide();
	$('#attributeCost' + nextIndex + 'Row').show();
}

// rowIndex: Number of current (clicked) row
function doOnClickRemoveAttrRow(rowIndex) {
	// TODO: Clear form field values
	var prevIndex = rowIndex - 1;

	// Clear field values
	$('#attributeCost' + rowIndex).val('');
	$('#attribute' + rowIndex).val('0');

	$('#attributeCost' + rowIndex + 'Row').hide();
	$('#attribute' + prevIndex + 'PlusLink').show();
}

function setupMenuEvents() {
	// Attach menu events
	$(".actionsLink").click(function() {
		$(this).next('.menu').toggle();
		return false;
	});
}

function setupPendingUserEvents() {
	var data, playerID;
	/********************************
	PLAYER APPROVE DIALOG
	*********************************/

	var playerApproveDialogOpts = {
		modal: true,
		title: "Approve Player?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Approve": function() {
				// Figure out current character ID
				playerID = $('#approvePlayerID').val();
				data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/player.handler.php?ajaxAction=approvePlayer', data, function() {
					$('#playerApproveDialog').dialog('close');
					fadeAndRemoveRow('#playerID_' + playerID, 'playerList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#playerApproveDialog').dialog(playerApproveDialogOpts);

	//define click handler for the button
	$('#pendingUserList .approveUserLink').click(function() {
		// Find player ID of clicked row
		playerID = $(this).closest('tr').find('.chkboxCol input[type=checkbox]').val();

		// Load character info into dialog div
		data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#playerApproveDialog').load('../ajax/player.handler.php?ajaxAction=loadPlayerApproveDialog', data, function() {
			$('#playerApproveDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;

	}); // end approveLink click handler

	/* END PLAYER APPROVE DIALOG */

	/********************************
	PLAYER REJECT DIALOG
	*********************************/

	var playerRejectDialogOpts = {
		modal: true,
		title: "Reject Access Request?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Reject": function() {
				// Figure out current character ID
				playerID = $('#rejectPlayerID').val();
				data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/player.handler.php?ajaxAction=rejectPlayer', data, function() {
					$('#playerRejectDialog').dialog('close');
					fadeAndRemoveRow('#playerID_' + playerID, 'playerList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#playerRejectDialog').dialog(playerRejectDialogOpts);

	//define click handler for the button
	$('#pendingUserList .rejectUserLink').click(function() {
		// Find player ID of clicked row
		playerID = $(this).closest('tr').find('.chkboxCol input[type=checkbox]').val();

		// Load character info into dialog div
		data = {playerID: playerID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#playerRejectDialog').load('../ajax/player.handler.php?ajaxAction=loadPlayerRejectDialog', data, function() {
			$('#playerRejectDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;

	}); // end rejectLink click handler

	/* END PLAYER REJECT DIALOG */

	/********************************
	PLAYER APPROVE MULTI DIALOG
	*********************************/

	var playerApproveMultiDialogOpts = {
		modal: true,
		title: "Approve Access Requests?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Approve": function() {
				playerIDList = $('#approvedPlayers').val();
				data = {playerIDList: playerIDList}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/player.handler.php?ajaxAction=approveMultiplePlayers', data, function() {
					$('#playerApproveMultiDialog').dialog('close');
					// convert IDlist to array
					var playerIDArr = playerIDList.split(',');
					var playerIDArrLen = playerIDArr.length;
					for (var i = 0; i < playerIDArrLen; i++) {
						// Loop through all approved players and remove their rows
						fadeAndRemoveRow('#playerID_' + playerIDArr[i], 'pendingUserList');
					}
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#playerApproveMultiDialog').dialog(playerApproveMultiDialogOpts);

	//define click handler for the button
	$('#approveUsersBtn').click(function() {
		data = {};
		var playerIDArr = new Array();
		// Figure out IDs for all selected players
		$('#pendingUserList td.chkboxCol input:checked').each(function() {
			// alert($(this).attr('id') + ' value: ' + $(this).val());
			playerIDArr.push($(this).val());
		});

		// Load character info into dialog div
		data = {playerIDArr: playerIDArr}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#playerApproveMultiDialog').load('../ajax/player.handler.php?ajaxAction=loadPlayerApproveMultiDialog', data, function() {
			$('#playerApproveMultiDialog').dialog('open'); // On success, open dialog
		});

		hideAllMenus();
		return false;

	}); // end approveUsersBtn click handler

	/* END PLAYER APPROVE MULTI DIALOG */

	/********************************
	REJECT MULTIPLE PLAYERS DIALOG
	*********************************/

	var playerRejectMultiDialogOpts = {
		modal: true,
		title: "Reject Access Requests?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Reject": function() {
				var playerIDList = $('#rejectedPlayers').val();
				var data = {playerIDList: playerIDList}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/player.handler.php?ajaxAction=rejectMultiplePlayers', data, function() {
					$('#playerRejectMultiDialog').dialog('close');
					// convert IDlist to array
					var playerIDArr = playerIDList.split(',');
					var playerIDArrLen = playerIDArr.length;
					for (var i = 0; i < playerIDArrLen; i++) {
						// Loop through all approved players and remove their rows
						fadeAndRemoveRow('#playerID_' + playerIDArr[i], 'pendingUserList');
					}
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#playerRejectMultiDialog').dialog(playerRejectMultiDialogOpts);

	//define click handler for the button
	$('#rejectUsersBtn').click(function() {
		data = {};
		var playerIDArr = new Array();
		// Figure out IDs for all selected players
		$('#pendingUserList td.chkboxCol input:checked').each(function() {
			playerIDArr.push($(this).val());
		});

		// Load character info into dialog div
		data = {playerIDArr: playerIDArr}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#playerRejectMultiDialog').load('../ajax/player.handler.php?ajaxAction=loadPlayerRejectMultiDialog', data, function() {
			$('#playerRejectMultiDialog').dialog('open'); // On success, open dialog
		});

		hideAllMenus();
		return false;

	}); // end rejectUsersBtn click handler

	/* END REJECT MULTIPLE PLAYERS DIALOG */

}

function setupCharacterEvents() {

	/********************************
	CHARACTER DELETE DIALOG
	*********************************/

	var charDeleteDialogOpts = {
		modal: true,
		title: "Delete Character?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				// Figure out current character ID
				var charID = $('#deleteCharID').val();
				var data = {characterID: charID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/character.handler.php?ajaxAction=deleteCharacter', data, function() {
					$('#charDeleteDialog').dialog('close');
					fadeAndRemoveRow('#characterID_' + charID, 'charList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#charDeleteDialog').dialog(charDeleteDialogOpts);

	//define click handler for the button
	$('#charList .deleteLink').click(function() {
		// Find characterID of clicked row
		var charID = $(this).closest('tr').find('.chkboxCol input[type=checkbox]').val();

		// Load character info into dialog div
		var data = {characterID: charID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#charDeleteDialog').load('../ajax/character.handler.php?ajaxAction=loadCharDeleteDialog', data, function() {
			$('#charDeleteDialog').dialog('open'); // On success, open dialog
		});
		hideAllMenus();
		return false;
	}); // end deleteLink click handler

	/* END DELETE DIALOG */

	/********************************
	TRANSFER CHARACTER DIALOG
	*********************************/

	var transferCharDialogOpts = {
		modal: true,
		title: "Transfer Character",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Transfer": function() {
				// Figure out current character ID
				var charID = $('#transferCharID').val();
				var playerID = $('#transferCharDialog #playerID').val();

				// alert('Character ID: ' + charID + '\n Player ID: ' + playerID);
				var data = {characterID: charID, playerID: playerID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/character.handler.php?ajaxAction=transferCharacter', data, function(response, status, xhr) {
					if (status == "error") {
						alert('error!');
					} else if ($.trim(response) == '') {
						// alert('Error: ' + response);
						$('#charTransferMsg').load('../ajax/admin.handler.php?ajaxAction=displayUIMessage');
						runCharTransferValidations();
						setupCharTransferValidations();
					} else {
						$('#transferCharDialog').dialog('close');
						$('#charFiltersBtn').click();
					}
				});
			}
		},
		autoOpen: false,
		width: 600
	};

	//create the dialog
	$('#transferCharDialog').dialog(transferCharDialogOpts);

	//define click handler for the button
	$('.transferLink').click(function() {
		// Find characterID of clicked row
		var charID = $(this).closest('tr').find('.col1 input[type=checkbox]').val();

		// Load character info into dialog div
		var data = {characterID: charID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#transferCharDialog').load('../ajax/character.handler.php?ajaxAction=loadCharTransferDialog', data, function() {
			// On success, open dialog
			$('#transferCharDialog').dialog('open');
			setupCharTransferValidations();
		});
		hideAllMenus();
		return false;

	}); // end transferLink click handler

	/* END TRANSFER DIALOG */

	/********************************
	CHARACTER DEATHS DIALOG
	*********************************/

	var charDeathsDialogOpts = {
		modal: true,
		title: "Add Character Deaths",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Add": function() {
				// Figure out current character ID
				var charID = $('#deathsCharID').val();
				var playerID = $('#charDeathsDialog #playerID').val();

				// alert('Character ID: ' + charID + '\n Player ID: ' + playerID);
				var data = {characterID: charID, playerID: playerID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/character.handler.php?ajaxAction=addCharDeaths', data, function() {
					if (status == "error") {
						alert('error!');
					} else {
						$('#charDeathsDialog').dialog('close');
					}
				});
			}
		},
		autoOpen: false,
		width: 600
	};

	//create the dialog
	$('#charDeathsDialog').dialog(charDeathsDialogOpts);

	//define click handler for the button
	$('.addDeathsLink').click(function() {
		// Find characterID of clicked row
		var charID = $(this).closest('tr').find('.col1 input[type=checkbox]').val();

		// Load character info into dialog div
		var data = {characterID: charID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#transferCharDialog').load('../ajax/character.handler.php?ajaxAction=loadCharDeathsDialog', data, function() {
			// On success, open dialog
			$('#transferCharDialog').dialog('open');
		});

	}); // end addDeathsLink click handler

	/* END CHAR DEATHS DIALOG */

} // end setupCharacterEvents

function setupHeaderEvents() {

	/********************************
	HEADER DELETE DIALOG
	*********************************/

	var headerDeleteDialogOpts = {
		modal: true,
		title: "Delete Header?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteHeader();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteHeader() {
		// Figure out current ID
		var headerID = $('#deleteHeaderID').val();
		var data = {headerID: headerID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/admin.handler.php?ajaxAction=deleteHeader', data, function() {
			$('#headerDeleteDialog').dialog('close');
			fadeAndRemoveRow('#headerID_' + headerID, 'headerList');
		});
	}

	// create the dialog
	$('#headerDeleteDialog').dialog(headerDeleteDialogOpts);

	// define click handler for the button
	$('#headerList .deleteLink').click(function() {
		// Find ID of clicked row
		var headerID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load header info into dialog div
		var data = {headerID: headerID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#headerDeleteDialog').load('../ajax/admin.handler.php?ajaxAction=loadHeaderDeleteDialog', data, function() {
			// On success, open dialog
			$('#headerDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowHeaderDelete').val() == 'no') {
				$('#headerDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#headerDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteHeader();
					}
				});
			}

		});
		return false;
	}); // end deleteLink click handler

	/* END HEADER DELETE */
} // end setupHeaderEvents

function setupSkillEvents() {

	/********************************
	SKILL DELETE DIALOG
	*********************************/

	var skillDeleteDialogOpts = {
		modal: true,
		title: "Delete Skill?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteSkill();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteSkill() {
		// Figure out current ID
		var skillID = $('#deleteSkillID').val();
		var data = {skillID: skillID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/admin.handler.php?ajaxAction=deleteSkill', data, function() {
			$('#skillDeleteDialog').dialog('close');
			fadeAndRemoveRow('#skillID_' + skillID, 'skillList');
		});
	}

	// create the dialog
	$('#skillDeleteDialog').dialog(skillDeleteDialogOpts);

	// define click handler for the button
	$('#skillList .deleteLink').click(function() {
		// Find ID of clicked row
		var skillID = $(this).closest('tr').find('.nameCol input[type=hidden]').val();

		// Load skill info into dialog div
		var data = {skillID: skillID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#skillDeleteDialog').load('../ajax/admin.handler.php?ajaxAction=loadSkillDeleteDialog', data, function() {
			// On success, open dialog
			$('#skillDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowSkillDelete').val() == 'no') {
				$('#skillDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#skillDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteSkill();
					}
				});
			}

		});
		hideAllMenus();
		return false;

	}); // end deleteLink click handler

	/* END SKILL DELETE */
} // end setupSkillEvents

function setupSpellEvents() {

	/********************************
	SPELL DELETE DIALOG
	*********************************/

	var spellDeleteDialogOpts = {
		modal: true,
		title: "Delete Spell?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteSpell();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteSpell() {
		// Figure out current ID
		var spellID = $('#deleteSpellID').val();
		var data = {spellID: spellID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/admin.handler.php?ajaxAction=deleteSpell', data, function() {
			$('#spellDeleteDialog').dialog('close');
			fadeAndRemoveRow('#spellID_' + spellID, 'spellList');
		});
	}

	// create the dialog
	$('#spellDeleteDialog').dialog(spellDeleteDialogOpts);

	// define click handler for the button
	$('#spellList .deleteLink').click(function() {
		// Find ID of clicked row
		var spellID = $(this).closest('tr').find('.col1 input[type=hidden]').val();

		// Load character info into dialog div
		var data = {spellID: spellID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#spellDeleteDialog').load('../ajax/admin.handler.php?ajaxAction=loadSpellDeleteDialog', data, function() {
			// On success, open dialog
			$('#spellDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowSpellDelete').val() == 'no') {
				$('#spellDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#spellDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteSpell();
					}
				});
			}

		});
		return false;

	}); // end deleteLink click handler

	/* END SPELL DELETE */

} // end of setupSpellEvents

function setupCPEvents() {
	/********************************
	CP DELETE DIALOG
	*********************************/

	var cpDeleteDialogOpts = {
		modal: true,
		title: "Delete CP Record?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				// Figure out current character ID
				var CPTrackID = $('#deleteCPTrackID').val();
				var data = {CPTrackID: CPTrackID}; // Create object/map of data to pass in AJAX call

				$('#msg').load('../ajax/cp.handler.php?ajaxAction=deleteCP', data, function() {
					$('#cpDeleteDialog').dialog('close');
					fadeAndRemoveRow('#CPTrackID_' + CPTrackID, 'cpList');
				});
			}
		},
		autoOpen: false,
		width: 500
	};

	//create the dialog
	$('#cpDeleteDialog').dialog(cpDeleteDialogOpts);

	//define click handler for the button
	$('#cpList .deleteLink').click(function() {

		// Find CPTrackID of clicked row
		var CPTrackID = $(this).closest('tr').find('.dateCol input[type=hidden]').val();

		// Load CP info into dialog div
		var data = {CPTrackID: CPTrackID}; // Create object/map of data to pass in AJAX call

		// Load character info into dialog
		$('#cpDeleteDialog').load('../ajax/cp.handler.php?ajaxAction=loadCPDeleteDialog', data, function() {
			$('#cpDeleteDialog').dialog('open'); // On success, open dialog

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowCPDelete').val() == 'no') {
				$('#cpDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			}
		});
		hideAllMenus();
		return false;
	}); // end deleteLink click handler

	setupCPAdd(); // Set up Quick Add CP dialog

} // end setupCPEvents

function setupCPAdd() {
	// alert('setupCPAdd');
	/********************************
	QUICK ADD CP DIALOG
	*********************************/

	var cpAddDialogOpts = {
		modal: true,
		title: "Add CP",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Add": function() {
				// Figure out current character ID
				var characterID = $('#cpAddDialog #characterID').val();
				var playerID = $('#cpAddDialog #playerID').val();
				var CPType = $('#cpAddDialog input:radio[name=CPType]:checked').val();
				var totalCP = $('#cpAddDialog #addCPTotal').html();

				// 1st row
				var numberCP1 = $('#cpAddDialog #numberCP1').val();
				var CPCatID1 = $('#cpAddDialog #CPCatID1').val();
				var CPNote1 = $('#cpAddDialog #CPNote1').val();

				// 2nd row
				var numberCP2 = $('#cpAddDialog #numberCP2').val();
				var CPCatID2 = $('#cpAddDialog #CPCatID2').val();
				var CPNote2 = $('#cpAddDialog #CPNote2').val();

				// 3rd row
				var numberCP3 = $('#cpAddDialog #numberCP3').val();
				var CPCatID3 = $('#cpAddDialog #CPCatID3').val();
				var CPNote3 = $('#cpAddDialog #CPNote3').val();

				// 4th row
				var numberCP4 = $('#cpAddDialog #numberCP4').val();
				var CPCatID4 = $('#cpAddDialog #CPCatID4').val();
				var CPNote4 = $('#cpAddDialog #CPNote4').val();

				// 5th row
				var numberCP5 = $('#cpAddDialog #numberCP5').val();
				var CPCatID5 = $('#cpAddDialog #CPCatID5').val();
				var CPNote5 = $('#cpAddDialog #CPNote5').val();

				// alert('Character ID: ' + characterID + '\n Player ID: ' + playerID);
				// alert('numberCP5: ' + numberCP5 + '\n CPCatID5: ' + CPCatID5);
				// alert('Type: ' + CPType);

				// Create object/map of data to pass in AJAX call
				var data = {
					CPType: CPType,
					characterID: characterID,
					playerID: playerID,
					totalCP: totalCP,
					numberCP1: numberCP1,
					CPCatID1: CPCatID1,
					CPNote1: CPNote1,
					numberCP2: numberCP2,
					CPCatID2: CPCatID2,
					CPNote2: CPNote2,
					numberCP3: numberCP3,
					CPCatID3: CPCatID3,
					CPNote3: CPNote3,
					numberCP4: numberCP4,
					CPCatID4: CPCatID4,
					CPNote4: CPNote4,
					numberCP5: numberCP5,
					CPCatID5: CPCatID5,
					CPNote5: CPNote5
				};

				$('#msg').load('../ajax/cp.handler.php?ajaxAction=cpAdd', data, function(response, status, xhr) {
					// alert('Type of response is: ' + typeof(response));
					if (status == "error") {
						alert('Load method failed!');
					} else if ($.trim(response) == '') {
						// alert('Error: ' + response);
						$('#cpAddMsg').load('../ajax/admin.handler.php?ajaxAction=displayUIMessage');
						runAddCPValidations();
					} else {
						// All is well
						$('#cpAddDialog').dialog('close');
						$('#cpFiltersBtn').click(); // Reload CP list to reflect changes
					}
				});
			}
		},
		autoOpen: false,
		width: 600
	};

	//create the dialog
	$('#cpAddDialog').dialog(cpAddDialogOpts);

	//define click handler for the button
	$('#cpPage .addLink').click(function() {
		/* Find characterID of clicked row
		var charID = $(this).closest('tr').find('.col1 input[type=checkbox]').val();
		
		// Load character info into dialog div
		var data = {characterID: charID}; // Create object/map of data to pass in AJAX call
		*/

		// Load character info into dialog
		$('#cpAddDialog').load('../ajax/cp.handler.php?ajaxAction=loadCPAddDialog', function() {
			// On success, open dialog
			$('#cpAddDialog').dialog('open');
			hideAllMenus();
			setupCPTypeEvents();
			setupAddCPValidations();
		});
		return false;

	}); // end cpAddLink click handler
	/* END QUICK ADD CP DIALOG */
}

function setupFeatEvents() {

	/********************************
	FEAT DELETE DIALOG
	*********************************/

	var featDeleteDialogOpts = {
		modal: true,
		title: "Delete Feat?",
		buttons: {
			"Close": function() {
				$(this).dialog('close');
			},
			"Delete": function() {
				deleteFeat();
			}
		},
		autoOpen: false,
		width: 500
	};

	function deleteFeat() {
		// Figure out current ID
		var featID = $('#deleteFeatID').val();
		var data = {featID: featID}; // Create object/map of data to pass in AJAX call

		$('#msg').html(''); // Clear previous messages from msg area

		$('#msg').load('../ajax/gameWorld.handler.php?ajaxAction=deleteFeat', data, function() {
			$('#featDeleteDialog').dialog('close');
			fadeAndRemoveRow('#featID_' + featID, 'featList');
		});
	}

	// create the dialog
	$('#featDeleteDialog').dialog(featDeleteDialogOpts);

	// define click handler for the button
	$('#featList .deleteLink').click(function() {
		// Find ID of clicked row
		var featID = $(this).closest('tr').find('.nameCol input[type=hidden]').val();

		// Load feat info into dialog div
		var data = {featID: featID}; // Create object/map of data to pass in AJAX call

		// Load confirmation
		$('#featDeleteDialog').load('../ajax/gameWorld.handler.php?ajaxAction=loadFeatDeleteDialog', data, function() {
			// On success, open dialog
			$('#featDeleteDialog').dialog('open');

			// If user should not be allowed to delete, replace default buttons
			// with single Close button. 
			if ($('#allowFeatDelete').val() == 'no') {
				$('#featDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					}
				});
			} else {
				// User is allowed to delete. Show correct buttons. 
				$('#featDeleteDialog').dialog("option", "buttons", {
					"Close": function() {
						$(this).dialog('close');
					},
					"Delete": function() {
						deleteFeat();
					}
				});
			}

		});
		return false;

	}); // end deleteLink click handler

	/* END FEAT DELETE */

} // end of setupFeatEvents
