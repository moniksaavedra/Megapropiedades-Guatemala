<?php
class Resido_Act {

	public function __construct() {
		$this->resido_register_action();
	}
	private function resido_register_action() {
		add_action( 'resido_search', array( 'Resido_Int', 'resido_search' ) );
		add_action( 'resido_preloader', array( 'Resido_Int', 'resido_preloader' ) );
		add_action( 'resido_breadcrumb', array( 'Resido_Int', 'resido_breadcrumb' ) );
		add_filter( 'resido_blog_social', array( 'Resido_Int', 'resido_blog_social' ) );
		add_filter( 'resido_authore_box', array( 'Resido_Int', 'resido_authore_box' ) );
	}
}
$resido_act = new Resido_Act();
