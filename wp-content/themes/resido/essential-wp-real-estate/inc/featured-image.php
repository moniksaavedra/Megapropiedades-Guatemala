<?php
/**
 * Displays the featured image
 *
 * @package WordPress
 * @since 1.0.0
 */

$provider = WPERECCP()->front->listing_provider;
$gallery  = $provider->get_meta_data( 'wperesds_gallery' );

if ( has_post_thumbnail() && $gallery ) {
	?>
	<!-- Slide Section -->
	<div class="listing-img-wrapper">
		<div class="list-img-slide">
			<div class="gallery-slider-active">
				<?php
				$provider->show_thumb( '840', '420' );
				if ( $gallery ) {
					foreach ( $gallery as $value ) {
						echo wp_get_attachment_image( $value, array( '840', '420' ) );
					}
				}
				?>
			</div>
		</div>
	</div>
	<?php
} elseif ( has_post_thumbnail() ) {
	$provider->show_thumb( array( '840', '420' ) );
} else {
	?>
	<!-- Placeholder Section -->
	<div class="listing-img-wrapper">
		<div class="list-img-slide">
			<div class="gallery-slider-active">
				<img src="<?php echo WPERESDS_ASSETS . '/img/placeholder_light.png'; ?>" alt="<?php esc_attr_e( 'Placeholder', 'resido' ); ?>">
			</div>
		</div>
	</div>
	<?php
}
