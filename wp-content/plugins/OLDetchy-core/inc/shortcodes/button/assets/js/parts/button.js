(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_button = {};

	$(document).ready(function () {
		qodefButton.init();
	});
	
	var qodefButton = {
		init: function () {
			this.buttons = $('.qodef-button');
			
			if (this.buttons.length) {
				
				this.buttons.each(function () {
					var $thisButton = $(this);
					
					if ($thisButton.hasClass('qodef-btn-wave-hover') && !$("body").hasClass('qodef-browser--ms-explorer')){
						if ($('.qodef-btn-wave--svg').length === 0) {
							$('body').append('<svg class="qodef-btn-wave--svg"><defs><clipPath id="qodef-btn-clip-id"><path d=""/></clipPath></defs></svg></div>' +
											 '<svg class="qodef-btn-wave--svg-initial"><defs><clipPath id="qodef-btn-clip-id-initial"><path d=""/></clipPath></defs></svg></div>');
						}
						qodefButton.initWaveButton($thisButton);
					} else {
						qodefButton.buttonHoverColor($thisButton);
						qodefButton.buttonHoverBgColor($thisButton);
						qodefButton.buttonHoverBorderColor($thisButton);
					}
				});
			}
		},
		initWaveButton: function ($button) {
			var btnSnap = Snap("#qodef-btn-clip-id path"),
				btnPaths = {
					pathFrom:
						"M1 29C4 27 6 25 10 24 18 20 26 28 33 32 41 37 49 41 58 39 65 37 71 34 75 28 79 21 80 12 86 7 89 5 92 4 95 4 101 3 106 5 111 6 120 6 128 3 136-1 91-1 46-1 1-1 1 9 1 19 1 29Z",
					pathTo:
						"m0 -1c0 0 6 0 0 0 5 0 14 0 19 0 6 0 17 0 23 0 5 0 15 0 20 0 4 0 11 0 15 0 2 0 7 0 10 0 4 0 12 0 16 0 6 0 17 0 25-1-45 0-90 0-135 0v1z"
				};
			
			// Init
			btnSnap.attr("d", btnPaths.pathTo);
			
			$button.on('mouseenter', function () {
				if ($('.qodef-active-wave-button').length === 0) {//disable posibilty of glicthing hover
					$button.addClass('qodef-active-wave-button');
					btnSnap.animate({ d: btnPaths.pathFrom }, 300, mina.easein);
				} else {
					setTimeout(function(){
						$button.addClass('qodef-active-wave-button');
						btnSnap.animate({ d: btnPaths.pathFrom }, 300, mina.easein);
					}, 300);
				}
			});
			$button.on('mouseleave', function () {
				btnSnap.animate({ d: btnPaths.pathTo }, 250, mina.easeinout);
				setTimeout(function(){
					$button.removeClass('qodef-active-wave-button');
				}, 250);
			});
		},
		buttonHoverColor: function ($button) {
			if (typeof $button.data('hover-color') !== 'undefined') {
				var hoverColor = $button.data('hover-color');
				var originalColor = $button.css('color');
				
				$button.on('mouseenter', function () {
					qodefButton.changeColor($button, 'color', hoverColor);
				});
				$button.on('mouseleave', function () {
					qodefButton.changeColor($button, 'color', originalColor);
				});
			}
		},
		buttonHoverBgColor: function ($button) {
			if (typeof $button.data('hover-background-color') !== 'undefined') {
				var hoverBackgroundColor = $button.data('hover-background-color');
				var originalBackgroundColor = $button.css('background-color');
				
				$button.on('mouseenter', function () {
					qodefButton.changeColor($button, 'background-color', hoverBackgroundColor);
				});
				$button.on('mouseleave', function () {
					qodefButton.changeColor($button, 'background-color', originalBackgroundColor);
				});
			}
		},
		buttonHoverBorderColor: function ($button) {
			if (typeof $button.data('hover-border-color') !== 'undefined') {
				var hoverBorderColor = $button.data('hover-border-color');
				var originalBorderColor = $button.css('borderTopColor');
				
				$button.on('mouseenter', function () {
					qodefButton.changeColor($button, 'border-color', hoverBorderColor);
				});
				$button.on('mouseleave', function () {
					qodefButton.changeColor($button, 'border-color', originalBorderColor);
				});
			}
		},
		changeColor: function ($button, cssProperty, color) {
			$button.css(cssProperty, color);
		}
	};

	qodefCore.shortcodes.etchy_core_button.qodefButton = qodefButton;


})(jQuery);