<article <?php post_class( 'qodef-blog-item qodef-e' ); ?>>
	<div class="qodef-e-inner">
		<div class="qodef-e-meddata">
			<?php
			// Include post media
			if ( etchy_has_post_media() ) {
				etchy_template_part( 'blog', 'templates/parts/post-info/media', 'date-on-image' );
			} else {
				etchy_template_part( 'blog', 'templates/parts/post-info/media' );
			}
			?>
		</div>
		<div class="qodef-e-content">
			<div class="qodef-e-info qodef-info--top">
				<?php
				// Include post category info
				etchy_template_part( 'blog', 'templates/parts/post-info/category' );

				// Include post date info
				if ( ! etchy_has_post_media() ) {
					etchy_template_part( 'blog', 'templates/parts/post-info/date' );
				}
				?>
			</div>
			<div class="qodef-e-text">
				<?php
				// Include post title
				etchy_template_part( 'blog', 'templates/parts/post-info/title' );
				
				// Include post content
				the_content();
				
				// Hook to include additional content after blog single content
				do_action( 'etchy_action_after_blog_single_content' );
				?>
			</div>
			<div class="qodef-e-info qodef-info--bottom">
				<div class="qodef-e-info-left">
					<?php
					// Include post tags info
					etchy_template_part( 'blog', 'templates/parts/post-info/tags' );
					?>
				</div>
				<?php if ( class_exists( 'EtchyCoreSocialShareShortcode' ) ) { ?>
					<div class="qodef-e-info-right">
						<?php
						// Include social share
						etchy_template_part( 'blog', 'templates/parts/post-info/social-share' );
						?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</article>