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
		var motionQuery = matchMedia('(prefers-reduced-motion: reduce)');
		if (!motionQuery.matches) {
			for (id = 0; id < mrParElements.length; id++)  {
				const currentElement = mrParElements[id];
				let x = currentElement.getBoundingClientRect().top / 6;
				let y = Math.round(x * 100) / 100;
				currentElement.style.backgroundPositionY = y + 'px';
			}
		}
	}
}