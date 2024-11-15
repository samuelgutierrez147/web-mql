<?php if ( $enable_wave_hover == 'yes' ) { ?>
	<span class='qodef-wave-btn-holder'>
<?php } ?>
	<button type="submit" <?php qode_framework_class_attribute( $holder_classes ); ?> <?php qode_framework_inline_attrs( $data_attrs ); ?> <?php qode_framework_inline_style( $styles ); ?>>
		<span class="qodef-btn-text"><?php echo esc_html( $text ); ?></span>
		<?php if ( $enable_wave_hover == 'yes' ) { ?>
			<span class="qodef-btn-masked" <?php qode_framework_inline_style($button_masked_style);?>>
				<span class="qodef-m-text"><?php echo esc_html( $text ); ?></span>
			</span>
		<?php } ?>
	</button>
<?php if ( $enable_wave_hover == 'yes' ) { ?>
	</span>
<?php } ?>