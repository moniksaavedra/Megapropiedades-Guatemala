<?php
class Resido_Scripts {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'resido_enqueue_scripts' ) );
	}
	public function resido_enqueue_scripts() {
		wp_enqueue_script( 'popper', RESIDO_JS_URL . 'popper.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'bootstrap', RESIDO_JS_URL . 'bootstrap.min.js', array( 'jquery' ), time(), true );
		wp_enqueue_script( 'rangeslider', RESIDO_JS_URL . 'rangeslider.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'select2', RESIDO_JS_URL . 'select2.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'jquery-magnific-popup', RESIDO_JS_URL . 'jquery.magnific-popup.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'slick', RESIDO_JS_URL . 'slick.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'slider-bg', RESIDO_JS_URL . 'slider-bg.js', array( 'jquery' ), time(), true );
		wp_enqueue_script( 'lightbox', RESIDO_JS_URL . 'lightbox.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'rasido-custom', RESIDO_JS_URL . 'custom.js', array( 'jquery' ), time(), true );
	}
}
$Resido_Scripts = new Resido_Scripts();
