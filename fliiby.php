<?php

/**
* Plugin Name: Fliiby
* Plugin URI: https://fliiby.com
* Description: Fliiby embed plugin with basic features and convinient defaults.
* Version: 1.0.0
* Author: Fliiby
* Author URI: https://fliiby.com
* License: GNU
*/

/*
  Fliiby
  Copyright (C) 2015 Fliiby

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/* _________________________________________________________________ */
$fby_site_name 			= 'Fliiby';
$fby_thickbox_title 	= 'Visual Fliiby Search Tool and Wizard';
$fliiby_url 			= 'https://fliiby.com';
$fby_media_button_url 	= $fliiby_url . '/wordpress/plugin/';
$fby_shortcode 			= 'fliiby';
$fby_content_regexp 	= '@(?:(?:\[' . $fby_shortcode . '\])|(?:=\")|(?:\">)){0,1}[\r\t ]*https?://(?:www\.)?(?:(?:fliiby\.com)|(?:flii\.by))/{1}(?:(?:file)|(?:embed))/{1}[a-zA-Z0-9]+/{0,1}[\r\t ]*(?:(?:\[/' . $fby_shortcode . '\])|(?:\")){0,1}@i';
$fby_link_regexp 		= '@https?://(?:www\.)?(?:(?:fliiby\.com)|(?:flii\.by))/{1}(?:(?:file)|(?:embed))/{1}[a-zA-Z0-9]+/{0,1}@im';
/* _________________________________________________________________ */

add_action('media_buttons', 'fliiby_media_button', 15.000392854348);
add_action('admin_menu', 'fliiby_dashboard_menu');
add_action('admin_enqueue_scripts', 'fliiby_admin_enqueue_scripts');

add_shortcode($fby_shortcode, 'fliiby_shortcode_embed');

add_filter('widget_text', 'fliiby_widget_text');
add_filter('the_content', 'fliiby_the_content');

/**
* Add "Fliiby" media button in "new post" section.
*/
function fliiby_media_button() {
	global $fby_site_name, $fby_thickbox_title, $fby_media_button_url, $fby_site_name;

	add_thickbox();

	$media_button_href  = $fby_media_button_url;
	$media_button_href .= '?domain=' . urlencode(site_url());
	$media_button_href .= '&TB_iframe=true&width=950&height=800';

	$media_button_label = $fby_site_name;
	$thickbox_title 	= $fby_thickbox_title;
?>

	<style>
		.fliiby-icon {
			background: url("<?php echo plugins_url() . '/' . strtolower($fby_site_name); ?>/images/fliiby_symbol_16x16.png") top left no-repeat;
			width: 16px;
			height: 16px;
			display: inline-block;
			margin: 1px 2px 0 0;
			vertical-align: text-top;
		}
	</style>

	<script>
		function fliiby_media_tb() {
			setTimeout(function() {
				jQuery("#TB_window").animate({marginLeft: '-' + parseInt((950 / 2), 10) + 'px', width: '950px'}, 300);
				jQuery("#TB_window iframe").animate({width: '950px'}, 300);
			}, 15);
		}
		jQuery(document).ready(function() {
			jQuery("#insert_fliiby_media").click(fliiby_media_tb);
			jQuery(window).resize(fliiby_media_tb);
		});
	</script>

	<a href="<?php echo $media_button_href; ?>" id="insert_fliiby_media" class="thickbox button" title="<?php echo $thickbox_title; ?>"><span class="fliiby-icon"></span> <?php echo $media_button_label; ?></a>

<?php
}

/**
* Add "Fliiby" dashboard menu button.
*/
function fliiby_dashboard_menu() {
	add_menu_page('Fliiby Settings', 'Fliiby', 'manage_options', 'fliiby-preferences', 'fliiby_show_options', plugins_url('images/fliiby_symbol_16x16.png', __FILE__), '11.000392854348');
	add_action('admin_init', 'fliiby_register_settings');
}

/**
* Enqueue admin sripts.
*/
function fliiby_admin_enqueue_scripts() {
	wp_enqueue_script('fliiby_embed_post', plugins_url('js/fliiby_embed_post.js', __FILE__), false, false, true);
}

/**
* Register necessary plugin settings.
*/
function fliiby_register_settings() {
	register_setting('fliiby-settings-group', 'embed_width');
	register_setting('fliiby-settings-group', 'embed_height');
	register_setting('fliiby-settings-group', 'embed_style');
}

/**
* Dashboard menu content.
*/
function fliiby_show_options() {
	global $fby_site_name, $fliiby_url;
?>

<style>
.small-title {
	width: 95%;
	float: left;
	background-color: #d9e9f7;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	-o-border-radius: 3px;
	border-radius: 3px;
	text-align: left;
	padding: 5px 10px;
	font-weight: bold;
	margin-top: 15px;
}

.description {
	width: 95%;
	float: left;
	text-align: justify;
}

.highlighted {
	width: auto;
	padding: 0 5px;
	background-color: rgba(0, 0, 0, 0.07);
	font-size: 15px;
}

.middle-align {
	vertical-align: middle;
}
</style>

<h2><img src="<?php echo plugins_url() . '/' . strtolower($fby_site_name); ?>/images/fliiby_symbol_16x16.png" alt="Fliiby" /> Fliiby Settings</h2>

<div class="small-title">How To Insert Any File From Fliiby File Library</div>
<div class="description">
	<ul>
		<li>
			<strong>Method 1:</strong><br />
			Do you already have a URL to the file you want to post? All you have to do is paste it on its own line, as shown below (including the https:// part). Easy, eh?
		</li>
		<li>
			<strong>Method 2:</strong><br />
			If you want to do some formatting (e.g. add HTML to center a file) or have two or more files next to each other on the same line, wrap each link with the <span class="highlighted">[fliiby]...[/fliiby]</span> shortcode.<br />
			Tip for embedding files on the sam line: As shown in the example image below, decrease the size of each file so they can fot together on the same line.
		</li>
	</ul>

	<p><strong>Examples:</strong><br /></p>
	<img src="<?php echo plugins_url() . '/' . strtolower($fby_site_name); ?>/images/fliiby_examples.jpg" alt="Examples" />

	<p>Always follow these rules for any URL:</p>
	<ul>
		<li>Make sure the URL is really on its own line by itself. Or, if you need multiple files on the same line, make sure each URL is wrapped properly with the shortcode (Example: <span class="highlighted">[fliiby]https://fliiby.com/file/ABCDEFGHIJK/?width=400&amp;height=250&amp;style=ABCD[/fliiby]</span>)</li>
		<li>Make sure the URL is not an active hyperlink (i.e., it should just be plain text). Otherwise, highlight the URL and click the &quot;unlink&quot; button in your editor.</li>
		<li>Make sure you did not format or align the URL in any way. If your URL still appears in your actual post instead of a file, highlight it and click the &quot;remove formatting&quot; button (formatting can be invisible sometimes).</li>
		<li>If you really want to align the file, try wrapping the link with the shortcode first. For example: <span class="highlighted">[fliiby]https://fliiby.com/file/ABCDEFGHIJK[/fliiby]</span>. Using the shortcode also allows you to have two or more files next to each other on the same line.</li>
	</ul>

	<p>
		Using the shortcode, you'll be allowed to set next parameters:
		<ul>
			<li><strong>width</strong> - Determines width of the player.</li>
			<li><strong>height</strong> - Determines height of the player.</li>
			<li><strong>style</strong> - Determines color scheme that will be used in the player. This color scheme you can define on your <strong><a href="<?php echo $fliiby_url; ?>/embedding/" target="_blank"><?php echo $fby_site_name; ?> embedding page</a></strong>.</li>
		</ul>
		<strong>Example:</strong> <span class="highlighted">[fliiby]https://fliiby.com/file/ABCDEFGHIJK/?width=300&height=150&style=ABCD[/fliiby]</span>
	</p>
</div>

<div class="small-title">Visual Fliiby Wizard</div>
<div class="description">
	<p>Let's say you don't know the exact URL of the file you wish to embed. Well, we've made the ability to directly search Fliiby and insert files right from your editor tab as a free feature to all users. Simply click the <img class="middle-align" src="<?php echo plugins_url() . '/' . strtolower($fby_site_name); ?>/images/fliiby_btn_wizard.png" alt="Fliiby" /> wizard button found above your editor to start the wizard (see image above to locate this button). There, you'll be given the option to search files on an easy way. Each result will have the <img class="middle-align" src="<?php echo plugins_url() . '/' . strtolower($fby_site_name); ?>/images/fliiby_btn_file_link.png" alt="File Link" height="30px" /> and <img class="middle-align" src="<?php echo plugins_url() . '/' . strtolower($fby_site_name); ?>/images/fliiby_btn_embed_code.png" alt="Embed Code" height="30px" /> button that you can click to directly embed the desired file link to your post without having to copy and paste.</p>
	<img src="<?php echo plugins_url() . '/' . strtolower($fby_site_name); ?>/images/fliiby_wizard.png" alt="Fliiby Wizard" />
</div>

<div class="small-title">Default Fliiby Options</div>
<div class="description">
	<p>
		One of the benefits of using this plugin is that you can set site-wide default options for all your files (click &quot;Save Changes&quot; when finished). However, you can also override them (and more) on a per-file basis using shortcode (e.g. <span class="highlighted">[fliiby]https://fliiby.com/file/ABCDEFGHIJK/?width=400&amp;height=250&amp;style=ABCD[/fliiby]</span>).
		<br />
		If you want to use default settings, just leave all fields blank.
	</p>

	<form action="options.php" method="post">
		<?php
			settings_fields('fliiby-settings-group');
			do_settings_sections('fliiby-settings-group');

			$embed_width 	= get_option('embed_width') ? esc_attr(get_option('embed_width')) : "";
			$embed_height 	= get_option('embed_height') ? esc_attr(get_option('embed_height')) : "";
			$embed_style 	= get_option('embed_style') ? esc_attr(get_option('embed_style')) : "";
		?>

		<div style="width: 300px; float: left;">
			<div style="width: 300px; float: left;">
				<div style="width: 100px; float: left;">
					<label for="embed_width">Width:</label><br />
					<input type="text" name="embed_width" id="embed_width" value="<?php echo $embed_width; ?>" maxlength="4" style="width: 50px;" onkeypress="validate(event);" /> px
				</div>
				<div style="width: 100px; float: left;">
					<label for="embed_height">Height:</label><br />
					<input type="text" name="embed_height" id="embed_height" value="<?php echo $embed_height; ?>" maxlength="4" style="width: 50px;" onkeypress="validate(event);" /> px
				</div>
			</div>
			<div style="width: 300px; float: left; margin-top: 10px;">
				<label for="embed_style">Style:</label><br />
				<input type="text" name="embed_style" id="embed_style" value="<?php echo $embed_style; ?>" style="width: 150px;" />
			</div>
			<div style="width: 300px; float: left; margin-top: -10px;">
				<?php submit_button(); ?>
			</div>
		</div>
	</form>
</div>

<script>
function validate(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	if ((key >= 48 && key <= 57) || key == 37 || key == 39 || key == 8 || key == 9) {
		return true;
	} else {
		theEvent.returnValue = false;
		if (theEvent.preventDefault)
			theEvent.preventDefault();
	}
}
</script>

<?php
}

/**
* Shortcode recognition.
*/
function fliiby_shortcode_embed($atts, $content) {
	return fliiby_create_embed($content);
}

/**
* Build embed from content links.
*/
function fliiby_the_content($content) {
	global $fby_content_regexp;

	$replaced = preg_replace_callback($fby_content_regexp, function($matches) {
		$match_url = $matches[0];

		if (fliiby_is_for_replacement($match_url)) {
			return fliiby_create_embed($match_url);
		} else {
			return $match_url;
		}
	}, $content);

	return $replaced;
}

/**
* Build embed from widget links.
*/
function fliiby_widget_text($text) {
	global $fby_content_regexp;

	$replaced = preg_replace_callback($fby_content_regexp, function($matches) {
		$match_url = $matches[0];

		if (fliiby_is_for_replacement($match_url)) {
			return fliiby_create_embed($match_url, true);
		} else {
			return $match_url;
		}
	}, $text);

	return $replaced;
}

/**
* Returns boolean value that determines does URL is for
* replacement (true) or not (false).
*/
function fliiby_is_for_replacement($match_url) {
	global $fby_shortcode;

	$first_one 	= substr($match_url, 0, 1);
	$first_two 	= substr($match_url, 0, 2);
	$last_one 	= substr($match_url, strlen($match_url) - 1, 1);

	if ($first_one == ' ' || $first_two == '="' || $first_two == '">'
		|| $last_one == ' ' || $last_one == '"'
		|| strpos($match_url, "[$fby_shortcode]") !== false) {
		return false;
	} else {
		return true;
	}
}

/**
* Creates embed (dif and iframe with embeded content).
*/
function fliiby_create_embed($url, $is_widget = false) {
	global $fliiby_url, $fby_link_regexp, $fby_shortcode;

	$url 		= html_entity_decode(trim(stripslashes(esc_url($url))));
	$url_atts 	= fliiby_get_atts_from_url($url);

	if (!preg_match($fby_link_regexp, $url)) {
		return ('[' . $fby_shortcode . ']' . $url . '[/' . $fby_shortcode . ']');
	}

	$just_url_arr 	= explode("?", $url);
	$url 			= $just_url_arr[0];

	if (substr($url, strlen($url) - 1, 1) !== '/')
		$url .= '/';

	$url 		= str_replace(array('http:', 'https:', 'file'), array('', '', 'embed'), $url);
	$url_arr 	= explode('/', $url);
	$code 		= $url_arr[count($url_arr) - 1];

	if (!isset($code) || empty($code) || substr($code, 0, 1) == "?")
		$code 	= $url_arr[count($url_arr) - 2];

	$curl_url 	= $fliiby_url . "/include/wordpress/fliiby/embed_info.php?file=" . $code . "&" . rand(0, 999999);
	$res 		= fliiby_curl($curl_url);

	if (isset($res->error)) {
		return $res->error->message;
	}

	$width 		= $res->file_width;
	$height 	= $res->file_height;
	$perc 		= @($height / $width) * 100;

	// calculate width and height and include style if it's necessary
	if (count($url_atts) > 0) {
		$my_width 	= $url_atts['width'] ? $url_atts['width'] . 'px' : '100%';
		$my_height 	= $url_atts['height'] ? $url_atts['height'] . 'px' : $perc . '%';
		$my_style 	= $url_atts['style'] ? '?style=' . $url_atts['style'] : '';
	} else {
		$my_width 	= get_option('embed_width') ? get_option('embed_width') . 'px' : '100%';
		$my_height 	= get_option('embed_height') ? get_option('embed_height') . 'px' : $perc . '%';
		$my_style 	= get_option('embed_style') ? '?style=' . get_option('embed_style') : '';
	}

	if ($is_widget === true) {
		$my_width 	= '100%';
		$my_height 	= $perc . '%';
	}

	// create and return output
	$output  	= '<div style="width: ' . $my_width . '; position: relative; padding-top: ' . $my_height . '; display: inline-block;">';
	$output    .= '<iframe frameborder=0 marginwidth=0 marginheight=0 scrolling=no width="100%" height="100%" src="' . $url . $my_style . '" webkitallowfullscreen mozallowfullscreen allowfullscreen style="position: absolute; top: 0; left: 0;"></iframe>';
	$output    .= '</div>';

	return $output;
}

/**
* Curl function.
*/
function fliiby_curl($url) {
	$c = curl_init($url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($c, CURLOPT_TIMEOUT, 5);

	return json_decode(curl_exec($c));
}

/**
* Returns an associative array of URL vars
*/
function fliiby_get_atts_from_url($url) {
	$query_str = parse_url($url, PHP_URL_QUERY);
	parse_str($query_str, $query_params);

	return $query_params;
}

?>