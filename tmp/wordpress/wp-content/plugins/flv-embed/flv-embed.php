<?php

/*

Plugin Name: FLV Embed

Plugin URI: http://www.channel-ai.com/blog/plugins/flv-embed/

Description: Standards compliant FLV embedding in your blog posts using SWFObject by Geoff and FLV Player by Jeroen. Supports Video Sitemap generation.

Version: 1.0

Author: Yaosan Yeo

Author URI: http://www.channel-ai.com/blog/

*/



/*  Copyright 2007  Yaosan Yeo  (email : eyn@channel-ai.com)



    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License as published by

    the Free Software Foundation; either version 2 of the License, or

    (at your option) any later version.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/



// global variables



	$flv_options = flv_get_options();	// retrieve options from database

	$flv_counter = 0;					// count how many players are inserted

	$flv_metakey = "flv";				// meta key used for custom field

	$flv_metavalues = array();			// array used to store metavalues for each FLV embed tags within a post







/////////////////////////////////////////////////////////////////// options function



function flv_get_options()

{

	// default values

	$my_options = array(

		'useposter' => 0,

		'posterpath' => "",

		'imagetype' => "jpg",

		'posterinfeed' => 0,

		'flvpath' => "",

		'backcolor' => "FFFFFF",

		'frontcolor' => "000000",

		'lightcolor' => "000000",

		'screencolor' => "000000",

		'logo' => "",

		'link' => "",

		'overstretch' => "fit",

		'largecontrols' => 0,

		'showicons' => 1,

		'showdigits' => 1,

		'showfsbutton' => 1,

		'showstop' => 0,

		'showvolume' => 1,

		'showcontrolbar' => 1,

		'autostart' => 1,

		'bufferlength' => 5,

		'volume' => 80,

		'sitemap' => 0

	);

	

	$options = get_option("flv_options");

	

	if (!empty($options)) {

		foreach ($options as $key => $option)

			$my_options[$key] = $option;

	}

	

	update_option("flv_options", $my_options);

	

	return $my_options;

}







/////////////////////////////////////////////////////////////////// database functions



add_action('save_post', 'flv_add_meta');



function flv_add_meta($post_id) {

	global $wpdb, $flv_options, $flv_sitemap_options, $flv_metakey, $flv_metavalues;



	// jump out of function if sitemap feature is not enabled

	if (!$flv_options['sitemap']) {

		return $post_id;

	}



	$content = $wpdb->get_var("SELECT post_content

							FROM $wpdb->posts

							WHERE ID = '$post_id'");



	// parse post content for FLV embed tags

	$content = preg_replace_callback( "/\[flv:(([^]]+))]/i", "flv_gen_meta", $content );



	// process each metavalues

	foreach ($flv_metavalues as $metavalue) {

		// read flv custom fields from database for current post_id

		$flvs = $wpdb->get_results("SELECT meta_value, meta_id

								FROM $wpdb->postmeta

								WHERE post_id = '$post_id'

								AND meta_key = '$flv_metakey'");



		// if flv custom field already exist, test its content and update the value with new flv path when applicable

		if (!empty($flvs)) {

			$update = 0;

			

			$input = explode("\n", $metavalue);

			$movie = $input[0];

			

			$file = basename($movie);

			

			// check to see if existing flv custom field can be updated

			foreach ($flvs as $flv) {

				if (stristr($flv->meta_value, $file)) {

					$metaid = $flv->meta_id;

					$update = 1;

				}

			}

		}

		

		// update existing flv custom field

		if ($update) {

			$results=  $wpdb->query("UPDATE $wpdb->postmeta

								SET meta_value = '$metavalue'

								WHERE post_id = '$post_id'

								AND meta_id = '$metaid'

								AND meta_key = '$flv_metakey'");

								

		// or write flv custom field to database

		} else {

			$results = $wpdb->query("INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value)

									VALUES ('$post_id', '$flv_metakey', '$metavalue')");

		}

	}

	

	// check if flv embed tags are found within current post

	if (!empty($metavalue)) {

		// rebuild video sitemap if allowed

		if ($flv_sitemap_options['allow_auto']) {

			// get post status...

			$post_status = $wpdb->get_var("SELECT post_status

						FROM $wpdb->posts

						WHERE ID = '$post_id'");

			// only rebuild if post is already published

			if ($post_status == 'publish')

				flv_sitemap_build();

		}

	}

	

	// clear the array

	$flv_metavalues = array();

	

	return $post_id;

}



function flv_gen_meta($matches) {

	global $flv_options, $flv_metavalues;

	

	// option values

	

	$options = $flv_options;

	

	$flv_useposter = $options['useposter'];

	$flv_imagetype = $options['imagetype'];

	$flv_posterpath = $options['posterpath'];

	$flv_flvpath = $options['flvpath'];



	// end of options



	$input = explode(" ", $matches[2]);

	$arg = count($input);



	// gets number of arguments to see if poster path is provided

	if ($arg == 4) {

		list($movie, $poster, $width, $height) = $input;

	} else if ($arg == 3) {

		list($movie, $width, $height) = $input;

	} else {

		$skip = $input[0];

	}



	// test if flv custom field addition should be skipped

	if (stristr($skip,"skip")) {

		$flv_metavalues = array();	// destroy array

		return 0;					// exit

	}

	

	// flv file location



	$usepath = 1;

	

	if ( (substr($movie,0,1) == '/') || (stristr($movie, "://")) )	// test site relative and absolute path

		$usepath = 0;

		

	if ($usepath)

		$movie = $flv_flvpath . $movie;



	$movie = flv_make_absolute($movie);

	// at this point, all $movie is in absolute URL format i.e. starting with http://

	

	

	// poster location

	

	if ($flv_useposter) {

		if (empty($poster)) {	// without poster argument

			

			// assume poster is in the same directory as flv file

			if (empty($flv_posterpath)) {

				$image = str_replace(".flv", ".", $movie);

				$posterpath = $image . $flv_imagetype;

			}

			

			// use poster path configured in options

			else {

				$image = basename($movie, ".flv");

				$posterpath = $flv_posterpath . $image . "." . $flv_imagetype;

			}

		

		} else {	// with poster argument

			$usepath = 1;

			

			// test site relative and absolute path

			if ( (substr($poster,0,1) == '/') || (stristr($poster, "://")) )

				$usepath = 0;

			

			// if not relative or absolute path, add poster path configured in options

			if ($usepath) {

				$posterpath = $flv_posterpath . $poster;

			} else {

				$posterpath = $poster;

			}

		}

		

		$posterpath = flv_make_absolute($posterpath);

		// at this point, all $posterpath is in absolute URL format i.e. starting with http://

	}

	

	// attempt to detect duration of flv

	$duration = flv_get_duration($movie);

	

	if (empty($duration))

		$metavalue = $movie . "\n" . $posterpath;

	else

		$metavalue = $movie . "\n" . $posterpath . "\n" . $duration;

	

	array_push($flv_metavalues, $metavalue);

		

	return 0;

}



function flv_make_absolute($path)

{

	$url = parse_url(get_settings('siteurl'));

	$site = "http://" . $url["host"];	// no trailing slash



	if (substr($path,0,1) == '/')

		$path = $site . $path;

	

	return $path;

}



function flv_make_relative($url)

{

	// attempt to shorten URL with site relative path

	// first check if url is under the same domain as the blog

	$my_url = parse_url($url);

	$blog_url = parse_url(get_settings('siteurl'));

			

	// if it is, shorten it to site relative path

	if ($my_url['host'] == $blog_url['host'])

		return $my_url['path'];

	else

		return false;

}



function flv_get_duration($url)

{

	global $flv_sitemap_options;

	

	$options = $flv_sitemap_options;



	// duration disallowed -> exit now!

	if (empty($options['allow_duration']))

		return false;

	

	// get site relative path without the first '/'

	$movie_path = substr(flv_make_relative($url),1);

	$server_path = $options['server_path'];

	$file = $server_path . $movie_path;

	

	// get contents of a file into a string

	if (file_exists($file)) {

		$handle = fopen($file, "r");

		$contents = fread($handle, filesize($file));

		fclose($handle);

        

		if (strlen($contents) > 3) {

			if (substr($contents,0,3) == "FLV") {

				$taglen = hexdec(bin2hex(substr($contents,strlen($contents)-3)));

				if (strlen($contents) > $taglen) {

					$duration = hexdec(bin2hex(substr($contents,strlen($contents)-$taglen,3)));

					return $duration;

				}

			}

		}

	}

	return false;

}



/////////////////////////////////////////////////////////////////// admin page functions



add_action('admin_menu', 'flv_options');



if ($flv_options['sitemap']) {

	include_once('flv-sitemap.php');

	add_action('admin_menu', 'flv_sitemap_options');

}



function flv_admin_js()

{

	// add swfobject.js

	flv_head();



	$player = get_settings('siteurl') . '/wp-content/plugins/flv-embed/flvplayer.swf';

	$movie = base64_decode("aHR0cDovL3d3dy5jaGFubmVsLWFpLmNvbS92aWRlby9leW4vZGVtby5mbHY=");

	

	print <<< JS

	<script type="text/javascript">

		function updateColor(id,value)

		{

			var el = document.getElementById(id);

			el.style.background = '#'+value;

		}

		

		function updatePlayer()

		{

			var w = 400;

			var h2 = 300;

			var l = "{$player}";



			var _bc = document.getElementById('backcolor');

			var _fc = document.getElementById('frontcolor');

			var _lc = document.getElementById('lightcolor');

			var _sc = document.getElementById('screencolor');

			

			var _fs = document.getElementById('showfsbutton');

				fs = (_fs.checked) ? "true" : "false";

			

			var _shc = document.getElementById('showcontrolbar');

				var h = (_shc.checked) ? h2+20 : h2;

			

			var _la = document.getElementById('largecontrols');

				la = (_la.checked) ? "true" : "false";

				h = (_la.checked && _shc.checked) ? h+20 : h;

			

			var _sd = document.getElementById('showdigits');

				sd = (_sd.checked) ? "true" : "false";

			

			var _si = document.getElementById('showicons');

				si = (_si.checked) ? "true" : "false";



			var _ss = document.getElementById('showstop');

				ss = (_ss.checked) ? "true" : "false";



			var _sv = document.getElementById('showvolume');

				sv = (_sv.checked) ? "true" : "false";



			var _ast = document.getElementById('autostart');

				ast = (_ast.checked) ? "true" : "false";



			var _lg = document.getElementById('logo');

			var _lk = document.getElementById('link');

			var _os = document.getElementById('overstretch');

			var _vol = document.getElementById('volume');

			

			var s1 = new SWFObject(l,'mpl',w,h,'7');

			s1.addParam("allowfullscreen","true");

			s1.addVariable("height",h);

			s1.addVariable("width",w); 

			s1.addVariable("displayheight",h2);

			s1.addVariable("usefullscreen",fs);

			s1.addVariable("file","{$movie}");

			s1.addVariable("backcolor","0x"+_bc.value);

			s1.addVariable("frontcolor","0x"+_fc.value);

			s1.addVariable("lightcolor","0x"+_lc.value);

			s1.addVariable("screencolor","0x"+_sc.value);

			s1.addVariable("largecontrols",la);

			s1.addVariable("showdigits",sd);

			s1.addVariable("showicons",si);

			s1.addVariable("showstop",ss);

			s1.addVariable("showvolume",sv);

			s1.addVariable("autostart",ast);

			if (_lg.value)

				s1.addVariable("logo",_lg.value);

			if (_lk.value)

				s1.addVariable("link",_lk.value);

			s1.addVariable("overstretch",_os.value);

			s1.addVariable("volume",_vol.value);

			s1.write("player");

		}

	</script>



JS;

}



function flv_options()

{

	add_options_page('FLV Embed', 'FLV Embed', 9, basename(__FILE__), 'flv_options_panel');

}



function flv_options_panel()

{

	global $flv_options;

	

	// output javascript used in admin panel

	flv_admin_js();

	

	// test if options should be updated

	if (isset($_POST['options_update'])) {

		$options = explode(',', stripslashes($_POST['page_options']));

		

		if ($options) {

			// retrieve option values from POST variables

			foreach ($options as $option) {

				$option = trim($option);

				$value = trim(stripslashes($_POST[$option]));

				if ( (($option == 'posterpath') || ($option == 'flvpath')) && (!empty($value)) )

					$flv_options[$option] = trailingslashit($value);

				else

					$flv_options[$option] = $value;

			}

			

			// update database option

			if (update_option("flv_options", $flv_options))

				echo '<div id="message" class="updated fade"><p>Options saved.</p></div>';

			else

				echo '<div id="message" class="error"><p>No option was changed since last update.</p></div>';

		} else {

			echo '<div id="message" class="error"><p>No option value found!</p></div>';

		}

	}

	

	// retrieve options data from database

	$options = $flv_options;

	

	// checkbox and select box value

	if ($options['sitemap'])

		$sitemap_check = 'checked="checked"';

	if ($options['useposter'])

		$useposter_check = 'checked="checked"';

	if ($options['posterinfeed'])

		$posterinfeed_check = 'checked="checked"';

	if ($options['largecontrols'])

		$largecontrols_check = 'checked="checked"';

	if ($options['showdigits'])

		$showdigits_check = 'checked="checked"';

	if ($options['showicons'])

		$showicons_check = 'checked="checked"';

	if ($options['showfsbutton'])

		$showfsbutton_check = 'checked="checked"';

	if ($options['showstop'])

		$showstop_check = 'checked="checked"';

	if ($options['showvolume'])

		$showvolume_check = 'checked="checked"';

	if ($options['showcontrolbar'])

		$showcontrolbar_check = 'checked="checked"';

	if ($options['autostart'])

		$autostart_check = 'checked="checked"';

	

	switch($options['overstretch']) {

		case "fit":

			$overstretch_fit = 'selected="selected"';

			break;

		case "true":

			$overstretch_true = 'selected="selected"';

			break;		

		case "false":

			$overstretch_false = 'selected="selected"';

			break;		

		case "none":

			$overstretch_none = 'selected="selected"';

			break;

	}



print <<< ADMIN_PANEL

<div class="wrap">

		<h2>FLV Embed Options</h2>

		<form method="post" id="flv_option">

		

		<p class="submit"><input name="options_update" value="Update Options &raquo;" type="submit"></p>



		<fieldset class="options">

			<legend>Sitemap</legend>

				<p>If sitemap feature is enabled, all the FLV embed tags will be parsed and saved as custom field data when you save your blog posts. These data will then be used for video sitemap generation. A new "Video Sitemap" option page will appear when sitemap feature is enabled.</p>

				<table class="editform optiontable">

				<tr>

					<th scope="row"></th>

					<td>

						<label for="sitemap">

						<input name="sitemap" type="checkbox" id="sitemap" value="1" {$sitemap_check} />

						Enable sitemap feature and automatic custom field addition</label>

					</td>

				</tr>

				</table>

		</fieldset>

					

		<fieldset class="options">

			<legend>Poster</legend>

				<table class="editform optiontable">

				<tr>

					<th scope="row"></th>

					<td>

						<label for="useposter">

						<input name="useposter" type="checkbox" id="useposter" value="1" {$useposter_check} />

						Display poster image for embedded FLV movies</label>

					</td>

				</tr>

				</table>

				<p>If only filename is given (i.e. not absolute or site relative path) for the movie or poster parameter when using the embed tags, the filename will be appended to the poster path defined below. If the display of poster image is disabled (i.e. the checkbox above is unchecked), none of the following settings will have effect.</p>

				<table class="editform optiontable">

				<tr valign="top">

					<th scope="row">Path to poster directory:</th>

					<td>

						<input name="posterpath" type="text" id="posterpath" class="code" value="{$options['posterpath']}" size="40" /><br />

						Leave blank if not required i.e always use absolute or site relative links when embedding

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Poster image extension:</th>

					<td>

						<input name="imagetype" type="text" id="imagetype" class="code" value="{$options['imagetype']}" size="4" /><br />

						Default to <code>jpg</code>, could be <code>png</code> or <code>gif</code> etc.

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="posterinfeed">

						<input name="posterinfeed" type="checkbox" id="posterinfeed" value="1" {$posterinfeed_check} />

						Display poster image in RSS feeds</label>

					</td>

				</tr>

				</table>

		</fieldset>

		

		<fieldset class="options">

			<legend>FLV</legend>

				<p>If only filename is given (i.e. not absolute or site relative path) for the movie parameter when using the embed tags, the filename will be appended to the following path.</p>

				<table class="editform optiontable">

				<tr valign="top">

					<th scope="row">Path to FLV directory:</th>

					<td>

						<input name="flvpath" type="text" id="flvpath" class="code" value="{$options['flvpath']}" size="40" /><br />

						Leave blank if not required i.e always use absolute or site relative links when embedding

					</td>

				</tr>

				</table>

		</fieldset>

		

		<fieldset class="options">

			<legend>Player</legend>

				<p>The following settings change the appearance and behaviour of the embedded FLV player. See <a href="http://www.channel-ai.com/blog/plugins/flv-embed/#options" title="FLV Embed Options" target="_blank">documentation</a> for more information regarding each setting.</p>

				

				<div style="width: 100%; text-align: center;" id="player">(Javascript disabled, turn it on to see this player)</div>

				<div style="width: 100%; text-align: center; padding: 10px;"><a href="javascript:void(0);" onclick="updatePlayer();">Update Player</a></div>

				

				<table class="editform optiontable">

				<tr valign="top">

					<th scope="row">Background colour:</th>

					<td>

						<input name="backcolor" type="text" id="backcolor" class="code" value="{$options['backcolor']}" size="6" maxlength="6" onchange="updateColor('_backcolor',this.value)" /> <input type="text" id="_backcolor" size="1" disabled style="background:#{$options['backcolor']}">

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Texts, buttons colour:</th>

					<td>

						<input name="frontcolor" type="text" id="frontcolor" class="code" value="{$options['frontcolor']}" size="6" maxlength="6" onchange="updateColor('_frontcolor',this.value)" /> <input type="text" id="_frontcolor" size="1" disabled style="background:#{$options['frontcolor']}">

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Rollover, active colour:</th>

					<td>

						<input name="lightcolor" type="text" id="lightcolor" class="code" value="{$options['lightcolor']}" size="6" maxlength="6" onchange="updateColor('_lightcolor',this.value)" /> <input type="text" id="_lightcolor" size="1" disabled style="background:#{$options['lightcolor']}">

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Display background colour:</th>

					<td>

						<input name="screencolor" type="text" id="screencolor" class="code" value="{$options['screencolor']}" size="6" maxlength="6" onchange="updateColor('_screencolor',this.value)" /> <input type="text" id="_screencolor" size="1" disabled style="background:#{$options['screencolor']}">

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Logo:</th>

					<td>

						<input name="logo" type="text" id="logo" class="code" value="{$options['logo']}" size="40" /><br />

						Set the URL to an image to be used as a watermark logo in the bottom right corner of the display<br />

						Tips: transparent PNG works the best

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Link:</th>

					<td>

						<input name="link" type="text" id="link" class="code" value="{$options['link']}" size="40" /><br />

						Set the URL you want the logo to link to

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Overstretch:</th>

					<td>

						<select name="overstretch" id="overstretch">

						<option value="fit" {$overstretch_fit}>Fit</option>

						<option value="true" {$overstretch_true}>True</option>

						<option value="false" {$overstretch_false}>False</option>

						<option value="none" {$overstretch_none}>None</option>

						</select><br />

						Defines how to stretch movies to make them fit the display

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Buffer length:</th>

					<td>

						<input name="bufferlength" type="text" id="bufferlength" class="code" value="{$options['bufferlength']}" size="4" /> seconds<br />

						Number of seconds an FLV should be buffered ahead before the player starts it

					</td>

				</tr>

				<tr valign="top">

					<th scope="row">Volume:</th>

					<td>

						<input name="volume" type="text" id="volume" class="code" value="{$options['volume']}" size="3" /> %<br />

						Startup volume for playback of movies in percentage. Default is <code>80</code> (%)

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="largecontrols">

						<input name="largecontrols" type="checkbox" id="largecontrols" value="1" {$largecontrols_check} />

						Make controlbar twice as large for visually impaired users</label>

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="showicons">

						<input name="showicons" type="checkbox" id="showicons" value="1" {$showicons_check} />

						Show the play and activitiy icons in the middle of the display</label>

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="showdigits">

						<input name="showdigits" type="checkbox" id="showdigits" value="1" {$showdigits_check} />

						Show the digits for % loaded, elapsed and remaining time in the FLV player</label>

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="showfsbutton">

						<input name="showfsbutton" type="checkbox" id="showfsbutton" value="1" {$showfsbutton_check} />

						Show the full screen button</label>

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="showstop">

						<input name="showstop" type="checkbox" id="showstop" value="1" {$showstop_check} />

						Show the stop button (only works on FLV Player 3.13+)</label>

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="showvolume">

						<input name="showvolume" type="checkbox" id="showvolume" value="1" {$showvolume_check} />

						Show the volume button</label>

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="showcontrolbar">

						<input name="showcontrolbar" type="checkbox" id="showcontrolbar" value="1" {$showcontrolbar_check} />

						Show the control bar</label>

					</td>

				</tr>

				<tr>

					<td></td>

					<td>

						<label for="autostart">

						<input name="autostart" type="checkbox" id="autostart" value="1" {$autostart_check} />

						Set the FLV player to automatically start playing (not recommended)</label>

					</td>

				</tr>

				</table>

		</fieldset>

		

		<p class="submit">

		<input type="hidden" name="page_options" value="sitemap,useposter,posterpath,imagetype,posterinfeed,flvpath,backcolor,frontcolor,lightcolor,screencolor,logo,link,overstretch,bufferlength,volume,largecontrols,showicons,showdigits,showfsbutton,showstop,showvolume,showcontrolbar,autostart" />

		<input type="submit" name="options_update" value="Update Options &raquo;" />

		</p>

		</form>

		

		<script type="text/javascript">updatePlayer();</script>

</div>

ADMIN_PANEL;



}







/////////////////////////////////////////////////////////////////// parse content functions



add_action('wp_head', 'flv_head');

add_filter('the_content', 'flv_parse');

//add_filter('the_excerpt', 'flv_parse');



function flv_parse($content)

{

	$content = preg_replace_callback( "/\[flv:(([^]]+))]/i", "flv_embed", $content );

	return $content;

}



function flv_addVariable($var, $value)

{

	global $flv_counter;

	

	$output = "\n\t" . 's' . $flv_counter . '.addVariable("' . $var . '","' . $value . '");';

	return $output;

}



function flv_embed($matches)

{

	global $post, $flv_counter, $flv_options;





	// retrieve option values from database

	

	$options = $flv_options;

	

	$flv_useposter = $options['useposter'];				// Set this to 1 if you want to display poster frame i.e. preview image

	$flv_posterinfeed = $options['posterinfeed'];		// Set this to 1 to allow RSS reader to see the poster image with a link to the flash player

	$flv_imagetype = $options['imagetype'];				// Preview image extension, can be "jpg", "png" or "gif" (this applies to autolocate poster feature i.e. the player will look for image with the same filename as the FLV file within same directory)



	$flv_posterpath = $options['posterpath'];			// Path to directory of poster images (trailing slash required). If only filename is given (i.e. not absolute or site relative path) when using embed tags, the filename will be appended to this path. Leave blank if not required. (this property has no effect if $flv_useposter = 0)



	$flv_flvpath = $options['flvpath'];					// Path to directory of flv files (trailing slash required). If only filename is given (i.e. not absolute or site relative path) when using embed tags, the filename will be appended to this path. leave blank if not required.



	$flv_backcolor = $options['backcolor'];				// Background color of the flvplayer

	$flv_frontcolor = $options['frontcolor'];			// Texts / buttons color of the flvplayer

	$flv_lightcolor = $options['lightcolor'];			// Rollover/ active color of the flvplayer

	$flv_screencolor = $options['screencolor'];			// Background color of the display of the flvplayer



	$flv_logo = $options['logo'];						// Set the URL to an image to be used as a watermark logo in the bottom right corner of the display, transparent PNG works the best

	$flv_link = $options['link'];						// Set the URL you want the logo to link to



	$flv_overstretch = $options['overstretch'];			// Defines how to stretch images/movies to make them fit the display. "true" will stretch them proportionally to fill the display, "false" will stretch them to fit. "fit" will stretch them disproportionally to fit both height and width. "none" will show all items in their original dimensions. Defaults to "fit".



	$flv_largecontrols = $options['largecontrols'];		// Set this to 1 to make the controlbar twice as large

	$flv_showicons = $options['showicons'];				// Set this to 1 to show the play and activitiy icons in the middle of the display

	$flv_showdigits = $options['showdigits'];			// Set this to 1 to show the digits for % loaded, elapsed and remaining time in the flvplayer

	$flv_showfsbutton = $options['showfsbutton'];		// Set this to 1 to show the fullscreen button

	$flv_showstop = $options['showstop'];				// Set this to 1 to show a stop button in the controlbar

	$flv_showvolume = $options['showvolume'];			// Set this to 0 to hide the volume button and save space

	$flv_showcontrolbar = $options['showcontrolbar'];	// Set this to 1 to show the control bar



	$flv_autostart = $options['autostart'];				// Set this to 1 if you want the flv player to automatically start playing (not recommended)

	$flv_bufferlength = $options['bufferlength'];		// This sets the number of seconds an FLV should be buffered ahead before the player starts it. Set this smaller for fast connections or short videos. Set this bigger for slow connections. The default is 5 seconds.

	$flv_volume = $options['volume'];					// The default volume for playback of sounds/movies is 80 (%), but you can set another startup value



	// end of option values





	$fs = get_settings('siteurl') . '/wp-content/plugins/flv-embed/fullscreen.php';

	$player = get_settings('siteurl') . '/wp-content/plugins/flv-embed/flvplayer.swf';

	$return = get_permalink($post->ID);



	$input = explode(" ", $matches[2]);

	$arg = count($input);

	

	// gets number of arguments to see if poster path is provided

	if ($arg == 4) {

		list($movie, $poster, $width, $height) = $input;

	} else if ($arg == 3) {

		list($movie, $width, $height) = $input;

	} else {

		return "";

	}

	

	$flv_counter++;

	

	// flv file location

	$usepath = 1;

	

	if ( (substr($movie,0,1) == '/') || (stristr($movie, "://")) )	// test site relative and absolute path

		$usepath = 0;

		

	if ($usepath)

		$movie = $flv_flvpath . $movie;



	$file = flv_addVariable("file", $movie);

	

	// controller show/hide

	if ($flv_showcontrolbar) {

		$height += 20;		// Extra 20px for controller bar

		$displayheight = "";

		if ($flv_largecontrols)	// Another 20px if control bar is large (2x height of original control bar)

			$height += 20;

	} else {

		$displayheight = flv_addVariable("displayheight", $height);

	}

	

	// poster

	if ($flv_useposter) {

		if (empty($poster)) {

			

			// assume poster is in the same directory as flv file

			if (empty($flv_posterpath)) {

				$image = str_replace(".flv", ".", $movie);

				$posterpath = $image . $flv_imagetype;

			}

			

			// use poster path configured in global variables

			else {

				$image = basename($movie, ".flv");

				$posterpath = $flv_posterpath . $image . "." . $flv_imagetype;

			}

		

		} else {

			$usepath = 1;

			

			// test site relative and absolute path

			if ( (substr($poster,0,1) == '/') || (stristr($poster, "://")) )

				$usepath = 0;

			

			// if not relative or absolute path, add poster path configured in options

			if ($usepath) {

				$posterpath = $flv_posterpath . $poster;

			} else {

				$posterpath = $poster;

			}

		}

		

		$image = flv_addVariable("image", $posterpath);

	}

	

	// return custom message for RSS feeds

	if (is_feed()) {

		if ( ($flv_posterinfeed) && (!empty($posterpath)) ) {

			// get full url of poster (which requires changing site relative path to absolute) to be used as optional preview image in RSS feeds

			

			// if NOT absolute path i.e. we need to add host name to path

			if (!stristr($poster, "://")) {

				$purl = parse_url(get_settings('siteurl'));

				$posterpath = "http://" . $purl["host"] . $posterpath;

			}

			

			return "<a href=\"$return\" title=\"Watch Flash video!\"><img src=\"$posterpath\" alt=\"preview image\"/></a>";

		} else {

			return "[See post to watch Flash video]";

		}

	}

	

	// colours

	$backcolor = ($flv_backcolor == "FFFFFF") ? "" : flv_addVariable("backcolor", "0x" . $flv_backcolor);

	$frontcolor = ($flv_frontcolor == "000000") ? "" : flv_addVariable("frontcolor", "0x" . $flv_frontcolor);

	$lightcolor = ($flv_lightcolor == "000000") ? "" : flv_addVariable("lightcolor", "0x" . $flv_lightcolor);

	$screencolor = ($flv_screencolor == "000000") ? "" : flv_addVariable("screencolor", "0x" . $flv_screencolor);

	

	// logo

	$logo = (empty($flv_logo)) ? "" : flv_addVariable("logo", $flv_logo);

	

	// link

	$link = (empty($flv_link)) ? "" : flv_addVariable("link", $flv_link);

	

	// overstretch

	$overstretch = ($flv_overstretch == "fit") ? "" : flv_addVariable("overstretch", $flv_overstretch);



	// largecontrols

	$largecontrols = ($flv_largecontrols == 0) ? "" : flv_addVariable("largecontrols", "true");

	

	// showicons

	$showicons = ($flv_showicons) ? "" : flv_addVariable("showicons", "false");

	

	// showdigit

	$showdigits = ($flv_showdigits) ? "" : flv_addVariable("showdigits", "false");

	

	// showstop

	$showstop = ($flv_showstop == 0) ? "" : flv_addVariable("showstop", "true");



	// showvolume

	$showvolume = ($flv_showvolume) ? "" : flv_addVariable("showvolume", "false");

		

	// autostart

	$autostart = ($flv_autostart) ? flv_addVariable("autostart", "true") : "";

	

	// bufferlength

	$bufferlength = ($flv_bufferlength == 5) ? "" : flv_addVariable("bufferlength", $flv_bufferlength);

	

	// volume

	$volume = ($flv_volume == 80) ? "" : flv_addVariable("volume", $flv_volume);

	

	// fullscreen

	if ($flv_showfsbutton) {

		$fsbuttonlink = flv_addVariable("fsbuttonlink", "$fs?f=$movie%26r=$return");

	} else {

		$usefullscreen = flv_addVariable("usefullscreen", "false");

	}

	

	// start outputting javascript

	ob_start();

	print <<< SWF

</p><p id="player{$flv_counter}" style="display:none"><a href="http://www.macromedia.com/go/getflashplayer">Get the latest Flash Player</a> to see this player.</p>

<noscript><p>[Javascript required to view Flash movie, please turn it on and refresh this page]</p></noscript>



<script type="text/javascript">

	document.getElementById("player{$flv_counter}").style.display = "";

	

	var s{$flv_counter} = new SWFObject("{$player}","player{$flv_counter}","{$width}","{$height}","7");

	s{$flv_counter}.addParam("allowfullscreen","true");

	s{$flv_counter}.addVariable("height","{$height}");

	s{$flv_counter}.addVariable("width","{$width}"); {$displayheight}{$file}{$image}{$backcolor}{$frontcolor}{$lightcolor}{$logo}{$link}{$overstretch}{$largecontrols}{$showdigits}{$showicons}{$showstop}{$showvolume}{$autostart}{$bufferlength}{$volume}{$fsbuttonlink}{$usefullscreen}

	s{$flv_counter}.write("player{$flv_counter}");

</script><p>

SWF;

	$output = ob_get_contents();

	ob_end_clean();



	return $output;

}



// Import external javascript in Wordpress header

function flv_head()

{

	$path = get_settings('siteurl') . '/wp-content/plugins/flv-embed/swfobject.js';

	echo '<script type="text/javascript" src="' . $path . '"></script>';

	echo "\n";

}



?>

