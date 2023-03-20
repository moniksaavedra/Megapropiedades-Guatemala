<?php
function resido_daynamic_styles() {
	ob_start();
	$resido_daynamic_styles_array = array();

	if ( is_page() ) {
		$featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		if ( $featured_img_url ) {
			$page_breadcrumb_bg             = '
                    .page-breadcrumb {
                        background-image: url(' . esc_url( $featured_img_url ) . ');
                    }
                    ';
			$resido_daynamic_styles_array[] = $page_breadcrumb_bg;
		}
	}
	$resido_daynamic_styles_array_expolord = implode( ' ', $resido_daynamic_styles_array );
	$resido_custom_css                     = ob_get_clean();
	return $resido_daynamic_styles_array_expolord;
}

function resido_get_color_styles() {
	global $resido_options;
	$redix_opt_prefix = 'resido_';
	if ( ( isset( $resido_options[ $redix_opt_prefix . 'primary_color' ] ) ) && ( ! empty( $resido_options[ $redix_opt_prefix . 'primary_color' ] ) ) ) {
		$primary_color = $resido_options[ $redix_opt_prefix . 'primary_color' ];
	} else {
		$primary_color = '';
	}
	ob_start();
	if ( ( isset( $resido_options[ $redix_opt_prefix . 'primary_color' ] ) ) && ( ! empty( $resido_options[ $redix_opt_prefix . 'primary_color' ] ) ) ) {
		?>
		:root {
		--primary: <?php echo esc_attr( $primary_color ); ?>;
		}
		<?php
	}
	$resido_custom_css = ob_get_clean();
	return $resido_custom_css;
}
