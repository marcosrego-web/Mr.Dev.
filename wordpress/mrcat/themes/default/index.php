<?php
 /*
	Theme Name:  Default
	Author:      Marcos Rego (Mr.Dev.)
	Author URI:  https://marcosrego.com
*/
defined('ABSPATH') or die;
if(is_admin()) {
?>
<p>
Layout:<br>
<select  class="widefat mrwid-layouts" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
<?php
$options = array( 'List','Grid','Collapsible','Accordion','Slider','Menu','Custom');
foreach ( $options as $option ) {
	echo '<option value="' . $option . '" id="' . $option . '"', $layout == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
}
?>
</select>
</p>
<p>
<div class="mrwid-customoptions" <?php if($layout != 'Custom') { echo 'style="display: none;"'; } ?>>
<label class="mrwid-label" for="<?php echo $this->get_field_id( 'layoutoptions' ); ?>"><strong><?php _e( 'customize:' ); ?></strong></label>
<p>Style:</p>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="themestyle" <?php checked( ( is_array( $layoutoptions ) AND in_array( "themestyle", $layoutoptions ) ) ? "themestyle" : '', "themestyle" ); ?> /> <?php _e( 'Apply theme style' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="checkcurrent" <?php checked( ( is_array( $layoutoptions ) AND in_array( "checkcurrent", $layoutoptions ) ) ? "checkcurrent" : '', "checkcurrent" ); ?> /> <?php _e( 'Current categories or link' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="verticaltitle" <?php checked( ( is_array( $layoutoptions ) AND in_array( "verticaltitle", $layoutoptions ) ) ? "verticaltitle" : '', "verticaltitle" ); ?> /> <?php _e( 'Vertical titles' ); ?></label><br>
<p>Toggles:</p>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="toggle01" <?php checked( ( is_array( $layoutoptions ) AND in_array( "toggle01", $layoutoptions ) ) ? "toggle01" : '', "toggle01" ); ?> /> <?php _e( 'Plus toggle' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="toggle02" <?php checked( ( is_array( $layoutoptions ) AND in_array( "toggle02", $layoutoptions ) ) ? "toggle02" : '', "toggle02" ); ?> /> <?php _e( 'Direction toggle' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="toggle03" <?php checked( ( is_array( $layoutoptions ) AND in_array( "toggle03", $layoutoptions ) ) ? "toggle03" : '', "toggle03" ); ?> /> <?php _e( 'Close toggle' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="hambmob" <?php checked( ( is_array( $layoutoptions ) AND in_array( "hambmob", $layoutoptions ) ) ? "hambmob" : '', "hambmob" ); ?> /> <?php _e( 'Hamburguer mobile' ); ?></label><br>
<p>Transitions:</p>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="revealactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "revealactive", $layoutoptions ) ) ? "revealactive" : '', "revealactive" ); ?> /> <?php _e( 'Reveal active' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="expandactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "expandactive", $layoutoptions ) ) ? "expandactive" : '', "expandactive" ); ?> /> <?php _e( 'Expand active' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="scaleactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "scaleactive", $layoutoptions ) ) ? "scaleactive" : '', "scaleactive" ); ?> /> <?php _e( 'Scale active' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="opaqueactive" <?php checked( ( is_array( $layoutoptions ) AND in_array( "opaqueactive", $layoutoptions ) ) ? "opaqueactive" : '', "opaqueactive" ); ?> /> <?php _e( 'Opaque active' ); ?></label><br>
<p>Modes:</p>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="windowheight" <?php checked( ( is_array( $layoutoptions ) AND in_array( "windowheight", $layoutoptions ) ) ? "windowheight" : '', "windowheight" ); ?> /> <?php _e( 'Window height' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="contentpagination" <?php checked( ( is_array( $layoutoptions ) AND in_array( "contentpagination", $layoutoptions ) ) ? "contentpagination" : '', "contentpagination" ); ?> /> <?php _e( 'Pagination inside content' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="hideinactives" <?php checked( ( is_array( $layoutoptions ) AND in_array( "hideinactives", $layoutoptions ) ) ? "hideinactives" : '', "hideinactives" ); ?> /> <?php _e( 'When active hide inactives' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="landscape" <?php checked( ( is_array( $layoutoptions ) AND in_array( "landscape", $layoutoptions ) ) ? "landscape" : '', "landscape" ); ?> /> <?php _e( 'Landscape on portrait window' ); ?></label><br>
<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="portrait" <?php checked( ( is_array( $layoutoptions ) AND  in_array( "portrait", $layoutoptions ) ) ? "portrait" : '', "portrait" ); ?> /> <?php _e( 'Portrait on landscape window' ); ?></label><br>
</div>
<div class="mrwid-notice perlineov" <?php if($theme == 'Default' && $layout == 'Collapsible' || $theme == 'Default' && $layout == 'Accordion' || $theme == 'Default' && $layout == 'Slider') { } else { echo 'style="display: none"'; } ?>><p>
	<strong>Items per line overriden</strong><br>
	The current layout will be forcing the items per line number on the 'Pagination' section. Customize or change layout if you want your changes on that option to take effect.</p>
</div>
<div class="mrwid-notice perpageov" <?php if($theme == 'Default' && $layout == 'Slider') { } else { echo 'style="display:none"'; } ?>>
	<p><strong>Items per page overriden</strong><br>
	The current layout will be forcing the items per page number on the 'Pagination' section. Customize or change layout if you want your changes on that option to take effect.</p>
</div>
<div class="mrwid-notice optionov" <?php if($theme == 'Default' && $layout == 'Menu') { } else { echo 'style="display:none"'; } ?>>
	<p><strong>Option overriden</strong><br>
	The current layout will be forcing to 'Only show subcategories of active' on the 'Options' section. Customize or change layout if you want your changes on that option to take effect.</p>
</div>
</p>
<script>
jQuery(document).ready(function( $ ) {
	jQuery('.mrwid-layouts').change(function() {
		if(jQuery(this).val() != 'Custom') {
			jQuery(this).parent().parent().find('.mrwid-customoptions').slideUp();
		}
		if(jQuery(this).val() == 'Custom') {
			jQuery(this).parent().parent().find('.mrwid-customoptions').slideDown();
		}
		if(jQuery(this).val() != 'Collapsible' && jQuery(this).val() != 'Accordion' && jQuery(this).val() != 'Slider' ) {
			jQuery(this).closest('.mrwid-admin').find('.perlineov').slideUp();
		} else {
			jQuery(this).closest('.mrwid-admin').find('.perlineov').slideDown();
		}
		if(jQuery(this).val() != 'Slider' ) {
			jQuery(this).closest('.mrwid-admin').find('.perpageov').slideUp();
		} else {
			jQuery(this).closest('.mrwid-admin').find('.perpageov').slideDown();
		}
		if(jQuery(this).val() != 'Menu' ) {
			jQuery(this).closest('.mrwid-admin').find('.optionov').slideUp();
		} else {
			jQuery(this).closest('.mrwid-admin').find('.optionov').slideDown();
		}
	});
});
</script>
<?php
	} else {
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
	}
?>
