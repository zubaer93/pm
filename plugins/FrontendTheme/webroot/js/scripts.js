/** ********************************************** **
 @Author			Dorin Grigoras
 @Website		www.stepofweb.com
 @Last Update	Wednesday, March 8, 2017
 
 NOTE! 	Do not change anything here if you want to
 be able to update in the future! Please use
 your custom script (eg. custom.js).
 
 
 TABLE CONTENTS
 -------------------------------
 
 
 INLINE SCRIPTS
 -------------------------------
 COUNT TO
 https://github.com/mhuggins/jquery-countTo
 
 BROWSER DETECT
 
 Appear
 https://github.com/bas2k/jquery.appear/
 
 Parallax v1.1.3
 http://www.ianlunn.co.uk/plugins/jquery-parallax/
 
 jQuery Easing v1.3
 http://gsgd.co.uk/sandbox/jquery/easing/
 
 WOW - v1.0.3
 http://mynameismatthieu.com/WOW/
 
 Modernizr 3.3.1
 http://modernizr.com/download/#-csstransforms3d-csstransitions-video-touch-shiv-cssclasses-addtest-prefixed-teststyles-testprop-testallprops-hasevent-prefixes-domprefixes-load
 
 Tether 1.3.3 - required by bootstrap
 http://tether.io/
 *************************************************** **/
window.width = jQuery(window).width();
window.height = jQuery(window).height();

/* Init */
jQuery(window).ready(function () {
    selec2itme = 0;

    // jQuery 3.x do no support size() - should be replaceced with .legth
    // We use this hack to make old plugins working
    jQuery.fn.extend({
        size: function () {
            return this.length;
        }
    });


//    _loadTetherBS4();

    // Load Bootstrap JS
    loadScript(plugin_path + 'bootstrap/js/bootstrap.min.js', function () {

        // Load Material Design Js
        if (jQuery("body").hasClass('enable-materialdesign')) {
            loadScript(plugin_path + 'mdl/material.min.js');
        }

        // Init
        Init(false);

    });


    /* --- */
    if (jQuery("html").hasClass("chrome") && jQuery("body").hasClass("smoothscroll") && navigator.platform.indexOf('Mac') < 0) {

        loadScript(plugin_path + 'smoothscroll.js', function () {
            jQuery.smoothScroll();
        });

    }
    /* --- */

    _notification();
});


/** Init
 Ajax Reinit:		Init(true);
 **************************************************************** **/
function Init(is_ajax) {

    // First Load Only
    if (is_ajax != true) {

        _afterResize();
        _slider_full();
        _topNav();
        _megaNavHorizontal();
        _sideNav();
        _stickyFooter();
        _infiniteScroll();

    }

    // Reinit on Ajax
    _owl_carousel();
//    _flexslider();
    _popover();
    _lightbox();
    _mixitup();
    _animate();
    _onepageNav();
    _scrollTo(false, 0);
//    _parallax();
//    _video();
//    _youtubeBG();
//    _toggle();
    _placeholder();
    _wrotate();
    _lazyload();
//    _misc();
//    _countDown();
    _masonryGallery();
//    _toastr(false, false, false, false);
//     _charts();
    _select2();
    _form();
    _regPickers();
    _pickers();
    _editors();
    _pajinate();
    _zoom();
    _autosuggest();
    _stepper();
    _slimScroll();
    _modalAutoLoad();
    _bgimage();
//    _cookie_alert();
//    _widget_flickr();
//    _widget_twitter();
//    _widget_facebook();
//    _widget_dribbble();
//    _widget_media();

    /** Bootstrap Tooltip **/
    jQuery("a[data-toggle=tooltip], button[data-toggle=tooltip], span[data-toggle=tooltip]").tooltip();
}



/** Preloader
 **************************************************************** **/
if (jQuery('#preloader').length > 0) {

    jQuery(window).on("load", function () {

        jQuery('#preloader').fadeOut(1000, function () {
            jQuery('#preloader').remove();
        });

        // setTimeout(function() {}, 1000);

    });

}



/** After Resize
 **************************************************************** **/
function _afterResize() {

    jQuery(window).on("load", function () {
        "use strict";

        // On Resize
        jQuery(window).resize(function () {

            if (window.afterResizeApp) {
                clearTimeout(window.afterResizeApp);
            }

            window.afterResizeApp = setTimeout(function () {

                /**
                 After Resize Code
                 .................
                 **/

                _slider_full();

                window.width = jQuery(window).width();
                window.height = jQuery(window).height();

                // Resize Flex Slider if exists!
                if (jQuery('.flexslider').length > 0) {
                    jQuery('.flexslider').resize();
                }


            }, 300);

        });

    });

}



/** Load Script
 
 USAGE
 var pageInit = function() {}
 loadScript(plugin_path + "script.js", function);
 
 Load multiple scripts and call a final function
 loadScript(plugin_path + "script1.js", function(){
 loadScript(plugin_path + "script2.js", function(){
 loadScript(plugin_path + "script3.js", function(){
 loadScript(plugin_path + "script4.js", function);
 });
 });
 });
 **************************************************************** **/
var _arr = {};
function loadScript(scriptName, callback) {
    if (!_arr[scriptName]) {
        _arr[scriptName] = true;

        var body = document.getElementsByTagName('body')[0];
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = scriptName;

        // then bind the event to the callback function
        // there are several events for cross browser compatibility
        // script.onreadystatechange = callback;
        script.onload = callback;

        // fire the loading
        body.appendChild(script);

    } else if (callback) {

        callback();

    }

}
;







/** 00. Slider Full Height
 **************************************************************** **/
function _slider_full() {
    _headerHeight = 0;

    if (jQuery("#header").hasClass('transparent') || jQuery("#header").hasClass('translucent')) {
        _headerHeight = 0;
    } else {
        _headerHeight = jQuery("#header").outerHeight() || 0;

        if (jQuery("#topBar").length > 0) {
            _topBarHeight = jQuery("#topBar").outerHeight() || 0;
            _headerHeight = _headerHeight + _topBarHeight;
        }
    }

    _screenHeight = jQuery(window).height() - _headerHeight;

    if (jQuery("#header").hasClass('static'))
        _screenHeight = jQuery(window).height();

    if (jQuery("#slider").hasClass('halfheight')) {
        jQuery("#slider.halfheight").height(_screenHeight / 2);
    }

    if (jQuery("#slider").hasClass('thirdheight')) {
        jQuery("#slider.thirdheight").height(_screenHeight / 1.5);
    }

    if (jQuery("#slider").hasClass('fullheight')) {
        jQuery("#slider.fullheight").height(_screenHeight);
        jQuery("#slider.fullheight-min").css({"min-height": _screenHeight + "px"});
    }

    if (window.width < 960) {
        jQuery("#slider.mobile-fullheight").height(_screenHeight);
    }
}








/** 01. Top Nav
 **************************************************************** **/
function _topNav() {
    window.scrollTop = 0;
    window._cmScroll = 0;
    var _header_el = jQuery("#header");

    jQuery(window).scroll(function () {
        _toTop();
    });

    /* Scroll To Top */
    function _toTop() {
        _scrollTop = jQuery(document).scrollTop();

        if (_scrollTop > 100) {

            if (jQuery("#toTop").is(":hidden")) {
                jQuery("#toTop").show();
            }

        } else {

            if (jQuery("#toTop").is(":visible")) {
                jQuery("#toTop").hide();
            }

        }

    }


    // Mobile Submenu
    var addActiveClass = false;
    jQuery("#topMain a.dropdown-toggle").bind("click", function (e) {

        if (jQuery(this).attr('href') == "#") {
            e.preventDefault();
        }

        addActiveClass = jQuery(this).parent().hasClass("resp-active");
        jQuery("#topMain").find(".resp-active").removeClass("resp-active");

        if (!addActiveClass) {
            jQuery(this).parents("li").addClass("resp-active");
        }

        return;

    });


    // Srearch
    jQuery('li.search i.fa').click(function () {
        if (jQuery('#header .search-box').is(":visible")) {
            jQuery('#header .search-box').fadeOut(300);
        } else {
            jQuery('.search-box').fadeIn(300);
            jQuery('#header .search-box form input').focus();

            // hide quick cart if visible
            if (jQuery('#header li.quick-cart div.quick-cart-box').is(":visible")) {
                jQuery('#header li.quick-cart div.quick-cart-box').fadeOut(300);
            }
        }
    });

    // close search box on body click
    if (jQuery('#header li.search i.fa').size() != 0) {
        jQuery('#header .search-box, #header li.search i.fa').on('click', function (e) {
            e.stopPropagation();
        });

        jQuery('body').on('click', function () {
            if (jQuery('#header li.search .search-box').is(":visible")) {
                jQuery('#header .search-box').fadeOut(300);
            }
        });
    }

    jQuery(document).bind("click", function () {
        if (jQuery('#header li.search .search-box').is(":visible")) {
            jQuery('#header .search-box').fadeOut(300);
        }
    });


    // Close Fullscreen Search
    jQuery("#closeSearch").bind("click", function (e) {
        e.preventDefault();

        jQuery('#header .search-box').fadeOut(300);
    });



    // Page Menu [mobile]
    jQuery("button#page-menu-mobile").bind("click", function () {
        jQuery(this).next('ul').slideToggle(150);
    });


    // Quick Cart
    jQuery('li.quick-cart>a').click(function (e) {
        e.preventDefault();

        var _quick_cart_box = jQuery('li.quick-cart div.quick-cart-box');

        if (_quick_cart_box.is(":visible")) {
            _quick_cart_box.fadeOut(300);
        } else {
            _quick_cart_box.fadeIn(300);

            // close search if visible
            if (jQuery('li.search .search-box').is(":visible")) {
                jQuery('.search-box').fadeOut(300);
            }
        }
    });
    // close quick cart on body click
    if (jQuery('li.quick-cart>a').size() != 0) {
        jQuery('li.quick-cart').on('click', function (e) {
            e.stopPropagation();
        });

        jQuery('body').on('click', function () {
            if (jQuery('li.quick-cart div.quick-cart-box').is(":visible")) {
                jQuery('li.quick-cart div.quick-cart-box').fadeOut(300);
            }
        });
    }


    // Page Menu [scrollTo]
    jQuery("#page-menu ul.menu-scrollTo>li").bind("click", function (e) {

        // calculate padding-top for scroll offset
        var _href = jQuery('a', this).attr('href');

        if (!jQuery('a', this).hasClass('external')) {
            e.preventDefault();

            jQuery("#page-menu ul.menu-scrollTo>li").removeClass('active');
            jQuery(this).addClass('active');

            if (jQuery(_href).length > 0) {

                _padding_top = 0;

                if (jQuery("#header").hasClass('sticky')) {
                    _padding_top = jQuery(_href).css('padding-top');
                    _padding_top = _padding_top.replace('px', '');
                }

                jQuery('html,body').animate({scrollTop: jQuery(_href).offset().top - _padding_top}, 800, 'easeInOutExpo');

            }

        }

    });




    // MOBILE TOGGLE BUTTON
    window.currentScroll = 0;
    jQuery("button.btn-mobile").bind("click", function (e) {
        e.preventDefault();

        jQuery(this).toggleClass('btn-mobile-active');
        jQuery('html').removeClass('noscroll');
        jQuery('#menu-overlay').remove();
        jQuery("#topNav div.nav-main-collapse").hide(0);

        if (jQuery(this).hasClass('btn-mobile-active')) {
            jQuery("#topNav div.nav-main-collapse").show(0);
            jQuery('html').addClass('noscroll');
            jQuery('body').append('<div id="menu-overlay"></div>');
            window.currentScroll = jQuery(window).scrollTop();
        } else {
            jQuery('html,body').animate({scrollTop: currentScroll}, 300, 'easeInOutExpo');
        }
    });




    // BOTTOM NAV
    if (_header_el.hasClass('bottom')) {

        // Add dropup class
        _header_el.addClass('dropup');
        window.homeHeight = jQuery(window).outerHeight() - 55;


        // sticky header
        if (_header_el.hasClass('sticky')) {
            window.isOnTop = true;


            // if scroll is > 60%, remove class dropup
            jQuery(window).scroll(function () {
                if (jQuery(document).scrollTop() > window.homeHeight / 2) {
                    _header_el.removeClass('dropup');
                } else {
                    _header_el.addClass('dropup');
                }
            });


            // Add fixed|not fixed & dropup|no dropup
            jQuery(window).scroll(function () {
                if (jQuery(document).scrollTop() > window.homeHeight) {
                    if (window.isOnTop === true) {
                        jQuery('#header').addClass('fixed');
                        _header_el.removeClass('dropup');
                        window.isOnTop = false;
                    }
                } else {
                    if (window.isOnTop === false) {
                        jQuery('#header').removeClass('fixed');
                        _header_el.addClass('dropup');
                        window.isOnTop = true;
                    }
                }
            });

            // get window height on resize
            jQuery(window).resize(function () {
                window.homeHeight = jQuery(window).outerHeight();
            });

        }

    } else

    // STICKY
    if (_header_el.hasClass('sticky')) {

        _topBar_H = jQuery("#topBar").outerHeight() || 0;

        // Force fixed header on mobile to avoid "jump" effect.
        if (window.width <= 992 && _topBar_H < 1) {

            var _scrollTop = jQuery(document).scrollTop();
            _header_H = _header_el.outerHeight() || 0;

            _header_el.addClass('fixed');
            jQuery('body').css({"padding-top": _header_H + "px"});

        }



        jQuery(window).scroll(function () {

            if ((window.width > 992 && _topBar_H < 1) || _topBar_H > 0) { // 992 to disable on mobile

                var _scrollTop = jQuery(document).scrollTop();

                if (_scrollTop > _topBar_H) {
                    _header_el.addClass('fixed');

                    _header_H = _header_el.outerHeight() || 0;

                    if (!_header_el.hasClass('transparent') && !_header_el.hasClass('translucent')) {
                        jQuery('body').css({"padding-top": _header_H + "px"});
                    }

                } else {
                    if (!_header_el.hasClass('transparent') && !_header_el.hasClass('translucent')) {
                        jQuery('body').css({"padding-top": "0px"});
                    }

                    _header_el.removeClass('fixed');
                }

            }



            // SWITCH DROPDOWN MENU CLASS ON SCROLL
            if (_header_el.hasClass('transparent')) {

                var _el = jQuery("#topNav div.nav-main-collapse"),
                        _data_switch_default = _el.attr('data-switch-default') || '',
                        _data_switch_scroll = _el.attr('data-switch-scroll') || '';


                if (_data_switch_default != '' || _data_switch_scroll != '') {

                    if (_scrollTop > 0) {

                        if (window._cmScroll < 1) {

                            _el.removeClass(_data_switch_default, _data_switch_scroll).addClass(_data_switch_scroll);

                            // set to 1, we want to change classes once, not for each pixel on scroll
                            window._cmScroll = 1;

                        }

                    } else

                    if (_scrollTop < 1) {

                        _el.removeClass(_data_switch_default, _data_switch_scroll).addClass(_data_switch_default);

                        // Set back to 0
                        window._cmScroll = 0;

                    }

                }


            }


        });

    } else


    // STATIC + TRANSPARENT
    if (_header_el.hasClass('static') && _header_el.hasClass('transparent')) {

        _topBar_H = jQuery("#topBar").outerHeight() || 0;

        // Force fixed header on mobile to avoid "jump" effect.
        if (window.width <= 992 && _topBar_H < 1) {

            var _scrollTop = jQuery(document).scrollTop();
            _header_H = _header_el.outerHeight() || 0;

            _header_el.addClass('fixed');

        }



        jQuery(window).scroll(function () {

            if ((window.width > 992 && _topBar_H < 1) || _topBar_H > 0) { // 992 to disable on mobile

                var _scrollTop = jQuery(document).scrollTop();

                if (_scrollTop > _topBar_H) {
                    _header_el.addClass('fixed');

                    _header_H = _header_el.outerHeight() || 0;

                } else {


                    _header_el.removeClass('fixed');
                }

            }

        });



    } else

    if (_header_el.hasClass('static')) {
        // _header_H = _header_el.outerHeight() + "px";
        // jQuery('body').css({"padding-top":_header_H});
    }



    // Slide Top
    jQuery("#slidetop a.slidetop-toggle").bind("click", function () {
        jQuery("#slidetop .container").slideToggle(150, function () {

            if (jQuery("#slidetop .container").is(":hidden")) {
                jQuery("#slidetop").removeClass('active');
            } else {
                jQuery("#slidetop").addClass('active');
            }

        });
    });
    // 'esc' key
    jQuery(document).keyup(function (e) {
        if (e.keyCode == 27) {
            if (jQuery("#slidetop").hasClass("active")) {
                jQuery("#slidetop .container").slideToggle(150, function () {
                    jQuery("#slidetop").removeClass('active');
                });
            }
        }
    });

    // Slide Panel
    jQuery("a#sidepanel_btn").bind("click", function (e) {
        e.preventDefault();

        _pos = "right";
        if (jQuery("#sidepanel").hasClass('sidepanel-inverse')) {
            _pos = "left";
        }

        if (jQuery("#sidepanel").is(":hidden")) {

            jQuery("body").append('<span id="sidepanel_overlay"></span>');

            if (_pos == "left") {
                jQuery("#sidepanel").stop().show().animate({"left": "0px"}, 150);
            } else {
                jQuery("#sidepanel").stop().show().animate({"right": "0px"}, 150);
            }

        } else {

            jQuery("#sidepanel_overlay").remove();

            if (_pos == "left") {
                jQuery("#sidepanel").stop().animate({"left": "-300px"}, 300);
            } else {
                jQuery("#sidepanel").stop().animate({"right": "-300px"}, 300);
            }

            setTimeout(function () {
                jQuery("#sidepanel").hide();
            }, 500);

        }

        _sidepanel_overlay();

    });
    // button close
    jQuery("#sidepanel_close").bind("click", function (e) {
        e.preventDefault();
        jQuery("a#sidepanel_btn").trigger('click');
    });
    // overlay click
    function _sidepanel_overlay() {
        jQuery("#sidepanel_overlay").unbind();
        jQuery("#sidepanel_overlay").bind("click", function () {
            jQuery("a#sidepanel_btn").trigger('click');
        });
    }
    // 'esc' key
    jQuery(document).keyup(function (e) {
        if (e.keyCode == 27) {
            if (jQuery("#sidepanel").is(":visible")) {
                jQuery("a#sidepanel_btn").trigger('click');
            }
        }
    });



    /** OVERLAY MENU
     *************************** **/
    if (jQuery("#menu_overlay_open").length > 0) {
        var is_ie9 = jQuery('html').hasClass('ie9') ? true : false;

        if (is_ie9 == true) {
            jQuery("#topMain").hide();
        }

        // open
        jQuery("#menu_overlay_open").bind("click", function (e) {
            e.preventDefault();

            jQuery('body').addClass('show-menu');

            if (is_ie9 == true) {
                jQuery("#topMain").show();
            }

        });

        // close
        jQuery("#menu_overlay_close").bind("click", function (e) {
            e.preventDefault();

            if (jQuery('body').hasClass('show-menu')) {
                jQuery('body').removeClass('show-menu');
            }

            if (is_ie9 == true) {
                jQuery("#topMain").hide();
            }

        });

        // 'esc' key
        jQuery(document).keyup(function (e) {
            if (e.keyCode == 27) {
                if (jQuery('body').hasClass('show-menu')) {
                    jQuery('body').removeClass('show-menu');
                }

                if (is_ie9 == true) {
                    jQuery("#topMain").hide();
                }
            }
        });

    }

    /** VERTICAL MENU SHOW|HIDE
     *************************** **/
    // RTL supported!
    if (jQuery("#sidebar_vertical_btn").length > 0) {
        if (jQuery("body").hasClass('menu-vertical-hide')) {

            // Determine the position (left or right?)
            _paddingStatusL = jQuery("#mainMenu.sidebar-vertical").css('left');
            _paddingStatusR = jQuery("#mainMenu.sidebar-vertical").css('right');

            if (parseInt(_paddingStatusL) < 0) {
                var _pos = "left";
            } else

            if (parseInt(_paddingStatusR) < 0) {
                var _pos = "right";
            } else {
                var _pos = "left";
            }

            // Show|Hide Vertical Menu
            jQuery("#sidebar_vertical_btn").bind("click", function (e) {

                _paddingStatus = jQuery("#mainMenu.sidebar-vertical").css(_pos);

                if (parseInt(_paddingStatus) < 0) {
                    if (_pos == "right") {
                        jQuery("#mainMenu.sidebar-vertical").stop().animate({"right": "0px"}, 200);
                    } else {
                        jQuery("#mainMenu.sidebar-vertical").stop().animate({"left": "0px"}, 200);
                    }
                } else {
                    if (_pos == "right") {
                        jQuery("#mainMenu.sidebar-vertical").stop().animate({"right": "-263px"}, 200);
                    } else {
                        jQuery("#mainMenu.sidebar-vertical").stop().animate({"left": "-263px"}, 200);
                    }
                }
            });

            // Hide on scroll
            jQuery(window).scroll(function () {

                _paddingStatus = parseInt(jQuery("#mainMenu.sidebar-vertical").css(_pos));

                if (_paddingStatus >= 0) {
                    if (_pos == "right") {
                        jQuery("#mainMenu.sidebar-vertical").stop().animate({"right": "-263px"}, 200);
                    } else {
                        jQuery("#mainMenu.sidebar-vertical").stop().animate({"left": "-263px"}, 200);
                    }
                }

            });

        }
    }

    // quick cart & search for mobile - top calculate
    // Quick Cart & top Search Fix (if #topBar exists).
    if (jQuery("#topBar").length > 0) {
        jQuery("#topNav ul").addClass('has-topBar serach-ul');
        jQuery("#topMainMobile").removeClass('serach-ul');
    }

    // Hide Cart & Search on Scroll
    jQuery(window).scroll(function () {
        if (window.width < 769) {
            // hide quick cart if visible
            if (jQuery('#header li.quick-cart div.quick-cart-box').is(":visible")) {
                jQuery('#header li.quick-cart div.quick-cart-box').fadeOut(0);
            }
            // hide search if visible
            if (jQuery('#header li.search .search-box').is(":visible")) {
                jQuery('#header .search-box').fadeOut(0);
            }
        }
    });
}







/** Mega Horizontal Navigation
 **************************************************************** **/
function _megaNavHorizontal() {

    // WRAPPER MAIN MENU
    if (jQuery("#wrapper nav.main-nav").length > 0) {

        var _sliderWidth = jQuery("#slider").width(),
                _sliderHeight = jQuery("#wrapper nav.main-nav").height();

        // Submenu widh & height
        jQuery("#wrapper nav.main-nav>div>ul>li>.main-nav-submenu").css({"min-height": _sliderHeight + "px"});
        jQuery("#wrapper nav.main-nav>div>ul>li.main-nav-expanded>.main-nav-submenu").css({"width": _sliderWidth + "px"});

        // SUBMENUS
        jQuery("#wrapper nav.main-nav>div>ul>li").bind("click", function (e) {
            var _this = jQuery(this);

            if (!jQuery('div', _this).hasClass('main-nav-open')) {
                jQuery("#wrapper nav.main-nav>div>ul>li>.main-nav-submenu").removeClass('main-nav-open');
            }

            jQuery('div', _this).toggleClass('main-nav-open');
        });

    }






    // HEADER MAIN MENU
    var _hsliderWidth = jQuery("#header>.container").width() - 278,
            _hsliderHeight = jQuery("#header nav.main-nav").height();

    // Submenu widh & height
    jQuery("#header nav.main-nav>div>ul>li>.main-nav-submenu").css({"min-height": _hsliderHeight + "px"});
    jQuery("#header nav.main-nav>div>ul>li.main-nav-expanded>.main-nav-submenu").css({"width": _hsliderWidth + "px"});


    // SUBMENUS
    jQuery("#header nav.main-nav>div>ul>li").bind("click", function (e) {
        var _this = jQuery(this);

        if (!jQuery('div', _this).hasClass('main-nav-open')) {
            jQuery("#header nav.main-nav>div>ul>li>.main-nav-submenu").removeClass('main-nav-open');
        }

        jQuery('div', _this).toggleClass('main-nav-open');
    });




    // HEADER MAIN MENU
    if (window.width > 767) { //  desktop|tablet

        jQuery("#header button.nav-toggle").mouseover(function (e) {
            e.preventDefault();

            _initMainNav();

        });


    } else { // mobile

        jQuery("#header button.nav-toggle").bind("click", function (e) {
            e.preventDefault();

            _initMainNav();

        });

    }

    jQuery('body').on('click', '#header button.nav-toggle, #header nav.main-nav', function (e) {
        e.stopPropagation();
    });

    jQuery("#header button.nav-toggle, #header nav.main-nav").mouseover(function (e) {
        e.stopPropagation();
    });


    jQuery(document).bind("click", function () {

        _hideMainNav();

    });



    function _initMainNav() {

        // remove overlay first, no matter what
        jQuery("#main-nav-overlay").remove();

        // open menu
        jQuery("#header nav.main-nav").addClass('min-nav-active');

        // add overlay
        jQuery('body').append('<div id="main-nav-overlay"></div>');

        // Mobile menu open|close on click
        jQuery('#header button.nav-toggle-close').bind("click", function () {
            jQuery("#header nav.main-nav").removeClass('min-nav-active');
        });

        // Close menu on hover
        jQuery("#main-nav-overlay, #header").mouseover(function () {

            _hideMainNav();

        });

    }

    function _hideMainNav() {
        jQuery("#main-nav-overlay").remove();
        jQuery("#header nav.main-nav").removeClass('min-nav-active');
    }


    // Menu Click
    jQuery("nav.main-nav>div>ul>li a").bind("click", function (e) {
        var _href = jQuery(this).attr('href');

        if (_href == '#') {
            e.preventDefault();
        }
    });
}







/** 02. Side Nav
 **************************************************************** **/
function _sideNav() {


    /* Mobile Button */
    jQuery("div.side-nav").each(function () {
        var _t = jQuery('ul', this);
        jQuery('button', this).bind("click", function () {
            _t.slideToggle(300);
        });
    });


    /* Submenus */
    jQuery("div.side-nav>ul>li>a.dropdown-toggle").bind("click", function (e) {
        e.preventDefault();

        jQuery(this).next('ul').slideToggle(200);
        jQuery(this).closest('li').toggleClass('active');
    });

}



/** 02. Animate
 
 EXAMPLE USAGE
 <img class="wow fadeInUp" data-wow-delay="0.1s" src="image.jpg" alt="" />
 **************************************************************** **/
function _animate() {

    if (jQuery("body").hasClass('enable-animation')) {

        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 90,
            mobile: false,
            live: true
        });

        wow.init();

    }

    // Count To
    jQuery(".countTo").appear(function () {
        var _t = jQuery(this),
                _from = _t.attr('data-from') || 0,
                _speed = _t.attr('data-speed') || 1300,
                _refreshInterval = _t.attr('data-refreshInterval') || 60;


        _t.countTo({
            from: parseInt(_from),
            to: _t.html(),
            speed: parseInt(_speed),
            refreshInterval: parseInt(_refreshInterval),
        });

    });
}



/** Onepage Nav
 **************************************************************** **/
function _onepageNav() {


    // Top Navigation
    var _container1 = jQuery(".nav-onepage");

    if (_container1.length > 0) {

        loadScript(plugin_path + 'jquery.nav.min.js', function () {

            jQuery(_container1).onePageNav({
                currentClass: 'active',
                changeHash: false,
                scrollSpeed: 750,
                scrollThreshold: 0.5,
                filter: ':not(.external)',
                easing: 'easeInOutExpo'
            });

        });

    }


    // Bullet Navigation
    var _container2 = jQuery("#nav-bullet");

    if (_container2.length > 0) {

        loadScript(plugin_path + 'jquery.nav.min.js', function () {

            jQuery(_container2).onePageNav({
                currentClass: 'active',
                changeHash: false,
                scrollSpeed: 750,
                scrollThreshold: 0.5,
                filter: ':not(.external)',
                easing: 'easeInOutExpo'
            });

        });

    }


}



/** 03. OWL Carousel
 **************************************************************** **/
function _owl_carousel() {


    // OWL CAROUSEL 1
    var _container = jQuery("div.owl-carousel");

    if (_container.length > 0) {

        loadScript(plugin_path + 'owl-carousel/owl.carousel.min.js', function () {

            _container.each(function () {

                var slider = jQuery(this);
                var options = slider.attr('data-plugin-options');

                // Progress Bar
                var $opt = eval('(' + options + ')');  // convert text to json

                if ($opt.progressBar == 'true') {
                    var afterInit = progressBar;
                } else {
                    var afterInit = false;
                }

                var defaults = {
                    items: 5,
                    itemsCustom: false,
                    itemsDesktop: [1199, 4],
                    itemsDesktopSmall: [980, 3],
                    itemsTablet: [768, 2],
                    itemsTabletSmall: false,
                    itemsMobile: [479, 1],
                    singleItem: true,
                    itemsScaleUp: false,

                    slideSpeed: 200,
                    paginationSpeed: 800,
                    rewindSpeed: 1000,

                    autoPlay: false,
                    stopOnHover: false,

                    navigation: false,
                    navigationText: [
                        '<i class="fa fa-angle-left"></i>',
                        '<i class="fa fa-angle-right"></i>'
                    ],
                    rewindNav: true,
                    scrollPerPage: false,

                    pagination: true,
                    paginationNumbers: false,

                    responsive: true,
                    responsiveRefreshRate: 200,
                    responsiveBaseWidth: window,

                    baseClass: "owl-carousel",
                    theme: "owl-theme",

                    lazyLoad: false,
                    lazyFollow: true,
                    lazyEffect: "fade",

                    autoHeight: false,

                    jsonPath: false,
                    jsonSuccess: false,

                    dragBeforeAnimFinish: true,
                    mouseDrag: true,
                    touchDrag: true,

                    transitionStyle: false,

                    addClassActive: false,

                    beforeUpdate: false,
                    afterUpdate: false,
                    beforeInit: false,
                    afterInit: afterInit,
                    beforeMove: false,
                    afterMove: (afterInit == false) ? false : moved,
                    afterAction: false,
                    startDragging: false,
                    afterLazyLoad: false
                }

                var config = jQuery.extend({}, defaults, options, slider.data("plugin-options"));
                slider.owlCarousel(config).addClass("owl-carousel-init");


                // Progress Bar
                var elem = jQuery(this);

                //Init progressBar where elem is $("#owl-demo")
                function progressBar(elem) {
                    $elem = elem;
                    //build progress bar elements
                    buildProgressBar();
                    //start counting
                    start();
                }

                //create div#progressBar and div#bar then prepend to $("#owl-demo")
                function buildProgressBar() {
                    $progressBar = jQuery("<div>", {
                        id: "progressBar"
                    });
                    $bar = jQuery("<div>", {
                        id: "bar"
                    });
                    $progressBar.append($bar).prependTo($elem);
                }

                function start() {
                    //reset timer
                    percentTime = 0;
                    isPause = false;
                    //run interval every 0.01 second
                    tick = setInterval(interval, 10);
                }
                ;


                var time = 7; // time in seconds
                function interval() {
                    if (isPause === false) {
                        percentTime += 1 / time;
                        $bar.css({
                            width: percentTime + "%"
                        });
                        //if percentTime is equal or greater than 100
                        if (percentTime >= 100) {
                            //slide to next item
                            $elem.trigger('owl.next')
                        }
                    }
                }

                //pause while dragging
                function pauseOnDragging() {
                    isPause = true;
                }

                //moved callback
                function moved() {
                    //clear interval
                    clearTimeout(tick);
                    //start again
                    start();
                }

            });

        });

    }



    // OWL CAROUSEL 2
    var _container2 = jQuery("div.owl-carousel-2");

    if (_container2.length > 0) {

        loadScript(plugin_path + 'owl-carousel-2/owl.carousel.min.js', function () {


            _container2.each(function () {
                var _this = jQuery(this),
                        _options = _this.attr('data-plugin-options');

                _defaults = {
                    loop: true,
                    margin: 10,
                    nav: true,

                    center: false,
                    mouseDrag: true,
                    touchDrag: true,
                    pullDrag: true,
                    freeDrag: false,
                    stagePadding: 0,
                    merge: false,
                    mergeFit: true,
                    autoWidth: false,
                    startPosition: 0,
                    URLhashListener: false,
                    navRewind: true,
                    navText: [
                        '<i class="fa fa-angle-left"></i>',
                        '<i class="fa fa-angle-right"></i>'
                    ],
                    slideBy: 1,
                    dots: true,
                    dotsEach: false,
                    dotData: false,
                    lazyLoad: false,
                    lazyContent: false,
                    autoplay: false,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: false,
                    smartSpeed: 250,
                    //fluidSpeed: 			'Number',
                    autoplaySpeed: false,
                    navSpeed: false,
                    //dotsSpeed: 			'Number/Boolean',
                    dragEndSpeed: false,
                    callbacks: true,
                    responsiveRefreshRate: 200,
                    responsiveBaseElement: '#wrapper',
                    responsiveClass: true,
                    video: false,
                    videoHeight: false,
                    videoWidth: false,
                    animateOut: false,
                    animateIn: false,
                    fallbackEasing: 'swing',
                    info: false,
                    nestedItemSelector: false,
                    itemElement: 'div',
                    navContainer: false,
                    dotsContainer: false,

                    animateOut: "slideOutDown",
                    animateIn: "flipInX",

                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        1000: {
                            items: 5
                        }
                    }
                };


                var _config = jQuery.extend({}, _defaults, JSON.parse(_options));
                _this.owlCarousel(_config).addClass("owl-loaded");


            });


        });

    }


}


/** 04. Flexslider
 **************************************************************** **/
function _flexslider() {
    var _container = jQuery(".flexslider");

    if (_container.length > 0) {

        loadScript(plugin_path + 'slider.flexslider/jquery.flexslider-min.js', function () {

            if (jQuery().flexslider) {
                var _controlNav = _container.attr('data-controlNav'),
                        _slideshowSpeed = _container.attr('data-slideshowSpeed') || 7000,
                        _pauseOnHover = _container.attr('data-pauseOnHover') || false;

                if (_pauseOnHover == "true") {
                    _pauseOnHover = true;
                } else {
                    _pauseOnHover = false;
                }

                if (_controlNav == 'thumbnails') {
                    _controlNav = 'thumbnails';
                } else
                if (_controlNav == 'true') {
                    _controlNav = true;
                } else
                if (_controlNav == 'false') {
                    _controlNav = false;
                } else {
                    _controlNav = true;
                }

                if (_controlNav == 'thumbnails' || _controlNav == false) {
                    _directionNav = false;
                } else {
                    _directionNav = true;
                }

                jQuery(_container).flexslider({
                    animation: "slide",
                    controlNav: _controlNav,
                    slideshowSpeed: parseInt(_slideshowSpeed) || 7000,
                    directionNav: _directionNav,
                    pauseOnHover: _pauseOnHover,
                    start: function (slider) {
                        jQuery('.flex-prev').html('<i class="fa fa-angle-left"></i>');
                        jQuery('.flex-next').html('<i class="fa fa-angle-right"></i>');
                    }
                });

                // Resize Flex Slider if exists!
                _container.resize();

            }

        });
    }
}





/** 04. Popover
 **************************************************************** **/
function _popover() {

    jQuery("a[data-toggle=popover]").bind("click", function (e) {
        jQuery('.popover-title .close').remove();
        e.preventDefault();
    });

    var isVisible = false,
            clickedAway = false;


    jQuery("a[data-toggle=popover], button[data-toggle=popover]").popover({

        html: true,
        trigger: 'manual'

    }).click(function (e) {

        jQuery(this).popover('show');

        clickedAway = false;
        isVisible = true;
        e.preventDefault();

    });

    jQuery(document).click(function (e) {
        if (isVisible & clickedAway) {

            jQuery("a[data-toggle=popover], button[data-toggle=popover]").popover('hide');
            isVisible = clickedAway = false;

        } else {


            clickedAway = true;

        }

    });

    jQuery("a[data-toggle=popover], button[data-toggle=popover]").popover({

        html: true,
        trigger: 'manual'

    }).click(function (e) {

        $(this).popover('show');
        $('.popover-title').append('<button type="button" class="close">&times;</button>');
        $('.close').click(function (e) {

            jQuery("a[data-toggle=popover], button[data-toggle=popover]").popover('hide');

        });

        e.preventDefault();
    });


    // jQuery("a[data-toggle=popover], button[data-toggle=popover]").popover();
}



/** 05. LightBox
 **************************************************************** **/
function _lightbox() {
    var _el = jQuery(".lightbox");

    if (_el.length > 0) {

        loadScript(plugin_path + 'magnific-popup/jquery.magnific-popup.min.js', function () {

            if (typeof (jQuery.magnificPopup) == "undefined") {
                return false;
            }

            jQuery.extend(true, jQuery.magnificPopup.defaults, {
                tClose: 'Close',
                tLoading: 'Loading...',

                gallery: {
                    tPrev: 'Previous',
                    tNext: 'Next',
                    tCounter: '%curr% / %total%'
                },

                image: {
                    tError: 'Image not loaded!'
                },

                ajax: {
                    tError: 'Content not loaded!'
                }
            });

            _el.each(function () {

                var _t = jQuery(this),
                        options = _t.attr('data-plugin-options'),
                        config = {},
                        defaults = {
                            type: 'image',
                            fixedContentPos: false,
                            fixedBgPos: false,
                            mainClass: 'mfp-no-margins mfp-with-zoom',
                            closeOnContentClick: true,
                            closeOnBgClick: true,
                            image: {
                                verticalFit: true
                            },

                            zoom: {
                                enabled: false,
                                duration: 300
                            },

                            gallery: {
                                enabled: false,
                                navigateByImgClick: true,
                                preload: [0, 1],
                                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                                tPrev: 'Previous',
                                tNext: 'Next',
                                tCounter: '<span class="mfp-counter">%curr% / %total%</span>'
                            },
                        };

                if (_t.data("plugin-options")) {
                    config = jQuery.extend({}, defaults, options, _t.data("plugin-options"));
                }

                jQuery(this).magnificPopup(config);

            });

        });

    }

}




/** 06. ScrollTo
 **************************************************************** **/
function _scrollTo(to, offset) {

    if (to == false) {

        jQuery("a.scrollTo").bind("click", function (e) {
            e.preventDefault();

            var href = jQuery(this).attr('href'),
                    _offset = jQuery(this).attr('data-offset') || 0;

            if (href != '#' && href != '#top') {
                jQuery('html,body').animate({scrollTop: jQuery(href).offset().top - parseInt(_offset)}, 800, 'easeInOutExpo');
            }

            if (href == '#top') {
                jQuery('html,body').animate({scrollTop: 0}, 800, 'easeInOutExpo');
            }
        });

        jQuery("#toTop").bind("click", function (e) {
            e.preventDefault();
            jQuery('html,body').animate({scrollTop: 0}, 800, 'easeInOutExpo');
        });

    } else {

        // USAGE: _scrollTo("#footer", 150);
        jQuery('html,body').animate({scrollTop: jQuery(to).offset().top - offset}, 800, 'easeInOutExpo');

    }
}




/** 07. Parallax
 **************************************************************** **/
function _parallax() {


    if (jQuery().parallax) {

        jQuery(".parallax-auto, .parallax-1, .parallax-2, .parallax-3, .parallax-4, section.page-header.page-header-parallax").each(function () {
            var _t = jQuery(this);

            jQuery(this).parallax("50%", "0.2");
        });

    }




    /** Slider Parallax
     Do not use overlay - will be very slow!
     **************************** **/
    var _slider = jQuery('#slider');

    if (_slider.length > 0) {
        if (_slider.hasClass('parallax-slider')) {

            if (window.width < 768 && _slider.hasClass('pallax-slider-mobile-deisable')) {
                return false;
            }

            var block_intro_top = _slider.offset().top;

            jQuery(window).scroll(function () {

                var _currentTop = jQuery(document).scrollTop(),
                        _offset = _slider.attr('data-parallax-offset') || 0;

                if (_currentTop < 768) {
                    var _sliderH = jQuery('#slider').height();

                    jQuery('#slider>div').css('top', (_currentTop * 0.5 - Number(_offset)));
                    if (!_slider.hasClass('parallax-slider-noopacity')) {
                        jQuery('#slider>div').css('opacity', (1 - _currentTop / _sliderH * 1));
                    }
                }

            });

        }
    }

}




/** 07. Video
 **************************************************************** **/
function _video() {

    if (jQuery("section.section-video").length > 0) {
        var _t = jQuery("section.section-video .section-container-video>video");
        _w = jQuery(window).width();

        _t.width(_w);

    }

}



/** 07. Youtube Backround
 **************************************************************** **/
function _youtubeBG() {
    var _container = jQuery('#YTPlayer');

    if (_container.length > 0) {
        loadScript(plugin_path + 'jquery.mb.YTPlayer.min.js', function () {


            if (jQuery().mb_YTPlayer) {
                var disableMobile = false;
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
                    // disableMobile = true;
                }

                if (disableMobile === false) {

                    jQuery(".player").mb_YTPlayer();

                    jQuery("#video-volume").bind("click", function (e) {
                        e.preventDefault();

                        jQuery('#YTPlayer').toggleVolume();
                    });

                    // audio control
                    jQuery("#video-volume").bind("click", function () {
                        if (jQuery('i.fa', this).hasClass('fa-volume-down')) {
                            jQuery('i.fa', this).removeClass('fa-volume-down');
                            jQuery('i.fa', this).removeClass('fa-volume-up');
                            jQuery('i.fa', this).addClass('fa-volume-up');
                        } else {
                            jQuery('i.fa', this).removeClass('fa-volume-up');
                            jQuery('i.fa', this).removeClass('fa-volume-v');
                            jQuery('i.fa', this).addClass('fa-volume-down');
                        }
                    });

                } else {

                    jQuery(".player , #video-volume").hide();

                }

            }

        });
    }
}


/** 08. Mixitup Filter
 **************************************************************** **/
function _mixitup() {
    var _container = jQuery('.mix-grid');

    if (_container.length > 0) {
        loadScript(plugin_path + 'mixitup/jquery.mixitup.min.js', function () {

            if (jQuery().mixitup) {

                _container.mixitup();
                jQuery("ul.mix-filter a").bind("click", function (e) {
                    e.preventDefault();
                });

            }

        });

    }

}



/** 09. Toggle
 **************************************************************** **/
function _toggle() {

    var $_t = this,
            previewParClosedHeight = 25;

    jQuery("div.toggle.active > p").addClass("preview-active");
    jQuery("div.toggle.active > div.toggle-content").slideDown(400);
    jQuery("div.toggle > label").click(function (e) {

        var parentSection = jQuery(this).parent(),
                parentWrapper = jQuery(this).parents("div.toggle"),
                previewPar = false,
                isAccordion = parentWrapper.hasClass("toggle-accordion");

        if (isAccordion && typeof (e.originalEvent) != "undefined") {
            parentWrapper.find("div.toggle.active > label").trigger("click");
        }

        parentSection.toggleClass("active");

        if (parentSection.find("> p").get(0)) {

            previewPar = parentSection.find("> p");
            var previewParCurrentHeight = previewPar.css("height");
            var previewParAnimateHeight = previewPar.css("height");
            previewPar.css("height", "auto");
            previewPar.css("height", previewParCurrentHeight);

        }

        var toggleContent = parentSection.find("> div.toggle-content");

        if (parentSection.hasClass("active")) {

            jQuery(previewPar).animate({height: previewParAnimateHeight}, 350, function () {
                jQuery(this).addClass("preview-active");
            });
            toggleContent.slideDown(350);

        } else {

            jQuery(previewPar).animate({height: previewParClosedHeight}, 350, function () {
                jQuery(this).removeClass("preview-active");
            });
            toggleContent.slideUp(350);

        }

    });
}



/** 11. Placeholder
 **************************************************************** **/
function _placeholder() {

    //check for IE
    if (navigator.appVersion.indexOf("MSIE") != -1) {

        jQuery('[placeholder]').focus(function () {

            var input = jQuery(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }

        }).blur(function () {

            var input = jQuery(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }

        }).blur();

    }

}



/** 12. Word Rotate
 **************************************************************** **/
function _wrotate() {
    jQuery(".word-rotator").each(function () {

        var _t = jQuery(this),
                _items = _t.find(".items"),
                items = _items.find("> span"),
                firstItem = items.eq(0),
                firstItemClone = firstItem.clone(),
                _iHeight = jQuery(this).height(),
                _cItem = 1,
                _cTop = 0,
                _delay = jQuery(this).attr('data-delay') || 2000;

        _items.append(firstItemClone);
        _t.height(_iHeight).addClass("active");

        setInterval(function () {
            _cTop = (_cItem * _iHeight);

            _items.animate({top: -(_cTop) + "px"}, 300, "easeOutQuad", function () {
                _cItem++;

                if (_cItem > items.length) {
                    _items.css("top", 0);
                    _cItem = 1;
                }

            });

        }, _delay);

    });


    var _container = jQuery('span.rotate');

    if (_container.length > 0) {

        loadScript(plugin_path + 'text-rotator/jquery.simple-text-rotator.min.js', function () {

            _container.each(function () {
                var _t = jQuery(this),
                        _animation = _t.attr('data-animation') || 'fade', // fade|flip|flipCube|flipUp|spin
                        _speed = _t.attr('data-speed') || 2000;

                _t.textrotator({
                    animation: _animation,
                    speed: parseInt(_speed)
                });

            });

        });

    }
}




/** 08. Lazy Load
 <img class="lazy" data-original="img/example.jpg" width="765" height="574">
 **************************************************************** **/
function _lazyload() {
    var _container = jQuery('img.lazy');

    if (_container.length > 0) {
        loadScript(plugin_path + 'lazyload/jquery.lazyload.min.js', function () {

            if (jQuery().lazyload) {

                _container.each(function () {
                    var _t = jQuery(this),
                            _effect = _t.attr('data-effect') || 'fadeIn';

                    _t.lazyload({
                        effect: _effect
                    });
                });

            }

        });

    }

}





/** 13. Misc
 **************************************************************** **/
function _misc() {


    /** Portfolio Bugfix
     *********************** **/
    if (jQuery("#portfolio").length > 0) {
        jQuery("#portfolio .item-box .owl-carousel").each(function () {

            // Fix if has owl-carousel slider!
            jQuery(this).parent().parent().find('.item-box-desc').css({"padding-top": "29px"});

        });
    }

    /** Masonry
     *********************** **/
    if (jQuery().masonry) {
        jQuery(".masonry").masonry();
    }



    /** Isotope Portfolio
     *********************** **/
    var portfolio_isotope_container = jQuery("#portfolio.portfolio-isotope");

    if (portfolio_isotope_container.length > 0) {
        loadScript(plugin_path + 'isotope/isotope.pkgd.min.js', function () {

            // Isotope Portfolio
            if (jQuery().isotope) {

                var _container = jQuery('#portfolio');

                // Calculate Item Width on Fullwidth portfolio
                if (_container.hasClass('portfolio-isotope-2')) {
                    _cols = 2;
                } else
                if (_container.hasClass('portfolio-isotope-3')) {
                    _cols = 3;
                } else
                if (_container.hasClass('portfolio-isotope-4')) {
                    _cols = 4;
                } else
                if (_container.hasClass('portfolio-isotope-5')) {
                    _cols = 5;
                } else
                if (_container.hasClass('portfolio-isotope-6')) {
                    _cols = 6;
                } else {
                    _cols = 4;
                }



                function _recalcW() {
                    _dw = jQuery(document).width();

                    if (_container.hasClass('fullwidth')) { // Fullwidth

                        // _w 		= jQuery(document).width(); // NOT USED - problems on aside header
                        _w = _container.width();
                        _wItem = (_w / _cols);

                        if (_dw < 760) {
                            _wItem = (_w / 2);
                        }
                        if (_dw < 480) {
                            _wItem = jQuery("#portfolio").width();
                        }

                        // Apply item width
                        jQuery("#portfolio>.portfolio-item").css({"width": _wItem});

                    } else { // Non Fullwidth

                        _mR = parseInt(jQuery("#portfolio>.portfolio-item").css('margin-right'));
                        _w = jQuery("#portfolio").closest('.container').width();
                        _wItem = _w / _cols - _mR;

                        if (_dw < 760) {
                            _wItem = (_w / 2 - _mR);
                        }
                        if (_dw < 480) {
                            _wItem = _w;
                        }

                        // Apply item & container width
                        jQuery("#portfolio.portfolio-isotope").css({"width": _w});
                        jQuery("#portfolio>.portfolio-item").css({"width": _wItem});

                    }

                    // Resize Flex Slider if exists!
                    if (jQuery('.flexslider').length > 0) {
                        jQuery('.flexslider').resize();
                    }

                }
                _recalcW();



                jQuery(window).on("load", function () {

                    var _t = setTimeout(function () {

                        _container.isotope({
                            masonry: {},

                            filter: '*',
                            animationOptions: {
                                duration: 750,
                                easing: 'linear',
                                queue: false
                            }
                        });

                        jQuery('#portfolio_filter>li>a').bind("click", function (e) {
                            e.preventDefault();

                            jQuery('#portfolio_filter>li.active').removeClass('active');
                            jQuery(this).parent('li').addClass('active');

                            var selector = jQuery(this).attr('data-filter');
                            _container.isotope({
                                filter: selector,
                                animationOptions: {
                                    duration: 750,
                                    easing: 'linear',
                                    queue: false
                                }
                            });

                        });


                    }, 50);

                    setTimeout(function () {
                        _container.isotope('layout');
                    }, 300);

                });



                // On Resize
                jQuery(window).resize(function () {

                    if (window.afterResizeApp2) {
                        clearTimeout(window.afterResizeApp2);
                    }

                    window.afterResizeApp2 = setTimeout(function () {

                        _recalcW();

                        setTimeout(function () {
                            _container.isotope('layout');
                        }, 300);

                    }, 300);

                });


            }


        });
    }	/** end isotope **/




    /** Isotope Blog
     *********************** **/
    var blog_isotope_container = jQuery("#blog.blog-isotope");

    if (blog_isotope_container.length > 0) {
        loadScript(plugin_path + 'isotope/isotope.pkgd.min.js', function () {

            // Isotope blog
            if (jQuery().isotope) {

                var _container = jQuery('#blog');

                // Calculate Item Width on Fullwidth Blog
                if (_container.hasClass('blog-isotope-2')) {
                    _cols = 2;
                } else
                if (_container.hasClass('blog-isotope-3')) {
                    _cols = 3;
                } else
                if (_container.hasClass('blog-isotope-4')) {
                    _cols = 4;
                } else {
                    _cols = 4;
                }


                function _recalcW() {
                    _dw = jQuery(document).width();

                    if (_container.hasClass('fullwidth')) { // Fullwidth

                        _w = jQuery(document).width();
                        _wItem = (_w / _cols);

                        if (_dw < 760) {
                            _wItem = (_w / 2);
                        }
                        if (_dw < 480) {
                            _wItem = jQuery("#blog").width();
                        }

                        // Apply item width
                        jQuery("#blog>.blog-post-item").css({"width": _wItem});

                    } else { // Non Fullwidth

                        _mR = parseInt(jQuery("#blog>.blog-post-item").css('margin-right'));
                        _w = jQuery("#blog").closest('.container').width();
                        _wItem = _w / _cols - _mR;

                        if (_dw < 760) {
                            _wItem = (_w / 2 - _mR);
                        }
                        if (_dw < 480) {
                            _wItem = _w;
                        }

                        // Apply item & container width
                        jQuery("#blog.blog-isotope").css({"width": _w});
                        jQuery("#blog>.blog-post-item").css({"width": _wItem});

                    }

                    // Resize Flex Slider if exists!
                    if (jQuery('.flexslider').length > 0) {
                        jQuery('.flexslider').resize();
                    }

                }
                _recalcW();



                jQuery(window).on("load", function () {

                    var _t = setTimeout(function () {

                        _container.isotope({
                            masonry: {},

                            filter: '*',
                            animationOptions: {
                                duration: 750,
                                easing: 'linear',
                                queue: false
                            }
                        });

                        jQuery('#blog_filter>li>a').bind("click", function (e) {
                            e.preventDefault();

                            jQuery('#blog_filter>li.active').removeClass('active');
                            jQuery(this).parent('li').addClass('active');

                            var selector = jQuery(this).attr('data-filter');
                            _container.isotope({
                                filter: selector,
                                animationOptions: {
                                    duration: 750,
                                    easing: 'linear',
                                    queue: false
                                }
                            });

                        });


                    }, 50);

                    setTimeout(function () {
                        _container.isotope('layout');
                    }, 300);

                });



                // On Resize
                jQuery(window).resize(function () {

                    if (window.afterResizeApp2) {
                        clearTimeout(window.afterResizeApp2);
                    }

                    window.afterResizeApp2 = setTimeout(function () {

                        _recalcW();

                        setTimeout(function () {
                            _container.isotope('layout');
                        }, 300);

                    }, 300);

                });


            }


        });
    }	/** end isotope **/




    /** Flip Boxes
     *********************** **/
    if (jQuery('.box-flip').length > 0) {

        jQuery('.box-flip').each(function () {
            _height1 = jQuery('.box1', this).outerHeight();
            _height2 = jQuery('.box2', this).outerHeight();

            if (_height1 >= _height2) {
                _height = _height1;
            } else {
                _height = _height2;
            }

            jQuery(this).css({"min-height": _height + "px"});
            jQuery('.box1', this).css({"min-height": _height + "px"});
            jQuery('.box2', this).css({"min-height": _height + "px"});
        });

        jQuery('.box-flip').hover(function () {
            jQuery(this).addClass('flip');
        }, function () {
            jQuery(this).removeClass('flip');
        });
    }




    /** Sticky Side (social icons)
     *********************** **/
    if (jQuery("div.sticky-side").length > 0) {

        var _t = jQuery("div.sticky-side");
        _h = _t.height() / 2;

        _t.css({"margin-top": "-" + _h + "px"});
    }




    /** Increase / Decrease No.
     Example: shop-single-left.html
     *********************** **/
    jQuery(".incr").bind("click", function (e) {
        e.preventDefault();

        var _for = jQuery(this).attr('data-for'),
                _max = parseInt(jQuery(this).attr('data-max')),
                _curVal = parseInt(jQuery("#" + _for).val());

        if (_curVal < _max) {
            jQuery("#" + _for).val(_curVal + 1);
        }
    });

    jQuery(".decr").bind("click", function (e) {
        e.preventDefault();

        var _for = jQuery(this).attr('data-for'),
                _min = parseInt(jQuery(this).attr('data-min')),
                _curVal = parseInt(jQuery("#" + _for).val());

        if (_curVal > _min) {
            jQuery("#" + _for).val(_curVal - 1);
        }
    });





    /** Default Button Toggle
     *********************** **/
    jQuery("a.toggle-default").bind("click", function (e) {
        e.preventDefault();

        var _href = jQuery(this).attr('href');

        if (jQuery(_href).is(":hidden")) {

            jQuery(_href).slideToggle(200);
            jQuery('i.fa', this).removeClass('fa-plus-square').addClass('fa-minus-square');

        } else {

            jQuery(_href).slideToggle(200);
            jQuery('i.fa', this).removeClass('fa-minus-square').addClass('fa-plus-square');

        }

    });





    /** Custom File Upload
     <input class="custom-file-upload" type="file" id="file" name="myfiles[]" multiple />
     *********************** **/
    var file_container = jQuery("input[type=file]");

    if (file_container.length > 0) {
        loadScript(plugin_path + 'custom.fle_upload.js');
    }



    /** Textarea Words Limit
     *********************** **/
    jQuery("textarea.word-count").on('keyup', function () {
        var _t = jQuery(this),
                words = this.value.match(/\S+/g).length,
                _limit = _t.attr('data-maxlength') || 200;

        if (words > parseInt(_limit)) {

            // Split the string on first 200 words and rejoin on spaces
            var trimmed = _t.val().split(/\s+/, 200).join(" ");
            // Add a space at the end to keep new typing making new words
            _t.val(trimmed + " ");

        } else {

            var _data_info = _t.attr('data-info');

            if (_data_info == '' || _data_info == undefined) {
                var _infoContainer = _t.next('div');
                jQuery('span', _infoContainer).text(words + '/' + _limit);
            } else {
                jQuery('#' + _data_info).text(words + '/' + _limit);
            }


        }
    });




}



/** Sticky Footer
 **************************************************************** **/
function _stickyFooter() {
    if (jQuery("#footer").hasClass('sticky')) {

        var footerHeight = 0,
                footerTop = 0,
                _footer = jQuery("#footer.sticky");

        positionFooter();

        function positionFooter() {
            footerHeight = _footer.height();
            footerTop = (jQuery(window).scrollTop() + jQuery(window).height() - footerHeight) + "px";

            if ((jQuery(document.body).height() + footerHeight) > jQuery(window).height()) {
                _footer.css({
                    position: "absolute"
                }).stop().animate({
                    top: footerTop
                }, 0);
            } else {
                _footer.css({position: "static"});
            }

        }

        jQuery(window).scroll(positionFooter).resize(positionFooter);

    }
}





/** Countdown
 **************************************************************** **/
function _countDown() {
    var _container = jQuery(".countdown"),
            _container2 = jQuery(".countdown-download");

    if (_container.length > 0 || _container2.length > 0) {

        loadScript(plugin_path + 'countdown/jquery.countdown.pack.min.js', function () {

            /** On Page Load **/
            _container.each(function () {
                var _t = jQuery(this),
                        _date = _t.attr('data-from'),
                        _labels = _t.attr('data-labels');

                if (_labels) {
                    _labels = _labels.split(",");
                }

                if (_date) {
                    var _d = new Date(_date);
                    jQuery(this).countdown({
                        until: new Date(_d),
                        labels: _labels || ["Years", "Months", "Weeks", "Days", "Hours", "Minutes", "Seconds"]
                    });
                }
            });


            /** Download **/
            _container2.bind("click", function (e) {
                e.preventDefault();

                var _t = jQuery(this),
                        cd_container = _t.attr('data-for'),
                        _countdown = jQuery("#" + cd_container + ' span.download-wait>.countdown'),
                        _seconds = parseInt(_t.attr('data-seconds')),
                        _dataURL = _t.attr('href');

                _t.fadeOut(250, function () {
                    jQuery("#" + cd_container).fadeIn(250, function () {

                        var currentDate = new Date();
                        currentDate.setSeconds(currentDate.getSeconds() + _seconds);

                        _countdown.countdown({
                            until: currentDate,
                            format: 'S',
                            expiryUrl: _dataURL,
                            onExpiry: function () {
                                jQuery("#" + cd_container + ' span.download-message').removeClass('hide');
                                jQuery("#" + cd_container + ' span.download-wait').addClass('hide');
                            }
                        });

                    });
                });

                return false;

            });


        });

    }

}



/** Masonry Gallery
 **************************************************************** **/
function _masonryGallery() {

    if (jQuery(".masonry-gallery").length > 0) {

        jQuery(".masonry-gallery").each(function () {
            var _container = jQuery(this),
                    columns = 4;

            if (_container.hasClass('columns-2'))
                columns = 2;
            else if (_container.hasClass('columns-3'))
                columns = 3;
            else if (_container.hasClass('columns-4'))
                columns = 4;
            else if (_container.hasClass('columns-5'))
                columns = 5;
            else if (_container.hasClass('columns-6'))
                columns = 6;

            var _firstElemWidth = _container.find('a:eq(0)').outerWidth(),
                    _bigImageNo = _container.attr('data-img-big'),
                    _containerWidth = _container.width();


            // Fix margins & Width
            var postWidth = (_containerWidth / columns);
            postWidth = Math.floor(postWidth);
            if ((postWidth * columns) >= _containerWidth) {
                _container.css({'margin-right': '-1px'});
            }
            if (columns < 6) {
                _container.children('a').css({"width": postWidth + "px"});
            }


            // Set Big Image
            if (parseInt(_bigImageNo) > 0) {

                _bigImageNo = Number(_bigImageNo) - 1;
                _container.find('a:eq(' + _bigImageNo + ')').css({width: _firstElemWidth * 2 + 'px'});

                loadScript(plugin_path + 'isotope/isotope.pkgd.min.js', function () {

                    setTimeout(function () {
                        _container.isotope({
                            masonry: {
                                columnWidth: _firstElemWidth
                            }
                        });

                        _container.isotope('layout');

                    }, 1000);

                });

            }

        });


    }

}




/** Toastr
 
 TYPE:
 primary
 info
 error
 sucess
 warning
 
 POSITION
 top-right
 top-left
 top-center
 top-full-width
 bottom-right
 bottom-left
 bottom-center
 bottom-full-width
 
 USAGE:
 _toastr("My Message here","top-right","error",false);
 
 NOTE:
 _onclick = url to redirect (example: http://www.stepofweb.com)
 **************************************************************** **/
function _toastr(_message, _position, _notifyType, _onclick) {
    var _btn = jQuery(".toastr-notify");

    if (_btn.length > 0 && _message != false) {

        loadScript(plugin_path + 'toastr/toastr.js', function () {
            // toastr.clear();

            /** BUTTON CLICK
             ********************* **/
            _btn.bind("click", function (e) {
                e.preventDefault();


                var _message = jQuery(this).attr('data-message'),
                        _notifyType = jQuery(this).attr('data-notifyType') || "default",
                        _position = jQuery(this).attr('data-position') || "top-right",
                        _progressBar = jQuery(this).attr('data-progressBar') == "true" ? true : false,
                        _closeButton = jQuery(this).attr('data-closeButton') == "true" ? true : false,
                        _debug = jQuery(this).attr('data-debug') == "true" ? true : false,
                        _newestOnTop = jQuery(this).attr('data-newestOnTop') == "true" ? true : false,
                        _preventDuplicates = jQuery(this).attr('data-preventDuplicates') == "true" ? true : false,
                        _showDuration = jQuery(this).attr('data-showDuration') || "300",
                        _hideDuration = jQuery(this).attr('data-hideDuration') || "1000",
                        _timeOut = jQuery(this).attr('data-timeOut') || "5000",
                        _extendedTimeOut = jQuery(this).attr('data-extendedTimeOut') || "1000",
                        _showEasing = jQuery(this).attr('data-showEasing') || "swing",
                        _hideEasing = jQuery(this).attr('data-hideEasing') || "linear",
                        _showMethod = jQuery(this).attr('data-showMethod') || "fadeIn",
                        _hideMethod = jQuery(this).attr('data-hideMethod') || "fadeOut";

                toastr.options = {
                    "closeButton": _closeButton,
                    "debug": _debug,
                    "newestOnTop": _newestOnTop,
                    "progressBar": _progressBar,
                    "positionClass": "toast-" + _position,
                    "preventDuplicates": _preventDuplicates,
                    "onclick": null,
                    "showDuration": _showDuration,
                    "hideDuration": _hideDuration,
                    "timeOut": _timeOut,
                    "extendedTimeOut": _extendedTimeOut,
                    "showEasing": _showEasing,
                    "hideEasing": _hideEasing,
                    "showMethod": _showMethod,
                    "hideMethod": _hideMethod
                }

                toastr[_notifyType](_message);
            });


            /** JAVSCRIPT / ON LOAD
             ************************* **/
            if (_message != false) {

                if (_onclick != false) {
                    onclick = function () {
                        window.location = _onclick;
                    }
                } else {
                    onclick = null
                }

                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-" + _position,
                    "preventDuplicates": false,
                    "onclick": onclick,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }

                setTimeout(function () {
                    toastr[_notifyType](_message);
                }, 1500); // delay 1.5s
            }
        });

    }

}


/** Chart
 **************************************************************** **/
function _charts() {

    /** Easy Pie Chart
     ************************* **/
    var _container = jQuery(".piechart");

    if (_container.length > 0) {

        loadScript(plugin_path + 'chart.easypiechart/dist/jquery.easypiechart.min.js', function () {

            jQuery(".piechart").each(function () {
                var _t = jQuery(this),
                        _size = _t.attr('data-size') || 150,
                        _animate = _t.attr('data-animate') || "3000";

                _t.easyPieChart({
                    size: _size,
                    animate: _animate,
                    scaleColor: false,
                    trackColor: _t.attr('data-trackcolor') || 'rgba(0,0,0,0.04)',
                    lineWidth: _t.attr('data-width') || '2',
                    lineCap: 'square',
                    barColor: _t.attr('data-color') || '#0093BF'
                });

                /* fix element positioning */
                jQuery("*", this).attr('style', "line-height:" + _size + "px !important; height:" + _size + "px !important; width:" + _size + "px !important");

            });

        });

    }


}



/** Select2
 **************************************************************** **/
function _select2() {
    var _container = jQuery('select.select2');

    if (_container.length > 0) {

        loadScript(plugin_path + 'select2/js/select2.full.min.js', function () {
            selec2itme = 1;
            _container.each(function () {
                var _t = jQuery(this);

                if (!_t.hasClass('select2-custom')) {
                    _t.select2();
                }

            })

        });
    }

}




/** Form [form plugin + validation plugin]
 **************************************************************** **/
function _form() {


    /** Form Validate
     LOAD PLUGIN ONLY!
     ************************ **/
    if (jQuery('form.validate-plugin').length > 0) {

        loadScript(plugin_path + 'form.validate/jquery.form.min.js', function () {
            loadScript(plugin_path + 'form.validate/jquery.validation.min.js');
        });

    }



    /** Form Validate
     ************************ **/
    if (jQuery('form.validate').length > 0) {

        loadScript(plugin_path + 'form.validate/jquery.form.min.js', function () {
            loadScript(plugin_path + 'form.validate/jquery.validation.min.js', function () {

                if (jQuery().validate) {

                    jQuery('form.validate').each(function () {

                        var _t = jQuery(this),
                                _Smessage = _t.attr('data-success') || "Successfully! Thank you!",
                                _Cmessage = _t.attr('data-captcha') || "Invalid Captcha!",
                                _Tposition = _t.attr('data-toastr-position') || "top-right",
                                _Ttype = _t.attr('data-toastr-type') || "success";
                        _Turl = _t.attr('data-toastr-url') || false;

                        // Append 'is_ajax' hidden input field!
                        _t.append('<input type="hidden" name="is_ajax" value="true" />');

                        _t.validate({
                            submitHandler: function (form) {

                                // Show spin icon
                                jQuery(form).find('.input-group-addon').find('.fa-envelope').removeClass('fa-envelope').addClass('fa-refresh fa-spin');

                                jQuery(form).ajaxSubmit({

                                    target: jQuery(form).find('.validate-result').length > 0 ? jQuery(form).find('.validate-result') : '',

                                    error: function (data) {
                                        _toastr("Sent Failed!", _Tposition, "error", false);
                                    },

                                    success: function (data) {
                                        var data = data.trim();

                                        // SMTP ERROR
                                        if (data == '_failed_') {
                                            _toastr("SMTP ERROR! Please, check your config file!", _Tposition, "error", false);
                                        }

                                        // CAPTCHA ERROR
                                        else if (data == '_captcha_') {
                                            _toastr("Invalid Captcha!", _Tposition, "error", false);


                                            // SUCCESS
                                        } else {

                                            // Remove spin icon
                                            jQuery(form).find('.input-group-addon').find('.fa-refresh').removeClass('fa-refresh fa-spin').addClass('fa-envelope');

                                            // Clear the form
                                            jQuery(form).find('input.form-control').val('');

                                            // Toastr Message
                                            _toastr(_Smessage, _Tposition, _Ttype, _Turl);

                                        }
                                    }
                                });

                            }
                        });

                    });

                }

            });
        });

    }




    /** Masked Input
     ************************ **/
    var _container = jQuery('input.masked');
    if (_container.length > 0) {

        loadScript(plugin_path + 'form.masked/jquery.maskedinput.js', function () {

            _container.each(function () {

                var _t = jQuery(this);
                _format = _t.attr('data-format') || '(999) 999-999999',
                        _placeholder = _t.attr('data-placeholder') || 'X';

                jQuery.mask.definitions['f'] = "[A-Fa-f0-9]";
                _t.mask(_format, {placeholder: _placeholder});

            });

        });

    }

}





/** Pickers
 **************************************************************** **/

function _regPickers() {
    var _container_1 = jQuery('.reg-datepicker');

    if (_container_1.length > 0) {

        loadScript(plugin_path + 'bootstrap.datepicker/js/bootstrap-datepicker.min.js', function () {

            if (jQuery().datepicker) {
                _container_1.each(function () {
                    var dt = new Date();
                    dt.setFullYear(new Date().getFullYear() - 18);
                    var _t = jQuery(this),
                            _lang = _t.attr('data-lang') || 'en';

                    if (_lang != 'en' && _lang != '') { // load language file
                        loadScript(plugin_path + 'bootstrap.datepicker/locales/bootstrap-datepicker.' + _lang + '.min.js');
                    }
                    console.log(_t);
                    jQuery(this).datepicker({
                        format: _t.attr('data-format') || 'yyyy-mm-dd',
                        language: _lang,
                        rtl: _t.attr('data-RTL') == "true" ? true : false,
                        changeMonth: _t.attr('data-changeMonth') == "false" ? false : true,
                        todayBtn: _t.attr('data-todayBtn') == "false" ? false : "linked",
                        calendarWeeks: _t.attr('data-calendarWeeks') == "false" ? false : true,
                        autoclose: _t.attr('data-autoclose') == "false" ? false : true,
                        todayHighlight: _t.attr('data-todayHighlight') == "false" ? false : true,
                        endDate: _t.attr('endDate') == "true" ? '' : dt,
                        onRender: function (date) {
                            // return date.valueOf() < nowDate.valueOf() ? 'disabled' : '';
                        }
                    }).on('changeDate', function (ev) {

                        // AJAX POST - OPTIONAL

                    }).data('datepicker');
                });

            }

        });
    }
}


function _pickers() {

    /** Date Picker
     <input type="text" class="form-control datepicker" data-format="yyyy-mm-dd" data-lang="en" data-RTL="false">
     ******************* **/
    var _container_1 = jQuery('.datepicker');

    if (_container_1.length > 0) {
        loadScript(plugin_path + 'bootstrap.datepicker/js/bootstrap-datepicker.min.js', function () {

            if (jQuery().datepicker) {

                _container_1.each(function () {
                    var _t = jQuery(this),
                            _lang = _t.attr('data-lang') || 'en';

                    if (_lang != 'en' && _lang != '') { // load language file
                        loadScript(plugin_path + 'bootstrap.datepicker/locales/bootstrap-datepicker.' + _lang + '.min.js');
                    }

                    jQuery(this).datepicker({
                        format: _t.attr('data-format') || 'yyyy-mm-dd',
                        language: _lang,
                        rtl: _t.attr('data-RTL') == "true" ? true : false,
                        changeMonth: _t.attr('data-changeMonth') == "false" ? false : true,
                        todayBtn: _t.attr('data-todayBtn') == "false" ? false : "linked",
                        calendarWeeks: _t.attr('data-calendarWeeks') == "false" ? false : true,
                        autoclose: _t.attr('data-autoclose') == "false" ? false : true,
                        todayHighlight: _t.attr('data-todayHighlight') == "false" ? false : true,

                        onRender: function (date) {
                            // return date.valueOf() < nowDate.valueOf() ? 'disabled' : '';
                        }
                    }).on('changeDate', function (ev) {

                        // AJAX POST - OPTIONAL

                    }).data('datepicker');
                });

            }

        });
    }




    /** Range Picker
     <input type="text" class="form-control rangepicker" value="2015-01-01 - 2016-12-31" data-format="yyyy-mm-dd" data-from="2015-01-01" data-to="2016-12-31">
     ******************* **/
    var _container_2 = jQuery('.rangepicker');

    if (_container_2.length > 0) {
        loadScript(plugin_path + 'bootstrap.daterangepicker/moment.min.js', function () {
            loadScript(plugin_path + 'bootstrap.daterangepicker/daterangepicker.js', function () {

                if (jQuery().datepicker) {

                    _container_2.each(function () {

                        var _t = jQuery(this),
                                _format = _t.attr('data-format').toUpperCase() || 'YYYY-MM-DD';

                        _t.daterangepicker(
                                {
                                    format: _format,
                                    startDate: _t.attr('data-from'),
                                    endDate: _t.attr('data-to'),

                                    ranges: {
                                        'Today': [moment(), moment()],
                                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                                    }
                                },
                                function (start, end, label) {
                                    // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                                });

                    });

                }

            });
        });
    }



    /** Time Picker
     <input type="text" class="form-control timepicker" value="11 : 55 : PM">
     ******************* **/
    var _container_3 = jQuery('.timepicker');

    if (_container_3.length > 0) {
        loadScript(plugin_path + 'timepicki/timepicki.min.js', function () {

            if (jQuery().timepicki) {

                _container_3.timepicki();

            }

        });
    }



    /** Color Picker
     ******************* **/
    var _container_4 = jQuery('.colorpicker');

    if (_container_4.length > 0) {
        loadScript(plugin_path + 'spectrum/spectrum.min.js', function () {

            if (jQuery().spectrum) {

                _container_4.each(function () {
                    var _t = jQuery(this),
                            _preferredFormat = _t.attr('data-format') || "hex", // hex, hex3, hsl, rgb, name
                            _palletteOnly = _t.attr('data-palletteOnly') || "false",
                            _fullPicker = _t.attr('data-fullpicker') || "false",
                            _allowEmpty = _t.attr('data-allowEmpty') || false;
                    _flat = _t.attr('data-flat') || false;

                    if (_palletteOnly == "true" || _fullPicker == "true") {

                        var _palette = [
                            ["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
                            ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
                            ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"],
                            ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
                            ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"],
                            ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
                            ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
                            ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]
                        ];

                    } else {
                        _palette = null;
                    }

                    if (_t.attr('data-defaultColor')) {
                        _color = _t.attr('data-defaultColor');
                    } else {
                        _color = "#ff0000";
                    }

                    if (!_t.attr('data-defaultColor') && _allowEmpty == "true") {
                        _color = null;
                    }

                    _t.spectrum({
                        showPaletteOnly: _palletteOnly == "true" ? true : false,
                        togglePaletteOnly: _palletteOnly == "true" ? true : false,

                        flat: _flat == "true" ? true : false,
                        showInitial: _allowEmpty == "true" ? true : false,
                        showInput: _allowEmpty == "true" ? true : false,
                        allowEmpty: _allowEmpty == "true" ? true : false,

                        chooseText: _t.attr('data-chooseText') || "Coose",
                        cancelText: _t.attr('data-cancelText') || "Cancel",

                        color: _color,
                        showInput: true,
                        showPalette: true,
                        preferredFormat: _preferredFormat,
                        showAlpha: _preferredFormat == "rgb" ? true : false,
                        palette: _palette
                    });

                });

            }

        });
    }

}





/** Editors
 **************************************************************** **/
function _editors() {

    /** Summernote HTML Editor
     <textarea class="summernote form-control" data-height="200"></textarea>
     ***************************** **/
    var _container_1 = jQuery('textarea.summernote');

    if (_container_1.length > 0) {

        loadScript(plugin_path + 'editor.summernote/summernote.min.js', function () {

            if (jQuery().summernote) {

                _container_1.each(function () {

                    var _lang = jQuery(this).attr('data-lang') || 'en-US';

                    if (_lang != 'en-US') { // Language!
                        alert(_lang);
                        loadScript(plugin_path + 'editor.summernote/lang/summernote-' + _lang + '.js');
                    }

                    jQuery(this).summernote({
                        height: jQuery(this).attr('data-height') || 200,
                        lang: jQuery(this).attr('data-lang') || 'en-US', // default: 'en-US'
                        toolbar: [
                            /*	[groupname, 	[button list]]	*/
                            ['style', ['style']],
                            ['fontsize', ['fontsize']],
                            ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['media', ['link', 'picture', 'video']],
                            ['misc', ['codeview', 'fullscreen', 'help']]
                        ]
                    });
                });

            }
        });
    }





    /** Markdown HTML Editor
     <textarea class="markdown" data-height="300" name="content" data-provide="markdown" data-lang="en" rows="10"></textarea>
     ***************************** **/
    var _container_2 = jQuery('textarea.markdown');

    if (_container_2.length > 0) {

        loadScript(plugin_path + 'editor.markdown/js/bootstrap-markdown.min.js', function () {

            if (jQuery().markdown) {

                _container_2.each(function () {
                    var _t = jQuery(this);

                    var _lang = _t.attr('data-lang') || 'en';

                    if (_lang != 'en') { // Language!
                        loadScript(plugin_path + 'editor.markdown/locale/bootstrap-markdown.' + _lang + '.js');
                    }

                    jQuery(this).markdown({
                        autofocus: _t.attr('data-autofocus') == "true" ? true : false,
                        savable: _t.attr('data-savable') == "true" ? true : false,
                        height: _t.attr('data-height') || 'inherit',
                        language: _lang == 'en' ? null : _lang
                    });

                });

            }

        });

    }

}






/** Pajinate [jQuery Pagination]
 USAGE
 
 <div class="pajinate" data-pajinante-items-per-page="8" data-pajinate-container=".pajinate-container">
 
 <div class="pajinate-container">
 
 <div>item1</div>
 <div>item2</div>
 <div>item3</div>
 .....
 
 </div>
 
 <div class="pajinate-nav">
 <ul class="pagination"><!-- pages added by pajinate plugin --></ul>
 </div>
 
 </div>
 **************************************************************** **/
function _pajinate() {
    var _container = jQuery('div.pajinate');

    if (_container.length > 0) {

        loadScript(plugin_path + 'pajinate/jquery.pajinate.bootstrap.min.js', function () {

            if (jQuery().pajinate) {

                _container.each(function () {
                    var _t = jQuery(this),
                            _perPage = _t.attr('data-pajinante-items-per-page') || 8;
                    _numLinks = _t.attr('data-pajinante-num-links') || 5;

                    _t.pajinate({
                        items_per_page: parseInt(_perPage),
                        num_page_links_to_display: parseInt(_numLinks),
                        item_container_id: _t.attr('data-pajinate-container') || '.pajinate-container',
                        nav_panel_id: '.pajinate-nav ul',
                        show_first_last: false,
                        wrap_around: true,
                        abort_on_small_lists: true,
                        start_page: 0,
                        nav_label_prev: '&laquo;',
                        nav_label_next: '&raquo;'
                    });

                });
            }

        });

    }

}





/** Infininte Scroll
 **************************************************************** **/
function _infiniteScroll() {
    var _container = jQuery(".infinite-scroll");

    if (_container.length > 0) {

        loadScript(plugin_path + 'infinite-scroll/jquery.infinitescroll.min.js', function () {

            _navSelector = _container.attr('data-nextSelector') || "#inf-load-nex",
                    _itemSelector = _container.attr('data-itemSelector') || ".item",
                    _nextSelector = _navSelector + " a";

            _container.infinitescroll({
                loading: {
                    finishedMsg: '<i class="fa fa-check"></i>',
                    msgText: '<i class="fa fa-refresh fa-spin"></i>',
                    img: "data:image/gif;base64,R0lGODlhGAAYAPUAABQSFCwuLBwaHAwKDKyurGxqbNze3CwqLCQmJLS2tOzu7OTi5JyenBweHBQWFJyanPz+/HRydLSytFxeXPz6/ExOTKSmpFRSVHR2dAwODAQCBOzq7PTy9ISChPT29IyKjIyOjISGhOTm5GRiZJSWlJSSlFRWVMTCxNza3ExKTNTS1KyqrHx6fGRmZKSipMzOzMTGxDQyNDw+PAQGBDQ2NERCRFxaXMzKzGxubDw6PCQiJLy+vERGRLy6vHx+fNTW1CH/C05FVFNDQVBFMi4wAwEAAAAh+QQJBQAAACwAAAAAGAAYAEAGqECAcAhoRAiojQJFiAiI0Kh0qOsZOhqhDMK9ZadgAI0WBmhAXAhFVm5HbZR0aTYdsFpSkwqjo5sRLAtpIjxuUzZpECmGjI1QA4JcKH5lGVICDHFpGyoqGx4uDWENFh4iKjcbiR4MT1ItLJSPJWkUNo9uAyhpBpaOGjdpOY7ExcYaIQs9OsUpibfENZoQIF9gY1EpqlwiLAh+M4AqJmUCOBJJGz8EOKJRQQAh+QQJBQABACwAAAAAGAAYAAAGp8CAcBhoRBILDgdFKAiI0KHAB5rUZBUWDALxMJ5R4SCmiWpoJ67iEm4TZx0upOCuB1jyir2tuXE3DnthE3IlglENchwDh0QDG3ITjUQ7ciGTQxFybJgBGkcYGhoYPaGdARdyOKchcjunhH8znQAccmCYJZGnDpAQN2WdFXI+pwEFch2znRe+MDTBbzGMbQIPHlwwLBcyNSMgLIF2Ai0WKAocBhI4uERBACH5BAkFACwALAAAAAAYABgAAAaoQJZwyNIEJiAJCpWICIjQKFGD6Gw8D4d0C3UQIJsKd1wsQSgFMldjgUAu6q1jA27EpRg34x5FUCAeT3xDAx5uBQAMJyZ8GRxuFiRuFAF3B24QKguYE3cpmAubbil3I5gGKpgIdwF/EA9tgAN8JicMGQVuHLODQgKGEKu9QgxuGMNCDQpgAMgsF38rGs4Ffx/TyBUiECtayAIPHgohAdi9DRFKTCAj5VJBACH5BAkFAAAALAAAAAAYABgAAAa0QIBwSAQMaphHoVFsOoezlsEleFqJDsnmcu1qLJBW9zpQUSpjqwyycQgPBAIiLYRBGIDMAgJRaegREB4CE3wQFAN0NHwRYHwwdAANfBIqhlx0AXwGCnx+kQV8Cp0QBZEaL3wbBnwBkReGKgl8TGkadnwugRA0dBkUhhMNHhARdBqWEAsZAAwQkHQIEgQHQgIbFDKRTRUUL4nbRC0QFjPhRBcbEm7nQg0uBi3g7Q0RDxEyzFdBACH5BAkFAAgALAAAAAAYABgAAAaxQIRwSCwKHMWkssgLCZbQYmNnUgpMh6gQoIoUZQqIh6ZFHDjV7QLCLpURIcUTAWKzvWUBhYFwcOwnA28IOx4CBXY3AIMIJRAFEmwoSIwYEAQGbDWMQiwQBh4QKpxCjhyhbqQqEByZLKQ1bAaRr4wOKGwSiKlvADd2BQIeJ4MDJ3YcSA8UlFqWdiBCAgohbyR2C4tCJhwBZTQUEAo5RQUqzVAHJuhDJjsNpFIhKfFG7FFBACH5BAkFAAAALAAAAQAYABYAAAa3QIBQmEnlNMOkcgmoGSCQEJNIY048UIhhKqS1lClKFtLjClmmoWAzvunMgJmqIWRkDTYkHIBxARpiECUDe0MIHg0RUCV6hQAaGxESEAszjkkvEk8sl0kqKgoQCJ1CGiIKChuNlwcQCigvpGcQKBKxpAMLEBI4IpaXGiVQODoeb44DwhAUAgAuGIUaEyhZDEINKr9cCDdjG81CJpxmO2MUPEojVVy6UBQ2TDGEUyFQCzKyjzk880NBACH5BAkFAAEALAAAAAAYABgAAAazwIBwGABMOhcNcckUOkoKiJTVrAYqG6k2YWXiKFptpEs0gbWbXmFmHQwbWcjNJlCSYwIhQ9qxk4UaVAIeEB1/TCANBRAnfodCExEEEDSPSzUJKCeWSzQGHBicRBUcHimiQywKC5WoGjAoCTKoATQUBBETqDMnEAUNH6ghEBQOAT6OZBo+UgxCAjF/Mw0TN1IKeUJuVTMFPSJhEBePGOHEBZYJ4SI8nCxaHB/GnBoXISYATUEAIfkECQUAKgAsAAAAABgAGAAABqpAlXCoErQsr4WBlCE6nQ2XB0Ktup5Yk6LKhZywzgKlyplSKRfwsELdYA6DDCI1OaiFgg2EALirHxAfGn5gDR4rg4RPGhEbDopYAQkdkFgjBnaVTiEoiZpDCQmfRBooIKNDBwYjqEIdCQGtDgoFnpoaEh4NqBogEA+oDisQjn4xExUIAAMILCIQFBV+JmNUHh7VEAWEMF1VCmmELt4UDAKQGSUoCy8WI+dPQQAh+QQJBQAJACwAAAAAGAAYAAAGrMCEcJhoRCQoxUblmmSI0KGA4YFYr9bFIUqsbLBgK4ErLFAosEiuESi8sBKyifKqRTWXk+el4zYULgNkQhkaZBYShoOLOigAi5ARE5CQDzOUixGYi3abXANPnlE5olyapUQzD6hELaesDgYNrAkzEi5kMwOKnxYbs1EIKh4wF5dQNSoQF2QSWC8FATo0GDcUHi2DBGFgGymLBwvcEBQPDpQZNi4qGxsoEjgCXEEAIfkEBQUACAAsAAAAABgAGAAABqZAhHCIEBQIBg/HICk4iNCh4OGBWK9WTgkQHZoUlFMJwyKpsJCFrBvhhJ7QGgqrgA9tr0BX6HhhTUQNO3Z7ADBWFAdEIQJ7UAMRJTREAjyOl0MNmJucnZ6foKGio6SdmqQphDljA5wCIUQBVRAwXJcAO6dCJlg3tl0BPxdQAgpYKDVRAh8cOF05C2g/JSw+JTAeCsOFJRxoVx4PjZgORygcHCgETl1BADs=",
                    speed: 'normal'
                },
                nextSelector: _nextSelector,
                navSelector: _navSelector,
                itemSelector: _itemSelector,
                behavior: '',

                state: {
                    isDone: false
                }
            },
                    function (newElements) {

                        Init(true);

                        if (jQuery().isotope) {

                            _container.isotope('appended', jQuery(newElements));

                            setTimeout(function () {
                                _container.isotope('layout');
                            }, 2000);

                        }

                    });

        });

    }

}





/** Image Zoom
 **************************************************************** **/
function _zoom() {
    var _container = jQuery('figure.zoom');

    if (_container.length > 0) {

        loadScript(plugin_path + 'image.zoom/jquery.zoom.min.js', function () {

            if (jQuery().zoom) {

                _container.each(function () {
                    var _t = jQuery(this),
                            _mode = _t.attr('data-mode'),
                            _id = _t.attr('id');

                    if (_mode == 'grab') {
                        _t.zoom({on: 'grab'});
                    } else

                    if (_mode == 'click') {
                        _t.zoom({on: 'click'});
                    } else

                    if (_mode == 'toggle') {
                        _t.zoom({on: 'toggle'});
                    } else {
                        _t.zoom();
                    }

                    if (isMobile.any()) {
                        _t.zoom({on: 'toggle'});
                    }

                    // Thumbnails
                    if (_id) {
                        jQuery('.zoom-more[data-for=' + _id + '] a').bind("click", function (e) {
                            e.preventDefault();

                            var _href = jQuery(this).attr('href');

                            if (_href != "#") {
                                jQuery('.zoom-more[data-for=' + _id + '] a').removeClass('active');
                                jQuery(this).addClass('active');

                                jQuery('figure#' + _id + '>.lightbox').attr('href', _href);

                                jQuery('figure#' + _id + '>img').fadeOut(0, function () {
                                    jQuery('figure#' + _id + '>img').attr('src', _href);
                                }).fadeIn(500);


                            }
                        });
                    }

                });

            }

        });

    }

}





/** Autosuggest
 http://twitter.github.io/typeahead.js/
 **************************************************************** **/
function _autosuggest() {
    _container = jQuery('div.autosuggest');

    if (_container.length > 0) {

        loadScript(plugin_path + 'typeahead.bundle.js', function () {

            if (jQuery().typeahead) {

                _container.each(function () {
                    var _t = jQuery(this),
                            _minLength = _t.attr('data-minLength') || 1,
                            _qryURL = _t.attr('data-queryURL'),
                            _limit = _t.attr('data-limit') || 10,
                            _autoload = _t.attr('data-autoload');

                    if (_autoload == "false") {
                        return false;
                    }

                    /** **/
                    /* Bloodhound (Suggestion Engine) */
                    var _typeahead = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: _limit,
                        remote: {
                            url: _qryURL + '%QUERY',
                        },
                    });

                    jQuery('.typeahead', _t).typeahead({
                        limit: _limit,
                        hint: _t.attr('data-hint') == "false" ? false : true,
                        highlight: _t.attr('data-highlight') == "false" ? false : true,
                        minLength: parseInt(_minLength),

                        cache: false,
                    },
                            {
                                name: '_typeahead',
                                source: _typeahead
                            });
                    /** **/

                });


            }

        });

    }

}






/** Form Stepper
 **************************************************************** **/
function _stepper() {
    var _container = jQuery('input.stepper');

    if (_container.length > 0) {

        loadScript(plugin_path + 'form.stepper/jquery.stepper.min.js', function () {

            if (jQuery().stepper) {

                jQuery(_container).each(function () {
                    var _t = jQuery(this),
                            _min = _t.attr('min') || null,
                            _max = _t.attr('max') || null;

                    _t.stepper({
                        limit: [_min, _max],
                        floatPrecission: _t.attr('data-floatPrecission') || 2,
                        wheel_step: _t.attr('data-wheelstep') || 0.1,
                        arrow_step: _t.attr('data-arrowstep') || 0.2,
                        allowWheel: _t.attr('data-mousescrool') == "false" ? false : true,
                        UI: _t.attr('data-UI') == "false" ? false : true,
                        // --
                        type: _t.attr('data-type') || "float",
                        preventWheelAcceleration: _t.attr('data-preventWheelAcceleration') == "false" ? false : true,
                        incrementButton: _t.attr('data-incrementButton') || "&blacktriangle;",
                        decrementButton: _t.attr('data-decrementButton') || "&blacktriangledown;",
                        onStep: null,
                        onWheel: null,
                        onArrow: null,
                        onButton: null,
                        onKeyUp: null
                    });

                });

            }

        });

    }

}






/** Slimscroll
 **************************************************************** **/
function _slimScroll() {
    var _container = jQuery('.slimscroll');

    if (_container.length > 0) {

        loadScript(plugin_path + 'slimscroll/jquery.slimscroll.min.js', function () {

            if (jQuery().slimScroll) {

                jQuery('.slimscroll').each(function () {

                    var height;
                    if (jQuery(this).attr("data-height")) {
                        height = jQuery(this).attr("data-height");
                    } else {
                        height = jQuery(this).height();
                    }

                    jQuery(this).slimScroll({
                        size: jQuery(this).attr("data-size") || '5px',
                        opacity: jQuery(this).attr("data-opacity") || .6,
                        position: jQuery(this).attr("data-position") || 'right',
                        allowPageScroll: false, // not working
                        disableFadeOut: false,
                        railVisible: true,
                        railColor: jQuery(this).attr("data-railColor") || '#222',
                        railOpacity: jQuery(this).attr("data-railOpacity") || 0.05,
                        alwaysVisible: (jQuery(this).attr("data-alwaysVisible") != "false" ? true : false),
                        railVisible: (jQuery(this).attr("data-railVisible") != "false" ? true : false),
                        color: jQuery(this).attr("data-color") || '#333',
                        wrapperClass: jQuery(this).attr("data-wrapper-class") || 'slimScrollDiv',
                        railColor: jQuery(this).attr("data-railColor") || '#eaeaea',
                        height: height
                    });

                    // Disable body scroll on slimscroll hover
                    if (jQuery(this).attr('disable-body-scroll') == 'true') {

                        jQuery(this).bind('mousewheel DOMMouseScroll', function (e) {
                            var scrollTo = null;

                            if (e.type == 'mousewheel') {
                                scrollTo = (e.originalEvent.wheelDelta * -1);
                            } else if (e.type == 'DOMMouseScroll') {
                                scrollTo = 40 * e.originalEvent.detail;
                            }

                            if (scrollTo) {
                                e.preventDefault();
                                jQuery(this).scrollTop(scrollTo + jQuery(this).scrollTop());
                            }
                        });
                    }
                });

            }

        });

    }

}




/** Modal Autoload
 
 USAGE:
 
 <div id="MODAL-ID-REQUIRED" class="modal fade" data-autoload="true" data-autoload-delay="2000">
 ...
 </div>
 **************************************************************** **/
function _modalAutoLoad() {
    if (jQuery("div.modal").length > 0) {

        jQuery("div.modal").each(function () {
            var _t = jQuery(this),
                    _id = _t.attr('id'),
                    _autostart = _t.attr('data-autoload') || false;


            // reset allow
            // localStorage.removeItem(_id);


            if (_id != '') { // rewrite if set to hidden by the user
                if (localStorage.getItem(_id) == 'hidden') {
                    _autostart = 'false';
                }
            }


            if (_autostart == 'true') {

                jQuery(window).on("load", function () { // required on load!
                    var _delay = _t.attr('data-autoload-delay') || 1000; // delay when modal apprear

                    setTimeout(
                            function () {

                                _t.modal('toggle');

                            }, parseInt(_delay));

                });

            }

            // LOCAL STORAGE - DO NOT HIDE ON NEXT PAGE LOAD!
            jQuery("input.loadModalHide", this).bind("click", function () {
                var _tt = jQuery(this);

                if (_tt.is(":checked")) {
                    localStorage.setItem(_id, 'hidden');
                    console.log('[Modal Autoload #' + _id + '] Added to localStorage');
                } else {
                    localStorage.removeItem(_id);
                    console.log('[Modal Autoload #' + _id + '] Removed from localStorage');
                }

            });

        });

    }
}





/** 10. Background Image
 class="boxed" should be added to body.
 Add to body - example: data-background="assets/images/boxed_background/1.jpg"
 **************************************************************** **/
function _bgimage() {


    // Section Background Slideshow
    var _t = jQuery('section[data-background], section div[data-background]');

    if (_t.length > 0) {

        loadScript(plugin_path + 'jquery.backstretch.min.js', function () {

            jQuery(_t).each(function () {
                var _this = jQuery(this),
                        data_background = _this.attr('data-background') || '';


                if (data_background != '') {

                    var data_background_delay = _this.attr('data-background-delay') || 3000,
                            data_background_fade = _this.attr('data-background-fade') || 750;
                    var instance;



                    var _db = data_background.split(',');
                    var _db = data_background.replace(" ", "").split(',');

                    if (_db[1]) {
                        _this.backstretch(_db, {duration: parseInt(data_background_delay), fade: parseInt(data_background_fade)});
                    } else {
                        _this.backstretch(_db);
                    }


                    /** NEXT|PREV
                     <!-- Backstretch Navigation -->
                     <a id="bs-next" href="#"></a>
                     <a id="bs-prev" href="#"></a>
                     **/
                    jQuery(".bs-next", _this).bind("click", function (e) {
                        e.preventDefault();

                        _this.data('backstretch').next();
                    });

                    jQuery(".bs-prev", _this).bind("click", function (e) {
                        e.preventDefault();

                        _this.data('backstretch').prev();
                    });


                    /** BACKSTRETCH BUGFIX
                     For some reason, this plugin has some issues on window resize
                     We use a small trick to force it to resize the background
                     **/
                    jQuery(window).resize(function () {

                        if (window.afterResizeBkstr) {
                            clearTimeout(window.afterResizeBkstr);
                        }

                        window.afterResizeBkstr = setTimeout(function () {

                            _this.data('backstretch').next();

                        }, 350);

                    });


                }

            })

        });




    }


    // BODY BACKGROUND ONLY
    var data_background = jQuery('body').attr('data-background') || '';

    if (data_background != '') {

        loadScript(plugin_path + 'jquery.backstretch.min.js', function () {

            if (data_background) {
                jQuery.backstretch(data_background);
                jQuery('body').addClass('transparent'); // remove backround color of boxed class
            }

        });

    }
}





/** Cookie Alert
 **************************************************************** **/
function _cookie_alert() {

    var _el = jQuery('#cookie-alert'),
            _elCookie = _getCookie('cookie-alert');

    if (_el.length > 0 && _elCookie == null) { // only if cookie is not set
        var _expire = _el.attr('data-expire') || 30;

        if (_el.hasClass('alert-position-bottom')) {

            _el.animate({"bottom": "0px"}, 800, 'easeInOutExpo');

        } else {

            _el.animate({"top": "0px"}, 800, 'easeInOutExpo');

        }

        // on close, set cookie as closed
        jQuery('button', _el).bind('click', function () {
            _setCookie('cookie-alert', 'closed', Number(_expire))
        });

    }

    // reset cookie on request
    if (_el.length > 0 && _el.hasClass('cookie-reset')) {
        _delCookie('cookie-alert');
    }

}





/** Flickr Widget
 <div class="widget-flickr clearfix lightbox margin-bottom-60" data-id="37304598@N02" data-limit="16" data-plugin-options='{"delegate": "a", "gallery": {"enabled": true}}'></div>
 **************************************************************** **/
function _widget_flickr() {
    var _container = jQuery('.widget-flickr');

    if (_container.length > 0) {

        loadScript(plugin_path + 'widget.jflickr/jflickrfeed.min.js', function () {

            if (jQuery().jflickrfeed) {
                if (jQuery('.widget-flickr')) {

                    /** **/
                    _container.each(function () {
                        var _t = jQuery(this),
                                _id = _t.attr('data-id'),
                                _limit = _t.attr('data-limit') || 14;

                        _t.jflickrfeed({
                            limit: parseInt(_limit),
                            qstrings: {
                                id: _id
                            },
                            itemTemplate: '<li>' +
                                    '<a href="{{image}}" title="{{title}}">' +
                                    '<img src="{{image_s}}" alt="{{title}}" width="63" height="63" />' +
                                    '</a>' +
                                    '</li>'
                        }, function (data) {
                            _lightbox();
                        });

                    });
                    /** **/

                }
            }

        });

    }

}




/** Twitter Widget
 **************************************************************** **/
function _widget_twitter() {
    var _container = jQuery(".widget-twitter");

    if (_container.length > 0) {

        loadScript(plugin_path + 'widget.twittie/twittie.min.js', function () {

            if (jQuery().twittie) {
                // jQuery('.example1 .tweet').twittie({
                // dateFormat: '%b. %d, %Y',
                // template: '{{tweet}} <div class="date">{{date}}</div>',
                // count: 1,
                // loadingText: 'Loading!'
                // });

                _container.each(function () {
                    var _t = jQuery(this),
                            _php = _t.attr('data-php'), // PHP Script Path
                            _usr = _t.attr('data-username'), // Twitter Username
                            _lmt = _t.attr('data-limit') || 3, // Tweets Limit

                            _url = _php + "?username=" + _usr + "&limit=" + _lmt;

                    jQuery.getJSON(_url, function (tweets) {
                        _t.html(format_twitter(tweets));
                    });

                });

            }

        });

    }

}

function format_twitter(twitt) {
    var statusHTML = [];

    for (var i = 0; i < twitt.length; i++) {
        var username = twitt[i].user.screen_name;

        var status = twitt[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function (url) {
            return '<a href="' + url + '" target="_blank">' + url + '</a>';
        }).replace(/\B@([_a-z0-9]+)/ig, function (reply) {
            return  reply.charAt(0) + '<a href="http://twitter.com/' + reply.substring(1) + '" target="_blank">' + reply.substring(1) + '</a>';
        });

        statusHTML.push('<li><i class="fa fa-twitter"></i><span>' + status + '</span><small><a href="http://twitter.com/' + username + '/statuses/' + twitt[i].id_str + '" target="_blank">' + relative_time(twitt[i].created_at) + '</a></small></li>');
    }

    return statusHTML.join('');
}


function relative_time(time_value) {
    var values = time_value.split(" "),
            parsed_date = Date.parse(time_value),
            relative_to = (arguments.length > 1) ? arguments[1] : new Date(),
            delta = parseInt((relative_to.getTime() - parsed_date) / 1000);

    time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
    delta = delta + (relative_to.getTimezoneOffset() * 60);

    if (delta < 60) {
        return 'less than a minute ago';
    } else if (delta < 120) {
        return 'about a minute ago';
    } else if (delta < (60 * 60)) {
        return (parseInt(delta / 60)).toString() + ' minutes ago';
    } else if (delta < (120 * 60)) {
        return 'about an hour ago';
    } else if (delta < (24 * 60 * 60)) {
        return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
    } else if (delta < (48 * 60 * 60)) {
        return '1 day ago';
    } else {
        return (parseInt(delta / 86400)).toString() + ' days ago';
    }
}




/** Facebook Widget
 <div class="fb-like" data-href="http://www.stepofweb.com" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
 **************************************************************** **/
function _widget_facebook() {

    /** Like & Share Button
     ************************ **/
    var _container_1 = jQuery('div.fb-like');
    var _container_2 = jQuery('div.fb-share-button');

    if (_container_1.length > 0 || _container_2.length > 0) {

        jQuery('body').append('<div id="fb-root"></div>');

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id))
                return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    }

}





/** Dribbble Widget
 **************************************************************** **/
function _widget_dribbble() {
    var _container = jQuery(".widget-dribbble");

    if (_container.length > 0) {

        loadScript(plugin_path + 'widget.dribbble/jribbble.min.js', function () {

            var _token = _container.attr('data-token') || 'f688ac519289f19ce5cebc1383c15ad5c02bd58205cd83c86cbb0ce09170c1b4', // demo default
                    _target = _container.attr('data-target') || '_blank',
                    _shots = _container.attr('data-shots') || 2046896; // demo default

            jQuery.jribbble.setToken(_token);

            jQuery.jribbble.shots(_shots).rebounds().then(function (res) {
                var html = [];

                res.forEach(function (shot) {
                    html.push('<li>');
                    html.push('<a href="' + shot.html_url + '" target="' + _target + '">');
                    html.push('<img class="img-fluid" src="' + shot.images.normal + '" alt="image">');
                    html.push('</a></li>');
                });

                _container.html(html.join(''));
            });


        });

    }

}





/** Media Widget [mediaelement plugin]
 **************************************************************** **/
function _widget_media() {
    var _container = jQuery(".widget-media");

    if (_container.length > 0) {

        loadScript(plugin_path + 'widget.mediaelementbuild/mediaelement-and-player.min.js', function () {



        });

    }

}
function _notification() {
    $.ajax({
        type: "GET",
        url: notification,
        data: {
        },
        success: function (response) {
            if (response.data) {
                $('.count_notif').text(response.data.count);
                if (response.data.count > 0)
                {
                    $('.count_notif').addClass('color_red');
                    $('.fa-bell-o').addClass('color_red')
                } else
                {
                    $('.count_notif').removeClass('color_red');
                    $('.fa-bell-o').removeClass('color_red')
                }
                var output = '';
                var array = response.data.notifications;
                for (var index = 0; index < array.length; index++) {
                    var display = "inline-block";
                    if (array[index]['seen'] == 0) {
                        display = "none";
                    }
                    var template = $('#notification-template').html();
                    output += Mustache.render(template, {
                        id: array[index]['id'],
                        seen: array[index]['seen'],
                        url: array[index]['url'],
                        title: array[index]['title'],
                        'display': display
                    });
                }
                $('.ul_notifications').html('');
                $('.ul_notifications').html(output);
                setTimeout(_notification, 30000);
            }
        },
        error: function (e) {
//            _notification();
        }

    });
}



/** **************************************************************************************************************** **/
/** **************************************************************************************************************** **/
/** **************************************************************************************************************** **/
/** **************************************************************************************************************** **/


/*
 Mobile Check
 
 if( isMobile.any() ) alert('Mobile');
 if( isMobile.iOS() ) alert('iOS');
 */

var isMobile = {
    iOS: function () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Android: function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    Opera: function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function () {
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    any: function () {
        return (isMobile.iOS() || isMobile.Android() || isMobile.BlackBerry() || isMobile.Opera() || isMobile.Windows());
    }
};
// Number Format
Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
// scroll
function wheel(e) {
    e.preventDefault();
}

function disable_scroll() {
    if (window.addEventListener) {
        window.addEventListener('DOMMouseScroll', wheel, false);
    }
    window.onmousewheel = document.onmousewheel = wheel;
}

function enable_scroll() {
    if (window.removeEventListener) {
        window.removeEventListener('DOMMouseScroll', wheel, false);
    }
    window.onmousewheel = document.onmousewheel = document.onkeydown = null;
}

// overlay
function enable_overlay() {
    jQuery("span.global-overlay").remove(); // remove first!
    jQuery('body').append('<span class="global-overlay"></span>');
}
function disable_overlay() {
    jQuery("span.global-overlay").remove();
}







/** Cookies
 **************************************************************** **/
function _setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function _getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0)
            return c.substring(nameEQ.length, c.length);
    }
    return null;
}


function _delCookie(name) {
    _setCookie(name, "", -1);
}



/** COUNT TO
 https://github.com/mhuggins/jquery-countTo
 **************************************************************** **/
(function ($) {
    $.fn.countTo = function (options) {
        options = options || {};

        return jQuery(this).each(function () {
            // set options for current element
            var settings = jQuery.extend({}, $.fn.countTo.defaults, {
                from: jQuery(this).data('from'),
                to: jQuery(this).data('to'),
                speed: jQuery(this).data('speed'),
                refreshInterval: jQuery(this).data('refresh-interval'),
                decimals: jQuery(this).data('decimals')
            }, options);

            // how many times to update the value, and how much to increment the value on each update
            var loops = Math.ceil(settings.speed / settings.refreshInterval),
                    increment = (settings.to - settings.from) / loops;

            // references & variables that will change with each update
            var self = this,
                    $self = jQuery(this),
                    loopCount = 0,
                    value = settings.from,
                    data = $self.data('countTo') || {};

            $self.data('countTo', data);

            // if an existing interval can be found, clear it first
            if (data.interval) {
                clearInterval(data.interval);
            }
            data.interval = setInterval(updateTimer, settings.refreshInterval);

            // __construct the element with the starting value
            render(value);

            function updateTimer() {
                value += increment;
                loopCount++;

                render(value);

                if (typeof (settings.onUpdate) == 'function') {
                    settings.onUpdate.call(self, value);
                }

                if (loopCount >= loops) {
                    // remove the interval
                    $self.removeData('countTo');
                    clearInterval(data.interval);
                    value = settings.to;

                    if (typeof (settings.onComplete) == 'function') {
                        settings.onComplete.call(self, value);
                    }
                }
            }

            function render(value) {
                var formattedValue = settings.formatter.call(self, value, settings);
                $self.html(formattedValue);
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0, // the number the element should start at
        to: 0, // the number the element should end at
        speed: 1000, // how long it should take to count between the target numbers
        refreshInterval: 100, // how often the element should be updated
        decimals: 0, // the number of decimal places to show
        formatter: formatter, // handler for formatting the value before rendering
        onUpdate: null, // callback method for every time the element is updated
        onComplete: null       // callback method for when the element finishes updating
    };

    function formatter(value, settings) {
        return value.toFixed(settings.decimals);
    }
}(jQuery));




/** Appear
 https://github.com/bas2k/jquery.appear/
 **************************************************************** **/
(function (a) {
    a.fn.appear = function (d, b) {
        var c = a.extend({data: undefined, one: true, accX: 0, accY: 0}, b);
        return this.each(function () {
            var g = a(this);
            g.appeared = false;
            if (!d) {
                g.trigger("appear", c.data);
                return
            }
            var f = a(window);
            var e = function () {
                if (!g.is(":visible")) {
                    g.appeared = false;
                    return
                }
                var r = f.scrollLeft();
                var q = f.scrollTop();
                var l = g.offset();
                var s = l.left;
                var p = l.top;
                var i = c.accX;
                var t = c.accY;
                var k = g.height();
                var j = f.height();
                var n = g.width();
                var m = f.width();
                if (p + k + t >= q && p <= q + j + t && s + n + i >= r && s <= r + m + i) {
                    if (!g.appeared) {
                        g.trigger("appear", c.data)
                    }
                } else {
                    g.appeared = false
                }
            };
            var h = function () {
                g.appeared = true;
                if (c.one) {
                    f.unbind("scroll", e);
                    var j = a.inArray(e, a.fn.appear.checks);
                    if (j >= 0) {
                        a.fn.appear.checks.splice(j, 1)
                    }
                }
                d.apply(this, arguments)
            };
            if (c.one) {
                g.one("appear", c.data, h)
            } else {
                g.bind("appear", c.data, h)
            }
            f.scroll(e);
            a.fn.appear.checks.push(e);
            (e)()
        })
    };
    a.extend(a.fn.appear, {checks: [], timeout: null, checkAll: function () {
            var b = a.fn.appear.checks.length;
            if (b > 0) {
                while (b--) {
                    (a.fn.appear.checks[b])()
                }
            }
        }, run: function () {
            if (a.fn.appear.timeout) {
                clearTimeout(a.fn.appear.timeout)
            }
            a.fn.appear.timeout = setTimeout(a.fn.appear.checkAll, 20)
        }});
    a.each(["append", "prepend", "after", "before", "attr", "removeAttr", "addClass", "removeClass", "toggleClass", "remove", "css", "show", "hide"], function (c, d) {
        var b = a.fn[d];
        if (b) {
            a.fn[d] = function () {
                var e = b.apply(this, arguments);
                a.fn.appear.run();
                return e
            }
        }
    })
})(jQuery);

/** Parallax
 http://www.ianlunn.co.uk/plugins/jquery-parallax/
 **************************************************************** **/
!function (n) {
    n.fn.parallax = function (n, t, e) {
        function o() {
            var o = jQuery(window).scrollTop();
            r = e ? function (n) {
                return n.outerHeight(!0)
            } : function (n) {
                return n.height()
            }, i.each(function () {
                var e = jQuery(this), i = e.offset().top, h = r(e);
                if (!(o > i + h || i > o + window.height)) {
                    var l = Math.round((u - o) * t);
                    e.css("backgroundPosition", n + " " + l + "px")
                }
            })
        }
        var r, u, i = jQuery(this);
        (arguments.length < 1 || null === n) && (n = "50%"), (arguments.length < 2 || null === t) && (t = .1), (arguments.length < 3 || null === e) && (e = !0), i.each(function () {
            u = i.offset().top, u < window.height && (u = 0)
        }), jQuery(window).bind("scroll", o).resize(o), o()
    }
}(jQuery);


/** jQuery Easing v1.3
 http://gsgd.co.uk/sandbox/jquery/easing/
 **************************************************************** **/
jQuery.easing.jswing = jQuery.easing.swing;
jQuery.extend(jQuery.easing, {def: "easeOutQuad", swing: function (e, f, a, h, g) {
        return jQuery.easing[jQuery.easing.def](e, f, a, h, g)
    }, easeInQuad: function (e, f, a, h, g) {
        return h * (f /= g) * f + a
    }, easeOutQuad: function (e, f, a, h, g) {
        return -h * (f /= g) * (f - 2) + a
    }, easeInOutQuad: function (e, f, a, h, g) {
        if ((f /= g / 2) < 1) {
            return h / 2 * f * f + a
        }
        return -h / 2 * ((--f) * (f - 2) - 1) + a
    }, easeInCubic: function (e, f, a, h, g) {
        return h * (f /= g) * f * f + a
    }, easeOutCubic: function (e, f, a, h, g) {
        return h * ((f = f / g - 1) * f * f + 1) + a
    }, easeInOutCubic: function (e, f, a, h, g) {
        if ((f /= g / 2) < 1) {
            return h / 2 * f * f * f + a
        }
        return h / 2 * ((f -= 2) * f * f + 2) + a
    }, easeInQuart: function (e, f, a, h, g) {
        return h * (f /= g) * f * f * f + a
    }, easeOutQuart: function (e, f, a, h, g) {
        return -h * ((f = f / g - 1) * f * f * f - 1) + a
    }, easeInOutQuart: function (e, f, a, h, g) {
        if ((f /= g / 2) < 1) {
            return h / 2 * f * f * f * f + a
        }
        return -h / 2 * ((f -= 2) * f * f * f - 2) + a
    }, easeInQuint: function (e, f, a, h, g) {
        return h * (f /= g) * f * f * f * f + a
    }, easeOutQuint: function (e, f, a, h, g) {
        return h * ((f = f / g - 1) * f * f * f * f + 1) + a
    }, easeInOutQuint: function (e, f, a, h, g) {
        if ((f /= g / 2) < 1) {
            return h / 2 * f * f * f * f * f + a
        }
        return h / 2 * ((f -= 2) * f * f * f * f + 2) + a
    }, easeInSine: function (e, f, a, h, g) {
        return -h * Math.cos(f / g * (Math.PI / 2)) + h + a
    }, easeOutSine: function (e, f, a, h, g) {
        return h * Math.sin(f / g * (Math.PI / 2)) + a
    }, easeInOutSine: function (e, f, a, h, g) {
        return -h / 2 * (Math.cos(Math.PI * f / g) - 1) + a
    }, easeInExpo: function (e, f, a, h, g) {
        return(f == 0) ? a : h * Math.pow(2, 10 * (f / g - 1)) + a
    }, easeOutExpo: function (e, f, a, h, g) {
        return(f == g) ? a + h : h * (-Math.pow(2, -10 * f / g) + 1) + a
    }, easeInOutExpo: function (e, f, a, h, g) {
        if (f == 0) {
            return a
        }
        if (f == g) {
            return a + h
        }
        if ((f /= g / 2) < 1) {
            return h / 2 * Math.pow(2, 10 * (f - 1)) + a
        }
        return h / 2 * (-Math.pow(2, -10 * --f) + 2) + a
    }, easeInCirc: function (e, f, a, h, g) {
        return -h * (Math.sqrt(1 - (f /= g) * f) - 1) + a
    }, easeOutCirc: function (e, f, a, h, g) {
        return h * Math.sqrt(1 - (f = f / g - 1) * f) + a
    }, easeInOutCirc: function (e, f, a, h, g) {
        if ((f /= g / 2) < 1) {
            return -h / 2 * (Math.sqrt(1 - f * f) - 1) + a
        }
        return h / 2 * (Math.sqrt(1 - (f -= 2) * f) + 1) + a
    }, easeInElastic: function (f, h, e, l, k) {
        var i = 1.70158;
        var j = 0;
        var g = l;
        if (h == 0) {
            return e
        }
        if ((h /= k) == 1) {
            return e + l
        }
        if (!j) {
            j = k * 0.3
        }
        if (g < Math.abs(l)) {
            g = l;
            var i = j / 4
        } else {
            var i = j / (2 * Math.PI) * Math.asin(l / g)
        }
        return -(g * Math.pow(2, 10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j)) + e
    }, easeOutElastic: function (f, h, e, l, k) {
        var i = 1.70158;
        var j = 0;
        var g = l;
        if (h == 0) {
            return e
        }
        if ((h /= k) == 1) {
            return e + l
        }
        if (!j) {
            j = k * 0.3
        }
        if (g < Math.abs(l)) {
            g = l;
            var i = j / 4
        } else {
            var i = j / (2 * Math.PI) * Math.asin(l / g)
        }
        return g * Math.pow(2, -10 * h) * Math.sin((h * k - i) * (2 * Math.PI) / j) + l + e
    }, easeInOutElastic: function (f, h, e, l, k) {
        var i = 1.70158;
        var j = 0;
        var g = l;
        if (h == 0) {
            return e
        }
        if ((h /= k / 2) == 2) {
            return e + l
        }
        if (!j) {
            j = k * (0.3 * 1.5)
        }
        if (g < Math.abs(l)) {
            g = l;
            var i = j / 4
        } else {
            var i = j / (2 * Math.PI) * Math.asin(l / g)
        }
        if (h < 1) {
            return -0.5 * (g * Math.pow(2, 10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j)) + e
        }
        return g * Math.pow(2, -10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j) * 0.5 + l + e
    }, easeInBack: function (e, f, a, i, h, g) {
        if (g == undefined) {
            g = 1.70158
        }
        return i * (f /= h) * f * ((g + 1) * f - g) + a
    }, easeOutBack: function (e, f, a, i, h, g) {
        if (g == undefined) {
            g = 1.70158
        }
        return i * ((f = f / h - 1) * f * ((g + 1) * f + g) + 1) + a
    }, easeInOutBack: function (e, f, a, i, h, g) {
        if (g == undefined) {
            g = 1.70158
        }
        if ((f /= h / 2) < 1) {
            return i / 2 * (f * f * (((g *= (1.525)) + 1) * f - g)) + a
        }
        return i / 2 * ((f -= 2) * f * (((g *= (1.525)) + 1) * f + g) + 2) + a
    }, easeInBounce: function (e, f, a, h, g) {
        return h - jQuery.easing.easeOutBounce(e, g - f, 0, h, g) + a
    }, easeOutBounce: function (e, f, a, h, g) {
        if ((f /= g) < (1 / 2.75)) {
            return h * (7.5625 * f * f) + a
        } else {
            if (f < (2 / 2.75)) {
                return h * (7.5625 * (f -= (1.5 / 2.75)) * f + 0.75) + a
            } else {
                if (f < (2.5 / 2.75)) {
                    return h * (7.5625 * (f -= (2.25 / 2.75)) * f + 0.9375) + a
                } else {
                    return h * (7.5625 * (f -= (2.625 / 2.75)) * f + 0.984375) + a
                }
            }
        }
    }, easeInOutBounce: function (e, f, a, h, g) {
        if (f < g / 2) {
            return jQuery.easing.easeInBounce(e, f * 2, 0, h, g) * 0.5 + a
        }
        return jQuery.easing.easeOutBounce(e, f * 2 - g, 0, h, g) * 0.5 + h * 0.5 + a
    }});

/** WOW - v1.0.3 - 2015-01-14
 http://mynameismatthieu.com/WOW/
 **************************************************************** **/
(function () {
    var a, b, c, d, e, f = function (a, b) {
        return function () {
            return a.apply(b, arguments)
        }
    }, g = [].indexOf || function (a) {
        for (var b = 0, c = this.length; c > b; b++)
            if (b in this && this[b] === a)
                return b;
        return-1
    };
    b = function () {
        function a() {}
        return a.prototype.extend = function (a, b) {
            var c, d;
            for (c in b)
                d = b[c], null == a[c] && (a[c] = d);
            return a
        }, a.prototype.isMobile = function (a) {
            return/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(a)
        }, a.prototype.addEvent = function (a, b, c) {
            return null != a.addEventListener ? a.addEventListener(b, c, !1) : null != a.attachEvent ? a.attachEvent("on" + b, c) : a[b] = c
        }, a.prototype.removeEvent = function (a, b, c) {
            return null != a.removeEventListener ? a.removeEventListener(b, c, !1) : null != a.detachEvent ? a.detachEvent("on" + b, c) : delete a[b]
        }, a.prototype.innerHeight = function () {
            return"innerHeight"in window ? window.innerHeight : document.documentElement.clientHeight
        }, a
    }(), c = this.WeakMap || this.MozWeakMap || (c = function () {
        function a() {
            this.keys = [], this.values = []
        }
        return a.prototype.get = function (a) {
            var b, c, d, e, f;
            for (f = this.keys, b = d = 0, e = f.length; e > d; b = ++d)
                if (c = f[b], c === a)
                    return this.values[b]
        }, a.prototype.set = function (a, b) {
            var c, d, e, f, g;
            for (g = this.keys, c = e = 0, f = g.length; f > e; c = ++e)
                if (d = g[c], d === a)
                    return void(this.values[c] = b);
            return this.keys.push(a), this.values.push(b)
        }, a
    }()), a = this.MutationObserver || this.WebkitMutationObserver || this.MozMutationObserver || (a = function () {
        function a() {
            "undefined" != typeof console && null !== console && console.warn("MutationObserver is not supported by your browser."), "undefined" != typeof console && null !== console && console.warn("WOW.js cannot detect dom mutations, please call .sync() after loading new content.")
        }
        return a.notSupported = !0, a.prototype.observe = function () {}, a
    }()), d = this.getComputedStyle || function (a) {
        return this.getPropertyValue = function (b) {
            var c;
            return"float" === b && (b = "styleFloat"), e.test(b) && b.replace(e, function (a, b) {
                return b.toUpperCase()
            }), (null != (c = a.currentStyle) ? c[b] : void 0) || null
        }, this
    }, e = /(\-([a-z]){1})/g, this.WOW = function () {
        function e(a) {
            null == a && (a = {}), this.scrollCallback = f(this.scrollCallback, this), this.scrollHandler = f(this.scrollHandler, this), this.start = f(this.start, this), this.scrolled = !0, this.config = this.util().extend(a, this.defaults), this.animationNameCache = new c
        }
        return e.prototype.defaults = {boxClass: "wow", animateClass: "animated", offset: 0, mobile: !0, live: !0, callback: null}, e.prototype.init = function () {
            var a;
            return this.element = window.document.documentElement, "interactive" === (a = document.readyState) || "complete" === a ? this.start() : this.util().addEvent(document, "DOMContentLoaded", this.start), this.finished = []
        }, e.prototype.start = function () {
            var b, c, d, e;
            if (this.stopped = !1, this.boxes = function () {
                var a, c, d, e;
                for (d = this.element.querySelectorAll("." + this.config.boxClass), e = [], a = 0, c = d.length; c > a; a++)
                    b = d[a], e.push(b);
                return e
            }.call(this), this.all = function () {
                var a, c, d, e;
                for (d = this.boxes, e = [], a = 0, c = d.length; c > a; a++)
                    b = d[a], e.push(b);
                return e
            }.call(this), this.boxes.length)
                if (this.disabled())
                    this.resetStyle();
                else
                    for (e = this.boxes, c = 0, d = e.length; d > c; c++)
                        b = e[c], this.applyStyle(b, !0);
            return this.disabled() || (this.util().addEvent(window, "scroll", this.scrollHandler), this.util().addEvent(window, "resize", this.scrollHandler), this.interval = setInterval(this.scrollCallback, 50)), this.config.live ? new a(function (a) {
                return function (b) {
                    var c, d, e, f, g;
                    for (g = [], e = 0, f = b.length; f > e; e++)
                        d = b[e], g.push(function () {
                            var a, b, e, f;
                            for (e = d.addedNodes || [], f = [], a = 0, b = e.length; b > a; a++)
                                c = e[a], f.push(this.doSync(c));
                            return f
                        }.call(a));
                    return g
                }
            }(this)).observe(document.body, {childList: !0, subtree: !0}) : void 0
        }, e.prototype.stop = function () {
            return this.stopped = !0, this.util().removeEvent(window, "scroll", this.scrollHandler), this.util().removeEvent(window, "resize", this.scrollHandler), null != this.interval ? clearInterval(this.interval) : void 0
        }, e.prototype.sync = function () {
            return a.notSupported ? this.doSync(this.element) : void 0
        }, e.prototype.doSync = function (a) {
            var b, c, d, e, f;
            if (null == a && (a = this.element), 1 === a.nodeType) {
                for (a = a.parentNode || a, e = a.querySelectorAll("." + this.config.boxClass), f = [], c = 0, d = e.length; d > c; c++)
                    b = e[c], g.call(this.all, b) < 0 ? (this.boxes.push(b), this.all.push(b), this.stopped || this.disabled() ? this.resetStyle() : this.applyStyle(b, !0), f.push(this.scrolled = !0)) : f.push(void 0);
                return f
            }
        }, e.prototype.show = function (a) {
            return this.applyStyle(a), a.className = "" + a.className + " " + this.config.animateClass, null != this.config.callback ? this.config.callback(a) : void 0
        }, e.prototype.applyStyle = function (a, b) {
            var c, d, e;
            return d = a.getAttribute("data-wow-duration"), c = a.getAttribute("data-wow-delay"), e = a.getAttribute("data-wow-iteration"), this.animate(function (f) {
                return function () {
                    return f.customStyle(a, b, d, c, e)
                }
            }(this))
        }, e.prototype.animate = function () {
            return"requestAnimationFrame"in window ? function (a) {
                return window.requestAnimationFrame(a)
            } : function (a) {
                return a()
            }
        }(), e.prototype.resetStyle = function () {
            var a, b, c, d, e;
            for (d = this.boxes, e = [], b = 0, c = d.length; c > b; b++)
                a = d[b], e.push(a.style.visibility = "visible");
            return e
        }, e.prototype.customStyle = function (a, b, c, d, e) {
            return b && this.cacheAnimationName(a), a.style.visibility = b ? "hidden" : "visible", c && this.vendorSet(a.style, {animationDuration: c}), d && this.vendorSet(a.style, {animationDelay: d}), e && this.vendorSet(a.style, {animationIterationCount: e}), this.vendorSet(a.style, {animationName: b ? "none" : this.cachedAnimationName(a)}), a
        }, e.prototype.vendors = ["moz", "webkit"], e.prototype.vendorSet = function (a, b) {
            var c, d, e, f;
            f = [];
            for (c in b)
                d = b[c], a["" + c] = d, f.push(function () {
                    var b, f, g, h;
                    for (g = this.vendors, h = [], b = 0, f = g.length; f > b; b++)
                        e = g[b], h.push(a["" + e + c.charAt(0).toUpperCase() + c.substr(1)] = d);
                    return h
                }.call(this));
            return f
        }, e.prototype.vendorCSS = function (a, b) {
            var c, e, f, g, h, i;
            for (e = d(a), c = e.getPropertyCSSValue(b), i = this.vendors, g = 0, h = i.length; h > g; g++)
                f = i[g], c = c || e.getPropertyCSSValue("-" + f + "-" + b);
            return c
        }, e.prototype.animationName = function (a) {
            var b;
            try {
                b = this.vendorCSS(a, "animation-name").cssText
            } catch (c) {
                b = d(a).getPropertyValue("animation-name")
            }
            return"none" === b ? "" : b
        }, e.prototype.cacheAnimationName = function (a) {
            return this.animationNameCache.set(a, this.animationName(a))
        }, e.prototype.cachedAnimationName = function (a) {
            return this.animationNameCache.get(a)
        }, e.prototype.scrollHandler = function () {
            return this.scrolled = !0
        }, e.prototype.scrollCallback = function () {
            var a;
            return!this.scrolled || (this.scrolled = !1, this.boxes = function () {
                var b, c, d, e;
                for (d = this.boxes, e = [], b = 0, c = d.length; c > b; b++)
                    a = d[b], a && (this.isVisible(a) ? this.show(a) : e.push(a));
                return e
            }.call(this), this.boxes.length || this.config.live) ? void 0 : this.stop()
        }, e.prototype.offsetTop = function (a) {
            for (var b; void 0 === a.offsetTop; )
                a = a.parentNode;
            for (b = a.offsetTop; a = a.offsetParent; )
                b += a.offsetTop;
            return b
        }, e.prototype.isVisible = function (a) {
            var b, c, d, e, f;
            return c = a.getAttribute("data-wow-offset") || this.config.offset, f = window.pageYOffset, e = f + Math.min(this.element.clientHeight, this.util().innerHeight()) - c, d = this.offsetTop(a), b = d + a.clientHeight, e >= d && b >= f
        }, e.prototype.util = function () {
            return null != this._util ? this._util : this._util = new b
        }, e.prototype.disabled = function () {
            return!this.config.mobile && this.util().isMobile(navigator.userAgent)
        }, e
    }()
}).call(this);

/** Modernizr 3.3.1
 http://modernizr.com/download/#-csstransforms3d-csstransitions-video-touch-shiv-cssclasses-addtest-prefixed-teststyles-testprop-testallprops-hasevent-prefixes-domprefixes-load
 **************************************************************** **/
!function (e, t, n) {
    function r(e, t) {
        return typeof e === t
    }
    function o() {
        var e, t, n, o, i, a, s;
        for (var l in b)
            if (b.hasOwnProperty(l)) {
                if (e = [], t = b[l], t.name && (e.push(t.name.toLowerCase()), t.options && t.options.aliases && t.options.aliases.length))
                    for (n = 0; n < t.options.aliases.length; n++)
                        e.push(t.options.aliases[n].toLowerCase());
                for (o = r(t.fn, "function")?t.fn():t.fn, i = 0; i < e.length; i++)
                    a = e[i], s = a.split("."), 1 === s.length ? Modernizr[s[0]] = o : (!Modernizr[s[0]] || Modernizr[s[0]]instanceof Boolean || (Modernizr[s[0]] = new Boolean(Modernizr[s[0]])), Modernizr[s[0]][s[1]] = o), C.push((o ? "" : "no-") + s.join("-"))
            }
    }
    function i(e) {
        var t = w.className, n = Modernizr._config.classPrefix || "";
        if (S && (t = t.baseVal), Modernizr._config.enableJSClass) {
            var r = new RegExp("(^|\\s)" + n + "no-js(\\s|$)");
            t = t.replace(r, "$1" + n + "js$2")
        }
        Modernizr._config.enableClasses && (t += " " + n + e.join(" " + n), S ? w.className.baseVal = t : w.className = t)
    }
    function a() {
        return"function" != typeof t.createElement ? t.createElement(arguments[0]) : S ? t.createElementNS.call(t, "http://www.w3.org/2000/svg", arguments[0]) : t.createElement.apply(t, arguments)
    }
    function s(e, t) {
        if ("object" == typeof e)
            for (var n in e)
                N(e, n) && s(n, e[n]);
        else {
            e = e.toLowerCase();
            var r = e.split("."), o = Modernizr[r[0]];
            if (2 == r.length && (o = o[r[1]]), "undefined" != typeof o)
                return Modernizr;
            t = "function" == typeof t ? t() : t, 1 == r.length ? Modernizr[r[0]] = t : (!Modernizr[r[0]] || Modernizr[r[0]]instanceof Boolean || (Modernizr[r[0]] = new Boolean(Modernizr[r[0]])), Modernizr[r[0]][r[1]] = t), i([(t && 0 != t ? "" : "no-") + r.join("-")]), Modernizr._trigger(e, t)
        }
        return Modernizr
    }
    function l(e) {
        return e.replace(/([a-z])-([a-z])/g, function (e, t, n) {
            return t + n.toUpperCase()
        }).replace(/^-/, "")
    }
    function c(e, t) {
        return!!~("" + e).indexOf(t)
    }
    function u() {
        var e = t.body;
        return e || (e = a(S ? "svg" : "body"), e.fake = !0), e
    }
    function f(e, n, r, o) {
        var i, s, l, c, f = "modernizr", d = a("div"), p = u();
        if (parseInt(r, 10))
            for (; r--; )
                l = a("div"), l.id = o ? o[r] : f + (r + 1), d.appendChild(l);
        return i = a("style"), i.type = "text/css", i.id = "s" + f, (p.fake ? p : d).appendChild(i), p.appendChild(d), i.styleSheet ? i.styleSheet.cssText = e : i.appendChild(t.createTextNode(e)), d.id = f, p.fake && (p.style.background = "", p.style.overflow = "hidden", c = w.style.overflow, w.style.overflow = "hidden", w.appendChild(p)), s = n(d, e), p.fake ? (p.parentNode.removeChild(p), w.style.overflow = c, w.offsetHeight) : d.parentNode.removeChild(d), !!s
    }
    function d(e, t) {
        return function () {
            return e.apply(t, arguments)
        }
    }
    function p(e, t, n) {
        var o;
        for (var i in e)
            if (e[i]in t)
                return n === !1 ? e[i] : (o = t[e[i]], r(o, "function") ? d(o, n || t) : o);
        return!1
    }
    function m(e) {
        return e.replace(/([A-Z])/g, function (e, t) {
            return"-" + t.toLowerCase()
        }).replace(/^ms-/, "-ms-")
    }
    function h(t, r) {
        var o = t.length;
        if ("CSS"in e && "supports"in e.CSS) {
            for (; o--; )
                if (e.CSS.supports(m(t[o]), r))
                    return!0;
            return!1
        }
        if ("CSSSupportsRule"in e) {
            for (var i = []; o--; )
                i.push("(" + m(t[o]) + ":" + r + ")");
            return i = i.join(" or "), f("@supports (" + i + ") { #modernizr { position: absolute; } }", function (e) {
                return"absolute" == getComputedStyle(e, null).position
            })
        }
        return n
    }
    function v(e, t, o, i) {
        function s() {
            f && (delete A.style, delete A.modElem)
        }
        if (i = r(i, "undefined") ? !1 : i, !r(o, "undefined")) {
            var u = h(e, o);
            if (!r(u, "undefined"))
                return u
        }
        for (var f, d, p, m, v, g = ["modernizr", "tspan", "samp"]; !A.style && g.length; )
            f = !0, A.modElem = a(g.shift()), A.style = A.modElem.style;
        for (p = e.length, d = 0; p > d; d++)
            if (m = e[d], v = A.style[m], c(m, "-") && (m = l(m)), A.style[m] !== n) {
                if (i || r(o, "undefined"))
                    return s(), "pfx" == t ? m : !0;
                try {
                    A.style[m] = o
                } catch (y) {
                }
                if (A.style[m] != v)
                    return s(), "pfx" == t ? m : !0
            }
        return s(), !1
    }
    function g(e, t, n, o, i) {
        var a = e.charAt(0).toUpperCase() + e.slice(1), s = (e + " " + z.join(a + " ") + a).split(" ");
        return r(t, "string") || r(t, "undefined") ? v(s, t, o, i) : (s = (e + " " + T.join(a + " ") + a).split(" "), p(s, t, n))
    }
    function y(e, t, r) {
        return g(e, n, n, t, r)
    }
    var C = [], b = [], E = {_version: "3.3.1", _config: {classPrefix: "", enableClasses: !0, enableJSClass: !0, usePrefixes: !0}, _q: [], on: function (e, t) {
            var n = this;
            setTimeout(function () {
                t(n[e])
            }, 0)
        }, addTest: function (e, t, n) {
            b.push({name: e, fn: t, options: n})
        }, addAsyncTest: function (e) {
            b.push({name: null, fn: e})
        }}, Modernizr = function () {};
    Modernizr.prototype = E, Modernizr = new Modernizr;
    var _ = E._config.usePrefixes ? " -webkit- -moz- -o- -ms- ".split(" ") : ["", ""];
    E._prefixes = _;
    var w = t.documentElement, S = "svg" === w.nodeName.toLowerCase();
    S || !function (e, t) {
        function n(e, t) {
            var n = e.createElement("p"), r = e.getElementsByTagName("head")[0] || e.documentElement;
            return n.innerHTML = "x<style>" + t + "</style>", r.insertBefore(n.lastChild, r.firstChild)
        }
        function r() {
            var e = C.elements;
            return"string" == typeof e ? e.split(" ") : e
        }
        function o(e, t) {
            var n = C.elements;
            "string" != typeof n && (n = n.join(" ")), "string" != typeof e && (e = e.join(" ")), C.elements = n + " " + e, c(t)
        }
        function i(e) {
            var t = y[e[v]];
            return t || (t = {}, g++, e[v] = g, y[g] = t), t
        }
        function a(e, n, r) {
            if (n || (n = t), f)
                return n.createElement(e);
            r || (r = i(n));
            var o;
            return o = r.cache[e] ? r.cache[e].cloneNode() : h.test(e) ? (r.cache[e] = r.createElem(e)).cloneNode() : r.createElem(e), !o.canHaveChildren || m.test(e) || o.tagUrn ? o : r.frag.appendChild(o)
        }
        function s(e, n) {
            if (e || (e = t), f)
                return e.createDocumentFragment();
            n = n || i(e);
            for (var o = n.frag.cloneNode(), a = 0, s = r(), l = s.length; l > a; a++)
                o.createElement(s[a]);
            return o
        }
        function l(e, t) {
            t.cache || (t.cache = {}, t.createElem = e.createElement, t.createFrag = e.createDocumentFragment, t.frag = t.createFrag()), e.createElement = function (n) {
                return C.shivMethods ? a(n, e, t) : t.createElem(n)
            }, e.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + r().join().replace(/[\w\-:]+/g, function (e) {
                return t.createElem(e), t.frag.createElement(e), 'c("' + e + '")'
            }) + ");return n}")(C, t.frag)
        }
        function c(e) {
            e || (e = t);
            var r = i(e);
            return!C.shivCSS || u || r.hasCSS || (r.hasCSS = !!n(e, "article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")), f || l(e, r), e
        }
        var u, f, d = "3.7.3", p = e.html5 || {}, m = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i, h = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i, v = "_html5shiv", g = 0, y = {};
        !function () {
            try {
                var e = t.createElement("a");
                e.innerHTML = "<xyz></xyz>", u = "hidden"in e, f = 1 == e.childNodes.length || function () {
                    t.createElement("a");
                    var e = t.createDocumentFragment();
                    return"undefined" == typeof e.cloneNode || "undefined" == typeof e.createDocumentFragment || "undefined" == typeof e.createElement
                }()
            } catch (n) {
                u = !0, f = !0
            }
        }();
        var C = {elements: p.elements || "abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video", version: d, shivCSS: p.shivCSS !== !1, supportsUnknownElements: f, shivMethods: p.shivMethods !== !1, type: "default", shivDocument: c, createElement: a, createDocumentFragment: s, addElements: o};
        e.html5 = C, c(t), "object" == typeof module && module.exports && (module.exports = C)
    }("undefined" != typeof e ? e : this, t);
    var x = "Moz O ms Webkit", T = E._config.usePrefixes ? x.toLowerCase().split(" ") : [];
    E._domPrefixes = T;
    var P = function () {
        function e(e, t) {
            var o;
            return e ? (t && "string" != typeof t || (t = a(t || "div")), e = "on" + e, o = e in t, !o && r && (t.setAttribute || (t = a("div")), t.setAttribute(e, ""), o = "function" == typeof t[e], t[e] !== n && (t[e] = n), t.removeAttribute(e)), o) : !1
        }
        var r = !("onblur"in t.documentElement);
        return e
    }();
    E.hasEvent = P, Modernizr.addTest("video", function () {
        var e = a("video"), t = !1;
        try {
            (t = !!e.canPlayType) && (t = new Boolean(t), t.ogg = e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/, ""), t.h264 = e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/, ""), t.webm = e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/, ""), t.vp9 = e.canPlayType('video/webm; codecs="vp9"').replace(/^no$/, ""), t.hls = e.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/, ""))
        } catch (n) {
        }
        return t
    });
    var N;
    !function () {
        var e = {}.hasOwnProperty;
        N = r(e, "undefined") || r(e.call, "undefined") ? function (e, t) {
            return t in e && r(e.constructor.prototype[t], "undefined")
        } : function (t, n) {
            return e.call(t, n)
        }
    }(), E._l = {}, E.on = function (e, t) {
        this._l[e] || (this._l[e] = []), this._l[e].push(t), Modernizr.hasOwnProperty(e) && setTimeout(function () {
            Modernizr._trigger(e, Modernizr[e])
        }, 0)
    }, E._trigger = function (e, t) {
        if (this._l[e]) {
            var n = this._l[e];
            setTimeout(function () {
                var e, r;
                for (e = 0; e < n.length; e++)
                    (r = n[e])(t)
            }, 0), delete this._l[e]
        }
    }, Modernizr._q.push(function () {
        E.addTest = s
    });
    var j = "CSS"in e && "supports"in e.CSS, k = "supportsCSS"in e;
    Modernizr.addTest("supports", j || k);
    var z = E._config.usePrefixes ? x.split(" ") : [];
    E._cssomPrefixes = z;
    var F = function (t) {
        var r, o = _.length, i = e.CSSRule;
        if ("undefined" == typeof i)
            return n;
        if (!t)
            return!1;
        if (t = t.replace(/^@/, ""), r = t.replace(/-/g, "_").toUpperCase() + "_RULE", r in i)
            return"@" + t;
        for (var a = 0; o > a; a++) {
            var s = _[a], l = s.toUpperCase() + "_" + r;
            if (l in i)
                return"@-" + s.toLowerCase() + "-" + t
        }
        return!1
    };
    E.atRule = F;
    var L = E.testStyles = f, $ = {elem: a("modernizr")};
    Modernizr._q.push(function () {
        delete $.elem
    });
    var A = {style: $.elem.style};
    Modernizr._q.unshift(function () {
        delete A.style
    });
    E.testProp = function (e, t, r) {
        return v([e], n, t, r)
    };
    E.testAllProps = g;
    E.prefixed = function (e, t, n) {
        return 0 === e.indexOf("@") ? F(e) : (-1 != e.indexOf("-") && (e = l(e)), t ? g(e, t, n) : g(e, "pfx"))
    };
    E.testAllProps = y, Modernizr.addTest("csstransitions", y("transition", "all", !0)), Modernizr.addTest("csstransforms3d", function () {
        var e = !!y("perspective", "1px", !0), t = Modernizr._config.usePrefixes;
        if (e && (!t || "webkitPerspective"in w.style)) {
            var n, r = "#modernizr{width:0;height:0}";
            Modernizr.supports ? n = "@supports (perspective: 1px)" : (n = "@media (transform-3d)", t && (n += ",(-webkit-transform-3d)")), n += "{#modernizr{width:7px;height:18px;margin:0;padding:0;border:0}}", L(r + n, function (t) {
                e = 7 === t.offsetWidth && 18 === t.offsetHeight
            })
        }
        return e
    }), o(), i(C), delete E.addTest, delete E.addAsyncTest;
    for (var M = 0; M < Modernizr._q.length; M++)
        Modernizr._q[M]();
    e.Modernizr = Modernizr
}(window, document);

/** TETHER - used by bootstrap
 http://tether.io/
 **************************************************************** **/
function _loadTetherBS4() {
    !function (t, e) {
        "function" == typeof define && define.amd ? define(e) : "object" == typeof exports ? module.exports = e(require, exports, module) : t.Tether = e()
    }(this, function (t, e, o) {
        "use strict";
        function n(t, e) {
            if (!(t instanceof e))
                throw new TypeError("Cannot call a class as a function")
        }
        function i(t) {
            var e = t.getBoundingClientRect(), o = {};
            for (var n in e)
                o[n] = e[n];
            if (t.ownerDocument !== document) {
                var r = t.ownerDocument.defaultView.frameElement;
                if (r) {
                    var s = i(r);
                    o.top += s.top, o.bottom += s.top, o.left += s.left, o.right += s.left
                }
            }
            return o
        }
        function r(t) {
            var e = getComputedStyle(t) || {}, o = e.position, n = [];
            if ("fixed" === o)
                return[t];
            for (var i = t; (i = i.parentNode) && i && 1 === i.nodeType; ) {
                var r = void 0;
                try {
                    r = getComputedStyle(i)
                } catch (s) {
                }
                if ("undefined" == typeof r || null === r)
                    return n.push(i), n;
                var a = r, f = a.overflow, l = a.overflowX, h = a.overflowY;
                /(auto|scroll)/.test(f + h + l) && ("absolute" !== o || ["relative", "absolute", "fixed"].indexOf(r.position) >= 0) && n.push(i)
            }
            return n.push(t.ownerDocument.body), t.ownerDocument !== document && n.push(t.ownerDocument.defaultView), n
        }
        function s() {
            A && document.body.removeChild(A), A = null
        }
        function a(t) {
            var e = void 0;
            t === document ? (e = document, t = document.documentElement) : e = t.ownerDocument;
            var o = e.documentElement, n = i(t), r = P();
            return n.top -= r.top, n.left -= r.left, "undefined" == typeof n.width && (n.width = document.body.scrollWidth - n.left - n.right), "undefined" == typeof n.height && (n.height = document.body.scrollHeight - n.top - n.bottom), n.top = n.top - o.clientTop, n.left = n.left - o.clientLeft, n.right = e.body.clientWidth - n.width - n.left, n.bottom = e.body.clientHeight - n.height - n.top, n
        }
        function f(t) {
            return t.offsetParent || document.documentElement
        }
        function l() {
            var t = document.createElement("div");
            t.style.width = "100%", t.style.height = "200px";
            var e = document.createElement("div");
            h(e.style, {position: "absolute", top: 0, left: 0, pointerEvents: "none", visibility: "hidden", width: "200px", height: "150px", overflow: "hidden"}), e.appendChild(t), document.body.appendChild(e);
            var o = t.offsetWidth;
            e.style.overflow = "scroll";
            var n = t.offsetWidth;
            o === n && (n = e.clientWidth), document.body.removeChild(e);
            var i = o - n;
            return{width: i, height: i}
        }
        function h() {
            var t = arguments.length <= 0 || void 0 === arguments[0] ? {} : arguments[0], e = [];
            return Array.prototype.push.apply(e, arguments), e.slice(1).forEach(function (e) {
                if (e)
                    for (var o in e)
                        ({}).hasOwnProperty.call(e, o) && (t[o] = e[o])
            }), t
        }
        function u(t, e) {
            if ("undefined" != typeof t.classList)
                e.split(" ").forEach(function (e) {
                    e.trim() && t.classList.remove(e)
                });
            else {
                var o = new RegExp("(^| )" + e.split(" ").join("|") + "( |$)", "gi"), n = c(t).replace(o, " ");
                g(t, n)
            }
        }
        function d(t, e) {
            if ("undefined" != typeof t.classList)
                e.split(" ").forEach(function (e) {
                    e.trim() && t.classList.add(e)
                });
            else {
                u(t, e);
                var o = c(t) + (" " + e);
                g(t, o)
            }
        }
        function p(t, e) {
            if ("undefined" != typeof t.classList)
                return t.classList.contains(e);
            var o = c(t);
            return new RegExp("(^| )" + e + "( |$)", "gi").test(o)
        }
        function c(t) {
            return t.className instanceof t.ownerDocument.defaultView.SVGAnimatedString ? t.className.baseVal : t.className
        }
        function g(t, e) {
            t.setAttribute("class", e)
        }
        function m(t, e, o) {
            o.forEach(function (o) {
                -1 === e.indexOf(o) && p(t, o) && u(t, o)
            }), e.forEach(function (e) {
                p(t, e) || d(t, e)
            })
        }
        function n(t, e) {
            if (!(t instanceof e))
                throw new TypeError("Cannot call a class as a function")
        }
        function v(t, e) {
            if ("function" != typeof e && null !== e)
                throw new TypeError("Super expression must either be null or a function, not " + typeof e);
            t.prototype = Object.create(e && e.prototype, {constructor: {value: t, enumerable: !1, writable: !0, configurable: !0}}), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : t.__proto__ = e)
        }
        function y(t, e) {
            var o = arguments.length <= 2 || void 0 === arguments[2] ? 1 : arguments[2];
            return t + o >= e && e >= t - o
        }
        function b() {
            return"undefined" != typeof performance && "undefined" != typeof performance.now ? performance.now() : +new Date
        }
        function w() {
            for (var t = {top: 0, left: 0}, e = arguments.length, o = Array(e), n = 0; e > n; n++)
                o[n] = arguments[n];
            return o.forEach(function (e) {
                var o = e.top, n = e.left;
                "string" == typeof o && (o = parseFloat(o, 10)), "string" == typeof n && (n = parseFloat(n, 10)), t.top += o, t.left += n
            }), t
        }
        function C(t, e) {
            return"string" == typeof t.left && -1 !== t.left.indexOf("%") && (t.left = parseFloat(t.left, 10) / 100 * e.width), "string" == typeof t.top && -1 !== t.top.indexOf("%") && (t.top = parseFloat(t.top, 10) / 100 * e.height), t
        }
        function O(t, e) {
            return"scrollParent" === e ? e = t.scrollParents[0] : "window" === e && (e = [pageXOffset, pageYOffset, innerWidth + pageXOffset, innerHeight + pageYOffset]), e === document && (e = e.documentElement), "undefined" != typeof e.nodeType && !function () {
                var t = e, o = a(e), n = o, i = getComputedStyle(e);
                if (e = [n.left, n.top, o.width + n.left, o.height + n.top], t.ownerDocument !== document) {
                    var r = t.ownerDocument.defaultView;
                    e[0] += r.pageXOffset, e[1] += r.pageYOffset, e[2] += r.pageXOffset, e[3] += r.pageYOffset
                }
                $.forEach(function (t, o) {
                    t = t[0].toUpperCase() + t.substr(1), "Top" === t || "Left" === t ? e[o] += parseFloat(i["border" + t + "Width"]) : e[o] -= parseFloat(i["border" + t + "Width"])
                })
            }(), e
        }
        var E = function () {
            function t(t, e) {
                for (var o = 0; o < e.length; o++) {
                    var n = e[o];
                    n.enumerable = n.enumerable || !1, n.configurable = !0, "value"in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
                }
            }
            return function (e, o, n) {
                return o && t(e.prototype, o), n && t(e, n), e
            }
        }(), x = void 0;
        "undefined" == typeof x && (x = {modules: []});
        var A = null, T = function () {
            var t = 0;
            return function () {
                return++t
            }
        }(), S = {}, P = function () {
            var t = A;
            t || (t = document.createElement("div"), t.setAttribute("data-tether-id", T()), h(t.style, {top: 0, left: 0, position: "absolute"}), document.body.appendChild(t), A = t);
            var e = t.getAttribute("data-tether-id");
            return"undefined" == typeof S[e] && (S[e] = i(t), M(function () {
                delete S[e]
            })), S[e]
        }, W = [], M = function (t) {
            W.push(t)
        }, _ = function () {
            for (var t = void 0; t = W.pop(); )
                t()
        }, k = function () {
            function t() {
                n(this, t)
            }
            return E(t, [{key: "on", value: function (t, e, o) {
                        var n = arguments.length <= 3 || void 0 === arguments[3] ? !1 : arguments[3];
                        "undefined" == typeof this.bindings && (this.bindings = {}), "undefined" == typeof this.bindings[t] && (this.bindings[t] = []), this.bindings[t].push({handler: e, ctx: o, once: n})
                    }}, {key: "once", value: function (t, e, o) {
                        this.on(t, e, o, !0)
                    }}, {key: "off", value: function (t, e) {
                        if ("undefined" != typeof this.bindings && "undefined" != typeof this.bindings[t])
                            if ("undefined" == typeof e)
                                delete this.bindings[t];
                            else
                                for (var o = 0; o < this.bindings[t].length; )
                                    this.bindings[t][o].handler === e ? this.bindings[t].splice(o, 1) : ++o
                    }}, {key: "trigger", value: function (t) {
                        if ("undefined" != typeof this.bindings && this.bindings[t]) {
                            for (var e = 0, o = arguments.length, n = Array(o > 1 ? o - 1 : 0), i = 1; o > i; i++)
                                n[i - 1] = arguments[i];
                            for (; e < this.bindings[t].length; ) {
                                var r = this.bindings[t][e], s = r.handler, a = r.ctx, f = r.once, l = a;
                                "undefined" == typeof l && (l = this), s.apply(l, n), f ? this.bindings[t].splice(e, 1) : ++e
                            }
                        }
                    }}]), t
        }();
        x.Utils = {getActualBoundingClientRect: i, getScrollParents: r, getBounds: a, getOffsetParent: f, extend: h, addClass: d, removeClass: u, hasClass: p, updateClasses: m, defer: M, flush: _, uniqueId: T, Evented: k, getScrollBarSize: l, removeUtilElements: s};
        var B = function () {
            function t(t, e) {
                var o = [], n = !0, i = !1, r = void 0;
                try {
                    for (var s, a = t[Symbol.iterator](); !(n = (s = a.next()).done) && (o.push(s.value), !e || o.length !== e); n = !0)
                        ;
                } catch (f) {
                    i = !0, r = f
                } finally {
                    try {
                        !n && a["return"] && a["return"]()
                    } finally {
                        if (i)
                            throw r
                    }
                }
                return o
            }
            return function (e, o) {
                if (Array.isArray(e))
                    return e;
                if (Symbol.iterator in Object(e))
                    return t(e, o);
                throw new TypeError("Invalid attempt to destructure non-iterable instance")
            }
        }(), E = function () {
            function t(t, e) {
                for (var o = 0; o < e.length; o++) {
                    var n = e[o];
                    n.enumerable = n.enumerable || !1, n.configurable = !0, "value"in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
                }
            }
            return function (e, o, n) {
                return o && t(e.prototype, o), n && t(e, n), e
            }
        }(), z = function (t, e, o) {
            for (var n = !0; n; ) {
                var i = t, r = e, s = o;
                n = !1, null === i && (i = Function.prototype);
                var a = Object.getOwnPropertyDescriptor(i, r);
                if (void 0 !== a) {
                    if ("value"in a)
                        return a.value;
                    var f = a.get;
                    if (void 0 === f)
                        return;
                    return f.call(s)
                }
                var l = Object.getPrototypeOf(i);
                if (null === l)
                    return;
                t = l, e = r, o = s, n = !0, a = l = void 0
            }
        };
        if ("undefined" == typeof x)
            throw new Error("You must include the utils.js file before tether.js");
        var j = x.Utils, r = j.getScrollParents, a = j.getBounds, f = j.getOffsetParent, h = j.extend, d = j.addClass, u = j.removeClass, m = j.updateClasses, M = j.defer, _ = j.flush, l = j.getScrollBarSize, s = j.removeUtilElements, Y = function () {
            if ("undefined" == typeof document)
                return"";
            for (var t = document.createElement("div"), e = ["transform", "WebkitTransform", "OTransform", "MozTransform", "msTransform"], o = 0; o < e.length; ++o) {
                var n = e[o];
                if (void 0 !== t.style[n])
                    return n
            }
        }(), L = [], D = function () {
            L.forEach(function (t) {
                t.position(!1)
            }), _()
        };
        !function () {
            var t = null, e = null, o = null, n = function i() {
                return"undefined" != typeof e && e > 16 ? (e = Math.min(e - 16, 250), void(o = setTimeout(i, 250))) : void("undefined" != typeof t && b() - t < 10 || (null != o && (clearTimeout(o), o = null), t = b(), D(), e = b() - t))
            };
            "undefined" != typeof window && "undefined" != typeof window.addEventListener && ["resize", "scroll", "touchmove"].forEach(function (t) {
                window.addEventListener(t, n)
            })
        }();
        var X = {center: "center", left: "right", right: "left"}, F = {middle: "middle", top: "bottom", bottom: "top"}, H = {top: 0, left: 0, middle: "50%", center: "50%", bottom: "100%", right: "100%"}, N = function (t, e) {
            var o = t.left, n = t.top;
            return"auto" === o && (o = X[e.left]), "auto" === n && (n = F[e.top]), {left: o, top: n}
        }, U = function (t) {
            var e = t.left, o = t.top;
            return"undefined" != typeof H[t.left] && (e = H[t.left]), "undefined" != typeof H[t.top] && (o = H[t.top]), {left: e, top: o}
        }, V = function (t) {
            var e = t.split(" "), o = B(e, 2), n = o[0], i = o[1];
            return{top: n, left: i}
        }, R = V, q = function (t) {
            function e(t) {
                var o = this;
                n(this, e), z(Object.getPrototypeOf(e.prototype), "constructor", this).call(this), this.position = this.position.bind(this), L.push(this), this.history = [], this.setOptions(t, !1), x.modules.forEach(function (t) {
                    "undefined" != typeof t.initialize && t.initialize.call(o)
                }), this.position()
            }
            return v(e, t), E(e, [{key: "getClass", value: function () {
                        var t = arguments.length <= 0 || void 0 === arguments[0] ? "" : arguments[0], e = this.options.classes;
                        return"undefined" != typeof e && e[t] ? this.options.classes[t] : this.options.classPrefix ? this.options.classPrefix + "-" + t : t
                    }}, {key: "setOptions", value: function (t) {
                        var e = this, o = arguments.length <= 1 || void 0 === arguments[1] ? !0 : arguments[1], n = {offset: "0 0", targetOffset: "0 0", targetAttachment: "auto auto", classPrefix: "tether"};
                        this.options = h(n, t);
                        var i = this.options, s = i.element, a = i.target, f = i.targetModifier;
                        if (this.element = s, this.target = a, this.targetModifier = f, "viewport" === this.target ? (this.target = document.body, this.targetModifier = "visible") : "scroll-handle" === this.target && (this.target = document.body, this.targetModifier = "scroll-handle"), ["element", "target"].forEach(function (t) {
                            if ("undefined" == typeof e[t])
                                throw new Error("Tether Error: Both element and target must be defined");
                            "undefined" != typeof e[t].jquery ? e[t] = e[t][0] : "string" == typeof e[t] && (e[t] = document.querySelector(e[t]))
                        }), d(this.element, this.getClass("element")), this.options.addTargetClasses !== !1 && d(this.target, this.getClass("target")), !this.options.attachment)
                            throw new Error("Tether Error: You must provide an attachment");
                        this.targetAttachment = R(this.options.targetAttachment), this.attachment = R(this.options.attachment), this.offset = V(this.options.offset), this.targetOffset = V(this.options.targetOffset), "undefined" != typeof this.scrollParents && this.disable(), "scroll-handle" === this.targetModifier ? this.scrollParents = [this.target] : this.scrollParents = r(this.target), this.options.enabled !== !1 && this.enable(o)
                    }}, {key: "getTargetBounds", value: function () {
                        if ("undefined" == typeof this.targetModifier)
                            return a(this.target);
                        if ("visible" === this.targetModifier) {
                            if (this.target === document.body)
                                return{top: pageYOffset, left: pageXOffset, height: innerHeight, width: innerWidth};
                            var t = a(this.target), e = {height: t.height, width: t.width, top: t.top, left: t.left};
                            return e.height = Math.min(e.height, t.height - (pageYOffset - t.top)), e.height = Math.min(e.height, t.height - (t.top + t.height - (pageYOffset + innerHeight))), e.height = Math.min(innerHeight, e.height), e.height -= 2, e.width = Math.min(e.width, t.width - (pageXOffset - t.left)), e.width = Math.min(e.width, t.width - (t.left + t.width - (pageXOffset + innerWidth))), e.width = Math.min(innerWidth, e.width), e.width -= 2, e.top < pageYOffset && (e.top = pageYOffset), e.left < pageXOffset && (e.left = pageXOffset), e
                        }
                        if ("scroll-handle" === this.targetModifier) {
                            var t = void 0, o = this.target;
                            o === document.body ? (o = document.documentElement, t = {left: pageXOffset, top: pageYOffset, height: innerHeight, width: innerWidth}) : t = a(o);
                            var n = getComputedStyle(o), i = o.scrollWidth > o.clientWidth || [n.overflow, n.overflowX].indexOf("scroll") >= 0 || this.target !== document.body, r = 0;
                            i && (r = 15);
                            var s = t.height - parseFloat(n.borderTopWidth) - parseFloat(n.borderBottomWidth) - r, e = {width: 15, height: .975 * s * (s / o.scrollHeight), left: t.left + t.width - parseFloat(n.borderLeftWidth) - 15}, f = 0;
                            408 > s && this.target === document.body && (f = -11e-5 * Math.pow(s, 2) - .00727 * s + 22.58), this.target !== document.body && (e.height = Math.max(e.height, 24));
                            var l = this.target.scrollTop / (o.scrollHeight - s);
                            return e.top = l * (s - e.height - f) + t.top + parseFloat(n.borderTopWidth), this.target === document.body && (e.height = Math.max(e.height, 24)), e
                        }
                    }}, {key: "clearCache", value: function () {
                        this._cache = {}
                    }}, {key: "cache", value: function (t, e) {
                        return"undefined" == typeof this._cache && (this._cache = {}), "undefined" == typeof this._cache[t] && (this._cache[t] = e.call(this)), this._cache[t]
                    }}, {key: "enable", value: function () {
                        var t = this, e = arguments.length <= 0 || void 0 === arguments[0] ? !0 : arguments[0];
                        this.options.addTargetClasses !== !1 && d(this.target, this.getClass("enabled")), d(this.element, this.getClass("enabled")), this.enabled = !0, this.scrollParents.forEach(function (e) {
                            e !== t.target.ownerDocument && e.addEventListener("scroll", t.position)
                        }), e && this.position()
                    }}, {key: "disable", value: function () {
                        var t = this;
                        u(this.target, this.getClass("enabled")), u(this.element, this.getClass("enabled")), this.enabled = !1, "undefined" != typeof this.scrollParents && this.scrollParents.forEach(function (e) {
                            e.removeEventListener("scroll", t.position)
                        })
                    }}, {key: "destroy", value: function () {
                        var t = this;
                        this.disable(), L.forEach(function (e, o) {
                            e === t && L.splice(o, 1)
                        }), 0 === L.length && s()
                    }}, {key: "updateAttachClasses", value: function (t, e) {
                        var o = this;
                        t = t || this.attachment, e = e || this.targetAttachment;
                        var n = ["left", "top", "bottom", "right", "middle", "center"];
                        "undefined" != typeof this._addAttachClasses && this._addAttachClasses.length && this._addAttachClasses.splice(0, this._addAttachClasses.length), "undefined" == typeof this._addAttachClasses && (this._addAttachClasses = []);
                        var i = this._addAttachClasses;
                        t.top && i.push(this.getClass("element-attached") + "-" + t.top), t.left && i.push(this.getClass("element-attached") + "-" + t.left), e.top && i.push(this.getClass("target-attached") + "-" + e.top), e.left && i.push(this.getClass("target-attached") + "-" + e.left);
                        var r = [];
                        n.forEach(function (t) {
                            r.push(o.getClass("element-attached") + "-" + t), r.push(o.getClass("target-attached") + "-" + t)
                        }), M(function () {
                            "undefined" != typeof o._addAttachClasses && (m(o.element, o._addAttachClasses, r), o.options.addTargetClasses !== !1 && m(o.target, o._addAttachClasses, r), delete o._addAttachClasses)
                        })
                    }}, {key: "position", value: function () {
                        var t = this, e = arguments.length <= 0 || void 0 === arguments[0] ? !0 : arguments[0];
                        if (this.enabled) {
                            this.clearCache();
                            var o = N(this.targetAttachment, this.attachment);
                            this.updateAttachClasses(this.attachment, o);
                            var n = this.cache("element-bounds", function () {
                                return a(t.element)
                            }), i = n.width, r = n.height;
                            if (0 === i && 0 === r && "undefined" != typeof this.lastSize) {
                                var s = this.lastSize;
                                i = s.width, r = s.height
                            } else
                                this.lastSize = {width: i, height: r};
                            var h = this.cache("target-bounds", function () {
                                return t.getTargetBounds()
                            }), u = h, d = C(U(this.attachment), {width: i, height: r}), p = C(U(o), u), c = C(this.offset, {width: i, height: r}), g = C(this.targetOffset, u);
                            d = w(d, c), p = w(p, g);
                            for (var m = h.left + p.left - d.left, v = h.top + p.top - d.top, y = 0; y < x.modules.length; ++y) {
                                var b = x.modules[y], O = b.position.call(this, {left: m, top: v, targetAttachment: o, targetPos: h, elementPos: n, offset: d, targetOffset: p, manualOffset: c, manualTargetOffset: g, scrollbarSize: S, attachment: this.attachment});
                                if (O === !1)
                                    return!1;
                                "undefined" != typeof O && "object" == typeof O && (v = O.top, m = O.left)
                            }
                            var E = {page: {top: v, left: m}, viewport: {top: v - pageYOffset, bottom: pageYOffset - v - r + innerHeight, left: m - pageXOffset, right: pageXOffset - m - i + innerWidth}}, A = this.target.ownerDocument, T = A.defaultView, S = void 0;
                            return A.body.scrollWidth > T.innerWidth && (S = this.cache("scrollbar-size", l), E.viewport.bottom -= S.height), A.body.scrollHeight > T.innerHeight && (S = this.cache("scrollbar-size", l), E.viewport.right -= S.width), (-1 === ["", "static"].indexOf(A.body.style.position) || -1 === ["", "static"].indexOf(A.body.parentElement.style.position)) && (E.page.bottom = A.body.scrollHeight - v - r, E.page.right = A.body.scrollWidth - m - i), "undefined" != typeof this.options.optimizations && this.options.optimizations.moveElement !== !1 && "undefined" == typeof this.targetModifier && !function () {
                                var e = t.cache("target-offsetparent", function () {
                                    return f(t.target)
                                }), o = t.cache("target-offsetparent-bounds", function () {
                                    return a(e)
                                }), n = getComputedStyle(e), i = o, r = {};
                                if (["Top", "Left", "Bottom", "Right"].forEach(function (t) {
                                    r[t.toLowerCase()] = parseFloat(n["border" + t + "Width"])
                                }), o.right = A.body.scrollWidth - o.left - i.width + r.right, o.bottom = A.body.scrollHeight - o.top - i.height + r.bottom, E.page.top >= o.top + r.top && E.page.bottom >= o.bottom && E.page.left >= o.left + r.left && E.page.right >= o.right) {
                                    var s = e.scrollTop, l = e.scrollLeft;
                                    E.offset = {top: E.page.top - o.top + s - r.top, left: E.page.left - o.left + l - r.left}
                                }
                            }(), this.move(E), this.history.unshift(E), this.history.length > 3 && this.history.pop(), e && _(), !0
                        }
                    }}, {key: "move", value: function (t) {
                        var e = this;
                        if ("undefined" != typeof this.element.parentNode) {
                            var o = {};
                            for (var n in t) {
                                o[n] = {};
                                for (var i in t[n]) {
                                    for (var r = !1, s = 0; s < this.history.length; ++s) {
                                        var a = this.history[s];
                                        if ("undefined" != typeof a[n] && !y(a[n][i], t[n][i])) {
                                            r = !0;
                                            break
                                        }
                                    }
                                    r || (o[n][i] = !0)
                                }
                            }
                            var l = {top: "", left: "", right: "", bottom: ""}, u = function (t, o) {
                                var n = "undefined" != typeof e.options.optimizations, i = n ? e.options.optimizations.gpu : null;
                                if (i !== !1) {
                                    var r = void 0, s = void 0;
                                    t.top ? (l.top = 0, r = o.top) : (l.bottom = 0, r = -o.bottom), t.left ? (l.left = 0, s = o.left) : (l.right = 0, s = -o.right), l[Y] = "translateX(" + Math.round(s) + "px) translateY(" + Math.round(r) + "px)", "msTransform" !== Y && (l[Y] += " translateZ(0)")
                                } else
                                    t.top ? l.top = o.top + "px" : l.bottom = o.bottom + "px", t.left ? l.left = o.left + "px" : l.right = o.right + "px"
                            }, d = !1;
                            if ((o.page.top || o.page.bottom) && (o.page.left || o.page.right) ? (l.position = "absolute", u(o.page, t.page)) : (o.viewport.top || o.viewport.bottom) && (o.viewport.left || o.viewport.right) ? (l.position = "fixed", u(o.viewport, t.viewport)) : "undefined" != typeof o.offset && o.offset.top && o.offset.left ? !function () {
                                l.position = "absolute";
                                var n = e.cache("target-offsetparent", function () {
                                    return f(e.target)
                                });
                                f(e.element) !== n && M(function () {
                                    e.element.parentNode.removeChild(e.element), n.appendChild(e.element)
                                }), u(o.offset, t.offset), d = !0
                            }() : (l.position = "absolute", u({top: !0, left: !0}, t.page)), !d) {
                                for (var p = !0, c = this.element.parentNode; c && 1 === c.nodeType && "BODY" !== c.tagName; ) {
                                    if ("static" !== getComputedStyle(c).position) {
                                        p = !1;
                                        break
                                    }
                                    c = c.parentNode
                                }
                                p || (this.element.parentNode.removeChild(this.element), this.element.ownerDocument.body.appendChild(this.element))
                            }
                            var g = {}, m = !1;
                            for (var i in l) {
                                var v = l[i], b = this.element.style[i];
                                b !== v && (m = !0, g[i] = v)
                            }
                            m && M(function () {
                                h(e.element.style, g)
                            })
                        }
                    }}]), e
        }(k);
        q.modules = [], x.position = D;
        var I = h(q, x), B = function () {
            function t(t, e) {
                var o = [], n = !0, i = !1, r = void 0;
                try {
                    for (var s, a = t[Symbol.iterator](); !(n = (s = a.next()).done) && (o.push(s.value), !e || o.length !== e); n = !0)
                        ;
                } catch (f) {
                    i = !0, r = f
                } finally {
                    try {
                        !n && a["return"] && a["return"]()
                    } finally {
                        if (i)
                            throw r
                    }
                }
                return o
            }
            return function (e, o) {
                if (Array.isArray(e))
                    return e;
                if (Symbol.iterator in Object(e))
                    return t(e, o);
                throw new TypeError("Invalid attempt to destructure non-iterable instance")
            }
        }(), j = x.Utils, a = j.getBounds, h = j.extend, m = j.updateClasses, M = j.defer, $ = ["left", "top", "right", "bottom"];
        x.modules.push({position: function (t) {
                var e = this, o = t.top, n = t.left, i = t.targetAttachment;
                if (!this.options.constraints)
                    return!0;
                var r = this.cache("element-bounds", function () {
                    return a(e.element)
                }), s = r.height, f = r.width;
                if (0 === f && 0 === s && "undefined" != typeof this.lastSize) {
                    var l = this.lastSize;
                    f = l.width, s = l.height
                }
                var u = this.cache("target-bounds", function () {
                    return e.getTargetBounds()
                }), d = u.height, p = u.width, c = [this.getClass("pinned"), this.getClass("out-of-bounds")];
                this.options.constraints.forEach(function (t) {
                    var e = t.outOfBoundsClass, o = t.pinnedClass;
                    e && c.push(e), o && c.push(o)
                }), c.forEach(function (t) {
                    ["left", "top", "right", "bottom"].forEach(function (e) {
                        c.push(t + "-" + e)
                    })
                });
                var g = [], v = h({}, i), y = h({}, this.attachment);
                return this.options.constraints.forEach(function (t) {
                    var r = t.to, a = t.attachment, l = t.pin;
                    "undefined" == typeof a && (a = "");
                    var h = void 0, u = void 0;
                    if (a.indexOf(" ") >= 0) {
                        var c = a.split(" "), m = B(c, 2);
                        u = m[0], h = m[1]
                    } else
                        h = u = a;
                    var b = O(e, r);
                    ("target" === u || "both" === u) && (o < b[1] && "top" === v.top && (o += d, v.top = "bottom"), o + s > b[3] && "bottom" === v.top && (o -= d, v.top = "top")), "together" === u && ("top" === v.top && ("bottom" === y.top && o < b[1] ? (o += d, v.top = "bottom", o += s, y.top = "top") : "top" === y.top && o + s > b[3] && o - (s - d) >= b[1] && (o -= s - d, v.top = "bottom", y.top = "bottom")), "bottom" === v.top && ("top" === y.top && o + s > b[3] ? (o -= d, v.top = "top", o -= s, y.top = "bottom") : "bottom" === y.top && o < b[1] && o + (2 * s - d) <= b[3] && (o += s - d, v.top = "top", y.top = "top")), "middle" === v.top && (o + s > b[3] && "top" === y.top ? (o -= s, y.top = "bottom") : o < b[1] && "bottom" === y.top && (o += s, y.top = "top"))), ("target" === h || "both" === h) && (n < b[0] && "left" === v.left && (n += p, v.left = "right"), n + f > b[2] && "right" === v.left && (n -= p, v.left = "left")), "together" === h && (n < b[0] && "left" === v.left ? "right" === y.left ? (n += p, v.left = "right", n += f, y.left = "left") : "left" === y.left && (n += p, v.left = "right", n -= f, y.left = "right") : n + f > b[2] && "right" === v.left ? "left" === y.left ? (n -= p, v.left = "left", n -= f, y.left = "right") : "right" === y.left && (n -= p, v.left = "left", n += f, y.left = "left") : "center" === v.left && (n + f > b[2] && "left" === y.left ? (n -= f, y.left = "right") : n < b[0] && "right" === y.left && (n += f, y.left = "left"))), ("element" === u || "both" === u) && (o < b[1] && "bottom" === y.top && (o += s, y.top = "top"), o + s > b[3] && "top" === y.top && (o -= s, y.top = "bottom")), ("element" === h || "both" === h) && (n < b[0] && ("right" === y.left ? (n += f, y.left = "left") : "center" === y.left && (n += f / 2, y.left = "left")), n + f > b[2] && ("left" === y.left ? (n -= f, y.left = "right") : "center" === y.left && (n -= f / 2, y.left = "right"))), "string" == typeof l ? l = l.split(",").map(function (t) {
                        return t.trim()
                    }) : l === !0 && (l = ["top", "left", "right", "bottom"]), l = l || [];
                    var w = [], C = [];
                    o < b[1] && (l.indexOf("top") >= 0 ? (o = b[1], w.push("top")) : C.push("top")), o + s > b[3] && (l.indexOf("bottom") >= 0 ? (o = b[3] - s, w.push("bottom")) : C.push("bottom")), n < b[0] && (l.indexOf("left") >= 0 ? (n = b[0], w.push("left")) : C.push("left")), n + f > b[2] && (l.indexOf("right") >= 0 ? (n = b[2] - f, w.push("right")) : C.push("right")), w.length && !function () {
                        var t = void 0;
                        t = "undefined" != typeof e.options.pinnedClass ? e.options.pinnedClass : e.getClass("pinned"), g.push(t), w.forEach(function (e) {
                            g.push(t + "-" + e)
                        })
                    }(), C.length && !function () {
                        var t = void 0;
                        t = "undefined" != typeof e.options.outOfBoundsClass ? e.options.outOfBoundsClass : e.getClass("out-of-bounds"), g.push(t), C.forEach(function (e) {
                            g.push(t + "-" + e)
                        })
                    }(), (w.indexOf("left") >= 0 || w.indexOf("right") >= 0) && (y.left = v.left = !1), (w.indexOf("top") >= 0 || w.indexOf("bottom") >= 0) && (y.top = v.top = !1), (v.top !== i.top || v.left !== i.left || y.top !== e.attachment.top || y.left !== e.attachment.left) && (e.updateAttachClasses(y, v), e.trigger("update", {attachment: y, targetAttachment: v}))
                }), M(function () {
                    e.options.addTargetClasses !== !1 && m(e.target, g, c), m(e.element, g, c)
                }), {top: o, left: n}
            }});
        var j = x.Utils, a = j.getBounds, m = j.updateClasses, M = j.defer;
        x.modules.push({position: function (t) {
                var e = this, o = t.top, n = t.left, i = this.cache("element-bounds", function () {
                    return a(e.element)
                }), r = i.height, s = i.width, f = this.getTargetBounds(), l = o + r, h = n + s, u = [];
                o <= f.bottom && l >= f.top && ["left", "right"].forEach(function (t) {
                    var e = f[t];
                    (e === n || e === h) && u.push(t)
                }), n <= f.right && h >= f.left && ["top", "bottom"].forEach(function (t) {
                    var e = f[t];
                    (e === o || e === l) && u.push(t)
                });
                var d = [], p = [], c = ["left", "top", "right", "bottom"];
                return d.push(this.getClass("abutted")), c.forEach(function (t) {
                    d.push(e.getClass("abutted") + "-" + t)
                }), u.length && p.push(this.getClass("abutted")), u.forEach(function (t) {
                    p.push(e.getClass("abutted") + "-" + t)
                }), M(function () {
                    e.options.addTargetClasses !== !1 && m(e.target, p, d), m(e.element, p, d)
                }), !0
            }});
        var B = function () {
            function t(t, e) {
                var o = [], n = !0, i = !1, r = void 0;
                try {
                    for (var s, a = t[Symbol.iterator](); !(n = (s = a.next()).done) && (o.push(s.value), !e || o.length !== e); n = !0)
                        ;
                } catch (f) {
                    i = !0, r = f
                } finally {
                    try {
                        !n && a["return"] && a["return"]()
                    } finally {
                        if (i)
                            throw r
                    }
                }
                return o
            }
            return function (e, o) {
                if (Array.isArray(e))
                    return e;
                if (Symbol.iterator in Object(e))
                    return t(e, o);
                throw new TypeError("Invalid attempt to destructure non-iterable instance")
            }
        }();
        return x.modules.push({position: function (t) {
                var e = t.top, o = t.left;
                if (this.options.shift) {
                    var n = this.options.shift;
                    "function" == typeof this.options.shift && (n = this.options.shift.call(this, {top: e, left: o}));
                    var i = void 0, r = void 0;
                    if ("string" == typeof n) {
                        n = n.split(" "), n[1] = n[1] || n[0];
                        var s = n, a = B(s, 2);
                        i = a[0], r = a[1], i = parseFloat(i, 10), r = parseFloat(r, 10)
                    } else
                        i = n.top, r = n.left;
                    return e += i, o += r, {top: e, left: o}
                }
            }}), I
    });
}



