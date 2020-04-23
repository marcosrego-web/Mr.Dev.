<?php
 /*
	Plugin Name: Yng.Dev.
	Plugin URI:  https://marcosrego.com/en/web-en/yngdev-en/
	Description: Yng.Dev. brings you a widget to display categories and posts with descriptions, media and links, in a variety of layouts with many customizable options.
	Version:     0.8.0
	Author:      Marcos Rego
	Author URI:  https://marcosrego.com
	License:     GNU Public License version 2 or later
	License URI: http://www.gnu.org/licenseses/gpl-2.0.html
*/
/* Copyright 2020 Yng.Dev. by Marcos Rego (email : web@marcosrego.com)
Yng.Dev. is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
Yng.Dev. is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Yng.Dev. If not, see http://www.gnu.org/licenseses/gpl-2.0.html.
*/
defined('ABSPATH') or die;
/*------UPDATER-----*/
require 'tools/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/marcosrego-web/mrdev/master/wordpress/updates/yngdev-updates.json',
	__FILE__,
	'yngdev'
);
/*----ADMIN NOTICES----*/
function yngdev_admin_notices() {
	$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	/*--Recommend Visual Editor for Categories and Tags--*/
	if( !is_plugin_active( 'visual-term-description-editor/visual-term-description-editor.php' ) ) {
		if (strpos($url,'taxonomy=category') !== false) {
			echo "<div class='notice notice-warning is-dismissible' ><p><strong>Yng.Dev.</strong> recommends using <a href='https://bungeshea.com/' target='_blank'>Shea Bunge</a>'s plugin '<a href='".get_site_url()."/wp-admin/plugin-install.php?tab=plugin-information&plugin=visual-term-description-editor' target='_blank'><strong>Visual Term Description Editor</strong></a>' to edit your categories descriptions with a visual editor. Easily make powerful html descriptions to show on your Yng.Dev. widgets. It's the perfect combo!</p></div>";
		}
	}
}
add_action( 'admin_notices', 'yngdev_admin_notices' );
/*----Widget----*/
if( !class_exists('yng_developer')) {
	include trailingslashit( plugin_dir_path( __FILE__ )).'widget/index.php';
}
if(is_admin()) {
	$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	/*---Correct the Visual Term Description Editor appearing above the categories list in some resolutions---*/
	if (strpos($url,'taxonomy=category') !== false || strpos($url,'taxonomy=post_tag') !== false) {
		function yngdev_add_style() {
			wp_enqueue_style( 'yngdev_admin', plugin_dir_url( __DIR__ ).'yngdev/assets/css/admin_v080.css');
		}
		add_action('admin_footer', 'yngdev_add_style');
	}
}
?>