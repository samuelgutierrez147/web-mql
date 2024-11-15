<?php

if ( ! function_exists( 'etchy_is_woo_page' ) ) {
	/**
	 * Function that check WooCommerce pages
	 *
	 * @param string $page
	 *
	 * @return bool
	 */
	function etchy_is_woo_page( $page ) {
		switch ( $page ) {
			case 'shop':
				return function_exists( 'is_shop' ) && is_shop();
				break;
			case 'single':
				return is_singular( 'product' );
				break;
			case 'cart':
				return function_exists( 'is_cart' ) && is_cart();
				break;
			case 'checkout':
				return function_exists( 'is_checkout' ) && is_checkout();
				break;
			case 'account':
				return function_exists( 'is_account_page' ) && is_account_page();
				break;
			case 'category':
				return function_exists( 'is_product_category' ) && is_product_category();
				break;
			case 'tag':
				return function_exists( 'is_product_tag' ) && is_product_tag();
				break;
			case 'any':
				return (
					function_exists( 'is_shop' ) && is_shop() ||
					is_singular( 'product' ) ||
					function_exists( 'is_cart' ) && is_cart() ||
					function_exists( 'is_checkout' ) && is_checkout() ||
					function_exists( 'is_account_page' ) && is_account_page() ||
					function_exists( 'is_product_category' ) && is_product_category() ||
					function_exists( 'is_product_tag' ) && is_product_tag()
				);
				break;
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'etchy_get_woo_main_page_classes' ) ) {
	/**
	 * Function that return current WooCommerce page class name
	 *
	 * @return string
	 */
	function etchy_get_woo_main_page_classes() {
		$classes = array();

		if ( etchy_is_woo_page( 'shop' ) ) {
			$classes[] = 'qodef--list';
		}

		if ( etchy_is_woo_page( 'single' ) ) {
			$classes[] = 'qodef--single';

			if ( etchy_get_post_value_through_levels( 'qodef_woo_single_enable_image_lightbox' ) === 'photo-swipe' ) {
				$classes[] = 'qodef-popup--photo-swipe';
			}

			if ( etchy_get_post_value_through_levels( 'qodef_woo_single_enable_image_lightbox' ) === 'magnific-popup' ) {
				$classes[] = 'qodef-popup--magnific-popup';
				// add classes to initialize lightbox from theme
				$classes[] = 'qodef-magnific-popup';
				$classes[] = 'qodef-popup-gallery';
			}
		}

		if ( etchy_is_woo_page( 'cart' ) ) {
			$classes[] = 'qodef--cart';
		}

		if ( etchy_is_woo_page( 'checkout' ) ) {
			$classes[] = 'qodef--checkout';
		}

		if ( etchy_is_woo_page( 'account' ) ) {
			$classes[] = 'qodef--account';
		}

		return apply_filters( 'etchy_filter_main_page_classes', implode( ' ', $classes ) );
	}
}

if ( ! function_exists( 'etchy_woo_get_global_product' ) ) {
	/**
	 * Function that return global WooCommerce object
	 *
	 * @return object
	 */
	function etchy_woo_get_global_product() {
		global $product;

		return $product;
	}
}

if ( ! function_exists( 'etchy_woo_get_main_shop_page_id' ) ) {
	/**
	 * Function that return main shop page ID
	 *
	 * @return int
	 */
	function etchy_woo_get_main_shop_page_id() {
		// Get page id from options table
		$shop_id = get_option( 'woocommerce_shop_page_id' );

		if ( ! empty( $shop_id ) ) {
			return $shop_id;
		}

		return false;
	}
}

if ( ! function_exists( 'etchy_woo_set_main_shop_page_id' ) ) {
	/**
	 * Function that set main shop page ID for get_post_meta options
	 *
	 * @param int $post_id
	 *
	 * @return int
	 */
	function etchy_woo_set_main_shop_page_id( $post_id ) {

		if ( etchy_is_woo_page( 'shop' ) || etchy_is_woo_page( 'single' ) || etchy_is_woo_page( 'category' ) || etchy_is_woo_page( 'tag' ) ) {
			$shop_id = etchy_woo_get_main_shop_page_id();

			if ( ! empty( $shop_id ) ) {
				$post_id = $shop_id;
			}
		}

		return $post_id;
	}

	add_filter( 'etchy_filter_page_id', 'etchy_woo_set_main_shop_page_id' );
	add_filter( 'qode_framework_filter_page_id', 'etchy_woo_set_main_shop_page_id' );
}

if ( ! function_exists( 'etchy_woo_set_page_title_text' ) ) {
	/**
	 * Function that returns current page title text for WooCommerce pages
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	function etchy_woo_set_page_title_text( $title ) {

		if ( etchy_is_woo_page( 'shop' ) || etchy_is_woo_page( 'single' ) ) {
			$shop_id = etchy_woo_get_main_shop_page_id();

			$title = ! empty( $shop_id ) ? get_the_title( $shop_id ) : esc_html__( 'Shop', 'etchy' );
		} elseif ( etchy_is_woo_page( 'category' ) || etchy_is_woo_page( 'tag' ) ) {
			$taxonomy_slug = etchy_is_woo_page( 'tag' ) ? 'product_tag' : 'product_cat';
			$taxonomy      = get_term( get_queried_object_id(), $taxonomy_slug );

			if ( ! empty( $taxonomy ) ) {
				$title = esc_html( $taxonomy->name );
			}
		}

		return $title;
	}

	add_filter( 'etchy_filter_page_title_text', 'etchy_woo_set_page_title_text' );
}

if ( ! function_exists( 'etchy_woo_breadcrumbs_title' ) ) {
	function etchy_woo_breadcrumbs_title( $wrap_child, $settings ) {
		
		if ( etchy_is_woo_page( 'category' ) || etchy_is_woo_page( 'tag' ) ) {
			$wrap_child    = '';
			$taxonomy_slug = etchy_is_woo_page( 'tag' ) ? 'product_tag' : 'product_cat';
			$taxonomy      = get_term( get_queried_object_id(), $taxonomy_slug );
			
			if ( isset( $taxonomy->parent ) && $taxonomy->parent !== 0 ) {
				$parent     = get_term( $taxonomy->parent );
				$wrap_child .= sprintf( $settings['link'], get_term_link( $parent->term_id ), $parent->name ) . $settings['separator'];
			}
			
			if ( ! empty( $taxonomy ) ) {
				$wrap_child .= sprintf( $settings['current_item'], esc_attr( $taxonomy->name ) );
			}
			
		} elseif ( etchy_is_woo_page( 'shop' ) ) {
			$shop_id    = etchy_woo_get_main_shop_page_id();
			$shop_title = ! empty( $shop_id ) ? get_the_title( $shop_id ) : esc_html__( 'Shop', 'etchy' );
			
			$wrap_child .= sprintf( $settings['current_item'], $shop_title );
			
		} elseif ( etchy_is_woo_page( 'single' ) ) {
			$wrap_child = '';
			$post_terms = wp_get_post_terms( get_the_ID(), 'product_cat' );
			
			if ( ! empty ( $post_terms ) ) {
				$post_term = $post_terms[0];
				if ( isset( $post_term->parent ) && $post_term->parent !== 0 ) {
					$parent     = get_term( $post_term->parent );
					$wrap_child .= sprintf( $settings['link'], get_term_link( $parent->term_id ), $parent->name ) . $settings['separator'];
				}
				$wrap_child .= sprintf( $settings['link'], get_term_link( $post_term ), $post_term->name ) . $settings['separator'];
			}
			
			$wrap_child .= sprintf( $settings['current_item'], get_the_title() );
		}
		
		return $wrap_child;
	}
	
	add_filter( 'etchy_core_filter_breadcrumbs_content', 'etchy_woo_breadcrumbs_title', 10, 2 );
}

if ( ! function_exists( 'etchy_woo_single_add_theme_supports' ) ) {
	/**
	 * Function that add native WooCommerce supports
	 */
	function etchy_woo_single_add_theme_supports() {
		// Add featured image zoom functionality on product single page
		$is_zoom_enabled = etchy_get_post_value_through_levels( 'qodef_woo_single_enable_image_zoom' ) !== 'no';

		if ( $is_zoom_enabled ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}

		// Add photo swipe lightbox functionality on product single images page
		$is_photo_swipe_enabled = etchy_get_post_value_through_levels( 'qodef_woo_single_enable_image_lightbox' ) === 'photo-swipe';

		if ( $is_photo_swipe_enabled ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
	}

	add_action( 'wp_loaded', 'etchy_woo_single_add_theme_supports', 11 ); // permission 11 is set because options are init with permission 10 inside framework plugin
}

if ( ! function_exists( 'etchy_woo_single_disable_page_title' ) ) {
	/**
	 * Function that disable page title area for single product page
	 *
	 * @param bool $enable_page_title
	 *
	 * @return bool
	 */
	function etchy_woo_single_disable_page_title( $enable_page_title ) {
		$is_enabled = etchy_get_post_value_through_levels( 'qodef_woo_single_enable_page_title' ) !== 'no';

		if ( ! $is_enabled && etchy_is_woo_page( 'single' ) ) {
			$enable_page_title = false;
		}

		return $enable_page_title;
	}

	add_filter( 'etchy_filter_enable_page_title', 'etchy_woo_single_disable_page_title' );
}

if ( ! function_exists( 'etchy_woo_single_thumb_images_position' ) ) {
	/**
	 * Function that changes the layout of thumbnails on single product page
	 */
	function etchy_woo_single_thumb_images_position( $classes ) {
		$product_thumbnail_position = etchy_is_installed( 'core' ) ? etchy_get_post_value_through_levels( 'qodef_woo_single_thumb_images_position' ) : 'below';

		if ( ! empty( $product_thumbnail_position ) ) {
			$classes[] = 'qodef-position--' . $product_thumbnail_position;
		}

		return $classes;
	}

	add_filter( 'woocommerce_single_product_image_gallery_classes', 'etchy_woo_single_thumb_images_position' );
}

if ( ! function_exists( 'etchy_set_woo_custom_sidebar_name' ) ) {
	/**
	 * Function that return sidebar name
	 *
	 * @param string $sidebar_name
	 *
	 * @return string
	 */
	function etchy_set_woo_custom_sidebar_name( $sidebar_name ) {
	
		if ( etchy_is_woo_page( 'shop' ) || etchy_is_woo_page( 'category' ) || etchy_is_woo_page( 'tag' ) ) {
			$option = etchy_get_post_value_through_levels( 'qodef_woo_product_list_custom_sidebar' );
			
			if ( isset( $option ) && ! empty( $option ) ) {
				$sidebar_name = $option;
			}
		}

		return $sidebar_name;
	}

	add_filter( 'etchy_filter_sidebar_name', 'etchy_set_woo_custom_sidebar_name' );
}

if ( ! function_exists( 'etchy_set_woo_sidebar_layout' ) ) {
	/**
	 * Function that return sidebar layout
	 *
	 * @param string $layout
	 *
	 * @return string
	 */
	function etchy_set_woo_sidebar_layout( $layout ) {
		
		if ( etchy_is_woo_page( 'shop' ) || etchy_is_woo_page( 'category' ) || etchy_is_woo_page( 'tag' ) ) {
			$option = etchy_get_post_value_through_levels( 'qodef_woo_product_list_sidebar_layout' );

			if ( isset( $option ) && ! empty( $option ) ) {
				$layout = $option;
			}
		}

		return $layout;
	}

	add_filter( 'etchy_filter_sidebar_layout', 'etchy_set_woo_sidebar_layout' );
}

if ( ! function_exists( 'etchy_set_woo_sidebar_grid_gutter_classes' ) ) {
	/**
	 * Function that returns grid gutter classes
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	function etchy_set_woo_sidebar_grid_gutter_classes( $classes ) {
		
		if ( etchy_is_woo_page( 'shop' ) || etchy_is_woo_page( 'category' ) || etchy_is_woo_page( 'tag' ) ) {
			$option = etchy_get_post_value_through_levels( 'qodef_woo_product_list_sidebar_grid_gutter' );
			
			if ( isset( $option ) && ! empty( $option ) ) {
				$classes = 'qodef-gutter--' . esc_attr( $option );
			}
		}

		return $classes;
	}

	add_filter( 'etchy_filter_grid_gutter_classes', 'etchy_set_woo_sidebar_grid_gutter_classes' );
}

if ( ! function_exists( 'etchy_set_woo_review_form_fields' ) ) {
	/**
	 * Function that add woo rating to wordpress comment form fields
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function etchy_set_woo_review_form_fields( $args ) {
		$comment_args = etchy_get_comment_form_args( array( 'comment_placeholder' => esc_attr__( 'Your Review *', 'etchy' ) ) );
		
		if ( key_exists( 'comment_field', $comment_args ) ) {
			
			if ( wc_review_ratings_enabled() ) {
				// add rating stuff before textarea element
				// copied from wp-content/plugins/woocommerce/templates/single-product-reviews.php
				$comment_args['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'etchy' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'etchy' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'etchy' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'etchy' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'etchy' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'etchy' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'etchy' ) . '</option>
					</select></div>' . $comment_args['comment_field'];
			}
		}
		
		// Override WooCommerce review arguments with ours
		$args = array_merge( $args, $comment_args );
		
		return $args;
	}
	
	add_filter( 'woocommerce_product_review_comment_form_args', 'etchy_set_woo_review_form_fields' );
}

if ( ! function_exists( 'etchy_woo_modify_add_to_cart_button' ) ) {
	/**
	 * Function that modifies add to cart buttom
	 *
	 * @param string $button
	 * @param object $product
	 * @param array $args
	 *
	 * @return string
	 */
	function etchy_woo_modify_add_to_cart_button( $button, $product, $args ) {

		if ( ! isset( $args['class'] ) ) {
			$args['class'] = 'button';
		}
		$args['class'] .= ' qodef-btn-wave-hover';

		$button = sprintf(
			'<a href="%s" data-quantity="%s" class="%s" %s>%s<span class="qodef-btn-masked"><span class="qodef-m-text">%s</span></span></a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text() ),
			esc_html( $product->add_to_cart_text() )
		);

		return $button;
	}

	add_filter( 'woocommerce_loop_add_to_cart_link', 'etchy_woo_modify_add_to_cart_button', 10, 3 );
}