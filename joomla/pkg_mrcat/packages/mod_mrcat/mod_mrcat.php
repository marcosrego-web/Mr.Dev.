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
// global scripts and styles
$doc->addScript(JURI::base().'modules/mod_mrcat/assets/js/mrcat_v051.js');
$doc->addStyleSheet(JURI::base().'modules/mod_mrcat/assets/css/mrcat_v051.css');
$browsercheck = $_SERVER['HTTP_USER_AGENT'];
if ( strpos($browsercheck, 'rv:11.0') !== false && strpos($browsercheck, 'Trident/7.0;')!== false || isset($browsercheck) && (strpos($browsercheck, 'MSIE') !== false)) {
	/*Polyfill for Vanilla Javascript on Internet Explorer*/
	JHtml::_('script', '//polyfill.io/v3/polyfill.min.js', array('crossorigin' => 'anonymous'));
}
// Check if it's an official theme or a custom theme
if(!$params['appearance']->theme || $params['appearance']->theme != 'Custom') {
	if(!$params['appearance']->theme) {
		$theme = 'Default';
	} else {
		$theme = htmlspecialchars($params['appearance']->theme);
	}
	include JPATH_ROOT.'/modules/mod_mrcat/themes/'.$theme.'/index.php';
	$doc->addStyleSheet(JURI::base().'modules/mod_mrcat/themes/'.$theme.'/'.$theme.'_v051.css');
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
