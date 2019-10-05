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
$module_id = $module->id;
$globallayoutoptions = $params->get('globallayoutoptions');
$perline = $params->get('perline');
$perpage = $params->get('perpage');
$pagetransition = $params->get('pagetransition');
$pagetoggles = $params->get('pagetoggles');
$orderby = $params->get('orderby');
$order = $params->get('order');
$excludeinclude = $params->get('excludeinclude');
$catexclude = $params->get('catexclude');
$bottomlink = $params->get('bottomlink');
$maintitle = $params->get('title');
$cattitle = $params->get('cattitle');
$catdesc = $params->get('catdesc');
$catlink = $params->get('catlink');
$catoptions = $params->get('catoptions');
$titletag = 'h3';
// global scripts and styles
JHtml::_('jquery.framework');
$doc->addScript(JURI::base().'modules/mod_mrcat/assets/js/mrcat_v042.js');
$doc->addStyleSheet(JURI::base().'modules/mod_mrcat/assets/css/mrcat_v042.css');
/* A heightfix for css 'vh' on mobile browsers address bar.
Detect IE because this fix breaks on that browser. */
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { } else {
	$doc->addScript(JURI::base().'modules/mod_mrcat/assets/js/heightfix.js');
}
// Check if it's an official theme or a custom theme
if(!$params['appearance']->theme || $params['appearance']->theme != 'Custom') {
	if(!$params['appearance']->theme) {
		$theme = 'Default';
	} else {
		$theme = $params['appearance']->theme;
	}
	require_once JPATH_ROOT.'/modules/mod_mrcat/themes/'.$theme.'/index.php';
	$doc->addStyleSheet(JURI::base().'modules/mod_mrcat/themes/'.$theme.'/'.$theme.'_v042.css');
} else {
	//CUSTOM THEME
	$theme = $params['appearance']->customthemeoptions->customtheme;
	echo $theme;
	require_once JPATH_ROOT.'/templates/mrdev/'.$theme.'/index.php';
	$doc->addStyleSheet(JURI::base(true).'/templates/mrdev/'.$theme.'/'.$theme.'.css');
}
include  __DIR__ .'/items.php';
echo $content;
?>