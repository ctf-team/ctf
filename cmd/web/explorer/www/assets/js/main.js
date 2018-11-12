   /*************************************************************************
    Template Name: Zman
    Template URI: https://themeforest.net/user/theme_bangla
    Description: A 'Zman – Personal Portfolio Html5 Template' is perfect if you like a clean and modern design. This theme is ideal for Agency, Freelancer, Portfolio, and those who need an easy, attractive and effective way to share their work with clients.
    Author: Theme_Bangle
    Version: 1.0
    Author URI: http://riyad.ninja
    
    
    Note: style js.
*************************************************************************/
/*
    
    00. Preloader
    01. Sticky Header
    02. Section Scroll
    03. Section Smoot Scroll
    04. Parallax Background
    05. Animated Progress
    06. Testimonail
    07. Fan Fact Counter
    08. Masonry
    09. IsoTop Postfolio
    10. Magnific Popup
    11. Google Map
 
==================================================
[ End table content ]
==================================================*/


(function($) {
    'use strict';
    
    var zmanApp = {
        /* ---------------------------------------------
         Preloader
         --------------------------------------------- */ 
        preloader: function() {
            //After 2s preloader is fadeOut
            $('.preloader').delay(2000).fadeOut('slow');
            setTimeout(function() {
                //After 2s, the no-scroll class of the body will be removed
                $('body').removeClass('no-scroll');
            }, 2000); //Here you can change preloader time
        },
    	/* ---------------------------------------------
    	 Menu
    	 --------------------------------------------- */
    	menu: function() {
    	    var $submenu = $(".navigation .slideLeft .mainmenu li").has(".sub-menu"),
    	        $submenuSelector = $(".sub-menu"),
    	        $mobileNavSelector = $("#main-mobile-container .main-navigation"),
    	        $mobileNavOverlay = $(".navigation .slideLeft"),
    	        $mobileMenuContent = $(".navigation .menu-content"),
    	        $mobileNavBar = $(".mobile-menu-link"),
    	        $mobileNav = $(".menu-mobile"),
    	        $menuWrap = $(".menu-list");



    	    if ($submenu) {
    	        var $hasSubmenuIcon = $("<span class='fa fa-angle-down'></span>");
    	        $submenuSelector.prev().append($hasSubmenuIcon);
    	    }

    	    // Main Navigation Mobile
    	    // --------------------------------            
    	    $mobileNavSelector.addClass("slideLeft");

    	    var menuopen_main = function() {
    	            $mobileNavOverlay.removeClass("menuclose").addClass("menuopen");
    	        },
    	        menuclose_main = function() {
    	            $mobileNavOverlay.removeClass("menuopen").addClass("menuclose");
    	        };
    	    $mobileNavBar.on("click", function(e) {
    	    	e.preventDefault();
    	        if ($mobileMenuContent.hasClass("menuopen")) {
    	            $(this).removeClass("menuopen");
    	            $(menuclose_main);
    	        } else {
    	        	$(this).addClass("menuopen");
    	            $(menuopen_main);
    	        }
    	    });
    	    // Sub Menu
    	    // -------------------------------- 
    	    var $mobileExtendBtn = $("<span class='menu-click'><i class='menu-arrow fa fa-plus'></i></span>");
    	    $submenu.prepend($mobileExtendBtn);

    	    $mobileNav.on("click", function() {
    	        $menuWrap.slideToggle("slow");
    	    });
    	    var $mobileSubMenuOpen = $(".menu-click");
    	    $mobileSubMenuOpen.on("click", function() {
    	        var $this = $(this);
    	        $this.siblings(".sub-menu").slideToggle("slow");
    	        $this.children(".menu-arrow").toggleClass("menu-extend");
    	    });		    

    	    // For Last menu
    	    // --------------------------------
    	    var $fullMenuElement = $(".navigation .mainmenu li");
    	    $fullMenuElement.on("mouseenter mouseleave", function(e) {
    	        var $this = $(this);
    	        if ($("ul", $this).length) {
    	            var elm = $("ul:first", $this),
    	                off = elm.offset(),
    	                l = off.left,
    	                w = elm.width(),
    	                docW = $(".header-bottom > .container").width(),
    	                isEntirelyVisible = (l + w <= docW);
    	            if (!isEntirelyVisible) {
    	                $this.addClass("right-side-menu");
    	            } else {
    	                $this.removeClass("right-side-menu");
    	            }
    	        }
    	    });

    	    var $dropdownSelector = $(".dropdown-menu input, .dropdown-menu label, .dropdown-menu select");
    	    $dropdownSelector.click(function(e) {
    	        e.stopPropagation();
    	    });
    	},

        /* ---------------------------------------------
            08. Masonry
        --------------------------------------------- */
        grid_masonry: function () {
            if ($('#masonry').length > 0) {
                var container = $('#masonry');
                container.imagesLoaded(function () {
                    container.masonry({
                        itemSelector: '.grid'
                    });
                });
            }
        },
        
        /* ---------------------------------------------
		Portfolio / Hover Animation
		 --------------------------------------------- */
		portfolio_Animation: function() {
			var $modelisotop = $('.portfolio-items-list');
            $modelisotop.isotope({
                filter: '*',
                animationOptions: {
                    duration: 1000,
                    easing: 'linear',
                    queue: false
                }
            });
            $('.portfolio-filter-menu > li a').on("click", function () {
                $('.portfolio-filter-menu > li a').removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter');
                $modelisotop.isotope({ 
                    filter: selector,
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false
                    }
                });
                return false;
            });

            $('.portfolio-thumb > img').clone().appendTo('.portfolio-item a.zoom');
			$('.portfolio-item a.zoom').magnificPopup({
                type: 'image',
                removalDelay: 300,
                mainClass: 'mfp-with-zoom',
                gallery: {
                    enabled: true
                },
                zoom: {
                    enabled: true, 
                    duration: 300, 
                    easing: 'ease-in', 
                    opener: function (openerElement) {
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
            });
		},

		/* ---------------------------------------------
		 Progress Bar
		--------------------------------------------- */
		progress_var: function() {
            $('.progress-bar > span' ).each(function () {
                var $this = $(this);
                var width = $(this).data('percent');
                $this.css({
                    'transition': 'width 3s'
                });
                setTimeout(function () {
                    $this.css('width', width + '%');
                }, 500);
            });
		},

		/* ---------------------------------------------
		 Widget Mobile fix
		--------------------------------------------- */
		widget_mobile: function () {
		    function debouncer(func, timeout) {
		        var timeoutID, timeout = timeout || 500;
		        return function () {
		            var scope = this,
		                args = arguments;
		            clearTimeout(timeoutID);
		            timeoutID = setTimeout(function () {
		                func.apply(scope, Array.prototype.slice.call(args));
		            }, timeout);
		        }
		    }
		    function resized() {
		        var getWidgetTitle = $('.widget .widget-title');
		        var getWidgetTitleContent;
		        if ($(window).width() <= 991) {
		            getWidgetTitleContent = $('.widget .widget-title').nextAll().hide();
		            getWidgetTitle.addClass('expand-margin');
		            getWidgetTitle.on('click', function(e) {
		                e.stopImmediatePropagation();
		                $(this).toggleClass('expand');
		                $(this).nextAll().slideToggle();
		                return false;
		            });
		            getWidgetTitle.each(function(){
		                $(this).addClass('mb-widget');
		            });
		        } else {
		            getWidgetTitleContent = $('.widget .widget-title').nextAll().show();
		            getWidgetTitle.removeClass('expand-margin');
		            getWidgetTitle.each(function(){
		                $(this).parent().removeClass('mb-widget');
		            });
		        };
		    }
		    resized();

		    var prevW = window.innerWidth || $(window).width();
		    $(window).resize(debouncer(function (e) {
		        var currentW = window.innerWidth || $(window).width();
		        if (currentW != prevW) {
		            resized();
		        }
		        prevW = window.innerWidth || $(window).width();
		    }));

		    //Mobile Responsive
		    var $extendBtn = $(".extend-btn .extend-icon");
		    $extendBtn.on("click", function(e) {
		        e.preventDefault();
		        var $self = $(this);
		        $self.parent().prev().toggleClass("mobile-extend");
		        $self.parent().toggleClass("extend-btn");
		        $self.toggleClass("up");
		    });
		},
		
        /* ---------------------------------------------
		 All Carousel Active Script
		--------------------------------------------- */
		allCarousel: function() {
			var $portfolioCarousel = $('.portfolio-carousel-gallery');
			$portfolioCarousel.owlCarousel({
				loop: false,
				responsive:{
					280:{
						items: 1
					},
					480 : {
						items: 1
					},
					768 : {
					   items: 1
					},
					1200 : {
					   items: 1
					}
				}
			});			

			var $heroBanner = $('.hero-slider');
			$heroBanner.owlCarousel({
				loop: false,
				dots: false,
				autoplay: true,
				autoplayHoverPause: true,
				slideTransition: 'linear',
				animateIn: 'fadeIn',
				responsive:{
					280:{
						items: 1
					},
					480 : {
						items: 1
					},
					768 : {
					   items: 1
					},
					1200 : {
					   items: 1
					}
				}
			});	
		},
        
		/* ---------------------------------------------
		 Scroll top
		--------------------------------------------- */
	    scroll_top: function () {
	    	//Fixed Navbar
	    	var $fixedHeader = $('.sticky-header');
	    	$(window).on('scroll', function() {
	    		if($(this).scrollTop() >= $(this).height()) {
	    			$fixedHeader
	    			.addClass('sticky-show')
	    			.removeClass('sticky-hide');
	    		} else if($(this).scrollTop() >= 100) {
	    			$fixedHeader
	    			.addClass('sticky-hide')
	    			.removeClass('sticky-show');
	    		} else {
	    			$fixedHeader
	    			.removeClass('sticky-hide');
	    		}
	    	});

	    	//Fixed Navbar
	    	var $fixedHeader2 = $('.site-fixed-header');
	    	$(window).on('scroll', function() {
	    		if($(this).scrollTop() >= 350) {
	    			$fixedHeader2
	    			.addClass('sticky-enable');
	    		} else {
	    			$fixedHeader2
	    			.removeClass('sticky-enable');
	    		}
	    	});

	    	//Footer Scroll Top
			//$("body").append("<a href='#top' id='scroll-top' class='topbutton btn-hide'><span class='fa fa-angle-up'></span></a>");
			
			var $scrolltop = $('#scroll-top');
			$(window).on('scroll', function() {
				if($(this).scrollTop() > $(this).height()) {
					$scrolltop
					.addClass('btn-show')
					.removeClass('btn-hide');
				} else {
					$scrolltop
					.addClass('btn-hide')
					.removeClass('btn-show');
				}
			});
			$("a[href='#top']").on('click', function() {
				$("html, body").animate({
					scrollTop: 0
				}, "normal");
				return false;
			});

		},
    
        /* ---------------------------------------------
         function initializ
         --------------------------------------------- */
        initializ: function() {   
            zmanApp.menu();   
            zmanApp.grid_masonry();   
            zmanApp.portfolio_Animation();   
            zmanApp.progress_var();   
            zmanApp.widget_mobile();   
            zmanApp.allCarousel();   
            zmanApp.scroll_top();   
        }
    };

    /* ---------------------------------------------
     Document ready function
     --------------------------------------------- */
    $(function() {
        zmanApp.initializ();
    }); 

    $(window).on('load', function() {
        zmanApp.preloader();
    });
    
})(jQuery);