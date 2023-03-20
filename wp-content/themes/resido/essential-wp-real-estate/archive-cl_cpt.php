<?php
/**
 * The template for displaying listing content
 *
 * This template can be overridden by copying it to yourtheme/wp-essential-real-estate/archive-cl_cpt.php.
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

// query vars
$listing_layout = get_query_var( 'editlisting' );
if ( ! empty( $listing_layout ) ) { // query vars
	$layoput_arr = explode( '_', $listing_layout );
	$layout      = '';
	$style       = '';
	if ( isset( $layoput_arr[0] ) ) {
		$style = $layoput_arr[0];
	}
	if ( isset( $layoput_arr[1] ) ) {
		$layout = $layoput_arr[1];
	}


	if ( $layout != 'sidebar' ) {
		$layout_sidebar = 0;
	} else {
		$layout_sidebar = 1;
	}
	$layout_view = '';
	$grid_class  = '';
	$list_class  = '';
	if ( $style == 'list' ) {
		$layout_view = 'list';
		$list_class  = 'active';
		$grid_class  = null;
	} elseif ( $style == 'grid' ) {
		$layout_view = 'grid';
		$grid_class  = 'active';
		$list_class  = null;
	}
} else {
	$layout_view    = 'list';
	$list_class     = 'active';
	$grid_class     = null;
	$layout_sidebar = 1;
}

$listingstyle = get_query_var( 'layout' );


global $pref;

$LISTING_Query = WPERECCP()->front->query->get_listing_query();


if ( is_active_sidebar( 'listing-sidebar' ) && $layout_sidebar == 1 ) {
	$column_class = 'col-lg-8';
} else {
	$column_class = 'col-lg-12';
}

$map_data = wp_json_encode(
	array(
		'enable_geolocation' => cl_admin_get_option( 'enable_geolocation' ) != 1 ? false : true,
		'default_latitude'   => cl_admin_get_option( 'default_latitude' ),
		'default_longitude'  => cl_admin_get_option( 'default_longitude' ),
		'default_zoom'       => cl_admin_get_option( 'default_zoom' ),
		'geo_markup'         => esc_html__( 'You are within {{radius_value}} meters from this point', 'resido' ),
	)
);
$value    = utf8_encode( json_encode( $map_data ) );

$map_layout = '';
$map_var    = get_query_var( 'map_var' );
if ( ! empty( $map_var ) && $map_var == 1 ) {
	$map_layout = 1;
}
?>
<?php if ( $map_layout == 1 ) { ?>
<div class="mb-4" id="map" data-map_data="<?php echo esc_attr( $map_data ); ?>" style="height: 600px;"></div>
<?php } ?>
<section class="gray cl_listing_archive">
	<div class="container">
		<!-- Sorter Section -->
		<?php
		do_action( $pref . 'before_listing_content' );
		?>
		<div class="row">
			<!-- Sidebar Section -->
			<?php

			if ( is_active_sidebar( 'listing-sidebar' ) && $layout_sidebar == 1 ) {
				do_action( $pref . 'get_sidebar_template' );
			}
			?>
			<!-- Looping Section -->
			<div class="<?php echo esc_attr( $column_class ); ?>">
				<?php
				cl_get_template( 'inc/sorter.php' );
				do_action( $pref . 'before_listing_loop' );

				if ( $LISTING_Query->have_posts() ) {
					while ( $LISTING_Query->have_posts() ) {
						$LISTING_Query->the_post();

						if ( $listing_layout == 'list_sidebar' || $listing_layout == 'list' || $listingstyle == 'list' ) {
							$type = 'list';
						} else {
							$type = 'grid';
						}
						do_action( $pref . 'listing_loop', $type );
					}
				} else {
					do_action( $pref . 'no_listings_found' );
				}
				do_action( $pref . 'after_listing_loop' );
				?>
				<div class="col-lg-12">
					<div class="pagination-wrapper">
						<?php cl_get_template( 'inc/pagination.php' ); ?>
					</div>
				</div>
			</div>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>

<?php
do_action( $pref . 'after_listing_content_archive' );
do_action( $pref . 'after_listing_content' );
get_footer();
