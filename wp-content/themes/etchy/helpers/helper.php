<?php

if ( ! function_exists( 'etchy_is_installed' ) ) {
	/**
	 * Function that checks if forward plugin installed
	 *
	 * @param string $plugin - plugin name
	 *
	 * @return bool
	 */
	function etchy_is_installed( $plugin ) {
		
		switch ( $plugin ) {
			case 'framework';
				return class_exists( 'QodeFramework' );
				break;
			case 'core';
				return class_exists( 'EtchyCore' );
				break;
			case 'woocommerce';
				return class_exists( 'WooCommerce' );
				break;
			case 'gutenberg-page';
				$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : array();
				
				return method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor();
				break;
			case 'gutenberg-editor':
				return class_exists( 'WP_Block_Type' );
				break;
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'etchy_include_theme_is_installed' ) ) {
	/**
	 * Function that set case is installed element for framework functionality
	 *
	 * @param bool $installed
	 * @param string $plugin - plugin name
	 *
	 * @return bool
	 */
	function etchy_include_theme_is_installed( $installed, $plugin ) {
		
		if ( $plugin === 'theme' ) {
			return class_exists( 'EtchyHandler' );
		}
		
		return $installed;
	}
	
	add_filter( 'qode_framework_filter_is_plugin_installed', 'etchy_include_theme_is_installed', 10, 2 );
}

if ( ! function_exists( 'etchy_template_part' ) ) {
	/**
	 * Function that echo module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params array of parameters to pass to template
	 */
	function etchy_template_part( $module, $template, $slug = '', $params = array() ) {
		echo etchy_get_template_part( $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'etchy_get_template_part' ) ) {
	/**
	 * Function that load module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function etchy_get_template_part( $module, $template, $slug = '', $params = array() ) {
		//HTML Content from template
		$html          = '';
		$template_path = ETCHY_INC_ROOT_DIR . '/' . $module;
		
		$temp = $template_path . '/' . $template;
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params );
		}
		
		$template = '';
		
		if ( ! empty( $temp ) ) {
			if ( ! empty( $slug ) ) {
				$template = "{$temp}-{$slug}.php";
				
				if ( ! file_exists( $template ) ) {
					$template = $temp . '.php';
				}
			} else {
				$template = $temp . '.php';
			}
		}
		
		if ( $template ) {
			ob_start();
			include( $template );
			$html = ob_get_clean();
		}
		
		return $html;
	}
}

if ( ! function_exists( 'etchy_get_page_id' ) ) {
	/**
	 * Function that returns current page id
	 * Additional conditional is to check if current page is any wp archive page (archive, category, tag, date etc.) and returns -1
	 *
	 * @return int
	 */
	function etchy_get_page_id() {
		$page_id = get_queried_object_id();
		
		if ( etchy_is_wp_template() ) {
			$page_id = -1;
		}
		
		return apply_filters( 'etchy_filter_page_id', $page_id );
	}
}

if ( ! function_exists( 'etchy_is_wp_template' ) ) {
	/**
	 * Function that checks if current page default wp page
	 *
	 * @return bool
	 */
	function etchy_is_wp_template() {
		return is_archive() || is_search() || is_404() || ( is_front_page() && is_home() );
	}
}

if ( ! function_exists( 'etchy_get_ajax_status' ) ) {
	/**
	 * Function that return status from ajax functions
	 *
	 * @param string $status - success or error
	 * @param string $message - ajax message value
	 * @param string|array $data - returned value
	 * @param string $redirect - url address
	 */
	function etchy_get_ajax_status( $status, $message, $data = null, $redirect = '' ) {
		$response = array(
			'status'   => esc_attr( $status ),
			'message'  => esc_html( $message ),
			'data'     => $data,
			'redirect' => ! empty( $redirect ) ? esc_url( $redirect ) : '',
		);
		
		$output = json_encode( $response );
		
		exit( $output );
	}
}

if ( ! function_exists( 'etchy_get_icon' ) ) {
	/**
	 * Function that return icon html
	 *
	 * @param string $icon - icon class name
	 * @param string $icon_pack - icon pack name
	 * @param string $backup_text - backup text label if framework is not installed
	 * @param array $params - icon parameters
	 *
	 * @return string|mixed
	 */
	function etchy_get_icon( $icon, $icon_pack, $backup_text, $params = array() ) {
		$value = etchy_is_installed( 'framework' ) && etchy_is_installed( 'core' ) ? qode_framework_icons()->render_icon( $icon, $icon_pack, $params ) : $backup_text;
		
		return $value;
	}
}

if ( ! function_exists( 'etchy_render_icon' ) ) {
	/**
	 * Function that render icon html
	 *
	 * @param string $icon - icon class name
	 * @param string $icon_pack - icon pack name
	 * @param string $backup_text - backup text label if framework is not installed
	 * @param array $params - icon parameters
	 */
	function etchy_render_icon( $icon, $icon_pack, $backup_text, $params = array() ) {
		echo etchy_get_icon( $icon, $icon_pack, $backup_text, $params );
	}
}

if ( ! function_exists( 'etchy_get_button_element' ) ) {
	/**
	 * Function that returns button with provided params
	 *
	 * @param array $params - array of parameters
	 *
	 * @return string - string representing button html
	 */
	function etchy_get_button_element( $params ) {
		if ( class_exists( 'EtchyCoreButtonShortcode' ) ) {
			return EtchyCoreButtonShortcode::call_shortcode( $params );
		} else {
			$link   = isset( $params['link'] ) ? $params['link'] : '#';
			$target = isset( $params['target'] ) ? $params['target'] : '_self';
			$text   = isset( $params['text'] ) ? $params['text'] : '';
			
			return '<a itemprop="url" class="qodef-theme-button" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $text ) . '</a>';
		}
	}
}

if ( ! function_exists( 'etchy_render_button_element' ) ) {
	/**
	 * Function that render button with provided params
	 *
	 * @param array $params - array of parameters
	 */
	function etchy_render_button_element( $params ) {
		echo etchy_get_button_element( $params );
	}
}

if ( ! function_exists( 'etchy_class_attribute' ) ) {
	/**
	 * Function that render class attribute
	 *
	 * @param string|array $class
	 */
	function etchy_class_attribute( $class ) {
		echo etchy_get_class_attribute( $class );
	}
}

if ( ! function_exists( 'etchy_get_class_attribute' ) ) {
	/**
	 * Function that return class attribute
	 *
	 * @param string|array $class
	 *
	 * @return string|mixed
	 */
	function etchy_get_class_attribute( $class ) {
		$value = etchy_is_installed( 'framework' ) ? qode_framework_get_class_attribute( $class ) : '';
		
		return $value;
	}
}

if ( ! function_exists( 'etchy_get_post_value_through_levels' ) ) {
	/**
	 * Function that returns meta value if exists
	 *
	 * @param string $name name of option
	 * @param int    $post_id id of
	 *
	 * @return string value of option
	 */
	function etchy_get_post_value_through_levels( $name, $post_id = null ) {
		return etchy_is_installed( 'framework' ) && etchy_is_installed( 'core' ) ? etchy_core_get_post_value_through_levels( $name, $post_id ) : '';
	}
}

if ( ! function_exists( 'etchy_get_space_value' ) ) {
	/**
	 * Function that returns spacing value based on selected option
	 *
	 * @param string $text_value - textual value of spacing
	 *
	 * @return int
	 */
	function etchy_get_space_value( $text_value ) {
		return etchy_is_installed( 'core' ) ? etchy_core_get_space_value( $text_value ) : 0;
	}
}

if ( ! function_exists( 'etchy_wp_kses_html' ) ) {
	/**
	 * Function that does escaping of specific html.
	 * It uses wp_kses function with predefined attributes array.
	 *
	 * @see wp_kses()
	 *
	 * @param string $type - type of html element
	 * @param string $content - string to escape
	 *
	 * @return string escaped output
	 */
	function etchy_wp_kses_html( $type, $content ) {
		return etchy_is_installed( 'framework' ) ? qode_framework_wp_kses_html( $type, $content ) : $content;
	}
}

if ( ! function_exists( 'etchy_arrow_left_svg' ) ) {
	function etchy_arrow_left_svg() {

		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="8.789px" height="16.641px" viewBox="0 0 8.789 16.641" enable-background="new 0 0 8.789 16.641" xml:space="preserve">
					<g>
						<path fill="#A3A3A3" d="M0,7.969L8.027,0l0.762,0.703L1.113,8.32l7.617,7.617l-0.703,0.703L0,8.672V7.969z"/>
					</g>
				</svg>';

		return $html;
	}
}

if ( ! function_exists( 'etchy_arrow_right_svg' ) ) {
	function etchy_arrow_right_svg() {

		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="8.789px" height="16.641px" viewBox="0 0 8.789 16.641" enable-background="new 0 0 8.789 16.641" xml:space="preserve">
					<g>
						<path fill="#A3A3A3" d="M8.789,8.672l-8.027,7.969L0,15.938L7.676,8.32L0.059,0.703L0.762,0l8.027,7.969V8.672z"/>
					</g>
				</svg>';

		return $html;
	}
}

if ( ! function_exists( 'etchy_play_icon_svg' ) ) {
	function etchy_play_icon_svg() {

		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="31.412px" height="34.649px" viewBox="0.309 0.735 31.412 34.649" enable-background="new 0.309 0.735 31.412 34.649" xml:space="preserve">
					<path fill="#FFFFFF" d="M31.721,18.061c-0.028-0.309-0.227-0.576-0.514-0.692L1.564,0.9C1.308,0.71,0.965,0.683,0.682,0.829 c-0.26,0.179-0.401,0.486-0.368,0.8V34.49c-0.033,0.313,0.108,0.621,0.368,0.8c0.283,0.147,0.625,0.122,0.882-0.067l29.643-16.47 C31.494,18.637,31.693,18.37,31.721,18.061z"/>
				</svg>';

		return $html;
	}
}

if ( ! function_exists( 'etchy_opener_svg' ) ) {
	function etchy_opener_svg() {

		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="17.143px" height="14.286px" viewBox="0 0 17.143 14.286" enable-background="new 0 0 17.143 14.286" xml:space="preserve">
					<g>
						<path d="M17.143,0.714v1.428c0,0.194-0.071,0.361-0.212,0.502c-0.141,0.142-0.309,0.212-0.502,0.212H0.714
							c-0.194,0-0.361-0.07-0.502-0.212C0.071,2.504,0,2.336,0,2.143V0.714c0-0.193,0.071-0.361,0.212-0.502C0.354,0.071,0.521,0,0.714,0
							h15.714c0.193,0,0.361,0.071,0.502,0.212C17.072,0.354,17.143,0.521,17.143,0.714z M17.143,6.429v1.428
							c0,0.194-0.071,0.361-0.212,0.502C16.79,8.5,16.622,8.571,16.429,8.571H0.714c-0.194,0-0.361-0.071-0.502-0.212
							C0.071,8.218,0,8.051,0,7.857V6.429c0-0.194,0.071-0.361,0.212-0.502c0.142-0.141,0.309-0.212,0.502-0.212h15.714
							c0.193,0,0.361,0.071,0.502,0.212C17.072,6.068,17.143,6.235,17.143,6.429z M17.143,12.143v1.429c0,0.194-0.071,0.361-0.212,0.502
							c-0.141,0.141-0.309,0.212-0.502,0.212H0.714c-0.194,0-0.361-0.071-0.502-0.212C0.071,13.932,0,13.765,0,13.571v-1.429
							c0-0.193,0.071-0.361,0.212-0.502c0.142-0.141,0.309-0.212,0.502-0.212h15.714c0.193,0,0.361,0.071,0.502,0.212
							C17.072,11.782,17.143,11.949,17.143,12.143z"/>
					</g>
				</svg>';

		return $html;
	}
}