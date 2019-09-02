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
					if($maintitle == "Main category title") {
						if ( ! empty( $maincat ) ) {
							echo $args['before_title'] . get_cat_name( $maincat ) . $args['after_title'];
						}
					} else if($maintitle == "Theme and layout title") {
						echo $args['before_title'] . $theme . ' - ' . $layout . $args['after_title'];
					} else if($maintitle == "Theme title") {
						echo $args['before_title'] . $theme . $args['after_title'];
					} else if($maintitle == "Layout title") {
						echo $args['before_title'] . $layout . $args['after_title'];
					} else if($maintitle == "No main title") {
						
					} else {
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
				$catarray = array('parent' => $maincat, 'orderby' => $orderby,'order' => $order,'hide_empty' => false, 'lang' => $lang);
				
				/*
				Create the main array of categories using 'get_terms' without subcategories.
				The subcategories will be added after, the reason for that is to create a hierarchy that works with the main category and parent id.
				*/
				if($orderby == 'parent' && $order == 'DESC') {
					$catlist = array_reverse(get_terms('category',$catarray));
				} else {
					$catlist = get_terms('category',$catarray);
				}
				
				/* Get extra classes to give to the main container */
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
									Check if current category was not group excluded.
									*/
									if($item->parent == $maincat || $maincat == 0) {
										if($excludeinclude == 'Exclude' && is_array($groupexclude) && in_array('samelink', $groupexclude) && !in_array('differentlink', $groupexclude) && str_replace("/./","/",get_category_link($item->term_id)) != $currentLink || $excludeinclude == 'Exclude' && is_array($groupexclude) && in_array('differentlink', $groupexclude) && !in_array('samelink', $groupexclude) && str_replace("/./","/",get_category_link($item->term_id)) == $currentLink || $excludeinclude == 'Exclude' && is_array($groupexclude) && in_array('differentlink', $groupexclude) && !in_array('samelink', $groupexclude) && str_replace("/./","/",get_category_link($item->term_id)) == $currentLink || !is_array($groupexclude) || is_array($groupexclude) && !in_array('samelink', $groupexclude) && !in_array('differentlink', $groupexclude) || $excludeinclude == 'Include') {
										
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
											Check if this item should be on a new page.
											*/
											if($itemcount == 0) {
												$content .= '<ul class="'.$perpageclass.' mrwid-page'.$pagecount.' '.$perlineclass.'">';
												if($pagecount > 1) {
													$content .= '<noscript>';
												}
											}

											$content .= '<li class="catid-'.$item->term_id.' '.$item->slug.' mr-wid '.$mrcurrent.'" '.((is_array($catoptions) AND in_array("url", $catoptions))?'url='.get_category_link($item->term_id):"").' ><div class="mrwid-container">'.$showcattitle.'<div class="mrwid-content">'.$showcatdesc.$bottomlinktext.'</div></div></li>';

											$itemcount = ($itemcount + 1);
										}
										}
									}
								}
								
										
								/*------SUBCATEGORIES------*/
								/*
								If parent category was manually removed do not show subcategories
								*/
								if($excludeinclude == 'Exclude' && is_array( $catexclude ) && !in_array($item->term_id, $catexclude) || $excludeinclude != 'Exclude') {
									
										/*
										Before continuing the mainarray of the other main categories, create a new array with the subcategories of the previous category. 
										By doing this, the selected main category is respected even if the order is not 'Parent'.
										*/
										$subcatarray = array('child_of' => $item->term_id, 'orderby' => $orderby,'order' => $order,'hide_empty' => false, 'lang' => $lang);
										
										if($orderby == 'parent' && $order == 'DESC') {
											$childs = array_reverse(get_terms( 'category',$subcatarray));
										} else {
											$childs = get_terms( 'category',$subcatarray);
										}
										
										foreach ( $childs as $key => $item ) {
											/*
											If parent category was not included do not show subcategories
											*/
											if($excludeinclude == 'Include' && is_array( $catexclude ) && in_array($item->parent, $catexclude) || $excludeinclude != 'Include') {
										
											if(is_admin()) {
													echo '<div class="mrwid-childs">';
													?>
												
														<label >
														<input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'catexclude' ) ); ?>[]" value="<?php echo $item->term_id; ?>" <?php checked( ( is_array( $catexclude ) AND in_array( $item->term_id, $catexclude ) ) ? $item->term_id : '', $item->term_id ); ?>/> <?php echo $item->name; ?></label>
														
													<?php 
													echo "</div>";
											} else {
												$currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
												
												if($excludeinclude == 'Exclude' && is_array($groupexclude) && in_array('samelink', $groupexclude) && !in_array('differentlink', $groupexclude) && str_replace("/./","/",get_category_link($item->term_id)) != $currentLink || $excludeinclude == 'Exclude' && is_array($groupexclude) && in_array('differentlink', $groupexclude) && !in_array('samelink', $groupexclude) && str_replace("/./","/",get_category_link($item->term_id)) == $currentLink || !is_array($groupexclude) || is_array($groupexclude) && !in_array('samelink', $groupexclude) && !in_array('differentlink', $groupexclude) || $excludeinclude == 'Include') {
													
												/*
												Check if current child category was not manually excluded.
												*/
												if($excludeinclude == 'Exclude' AND is_array( $catexclude ) AND !in_array($item->term_id, $catexclude) OR $excludeinclude == 'Include' AND is_array( $catexclude ) AND in_array($item->term_id, $catexclude)) {
													/*
													Child title starts here
													*/
													if($titletag == null || $titletag == "") {
														$titletag == 'h3';
													}
													
													if($cattitle == "Category title") {
														$showcattitle = '<'.$titletag.' class="mrwid-title">'.$item->name.((is_array($catoptions) AND in_array("artcount", $catoptions))?' <small>('.$item->count.')</small>':"").'</'.$titletag.'>';
													} else if($cattitle == "No title")  {
														$showcattitle = ''.((is_array($catoptions) AND in_array("artcount", $catoptions))?'<'.$titletag.' class="mrwid-title">('.$item->count.')</'.$titletag.'>':"");
													} else  {
														$showcattitle = '<'.$titletag.' class="mrwid-title">'.'<a href="'.get_category_link($item->term_id).'">'.$item->name.((is_array($catoptions) AND in_array("artcount", $catoptions))?' <small>('.$item->count.')</small>':"").'</a>'.'</'.$titletag.'>';
													}
															
													/*
													Child description starts here
													*/														
													if($catdesc == "No description") {
														$showcatdesc = '';
													} else  {
														$showcatdesc = '<div class="mrwid-desc">'.do_shortcode( $item->description).'</div>';
													}
														
													/*
													Child bottom link starts here
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
													Check front for active category and adds a class if it's the current child category.
													*/
													if(is_array($currentcat) && in_array($item->term_id, $currentcat) || str_replace("/./","/",get_category_link($item->term_id)) == $currentLink) {
														$mrcurrent = 'mrwid-current';
													} else if($currentcat != '' && $currentcat == $item->term_id) {
														$mrcurrent = 'mrwid-current';
													} else {
														$mrcurrent = '';
													}
													
													/*
													Add the content of the current child after the previous inserted parent category.
													*/
													
													$content .= '<li class="catid-'.$item->term_id.' '.$item->slug.' mr-wid mrwid-subcat '.$mrcurrent.'" '.((is_array($catoptions) AND in_array("url", $catoptions))?'url='.get_category_link($item->term_id):"").' ><div class="mrwid-container">'.$showcattitle.'<div class="mrwid-content">'.$showcatdesc.$bottomlinktext.'</div></div></li>';
												}
												}
											}
											}
										}
									}
								

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