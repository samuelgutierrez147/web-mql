<?php if ( ! empty( $button_params ) && class_exists( 'EtchyCoreButtonShortcode' ) ) { ?>
	<div class="qodef-m-button">
		<?php echo EtchyCoreButtonShortcode::call_shortcode( $button_params ); ?>
	</div>
<?php } ?>