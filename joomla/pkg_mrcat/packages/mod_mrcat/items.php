<?php
defined('_JEXEC') or die;
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
			    $lang = '';
				$currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					/*
					Title starts here
					*/
					if(!$titletag) {
						$titletag = 'h3';
					}
					/*
					Main title depends of the selected display option.
					Widget title is the default.
					*/
					if($maintitle == 3) { //Theme and layout title
						echo  '<'.$titletag.'>'.$theme . ' - ' . $layout.'</'.$titletag.'>';
					} else if($maintitle == 4) { //Theme title
						echo  '<'.$titletag.'>'.$theme.'</'.$titletag.'>';
					} else if($maintitle == 5) { //Layout title
						echo  '<'.$titletag.'>'.$layout.'</'.$titletag.'>';
					} else {
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
				Get current active categories.
				*/
				$currentcat = '';
				if(!$excludeinclude) {
					$excludeinclude == 'Exclude';
				}
				/*
				If no Category is selected with Exclude Option, auto select 'Root'
				*/
				if($excludeinclude == 'Exclude' && !$catexclude || $excludeinclude == 'Exclude' && empty($catexclude)) {
					$catexclude = '';
				}
				/*
				Get the selected automatic order.
				*/
				if(!$orderby || $orderby == 0) { //Parent
					$orderby = 'parent_id';
				} else if($orderby == 1) { //Name/Title
					$orderby = 'title';
				} else if($orderby == 3) { //Article Count
					$orderby = 'count';
				} else if($orderby == 4) { //Slug/Alias
					$orderby = 'alias';
				} else if($orderby == 2) { //Creation
					$orderby = 'id';
				} else if($orderby == 5) { //Ordering
					$orderby = 'rgt';
				} else if($orderby == 6) { //Level
					$orderby = 'level';
				} else { //Parent
					$orderby = 'parent_id';
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
				$query->select('*')->from($db->quoteName('#__categories'));
				$query->order($orderby.' '.$order);
				$db->setQuery($query);
                $categories = $db->loadObjectList();
				 $catlist = $categories;
				/* Get extra classes to give to the main container */
				if ( ! empty( $catlist ) ) {
						/*
						Start the content with the container for the categories.
						The theme name, the layout name and the global options are imploded in here has classes.
						*/
						echo  '<div class="mr-widget mrwid-theme mrwid-'.strtolower($theme).'"><div class="mrwid-layout mrwid-'.strtolower($layout).' mrwid-'.implode(" mrwid-", $layoutoptions).' mrwid-'.implode(" mrwid-", $globallayoutoptions).' mrwid-'.implode(" mrwid-", $catoptions).'">';
						$itemcount = 0;
						$pagecount = 1;
						foreach ( $catlist as $key => $item) {
								/*
								Add the content of the current category in the container.
								The layout options are imploded in here has classes.
								*/
									/*
									For now only display categories from the extension com_content
									*/
									if($item->extension == 'com_content') {
										/*
										Check if current category was not manually excluded.
										*/
										if($excludeinclude == 'Exclude' AND is_array( $catexclude ) AND !in_array($item->id, $catexclude) OR $excludeinclude == 'Include' AND is_array( $catexclude ) AND in_array($item->id, $catexclude) ) {
											$showcattitle = '<'.$titletag.' class="mrwid-title">'.$item->title.'</'.$titletag.'>';
											$model = JModelLegacy::getInstance('Articles', 'ContentModel');
											$model->setState('filter.category_id', $item->id);
											$articles = $model->getItems();
											$num_articles = count($articles);
											if($cattitle == 2) { //Category title
												$showcattitle = '<'.$titletag.' class="mrwid-title">'.$item->title.((is_array( $catoptions ) AND  in_array("artcount", $catoptions))?' <small>('. $num_articles .')</small>':"").'</'.$titletag.'>';
											} else if($cattitle == 0)  { //No title
												$showcattitle = ''.((is_array($catoptions) AND in_array("artcount", $catoptions))?'<'.$titletag.' class="mrwid-title">('. $num_articles .')</'.$titletag.'>':"");
											} else  {
												$showcattitle = '<'.$titletag.' class="mrwid-title">'.'<a href="'.$itemLink.'">'.$item->title.((is_array($catoptions) AND in_array("artcount", $catoptions))?' <small>('. $num_articles .')</small>':"").'</a>'.'</'.$titletag.'>';
											}
											/*
											Description starts here
											*/
											if($catdesc == "No description") {
												$showcatdesc = '';
											} else  {
												$showcatdesc = '<div class="mrwid-desc">'. $item->description.'</div>';
											}
											/*
											Bottom link starts here
											*/
											$itemLink = JURI::root().$item->path;
											if($catlink == "No bottom link") {
												$bottomlinktext="";
											} else {
												if($bottomlink == "") {
													$bottomlink = "Know more...";
												}
												$bottomlinktext = '<div class="mrwid-link"><a class="'.$bottomlinkclasses.'" href="'.$itemLink.'" title="'. $item->title .'">'.$bottomlink.'</a></div>';
											}
											/*
											Check front for active category/link and adds a class if it's the current category/link.
											*/
											if(is_array($currentcat) && in_array($item->id, $currentcat) || str_replace("/./","/",$itemLink) == $currentLink) {
												$mrcurrent = 'mrwid-current';
											} else if($currentcat != '' && $currentcat == $item->id) {
												$mrcurrent = 'mrwid-current';
											} else {
												$mrcurrent = '';
											}
											/*
											Add classes for subcategories
											*/
											if(is_array($maincat) && !in_array($item->parent_id,$maincat) && $item->parent_id != 1 && $item->parent_id != 0 || !is_array($maincat) && $item->parent_id != $maincat && $item->parent_id != 1 && $item->parent_id != 0) {
												$mrsubcat = 'mrwid-subcat parentcatid-'.$item->parent_id;
											} else {
												$mrsubcat = '';
											}
											/*
											Check if this item should be on a new page.
											*/
											if($itemcount == 0) {
												echo  '<ul class="'.$perpageclass.' mrwid-page'.$pagecount.' '.$perlineclass.'">';
												if($pagecount > 1) {
													echo  '<noscript>';
												}
											}
											echo  '<li class="catid-'.$item->id.' '.$item->alias.' '.$mrsubcat.' mr-wid '.$mrcurrent.'" '.((is_array($catoptions) AND in_array("url", $catoptions))?'url='.$itemLink:"").'><div class="mrwid-container">'.$showcattitle.'<div class="mrwid-content">'.$showcatdesc.$bottomlinktext.'</div></div></li>';
											$itemcount = ($itemcount + 1);
											/*
											If the option 'only show subcategories of active' is enabled and this item is a subcategory, it should not close the page yet.
											*/
											if(is_array($globallayoutoptions) && in_array( "subcatactive", $globallayoutoptions ) && $mrsubcat != '' && $mrsubcat || !is_array($globallayoutoptions ) && $globallayoutoptions == "subcatactive" && $mrsubcat != '' && $mrsubcat) {
											} else {
												if($itemcount == $perpage) {
														if($pagecount > 1) {
															echo  '</noscript>';
														}
														echo  '</ul>';
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
							if($pagecount > 1) {
								echo  '</noscript>';
							}
							echo  '</ul>';
							$itemcount = 0;
							$pagecount = ($pagecount + 1);
						}
						$pagecount = ($pagecount - 1);
							if($pagecount > 1) {
								if( is_array( $pagetoggles ) && in_array( 'arrows', $pagetoggles ) || !is_array( $pagetoggles ) && $pagetoggles == 'arrows') {
									echo  '<button class="mrwid-arrows mrwid-prev" value="'.$pagecount.'"><span><</span></button>';
								}
								if( is_array( $pagetoggles ) && in_array( 'below', $pagetoggles ) || !is_array( $pagetoggles ) && $pagetoggles == 'below' || is_array( $pagetoggles ) && in_array( 'scroll', $pagetoggles ) || !is_array( $pagetoggles ) && $pagetoggles == 'scroll' ) {
									echo  '<button class="'.((is_array($pagetoggles) AND in_array("below", $pagetoggles))?'mrwid-below':"").' '.((is_array($pagetoggles) AND in_array("scroll", $pagetoggles))?'mrwid-scroll':"").'"><span>+</span></button>';
								}
								if( is_array( $pagetoggles ) && in_array( 'arrows', $pagetoggles ) || !is_array( $pagetoggles ) && $pagetoggles == 'arrows') {
									echo  '<button class="mrwid-arrows mrwid-next" value="2"><span>></span></button>';
								}
								echo  '<div class="mrwid-pagination mrwid-'.$pagetransition.'">';
									$hideelement = '';
									if( is_array( $pagetoggles ) && !in_array( 'pageselect', $pagetoggles ) || !is_array( $pagetoggles ) && $pagetoggles != 'pageselect') {
										$hideelement = 'style="display:none;"';
									}
									echo  '<select class="mrwid-pageselect" title="/'.$pagecount.'" '.$hideelement.'>';
									$pagenumber = 0;
									while ($pagenumber++ < $pagecount) {
										echo  '<option value="'.$pagenumber.'">'.$pagenumber.'</option>';
									}
									echo  '</select>';
									if( is_array( $pagetoggles ) && in_array( 'radio', $pagetoggles ) || !is_array( $pagetoggles ) && $pagetoggles == 'radio') {
										$pagenumber = 0;
										while ($pagenumber++ < $pagecount) {
											echo  '<input class="mrwid-radio" type="radio" name="mrwid-radio" value="'.$pagenumber.'" title="'.$pagenumber.'/'.$pagecount.'">';
										}
									}
								echo  '</div>';
							}
							echo  '</div></div>';
				}
		
?>
