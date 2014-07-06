/**************************************************************
	NAME:	main.js
	NOTES:	This file contains the JavaScript for the main area. 
**************************************************************/

function expandContract(elemId, linkId, contractedTxt, expandedTxt) {
	elemToShow = $('#' + elemId);
	linkElem = $('#' + linkId);
	if (elemToShow.is(':visible')) {
		elemToShow.slideUp();
		linkElem.addClass('contracted');
		linkElem.removeClass('expanded');
		linkElem.html(contractedTxt);
	} else {
		elemToShow.slideDown();
		linkElem.addClass('expanded');
		linkElem.removeClass('contracted');
		linkElem.html(expandedTxt);
	}
}

function init() {
	var doClose = function() {
		//close the dialog
		$("#aboutDialog").dialog("close");
	};

	var dialogOpts = {
		modal: true,
		title: "Character Generator 2.0 (Beta)",
		buttons: {
			"Close": doClose
		},
		autoOpen: false,
		width: 750
	};

	//create the dialog
	$("#aboutDialog").dialog(dialogOpts);

	//define click handler for the button
	$("#aboutLink").click(function() {
		$("#aboutDialog").dialog("open");
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

}
