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
						$perlineclass = " mr-perliner mr-".$perline."perline";
					} else {
						$perlineclass = "";
					}
					/*
					Get the perpage number
					*/
					if($perpage && $perpage != "∞" && $perpage > 0) {
						$perpageclass = "mr-pages mr-".$perpage."perpage";
					} else {
						$perpageclass = "mr-pages";
					}
					/*
					Get the autoplay seconds
					*/
					if($autoplay && $autoplay != "∞" && $autoplay > 0) {
						$autoplay = ' mr-autoplay'.$autoplay."s mr-transitionright";
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
				if(!$orderby) { //Creation
					$orderby = 'term_id';
				} else if($orderby == 1) { //Title
					$orderby = 'name';
				} else if($orderby == 3) { //Article count
					$orderby = 'count';
				} else if($orderby == 4) { //Slug
					$orderby = 'slug';
				} else { //Parent (2)
					$orderby = 'parent';
				}
				if(!$order) { //Descending
					$order = 'DESC';
				} else { //Ascending
					$order = 'ASC';
				}
				/*
				Join all the previous options for the main array of categories.
				*/
				if (!$contenttypes || $contenttypes == 'category') {
					$itemlist = get_terms(array('taxonomy' => 'category','orderby' => $orderby,'order' => $order,'hide_empty' => false, 'lang' => $lang, 'hierarchical' => true));
				} else {
					$itemlist = get_posts(array('post_type' => 'post','numberposts'=> -1,'post_status'=>'publish','orderby' => $orderby,'order' => $order));
				}

				if ( ! empty( $itemlist ) ) {
						/*
						Start the content with the container for the categories.
						The theme name, the layout name and the global options are imploded in here has classes.
						*/
						if(is_admin()) {
						} else {
							$content .= '<div class="yngdev-widget mr-'.$contenttypes.' mr-theme mr-'.strtolower($theme).'"><div class="mr-layout mr-'.strtolower($layout).(($layoutoptions)?' mr-'.implode(" mr-", $layoutoptions):" ").(($globallayoutoptions)?' mr-'.implode(" mr-", $globallayoutoptions):" ").(($itemoptions)?' mr-'.implode(" mr-", $itemoptions):" ").(($tabsposition != 'tabstop')?' mr-'.$tabsposition:" ").$autoplay.'">';
							if($tabs == 1) { //Items Tabs
								$content .= '<ul class="mr-tabs mr-items">';
								foreach ( $itemlist as $key => $tab) {
									if (!$contenttypes || $contenttypes == 'category') {
										$tabid = $tab->term_id;
										$tabname = $tab->name;
										$tabslug = $tab->slug;
									} else {
										$tabid = $tab->ID;
										$tabname = $tab->post_title;
										$tabslug = $tab->post_name;
									}
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
									$content .= '<li class="itemid-'.$tabid.' '.$tabslug.' mr-tab">'.$tabname.'</li>';
								}
								$content .= '</ul>';
							}
							if($tabs == 2) { //Parent Items Tabs
								$content .= '<ul class="mr-tabs mr-parentitems">';
								foreach ( $itemlist as $key => $tab) {
									if (!$contenttypes || $contenttypes == 'category') {
										$tabid = $tab->term_id;
										$tabname = $tab->name;
										$tabslug = $tab->slug;
									} else {
										$tabid = $tab->ID;
										$tabname = $tab->post_title;
										$tabslug = $tab->post_name;
									}
									$content .= '<li class="parentitemid-'.$tabid.' '.$tabslug.' mr-tab">'.$tabname.'</li>';
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
								//print_r($item);
								if (!$contenttypes || $contenttypes == 'category') {
									$itemid = $item->term_id;
									$itemslug = $item->slug;
									$itemtitle = $item->name;
									$itemparent = $item->parent;
									$itemcats = array($itemparent);
								} else {
									$itemid = $item->ID;
									$itemslug = $item->post_name;
									$itemtitle = $item->post_title;
									$itemparent = $item->post_parent;
									$itemcats = wp_get_post_categories($itemid);
									if($itemcats && !is_array($itemcats)) {
										$itemcats = array($itemcats);
									} else if(!$itemcats) {
										$itemcats = array();
									}
									$itemtags = wp_get_post_tags($itemid, array('fields' => 'ids'));
									if($itemtags && !is_array($itemtags)) {
										$itemtags = array($itemtags);
									} else if(!$itemtags) {
										$itemtags = array();
									}
								}
								if(in_array("artcount", $itemoptions)) {
									$num_articles = $item->count;
								}
								if(!$itemdesc || $itemdesc != 1 || $itemimage && $itemimage == 8) {
									if (!$contenttypes || $contenttypes == 'category') {
										$itemdescription = $item->description;
									} else {
										$itemdescription = $item->post_content;
									}
								}
								if($itemlink != 1 || $itemstitle != 2 && $itemstitle != 1  ) {
									if (!$contenttypes || $contenttypes == 'category') {
										$itemurl = str_replace("/./","/",get_category_link($itemid));
									} else {
										$itemurl = str_replace("/./","/",get_permalink($itemid));
									}
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
											echo '<div class="mr-list-container">';
										}
										echo '<div class="mr-childs">';
										?>
												<label ><input type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemselect' ) ); ?>[]" value="<?php echo $itemid; ?>" <?php checked( (in_array( $itemid, $itemselect ) ) ? $itemid : '', $itemid ); ?>/> <?php echo $itemtitle; ?></label>
										<?php
										echo "</div>";
										$itemcount = ($itemcount + 1);
										/*
										If the option 'only show subcategories of active' is enabled and this item is a subcategory, it should not close the page yet.
										*/
										if(in_array( "subcatactive", $globallayoutoptions ) && $mrclasses != '' && $mrclasses != null) {
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
												if (!$itemimage || $itemimage == 1) { //Item image
													if (!$contenttypes || $contenttypes == 'category') {
														$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $itemdescription, $matches);
														$getimg = $matches[1][0];
													} else {
														$getimg = get_the_post_thumbnail_url($itemid);
													}
												} else if ($itemimage == 8) { //Description first image
													$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $itemdescription, $matches);
													$getimg = $matches[1][0];
												} else if ($itemimage == 2 || $itemimage == 5) { //Post images
													if($itemimage == 2) { //Latest sticky post
														$sticky = get_option( 'sticky_posts' );
														if ( !empty($sticky) ) {
															if (!$contenttypes || $contenttypes == 'category') {
																$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemid,'post_status' => 'publish','post__in' => $sticky,'ignore_sticky_posts' => 1));
															} else {
																$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemcats[0],'post_status' => 'publish','post__in' => $sticky,'ignore_sticky_posts' => 1));
															}
														} else {
															$posts = null;
														}
													} else if($itemimage == 5) { //Latest post
														if (!$contenttypes || $contenttypes == 'category') {
															$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemid,'post_status' => 'publish'));
														} else {
															$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemcats[0],'post_status' => 'publish'));
														}
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
													$showimage = "><figure class='mr-image' style='background-image: url(".$getimg.");'></figure";
												}
											}
											/*
											Title starts here
											*/
											if(!$titletag) {
												$titletag = 'h3';
											}
											if($itemstitle == 2) { //Item title
												$showitemtitle = '<'.$titletag.' class="mr-title">'.$itemtitle.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?' <small>('.$num_articles.')</small>':"").'</'.$titletag.'>';
											} else if($itemstitle == 1)  { //No title
												$showitemtitle = ''.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?'<'.$titletag.' class="mr-title">('.$num_articles.')</'.$titletag.'>':"");
											} else  { //Linked item title
												$showitemtitle = '<'.$titletag.' class="mr-title">'.'<a href="'.$itemurl.'">'.$itemtitle.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?' <small>('.$num_articles.')</small>':"").'</a>'.'</'.$titletag.'>';
											}
											/*
											Description starts here
											*/
											if($itemdesc == 1) { //No description
												$showitemdesc = '';
											} else  { //With description
												if($itemdesc == 4) { //Item excerpt
													if (strpos($contenttypes, 'posttype_') !== false && has_excerpt($itemid)) {
														$itemdescription = get_the_excerpt($itemid);
													} else {
														$itemdescription = strstr($itemdescription, '<!--more-->', true);
													}
												} else if($itemdesc == 2) { //Item intro text
													$itemdescription = strstr($itemdescription, '<!--more-->', true);
												} else if($itemdesc == 3) { //Item full text
													if (strpos($itemdescription, '<!--more-->') !== false) {
														$itemdescription = explode('<!--more-->', $itemdescription)[1];
													}
												} else { //Item description
													if (strpos($contenttypes, 'posttype_') !== false && has_excerpt($itemid)) {
														$itemexcerpt = get_the_excerpt($itemid);
													} else {
														$itemexcerpt = strstr($itemdescription, '<!--more-->', true);
													}
													if(!$itemexcerpt) {
														$itemdescription = explode('<!--more-->', $itemdescription)[0];
													} else {
														$itemdescription = $itemexcerpt;
													}
												}
												if($itemdescmax != 0) {
													$itemdescription = strip_tags($itemdescription);
													$itemdescription = (strlen($itemdescription) > $itemdescmax) ? mb_substr($itemdescription,0,$itemdescmax, 'utf-8').'<span class="mr-ellipsis">...</span>' : $itemdescription;
												}
												$showitemdesc = '<div class="mr-desc">'.do_shortcode($itemdescription).'</div>';
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
												$bottomlinktext = '<div class="mr-link"><a class="'.$bottomlinkclasses.'" href="'.$itemurl.'" title="'. $cattitle .'">'.$bottomlink.'</a></div>';
											}
											/*
											Check front for active category/link and adds a class if it's the current category/link.
											*/
											if(is_array($currentcat) && in_array($itemid, $currentcat) || $itemurl == $currentLink) {
												$mrcurrent = 'mr-current';
											} else if($currentcat != '' && $currentcat == $itemid) {
												$mrcurrent = 'mr-current';
											} else {
												$mrcurrent = '';
											}
											/*
											Add classes for subitems
											*/
											if($itemparent) {
												$mrclasses = 'mr-subitem parentitemid-'.$itemparent;
											} else {
												$mrclasses = '';
											}
											/*
											Add classes for categories
											*/
											if (!$contenttypes || $contenttypes == 'category') {
												if($itemcats) {
													$mrclasses .= ' catid-'.$item->term_id;
												} else {
													$mrclasses .= '';
												}
											} else {
												if($itemcats) {
													$mrclasses .= ' catid-'.implode(" catid-",$itemcats);
												} else {
													$mrclasses .= '';
												}
											}
											/*
											Check if this item should be on a new page.
											*/
											if($itemcount == 0) {
												$content .= '<ul class="'.$perpageclass.' mr-page'.$pagecount.' '.$perlineclass.' mr-'.$pagetransition.''.($pagecount == 1 ? " active" : " inactive").'">';
												if($pagecount > 1) {
													$content .= '<noscript>';
												}
											}
											$content .= '<li class="itemid-'.$itemid.' '.$itemslug.' '.$mrclasses.' mr-item '.$mrcurrent.'" '.((in_array("url", $itemoptions))?'url='.$itemurl:"").' ><div class="mr-container"'.$showimage.'>'.$showitemtitle.'<div class="mr-content">'.$showitemdesc.$bottomlinktext.'</div></div></li>';
											$itemcount = ($itemcount + 1);
											/*
											If the option 'only show subcategories of active' is enabled and this item is a subcategory, it should not close the page yet.
											*/
											if(in_array( "subcatactive", $globallayoutoptions ) && $mrclasses != '' && $mrclasses != null) {
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
								if( in_array( 0, $pagetoggles )) {
									$content .= '<button class="mr-arrows mr-prev" value="'.$pagecount.'"><span><</span></button>';
								}
								if( in_array( 0, $pagetoggles )) {
									$content .= '<button class="mr-arrows mr-next" value="2"><span>></span></button>';
								}
								if( in_array( 3, $pagetoggles ) || in_array( 4, $pagetoggles )) {
									$content .= '<button class="'.((in_array(3, $pagetoggles))?'mr-below':"").' '.((in_array(4, $pagetoggles))?'mr-scroll':"").'"><span>+</span></button>';
								}
								$content .= '<div class="mr-pagination '.((in_array(5, $pagetoggles))?'mr-keyboard':"").'">';
									$hideelement = '';
									if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles )) {
										$hideelement = 'style="display:none;"';
									}
									$content .= '<select class="mr-pageselect" title="/'.$pagecount.'" '.$hideelement.'>';
									$pagenumber = 0;
									while ($pagenumber++ < $pagecount) {
										$content .= '<option value="'.$pagenumber.'">'.$pagenumber.'</option>';
									}
									$content .= '</select>';
									if( in_array( 2, $pagetoggles )) {
										$pagenumber = 0;
										while ($pagenumber++ < $pagecount) {
											$content .= '<input name="mr-radio" title="'.$pagenumber.'/'.$pagecount.'" class="mr-radio" type="radio" value="'.$pagenumber.'"'.(($pagenumber==1)?' checked="checked" ':'').'>';
										}
									}
								$content .= '</div>';
							}
							$content .= '</div></div>';
						}
				}
?>