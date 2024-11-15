import {registerPlugin} from '@wordpress/plugins';
import {useSelect} from "@wordpress/data";
import {useEffect} from "@wordpress/element";

const render = () => {
    const cartItems = useSelect((select) => select('wc/store/cart').getCartData().items);

    useEffect(() => {
        setTimeout( function () {

            // Change product thumbnail.
            const cartHtml     = document.querySelector('.wc-block-cart .wc-block-cart__main') || document.querySelector('.wc-block-cart .wc-block-components-order-summary__content');
            const checkoutHtml = document.querySelector('.wc-block-checkout .wc-block-components-order-summary');

            const cartElements = {
                itemsRow : '.wc-block-cart-items__row',
                imageSelector : '.wc-block-cart-item__image a img'
            };
            const checkoutElements = {
                itemsRow : '.wc-block-components-order-summary-item',
                imageSelector : '.wc-block-components-order-summary-item__image img'
            };

            var elements = null;
            var elementHtml = null;

            if ( cartHtml !== null ) {
                elementHtml = cartHtml;
                elements    = cartElements;
            } else if ( checkoutHtml !== null ) {
                elementHtml = checkoutHtml;
                elements    = checkoutElements;
            }

            if ( elements !== null && elementHtml !== null ) {

                const itemsRow = elementHtml.querySelectorAll( elements.itemsRow );
                itemsRow.forEach((itemRow, indexRow) => {
                    const imageToReplace = cartItems[indexRow].extensions.yith_wapo_wc_block_manager.replace_image;
                    if ( imageToReplace ) {
                        const cartItemRowImage = itemRow.querySelector( elements.imageSelector );
                        cartItemRowImage.src   = imageToReplace;
                    }

                });
            }
        }, 650 );
    }, []);


}

registerPlugin('yith-wapo-image-replacement', {
    render,
    scope: 'woocommerce-checkout',
})