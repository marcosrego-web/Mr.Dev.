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
					if($perline && $perline != "∞" && $perline > 0) {
						$perlineclass = " mrwid-perliner mrwid-".$perline."perline";
					} else {
						$perlineclass = "";
					}
					/*
					Get the perpage number
					*/
					if($perpage && $perpage != "∞" && $perpage > 0) {
						$perpageclass = "mrwid-pages mrwid-".$perpage."perpage";
					} else {
						$perpageclass = "mrwid-pages";
					}
					/*
					Get the autoplay seconds
					*/
					if($autoplay && $autoplay != "∞" && $autoplay > 0) {
						$autoplay = ' mrwid-autoplay'.$autoplay."s mrwid-transitionright";
					} else {
						$autoplay = "";
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
				if(!$excludeinclude) {
					$excludeinclude == 0;
				}
				/*
				Get the selected automatic order.
				*/
				if(!$orderby  || $orderby == 0) { //Parent
					$orderby = 'parent';
				} else if($orderby == 1) { //Title
					$orderby = 'name';
				} else if($orderby == 3) { //Article count
					$orderby = 'count';
				} else if($orderby == 4) { //Slug
					$orderby = 'slug';
				} else if($orderby == 2) { //Creation
					$orderby = 'term_id';
				}  else { //Parent
					$orderby = 'parent';
				}
				if(!$order || $order == 0 ) { //Ascending
					$order = 'ASC';
				} else if($order == 1) { //Descending
					$order = 'DESC';
				} else { //Ascending
					$order = 'ASC';
				}
				/*
				Join all the previous options for the main array of categories.
				*/
				$itemarray = array('orderby' => $orderby,'order' => $order,'hide_empty' => false, 'lang' => $lang, 'hierarchical' => true);
				$itemlist = get_terms('category',$itemarray);
				if ( ! empty( $itemlist ) ) {
						/*
						Start the content with the container for the categories.
						The theme name, the layout name and the global options are imploded in here has classes.
						*/
						if(is_admin()) {
						} else {
							$content .= '<div class="mr-widget mr-categories mrwid-theme mrwid-'.strtolower($theme).'"><div class="mrwid-layout mrwid-'.strtolower($layout).' mrwid-'.implode(" mrwid-", $layoutoptions).' mrwid-'.implode(" mrwid-", $globallayoutoptions).' mrwid-'.implode(" mrwid-", $itemoptions).$autoplay.'">';
							if($tabs == 1) { //Items Tabs
								$content .= '<ul class="mrwid-tabs mrwid-items">';
								foreach ( $itemlist as $key => $tab) {
									$tabid = $tab->term_id;
									/*
									If there is a pinned item, pin also the tab.
									*/
									if(!$pin) {
										$pinned = '';
									} else if($pin == $tabid) {
										$pinned = ' active';
									} else if($pin != $tabid) {
										$pinned = ' inactive';
									}
									/*
									Get the manual order also for the tabs
									*/
									if ($manualordering[$tabid]) {
										$manualorder = '-ms-flex-order: '.$manualordering[$tabid].'; -webkit-order: '.$manualordering[$tabid].'; order: '.$manualordering[$tabid].';';
									} else {
										$manualorder = '-ms-flex-order: 0; -webkit-order: 0; order: 0;';
									}
									$content .= '<li class="itemid-'.$tabid.' '.$tab->slug.' mr-tab'.$pinned.'" style="'.$manualorder.'">'.$tab->name.'</li>';
								}
								$content .= '</ul>';
							}
							if($tabs == 2) { //Parent Items Tabs
								$content .= '<ul class="mrwid-tabs mrwid-parentitems">';
								foreach ( $itemlist as $key => $tab) {
									$tabid = $tab->term_id;
									if ($manualordering[$tabid]) {
										$manualorder = '-ms-flex-order: '.$manualordering[$tabid].'; -webkit-order: '.$manualordering[$tabid].'; order: '.$manualordering[$tabid].';';
									} else {
										$manualorder = '-ms-flex-order: 0; -webkit-order: 0; order: 0;';
									}
									$content .= '<li class="parentitemid-'.$tabid.' '.$tab->slug.' mr-tab" style="'.$manualorder.'">'.$tab->name.'</li>';
								}
								$content .= '</ul>';
							}
						}
						$itemcount = 0;
						$pagecount = 1;
						foreach ( $itemlist as $key => $item) {
								/*
								Get all needed item values
								*/
								$itemid = $item->term_id;
								$itemslug = $item->slug;
								$itemparent = $item->parent;
								$cattitle = $item->name;
								if($itemstitlemax != 0) {
									$itemtitle = (strlen($cattitle) > $itemstitlemax) ? mb_substr($cattitle,0,$itemstitlemax, 'utf-8').'<span class="mrwid-ellipsis">...</span>' : $cattitle;
								} else {
									$itemtitle = $cattitle;
								}
								if(in_array("artcount", $itemoptions)) {
									$num_articles = $item->count;
								}
								if(!$itemdesc || $itemdesc != 1 || $itemimage && $itemimage == 8) {
									$catdescription = $item->description;
								}
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
												<label ><input type="checkbox" class="mrwid-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemselect' ) ); ?>[]" value="<?php echo $itemid; ?>" <?php checked( (in_array( $itemid, $itemselect ) ) ? $itemid : '', $itemid ); ?>/> <?php echo $itemtitle; ?></label>
										<?php
										echo "</div>";
										$itemcount = ($itemcount + 1);
										/*
										If the option 'only show subcategories of active' is enabled and this item is a subcategory, it should not close the page yet.
										*/
										if(in_array( "subcatactive", $globallayoutoptions ) && $mrsubcat != '' && $mrsubcat != null) {
										} else {
											if($itemcount == $perpage) {
												echo '</div><hr>';
												$itemcount = 0;
												$pagecount = ($pagecount + 1);
											}
										}
								} else {
										/*
										Check if current category was not manually excluded.
										*/
										if($excludeinclude == 0 AND !in_array($itemid, $itemselect) OR $excludeinclude == 1 AND in_array($itemid, $itemselect) ) {
											/*
											Image starts here
											*/
											if(!$itemimage || $itemimage == 0) {
												$showimage = '';
											} else {
												$showimage = '';
												if (!$itemimage || $itemimage == 8) { //Category images
													$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $catdescription, $matches);
													$getimg = $matches[1][0]; //Description first image
												} else if ($itemimage == 2 || $itemimage == 5) { //Post images
													if($itemimage == 2) { //Latest sticky post
														$sticky = get_option( 'sticky_posts' );
														if ( !empty($sticky) ) {
															$posts = get_posts(array('posts_per_page' => 1,'category__in' => $itemid,'post_status' => 'publish','post__in' => $sticky,'ignore_sticky_posts' => 1));
														} else {
															$posts = null;
														}
													} else if($itemimage == 5) { //Latest post
														$posts = get_posts(array('posts_per_page' => 1,'category__in' => $itemid,'post_status' => 'publish'));
													} else {
														$posts = null;
													}
													if($posts) {
														$post = $posts[0]->ID;
														$getimg = get_the_post_thumbnail_url($post);
													} else {
														$getimg = null;
													}
												}
												if($getimg) {
													$showimage = "style='background-image: url(".$getimg.");'";
												}
											}
											/*
											Title starts here
											*/
											if(!$titletag) {
												$titletag = 'h3';
											}
											if($itemstitle == 2) { //Category title
												$showitemtitle = '<'.$titletag.' class="mrwid-title">'.$itemtitle.((in_array("artcount", $itemoptions))?' <small>('.$num_articles.')</small>':"").'</'.$titletag.'>';
											} else if($itemstitle == 1)  { //No title
												$showitemtitle = ''.((in_array("artcount", $itemoptions))?'<'.$titletag.' class="mrwid-title">('.$num_articles.')</'.$titletag.'>':"");
											} else  { //Linked category title
												$showitemtitle = '<'.$titletag.' class="mrwid-title">'.'<a href="'.get_category_link($itemid).'">'.$itemtitle.((in_array("artcount", $itemoptions))?' <small>('.$num_articles.')</small>':"").'</a>'.'</'.$titletag.'>';
											}
											/*
											Description starts here
											*/
											if($itemdesc == 1) { //No description
												$showitemdesc = '';
											} else  { //With description
												$catdescription = do_shortcode($catdescription);
												if($itemdesc == 2) { //Category intro text
													$catdescription = strstr($catdescription, '<!--more-->', true);
													$itemdescription = $catdescription;
												} else if($itemdesc == 3) { //Category full text
													if (strpos($catdescription, '<!--more-->') !== false) {
														$catdescription = explode('<!--more-->', $catdescription)[1];
													}
													$itemdescription = $catdescription;
												} else { //Category description
													$catdescription = explode('<!--more-->', $catdescription)[0];
													$itemdescription = $catdescription;
												}
												if($itemdescmax != 0) {
													$itemdescription = strip_tags($itemdescription);
													$itemdescription = (strlen($itemdescription) > $itemdescmax) ? mb_substr($itemdescription,0,$itemdescmax, 'utf-8').'<span class="mrwid-ellipsis">...</span>' : $itemdescription;
												}
												$showitemdesc = '<div class="mrwid-desc">'.$itemdescription.'</div>';
											}
											/*
											Bottom link starts here
											*/
											if($itemlink == 1) { //No bottom link
												$bottomlinktext="";
											} else { //Category link
												if($bottomlink == "") {
													$bottomlink = "Know more...";
												}
												$bottomlinktext = '<div class="mrwid-link"><a class="'.$bottomlinkclasses.'" href="'.get_category_link($itemid).'" title="'. $cattitle .'">'.$bottomlink.'</a></div>';
											}
											/*
											Check front for active category/link and adds a class if it's the current category/link.
											*/
											if(is_array($currentcat) && in_array($itemid, $currentcat) || str_replace("/./","/",get_category_link($itemid)) == $currentLink) {
												$mrcurrent = 'mrwid-current';
											} else if($currentcat != '' && $currentcat == $itemid) {
												$mrcurrent = 'mrwid-current';
											} else {
												$mrcurrent = '';
											}
											/*
											Add classes for subcategories
											*/
											if($itemparent != 0) {
												$mrsubcat = 'mrwid-subcat parentitemid-'.$itemparent;
											} else {
												$mrsubcat = '';
											}
											/*
											Check if this item should be on a new page.
											*/
											if($itemcount == 0) {
												$content .= '<ul class="'.$perpageclass.' mrwid-page'.$pagecount.' '.$perlineclass.' mrwid-'.$pagetransition.''.($pagecount == 1 ? " active" : " inactive").'">';
												if($pagecount > 1) {
													$content .= '<noscript>';
												}
											}
											$content .= '<li class="itemid-'.$itemid.' '.$itemslug.' '.$mrsubcat.' mr-wid '.$mrcurrent.'" '.((in_array("url", $itemoptions))?'url='.get_category_link($itemid):"").' ><div class="mrwid-container"'.$showimage.'>'.$showitemtitle.'<div class="mrwid-content">'.$showitemdesc.$bottomlinktext.'</div></div></li>';
											$itemcount = ($itemcount + 1);
											/*
											If the option 'only show subcategories of active' is enabled and this item is a subcategory, it should not close the page yet.
											*/
											if(in_array( "subcatactive", $globallayoutoptions ) && $mrsubcat != '' && $mrsubcat != null) {
											} else {
												if($itemcount == $perpage) {
													if($pagecount > 1) {
														$content .= '</noscript>';
													}
													$content .= '</ul>';
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
								if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles ) && !in_array( 1, $pagetoggles ) && !in_array( 2, $pagetoggles ) && !in_array( 3, $pagetoggles ) && !in_array( 4, $pagetoggles ) && !in_array( 5, $pagetoggles ) || in_array( 0, $pagetoggles )) {
									$content .= '<button class="mrwid-arrows mrwid-prev" value="'.$pagecount.'"><span><</span></button>';
								}
								if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles ) && !in_array( 1, $pagetoggles ) && !in_array( 2, $pagetoggles ) && !in_array( 3, $pagetoggles ) && !in_array( 4, $pagetoggles ) && !in_array( 5, $pagetoggles ) || in_array( 0, $pagetoggles )) {
									$content .= '<button class="mrwid-arrows mrwid-next" value="2"><span>></span></button>';
								}
								if( in_array( 3, $pagetoggles ) || in_array( 4, $pagetoggles )) {
									$content .= '<button class="'.((in_array(3, $pagetoggles))?'mrwid-below':"").' '.((in_array(4, $pagetoggles))?'mrwid-scroll':"").'"><span>+</span></button>';
								}
								$content .= '<div class="mrwid-pagination '.((in_array(5, $pagetoggles))?'mrwid-keyboard':"").'">';
									$hideelement = '';
									if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles )) {
										$hideelement = 'style="display:none;"';
									}
									$content .= '<select class="mrwid-pageselect" title="/'.$pagecount.'" '.$hideelement.'>';
									$pagenumber = 0;
									while ($pagenumber++ < $pagecount) {
										$content .= '<option value="'.$pagenumber.'">'.$pagenumber.'</option>';
									}
									$content .= '</select>';
									if( in_array( 2, $pagetoggles )) {
										$pagenumber = 0;
										while ($pagenumber++ < $pagecount) {
											$content .= '<input name="mrwid-radio" title="'.$pagenumber.'/'.$pagecount.'" class="mrwid-radio" type="radio" value="'.$pagenumber.'"'.(($pagenumber==1)?' checked="checked" ':'').'>';
										}
									}
								$content .= '</div>';
							}
							$content .= '</div></div>';
						}
				}
?>
