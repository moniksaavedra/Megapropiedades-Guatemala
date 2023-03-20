<?php

namespace Resido\Helper\Elementor;

/**
 * The Menu handler class
 */

class Scripts {


	public function __construct() {
		 add_action( 'elementor/frontend/after_register_scripts', array( $this, 'resido_core_required_script' ) );
		add_action( 'wp_head', array( $this, 'widget_assets_css' ) );
		add_action( 'wp_footer', array( $this, 'widget_scripts' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'widget_editor_scripts' ) );

		add_action( 'wp_ajax_nopriv_resido_contact', array( $this, 'process_contact_form' ) );
		add_action( 'wp_ajax_resido_contact', array( $this, 'process_contact_form' ) );
	}

	public function resido_core_required_script() {
		 wp_register_script( 'sds-contact-script', RESIDO_CORE_ASSETS . '/elementor/js/contact.js', array( 'jquery' ), time(), true );
		$ajax_url = admin_url( 'admin-ajax.php' );
		wp_localize_script( 'sds-contact-script', 'venus', array( 'ajax_url' => $ajax_url ) );
	}
	public function widget_assets_css() {
		// wp_enqueue_style( 'elementor-custom-style', RESIDO_CORE_ASSETS . '/elementor/css/style.css', true );
	}

	public function widget_scripts() {
		wp_enqueue_script( 'smart_testimonials', RESIDO_CORE_ASSETS . '/elementor/js/smart_testimonials.js', array( 'jquery' ), time(), true );
		wp_enqueue_script( 'explore_property_slide', RESIDO_CORE_ASSETS . '/elementor/js/explore_property_slide.js', array( 'jquery' ), time(), true );
		wp_enqueue_script( 'home_banner_slider', RESIDO_CORE_ASSETS . '/elementor/js/home_banner_slider.js', array( 'jquery' ), time(), true );
	}

	public function widget_editor_scripts() {   }

	public function process_contact_form() {
		if ( wp_verify_nonce( $_POST['nonce'], 'resido_contact' ) ) {

			$messages = array();
			$formdata = $_POST['formdata'];
			foreach ( $formdata as $key => $val ) {
				$messages[] = $val;
			}

			$val      = array();
			$text_msg = array();
			$val_i    = 0;
			foreach ( $messages as $value ) {
				if ( $val_i <= 5 ) {
					$val[] = $value['name'] . ': ' . $value['value'] . "\r\n";
				} else {
					$text_msg[] = $value['name'] . ': ' . $value['value'] . "\r\n";
				}
				$val_i++;
			}

			$headers = explode( ':', $val[1] );
			$headers = $headers[1];
			$email   = explode( ':', $val[2] );
			$email   = $email[1];
			$subject = explode( ':', $val[3] );
			$subject = $subject[1];

			$text_msg = implode( '', $text_msg );

			$attachement = $_FILES['attachment']['name'];

			wp_mail( $email, $subject, $text_msg, $headers, $attachement );
			echo 'sent';
		}
		die();
	}
}
