<?php
$taxonomy_items = get_terms( array( 'taxonomy' => $taxonomy ) );
if ( ! is_wp_error( $taxonomy_items ) && ! empty( $taxonomy_items ) ) { ?>
	<ul>
		<?php foreach ( $taxonomy_items as $taxonomy_item ) {
			$item_slug = $taxonomy_item->slug;
			$item_name = $taxonomy_item->name;
			$item_id   = $taxonomy_item->term_id;
			$item_url  = get_term_link( $item_slug, $taxonomy );
			?>
			<li>
				<a href="<?php echo esc_url( $item_url ); ?>">
					<?php if ( ! empty( qode_framework_get_option_value( '', 'taxonomy', 'qodef_' . $taxonomy . '_svg_icon', '', $item_id ) ) ) { ?>
						<span class="qodef-svg-icon"><?php echo qode_framework_get_option_value( '', 'taxonomy', 'qodef_' . $taxonomy . '_svg_icon', '', $item_id ); ?></span>
					<?php } ?>
					<?php echo esc_html( $item_name ); ?>
				</a>
			</li>
		<?php } ?>
	</ul>
<?php }