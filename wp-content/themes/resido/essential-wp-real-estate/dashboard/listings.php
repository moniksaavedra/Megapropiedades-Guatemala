<div class="dashboard-wraper">
	<div class="form-submit">
		<h4><?php esc_html_e( 'My Listings', 'resido' ); ?></h4>
	</div>
<?php
global $current_user;
$args               = array(
	'author'         => $current_user->ID,
	'post_type'      => 'cl_cpt',
	'post_status'    => array( 'publish', 'pending', 'draft' ),
	'orderby'        => 'post_date',
	'order'          => 'DESC',
	'posts_per_page' => -1, // no limit,
);
$current_user_posts = get_posts( $args );
$provider           = WPERECCP()->front->listing_provider;
if ( ! empty( $current_user_posts ) ) {
	foreach ( $current_user_posts as $single_post ) {
		$comments  = get_comments( array( 'post_id' => $single_post->ID ) );
		$term_list = wp_get_post_terms( $single_post->ID, 'listings_property' );
		$address   = get_post_meta( $single_post->ID, 'wperesds_address', true );
		$price     = get_post_meta( $single_post->ID, 'wperesds_pricing', true );
		$comments  = get_comments( array( 'post_id' => $single_post->ID ) );
		?>
		<!-- Single Property -->
		<div class="col-md-12 col-sm-12 col-md-12">
		  <div class="singles-dashboard-list">
			<span class="post-status <?php echo esc_attr($single_post->post_status);?>"><?php echo esc_html($single_post->post_status); ?></span>
			<?php
			if ( has_post_thumbnail( $single_post->ID ) ) {
				?>
			  <div class="sd-list-left">
				<?php echo get_the_post_thumbnail( $single_post->ID, array( 240, 180 ) ); ?>
			  </div>
				<?php
			} else {
				?>
			  <div class="sd-list-left">
				  <img src="<?php echo WPERESDS_ASSETS . '/img/placeholder_light.png'; ?>" alt="<?php esc_attr_e( 'Placeholder', 'resido' ); ?>">
			  </div>
			<?php } ?>
			<div class="sd-list-right">
			  <h4 class="listing_dashboard_title"><a href="<?php echo get_permalink( $single_post->ID ); ?>" class="theme-cl"><?php echo esc_html( $single_post->post_title ); ?></a></h4>
			  <div class="user_dashboard_listed">
				<?php
				echo esc_html( 'Price: from ' . WPERECCP()->common->formatting->cl_currency_filter( WPERECCP()->common->formatting->cl_format_amount( $price ) ) );
				?>
			  </div>
			  <?php if ( $term_list ) { ?>
				<div class="user_dashboard_listed">
					<?php echo esc_html( 'Listed in' ); ?>
					<?php foreach ( $term_list as $term ) { ?>
							<a href="<?php echo esc_url( get_term_link( $term->slug, 'listings_property' ) ); ?>" class="theme-cl"><?php echo esc_html( $term->name ); ?></a>
					<?php } ?>
				</div>
			  <?php } ?>
			  <div class="user_dashboard_listed">
				<?php echo esc_html( $address ); ?>
			  </div>
			  <div class="action">
				<a href="<?php echo add_query_arg( 'cl_edit_listing_var', $single_post->ID, get_page_link( cl_admin_get_option( 'cl_edit_listing' ) ) ); ?>" data-toggle="tooltip" data-placement="top" title="Edit Property"><i class="ti-pencil"></i></a>
				<a href="<?php echo get_permalink( $single_post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="202 User View"><i class="ti-eye"></i></a>
				<?php if ( current_user_can( 'administrator' ) ) { ?>
				  <a onclick="return confirm('Do you really want to delete this Listing?')" href="<?php echo get_delete_post_link( $single_post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="Delete Property" class="delete"><i class="ti-close"></i></a>
					<?php
				} else {
					?>
				  <a data-warning="<?php echo esc_attr__( 'Are you sure you want to delete this item?', 'resido' ); ?>" id="delete-listing" data-listing-id="<?php echo esc_attr( $single_post->ID ); ?>" class="delete-listing button gray" href="javascript:void(0);"><i class="ti-close"></i></a>
					<?php
				}
				$featured_state = get_post_meta( $single_post->ID, 'featured', true );
				if ( isset( $featured_state ) && $featured_state == 1 ) {
					$icon_class = 'ti-star';
				} else {
					$icon_class = 'fa fa-star';
				}
				?>
				<a id="make-featured" data-listing-id="<?php echo esc_attr( $single_post->ID ); ?>" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Make Featured"><i class="<?php echo esc_attr( $icon_class ); ?>"></i></a>
			  </div>
			</div>
		  </div>
		</div>
		<?php
	}
} else {
	echo '<p class="messages-headline">';
	echo esc_html__( 'No Listing Found', 'resido' );
	echo '</p>';
}
?>
</div>
