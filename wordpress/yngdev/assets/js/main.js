function mrwidMain(mrwidThis) {
	if (!mrwidThis.matches('.mr-item')) {
		mrwidThis = event.target.closest('.mr-item');
	}
	const mrwidLayout = mrwidThis.closest('.mr-layout');
	const mrwidPage = mrwidThis.closest('.mr-pages');
	const mrwidItemsTabs = mrwidLayout.querySelector('.mr-tabs.mr-items');
	const mrwidTabs = mrwidLayout.querySelector('.mr-tabs:not(.mr-items)');
	/*start donotinactive*/
	if(mrwidTabs && !mrwidLayout.classList.contains('mr-donotinactive')) {
		const mrtabs = mrwidTabs.querySelectorAll('.mr-tab');
		for (id = 0; id < mrtabs.length; id++) {
			const mrtab = mrtabs[id];
			mrtab.classList.remove('active','inactive','open');
		}
	}
	/*end donotinactive*/
	if(mrwidThis.classList.contains('active')) {
		mrCloseActive(mrwidThis,mrwidLayout,mrwidPage,mrwidItemsTabs);
	} else if(!mrwidThis.classList.contains('active')) {
		mrChangeStatus(mrwidThis,mrwidLayout,mrwidPage,mrwidItemsTabs);
	}
	mrConfirmStatus(mrwidPage,mrwidItemsTabs);
}
function mrChangeStatus(mrwidThis,mrwidLayout,mrwidPage,mrwidItemsTabs) {
	const mrwids = mrwidPage.querySelectorAll('.mr-item');
	//ES6:
	//mrwids.forEach(x => x.classList.remove('active','open','mr-scroll'));
	//mrwids.forEach(x => x.classList.add('inactive'));
	for (id = 0; id < mrwids.length; id++)  {
		if(mrwidLayout.classList.contains('mr-keepactive')) {
			if(!mrwids[id].classList.contains('active')) {
				mrwids[id].classList.add('inactive');
				if(mrwidItemsTabs) {
					const mrwidTabId = mrwids[id].className.split(" ")[0];
					const mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
					if(mrwidTab)  {
						mrwidTab.classList.add('inactive');
					}
				}
			}
		} else if(!mrwidLayout.classList.contains('mr-keepactive')) {
			mrwids[id].classList.remove('active','open','mr-scroll');
			mrwids[id].classList.add('inactive');
			if(mrwidItemsTabs) {
				const mrwidTabId = mrwids[id].className.split(" ")[0];
				const mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
				if(mrwidTab)  {
					mrwidTab.classList.remove('active','open','mr-scroll');
					mrwidTab.classList.add('inactive');
				}
			}
		}
	}
	mrwidThis.classList.remove('inactive');
	mrwidThis.classList.add('active');
	if(mrwidItemsTabs) {
		const mrwidTabId = mrwidThis.className.split(" ")[0];
		const mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
		if(mrwidTab)  {
			mrwidTab.classList.remove('inactive');
			mrwidTab.classList.add('active');
		}
	}
	/*start autoscroll*/
	if(mrwidLayout.classList.contains('mr-autoscroll')) {
		let rect = mrwidThis.getBoundingClientRect();
		if(mrwidLayout.classList.contains('mr-windowheight')) {
			rect = mrwidPage.getBoundingClientRect();
		}
		const elementoffset = rect.top + window.pageYOffset;
		mrScrollTo(elementoffset,500);
	}
	/*end autoscroll*/
	/*start url*/
	if(mrwidLayout.classList.contains('mr-url')) {
		history.pushState("object or string", mrwidThis.querySelector('.mr-title').textContent, mrwidThis.getAttribute('url'));
	}
	/*end url*/
	/*start remember*/
	if(mrwidLayout.classList.contains('mr-remember')) {
		const CookieDate = new Date;
		CookieDate.setFullYear(CookieDate.getFullYear() +1);
		const Classes = mrwidThis.getAttribute('class');
		const firstClass = Classes.indexOf(" ");
		document.cookie = 'mrwidRemember='+Classes.substring(0,firstClass)+'; expires=' + CookieDate.toGMTString() + '; path=/';
	} else {
		if (mrGetCookie("mrwidRemember") != "") {
			const CookieDate = new Date;
			CookieDate.setFullYear(CookieDate.getFullYear() -1);
			document.cookie = 'mrwidRemember=; expires=' + CookieDate.toGMTString() + '; path=/';
		}
	}
	/*end remember*/
	/*start subitemactive*/
	if(mrwidLayout.classList.contains('mr-subitemactive')) {
		const mrwidSubCats = mrwidPage.querySelectorAll('.mr-subitem.parent'+mrwidThis.classList[0]);
		if(mrwidSubCats)  {
			for (id = 0; id < mrwidSubCats.length; id++) {
				mrwidSubCats[id].classList.add('mr-hidden');
				if(mrwidSubCats[id].classList.contains('parent'+mrwidThis.classList[0])) {
					mrwidSubCats[id].classList.remove('mr-hidden');
					mrwidSubCats[id].classList.remove('inactive');
				}
			}
		}
	}
	/*end subitemactive*/
}
function mrCloseActive(mrwidThis,mrwidLayout,mrwidPage,mrwidItemsTabs) {
	/*start donotinactive*/
	if(!mrwidLayout.classList.contains('mr-donotinactive')) {
		mrwidThis.classList.remove('active','open');
		mrwidThis.classList.add('inactive');
		if(mrwidItemsTabs) {
			const mrwidTabId = mrwidThis.className.split(" ")[0];
			const mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
			if(mrwidTab)  {
				mrwidTab.classList.remove('active','inactive','open');
				mrwidTab.classList.add('inactive');
			}
		}
	}
	/* end donotinactive*/
	/*start subitemactive*/
	if(mrwidLayout.classList.contains('mr-subitemactive') && mrwidLayout.classList.contains('mr-hideinactives')) {
		//mrSubItemOnActive(mrwidPage,mrwidItemsTabs);
		//ONLY SHOW SUBITEMS OF ACTIVE + ON ACTIVE HIDE INACTIVE
		//With those options, when clicking an active item go back to the beginning, removing all states:
		const mrwids = mrwidPage.querySelectorAll('.mr-item');
		if(mrwids)  {
			for (id = 0; id < mrwids.length; id++) {
				if(mrwids[id].classList.contains('mr-subitem')) {
					mrwids[id].classList.add('mr-hidden');
				}
				mrwids[id].classList.remove('active','inactive','open');
				if(mrwidItemsTabs) {
					const mrwidTabId = mrwids[id].className.split(" ")[0];
					const mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
					if(mrwidTab)  {
						mrwidTab.classList.remove('active','inactive','open');
					}
				}
			}
		}
	}
	/*end subitemactive*/
}
function mrConfirmStatus(mrwidPage,mrwidItemsTabs) {
	const mrwidCheckState = mrwidPage.querySelectorAll('.active');
	if(!mrwidCheckState.length) {
		const mrwids = mrwidPage.querySelectorAll('.mr-item');
		for (id = 0; id < mrwids.length; id++)  {
			mrwids[id].classList.remove('inactive');
		}
	}
	if(mrwidItemsTabs) {
		const mrwidCheckState = mrwidItemsTabs.querySelectorAll('.mr-tab.active');
		if(!mrwidCheckState.length) {
			const mrtabs = mrwidItemsTabs.querySelectorAll('.mr-tab');
			if (mrtabs) {
				for (id = 0; id < mrtabs.length; id++)  {
					mrtabs[id].classList.remove('inactive');
				}
			}
		}
	}
}
function mrwidChangePage(currentElement,mrwidLayout,mrwidPage) {
	if(!!mrwidLayout.querySelector('.mr-pageselect option[value="'+mrwidPage+'"]')) {
		const mrwidPageSelect = mrwidLayout.querySelector('.mr-pageselect');
		const mrwidRadios = mrwidLayout.querySelectorAll('.mr-radio');
		const mrwidCurrentRadio = mrwidLayout.querySelector('.mr-radio[value="'+mrwidPage+'"]');
		if(mrwidPageSelect) {
			mrwidPageSelect.value = mrwidPage;
		}
		//ES6:
		//for (mrwidRadio of mrwidRadios)
		if(mrwidRadios) {
			for (id = 0; id < mrwidRadios.length; id++) {
				mrwidRadios[id].removeAttribute('checked');
			}
		}
		if(mrwidCurrentRadio) {
			mrwidCurrentRadio.setAttribute('checked','checked');
		}
		mrwidLayout.classList.remove('mr-transitionright','mr-transitionleft');
		if(currentElement.classList.contains('mr-next')) {
			mrwidLayout.classList.add('mr-transitionright');
		} else if(currentElement.classList.contains('mr-prev')) {
			mrwidLayout.classList.add('mr-transitionleft');
		}
		/*
		const mrwidInitHeight = parseInt(getComputedStyle(mrwidLayout).height);
		mrwidLayout.style.minHeight = mrwidInitHeight+'px';
		*/
		if(!!mrwidLayout.querySelector('.mr-page'+mrwidPage+' noscript')) {
			mrwidLayout.querySelector('.mr-page'+mrwidPage).innerHTML = mrwidLayout.querySelector('.mr-page'+mrwidPage+' noscript').textContent;
		}
		const mrwidPages = mrwidLayout.querySelectorAll('.mr-pages');
		const mrwidActivePages = mrwidLayout.querySelectorAll('.mr-pages.active');
		const mrwidNewPage = mrwidLayout.querySelector('.mr-page'+mrwidPage);
		for (id = 0; id < mrwidActivePages.length; id++) {
			const mrwidActivePage = mrwidActivePages[id];
			mrwidActivePage.classList.remove('active','inactive','open');
		}
		setTimeout(function() {
			for (id = 0; id < mrwidPages.length; id++) {
				const mrwidPage = mrwidPages[id];
				mrwidPage.classList.remove('active');
				mrwidPage.classList.add('inactive');
			}
			mrwidNewPage.classList.remove('inactive');
			mrwidNewPage.classList.add('active');
			if(mrwidNewPage.nextElementSibling && mrwidNewPage.nextElementSibling.classList.contains('mr-pages') && mrwidLayout.querySelector('.mr-below')) {
				mrwidLayout.querySelector('.mr-below').classList.remove('mr-hidden');
			} else if(mrwidLayout.querySelector('.mr-below')) {
				mrwidLayout.querySelector('.mr-below').classList.add('mr-hidden');
			}
		}, 400);
	}
	setTimeout(function() {
		/*if(mrwidLayout.classList.contains('mr-contentpagination')) {
			const mrwidBackground = mrwidLayout.querySelector('.mr-1perpage.mr-1perline.active .mr-container');
			if(mrwidBackground) {
				mrwidBackground = mrwidLayout.querySelector('.mr-1perpage.mr-1perline.active .mr-container').style.backgroundImage;
				if(!mrwidBackground) {
					mrwidBackground = 'none';
				}
				mrwidLayout.style.backgroundImage = mrwidBackground;
			}
		}*/
		//mrwidLayout.style.minHeight = 'inherit';
		currentElement.classList.remove('loading');
		mrwidTabs();
	}, 500);
}
function mrwidNext(currentElement) {
	const mrwidLayout = currentElement.parentElement;
	let mrwidPage = mrwidLayout.querySelector('.mr-pageselect');
	if(mrwidPage) {
		mrwidPage = mrwidPage.value;
		const mrwidPageLastValue = mrwidLayout.querySelector('.mr-pageselect option:last-child').value;
		if(mrwidPage < parseInt(mrwidPageLastValue)) {
			mrwidPage = parseInt(mrwidPage)+1;
		} else {
			mrwidPage = 1;
		}
		mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
	}
}
function mrwidPrev(currentElement) {
	const mrwidLayout = currentElement.parentElement;
	let mrwidPage = mrwidLayout.querySelector('.mr-pageselect').value;
	const mrwidPageFirstValue = mrwidLayout.querySelector('.mr-pageselect option:first-child').value;
	const mrwidPageLastValue = mrwidLayout.querySelector('.mr-pageselect option:last-child').value;
	if(mrwidPage == parseInt(mrwidPageFirstValue)) {
		mrwidPage = parseInt(mrwidPageLastValue);
	} else {
		mrwidPage = mrwidPage-1;
	}
	mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
}
function mrwidBelow(currentElement) {
	const mrwidLayout = currentElement.parentElement;
	const mrwidPage = mrwidLayout.querySelectorAll('.mr-pages.active');
	const mrwidLastActive = mrwidPage[mrwidPage.length - 1];
	let mrwidNewPage = mrwidLastActive.nextElementSibling;
	mrwidLayout.classList.remove('mr-transitionright','mr-transitionleft');
	if(!mrwidPage) {
		mrwidLayout.querySelector('.mr-page1').classList.add('active');
		currentElement.style.classList.remove('mr-hidden');
	} else if(!!mrwidNewPage && mrwidNewPage.classList.contains('mr-pages')) {
		if(!!mrwidNewPage.querySelector('noscript')) {
			mrwidNewPage.innerHTML = mrwidNewPage.querySelector('noscript').textContent;
		}
		mrwidNewPage.classList.remove('inactive');
		mrwidNewPage.classList.add('active');
		//const mrwidNewPageNumber = mrwidNewPage.childElementCount; //THE CHILD NUMBER NEEDS TESTING
		mrwidNewPage = mrwidLayout.querySelectorAll('.mr-pages.active');
		const mrwidNewLastActive = mrwidNewPage[mrwidNewPage.length - 1];
		const mrwidNextPage = mrwidNewLastActive.nextElementSibling;
		/*const mrwidRadios = mrwidLayout.querySelectorAll('.mr-radio');
		mrwidLayout.querySelector('.mr-pageselect').value = mrwidNewPageNumber;
		if(mrwidRadios) {
			for (id = 0; id < mrwidRadios.length; id++) {
				mrwidRadios[id].removeAttribute('checked');
			}
		}
		mrwidLayout.querySelector('.mr-radio[value="'+mrwidNewPageNumber+'"]').setAttribute('checked','checked');*/
		if(!mrwidNextPage && mrwidLayout.querySelector('.mr-below') || !mrwidNextPage.classList.contains('mr-pages') && mrwidLayout.querySelector('.mr-below')) {
			mrwidLayout.querySelector('.mr-below').classList.add('mr-hidden');
		}
	} else {
		mrwidLayout.querySelector('.mr-below').classList.add('mr-hidden');
	}
	setTimeout(function() {
		currentElement.classList.remove('loading');
		mrwidTabs();
	}, 600);
}
function mrAutoPlay() {
	const mrwidsAutoPlay = document.querySelectorAll('.mr-layout[class*=mr-autoplay]');
	if (mrwidsAutoPlay) {
		for (id = 0; id < mrwidsAutoPlay.length; id++)  {
			const currentElement = mrwidsAutoPlay[id];
			//const mrwidAutoPlayClass = currentElement.classList.item(currentElement.classList.length-1);
			const mrwidAutoPlayClasses = [].slice.call(currentElement.classList).toString();
			const mrwidAutoPlayClass = mrwidAutoPlayClasses.substring(mrwidAutoPlayClasses.lastIndexOf("mr-autoplay") + 11, mrwidAutoPlayClasses.lastIndexOf("s"));
			//if (mrIsInView(currentElement)) {
				if(!currentElement.classList.contains('mr-hovering')) {
					mrwidNext(currentElement);
				}
			//}
			setTimeout(mrAutoPlay,parseInt(mrwidAutoPlayClass)*1000);
		}
	}
}
function mrwidTabs() {
	const mrwidItemsTabs = document.querySelectorAll('.mr-tabs .mr-tab');
	if(mrwidItemsTabs) {
		for (id = 0; id < mrwidItemsTabs.length; id++)  {
			const mrwidItemTab = mrwidItemsTabs[id];
			const mrwidTabId = mrwidItemTab.className.split(" ")[0];
			const mrwidLayout = mrwidItemTab.closest('.mr-layout');
			const mrwidThis = mrwidLayout.querySelector('.mr-pages.active .mr-item.'+mrwidTabId);
			if(mrwidThis) {
				if(mrwidItemTab.classList.contains('mr-hidden')) {
					mrwidItemTab.classList.remove('mr-hidden');
				}
			} else {
				mrwidItemTab.classList.add('mr-hidden');
			}
		}
	}
}
function mrwidTabsChange(mrwidThis) {
	const mrwidTabId = mrwidThis.className.split(" ")[0];
	const mrwidLayout = mrwidThis.closest('.mr-layout');
	const mrwidTabs = mrwidLayout.querySelectorAll('.mr-tab');
	const mrwidActiveTab = mrwidLayout.querySelector('.mr-tab.'+mrwidTabId);
	const mrWids = mrwidLayout.querySelectorAll('.mr-pages.active .mr-item');
	const mrWidsInactive = mrwidLayout.querySelectorAll('.mr-pages.active .mr-item.inactive');
		if(/*!mrwidLayout.classList.contains('mr-donotinactive') && */mrwidThis.classList.contains('active')) {
			if(mrwidTabs) {
				for (id = 0; id < mrwidTabs.length; id++)  {
					const mrwidTab = mrwidTabs[id];
					mrwidTab.classList.remove('active','inactive');
				}
			}
			if(mrWids) {
				for (id = 0; id < mrWids.length; id++)  {
					const mrWid = mrWids[id];
					mrWid.classList.remove('active','inactive');
				}
			}
		} else {
			if(mrwidTabs && !mrwidLayout.classList.contains('mr-keepactive')) {
				for (id = 0; id < mrwidTabs.length; id++)  {
					const mrwidTab = mrwidTabs[id];
					mrwidTab.classList.remove('active');
					mrwidTab.classList.add('inactive');
				}
			}
			if(mrWids) {
				for (id = 0; id < mrWids.length; id++)  {
					const mrWid = mrWids[id];
					if(mrWid.classList.contains(mrwidTabId)) {
						mrWid.classList.remove('inactive');
						mrWid.classList.add('active');
						if(mrwidActiveTab) {
							mrwidActiveTab.classList.remove('inactive');
							mrwidActiveTab.classList.add('active');
						}
					} else {
						if(!mrWidsInactive.length || !mrwidLayout.classList.contains('mr-keepactive')) {
							mrWid.classList.remove('active');
							mrWid.classList.add('inactive');
						}
					}
				}
			}
		}
		const mrwidCheckState = mrwidLayout.querySelectorAll('.mr-tab.active');
		if(!mrwidCheckState.length) {
			if(mrwidTabs) {
				for (id = 0; id < mrwidTabs.length; id++)  {
					const mrwidTab = mrwidTabs[id];
					mrwidTab.classList.remove('active','inactive');
				}
			}
			if(mrWids) {
				for (id = 0; id < mrWids.length; id++)  {
					const mrWid = mrWids[id];
					mrWid.classList.remove('active','inactive');
				}
			}
		}
}
document.addEventListener('DOMContentLoaded', function() {
	/*const mrwidPages1 = document.querySelectorAll('.mr-page1');
	for (id = 0; id < mrwidPages1.length; id++) {
		const mrwidPage1 = mrwidPages1[id];
		if(mrwidPage1.classList.contains('mr-1perline')) {
			if(mrwidPage1.parentElement.classList.contains('mr-contentpagination')) {
				const mrwidBackground = mrwidPage1.querySelector('.mr-container').style.backgroundImage;
				if(!mrwidBackground) {
					mrwidBackground = 'none';
				}
				mrwidPage1.style.backgroundImage = mrwidBackground;
			}
		}
	}*/
	//ACTIVATE LAST REMEMBERED ACTIVE
	if (mrGetCookie("mrwidRemember") != "") {
		const mrwidRemembered = mrGetCookie("mrwidRemember");
		const mrwids = document.querySelectorAll('.mr-remember .mr-pages.active .mr-item');
		if(mrwids) {
			for (id = 0; id < mrwids.length; id++) {
				if(mrwids[id].classList.contains(mrwidRemembered)) {
					mrwids[id].classList.remove('inactive');
					mrwids[id].classList.add('active');
				} else {
					mrwids[id].classList.remove('active');
					mrwids[id].classList.add('inactive');
				}
			}
		}
	}
	//ON WINDOW RETURNS CHANGE ACTIVE FOR ITEMS THAT CHANGED WINDOW URL
	window.addEventListener('popstate', function(){
		const checkattr = document.querySelectorAll('.mr-url .mr-item[url]');
		if(checkattr)  {
			for (id = 0; id < checkattr.length; id++) {
				const getWidUrl = checkattr[id].getAttribute('url');
				if(getWidUrl.indexOf('/./') > -1) {
					const getWidUrl = getWidUrl.replace('./', '');
				}
				if(window.location.href.indexOf(getWidUrl) > -1) {
					checkattr[id].classList.remove('inactive');
					checkattr[id].classList.add('active');
				} else {
					checkattr[id].classList.remove('active');
					checkattr[id].classList.add('inactive');
				}
			}
		}
	});
	//CSS WINDOW HEIGHT FIX (MADE SPECIALLY FOR MOBILE TO TAKE BROWSER ADDRESS BAR INTO ACCOUNT)
	const mrwidsHeightFix = document.querySelectorAll('.mr-windowheight');
	if(mrwidsHeightFix) {
		const vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', vh + 'px');
		window.addEventListener('resize', function() {
			const vh = window.innerHeight * 0.01;
			document.documentElement.style.setProperty('--vh', vh + 'px');
		});
	}
	//CHECK IF THERE IS THE NEED TO AUTOPLAY
	const mrwidAutoPlay = document.querySelector('.mr-layout[class*=mr-autoplay]');
	if (mrwidAutoPlay) {
		const mrwidAutoPlayClasses = [].slice.call(mrwidAutoPlay.classList).toString();
		const mrwidAutoPlayClass = mrwidAutoPlayClasses.substring(mrwidAutoPlayClasses.lastIndexOf("mr-autoplay") + 14, mrwidAutoPlayClasses.lastIndexOf("s"));
		setTimeout(mrAutoPlay,parseInt(mrwidAutoPlayClass)*1000);
	}
	//ON MOUSEOVER/MOUSELEAVE
	const mrwidsHover = document.querySelectorAll('.mr-layout.mr-hover,.mr-layout[class*=mr-autoplay]');
	if (mrwidsHover) {
		//ES6:
		//for (mrwidHover of mrwidsHover) 
		for (id = 0; id < mrwidsHover.length; id++)  {
			mrwidsHover[id].addEventListener('mouseover',function(event) {
				if (event.target.matches('.mr-layout.mr-hover .mr-item')) {
					mrwidMain(event.target);
					event.stopPropagation();
				} else if (event.target.matches('.mr-layout.mr-hover .mr-tab')) {
					const mrwidThis = event.target;
					mrwidTabsChange(mrwidThis);
					event.stopPropagation();
				}
				if (event.target.matches('.mr-layout[class*=mr-autoplay] .mr-item') || event.target.matches('.mr-layout[class*=mr-autoplay] .mr-tab') || event.target.matches('.mr-layout[class*=mr-autoplay] .mr-pagination') || event.target.matches('.mr-layout[class*=mr-autoplay] .mr-arrows') || event.target.matches('.mr-layout[class*=mr-autoplay] .mr-below')) {
					event.target.closest('.mr-layout').classList.add('mr-hovering');
				}
			});
			mrwidsHover[id].addEventListener('mouseleave', function(event) {
				if(event.target.classList.contains('mr-hovering')) {
					event.target.classList.remove('mr-hovering');
				}
				const mrwid = event.target.querySelectorAll('.mr-layout.mr-hover .mr-pages.active .mr-item');
				if (mrwid) {
					for (id = 0; id < mrwid.length; id++)  {
						mrwid[id].classList.remove('active','inactive');
					}
					if(event.target.classList.contains('mr-subitemactive')) {
						const mrwidSubcats = event.target.querySelectorAll('.mr-pages.active .mr-subitem');
						if (mrwidSubcats) {
							for (id = 0; id < mrwidSubcats.length; id++)  {
								mrwidSubcats[id].classList.add('mr-hidden');
							}
						}
						event.stopPropagation();
					}
				}
			});
		}
	}
	mrwidTabs();
});
//ON CLICK/TOUCH
document.addEventListener('click',function(event) {
	if (event.target.matches('.mr-layout:not(.mr-hover) .mr-item') || event.target.matches('.mr-layout:not(.mr-hover) .mr-container') || event.target.matches('.mr-layout:not(.mr-hover) .mr-image') || event.target.matches('.mr-layout:not(.mr-hover) .mr-title') || event.target.matches('.mr-layout:not(.mr-hover) .mr-item:not(.active) .mr-content, .mr-layout:not(.mr-hover) .mr-item:not(.active) .mr-content *')) {
		const mrwidThis = event.target;
		mrwidMain(mrwidThis);
		event.stopPropagation();
	} else if (event.target.matches('.mr-tabs .mr-tab')) {
		const mrwidThis = event.target;
		mrwidTabsChange(mrwidThis);
		event.stopPropagation();
	} else if (event.target.matches('.mr-layout:not(.mr-hover) a:not([href="#"]):not([href="javascript:void(0)"])')) {
		//When changing page from inside the widget, add a 'open' class to do a transition/animation:
		const mrwidThis = event.target.closest('.mr-item');
		mrwidThis.classList.add('open');
		event.stopPropagation();
	//PAGE TOGGLES
	} else if (event.target.matches('.mr-next')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidNext(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mr-next span')) {
		const currentElement = event.target.parentElement;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidNext(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mr-prev')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidPrev(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mr-prev span')) {
		const currentElement = event.target.parentElement;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidPrev(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mr-radio')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			const mrwidLayout = currentElement.closest('.mr-layout');
			const mrwidPage = currentElement.value;
			mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mr-pageselect')) {
		event.target.addEventListener('change',function(event) {
			const currentElement = event.target;
			if(!currentElement.classList.contains('loading')) {
				currentElement.classList.add('loading');
				const mrwidLayout = currentElement.closest('.mr-layout');
				const mrwidPage = currentElement.value;
				mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
			}
		});
		event.stopPropagation();
	} else if (event.target.matches('.mr-below') || event.target.matches('.mr-scroll')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidBelow(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mr-below span') || event.target.matches('.mr-scroll span')) {
		const currentElement = event.target.parentElement;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidBelow(currentElement);
		}
		event.stopPropagation();
	}
});
document.addEventListener('keydown',function(event) {
	const mrwidKeyboard = document.querySelectorAll('.mr-keyboard');
	if(mrwidKeyboard) {
		for (id = 0; id < mrwidKeyboard.length; id++)  {
			const currentElement = mrwidKeyboard[id];
			if (mrIsInView(currentElement)) {
				if(!currentElement.classList.contains('loading')) {
					currentElement.classList.add('loading');
					const mrwidLayout = currentElement.parentElement;
					if (event.keyCode === 39) { mrwidNext(currentElement); return false;
					} else if (event.keyCode === 37) { mrwidPrev(currentElement); return false;
					} else if (event.keyCode === 40) { mrwidBelow(currentElement); return false;
					} else if (event.keyCode === 49 || event.keyCode === 97) { const mrwidPage = 1; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 50 || event.keyCode === 98) { const mrwidPage = 2; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 51 || event.keyCode === 99) { const mrwidPage = 3; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 52 || event.keyCode === 100) { const mrwidPage = 4; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 53 || event.keyCode === 101) { const mrwidPage = 5; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 54 || event.keyCode === 102) { const mrwidPage = 6; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 55 || event.keyCode === 103) { const mrwidPage = 7; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 56 || event.keyCode === 104) { const mrwidPage = 8; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode === 57 || event.keyCode === 105) { const mrwidPage = 9; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					}
				}
			}
		}
	}
});
let scrollTimer;
let initst;
window.addEventListener('scroll',function(event) {
	//SCROLL PAGE TOGGLE
	const mrwidsScroll = document.querySelectorAll('.mr-scroll');
	if (mrwidsScroll) {
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(function() {
			//ES6:
			//for (mrwidScroll of mrwidsScroll)
			for (id = 0; id < mrwidsScroll.length; id++)  {
				const currentElement = mrwidsScroll[id];
				if (mrIsInView(currentElement)) {
					mrwidBelow(currentElement);
					//currentElement.click();
					/*setTimeout(function() {
						currentElement.classList.remove('loading');
					}, 600);*/
				}
			}
		}, 400);
	}
	mrParallax('.mr-theme .mr-layout.mr-parallax.mr-background .mr-pages.active .mr-image');
	const st = window.pageYOffset;
	initst = st;
	const mrthumbparElements = document.querySelectorAll('.mr-theme .mr-layout.mr-parallax.mr-thumbnail:not(.mr-background) .mr-pages.active .mr-image img');
	if(mrthumbparElements) {
		for (id = 0; id < mrthumbparElements.length; id++)  {
			mrthumbparElements[id].style.transform = "translateY(-"+(st*.04)+"px)";
		}
	}
});