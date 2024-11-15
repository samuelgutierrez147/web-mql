<?php if ( class_exists( 'EtchyCoreSocialShareShortcode' ) ) { ?>
	<div class="qodef-woo-product-social-share">
		<?php
		$params = array();
		$params['title'] = esc_html__( 'Share:', 'etchy-core' );
		
		echo EtchyCoreSocialShareShortcode::call_shortcode( $params ); ?>
	</div>
<?php } ?>