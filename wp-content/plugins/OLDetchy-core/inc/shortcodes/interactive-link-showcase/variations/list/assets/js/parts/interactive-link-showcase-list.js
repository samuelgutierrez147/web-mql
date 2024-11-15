(function ($) {
    "use strict";

    $(document).ready(function () {
        qodefInteractiveLinkShowcaseList.init();
    });

    var qodefInteractiveLinkShowcaseList = {
        init: function () {
            this.holder = $('.qodef-interactive-link-showcase.qodef-layout--list');

            if (this.holder.length) {
                this.holder.each(function () {
                    var $thisHolder = $(this),
                        $images = $thisHolder.find('.qodef-m-image'),
                        $links = $thisHolder.find('.qodef-m-item');
    
                    $images.eq(0).addClass('qodef--active');
                    $links.eq(0).addClass('qodef--active');
    
                    $links.on('touchstart mouseenter', function (e) {
                        var $thisLink = $(this);
        
                        if (!qodefCore.html.hasClass('touchevents') || (!$thisLink.hasClass('qodef--active') && qodefCore.windowWidth > 680)) {
                            e.preventDefault();
                            $images.removeClass('qodef--active').eq($thisLink.index()).addClass('qodef--active');
                            $links.removeClass('qodef--active').eq($thisLink.index()).addClass('qodef--active');
                        }
                    }).on('touchend mouseleave', function () {
                        var $thisLink = $(this);
        
                        if (!qodefCore.html.hasClass('touchevents') || (!$thisLink.hasClass('qodef--active') && qodefCore.windowWidth > 680)) {
                            $links.removeClass('qodef--active').eq($thisLink.index()).addClass('qodef--active');
                            $images.removeClass('qodef--active').eq($thisLink.index()).addClass('qodef--active');
                        }
                    });
    
                    $thisHolder.addClass('qodef--init');
                });
            }
        }
    };

	qodefCore.shortcodes.etchy_core_interactive_link_showcase.qodefInteractiveLinkShowcaseList = qodefInteractiveLinkShowcaseList;

})(jQuery);