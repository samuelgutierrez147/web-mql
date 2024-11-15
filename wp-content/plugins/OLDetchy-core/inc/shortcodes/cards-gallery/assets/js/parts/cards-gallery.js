(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_cards_gallery = {};

	$(document).ready(function () {
		qodefCardsGallery.init();
	});

	var qodefCardsGallery = {
		init: function () {
			this.holder = $('.qodef-cards-gallery');

			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this);
					qodefCardsGallery.initCards( $thisHolder );
					qodefCardsGallery.initBundle( $thisHolder );
				});
			}
		},
		initCards: function ($holder) {
			var $cards = $holder.find('.qodef-m-card');
			$cards.each(function () {
				var $card = $(this);

				$card.on('click', function () {
					if (!$cards.last().is($card)) {
						$card.addClass('qodef-out qodef-animating').siblings().addClass('qodef-animating-siblings');
						$card.detach();
						$card.insertAfter($cards.last());

						setTimeout(function () {
							$card.removeClass('qodef-out');
						}, 200);

						setTimeout(function () {
							$card.removeClass('qodef-animating').siblings().removeClass('qodef-animating-siblings');
						}, 1200);

						$cards = $holder.find('.qodef-m-card');

						return false;
					}
				});


			});
		},
		initBundle: function($holder) {
			if ($holder.hasClass('qodef-animation--bundle') && !qodefCore.html.hasClass('touchevents')) {
				$holder.appear(function () {
					$holder.addClass('qodef-appeared');
					$holder.find('img').one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function () {
						$(this).addClass('qodef-animation-done');
					});
				}, {accX: 0, accY: -100});
			}
		}
	};

	qodefCore.shortcodes.etchy_core_cards_gallery.qodefCardsGallery  = qodefCardsGallery;
	qodefCore.shortcodes.etchy_core_cards_gallery.qodefAppear = qodef.qodefAppear ;
	
})(jQuery);