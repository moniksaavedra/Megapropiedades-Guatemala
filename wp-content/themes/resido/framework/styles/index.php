<?php
class Resido_Style {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'resido_enqueue_style' ) );
	}
	public function resido_enqueue_style() {
		wp_enqueue_style( 'resido-styles', RESIDO_CSS_URL . 'styles.css', false, time() );
		wp_enqueue_style( 'resido-colors', RESIDO_CSS_URL . 'colors.css', false, time() );
		if ( ! class_exists( 'Resido_Listing' ) ) {
			wp_enqueue_style( 'resido-listing-style', RESIDO_CSS_URL . 'listing-style.css', false, time() );
		}
		wp_enqueue_style( 'resido-theme-style', RESIDO_CSS_URL . 'theme-style.css', false, time() );
		if ( function_exists( 'resido_daynamic_styles' ) ) {
			wp_add_inline_style( 'resido-theme-style', resido_daynamic_styles() );
		}
	}
}
$Resido_Style = new Resido_Style();

function resido_custom_css() {
	$resido_custom_inline_style = '';
	if ( function_exists( 'resido_get_color_styles' ) ) {
		$resido_custom_inline_style = resido_get_color_styles();
	}
	wp_add_inline_style( 'resido-theme-style', $resido_custom_inline_style );
}
add_action( 'wp_enqueue_scripts', 'resido_custom_css', 20 );
