<?php
$term_name       = wp_get_object_terms( get_the_ID(), 'rlisting_category', array( 'fields' => 'names' ) );
$term_ID         = wp_get_object_terms( get_the_ID(), 'rlisting_category' );
$rlisting_status = wp_get_object_terms( get_the_ID(), 'rlisting_status', array( 'fields' => 'names' ) );
$listing_option  = resido_listing_option();

if ( ! isset( $listing_option['currency_symbol'] ) ) {
	$currency = '$';
} else {
	$currency = $listing_option['currency_symbol'];
}

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
?>
<div class="single-items <?php echo esc_attr__( $column, 'resido-core' ); ?>">
	<div class="property-listing property-2 <?php echo $inner_class; ?>">
		<div class="listing-img-wrapper">
			<div class="list-img-slide">
				<div class="click">
					<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
						<div><a href="<?php the_permalink(); ?>"> <?php the_post_thumbnail(); ?></a></div>
					<?php } else { ?>
						<div><a href="<?php the_permalink(); ?>"><img src="<?php echo plugins_url( 'resido-core' ) . '/assets/img/placeholder.png'; ?>" alt=""></a></div>
						<?php
					}
					$galleryImage = listing_meta_field_gallery( 'gallery-image' );
					if ( ! empty( $galleryImage ) ) {
						foreach ( $galleryImage as $image_id ) {
							$image_url = wp_get_attachment_url( $image_id );
							?>
							<div><a href="<?php the_permalink(); ?>">
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
						<a href="<?php the_permalink(); ?>" class="prt-link-detail"><?php the_title(); ?></a>
					</h4>
				</div>
				<div class="listing-short-detail-flex">
					<h6 class="listing-card-info-price">
						<?php resido_currency_html(); ?>
                <?php if (get_listing_meta_field('price_postfix')) { ?>
                    <sub>/<?php listing_meta_field('price_postfix'); ?></sub>
                <?php } ?> 
					</h6>
				</div>
			</div>
		</div>
		<?php
			$beds = esc_html__( ' Beds', 'resido-core' );
		?>

        <div class="price-features-wrapper">
            <div class="list-fx-features">
                <?php if (get_post_meta(get_the_ID(), 'rlisting_bedrooms', true)) { ?>
                    <div class="listing-card-info-icon">
                        <div class="inc-fleat-icon">
                            <img src="<?php echo plugins_url() . '/resido-listing/elementor/assets/img/bed.svg'; ?>" width="13" alt="" />
                        </div>
                        <?php
                        echo esc_html(get_post_meta(get_the_ID(), 'rlisting_bedrooms', true));
                        if (get_post_meta(get_the_ID(), 'rlisting_bedrooms', true) == 1){
                          echo esc_html__(' Bed', 'resido-core');
                            }else{
                                echo esc_html__(' Beds', 'resido-core');  
                            } ?>
                    </div>
                <?php }
                if (get_post_meta(get_the_ID(), 'rlisting_bathrooms', true)) { ?>
                    <div class="listing-card-info-icon">
                        <div class="inc-fleat-icon">
                            <img src="<?php echo plugins_url() . '/resido-listing/elementor/assets/img/bathtub.svg'; ?>" width="13" alt="" />
                        </div>
                        <?php
                        echo esc_html(get_post_meta(get_the_ID(), 'rlisting_bathrooms', true));
                        if (get_post_meta(get_the_ID(), 'rlisting_bathrooms', true) == 1){
                            echo esc_html__(' Bath', 'resido-core');
                        }else{
                            echo esc_html__(' Baths', 'resido-core');  
                        }
                        ?>
                    </div>
                <?php }
                if (get_post_meta(get_the_ID(), 'rlisting_area_size', true)) { ?>
                    <div class="listing-card-info-icon">
                        <div class="inc-fleat-icon">
                            <img src="<?php echo plugins_url() . '/resido-listing/elementor/assets/img/move.svg'; ?>" width="13" alt="" />
                        </div>
                        <?php
                        echo esc_html(get_post_meta(get_the_ID(), 'rlisting_area_size', true)). ' ';
                        listing_meta_field('area_size_postfix');
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="listing-detail-footer">
            <?php if (get_post_meta(get_the_ID(), "rlisting_address", true)) { ?>
                <div class="footer-first">
                    <div class="foot-location">
                        <img src="<?php echo plugins_url() . '/resido-listing/elementor/assets/img/pin.svg'; ?>" width="18" alt="" /><?php listing_meta_field('address'); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="footer-flex">
                <a href="<?php the_permalink(); ?>" class="prt-view"><?php echo esc_html__("View", 'resido-core'); ?></a>
            </div>
        </div>
    </div>
    <!-- End Single Property -->
</div>