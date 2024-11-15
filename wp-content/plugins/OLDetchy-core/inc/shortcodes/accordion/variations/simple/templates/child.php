<<?php echo esc_attr( $title_tag ); ?> class="qodef-accordion-title">
	<span class="qodef-tab-title">
		<?php echo esc_html( $title ); ?>
	</span>
	<span class="qodef-accordion-mark">
		<?php echo qode_framework_icons()->render_icon( 'fas fa-plus', 'font-awesome', array( 'icon_attributes' => array( 'class' => 'qodef-icon--plus' ) ) ); ?>
		<?php echo qode_framework_icons()->render_icon( 'fas fa-minus', 'font-awesome', array( 'icon_attributes' => array( 'class' => 'qodef-icon--minus' ) ) ); ?>
	</span>
</<?php echo esc_attr( $title_tag ); ?>>
<div class="qodef-accordion-content">
	<div class="qodef-accordion-content-inner">
		<?php echo do_shortcode( $content ); ?>
	</div>
</div>