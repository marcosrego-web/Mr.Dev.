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
		array( 'description' => __( 'Powered by Mr.Cat. | Displays categories in a variety of layouts and customizable options.', 'mr_categories' ), ) 
		);
	}
	public function widget( $args, $instance ) {
		/*--- Get all instances into variables ---*/
		$title = apply_filters( 'widget_title', $instance['title'] );
		$theme = $instance['theme'];
		$layout = $instance['layout'];
		$perline = $instance['perline'];
		$perpage = $instance['perpage'];
		$pagetransition = $instance['pagetransition'];
		$pagetoggles = $instance['pagetoggles'];
		if($instance['layoutoptions']) {
			$layoutoptions = $instance['layoutoptions'];
		}
		$orderby = $instance['orderby'];
		$order = $instance['order'];
		$excludeinclude = $instance['excludeinclude'];
		$catexclude = $instance['catexclude'];
		$bottomlink = $instance['bottomlink'];
		$maintitle = $instance['maintitle'];
		$cattitle = $instance['cattitle'];
		$catdesc = $instance['catdesc'];
		$catlink = $instance['catlink'];
		$catoptions = $instance['catoptions'];
		$lastactivedetails = $instance['lastactivedetails'];
		echo $args['before_widget'];
			/* Add the main global script and style */
			wp_register_script( 'mrcat_scripts', plugin_dir_url( __DIR__ ).'assets/js/mrcat_v042.js', array('jquery'));
			wp_enqueue_script( 'mrcat_scripts' );
			wp_enqueue_style( 'mrcat_css', plugin_dir_url( __DIR__ ).'assets/css/mrcat_v042.css');
			/* A heightfix for css 'vh' on mobile browsers address bar.
			Detect IE because this fix breaks on that browser. */
			if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { } else { 
				wp_register_script( 'mrcat_heightfix', plugin_dir_url( __DIR__ ).'assets/js/heightfix.js', array('jquery')); wp_enqueue_script( 'mrcat_heightfix' ); 
			}
				if ( ! empty( $catexclude ) ) {
					$catexclude = ! empty( $instance['catexclude'] ) ? $instance['catexclude'] : array();
				} else {
					$catexclude = array();
				}
				if ( ! empty( $layoutoptions ) ) {
					$layoutoptions = ! empty( $instance['layoutoptions'] ) ? $instance['layoutoptions'] : array();
				} else {
					$layoutoptions = array();
				}
				if ( ! empty( $catoptions ) ) {
					$catoptions = ! empty( $instance['catoptions'] ) ? $instance['catoptions'] : array();
				} else {
					$catoptions = array();
				}
				if ( ! empty( $pagetoggles ) ) {
					$pagetoggles = ! empty( $instance['pagetoggles'] ) ? $instance['pagetoggles'] : array();
				} else {
					$pagetoggles = array();
				}
				$content = '';
				/*
				Check if it's an official theme or a custom one.
				A css file with the theme's name is mandatory.
				If it's official it has the version number.
				*/
				if(!$theme) {
					require_once plugin_dir_path( __DIR__ ).'themes/Default/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', plugin_dir_url( __DIR__ ).'themes/Default/Default_v042.css');
				} else if($theme == "Default") {
					//Official Themes
					require_once plugin_dir_path( __DIR__ ).'themes/'.$theme.'/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', plugin_dir_url( __DIR__ ).'themes/'.$theme.'/'.$theme.'_v042.css');
				} else {
					//Custom Themes
					include '/wp-content/themes/mrdev/'.$theme.'/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', '/wp-content/themes/mrdev/'.$theme.'/'.$theme.'.css');
				}
				include trailingslashit( plugin_dir_path( __FILE__ )).'/items.php';
			echo __( $content, 'mr_categories' );
		echo $args['after_widget'];
	}
/*------WIDGET ADMIN------*/
	public function form( $instance ) {
		wp_enqueue_style( 'mrwid_admin', plugin_dir_url( __DIR__ ).'assets/css/admin_v042.css');
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
						if ( isset( $instance[ 'catexclude' ] ) ) {
							$catexclude = $instance[ 'catexclude' ];
						}
						else {
							$catexclude = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'maintitle' ] ) ) {
							$maintitle = $instance[ 'maintitle' ];
						}
						else {
							$maintitle = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'cattitle' ] ) ) {
							$cattitle = $instance[ 'cattitle' ];
						}
						else {
							$cattitle = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'catdesc' ] ) ) {
							$catdesc = $instance[ 'catdesc' ];
						}
						else {
							$catdesc = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'catlink' ] ) ) {
							$catlink = $instance[ 'catlink' ];
						}
						else {
							$catlink = __( '', 'mr_categories' );
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
						if ( isset( $instance[ 'catoptions' ] ) ) {
							$catoptions = $instance[ 'catoptions' ];
						}
						else {
							$catoptions = __( '', 'mr_categories' );
						}
						if ( isset( $instance[ 'lastactivedetails' ] ) ) {
							$lastactivedetails = $instance[ 'lastactivedetails' ];
						}
						else {
							$lastactivedetails = __( '', 'mr_categories' );
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
										echo '
										<option value="0" id="Parent"', $orderby == 0 ? ' selected="selected"' : '', '>Parent</option>
										<option value="1" id="Title"', $orderby == 1 ? ' selected="selected"' : '', '>Title</option>
										<option value="2" id="Creation"', $orderby == 2 ? ' selected="selected"' : '', '>Creation</option>
										<option value="3" id="Article count"', $orderby == 2 ? ' selected="selected"' : '', '>Article count</option>
										<option value="4" id="Slug"', $orderby == 2 ? ' selected="selected"' : '', '>Slug</option>
										';
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
						<select class="" id="<?php echo $this->get_field_id('excludeinclude'); ?>" name="<?php echo $this->get_field_name('excludeinclude'); ?>">
									<?php
										$options = array( 'Exclude','Include');
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $excludeinclude == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
						</select>:
						<div class="mrwid-list <?php if($excludeinclude == 'Include') { echo 'including '; } ?>" >
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
										$options = array('Default');
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $theme == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
										$customOptions = array_map('basename', glob(get_template_directory().'/mrdev/themes/*' , GLOB_ONLYDIR));
										foreach ( $customOptions as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $theme == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
								</select>
								<div class="mrwid-themeoptions">
								<?php
								if(!$theme) {
									include trailingslashit( plugin_dir_path( __DIR__ )).'themes/Default/index.php';
								} else {
									if($theme == "Default") {
										include trailingslashit( plugin_dir_path( __DIR__ )).'themes/'.$theme.'/index.php';
									} else {
										include trailingslashit( 'ABSPATH').'wp-content/themes/mrdev/themes/'.$theme.'/index.php';
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
										$options = array( '∞',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $perline == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
						</select> per line<br>
						<input class="mrwid-pagination-input mrwid-pages-input" type="number" id="<?php echo $this->get_field_id( 'perpage' ); ?>" name="<?php echo $this->get_field_name( 'perpage' ); ?>" type="text" placeholder="∞" title="Choose the number of items per page" value="<?php if(esc_attr( $perpage ) == "" || esc_attr( $perpage ) <= 0) { } else { echo esc_attr( $perpage ); } ?>" /> per page
						</p>
						<p>
									<label  for="<?php echo $this->get_field_id( 'pagetoggles' ); ?>"><?php _e( 'Toggles:' ); ?></label> <br>
									<label ><input <?php if($pagination_access == 'Denied') { echo 'disabled'; } ?> type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="pageselect" <?php checked( ( is_array($pagetoggles) AND in_array( "pageselect", $pagetoggles ) ) ? "pageselect" : '', "pageselect" ); ?>  /> <?php _e( 'Select' ); ?></label><br>
									<label ><input <?php if($pagination_access == 'Denied') { echo 'disabled'; } ?> type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="arrows" <?php if( is_array( $pagetoggles ) && in_array( 'arrows', $pagetoggles ) || !is_array( $pagetoggles ) || is_array( $pagetoggles ) && !in_array( 'arrows', $pagetoggles ) && !in_array( 'pageselect', $pagetoggles ) && !in_array( 'radio', $pagetoggles ) && !in_array( 'below', $pagetoggles) && !in_array( 'scroll', $pagetoggles) ) { echo 'checked="checked"'; } ?> /> <?php _e( 'Arrows' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="radio" <?php checked( ( is_array($pagetoggles) AND in_array( "radio", $pagetoggles ) ) ? "radio" : '', "radio" ); ?> /> <?php _e( 'Radio' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="below" <?php checked( ( is_array($pagetoggles) AND in_array( "below", $pagetoggles ) ) ? "below" : '', "below" ); ?> /> <?php _e( 'Below' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="scroll" <?php checked( ( is_array($pagetoggles) AND in_array( "scroll", $pagetoggles ) ) ? "scroll" : '', "scroll" ); ?> /> <?php _e( 'Scroll' ); ?></label><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'pagetransition' ); ?>"><?php _e( 'Transition:' ); ?></label><br>
						<select  class="widefat" id="<?php echo $this->get_field_id('pagetransition'); ?>" name="<?php echo $this->get_field_name('pagetransition'); ?>">
									<?php
										$options = array( 'Fade','Slide');
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $pagetransition == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
								</select><br>
						</p>
						</details>
						<details class="optionsDetails" <?php if(esc_attr( $lastactivedetails ) == 'optionsDetails') { echo 'open="open"'; }  ?>>
						<summary class="mrwid-section">Options</summary>
						<p>
						<label  for="<?php echo $this->get_field_id( 'catdisplay' ); ?>"><?php _e( 'Display options:' ); ?></label><br>
						<select  class="widefat" id="<?php echo $this->get_field_id('maintitle'); ?>" name="<?php echo $this->get_field_name('maintitle'); ?>" title="Main title">
							<?php
							echo '<option value="0" id="widgettitle"', $maintitle == 0 ? ' selected="selected"' : '', '>Widget title</option>';
							echo '<option value="3" id="themeandlayouttitle"', $maintitle == 3 ? ' selected="selected"' : '', '>Theme and layout title</option>';
							echo '<option value="4" id="themetitle"', $maintitle == 4 ? ' selected="selected"' : '', '>Theme title</option>';
							echo '<option value="5" id="layouttitle"', $maintitle == 5 ? ' selected="selected"' : '', '>Layout title</option>';
							echo '<option value="6" id="nomaintitle"', $maintitle == 6 ? ' selected="selected"' : '', '>No main title</option>';
							?>
						</select><br>
						<select  class="widefat" id="<?php echo $this->get_field_id('cattitle'); ?>" name="<?php echo $this->get_field_name('cattitle'); ?>" title="Titles">
									<?php
										$options = array( 'Linked category title','Category title','No title');
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $cattitle == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
								</select><br>
						<select  class="widefat" id="<?php echo $this->get_field_id('catdesc'); ?>" name="<?php echo $this->get_field_name('catdesc'); ?>" title="Descriptions">
									<?php
										$options = array( 'Category description','No description');
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $catdesc == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
								</select><br>
						<select  class="widefat" id="<?php echo $this->get_field_id('catlink'); ?>" name="<?php echo $this->get_field_name('catlink'); ?>" title="Bottom links">
									<?php
										$options = array( 'Category link','No bottom link');
										foreach ( $options as $option ) {
											echo '<option value="' . $option . '" id="' . $option . '"', $catlink == $option ? ' selected="selected"' : '', '>' . $option . '</option>';
										}
									?>
						</select><br>
						<?php if($catlink && $catlink != "No bottom link") { ?>
						<input  class="widefat" id="<?php echo $this->get_field_id( 'bottomlink' ); ?>" name="<?php echo $this->get_field_name( 'bottomlink' ); ?>" type="text" placeholder="Bottom link text" title="Bottom link text" value="<?php if(esc_attr( $bottomlink ) == "") { echo "Know more..."; } else { echo esc_attr( $bottomlink ); } ?>" />
						<?php } ?>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'catoptions' ); ?>"><?php _e( 'Other options:' ); ?></label> <br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'catoptions' ) ); ?>[]" value="artcount" <?php checked( ( is_array($catoptions) AND in_array( "artcount", $catoptions ) ) ? "artcount" : '', "artcount" ); ?> /> <?php _e( 'Show number of articles' ); ?></label><br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'catoptions' ) ); ?>[]" value="hover" <?php checked( ( is_array( $catoptions ) AND in_array( "hover", $catoptions ) ) ? "hover" : '', "hover" ); ?> /> <?php _e( 'Active on mouseover' ); ?></label><br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'catoptions' ) ); ?>[]" value="autoscroll" <?php checked( ( is_array( $catoptions ) AND in_array( "autoscroll", $catoptions ) ) ? "autoscroll" : '', "autoscroll" ); ?> /> <?php _e( 'Auto scroll to active' ); ?></label><br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="keepopen" <?php checked( ( is_array( $layoutoptions ) AND in_array( "keepopen", $layoutoptions ) ) ? "keepopen" : '', "keepopen" ); ?> /> <?php _e( 'Keep other actives opened' ); ?></label><br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="donotclose" <?php checked( ( is_array( $layoutoptions ) AND in_array( "donotclose", $layoutoptions ) ) ? "donotclose" : '', "donotclose" ); ?> /> <?php _e( 'Do not inactive on click' ); ?></label><br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'layoutoptions' ) ); ?>[]" value="subcatactive" <?php checked( ( is_array($layoutoptions ) AND in_array( "subcatactive", $layoutoptions ) ) ? "subcatactive" : '', "subcatactive" ); ?> /> <?php _e( 'Only show subcategories of active' ); ?></label><br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'catoptions' ) ); ?>[]" value="url" <?php checked( ( is_array( $catoptions ) AND in_array( "url", $catoptions ) ) ? "url" : '', "url" ); ?> /> <?php _e( 'Change URL on active' ); ?></label><br>
									<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'catoptions' ) ); ?>[]" value="remember" <?php checked( ( is_array( $catoptions ) AND in_array( "remember", $catoptions ) ) ? "remember" : '', "remember" ); ?> /> <?php _e( 'Remember last active <small>(<i>uses cookies</i>)</small>' ); ?></label><br>
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
						<li><strong>Hide widget sections</strong> to specific users or roles.</li>
						<li>Other <strong>Advanced</strong> options such as choosing the titles tag (h2, h3, h4, p, etc), add custom classes to the bottom link.</li>
						<li>And more...</li>
						</ol>
						<p><a class="button button-primary" href="https://marcosrego.com/en/web-en/mrdev-en/" target="_blank">Get Mr.Dev.</a></p>
					</details>
						<?php
						/* The following script is inline to avoid problems running after saving the widget */
						?>
						<script>
						jQuery(document).ready(function( $ ) {
							jQuery('.mrwid-admin details').on('click',function() {
								jQuery(this).parent().find('input.lastactivedetails').val(jQuery(this).attr('class'));
							});
							jQuery('.mrwid-themes').change(function() {
								jQuery(this).parent().parent().find('.mrwid-themeoptions').slideUp();
								jQuery(this).parent().parent().find('.mrwid-savetheme').slideDown();
							});
						});
						</script>
						</div>
			<?php
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
		$instance['perpage'] = ( ! empty( $new_instance['perpage'] ) ) ? strip_tags( $new_instance['perpage'] ) : '';
		$instance['pagetransition'] = ( ! empty( $new_instance['pagetransition'] ) ) ? strip_tags( $new_instance['pagetransition'] ) : '';
		$pagetoggles = ( ! empty ( $new_instance['pagetoggles'] ) ) ? (array) $new_instance['pagetoggles'] : array();
		$instance['pagetoggles'] = array_map( 'sanitize_text_field', $pagetoggles );
			$instance['excludeinclude'] = ( !empty( $new_instance['excludeinclude'] ) ) ? strip_tags( $new_instance['excludeinclude'] ) : '';
			$instance['orderby'] = ( !empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';
			$instance['order'] = ( !empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';
			$catexclude = ( ! empty ( $new_instance['catexclude'] ) ) ? (array) $new_instance['catexclude'] : array();
			$instance['catexclude'] = array_map( 'sanitize_text_field', $catexclude );
		$instance['maintitle'] = ( !empty( $new_instance['maintitle'] ) ) ? strip_tags( $new_instance['maintitle'] ) : '';
		$instance['cattitle'] = ( !empty( $new_instance['cattitle'] ) ) ? strip_tags( $new_instance['cattitle'] ) : '';
		$instance['catdesc'] = ( !empty( $new_instance['catdesc'] ) ) ? strip_tags( $new_instance['catdesc'] ) : '';
		$instance['catlink'] = ( !empty( $new_instance['catlink'] ) ) ? strip_tags( $new_instance['catlink'] ) : '';
		$instance['bottomlink'] = ( ! empty( $new_instance['bottomlink'] ) ) ? strip_tags( $new_instance['bottomlink'] ) : '';
		$catoptions = ( ! empty ( $new_instance['catoptions'] ) ) ? (array) $new_instance['catoptions'] : array();
		$instance['catoptions'] = array_map( 'sanitize_text_field', $catoptions );
		$instance['lastactivedetails'] = ( ! empty( $new_instance['lastactivedetails'] ) ) ? strip_tags( $new_instance['lastactivedetails'] ) : '';
		return $instance;
	}
}
?>
