<?php
if ( array_key_exists( 'value', $args['field_data'] ) ) {
	$value = $args['field_data']['value'];
} else {
	$value = '';
}
$package_price = '';
$plan_type = '';
$current_user  = wp_get_current_user();
$package_args  = array(
	'post_type'      => 'cl_payment',
	'posts_per_page' => 1,
	'meta_query'     => array(
		array(
			'key'   => '_cl_payment_user_email',
			'value' => $current_user->data->user_email,
		),
	),
);
$package_query = new \WP_Query( $package_args );
if ( $package_query->posts ) {
	foreach ( $package_query->posts as $key => $post ) {
		$package_name = get_post_meta( $post->ID, '_cl_payment_meta', true );
		$package_id   = $package_name['cart_details'][0]['id'];
		$package_price   = $package_name['cart_details'][0]['price'];
	}
}

$woocommerce_active = cl_admin_get_option( 'woocommerce_active' ) != 1 ? false : true;
if($woocommerce_active == '1' && class_exists( 'WooCommerce' )){
	$customer_orders = wc_get_orders( array(
		'meta_key' => '_customer_user',
		'meta_value' => $current_user->ID,
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
			$plan_type = get_post_meta($package_id,'resido_plan_type',true);
		}
	}
}

if($package_price == '' || $plan_type == 'free-pln'){
	$max_characters = 'maxlength="300"';
	$max_characters_title = esc_html__('(For free plan max characters 300)','essential-wp-real-estate');
}else{
	$max_characters = '';
	$max_characters_title = '';
}
?>
<div class="column form-group col-md-12" data-group_field_id="<?php echo esc_attr( $args['field_data']['id'] ); ?>">
	<label for="<?php echo 'add_' . esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['field_data']['name'] ).' '.$max_characters_title; ?></label>
	<textarea id="<?php echo 'add_' . esc_attr( $args['id'] ); ?>" class="form-control cl_add_field" name="<?php echo esc_attr( $args['id'] ); ?>" id="" cols="30" rows="10" <?php echo $max_characters;?>><?php echo esc_attr( $value ); ?></textarea>
</div>
