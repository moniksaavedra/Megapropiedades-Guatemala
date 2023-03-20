<?php
$term_name       = wp_get_object_terms( get_the_ID(), 'listings_property', array( 'fields' => 'names' ) );
$term_ID         = wp_get_object_terms( get_the_ID(), 'listings_property' );
$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );

// From elementor widget to create border and no-shadow for slide
if ( isset( $show_as_slide ) && ! empty( $show_as_slide ) ) {
	$inner_class = 'shadow-none border';
} else {
	$inner_class = null;
}
if ( isset( $settings['column'] ) && $settings['column'] ) {
	$column = $settings['column'];
} else {
	$column = 'col-lg-12 col-md-12';
}
// From elementor widget to create border and no-shadow for slide
$provider     = WPERECCP()->front->listing_provider;
$galleryImage = $provider->get_meta_data( 'wperesds_gallery' );
$special_listing_data = resido_special_listing();
$package_class = (isset($special_listing_data["package_class"]) ? $special_listing_data["package_class"] : "");
$package_badge = (isset($special_listing_data["package_badge"]) ? $special_listing_data["package_badge"] : "");
?>
<div class="single-items <?php echo esc_attr( $column ).' '.$package_class; ?>">
	<div class="property-listing property-2 <?php echo $inner_class; ?>">
		<div class="thumbnail-section lazy-section">
			<div class="wperesds-thumb-sec top-left">
				<?php 
				echo $package_badge;
				?>
			</div>
		</div>
		<div class="listing-img-wrapper">
			<div class="list-img-slide">
				<div class="gallery-slider-active">
					<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
						<div><a href="<?php esc_url( the_permalink() ); ?>"> <?php the_post_thumbnail(); ?></a></div>
					<?php } else { ?>
						<div><a href="<?php esc_url( the_permalink() ); ?>"><img src="<?php echo RESIDO_IMG_URL . 'placeholder.png'; ?>" alt=""></a></div>
						<?php
					}

					if ( ! empty( $galleryImage ) ) {
						foreach ( $galleryImage as $image_id ) {
							$image_url = wp_get_attachment_url( $image_id );
							?>
							<div><a href="<?php esc_url( the_permalink() ); ?>">
								<img src="<?php echo esc_url( $image_url ); ?>" class="img-fluid mx-auto" alt="" /></a>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<div class="listing-detail-wrapper">
			<div class="listing-short-detail-wrap">
				<div class="listing-short-detail">
					<?php
					if ( $rlisting_status ) {
						foreach ( $rlisting_status as $key => $single_rlisting_status ) {
							?>
							<span class="property-type"><?php echo $single_rlisting_status; ?></span>
						<?php } ?>
					<?php } ?>
					<h4 class="listing-name verified">
						<a href="<?php esc_url( the_permalink() ); ?>" class="prt-link-detail"><?php the_title(); ?></a>
					</h4>
				</div>
				<div class="listing-short-detail-flex">
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
		<div class="listing-detail-footer">
			<?php if ( get_post_meta( get_the_ID(), 'wperesds_address', true ) ) { ?>
				<div class="footer-first">
					<div class="foot-location">
						<img src="<?php echo RESIDO_IMG_URL . 'pin.svg'; ?>" width="18" alt="" />
						<?php echo esc_html( get_post_meta( get_the_ID(), 'wperesds_address', true ) ); ?>
					</div>
				</div>
			<?php } ?>
			<div class="footer-flex">
				<a href="<?php esc_url( the_permalink() ); ?>" class="prt-view"><?php esc_html_e( 'View', 'resido-core' ); ?></a>
			</div>
		</div>
	</div>
	<!-- End Single Property -->
</div>
