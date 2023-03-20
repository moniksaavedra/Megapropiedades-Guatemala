<?php
// Loading Script for media load
wp_enqueue_media();
$key = '';
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
	$max_characters_title = esc_html__('(For free plan max 5 images can set to gallery)','essential-wp-real-estate');
}else{
	$max_characters_title = '';
}
?>
<div class="form-group col-md-12">
	<div class="mb_gal__container" data-key="<?php echo esc_attr( $key ); ?>">
		<label for="<?php echo 'add_' . esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['field_data']['name'] ).' '.$max_characters_title; ?></label>
		<div data-field_id="<?php echo esc_attr( $args['field_data']['id'] ); ?>" id="<?php echo esc_attr( $args['field_data']['id'] ) . $key . '_cont'; ?>" class="components-responsive-wrapper">
			<?php
			if ( isset( $value ) && ! empty( $value ) ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $attachment_id ) {
						echo '<div id="' . esc_attr( $attachment_id ) . '" class="single_img"><input type="hidden" name="' . esc_attr( $args['id'] ) . '[]" value="' . esc_attr( $attachment_id ) . '"><img id="' . esc_attr( $attachment_id ) . '" src="' . wp_get_attachment_url( $attachment_id ) . '" width="150" height="150"><a data-img_id="' . esc_attr( $attachment_id ) . '" class="cl-remove" href="javascript:void(0)">X</a></div>';
					}
				} else {
				}
			} else {
				?>
				<img id="<?php echo esc_attr( $args['field_data']['id'] ) . $key; ?>" class="cl_mb_placeholder" src="<?php echo WPERESDS_ASSETS . '/img/placeholder.png'; ?>" alt="<?php esc_attr_e( 'Placeholder', 'essential-wp-real-estate' ); ?>">
			<?php } ?>
		</div>
		<div class="button_control">
			<button data-name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['field_data']['id'] ) . $key; ?>" type="button" class="mb_btn mb_img_upload_btn"><?php echo esc_html__( 'Upload Images', 'essential-wp-real-estate' ); ?></button>
			<button data-placeholder="<?php echo WPERESDS_ASSETS . '/img/placeholder.png'; ?>" id="<?php echo esc_attr( $args['field_data']['id'] ) . $key; ?>" type="button" class="mb_btn cl_mb_clear_btn"><?php echo esc_html__( 'Clear', 'essential-wp-real-estate' ); ?></button>
		</div>
	</div>
</div>
