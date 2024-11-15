<?php

if ( ( $icon_type == 'icon-pack' ) && ! empty( $main_icon ) ) {
	echo EtchyCoreIconShortcode::call_shortcode( $icon_params );
}