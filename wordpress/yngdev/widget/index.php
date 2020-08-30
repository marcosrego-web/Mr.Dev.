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
		array( 'description' => __( 'Displays categories or posts in a variety of layouts and customizable options.', 'yng_developer' ), ) 
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
			$layoutoptions = array_map("htmlspecialchars",$instance['layoutoptions']);
		}
		$perline = intval($instance['perline']);
		$perpage = intval($instance['perpage']);
		$autoplay = intval($instance['autoplay']);
		$pagetransition = htmlspecialchars($instance['pagetransition']);
		$tabs = intval($instance['tabs']);
		$tabsposition = htmlspecialchars($instance['tabsposition']);
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
			wp_register_script( 'yngdev_utils', plugin_dir_url( __DIR__ ).'assets/js/utils.js',array(),'0.9.20');
			wp_enqueue_script( 'yngdev_utils' );
			wp_register_script( 'yngdev_main', plugin_dir_url( __DIR__ ).'assets/js/main.js', array('yngdev_utils'),'0.9.20');
			wp_enqueue_script( 'yngdev_main' );
			wp_enqueue_style( 'yngdev_utils', plugin_dir_url( __DIR__ ).'assets/css/utils.css',array(),'0.9.20');
			wp_enqueue_style( 'yngdev_main', plugin_dir_url( __DIR__ ).'assets/css/main.css', array('yngdev_utils'),'0.9.20');
				$content = '';
				/*
				Check if it's an official theme or a custom one.
				A css file with the theme's name is mandatory.
				If it's official it has the version number.
				*/
				if($theme == "default") {
					include plugin_dir_path( __DIR__ ).'widget/themes/'.$theme.'/index.php';
					wp_enqueue_style( 'yngdev_'.$theme.'_css', plugin_dir_url( __DIR__ ).'widget/themes/'.$theme.'/'.$theme.'.css',array('yngdev_main'),'0.9.20');
				} else if($theme == "none") {
				} else { //Custom Themes
					include ABSPATH.'wp-content/themes/mrdev/widget/themes/'.$theme.'/index.php';
					wp_enqueue_style( 'yngdev_'.$theme.'_css', get_template_directory_uri().'/mrdev/widget/themes/'.$theme.'/'.$theme.'.css',array('yngdev_main'),'0.9.20');
				}
				require trailingslashit( plugin_dir_path( __FILE__ )).'/items.php';
			echo __( $content, 'yng_developer' );
		echo $args['after_widget'];
	}
/*------WIDGET ADMIN------*/
	public function form( $instance ) {
		wp_enqueue_style( 'mrwid_admin', plugin_dir_url( __DIR__ ).'assets/css/admin.css',array(),'0.9.20');
		?>
		<div class="mr-admin">
		<p class="mr-section"><a href="https://marcosrego.com/en/web-en/yngdev-en/" target="_blank"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDQ1LjEyOSA0NS4xMyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDUuMTI5IDQ1LjEzOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPGc+CgkJPGVsbGlwc2UgY3g9IjIyLjU2NSIgY3k9IjMxLjE5MiIgcng9IjIuNSIgcnk9IjEiIGZpbGw9IiMwMDAwMDAiLz4KCQk8cGF0aCBkPSJNNDIuNjksMjQuNDY5VjE1LjljMC0wLjU5Ny0wLjA1LTEuMTQzLTAuMTIyLTEuNjY1Yy0wLjAxMi0wLjA4MS0wLjAwNy0wLjE0Ni0wLjAyMi0wLjIzICAgIGMtMC4wMTUtMC4wNzEtMC4wMzktMC4xNDUtMC4wNTUtMC4yMTVjLTAuMTM3LTAuNzQyLTAuMzQ5LTEuNDEtMC42MzEtMi4wMDhjLTIuMTk3LTUuMTgtOC40MzQtMTAuMDctMTUuNjI4LTExLjQ2NyAgICBDMTguNTc3LTEuMTY5LDEwLjQyNiwyLjc5OSw4LjQ5OSw3LjcxOUM0Ljg0MSw4Ljc5NywyLjQ0LDExLjExOSwyLjQ0LDE1Ljl2OC41NzNjLTAuODU3LDEuMTIzLTEuMzc1LDIuNTQ0LTEuMzc1LDQuMDk0ICAgIGMwLDIuOTcxLDEuODg3LDUuNDgzLDQuNDY0LDYuMjkxQzguNzksNDEuMTAxLDE1LjMzNSw0NS4xMywyMi41OTYsNDUuMTNjNy4yNjksMCwxMy44MjEtNC4wMzksMTcuMDgxLTEwLjI5NSAgICBjMi41MzktMC44MzIsNC4zODktMy4zMjYsNC4zODktNi4yNjlDNDQuMDY1LDI3LjAxOCw0My41NDcsMjUuNTkyLDQyLjY5LDI0LjQ2OXogTTM3LjgyMywzMS4xMjljLTAuMjksMC0wLjU2NC0wLjA2Ni0wLjgxOC0wLjE4MyAgICBjLTIuMDM5LDUuOTE4LTcuNzExLDEwLjE4My0xNC40MDYsMTAuMTgzYy02LjcwMywwLTEyLjM4NC00LjI3Mi0xNC40MTUtMTAuMmMtMC4yNywwLjEyOS0wLjU2MywwLjItMC44NzQsMC4yICAgIGMtMS4yNDQsMC0yLjI0Mi0xLjE0Ni0yLjI0Mi0yLjU2M3MwLjk5OC0yLjU2MiwyLjI0Mi0yLjU2MmMwLjAyMiwwLDAuMDQ1LDAuMDA2LDAuMDY5LDAuMDA4YzAuMDE2LTIsMC40MzktNS4xNiwxLjE3OC03LjA2NyAgICBjMC45NzIsMS4zNTgsMi40NTgsMi42MjgsNC42NDUsMi42MjhjMCwwLDAsMCwwLjAwMiwwYzAuMTAyLDAsMC4yMDQtMC4wMDMsMC4zMDktMC4wMDljMC4yMDMtMC4wMTEsMC4zNzktMC4xNDQsMC40NDUtMC4zMzUgICAgYzAuMDE5LTAuMDUzLDEuODQ0LTUuMTQ2LDYuNjQ0LTUuNjM1YzAuMjAyLDAuOTkyLDAuNTA4LDMuNjktMS42NjUsNS4zMmMtMC4xNjEsMC4xMi0wLjIzNCwwLjMyNS0wLjE4NiwwLjUyMSAgICBzMC4yMTEsMC4zNDIsMC40MSwwLjM3MmMwLjA0MSwwLjAwNywxLjAzNSwwLjE1MywyLjUxNiwwLjE1M2MyLjUyNCwwLDcuMTEtMC40NTksMTAuMDk5LTMuNDYxICAgIGMwLjUwMSwwLjUwNCwxLjM4MiwxLjc0MywwLjk3LDMuOTczYy0wLjAzOSwwLjIwOCwwLjA1OSwwLjQxOCwwLjI0MSwwLjUyM2MwLjA3OCwwLjA0NSwwLjE2NCwwLjA2NSwwLjI1LDAuMDY1ICAgIGMwLjExNiwwLDAuMjMxLTAuMDQxLDAuMzI1LTAuMTJjMC4xMDctMC4wOTIsMS45NjQtMS42OTUsMy4yNzMtMy40N2MwLjYxNywxLjkyNCwwLjk3Miw0LjcxMSwwLjk4Myw2LjUyOSAgICBjMC4wMDMsMCwwLjAwNSwwLDAuMDA3LDBjMS4yNDQsMCwyLjI0MiwxLjE0NiwyLjI0MiwyLjU2MlMzOS4wNjcsMzEuMTI5LDM3LjgyMywzMS4xMjl6IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iMTUuNzczIiBjeT0iMjUuMDYxIiByPSIyLjI1IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iMjkuMzU3IiBjeT0iMjUuMDYxIiByPSIyLjI1IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" alt="Yng.Dev. Signature" title="Icon made by Freepik from flaticon.com" style="margin-bottom: -7px; margin-right: 3px;"><strong style="font-weight:700;">Yng.Dev.</strong></a>
		</p>
		<?php
						if ( isset( $instance[ 'title' ] ) ) {
							$title = $instance[ 'title' ];
						} else {
							$title = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'theme' ] ) ) {
							$theme = $instance[ 'theme' ];
						} else {
							$theme = __( 'default', 'yng_developer' );
						}
						if ( isset( $instance[ 'layout' ] ) ) {
							$layout = $instance[ 'layout' ];
						} else {
							$layout = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'layoutoptions' ] ) ) {
							$layoutoptions = $instance[ 'layoutoptions' ];
						} else {
							$layoutoptions = __( array(), 'yng_developer' );
						}
						if ( isset( $instance[ 'orderby' ] ) ) {
							$orderby = $instance[ 'orderby' ];
						} else {
							$orderby = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'order' ] ) ) {
							$order = $instance[ 'order' ];
						} else {
							$order = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'excludeinclude' ] ) ) {
							$excludeinclude = $instance[ 'excludeinclude' ];
						} else {
							$excludeinclude = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'itemselect' ] ) ) {
							$itemselect = $instance[ 'itemselect' ];
						} else {
							$itemselect = __( array(), 'yng_developer' );
						}
						if ( isset( $instance[ 'maintitle' ] ) ) {
							$maintitle = $instance[ 'maintitle' ];
						} else {
							$maintitle = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'itemimage' ] ) ) {
							$itemimage = $instance[ 'itemimage' ];
						} else {
							$itemimage = __( 1, 'yng_developer' );
						}
						if ( isset( $instance[ 'itemstitle' ] ) ) {
							$itemstitle = $instance[ 'itemstitle' ];
						} else {
							$itemstitle = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'itemstitlemax' ] ) ) {
							$itemstitlemax = $instance[ 'itemstitlemax' ];
						} else {
							$itemstitlemax = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemdesc' ] ) ) {
							$itemdesc = $instance[ 'itemdesc' ];
						} else {
							$itemdesc = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'itemdescmax' ] ) ) {
							$itemdescmax = $instance[ 'itemdescmax' ];
						} else {
							$itemdescmax = __( '', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemlink' ] ) ) {
							$itemlink = $instance[ 'itemlink' ];
						} else {
							$itemlink = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'bottomlink' ] ) ) {
							$bottomlink = $instance[ 'bottomlink' ];
						} else {
							$bottomlink = __( 'Know more...', 'yng_developer' );
						}
						if ( isset( $instance[ 'perline' ] ) ) {
							$perline = $instance[ 'perline' ];
						} else {
							$perline = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'perpage' ] ) ) {
							$perpage = $instance[ 'perpage' ];
						} else {
							$perpage = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'autoplay' ] ) ) {
							$autoplay = $instance[ 'autoplay' ];
						} else {
							$autoplay = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'pagetransition' ] ) ) {
							$pagetransition = $instance[ 'pagetransition' ];
						} else {
							$pagetransition = __( 'fade', 'yng_developer' );
						}
						if ( isset( $instance[ 'pagetoggles' ] ) ) {
							$pagetoggles = $instance[ 'pagetoggles' ];
						} else {
							$pagetoggles = __( array(), 'yng_developer' );
						}
						if ( isset( $instance[ 'tabs' ] ) ) {
							$tabs = $instance[ 'tabs' ];
						} else {
							$tabs = __( 0, 'yng_developer' );
						}
						if ( isset( $instance[ 'tabsposition' ] ) ) {
							$tabsposition = $instance[ 'tabsposition' ];
						} else {
							$tabsposition = __( 'tabstop', 'yng_developer' );
						}
						if ( isset( $instance[ 'itemoptions' ] ) ) {
							$itemoptions = $instance[ 'itemoptions' ];
						} else {
							$itemoptions = __( array(), 'yng_developer' );
						}
						if ( isset( $instance[ 'globallayoutoptions' ] ) ) {
							$globallayoutoptions = $instance[ 'globallayoutoptions' ];
						} else {
							$globallayoutoptions = __( array(), 'yng_developer' );
						}
						if ( isset( $instance[ 'contenttypes' ] ) ) {
							$contenttypes = $instance[ 'contenttypes' ];
						} else {
							$contenttypes = __( 'category', 'yng_developer' );
						}
						if ( isset( $instance[ 'lastactivedetails' ] ) ) {
							$lastactivedetails = $instance[ 'lastactivedetails' ];
						} else {
							$lastactivedetails = __( '', 'yng_developer' );
						}
						?>
						<p>
						<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label><br>
						<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
						</p>
						<details class="widgetDetails" <?php if(esc_attr( $lastactivedetails ) == 'widgetDetails' || !isset( $contenttypes )) { echo 'open="open"'; } ?>>
						<summary class="mr-section">Content</summary>
						<p>
						<label  for="<?php echo $this->get_field_id( 'contenttypes' ); ?>"><?php _e( 'Type:' ); ?></label><br>
						<select class="widefat mr-contenttypes" id="<?php echo $this->get_field_id('contenttypes'); ?>" name="<?php echo $this->get_field_name('contenttypes'); ?>">
							<?php
								echo '<option value="category"', $contenttypes == 'category' ? ' selected="selected"' : '', '>Categories</option>
								<option value="post"', $contenttypes == 'post' ? ' selected="selected"' : '', '>Posts</option>';
							?>
						</select><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Sort by:' ); ?></label><br>
								<select id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat mr-halfsize" name="<?php echo $this->get_field_name('orderby'); ?>">
									<?php
										echo '<option value="0"', $orderby == 0 ? ' selected="selected"' : '', '>Creation</option>
										<option value="1"', $orderby == 1 ? ' selected="selected"' : '', '>Title</option>
										<option value="2"', $orderby == 2 ? ' selected="selected"' : '', '>Parent</option>
										<option value="3"', $orderby == 3 ? ' selected="selected"' : '', '>Post count</option>
										<option value="4"', $orderby == 4 ? ' selected="selected"' : '', '>Slug</option>';
									?>
								</select>
								<select id="<?php echo $this->get_field_id('order'); ?>" class="widefat mr-halfsize" name="<?php echo $this->get_field_name('order'); ?>">
									<?php
										echo '
										<option value="0" id="Descending"', $orderby == 0 ? ' selected="selected"' : '', '>Descending</option>
										<option value="1" id="Ascending"', $orderby == 1 ? ' selected="selected"' : '', '>Ascending</option>
										';
									?>
						</select><br>
						</p>
						<p><?php _e( 'Items:' ); ?></p>
						<div class="mr-itemscontainer">
						<p class="mr-heading">	
						<select class="mr-excludeinclude" id="<?php echo $this->get_field_id('excludeinclude'); ?>" name="<?php echo $this->get_field_name('excludeinclude'); ?>">
									<?php
										echo '<option value="0"', $excludeinclude == 0 ? ' selected="selected"' : '', '>Exclude</option>
										<option value="1"', $excludeinclude == 1 ? ' selected="selected"' : '', '>Include</option>';
									?>
						</select>:
						<div class="mr-list <?php if($excludeinclude == 1) { echo 'including '; } ?>" >
						<?php
							  /*-----CAT SELECT-----*/
								include trailingslashit( plugin_dir_path( __FILE__ )).'/items.php';
						?>
						</div>
						</div>
						<p class="mr-saveexcludeinclude" style="display: none;">
						<?php _e( 'Please save the changes when you want to see the items considered by your selected options.' ); ?>
						</p>
						</p>
						</details>
						<details class="appearanceDetails" <?php if(esc_attr( $lastactivedetails ) == 'appearanceDetails' || !isset( $theme )) { echo 'open="open"'; } ?>>
						<summary class="mr-section">Appearance</summary>
						<p>
						Theme:<br>
								<select  class="widefat mr-themes" id="<?php echo $this->get_field_id('theme'); ?>" name="<?php echo $this->get_field_name('theme'); ?>">
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
								<div class="mr-themeoptions">
								<?php
								if($theme == "default") {
										include trailingslashit( plugin_dir_path( __DIR__ )).'widget/themes/'.$theme.'/index.php';
								} else if($theme == "none") {
								} else {
									include ABSPATH.'wp-content/themes/mrdev/widget/themes/'.$theme.'/index.php';
								}
								?>
								</div>
								<div class="mr-savetheme" style="display: none;">
								Theme options will appear if available after saving.
								</div>
						</p>
						</details>
						<details class="paginationDetails" <?php if(esc_attr( $lastactivedetails ) == 'paginationDetails') { echo 'open="open"'; } ?>>
						<summary class="mr-section">Pagination</summary>
						<p>
						<select class="mr-pagination-input mr-perline-input" id="<?php echo $this->get_field_id('perline'); ?>" name="<?php echo $this->get_field_name('perline'); ?>" title="Choose the number of items per line">
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
						</select> items per line<br>
						<input class="mr-pagination-input mr-pages-input" type="number" id="<?php echo $this->get_field_id( 'perpage' ); ?>" name="<?php echo $this->get_field_name( 'perpage' ); ?>" type="text" placeholder="∞" title="Choose the number of items per page" value="<?php if(esc_attr( $perpage ) == "" || esc_attr( $perpage ) <= 0) { } else { echo esc_attr( $perpage ); } ?>" /> items per page
						</p>
						<p>
									<label  for="<?php echo $this->get_field_id( 'pagetoggles' ); ?>"><?php _e( 'Toggles:' ); ?></label> <br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="0" <?php checked( ( is_array($pagetoggles) AND in_array( 0, $pagetoggles ) ) ? 0 : '', 0 ); ?> /> <?php _e( 'Arrows' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="1" <?php checked( ( is_array($pagetoggles) AND in_array( 1, $pagetoggles ) ) ? 1 : '', 1 ); ?> /> <?php _e( 'Select' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="2" <?php checked( ( is_array($pagetoggles) AND in_array( 2, $pagetoggles ) ) ? 2 : '', 2 ); ?> /> <?php _e( 'Radio' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="5" <?php checked( ( is_array($pagetoggles) AND in_array( 5, $pagetoggles ) ) ? 5 : '', 5 ); ?> /> <?php _e( 'Keyboard' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="3" <?php checked( ( is_array($pagetoggles) AND in_array( 3, $pagetoggles ) ) ? 3 : '', 3 ); ?> /> <?php _e( 'Below' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'pagetoggles' ) ); ?>[]" value="4" <?php checked( ( is_array($pagetoggles) AND in_array( 4, $pagetoggles ) ) ? 4 : '', 4 ); ?> /> <?php _e( 'Scroll' ); ?></label><br>
						</p>
						<p>
							<label  for="<?php echo $this->get_field_id( 'tabs' ); ?>"><?php _e( 'Tabs:' ); ?></label><br>
							<select class="widefat mr-halfsize" id="<?php echo $this->get_field_id('tabs'); ?>" name="<?php echo $this->get_field_name('tabs'); ?>">
										<?php
											echo '<option value="0" id="notabs"', $tabs == 0 ? ' selected="selected"' : '', '>None</option>
											<option value="1" id="itemstabs"', $tabs == 1 ? ' selected="selected"' : '', '>Items</option>
											<option value="2" id="parenttabs"', $tabs == 2 ? ' selected="selected"' : '', '>Parent items</option>';
										?>
							</select>
							<select class="widefat mr-halfsize" id="<?php echo $this->get_field_id('tabsposition'); ?>" name="<?php echo $this->get_field_name('tabsposition'); ?>">
										<?php
											echo '<option value="tabstop" id="tabstop"', $tabsposition == 'tabstop' ? ' selected="selected"' : '', '>Top</option>
											<option value="tabsright" id="tabsright"', $tabsposition == 'tabsright' ? ' selected="selected"' : '', '>Right</option>
											<option value="tabsbottom" id="tabsbottom"', $tabsposition == 'tabsbottom' ? ' selected="selected"' : '', '>Bottom</option>
											<option value="tabsleft" id="tabsleft"', $tabsposition == 'tabsleft' ? ' selected="selected"' : '', '>Left</option>';
										?>
							</select><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'pagetransition' ); ?>"><?php _e( 'Page transition:' ); ?></label><br>
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
						<input class="mr-pagination-input mr-autoplay-input" type="number" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" type="text" placeholder="∞" title="Choose how many seconds the autoplay should take to change page. Leave empty or choose '0' to turn off autoplay." value="<?php if(esc_attr( $autoplay ) == "" || esc_attr( $autoplay ) <= 0) { } else { echo esc_attr( $autoplay ); } ?>" /> seconds per page
						</p>
						</details>
						<details class="displayDetails" <?php if(esc_attr( $lastactivedetails ) == 'displayDetails') { echo 'open="open"'; } ?>>
						<summary class="mr-section">Display</summary>
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
						<select class="widefat mr-itemimage" id="<?php echo $this->get_field_id('itemimage'); ?>" name="<?php echo $this->get_field_name('itemimage'); ?>">
										<?php
											echo '<option value="9"', $itemimage == 9 ? ' selected="selected"' : '', '>No image</option>
											<option value="1"', $itemimage == 1 ? ' selected="selected"' : '', '>Item image</option>
											<option value="8"', $itemimage == 8 ? ' selected="selected"' : '', '>Description first image</option>
											<option value="2"', $itemimage == 2 ? ' selected="selected"' : '', '>Latest sticky post image</option>
											<option value="5"', $itemimage == 5 ? ' selected="selected"' : '', '>Latest post image</option>';
										?>
						</select><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemstitle' ); ?>"><?php _e( 'Titles:' ); ?></label><br>
						<select  class="widefat mr-itemstitleinput" id="<?php echo $this->get_field_id('itemstitle'); ?>" name="<?php echo $this->get_field_name('itemstitle'); ?>">
									<?php
										echo '<option value="0"', $itemstitle == 0 ? ' selected="selected"' : '', '>Linked item title</option>
										<option value="2"', $itemstitle == 2 ? ' selected="selected"' : '', '>Item title</option>
										<option value="1"', $itemstitle == 1 ? ' selected="selected"' : '', '>No title</option>';
									?>
								</select><br>
								<span class="mr-itemstitlemax" <?php if($itemstitle && $itemstitle == 1) { echo 'style="display: none;"'; } ?>>
								<input class="widefat mr-pagination-input" id="<?php echo $this->get_field_id( 'itemstitlemax' ); ?>" name="<?php echo $this->get_field_name( 'itemstitlemax' ); ?>" placeholder="∞" type="number" value="<?php if(!esc_attr( $itemstitlemax )) {  } else { echo esc_attr( $itemstitlemax ); } ?>" /> max. characters
								</span>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemdesc' ); ?>"><?php _e( 'Descriptions:' ); ?></label><br>
						<select  class="widefat mr-itemdescinput" id="<?php echo $this->get_field_id('itemdesc'); ?>" name="<?php echo $this->get_field_name('itemdesc'); ?>">
									<?php
										echo '<option value="0"', $itemdesc == 0 ? ' selected="selected"' : '', '>Item description</option>
										<option value="4"', $itemdesc == 4 ? ' selected="selected"' : '', '>Item excerpt</option>
										<option value="2"', $itemdesc == 2 ? ' selected="selected"' : '', '>Item intro text</option>
										<option value="3"', $itemdesc == 3 ? ' selected="selected"' : '', '>Item full text</option>
										<option value="1"', $itemdesc == 1 ? ' selected="selected"' : '', '>No description</option>';
									?>
								</select><br>
								<span class="mr-itemdescmax" <?php if($itemdesc && $itemdesc == 1) { echo 'style="display: none;"'; } ?> >
								<input class="widefat mr-pagination-input" id="<?php echo $this->get_field_id( 'itemdescmax' ); ?>" name="<?php echo $this->get_field_name( 'itemdescmax' ); ?>" placeholder="∞" type="number" value="<?php if(!esc_attr( $itemdescmax )) {  } else { echo esc_attr( $itemdescmax ); } ?>" /> max. characters
								</span>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemlink' ); ?>"><?php _e( 'Links:' ); ?></label><br>
						<select  class="widefat mr-bottomlinkinput" id="<?php echo $this->get_field_id('itemlink'); ?>" name="<?php echo $this->get_field_name('itemlink'); ?>">
									<?php
										echo '<option value="0"', $itemlink == 0 ? ' selected="selected"' : '', '>Item link</option>
										<option value="1"', $itemlink == 1 ? ' selected="selected"' : '', '>No bottom link</option>';
									?>
						</select><br>
						<input  class="widefat  mr-bottomlinktext" id="<?php echo $this->get_field_id( 'bottomlink' ); ?>"  <?php if(isset($itemlink) && $itemlink == 1) { echo 'style="display: none;"'; } ?>  name="<?php echo $this->get_field_name( 'bottomlink' ); ?>" type="text" placeholder="Bottom link text" title="Bottom link text" value="<?php if(esc_attr( $bottomlink ) == "") { echo "Know more..."; } else { echo esc_attr( $bottomlink ); } ?>" />
						</p>
						</details>
						<details class="optionsDetails" <?php if(esc_attr( $lastactivedetails ) == 'optionsDetails') { echo 'open="open"'; }  ?>>
						<summary class="mr-section">Options</summary>
						<p>
						<label  for="<?php echo $this->get_field_id( 'globallayoutoptions' ); ?>"><?php _e( 'Global layout options:' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="windowheight" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "windowheight", $globallayoutoptions ) ) ? "windowheight" : '', "windowheight" ); ?> /> <?php _e( 'Window height' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="onlyactives" <?php checked( ( is_array($globallayoutoptions ) AND in_array( "onlyactives", $globallayoutoptions ) ) ? "onlyactives" : '', "onlyactives" ); ?> /> <?php _e( 'Only show actives' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="hideinactives" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "hideinactives", $globallayoutoptions ) ) ? "hideinactives" : '', "hideinactives" ); ?> /> <?php _e( 'On active hide inactives' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="keepactive" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "keepactive", $globallayoutoptions ) ) ? "keepactive" : '', "keepactive" ); ?> /> <?php _e( 'Keep other actives opened' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="subcatactive" <?php checked( ( is_array($globallayoutoptions ) AND in_array( "subcatactive", $globallayoutoptions ) ) ? "subcatactive" : '', "subcatactive" ); ?> /> <?php _e( 'Only show subitems of active' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="contentpagination" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "contentpagination", $globallayoutoptions ) ) ? "contentpagination" : '', "contentpagination" ); ?> /> <?php _e( 'Pagination inside content' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'globallayoutoptions' ) ); ?>[]" value="donotinactive" <?php checked( ( is_array( $globallayoutoptions ) AND in_array( "donotinactive", $globallayoutoptions ) ) ? "donotinactive" : '', "donotinactive" ); ?> /> <?php _e( 'Do not inactive on click' ); ?></label><br>
						</p>
						<p>
						<label  for="<?php echo $this->get_field_id( 'itemoptions' ); ?>"><?php _e( 'Other options:' ); ?></label> <br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="artcount" <?php checked( ( is_array($itemoptions) AND in_array( "artcount", $itemoptions ) ) ? "artcount" : '', "artcount" ); ?> /> <?php _e( 'Show number of articles' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="hover" <?php checked( ( is_array( $itemoptions ) AND in_array( "hover", $itemoptions ) ) ? "hover" : '', "hover" ); ?> /> <?php _e( 'Active on mouseover' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="autoscroll" <?php checked( ( is_array( $itemoptions ) AND in_array( "autoscroll", $itemoptions ) ) ? "autoscroll" : '', "autoscroll" ); ?> /> <?php _e( 'Auto scroll to active' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="url" <?php checked( ( is_array( $itemoptions ) AND in_array( "url", $itemoptions ) ) ? "url" : '', "url" ); ?> /> <?php _e( 'Change URL on active' ); ?></label><br>
									<label ><input  type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemoptions' ) ); ?>[]" value="remember" <?php checked( ( is_array( $itemoptions ) AND in_array( "remember", $itemoptions ) ) ? "remember" : '', "remember" ); ?> /> <?php _e( 'Remember last active <small>(<i>uses cookies</i>)</small>' ); ?></label><br>
						</p>
						</details>
						<input class="widefat lastactivedetails" id="<?php echo $this->get_field_id( 'lastactivedetails' ); ?>" name="<?php echo $this->get_field_name( 'lastactivedetails' ); ?>" type="text" placeholder="Last Active Admin Details/Option" title="Last Active Admin Details/Option" value="<?php if(esc_attr( $lastactivedetails ) != "") { echo esc_attr( $lastactivedetails ); } ?>" readonly hidden />
						
						<details class="featuresDetails" <?php if(esc_attr( $lastactivedetails ) == 'featuresDetails') { echo 'open="open"'; } ?>>
							<?php if (!is_plugin_active('mrdev/mrdev.php') ) { ?>
							<summary class="mr-section"><strong>DO YOU NEED MORE FEATURES?</strong></summary>
							<p>
							If you need more features then you need <strong>Mr.Dev.</strong>:</p>
							<ol>
							<li>Insert widgets inside the content section on posts/pages/categories using <strong>blocks, classic editor button or shortcodes</strong>.</li>
							<li><strong>More content types</strong> such as pages, tags and some compatibility with other third-party registered terms/post-types (such as events and products).</li>
							<li><strong>Override the content</strong> of each item per widget, without affecting the original content.</li>
							<li>Create and edit <strong>custom items</strong> directly on the widgets.</li>
							<li>Choose <strong>items' parents such as parent categories, categories and tags</strong> to only display their childs.</li>
							<li>Manually <strong>reorder</strong>.</li>
							<li><strong>Pin</strong> to choose the ones starting active.</li>
							<li><strong>Auto exclude</strong> Subcategories, Categories with no posts, same link, different link and more.</li>
							<li>More image options such as <strong>thumbnails and parallax</strong>.</li>
							<li>Choose a <strong>fallback image</strong>.</li>
							<li>Choose <strong>images maximum size</strong> together with <strong>srcset and native lazyload</strong>.</li>
							<li><strong>More options for tabs</strong> such as Categories and Tags.</li>
							<li><strong>Hide widget sections</strong> to specific users or roles.</li>
							<li>Other <strong>Advanced</strong> options such as preload pages, content cache, choose the titles tag (h2, h3, h4, p, etc), load polyfill on IE and add custom classes to the bottom link.</li>
							</ol>
							<p>And more...</p>
							<p><a class="button button-primary" href="https://marcosrego.com/en/web-en/mrdev-en/" target="_blank">Get Mr.Dev.</a></p>
							<?php } else {  ?>
							<summary class="mr-section"><strong>LET THE YOUNG GROW TO A MISTER!</strong></summary>
							<p>Use Mr.Dev. widgets to manually recreate your current Yng.Dev. widgets and get all the features. After that you can safely delete the plugin Yng.Dev. </p>
							<?php } ?> 
						</details>
						<?php
							wp_register_script( 'yngdev_admin', plugin_dir_url( __DIR__ ).'assets/js/admin.js',array(),'0.9.20');
							wp_enqueue_script( 'yngdev_admin' );
						?>
						</div>
			<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['theme'] = ( !empty( $new_instance['theme'] ) ) ? strip_tags( $new_instance['theme'] ) : 'default';
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
			$instance['layoutoptions'] = array();
		}
		$instance['perline'] = ( ! empty( $new_instance['perline'] ) ) ? strip_tags( $new_instance['perline'] ) : 0;
		$instance['perpage'] = ( ! empty( $new_instance['perpage'] ) ) ? strip_tags( absint( $new_instance['perpage'] ) ) : 0;
		$instance['autoplay'] = ( ! empty( $new_instance['autoplay'] ) ) ? strip_tags( absint( $new_instance['autoplay'] ) ) : 0;
		$instance['tabs'] = ( ! empty( $new_instance['tabs'] ) ) ? strip_tags( $new_instance['tabs'] ) : 0;
		$instance['tabsposition'] = ( !empty( $new_instance['tabsposition'] ) ) ? strip_tags( $new_instance['tabsposition'] ) : 'tabstop';
		$instance['pagetransition'] = ( ! empty( $new_instance['pagetransition'] ) ) ? strip_tags( $new_instance['pagetransition'] ) : 'fade';
		$pagetoggles = ( ! empty ( $new_instance['pagetoggles'] ) ) ? (array) $new_instance['pagetoggles'] : array();
		$instance['pagetoggles'] = array_map( 'sanitize_text_field', $pagetoggles );
		$instance['pagetoggles'] = array_map( 'intval', $instance['pagetoggles'] );
			$instance['contenttypes'] = ( !empty( $new_instance['contenttypes'] ) ) ? strip_tags( $new_instance['contenttypes'] ) : 'category';
			$instance['excludeinclude'] = ( !empty( $new_instance['excludeinclude'] ) ) ? strip_tags( absint( $new_instance['excludeinclude'] ) ) : 0;
			$instance['orderby'] = ( !empty( $new_instance['orderby'] ) ) ? strip_tags( absint( $new_instance['orderby'] ) ) : 0;
			$instance['order'] = ( !empty( $new_instance['order'] ) ) ? strip_tags( absint( $new_instance['order'] ) ) : 0;
			$itemselect = ( ! empty ( $new_instance['itemselect'] ) ) ? (array) $new_instance['itemselect'] : array();
			$instance['itemselect'] = array_map( 'sanitize_text_field', $itemselect );
		$instance['maintitle'] = ( !empty( $new_instance['maintitle'] ) ) ? strip_tags( $new_instance['maintitle'] ) : 0;
		$instance['itemimage'] = ( !empty( $new_instance['itemimage'] ) ) ? strip_tags( absint( $new_instance['itemimage'] ) ) : 1;
		$instance['itemstitle'] = ( !empty( $new_instance['itemstitle'] ) ) ? strip_tags( absint( $new_instance['itemstitle'] ) ) : 0;
		$instance['itemstitlemax'] = ( ! empty( $new_instance['itemstitlemax'] ) ) ? strip_tags( absint( $new_instance['itemstitlemax'] ) ) : '';
		$instance['itemdesc'] = ( !empty( $new_instance['itemdesc'] ) ) ? strip_tags( absint( $new_instance['itemdesc'] ) ) : 0;
		$instance['itemdescmax'] = ( ! empty( $new_instance['itemdescmax'] ) ) ? strip_tags( absint( $new_instance['itemdescmax'] ) ) : '';
		$instance['itemlink'] = ( !empty( $new_instance['itemlink'] ) ) ? strip_tags( absint( $new_instance['itemlink'] ) ) : 0;
		$instance['bottomlink'] = ( ! empty( $new_instance['bottomlink'] ) ) ? strip_tags( $new_instance['bottomlink'] ) : 'Know more...';
		$itemoptions = ( ! empty ( $new_instance['itemoptions'] ) ) ? (array) $new_instance['itemoptions'] : array();
		$instance['itemoptions'] = array_map( 'sanitize_text_field', $itemoptions );
		$globallayoutoptions = ( ! empty ( $new_instance['globallayoutoptions'] ) ) ? (array) $new_instance['globallayoutoptions'] : array();
		$instance['globallayoutoptions'] = array_map( 'sanitize_text_field', $globallayoutoptions );
		$instance['lastactivedetails'] = ( ! empty( $new_instance['lastactivedetails'] ) ) ? strip_tags( $new_instance['lastactivedetails'] ) : '';
		return $instance;
	}
}
?>