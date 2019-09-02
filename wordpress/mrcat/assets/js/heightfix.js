jQuery(document).ready(function( $ ) {
    if(jQuery('.mrwid-windowheight').length) {
		let vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', `${vh}px`);
	}
	window.addEventListener('resize', () => {
		if(jQuery('.mrwid-windowheight').length) {
			let vh = window.innerHeight * 0.01;
			document.documentElement.style.setProperty('--vh', `${vh}px`);
		}
	});
});