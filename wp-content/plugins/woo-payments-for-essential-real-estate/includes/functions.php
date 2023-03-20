<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_listing_add_to_cart', 'listing_add_to_cart' );
add_action( 'wp_ajax_nopriv_listing_add_to_cart', 'listing_add_to_cart' );
function listing_add_to_cart() {

	global $woocommerce;
	if ( ! $woocommerce || ! $woocommerce->cart ) {
		return $_POST['product_id'];
	}

	if ( ! isset( $_POST['product_id'] ) ) {
		die();
	}


	WC()->session->set( 'custom_price' . (int) $_POST['product_id'], ( $_POST['price'] ) );
	$cart_items     = $woocommerce->cart->get_cart();
	
	$woo_cart_param = array(
		'product_id'     => $_POST['product_id'],
		'quantity'       => $_POST['quantity'],
		'custom_price'   => $_POST['price'],
	);

	$woo_cart_id = $woocommerce->cart->generate_cart_id( $woo_cart_param['product_id'], null, array(), $woo_cart_param );

	if ( array_key_exists( $woo_cart_id, $cart_items ) ) {
		$woocommerce->cart->set_quantity( $woo_cart_id, $_POST['quantity'] );
	} else {
		$woocommerce->cart->add_to_cart( $woo_cart_param['product_id'], $woo_cart_param['quantity'], null, array(), $woo_cart_param );
	}
	$woocommerce->cart->calculate_totals();
	// Save cart to session
	$woocommerce->cart->set_session();
	// Maybe set cart cookies
	$woocommerce->cart->maybe_set_cart_cookies();
	echo 'success';
	wp_die();
}