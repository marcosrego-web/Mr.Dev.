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
					$excludeinclude == 0;
				}
				/*
				If no Category is selected with Exclude Option, auto select 'Root'
				*/
				if($excludeinclude == 0 && !$itemselect || $excludeinclude == 0 && empty($itemselect)) {
					$itemselect = '';
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
				 $itemlist = $categories;
				/* Get extra classes to give to the main container */
				if ( ! empty( $itemlist ) ) {
						/*
						Start the content with the container for the categories.
						The theme name, the layout name and the global options are imploded in here has classes.
						*/
						echo  '<div class="mr-widget mr-categories mrwid-theme mrwid-'.strtolower($theme).'"><div class="mrwid-layout mrwid-'.strtolower($layout).' mrwid-'.implode(" mrwid-", $layoutoptions).' mrwid-'.implode(" mrwid-", $globallayoutoptions).' mrwid-'.implode(" mrwid-", $itemoptions).'">';
						$itemcount = 0;
						$pagecount = 1;
						foreach ( $itemlist as $key => $item) {
								/*
								Get all needed item values
								*/
								$itemid = $item->id;
								$itemslug = $item->alias;
								$itemparent = $item->parent_id;
								if(in_array("artcount", $itemoptions)) {
									$model = JModelLegacy::getInstance('Articles', 'ContentModel');
									$model->setState('filter.category_id', $itemid);
									$articles = $model->getItems();
									$num_articles = count($articles);
								}
								$itemtitle = $item->title;
								if(!$itemdesc || $itemdesc == 0 || $itemimage && $itemimage == 8) {
									$itemdescription = $item->description;
								}
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
										if($excludeinclude == 0 AND !in_array($itemid, $itemselect) OR $excludeinclude == 1 AND in_array($itemid, $itemselect) ) {
											/*
											Image starts here
											*/
											if($itemimage == 0) { //No image
												$showimage = '';
											} else {
												$showimage = '';
												if(!$itemimage || $itemimage == 1) { //Category image
													$getimg = JCategories::getInstance('Content')->get($itemid)->getParams()->get('image');
												} else if ($itemimage == 8) { //Category description first image
													$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $itemdescription, $matches);
													$getimg = $matches[1][0];
												} else if ($itemimage == 2 || $itemimage == 3 || $itemimage == 4 || $itemimage == 5 || $itemimage == 6 || $itemimage == 7) { //Article images
													$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
													$model->getState();$app = JFactory::getApplication();
													$appParams = $app->getParams();
													$model->setState('params', $appParams);
													$model->setState('list.start', 0);
													$model->setState('list.limit', 1);
													$model->setState('filter.category_id', $itemid);
													$model->setState('filter.published', 1);
													$model->setState('list.ordering', 'a.publish_up');
													$model->setState('list.direction', 'DESC');
													if ($itemimage == 2 || $itemimage == 3) { //Featured article?
														$model->setState('filter.featured', 'only');
													}
													$items = $model->getItems();
													$itemimages = json_decode($items[0]->images);
													if ($itemimage == 2 || $itemimage == 5) { //Article image (if no intro get full)
														$introimg = $itemimages->image_intro;
														if($introimg) {
															$getimg = $introimg;
														} else {
															$getimg = $itemimages->image_fulltext;
														}
													} else if ($itemimage == 3 || $itemimage == 6) { //Article intro image
														$getimg = $itemimages->image_intro;
													} else { //Article full image
														$getimg = $itemimages->image_fulltext;
													}
												}
												if($getimg) {
													$showimage = "style='background-image: url(".$getimg.");'";
												}
											}
											/*
											Title starts here
											*/
											$showitemtitle = '<'.$titletag.' class="mrwid-title">'.$itemtitle.'</'.$titletag.'>';
											if($itemstitle == 2) { //Category title
												$showitemtitle = '<'.$titletag.' class="mrwid-title">'.$itemtitle.((in_array("artcount", $itemoptions))?' <small>('. $num_articles .')</small>':"").'</'.$titletag.'>';
											} else if($itemstitle == 1)  { //No title
												$showitemtitle = ''.((in_array("artcount", $itemoptions))?'<'.$titletag.' class="mrwid-title">('. $num_articles .')</'.$titletag.'>':"");
											} else  { //Linked category title
												$showitemtitle = '<'.$titletag.' class="mrwid-title">'.'<a href="'.$itemLink.'">'.$itemtitle.((in_array("artcount", $itemoptions))?' <small>('. $num_articles .')</small>':"").'</a>'.'</'.$titletag.'>';
											}
											/*
											Description starts here
											*/
											if($itemdesc == 1) { //No description
												$showitemdesc = '';
											} else  { //Category description
												$showitemdesc = '<div class="mrwid-desc">'. $itemdescription.'</div>';
											}
											/*
											Bottom link starts here
											*/
											$itemLink = JURI::root().$item->path;
											if($itemlink == 1) { //No bottom link
												$bottomlinktext="";
											} else { //Category link
												if($bottomlink == "") {
													$bottomlink = "Know more...";
												}
												$bottomlinktext = '<div class="mrwid-link"><a class="'.$bottomlinkclasses.'" href="'.$itemLink.'" title="'. $itemtitle .'">'.$bottomlink.'</a></div>';
											}
											/*
											Check front for active category/link and adds a class if it's the current category/link.
											*/
											if(is_array($currentcat) && in_array($itemid, $currentcat) || str_replace("/./","/",$itemLink) == $currentLink) {
												$mrcurrent = 'mrwid-current';
											} else if($currentcat != '' && $currentcat == $itemid) {
												$mrcurrent = 'mrwid-current';
											} else {
												$mrcurrent = '';
											}
											/*
											Add classes for subcategories
											*/
											if(!in_array($itemparent,$mainitem) && $itemparent != 1 && $itemparent != 0) {
												$mrsubcat = 'mrwid-subcat parentitemid-'.$itemparent;
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
											echo  '<li class="itemid-'.$itemid.' '.$itemslug.' '.$mrsubcat.' mr-wid '.$mrcurrent.'" '.((in_array("url", $itemoptions))?'url='.$itemLink:"").'><div class="mrwid-container"'.$showimage.'>'.$showitemtitle.'<div class="mrwid-content">'.$showitemdesc.$bottomlinktext.'</div></div></li>';
											$itemcount = ($itemcount + 1);
											/*
											If the option 'only show subcategories of active' is enabled and this item is a subcategory, it should not close the page yet.
											*/
											if(in_array( "subcatactive", $globallayoutoptions ) && $mrsubcat != '' && $mrsubcat) {
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
							if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles ) && !in_array( 1, $pagetoggles ) && !in_array( 2, $pagetoggles ) && !in_array( 3, $pagetoggles ) && !in_array( 4, $pagetoggles ) || in_array( 0, $pagetoggles )) {
								echo  '<button class="mrwid-arrows mrwid-prev mrwid-'.$pagetransition.'" value="'.$pagecount.'"><span><</span></button>';
							}
							if( in_array( 3, $pagetoggles ) || in_array( 4, $pagetoggles )) {
								echo  '<button class="'.((in_array(3, $pagetoggles))?'mrwid-below':"").' '.((in_array(4, $pagetoggles))?'mrwid-scroll':"").' mrwid-'.$pagetransition.'"><span>+</span></button>';
							}
							if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles ) && !in_array( 1, $pagetoggles ) && !in_array( 2, $pagetoggles ) && !in_array( 3, $pagetoggles ) && !in_array( 4, $pagetoggles ) || in_array( 0, $pagetoggles )) {
								echo  '<button class="mrwid-arrows mrwid-next mrwid-'.$pagetransition.'" value="2"><span>></span></button>';
							}
							echo  '<div class="mrwid-pagination mrwid-'.$pagetransition.'">';
								$hideelement = '';
								if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles )) {
									$hideelement = 'style="display:none;"';
								}
								echo  '<select class="mrwid-pageselect" title="/'.$pagecount.'" '.$hideelement.'>';
								$pagenumber = 0;
								while ($pagenumber++ < $pagecount) {
									echo  '<option value="'.$pagenumber.'">'.$pagenumber.'</option>';
								}
								echo  '</select>';
								if( in_array( 2, $pagetoggles )) {
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
