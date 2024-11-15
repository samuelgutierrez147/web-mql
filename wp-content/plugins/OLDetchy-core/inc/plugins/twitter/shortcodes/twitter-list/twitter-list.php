<?php

if ( ! function_exists( 'etchy_core_add_twitter_list_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_twitter_list_shortcode( $shortcodes ) {
		if( qode_framework_is_installed( 'twitter' ) ) {
			$shortcodes[] = 'EtchyCoreTwitterListShortcode';
		}
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_twitter_list_shortcode' );
}

if ( class_exists( 'EtchyCoreListShortcode' ) ) {
	class EtchyCoreTwitterListShortcode extends EtchyCoreListShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_PLUGINS_URL_PATH . '/twitter/shortcodes/twitter-list' );
			$this->set_base( 'etchy_core_twitter_list' );
			$this->set_name( esc_html__( 'Twitter List', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that displays twitter list', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'name'       => 'custom_class',
				'field_type' => 'text',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' )
			) );
			$this->map_list_options( array(
				'exclude_behavior' => array( 'masonry', 'slider', 'justified-gallery' ),
				'exclude_option'   => array( 'images_proportion' )
			) );
			$this->set_option( array(
				'name'       => 'number_of_items',
				'field_type' => 'text',
				'title'      => esc_html__( 'Number of Tweets', 'etchy-core' ),
				'group'      => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_retweeted_text',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Retweeted Text', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' ),
				'default_value' => 'yes',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_avatar_image',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Avatar Image', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' ),
				'default_value' => 'yes',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_author_name',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Author Name', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' ),
				'default_value' => 'yes',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_tweet_text',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Tweet Text', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' ),
				'default_value' => 'yes',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_media_placeholder',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Media Placeholder', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'no_yes' ),
				'default_value' => 'no',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_date',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Date', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' ),
				'default_value' => 'yes',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_tweet_actions',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Tweet Actions', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' ),
				'default_value' => 'yes',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'          => 'show_twitter_link',
				'field_type'    => 'select',
				'title'         => esc_html__( 'Show Twitter Link', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'no_yes' ),
				'default_value' => 'no',
				'group'         => esc_html__( 'Info', 'etchy-core' )
			) );
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			
			$atts = $this->get_atts();
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['twitter_params']   = $this->get_twitter_params( $atts );
			
			return etchy_core_get_template_part( 'plugins/twitter/shortcodes/twitter-list', 'templates/twitter-list', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-twitter-list';
			
			$list_classes   = $this->get_list_classes( $atts );
			$list_classes[] = 'qodef--no-bottom-space';
			if( isset ( $atts['show_tweet_actions'] ) && $atts['show_tweet_actions'] == 'no' && isset ( $atts['show_twitter_link'] ) && $atts['show_twitter_link'] == 'no' ) {
				$list_classes[] = 'qodef--no-bottom-info';
			}
			$holder_classes = array_merge( $holder_classes, $list_classes );
			
			$holder_classes = array_merge( $holder_classes );
			
			return implode( ' ', $holder_classes );
		}
		
		private function get_twitter_params( $atts ) {
			$params = array();
			
			$params['num'] = isset( $atts['number_of_items'] ) && ! empty( $atts['number_of_items'] ) ? $atts['number_of_items'] : 3;
			$params['showheader'] = false;
			$params['creditctf'] = false;
			$params['showbutton'] = false;
			
			$include = array();
			$exclude = array();
			if( isset( $atts['show_retweeted_text'] ) && ! empty( $atts['show_retweeted_text'] ) ) {
				if( $atts['show_retweeted_text'] == 'yes' ) {
					$include[] = 'retweeter';
				} elseif( $atts['show_retweeted_text'] == 'no' ) {
					$exclude[] = 'retweeter';
				}
			}
			if( isset( $atts['show_avatar_image'] ) && ! empty( $atts['show_avatar_image'] ) ) {
				if( $atts['show_avatar_image'] == 'yes' ) {
					$include[] = 'avatar';
				} elseif( $atts['show_avatar_image'] == 'no' ) {
					$exclude[] = 'avatar';
				}
			}
			if( isset( $atts['show_author_name'] ) && ! empty( $atts['show_author_name'] ) ) {
				if( $atts['show_author_name'] == 'yes' ) {
					$include[] = 'author';
				} elseif( $atts['show_author_name'] == 'no' ) {
					$exclude[] = 'author';
				}
			}
			if( isset( $atts['show_tweet_text'] ) && ! empty( $atts['show_tweet_text'] ) ) {
				if( $atts['show_tweet_text'] == 'yes' ) {
					$include[] = 'text';
				} elseif( $atts['show_tweet_text'] == 'no' ) {
					$exclude[] = 'text';
				}
			}
			if( isset( $atts['show_media_placeholder'] ) && ! empty( $atts['show_media_placeholder'] ) ) {
				if( $atts['show_media_placeholder'] == 'yes' ) {
					$include[] = 'placeholder';
				} elseif( $atts['show_media_placeholder'] == 'no' ) {
					$exclude[] = 'placeholder';
				}
			}
			if( isset( $atts['show_date'] ) && ! empty( $atts['show_date'] ) ) {
				if( $atts['show_date'] == 'yes' ) {
					$include[] = 'date';
				} elseif( $atts['show_date'] == 'no' ) {
					$exclude[] = 'date';
				}
			}
			if( isset( $atts['show_tweet_actions'] ) && ! empty( $atts['show_tweet_actions'] ) ) {
				if( $atts['show_tweet_actions'] == 'yes' ) {
					$include[] = 'actions';
				} elseif( $atts['show_tweet_actions'] == 'no' ) {
					$exclude[] = 'actions';
				}
			}
			if( isset( $atts['show_twitter_link'] ) && ! empty( $atts['show_twitter_link'] ) ) {
				if( $atts['show_twitter_link'] == 'yes' ) {
					$include[] = 'twitterlink';
				} elseif( $atts['show_twitter_link'] == 'no' ) {
					$exclude[] = 'twitterlink';
				}
			}
			
			$params['include'] = implode( ',', $include );
			$params['exclude'] = implode( ',', $exclude );
			
			if ( is_array( $params ) && count( $params ) ) {
				foreach ( $params as $key => $value ) {
					if ( $value !== '' ) {
						$params[] = $key . "='" . esc_attr( str_replace( ' ', '', $value ) ) . "'";
					}
				}
			}
			
			return implode( ' ', $params );
		}
	}
}