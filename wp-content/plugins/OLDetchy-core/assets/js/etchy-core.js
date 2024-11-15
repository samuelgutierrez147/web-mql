(function ($) {
	"use strict";
	
	// This case is important when theme is not active
	if (typeof qodef !== 'object') {
		window.qodef = {};
	}
	
	window.qodefCore = {};
	qodefCore.shortcodes = {};
	qodefCore.listShortcodesScripts = {
		qodefSwiper: qodef.qodefSwiper,
		qodefPagination: qodef.qodefPagination,
		qodefFilter: qodef.qodefFilter,
		qodefMasonryLayout: qodef.qodefMasonryLayout,
		qodefJustifiedGallery: qodef.qodefJustifiedGallery,
		qodefModifiedButton: qodef.qodefModifiedButton,
	};

	qodefCore.body = $('body');
	qodefCore.html = $('html');
	qodefCore.windowWidth = $(window).width();
	qodefCore.windowHeight = $(window).height();
	qodefCore.scroll = 0;

	$(document).ready(function () {
		qodefCore.scroll = $(window).scrollTop();
		qodefInlinePageStyle.init();
	});

	$(window).resize(function () {
		qodefCore.windowWidth = $(window).width();
		qodefCore.windowHeight = $(window).height();
	});

	$(window).scroll(function () {
		qodefCore.scroll = $(window).scrollTop();
	});

	var qodefScroll = {
		disable: function(){
			if (window.addEventListener) {
				window.addEventListener('wheel', qodefScroll.preventDefaultValue, {passive: false});
			}

			// window.onmousewheel = document.onmousewheel = qodefScroll.preventDefaultValue;
			document.onkeydown = qodefScroll.keyDown;
		},
		enable: function(){
			if (window.removeEventListener) {
				window.removeEventListener('wheel', qodefScroll.preventDefaultValue, {passive: false});
			}
			window.onmousewheel = document.onmousewheel = document.onkeydown = null;
		},
		preventDefaultValue: function(e){
			e = e || window.event;
			if (e.preventDefault) {
				e.preventDefault();
			}
			e.returnValue = false;
		},
		keyDown: function(e) {
			var keys = [37, 38, 39, 40];
			for (var i = keys.length; i--;) {
				if (e.keyCode === keys[i]) {
					qodefScroll.preventDefaultValue(e);
					return;
				}
			}
		}
	};

	qodefCore.qodefScroll = qodefScroll;

	var qodefPerfectScrollbar = {
		init: function ($holder) {
			if ($holder.length) {
				qodefPerfectScrollbar.qodefInitScroll($holder);
			}
		},
		qodefInitScroll: function ($holder) {
			var $defaultParams = {
				wheelSpeed: 0.6,
				suppressScrollX: true
			};

			var $ps = new PerfectScrollbar($holder.selector, $defaultParams);
			$(window).resize(function () {
				$ps.update();
			});
		}
	};

	qodefCore.qodefPerfectScrollbar = qodefPerfectScrollbar;

	var qodefInlinePageStyle = {
		init: function () {
			this.holder = $('#etchy-core-page-inline-style');

			if (this.holder.length) {
				var style = this.holder.data('style');

				if (style.length) {
					$('head').append('<style type="text/css">' + style + '</style>');
				}
			}
		}
	};

})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefAgeVerificationModal.init();
	});
	
	var qodefAgeVerificationModal = {
		init: function () {
			this.holder = $('#qodef-age-verification-modal');
			
			if (this.holder.length) {
				var $preventHolder = this.holder.find('.qodef-m-content-prevent');
				
				if ($preventHolder.length) {
					var $preventYesButton = $preventHolder.find('.qodef-prevent--yes');
					
					$preventYesButton.on('click', function () {
						var cname = 'disabledAgeVerification';
						var cvalue = 'Yes';
						var exdays = 7;
						var d = new Date();
						
						d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
						var expires = "expires=" + d.toUTCString();
						document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
						
						qodefAgeVerificationModal.handleClassAndScroll('remove');
					});
				}
			}
		},
		
		handleClassAndScroll: function (option) {
			if (option === 'remove') {
				qodefCore.body.removeClass('qodef-age-verification--opened');
				qodefCore.qodefScroll.enable();
			}
			if (option === 'add') {
				qodefCore.body.addClass('qodef-age-verification--opened');
				qodefCore.qodefScroll.disable();
			}
		},
	};
	
})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefBackToTop.init();
	});
	
	var qodefBackToTop = {
		init: function () {
			this.holder = $('#qodef-back-to-top');
			
			if(this.holder.length) {
				// Scroll To Top
				this.holder.on('click', function (e) {
					e.preventDefault();
					qodefBackToTop.animateScrollToTop();
				});
				
				qodefBackToTop.showHideBackToTop();
			}
		},
		animateScrollToTop: function() {
			var startPos = qodef.scroll,
				newPos = qodef.scroll,
				step = .9,
				animationFrameId;
			
			var startAnimation = function() {
				if (newPos === 0) return;
				newPos < 0.0001 ? newPos = 0 : null;
				var ease = qodefBackToTop.easingFunction((startPos - newPos) / startPos);
				$('html, body').scrollTop(startPos - (startPos - newPos) * ease);
				newPos = newPos * step;
				
				animationFrameId = requestAnimationFrame(startAnimation)
			}
			startAnimation();
			$(window).one('wheel touchstart', function() {
				cancelAnimationFrame(animationFrameId);
			});
		},
		easingFunction: function(n) {
			return 0 == n ? 0 : Math.pow(1024, n - 1);
		},
		showHideBackToTop: function () {
			$(window).scroll(function () {
				var $thisItem = $(this),
					b = $thisItem.scrollTop(),
					c = $thisItem.height(),
					d;
				
				if (b > 0) {
					d = b + c / 2;
				} else {
					d = 1;
				}
				
				if (d < 1e3) {
					qodefBackToTop.addClass('off');
				} else {
					qodefBackToTop.addClass('on');
				}
			});
		},
		addClass: function (a) {
			this.holder.removeClass('qodef--off qodef--on');
			
			if (a === 'on') {
				this.holder.addClass('qodef--on');
			} else {
				this.holder.addClass('qodef--off');
			}
		},
	};
	
})(jQuery);

(function ($) {
	"use strict";
	
	$(window).on('load', function () {
		qodefUncoverFooter.init();
	});
	
	var qodefUncoverFooter = {
		holder: '',
		init: function () {
			this.holder = $('#qodef-page-footer.qodef--uncover');
			
			if (this.holder.length && !qodefCore.html.hasClass('touchevents')) {
				qodefUncoverFooter.addClass();
				qodefUncoverFooter.setHeight(this.holder);
				
				$(window).resize(function () {
                    qodefUncoverFooter.setHeight(qodefUncoverFooter.holder);
				});
			}
		},
        setHeight: function ($holder) {
	        $holder.css('height', 'auto');
	        
            var footerHeight = $holder.outerHeight();
            
            if (footerHeight > 0) {
                $('#qodef-page-outer').css({'margin-bottom': footerHeight, 'background-color': qodefCore.body.css('backgroundColor')});
                $holder.css('height', footerHeight);
            }
        },
		addClass: function () {
			qodefCore.body.addClass('qodef-page-footer--uncover');
		}
	};
	
})(jQuery);

(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefFullscreenMenu.init();
	});
	
	var qodefFullscreenMenu = {
		init: function () {
			var $fullscreenMenuOpener = $('a.qodef-fullscreen-menu-opener'),
				$menuItems = $('#qodef-fullscreen-area nav ul li a');
			
			// Open popup menu
			$fullscreenMenuOpener.on('click', function (e) {
				e.preventDefault();
				
				if (!qodefCore.body.hasClass('qodef-fullscreen-menu--opened')) {
					qodefFullscreenMenu.openFullscreen();
					$(document).keyup(function (e) {
						if (e.keyCode === 27) {
							qodefFullscreenMenu.closeFullscreen();
						}
					});
				} else {
					qodefFullscreenMenu.closeFullscreen();
				}
			});
			
			//open dropdowns
			$menuItems.on('tap click', function (e) {
				var $thisItem = $(this);
				if ($thisItem.parent().hasClass('menu-item-has-children')) {
					e.preventDefault();
					qodefFullscreenMenu.clickItemWithChild($thisItem);
				} else if (($(this).attr('href') !== "http://#") && ($(this).attr('href') !== "#")) {
					qodefFullscreenMenu.closeFullscreen();
				}
			});
		},
		openFullscreen: function () {
			qodefCore.body.removeClass('qodef-fullscreen-menu-animate--out').addClass('qodef-fullscreen-menu--opened qodef-fullscreen-menu-animate--in');
			qodefCore.qodefScroll.disable();
		},
		closeFullscreen: function () {
			qodefCore.body.removeClass('qodef-fullscreen-menu--opened qodef-fullscreen-menu-animate--in').addClass('qodef-fullscreen-menu-animate--out');
			qodefCore.qodefScroll.enable();
			$("nav.qodef-fullscreen-menu ul.sub_menu").slideUp(200);
		},
		clickItemWithChild: function (thisItem) {
			var $thisItemParent = thisItem.parent(),
				$thisItemSubMenu = $thisItemParent.find('.sub-menu').first();
			
			if ($thisItemSubMenu.is(':visible')) {
				$thisItemSubMenu.slideUp(300);
			} else {
				$thisItemSubMenu.slideDown(300);
				$thisItemParent.siblings().find('.sub-menu').slideUp(400);
			}
		}
	};
	
})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefHeaderScrollAppearance.init();
	});
	
	var qodefHeaderScrollAppearance = {
		appearanceType: function () {
			return qodefCore.body.attr('class').indexOf('qodef-header-appearance--') !== -1 ? qodefCore.body.attr('class').match(/qodef-header-appearance--([\w]+)/)[1] : '';
		},
		init: function () {
			var appearanceType = this.appearanceType();
			
			if (appearanceType !== '' && appearanceType !== 'none') {
                qodefCore[appearanceType + "HeaderAppearance"]();
			}
		}
	};
	
})(jQuery);

(function ($) {
    "use strict";

    $(document).ready(function () {
        qodefMobileHeaderAppearance.init();
    });

    /*
     **	Init mobile header functionality
     */
    var qodefMobileHeaderAppearance = {
        init: function () {
            if (qodefCore.body.hasClass('qodef-mobile-header-appearance--sticky')) {

                var docYScroll1 = qodefCore.scroll,
                    displayAmount = qodefGlobal.vars.mobileHeaderHeight + qodefGlobal.vars.adminBarHeight,
                    $pageOuter = $('#qodef-page-outer');

                qodefMobileHeaderAppearance.showHideMobileHeader(docYScroll1, displayAmount, $pageOuter);
                $(window).scroll(function () {
                    qodefMobileHeaderAppearance.showHideMobileHeader(docYScroll1, displayAmount, $pageOuter);
                    docYScroll1 = qodefCore.scroll;
                });

                $(window).resize(function () {
                    $pageOuter.css('padding-top', 0);
                    qodefMobileHeaderAppearance.showHideMobileHeader(docYScroll1, displayAmount, $pageOuter);
                });
            }
        },
        showHideMobileHeader: function(docYScroll1, displayAmount,$pageOuter){
            if(qodefCore.windowWidth <= 1024) {
                if (qodefCore.scroll > displayAmount * 2) {
                    //set header to be fixed
                    qodefCore.body.addClass('qodef-mobile-header--sticky');

                    //add transition to it
                    setTimeout(function () {
                        qodefCore.body.addClass('qodef-mobile-header--sticky-animation');
                    }, 300); //300 is duration of sticky header animation

                    //add padding to content so there is no 'jumping'
                    $pageOuter.css('padding-top', qodefGlobal.vars.mobileHeaderHeight);
                } else {
                    //unset fixed header
                    qodefCore.body.removeClass('qodef-mobile-header--sticky');

                    //remove transition
                    setTimeout(function () {
                        qodefCore.body.removeClass('qodef-mobile-header--sticky-animation');
                    }, 300); //300 is duration of sticky header animation

                    //remove padding from content since header is not fixed anymore
                    $pageOuter.css('padding-top', 0);
                }

                if ((qodefCore.scroll > docYScroll1 && qodefCore.scroll > displayAmount) || (qodefCore.scroll < displayAmount * 3)) {
                    //show sticky header
                    qodefCore.body.removeClass('qodef-mobile-header--sticky-display');
                } else {
                    //hide sticky header
                    qodefCore.body.addClass('qodef-mobile-header--sticky-display');
                }
            }
        }
    };

})(jQuery);
(function ($) {
	"use strict";

	$(document).ready(function () {
		qodefNavMenu.init();
	});

	var qodefNavMenu = {
		init: function () {
			qodefNavMenu.dropdownBehavior();
			qodefNavMenu.wideDropdownPosition();
			qodefNavMenu.dropdownPosition();
		},
		dropdownBehavior: function () {
			var $menuItems = $('.qodef-header-navigation > ul > li');
			
			$menuItems.each(function () {
				var $thisItem = $(this);
				
				if ($thisItem.find('.qodef-drop-down-second').length) {
					$thisItem.waitForImages(function () {
						var $dropdownHolder = $thisItem.find('.qodef-drop-down-second'),
							$dropdownMenuItem = $dropdownHolder.find('.qodef-drop-down-second-inner ul'),
							dropDownHolderHeight = $dropdownMenuItem.outerHeight();
						
						if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
							$thisItem.on("touchstart mouseenter", function () {
								$dropdownHolder.css({
									'height': dropDownHolderHeight,
									'overflow': 'visible',
									'visibility': 'visible',
									'opacity': '1'
								});
							}).on("mouseleave", function () {
								$dropdownHolder.css({
									'height': '0px',
									'overflow': 'hidden',
									'visibility': 'hidden',
									'opacity': '0'
								});
							});
						} else {
							if (qodefCore.body.hasClass('qodef-drop-down-second--animate-height')) {
								var animateConfig = {
									interval: 0,
									over: function () {
										setTimeout(function () {
											$dropdownHolder.addClass('qodef-drop-down--start').css({
												'visibility': 'visible',
												'height': '0',
												'opacity': '1'
											});
											$dropdownHolder.stop().animate({
												'height': dropDownHolderHeight
											}, 400, 'easeInOutQuint', function () {
												$dropdownHolder.css('overflow', 'visible');
											});
										}, 100);
									},
									timeout: 100,
									out: function () {
										$dropdownHolder.stop().animate({
											'height': '0',
											'opacity': 0
										}, 100, function () {
											$dropdownHolder.css({
												'overflow': 'hidden',
												'visibility': 'hidden'
											});
										});
										
										$dropdownHolder.removeClass('qodef-drop-down--start');
									}
								};
								
								$thisItem.hoverIntent(animateConfig);
							} else {
								var config = {
									interval: 0,
									over: function () {
										setTimeout(function () {
											$dropdownHolder.addClass('qodef-drop-down--start').stop().css({'height': dropDownHolderHeight});
										}, 150);
									},
									timeout: 150,
									out: function () {
										$dropdownHolder.stop().css({'height': '0'}).removeClass('qodef-drop-down--start');
									}
								};
								
								$thisItem.hoverIntent(config);
							}
						}
					});
				}
			});
		},
		wideDropdownPosition: function () {
			var $menuItems = $(".qodef-header-navigation > ul > li.qodef-menu-item--wide");

			if ($menuItems.length) {
				$menuItems.each(function () {
					var $menuItem = $(this);
					var $menuItemSubMenu = $menuItem.find('.qodef-drop-down-second');

					if ($menuItemSubMenu.length) {
						$menuItemSubMenu.css('left', 0);

						var leftPosition = $menuItemSubMenu.offset().left;

						if (qodefCore.body.hasClass('qodef--boxed')) {
							//boxed layout case
							var boxedWidth = $('.qodef--boxed #qodef-page-wrapper').outerWidth();
							leftPosition = leftPosition - (qodefCore.windowWidth - boxedWidth) / 2;
							$menuItemSubMenu.css({'left': -leftPosition, 'width': boxedWidth});

						} else if (qodefCore.body.hasClass('qodef-drop-down-second--full-width')) {
							//wide dropdown full width case
							$menuItemSubMenu.css({'left': -leftPosition});
						}
						else {
							//wide dropdown in grid case
							$menuItemSubMenu.css({'left': -leftPosition + (qodefCore.windowWidth - $menuItemSubMenu.width()) / 2});
						}
					}
				});
			}
		},
		dropdownPosition: function () {
			var $menuItems = $('.qodef-header-navigation > ul > li.qodef-menu-item--narrow.menu-item-has-children');

			if ($menuItems.length) {
				$menuItems.each(function () {
					var $thisItem = $(this),
						menuItemPosition = $thisItem.offset().left,
						$dropdownHolder = $thisItem.find('.qodef-drop-down-second'),
						$dropdownMenuItem = $dropdownHolder.find('.qodef-drop-down-second-inner ul'),
						dropdownMenuWidth = $dropdownMenuItem.outerWidth(),
						menuItemFromLeft = $(window).width() - menuItemPosition;

                    if (qodef.body.hasClass('qodef--boxed')) {
                        //boxed layout case
                        var boxedWidth = $('.qodef--boxed #qodef-page-wrapper').outerWidth();
                        menuItemFromLeft = boxedWidth - menuItemPosition;
                    }

					var dropDownMenuFromLeft;

					if ($thisItem.find('li.menu-item-has-children').length > 0) {
						dropDownMenuFromLeft = menuItemFromLeft - dropdownMenuWidth;
					}

					$dropdownHolder.removeClass('qodef-drop-down--right');
					$dropdownMenuItem.removeClass('qodef-drop-down--right');
					if (menuItemFromLeft < dropdownMenuWidth || dropDownMenuFromLeft < dropdownMenuWidth) {
						$dropdownHolder.addClass('qodef-drop-down--right');
						$dropdownMenuItem.addClass('qodef-drop-down--right');
					}
				});
			}
		}
	};

})(jQuery);
(function ($) {
    "use strict";

    $(window).on('load', function () {
        qodefParallaxBackground.init();
    });

    /**
     * Init global parallax background functionality
     */
    var qodefParallaxBackground = {
        init: function (settings) {
            this.$sections = $('.qodef-parallax');

            // Allow overriding the default config
            $.extend(this.$sections, settings);

            var isSupported = !qodefCore.html.hasClass('touchevents') && !qodefCore.body.hasClass('qodef-browser--edge') && !qodefCore.body.hasClass('qodef-browser--ms-explorer');

            if (this.$sections.length && isSupported) {
                this.$sections.each(function () {
                    qodefParallaxBackground.ready($(this));
                });
            }
        },
        ready: function ($section) {
            $section.$imgHolder = $section.find('.qodef-parallax-img-holder');
            $section.$imgWrapper = $section.find('.qodef-parallax-img-wrapper');
            $section.$img = $section.find('img.qodef-parallax-img');

            var h = $section.outerHeight(),
                imgWrapperH = $section.$imgWrapper.height();

            $section.movement = 300 * (imgWrapperH - h) / h / 2; //percentage (divided by 2 due to absolute img centering in CSS)

            $section.buffer = window.pageYOffset;
            $section.scrollBuffer = null;


            //calc and init loop
            requestAnimationFrame(function () {
				$section.$imgHolder.animate({opacity: 1}, 100);
                qodefParallaxBackground.calc($section);
                qodefParallaxBackground.loop($section);
            });

            //recalc
            $(window).on('resize', function () {
                qodefParallaxBackground.calc($section);
            });
        },
        calc: function ($section) {
            var wH = $section.$imgWrapper.height(),
                wW = $section.$imgWrapper.width();

            if ($section.$img.width() < wW) {
                $section.$img.css({
                    'width': '100%',
                    'height': 'auto'
                });
            }

            if ($section.$img.height() < wH) {
                $section.$img.css({
                    'height': '100%',
                    'width': 'auto',
                    'max-width': 'unset'
                });
            }
        },
        loop: function ($section) {
            if ($section.scrollBuffer === Math.round(window.pageYOffset)) {
                requestAnimationFrame(function () {
                    qodefParallaxBackground.loop($section);
                }); //repeat loop
                return false; //same scroll value, do nothing
            } else {
                $section.scrollBuffer = Math.round(window.pageYOffset);
            }

            var wH = window.outerHeight,
                sTop = $section.offset().top,
                sH = $section.outerHeight();

            if ($section.scrollBuffer + wH * 1.2 > sTop && $section.scrollBuffer < sTop + sH) {
                var delta = (Math.abs($section.scrollBuffer + wH - sTop) / (wH + sH)).toFixed(4), //coeff between 0 and 1 based on scroll amount
                    yVal = (delta * $section.movement).toFixed(4);

                if ($section.buffer !== delta) {
                    $section.$imgWrapper.css('transform', 'translate3d(0,' + yVal + '%, 0)');
                }

                $section.buffer = delta;
            }

            requestAnimationFrame(function () {
                qodefParallaxBackground.loop($section);
            }); //repeat loop
        }
    };

    qodefCore.qodefParallaxBackground = qodefParallaxBackground;

})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefReview.init();
	});
	
	var qodefReview = {
		init: function () {
			var ratingHolder = $('#qodef-page-comments-form .qodef-rating-inner');
			
			var addActive = function (stars, ratingValue) {
				for (var i = 0; i < stars.length; i++) {
					var star = stars[i];
					if (i < ratingValue) {
						$(star).addClass('active');
					} else {
						$(star).removeClass('active');
					}
				}
			};
			
			ratingHolder.each(function () {
				var thisHolder = $(this),
					ratingInput = thisHolder.find('.qodef-rating'),
					ratingValue = ratingInput.val(),
					stars = thisHolder.find('.qodef-star-rating');
				
				addActive(stars, ratingValue);
				
				stars.on('click', function () {
					ratingInput.val($(this).data('value')).trigger('change');
				});
				
				ratingInput.change(function () {
					ratingValue = ratingInput.val();
					addActive(stars, ratingValue);
				});
			});
		}
	}
})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefSideArea.init();
	});
	
	var qodefSideArea = {
		init: function () {
			var $sideAreaOpener = $('a.qodef-side-area-opener'),
				$sideAreaClose = $('#qodef-side-area-close'),
				$sideArea = $('#qodef-side-area');
				qodefSideArea.openerHoverColor($sideAreaOpener);
			// Open Side Area
			$sideAreaOpener.on('click', function (e) {
				e.preventDefault();
				
				if (!qodefCore.body.hasClass('qodef-side-area--opened')) {
					qodefSideArea.openSideArea();
					
					$(document).keyup(function (e) {
						if (e.keyCode === 27) {
							qodefSideArea.closeSideArea();
						}
					});
				} else {
					qodefSideArea.closeSideArea();
				}
			});
			
			$sideAreaClose.on('click', function (e) {
				e.preventDefault();
				qodefSideArea.closeSideArea();
			});
			
			if ($sideArea.length && typeof qodefCore.qodefPerfectScrollbar === 'object') {
				qodefCore.qodefPerfectScrollbar.init($sideArea);
			}
		},
		openSideArea: function () {
			var $wrapper = $('#qodef-page-wrapper');
			var currentScroll = $(window).scrollTop();

			$('.qodef-side-area-cover').remove();
			$wrapper.prepend('<div class="qodef-side-area-cover"/>');
			qodefCore.body.removeClass('qodef-side-area-animate--out').addClass('qodef-side-area--opened qodef-side-area-animate--in');

			$('.qodef-side-area-cover').on('click', function (e) {
				e.preventDefault();
				qodefSideArea.closeSideArea();
			});

			$(window).scroll(function () {
				if (Math.abs(qodefCore.scroll - currentScroll) > 400) {
					qodefSideArea.closeSideArea();
				}
			});

		},
		closeSideArea: function () {
			qodefCore.body.removeClass('qodef-side-area--opened qodef-side-area-animate--in').addClass('qodef-side-area-animate--out');
		},
		openerHoverColor: function ($opener) {
			if (typeof $opener.data('hover-color') !== 'undefined') {
				var hoverColor = $opener.data('hover-color');
				var originalColor = $opener.css('color');
				
				$opener.on('mouseenter', function () {
					$opener.css('color', hoverColor);
				}).on('mouseleave', function () {
					$opener.css('color', originalColor);
				});
			}
		}
	};
	
})(jQuery);

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
(function ($) {
    "use strict";

    $(window).on('load', function () {
        qodefSubscribeModal.init();
    });

    var qodefSubscribeModal = {
        init: function () {
            this.holder = $('#qodef-subscribe-popup-modal');

            if (this.holder.length) {
                var $preventHolder = this.holder.find('.qodef-sp-prevent'),
                    $modalClose = $('.qodef-sp-close'),
                    disabledPopup = 'no';

                if ($preventHolder.length) {
                    var isLocalStorage = this.holder.hasClass('qodef-sp-prevent-cookies'),
                        $preventInput = $preventHolder.find('.qodef-sp-prevent-input'),
                        preventValue = $preventInput.data('value');

                    if (isLocalStorage) {
                        disabledPopup = localStorage.getItem('disabledPopup');
                        sessionStorage.removeItem('disabledPopup');
                    } else {
                        disabledPopup = sessionStorage.getItem('disabledPopup');
                        localStorage.removeItem('disabledPopup');
                    }

                    $preventHolder.children().on('click', function (e) {
                        if (preventValue !== 'yes') {
                            preventValue = 'yes';
                            $preventInput.addClass('qodef-sp-prevent-clicked').data('value', 'yes');
                        } else {
                            preventValue = 'no';
                            $preventInput.removeClass('qodef-sp-prevent-clicked').data('value', 'no');
                        }

                        if (preventValue === 'yes') {
                            if (isLocalStorage) {
                                localStorage.setItem('disabledPopup', 'yes');
                            } else {
                                sessionStorage.setItem('disabledPopup', 'yes');
                            }
                        } else {
                            if (isLocalStorage) {
                                localStorage.setItem('disabledPopup', 'no');
                            } else {
                                sessionStorage.setItem('disabledPopup', 'no');
                            }
                        }
                    });
                }

                if (disabledPopup !== 'yes') {
                    if (qodefCore.body.hasClass('qodef-sp-opened')) {
                        qodefSubscribeModal.handleClassAndScroll('remove');
                    } else {
                        qodefSubscribeModal.handleClassAndScroll('add');
                    }

                    $modalClose.on('click', function (e) {
                        e.preventDefault();

                        qodefSubscribeModal.handleClassAndScroll('remove');
                    });

                    // Close on escape
                    $(document).keyup(function (e) {
                        if (e.keyCode === 27) { // KeyCode for ESC button is 27
                            qodefSubscribeModal.handleClassAndScroll('remove');
                        }
                    });
                }
            }
        },

        handleClassAndScroll: function (option) {
            if (option === 'remove') {
                qodefCore.body.removeClass('qodef-sp-opened');
                qodefCore.qodefScroll.enable();
            }
            if (option === 'add') {
                qodefCore.body.addClass('qodef-sp-opened');
                qodefCore.qodefScroll.disable();
            }
        },
    };

})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefWishlist.init();
	});
	
	/**
	 * Function object that represents wishlist area popup.
	 * @returns {{init: Function}}
	 */
	var qodefWishlist = {
		init: function () {
			var $wishlistLink = $('.qodef-wishlist .qodef-m-link');
			
			if ($wishlistLink.length) {
				$wishlistLink.each(function () {
					var $thisWishlistLink = $(this),
						wishlistIconHTML = $thisWishlistLink.html(),
						$responseMessage = $thisWishlistLink.siblings('.qodef-m-response');
					
					$thisWishlistLink.off().on('click', function (e) {
						e.preventDefault();
						
						if (qodefCore.body.hasClass('logged-in')) {
							var itemID = $thisWishlistLink.data('id');
							
							if (itemID !== 'undefined' && !$thisWishlistLink.hasClass('qodef--added')) {
								$thisWishlistLink.html('<span class="fa fa-spinner fa-spin" aria-hidden="true"></span>');
								
								var wishlistData = {
									type: 'add',
									itemID: itemID
								};
								
								$.ajax({
									type: "POST",
									url: qodefGlobal.vars.restUrl + qodefGlobal.vars.wishlistRestRoute,
									data: {
										options: wishlistData
									},
									beforeSend: function (request) {
										request.setRequestHeader('X-WP-Nonce', qodefGlobal.vars.restNonce);
									},
									success: function (response) {
										
										if (response.status === 'success') {
											$thisWishlistLink.addClass('qodef--added');
											$responseMessage.html(response.message).addClass('qodef--show').fadeIn(200);
											
											$(document).trigger('etchy_core_wishlist_item_is_added', [itemID, response.data.user_id]);
										} else {
											$responseMessage.html(response.message).addClass('qodef--show').fadeIn(200);
										}
										
										setTimeout(function () {
											$thisWishlistLink.html(wishlistIconHTML);
											
											var $wishlistTitle = $thisWishlistLink.find('.qodef-m-link-label');
											
											if ($wishlistTitle.length) {
												$wishlistTitle.text($wishlistTitle.data('added-title'));
											}
											
											$responseMessage.fadeOut(300).removeClass('qodef--show').empty();
										}, 800);
									}
								});
							}
						} else {
							// Trigger event.
							$(document.body).trigger('etchy_membership_trigger_login_modal');
						}
					});
				});
			}
		}
	};
	
	$(document).on('etchy_core_wishlist_item_is_removed', function (e, removedItemID) {
		var $wishlistLink = $('.qodef-wishlist .qodef-m-link');
		
		if ($wishlistLink.length) {
			$wishlistLink.each(function(){
				var $thisWishlistLink = $(this),
					$wishlistTitle = $thisWishlistLink.find('.qodef-m-link-label');
				
				if ($thisWishlistLink.data('id') === removedItemID && $thisWishlistLink.hasClass('qodef--added')) {
					$thisWishlistLink.removeClass('qodef--added');
					
					if ($wishlistTitle.length) {
						$wishlistTitle.text($wishlistTitle.data('title'));
					}
				}
			});
		}
	});
	
})(jQuery);
(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_accordion = {};

	$(document).ready(function () {
		qodefAccordion.init();
	});
	
	var qodefAccordion = {
		init: function () {
			this.holder = $('.qodef-accordion');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this);
					
					if ($thisHolder.hasClass('qodef-behavior--accordion')) {
						qodefAccordion.initAccordion($thisHolder);
					}
					
					if ($thisHolder.hasClass('qodef-behavior--toggle')) {
						qodefAccordion.initToggle($thisHolder);
					}
					
					$thisHolder.addClass('qodef--init');
				});
			}
		},
		initAccordion: function ($accordion) {
			$accordion.accordion({
				animate: "swing",
				collapsible: true,
				active: 0,
				icons: "",
				heightStyle: "content"
			});
		},
		initToggle: function ($toggle) {
			var $toggleAccordionTitle = $toggle.find('.qodef-accordion-title'),
				$toggleAccordionContent = $toggleAccordionTitle.next();
			
			$toggle.addClass("accordion ui-accordion ui-accordion-icons ui-widget ui-helper-reset");
			$toggleAccordionTitle.addClass("ui-accordion-header ui-state-default ui-corner-top ui-corner-bottom");
			$toggleAccordionContent.addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").hide();
			
			$toggleAccordionTitle.each(function () {
				var $thisTitle = $(this);
				
				$thisTitle.hover(function () {
					$thisTitle.toggleClass("ui-state-hover");
				});
				
				$thisTitle.on('click', function () {
					$thisTitle.toggleClass('ui-accordion-header-active ui-state-active ui-state-default ui-corner-bottom');
					$thisTitle.next().toggleClass('ui-accordion-content-active').slideToggle(400);
				});
			});
		}
	};

	qodefCore.shortcodes.etchy_core_accordion.qodefAccordion = qodefAccordion;

})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefAuthorListPagination.init();
	});
	
	$(window).scroll(function () {
		qodefAuthorListPagination.scroll();
	});
	
	$(document).on('etchy_core_trigger_author_load_more', function (e, $holder, nextPage) {
		qodefAuthorListPagination.triggerLoadMore($holder, nextPage);
	});
	
	/*
	 **	Init pagination functionality
	 */
	var qodefAuthorListPagination = {
		init: function (settings) {
			this.holder = $('.qodef-author-pagination--on');
			
			// Allow overriding the default config
			$.extend(this.holder, settings);
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $holder = $(this);
					
					qodefAuthorListPagination.initPaginationType($holder);
				});
			}
		},
		scroll: function (settings) {
			this.holder = $('.qodef-author-pagination--on');
			
			// Allow overriding the default config
			$.extend(this.holder, settings);
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $holder = $(this);
					
					if ($holder.hasClass('qodef-pagination-type--infinite-scroll')) {
						qodefAuthorListPagination.initInfiniteScroll($holder);
					}
				});
			}
		},
		initPaginationType: function ($holder) {
			if ($holder.hasClass('qodef-pagination-type--standard')) {
				qodefAuthorListPagination.initStandard($holder);
			} else if ($holder.hasClass('qodef-pagination-type--load-more')) {
				qodefAuthorListPagination.initLoadMore($holder);
			} else if ($holder.hasClass('qodef-pagination-type--infinite-scroll')) {
				qodefAuthorListPagination.initInfiniteScroll($holder);
			}
		},
		initStandard: function ($holder) {
			var $paginationItems = $holder.find('.qodef-m-pagination-items');
			
			if ($paginationItems.length) {
				var options = $holder.data('options');
				
				$paginationItems.children().each(function () {
					var $thisItem = $(this),
						$itemLink = $thisItem.children('a');
					
					qodefAuthorListPagination.changeStandardState($holder, options.max_num_pages, 1);
					
					$itemLink.on('click', function (e) {
						e.preventDefault();
						
						if (!$thisItem.hasClass('qodef--active')) {
							qodefAuthorListPagination.getNewPosts($holder, $itemLink.data('paged'));
						}
					});
				});
			}
		},
		changeStandardState: function ($holder, max_num_pages, nextPage) {
			if ($holder.hasClass('qodef-pagination-type--standard')) {
				var $paginationNav = $holder.find('.qodef-m-pagination-items'),
					$numericItem = $paginationNav.children('.qodef--number'),
					$prevItem = $paginationNav.children('.qodef--prev'),
					$nextItem = $paginationNav.children('.qodef--next');
				
				$numericItem.removeClass('qodef--active').eq(nextPage - 1).addClass('qodef--active');
				
				$prevItem.children().data('paged', nextPage - 1);
				
				if (nextPage > 1) {
					$prevItem.show();
				} else {
					$prevItem.hide();
				}
				
				$nextItem.children().data('paged', nextPage + 1);
				
				if (nextPage === max_num_pages) {
					$nextItem.hide();
				} else {
					$nextItem.show();
				}
			}
		},
		initLoadMore: function ($holder) {
			var $loadMoreButton = $holder.find('.qodef-load-more-button');
			
			$loadMoreButton.on('click', function (e) {
				e.preventDefault();
				
				qodefAuthorListPagination.getNewPosts($holder);
			});
		},
		triggerLoadMore: function ($holder, nextPage) {
			qodefAuthorListPagination.getNewPosts($holder, nextPage);
		},
		hideLoadMoreButton: function ($holder, options) {
			if ($holder.hasClass('qodef-pagination-type--load-more') && options.next_page > options.max_num_pages) {
				$holder.find('.qodef-load-more-button').hide();
			}
		},
		initInfiniteScroll: function ($holder) {
			var holderEndPosition = $holder.outerHeight() + $holder.offset().top,
				scrollPosition = qodefCore.scroll + qodefCore.windowHeight,
				options = $holder.data('options');
			
			if (!$holder.hasClass('qodef--loading') && scrollPosition > holderEndPosition  && options.max_num_pages >= options.next_page) {
				qodefAuthorListPagination.getNewPosts($holder);
			}
		},
		getNewPosts: function ($holder, nextPage) {
			$holder.addClass('qodef--loading');
			
			var $itemsHolder = $holder.children('.qodef-grid-inner');
			var options = $holder.data('options');
			
			qodefAuthorListPagination.setNextPageValue(options, nextPage, false);
			
			$.ajax({
				type: "GET",
				url: qodefGlobal.vars.restUrl + qodefGlobal.vars.authorPaginationRestRoute,
				data: {
					options: options
				},
				beforeSend: function( request ) {
					request.setRequestHeader( 'X-WP-Nonce', qodefGlobal.vars.restNonce );
				},
				success: function (response) {
					
					if (response.status === 'success') {
						qodefAuthorListPagination.setNextPageValue(options, nextPage, true);
						qodefAuthorListPagination.changeStandardState($holder, options.max_num_pages, nextPage);
						
						$itemsHolder.waitForImages(function () {
							qodefAuthorListPagination.addPosts($itemsHolder, response.data, nextPage);
							
							qodefCore.body.trigger('etchy_core_trigger_get_new_authors', [$holder]);
						});
						
						qodefAuthorListPagination.hideLoadMoreButton($holder, options);
					} else {
						console.log(response.message);
					}
				},
				complete: function () {
					$holder.removeClass('qodef--loading');
				}
			});
		},
		setNextPageValue: function (options, nextPage, ajaxTrigger) {
			if (typeof nextPage !== 'undefined' && nextPage !== '' && !ajaxTrigger) {
				options.next_page = nextPage;
			} else if (ajaxTrigger) {
				options.next_page = parseInt(options.next_page, 10) + 1;
			}
		},
		addPosts: function ($itemsHolder, newItems, nextPage) {
			if (typeof nextPage !== 'undefined' && nextPage !== '') {
				$itemsHolder.html(newItems);
			} else {
				$itemsHolder.append(newItems);
			}
		}
	};
	
})(jQuery);
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
(function ($) {
	"use strict";
	
	qodefCore.shortcodes.etchy_core_item_check = {};
	
	$(document).ready(function () {
		qodefItemCheckList.init();
	});
	
	var qodefItemCheckList = {
		init: function () {
			var $holder = $('.qodef-check-list');
			if ($holder.length) {
				$holder.each(function () {
					var checkList = $(this),
						totalPriceHolder = checkList.find('.qodef-e-total-price'),
						totalPrice = parseFloat(totalPriceHolder.text()),
						items = checkList.find('.qodef-m-item.qodef-item-price');
					
					if (isNaN(totalPrice)) {
						totalPrice = 0;
					}
					
					items.each(function () {
						var currentItem = $(this),
							currentCheckbox = currentItem.find('input[type="checkbox"]'),
							currentPrice = 0;
						
						if (typeof currentItem.data('price') !== 'undefined' && currentItem.data('price') !== false) {
							currentPrice = parseFloat(currentItem.data('price'));
							
							if (isNaN(currentPrice)) {
								currentPrice = 0;
							}
						}
						
						currentCheckbox.change(function () {
							if ($(this).is(':checked')) {
								totalPrice += currentPrice; //+
							} else {
								totalPrice -= currentPrice; // -
							}
							totalPriceHolder.text(totalPrice);
						});
					})
				})
			}
		}
	};
	qodefCore.shortcodes.etchy_core_item_check.qodefItemCheckList = qodefItemCheckList;
	
})(jQuery);
(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_countdown = {};

	$(document).ready(function () {
		qodefCountdown.init();
	});
	
	var qodefCountdown = {
		init: function () {
			this.countdowns = $('.qodef-countdown');
			
			if (this.countdowns.length) {
				this.countdowns.each(function () {
					var $thisCountdown = $(this),
						$countdownElement = $thisCountdown.find('.qodef-m-date'),
						options = qodefCountdown.generateOptions($thisCountdown);
					
					qodefCountdown.initCountdown($countdownElement, options);
				});
			}
		},
		generateOptions: function($countdown) {
			var options = {};
			options.date = typeof $countdown.data('date') !== 'undefined' ? $countdown.data('date') : null;
			
			options.weekLabel = typeof $countdown.data('week-label') !== 'undefined' ? $countdown.data('week-label') : '';
			options.weekLabelPlural = typeof $countdown.data('week-label-plural') !== 'undefined' ? $countdown.data('week-label-plural') : '';
			
			options.dayLabel = typeof $countdown.data('day-label') !== 'undefined' ? $countdown.data('day-label') : '';
			options.dayLabelPlural = typeof $countdown.data('day-label-plural') !== 'undefined' ? $countdown.data('day-label-plural') : '';
			
			options.hourLabel = typeof $countdown.data('hour-label') !== 'undefined' ? $countdown.data('hour-label') : '';
			options.hourLabelPlural = typeof $countdown.data('hour-label-plural') !== 'undefined' ? $countdown.data('hour-label-plural') : '';
			
			options.minuteLabel = typeof $countdown.data('minute-label') !== 'undefined' ? $countdown.data('minute-label') : '';
			options.minuteLabelPlural = typeof $countdown.data('minute-label-plural') !== 'undefined' ? $countdown.data('minute-label-plural') : '';
			
			options.secondLabel = typeof $countdown.data('second-label') !== 'undefined' ? $countdown.data('second-label') : '';
			options.secondLabelPlural = typeof $countdown.data('second-label-plural') !== 'undefined' ? $countdown.data('second-label-plural') : '';
			
			return options;
		},
		initCountdown: function ($countdownElement, options) {
			var $weekHTML = '<span class="qodef-digit-wrapper"><span class="qodef-digit">%w</span><span class="qodef-label">' + '%!w:' + options.weekLabel + ',' + options.weekLabelPlural + ';</span></span>';
			var $dayHTML = '<span class="qodef-digit-wrapper"><span class="qodef-digit">%d</span><span class="qodef-label">' + '%!d:' + options.dayLabel + ',' + options.dayLabelPlural + ';</span></span>';
			var $hourHTML = '<span class="qodef-digit-wrapper"><span class="qodef-digit">%H</span><span class="qodef-label">' + '%!H:' + options.hourLabel + ',' + options.hourLabelPlural + ';</span></span>';
			var $minuteHTML = '<span class="qodef-digit-wrapper"><span class="qodef-digit">%M</span><span class="qodef-label">' + '%!M:' + options.minuteLabel + ',' + options.minuteLabelPlural + ';</span></span>';
			var $secondHTML = '<span class="qodef-digit-wrapper"><span class="qodef-digit">%S</span><span class="qodef-label">' + '%!S:' + options.secondLabel + ',' + options.secondLabelPlural + ';</span></span>';
			
			$countdownElement.countdown(options.date, function(event) {
				$(this).html(event.strftime($weekHTML + $dayHTML + $hourHTML + $minuteHTML + $secondHTML));
			});
		}
	};

	qodefCore.shortcodes.etchy_core_countdown.qodefCountdown  = qodefCountdown;


})(jQuery);
(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_counter = {};

	$(document).ready(function () {
		qodefCounter.init();
	});
	
	var qodefCounter = {
		init: function () {
			this.counters = $('.qodef-counter');
			
			if (this.counters.length) {
				this.counters.each(function () {
					var $thisCounter = $(this),
						$counterElement = $thisCounter.find('.qodef-m-digit'),
						options = qodefCounter.generateOptions($thisCounter);
					
					qodefCounter.counterScript($counterElement, options);
				});
			}
		},
		generateOptions: function($counter) {
			var options = {};
			options.start = typeof $counter.data('start-digit') !== 'undefined' && $counter.data('start-digit') !== '' ? $counter.data('start-digit') : 0;
			options.end = typeof $counter.data('end-digit') !== 'undefined' && $counter.data('end-digit') !== '' ? $counter.data('end-digit') : null;
			options.step = typeof $counter.data('step-digit') !== 'undefined' && $counter.data('step-digit') !== '' ? $counter.data('step-digit') : 1;
			options.delay = typeof $counter.data('step-delay') !== 'undefined' && $counter.data('step-delay') !== '' ? parseInt( $counter.data('step-delay'), 10 ) : 100;
			options.txt = typeof $counter.data('digit-label') !== 'undefined' && $counter.data('digit-label') !== '' ? $counter.data('digit-label') : '';
			
			return options;
		},
		counterScript: function ($counterElement, options) {
			var defaults = {
				start: 0,
				end: null,
				step: 1,
				delay: 100,
				txt: ""
			};
			
			var settings = $.extend(defaults, options || {});
			var nb_start = settings.start;
			var nb_end = settings.end;
			
			$counterElement.text(nb_start + settings.txt);
			
			var counter = function() {
				// Definition of conditions of arrest
				if (nb_end !== null && nb_start >= nb_end) {
					return;
				}
				// incrementation
				nb_start = nb_start + settings.step;
				
				if( nb_start >= nb_end ) {
					nb_start = nb_end;
				}
				// display
				$counterElement.text(nb_start + settings.txt);
			};
			
			// Timer
			// Launches every "settings.delay"
			setInterval(counter, settings.delay);
		}
	};

	qodefCore.shortcodes.etchy_core_counter.qodefCounter  = qodefCounter;

})(jQuery);
(function ($) {
	'use strict';

	qodefCore.shortcodes.etchy_core_frame_slider = {};

	$(document).ready(function () {
		qodefFrameSlider.init();
	});
	
	var qodefFrameSlider = {
		init: function () {
			this.holder = $('.qodef-frame-slider-holder');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this);
					
					qodefFrameSlider.createSlider($thisHolder);
				});
			}
		},
		
		createSlider: function ($holder) {
			var $swiperHolder = $holder.find('.qodef-m-swiper'),
				$sliderHolder = $holder.find('.qodef-m-items'),
				$pagination = $holder.find('.swiper-pagination');
			
			var $swiper = new Swiper($swiperHolder, {
				slidesPerView: 'auto',
				centeredSlides: true,
				spaceBetween: 0,
				autoplay: true,
				loop: true,
				speed: 800,
				pagination: {
					el: $pagination,
					type: 'bullets',
					clickable: true
				},
				on: {
					init: function () {
						setTimeout(function () {
                            $sliderHolder.addClass('qodef-swiper--initialized');
                        }, 1500);
					}
				}
			});
		}
	};

	qodefCore.shortcodes.etchy_core_frame_slider.qodefFrameSlider  = qodefFrameSlider;

})(jQuery);
(function ($) {
	"use strict";
	
	qodefCore.shortcodes.etchy_core_google_map = {};
	
	$(document).ready(function () {
		qodefGoogleMap.init();
	});
	
	var qodefGoogleMap = {
		init: function () {
			this.holder = $('.qodef-google-map');
			
			if (this.holder.length) {
				this.holder.each(function () {
					if (typeof window.qodefGoogleMap !== 'undefined') {
						window.qodefGoogleMap.initMap($(this).find('.qodef-m-map'));
					}
				});
			}
		}
	};
	
	qodefCore.shortcodes.etchy_core_google_map.qodefGoogleMap = qodefGoogleMap;
	
})(jQuery);
(function ($) {
    "use strict";

	qodefCore.shortcodes.etchy_core_icon = {};

    $(document).ready(function () {
        qodefIcon.init();
    });

    var qodefIcon = {
        init: function () {
            this.icons = $('.qodef-icon-holder');

            if (this.icons.length) {
                this.icons.each(function () {
                    var $thisIcon = $(this);

                    qodefIcon.iconHoverColor($thisIcon);
                    qodefIcon.iconHoverBgColor($thisIcon);
                    qodefIcon.iconHoverBorderColor($thisIcon);
                });
            }
        },
        iconHoverColor: function ($iconHolder) {
            if (typeof $iconHolder.data('hover-color') !== 'undefined') {
                var spanHolder = $iconHolder.find('span');
                var originalColor = spanHolder.css('color');
                var hoverColor = $iconHolder.data('hover-color');

                $iconHolder.on('mouseenter', function () {
                    qodefIcon.changeColor(spanHolder, 'color', hoverColor);
                });
                $iconHolder.on('mouseleave', function () {
                    qodefIcon.changeColor(spanHolder, 'color', originalColor);
                });
            }
        },
        iconHoverBgColor: function ($iconHolder) {
            if (typeof $iconHolder.data('hover-background-color') !== 'undefined') {
                var hoverBackgroundColor = $iconHolder.data('hover-background-color');
                var originalBackgroundColor = $iconHolder.css('background-color');

                $iconHolder.on('mouseenter', function () {
                    qodefIcon.changeColor($iconHolder, 'background-color', hoverBackgroundColor);
                });
                $iconHolder.on('mouseleave', function () {
                    qodefIcon.changeColor($iconHolder, 'background-color', originalBackgroundColor);
                });
            }
        },
        iconHoverBorderColor: function ($iconHolder) {
            if (typeof $iconHolder.data('hover-border-color') !== 'undefined') {
                var hoverBorderColor = $iconHolder.data('hover-border-color');
                var originalBorderColor = $iconHolder.css('borderTopColor');

                $iconHolder.on('mouseenter', function () {
                    qodefIcon.changeColor($iconHolder, 'border-color', hoverBorderColor);
                });
                $iconHolder.on('mouseleave', function () {
                    qodefIcon.changeColor($iconHolder, 'border-color', originalBorderColor);
                });
            }
        },
        changeColor: function (iconElement, cssProperty, color) {
            iconElement.css(cssProperty, color);
        }
    };

	qodefCore.shortcodes.etchy_core_icon.qodefIcon = qodefIcon;

})(jQuery);
(function ($) {
    "use strict";
	qodefCore.shortcodes.etchy_core_icon_with_text = {};
	qodefCore.shortcodes.etchy_core_icon_with_text.qodefAppear = qodef.qodefAppear;
})(jQuery);
(function ($) {
    "use strict";
	qodefCore.shortcodes.etchy_core_image_gallery = {};
	qodefCore.shortcodes.etchy_core_image_gallery.qodefSwiper = qodef.qodefSwiper;
	qodefCore.shortcodes.etchy_core_image_gallery.qodefMasonryLayout = qodef.qodefMasonryLayout;

})(jQuery);
(function ($) {
    "use strict";

	qodefCore.shortcodes.etchy_core_image_with_text = {};
    qodefCore.shortcodes.etchy_core_image_with_text.qodefMagnificPopup = qodef.qodefMagnificPopup;

})(jQuery);
(function ($) {
    "use strict";

	qodefCore.shortcodes.etchy_core_interactive_link_showcase = {};

})(jQuery);
(function ($) {
    "use strict";
	
	qodefCore.shortcodes.etchy_core_item_showcase = {};
	
	$(document).ready(function () {
		qodefItemShowcaseList.init();
	});
	
	var qodefItemShowcaseList = {
		init: function () {
			this.holder = $('.qodef-item-showcase');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this);
					
					$thisHolder.appear(function(){
						$thisHolder.addClass('qodef--init');
					}, {accX: 0, accY: -100});
				});
			}
		}
	};
	qodefCore.shortcodes.etchy_core_item_showcase.qodefItemShowcaseList = qodefItemShowcaseList;

})(jQuery);
(function ($) {
    "use strict";
	qodefCore.shortcodes.etchy_core_outline_text = {};
	qodefCore.shortcodes.etchy_core_outline_text.qodefAppear = qodef.qodefAppear;
	
	$(document).ready(function () {
		qodefOutlineText.init();
	});
	
	var qodefOutlineText = {
		init: function () {
			this.holder = $('.qodef-outline-text');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this),
						svgDots = '<svg class="qodef-svg-dots" xmlns="http://www.w3.org/2000/svg" width="298.479" height="218.638">' +
							'<g class="qodef-svg-dots-outer" fill="#EA5D3D" fill-rule="evenodd" clip-rule="evenodd">' +
							'<g class="qodef-svg-dots-inner-one">' +
							'<path class="qodef-svg-dots-front" d="M36.507 72.837c.805.082 1.613.16 2.418.241.881.411.984.645 1.448 1.437v.718c-1.02 1.262-2.741 3.539-5.317 2.397-3.168-1.412.03-4.047 1.451-4.793z"/>' +
							'<path class="qodef-svg-dots-back" d="M173.587 98.716c2.728.005 4.803 1.122 5.805 2.876-1.182 2.309-8.701 5.731-8.947.478 1.016-1.503 1.78-2.236 3.142-3.354z"/>' +
							'<path class="qodef-svg-dots-front" d="M44 180.656l3.146.48c2.052 2.568.718 5.022-3.39 4.794-.979-.473-1.449-.686-1.931-1.68.082-.801.161-1.596.241-2.396.647-.398 1.291-.795 1.934-1.198z"/>' +
							'<path class="qodef-svg-dots-back" d="M89.696 100.633c1.888.264 2.966.932 3.867 2.154l-.24.959c-.717 1.534-2.44 2.524-4.593 2.635-.567-.398-1.131-.798-1.693-1.196.014-2.139.63-2.842 1.45-4.073.403-.162.804-.322 1.209-.479z"/>' +
							'<path class="qodef-svg-dots-front" d="M141.917 134.896c1.878.229 2.797.829 2.901 2.871-.937 1.86-2.104 2.563-4.109 3.358-.932-.698-1.514-1.201-2.178-2.159.586-2.381 1.45-3.025 3.386-4.07z"/>' +
							'<path class="qodef-svg-dots-back" d="M113.631 139.446c2.029.051 2.9.661 4.108 1.438v.24c-.958 1.576-2.948 4.359-5.801 3.116-.909-.46-.999-.805-1.451-1.68.442-2.162 1.451-2.24 3.144-3.114z"/>' +
							'<path class="qodef-svg-dots-front" d="M125.237 180.656c2.081.528 3.671.988 4.831 3.114-.159.322-.317.64-.481.961-2.583 1.362-5.963 1.667-7.494-.961.79-1.803 1.574-2.117 3.144-3.114z"/>' +
							'<path class="qodef-svg-dots-front" d="M172.619 166.762c3.119-.14 4.194.583 5.082 2.635-.247.48-.485.957-.732 1.438-1.805.324-3.111.446-4.835 0-.237-.398-.474-.801-.721-1.198.163-.56.318-1.117.483-1.676.238-.403.484-.801.723-1.199z"/>' +
							'<path class="qodef-svg-dots-front" d="M292.536 133.696c2.84-.069 3.974.641 5.565 1.679.255.857.559 1.292.239 2.155-.979 2.057-3.669 2.322-6.041 1.678-1.036-.719-1.446-1.379-2.177-2.396.353-1.15.633-1.767 1.443-2.878.332-.078.651-.155.971-.238z"/>' +
							'<path class="qodef-svg-dots-front" d="M246.361 102.55c3.061-.106 4.292.779 5.317 2.635v.48c-1.017 2.185-2.863 3.798-6.041 3.112-.402-.479-.812-.958-1.214-1.437-.354-1.216-.058-2.267.246-3.355.566-.478 1.125-.955 1.692-1.435z"/>' +
							'<path class="qodef-svg-dots-back" d="M264.732 72.837c3.062-.117 4.408.48 5.804 1.919-.082.717-.165 1.435-.236 2.156-3.071 1.871-9.056 2.679-7.497-2.878.641-.398 1.29-.798 1.929-1.197z"/>' +
							'<path class="qodef-svg-dots-front" d="M284.804 41.69c2.511-.058 3.899.496 5.319 1.438v.24c-.065 1.983-.617 2.049-1.453 3.113-1.511.426-3.226 1.692-4.835 1.199-1.224-.225-1.019-.329-1.692-.958-.08-3.229.945-3.697 2.661-5.032z"/>' +
							'<path class="qodef-svg-dots-back" d="M220.731 44.805c1.569.281 1.815.781 2.66 1.676-.082.641-.156 1.279-.237 1.92-1.199.927-3.251 2.681-5.564 1.675-.322-.161-.643-.319-.971-.479.082-.558.166-1.118.246-1.676.796-1.973 1.987-2.215 3.866-3.116z"/>' +
							'</g>' +
							'<g class="qodef-svg-dots-inner-two">' +
							'<path class="qodef-svg-dots-front" d="M172.865 211.566c1.641.165 2.734.496 3.384 1.675.499.828.468 1.585.246 2.64-.486.477-.97.957-1.453 1.438-3.382.512-5.739-.667-4.835-4.314.483-.321.968-.641 1.453-.961.394-.157.803-.317 1.205-.478z"/>' +
							'<path class="qodef-svg-dots-front" d="M185.196 32.346c3.142-.035 4.055 1.106 5.318 2.875-.162.32-.327.638-.484.959-.779 1.604-4.646 3.341-7.257 2.156-.819-.387-.805-.39-1.207-1.197-.08-.639-.164-1.279-.237-1.917.977-1.916 1.946-1.904 3.867-2.876z"/>' +
							'<path class="qodef-svg-dots-back" d="M146.271 0c1.769.067 2.383.493 3.383 1.199v.24c.096 3.185-1.546 5.212-4.591 5.27-.484-.319-.97-.637-1.452-.958-1.089-3.371.092-4.573 2.66-5.751z"/>' +
							'<path class="qodef-svg-dots-front" d="M143.367 66.129c2.248-.083 3.363.209 4.596.958l.24 2.397c-2.164 3.738-5.619 4.198-7.252-.239.677-1.438 1.287-2.159 2.416-3.116z"/> ' +
							'<path class="qodef-svg-dots-back" d="M86.069 35.701c2.219.084 3.095.749 4.351 1.677v1.197c-1.182 1.953-3.219 3.515-6.283 2.874l-1.453-1.197c-.081-.719-.16-1.439-.239-2.154.996-1.471 1.934-1.578 3.624-2.397z"/>' +
							'<path class="qodef-svg-dots-back" d="M36.988 15.335c2.283.02 3.647.82 4.353 2.394v1.439c-.981 2.257-3.891 2.683-6.527 1.917l-.966-.959c.08-.798.161-1.599.243-2.397.67-1.44 1.59-1.61 2.897-2.394z"/> ' +
							'<path class="qodef-svg-dots-front" d="M132.487 213.241c2.841-.104 4.071.373 5.563 1.44.08.239.158.48.24.719-.16.559-.321 1.117-.484 1.675-1.352.586-3.151 1.982-4.834 1.439-1.39-.151-1.886-.455-2.418-1.439-1.694-2.023.633-3.164 1.933-3.834z"/>' +
							'<path class="qodef-svg-dots-back" d="M2.659 136.573c2.622.468 3.792 1.122 5.078 2.873v.237c-1.212 1.644-2.421 2.397-5.318 2.397-1.496-.729-1.797-1.271-2.419-2.873.403-.719.805-1.441 1.208-2.158.484-.158.968-.315 1.451-.476z"/>' +
							'</g>' +
							'</g>' +
							'</svg>';
					
					$thisHolder.find('.qodef-custom-styles').append(svgDots);
					qodefOutlineText.animateDots($('#qodef-page-wrapper'));
					
				});
			}
		},
		
		animateDots : function ($holder){
			
			$holder.mousemove(function(event) {
				
				var tMember = $(this);
				var mouseX = event.pageX,
					mouseY = event.pageY;
				
				function qodefFoliageMove() {
					var items = tMember;
					var $currentItem;
					
					for (var i = 0, j = items.length; i < j; i++) {
						$currentItem = $(items[i]);
						
						var tFront = $currentItem.find('.qodef-svg-dots-back');
						var tBack = $currentItem.find('.qodef-svg-dots-front');
						
						/*var x = mouseX/50;
						var y = (mouseY - $currentItem.offset().top)/25;*/
						
						var x = mouseX/500;
						var y = (mouseY - $currentItem.offset().top)/250;
						
						/*var transformation = 'translate3D(' + x*.75 + 'px, '+ y * 1.75 + 'px, 0px)';
						var transformationBack = 'translate3D(' + -x*.35 + 'px, '+ -y*1.5 + 'px, 0px)';*/
						var transformation = 'translate3D(' + x*7.7 + 'px, '+ y * 1.75 + 'px, 0px)';
						var transformationBack = 'translate3D(' + -x*5.3 + 'px, '+ -y*1.5 + 'px, 0px)';
						
						tFront.css({
							'transform': transformation
						});
						tBack.css({
							'transform': transformationBack
						});
					}
				}
				
				qodefFoliageMove();
				
			});
		}
	};
	
	qodefCore.shortcodes.etchy_core_outline_text.qodefOutlineTextn = qodefOutlineText;
})(jQuery);
(function ($) {
	'use strict';

	qodefCore.shortcodes.etchy_core_progress_bar = {};

	$(document).ready(function () {
		qodefProgressBar.init();
	});

	/**
	 * Init progress bar shortcode functionality
	 */
	var qodefProgressBar = {
		init: function () {
			this.holder = $('.qodef-progress-bar');

			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this),
						layout = $thisHolder.data('layout');
					
					$thisHolder.appear(function () {
						$thisHolder.addClass('qodef--init');
						
						var $container = $thisHolder.find('.qodef-m-canvas'),
							data = qodefProgressBar.generateBarData($thisHolder, layout),
							number = $thisHolder.data('number') / 100;
						
						switch (layout) {
							case 'circle':
								qodefProgressBar.initCircleBar($container, data, number);
								break;
							case 'semi-circle':
								qodefProgressBar.initSemiCircleBar($container, data, number);
								break;
							case 'line':
								data = qodefProgressBar.generateLineData($thisHolder, number);
								qodefProgressBar.initLineBar($container, data);
								break;
							case 'custom':
								qodefProgressBar.initCustomBar($container, data, number);
								break;
						}
					});
				});
			}
		},
		generateBarData: function (thisBar, layout) {
			var activeWidth = thisBar.data('active-line-width');
			var activeColor = thisBar.data('active-line-color');
			var inactiveWidth = thisBar.data('inactive-line-width');
			var inactiveColor = thisBar.data('inactive-line-color');
			var easing = 'linear';
			var duration = typeof thisBar.data('duration') !== 'undefined' && thisBar.data('duration') !== '' ? parseInt(thisBar.data('duration'), 10) : 1600;
			var textColor = thisBar.data('text-color');

			return {
				strokeWidth: activeWidth,
				color: activeColor,
				trailWidth: inactiveWidth,
				trailColor: inactiveColor,
				easing: easing,
				duration: duration,
				svgStyle: {
					width: '100%',
					height: '100%'
				},
				text: {
					style: {
						color: textColor
					},
					autoStyleContainer: false
				},
				from: {
					color: inactiveColor
				},
				to: {
					color: activeColor
				},
				step: function (state, bar) {
					if (layout !== 'custom') {
						bar.setText(Math.round(bar.value() * 100) + '%');
					}
				}
			};
		},
		generateLineData: function (thisBar, number) {
			var height = thisBar.data('active-line-width');
			var activeColor = thisBar.data('active-line-color');
			var inactiveHeight = thisBar.data('inactive-line-width');
			var inactiveColor = thisBar.data('inactive-line-color');
			var duration = typeof thisBar.data('duration') !== 'undefined' && thisBar.data('duration') !== '' ? parseInt(thisBar.data('duration'), 10) : 1600;
			var textColor = thisBar.data('text-color');

			return {
				percentage: number * 100,
				duration: duration,
				fillBackgroundColor: activeColor,
				backgroundColor: inactiveColor,
				height: height,
				inactiveHeight: inactiveHeight,
				followText: thisBar.hasClass('qodef-percentage--floating'),
				textColor: textColor
			};
		},
		initCircleBar: function ($container, data, number) {
			var $bar = new ProgressBar.Circle($container[0], data);
			
			$bar.animate(number);
		},
		initSemiCircleBar: function ($container, data, number) {
			var $bar = new ProgressBar.SemiCircle($container[0], data);

			$bar.animate(number);
		},
		initCustomBar: function ($container, data, number) {
			var $bar = new ProgressBar.Path($container[0], data);
			$bar.set(0);

			$bar.animate(number);
		},
		initLineBar: function ($container, data) {
			$container.LineProgressbar(data);
		}
	};

	qodefCore.shortcodes.etchy_core_progress_bar.qodefProgressBar = qodefProgressBar;

})(jQuery);
(function ($) {
    "use strict";
	qodefCore.shortcodes.etchy_core_section_title = {};
	qodefCore.shortcodes.etchy_core_section_title.qodefAppear = qodef.qodefAppear;
})(jQuery);
(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_stacked_images = {};

	$(document).ready(function () {
		qodefStackedImages.init();
	});
	
	var qodefStackedImages = {
		init: function () {
			this.images = $('.qodef-stacked-images');
			
			if (this.images.length) {
				this.images.each(function () {
					var $thisImage = $(this);
					
					qodefStackedImages.animate($thisImage);
				});
			}
		},
		animate: function ($image) {
			
			var itemImage = $image.find('.qodef-m-images');
			$image.animate({opacity: 1}, 300);
			
			setTimeout(function () {
				$image.appear(function () {
					itemImage.addClass('qodef--appeared');
				});
			}, 200);
			
		}
	};

	qodefCore.shortcodes.etchy_core_stacked_images.qodefStackedImages = qodefStackedImages;

})(jQuery);
(function ($) {
	'use strict';

	qodefCore.shortcodes.etchy_core_stamp = {};

	$(document).ready(function () {
		qodefInitStamp.init();
	});
	
	/**
	 * Inti stamp shortcode on appear
	 */
	var qodefInitStamp = {
		init: function () {
			this.holder = $('.qodef-stamp');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $holder = $(this),
						appearing_delay = typeof $holder.data('appearing-delay') !== 'undefined' ? parseInt($holder.data('appearing-delay'), 10) : 0;
					
					// Initialization
                    qodefInitStamp.initStampText($holder);
					qodefInitStamp.load($holder, appearing_delay);
					
					if ($holder.hasClass('qodef--repeating')) {
						setInterval(function () {
							qodefInitStamp.reLoad($holder);
						}, 5500);
					}
				});
			}
		},
		initStampText: function ($holder) {
			var $stamp = $holder.children('.qodef-m-text'),
				count = typeof $holder.data('appearing-delay') !== 'undefined' ? parseInt($stamp.data('count'), 10) : 1;

			$stamp.children().each(function (i) {
				var transform = -90 + i * 360 / count,
					transitionDelay = i * 60 / count * 10;
				
				$(this).css({
					'transform': 'rotate(' + transform + 'deg) translateZ(0)',
					'transition-delay': transitionDelay + 'ms'
				});
			});
		},
		load: function ($holder, appearing_delay) {
			if ($holder.hasClass('qodef--nested')) {
				setTimeout(function () {
					qodefInitStamp.appear($holder);
				}, appearing_delay);
			} else {
				$holder.appear(function () {
					setTimeout(function () {
						qodefInitStamp.appear($holder);
					}, appearing_delay);
				}, {accX: 0, accY: -100});
			}
		},
		reLoad: function ($holder) {
			$holder.removeClass('qodef--init');
			
			setTimeout(function () {
				$holder.removeClass('qodef--appear');
				
				setTimeout(function () {
					qodefInitStamp.appear($holder);
				}, 500);
			}, 600);
		},
		appear: function ($holder) {
			$holder.addClass('qodef--appear');
			
			setTimeout(function () {
				$holder.addClass('qodef--init');
			}, 300);
		}
	};

	qodefCore.qodefInitStamp = qodefInitStamp;
	qodefCore.shortcodes.etchy_core_stamp.qodefInitStamp = qodefInitStamp;

})(jQuery);
(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_swapping_image_gallery = {};

	$(document).ready(function () {
		qodefSwappingImageGallery.init();
	});
	
	var qodefSwappingImageGallery = {
		init: function () {
			this.holder = $('.qodef-swapping-image-gallery');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this);
					qodefSwappingImageGallery.createSlider($thisHolder);
				});
			}
		},
		createSlider: function ($holder) {
			var $swiperHolder = $holder.find('.qodef-m-image-holder');
			var $paginationHolder = $holder.find('.qodef-m-thumbnails-holder .qodef-grid-inner');
			var spaceBetween = 0;
			var slidesPerView = 1;
			var centeredSlides = false;
			var loop = false;
			var autoplay = false;
			var speed = 800;
			
			var $swiper = new Swiper($swiperHolder, {
				slidesPerView: slidesPerView,
				centeredSlides: centeredSlides,
				spaceBetween: spaceBetween,
				autoplay: autoplay,
				loop: loop,
				speed: speed,
				pagination: {
					el: $paginationHolder,
					type: 'custom',
					clickable: true,
					bulletClass: 'qodef-m-thumbnail'
				},
				on: {
					init: function () {
						$swiperHolder.addClass('qodef-swiper--initialized');
						$paginationHolder.find('.qodef-m-thumbnail').eq(0).addClass('qodef--active');
					},
					slideChange: function slideChange() {
						var swiper = this;
						var activeIndex = swiper.activeIndex;
						$paginationHolder.find('.qodef--active').removeClass('qodef--active');
						$paginationHolder.find('.qodef-m-thumbnail').eq(activeIndex).addClass('qodef--active');
					}
				}
			});
		}
	};

	qodefCore.shortcodes.etchy_core_swapping_image_gallery.qodefSwappingImageGallery = qodefSwappingImageGallery;

})(jQuery);
(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_tabs = {};

	$(document).ready(function () {
		qodefTabs.init();
	});
	
	var qodefTabs = {
		init: function () {
			this.holder = $('.qodef-tabs');
			
			if (this.holder.length) {
				this.holder.each(function () {
					qodefTabs.initTabs($(this));
				});
			}
		},
		initTabs: function ($tabs) {
			$tabs.children('.qodef-tabs-content').each(function (index) {
				index = index + 1;
				
				var $that = $(this),
					link = $that.attr('id'),
					$navItem = $that.parent().find('.qodef-tabs-navigation li:nth-child(' + index + ') a'),
					navLink = $navItem.attr('href');
				
				link = '#' + link;
				
				if (link.indexOf(navLink) > -1) {
					$navItem.attr('href', link);
				}
			});
			
			$tabs.addClass('qodef--init').tabs();
		}
	};

	qodefCore.shortcodes.etchy_core_tabs.qodefTabs = qodefTabs;

})(jQuery);
(function ($) {
	"use strict";

	qodefCore.shortcodes.etchy_core_text_marquee = {};

	$(document).ready(function () {
		qodefTextMarquee.init();
	});
	
	var qodefTextMarquee = {
		init: function () {
			this.holder = $('.qodef-text-marquee');
			
			if (this.holder.length) {
				this.holder.each(function () {
					qodefTextMarquee.initMarquee($(this));
					qodefTextMarquee.initResponsive($(this).find('.qodef-m-content'));
				});
			}
		},
		initResponsive: function (thisMarquee) {
			var fontSize,
				lineHeight,
				coef1 = 1,
				coef2 = 1;
			
			if (qodefCore.windowWidth < 1480) {
				coef1 = 0.8;
			}
			
			if (qodefCore.windowWidth < 1200) {
				coef1 = 0.7;
			}
			
			if (qodefCore.windowWidth < 768) {
				coef1 = 0.55;
				coef2 = 0.65;
			}
			
			if (qodefCore.windowWidth < 600) {
				coef1 = 0.45;
				coef2 = 0.55;
			}
			
			if (qodefCore.windowWidth < 480) {
				coef1 = 0.4;
				coef2 = 0.5;
			}
			
			fontSize = parseInt(thisMarquee.css('font-size'));
			
			if (fontSize > 200) {
				fontSize = Math.round(fontSize * coef1);
			} else if (fontSize > 60) {
				fontSize = Math.round(fontSize * coef2);
			}
			
			thisMarquee.css('font-size', fontSize + 'px');
			
			lineHeight = parseInt(thisMarquee.css('line-height'));
			
			if (lineHeight > 70 && qodefCore.windowWidth < 1440) {
				lineHeight = '1.2em';
			} else if (lineHeight > 35 && qodefCore.windowWidth < 768) {
				lineHeight = '1.2em';
			} else {
				lineHeight += 'px';
			}
			
			thisMarquee.css('line-height', lineHeight);
		},
		initMarquee: function (thisMarquee) {
			var elements = thisMarquee.find('.qodef-m-text'),
				delta = 0.05;
			
			elements.each(function (i) {
				$(this).data('x', 0);
			});
			
			requestAnimationFrame(function () {
				qodefTextMarquee.loop(thisMarquee, elements, delta);
			});
		},
		inRange: function (thisMarquee) {
			if (qodefCore.scroll + qodefCore.windowHeight >= thisMarquee.offset().top && qodefCore.scroll < thisMarquee.offset().top + thisMarquee.height()) {
				return true;
			}
			
			return false;
		},
		loop: function (thisMarquee, elements, delta) {
			if (!qodefTextMarquee.inRange(thisMarquee)) {
				requestAnimationFrame(function () {
					qodefTextMarquee.loop(thisMarquee, elements, delta);
				});
				return false;
			} else {
				elements.each(function (i) {
					var el = $(this);
					el.css('transform', 'translate3d(' + el.data('x') + '%, 0, 0)');
					el.data('x', (el.data('x') - delta).toFixed(2));
					el.offset().left < -el.width() - 25 && el.data('x', 100 * Math.abs(i - 1));
				});
				requestAnimationFrame(function () {
					qodefTextMarquee.loop(thisMarquee, elements, delta);
				});
			}
		}
	};

	qodefCore.shortcodes.etchy_core_text_marquee.qodefTextMarquee = qodefTextMarquee;

})(jQuery);
(function ($) {
    "use strict";

	qodefCore.shortcodes.etchy_vertical_split_slider = {};

    $(document).ready(function () {
        qodefVerticalSplitSlider.init();
    });

    var qodefVerticalSplitSlider = {
        init: function () {
            var $holder = $('.qodef-vertical-split-slider'),
                breakpoint = qodefVerticalSplitSlider.getBreakpoint($holder),
                initialHeaderStyle = '';

            if (qodefCore.body.hasClass('qodef-header--light')) {
                initialHeaderStyle = 'light';
            } else if (qodefCore.body.hasClass('qodef-header--dark')) {
                initialHeaderStyle = 'dark';
            }

            if ($holder.length) {
                $holder.multiscroll({
                    navigation: true,
                    navigationPosition: 'right',
                    afterRender: function () {
                        qodefCore.body.addClass('qodef-vertical-split-slider--initialized');
                        qodefVerticalSplitSlider.bodyClassHandler($('.ms-left .ms-section:first-child').data('header-skin'), initialHeaderStyle);
                    },
                    onLeave: function (index, nextIndex) {
                        qodefVerticalSplitSlider.bodyClassHandler($($('.ms-left .ms-section')[nextIndex - 1]).data('header-skin'), initialHeaderStyle);
                    }
                });

                $holder.height(qodefCore.windowHeight);
                qodefVerticalSplitSlider.buildAndDestroy(breakpoint);

                $(window).resize(function () {
                    qodefVerticalSplitSlider.buildAndDestroy(breakpoint);
                });
            }
        },
        getBreakpoint: function ($holder) {
            if ($holder.hasClass('qodef-disable-below--768')) {
                return 768;
            } else {
                return 1024;
            }
        },
        buildAndDestroy: function (breakpoint) {
            if (qodefCore.windowWidth <= breakpoint) {
                $.fn.multiscroll.destroy();
                $('html, body').css('overflow', 'initial');
                qodefCore.body.removeClass('qodef-vertical-split-slider--initialized');
            } else {
                $.fn.multiscroll.build();
                qodefCore.body.addClass('qodef-vertical-split-slider--initialized');
            }
        },
        bodyClassHandler: function (slideHeaderStyle, initialHeaderStyle) {
            if (slideHeaderStyle !== undefined && slideHeaderStyle !== '') {
                qodefCore.body.removeClass('qodef-header--light qodef-header--dark').addClass('qodef-header--' + slideHeaderStyle);
            } else if (initialHeaderStyle !== '') {
                qodefCore.body.removeClass('qodef-header--light qodef-header--dark').addClass('qodef-header--' + slideHeaderStyle);
            } else {
                qodefCore.body.removeClass('qodef-header--light qodef-header--dark');
            }
        }
    };

	qodefCore.shortcodes.etchy_vertical_split_slider.qodefVerticalSplitSlider = qodefVerticalSplitSlider;

})(jQuery);
(function ($) {
	'use strict';

	qodefCore.shortcodes.etchy_core_video_button = {};
	qodefCore.shortcodes.etchy_core_video_button.qodefMagnificPopup = qodef.qodefMagnificPopup;
	qodefCore.shortcodes.etchy_core_video_button.qodefInitStamp = qodefCore.qodefInitStamp;

})(jQuery);
(function ($) {
	"use strict";
	
	$(window).on('load', function () {
		qodefStickySidebar.init();
	});
	
	var qodefStickySidebar = {
		init: function () {
			var info = $('.widget_etchy_core_sticky_sidebar');
			
			if (info.length && qodefCore.windowWidth > 1024) {
				info.wrapper = info.parents('#qodef-page-sidebar');
				info.c = 24;
				info.offsetM = info.offset().top - info.wrapper.offset().top;
				info.adj = 15;
				
				qodefStickySidebar.callStack(info);
				
				$(window).on('resize', function () {
					if (qodefCore.windowWidth > 1024) {
						qodefStickySidebar.callStack(info);
					}
				});
				
				$(window).on('scroll', function () {
					if (qodefCore.windowWidth > 1024) {
						qodefStickySidebar.infoPosition(info);
					}
				});
			}
		},
		calc: function (info) {
			var content = $('.qodef-page-content-section'),
				header = $('.header-appear, .qodef-fixed-wrapper'),
				headerH = (header.length) ? header.height() : 0;
			
			info.start = content.offset().top;
			info.end = content.outerHeight();
			info.h = info.wrapper.height();
			info.w = info.outerWidth();
			info.left = info.offset().left;
			info.top = headerH + qodefGlobal.vars.adminBarHeight + info.c - info.offsetM;
			info.data('state', 'top');
		},
		infoPosition: function (info) {
			if (qodefCore.scroll < info.start - info.top && qodefCore.scroll + info.h && info.data('state') !== 'top') {
				TweenMax.to(info.wrapper, .1, {
					y: 5,
				});
				TweenMax.to(info.wrapper, .3, {
					y: 0,
					delay: .1,
				});
				info.data('state', 'top');
				info.wrapper.css({
					'position': 'static',
				});
			} else if (qodefCore.scroll >= info.start - info.top && qodefCore.scroll + info.h + info.adj <= info.start + info.end &&
				info.data('state') !== 'fixed') {
				var c = info.data('state') === 'top' ? 1 : -1;
				info.data('state', 'fixed');
				info.wrapper.css({
					'position': 'fixed',
					'top': info.top,
					'left': info.left,
					'width': info.w
				});
				TweenMax.fromTo(info.wrapper, .2, {
					y: 0
				}, {
					y: c * 10,
					ease: Power4.easeInOut
				});
				TweenMax.to(info.wrapper, .2, {
					y: 0,
					delay: .2,
				});
			} else if (qodefCore.scroll + info.h + info.adj > info.start + info.end && info.data('state') !== 'bottom') {
				info.data('state', 'bottom');
				info.wrapper.css({
					'position': 'absolute',
					'top': info.end - info.h - info.adj,
					'left': 0,
				});
				TweenMax.fromTo(info.wrapper, .1, {
					y: 0
				}, {
					y: -5,
				});
				TweenMax.to(info.wrapper, .3, {
					y: 0,
					delay: .1,
				});
			}
		},
		callStack: function (info) {
			this.calc(info);
			this.infoPosition(info);
		}
	};
	
})(jQuery);
(function ($) {
	"use strict";
	
	var shortcode = 'etchy_core_blog_list';
	
	qodefCore.shortcodes[shortcode] = {};
	
	if (typeof qodefCore.listShortcodesScripts === 'object') {
		$.each(qodefCore.listShortcodesScripts, function (key, value) {
			qodefCore.shortcodes[shortcode][key] = value;
		});
	}
	
})(jQuery);
(function ($) {
	"use strict";
	
	var fixedHeaderAppearance = {
		showHideHeader: function ($pageOuter, $header) {
			if (qodefCore.windowWidth > 1024) {
				if (qodefCore.scroll <= 0) {
					qodefCore.body.removeClass('qodef-header--fixed-display');
					$pageOuter.css('padding-top', '0');
					$header.css('margin-top', '0');
				} else {
					qodefCore.body.addClass('qodef-header--fixed-display');
					$pageOuter.css('padding-top', parseInt(qodefGlobal.vars.headerHeight + qodefGlobal.vars.topAreaHeight) + 'px');
					$header.css('margin-top', parseInt(qodefGlobal.vars.topAreaHeight) + 'px');
				}
			}
		},
		init: function () {
            
            if (!qodefCore.body.hasClass('qodef-header--vertical')) {
                var $pageOuter = $('#qodef-page-outer'),
                    $header = $('#qodef-page-header');
                
                fixedHeaderAppearance.showHideHeader($pageOuter, $header);
                
                $(window).scroll(function () {
                    fixedHeaderAppearance.showHideHeader($pageOuter, $header);
                });
                
                $(window).resize(function () {
                    $pageOuter.css('padding-top', '0');
                    fixedHeaderAppearance.showHideHeader($pageOuter, $header);
                });
            }
		}
	};
	
	qodefCore.fixedHeaderAppearance = fixedHeaderAppearance.init;
	
})(jQuery);
(function ($) {
	"use strict";
	
	var stickyHeaderAppearance = {
		displayAmount: function () {
			if (qodefGlobal.vars.qodefStickyHeaderScrollAmount !== 0) {
				return parseInt(qodefGlobal.vars.qodefStickyHeaderScrollAmount, 10);
			} else {
				return parseInt(qodefGlobal.vars.headerHeight + qodefGlobal.vars.adminBarHeight, 10);
			}
		},
		showHideHeader: function (displayAmount) {
			
			if (qodefCore.scroll < displayAmount) {
				qodefCore.body.removeClass('qodef-header--sticky-display');
			} else {
				qodefCore.body.addClass('qodef-header--sticky-display');
			}
		},
		init: function () {
			var displayAmount = stickyHeaderAppearance.displayAmount();
			
			stickyHeaderAppearance.showHideHeader(displayAmount);
			$(window).scroll(function () {
				stickyHeaderAppearance.showHideHeader(displayAmount);
			});
		}
	};
	
	qodefCore.stickyHeaderAppearance = stickyHeaderAppearance.init;
	
})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefSearchCoversHeader.init();
	});
	
	var qodefSearchCoversHeader = {
		init: function () {
			var $searchOpener = $('a.qodef-search-opener'),
				$searchForm = $('.qodef-search-cover-form'),
				$searchClose = $searchForm.find('.qodef-m-close');
			
			if ($searchOpener.length && $searchForm.length) {
				$searchOpener.on('click', function (e) {
					e.preventDefault();
					qodefSearchCoversHeader.openCoversHeader($searchForm);
					
				});
				$searchClose.on('click', function (e) {
					e.preventDefault();
					qodefSearchCoversHeader.closeCoversHeader($searchForm);
				});
			}
		},
		openCoversHeader: function ($searchForm) {
			qodefCore.body.addClass('qodef-covers-search--opened qodef-covers-search--fadein');
			qodefCore.body.removeClass('qodef-covers-search--fadeout');
			
			setTimeout(function () {
				$searchForm.find('.qodef-m-form-field').focus();
			}, 600);
		},
		closeCoversHeader: function ($searchForm) {
			qodefCore.body.removeClass('qodef-covers-search--opened qodef-covers-search--fadein');
			qodefCore.body.addClass('qodef-covers-search--fadeout');
			
			setTimeout(function () {
				$searchForm.find('.qodef-m-form-field').val('');
				$searchForm.find('.qodef-m-form-field').blur();
				qodefCore.body.removeClass('qodef-covers-search--fadeout');
			}, 300);
		}
	};
	
})(jQuery);

(function($) {
    "use strict";

    $(document).ready(function(){
        qodefSearchFullscreen.init();
    });

	var qodefSearchFullscreen = {
	    init: function(){
            var $searchOpener = $('a.qodef-search-opener'),
                $searchHolder = $('.qodef-fullscreen-search-holder'),
                $searchClose = $searchHolder.find('.qodef-m-close');

            if ($searchOpener.length && $searchHolder.length) {
                $searchOpener.on('click', function (e) {
                    e.preventDefault();
                    if(qodefCore.body.hasClass('qodef-fullscreen-search--opened')){
                        qodefSearchFullscreen.closeFullscreen($searchHolder);
                    }else{
                        qodefSearchFullscreen.openFullscreen($searchHolder);
                    }
                });
                $searchClose.on('click', function (e) {
                    e.preventDefault();
                    qodefSearchFullscreen.closeFullscreen($searchHolder);
                });

                //Close on escape
                $(document).keyup(function (e) {
                    if (e.keyCode === 27 && qodefCore.body.hasClass('qodef-fullscreen-search--opened')) { //KeyCode for ESC button is 27
                        qodefSearchFullscreen.closeFullscreen($searchHolder);
                    }
                });
            }
        },
        openFullscreen: function($searchHolder){
            qodefCore.body.removeClass('qodef-fullscreen-search--fadeout');
            qodefCore.body.addClass('qodef-fullscreen-search--opened qodef-fullscreen-search--fadein');

            setTimeout(function () {
                $searchHolder.find('.qodef-m-form-field').focus();
            }, 900);

            qodefCore.qodefScroll.disable();
        },
        closeFullscreen: function($searchHolder){
            qodefCore.body.removeClass('qodef-fullscreen-search--opened qodef-fullscreen-search--fadein');
            qodefCore.body.addClass('qodef-fullscreen-search--fadeout');

            setTimeout(function () {
                $searchHolder.find('.qodef-m-form-field').val('');
                $searchHolder.find('.qodef-m-form-field').blur();
                qodefCore.body.removeClass('qodef-fullscreen-search--fadeout');
            }, 300);

            qodefCore.qodefScroll.enable();
        }
    };

})(jQuery);

(function ($) {
	"use strict";
	
	$(document).ready(function () {
        qodefSearch.init();
	});
	
	var qodefSearch = {
		init: function () {
            this.search = $('a.qodef-search-opener');

            if (this.search.length) {
                this.search.each(function () {
                    var $thisSearch = $(this);

                    qodefSearch.searchHoverColor($thisSearch);
                });
            }
        },
		searchHoverColor: function ($searchHolder) {
			if (typeof $searchHolder.data('hover-color') !== 'undefined') {
				var hoverColor = $searchHolder.data('hover-color'),
				    originalColor = $searchHolder.css('color');
				
				$searchHolder.on('mouseenter', function () {
					$searchHolder.css('color', hoverColor);
				}).on('mouseleave', function () {
					$searchHolder.css('color', originalColor);
				});
			}
		}
	};
	
})(jQuery);

(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefProgressBarSpinner.init();
	});
	
	var qodefProgressBarSpinner = {
		percentNumber: 0,
		init: function () {
			this.holder = $('#qodef-page-spinner.qodef-layout--progress-bar');
			
			if (this.holder.length) {
				qodefProgressBarSpinner.animateSpinner(this.holder);
			}
		},
		animateSpinner: function ($holder) {
			
			var $numberHolder = $holder.find('.qodef-m-spinner-number-label'),
				$spinnerLine = $holder.find('.qodef-m-spinner-line-front'),
				numberIntervalFastest,
				windowLoaded = false;
			
			$spinnerLine.animate({'width': '100%'}, 10000, 'linear');
			
			var numberInterval = setInterval(function () {
				qodefProgressBarSpinner.animatePercent($numberHolder, qodefProgressBarSpinner.percentNumber);
			
				if (windowLoaded) {
					clearInterval(numberInterval);
				}
			}, 100);
			
			$(window).on('load', function () {
				windowLoaded = true;
				
				numberIntervalFastest = setInterval(function () {
					if (qodefProgressBarSpinner.percentNumber >= 100) {
						clearInterval(numberIntervalFastest);
						$spinnerLine.stop().animate({'width': '100%'}, 500);
						
						setTimeout(function () {
							$holder.addClass('qodef--finished');
							
							setTimeout(function () {
								qodefProgressBarSpinner.fadeOutLoader($holder);
							}, 1000);
						}, 600);
					} else {
						qodefProgressBarSpinner.animatePercent($numberHolder, qodefProgressBarSpinner.percentNumber);
					}
				}, 6);
			});
		},
		animatePercent: function ($numberHolder, percentNumber) {
			if (percentNumber < 100) {
				percentNumber += 5;
				$numberHolder.text(percentNumber);
				
				qodefProgressBarSpinner.percentNumber = percentNumber;
			}
		},
		fadeOutLoader: function ($holder, speed, delay, easing) {
			speed = speed ? speed : 600;
			delay = delay ? delay : 0;
			easing = easing ? easing : 'swing';
			
			$holder.delay(delay).fadeOut(speed, easing);
			
			$(window).on('bind', 'pageshow', function (event) {
				if (event.originalEvent.persisted) {
					$holder.fadeOut(speed, easing);
				}
			});
		}
	};
	
})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefWishlistDropdown.init();
	});
	
	/**
	 * Function object that represents wishlist dropdown.
	 * @returns {{init: Function}}
	 */
	var qodefWishlistDropdown = {
		init: function () {
			var $holder = $('.qodef-wishlist-dropdown');
			
			if ($holder.length) {
				$holder.each(function () {
					var $thisHolder = $(this),
						$link = $thisHolder.find('.qodef-m-link');
					
					$link.on('click', function (e) {
						e.preventDefault();
					});
					
					qodefWishlistDropdown.removeItem($thisHolder);
				});
			}
		},
		removeItem: function ($holder) {
			var $removeLink = $holder.find('.qodef-e-remove');
			
			$removeLink.off().on('click', function (e) {
				e.preventDefault();
				
				var $thisRemoveLink = $(this),
					removeLinkHTML = $thisRemoveLink.html(),
					removeItemID = $thisRemoveLink.data('id');
				
				$thisRemoveLink.html('<span class="fa fa-spinner fa-spin" aria-hidden="true"></span>');
				
				var wishlistData = {
					type: 'remove',
					itemID: removeItemID
				};
				
				$.ajax({
					type: "POST",
					url: qodefGlobal.vars.restUrl + qodefGlobal.vars.wishlistRestRoute,
					data: {
						options: wishlistData
					},
					beforeSend: function (request) {
						request.setRequestHeader('X-WP-Nonce', qodefGlobal.vars.restNonce);
					},
					success: function (response) {
						if (response.status === 'success') {
							var newNumberOfItemsValue = parseInt(response.data['count'], 10);
							
							$holder.find('.qodef-m-link-count').html(newNumberOfItemsValue);
							
							if (newNumberOfItemsValue === 0) {
								$holder.removeClass('qodef-items--has').addClass('qodef-items--no');
							}
							
							$thisRemoveLink.closest('.qodef-m-item').fadeOut(200).remove();
							
							$(document).trigger('etchy_core_wishlist_item_is_removed', [removeItemID]);
						} else {
							$thisRemoveLink.html(removeLinkHTML);
						}
					}
				});
			});
		}
	};
	
	$(document).on('etchy_core_wishlist_item_is_added', function (e, addedItemID, addedUserID) {
		var $holder = $('.qodef-wishlist-dropdown');
		
		if ($holder.length) {
			$holder.each(function () {
				var $thisHolder = $(this),
					$link = $thisHolder.find('.qodef-m-link'),
					numberOfItemsValue = $link.find('.qodef-m-link-count'),
					$itemsHolder = $thisHolder.find('.qodef-m-items');
				
				var wishlistData = {
					itemID: addedItemID,
					userID: addedUserID,
				};
				
				$.ajax({
					type: "POST",
					url: qodefGlobal.vars.restUrl + qodefGlobal.vars.wishlistDropdownRestRoute,
					data: {
						options: wishlistData
					},
					beforeSend: function (request) {
						request.setRequestHeader('X-WP-Nonce', qodefGlobal.vars.restNonce);
					},
					success: function (response) {
						if (response.status === 'success') {
							numberOfItemsValue.html(parseInt(response.data['count'], 10));
							
							if ($thisHolder.hasClass('qodef-items--no')) {
								$thisHolder.removeClass('qodef-items--no').addClass('qodef-items--has');
							}
							
							$itemsHolder.append(response.data['new_html']);
						}
					},
					complete: function () {
						qodefWishlistDropdown.init();
					}
				});
			});
		}
	});
	
})(jQuery);

(function ($) {
	"use strict";
	
	qodefCore.shortcodes.etchy_core_instagram_list = {};
	
	$(document).ready(function () {
		qodefInstagram.init();
	});
	
	var qodefInstagram = {
		init: function () {
			this.holder = $('.sbi.qodef-instagram-swiper-container');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this),
						sliderOptions = $thisHolder.parent().attr('data-options'),
						$instagramImage = $thisHolder.find('.sbi_item.sbi_type_image'),
						$imageHolder = $thisHolder.find('#sbi_images');
					
					$thisHolder.attr('data-options', sliderOptions);
					
					$imageHolder.addClass('swiper-wrapper');
					
					if ($instagramImage.length) {
						$instagramImage.each(function () {
							$(this).addClass('qodef-e qodef-image-wrapper swiper-slide');
						});
					}
					
					if (typeof qodef.qodefSwiper === 'object') {
						qodef.qodefSwiper.init($thisHolder);
					}
				});
			}
		},
	};
	
	qodefCore.shortcodes.etchy_core_instagram_list.qodefInstagram = qodefInstagram;
	qodefCore.shortcodes.etchy_core_instagram_list.qodefSwiper = qodef.qodefSwiper;
	
})(jQuery);
(function($) {
    "use strict";

    /*
     **	Re-init scripts on gallery loaded
     */
	$(document).on('yith_wccl_product_gallery_loaded', function () {
		
		if (typeof qodefCore.qodefWooMagnificPopup === "function") {
			qodefCore.qodefWooMagnificPopup.init();
		}
	});

})(jQuery);
(function ($) {
    "use strict";
    
	qodefCore.shortcodes.etchy_core_product_categories_list = {};
	qodefCore.shortcodes.etchy_core_product_categories_list.qodefMasonryLayout = qodef.qodefMasonryLayout;
	qodefCore.shortcodes.etchy_core_product_categories_list.qodefSwiper = qodef.qodefSwiper;

})(jQuery);
(function ($) {
	"use strict";
	
	var shortcode = 'etchy_core_product_list';
	
	qodefCore.shortcodes[shortcode] = {};
	
	if (typeof qodefCore.listShortcodesScripts === 'object') {
		$.each(qodefCore.listShortcodesScripts, function (key, value) {
			qodefCore.shortcodes[shortcode][key] = value;
		});
	}

})(jQuery);
(function ($) {
	"use strict";
	
	$(document).ready(function () {
		qodefSideAreaCart.init();
	});
	
	var qodefSideAreaCart = {
		init: function () {
			var $holder = $('.qodef-woo-side-area-cart');
			
			if ($holder.length) {
				$holder.each(function () {
					var $thisHolder = $(this);
					
					if (qodefCore.windowWidth > 680) {
						qodefSideAreaCart.trigger($thisHolder);
						
						qodefCore.body.on('added_to_cart', function () {
							qodefSideAreaCart.trigger($thisHolder);
						});
					}
				});
			}
		},
		trigger: function ($holder) {
			var $opener = $holder.find('.qodef-m-opener'),
				$close = $holder.find('.qodef-m-close'),
				$items = $holder.find('.qodef-m-items');
			
			// Open Side Area
			$opener.on('click', function (e) {
				e.preventDefault();
				
				if (!$holder.hasClass('qodef--opened')) {
					qodefSideAreaCart.openSideArea($holder);
					
					$(document).keyup(function (e) {
						if (e.keyCode === 27) {
							qodefSideAreaCart.closeSideArea($holder);
						}
					});
				} else {
					qodefSideAreaCart.closeSideArea($holder);
				}
			});
			
			$close.on('click', function (e) {
				e.preventDefault();
				
				qodefSideAreaCart.closeSideArea($holder);
			});
			
			if ($items.length && typeof qodefCore.qodefPerfectScrollbar === 'object') {
				qodefCore.qodefPerfectScrollbar.init($items);
			}
		},
		openSideArea: function ($holder) {
			qodefCore.qodefScroll.disable();
			
			$holder.addClass('qodef--opened');
			$('#qodef-page-wrapper').prepend('<div class="qodef-woo-side-area-cart-cover"/>');
			
			$('.qodef-woo-side-area-cart-cover').on('click', function (e) {
				e.preventDefault();
				
				qodefSideAreaCart.closeSideArea($holder);
			});
		},
		closeSideArea: function ($holder) {
			if ($holder.hasClass('qodef--opened')) {
				qodefCore.qodefScroll.enable();
				
				$holder.removeClass('qodef--opened');
				$('.qodef-woo-side-area-cart-cover').remove();
			}
		}
	};
	
})(jQuery);

(function ($) {
    "use strict";

    qodefCore.shortcodes.etchy_core_clients_list = {};
    qodefCore.shortcodes.etchy_core_clients_list.qodefSwiper = qodef.qodefSwiper;
})(jQuery);
(function ($) {
	"use strict";
	
	var shortcode = 'etchy_core_portfolio_list';
	
	qodefCore.shortcodes[shortcode] = {};
	
	if (typeof qodefCore.listShortcodesScripts === 'object') {
		$.each(qodefCore.listShortcodesScripts, function (key, value) {
			qodefCore.shortcodes[shortcode][key] = value;
		});
	}
	
})(jQuery);
(function ($) {
	"use strict";
	
	var shortcode = 'etchy_core_team_list';
	
	qodefCore.shortcodes[shortcode] = {};
	
	if (typeof qodefCore.listShortcodesScripts === 'object') {
		$.each(qodefCore.listShortcodesScripts, function (key, value) {
			qodefCore.shortcodes[shortcode][key] = value;
		});
	}
	
})(jQuery);
(function ($) {
    "use strict";
	qodefCore.shortcodes.etchy_core_testimonials_list = {};
	qodefCore.shortcodes.etchy_core_testimonials_list.qodefSwiper = qodef.qodefSwiper;

})(jQuery);
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