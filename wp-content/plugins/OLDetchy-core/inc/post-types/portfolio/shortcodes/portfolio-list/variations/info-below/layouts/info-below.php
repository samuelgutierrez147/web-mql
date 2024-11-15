<?php
$styles = array();
if ( ! empty( $info_below_content_margin_top ) ) {
	$margin_top = qode_framework_string_ends_with_space_units( $info_below_content_margin_top ) ? $info_below_content_margin_top : intval( $info_below_content_margin_top ) . 'px';
	$styles[] = 'margin-top:' . $margin_top;
}
if ( ! empty( $info_below_content_margin_bottom ) ) {
	$margin_bottom = qode_framework_string_ends_with_space_units( $info_below_content_margin_bottom ) ? $info_below_content_margin_bottom : intval( $info_below_content_margin_bottom ) . 'px';
	$styles[] = 'margin-bottom:' . $margin_bottom;
}
?>
<article <?php post_class( $item_classes ); ?>>
	<div class="qodef-e-inner" <?php qode_framework_inline_style( $this_shortcode->get_list_item_style( $params ) ) ?>>
		<div class="qodef-e-image">
			<?php etchy_core_list_sc_template_part( 'post-types/portfolio/shortcodes/portfolio-list', 'post-info/image', '', $params ); ?>
		</div>
		<div class="qodef-e-content" <?php qode_framework_inline_style( $styles ); ?>>
			<?php etchy_core_list_sc_template_part( 'post-types/portfolio/shortcodes/portfolio-list', 'post-info/category', '', $params ); ?>
			<?php etchy_core_list_sc_template_part( 'post-types/portfolio/shortcodes/portfolio-list', 'post-info/title', '', $params ); ?>
		</div>
	</div>
</article>