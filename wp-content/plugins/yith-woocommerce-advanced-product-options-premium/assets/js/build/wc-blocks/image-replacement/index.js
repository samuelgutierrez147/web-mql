/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: external ["wp","plugins"]
const external_wp_plugins_namespaceObject = window["wp"]["plugins"];
;// CONCATENATED MODULE: external ["wp","data"]
const external_wp_data_namespaceObject = window["wp"]["data"];
;// CONCATENATED MODULE: external ["wp","element"]
const external_wp_element_namespaceObject = window["wp"]["element"];
;// CONCATENATED MODULE: ./includes/wc-blocks/assets/js/image-replacement/index.js



var render = function render() {
  var cartItems = (0,external_wp_data_namespaceObject.useSelect)(function (select) {
    return select('wc/store/cart').getCartData().items;
  });
  (0,external_wp_element_namespaceObject.useEffect)(function () {
    setTimeout(function () {
      // Change product thumbnail.
      var cartHtml = document.querySelector('.wc-block-cart .wc-block-cart__main') || document.querySelector('.wc-block-cart .wc-block-components-order-summary__content');
      var checkoutHtml = document.querySelector('.wc-block-checkout .wc-block-components-order-summary');
      var cartElements = {
        itemsRow: '.wc-block-cart-items__row',
        imageSelector: '.wc-block-cart-item__image a img'
      };
      var checkoutElements = {
        itemsRow: '.wc-block-components-order-summary-item',
        imageSelector: '.wc-block-components-order-summary-item__image img'
      };
      var elements = null;
      var elementHtml = null;
      if (cartHtml !== null) {
        elementHtml = cartHtml;
        elements = cartElements;
      } else if (checkoutHtml !== null) {
        elementHtml = checkoutHtml;
        elements = checkoutElements;
      }
      if (elements !== null && elementHtml !== null) {
        var itemsRow = elementHtml.querySelectorAll(elements.itemsRow);
        itemsRow.forEach(function (itemRow, indexRow) {
          var imageToReplace = cartItems[indexRow].extensions.yith_wapo_wc_block_manager.replace_image;
          if (imageToReplace) {
            var cartItemRowImage = itemRow.querySelector(elements.imageSelector);
            cartItemRowImage.src = imageToReplace;
          }
        });
      }
    }, 650);
  }, []);
};
(0,external_wp_plugins_namespaceObject.registerPlugin)('yith-wapo-image-replacement', {
  render: render,
  scope: 'woocommerce-checkout'
});
/******/ })()
;
//# sourceMappingURL=index.js.map