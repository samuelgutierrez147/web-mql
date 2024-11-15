<?php if ( isset( $query_result ) && intval( $max_num_pages ) > 1 ) { ?>
	<div class="qodef-m-pagination qodef--infinite-scroll">
		<?php echo qode_framework_icons()->render_icon( 'qodef-infinite-scroll-spinner fa fa-spinner fa-spin', 'font-awesome' ); ?>
	</div>
<?php } ?>