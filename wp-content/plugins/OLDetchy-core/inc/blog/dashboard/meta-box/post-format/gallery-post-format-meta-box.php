<?php

if ( ! function_exists( 'etchy_core_add_gallery_post_format_meta_box' ) ) {
	/**
	 * Function that add options for post format
	 *
	 * @param mixed $page - general post format meta box section
	 */
	function etchy_core_add_gallery_post_format_meta_box( $page ) {
		
		if ( $page ) {
			$post_format_section = $page->add_section_element(
				array(
					'name'  => 'qodef_post_format_gallery_section',
					'title' => esc_html__( 'Post Format Gallery', 'etchy-core' )
				)
			);
			
			$post_format_section->add_field_element(
				array(
					'field_type'  => 'image',
					'name'        => 'qodef_post_format_gallery_images',
					'title'       => esc_html__( 'Gallery Images', 'etchy-core' ),
					'description' => esc_html__( 'Choose your gallery images for your post', 'etchy-core' ),
					'multiple'    => 'yes'
				)
			);
			
			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_gallery_post_format_meta_box', $page );
		}
	}
	
	add_action( 'etchy_core_action_after_blog_single_meta_box_map', 'etchy_core_add_gallery_post_format_meta_box', 1 );
}