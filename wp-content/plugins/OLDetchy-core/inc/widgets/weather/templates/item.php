<div class="qodef-m-inner">
	<div class="qodef-m-weather">
		<span class="qodef-m-weather-icon fa qodef--<?php echo esc_attr( $today_description_class ); ?>"></span>
		<?php etchy_core_template_part( 'widgets/weather', 'templates/parts/temperature', '', $params ); ?>
	</div>
	<div class="qodef-m-weather-info qodef-e">
		<div class="qodef-e-heading">
			<?php if ( isset( $city ) && ! empty( $city ) ) { ?>
				<h4 class="qodef-e-heading-city"><?php echo esc_html( $city ); ?></h4>
			<?php } ?>
			<?php if ( isset( $today_description ) && ! empty( $today_description ) ) { ?>
				<p class="qodef-e-heading-description"><?php echo esc_html( $today_description ) ?></p>
			<?php } ?>
		</div>
		<p class="qodef-e-humidity">
			<?php esc_html_e( 'Humidity:', 'etchy-core' ); echo esc_html( $today_humidity ); echo esc_html__( '%', 'etchy-core' ); ?>
		</p>
		<p class="qodef-e-wind">
			<?php esc_html_e( 'Wind:', 'etchy-core' ); echo esc_html( $today_wind_speed ); echo esc_html( $wind_unit ); ?>
		</p>
		<p class="qodef-e-temperature qodef--high-low">
			<span class="qodef-e-temperature-low"><?php
				echo sprintf( '%s %s%s%s',
					esc_html__( 'L', 'etchy-core' ),
					esc_html( $today_low ),
					'<sup>°</sup>',
					esc_html( $temp_unit )
				);
			?></span>
			<span class="qodef-e-temperature-high"><?php
				echo sprintf( '%s%s%s',
					esc_html__( 'H ', 'etchy-core' ),
					esc_html( $today_high ),
					'<sup>°</sup>',
					esc_html( $temp_unit )
				);
			?></span>
		</p>
	</div>
</div>