<?php
add_action( 'wp_enqueue_scripts', 'resido_child_enqueue_styles', PHP_INT_MAX);
function resido_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_parent_theme_file_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ) );
}

add_action('resido_special_listing_hook','resido_special_listing_hook_func');
function resido_special_listing_hook_func(){
	global $post;
	$author_id = $post->post_author;
	$listing_user = get_the_author_meta($author_id);
	$listing_user_email = get_the_author_meta( 'email', $author_id );
	
	$package_args  = array(
		'post_type'      => 'cl_payment',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'   => '_cl_payment_user_email',
				'value' => $listing_user_email,
			),
		),
	);

	
	$package_name = '';
	$package_price = '';
	$plan_type = '';
	$package_query = new \WP_Query( $package_args );
	if ( $package_query->posts ) {
		foreach ( $package_query->posts as $key => $package ) {
			$package_data = get_post_meta( $package->ID, '_cl_payment_meta', true );
			$package_id   = $package_data['cart_details'][0]['id'];
			$package_price   = $package_data['cart_details'][0]['price'];
			$package_name   = $package_data['cart_details'][0]['name'];

			$plan_type = get_post_meta($package_id,'resido_plan_type',true);
		}
	}

	$woocommerce_active = cl_admin_get_option( 'woocommerce_active' ) != 1 ? false : true;
	if($woocommerce_active == '1' && class_exists( 'WooCommerce' )){
		$customer_orders = wc_get_orders( array(
			'meta_key' => '_customer_user',
			'meta_value' => $author_id,
			'post_status' => 'wc-completed',
			'numberposts' => 1
		) );
	
		if($customer_orders){
			foreach($customer_orders as $order ){
				$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
			}
	
			$woo_order = new WC_Order($order_id);
			$order_user = $woo_order->get_user();
			$items = $woo_order->get_items();
			$package_start = $woo_order->get_date_completed();
			
			$product_id = '';
			$product_name = '';
			foreach ($items as $item) {
				$package_id = $item->get_product_id();
				$package_name = $item->get_name();
				$product = wc_get_product( $package_id );
				$package_price = $product->get_price();
				$plan_type = get_post_meta($package_id,'resido_plan_type',true);
			}
		}
	}


	$special_listing_class = array();
	if($plan_type == 'standard-pln'){
		$special_listing_class['package_class'] = 'blue_listing';
		$special_listing_class['package_badge'] = '<span class="package-badge">'.esc_html__('OPORTUNIDAD').'</span>';
	}elseif($plan_type == 'platinum-pln'){
		$special_listing_class['package_class'] = 'orange_listing';
		$special_listing_class['package_badge'] = '<span class="package-badge">'.esc_html__('SUPER OFERTA').'</span>';;
	}
	return $special_listing_class;
}



/**
 * Auto Complete all WooCommerce orders.
 */
add_action( 'woocommerce_thankyou', 'resido_child_woocommerce_auto_complete_order' );
function resido_child_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );

	// Iterating through each "line" items in the order      
	foreach ($order->get_items() as $item_id => $item ) {
		// Get an instance of corresponding the WC_Product object
		$product        = $item->get_product();
		$active_price   = $product->get_price(); 
		$package_id = $item->get_product_id();
		$plan_type = get_post_meta($package_id,'resido_plan_type',true);
	}

	if($plan_type == 'free-pln'){
		$order->update_status( 'completed' );
	}
    
}

add_action( 'woocommerce_order_button_html', 'resido_child_woocommerce_order_button_html_func' );
function resido_child_woocommerce_order_button_html_func() { 

	global $current_user;

	$customer_orders = wc_get_orders( array(
		'meta_key' => '_customer_user',
		'meta_value' => $current_user->ID,
		// 'post_status' => 'wc-completed','wc-processing','wc-on-hold',
		'numberposts' => 1
	) );


	$order_button_text = 'Realizar el pedido';

	if(!empty($customer_orders)){
		echo '<p class="has_purchased">'.esc_html__('Ya has comprado Plan').'</p>';
	}else{
		echo '<button type="submit" class="button alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>';
	}

}
