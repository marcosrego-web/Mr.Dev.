<?php
defined('ABSPATH') or die;

			    $lang = '';
				if(!is_admin()) {
					$currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					
					/*
					In front when using multilang, only show categories of the current language.
					*/
					$lang =	get_locale();
					
					/*
					Main title depends of the selected display option.
					Widget title is the default.
					*/
					if($maintitle == 3) { // Theme and layout title

						echo $args['before_title'] . $theme . ' - ' . $layout . $args['after_title'];

					} else if($maintitle == 4) { // Theme title

						echo $args['before_title'] . $theme . $args['after_title'];

					} else if($maintitle == 5) { // Layout title

						echo $args['before_title'] . $layout . $args['after_title'];

					} else if($maintitle == 6) { // No main title

						

					} else { // Widget title

						if ( ! empty( $title ) ) {

							echo $args['before_title'] . $title . $args['after_title'];

						}

					}
					
					/*
					Get the perline number
					*/
					if($perline != null && $perline != "" && $perline != "∞" && $perline > 0) {
						$perlineclass = " mrwid-perliner mrwid-".$perline."perline";
					} else {
						$perlineclass = "";
					}

					/*
					Get the perpage number
					*/
					if($perpage != null && $perpage != "" && $perpage != "∞" && $perpage > 0) {
						$perpageclass = "mrwid-pages mrwid-".$perpage."perpage";
					} else {
						$perpageclass = "mrwid-pages";
					}
				}
				
				/*
				Get current active categories.
				*/
				$currentcat = '';
				if (is_category()) {
					$currentcat = get_query_var('cat');
				} else if(is_single()) {
					$currentcat = wp_get_post_categories(get_the_ID());
				}
				
				$maincat = 0;
				
				if($excludeinclude == null || $excludeinclude == '') {
					$excludeinclude == 'Exclude';
				}
				
				/*
				Get the selected automatic order.
				*/
				if($orderby == null || $orderby == '' || $orderby == 'Parent') {
					$orderby = 'parent';
				} else if($orderby == 'Title') {
					$orderby = 'name';
				} else if($orderby == 'Article count') {
					$orderby = 'count';
				} else if($orderby == 'Slug') {
					$orderby = 'slug';
				} else if($orderby == 'Creation') {
					$orderby = 'term_id';
				}  else {
					$orderby = 'parent';
				}
				
				if($order == null || $order == '' || $order == 'Ascending' ) {
					$order = 'ASC';
				} else if($order == 'Descending') {
					$order = 'DESC';
				} else {
					$order = 'ASC';
				}
				
				
				/*
				Join all the previous options for the main array of categories.
				*/
				$catarray = array('orderby' => $orderby,'order' => $order,'hide_empty' => false, 'lang' => $lang, 'hierarchical' => true);
				$catlist = get_terms('category',$catarray);
				
				if ( ! empty( $catlist ) ) {

						/*
						Start the content with the container for the categories.
						The theme name, the layout name and the global options are imploded in here has classes.
						*/
						if(is_admin()) {
						} else {
							$content .= '<div class="mr-widget mrwid-theme mrwid-'.strtolower($theme).'"><div class="mrwid-layout mrwid-'.strtolower($layout).' mrwid-'.implode(" mrwid-", $layoutoptions).' mrwid-'.implode(" mrwid-", $catoptions).'">';
						}
						$itemcount = 0;
						$pagecount = 1;

						foreach ( $catlist as $key => $item) {
									
								/*
								Add the content of the current category in the container.
								The layout options are imploded in here has classes.
								*/
								if(is_admin()) {
										/*
										Check if this item should be on a new page.
										*/
										if($itemcount == 0) {
											echo '<div class="mrwid-list-container">';
										}
									
										echo '<div class="mrwid-childs">';
										?>
												
												<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'catexclude' ) ); ?>[]" value="<?php echo $item->term_id; ?>" <?php checked( ( is_array( $catexclude ) AND in_array( $item->term_id, $catexclude ) ) ? $item->term_id : '', $item->term_id ); ?>/> <?php echo $item->name; ?></label>
												
										<?php
										echo "</div>";

										$itemcount = ($itemcount + 1);
								} else {
									
										/*
										Check if current category was not manually excluded.
										*/
										if($excludeinclude == 'Exclude' AND is_array( $catexclude ) AND !in_array($item->term_id, $catexclude) OR $excludeinclude == 'Include' AND is_array( $catexclude ) AND in_array($item->term_id, $catexclude) ) {
										
											/*
											Title starts here
											*/
											if($titletag == null || $titletag == "") {
												$titletag = 'h3';
											}
											if($cattitle == "Category title") {
												$showcattitle = '<'.$titletag.' class="mrwid-title">'.$item->name.((is_array( $catoptions ) AND  in_array("artcount", $catoptions))?' <small>('.$item->count.')</small>':"").'</'.$titletag.'>';
											} else if($cattitle == "No title")  {
												$showcattitle = ''.((is_array($catoptions) AND in_array("artcount", $catoptions))?'<'.$titletag.' class="mrwid-title">('.$item->count.')</'.$titletag.'>':"");
											} else  {
												$showcattitle = '<'.$titletag.' class="mrwid-title">'.'<a href="'.get_category_link($item->term_id).'">'.$item->name.((is_array($catoptions) AND in_array("artcount", $catoptions))?' <small>('.$item->count.')</small>':"").'</a>'.'</'.$titletag.'>';
											}
														
											/*
											Description starts here
											*/
											if($catdesc == "No description") {
												$showcatdesc = '';
											} else  {
												$showcatdesc = '<div class="mrwid-desc">'.do_shortcode( $item->description).'</div>';
											}
													
											/*
											Bottom link starts here
											*/
											if($catlink == "No bottom link") {
												$bottomlinktext="";
											} else {
												if($bottomlink == "") {
													$bottomlink = "Know more...";
												}
															
												$bottomlinktext = '<div class="mrwid-link"><a class="'.$bottomlinkclasses.'" href="'.get_category_link($item->term_id).'" title="'. $item->name .'">'.$bottomlink.'</a></div>';
											}
											
											/*
											Check front for active category/link and adds a class if it's the current category/link.
											*/
											if(is_array($currentcat) && in_array($item->term_id, $currentcat) || str_replace("/./","/",get_category_link($item->term_id)) == $currentLink) {
												$mrcurrent = 'mrwid-current';
											} else if($currentcat != '' && $currentcat == $item->term_id) {
												$mrcurrent = 'mrwid-current';
											} else {
												$mrcurrent = '';
											}

											/*

											Add classes for subcategories

											*/

											if($item->parent != 0) {

												$mrsubcat = 'mrwid-subcat parentcatid-'.$item->parent;

											} else {

												$mrsubcat = '';

											}
											
											/*
											Check if this item should be on a new page.
											*/
											if($itemcount == 0) {
												$content .= '<ul class="'.$perpageclass.' mrwid-page'.$pagecount.' '.$perlineclass.'">';
												if($pagecount > 1) {
													$content .= '<noscript>';
												}
											}

											$content .= '<li class="catid-'.$item->term_id.' '.$item->slug.' '.$mrsubcat.' mr-wid '.$mrcurrent.'" '.((is_array($catoptions) AND in_array("url", $catoptions))?'url='.get_category_link($item->term_id):"").' ><div class="mrwid-container">'.$showcattitle.'<div class="mrwid-content">'.$showcatdesc.$bottomlinktext.'</div></div></li>';

											$itemcount = ($itemcount + 1);

											/*
											If the option 'only show subcategories of active' is enabled and this item is a subcategory, it should not close the page yet.
											*/

											if(is_array($layoutoptions) && in_array( "subcatactive", $layoutoptions ) && $mrsubcat != '' && $mrsubcat != null || !is_array($layoutoptions ) && $layoutoptions == "subcatactive" && $mrsubcat != '' && $mrsubcat != null) {

											} else {
												if($itemcount == $perpage) {

													if(is_admin()) {

														echo '</div><hr>';

													} else {

														if($pagecount > 1) {

															$content .= '</noscript>';

														}

														$content .= '</ul>';

													}

													$itemcount = 0;

													$pagecount = ($pagecount + 1);

												}
											}
										}
								}
								
						}

						/*
						Doublecheck if the last page was closed in case the last item was a hidden subcategory.
						*/
						if($itemcount != 0) {
							if(is_admin()) {
								echo '</div><hr>';
							} else {
								if($pagecount > 1) {
									$content .= '</noscript>';
								}
								$content .= '</ul>';
							}

							$itemcount = 0;
							$pagecount = ($pagecount + 1);
						}

						$pagecount = ($pagecount - 1);

						if(is_admin()) {
						} else {
							if($pagecount > 1) {
								if( is_array( $pagetoggles ) && in_array( 'arrows', $pagetoggles )) {
									$content .= '<button class="mrwid-arrows mrwid-prev" value="'.$pagecount.'"><span><</span></button>';
								}

								if( is_array( $pagetoggles ) && in_array( 'below', $pagetoggles ) || is_array( $pagetoggles ) && in_array( 'scroll', $pagetoggles )) {
									$content .= '<button class="'.((is_array($pagetoggles) AND in_array("below", $pagetoggles))?'mrwid-below':"").' '.((is_array($pagetoggles) AND in_array("scroll", $pagetoggles))?'mrwid-scroll':"").'"><span>+</span></button>';
								}

								if( is_array( $pagetoggles ) && in_array( 'arrows', $pagetoggles )) {
									$content .= '<button class="mrwid-arrows mrwid-next" value="2"><span>></span></button>';
								}
								$content .= '<div class="mrwid-pagination mrwid-'.$pagetransition.'">';
									$hideelement = '';
									if (is_array( $pagetoggles ) && !in_array( 'pageselect', $pagetoggles ) && !in_array( 'arrows', $pagetoggles ) && !in_array( 'radio', $pagetoggles ) && !in_array( 'loadmore', $pagetoggles ) && !in_array( 'scroll', $pagetoggles )) {
										$hideelement = '';
									} else if( is_array( $pagetoggles ) && !in_array( 'pageselect', $pagetoggles )) {
										$hideelement = 'style="display:none;"';
									}

									$content .= '<select class="mrwid-pageselect" title="/'.$pagecount.'" '.$hideelement.'>';
									$pagenumber = 0;
									while ($pagenumber++ < $pagecount) {
										$content .= '<option value="'.$pagenumber.'">'.$pagenumber.'</option>';
									}
									$content .= '</select>';
									
									
									if( is_array( $pagetoggles ) && in_array( 'radio', $pagetoggles )) {
										$pagenumber = 0;
										while ($pagenumber++ < $pagecount) {
											$content .= '<input class="mrwid-radio" type="radio" name="mrwid-radio" value="'.$pagenumber.'" title="'.$pagenumber.'/'.$pagecount.'">';
										}
									}
								$content .= '</div>';
							}
							$content .= '</div></div>';
						}
				}
				
				
?>
