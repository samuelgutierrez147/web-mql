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