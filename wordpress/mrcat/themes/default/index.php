<?php
 /*

	Theme Name:  Default
	
	Author:      Marcos Rego (Mr.Dev.)

	Author URI:  https://marcosrego.com

*/

defined('ABSPATH') or die;
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

</p>
<script>
jQuery(document).ready(function( $ ) {
	jQuery('.mrwid-layouts').change(function() {
		if(jQuery(this).val() == 'List') {
			jQuery(this).parent().parent().find('input[type="checkbox"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('input[value="subcatactive"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('.mrwid-perline-input').val('1');
			jQuery(this).closest('.mrwid-admin').find('.mrwid-pages-input').val('∞');
		}
		
		if(jQuery(this).val() == 'Grid') {
			jQuery(this).parent().parent().find('input[type="checkbox"]').attr('checked', false);
			jQuery(this).parent().parent().find('input[value="themestyle"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="scaleactive"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="opaqueactive"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="subcatactive"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('.mrwid-perline-input').val('4');
			jQuery(this).closest('.mrwid-admin').find('.mrwid-pages-input').val('∞');
		}
		
		if(jQuery(this).val() == 'Collapsible') {
			jQuery(this).parent().parent().find('input[type="checkbox"]').attr('checked', false);
			jQuery(this).parent().parent().find('input[value="themestyle"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="toggle02"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="keepopen"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="portrait"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="revealactive"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="subcatactive"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('.mrwid-perline-input').val('1');
			jQuery(this).closest('.mrwid-admin').find('.mrwid-pages-input').val('∞');
		}
		
		if(jQuery(this).val() == 'Accordion') {
			jQuery(this).parent().parent().find('input[type="checkbox"]').attr('checked', false);
			jQuery(this).parent().parent().find('input[value="themestyle"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="windowheight"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="expandactive"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="subcatactive"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('.mrwid-perline-input').val('∞');
			jQuery(this).closest('.mrwid-admin').find('.mrwid-pages-input').val('∞');
		}

		if(jQuery(this).val() == 'Slider') {
			jQuery(this).parent().parent().find('input[type="checkbox"]').attr('checked', false);
			jQuery(this).parent().parent().find('input[value="themestyle"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="contentpagination"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="pageselect"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('input[value="below"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('input[value="scroll"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('input[value="arrows"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="radio"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="subcatactive"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('.mrwid-perline-input').val('1');
			jQuery(this).closest('.mrwid-admin').find('.mrwid-pages-input').val('1');
		}
		
		if(jQuery(this).val() == 'Menu') {
			jQuery(this).parent().parent().find('input[type="checkbox"]').attr('checked', false);
			jQuery(this).parent().parent().find('input[value="checkcurrent"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="toggle02"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="toggle03"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="hambmob"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="revealactive"]').attr('checked', true);
			jQuery(this).parent().parent().find('input[value="hideinactives"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="subcatactive"]').attr('checked', true);
			jQuery(this).closest('.mrwid-admin').find('input[value="keepopen"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('input[value="donotclose"]').attr('checked', false);
			jQuery(this).closest('.mrwid-admin').find('.mrwid-perline-input').val('∞');
			jQuery(this).closest('.mrwid-admin').find('.mrwid-pages-input').val('∞');
		}
		
		if(jQuery(this).val() != 'Custom') {
			jQuery(this).parent().parent().find('.mrwid-customoptions').slideUp();
		}
		
		if(jQuery(this).val() == 'Custom') {
			jQuery(this).parent().parent().find('.mrwid-customoptions').slideDown();
		}
	});
});
</script>
