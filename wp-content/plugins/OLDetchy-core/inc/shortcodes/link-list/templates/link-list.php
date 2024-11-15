<div <?php qode_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-items qodef-link-items">
		<?php foreach ( $items as $item ) { ?>
			<div class="qodef-m-item">
				<a class="qodef-m-link-name <?php if ($item['item_active'] == 'yes') echo 'qodef-link-active'; ?>" href="<?php echo esc_url( $item['link_url'] ); ?>" target="<?php echo esc_attr( $item['link_target'] ); ?>" >
					<?php echo esc_html( $item['item_title'] ); ?>
				</a>
			</div>
		<?php } ?>
	</div>
</div>