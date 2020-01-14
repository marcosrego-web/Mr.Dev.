function mrGetCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}
function mrIsInView(currentElement) {
	var docViewTop = window.pageYOffset;
	var docViewBottom = docViewTop + window.innerHeight;
	var elemTop = currentElement.offsetTop;
	var elemBottom = elemTop + parseInt(getComputedStyle(currentElement).height);
	return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
function mrScrollTo(to, duration) {
    element = document.scrollingElement || document.documentElement,
    start = element.scrollTop,
    change = to - start,
    startDate = +new Date(),
    // t = current time
    // b = start value
    // c = change in value
    // d = duration
    easeInOutQuad = function(t, b, c, d) {
        t /= d/2;
        if (t < 1) return c/2*t*t + b;
        t--;
        return -c/2 * (t*(t-2) - 1) + b;
    },
    animateScroll = function() {
		//ES6:
		//const
        var currentDate = +new Date();
        var currentTime = currentDate - startDate;
        element.scrollTop = parseInt(easeInOutQuad(currentTime, start, change, duration));
        if(currentTime < duration) {
            requestAnimationFrame(animateScroll);
        }
        else {
            element.scrollTop = to;
        }
    };
    animateScroll();
}
function mrwidMain(mrwidThis) {
	if (!mrwidThis.matches('.mr-wid')) {
		var mrwidThis = event.target.closest('.mr-wid');
	}
	var mrwidLayout = mrwidThis.closest('.mrwid-layout');
	var mrwidPage = mrwidThis.closest('.mrwid-pages');
	var mrwidItemsTabs = mrwidLayout.querySelector('.mrwid-tabs.mrwid-items');
	var mrwidTabs = mrwidLayout.querySelector('.mrwid-tabs:not(.mrwid-items)');
	if(mrwidTabs && !mrwidLayout.classList.contains('mrwid-donotinactive')) {
		var mrtabs = mrwidTabs.querySelectorAll('.mr-tab');
		for (var id = 0; id < mrtabs.length; id++) {
			var mrtab = mrtabs[id];
			mrtab.classList.remove('active','inactive','open');
		}
	}
	if(mrwidThis.classList.contains('active')) {
		//CLOSE ACTIVE
		if(!mrwidLayout.classList.contains('mrwid-donotinactive')) {
			mrwidThis.classList.remove('active','open');
			mrwidThis.classList.add('inactive');
			if(mrwidItemsTabs) {
				var mrwidTabId = mrwidThis.className.split(" ")[0];
				var mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
				if(mrwidTab)  {
					mrwidTab.classList.remove('active','inactive','open');
					mrwidTab.classList.add('inactive');
				}
			}
		}
		//ONLY SHOW SUBCATEGORIES OF ACTIVE + ON ACTIVE HIDE INACTIVE
		//With those options, when clicking an active item go back to the beginning, removing all states:
		if(mrwidLayout.classList.contains('mrwid-subcatactive') && mrwidLayout.classList.contains('mrwid-hideinactives')) {
			var mrwids = mrwidPage.querySelectorAll('.mr-wid');
			if(mrwids)  {
				for (var id = 0; id < mrwids.length; id++) {
					if(mrwids[id].classList.contains('mrwid-subcat')) {
						mrwids[id].classList.add('mrwid-hidden');
					}
					mrwids[id].classList.remove('active','inactive','open');
					if(mrwidItemsTabs) {
						var mrwidTabId = mrwids[id].className.split(" ")[0];
						var mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
						if(mrwidTab)  {
							mrwidTab.classList.remove('active','inactive','open');
						}
					}
				}
			}
		}
	} else {
		var mrwids = mrwidPage.querySelectorAll('.mr-wid');
		//ES6:
		//mrwids.forEach(x => x.classList.remove('active','open','mrwid-scroll'));
		//mrwids.forEach(x => x.classList.add('inactive'));
		for (var id = 0; id < mrwids.length; id++)  {
			if(mrwidLayout.classList.contains('mrwid-keepactive')) {
				if(!mrwids[id].classList.contains('active')) {
					mrwids[id].classList.add('inactive');
					if(mrwidItemsTabs) {
						var mrwidTabId = mrwids[id].className.split(" ")[0];
						var mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
						if(mrwidTab)  {
							mrwidTab.classList.add('inactive');
						}
					}
				}
			} else {
				mrwids[id].classList.remove('active','open','mrwid-scroll');
				mrwids[id].classList.add('inactive');
				if(mrwidItemsTabs) {
					var mrwidTabId = mrwids[id].className.split(" ")[0];
					var mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
					if(mrwidTab)  {
						mrwidTab.classList.remove('active','open','mrwid-scroll');
						mrwidTab.classList.add('inactive');
					}
				}
			}
		}
		mrwidThis.classList.remove('inactive');
		mrwidThis.classList.add('active');
		if(mrwidItemsTabs) {
			var mrwidTabId = mrwidThis.className.split(" ")[0];
			var mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
			if(mrwidTab)  {
				mrwidTab.classList.remove('inactive');
				mrwidTab.classList.add('active');
			}
		}
		//AUTO SCROLL
		if(mrwidLayout.classList.contains('mrwid-autoscroll')) {
			if(mrwidLayout.classList.contains('mrwid-windowheight')) {
				var rect = mrwidPage.getBoundingClientRect();
			} else {
				var rect = mrwidThis.getBoundingClientRect();
			}
			var elementoffset = rect.top + window.pageYOffset;
			mrScrollTo(elementoffset,500);
		}
		//CHANGE URL ON ACTIVE
		if(mrwidLayout.classList.contains('mrwid-url')) {
			history.pushState("object or string", mrwidThis.querySelector('.mrwid-title').textContent, mrwidThis.getAttribute('url'));
		}
		//REMEMBER LAST ACTIVE
		if(mrwidLayout.classList.contains('mrwid-remember')) {
			var CookieDate = new Date;
			CookieDate.setFullYear(CookieDate.getFullYear() +1);
			var Classes = mrwidThis.getAttribute('class');
			var firstClass = Classes.indexOf(" ");
			document.cookie = 'mrwidRemember='+Classes.substring(0,firstClass)+'; expires=' + CookieDate.toGMTString() + '; path=/';
		} else {
			if (mrGetCookie("mrwidRemember") != "") {
				var CookieDate = new Date;
				CookieDate.setFullYear(CookieDate.getFullYear() -1);
				document.cookie = 'mrwidRemember=; expires=' + CookieDate.toGMTString() + '; path=/';
			}
		}
		//ONLY SHOW SUBCATEGORIES OF ACTIVE
		if(mrwidLayout.classList.contains('mrwid-subcatactive')) {
			var mrwidSubCats = mrwidPage.querySelectorAll('.mrwid-subcat.parent'+mrwidThis.classList[0]);
			if(mrwidSubCats)  {
				for (var id = 0; id < mrwidSubCats.length; id++) {
					if(mrwidSubCats[id].classList.contains('parent'+mrwidThis.classList[0])) {
						mrwidSubCats[id].classList.remove('mrwid-hidden');
					} else {
						mrwidSubCats[id].classList.add('mrwid-hidden');
					}
				}
			}
		}
	}
	//If after click there are no actives, remove the inactive state of all items and tabs:
	var mrwidCheckState = mrwidPage.querySelectorAll('.active');
	if(!mrwidCheckState.length) {
		var mrwids = mrwidPage.querySelectorAll('.mr-wid');
		for (var id = 0; id < mrwids.length; id++)  {
			mrwids[id].classList.remove('inactive');
		}
	}
	if(mrwidItemsTabs) {
		var mrwidCheckState = mrwidItemsTabs.querySelectorAll('.mr-tab.active');
		if(!mrwidCheckState.length) {
			var mrtabs = mrwidItemsTabs.querySelectorAll('.mr-tab');
			if (mrtabs) {
				for (var id = 0; id < mrtabs.length; id++)  {
					mrtabs[id].classList.remove('inactive');
				}
			}
		}
	}
}
function mrwidTabs() {
	var mrwidItemsTabs = document.querySelectorAll('.mrwid-tabs .mr-tab');
	if(mrwidItemsTabs) {
		for (var id = 0; id < mrwidItemsTabs.length; id++)  {
			var mrwidItemTab = mrwidItemsTabs[id];
			var mrwidTabId = mrwidItemTab.className.split(" ")[0];
			var mrwidLayout = mrwidItemTab.closest('.mrwid-layout');
			var mrwidThis = mrwidLayout.querySelector('.mrwid-pages.active .mr-wid.'+mrwidTabId);
			if(mrwidThis) {
				if(mrwidItemTab.classList.contains('mrwid-hidden')) {
					mrwidItemTab.classList.remove('mrwid-hidden');
				}
			} else {
				mrwidItemTab.classList.add('mrwid-hidden');
			}
		}
	}
}
function mrwidTabsChange(mrwidThis) {
	var mrwidTabId = mrwidThis.className.split(" ")[0];
	var mrwidLayout = mrwidThis.closest('.mrwid-layout');
	var mrwidTabs = mrwidLayout.querySelectorAll('.mr-tab');
	var mrwidActiveTab = mrwidLayout.querySelector('.mr-tab.'+mrwidTabId);
	var mrWids = mrwidLayout.querySelectorAll('.mrwid-pages.active .mr-wid');
	var mrWidsInactive = mrwidLayout.querySelectorAll('.mrwid-pages.active .mr-wid.inactive');
		if(/*!mrwidLayout.classList.contains('mrwid-donotinactive') && */mrwidThis.classList.contains('active')) {
			if(mrwidTabs) {
				for (var id = 0; id < mrwidTabs.length; id++)  {
					var mrwidTab = mrwidTabs[id];
					mrwidTab.classList.remove('active','inactive');
				}
			}
			if(mrWids) {
				for (var id = 0; id < mrWids.length; id++)  {
					var mrWid = mrWids[id];
					mrWid.classList.remove('active','inactive');
				}
			}
		} else {
			if(mrwidTabs && !mrwidLayout.classList.contains('mrwid-keepactive')) {
				for (var id = 0; id < mrwidTabs.length; id++)  {
					var mrwidTab = mrwidTabs[id];
					mrwidTab.classList.remove('active');
					mrwidTab.classList.add('inactive');
				}
			}
			if(mrWids) {
				for (var id = 0; id < mrWids.length; id++)  {
					var mrWid = mrWids[id];
					if(mrWid.classList.contains(mrwidTabId)) {
						mrWid.classList.remove('inactive');
						mrWid.classList.add('active');
						if(mrwidActiveTab) {
							mrwidActiveTab.classList.remove('inactive');
							mrwidActiveTab.classList.add('active');
						}
					} else {
						if(!mrWidsInactive.length || !mrwidLayout.classList.contains('mrwid-keepactive')) {
							mrWid.classList.remove('active');
							mrWid.classList.add('inactive');
						}
					}
				}
			}
		}
		var mrwidCheckState = mrwidLayout.querySelectorAll('.mr-tab.active');
		if(!mrwidCheckState.length) {
			if(mrwidTabs) {
				for (var id = 0; id < mrwidTabs.length; id++)  {
					var mrwidTab = mrwidTabs[id];
					mrwidTab.classList.remove('active','inactive');
				}
			}
			if(mrWids) {
				for (var id = 0; id < mrWids.length; id++)  {
					var mrWid = mrWids[id];
					mrWid.classList.remove('active','inactive');
				}
			}
		}
}
function mrwidChangePage(currentElement,mrwidLayout,mrwidPage) {
	if(!!mrwidLayout.querySelector('.mrwid-pageselect option[value="'+mrwidPage+'"]')) {
		var mrwidPageSelect = mrwidLayout.querySelector('.mrwid-pageselect');
		var mrwidRadios = mrwidLayout.querySelectorAll('.mrwid-radio');
		var mrwidCurrentRadio = mrwidLayout.querySelector('.mrwid-radio[value="'+mrwidPage+'"]');
		if(mrwidPageSelect) {
			mrwidPageSelect.value = mrwidPage;
		}
		//ES6:
		//for (var mrwidRadio of mrwidRadios)
		if(mrwidRadios) {
			for (var id = 0; id < mrwidRadios.length; id++) {
				mrwidRadios[id].removeAttribute('checked');
			}
		}
		if(mrwidCurrentRadio) {
			mrwidCurrentRadio.setAttribute('checked','checked');
		}
		mrwidLayout.classList.remove('mrwid-transitionright','mrwid-transitionleft');
		if(currentElement.classList.contains('mrwid-next')) {
			mrwidLayout.classList.add('mrwid-transitionright');
		} else if(currentElement.classList.contains('mrwid-prev')) {
			mrwidLayout.classList.add('mrwid-transitionleft');
		}
		/*
		var mrwidInitHeight = parseInt(getComputedStyle(mrwidLayout).height);
		mrwidLayout.style.minHeight = mrwidInitHeight+'px';
		*/
		if(!!mrwidLayout.querySelector('.mrwid-page'+mrwidPage+' noscript')) {
			mrwidLayout.querySelector('.mrwid-page'+mrwidPage).innerHTML = mrwidLayout.querySelector('.mrwid-page'+mrwidPage+' noscript').textContent;
		}
		var mrwidPages = mrwidLayout.querySelectorAll('.mrwid-pages');
		var mrwidActivePages = mrwidLayout.querySelectorAll('.mrwid-pages.active');
		var mrwidNewPage = mrwidLayout.querySelector('.mrwid-page'+mrwidPage);
		for (var id = 0; id < mrwidActivePages.length; id++) {
			var mrwidActivePage = mrwidActivePages[id];
			mrwidActivePage.classList.remove('active','inactive','open');
		}
		setTimeout(function() {
			for (var id = 0; id < mrwidPages.length; id++) {
				var mrwidPage = mrwidPages[id];
				mrwidPage.classList.remove('active');
				mrwidPage.classList.add('inactive');
			}
			mrwidNewPage.classList.remove('inactive');
			mrwidNewPage.classList.add('active');
			if(mrwidNewPage.nextElementSibling && mrwidNewPage.nextElementSibling.classList.contains('mrwid-pages') && mrwidLayout.querySelector('.mrwid-below')) {
				mrwidLayout.querySelector('.mrwid-below').classList.remove('mrwid-hidden');
			} else if(mrwidLayout.querySelector('.mrwid-below')) {
				mrwidLayout.querySelector('.mrwid-below').classList.add('mrwid-hidden');
			}
		}, 400);
	}
	setTimeout(function() {
		/*if(mrwidLayout.classList.contains('mrwid-contentpagination')) {
			var mrwidBackground = mrwidLayout.querySelector('.mrwid-1perpage.mrwid-1perline.active .mrwid-container');
			if(mrwidBackground) {
				mrwidBackground = mrwidLayout.querySelector('.mrwid-1perpage.mrwid-1perline.active .mrwid-container').style.backgroundImage;
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
	var mrwidLayout = currentElement.parentElement;
	var mrwidPage = mrwidLayout.querySelector('.mrwid-pageselect');
	if(mrwidPage) {
		var mrwidPage = mrwidPage.value;
		var mrwidPageLastValue = mrwidLayout.querySelector('.mrwid-pageselect option:last-child').value;
		if(mrwidPage < parseInt(mrwidPageLastValue)) {
			var mrwidPage = parseInt(mrwidPage)+1;
		} else {
			var mrwidPage = 1;
		}
		mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
	}
}
function mrwidPrev(currentElement) {
	var mrwidLayout = currentElement.parentElement;
	var mrwidPage = mrwidLayout.querySelector('.mrwid-pageselect').value;
	var mrwidPageFirstValue = mrwidLayout.querySelector('.mrwid-pageselect option:first-child').value;
	var mrwidPageLastValue = mrwidLayout.querySelector('.mrwid-pageselect option:last-child').value;
	if(mrwidPage == parseInt(mrwidPageFirstValue)) {
		var mrwidPage = parseInt(mrwidPageLastValue);
	} else {
		var mrwidPage = mrwidPage-1;
	}
	mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
}
function mrwidBelow(currentElement) {
	var mrwidLayout = currentElement.parentElement;
	var mrwidPage = mrwidLayout.querySelectorAll('.mrwid-pages.active');
	var mrwidLastActive = mrwidPage[mrwidPage.length - 1];
	var mrwidNewPage = mrwidLastActive.nextElementSibling;
	mrwidLayout.classList.remove('mrwid-transitionright','mrwid-transitionleft');
	if(!mrwidPage) {
		mrwidLayout.querySelector('.mrwid-page1').classList.add('active');
		currentElement.style.classList.remove('mrwid-hidden');
	} else if(!!mrwidNewPage && mrwidNewPage.classList.contains('mrwid-pages')) {
		if(!!mrwidNewPage.querySelector('noscript')) {
			mrwidNewPage.innerHTML = mrwidNewPage.querySelector('noscript').textContent;
		}
		mrwidNewPage.classList.remove('inactive');
		mrwidNewPage.classList.add('active');
		//var mrwidNewPageNumber = mrwidNewPage.childElementCount; //THE CHILD NUMBER NEEDS TESTING
		var mrwidNewPage = mrwidLayout.querySelectorAll('.mrwid-pages.active');
		var mrwidNewLastActive = mrwidNewPage[mrwidNewPage.length - 1];
		var mrwidNextPage = mrwidNewLastActive.nextElementSibling;
		/*var mrwidRadios = mrwidLayout.querySelectorAll('.mrwid-radio');
		mrwidLayout.querySelector('.mrwid-pageselect').value = mrwidNewPageNumber;
		if(mrwidRadios) {
			for (var id = 0; id < mrwidRadios.length; id++) {
				mrwidRadios[id].removeAttribute('checked');
			}
		}
		mrwidLayout.querySelector('.mrwid-radio[value="'+mrwidNewPageNumber+'"]').setAttribute('checked','checked');*/
		if(!mrwidNextPage && mrwidLayout.querySelector('.mrwid-below') || !mrwidNextPage.classList.contains('mrwid-pages') && mrwidLayout.querySelector('.mrwid-below')) {
			mrwidLayout.querySelector('.mrwid-below').classList.add('mrwid-hidden');
		}
	} else {
		mrwidLayout.querySelector('.mrwid-below').classList.add('mrwid-hidden');
	}
	setTimeout(function() {
		currentElement.classList.remove('loading');
		mrwidTabs();
	}, 600);
}
//AUTOPLAY PAGES
function mrAutoPlay() {
	var mrwidsAutoPlay = document.querySelectorAll('.mrwid-layout[class*=mrwid-autoplay]');
	if (mrwidsAutoPlay) {
		for (var id = 0; id < mrwidsAutoPlay.length; id++)  {
			var currentElement = mrwidsAutoPlay[id];
			//var mrwidAutoPlayClass = currentElement.classList.item(currentElement.classList.length-1);
			var mrwidAutoPlayClasses = [].slice.call(currentElement.classList).toString();
			var mrwidAutoPlayClass = mrwidAutoPlayClasses.substring(mrwidAutoPlayClasses.lastIndexOf("mrwid-autoplay") + 14, mrwidAutoPlayClasses.lastIndexOf("s"));
			//if (mrIsInView(currentElement)) {
				if(!currentElement.classList.contains('mrwid-hovering')) {
					mrwidNext(currentElement);
				}
			//}
			setTimeout(mrAutoPlay,parseInt(mrwidAutoPlayClass)*1000);
		}
	}
}
document.addEventListener('DOMContentLoaded', function() {
	/*var mrwidPages1 = document.querySelectorAll('.mrwid-page1');
	for (var id = 0; id < mrwidPages1.length; id++) {
		var mrwidPage1 = mrwidPages1[id];
		if(mrwidPage1.classList.contains('mrwid-1perline')) {
			if(mrwidPage1.parentElement.classList.contains('mrwid-contentpagination')) {
				var mrwidBackground = mrwidPage1.querySelector('.mrwid-container').style.backgroundImage;
				if(!mrwidBackground) {
					mrwidBackground = 'none';
				}
				mrwidPage1.style.backgroundImage = mrwidBackground;
			}
		}
	}*/
	if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
		var mrwidPages = document.querySelectorAll('.mrwid-theme .mrwid-layout.mrwid-windowheight .mrwid-pages');
		for (var id = 0; id < mrwidPages.length; id++) {
			mrwidPages[id].classList.add('ios-scrolling');
		}
		var mrwidContainers = document.querySelectorAll('.mrwid-theme .mrwid-layout.mrwid-expandactive .mrwid-pages:not(.mrwid-perliner) .mr-wid.active .mrwid-container');
		for (var id = 0; id < mrwidContainers.length; id++) {
			mrwidContainers[id].classList.add('ios-scrolling');
		}
	}
	//ACTIVATE LAST REMEMBERED ACTIVE
	if (mrGetCookie("mrwidRemember") != "") {
		var mrwidRemembered = mrGetCookie("mrwidRemember");
		var mrwids = document.querySelectorAll('.mrwid-remember .mrwid-pages.active .mr-wid');
		if(mrwids) {
			for (var id = 0; id < mrwids.length; id++) {
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
		var checkattr = document.querySelectorAll('.mrwid-url .mr-wid[url]');
		if(checkattr)  {
			for (var id = 0; id < checkattr.length; id++) {
				var getWidUrl = checkattr[id].getAttribute('url');
				if(getWidUrl.indexOf('/./') > -1) {
					var getWidUrl = getWidUrl.replace('./', '');
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
	var mrwidsHeightFix = document.querySelectorAll('.mrwid-windowheight');
	if(mrwidsHeightFix) {
		var vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', vh + 'px');
		window.addEventListener('resize', function() {
			var vh = window.innerHeight * 0.01;
			document.documentElement.style.setProperty('--vh', vh + 'px');
		});
	}
	//CHECK IF THERE IS THE NEED TO AUTOPLAY
	var mrwidAutoPlay = document.querySelector('.mrwid-layout[class*=mrwid-autoplay]');
	if (mrwidAutoPlay) {
		var mrwidAutoPlayClasses = [].slice.call(mrwidAutoPlay.classList).toString();
		var mrwidAutoPlayClass = mrwidAutoPlayClasses.substring(mrwidAutoPlayClasses.lastIndexOf("mrwid-autoplay") + 14, mrwidAutoPlayClasses.lastIndexOf("s"));
		setTimeout(mrAutoPlay,parseInt(mrwidAutoPlayClass)*1000);
	}
	//ON MOUSEOVER/MOUSELEAVE
	var mrwidsHover = document.querySelectorAll('.mrwid-layout.mrwid-hover,.mrwid-layout[class*=mrwid-autoplay]');
	if (mrwidsHover) {
		//ES6:
		//for (var mrwidHover of mrwidsHover) 
		for (var id = 0; id < mrwidsHover.length; id++)  {
			mrwidsHover[id].addEventListener('mouseover',function(event) {
				if (event.target.matches('.mrwid-layout.mrwid-hover .mr-wid')) {
					mrwidMain(event.target);
					event.stopPropagation();
				} else if (event.target.matches('.mrwid-layout.mrwid-hover .mr-tab')) {
					var mrwidThis = event.target;
					mrwidTabsChange(mrwidThis);
					event.stopPropagation();
				}
				if (event.target.matches('.mrwid-layout[class*=mrwid-autoplay] .mr-wid') || event.target.matches('.mrwid-layout[class*=mrwid-autoplay] .mr-tab') || event.target.matches('.mrwid-layout[class*=mrwid-autoplay] .mrwid-pagination') || event.target.matches('.mrwid-layout[class*=mrwid-autoplay] .mrwid-arrows') || event.target.matches('.mrwid-layout[class*=mrwid-autoplay] .mrwid-below')) {
					event.target.closest('.mrwid-layout').classList.add('mrwid-hovering');
				}
			});
			mrwidsHover[id].addEventListener('mouseleave', function(event) {
				if(event.target.classList.contains('mrwid-hovering')) {
					event.target.classList.remove('mrwid-hovering');
				}
				var mrwid = event.target.querySelectorAll('.mrwid-layout.mrwid-hover .mrwid-pages.active .mr-wid');
				if (mrwid) {
					for (var id = 0; id < mrwid.length; id++)  {
						mrwid[id].classList.remove('active','inactive');
					}
					if(event.target.classList.contains('mrwid-subcatactive')) {
						var mrwidSubcats = event.target.querySelectorAll('.mrwid-pages.active .mrwid-subcat');
						if (mrwidSubcats) {
							for (var id = 0; id < mrwidSubcats.length; id++)  {
								mrwidSubcats[id].classList.add('mrwid-hidden');
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
	if (event.target.matches('.mrwid-layout:not(.mrwid-hover) .mr-wid') || event.target.matches('.mrwid-layout:not(.mrwid-hover) .mrwid-container') || event.target.matches('.mrwid-layout:not(.mrwid-hover) .mrwid-image') || event.target.matches('.mrwid-layout:not(.mrwid-hover) .mrwid-title') || event.target.matches('.mrwid-layout:not(.mrwid-hover) .mr-wid:not(.active) .mrwid-content, .mrwid-layout:not(.mrwid-hover) .mr-wid:not(.active) .mrwid-content *')) {
		var mrwidThis = event.target;
		mrwidMain(mrwidThis);
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-tabs .mr-tab')) {
		var mrwidThis = event.target;
		mrwidTabsChange(mrwidThis);
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-layout:not(.mrwid-hover) a:not([href="#"]):not([href="javascript:void(0)"])')) {
		//When changing page from inside the widget, add a 'open' class to do a transition/animation:
		var mrwidThis = event.target.closest('.mr-wid');
		mrwidThis.classList.add('open');
		event.stopPropagation();
	//PAGE TOGGLES
	} else if (event.target.matches('.mrwid-next')) {
		var currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidNext(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-prev')) {
		var currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidPrev(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-radio')) {
		var currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			var mrwidLayout = currentElement.closest('.mrwid-layout');
			var mrwidPage = currentElement.value;
			mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-pageselect')) {
		event.target.addEventListener('change',function(event) {
			var currentElement = event.target;
			if(!currentElement.classList.contains('loading')) {
				currentElement.classList.add('loading');
				var mrwidLayout = currentElement.closest('.mrwid-layout');
				var mrwidPage = currentElement.value;
				mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
			}
		});
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-below') || event.target.matches('.mrwid-scroll')) {
		var currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidBelow(currentElement);
		}
		event.stopPropagation();
	}
});
document.addEventListener('keydown',function(event) {
	var mrwidKeyboard = document.querySelectorAll('.mrwid-keyboard');
	if(mrwidKeyboard) {
		for (var id = 0; id < mrwidKeyboard.length; id++)  {
			var currentElement = mrwidKeyboard[id];
			if (mrIsInView(currentElement)) {
				if(!currentElement.classList.contains('loading')) {
					currentElement.classList.add('loading');
					var mrwidLayout = currentElement.parentElement;
					if (event.keyCode == 39) { mrwidNext(currentElement); return false;
					} else if (event.keyCode == 37) { mrwidPrev(currentElement); return false;
					} else if (event.keyCode == 40) { mrwidBelow(currentElement); return false;
					} else if (event.keyCode == 49 || event.keyCode == 97) { var mrwidPage = 1; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 50 || event.keyCode == 98) { var mrwidPage = 2; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 51 || event.keyCode == 99) { var mrwidPage = 3; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 52 || event.keyCode == 100) { var mrwidPage = 4; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 53 || event.keyCode == 101) { var mrwidPage = 5; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 54 || event.keyCode == 102) { var mrwidPage = 6; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 55 || event.keyCode == 103) { var mrwidPage = 7; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 56 || event.keyCode == 104) { var mrwidPage = 8; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					} else if (event.keyCode == 57 || event.keyCode == 105) { var mrwidPage = 9; mrwidChangePage(currentElement,mrwidLayout,mrwidPage); return false;
					}
				}
			}
		}
	}
});
var scrollTimer;
var initst;
var mrparIntensity = 3; //Change intensity/speed of the background image scroll
var mrparSize = 15; //Change size of the parallax
var wh = window.innerHeight;
var bgtoplimit = (wh*mrparSize/100);
var bgtop = 0;
window.addEventListener('scroll',function(event) {
	//SCROLL PAGE TOGGLE
	var mrwidsScroll = document.querySelectorAll('.mrwid-scroll');
	if (mrwidsScroll) {
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(function() {
			//ES6:
			//for (var mrwidScroll of mrwidsScroll)
			for (var id = 0; id < mrwidsScroll.length; id++)  {
				var currentElement = mrwidsScroll[id];
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
	//BACKGROUND PARALLAX
	var mrparElements = document.querySelectorAll(/*'.mrwid-theme .mrwid-layout.mrwid-parallax:not(.mrwid-thumbnail),*/'.mrwid-theme .mrwid-layout.mrwid-parallax:not(.mrwid-thumbnail) .mr-wid .mrwid-container, .mrwid-theme .mrwid-layout.mrwid-parallax:not(.mrwid-thumbnail) .mr-wid .mrwid-container .mrwid-image, .mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail.mrwid-background, .mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail.mrwid-background .mr-wid .mrwid-container, .mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail.mrwid-background .mr-wid .mrwid-container .mrwid-image');
	var st = window.pageYOffset;
	if(!initst || initst == 0 || st > initst) {
		bgtop = bgtop + mrparIntensity;
	} else if(st < initst) {
		if(bgtop > 0) {
			bgtop = bgtop - mrparIntensity;
		}
	}
	initst = st;
	if(mrparElements) {
		for (var id = 0; id < mrparElements.length; id++)  {
			var currentElement = mrparElements[id];
			var elemoffset = (currentElement.offsetTop - wh);
			if(st > elemoffset) {
				if(bgtop < bgtoplimit) {
					var bgoffset = (elemoffset-st);
					if(bgoffset < 0) {
						bgoffset = 0;
					}
					currentElement.style.backgroundSize = 'auto '+(100+mrparSize)+'vh'; //landscape
					//currentElement.style.backgroundSize = (100+mrparSize)+'vh auto'; //portrait
					currentElement.style.backgroundPosition = 'top -'+(bgoffset+bgtop)+'px center';
				}
			}
		}
	}
	//THUMBNAIL 'PARALLAX'
	var mrthumbparElements = document.querySelectorAll('.mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail .mrwid-pages.active .mr-wid .mrwid-container .mrwid-image img');
	if(mrthumbparElements) {
		for (var id = 0; id < mrthumbparElements.length; id++)  {
			var currentElement = mrthumbparElements[id];
			currentElement.style.transform = "translateY(-"+(st*.04)+"px)";
		}
	}
});
