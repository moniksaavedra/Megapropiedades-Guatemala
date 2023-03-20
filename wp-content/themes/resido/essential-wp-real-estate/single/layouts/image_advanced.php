<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$provider = WPERECCP()->front->listing_provider;
$value    = $provider->get_meta_data( $args['id'], get_the_ID() );
$masonry  = false;

if ( ! empty( $value ) ) {
	echo '<div class="grid-wrapper front_gallery_block">';
	foreach ( $value as $item ) {
		$image_url = wp_get_attachment_image_url( $item, array( '350', '350' ) );
		if ( $masonry == true ) {
			$rand_class = array( 'small', 'big' );
			$key        = array_rand( $rand_class );
			echo '<div class="gallery-item ' . esc_attr( $rand_class[ $key ] ) . '"><a href="' . esc_url( $image_url ) . '" class="mfp-gallery">' . wp_get_attachment_image( $item, array( '350', '350' ) ) . '</a></div>';
		} else {
			echo '<div class="gallery-item"><a href="' . esc_url( $image_url ) . '" class="mfp-gallery">' . wp_get_attachment_image( $item, array( '350', '350' ), '', array( 'rel' => 'lightbox' ) ) . '</a></div>';
		}
	}
	echo '</div>';
}
