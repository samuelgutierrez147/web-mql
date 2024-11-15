<?php
$image_size = isset ( $image_size ) && ! empty( $image_size ) ? $image_size : '80';

echo get_avatar( $author_params['ID'], $image_size );