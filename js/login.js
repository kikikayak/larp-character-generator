// JavaScript Document

function expandContract(elem, alink) {
	$(elem).slideToggle('fast', function() {
		toggleClasses(elem, alink);						 
	});
}

function toggleClasses(elem, alink) {
	if ($(elem).is(':visible')) {
		$(alink).addClass('expanded');
		$(alink).removeClass('contracted');
	} else {
		$(alink).addClass('contracted')
		$(alink).removeClass('expanded');
	}	
}