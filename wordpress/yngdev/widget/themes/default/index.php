<?php
 /*
	Theme Name:  Default
	Author:      Marcos Rego
	Author URI:  https://marcosrego.com
*/
defined('ABSPATH') or die;
if(is_admin()) {
	?>
	<p>
	Layout:<br>
	<select  class="widefat mr-layouts" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
	<?php
	$options = array( 'Grid','Collapsible','Accordion','Slider','Menu','Tabs','Mosaic','Popup','Custom');
	foreach ( $options as $option ) {
		echo '<option value="' . $option . '" id="' . $option . '"', $layout == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
	}
	?>
	</select>
	</p>
	<p>
	<div class="mr-customoptions" <?php if($layout != 'Custom') { echo 'style="display: none;"'; } ?>>
	<label class="mr-label" for="<?php echo $this->get_field_id( 'layoutoptions' ); ?>"><strong><?php _e( 'customize:' ); ?></strong></label>
	<p>Style:</p>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="themestyle" <?php checked( ( is_array( $layoutoptions ) AND in_array( "themestyle", $layoutoptions ) ) ? "themestyle" : '', "themestyle" ); ?> /> <?php _e( 'Apply theme style' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="checkcurrent" <?php checked( ( is_array( $layoutoptions ) AND in_array( "checkcurrent", $layoutoptions ) ) ? "checkcurrent" : '', "checkcurrent" ); ?> /> <?php _e( 'Current categories or link' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="verticaltitle" <?php checked( ( is_array( $layoutoptions ) AND in_array( "verticaltitle", $layoutoptions ) ) ? "verticaltitle" : '', "verticaltitle" ); ?> /> <?php _e( 'Vertical titles' ); ?></label><br>
	<p>Toggles:</p>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="toggle01" <?php checked( ( is_array( $layoutoptions ) AND in_array( "toggle01", $layoutoptions ) ) ? "toggle01" : '', "toggle01" ); ?> /> <?php _e( 'Plus toggle' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="toggle02" <?php checked( ( is_array( $layoutoptions ) AND in_array( "toggle02", $layoutoptions ) ) ? "toggle02" : '', "toggle02" ); ?> /> <?php _e( 'Direction toggle' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="toggle03" <?php checked( ( is_array( $layoutoptions ) AND in_array( "toggle03", $layoutoptions ) ) ? "toggle03" : '', "toggle03" ); ?> /> <?php _e( 'Close toggle' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="hambmob" <?php checked( ( is_array( $layoutoptions ) AND in_array( "hambmob", $layoutoptions ) ) ? "hambmob" : '', "hambmob" ); ?> /> <?php _e( 'Hamburguer mobile' ); ?></label><br>
	<p>Transitions:</p>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="revealactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "revealactive", $layoutoptions ) ) ? "revealactive" : '', "revealactive" ); ?> /> <?php _e( 'Reveal active' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="expandactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "expandactive", $layoutoptions ) ) ? "expandactive" : '', "expandactive" ); ?> /> <?php _e( 'Expand active' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="fixactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "fixactive", $layoutoptions ) ) ? "fixactive" : '', "fixactive" ); ?> /> <?php _e( 'Fix active' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="scaleactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "scaleactive", $layoutoptions ) ) ? "scaleactive" : '', "scaleactive" ); ?> /> <?php _e( 'Scale active' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="opaqueactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "opaqueactive", $layoutoptions ) ) ? "opaqueactive" : '', "opaqueactive" ); ?> /> <?php _e( 'Opaque active' ); ?></label><br>
	<p>Modes:</p>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="landscape" <?php checked( ( is_array( $layoutoptions ) AND in_array( "landscape", $layoutoptions ) ) ? "landscape" : '', "landscape" ); ?> /> <?php _e( 'Landscape on portrait window' ); ?></label><br>
	<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="portrait" <?php checked( ( is_array( $layoutoptions ) AND  in_array( "portrait", $layoutoptions ) ) ? "portrait" : '', "portrait" ); ?> /> <?php _e( 'Portrait on landscape window' ); ?></label><br>
	</div>
	<div class="mr-notice perlineov" <?php if($theme == 'default' && $layout == 'Collapsible' || $theme == 'default' && $layout == 'Accordion' || $theme == 'default' && $layout == 'Slider' || $theme == 'default' && $layout == 'Tabs' || $theme == 'default' && $layout == 'Mosaic') { } else { echo 'style="display: none"'; } ?>><p>
		<strong>Items per line overriden</strong><br>
		The current layout might force the items per line number on the 'Pagination' section. Customize or change layout if you want all changes on that option to take effect.</p>
	</div>
	<div class="mr-notice perpageov" <?php if($theme == 'default' && $layout == 'Slider' || $theme == 'default' && $layout == 'Menu') { } else { echo 'style="display:none"'; } ?>>
		<p><strong>Items per page overriden</strong><br>
		The current layout might force the items per page number on the 'Pagination' section. Customize or change layout if you want all changes on that option to take effect.</p>
	</div>
	<div class="mr-notice tabs-paginationov" <?php if($theme == 'default' && $layout == 'Tabs' || $theme == 'default' && $layout == 'Mosaic') { } else { echo 'style="display:none"'; } ?>>
		<p><strong>Tabs overriden</strong><br>
		The current layout will be forcing the 'Tabs' option on the 'Pagination' section to not be 'None'. You can still change the type of tabs but can't select 'None' while using the current layout.</p>
	</div>
	<div class="mr-notice slider-optionov" <?php if($theme == 'default' && $layout == 'Slider') { } else { echo 'style="display:none"'; } ?>>
		<p><strong>Option overriden</strong><br>
		The current layout will be forcing the option 'Pagination inside content' on the 'Options' section. Customize or change layout if you want your changes on that option to take effect.</p>
	</div>
	<div class="mr-notice menu-optionov" <?php if($theme == 'default' && $layout == 'Menu') { } else { echo 'style="display:none"'; } ?>>
		<p><strong>Options overriden</strong><br>
		The current layout will be forcing the options 'Only show subcategories of active' and 'On active hide inactives' on the 'Options' section. Customize or change layout if you want your changes on those options to take effect.</p>
	</div>
	<div class="mr-notice tabs-optionov" <?php if($theme == 'default' && $layout == 'Tabs') { } else { echo 'style="display:none"'; } ?>>
		<p><strong>Options overriden</strong><br>
		The current layout will be forcing the options 'Only show actives' and 'Do not inactive on click' on the 'Options' section. Customize or change layout if you want your changes on those options to take effect.</p>
	</div>
	<div class="mr-notice mosaic-optionov" <?php if($theme == 'default' && $layout == 'Mosaic') { } else { echo 'style="display:none"'; } ?>>
		<p><strong>Options overriden</strong><br>
		The current layout will be forcing the options 'On active hide inactives' and 'Do not inactive on click' on the 'Options' section. Customize or change layout if you want your changes on those options to take effect.</p>
	</div>
	</p>
	<script>
	document.addEventListener('click',function(event) {
		if (event.target.matches('.mr-layouts')) {
			event.target.addEventListener('change',function(event) {
				if(event.target.value != 'Custom') {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-customoptions"));
				}
				if(event.target.value == 'Custom') {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-customoptions"));
				}
				if(event.target.value != 'Collapsible' && event.target.value != 'Accordion' && event.target.value != 'Slider' && event.target.value != 'Tabs' && event.target.value != 'Mosaic') {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".perlineov"));
				} else {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".perlineov"));
				}
				if(event.target.value != 'Slider' && event.target.value != 'Menu') {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".perpageov"));
				} else {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".perpageov"));
				}
				if(event.target.value == 'Tabs' || event.target.value == 'Mosaic') {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".tabs-paginationov"));
				} else {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".tabs-paginationov"));
				}
				if(event.target.value == 'Slider') {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".slider-optionov"));
				} else {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".slider-optionov"));
				}
				if(event.target.value != 'Menu') {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".menu-optionov"));
				} else {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".menu-optionov"));
				}
				if(event.target.value == 'Tabs') {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".tabs-optionov"));
				} else {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".tabs-optionov"));
				}
				if(event.target.value == 'Mosaic') {
					mrSlideDown(event.target.closest('.mr-admin').querySelector(".mosaic-optionov"));
				} else {
					mrSlideUp(event.target.closest('.mr-admin').querySelector(".mosaic-optionov"));
				}
			});
		}
	});
	</script>
	<?php
} else {
		if($layout == 'Grid' ) {
			$layoutoptions = array('themestyle','scaleactive','opaqueactive');
		} else if($layout == 'Collapsible' ) {
			$layoutoptions = array('themestyle','toggle02','revealactive','portrait');
			$perline = 1;
		} else if($layout == 'Accordion' ) {
			$layoutoptions = array('themestyle','expandactive');
			$perline = 0;
		} else if($layout == 'Slider' ) {
			$layoutoptions = array('themestyle','contentpagination');
			$perline = 1;
			$perpage = 1;
		} else if($layout == 'Menu' ) {
			$layoutoptions = array('checkcurrent','toggle02','toggle03','hambmob','revealactive','hideinactives','subitemactive');
			$perpage = 0;
		} else if($layout == 'Tabs' ) {
			$layoutoptions = array('themestyle','expandactive','portrait','onlyactives','donotinactive');
			if($tabs == 0) {
				$tabs = 1;
			}
			$perline = 0;
		} else if($layout == 'Mosaic' ) {
			$layoutoptions = array('themestyle','opaqueactive','landscape','hideinactives','donotinactive');
			if($tabs == 0) {
				$tabs = 3;
			}
			if($perline == 0 || $perline == '∞') {
				$perline = 3;
			}
		} else if($layout == 'Popup' ) {
			$layoutoptions = array('defaultstyle','fixactive','revealactive','toggle01','toggle03');
		}
	}
?>