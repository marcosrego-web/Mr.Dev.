<?php





/**



 * @copyright	Copyright (c) 2019 Marcos Rego. All rights reserved.



 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL



 */





// no direct access





defined('_JEXEC') or die;



// DEFAULT LAYOUT

	$layout = $params['appearance']->layout;

	$layoutoptions = $params['appearance']->layoutoptions;


	if($layout == 'List' ) {

		$layoutoptions = array();

	} else if($layout == 'Grid' ) {

		$layoutoptions = array('themestyle','scaleactive','opaqueactive');

	} else if($layout == 'Collapsible' ) {

		$layoutoptions = array('themestyle','toggle02','revealactive','portrait');

		$perline = 1;

	} else if($layout == 'Accordion' ) {

		$layoutoptions = array('themestyle','expandactive','windowheight');

		$perline = 0;

	} else if($layout == 'Slider' ) {

		$layoutoptions = array('themestyle','contentpagination');

		$perline = 1;

		$perpage = 1;

	} else if($layout == 'Menu' ) {

		$layoutoptions = array('checkcurrent','toggle02','toggle03','hambmob','revealactive','hideinactives','subcatactive');

	}





?>

