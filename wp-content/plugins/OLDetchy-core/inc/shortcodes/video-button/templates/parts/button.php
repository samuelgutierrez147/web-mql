<?php if ( ! empty( $video_link ) ) { ?>
	<a itemprop="url" class="qodef-m-play qodef-magnific-popup qodef-popup-item" href="<?php echo esc_url( $video_link ); ?>" data-type="iframe">
		
		<div class="qodef-m-stamp-video">
			
			<div class="qodef-m-video-stamp">
				<?php
				$stamp_params = array(
					'words_number'   => '4',
					'text_color' => '#fff',
					'stamp_size'     => '194',
					'text_font_size' => '18px',
					'text'           => esc_html__( 'PlayPlayPlayPlayPlayPlayPlayPlayPlayPlay', 'etchy-core' )
				);
				
				echo EtchyCoreStampShortcode::call_shortcode( $stamp_params ); ?>
			</div>
			
			<span class="qodef-m-play-inner">
				<span class="qodef-play-icon" <?php echo qode_framework_get_inline_style( $play_button_styles ); ?>>
					<?php echo etchy_play_icon_svg(); ?>
				</span>
			</span>
		</div>
	</a>
<?php } ?>