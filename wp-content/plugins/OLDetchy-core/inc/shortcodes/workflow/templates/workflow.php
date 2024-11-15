<div <?php qode_framework_class_attribute( $holder_classes ); ?>>
	<?php if ( ! empty( $image ) ) { ?>
		<div class="qodef-e-workflow-image">
			<?php echo wp_get_attachment_image( $image, 'full' ) ?>
		</div>
	<?php } ?>
	<?php foreach ( $items as $key => $item ) : ?>
		<div class="qodef-e-workflow-item">
			<div class="qodef-e-workflow-item-inner">
				<div class="qodef-e-workflow-text">
					<?php if ( isset( $item['subtitle'] ) && ! empty( $item['subtitle'] ) ) { ?>
						<p class="qodef-e-subtitle"><?php echo esc_html( $item['subtitle'] ); ?></p>
					<?php } ?>
					<h5 class="qodef-e-title"><?php echo esc_html( $item['title'] ); ?></h5>
					<?php if ( isset( $item['text'] ) && ! empty( $item['text'] ) ) { ?>
						<p class="qodef-e-text"><?php echo esc_html( $item['text'] ); ?></p>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>