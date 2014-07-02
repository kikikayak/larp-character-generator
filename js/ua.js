// JavaScript Document

$(document).ready(function () {
	uaInit();
}); // end of ready

function uaInit() {
     
	var doClose = function() {
		//close the dialog
		$("#aboutDialog").dialog("close");
	}
	
	var dialogOpts = {
		modal: true,
		title: "About the Character Generator",
		buttons: {
			"Close": doClose
		 },
		autoOpen: false,
		width: 500,
	};
	
	//create the dialog
	$("#aboutDialog").dialog(dialogOpts);
	
	//define click handler for the button
	$("#aboutLink").click(function() {
		$("#aboutDialog").dialog("open");
	});
	
	$("#firstName").focus(function () {
		 hideAllHelp();
	});
	
	$("#lastName").focus(function () {
		 hideAllHelp();
	});
	
	$("#email").focus(function () {
		 showHelp(this, 'emailHelp');
	});
	
	$("#password").focus(function () {
		 showHelp(this, 'passwordHelp');
	});
	
	$("#confirmPassword").focus(function () {
		 showHelp(this, 'passwordHelp');
	});
	
	$(".help .closeLink").each(function() {
		$(this).click(function() {
			$(this).closest(".help").hide();
			$("#helpArrow").hide();
			return false;
		});
	});
}