<?php
$provider        = WPERECCP()->front->listing_provider;
$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );
?>

<div class="property_block_wrap style-3">
	<div class="ft-flex-thumb">
		<?php the_post_thumbnail(); ?>
	</div>
	<div class="pbw-flex">
		<div class="prt-detail-title-desc lstng-pg-title-desc">
			<?php
			if ( $rlisting_status ) {
				foreach ( $rlisting_status as $key => $single_rlisting_status ) {
					?>
					<span class="prt-types sale"><?php echo esc_html( $single_rlisting_status ); ?></span>
					<?php
				}
			}
			?>
			<h3><?php the_title(); ?></h3>
			<span class="lstng-pg-address"><i class="lni-map-marker"></i> <?php echo esc_html( get_post_meta( get_the_ID(), 'wperesds_address', true ) ); ?></span>
			<h3 class="prt-price-fix"><?php echo wp_kses( $provider->get_listing_pricing(), 'code_contxt' ); ?></h3>
			<div class="list-fx-features">
			<div class="listing-card-info-icon">
				<div class="inc-fleat-icon"><img src="<?php echo WPERESDS_ASSETS . '/img/bed.svg'; ?>" width="13" alt="<?php esc_attr( 'bed', 'resido' ); ?>"></div>
				<?php
				echo esc_html( get_post_meta( get_the_ID(), 'wperesds_beds', true ) );
				if ( get_post_meta( get_the_ID(), 'wperesds_beds', true ) == 1 ) {
					echo esc_html__( ' Bed', 'resido' );
				} else {
					echo esc_html__( ' Beds', 'resido' );
				}
				?>
				 </div>
			<div class="listing-card-info-icon">
				<div class="inc-fleat-icon"><img src="<?php echo WPERESDS_ASSETS . '/img/bathtub.svg'; ?>" width="13" alt="<?php esc_attr( 'bath', 'resido' ); ?>"></div>
				<?php
					echo esc_html( get_post_meta( get_the_ID(), 'wperesds_bath', true ) );
				if ( get_post_meta( get_the_ID(), 'wperesds_bath', true ) == 1 ) {
					echo esc_html__( ' Bath', 'resido' );
				} else {
					echo esc_html__( ' Baths', 'resido' );
				}
				?>
				</div>
			<div class="listing-card-info-icon">
				<div class="inc-fleat-icon"><img src="<?php echo WPERESDS_ASSETS . '/img/move.svg'; ?>" width="13" alt="<?php esc_attr( 'sqft', 'resido' ); ?>"></div>
				<?php echo esc_html( get_post_meta( get_the_ID(), 'wperesds_area', true ) ) . ' ' . esc_html__( 'sqft', 'resido' ); ?>
				 </div>
			</div>
		</div>
	</div>
</div>
