<div class="qodef-header-wrapper">
	<div class="qodef-header-logo">
		<?php
		// Include logo
		etchy_core_get_header_logo_image(); ?>
	</div>
	<?php
	// Include main navigation
	etchy_core_template_part( 'header', 'templates/parts/navigation' );
	
	// Include widget area one
	if ( is_active_sidebar( 'qodef-header-widget-area-one' ) ) { ?>
		<div class="qodef-widget-holder">
			<?php etchy_core_get_header_widget_area(); ?>
		</div>
	<?php } ?>
</div>