<?php
defined('_JEXEC') or die;
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
			    $lang = '';
				$currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					/*
					Title starts here
					*/
					$titletag = 'h3';
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
						$perlineclass = " mr-".$perline."perline";
					} else {
						$perlineclass = " mr-flex";
					}
					/*
					Get the perpage number
					*/
					if($perpage && $perpage != "∞" && $perpage > 0) {
						$perpageclass = "mr-pages mr-".$perpage."perpage mr-nobullets";
					} else {
						$perpageclass = "mr-pages mr-nobullets";
					}
					/*
					Get the autoplay seconds
					*/
					if($autoplay && $autoplay != "∞" && $autoplay > 0) {
						$autoplay = ' mr-autoplay'.$autoplay."s mr-transitionright";
					} else {
						$autoplay = "";
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
				if(!$orderby) { //Creation
					if($contenttypes == 'categories') {
						$orderby = 'created_time';
					} else if($contenttypes == 'content') {
						$orderby = 'created';
					} else {
						$orderby = 'id';
					}
				} else if($orderby == 1) { //Name/Title
					$orderby = 'title';
				} else if($orderby == 3) { //Article Count
					$orderby = 'count';
				} else if($orderby == 4) { //Slug/Alias
					$orderby = 'alias';
				} else if($orderby == 5) { //Ordering
					if($contenttypes != 'content') {
						$orderby = 'rgt';
					} else {
						$orderby = 'ordering';
					}
				} else if($orderby == 6) { //Level
					if($contenttypes != 'content') {
						$orderby = 'level';
					} else {
						$orderby = 'ordering';
					}
				} else { //Parent (2)
					if($contenttypes != 'content') {
						$orderby = 'parent_id';
					} else {
						$orderby = 'catid';
					}
				}
				if(!$order) { //Descending
					$order = 'DESC';
				} else { //Ascending
					$order = 'ASC';
				}
				/*
				Join all the previous options for the main array of categories.
				*/
				if(!$contenttypes) {
					$query->select('*')->from($db->quoteName('#__categories'));
				} else {
					$query->select('*')->from($db->quoteName('#__'.$contenttypes.''));
				}
				$query->order($orderby.' '.$order);
				$db->setQuery($query);
                $itemlist = $db->loadObjectList();
				/* Get extra classes to give to the main container */
				if ( ! empty( $itemlist ) ) {
						/*
						Start the content with the container for the categories.
						The theme name, the layout name and the global options are imploded in here has classes.
						*/
						echo  '<div class="yngdev-module mr-'.$contenttypes.' mr-theme mr-'.strtolower($theme).' mr-boxsize"><div class="mr-layout mr-'.strtolower($layout).(($layoutoptions)?' mr-'.implode(" mr-", $layoutoptions):" ").(($globallayoutoptions)?' mr-'.implode(" mr-", $globallayoutoptions):" ").(($itemoptions)?' mr-'.implode(" mr-", $itemoptions):" ").(($tabsposition != 'tabstop')?' mr-'.$tabsposition:" ").$autoplay.' mr-noscroll">';
						if($tabs == 1) { //Items Tabs
							echo '<ul class="mr-tabs mr-items mr-flex mr-scroll mr-nobullets">';
							foreach ( $itemlist as $key => $tab) {
								$tabid = $tab->id;
								echo '<li class="itemid-'.$tabid.' '.$tab->alias.' mr-tab">'.$tab->title.'</li>';
							}
							echo '</ul>';
						}
						if($tabs == 2) { //Parent Items Tabs
							echo '<ul class="mr-tabs mr-parentitems mr-flex mr-scroll mr-nobullets">';
							foreach ( $itemlist as $key => $tab) {
								$tabid = $tab->id;
								echo '<li class="parentitemid-'.$tabid.' '.$tab->alias.' mr-tab">'.$tab->title.'</li>';
							}
							echo '</ul>';
						}
						$itemcount = 0;
						$pagecount = 1;
						foreach ( $itemlist as $key => $item) {
								/*
								Get all needed item values
								*/
								if($item->id) {
									$itemid = $item->id;
								} else {
									$itemid = 0;
								}
								if($item->alias) {
									$itemslug = $item->alias;
								} else {
									$itemslug = '';
								}
								if($contenttypes == 'categories') { //Categories and tags have parents
									$itemparent = $item->parent_id;
									if(is_array($parentcats) && in_array($itemparent,$parentcats) || !$parentcats) {
										$parentcheck = 1;
									} else {
										$parentcheck = 0;
									}
								} else {
									$itemparent = 0;
								}
								if($contenttypes == 'content') {
									$itemcats = $item->catid;
									$item->tags = new JHelperTags;
  									$item->tags->getTagIds($itemid, 'com_content.article');
									$itemtags = explode(',',$item->tags->tags);
									if(is_array($parentcats) && in_array($itemcats,$parentcats) || is_array($parenttags) && array_intersect($itemtags,$parenttags) || !$parentcats && !$parenttags) {
										$parentcheck = 1;
									} else {
										$parentcheck = 0;
									}
								}
								if($contenttypes == 'categories' && in_array("artcount", $itemoptions)) {
									$model = JModelLegacy::getInstance('Articles', 'ContentModel');
									$model->setState('filter.category_id', $itemid);
									$articles = $model->getItems();
									$num_articles = count($articles);
								}
								if($item->title) {
									$cattitle = $item->title;
								} else if($item->name) {
									$cattitle = $item->name;
								} else {
									$cattitle = '';
								}
								if($itemstitlemax != 0) {
									$itemtitle = (strlen($cattitle) > $itemstitlemax) ? mb_substr($cattitle,0,$itemstitlemax, 'utf-8').'<span class="mr-ellipsis">...</span>' : $cattitle;
								} else {
									$itemtitle = $cattitle;
								}
								if(!$itemdesc || $itemdesc != 1 || $itemimage && $itemimage == 8) {
									if($item->description) {
										$itemdescription = $item->description;
										if($itemdesc == 2) { //Category intro text
											$itemdescription = strstr($itemdescription, '<hr id="system-readmore" />', true);
										} else if($itemdesc == 3) { //Category full text
											if (strpos($itemdescription, '<hr id="system-readmore" />') !== false) {
												$itemdescription = explode('<hr id="system-readmore" />', $itemdescription)[1];
											}
										} else if($itemdesc == 0) { //Category description
											$itemdescription = explode('<hr id="system-readmore" />', $itemdescription)[0];
										} else {
											$itemdescription = '';
										}
									} else {
										if($itemdesc == 2 && $item->introtext) { //Article intro text
											$itemdescription = $item->introtext;
										} else if($itemdesc == 3 && $item->fulltext) { //Article full text
											$itemdescription = $item->fulltext;
										} else if($itemdesc == 0) { //Article description
											if($item->introtext) {
												$itemdescription = $item->introtext;
											} else if($item->fulltext) {
												$itemdescription = $item->fulltext;
											} else {
												$itemdescription = '';
											}
										}
									}
								}
								/*
								Add the content of the current category in the container.
								The layout options are imploded in here has classes.
								*/
									/*
									For now only display categories from the extension com_content
									*/
									if($item->extension && $item->extension == 'com_content' && $item->published == 1 && $itemid > 1 || !$item->extension && $item->published == 1 && $itemid > 1 || $item->state && $item->state == 1) {
										/*
										Check if current category was not manually excluded.
										*/
										if($excludeinclude == 0 AND !in_array($itemid, $itemselect) OR $excludeinclude == 1 AND in_array($itemid, $itemselect) ) {
											/*
											Get link for linked item title and bottom link
											*/
											if($itemcustom && strpos($itemcustom, '[l]http') !== false) { //Linkoverride
												$itemLink = explode("[l]", $itemcustom, 2)[1];
											} else if($contenttypes != 'content') { //Content links
												$itemLink = str_replace("/./","/",JURI::root().$item->path);
											} else { //Articles link
												$itemLink = str_replace("/./","/",JURI::root().$item->alias);
											}
											/*
											Image starts here
											*/
											if($itemimage == 0) { //No image
												$showimage = '';
											} else {
												$showimage = '';
												if(!$itemimage || $itemimage == 1) { //Item intro image
													if($contenttypes == 'categories') {
														$getimg = JCategories::getInstance('Content')->get($itemid)->getParams()->get('image');
													} else if($contenttypes == 'content') {
														$getimg = json_decode($item->images)->image_intro;
													}
												} else if($itemimage == 9) { //Item full image
													if($contenttypes == 'categories') {
														$getimg = JCategories::getInstance('Content')->get($itemid)->getParams()->get('image');
													} else if($contenttypes == 'content') {
														$getimg = json_decode($item->images)->image_fulltext;
													}
												} else if ($itemimage == 8) { //Description first image
													$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $itemdescription, $matches);
													$getimg = $matches[1][0];
												} else if ($itemimage == 2 || $itemimage == 3 || $itemimage == 4 || $itemimage == 5 || $itemimage == 6 || $itemimage == 7) { //Article images from category
													$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
													$model->getState();
													$app = JFactory::getApplication();
													$appParams = $app->getParams();
													$model->setState('params', $appParams);
													$model->setState('list.start', 0);
													$model->setState('list.limit', 1);
													if($contenttypes == 'categories') {
														$model->setState('filter.category_id', $itemid);
													} else if($contenttypes == 'content') {
														$model->setState('filter.category_id', $itemcats);
													}
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
													if($contenttypes == 'categories') {
														$imgalt = JCategories::getInstance('Content')->get($itemid)->getParams()->get('image_alt');
														$imgtitle = $imgalt;
													} else if($contenttypes == 'content' && !$itemimage || $contenttypes == 'content' && $itemimage == 1) {
														$imgalt = json_decode($item->images)->image_intro_alt;
														$imgtitle = $imgalt;
														$imgcaption = json_decode($item->images)->image_intro_caption;
													} else if($contenttypes == 'content' && $itemimage == 9) {
														$imgalt = json_decode($item->images)->image_fulltext_alt;
														$imgtitle = $imgalt;
														$imgcaption = json_decode($item->images)->image_fulltext_caption;
													}
													if($imgalt) {
														$imgalt = " alt='".$imgalt."' title='".$imgtitle."' ";
													} else {
														$imgalt = "";
													}
													if($imgcaption) {
														$imgcaption = "<figcaption><small>".$imgcaption."</small></figcaption>";
													} else {
														$imgcaption = "";
													}
													$showimage = "><figure class='mr-image' style='background-image: url(".filter_var($getimg, FILTER_VALIDATE_URL).");'></figure";
													/*$showimage = '><figure class="mr-image" '.((in_array('background',$imagestype))?'style="background-image: url('.$getimg.');':'src="'.filter_var($getimg, FILTER_VALIDATE_URL)).((in_array('thumbnail',$imagestype))?$addimagemaxwidth.$addimagemaxheight:'').'"'.$imgalt.'>'.$imgcaption.'</figure';*/
												}
											}
											/*
											Title starts here
											*/
											$showitemtitle = '<'.$titletag.' class="mr-title">'.$itemtitle.'</'.$titletag.'>';
											if($itemstitle == 2) { //Category title
												$showitemtitle = '<'.$titletag.' class="mr-title">'.$itemtitle.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?' <small>('. $num_articles .')</small>':"").'</'.$titletag.'>';
											} else if($itemstitle == 1)  { //No title
												$showitemtitle = ''.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?'<'.$titletag.' class="mr-title">('. $num_articles .')</'.$titletag.'>':"");
											} else  { //Linked category title
												$showitemtitle = '<'.$titletag.' class="mr-title">'.'<a href="'.filter_var($itemLink, FILTER_VALIDATE_URL).'">'.$itemtitle.((in_array("artcount", $itemoptions) && is_numeric($num_articles))?' <small>('. $num_articles .')</small>':"").'</a>'.'</'.$titletag.'>';
											}
											/*
											Description starts here
											*/
											if($itemdesc == 1) { //No description
												$showitemdesc = '';
											} else { //With description
												if($itemdescmax != 0) { //Max. characters
													$itemdescription = strip_tags($itemdescription);
													$itemdescription = (strlen($itemdescription) > $itemdescmax) ? mb_substr($itemdescription,0,$itemdescmax, 'utf-8').'<span class="mr-ellipsis">...</span>' : $itemdescription;
												}
												$showitemdesc = '<div class="mr-desc">'.$itemdescription.'</div>';
											}
											/*
											Bottom link starts here
											*/
											if($itemlink == 1) { //No bottom link
												$bottomlinktext="";
											} else {  //Category link
												if($bottomlink == "") {
													$bottomlink = "Know more...";
												}
												$bottomlinktext = '<div class="mr-link"><a href="'.filter_var($itemLink, FILTER_VALIDATE_URL).'" title="'. $itemtitle .'">'.$bottomlink.'</a></div>';
											}
											/*
											Check front for active category/link and adds a class if it's the current category/link.
											*/
											if(is_array($currentcat) && in_array($itemid, $currentcat) || $itemLink == $currentLink) {
												$mrcurrent = 'mr-current';
											} else if($currentcat != '' && $currentcat == $itemid) {
												$mrcurrent = 'mr-current';
											} else {
												$mrcurrent = '';
											}
											/*
											Add classes for categories
											*/
											if($contenttypes == 'categories') {
												$mrclasses .= ' catid-'.$itemid;
											} else if($contenttypes == 'content') {
												$mrclasses .= ' catid-'.$itemcats;
											}
											/*
											Check if this item should be on a new page.
											*/
											if($itemcount == 0) {
												echo  '<ul class="'.$perpageclass.' mr-page'.$pagecount.' '.$perlineclass.' mr-'.$pagetransition.''.($pagecount == 1 ? " active" : " inactive").'">';
												if($pagecount > 1) {
													echo  '<noscript>';
												}
											}
											echo  '<li class="itemid-'.$itemid.' '.$itemslug.' '.$mrclasses.' mr-item '.$mrcurrent.'" '.((in_array("url", $itemoptions))?'url='.$itemLink:"").'><div class="mr-container"'.$showimage.'>'.$showitemtitle.'<div class="mr-content">'.$showitemdesc.$bottomlinktext.'</div></div></li>';
											$itemcount = ($itemcount + 1);
											/*
											If the option 'only show subitems of active' is enabled and this item is a subitem, it should not close the page yet.
											*/
											if(in_array( "subitemactive", $globallayoutoptions ) && $mrclasses != '' && $mrclasses || in_array( "subitemactive", $layoutoptions ) && $mrclasses != '' && $mrclasses) {
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
						Doublecheck if the last page was closed in case the last item was a hidden subitem.
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
							if( in_array( 0, $pagetoggles )) {
								echo  '<button class="mr-arrows mr-prev" value="'.$pagecount.'"><span><</span></button>';
							}
							if( in_array( 0, $pagetoggles )) {
								echo  '<button class="mr-arrows mr-next" value="2"><span>></span></button>';
							}
							if( in_array( 3, $pagetoggles ) || in_array( 4, $pagetoggles )) {
								echo  '<button class="'.((in_array(3, $pagetoggles))?'mr-below':"").' '.((in_array(4, $pagetoggles))?'mr-scroll':"").'"><span>+</span></button>';
							}
							echo  '<div class="mr-pagination '.((in_array(5, $pagetoggles))?'mr-keyboard':"").'">';
								$hideelement = '';
								if( empty( $pagetoggles ) || !in_array( 1, $pagetoggles )) {
									$hideelement = 'style="display:none;"';
								}
								echo  '<select class="mr-pageselect" title="/'.$pagecount.'" '.$hideelement.'>';
								$pagenumber = 0;
								while ($pagenumber++ < $pagecount) {
									echo  '<option value="'.$pagenumber.'">'.$pagenumber.'</option>';
								}
								echo  '</select>';
								if( in_array( 2, $pagetoggles )) {
									$pagenumber = 0;
									while ($pagenumber++ < $pagecount) {
										echo '<input name="mr-radio" title="'.$pagenumber.'/'.$pagecount.'" class="mr-radio" type="radio" value="'.$pagenumber.'"'.(($pagenumber==1)?' checked="checked" ':'').'>';
									}
								}
							echo  '</div>';
						}
						echo  '</div></div>';
				}
?>