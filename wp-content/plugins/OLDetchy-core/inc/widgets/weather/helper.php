<?php

if ( ! function_exists( 'etchy_core_weather_widget_logic' ) ) {
	function etchy_core_weather_widget_logic( $atts ) {
		$weather_data = array();
		$api_key      = ! empty ( $atts['api_key'] ) ? $atts['api_key'] : false;
		$location     = ! empty ( $atts['location'] ) ? $atts['location'] : false;
		$units        = ! empty ( $atts['units'] ) ? $atts['units'] : false;
		$days_to_show = ! empty ( $atts['days_to_show'] ) ? $atts['days_to_show'] : 1;
		$locale       = 'en';
		
		$system_locale     = get_locale();
		$available_locales = array(
			'en',
			'es',
			'sp',
			'fr',
			'it',
			'de',
			'pt',
			'ro',
			'pl',
			'ru',
			'uk',
			'ua',
			'fi',
			'nl',
			'bg',
			'sv',
			'se',
			'ca',
			'tr',
			'hr',
			'zh',
			'zh_tw',
			'zh_cn',
			'hu'
		);
		
		// check for locale
		if ( in_array( $system_locale, $available_locales ) ) {
			$locale = $system_locale;
		}
		
		// check for locale by first two digits, used as language in returned data
		if ( in_array( substr( $system_locale, 0, 2 ), $available_locales ) ) {
			$locale = substr( $system_locale, 0, 2 );
		}
		
		// if location is empty abort
		if ( ! $location ) {
			return etchy_core_weather_widget_error();
		}
		
		// find and cache city id
		if ( is_numeric( $location ) ) {
			$city_name_slug = sanitize_title( $location );;
			$api_query = "id=" . $location;
		} else {
			$city_name_slug = sanitize_title( $location );
			$api_query      = "q=" . $location;
		}
		
		// set transient name
		$weather_transient_name = 'etchy_core_' . $city_name_slug . "_" . $days_to_show . "_" . $units . '_' . $locale;
		
		// get weather data
		if ( get_transient( $weather_transient_name ) ) {
			$weather_data = get_transient( $weather_transient_name );
		} else {
			$weather_data['now']      = array();
			$weather_data['forecast'] = array();
			
			$ping_params = array(
				'api_query' => $api_query,
				'locale'    => $locale,
				'units'     => $units,
				'api_key'   => $api_key
			);
			
			// ping weather now api
			$weather_data['now'] = etchy_core_weather_ping( $ping_params );
			
			if ( $days_to_show == 5 ) {
				$weather_data['forecast'] = etchy_core_weather_ping( $ping_params, true );
			}
			
			if ( $weather_data['now'] || $weather_data['forecast'] ) {
				// set the transient, cache for three hours
				set_transient( $weather_transient_name, $weather_data, apply_filters( 'etchy_core_filter_widget_weather_cache', 1800 ) );
			}
		}
		
		// no weather
		if ( ! $weather_data || ! isset( $weather_data['now'] ) ) {
			return etchy_core_weather_widget_error();
		}
		
		return $weather_data;
	}
}

if ( ! function_exists( 'etchy_core_weather_ping' ) ) {
	function etchy_core_weather_ping( $params, $days = false ) {
		
		// ping weather now api
		if ( $days ) {
			$ping = "http://api.openweathermap.org/data/2.5/forecast/daily?" . $params['api_query'] . "&lang=" . $params['locale'] . "&units=" . $params['units'] . "&cnt=7&APPID=" . $params['api_key'];
		} else {
			$ping = "http://api.openweathermap.org/data/2.5/weather?" . $params['api_query'] . "&lang=" . $params['locale'] . "&units=" . $params['units'] . "&APPID=" . $params['api_key'];
		}
		
		$ping     = str_replace( " ", "", $ping );
		$ping_get = wp_remote_get( $ping );
		
		// ping url error
		if ( is_wp_error( $ping_get ) ) {
			return etchy_core_weather_widget_error( $ping_get->get_error_message() );
		}
		
		// get body of request
		$data = json_decode( $ping_get['body'] );
		
		if ( isset( $data->cod ) AND $data->cod == 404 ) {
			return etchy_core_weather_widget_error( $data->message );
		} else {
			return $data;
		}
	}
}

if ( ! function_exists( 'etchy_core_weather_widget_error' ) ) {
	function etchy_core_weather_widget_error( $msg = false ) {
		
		if ( ! $msg ) {
			$msg = esc_html__( 'No weather information available', 'etchy-core' );
		}
		
		echo esc_html( $msg );
		
		return false;
	}
}