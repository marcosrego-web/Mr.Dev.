<?php
/**
 * @copyright	@copyright	Copyright (c) 2019 Marcos Rego. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
// include the syndicate functions only once
require_once __DIR__ . '/helper.php';
// get all parameters
$module_id = intval($module->id);
$globallayoutoptions = array_map("htmlspecialchars", $params->get('globallayoutoptions'));
$perline = intval($params->get('perline'));
$perpage = intval($params->get('perpage'));
$pagetransition = htmlspecialchars($params->get('pagetransition'));
$pagetoggles = array_filter($params->get('pagetoggles'), 'is_numeric');
$orderby = intval($params->get('orderby'));
$order = intval($params->get('order'));
$excludeinclude = intval($params->get('excludeinclude'));
$itemselect = array_filter($params->get('itemselect'), 'is_numeric');
$bottomlink = htmlspecialchars($params->get('bottomlink'));
$maintitle = intval($params->get('title'));
$itemimage = intval($params->get('itemimage'));
$itemstitle = intval($params->get('itemstitle'));
$itemdesc = intval($params->get('itemdesc'));
$itemlink = intval($params->get('itemlink'));
$itemoptions = array_map("htmlspecialchars", $params->get('itemoptions'));
$titletag = 'h3';
if ( empty( $itemselect ) ) {
	$itemselect = array();
}
if ( empty( $itemoptions ) ) {
	$itemoptions = array();
}
if ( empty( $globallayoutoptions ) ) {
	$globallayoutoptions = array();
}
if ( empty( $pagetoggles ) ) {
	$pagetoggles = array(0); //Defaults to 'Arrows'
}
// global scripts and styles
JHtml::_('jquery.framework');
$doc->addScript(JURI::base().'modules/mod_mrcat/assets/js/mrcat_v050.js');
$doc->addStyleSheet(JURI::base().'modules/mod_mrcat/assets/css/mrcat_v050.css');
/* A heightfix for css 'vh' on mobile browsers address bar.
Detect IE because this fix breaks on that browser. */
if(in_array('windowheight',$globallayoutoptions)) {
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { } else {
		$doc->addScript(JURI::base().'modules/mod_mrcat/assets/js/heightfix.js');
	}
}
// Check if it's an official theme or a custom theme
if(!$params['appearance']->theme || $params['appearance']->theme != 'Custom') {
	if(!$params['appearance']->theme) {
		$theme = 'Default';
	} else {
		$theme = htmlspecialchars($params['appearance']->theme);
	}
	include JPATH_ROOT.'/modules/mod_mrcat/themes/'.$theme.'/index.php';
	$doc->addStyleSheet(JURI::base().'modules/mod_mrcat/themes/'.$theme.'/'.$theme.'_v050.css');
} else {
	//CUSTOM THEME
	$theme = htmlspecialchars($params['appearance']->customthemeoptions->customtheme);
	echo $theme;
	include JPATH_ROOT.'/templates/mrdev/'.$theme.'/index.php';
	$doc->addStyleSheet(JURI::base(true).'/templates/mrdev/'.$theme.'/'.$theme.'.css');
}
include  __DIR__ .'/items.php';
echo $content;
?>
