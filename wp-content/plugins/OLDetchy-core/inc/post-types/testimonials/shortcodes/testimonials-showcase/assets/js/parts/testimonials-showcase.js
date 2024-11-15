(function ($) {
    "use strict";
	qodefCore.shortcodes.etchy_core_testimonials_showcase = {};
	qodefCore.shortcodes.etchy_core_testimonials_showcase.qodefSwiper = qodef.qodefSwiper;

	$(document).ready(function () {
		qodefTestimonialsShowcase.init();
	});

	var qodefTestimonialsShowcase = {
		init: function () {
			this.holder = $('.qodef-testimonials-showcase');

			if (this.holder.length) {
				this.holder.each(function (SliderIndex) {
					var $thisHolder = $(this),
						$thumbs = $thisHolder.find('.qodef-e-media-image'),
						$bullets = $thisHolder.find('.swiper-pagination-bullet');

					qodef.qodefSwiper.sliders[SliderIndex].on('slideChange', function() {
						$thumbs.removeClass('qodef-active-thumb');
						$($thumbs[qodef.qodefSwiper.sliders[SliderIndex].realIndex]).addClass('qodef-active-thumb');
					});

					if ($thumbs.length) {
						if ($thumbs.length % 2 == 0) {
							$($thumbs[$thumbs.length - 2]).addClass('qodef-thumb-last-row');
						}
						$($thumbs[$thumbs.length - 1]).addClass('qodef-thumb-last-row');

						$thumbs.each(function(thumbIndex) {
							var $thisThumb = $(this);

							if (thumbIndex === 0) {
								$($thumbs[thumbIndex]).addClass('qodef-active-thumb');
							}

							$thisThumb.on('click', function (e) {
			                    e.preventDefault();
			                    qodef.qodefSwiper.sliders[SliderIndex].slideTo(thumbIndex + 1);
			                    if ($bullets.length) {
				                    $bullets[thumbIndex].click(); // Fix for Elementor swiper bug
			                    }
			                });
						});
					}
				});
			}
		}
	};

	qodefCore.shortcodes.etchy_core_testimonials_showcase.qodefTestimonialsShowcase = qodefTestimonialsShowcase;

})(jQuery);