<?php
 /*

	Plugin Name: Mr.Cat.

	Plugin URI:  https://marcosrego.com/en/web-en/mrcat-en/

	Description: Mr.Cat. brings you the widget 'MR Categories'. Display categories with descriptions, media and links, in a variety of layouts with many customizable options.
	
	Version:     0.4.0

	Author:      Marcos Rego

	Author URI:  https://marcosrego.com

	License:     GNU Public License version 2 or later

	License URI: http://www.gnu.org/licenseses/gpl-2.0.html

*/

/* Copyright 2019 Mr.Cat. by Marcos Rego (email : web@marcosrego.com)

Mr.Cat. is free software: you can redistribute it and/or modify

it under the terms of the GNU General Public License as published by

the Free Software Foundation, either version 2 of the License, or

any later version.


Mr.Dev. is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the

GNU General Public License for more details.

You should have received a copy of the GNU General Public License

along with Mr.Dev. If not, see http://www.gnu.org/licenseses/gpl-2.0.html.

*/

defined('ABSPATH') or die;

/*------UPDATER-----*/
require 'update/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/marcosrego-web/mrdev/master/wordpress/updates/mrcat-updates.json',
	__FILE__,
	'mrcat'
);

/*----ADMIN NOTICES----*/
function mrcat_admin_notices() {
	$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		
	/*--Recommend Visual Editor for Categories and Tags--*/
	if( !is_plugin_active( 'visual-term-description-editor/visual-term-description-editor.php' ) ) {
		if (strpos($url,'taxonomy=category') !== false) {
			echo "<div class='notice notice-warning is-dismissible' ><p><strong>Mr.Cat.</strong> recommends using <a href='https://bungeshea.com/' target='_blank'>Shea Bunge</a>'s plugin '<a href='https://wordpress.org/plugins/visual-term-description-editor/' target='_blank'><strong>Visual Term Description Editor</strong></a>' to edit your categories descriptions with a visual editor. Easily make powerful html descriptions to show on your MR Categories widgets. It's the perfect combo!</p></div>";
		}
	}
}
add_action( 'admin_notices', 'mrcat_admin_notices' );

/*----Activate MR Categories Widget----*/
if( !class_exists('mr_categories')) {
	include trailingslashit( plugin_dir_path( __FILE__ )).'mrcat/index.php';
}

if(is_admin()) {
	$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	/*---Correct the Visual Term Description Editor appearing above the categories list in some resolutions---*/
	if (strpos($url,'taxonomy=category') !== false || strpos($url,'taxonomy=post_tag') !== false) {
		function add_style() {
			wp_enqueue_style( 'mrwid_admin', plugin_dir_url( __DIR__ ).'mrdev/assets/css/admin_v040.css');
		}
		add_action('admin_footer', 'add_style');
	}
}

?>
