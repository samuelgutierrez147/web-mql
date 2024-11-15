<?php

if ( ! function_exists( 'etchy_get_blog_holder_classes' ) ) {
	/**
	 * Function that return classes for the main blog holder
	 *
	 * @return string
	 */
	function etchy_get_blog_holder_classes() {
		$classes = array();
		
		if ( is_single() ) {
			$classes[] = 'qodef--single';
		} else {
			$classes[] = 'qodef--list';
		}
		
		return implode( ' ', apply_filters( 'etchy_filter_blog_holder_classes', $classes ) );
	}
}

if ( ! function_exists( 'etchy_get_blog_list_excerpt_length' ) ) {
	/**
	 * Function that return number of characters for excerpt on blog list page
	 *
	 * @return int
	 */
	function etchy_get_blog_list_excerpt_length() {
		$length = apply_filters( 'etchy_filter_blog_list_excerpt_length', 180 );
		
		return intval( $length );
	}
}

if ( ! function_exists( 'etchy_post_has_read_more' ) ) {
	/**
	 * Function that checks if current post has read more tag set
	 *
	 * @return int position of read more tag text. It will return false if read more tag isn't set
	 */
	function etchy_post_has_read_more() {
		global $post;
		
		return ! empty( $post ) ? strpos( $post->post_content, '<!--more-->' ) : false;
	}
}

if ( ! function_exists( 'etchy_has_post_media' ) ) {
	/**
	 * Function that gets post media params
	 *
	 * @return boolean
	 */
	function etchy_has_post_media() {

		switch ( get_post_format() ) {
			case 'gallery':
				$gallery_meta = get_post_meta( get_the_ID(), 'qodef_post_format_gallery_images', true );
				return ! empty( $gallery_meta ) || has_post_thumbnail();
				break;
			case 'video':
				$video_meta = get_post_meta( get_the_ID(), 'qodef_post_format_video_url', true );
				return ! empty( $video_meta ) || has_post_thumbnail();
				break;
			default:
				return has_post_thumbnail();
				break;
		}

		return $params;
	}
}

if ( ! function_exists( 'etchy_quote_svg' ) ) {
	function etchy_quote_svg() {
		
		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 width="71.466px" height="51.516px" viewBox="0 0 71.466 51.516" enable-background="new 0 0 71.466 51.516" xml:space="preserve">
		<path fill="none" stroke="#FFFFFF" stroke-miterlimit="10" d="M25.338,38.407c5.807-6.167,8.71-13.773,8.71-22.817
			c0-4.491-1.37-8.129-4.107-10.914C27.202,1.892,23.758,0.5,19.606,0.5c-3.43,0-6.198,0.988-8.304,2.964
			C9.195,5.44,8.143,8.076,8.143,11.369c0,3.714,2.015,6.827,6.047,9.343c3.972,2.515,5.958,4.851,5.958,7.007
			c0,7.486-6.289,12.636-18.865,15.45L0.56,50.894C11.271,48.738,19.53,44.576,25.338,38.407z M62.255,38.497
			c5.806-6.167,8.71-13.803,8.71-22.906c0-4.491-1.37-8.129-4.107-10.914C64.119,1.892,60.676,0.5,56.523,0.5
			c-3.491,0-6.259,0.988-8.304,2.964c-2.046,1.976-3.069,4.612-3.069,7.905c0,3.714,2.016,6.827,6.048,9.343
			c3.972,2.515,5.957,4.851,5.957,7.007c0,7.486-6.289,12.636-18.865,15.45l-0.722,7.725C48.219,48.798,56.447,44.666,62.255,38.497z"
			/>
		</svg>';
		
		return $html;
	}
}

if ( ! function_exists( 'etchy_link_svg' ) ) {
	function etchy_link_svg() {
		
		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 width="39.345px" height="51.791px" viewBox="0 0 39.345 51.791" enable-background="new 0 0 39.345 51.791" xml:space="preserve">
		<path fill="none" stroke="#FFFFFF" stroke-miterlimit="10" d="M6.724,9.232c-0.938,0.938-1.405,2.075-1.405,3.413
			c0,1.339,0.467,2.477,1.405,3.413l17.265,17.265c-3.614,0.938-6.692,0.101-9.235-2.509L3.411,19.472
			C1.47,17.599,0.5,15.34,0.5,12.696c0-2.643,0.97-4.934,2.911-6.876l2.51-2.51c1.873-1.873,4.148-2.81,6.826-2.81
			c2.676,0,4.951,0.938,6.826,2.81l11.343,11.343c2.61,2.609,3.413,5.722,2.409,9.335L16.159,6.723
			c-0.938-0.936-2.075-1.405-3.413-1.405c-1.339,0-2.477,0.469-3.413,1.405L6.724,9.232z M8.43,37.138
			c-2.61-2.609-3.413-5.721-2.409-9.335l17.165,17.265c0.936,0.938,2.073,1.406,3.413,1.406c1.338,0,2.475-0.468,3.413-1.406
			l2.609-2.509c0.937-0.937,1.406-2.074,1.406-3.413c0-1.338-0.469-2.475-1.406-3.413L15.356,18.467c3.613-0.936,6.691-0.1,9.235,2.51
			l11.343,11.342c1.94,1.875,2.911,4.133,2.911,6.776c0,2.645-0.971,4.936-2.911,6.876l-2.51,2.51c-1.874,1.873-4.15,2.81-6.826,2.81
			c-2.677,0-4.953-0.938-6.826-2.81L8.43,37.138z"/>
		</svg>';
		
		return $html;
	}
}