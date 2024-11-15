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
