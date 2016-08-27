function ieupdate(){
	var strBrowser = navigator.userAgent.toLowerCase();
	if(strBrowser.indexOf("msie") > -1 && strBrowser.indexOf("mac") < 0){
	var theObjects = document.getElementsByTagName('object');
	var theObjectsLen = theObjects.length;
	for (var i = 0; i < theObjectsLen; i++) {
		if(theObjects[i].outerHTML){
		if(theObjects[i].data){
		theObjects[i].removeAttribute('data');
	}
	var theParams = theObjects[i].getElementsByTagName("param");
	var theParamsLength = theParams.length;
	for (var j = 0; j < theParamsLength; j++) {
		if(theParams[j].name.toLowerCase() == 'flashvars'){
			var theFlashVars = theParams[j].value;
		}
	}
	var theOuterHTML = theObjects[i].outerHTML;
	var re = /<param name="FlashVars" value="">/ig;
	theOuterHTML = theOuterHTML.replace(re,
	"<param name='FlashVars' value='" + theFlashVars + "'>");
	theObjects[i].outerHTML = theOuterHTML;
}
}
}
}

window.onunload = function() {
	if (document.getElementsByTagName) {
	var objs = document.getElementsByTagName("object");
		for (i=0; i<objs.length; i++) {
			objs[i].outerHTML = "";
		}
	}
}
