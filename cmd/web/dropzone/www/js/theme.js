(function($) {
    "use strict";
    
    
    $(document).ready(function(){
        
        /*----------------------------------------------------*/
        /*  Navigation Scroll
        /*----------------------------------------------------*/
        $(window).scroll(function() {    
            var scroll = $(window).scrollTop();
            var window_width = $(window).width();
            if (scroll >= 300 ) {
                $(".navbar").addClass("navbar-fixed-top");
            } else {
                $(".navbar").removeClass("navbar-fixed-top");
            }
        });
        
        /*----------------------------------------------------*/
        /*  Post display
        /*----------------------------------------------------*/
        $('.postTypeFilter .postType2').on('click',function(){
            $('.video_post').removeClass('hidden');
            if( $(this).hasClass('active') ){}
            else{
                $('.video_post.postType2').addClass('hidden')
            }
        }); 
        $('.postTypeFilter .postType3').on('click',function(){
            $('.video_post').removeClass('hidden');
            if( $(this).hasClass('active') ){}
            else{
                $('.video_post.postType3').addClass('hidden')
            }
        }); 
        $('.postTypeFilter .postType4').on('click',function(){
            $('.video_post').removeClass('hidden');
            if( $(this).hasClass('active') ){}
            else{
                $('.video_post.postType4').addClass('hidden')
            }
        }); 
        $('.postTypeFilter .postType1').on('click',function(){
            $('.video_post').removeClass('hidden');
        }); 
        
        /*----------------------------------------------------*/
        /*  PopUps
        /*----------------------------------------------------*/
        $('a[href="login.html"],a[href="signup-popup.html"]').magnificPopup({
            type: 'ajax'
        });
        
        /*----------------------------------------------------*/
        /*  Ribbon Links Disable
        /*----------------------------------------------------*/
        $('.ribbon a[href="#"]').on('click',function(){
            return false
        });
        
        /*----------------------------------------------------*/
        /*  Post Page with Sidebar2's height Depend on Sidebar 3
        /*----------------------------------------------------*/
        $('.post_page_sidebar2').css( "min-height", function(){
            return $('.sidebar3').height() 
        });
        
        /*----------------------------------------------------*/
        /*  Sidebar1 each-widget minimum height based the widgets
        /*----------------------------------------------------*/
        $('.sidebar1 .widget').css( "min-height", function(){
            return Math.max( $('.sidebar1 .widget1').height(), $('.sidebar1 .widget2').height(), $('.sidebar1 .widget3').height(), $('.sidebar1 .widget4').height() ) + 16
        });
        
        /*----------------------------------------------------*/
        /*  footer each-widget minimum height based the widgets
        /*----------------------------------------------------*/
        $('.sidebar_footer .w_in_footer').css( "min-height", function(){
            return Math.max( $('.sidebar_footer .widget1').height(), $('.sidebar_footer .widget2').height(), $('.sidebar_footer .widget3').height(), $('.sidebar_footer .widget4').height() ) + 16
        });
        
        /*----------------------------------------------------*/
        /*  Category Filter Dropdown
        /*----------------------------------------------------*/
        $('.category_filter .dropdown-menu').find('a').on('click',function(e) {
            e.preventDefault();
            var concept = $(this).find('.filter_text').text();
            $('.category_filter .btn span.filter-option').text(concept);
            $('.category_filter .btn').addClass('active');
            $('.category_filter .dropdown-menu').find('.selected').removeClass('selected');
            $(this).parent().addClass('selected');
            if ( $('.category_filter .dropdown-menu li:first-child').hasClass('selected') ){
                $('.category_filter .btn').removeClass('active');
            }
        });


        /*----------------------------------------------------*/
        /*  jScroll // Loading More Function
        /*----------------------------------------------------*/
        $('.content_video_posts').jscroll({
            loadingHtml: '<div class="clearfix"></div><div class="row m0 loadting_text"><i class="fa fa-refresh fa-spin"></i>loading ...</div>',
            nextSelector: 'a.load_more_videos:last',
            autoTrigger: false
        })
        
    }); 
    
    $(window).on('load', function() { // makes sure the whole site is loaded
		$('#status').fadeOut(); // will first fade out the loading animation
		$('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
		$('body').delay(350).css({'overflow':'visible'})
    })
    
})(jQuery);