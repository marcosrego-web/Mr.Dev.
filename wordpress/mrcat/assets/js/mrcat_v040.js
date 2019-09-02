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
});

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
		if(!jQuery(this).hasClass('mrwid-subcat')) {
			if(jQuery(this).hasClass('active')) {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat').hide();
				jQuery(this).nextUntil('.mr-wid:not(.mrwid-subcat)').show();
			} else /*if(!jQuery(this).parent().hasClass('mrwid-keepopen'))*/ {
				jQuery(this).nextUntil('.mr-wid:not(.mrwid-subcat)').hide();
			}
		} else {
			if(!jQuery(this).hasClass('active')) {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat').hide();
			} /*else {
				jQuery(this).prev('.mr-wid:not(.mrwid-subcat)').show();
			}*/
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
		if(!jQuery(this).hasClass('mrwid-subcat')) {
			if(jQuery(this).hasClass('active')) {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat').hide();
				jQuery(this).nextUntil('.mr-wid:not(.mrwid-subcat)').show();
			} else /*if(!jQuery(this).parent().hasClass('mrwid-keepopen'))*/ {
				jQuery(this).nextUntil('.mr-wid:not(.mrwid-subcat)').hide();
			}
		} else {
			if(!jQuery(this).hasClass('active')) {
				jQuery(this).closest('.mrwid-layout').find('.mrwid-subcat').hide();
			} /*else {
				jQuery(this).prev('.mr-wid:not(.mrwid-subcat)').show();
			}*/
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
	jQuery(this).parent().find('.mrwid-radio[value="'+jQuery(this).val()+'"]').attr('checked',true);
	jQuery(this).parent().find('.mrwid-pageselect option[value="'+jQuery(this).val()+'"]').attr('selected', 'selected');

	if(jQuery(this).parent().parent().find('.mrwid-page'+jQuery(this).val()).find('noscript').length) {
		jQuery(this).parent().parent().find('.mrwid-page'+jQuery(this).val()).html(jQuery(this).parent().parent().find('.mrwid-page'+jQuery(this).val()).find('noscript').text());
	}

	if(jQuery(this).parent().hasClass('mrwid-Slide')) {
		jQuery(this).parent().parent().find('.mrwid-pages').slideUp().removeClass('active');
		jQuery(this).parent().parent().find('.mrwid-page'+jQuery(this).val()).slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
	} else {
		jQuery(this).parent().parent().find('.mrwid-pages').fadeOut().removeClass('active');
		jQuery(this).parent().parent().find('.mrwid-page'+jQuery(this).val()).delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
	}

	jQuery(this).parent().parent().find('.mrwid-prev').val(parseInt(jQuery(this).val()) - 1);
	jQuery(this).parent().parent().find('.mrwid-next').val(parseInt(jQuery(this).val()) + 1);
});

jQuery('.mrwid-next').click(function() {
	if(jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).length) {
		jQuery(this).parent().find('.mrwid-pageselect option[value="'+jQuery(this).val()+'"]').attr('selected', 'selected');
		jQuery(this).parent().find('.mrwid-radio[value="'+jQuery(this).val()+'"]').attr('checked',true);

		if(jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).find('noscript').length) {
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).html(jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).find('noscript').text());
		}

		if(jQuery(this).parent().find('.mrwid-Slide').length) {
			jQuery(this).parent().find('.mrwid-pages').slideUp().removeClass('active');
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		} else {
			jQuery(this).parent().find('.mrwid-pages').fadeOut().removeClass('active');
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		}

		jQuery(this).parent().find('.mrwid-prev').val(parseInt(jQuery(this).val()) - 1);
		jQuery(this).val(parseInt(jQuery(this).val()) + 1);
	} else {
		jQuery(this).parent().find('.mrwid-pageselect option[value="1"]').attr('selected', 'selected');
		jQuery(this).parent().find('.mrwid-radio[value="1"]').attr('checked',true);

		if(jQuery(this).parent().find('.mrwid-Slide').length) {
			jQuery(this).parent().find('.mrwid-pages').slideUp().removeClass('active');
			jQuery(this).parent().find('.mrwid-page1').slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		} else {
			jQuery(this).parent().find('.mrwid-pages').fadeOut().removeClass('active');
			jQuery(this).parent().find('.mrwid-page1').delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		}

		jQuery(this).parent().find('.mrwid-prev').val(jQuery(this).parent().find('.mrwid-pageselect option:last-child').val());
		jQuery(this).val(2);
	}
});

jQuery('.mrwid-prev').click(function() {
	if(jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).length) {
		jQuery(this).parent().find('.mrwid-pageselect option[value="'+jQuery(this).val()+'"]').attr('selected', 'selected');
		jQuery(this).parent().find('.mrwid-radio[value="'+jQuery(this).val()+'"]').attr('checked',true);

		if(jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).find('noscript').length) {
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).html(jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).find('noscript').text());
		}

		if(jQuery(this).parent().find('.mrwid-Slide').length) {
			jQuery(this).parent().find('.mrwid-pages').slideUp().removeClass('active');
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		} else {
			jQuery(this).parent().find('.mrwid-pages').fadeOut().removeClass('active');
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).val()).delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		}

		jQuery(this).parent().find('.mrwid-next').val(parseInt(jQuery(this).val()) + 1);
		jQuery(this).val(parseInt(jQuery(this).val()) - 1);
	} else {
		jQuery(this).parent().find('.mrwid-pageselect option:last-child').attr('selected', 'selected');
		jQuery(this).parent().find('.mrwid-radio[value="'+jQuery(this).parent().find('.mrwid-pageselect option:last-child').val()+'"]').attr('checked',true);

		if(jQuery(this).parent().find('.mrwid-page'+jQuery(this).parent().find('.mrwid-pageselect option:last-child').val()).find('noscript').length) {
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).parent().find('.mrwid-pageselect option:last-child').val()).html(jQuery(this).parent().find('.mrwid-page'+jQuery(this).parent().find('.mrwid-pageselect option:last-child').val()).find('noscript').text());
		}

		if(jQuery(this).parent().find('.mrwid-Slide').length) {
			jQuery(this).parent().find('.mrwid-pages').slideUp().removeClass('active');
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).parent().find('.mrwid-pageselect option:last-child').val()).slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		} else {
			jQuery(this).parent().find('.mrwid-pages').fadeOut().removeClass('active');
			jQuery(this).parent().find('.mrwid-page'+jQuery(this).parent().find('.mrwid-pageselect option:last-child').val()).delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		}

		jQuery(this).parent().find('.mrwid-next').val(1);
		jQuery(this).val(parseInt(jQuery(this).parent().find('.mrwid-pageselect option:last-child').val()) -1 );
	}
});

jQuery('.mrwid-below,.mrwid-scroll').click(function() {
	jQuery(this).addClass('loading');

	if(!jQuery(this).parent().find('.mrwid-pages.active').length) {
		jQuery(this).parent().find('.mrwid-page1').addClass('active');
	}

	if(jQuery(this).parent().find('.mrwid-pages.active').last().next().hasClass('mrwid-pages')) {
		if(jQuery(this).parent().find('.mrwid-pages.active').last().next().find('noscript').length) {
			jQuery(this).parent().find('.mrwid-pages.active').last().next().html(jQuery(this).parent().find('.mrwid-pages.active').last().next().find('noscript').text());
		}

		if(jQuery(this).parent().find('.mrwid-Slide').length) {
			jQuery(this).parent().find('.mrwid-pages.active').last().next().slideDown({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		} else {
			jQuery(this).parent().find('.mrwid-pages.active').last().next().delay(400).fadeIn({start: function () {jQuery(this).css({display: "flex"})}}).addClass('active');
		}

		if(!jQuery(this).parent().find('.mrwid-pages.active').last().next().hasClass('mrwid-pages')) {
			jQuery(this).remove();
		}
	} else {
		jQuery(this).remove();
	}

	setTimeout(function() {
		jQuery('.mrwid-below,.mrwid-scroll').removeClass('loading');
	}, 400);
});

var scrollTimer;
jQuery(window).scroll(function() {
	clearTimeout(scrollTimer);
	scrollTimer = setTimeout(function() {
		jQuery(".mrwid-scroll").each(function() {
			if (isScrolledIntoView(jQuery(this))) {
				jQuery(this).click();
			}
		});
	}, 400);
});
