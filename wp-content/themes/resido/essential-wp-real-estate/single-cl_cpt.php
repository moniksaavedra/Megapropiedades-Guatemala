<?php
/**
 * The template for displaying single listing content
 *
 * This template can be overridden by copying it to yourtheme/wp-essential-real-estate/single-cl_cpt.php.
 *
 * HOWEVER, on occasion wp-essential-real-estate will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.wp-essential-real-estate.com/document/template-structure/
 * @package wp-essential-real-estate/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();


$listing_layout_var = get_query_var( 'editlisting' );


if ( $listing_layout_var ) {
	$listing_layout = $listing_layout_var;
} else {
	$listing_layout = resido_get_options( 'listing_single_layout' );
}


global $pref;
do_action( $pref . 'before_listing_content' );
if ( is_active_sidebar( 'listing-single' ) ) {
	$column_class = 'col-lg-8';
} else {
	$column_class = 'col-lg-12';
}

$provider           = WPERECCP()->front->listing_provider;
$gallery            = $provider->get_meta_data( 'wperesds_gallery' );
$featured_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
if ( $listing_layout != '3' ) {
	if ( has_post_thumbnail() || $gallery ) {
		?>
		<!-- Slide Section -->
		<div class="featured_slick_gallery gray lazy-section">
			<div class="featured_slick_gallery-slide">
			<?php
			if ( $featured_image_url ) {
				echo '<a href="' . esc_url( $featured_image_url ) . '" class="item-slick mfp-gallery">' . get_the_post_thumbnail( get_the_ID(), 'full' ) . '</a>';
			}
			if ( ! empty( $gallery ) ) {
				foreach ( $gallery as $value ) {
					$galary_image_url = wp_get_attachment_image_url( $value, 'full' );
					echo '<a href="' . esc_url( $galary_image_url ) . '" class="item-slick mfp-gallery">';
					echo wp_get_attachment_image( $value, 'full' );
					echo '</a>';
				}
			}
			?>
			</div>
		</div>
		<?php
	}
}
?>
<?php if ( $listing_layout == '2' ) { ?>
<section class="gray-simple rtl p-0">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-11 col-md-12">
			<?php
			cl_get_template( 'single/single-listing-info-2.php' );
			?>
			</div>
		</div>
	</div>
</section>
<?php } elseif ( $listing_layout == '3' ) { ?>
<section class="bg-title">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-11 col-md-12">
			<?php
			cl_get_template( 'single/single-listing-info-3.php' );
			?>
			</div>
		</div>
	</div>
</section>
<?php } ?>
<section class="cl_listing_single">
	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr( $column_class ); ?> cl_listing_single_content">
				<?php
				do_action( $pref . 'before_listing_loop' );
				do_action( $pref . 'listing_loop' );
				do_action( $pref . 'after_listing_loop' );
				?>
			</div>
			<?php
			do_action( $pref . 'get_sidebar_template', 'single' );
			?>
		</div>
	</div>
</section>
<?php
do_action( $pref . 'after_listing_content', array( 'listing_id' => get_the_ID() ) );
get_footer();
