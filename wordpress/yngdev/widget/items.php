<?php
defined('ABSPATH') or die;
				$lang = '';
				$is_admin = is_admin();
				if($is_admin === false) {
					$currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					/*
					In front when using multilang, only show categories of the current language.
					*/
					$lang =	get_locale();
					/*
					Main title depends of the selected display option.
					Widget title is the default.
					*/
					if($maintitle === 3) { // Theme and layout title
						echo $args['before_title'] . $theme . ' - ' . $layout . $args['after_title'];
					} else if($maintitle === 4) { // Theme title
						echo $args['before_title'] . $theme . $args['after_title'];
					} else if($maintitle === 5) { // Layout title
						echo $args['before_title'] . $layout . $args['after_title'];
					} else if($maintitle === 6) { // No main title
					} else { // Widget title
						if ( ! empty( $title ) ) {
							echo $args['before_title'] . $title . $args['after_title'];
						}
					}
					/*
					Get the perline number
					*/
					if($perline === 0) {
						$perlineclass = " mr-flex";
					} else if($perline > 0) {
						$perlineclass = " mr-".$perline."perline";
					}
					/*
					Get the perpage number
					*/
					if($perpage === 0) {
						$perpageclass = "mr-pages mr-nobullets";
					} else if($perpage > 0) {
						$perpageclass = "mr-pages mr-".$perpage."perpage mr-nobullets";
					}
					/*
					Get the autoplay seconds
					*/
					if($autoplay === 0) {
						$autoplayclass = '';
					} else if($autoplay > 0) {
						$autoplayclass = ' mr-autoplay'.$autoplay."s mr-transitionright";
					}
				}
				/*
				Get current active categories.
				*/
				$currentitem = '';
				if (is_category()) {
					$currentitem = get_query_var('cat');
				} else if(is_single()) {
					$currentitem = wp_get_post_categories(get_the_ID());
				}
				/*
				Get the selected automatic order.
				*/
				if($orderby === 0) { //Creation
					$orderby = 'term_id';
				} else if($orderby === 1) { //Title
					$orderby = 'name';
				} else if($orderby === 3) { //Article count
					$orderby = 'count';
				} else if($orderby === 4) { //Slug
					$orderby = 'slug';
				} else if($orderby === 2) { //Parent (2)
					$orderby = 'parent';
				}
				if($order === 1) {
					$order = 'ASC';
				} else if($order === 0) {
					$order = 'DESC';
				}
				/*
				Join all the previous options for the main array of categories.
				*/
				if ($contenttypes === 'category') {
					$itemlist = get_terms(array('taxonomy' => 'category','orderby' => $orderby,'order' => $order,'hide_empty' => false, 'lang' => $lang, 'hierarchical' => true,'no_found_rows' => true, 'suppress_filter' => false));
				} else if ($contenttypes === 'post') {
					$itemlist = get_posts(array('post_type' => 'post','numberposts'=> -1,'post_status'=>'publish','orderby' => $orderby,'order' => $order, 'no_found_rows' => true, 'suppress_filters' => false));
				}

				if ( !empty( $itemlist ) ) {
						/*
						Start the content with the container for the categories.
						The theme name, the layout name and the global options are imploded in here has classes.
						*/
						if($is_admin === false) {
							$content .= '<div class="yngdev-widget mr-'.$contenttypes.' mr-theme mr-'.strtolower($theme).' mr-boxsize"><div class="mr-layout mr-'.strtolower($layout).(($layoutoptions)?' mr-'.implode(" mr-", $layoutoptions):" ").(($globallayoutoptions)?' mr-'.implode(" mr-", $globallayoutoptions):" ").(($itemoptions)?' mr-'.implode(" mr-", $itemoptions):" ").(($tabsposition != 'tabstop')?' mr-'.$tabsposition:" ").$autoplayclass.' mr-noscroll">';
							if($tabs === 1) { //Items Tabs
								$content .= '<ul class="mr-tabs mr-items mr-flex mr-scroll mr-nobullets">';
								foreach ( $itemlist as $key => $tab) {
									if ($contenttypes === 'category') {
										$tabid = $tab->term_id;
										$tabname = $tab->name;
										$tabslug = $tab->slug;
									} else if ($contenttypes === 'post') {
										$tabid = $tab->ID;
										$tabname = $tab->post_title;
										$tabslug = $tab->post_name;
									}
									/*
									Get the manual order also for the tabs
									*/
									$content .= '<li class="itemid-'.$tabid.' '.$tabslug.' mr-tab">'.$tabname.'</li>';
								}
								$content .= '</ul>';
							}
							if($tabs === 2) { //Parent Items Tabs
								$content .= '<ul class="mr-tabs mr-parentitems mr-flex mr-scroll mr-nobullets">';
								foreach ( $itemlist as $key => $tab) {
									if ($contenttypes === 'category') {
										$tabid = $tab->term_id;
										$tabname = $tab->name;
										$tabslug = $tab->slug;
									} else if ($contenttypes === 'post') {
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
								if ($contenttypes === 'category') {
									$itemid = $item->term_id;
									$itemslug = $item->slug;
									$itemtitle = $item->name;
									$itemparent = $item->parent;
									$itemcats = array($itemparent);
									$itemurl = str_replace("/./","/",get_category_link($itemid));
								} else if ($contenttypes === 'post') {
									$itemid = $item->ID;
									$itemslug = $item->post_name;
									$itemtitle = $item->post_title;
									$itemparent = $item->post_parent;
									$itemcats = array();
									$itemtags = array();
									$itemurl = str_replace("/./","/",get_permalink($itemid));
								}
								if($itemstitlemax != 0) {
									$itemtitle = (strlen($itemtitle) > $itemstitlemax) ? mb_substr($itemtitle,0,$itemstitlemax, 'utf-8').'<span class="mr-ellipsis">...</span>' : $itemtitle;
								}
								if(in_array("artcount", $itemoptions)) {
									$num_articles = $item->count;
								}
								if($itemdesc != 1 || $itemimage === 8) {
									if ($contenttypes === 'category') {
										$itemdescription = $item->description;
									} else {
										$itemdescription = $item->post_content;
									}
								}
								/*
								Add the content of the current category in the container.
								The layout options are imploded in here has classes.
								*/
								if($is_admin === true) {
										/*
										Check if this item should be on a new page.
										*/
										if($itemcount === 0) {
											echo '<div class="mr-list-container">';
										}
										echo '<div class="mr-childs">';
										?>
												<label ><input type="checkbox" class="mr-checkbox" name="<?php echo esc_attr( $this->get_field_name( 'itemselect' ) ); ?>[]" value="<?php echo $itemid; ?>" <?php checked( (in_array( $itemid, $itemselect ) ) ? $itemid : '', $itemid ); ?>/> <?php echo $itemtitle; ?></label>
										<?php
										echo "</div>";
										$itemcount = ($itemcount + 1);
										/*
										If the option 'only show subitems of active' is enabled and this item is a subitem, it should not close the page yet.
										*/
										if(in_array( "subitemactive", $globallayoutoptions ) && $itemparent > 0) { } else {
											if($itemcount === $perpage) {
												echo '</div><hr>';
												$itemcount = 0;
												$pagecount = ($pagecount + 1);
											}
										}
								} else {
										/*
										Check if current item was not manually excluded.
										*/
										if($excludeinclude === 0 AND !in_array($itemid, $itemselect) OR $excludeinclude === 1 AND in_array($itemid, $itemselect) ) {
											/*
											Image starts here
											*/
											if($itemimage === 9) {
												$showimage = '';
											} else {
												$showimage = '';
												if ($itemimage === 1) { //Item image
													if ($contenttypes === 'post') {
														$getimg = get_the_post_thumbnail_url($itemid);
													} else if ($contenttypes === 'category') {
														$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $itemdescription, $matches);
														if( !empty($output) ) {
															$getimg = $matches[1][0];
														} else {
															unset($getimg);
														}
													}
												} else if ($itemimage === 8) { //Description first image
													$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $itemdescription, $matches);
													if( !empty($output) ) {
														$getimg = $matches[1][0];
													} else {
														unset($getimg);
													}
												} else if ($itemimage === 2 || $itemimage === 5) { //Post images
													if($itemimage === 2) { //Latest sticky post
														$sticky = get_option( 'sticky_posts' );
														if ( !empty($sticky) ) {
															if ($contenttypes === 'category') {
																$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemid,'post_status' => 'publish','post__in' => $sticky,'ignore_sticky_posts' => 1, 'no_found_rows' => true, 'suppress_filters' => false));
															} else if ($contenttypes === 'post') {
																$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemcats[0],'post_status' => 'publish','post__in' => $sticky,'ignore_sticky_posts' => 1, 'no_found_rows' => true, 'suppress_filters' => false));
															}
														} else {
															unset($posts);
														}
													} else if($itemimage === 5) { //Latest post
														if ($contenttypes === 'category') {
															$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemid,'post_status' => 'publish', 'no_found_rows' => true, 'suppress_filters' => false));
														} else if ($contenttypes === 'post') {
															$posts = get_posts(array('posts_per_page' => 1,'orderby' => 'date','order'=>'DESC','category__in' => $itemcats[0],'post_status' => 'publish', 'no_found_rows' => true, 'suppress_filters' => false));
														}
													} else {
														unset($posts);
													}
													if(isset($posts)) {
														$getimg = get_the_post_thumbnail_url($posts[0]->ID);
													} else {
														unset($getimg);
													}
												}
												if(!empty($getimg)) {
													$showimage = "><figure class='mr-image' style='background-image: url(".esc_url($getimg).");'></figure";
												}
											}
											/*
											Title starts here
											*/
											$titletag = 'h3';
											if($itemstitle === 2) { //Item title
												$showitemtitle = '<'.$titletag.' class="mr-title">'.$itemtitle.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?' <small>('.$num_articles.')</small>':"").'</'.$titletag.'>';
											} else if($itemstitle === 1)  { //No title
												$showitemtitle = ''.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?'<'.$titletag.' class="mr-title">('.$num_articles.')</'.$titletag.'>':"");
											} else { //Linked item title
												$showitemtitle = '<'.$titletag.' class="mr-title">'.'<a href="'.esc_url($itemurl).'">'.$itemtitle.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?' <small>('.$num_articles.')</small>':"").'</a>'.'</'.$titletag.'>';
											}
											/*
											Description starts here
											*/
											if($itemdesc === 1) { //No description
												$showitemdesc = '';
											} else  { //With description
												if($itemdesc === 4) { //Item excerpt
													if ($contenttypes === 'post' && has_excerpt($itemid)) {
														$itemdescription = get_the_excerpt($itemid);
													} else {
														$itemdescription = strstr($itemdescription, '<!--more-->', true);
													}
												} else if($itemdesc === 2) { //Item intro text
													$itemdescription = strstr($itemdescription, '<!--more-->', true);
												} else if($itemdesc === 3) { //Item full text
													if (strpos($itemdescription, '<!--more-->') !== false) {
														$itemdescription = explode('<!--more-->', $itemdescription)[1];
													}
												} else if($itemdesc === 0) { //Item description
													if ($contenttypes === 'post' && has_excerpt($itemid)) {
														$itemexcerpt = get_the_excerpt($itemid);
													} else {
														$itemexcerpt = strstr($itemdescription, '<!--more-->', true);
													}
													if($itemexcerpt) {
														$itemdescription = $itemexcerpt;
													} else {
														$itemdescription = explode('<!--more-->', $itemdescription)[0];
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
											if($itemlink === 1) { //No bottom link
												$bottomlinktext="";
											} else { //Item link
												$bottomlinktext = '<div class="mr-link"><a href="'.esc_url($itemurl).'" title="'. strip_tags($itemtitle) .'">'.$bottomlink.'</a></div>';
											}
											/*
											Check front for active item/link and adds a class if it's the current item/link.
											*/
											if(is_array($currentitem) && in_array($itemid, $currentitem) || $itemurl === $currentLink) {
												$mrcurrent = 'mr-current';
											} else if($currentitem != '' && $currentitem === $itemid) {
												$mrcurrent = 'mr-current';
											} else {
												$mrcurrent = '';
											}
											$mrclasses = '';
											/*
											Add classes for subitems
											*/
											if($itemparent === 0) {
												$mrclasses .= '';
											} else {
												$mrclasses .= 'mr-subitem parentitemid-'.$itemparent;
												/*
												If the option 'only show subitems of active' is enabled and this item is a subitem, it should be initially hidden.
												*/
												if(in_array( "subitemactive", $globallayoutoptions ) || in_array( "subitemactive", $layoutoptions )) {
													$mrclasses .= ' mr-hidden';
												}
											}
											/*
											Check if this item should be on a new page.
											*/
											if($itemcount === 0) {
												$content .= '<ul class="'.$perpageclass.' mr-page'.$pagecount.' '.$perlineclass.' mr-'.$pagetransition.''.($pagecount === 1 ? " active" : " inactive").'">';
												if($pagecount > 1) {
													$content .= '<noscript>';
												}
											}
											$content .= '<li class="itemid-'.$itemid.' '.$itemslug.' '.$mrclasses.' mr-item '.$mrcurrent.'" '.((in_array("url", $itemoptions))?'url='.$itemurl:"").' ><div class="mr-container"'.$showimage.'>'.$showitemtitle.'<div class="mr-content">'.$showitemdesc.$bottomlinktext.'</div></div></li>';
											$itemcount = ($itemcount + 1);
											/*
											If the option 'only show subitems of active' is enabled and this item is a subitem, it should not close the page yet.
											*/
											if(in_array( "subitemactive", $globallayoutoptions ) && $itemparent > 0) {
											} else {
												if($itemcount === $perpage) {
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
						Doublecheck if the last page was closed in case the last item was a hidden subitem.
						*/
						if($itemcount != 0) {
							if($is_admin) {
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
						if($is_admin === false) {
							if($pagecount > 1) {
								if( in_array( 0, $pagetoggles ) || empty($pagetoggles) && $autoplay === 0) {
									$content .= '<button class="mr-arrows mr-prev" value="'.$pagecount.'"><span><</span></button>';
								}
								if( in_array( 0, $pagetoggles ) || empty($pagetoggles) && $autoplay === 0) {
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