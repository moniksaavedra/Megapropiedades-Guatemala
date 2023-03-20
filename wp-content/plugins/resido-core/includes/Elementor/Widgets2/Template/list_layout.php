<?php
$term_name       = wp_get_object_terms( get_the_ID(), 'listings_property', array( 'fields' => 'names' ) );
$term_ID         = wp_get_object_terms( get_the_ID(), 'listings_property' );
$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );

if ( isset( $settings['column'] ) && $settings['column'] ) {
	$column = $settings['column'];
} else {
	$column = 'col-md-12';
}
$provider     = WPERECCP()->front->listing_provider;
$galleryImage = $provider->get_meta_data( 'wperesds_gallery' );
$special_listing_data = resido_special_listing();
$package_class = (isset($special_listing_data["package_class"]) ? $special_listing_data["package_class"] : "");
$package_badge = (isset($special_listing_data["package_badge"]) ? $special_listing_data["package_badge"] : "");
?>
<div class="single-items <?php echo esc_attr( $column ).' '.$package_class; ?>">
	<div class="property-listing property-1">
		<div class="thumbnail-section lazy-section">
			<div class="wperesds-thumb-sec top-left">
				<?php 
				echo $package_badge;
				?>
			</div>
		</div>
		<div class="listing-img-wrapper">
			<a href="<?php the_permalink(); ?>">
				<?php
				if ( has_post_thumbnail( get_the_ID() ) ) {
					$featured_img_url = get_the_post_thumbnail_url( get_the_ID() );
					?>
					<img src="<?php echo esc_url( $featured_img_url ); ?>" class="img-fluid mx-auto" alt="img" />
				<?php } else { ?>
					<img src="<?php echo RESIDO_IMG_URL . 'placeholder.png'; ?>" alt="">
				<?php } ?>
			</a>
		</div>
		<div class="listing-content">
			<div class="listing-detail-wrapper-box">
				<div class="listing-detail-wrapper">
					<div class="listing-short-detail">
						<h4 class="listing-name"><a href="<?php esc_url( the_permalink() ); ?>"><?php the_title(); ?></a></h4>
						<div class="listing-short-detail">
							<div class="fr-can-rating" data-rating="5">
								<?php
								// $average        = resido_get_average_rate( get_the_ID() );
								// $averageRounded = ceil( $average );
								// if ( $averageRounded ) {
								// $active_comment_rate = $averageRounded;
								// for ( $x = 1; $x <= $active_comment_rate; $x++ ) {
								// echo '<i class="fa fa-star filled"></i>';
								// }
								// $inactive_comment_rate = 5 - $active_comment_rate;
								// if ( $inactive_comment_rate > 0 ) {
								// for ( $x = 1; $x <= $inactive_comment_rate; $x++ ) {
								// echo '<i class="fa fa-star"></i>';
								// }
								// }
								// if ( get_comments_number() == 1 ) {
								// echo '<span class="reviews_text">(' . get_comments_number() . ' Review' . ')</span>';
								// } else {
								// echo '<span class="reviews_text">(' . get_comments_number() . ' Reviews' . ')</span>';
								// }
								// }
								?>
							</div>
						</div>
						<?php
						if ( $rlisting_status ) {
							foreach ( $rlisting_status as $key => $rlisting_state ) {
								?>
								<span class="prt-types sale"><?php echo $rlisting_state; ?></span>
								<?php
							}
						}
						?>
					</div>
					<div class="list-price">
						<h6 class="listing-card-info-price">
							<?php echo wp_kses( $provider->get_listing_pricing(), 'code_contxt' ); ?>
						</h6>
					</div>
				</div>
			</div>
			<div class="price-features-wrapper">
				<div class="list-fx-features">
					<?php if ( get_post_meta( get_the_ID(), 'wperesds_beds', true ) ) { ?>
						<div class="listing-card-info-icon">
							<div class="inc-fleat-icon">
								<img src="<?php echo RESIDO_IMG_URL . 'bed.svg'; ?>" width="13" alt="" />
							</div>
							<?php
							echo esc_html( get_post_meta( get_the_ID(), 'wperesds_beds', true ) );
							echo esc_html__( ' Beds', 'resido-core' );
							?>
						</div>
						<?php
					}
					if ( get_post_meta( get_the_ID(), 'wperesds_bath', true ) ) {
						?>
						<div class="listing-card-info-icon">
							<div class="inc-fleat-icon">
								<img src="<?php echo RESIDO_IMG_URL . 'bathtub.svg'; ?>" width="13" alt="" />
							</div>
							<?php
							echo esc_html( get_post_meta( get_the_ID(), 'wperesds_bath', true ) );
							if ( get_post_meta( get_the_ID(), 'wperesds_bath', true ) == 1 ) {
								echo esc_html__( ' Bath', 'resido-core' );
							} else {
								echo esc_html__( ' Baths', 'resido-core' );
							}
							?>
						</div>
						<?php
					}
					if ( get_post_meta( get_the_ID(), 'wperesds_area', true ) ) {
						?>
						<div class="listing-card-info-icon">
							<div class="inc-fleat-icon">
								<img src="<?php echo RESIDO_IMG_URL . 'move.svg'; ?>" width="13" alt="" />
							</div>
							<?php
								echo esc_html( get_post_meta( get_the_ID(), 'wperesds_area', true ) ) . ' ' . esc_html__( 'sqft', 'resido-core' );
							?>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="listing-footer-wrapper">
				<?php if ( get_post_meta( get_the_ID(), 'wperesds_address', true ) ) { ?>
					<div class="listing-locate">
						<span class="listing-location"><i class="ti-location-pin"></i>
						<?php echo esc_html( get_post_meta( get_the_ID(), 'wperesds_address', true ) ); ?>
						</span>
					</div>
				<?php } ?>
				<div class="listing-detail-btn">
					<a href="<?php esc_url( the_permalink() ); ?>" class="more-btn"><?php esc_html_e( 'View', 'resido-core' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
