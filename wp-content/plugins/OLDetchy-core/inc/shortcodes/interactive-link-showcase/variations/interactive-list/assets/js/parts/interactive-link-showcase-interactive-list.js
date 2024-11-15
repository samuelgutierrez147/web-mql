(function ($) {
    "use strict";

    $(document).ready(function () {
        qodefInteractiveLinkShowcaseInteractiveList.init();
    });

    var qodefInteractiveLinkShowcaseInteractiveList = {
        init: function () {
            this.holder = $('.qodef-interactive-link-showcase.qodef-layout--interactive-list');

            if (this.holder.length) {
                this.holder.each(function () {
                    var $thisHolder = $(this),
                        $links = $thisHolder.find('.qodef-m-item'),
                        x = 0,
                        y = 0,
                        currentXCPosition = 0,
                        currentYCPosition = 0;
    
                    if ($links.length) {
                        $links.on('mouseenter', function () {
                            $links.removeClass('qodef--active');
                            $(this).addClass('qodef--active');
                        }).on('mousemove', function (event) {
                            var $thisLink = $(this),
                                $followInfoHolder = $thisLink.find('.qodef-e-follow-content'),
                                $followImage = $followInfoHolder.find('.qodef-e-follow-image'),
                                $followImageItem = $followImage.find('img'),
                                followImageWidth = $followImageItem.width(),
                                followImagesCount = parseInt($followImage.data('images-count'), 10),
                                followImagesSrc = $followImage.data('images'),
                                $followTitle = $followInfoHolder.find('.qodef-e-follow-title'),
                                itemWidth = $thisLink.outerWidth(),
                                itemHeight = $thisLink.outerHeight(),
                                itemOffsetTop = $thisLink.offset().top - qodefCore.scroll,
                                itemOffsetLeft = $thisLink.offset().left;
            
                            x = (event.clientX - itemOffsetLeft) >> 0;
                            y = (event.clientY - itemOffsetTop) >> 0;
            
                            if (x > itemWidth) {
                                currentXCPosition = itemWidth;
                            } else if (x < 0) {
                                currentXCPosition = 0;
                            } else {
                                currentXCPosition = x;
                            }
            
                            if (y > itemHeight) {
                                currentYCPosition = itemHeight;
                            } else if (y < 0) {
                                currentYCPosition = 0;
                            } else {
                                currentYCPosition = y;
                            }
            
                            if (followImagesCount > 1) {
                                var imagesUrl = followImagesSrc.split('|'),
                                    itemPartSize = itemWidth / followImagesCount;
                
                                $followImageItem.removeAttr('srcset');
                
                                if (currentXCPosition < itemPartSize) {
                                    $followImageItem.attr('src', imagesUrl[0]);
                                }
                
                                // -2 is constant - to remove first and last item from the loop
                                for (var index = 1; index <= (followImagesCount - 2); index++) {
                                    if (currentXCPosition >= itemPartSize * index && currentXCPosition < itemPartSize * (index + 1)) {
                                        $followImageItem.attr('src', imagesUrl[index]);
                                    }
                                }
                
                                if (currentXCPosition >= itemWidth - itemPartSize) {
                                    $followImageItem.attr('src', imagesUrl[followImagesCount - 1]);
                                }
                            }
            
                            $followImage.css({'top': itemHeight / 2});
                            $followTitle.css({'transform': 'translateY(' + -(parseInt(itemHeight, 10) / 2 + currentYCPosition) + 'px)', 'left': -(currentXCPosition - followImageWidth/2)});
                            $followInfoHolder.css({'top': currentYCPosition, 'left': currentXCPosition});
                        }).on('mouseleave', function () {
                            $links.removeClass('qodef--active');
                        });
                    }
                    $thisHolder.addClass('qodef--init');
                });
            }
        }
    };
    
	qodefCore.shortcodes.etchy_core_interactive_link_showcase.qodefInteractiveLinkShowcaseInteractiveList = qodefInteractiveLinkShowcaseInteractiveList;
	
})(jQuery);