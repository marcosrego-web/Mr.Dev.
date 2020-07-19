<?php
/**
 * @copyright	@copyright	Copyright (c) 2020 Marcos Rego. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$lang = '';
$content = '';
// include the syndicate functions only once
require_once __DIR__ . '/helper.php';
// get all parameters
$module_id = intval($module->id);
$globallayoutoptions = array_map("htmlspecialchars", $params->get('globallayoutoptions'));
$perline = intval($params->get('perline'));
$perpage = intval($params->get('perpage'));
$autoplay = intval($params->get('autoplay'));
$pagetransition = htmlspecialchars($params->get('pagetransition'));
$tabs = intval($params->get('tabs'));
$tabsposition = htmlspecialchars($params->get('tabsposition'));
$pagetoggles = array_filter($params->get('pagetoggles'), 'is_numeric');
$orderby = intval($params->get('orderby'));
$order = intval($params->get('order'));
$excludeinclude = intval($params->get('excludeinclude'));
$contenttypes = htmlspecialchars($params->get('contenttypes'));
$bottomlink = htmlspecialchars($params->get('bottomlink'));
$maintitle = intval($params->get('title'));
$itemimage = intval($params->get('itemimage'));
$itemstitle = intval($params->get('itemstitle'));
$itemstitlemax = intval($params->get('itemstitlemax'));
$itemdesc = intval($params->get('itemdesc'));
$itemdescmax = intval($params->get('itemdescmax'));
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
if($contenttypes == 'categories') { //Categories
	if($params->get('catselect') && is_array($params->get('catselect'))) {
		$itemselect = array_filter($params->get('catselect'), 'is_numeric');
	} else {
		$itemselect = array(intval($params->get('catselect')));
	}
} else if($contenttypes == 'content') { //Articles
	if($params->get('artselect') && is_array($params->get('artselect'))) {
		$itemselect = array_filter($params->get('artselect'), 'is_numeric');
	} else {
		$itemselect = array(intval($params->get('artselect')));
	}
}
// global scripts and styles
$doc->addScript(JURI::base().'modules/mod_yngdev/assets/js/utils.js', array('version'=>'0.9.1'));
$doc->addScript(JURI::base().'modules/mod_yngdev/assets/js/main.js', array('version'=>'0.9.1'));
$doc->addStyleSheet(JURI::base().'modules/mod_yngdev/assets/css/utils.css', array('version'=>'0.9.1'));
$doc->addStyleSheet(JURI::base().'modules/mod_yngdev/assets/css/main.css', array('version'=>'0.9.1'));
// Check if it's an official theme or a custom theme
if(!$params['appearance']->theme || $params['appearance']->theme != 'Custom') {
	if(!$params['appearance']->theme) {
		$theme = 'Default';
	} else {
		$theme = htmlspecialchars($params['appearance']->theme);
	}
	if($theme != 'None') {
		include JPATH_ROOT.'/modules/mod_yngdev/themes/'.$theme.'/index.php';
		$doc->addStyleSheet(JURI::base().'modules/mod_yngdev/themes/'.$theme.'/'.$theme.'.css', array('version'=>'0.9.1'));
	} else {
		$layout = 'none';
		$layoutoptions = array();
	}
} else {
	//CUSTOM THEME
	$theme = htmlspecialchars($params['appearance']->customthemeoptions->customtheme);
	include JPATH_ROOT.'/templates/mrdev/'.$theme.'/index.php';
	$doc->addStyleSheet(JURI::base(true).'/templates/mrdev/'.$theme.'/'.$theme.'.css', array('version'=>'0.9.1'));
}
include  __DIR__ .'/items.php';
?>