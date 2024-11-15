<div class="qodef-e qodef-inof--social-share">
	<?php if ( class_exists( 'EtchyCoreSocialShareShortcode' ) ) {
		$params = array(
			'title'  => esc_html__( 'Share:', 'etchy-core' ),
			'layout' => 'text'
		);
		
		echo EtchyCoreSocialShareShortcode::call_shortcode( $params );
	} ?>
</div>