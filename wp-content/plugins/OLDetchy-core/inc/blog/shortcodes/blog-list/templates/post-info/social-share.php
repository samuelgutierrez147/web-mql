<?php if ( class_exists( 'EtchyCoreSocialShareShortcode' ) ) { ?>
	<div class="qodef-e-info-item qodef-e-info-social-share">
		<?php
		$params          = array();
		$params['title'] = esc_html__( 'share:', 'etchy-core' );
		$params['layout']    = 'text';
		
		echo EtchyCoreSocialShareShortcode::call_shortcode( $params ); ?>
	</div>
<?php } ?>




