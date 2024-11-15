<?php

if ( ! function_exists( 'etchy_core_add_author_info_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_author_info_widget( $widgets ) {
		$widgets[] = 'EtchyCoreAuthorInfoWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_author_info_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreAuthorInfoWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$this->set_base( 'etchy_core_author_info' );
			$this->set_name( esc_html__( 'Etchy Author Info', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Add author info element into widget areas', 'etchy-core' ) );
			$this->set_widget_option(
				array(
					'field_type' => 'text',
					'name'       => 'author_username',
					'title'      => esc_html__( 'Author Username', 'etchy-core' )
				)
			);
			$this->set_widget_option(
				array(
					'field_type' => 'color',
					'name'       => 'author_color',
					'title'      => esc_html__( 'Author Color', 'etchy-core' )
				)
			);
			$this->set_widget_option(
				array(
					'field_type' => 'iconpack',
					'name'       => 'icon_pack_author',
					'title'      => esc_html__( 'Author Icon', 'etchy-core' )
				)
			);
		}
		
		public function render( $atts ) {
			$author_id = 1;
			if ( ! empty( $atts['author_username'] ) ) {
				$author = get_user_by( 'login', $atts['author_username'] );
				
				if ( ! empty( $author ) ) {
					$author_id = $author->ID;
				}
			}
			
			$author_link  = get_author_posts_url( $author_id );
			$author_bio   = get_the_author_meta( 'description', $author_id );
			$user_socials = etchy_core_get_author_social_networks( $author_id );
			?>
			<div class="widget qodef-author-info">
				<a itemprop="url" class="qodef-author-info-image" href="<?php echo esc_url( $author_link ); ?>">
					<?php echo get_avatar( $author_id, 320 ); ?>
				</a>
				<?php if ( ! empty( $author_bio ) ) { ?>
					<span class="qodef-author-info-text"><?php esc_html_e( 'Written by', 'etchy-core' ); ?></span>
					<h5 class="qodef-author-info-name vcard author">
						<a itemprop="url" href="<?php echo esc_url( $author_link ); ?>">
							<span class="fn"><?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></span>
						</a>
					</h5>
					<p itemprop="description"
					   class="qodef-author-info-description"><?php echo esc_html( $author_bio ); ?></p>
				<?php } ?>
				
				<?php if ( ! empty( $user_socials ) ) { ?>
					<span class="qodef-m-social-icons-text"><?php esc_html_e( 'follow me:', 'etchy-core' ); ?></span>
					<div class="qodef-m-social-icons">
						<?php foreach ( $user_socials as $social ) { ?>
							<a itemprop="url" class="<?php echo esc_attr( $social['class'] ) ?>" href="<?php echo esc_url( $social['url'] ) ?>" target="_blank">
								<?php echo esc_html($social['text']); ?>
							</a>
						<?php } ?>
					</div>
				<?php } ?>
			
			</div>
			<?php
		}
	}
}
