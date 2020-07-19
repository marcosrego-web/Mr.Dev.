function mrSlideUp(target) {
    let duration = 500;
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.boxSizing = 'border-box';
    target.style.height = target.offsetHeight + 'px';
    target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout( function() {
        target.style.display = 'none';
        target.style.removeProperty('height');
        target.style.removeProperty('padding-top');
        target.style.removeProperty('padding-bottom');
        target.style.removeProperty('margin-top');
        target.style.removeProperty('margin-bottom');
        target.style.removeProperty('overflow');
        target.style.removeProperty('transition-duration');
        target.style.removeProperty('transition-property');
    }, duration);
}
function mrSlideDown(target) {
    let duration = 500;
    let display = window.getComputedStyle(target).display;
    if (display === 'none' && display != 'block') {
        target.style.removeProperty('display');
        display = 'block';
        target.style.display = display;
        let height = target.offsetHeight;
        target.style.overflow = 'hidden';
        target.style.height = 0;
        target.style.paddingTop = 0;
        target.style.paddingBottom = 0;
        target.style.marginTop = 0;
        target.style.marginBottom = 0;
        target.offsetHeight;
        target.style.boxSizing = 'border-box';
        target.style.transitionProperty = "height, margin, padding";
        target.style.transitionDuration = duration + 'ms';
        target.style.height = height + 'px';
        target.style.removeProperty('padding-top');
        target.style.removeProperty('padding-bottom');
        target.style.removeProperty('margin-top');
        target.style.removeProperty('margin-bottom');
        window.setTimeout( function() {
            target.style.removeProperty('height');
            target.style.removeProperty('overflow');
            target.style.removeProperty('transition-duration');
            target.style.removeProperty('transition-property');
        }, duration);
    }
}
document.addEventListener('click',function(event) {
    if(event.target.matches('.mr-admin details:not([open]) .mr-section')) {
        event.target.closest('.mr-admin').querySelector("input.lastactivedetails").value = event.target.parentElement.getAttribute('class');
        var mrwidDetails = document.querySelectorAll(".mr-admin details:not([active])");
        for (var id = 0; id < mrwidDetails.length; id++) {
            var mrwidDetail = mrwidDetails[id];
            if (!mrwidDetail.classList.contains('mr-parentscontainer') && mrwidDetail !== event.target) {
                mrwidDetail.removeAttribute("open");
            }
        }
    } else if (event.target.matches('.mr-admin .mr-editoverride')) {
        mrSlideDown(event.target.nextElementSibling);
        event.target.closest('.mr-childs').classList.add('mr-openedpanel');
    } else if (event.target.matches('.mr-admin .mr-closeeditoverride')) {
        mrSlideUp(event.target.closest('.mr-overridepanel'));
        event.target.closest('.mr-childs').classList.remove('mr-openedpanel');
    } else if (event.target.matches('.mr-themes')) {
        event.target.addEventListener('change',function(event) {
            mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-themeoptions"));
            mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-savetheme"));
        });
    } else if (event.target.matches('.mr-parents input') || event.target.matches('.mr-notchilds input')) {
        event.target.addEventListener('change',function(event) {
            mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-saveexcludeinclude"));
        });
    } else if (event.target.matches('select.mr-contenttypes')) {
        event.target.addEventListener('change',function(event) {
            mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-saveexcludeinclude"));
            mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-itemscontainer"));
            mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-itemsnumber"));
            mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-parentscontainer"));
        });
    } else if (event.target.matches('.mr-excludeinclude')) {
        event.target.addEventListener('change',function(event) {
            if(event.target.value != 0) {
                event.target.closest('.mr-admin').querySelector(".mr-list").classList.add('including');
                mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-parentscontainer"));
                mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-groupexcludecontainer"));
            } else {
                if(event.target.closest('.mr-admin').querySelector(".mr-list").classList.contains('including')) {
                    event.target.closest('.mr-admin').querySelector(".mr-list").classList.remove('including');
                }
                mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-parentscontainer"));
                mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-groupexcludecontainer"));
            }
        });
    } else if (event.target.matches('.mr-itemimage')) {
        event.target.addEventListener('change',function(event) {
            if(event.target.value != 0) {
                mrSlideDown(event.target.closest('.mr-admin').querySelector('.mr-fallbackimage'));
            } else {
                mrSlideUp(event.target.closest('.mr-admin').querySelector('.mr-fallbackimage'));
            }
        });
    } else if (event.target.matches('.mr-mediabtn')) {
        if(event.target.closest('.mr-media').querySelector('img')) {
            event.target.closest('.mr-media').querySelector('img').remove();
        }
    } else if (event.target.matches('.mr-checkbox[value="thumbnail"]')) {
        event.target.addEventListener('change',function(event) {
            if(event.target.matches(':checked')) {
                mrSlideDown(event.target.closest('.mr-admin').querySelector('.mr-imagessize'));
            } else {
                mrSlideUp(event.target.closest('.mr-admin').querySelector('.mr-imagessize'));
            }
        });
    } else if (event.target.matches('.mr-itemstitleinput')) {
        event.target.addEventListener('change',function(event) {
            if(event.target.value != 1) {
                mrSlideDown(event.target.closest('.mr-admin').querySelector('.mr-itemstitlemax'));
            } else {
				mrSlideUp(event.target.closest('.mr-admin').querySelector('.mr-itemstitlemax'));
            }
        });
    } else if (event.target.matches('.mr-itemdescinput')) {
        event.target.addEventListener('change',function(event) {
            if(event.target.value != 1) {
                mrSlideDown(event.target.closest('.mr-admin').querySelector('.mr-itemdescmax'));
            } else {
                mrSlideUp(event.target.closest('.mr-admin').querySelector('.mr-itemdescmax'));
            }
        });
    } else if (event.target.matches('.mr-bottomlinkinput')) {
        event.target.addEventListener('change',function(event) {
            if(event.target.value != 1) {
                mrSlideDown(event.target.closest('.mr-admin').querySelector('.mr-bottomlinktext'));
            } else {
                mrSlideUp(event.target.closest('.mr-admin').querySelector('.mr-bottomlinktext'));
            }
        });
    }
});
jQuery(document).ready(function ($) {
    $(document).on("click", ".mr-mediabtn", function (e) {
        if(!$('body').hasClass('modal-open')) {
            //Thanks to vedmant - https://vedmant.com/using-wordpress-media-library-in-widgets-options/
            e.preventDefault();
            var $button = $(this);
            var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or upload image',
            library: {
                type: 'image'
            },
            button: {
                text: 'Select'
            },
            multiple: false
            });
            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $button.siblings('input').val(attachment.id);
            });
            file_frame.open();
        }
    });
});