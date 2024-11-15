<?php if ( $days_to_show == 5  && isset( $today_params['rest'] ) && ! empty( $today_params['rest'] ) ) {
	foreach ( $today_params['rest'] as $key => $value ) {
		// Add surrounding div for days after today
		if ( $key === 0 ) { ?>
			<div class="qodef-m-other-days">
		<?php }
		$new_params              = $value;
		$new_params['temp_unit'] = $today_params['temp_unit'];
		
		etchy_core_template_part( 'widgets/weather', 'templates/parts/day', '', $new_params );
		
		if ( $key === intval( $days_to_show ) - 1 ) { ?>
			</div>
			<?php
			break;
		}
	}
} ?>