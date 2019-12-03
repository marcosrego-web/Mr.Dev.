function getCookie(cname) {
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
function isScrolledIntoView(elem) {
	var docViewTop = jQuery(window).scrollTop();
	var docViewBottom = docViewTop + jQuery(window).height();
	var elemTop = jQuery(elem).offset().top;
	var elemBottom = elemTop + jQuery(elem).height();
	return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
function mrwidChangePage(currentElement,mrwidPage) {
	if(currentElement.parent().find('.mrwid-pageselect option[value="'+mrwidPage+'"]').length) {
		currentElement.parent().find('.mrwid-pageselect').val(mrwidPage);
		currentElement.parent().find('.mrwid-radio').attr('checked',false);
		currentElement.parent().find('.mrwid-radio[value="'+mrwidPage+'"]').attr('checked',true);
		if(currentElement.parent().find('.mrwid-page'+mrwidPage).find('noscript').length) {
			currentElement.parent().find('.mrwid-page'+mrwidPage).html(currentElement.parent().find('.mrwid-page'+mrwidPage).find('noscript').text());
		}
		if(currentElement.hasClass('mrwid-Slide')) {
			currentElement.parent().find('.mrwid-pages').slideUp().removeClass('active');
			currentElement.parent().find('.mrwid-page'+mrwidPage).slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		} else {
			currentElement.parent().css('background-image',currentElement.parent().find('.mrwid-1perpage.mrwid-1perline.active .mrwid-container').css("background-image"));
			currentElement.parent().find('.mrwid-pages').fadeOut().removeClass('active');
			currentElement.parent().find('.mrwid-page'+mrwidPage).delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
			if(currentElement.parent().find('.mrwid-1perpage.mrwid-1perline.active .mrwid-container').css('background-image') == 'none') {
				currentElement.parent().css('background-image','none');
			}
		}
	}
}
function mrwidNext(currentElement) {
	var mrwidPage = parseInt(currentElement.parent().find('.mrwid-pageselect').val());
	if(mrwidPage < parseInt(currentElement.parent().find('.mrwid-pageselect option:last-child').val())) {
		var mrwidPage = mrwidPage+1;
	} else {
		var mrwidPage = 1;
	}
	mrwidChangePage(currentElement,mrwidPage);
}
function mrwidPrev(currentElement) {
	var mrwidPage = parseInt(currentElement.parent().find('.mrwid-pageselect').val());
	if(mrwidPage == parseInt(currentElement.parent().find('.mrwid-pageselect option:first-child').val())) {
		var mrwidPage = parseInt(currentElement.parent().find('.mrwid-pageselect option:last-child').val());
	} else {
		var mrwidPage = mrwidPage-1;
	}
	mrwidChangePage(currentElement,mrwidPage);
}
function mrwidBelow(currentElement) {
	currentElement.parent().find('.mrwid-below').addClass('loading');
	if(!currentElement.parent().find('.mrwid-pages.active').length) {
		currentElement.parent().find('.mrwid-page1').addClass('active');
	}
	if(currentElement.parent().find('.mrwid-pages.active').last().next().hasClass('mrwid-pages')) {
		if(currentElement.parent().find('.mrwid-pages.active').last().next().find('noscript').length) {
			currentElement.parent().find('.mrwid-pages.active').last().next().html(currentElement.parent().find('.mrwid-pages.active').last().next().find('noscript').text());
		}
		if(currentElement.hasClass('mrwid-Slide')) {
			currentElement.parent().find('.mrwid-pages.active').last().next().slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		} else {
			currentElement.parent().css('background-image','none');
			currentElement.parent().find('.mrwid-pages.active').last().next().delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		}
		if(!currentElement.parent().find('.mrwid-pages.active').last().next().hasClass('mrwid-pages')) {
			currentElement.parent().find('.mrwid-below').remove();
		}
	} else {
		currentElement.parent().find('.mrwid-below').remove();
	}
	setTimeout(function() {
		jQuery('.mrwid-below,.mrwid-scroll').removeClass('loading');
	}, 400);
}
jQuery(document).ready(function( $ ) {
    if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
		jQuery( '.mrwid-theme .mrwid-layout.mrwid-windowheight .mrwid-pages, .mrwid-theme .mrwid-layout.mrwid-expandactive .mrwid-pages:not(.mrwid-perliner) .mr-wid.active .mrwid-container' ).addClass('ios-scrolling');
	}
	window.addEventListener('popstate', function(){
		var checkattr = jQuery('.mr-wid').attr('url');
		if (typeof checkattr !== typeof undefined && checkattr !== false) {
			jQuery('.mr-wid').each(function() {
				var getWidUrl = jQuery(this).attr('url');
				if(getWidUrl.indexOf('/./') > -1) {
					var getWidUrl = jQuery(this).attr('url').replace('./', '');
				}
				if(window.location.href.indexOf(getWidUrl) > -1) {
					jQuery('.mr-wid').removeClass('active');
					jQuery('.mr-wid').removeClass('inactive');
					jQuery(this).addClass('active');
					jQuery('.mr-wid:not(.active)').addClass('inactive');
				}
			});
		}
	});
	jQuery('.mrwid-radio[value="1"]').attr('checked',true);
	jQuery('.mrwid-page1').addClass('active');
	jQuery('.mrwid-remember').each(function() {
		if (getCookie("mrwidRemember") != "") {
			var remembered = '.'+getCookie("mrwidRemember");
			if(jQuery(this).find(remembered).length) {
				jQuery(this).find('.mr-wid').removeClass('active').addClass('inactive');
				jQuery(this).find(remembered).removeClass('inactive').addClass('active');
			}
		}
	});
	jQuery('.mrwid-layout.mrwid-hover').on('mouseover touchstart','.mrwid-pages .mr-wid',function(e) {
		jQuery(this).closest('.mrwid-layout:not(.mrwid-keepopen)').find('.mr-wid').removeClass('active').removeClass('mrwid-scroll').addClass('inactive');
		jQuery(this).removeClass('inactive').addClass('active');
		jQuery(this).removeClass('inactive').addClass('active');
		if(jQuery(this).parent().hasClass('mrwid-autoscroll')) {
			if(jQuery(this).parent().hasClass('mrwid-windowheight')) {
				jQuery('html,body').animate({
					scrollTop: jQuery(this).parent().offset().top
				}, 600);
			} else {
				jQuery('html,body').animate({
					scrollTop: jQuery(this).offset().top
				}, 600);
			}
		}
		if(jQuery(this).closest('.mrwid-layout').hasClass('mrwid-subcatactive')) {
			if(jQuery(this).hasClass('active')) {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat:not(.parent'+jQuery(this).attr('class').split(' ')[0]+'):not(.'+jQuery(this).attr('class').split(' ')[0]+')').hide();
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat.parent'+jQuery(this).attr('class').split(' ')[0]).show();
			} else {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat').hide();
			}
		}
		if (jQuery(this).closest('.mrwid-layout').hasClass('mrwid-url') && !jQuery(e.target).parents(".mrwid-content").length) {
			history.pushState("object or string", jQuery(this).find('.mrwid-title').text(), jQuery(this).attr('url'));
		}
		if(jQuery(this).closest('.mrwid-layout').hasClass('mrwid-remember')) {
			var CookieDate = new Date;
			CookieDate.setFullYear(CookieDate.getFullYear() +1);
			var Classes = jQuery(this).attr('class');
			var firstClass = Classes.indexOf(" ");
			document.cookie = 'mrwidRemember='+Classes.substring(0,firstClass)+'; expires=' + CookieDate.toGMTString() + '; path=/';
		} else {
			if (getCookie("mrwidRemember") != "") {
				var CookieDate = new Date;
				CookieDate.setFullYear(CookieDate.getFullYear() -1);
				document.cookie = 'mrwidRemember=; expires=' + CookieDate.toGMTString() + '; path=/';
			}
		}
	});
	jQuery('.mrwid-layout.mrwid-hover').on('mouseleave',function(e) {
		jQuery(this).find('.active').removeClass('active');
		jQuery(this).find('.inactive').removeClass('inactive');
		if(jQuery(this).hasClass('mrwid-subcatactive')) {
			jQuery(this).find('.mrwid-subcat').hide();
		}
	});
	jQuery('.mrwid-layout:not(.mrwid-hover)').on('click','.mrwid-pages .mr-wid',function(e) {
		if(!jQuery(this).closest('.mrwid-layout').hasClass('mrwid-donotclose') && jQuery(this).hasClass('active') && !jQuery(this).closest('.mrwid-layout').hasClass('mrwid-keepopen')) {
			if(!jQuery(this).find('.mrwid-title').length || jQuery(e.target).closest('.mrwid-content').length === 0) {
				jQuery(this).closest('.mrwid-layout').find('.mr-wid').removeClass('active').removeClass('inactive');
			}
		} else if(!jQuery(this).closest('.mrwid-layout').hasClass('mrwid-donotclose') && jQuery(this).hasClass('active')) {
			if(jQuery(e.target).closest('.mrwid-content').length === 0) {
				jQuery(this).removeClass('active').addClass('inactive');
			}
		} else {
			jQuery(this).closest('.mrwid-layout:not(.mrwid-keepopen)').find('.mr-wid').removeClass('active').removeClass('mrwid-scroll').addClass('inactive');
			jQuery(this).removeClass('inactive').addClass('active');
		}
		if(jQuery(this).closest('.mrwid-layout').hasClass('mrwid-autoscroll')) {
			if(jQuery(this).hasClass('active')) {
				if(jQuery(this).closest('.mrwid-layout').hasClass('mrwid-windowheight')) {
					jQuery('html,body').animate({
						scrollTop: jQuery(this).parent().offset().top
					}, 600);
				} else {
					jQuery('html,body').animate({
						scrollTop: jQuery(this).offset().top
					}, 600);
				}
			}
		}
		if(jQuery(this).closest('.mrwid-layout').hasClass('mrwid-subcatactive')) {
			if(jQuery(this).hasClass('active')) {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat:not(.parent'+jQuery(this).attr('class').split(' ')[0]+'):not(.'+jQuery(this).attr('class').split(' ')[0]+')').hide();
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat.parent'+jQuery(this).attr('class').split(' ')[0]).show();
			} else {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat').hide();
			}
		}
		if (jQuery(this).closest('.mrwid-layout').hasClass('mrwid-url') && !jQuery(e.target).parents(".mrwid-content").length) {
		history.pushState("object or string", jQuery(this).find('.mrwid-title').text(), jQuery(this).attr('url'));
		}
		if(jQuery(this).closest('.mrwid-layout').hasClass('mrwid-remember')) {
			var CookieDate = new Date;
			CookieDate.setFullYear(CookieDate.getFullYear() +1);
			var Classes = jQuery(this).attr('class');
			var firstClass = Classes.indexOf(" ");
			document.cookie = 'mrwidRemember='+Classes.substring(0,firstClass)+'; expires=' + CookieDate.toGMTString() + '; path=/';
		} else {
			if (getCookie("mrwidRemember") != "") {
				var CookieDate = new Date;
				CookieDate.setFullYear(CookieDate.getFullYear() -1);
				document.cookie = 'mrwidRemember=; expires=' + CookieDate.toGMTString() + '; path=/';
			}
		}
	});
	jQuery('.mrwid-pageselect,.mrwid-radio').change(function() {
		var currentElement = jQuery(this).parent();
		var mrwidPage = jQuery(this).val();
		mrwidChangePage(currentElement,mrwidPage);
	});
	jQuery('.mrwid-next').click(function() {
		var currentElement = jQuery(this);
		mrwidNext(currentElement);
	});
	jQuery('.mrwid-prev').click(function() {
		var currentElement = jQuery(this);
		mrwidPrev(currentElement);
	});
	jQuery('.mrwid-below,.mrwid-scroll').click(function() {
		var currentElement = jQuery(this);
		mrwidBelow(currentElement);
	});
	jQuery(document).keydown(function(e){
		jQuery(".mrwid-keyboard").each(function() {
			if (isScrolledIntoView(jQuery(this))) {
				var currentElement = jQuery(this);
				if (e.which == 39) {
					mrwidNext(currentElement);
					return false;
				} else if (e.which == 37) {
					mrwidPrev(currentElement);
					return false;
				} else if (e.which == 40) {
					mrwidBelow(currentElement);
					return false;
				} else if (e.which == 49 || e.which == 97) {
					var mrwidPage = 1;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 50 || e.which == 98) {
					var mrwidPage = 2;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 51 || e.which == 99) { 
					var mrwidPage = 3;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 52 || e.which == 100) {
					var mrwidPage = 4;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 53 || e.which == 101) {
					var mrwidPage = 5;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 54 || e.which == 102) {
					var mrwidPage = 6;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 55 || e.which == 103) {
					var mrwidPage = 7;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 56 || e.which == 104) {
					var mrwidPage = 8;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				} else if (e.which == 57 || e.which == 105) {
					var mrwidPage = 9;
					mrwidChangePage(currentElement,mrwidPage);
					return false;
				}
			}
		});
	});
	var scrollTimer;
	var mrparElements = '.mrwid-theme .mrwid-layout.mrwid-parallax:not(.mrwid-thumbnail), .mrwid-theme .mrwid-layout.mrwid-parallax:not(.mrwid-thumbnail) .mr-wid .mrwid-container, .mrwid-theme .mrwid-layout.mrwid-parallax:not(.mrwid-thumbnail) .mr-wid .mrwid-container .mrwid-image, .mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail.mrwid-background, .mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail.mrwid-background .mr-wid .mrwid-container, .mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail.mrwid-background .mr-wid .mrwid-container .mrwid-image';
	var mrparIntensity = 3;
	var mrparSize = 15;
	var initst = jQuery(document).scrollTop();
	var wh = jQuery(window).height();
	var bgtoplimit = (wh*mrparSize/100);
	var bgtop = 0;
	jQuery(window).scroll(function() {
		var st = jQuery(document).scrollTop();
		if(st > initst) {
			bgtop = bgtop + mrparIntensity;
		} else if(st < initst) {
			if(bgtop > 0) {
				bgtop = bgtop - mrparIntensity;
			}
		}
		initst = st;
		jQuery(mrparElements).each(function() {
			var elemoffset = (jQuery(this).offset().top - wh);
			if(st > elemoffset) {
				if(bgtop < bgtoplimit) {
					var bgoffset = (elemoffset-st);
					if(bgoffset < 0) {
						bgoffset = 0;
					}
					jQuery(this).css({'background-size':'auto '+(100+mrparSize)+'vh','background-position':'top -'+(bgoffset+bgtop)+'px center'});
				}
			}
		});
		jQuery('.mrwid-theme .mrwid-layout.mrwid-parallax.mrwid-thumbnail .mrwid-pages.active .mr-wid .mrwid-container .mrwid-image img').css({'transform':"translateY(-"+(st*.04)+"px)"});
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(function() {
			jQuery(".mrwid-scroll").each(function() {
				if (isScrolledIntoView(jQuery(this))) {
					jQuery(this).click();
				}
			});
		}, 400);
	});
});
