(function ($) {
    "use strict";

    $(document).ready(function () {
        qodefInteractiveLinkShowcaseSlider.init();
    });

    var qodefInteractiveLinkShowcaseSlider = {
        init: function () {
            this.holder = $('.qodef-interactive-link-showcase.qodef-layout--slider');

            if (this.holder.length) {
                this.holder.each(function () {
                    var $thisHolder = $(this),
                        $images = $thisHolder.find('.qodef-m-image');
    
                    var $swiperSlider = new Swiper($thisHolder.find('.swiper-container'), {
                        loop: true,
                        slidesPerView: 'auto',
                        centeredSlides: true,
                        speed: 1400,
                        mousewheel: true,
                        init: false
                    });
    
                    $thisHolder.waitForImages(function () {
                        $swiperSlider.init();
                    });
    
                    $swiperSlider.on('init', function () {
                        $images.eq(0).addClass('qodef--active');
                        $thisHolder.find('.swiper-slide-active').addClass('qodef--active');
        
                        $swiperSlider.on('slideChangeTransitionStart', function () {
                            var $swiperSlides = $thisHolder.find('.swiper-slide'),
                                $activeSlideItem = $thisHolder.find('.swiper-slide-active');
            
                            $images.removeClass('qodef--active').eq($activeSlideItem.data('swiper-slide-index')).addClass('qodef--active');
                            $swiperSlides.removeClass('qodef--active');
            
                            $activeSlideItem.addClass('qodef--active');
                        });
        
                        $thisHolder.find('.swiper-slide').on('click', function (e) {
                            var $thisSwiperLink = $(this),
                                $activeSlideItem = $thisHolder.find('.swiper-slide-active');
            
                            if (!$thisSwiperLink.hasClass('swiper-slide-active')) {
                                e.preventDefault();
                                e.stopImmediatePropagation();
                
                                if (e.pageX < $activeSlideItem.offset().left) {
                                    $swiperSlider.slidePrev();
                                    return false;
                                }
                
                                if (e.pageX > $activeSlideItem.offset().left + $activeSlideItem.outerWidth()) {
                                    $swiperSlider.slideNext();
                                    return false;
                                }
                            }
                        });
        
                        $thisHolder.addClass('qodef--init');
                    });
                });
            }
        }
    };

	qodefCore.shortcodes.etchy_core_interactive_link_showcase.qodefInteractiveLinkShowcaseSlider = qodefInteractiveLinkShowcaseSlider;

})(jQuery);