<?php if ( ! post_password_required() && class_exists( 'EtchyCoreButtonShortcode' ) ) { ?>
	<div class="qodef-e-read-more">
		<?php
		$button_params = array(
			'link' => get_the_permalink(),
			'text' => esc_html__( 'Read More', 'etchy-core' )
		);
		
		echo EtchyCoreButtonShortcode::call_shortcode( $button_params ); ?>
	</div>
<?php } ?>