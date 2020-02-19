function mrwidMain(mrwidThis) {
	if (!mrwidThis.matches('.mr-wid')) {
		mrwidThis = event.target.closest('.mr-wid');
	}
	const mrwidLayout = mrwidThis.closest('.mrwid-layout');
	const mrwidPage = mrwidThis.closest('.mrwid-pages');
	const mrwidItemsTabs = mrwidLayout.querySelector('.mrwid-tabs.mrwid-items');
	const mrwidTabs = mrwidLayout.querySelector('.mrwid-tabs:not(.mrwid-items)');
	/*start donotinactive*/
	if(mrwidTabs && !mrwidLayout.classList.contains('mrwid-donotinactive')) {
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
function mrGetCookie(cname) {
	const name = cname + "=";
	const decodedCookie = decodeURIComponent(document.cookie);
	const ca = decodedCookie.split(';');
	for (id = 0; id < ca.length; id++) {
		let c = ca[id];
		while (c.charAt(0) === ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) === 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}
function mrIsInView(currentElement) {
	const docViewTop = window.pageYOffset;
	const docViewBottom = docViewTop + window.innerHeight;
	const elemTop = currentElement.offsetTop;
	const elemBottom = elemTop + parseInt(getComputedStyle(currentElement).height);
	return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
function mrScrollTo(to, duration) {
    element = document.scrollingElement || document.documentElement,
    start = element.scrollTop,
    change = to - start,
    startDate = +new Date(),
    easeInOutQuad = function(t, b, c, d) {
        t /= d/2;
        if (t < 1) return c/2*t*t + b;
        t--;
        return -c/2 * (t*(t-2) - 1) + b;
    },
    animateScroll = function() {
		const currentDate = +new Date();
        const currentTime = currentDate - startDate;
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
function mrParallax(mrParElements) {
	mrParElements = document.querySelectorAll(mrParElements);
	if(mrParElements) {
		var motionQuery = matchMedia('(prefers-reduced-motion)');
		if (!motionQuery.matches) {
			for (id = 0; id < mrParElements.length; id++)  {
				const currentElement = mrParElements[id];
				let x = currentElement.getBoundingClientRect().top / 6;
				let y = Math.round(x * 100) / 100;
				currentElement.style.backgroundPosition = 'center ' + y + 'px';
			}
		}
	}
}
function mrChangeStatus(mrwidThis,mrwidLayout,mrwidPage,mrwidItemsTabs) {
	const mrwids = mrwidPage.querySelectorAll('.mr-wid');
	//ES6:
	//mrwids.forEach(x => x.classList.remove('active','open','mrwid-scroll'));
	//mrwids.forEach(x => x.classList.add('inactive'));
	for (id = 0; id < mrwids.length; id++)  {
		if(mrwidLayout.classList.contains('mrwid-keepactive')) {
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
		} else if(!mrwidLayout.classList.contains('mrwid-keepactive')) {
			mrwids[id].classList.remove('active','open','mrwid-scroll');
			mrwids[id].classList.add('inactive');
			if(mrwidItemsTabs) {
				const mrwidTabId = mrwids[id].className.split(" ")[0];
				const mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
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
		const mrwidTabId = mrwidThis.className.split(" ")[0];
		const mrwidTab = mrwidItemsTabs.querySelector('.mr-tab.'+mrwidTabId);
		if(mrwidTab)  {
			mrwidTab.classList.remove('inactive');
			mrwidTab.classList.add('active');
		}
	}
	/*start autoscroll*/
	if(mrwidLayout.classList.contains('mrwid-autoscroll')) {
		let rect = mrwidThis.getBoundingClientRect();
		if(mrwidLayout.classList.contains('mrwid-windowheight')) {
			rect = mrwidPage.getBoundingClientRect();
		}
		const elementoffset = rect.top + window.pageYOffset;
		mrScrollTo(elementoffset,500);
	}
	/*end autoscroll*/
	/*start url*/
	if(mrwidLayout.classList.contains('mrwid-url')) {
		history.pushState("object or string", mrwidThis.querySelector('.mrwid-title').textContent, mrwidThis.getAttribute('url'));
	}
	/*end url*/
	/*start remember*/
	if(mrwidLayout.classList.contains('mrwid-remember')) {
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
	if(mrwidLayout.classList.contains('mrwid-subitemactive')) {
		const mrwidSubCats = mrwidPage.querySelectorAll('.mrwid-subitem.parent'+mrwidThis.classList[0]);
		if(mrwidSubCats)  {
			for (id = 0; id < mrwidSubCats.length; id++) {
				if(mrwidSubCats[id].classList.contains('parent'+mrwidThis.classList[0])) {
					mrwidSubCats[id].classList.remove('mrwid-hidden');
				} else {
					mrwidSubCats[id].classList.add('mrwid-hidden');
				}
			}
		}
	}
	/*end subitemactive*/
}
function mrCloseActive(mrwidThis,mrwidLayout,mrwidPage,mrwidItemsTabs) {
	/*start donotinactive*/
	if(!mrwidLayout.classList.contains('mrwid-donotinactive')) {
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
	if(mrwidLayout.classList.contains('mrwid-subitemactive') && mrwidLayout.classList.contains('mrwid-hideinactives')) {
		//mrSubItemOnActive(mrwidPage,mrwidItemsTabs);
		//ONLY SHOW SUBCATEGORIES OF ACTIVE + ON ACTIVE HIDE INACTIVE
		//With those options, when clicking an active item go back to the beginning, removing all states:
		const mrwids = mrwidPage.querySelectorAll('.mr-wid');
		if(mrwids)  {
			for (id = 0; id < mrwids.length; id++) {
				if(mrwids[id].classList.contains('mrwid-subitem')) {
					mrwids[id].classList.add('mrwid-hidden');
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
		const mrwids = mrwidPage.querySelectorAll('.mr-wid');
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
	if(!!mrwidLayout.querySelector('.mrwid-pageselect option[value="'+mrwidPage+'"]')) {
		const mrwidPageSelect = mrwidLayout.querySelector('.mrwid-pageselect');
		const mrwidRadios = mrwidLayout.querySelectorAll('.mrwid-radio');
		const mrwidCurrentRadio = mrwidLayout.querySelector('.mrwid-radio[value="'+mrwidPage+'"]');
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
		mrwidLayout.classList.remove('mrwid-transitionright','mrwid-transitionleft');
		if(currentElement.classList.contains('mrwid-next')) {
			mrwidLayout.classList.add('mrwid-transitionright');
		} else if(currentElement.classList.contains('mrwid-prev')) {
			mrwidLayout.classList.add('mrwid-transitionleft');
		}
		/*
		const mrwidInitHeight = parseInt(getComputedStyle(mrwidLayout).height);
		mrwidLayout.style.minHeight = mrwidInitHeight+'px';
		*/
		if(!!mrwidLayout.querySelector('.mrwid-page'+mrwidPage+' noscript')) {
			mrwidLayout.querySelector('.mrwid-page'+mrwidPage).innerHTML = mrwidLayout.querySelector('.mrwid-page'+mrwidPage+' noscript').textContent;
		}
		const mrwidPages = mrwidLayout.querySelectorAll('.mrwid-pages');
		const mrwidActivePages = mrwidLayout.querySelectorAll('.mrwid-pages.active');
		const mrwidNewPage = mrwidLayout.querySelector('.mrwid-page'+mrwidPage);
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
			if(mrwidNewPage.nextElementSibling && mrwidNewPage.nextElementSibling.classList.contains('mrwid-pages') && mrwidLayout.querySelector('.mrwid-below')) {
				mrwidLayout.querySelector('.mrwid-below').classList.remove('mrwid-hidden');
			} else if(mrwidLayout.querySelector('.mrwid-below')) {
				mrwidLayout.querySelector('.mrwid-below').classList.add('mrwid-hidden');
			}
		}, 400);
	}
	setTimeout(function() {
		/*if(mrwidLayout.classList.contains('mrwid-contentpagination')) {
			const mrwidBackground = mrwidLayout.querySelector('.mrwid-1perpage.mrwid-1perline.active .mrwid-container');
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
	const mrwidLayout = currentElement.parentElement;
	let mrwidPage = mrwidLayout.querySelector('.mrwid-pageselect');
	if(mrwidPage) {
		mrwidPage = mrwidPage.value;
		const mrwidPageLastValue = mrwidLayout.querySelector('.mrwid-pageselect option:last-child').value;
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
	let mrwidPage = mrwidLayout.querySelector('.mrwid-pageselect').value;
	const mrwidPageFirstValue = mrwidLayout.querySelector('.mrwid-pageselect option:first-child').value;
	const mrwidPageLastValue = mrwidLayout.querySelector('.mrwid-pageselect option:last-child').value;
	if(mrwidPage === parseInt(mrwidPageFirstValue)) {
		mrwidPage = parseInt(mrwidPageLastValue);
	} else {
		mrwidPage = mrwidPage-1;
	}
	mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
}
function mrwidBelow(currentElement) {
	const mrwidLayout = currentElement.parentElement;
	const mrwidPage = mrwidLayout.querySelectorAll('.mrwid-pages.active');
	const mrwidLastActive = mrwidPage[mrwidPage.length - 1];
	let mrwidNewPage = mrwidLastActive.nextElementSibling;
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
		//const mrwidNewPageNumber = mrwidNewPage.childElementCount; //THE CHILD NUMBER NEEDS TESTING
		mrwidNewPage = mrwidLayout.querySelectorAll('.mrwid-pages.active');
		const mrwidNewLastActive = mrwidNewPage[mrwidNewPage.length - 1];
		const mrwidNextPage = mrwidNewLastActive.nextElementSibling;
		/*const mrwidRadios = mrwidLayout.querySelectorAll('.mrwid-radio');
		mrwidLayout.querySelector('.mrwid-pageselect').value = mrwidNewPageNumber;
		if(mrwidRadios) {
			for (id = 0; id < mrwidRadios.length; id++) {
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
function mrAutoPlay() {
	const mrwidsAutoPlay = document.querySelectorAll('.mrwid-layout[class*=mrwid-autoplay]');
	if (mrwidsAutoPlay) {
		for (id = 0; id < mrwidsAutoPlay.length; id++)  {
			const currentElement = mrwidsAutoPlay[id];
			//const mrwidAutoPlayClass = currentElement.classList.item(currentElement.classList.length-1);
			const mrwidAutoPlayClasses = [].slice.call(currentElement.classList).toString();
			const mrwidAutoPlayClass = mrwidAutoPlayClasses.substring(mrwidAutoPlayClasses.lastIndexOf("mrwid-autoplay") + 14, mrwidAutoPlayClasses.lastIndexOf("s"));
			//if (mrIsInView(currentElement)) {
				if(!currentElement.classList.contains('mrwid-hovering')) {
					mrwidNext(currentElement);
				}
			//}
			setTimeout(mrAutoPlay,parseInt(mrwidAutoPlayClass)*1000);
		}
	}
}
function mrwidTabs() {
	const mrwidItemsTabs = document.querySelectorAll('.mrwid-tabs .mr-tab');
	if(mrwidItemsTabs) {
		for (id = 0; id < mrwidItemsTabs.length; id++)  {
			const mrwidItemTab = mrwidItemsTabs[id];
			const mrwidTabId = mrwidItemTab.className.split(" ")[0];
			const mrwidLayout = mrwidItemTab.closest('.mrwid-layout');
			const mrwidThis = mrwidLayout.querySelector('.mrwid-pages.active .mr-wid.'+mrwidTabId);
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
	const mrwidTabId = mrwidThis.className.split(" ")[0];
	const mrwidLayout = mrwidThis.closest('.mrwid-layout');
	const mrwidTabs = mrwidLayout.querySelectorAll('.mr-tab');
	const mrwidActiveTab = mrwidLayout.querySelector('.mr-tab.'+mrwidTabId);
	const mrWids = mrwidLayout.querySelectorAll('.mrwid-pages.active .mr-wid');
	const mrWidsInactive = mrwidLayout.querySelectorAll('.mrwid-pages.active .mr-wid.inactive');
		if(/*!mrwidLayout.classList.contains('mrwid-donotinactive') && */mrwidThis.classList.contains('active')) {
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
			if(mrwidTabs && !mrwidLayout.classList.contains('mrwid-keepactive')) {
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
						if(!mrWidsInactive.length || !mrwidLayout.classList.contains('mrwid-keepactive')) {
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
	/*const mrwidPages1 = document.querySelectorAll('.mrwid-page1');
	for (id = 0; id < mrwidPages1.length; id++) {
		const mrwidPage1 = mrwidPages1[id];
		if(mrwidPage1.classList.contains('mrwid-1perline')) {
			if(mrwidPage1.parentElement.classList.contains('mrwid-contentpagination')) {
				const mrwidBackground = mrwidPage1.querySelector('.mrwid-container').style.backgroundImage;
				if(!mrwidBackground) {
					mrwidBackground = 'none';
				}
				mrwidPage1.style.backgroundImage = mrwidBackground;
			}
		}
	}*/
	if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
		const mrwidPages = document.querySelectorAll('.mrwid-theme .mrwid-layout.mrwid-windowheight .mrwid-pages');
		for (id = 0; id < mrwidPages.length; id++) {
			mrwidPages[id].classList.add('ios-scrolling');
		}
		const mrwidContainers = document.querySelectorAll('.mrwid-theme .mrwid-layout.mrwid-expandactive .mrwid-pages:not(.mrwid-perliner) .mr-wid.active .mrwid-container');
		for (id = 0; id < mrwidContainers.length; id++) {
			mrwidContainers[id].classList.add('ios-scrolling');
		}
	}
	//ACTIVATE LAST REMEMBERED ACTIVE
	if (mrGetCookie("mrwidRemember") != "") {
		const mrwidRemembered = mrGetCookie("mrwidRemember");
		const mrwids = document.querySelectorAll('.mrwid-remember .mrwid-pages.active .mr-wid');
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
		const checkattr = document.querySelectorAll('.mrwid-url .mr-wid[url]');
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
	const mrwidsHeightFix = document.querySelectorAll('.mrwid-windowheight');
	if(mrwidsHeightFix) {
		const vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', vh + 'px');
		window.addEventListener('resize', function() {
			const vh = window.innerHeight * 0.01;
			document.documentElement.style.setProperty('--vh', vh + 'px');
		});
	}
	//CHECK IF THERE IS THE NEED TO AUTOPLAY
	const mrwidAutoPlay = document.querySelector('.mrwid-layout[class*=mrwid-autoplay]');
	if (mrwidAutoPlay) {
		const mrwidAutoPlayClasses = [].slice.call(mrwidAutoPlay.classList).toString();
		const mrwidAutoPlayClass = mrwidAutoPlayClasses.substring(mrwidAutoPlayClasses.lastIndexOf("mrwid-autoplay") + 14, mrwidAutoPlayClasses.lastIndexOf("s"));
		setTimeout(mrAutoPlay,parseInt(mrwidAutoPlayClass)*1000);
	}
	//ON MOUSEOVER/MOUSELEAVE
	const mrwidsHover = document.querySelectorAll('.mrwid-layout.mrwid-hover,.mrwid-layout[class*=mrwid-autoplay]');
	if (mrwidsHover) {
		//ES6:
		//for (mrwidHover of mrwidsHover) 
		for (id = 0; id < mrwidsHover.length; id++)  {
			mrwidsHover[id].addEventListener('mouseover',function(event) {
				if (event.target.matches('.mrwid-layout.mrwid-hover .mr-wid')) {
					mrwidMain(event.target);
					event.stopPropagation();
				} else if (event.target.matches('.mrwid-layout.mrwid-hover .mr-tab')) {
					const mrwidThis = event.target;
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
				const mrwid = event.target.querySelectorAll('.mrwid-layout.mrwid-hover .mrwid-pages.active .mr-wid');
				if (mrwid) {
					for (id = 0; id < mrwid.length; id++)  {
						mrwid[id].classList.remove('active','inactive');
					}
					if(event.target.classList.contains('mrwid-subitemactive')) {
						const mrwidSubcats = event.target.querySelectorAll('.mrwid-pages.active .mrwid-subitem');
						if (mrwidSubcats) {
							for (id = 0; id < mrwidSubcats.length; id++)  {
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
		const mrwidThis = event.target;
		mrwidMain(mrwidThis);
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-tabs .mr-tab')) {
		const mrwidThis = event.target;
		mrwidTabsChange(mrwidThis);
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-layout:not(.mrwid-hover) a:not([href="#"]):not([href="javascript:void(0)"])')) {
		//When changing page from inside the widget, add a 'open' class to do a transition/animation:
		const mrwidThis = event.target.closest('.mr-wid');
		mrwidThis.classList.add('open');
		event.stopPropagation();
	//PAGE TOGGLES
	} else if (event.target.matches('.mrwid-next')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidNext(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-next span')) {
		const currentElement = event.target.parentElement;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidNext(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-prev')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidPrev(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-prev span')) {
		const currentElement = event.target.parentElement;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidPrev(currentElement);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-radio')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			const mrwidLayout = currentElement.closest('.mrwid-layout');
			const mrwidPage = currentElement.value;
			mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
		}
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-pageselect')) {
		event.target.addEventListener('change',function(event) {
			const currentElement = event.target;
			if(!currentElement.classList.contains('loading')) {
				currentElement.classList.add('loading');
				const mrwidLayout = currentElement.closest('.mrwid-layout');
				const mrwidPage = currentElement.value;
				mrwidChangePage(currentElement,mrwidLayout,mrwidPage);
			}
		});
		event.stopPropagation();
	} else if (event.target.matches('.mrwid-below') || event.target.matches('.mrwid-scroll')) {
		const currentElement = event.target;
		if(!currentElement.classList.contains('loading')) {
			currentElement.classList.add('loading');
			mrwidBelow(currentElement);
		}
		event.stopPropagation();
	}
});
document.addEventListener('keydown',function(event) {
	const mrwidKeyboard = document.querySelectorAll('.mrwid-keyboard');
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
	const mrwidsScroll = document.querySelectorAll('.mrwid-scroll');
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
	mrParallax('.mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-background .mrwid-pages.active .mrwid-image');
	const st = window.pageYOffset;
	initst = st;
	const mrthumbparElements = document.querySelectorAll('.mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail:not(.mrwid-background) .mrwid-pages.active .mrwid-image img');
	if(mrthumbparElements) {
		for (id = 0; id < mrthumbparElements.length; id++)  {
			mrthumbparElements[id].style.transform = "translateY(-"+(st*.04)+"px)";
		}
	}
});