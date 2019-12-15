<?php
/*
MR Categories
*/
defined('ABSPATH') or die;
/*------WIDGET FRONT------*/
function mr_load_widget() {
    register_widget( 'mr_categories' );
}
add_action( 'widgets_init', 'mr_load_widget' );
class mr_categories extends WP_Widget {
	function __construct() {
		parent::__construct(
		'mr_categories', 
		__('MR Categories', 'mr_categories'), 
		array( 'description' => __( 'from Mr.Cat. | Displays categories in a variety of layouts and customizable options.', 'mr_categories' ), ) 
		);
	}
	public function widget( $args, $instance ) {
		/*--- Get all instances into variables ---*/
		$title = apply_filters( 'widget_title', $instance['title'] );
		$theme = htmlspecialchars($instance['theme']);
		$layout = htmlspecialchars($instance['layout']);
		$perline = intval($instance['perline']);
		$perpage = intval($instance['perpage']);
		$pagetransition = htmlspecialchars($instance['pagetransition']);
		$pagetoggles = array_filter($instance['pagetoggles'], 'is_numeric');
		$layoutoptions = array_map("htmlspecialchars", $instance['layoutoptions']);
		$orderby = intval($instance['orderby']);
		$order = intval($instance['order']);
		$excludeinclude = intval($instance['excludeinclude']);
		$itemselect = array_filter($instance['itemselect'], 'is_numeric');
		$bottomlink = htmlspecialchars($instance['bottomlink']);
		$maintitle = intval($instance['maintitle']);
		$itemimage = intval($instance['itemimage']);
		$itemstitle = intval($instance['itemstitle']);
		$itemstitlemax = intval($instance['itemstitlemax']);
		$itemdesc = intval($instance['itemdesc']);
		$itemdescmax = intval($instance['itemdescmax']);
		$itemlink = intval($instance['itemlink']);
		$itemoptions = array_map("htmlspecialchars",$instance['itemoptions']);
		$globallayoutoptions = array_map("htmlspecialchars", $instance['globallayoutoptions']);
		$lastactivedetails = htmlspecialchars($instance['lastactivedetails']);
		echo $args['before_widget'];
			/* Add the main global script and style */
			wp_register_script( 'mrcat_scripts', plugin_dir_url( __DIR__ ).'assets/js/mrcat_v051.js');
			wp_enqueue_script( 'mrcat_scripts' );
			wp_enqueue_style( 'mrcat_css', plugin_dir_url( __DIR__ ).'assets/css/mrcat_v051.css');
			$browsercheck = $_SERVER['HTTP_USER_AGENT'];
			if ( strpos($browsercheck, 'rv:11.0') !== false && strpos($browsercheck, 'Trident/7.0;')!== false || isset($browsercheck) && (strpos($browsercheck, 'MSIE') !== false)) {
				/*Polyfill for Vanilla Javascript on Internet Explorer*/
				wp_register_script( 'mrcat_polyfill', '//polyfill.io/v3/polyfill.min.js');
				wp_enqueue_script( 'mrcat_polyfill' );
				wp_script_add_data( 'mrcat_polyfill', 'crossorigin' , 'anonymous' );
			}
				$content = '';
				/*
				Check if it's an official theme or a custom one.
				A css file with the theme's name is mandatory.
				If it's official it has the version number.
				*/
				if(!$theme) {
					include plugin_dir_path( __DIR__ ).'themes/default/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', plugin_dir_url( __DIR__ ).'themes/default/default_v051.css');
				} else if($theme == "default") {
					//Official Themes
					include plugin_dir_path( __DIR__ ).'themes/'.$theme.'/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', plugin_dir_url( __DIR__ ).'themes/'.$theme.'/'.$theme.'_v051.css');
				} else {
					//Custom Themes
					include ABSPATH.'wp-content/themes/mrdev/'.$theme.'/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', get_home_url().'/wp-content/themes/mrdev/'.$theme.'/'.$theme.'.css');
				}
				require trailingslashit( plugin_dir_path( __FILE__ )).'/items.php';
			echo __( $content, 'mr_categories' );
		echo $args['after_widget'];
	}
/*------WIDGET ADMIN------*/
	public function form( $instance ) {
		wp_enqueue_style( 'mrwid_admin', plugin_dir_url( __DIR__ ).'assets/css/admin_v051.css');
		?>
		<div class="mrwid-admin">
		<p class="mrwid-section"><a href="https://marcosrego.com/en/web-en/mrcat-en/" target="_blank">
		<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTM3MC4zODEsMzA3LjgzN2MtMTIuMDExLTQuOTY4LTE5Ljc3Mi0xNi41NzUtMTkuNzcyLTI5LjU3NmMwLTU0LjI5OS00My4zMjYtMTAwLjE3NC05NC42MDktMTAwLjE3NCAgICBzLTk0LjYwOSw0NS44NzUtOTQuNjA5LDEwMC4xNzRjMCwxMy03Ljc2MSwyNC42MTQtMTkuNzcyLDI5LjU4MWMtMzUuMzE1LDE0LjYxNC01OC4xNDEsNDguODc1LTU4LjE0MSw4Ny4yODggICAgYzAsNTIuMTY4LDQyLjQ0Niw5NC42MDksOTQuNjA5LDk0LjYwOWMxMy4xODUsMCwyNS45NTYtMi42NjgsMzcuOTc4LTcuOTM1YzI1LjM4MS0xMS4xMjUsNTQuNDc4LTExLjE0Miw3OS44ODEsMC4wMDYgICAgYzEyLjAxMSw1LjI2MSwyNC43ODIsNy45MjksMzcuOTY3LDcuOTI5YzUyLjE2MywwLDk0LjYwOS00Mi40NCw5NC42MDktOTQuNjA5QzQyOC41MjIsMzU2LjcxMSw0MDUuNjk1LDMyMi40NDUsMzcwLjM4MSwzMDcuODM3eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTE3OC4wODcsMjIuMjYxYy0zNi4xNTIsMC01NS42NTIsNjMuMjAxLTU1LjY1Miw4OS4wNDRjMCwzMC42ODQsMjQuOTY4LDU1LjY1Miw1NS42NTIsNTUuNjUyICAgIHM1NS42NTItMjQuOTY4LDU1LjY1Mi01NS42NTJDMjMzLjczOSw4NS40NjIsMjE0LjIzOSwyMi4yNjEsMTc4LjA4NywyMi4yNjF6IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNMzMzLjkxMywyMi4yNjFjLTM2LjE1MiwwLTU1LjY1Miw2My4yMDEtNTUuNjUyLDg5LjA0NGMwLDMwLjY4NCwyNC45NjgsNTUuNjUyLDU1LjY1Miw1NS42NTIgICAgYzMwLjY4NCwwLDU1LjY1Mi0yNC45NjgsNTUuNjUyLTU1LjY1MkMzODkuNTY1LDg1LjQ2MiwzNzAuMDY1LDIyLjI2MSwzMzMuOTEzLDIyLjI2MXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik01NS42NTIsMTU1LjgyNkMxOS41MDEsMTU1LjgyNiwwLDIxOS4wMjcsMCwyNDQuODdjMCwzMC42ODQsMjQuOTY4LDU1LjY1Miw1NS42NTIsNTUuNjUyICAgIGMzMC42ODQsMCw1NS42NTItMjQuOTY4LDU1LjY1Mi01NS42NTJDMTExLjMwNCwyMTkuMDI3LDkxLjgwNCwxNTUuODI2LDU1LjY1MiwxNTUuODI2eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQ1Ni4zNDgsMTU1LjgyNmMtMzYuMTUyLDAtNTUuNjUyLDYzLjIwMS01NS42NTIsODkuMDQzYzAsMzAuNjg0LDI0Ljk2OCw1NS42NTIsNTUuNjUyLDU1LjY1MiAgICBjMzAuNjg0LDAsNTUuNjUyLTI0Ljk2OCw1NS42NTItNTUuNjUyQzUxMiwyMTkuMDI3LDQ5Mi41LDE1NS44MjYsNDU2LjM0OCwxNTUuODI2eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" alt="Mr.Cat. Signature" title="Icon made by Freepik from flaticon.com"/> <strong style="font-weight:700" title="Click to know Mr.Cat.">Mr.Cat.</strong></a>
		</p>
		<?php
						if ( isset( $instance[ 'title' ] ) ) {
							$title = $instance[ 'title' ];
						}
						else {
							$title = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'theme' ] ) ) {
							$theme = $instance[ 'theme' ];
						}
						else {
							$theme = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'layout' ] ) ) {
							$layout = $instance[ 'layout' ];
						}
						else {
							$layout = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'layoutoptions' ] ) ) {
							$layoutoptions = $instance[ 'layoutoptions' ];
						}
						else {
							$layoutoptions = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'orderby' ] ) ) {
							$orderby = $instance[ 'orderby' ];
						}
						else {
							$orderby = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'order' ] ) ) {
							$order = $instance[ 'order' ];
						}
						else {
							$order = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'excludeinclude' ] ) ) {
							$excludeinclude = $instance[ 'excludeinclude' ];
						}
						else {
							$excludeinclude = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemselect' ] ) ) {
							$itemselect = $instance[ 'itemselect' ];
						}
						else {
							$itemselect = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'maintitle' ] ) ) {
							$maintitle = $instance[ 'maintitle' ];
						}
						else {
							$maintitle = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemimage' ] ) ) {
							$itemimage = $instance[ 'itemimage' ];
						}
						else {
							$itemimage = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemstitle' ] ) ) {
							$itemstitle = $instance[ 'itemstitle' ];
						}
						else {
							$itemstitle = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemstitlemax' ] ) ) {
							$itemstitlemax = $instance[ 'itemstitlemax' ];
						}
						else {
							$itemstitlemax = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemdesc' ] ) ) {
							$itemdesc = $instance[ 'itemdesc' ];
						}
						else {
							$itemdesc = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemdescmax' ] ) ) {
							$itemdescmax = $instance[ 'itemdescmax' ];
						}
						else {
							$itemdescmax = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemlink' ] ) ) {
							$itemlink = $instance[ 'itemlink' ];
						}
						else {
							$itemlink = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'bottomlink' ] ) ) {
							$bottomlink = $instance[ 'bottomlink' ];
						}
						else {
							$bottomlink = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'perline' ] ) ) {
							$perline = $instance[ 'perline' ];
						}
						else {
							$perline = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'perpage' ] ) ) {
							$perpage = $instance[ 'perpage' ];
						}
						else {
							$perpage = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'pagetransition' ] ) ) {
							$pagetransition = $instance[ 'pagetransition' ];
						}
						else {
							$pagetransition = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'pagetoggles' ] ) ) {
							$pagetoggles = $instance[ 'pagetoggles' ];
						}
						else {
							$pagetoggles = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'itemoptions' ] ) ) {
							$itemoptions = $instance[ 'itemoptions' ];
						}
						else {
							$itemoptions = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'globallayoutoptions' ] ) ) {
							$globallayoutoptions = $instance[ 'globallayoutoptions' ];
						}
						else {
							$globallayoutoptions = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'lastactivedetails' ] ) ) {
							$lastactivedetails = $instance[ 'lastactivedetails' ];
						}
						else {
							$lastactivedetails = __( '', 'mr_categories' );
						}
						if ( !is_array($itemselect) || empty($itemselect) ) {
							$itemselect = array();
						}
						if ( !is_array($layoutoptions) || empty($layoutoptions) ) {
							$layoutoptions = array();
						}
						if ( !is_array($itemoptions) || empty($itemoptions) ) {
							$itemoptions = array();
						}
						if ( !is_array($globallayoutoptions) || empty($globallayoutoptions) ) {
							$globallayoutoptions = array();
						}
						if ( !is_array($pagetoggles) || empty($pagetoggles) ) {
							$pagetoggles = array(0); //Defaults to 'Arrows'
						}
						?>
						<p>
						<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label><br>
						<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
						</p>
						<details class="widgetDetails" <?php if(esc_attr( $lastactivedetails ) == 'widgetDetails') { echo 'open="open"'; } ?>>
						<summary class="mrwid-section">Categories</summary>
						<p>
						<label  for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Sort by:' ); ?></label><br>
								<select  class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
									<?php
										echo '<option value="0"', $orderby == 0 ? ' selected="selected"' : '', '>Parent</option>
										<option value="1"', $orderby == 1 ? ' selected="selected"' : '', '>Title</option>
										<option value="2"', $orderby == 2 ? ' selected="selected"' : '', '>Creation</option>
										<option value="3"', $orderby == 3 ? ' selected="selected"' : '', '>Post count</option>
										<option value="4"', $orderby == 4 ? ' selected="selected"' : '', '>Slug</option>';
									?>
								</select><br>
								<select  class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
									<?php
										echo '
										<option value="0" id="Ascending"', $orderby == 0 ? ' selected="selected"' : '', '>Ascending</option>
										<option value="1" id="Descending"', $orderby == 1 ? ' selected="selected"' : '', '>Descending</option>
										';
									?>
						</select><br>
						</p>
						<p class="mrwid-heading">	
						<select class="mrwid-excludeinclude" id="<?php echo $this->get_field_id('excludeinclude'); ?>" name="<?php echo $this->get_field_name('excludeinclude'); ?>">
									<?php
										echo '<option value="0"', $excludeinclude == 0 ? ' selected="selected"' : '', '>Exclude</option>
										<option value="1"', $excludeinclude == 1 ? ' selected="selected"' : '', '>Include</option>';
									?>
						</select>:
						<div class="mrwid-list <?php if($excludeinclude == 1) { echo 'including '; } ?>" >
						<?php
							  /*-----CAT SELECT-----*/
								include trailingslashit( plugin_dir_path( __FILE__ )).'/items.php';
						?>
						</div>
						</p>
						</details>
						<details class="appearanceDetails" <?php if(esc_attr( $lastactivedetails ) == 'appearanceDetails') { echo 'open="open"'; } ?>>
						<summary class="mrwid-section">Appearance</summary>
						<p>
						Theme:<br>
								<select  class="widefat mrwid-themes" id="<?php echo $this->get_field_id('theme'); ?>" name="<?php echo $this->get_field_name('theme'); ?>">
									<?php
										$options = array('default');
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $theme == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
										$customOptions = array_map('basename', glob(ABSPATH.'wp-content/themes/mrdev/*' , GLOB_ONLYDIR));
										foreach ( $customOptions as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $theme == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
								</select>
								<div class="mrwid-themeoptions">
								<?php
								if(!$theme) {
									include trailingslashit( plugin_dir_path( __DIR__ )).'themes/default/index.php';
								} else {
									if($theme == "default") {
										include trailingslashit( plugin_dir_path( __DIR__ )).'themes/'.$theme.'/index.php';
									} else {
										include ABSPATH.'wp-content/themes/mrdev/'.$theme.'/index.php';
									}
								}
								?>
								</div>
								<div class="mrwid-savetheme" style="display: none;">
								Please save your changes for the theme options to appear.
								</div>
						</p>
						</details>
						<details class="paginationDetails" <?php if(esc_attr( $lastactivedetails ) == 'paginationDetails') { echo 'open="open"'; } ?>>
						<summary class="mrwid-section">Pagination</summary>
						<p>
						<select class="mrwid-pagination-input mrwid-perline-input" id="<?php echo $this->get_field_id('perline'); ?>" name="<?php echo $this->get_field_name('perline'); ?>" title="Choose the number of items per line">
									<?php
										echo '<option value="0"', $perline == 0 ? ' selected="selected"' : '', '>∞</option>
										<option value="1"', $perline == 1 ? ' selected="selected"' : '', '>1</option>
										<option value="2"', $perline == 2 ? ' selected="selected"' : '', '>2</option>
										<option value="3"', $perline == 3 ? ' selected="selected"' : '', '>3</option>
										<option value="4"', $perline == 4 ? ' selected="selected"' : '', '>4</option>
										<option value="5"', $perline == 5 ? ' selected="selected"' : '', '>5</option>
										<option value="6"', $perline == 6 ? ' selected="selected"' : '', '>6</option>
										<option value="7"', $perline == 7 ? ' selected="selected"' : '', '>7</option>
										<option value="8"', $perline == 8 ? ' selected="selected"' : '', '>8</option>
										<option value="9"', $perline == 9 ? ' selected="selected"' : '', '>9</option>
										<option value="10"', $perline == 10 ? ' selected="selected"' : '', '>10</option>
										<option value="11"', $perline == 11 ? ' selected="selected"' : '', '>11</option>
										<option value="12"', $perline == 12 ? ' selected="selected"' : '', '>12</option>';
									?>
						</select> per line<br>
						<input class="mrwid-pagination-input mrwid-pages-input" type="number" id="<?php echo $this->get_field_id( 'perpage' ); ?>" name="<?php echo $this->get_field_name( 'perpage' ); ?>" type="text" placeholder="∞" title="Choose the number of items per page" value="<?php if(esc_attr( $perpage ) == "" || esc_attr( $perpage ) <= 0) { } else { echo esc_attr( $perpage ); } ?>" /> per page
						</p>
						<p>
									<label  for="<?php echo $this->get_field_id( 'pagetoggles' ); ?>"><?php _e( 'Toggles:' ); ?></label> <br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="0" <?php if( is_array( $pagetoggles ) && in_array( 0, $pagetoggles ) || !is_array( $pagetoggles ) || is_array( $pagetoggles ) && !in_array( 0, $pagetoggles ) && !in_array( 1, $pagetoggles ) && !in_array( 2, $pagetoggles ) && !in_array( 3, $pagetoggles) && !in_array( 4, $pagetoggles) ) { echo 'checked="checked"'; } ?> /> <?php _e( 'Arrows' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="1" <?php checked( ( is_array($pagetoggles) AND in_array( 1, $pagetoggles ) ) ? 1 : '', 1 ); ?> /> <?php _e( 'Select' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="2" <?php checked( ( is_array($pagetoggles) AND in_array( 2, $pagetoggles ) ) ? 2 : '', 2 ); ?> /> <?php _e( 'Radio' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="5" <?php checked( ( is_array($pagetoggles) AND in_array( 5, $pagetoggles ) ) ? 5 : '', 5 ); ?> /> <?php _e( 'Keyboard' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="3" <?php checked( ( is_array($pagetoggles) AND in_array( 3, $pagetoggles ) ) ? 3 : '', 3 ); ?> /> <?php _e( 'Below' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="4" <?php checked( ( is_array($pagetoggles) AND in_array( 4, $pagetoggles ) ) ? 4 : '', 4 ); ?> /> <?php _e( 'Scroll' ); ?></label><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'pagetransition' ); ?>"><?php _e( 'Transition:' ); ?></label><br>
						<select  class="widefat" id="<?php echo $this->get_field_id('pagetransition'); ?>" name="<?php echo $this->get_field_name('pagetransition'); ?>">
									<?php
										echo '<option value="fade" id="fade"', $pagetransition == 'fade' ? ' selected="selected"' : '', '>Fade</option>
										<option value="slide" id="slide"', $pagetransition == 'slide' ? ' selected="selected"' : '', '>Slide</option>
										<option value="scale" id="scale"', $pagetransition == 'scale' ? ' selected="selected"' : '', '>Scale</option>
										<option value="zoom" id="zoom"', $pagetransition == 'zoom' ? ' selected="selected"' : '', '>Zoom</option>
										<option value="none" id="none"', $pagetransition == 'none' ? ' selected="selected"' : '', '>None</option>';
									?>
								</select><br>
						</p>
						</details>
						<details class="displayDetails" <?php if(esc_attr( $lastactivedetails ) == 'displayDetails') { echo 'open="open"'; } ?>>
						<summary class="mrwid-section">Display</summary>
						<p>
						<label  for="<?php echo $this->get_field_id( 'maintitle' ); ?>"><?php _e( 'Main title:' ); ?></label><br>
						<select  class="widefat" id="<?php echo $this->get_field_id('maintitle'); ?>" name="<?php echo $this->get_field_name('maintitle'); ?>">
							<?php
							echo '<option value="0" id="widgettitle"', $maintitle == 0 ? ' selected="selected"' : '', '>Widget title</option>
							<option value="3" id="themeandlayouttitle"', $maintitle == 3 ? ' selected="selected"' : '', '>Theme and layout title</option>
							<option value="4" id="themetitle"', $maintitle == 4 ? ' selected="selected"' : '', '>Theme title</option>
							<option value="5" id="layouttitle"', $maintitle == 5 ? ' selected="selected"' : '', '>Layout title</option>
							<option value="6" id="nomaintitle"', $maintitle == 6 ? ' selected="selected"' : '', '>No main title</option>';
							?>
						</select><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemimage' ); ?>"><?php _e( 'Images:' ); ?></label><br>
						<select <?php if($display_access == 'Denied') { echo 'disabled'; } ?> class="widefat mrwid-itemimage" id="<?php echo $this->get_field_id('itemimage'); ?>" name="<?php echo $this->get_field_name('itemimage'); ?>">
										<?php
											echo '<option value="0"', $itemimage == 0 ? ' selected="selected"' : '', '>No image</option>
											<option value="8"', $itemimage == 8 ? ' selected="selected"' : '', '>Description first image</option>
											<option value="2"', $itemimage == 2 ? ' selected="selected"' : '', '>Latest sticky post image</option>
											<option value="5"', $itemimage == 5 ? ' selected="selected"' : '', '>Latest post image</option>';
										?>
						</select><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemstitle' ); ?>"><?php _e( 'Titles:' ); ?></label><br>
						<select  class="widefat mrwid-itemstitleinput" id="<?php echo $this->get_field_id('itemstitle'); ?>" name="<?php echo $this->get_field_name('itemstitle'); ?>">
									<?php
										echo '<option value="0"', $itemstitle == 0 ? ' selected="selected"' : '', '>Linked category title</option>
										<option value="2"', $itemstitle == 2 ? ' selected="selected"' : '', '>Category title</option>
										<option value="1"', $itemstitle == 1 ? ' selected="selected"' : '', '>No title</option>';
									?>
								</select><br>
								<span class="mrwid-itemstitlemax">
								<input <?php if($display_access == 'Denied') { echo 'disabled'; } ?> <?php if($itemstitle && $itemstitle == 1) { echo 'style="display: none;"'; } ?> class="widefat mrwid-pagination-input" id="<?php echo $this->get_field_id( 'itemstitlemax' ); ?>" name="<?php echo $this->get_field_name( 'itemstitlemax' ); ?>" placeholder="∞" type="number" value="<?php if(!esc_attr( $itemstitlemax )) {  } else { echo esc_attr( $itemstitlemax ); } ?>" /> max. characters
								</span>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemdesc' ); ?>"><?php _e( 'Descriptions:' ); ?></label><br>
						<select  class="widefat mrwid-itemdescinput" id="<?php echo $this->get_field_id('itemdesc'); ?>" name="<?php echo $this->get_field_name('itemdesc'); ?>">
									<?php
										echo '<option value="0"', $itemdesc == 0 ? ' selected="selected"' : '', '>Category description</option>
										<option value="2"', $itemdesc == 2 ? ' selected="selected"' : '', '>Category intro text</option>
										<option value="3"', $itemdesc == 3 ? ' selected="selected"' : '', '>Category full text</option>
										<option value="1"', $itemdesc == 1 ? ' selected="selected"' : '', '>No description</option>';
									?>
								</select><br>
								<span class="mrwid-itemdescmax">
								<input <?php if($display_access == 'Denied') { echo 'disabled'; } ?> <?php if($itemdesc && $itemdesc == 1) { echo 'style="display: none;"'; } ?> class="widefat mrwid-pagination-input" id="<?php echo $this->get_field_id( 'itemdescmax' ); ?>" name="<?php echo $this->get_field_name( 'itemdescmax' ); ?>" placeholder="∞" type="number" value="<?php if(!esc_attr( $itemdescmax )) {  } else { echo esc_attr( $itemdescmax ); } ?>" /> max. characters
								</span>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemlink' ); ?>"><?php _e( 'Links:' ); ?></label><br>
						<select  class="widefat mrwid-bottomlinkinput" id="<?php echo $this->get_field_id('itemlink'); ?>" name="<?php echo $this->get_field_name('itemlink'); ?>">
									<?php
										echo '<option value="0"', $itemlink == 0 ? ' selected="selected"' : '', '>Category link</option>
										<option value="1"', $itemlink == 1 ? ' selected="selected"' : '', '>No bottom link</option>';
									?>
						</select><br>
						<input  class="widefat  mrwid-bottomlinktext" id="<?php echo $this->get_field_id( 'bottomlink' ); ?>"  <?php if(isset($itemlink) && $itemlink == 1) { echo 'style="display: none;"'; } ?>  name="<?php echo $this->get_field_name( 'bottomlink' ); ?>" type="text" placeholder="Bottom link text" title="Bottom link text" value="<?php if(esc_attr( $bottomlink ) == "") { echo "Know more..."; } else { echo esc_attr( $bottomlink ); } ?>" />
						</p>
						</details>
						<details class="optionsDetails" <?php if(esc_attr( $lastactivedetails ) == 'optionsDetails') { echo 'open="open"'; }  ?>>
						<summary class="mrwid-section">Options</summary>
						<p>
						<label  for="<?php echo $this->get_field_id( 'globallayoutoptions' ); ?>"><?php _e( 'Global layout options:' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="windowheight" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "windowheight", $globallayoutoptions ) ) ? "windowheight" : '', "windowheight" ); ?> /> <?php _e( 'Window height' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="contentpagination" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "contentpagination", $globallayoutoptions ) ) ? "contentpagination" : '', "contentpagination" ); ?> /> <?php _e( 'Pagination inside content' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="donotinactive" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "donotinactive", $globallayoutoptions ) ) ? "donotinactive" : '', "donotinactive" ); ?> /> <?php _e( 'Do not inactive on click' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="keepactive" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "keepactive", $globallayoutoptions ) ) ? "keepactive" : '', "keepactive" ); ?> /> <?php _e( 'Keep other actives opened' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="hideinactives" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "hideinactives", $globallayoutoptions ) ) ? "hideinactives" : '', "hideinactives" ); ?> /> <?php _e( 'When active hide inactives' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="subcatactive" <?php checked( ( is_array($globallayoutoptions ) AND in_array( "subcatactive", $globallayoutoptions ) ) ? "subcatactive" : '', "subcatactive" ); ?> /> <?php _e( 'Only show subcategories of active' ); ?></label><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemoptions' ); ?>"><?php _e( 'Other options:' ); ?></label> <br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="artcount" <?php checked( ( is_array($itemoptions) AND in_array( "artcount", $itemoptions ) ) ? "artcount" : '', "artcount" ); ?> /> <?php _e( 'Show number of articles' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="hover" <?php checked( ( is_array( $itemoptions ) AND in_array( "hover", $itemoptions ) ) ? "hover" : '', "hover" ); ?> /> <?php _e( 'Active on mouseover' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="autoscroll" <?php checked( ( is_array( $itemoptions ) AND in_array( "autoscroll", $itemoptions ) ) ? "autoscroll" : '', "autoscroll" ); ?> /> <?php _e( 'Auto scroll to active' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="url" <?php checked( ( is_array( $itemoptions ) AND in_array( "url", $itemoptions ) ) ? "url" : '', "url" ); ?> /> <?php _e( 'Change URL on active' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="remember" <?php checked( ( is_array( $itemoptions ) AND in_array( "remember", $itemoptions ) ) ? "remember" : '', "remember" ); ?> /> <?php _e( 'Remember last active <small>(<i>uses cookies</i>)</small>' ); ?></label><br>
						</p>
						</details>
						<input class="widefat lastactivedetails" id="<?php echo $this->get_field_id( 'lastactivedetails' ); ?>" name="<?php echo $this->get_field_name( 'lastactivedetails' ); ?>" type="text" placeholder="Last Active Admin Details/Option" title="Last Active Admin Details/Option" value="<?php if(esc_attr( $lastactivedetails ) != "") { echo esc_attr( $lastactivedetails ); } ?>" readonly hidden />
						<details class="featuresDetails" <?php if(esc_attr( $lastactivedetails ) == 'featuresDetails') { echo 'open="open"'; } ?>>
						<summary class="mrwid-section"><strong>DO YOU NEED MORE FEATURES?</strong></summary>
						<p>
						If you need more features then you need <strong>Mr.Dev.</strong><br>One plugin that combines all MR widgets, add-ons and utilities giving to "MR Categories" extras such as:</p>
						<ol>
						<li>Insert widgets inside the content on posts/pages/categories using <strong>blocks, classic editor button or shortcodes</strong>.</li>
						<li>Choose <strong>main categories</strong> to only display their childs.</li>
						<li>Manually <strong>reorder</strong>.</li>
						<li><strong>Pin</strong> to choose the one starting active.</li>
						<li><strong>Auto exclude</strong> Subcategories, Categories with no posts, same link, different link and more.</li>
						<li>More image options such as <strong>thumbnails and parallax</strong>.</li>
						<li>Choose a <strong>fallback image</strong>.</li>
						<li><strong>Hide widget sections</strong> to specific users or roles.</li>
						<li>Other <strong>Advanced</strong> options such as preload pages, choose the titles tag (h2, h3, h4, p, etc), do not load polyfill on IE and add custom classes to the bottom link.</li>
						</ol>
						<p>And more...</p>
						<p><a class="button button-primary" href="https://marcosrego.com/en/web-en/mrdev-en/" target="_blank">Get Mr.Dev.</a></p>
					</details>
						<?php
						/* The following script is inline to run even after saving the widget */
						?>
						<script>
						function mrSlideUp(target) {
							let duration = 500;
							target.style.transitionProperty = 'height, margin, padding';
							target.style.transitionDuration = duration + 'ms';
							target.style.boxSizing = 'border-box';
							target.style.height = target.offsetHeight + 'px';
							target.offsetHeight;
							target.style.overflow = 'hidden';
							target.style.height = 0;
							target.style.paddingTop = 0;
							target.style.paddingBottom = 0;
							target.style.marginTop = 0;
							target.style.marginBottom = 0;
							window.setTimeout( function() {
								target.style.display = 'none';
								target.style.removeProperty('height');
								target.style.removeProperty('padding-top');
								target.style.removeProperty('padding-bottom');
								target.style.removeProperty('margin-top');
								target.style.removeProperty('margin-bottom');
								target.style.removeProperty('overflow');
								target.style.removeProperty('transition-duration');
								target.style.removeProperty('transition-property');
							}, duration);
						}
						function mrSlideDown(target) {
							let duration = 500;
							let display = window.getComputedStyle(target).display;
							if (display === 'none' && display != 'block') {
								target.style.removeProperty('display');
								display = 'block';
								target.style.display = display;
								let height = target.offsetHeight;
								target.style.overflow = 'hidden';
								target.style.height = 0;
								target.style.paddingTop = 0;
								target.style.paddingBottom = 0;
								target.style.marginTop = 0;
								target.style.marginBottom = 0;
								target.offsetHeight;
								target.style.boxSizing = 'border-box';
								target.style.transitionProperty = "height, margin, padding";
								target.style.transitionDuration = duration + 'ms';
								target.style.height = height + 'px';
								target.style.removeProperty('padding-top');
								target.style.removeProperty('padding-bottom');
								target.style.removeProperty('margin-top');
								target.style.removeProperty('margin-bottom');
								window.setTimeout( function() {
									target.style.removeProperty('height');
									target.style.removeProperty('overflow');
									target.style.removeProperty('transition-duration');
									target.style.removeProperty('transition-property');
								}, duration);
							}
						}
						document.addEventListener('click',function(event) {
							if(event.target.matches('.mrwid-admin details:not([open]) .mrwid-section')) {
								event.target.closest('.mrwid-admin').querySelector("input.lastactivedetails").value = event.target.parentElement.getAttribute('class');
								var mrwidDetails = document.querySelectorAll(".mrwid-admin details");
								for (var id = 0; id < mrwidDetails.length; id++) {
									var mrwidDetail = mrwidDetails[id];
									if (!mrwidDetail.classList.contains('mrwid-mainitemcontainer') && mrwidDetail !== event.target) {
										mrwidDetail.removeAttribute("open");
									}
								}
							} else if (event.target.matches('.mrwid-themes')) {
								event.target.addEventListener('change',function(event) {
									mrSlideUp(event.target.closest('.mrwid-admin').querySelector(".mrwid-themeoptions"));
									mrSlideDown(event.target.closest('.mrwid-admin').querySelector(".mrwid-savetheme"));
								});
							} else if (event.target.matches('.mrwid-excludeinclude')) {
								event.target.addEventListener('change',function(event) {
									if(event.target.value != 0) {
										event.target.closest('.mrwid-admin').querySelector(".mrwid-list").classList.add('including');
									} else {
										if(event.target.closest('.mrwid-admin').querySelector(".mrwid-list").classList.contains('including')) {
											event.target.closest('.mrwid-admin').querySelector(".mrwid-list").classList.remove('including');
										}
									}
								});
							} else if (event.target.matches('.mrwid-itemstitleinput')) {
								event.target.addEventListener('change',function(event) {
									if(event.target.value != 1) {
										mrSlideDown(event.target.closest('.mrwid-admin').querySelector('.mrwid-itemstitlemax'));
									} else {
										mrSlideUp(event.target.closest('.mrwid-admin').querySelector('.mrwid-itemstitlemax'));
									}
								});
							} else if (event.target.matches('.mrwid-itemdescinput')) {
								event.target.addEventListener('change',function(event) {
									if(event.target.value != 1) {
										mrSlideDown(event.target.closest('.mrwid-admin').querySelector('.mrwid-itemdescmax'));
									} else {
										mrSlideUp(event.target.closest('.mrwid-admin').querySelector('.mrwid-itemdescmax'));
									}
								});
							} else if (event.target.matches('.mrwid-bottomlinkinput')) {
								event.target.addEventListener('change',function(event) {
									if(event.target.value != 1) {
										mrSlideDown(event.target.closest('.mrwid-admin').querySelector('.mrwid-bottomlinktext'));
									} else {
										mrSlideUp(event.target.closest('.mrwid-admin').querySelector('.mrwid-bottomlinktext'));
									}
								});
							}
						});
						</script>
						</div>
			<?php
						$browsercheck = $_SERVER['HTTP_USER_AGENT'];
						if ( strpos($browsercheck, 'rv:11.0') !== false && strpos($browsercheck, 'Trident/7.0;')!== false || isset($browsercheck) && (strpos($browsercheck, 'MSIE') !== false)) {
							/*Polyfill for Vanilla Javascript on Internet Explorer*/
							wp_register_script( 'mrdev_polyfill', '//polyfill.io/v3/polyfill.min.js');
							wp_enqueue_script( 'mrdev_polyfill' );
							wp_script_add_data( 'mrdev_polyfill', 'crossorigin' , 'anonymous' );
						}
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['theme'] = ( !empty( $new_instance['theme'] ) ) ? strip_tags( $new_instance['theme'] ) : '';
		$instance['layout'] = ( !empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : '';
		if($instance['theme'] == $old_instance['theme'] || $old_instance['theme'] == null) {
			$layoutoptions = ( ! empty ( $new_instance['layoutoptions'] ) ) ? (array) $new_instance['layoutoptions'] : array();
			$instance['layoutoptions'] = array_map( 'sanitize_text_field', $layoutoptions );
		} else {
			$instance['layout'] = "";
			$layoutoptions = "";
		}
		$instance['perline'] = ( ! empty( $new_instance['perline'] ) ) ? strip_tags( $new_instance['perline'] ) : '';
		$instance['perpage'] = ( ! empty( $new_instance['perpage'] ) ) ? strip_tags( absint( $new_instance['perpage'] ) ) : '';
		$instance['pagetransition'] = ( ! empty( $new_instance['pagetransition'] ) ) ? strip_tags( $new_instance['pagetransition'] ) : '';
		$pagetoggles = ( ! empty ( $new_instance['pagetoggles'] ) ) ? (array) $new_instance['pagetoggles'] : array();
		$instance['pagetoggles'] = array_map( 'sanitize_text_field', $pagetoggles );
		$instance['pagetoggles'] = array_map( 'intval', $instance['pagetoggles'] );
			$instance['excludeinclude'] = ( !empty( $new_instance['excludeinclude'] ) ) ? strip_tags( absint( $new_instance['excludeinclude'] ) ) : '';
			$instance['orderby'] = ( !empty( $new_instance['orderby'] ) ) ? strip_tags( absint( $new_instance['orderby'] ) ) : '';
			$instance['order'] = ( !empty( $new_instance['order'] ) ) ? strip_tags( absint( $new_instance['order'] ) ) : '';
			$itemselect = ( ! empty ( $new_instance['itemselect'] ) ) ? (array) $new_instance['itemselect'] : array();
			$instance['itemselect'] = array_map( 'sanitize_text_field', $itemselect );
		$instance['maintitle'] = ( !empty( $new_instance['maintitle'] ) ) ? strip_tags( $new_instance['maintitle'] ) : '';
		$instance['itemimage'] = ( !empty( $new_instance['itemimage'] ) ) ? strip_tags( absint( $new_instance['itemimage'] ) ) : '';
		$instance['itemstitle'] = ( !empty( $new_instance['itemstitle'] ) ) ? strip_tags( absint( $new_instance['itemstitle'] ) ) : '';
		$instance['itemstitlemax'] = ( ! empty( $new_instance['itemstitlemax'] ) ) ? strip_tags( absint( $new_instance['itemstitlemax'] ) ) : '';
		$instance['itemdesc'] = ( !empty( $new_instance['itemdesc'] ) ) ? strip_tags( absint( $new_instance['itemdesc'] ) ) : '';
		$instance['itemdescmax'] = ( ! empty( $new_instance['itemdescmax'] ) ) ? strip_tags( absint( $new_instance['itemdescmax'] ) ) : '';
		$instance['itemlink'] = ( !empty( $new_instance['itemlink'] ) ) ? strip_tags( absint( $new_instance['itemlink'] ) ) : '';
		$instance['bottomlink'] = ( ! empty( $new_instance['bottomlink'] ) ) ? strip_tags( $new_instance['bottomlink'] ) : '';
		$itemoptions = ( ! empty ( $new_instance['itemoptions'] ) ) ? (array) $new_instance['itemoptions'] : array();
		$instance['itemoptions'] = array_map( 'sanitize_text_field', $itemoptions );
		$globallayoutoptions = ( ! empty ( $new_instance['globallayoutoptions'] ) ) ? (array) $new_instance['globallayoutoptions'] : array();
		$instance['globallayoutoptions'] = array_map( 'sanitize_text_field', $globallayoutoptions );
		$instance['lastactivedetails'] = ( ! empty( $new_instance['lastactivedetails'] ) ) ? strip_tags( $new_instance['lastactivedetails'] ) : '';
		return $instance;
	}
}
?>
