<?php
	$flv_backcolor = "0xFFFFFF";		// Backgroundcolor of the flvplayer
	$flv_frontcolor = "0x000000";		// Texts / buttons color of the flvplayer
	$flv_lightcolor = "0x000000";		// Rollover/ active color of the flvplayer
	$flv_screencolor = "0x000000";		// Background color of the display of the flvplayer

	print <<< HTML
<html>
<head>
	<title>Flash Player</title>
	<script type="text/javascript" src="swfobject.js"></script>
</head>
<body style="margin:0;padding:0;">
	<div id="player"><a href="http://www.macromedia.com/go/getflashplayer">Get the latest Flash Player</a> to see this player.</div>
	<script type="text/javascript">
		var so = new SWFObject('flvplayer.swf','player','100%','100%','7');
		so.addParam('allowfullscreen','true');
		so.addVariable('file',getQueryParamValue("f"));
		so.addVariable('backcolor','{$flv_backcolor}');
		so.addVariable('frontcolor','{$flv_frontcolor}');
		so.addVariable('lightcolor','{$flv_lightcolor}');
		so.addVariable('screencolor','{$flv_screencolor}');
		so.addVariable('fsbuttonlink',getQueryParamValue("r"));
		so.addVariable('autostart','true');
		so.write('player');
	</script>
</body>
</html>
HTML;
?>
