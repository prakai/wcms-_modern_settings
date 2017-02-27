$(function() {
	if($("body > div").hasClass("alert")){
		$("body").prepend('<div style="padding-top: 50px;"></div>');
		$(".settings").removeClass('settings');
	}
});

var currentPanel = '';

/* Open Overlay Panel */
function openPanel(cp) {
	if (currentPanel==cp){
		return;
	}
	if (currentPanel!='') {
		closePanel(currentPanel);
	}
	currentPanel = cp;
	document.getElementById(cp+'Panel').style.height = (window.innerHeight - 50)+'px';
	$('#navbar-settings ul li a.active').removeClass('active');
	$('#navbar-settings ul li a#'+cp+'Settings').addClass('active');
}

/* Close Overlay Panel */
function closePanel(cp) {
	currentPanel = '';
	document.getElementById(cp+'Panel').style.height = "0%";
}
