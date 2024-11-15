<?php

if ( isset( $media ) && ! empty( $media ) ) {
	$wrapped_start = '';
	$wrapped_end   = '';
	
	if ( isset( $media_type ) && $media_type === 'gallery' ) {
		$wrapped_start = '<div class="qodef-grid-item">';
		$wrapped_end   = '</div>';
	}
	
	echo sprintf( '%s%s%s', $wrapped_start, wp_audio_shortcode( array( 'src' => esc_url( $media ) ) ), $wrapped_end );
}