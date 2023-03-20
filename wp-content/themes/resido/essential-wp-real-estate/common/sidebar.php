<?php
/**
 * The template for displaying listing single sidebar
 *
 * @see     https://docs.wp-essential-real-estate.com/document/template-structure/
 * @package wp-essential-real-estate/Templates
 * @version 1.0.0
 */

global $pref;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $args ) && $args == 'single' ) {
	if ( ! is_active_sidebar( 'listing-single' ) ) {
		return;
	}
} else {
	if ( ! is_active_sidebar( 'listing-sidebar' ) ) {
		return;
	}
}
?>
<div class="listing-sidebar col-lg-4">
	<div class="details-sidebar">
		<?php
		if ( isset( $args ) && $args == 'single' ) {
			$listing_purchase = cl_admin_get_option( 'listing_purchase' );
			
			if($listing_purchase == '1'){
				?>
				<div class="like_share_wrap b-0">
				<?php
				do_action( $pref . 'listing_cart' );
				?>
				</div>
				<?php
			}
			?>
			<div class="like_share_wrap b-0">
				<ul class="like_share_list">
					<?php
					do_action( $pref . 'listing_share' );
					do_action( $pref . 'listing_favourite' );
					?>
				</ul>
				<?php do_action( $pref . 'listing_compare' ); ?>
			</div>
			<?php
			dynamic_sidebar( 'listing-single' );
		} else {
			dynamic_sidebar( 'listing-sidebar' );
		}
		?>
	</div>
</div>
