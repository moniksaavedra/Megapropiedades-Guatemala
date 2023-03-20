<?php
if ( ! is_user_logged_in() ) {
	echo '<p>' . esc_html__( 'Please', 'resido' ) . ' <a href="' . esc_url( get_page_link( cl_admin_get_option( 'login_redirect_page' ) ) ) . '">' . esc_html__( 'Login', 'resido' ) . '</a></p>';
} else {
	global $current_user;
	$udata = get_userdata( $current_user->ID );

	$current_user = wp_get_current_user();
	$package_args = array(
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
	$package_price  = '';
	$package_name  = '';
	if ( $package_query->posts ) {
		foreach ( $package_query->posts as $key => $post ) {
			$package  = get_post_meta( $post->ID, '_cl_payment_meta', true );
			$package_id    = $package['cart_details'][0]['id'];
			$package_name  = $package['cart_details'][0]['name'];
			$package_price  = $package['cart_details'][0]['price'];
			$package_start = $post->post_date;
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
			}
		}
		
		
	}
	

	?>
	<div id="cl-user-overview" class="cl-user-overview">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<?php if ( $package_name ) { ?>
					<h4><?php esc_html_e( 'Your Current Package', 'resido' ); ?> <span class="pc-title theme-cl">- <?php echo esc_html( $package_name ); ?> </span></h4>
				<?php } else { ?>
					<h4><?php esc_html_e( 'Currently No Package Selected', 'resido' ); ?></h4>
				<?php } ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-stat widget-1">
					<div class="dashboard-stat-content">
						<h4><?php echo cl_total_active_listing_by_user(); ?></h4>
						<span><?php esc_html_e( 'Listings Included', 'resido' ); ?></span>
					</div>
					<div class="dashboard-stat-icon"><i class="ti-location-pin"></i></div>
				</div>
			</div>
			<?php
			if ( ! empty( $package_id ) ) {
				$package_listing_count     = get_post_meta( $package_id, 'resido_list_subn_limit', true );
	
				if($package_listing_count != 'unlimited'){
					$package_listing_remaining = $package_listing_count - cl_total_active_listing_by_user();
				}else{
					$package_listing_remaining = $package_listing_count;
				}
				
				?>
			<div class="col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-stat widget-2">
					<div class="dashboard-stat-content">
						<h4><?php echo esc_html( $package_listing_remaining ); ?></h4> 
						<span><?php esc_html_e( 'Listings Remaining', 'resido' ); ?></span>
					</div>
					<div class="dashboard-stat-icon"><i class="ti-pie-chart"></i></div>
				</div>
			</div>
			<?php } ?>
			<div class="col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-stat widget-3">
					<div class="dashboard-stat-content">
						<h4><?php echo cl_listing_total_review(); ?></h4>
						<span><?php esc_html_e( 'Total Reviews', 'resido' ); ?></span>
					</div>
					<div class="dashboard-stat-icon"><i class="ti-user"></i></div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-stat widget-4">
					<div class="dashboard-stat-content">
						<h4><?php echo cl_listing_total_saved(); ?></h4>
						<span><?php esc_html_e( 'Bookmarked', 'resido' ); ?></span>
					</div>
					<div class="dashboard-stat-icon"><i class="ti-bookmark"></i></div>
				</div>
			</div>
			<?php
			if ( ! empty( $package_id ) ) {
				$package_listing_count = get_post_meta( $package_id, 'resido_list_subn_limit', true );
				$expire_duration       = get_post_meta( $package_id, 'resido_plan_expire', true );

				if ( $expire_duration ) {
					$expire_date = date( get_option( 'date_format' ), strtotime( '+' . $expire_duration . 'days', strtotime( str_replace( '/', '-', $package_start ) ) ) ) . PHP_EOL;
				} else {
					$expire_date = esc_html__( 'Never Expire', 'resido' );
				}
				?>
			<div class="col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-stat widget-5">
					<div class="dashboard-stat-content">
						<?php if($package_price == '0'){ ?>
							<h4><?php echo esc_html('5','resido'); ?></h4> <span><?php esc_html_e( 'Images / per listing', 'resido' ); ?></span>
						<?php }else{?>
							<h4><?php echo esc_html('Unlimited','resido'); ?></h4> <span><?php esc_html_e( 'Images / per listing', 'resido' ); ?></span>
						<?php } ?>
					</div>
					<div class="dashboard-stat-icon"><i class="ti-user"></i></div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-stat widget-6">
					<div class="dashboard-stat-content">
						<h4><?php echo esc_html( $expire_date ); ?></h4>
						<span><?php esc_html_e( 'Ends On', 'resido' ); ?></span>
					</div>
					<div class="dashboard-stat-icon"><i class="ti-pie-chart"></i></div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php
}
