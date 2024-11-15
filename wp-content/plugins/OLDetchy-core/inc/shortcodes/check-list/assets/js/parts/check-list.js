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