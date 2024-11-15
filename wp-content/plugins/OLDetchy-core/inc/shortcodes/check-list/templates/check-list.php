<div <?php qode_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-items qodef-check-items">
		<?php foreach ( $items as $item ) { ?>
			<div class="qodef-m-item qodef-e qodef-item-price" data-price="<?php echo esc_attr( $item['item_price'] ); ?>">
				<div class="qodef-e-holder">
					<label class="qodef-e-switch">
						<input type="checkbox" <?php if ($item['item_active'] == 'yes') echo 'checked'; ?>>
						<span class="qodef-e-slider"></span>
					</label>
				</div>
				<div class="qodef-e-title-holder">
					<<?php echo esc_attr($title_tag); ?>>
						<?php echo esc_attr( $item['item_title'] ); ?>
					</<?php echo esc_attr($title_tag); ?>>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="qodef-m-items qodef-check-text">
        <div class="qodef-e-top">
		<span class="qode-e-check-holder">
			<span class="qodef-e-currency"><?php echo esc_attr($currency); ?></span><span class="qodef-e-total-price"><?php echo esc_attr($total_price); ?></span>
		</span>
		<?php if ( ! empty( $text_side ) ) { ?>
			<p class="qodef-e-text-side"><?php echo esc_attr($text_side); ?></p>
		<?php } ?>
		</div>
	
		<span class="qode-e-button">
			<a itemprop="url" class="qodef-button qodef-layout--filled qodef-btn-wave-hover qodef-size--medium qodef-html--link" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"><?php echo esc_attr($button_text); ?><span class="qodef-btn-masked"><span class="qodef-m-text"><?php echo esc_attr($button_text); ?></span></span></a>
		</span>
	</div>
</div>