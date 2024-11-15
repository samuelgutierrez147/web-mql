<?php

if ( ! function_exists( 'etchy_core_add_working_hours_options' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_working_hours_options() {
		$qode_framework = qode_framework_get_framework_root();

		$page = $qode_framework->add_options_page(
			array(
				'scope'       => ETCHY_CORE_OPTIONS_NAME,
				'type'        => 'admin',
				'slug'        => 'working-hours',
				'icon'        => 'fa fa-book',
				'title'       => esc_html__( 'Working Hours', 'etchy-core' ),
				'description' => esc_html__( 'Global Working Hours Options', 'etchy-core' )
			)
		);

		if ( $page ) {
			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_monday',
					'title'      => esc_html__( 'Working Hours For Monday', 'etchy-core' )
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_tuesday',
					'title'      => esc_html__( 'Working Hours For Tuesday', 'etchy-core' )
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_wednesday',
					'title'      => esc_html__( 'Working Hours For Wednesday', 'etchy-core' )
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_thursday',
					'title'      => esc_html__( 'Working Hours For Thursday', 'etchy-core' )
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_friday',
					'title'      => esc_html__( 'Working Hours For Friday', 'etchy-core' )
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_saturday',
					'title'      => esc_html__( 'Working Hours For Saturday', 'etchy-core' )
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_sunday',
					'title'      => esc_html__( 'Working Hours For Sunday', 'etchy-core' )
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'checkbox',
					'name'       => 'qodef_working_hours_special_days',
					'title'      => esc_html__( 'Special Days', 'etchy-core' ),
					'options'    => array(
						'monday'    => esc_html__( 'Monday', 'etchy-core' ),
						'tuesday'   => esc_html__( 'Tuesday', 'etchy-core' ),
						'wednesday' => esc_html__( 'Wednesday', 'etchy-core' ),
						'thursday'  => esc_html__( 'Thursday', 'etchy-core' ),
						'friday'    => esc_html__( 'Friday', 'etchy-core' ),
						'saturday'  => esc_html__( 'Saturday', 'etchy-core' ),
						'sunday'    => esc_html__( 'Sunday', 'etchy-core' ),
					)
				)
			);

			$page->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_working_hours_special_text',
					'title'      => esc_html__( 'Featured Text For Special Days', 'etchy-core' )
				)
			);

			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_working_hours_options_map', $page );
		}
	}

	add_action( 'etchy_core_action_default_options_init', 'etchy_core_add_working_hours_options', etchy_core_get_admin_options_map_position( 'working-hours' ) );
}