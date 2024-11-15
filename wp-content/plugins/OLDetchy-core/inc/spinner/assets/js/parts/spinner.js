(function ($) {
	"use strict";
	
	$(window).on('elementor/frontend/init', function() {
		var isEditMode = Boolean( elementorFrontend.isEditMode() );
		if( isEditMode) {
			qodefSpinner.fadeOutLoader($('#qodef-page-spinner:not(.qodef--custom-spinner)'));
		}
	});
	
	$(document).ready(function () {
		qodefSpinner.init();
	});
	
	var qodefSpinner = {
		init: function () {
			this.holder = $('#qodef-page-spinner:not(.qodef--custom-spinner)');
			
			if (this.holder.length) {
				qodefSpinner.animateSpinner(this.holder);
			}
		},
		animateSpinner: function ($holder) {
			if ($holder.hasClass('qodef-layout--predefined-svg')) {
				$holder.find('.qodef-m-predefined').css("opacity", "1");
				$holder.find('.qodef-m-predefined').addClass('qodef-spinner-animate');
			}
			
			var	mainRevHolder = $('#qodef-main-rev-holder'),
				mobileRevHolder = $('#qodef-main-rev-holder-mobile');
			
			$(window).on('load', function () {
				setTimeout(function () {
					$holder.addClass('qodef-spinner-remove');
				}, 3700);
				
				qodefSpinner.fadeOutLoader($holder);
				
				if(mainRevHolder.length && !mobileRevHolder.length) {
					setTimeout(function() {
						mainRevHolder.find('rs-module').revstart();
					}, 3400);
				} else if (mainRevHolder.length && mobileRevHolder.length ) {
					if (qodefCore.windowWidth >= 768) {
						setTimeout(function() {
							mainRevHolder.find('rs-module').revstart();
						}, 3400);
					} else {
						setTimeout(function() {
							mobileRevHolder.find('rs-module').revstart();
						}, 3400);
					}
				}
				
				qodefSpinner.fadeOutAnimation();
			});
		},
		fadeOutLoader: function ($holder, speed, delay, easing) {
			speed = speed ? speed : 600;
			delay = delay ? delay : 4700;
			easing = easing ? easing : 'swing';
			
			$holder.delay(delay).fadeOut(speed, easing);
			
			$(window).on('bind', 'pageshow', function (event) {
				if (event.originalEvent.persisted) {
					$holder.fadeOut(speed, easing);
				}
			});
		},
		fadeOutAnimation: function () {
			
			// Check for fade out animation
			if (qodefCore.body.hasClass('qodef-spinner--fade-out')) {
				var $pageHolder = $('#qodef-page-wrapper'),
					$linkItems = $('a');
				
				// If back button is pressed, than show content to avoid state where content is on display:none
				window.addEventListener("pageshow", function (event) {
					var historyPath = event.persisted || (typeof window.performance !== "undefined" && window.performance.navigation.type === 2);
					if (historyPath && !$pageHolder.is(':visible')) {
						$pageHolder.show();
					}
				});
				
				$linkItems.on('click', function (e) {
					var $clickedLink = $(this);
					
					if (
						e.which === 1 && // check if the left mouse button has been pressed
						$clickedLink.attr('href').indexOf(window.location.host) >= 0 && // check if the link is to the same domain
						!$clickedLink.hasClass('remove') && // check is WooCommerce remove link
						$clickedLink.parent('.product-remove').length <= 0 && // check is WooCommerce remove link
						$clickedLink.parents('.woocommerce-product-gallery__image').length <= 0 && // check is product gallery link
						typeof $clickedLink.data('rel') === 'undefined' && // check pretty photo link
						typeof $clickedLink.attr('rel') === 'undefined' && // check VC pretty photo link
						!$clickedLink.hasClass('lightbox-active') && // check is lightbox plugin active
						(typeof $clickedLink.attr('target') === 'undefined' || $clickedLink.attr('target') === '_self') && // check if the link opens in the same window
						$clickedLink.attr('href').split('#')[0] !== window.location.href.split('#')[0] // check if it is an anchor aiming for a different page
					) {
						e.preventDefault();
						
						$pageHolder.fadeOut(600, 'easeOutSine', function () {
							window.location = $clickedLink.attr('href');
						});
					}
				});
			}
		}
	}
	
})(jQuery);