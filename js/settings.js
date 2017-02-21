$(function() {
	if($("body > div").hasClass("alert")){
		$("body").prepend('<div style="padding-top: 50px;"></div>');
		$(".settings").removeClass('settings');
	}
});

var currentPanel = '';

/* Open Overlay Panel */
function openPanel(p) {
	if (currentPanel==p){
		return;
	}
	if (currentPanel!='') {
		closePanel(currentPanel);
	}
	currentPanel = p;
	document.getElementById(p+'Panel').style.height = (window.innerHeight - 50)+'px';//"100%";
	$('#'+p+'Settings').addClass('active');
}

/* Close Overlay Panel */
function closePanel(p) {
	currentPanel = '';
	document.getElementById(p+'Panel').style.height = "0%";
	$('#'+p+'Settings').removeClass('active');
}
