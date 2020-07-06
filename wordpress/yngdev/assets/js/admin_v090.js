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
            if (!mrwidDetail.classList.contains('mr-mainitemcontainer') && mrwidDetail !== event.target) {
                mrwidDetail.removeAttribute("open");
            }
        }
    } else if (event.target.matches('.mr-themes')) {
        event.target.addEventListener('change',function(event) {
            mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-themeoptions"));
            mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-savetheme"));
        });
    }else if (event.target.matches('select.mr-contenttypes')) {
        event.target.addEventListener('change',function(event) {
            mrSlideUp(event.target.closest('.mr-admin').querySelector(".mr-itemscontainer"));
            mrSlideDown(event.target.closest('.mr-admin').querySelector(".mr-saveexcludeinclude"));
        });
    } else if (event.target.matches('.mr-excludeinclude')) {
        event.target.addEventListener('change',function(event) {
            if(event.target.value != 0) {
                event.target.closest('.mr-admin').querySelector(".mr-list").classList.add('including');
            } else {
                if(event.target.closest('.mr-admin').querySelector(".mr-list").classList.contains('including')) {
                    event.target.closest('.mr-admin').querySelector(".mr-list").classList.remove('including');
                }
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