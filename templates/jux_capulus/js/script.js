!function($){   
    $(document).ready(function(){
        // Cache the Window object
        $window = $(window);

    // Add class for mainmenu when scroller
        (function(){
                
            var lastpos     = $(window).scrollTop(),
                mainnav     = $('#t3-mainnav'),
                header      = $('#t3-header');

            if(mainnav.length){               
                elmHeightMainnav = mainnav.outerHeight(true);
                elmHeightHeader  = header.outerHeight(true);
                elmHeight        = elmHeightHeader + elmHeightMainnav; 
                $(window).scroll(function() {
                    //ignore when offcanvas open => leave unchanged
                    if($(document.body).hasClass('off-canvas-right') || $(document.body).hasClass('off-canvas-left')){
                        return false;
                    }

                    var scrolltop = $(window).scrollTop();

                    if(scrolltop < lastpos && lastpos > elmHeight){
                        if(!mainnav.hasClass('affix')) {
                            mainnav.addClass('affix');
                        }

                    } else if(scrolltop <= elmHeight) {
                        mainnav.removeClass('affix');
                    }
                    lastpos = scrolltop;

                })
            }
            
            new Headroom(document.querySelector("#t3-mainnav"), {
                tolerance: 10,
                offset : 205,
                classes: {
                  initial: "animated",
                  pinned: "fadeInDown",
                  unpinned: "fadeOutUp"
                }
            }).init();
            
        })(); 
    
        // Add class for mainmenu when scroller
        // (function() {

        //   var mainnav = $('#t3-header');
        //   var elmHeight = $('.t3-header').outerHeight(true);

        //   if (mainnav.length) {
        //     var elmHeight = $('.t3-header').outerHeight(true);
        //     $(window).scroll(function() {

        //       var scrolltop = $(window).scrollTop();
        //       if (scrolltop > elmHeight) {
        //         if (!mainnav.hasClass('affix')) {
        //           mainnav.addClass('affix');
        //         }

        //       } else {
        //         mainnav.removeClass('affix');
        //       }

        //     })
        //   }

        // })();


        //scroll to top button
        var offset = 220;
        var duration = 200;
        jQuery(window).scroll(function() {
            if (jQuery(this).scrollTop() > offset) {
                jQuery('.back-to-top').fadeIn(duration);
            } else {
                jQuery('.back-to-top').fadeOut(duration);
            }
        });
        
        jQuery('.back-to-top').click(function(event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, duration);
            return false;
        });



/*----------------------------------------------------*/
/* MOBILE DETECT FUNCTION
/*----------------------------------------------------*/

		var isMobile = {
	        Android: function() {
	            return navigator.userAgent.match(/Android/i);
	        },
	        BlackBerry: function() {
	            return navigator.userAgent.match(/BlackBerry/i);
	        },
	        iOS: function() {
	            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	        },
	        Opera: function() {
	            return navigator.userAgent.match(/Opera Mini/i);
	        },
	        Windows: function() {
	            return navigator.userAgent.match(/IEMobile/i);
	        },
	        any: function() {
	            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	        }
	    };	  
	  
		testMobile = isMobile.any();
		
		if (testMobile == null)
		{
			//jQuery('.parallax .bg').parallax("50%", 0.5);				
		} 

    });

}(jQuery);
