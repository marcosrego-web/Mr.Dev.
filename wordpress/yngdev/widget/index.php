<?php

defined('ABSPATH') or die;
/*------WIDGET FRONT------*/
function yngdev_load_widget() {
    register_widget( 'yng_developer' );
}
add_action( 'widgets_init', 'yngdev_load_widget' );
class yng_developer extends WP_Widget {
	function __construct() {
		parent::__construct(
		'yng_developer', 
		__('Yng.Dev.', 'yng_developer'), 
		array( 'description' => __( 'Displays categories and posts in a variety of layouts and customizable options.', 'yng_developer' ), ) 
		);
	}
	public function widget( $args, $instance ) {
		/*--- Get all instances into variables ---*/
		$title = apply_filters( 'widget_title', $instance['title'] );
		$theme = htmlspecialchars($instance['theme']);
		if($theme == 'none') {
			$layout = 'None';
			$layoutoptions = array();
		} else {
			$layout = htmlspecialchars($instance['layout']);
			if(!$layout && $theme == 'default') {
				$layout = 'Grid';
			} else if(!$layout) {
				$layout = 'None';
			}
			$layoutoptions = array_map("htmlspecialchars",$instance['layoutoptions']);
			if(!$layoutoptions) {
				$layoutoptions = array();
			}
		}
		$perline = intval($instance['perline']);
		$perpage = intval($instance['perpage']);
		$autoplay = intval($instance['autoplay']);
		$pagetransition = htmlspecialchars($instance['pagetransition']);
		$tabs = intval($instance['tabs']);
		$pagetoggles = array_filter($instance['pagetoggles'], 'is_numeric');
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
		$contenttypes = htmlspecialchars($instance['contenttypes']);
		$lastactivedetails = htmlspecialchars($instance['lastactivedetails']);
		echo $args['before_widget'];
			/* Add the main global script and style */
			wp_register_script( 'mrdev_scripts', plugin_dir_url( __DIR__ ).'assets/js/yngdev_v070.js');
			wp_enqueue_script( 'mrdev_scripts' );
			wp_enqueue_style( 'mrdev_css', plugin_dir_url( __DIR__ ).'assets/css/yngdev_v070.css');
				$content = '';
				/*
				Check if it's an official theme or a custom one.
				A css file with the theme's name is mandatory.
				If it's official it has the version number.
				*/
				if(!$theme) {
					include plugin_dir_path( __DIR__ ).'widget/themes/default/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', plugin_dir_url( __DIR__ ).'widget/themes/default/default_v070.css');
				} else if($theme == "default") {
					//Official Themes
					include plugin_dir_path( __DIR__ ).'widget/themes/'.$theme.'/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', plugin_dir_url( __DIR__ ).'widget/themes/'.$theme.'/'.$theme.'_v070.css');
				} else if($theme == "none") {
				} else {
					//Custom Themes
					include ABSPATH.'wp-content/themes/mrdev/'.$theme.'/index.php';
					wp_enqueue_style( 'mrdev_'.$theme.'_css', get_template_directory_uri().'/mrdev/'.$theme.'/'.$theme.'.css');
				}
				require trailingslashit( plugin_dir_path( __FILE__ )).'/items.php';
			echo __( $content, 'yng_developer' );
		echo $args['after_widget'];
	}
/*------WIDGET ADMIN------*/
	public function form( $instance ) {
		wp_enqueue_style( 'mrwid_admin', plugin_dir_url( __DIR__ ).'assets/css/admin_v070.css');
		?>
		<div class="mrwid-admin">
		<p class="mrwid-section"><a href="https://marcosrego.com/en/web-en/yngdev-en/" target="_blank"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDQ1LjEyOSA0NS4xMyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDUuMTI5IDQ1LjEzOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPGc+CgkJPGVsbGlwc2UgY3g9IjIyLjU2NSIgY3k9IjMxLjE5MiIgcng9IjIuNSIgcnk9IjEiIGZpbGw9IiMwMDAwMDAiLz4KCQk8cGF0aCBkPSJNNDIuNjksMjQuNDY5VjE1LjljMC0wLjU5Ny0wLjA1LTEuMTQzLTAuMTIyLTEuNjY1Yy0wLjAxMi0wLjA4MS0wLjAwNy0wLjE0Ni0wLjAyMi0wLjIzICAgIGMtMC4wMTUtMC4wNzEtMC4wMzktMC4xNDUtMC4wNTUtMC4yMTVjLTAuMTM3LTAuNzQyLTAuMzQ5LTEuNDEtMC42MzEtMi4wMDhjLTIuMTk3LTUuMTgtOC40MzQtMTAuMDctMTUuNjI4LTExLjQ2NyAgICBDMTguNTc3LTEuMTY5LDEwLjQyNiwyLjc5OSw4LjQ5OSw3LjcxOUM0Ljg0MSw4Ljc5NywyLjQ0LDExLjExOSwyLjQ0LDE1Ljl2OC41NzNjLTAuODU3LDEuMTIzLTEuMzc1LDIuNTQ0LTEuMzc1LDQuMDk0ICAgIGMwLDIuOTcxLDEuODg3LDUuNDgzLDQuNDY0LDYuMjkxQzguNzksNDEuMTAxLDE1LjMzNSw0NS4xMywyMi41OTYsNDUuMTNjNy4yNjksMCwxMy44MjEtNC4wMzksMTcuMDgxLTEwLjI5NSAgICBjMi41MzktMC44MzIsNC4zODktMy4zMjYsNC4zODktNi4yNjlDNDQuMDY1LDI3LjAxOCw0My41NDcsMjUuNTkyLDQyLjY5LDI0LjQ2OXogTTM3LjgyMywzMS4xMjljLTAuMjksMC0wLjU2NC0wLjA2Ni0wLjgxOC0wLjE4MyAgICBjLTIuMDM5LDUuOTE4LTcuNzExLDEwLjE4My0xNC40MDYsMTAuMTgzYy02LjcwMywwLTEyLjM4NC00LjI3Mi0xNC40MTUtMTAuMmMtMC4yNywwLjEyOS0wLjU2MywwLjItMC44NzQsMC4yICAgIGMtMS4yNDQsMC0yLjI0Mi0xLjE0Ni0yLjI0Mi0yLjU2M3MwLjk5OC0yLjU2MiwyLjI0Mi0yLjU2MmMwLjAyMiwwLDAuMDQ1LDAuMDA2LDAuMDY5LDAuMDA4YzAuMDE2LTIsMC40MzktNS4xNiwxLjE3OC03LjA2NyAgICBjMC45NzIsMS4zNTgsMi40NTgsMi42MjgsNC42NDUsMi42MjhjMCwwLDAsMCwwLjAwMiwwYzAuMTAyLDAsMC4yMDQtMC4wMDMsMC4zMDktMC4wMDljMC4yMDMtMC4wMTEsMC4zNzktMC4xNDQsMC40NDUtMC4zMzUgICAgYzAuMDE5LTAuMDUzLDEuODQ0LTUuMTQ2LDYuNjQ0LTUuNjM1YzAuMjAyLDAuOTkyLDAuNTA4LDMuNjktMS42NjUsNS4zMmMtMC4xNjEsMC4xMi0wLjIzNCwwLjMyNS0wLjE4NiwwLjUyMSAgICBzMC4yMTEsMC4zNDIsMC40MSwwLjM3MmMwLjA0MSwwLjAwNywxLjAzNSwwLjE1MywyLjUxNiwwLjE1M2MyLjUyNCwwLDcuMTEtMC40NTksMTAuMDk5LTMuNDYxICAgIGMwLjUwMSwwLjUwNCwxLjM4MiwxLjc0MywwLjk3LDMuOTczYy0wLjAzOSwwLjIwOCwwLjA1OSwwLjQxOCwwLjI0MSwwLjUyM2MwLjA3OCwwLjA0NSwwLjE2NCwwLjA2NSwwLjI1LDAuMDY1ICAgIGMwLjExNiwwLDAuMjMxLTAuMDQxLDAuMzI1LTAuMTJjMC4xMDctMC4wOTIsMS45NjQtMS42OTUsMy4yNzMtMy40N2MwLjYxNywxLjkyNCwwLjk3Miw0LjcxMSwwLjk4Myw2LjUyOSAgICBjMC4wMDMsMCwwLjAwNSwwLDAuMDA3LDBjMS4yNDQsMCwyLjI0MiwxLjE0NiwyLjI0MiwyLjU2MlMzOS4wNjcsMzEuMTI5LDM3LjgyMywzMS4xMjl6IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iMTUuNzczIiBjeT0iMjUuMDYxIiByPSIyLjI1IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iMjkuMzU3IiBjeT0iMjUuMDYxIiByPSIyLjI1IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" alt="Yng.Dev. Signature" title="Icon made by Freepik from flaticon.com" style="margin-bottom: -7px; margin-right: 3px;"><strong style="font-weight:700;">Yng.Dev.</strong></a>
		</p>
		<?php
						if ( isset( $instance[ 'title' ] ) ) {
							$title = $instance[ 'title' ];
						}
						else {
							$title = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'theme' ] ) ) {
							$theme = $instance[ 'theme' ];
						}
						else {
							$theme = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'layout' ] ) ) {
							$layout = $instance[ 'layout' ];
						}
						else {
							$layout = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'layoutoptions' ] ) ) {
							$layoutoptions = $instance[ 'layoutoptions' ];
						}
						else {
							$layoutoptions = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'orderby' ] ) ) {
							$orderby = $instance[ 'orderby' ];
						}
						else {
							$orderby = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'order' ] ) ) {
							$order = $instance[ 'order' ];
						}
						else {
							$order = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'excludeinclude' ] ) ) {
							$excludeinclude = $instance[ 'excludeinclude' ];
						}
						else {
							$excludeinclude = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemselect' ] ) ) {
							$itemselect = $instance[ 'itemselect' ];
						}
						else {
							$itemselect = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'maintitle' ] ) ) {
							$maintitle = $instance[ 'maintitle' ];
						}
						else {
							$maintitle = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemimage' ] ) ) {
							$itemimage = $instance[ 'itemimage' ];
						}
						else {
							$itemimage = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemstitle' ] ) ) {
							$itemstitle = $instance[ 'itemstitle' ];
						}
						else {
							$itemstitle = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemstitlemax' ] ) ) {
							$itemstitlemax = $instance[ 'itemstitlemax' ];
						}
						else {
							$itemstitlemax = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemdesc' ] ) ) {
							$itemdesc = $instance[ 'itemdesc' ];
						}
						else {
							$itemdesc = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemdescmax' ] ) ) {
							$itemdescmax = $instance[ 'itemdescmax' ];
						}
						else {
							$itemdescmax = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemlink' ] ) ) {
							$itemlink = $instance[ 'itemlink' ];
						}
						else {
							$itemlink = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'bottomlink' ] ) ) {
							$bottomlink = $instance[ 'bottomlink' ];
						}
						else {
							$bottomlink = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'perline' ] ) ) {
							$perline = $instance[ 'perline' ];
						}
						else {
							$perline = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'perpage' ] ) ) {
							$perpage = $instance[ 'perpage' ];
						}
						else {
							$perpage = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'autoplay' ] ) ) {
							$autoplay = $instance[ 'autoplay' ];
						}
						else {
							$autoplay = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'pagetransition' ] ) ) {
							$pagetransition = $instance[ 'pagetransition' ];
						}
						else {
							$pagetransition = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'pagetoggles' ] ) ) {
							$pagetoggles = $instance[ 'pagetoggles' ];
						}
						else {
							$pagetoggles = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'tabs' ] ) ) {
							$tabs = $instance[ 'tabs' ];
						}
						else {
							$tabs = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemoptions' ] ) ) {
							$itemoptions = $instance[ 'itemoptions' ];
						}
						else {
							$itemoptions = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'globallayoutoptions' ] ) ) {
							$globallayoutoptions = $instance[ 'globallayoutoptions' ];
						}
						else {
							$globallayoutoptions = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'contenttypes' ] ) ) {
							$contenttypes = $instance[ 'contenttypes' ];
						}
						else {
							$contenttypes = __( '', 'mr_developer' );
						}
						if(!$contenttypes) { 
							$contenttypes = 'category';
						}
						if ( isset( $instance[ 'lastactivedetails' ] ) ) {
							$lastactivedetails = $instance[ 'lastactivedetails' ];
						}
						else {
							$lastactivedetails = __( '', 'yng_developer' );
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
						<summary class="mrwid-section">Content</summary>
						<p>
						<label  for="<?php echo $this->get_field_id( 'contenttypes' ); ?>"><?php _e( 'Type:' ); ?></label><br>
						<select class="widefat mrwid-contenttypes" id="<?php echo $this->get_field_id('contenttypes'); ?>" name="<?php echo $this->get_field_name('contenttypes'); ?>">
							<?php
								echo '<option value="category"', $contenttypes == 'category' ? ' selected="selected"' : '', '>Categories</option>
								<option value="post"', $contenttypes == 'post' ? ' selected="selected"' : '', '>Posts</option>';
							?>
						</select><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Sort by:' ); ?></label><br>
								<select id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat mrwid-halfsize" name="<?php echo $this->get_field_name('orderby'); ?>">
									<?php
										echo '<option value="0"', $orderby == 0 ? ' selected="selected"' : '', '>Creation</option>
										<option value="1"', $orderby == 1 ? ' selected="selected"' : '', '>Title</option>
										<option value="2"', $orderby == 2 ? ' selected="selected"' : '', '>Parent</option>
										<option value="3"', $orderby == 3 ? ' selected="selected"' : '', '>Post count</option>
										<option value="4"', $orderby == 4 ? ' selected="selected"' : '', '>Slug</option>';
									?>
								</select>
								<select id="<?php echo $this->get_field_id('order'); ?>" class="widefat mrwid-halfsize" name="<?php echo $this->get_field_name('order'); ?>">
									<?php
										echo '
										<option value="0" id="Descending"', $orderby == 0 ? ' selected="selected"' : '', '>Descending</option>
										<option value="1" id="Ascending"', $orderby == 1 ? ' selected="selected"' : '', '>Ascending</option>
										';
									?>
						</select><br>
						</p>
						<p><?php _e( 'Items:' ); ?></p>
						<div class="mrwid-itemscontainer">
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
						</div>
						<p class="mrwid-saveexcludeinclude" style="display: none;">
						<?php _e( 'Please save the changes when you want to see the items considered by your selected options.' ); ?>
						</p>
						</p>
						</details>
						<details class="appearanceDetails" <?php if(esc_attr( $lastactivedetails ) == 'appearanceDetails') { echo 'open="open"'; } ?>>
						<summary class="mrwid-section">Appearance</summary>
						<p>
						Theme:<br>
								<select  class="widefat mrwid-themes" id="<?php echo $this->get_field_id('theme'); ?>" name="<?php echo $this->get_field_name('theme'); ?>">
									<?php
										$options = array('default','none');
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
									include trailingslashit( plugin_dir_path( __DIR__ )).'widget/themes/default/index.php';
								} else {
									if($theme == "default") {
										include trailingslashit( plugin_dir_path( __DIR__ )).'widget/themes/'.$theme.'/index.php';
									} else if($theme == "none") {
									} else {
										include ABSPATH.'wp-content/themes/mrdev/'.$theme.'/index.php';
									}
								}
								?>
								</div>
								<div class="mrwid-savetheme" style="display: none;">
								Theme options will appear if available after saving.
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
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="0" <?php if( is_array( $pagetoggles ) && in_array( 0, $pagetoggles ) || !is_array( $pagetoggles ) && $pagetoggles == 0 || !$pagetoggles ) { echo 'checked="checked"'; } ?> /> <?php _e( 'Arrows' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="1" <?php checked( ( is_array($pagetoggles) AND in_array( 1, $pagetoggles ) ) ? 1 : '', 1 ); ?> /> <?php _e( 'Select' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="2" <?php checked( ( is_array($pagetoggles) AND in_array( 2, $pagetoggles ) ) ? 2 : '', 2 ); ?> /> <?php _e( 'Radio' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="5" <?php checked( ( is_array($pagetoggles) AND in_array( 5, $pagetoggles ) ) ? 5 : '', 5 ); ?> /> <?php _e( 'Keyboard' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="3" <?php checked( ( is_array($pagetoggles) AND in_array( 3, $pagetoggles ) ) ? 3 : '', 3 ); ?> /> <?php _e( 'Below' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="4" <?php checked( ( is_array($pagetoggles) AND in_array( 4, $pagetoggles ) ) ? 4 : '', 4 ); ?> /> <?php _e( 'Scroll' ); ?></label><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'tabs' ); ?>"><?php _e( 'Tabs:' ); ?></label><br>
						<select <?php if($pagination_access == 'Denied') { echo 'disabled'; } ?> class="widefat" id="<?php echo $this->get_field_id('tabs'); ?>" name="<?php echo $this->get_field_name('tabs'); ?>">
									<?php
										echo '<option value="0" id="notabs"', $tabs == 0 ? ' selected="selected"' : '', '>None</option>
										<option value="1" id="itemstabs"', $tabs == 1 ? ' selected="selected"' : '', '>Items</option>
										<option value="2" id="parenttabs"', $tabs == 2 ? ' selected="selected"' : '', '>Parent items</option>';
									?>
								</select><br>
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
						<p>
						<label  for="<?php echo $this->get_field_id( 'autoplay' ); ?>"><?php _e( 'Autoplay:' ); ?></label><br>
						<input <?php if($pagination_access == 'Denied') { echo 'disabled'; } ?> class="mrwid-pagination-input mrwid-autoplay-input" type="number" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" type="text" placeholder="∞" title="Choose how many seconds the autoplay should take to change page. Leave empty or choose '0' to turn off autoplay." value="<?php if(esc_attr( $autoplay ) == "" || esc_attr( $autoplay ) <= 0) { } else { echo esc_attr( $autoplay ); } ?>" /> seconds
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
											<option value="1"', $itemimage == 1 ? ' selected="selected"' : '', '>Item image</option>
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
										echo '<option value="0"', $itemstitle == 0 ? ' selected="selected"' : '', '>Linked item title</option>
										<option value="2"', $itemstitle == 2 ? ' selected="selected"' : '', '>Item title</option>
										<option value="1"', $itemstitle == 1 ? ' selected="selected"' : '', '>No title</option>';
									?>
								</select><br>
								<span class="mrwid-itemstitlemax" <?php if($itemstitle && $itemstitle == 1) { echo 'style="display: none;"'; } ?>>
								<input <?php if($display_access == 'Denied') { echo 'disabled'; } ?> class="widefat mrwid-pagination-input" id="<?php echo $this->get_field_id( 'itemstitlemax' ); ?>" name="<?php echo $this->get_field_name( 'itemstitlemax' ); ?>" placeholder="∞" type="number" value="<?php if(!esc_attr( $itemstitlemax )) {  } else { echo esc_attr( $itemstitlemax ); } ?>" /> max. characters
								</span>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemdesc' ); ?>"><?php _e( 'Descriptions:' ); ?></label><br>
						<select  class="widefat mrwid-itemdescinput" id="<?php echo $this->get_field_id('itemdesc'); ?>" name="<?php echo $this->get_field_name('itemdesc'); ?>">
									<?php
										echo '<option value="0"', $itemdesc == 0 ? ' selected="selected"' : '', '>Item description</option>
										<option value="4"', $itemdesc == 4 ? ' selected="selected"' : '', '>Item excerpt</option>
										<option value="2"', $itemdesc == 2 ? ' selected="selected"' : '', '>Item intro text</option>
										<option value="3"', $itemdesc == 3 ? ' selected="selected"' : '', '>Item full text</option>
										<option value="1"', $itemdesc == 1 ? ' selected="selected"' : '', '>No description</option>';
									?>
								</select><br>
								<span class="mrwid-itemdescmax" <?php if($itemdesc && $itemdesc == 1) { echo 'style="display: none;"'; } ?> >
								<input <?php if($display_access == 'Denied') { echo 'disabled'; } ?> class="widefat mrwid-pagination-input" id="<?php echo $this->get_field_id( 'itemdescmax' ); ?>" name="<?php echo $this->get_field_name( 'itemdescmax' ); ?>" placeholder="∞" type="number" value="<?php if(!esc_attr( $itemdescmax )) {  } else { echo esc_attr( $itemdescmax ); } ?>" /> max. characters
								</span>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemlink' ); ?>"><?php _e( 'Links:' ); ?></label><br>
						<select  class="widefat mrwid-bottomlinkinput" id="<?php echo $this->get_field_id('itemlink'); ?>" name="<?php echo $this->get_field_name('itemlink'); ?>">
									<?php
										echo '<option value="0"', $itemlink == 0 ? ' selected="selected"' : '', '>Item link</option>
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
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="onlyactives" <?php checked( ( is_array($globallayoutoptions ) AND in_array( "onlyactives", $globallayoutoptions ) ) ? "onlyactives" : '', "onlyactives" ); ?> /> <?php _e( 'Only show actives' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="hideinactives" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "hideinactives", $globallayoutoptions ) ) ? "hideinactives" : '', "hideinactives" ); ?> /> <?php _e( 'On active hide inactives' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="keepactive" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "keepactive", $globallayoutoptions ) ) ? "keepactive" : '', "keepactive" ); ?> /> <?php _e( 'Keep other actives opened' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="subcatactive" <?php checked( ( is_array($globallayoutoptions ) AND in_array( "subcatactive", $globallayoutoptions ) ) ? "subcatactive" : '', "subcatactive" ); ?> /> <?php _e( 'Only show subitems of active' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="contentpagination" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "contentpagination", $globallayoutoptions ) ) ? "contentpagination" : '', "contentpagination" ); ?> /> <?php _e( 'Pagination inside content' ); ?></label><br>
									<label ><input  type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="donotinactive" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "donotinactive", $globallayoutoptions ) ) ? "donotinactive" : '', "donotinactive" ); ?> /> <?php _e( 'Do not inactive on click' ); ?></label><br>
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
							<?php if (!is_plugin_active('mrdev/mrdev.php') ) { ?>
							<summary class="mrwid-section"><strong>DO YOU NEED MORE FEATURES?</strong></summary>
							<p>
							If you need more features then you need <strong>Mr.Dev.</strong><br>:</p>
							<ol>
							<li>Insert widgets inside the content on posts/pages/categories using <strong>blocks, classic editor button or shortcodes</strong>.</li>
							<li><strong>More content types</strong> such as pages, tags and other Wordpress registered terms and post types.</li>
							<li>Choose <strong>items' parents such as parent categories, categories and tags</strong> to only display their childs.</li>
							<li>Manually <strong>reorder</strong>.</li>
							<li><strong>Pin</strong> to choose the ones starting active.</li>
							<li><strong>Auto exclude</strong> Subcategories, Categories with no posts, same link, different link and more.</li>
							<li>More image options such as <strong>thumbnails and parallax</strong>.</li>
							<li>Choose a <strong>fallback image</strong>.</li>
							<li><strong>Hide widget sections</strong> to specific users or roles.</li>
							<li>Other <strong>Advanced</strong> options such as preload pages, choose the titles tag (h2, h3, h4, p, etc), do not load polyfill on IE and add custom classes to the bottom link.</li>
							</ol>
							<p>And more...</p>
							<p><a class="button button-primary" href="https://marcosrego.com/en/web-en/mrdev-en/" target="_blank">Get Mr.Dev.</a></p>
							<?php } else {  ?>
							<summary class="mrwid-section"><strong>LET THE YOUNG GROW TO A MISTER!</strong></summary>
							<p>Use Mr.Dev. widgets to manually recreate your current Yng.Dev. widgets and get all the features. After that you can safely delete the plugin Yng.Dev. </p>
							<?php } ?> 
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
							}else if (event.target.matches('select.mrwid-contenttypes')) {
								event.target.addEventListener('change',function(event) {
									mrSlideUp(event.target.closest('.mrwid-admin').querySelector(".mrwid-itemscontainer"));
									mrSlideDown(event.target.closest('.mrwid-admin').querySelector(".mrwid-saveexcludeinclude"));
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
						/*
						wp_register_script( 'mrdev_polyfill', '//polyfill.io/v3/polyfill.min.js');
						wp_enqueue_script( 'mrdev_polyfill' );
						wp_script_add_data( 'mrdev_polyfill', 'crossorigin' , 'anonymous' );
						*/
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['theme'] = ( !empty( $new_instance['theme'] ) ) ? strip_tags( $new_instance['theme'] ) : '';
		if($instance['theme'] == $old_instance['theme'] || !$old_instance['theme']) {
			$instance['layout'] = ( !empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : '';
			$layoutoptions = ( ! empty ( $new_instance['layoutoptions'] ) ) ? (array) $new_instance['layoutoptions'] : array();
			$instance['layoutoptions'] = array_map( 'sanitize_text_field', $layoutoptions );
		} else {
			if($instance['theme'] == 'default') {
				$instance['layout'] = "Grid";
			} else if($instance['theme'] == 'none') {
				$instance['layout'] = "none";
			} else {
				$instance['layout'] = "";
			}
			$layoutoptions = array();
		}
		$instance['perline'] = ( ! empty( $new_instance['perline'] ) ) ? strip_tags( $new_instance['perline'] ) : '';
		$instance['perpage'] = ( ! empty( $new_instance['perpage'] ) ) ? strip_tags( absint( $new_instance['perpage'] ) ) : '';
		$instance['autoplay'] = ( ! empty( $new_instance['autoplay'] ) ) ? strip_tags( absint( $new_instance['autoplay'] ) ) : '';
		$instance['tabs'] = ( ! empty( $new_instance['tabs'] ) ) ? strip_tags( $new_instance['tabs'] ) : '';
		$instance['pagetransition'] = ( ! empty( $new_instance['pagetransition'] ) ) ? strip_tags( $new_instance['pagetransition'] ) : '';
		$pagetoggles = ( ! empty ( $new_instance['pagetoggles'] ) ) ? (array) $new_instance['pagetoggles'] : array();
		$instance['pagetoggles'] = array_map( 'sanitize_text_field', $pagetoggles );
		$instance['pagetoggles'] = array_map( 'intval', $instance['pagetoggles'] );
			$instance['contenttypes'] = ( !empty( $new_instance['contenttypes'] ) ) ? strip_tags( $new_instance['contenttypes'] ) : '';
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