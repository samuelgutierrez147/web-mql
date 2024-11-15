<article <?php post_class( $item_classes ); ?>>
	<div class="qodef-e-inner">
		<div class="qodef-e-meddata">
			<?php
			// Include post media
			if ( $enable_date !== 'no' && etchy_has_post_media() ) {
				etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/media', 'date-on-image' );
			} else {
				etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/media' );
			}
			?>
		</div>
		<div class="qodef-e-content" <?php ! empty( $content_styles ) ? qode_framework_inline_style( $content_styles ): ''; ?>>
			<div class="qodef-e-info qodef-info--top">
				<?php
				// Include post category info
				if ( $enable_category !== 'no' ) {
					etchy_core_theme_template_part( 'blog', 'templates/parts/post-info/category' );
				}

				// Include post date info
				if ( $enable_date !== 'no' && ! etchy_has_post_media() ) {
					etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/date' );
				}
				?>
			</div>
			<div class="qodef-e-text">
				<?php
				// Include post title
				etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/title', '', $params );
				
				// Include post excerpt
				if ( $enable_excerpt !== 'no' ) {
					etchy_core_theme_template_part( 'blog', 'templates/parts/post-info/excerpt', '', $params );
				}
				
				// Hook to include additional content after blog single content
				if ( $enable_content !== 'no' ) {
					do_action( 'etchy_action_after_blog_single_content' );
				}
				?>
			</div>
			<div class="qodef-e-info qodef-info--bottom">
				<?php if ( $enable_button !== 'no' ) { ?>
					<div class="qodef-e-info-left">
						<?php // Include post read more
						etchy_core_theme_template_part( 'blog', 'templates/parts/post-info/read-more' ); ?>
					</div>
				<?php } ?>
				<?php if ( $enable_share !== 'no' ) { ?>
					<div class="qodef-e-info-right">
						<?php // Include social share
						etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/social-share', '', $params ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</article>