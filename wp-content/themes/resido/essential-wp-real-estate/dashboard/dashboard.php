<?php
$current_user = wp_get_current_user();

$value       = get_query_var( 'dashboard' );
$editlisting = get_query_var( 'editlisting' );
$editagency  = get_query_var( 'editagency' );
$editagent   = get_query_var( 'editagent' );



$messageactive        = '';
$profileactive        = '';
$listingsactive       = '';
$bookmarkedactive     = '';
$changepasswordactive = '';
$agencyactive         = '';
$agentactive          = '';
$dashactive           = '';
if ( 'message' === $value ) {
	$messageactive = 'active';
} elseif ( 'profile' === $value ) {
	$profileactive = 'active';
} elseif ( 'listings' === $value ) {
	$listingsactive = 'active';
} elseif ( 'bookmarked' === $value ) {
	$bookmarkedactive = 'active';
} elseif ( 'changepassword' === $value ) {
	$changepasswordactive = 'active';
} elseif ( 'agency' === $value ) {
	$agencyactive = 'active';
} elseif ( 'agent' === $value ) {
	$agentactive = 'active';
} else {
	$dashactive = 'active';
}
?>
<section class="gray">
<div class="container-fluid dashboard">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-12">
			<div class="simple-sidebar sm-sidebar" id="filter_search">
				<div class="d-user-avater">
					<?php
					echo cl_get_current_avatar();
					?>
					<h4><?php echo esc_html( $current_user->display_name ); ?></h4>
					<span> </span>
				</div>
				<div class="d-navigation">
					<ul>
						<li class="<?php echo esc_attr( $dashactive ); ?>"><a href="<?php echo site_url( 'dashboard' ); ?>"><i class="ti-dashboard"></i><?php esc_html_e( 'Dashboard', 'resido' ); ?></a></li>
						<li class="<?php echo esc_attr( $profileactive ); ?>"><a href="<?php echo site_url( 'dashboard/?dashboard=profile' ); ?>"><i class="ti-user"></i><?php esc_html_e( 'My Profile', 'resido' ); ?></a></li>
						<li class="<?php echo esc_attr( $messageactive ); ?>"><a href="<?php echo site_url( 'dashboard/?dashboard=message' ); ?>"><i class="ti-email"></i><?php esc_html_e( ' Messages', 'resido' ); ?></a></li>
						<li class="<?php echo esc_attr( $agencyactive ); ?>"><a href="<?php echo site_url( 'dashboard/?dashboard=agency' ); ?>"><i class="ti-home"></i><?php esc_html_e( ' Agency', 'resido' ); ?></a></li>
						<li class="<?php echo esc_attr( $agentactive ); ?>"><a href="<?php echo site_url( 'dashboard/?dashboard=agent' ); ?>"><i class="ti-user"></i><?php esc_html_e( '  Agent', 'resido' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_page_link( cl_admin_get_option( 'cl_add_listing' ) ) ); ?>"><i class="ti-plus"></i><?php esc_html_e( 'Add Listing', 'resido' ); ?></a></li>
						<li class="<?php echo esc_attr( $listingsactive ); ?>"><a href="<?php echo site_url( 'dashboard/?dashboard=listings' ); ?>"><i class="ti-layers-alt"></i><?php esc_html_e( 'My Listings', 'resido' ); ?></a></li>
						<li class="<?php echo esc_attr( $bookmarkedactive ); ?>"><a href="<?php echo site_url( 'dashboard/?dashboard=bookmarked' ); ?>"><i class="ti-heart"></i><?php esc_html_e( 'Bookmarked Listings', 'resido' ); ?></a></li>
						<li class="<?php echo esc_attr( $changepasswordactive ); ?>"><a href="<?php echo site_url( 'dashboard/?dashboard=changepassword' ); ?>"><i class="ti-heart"></i><?php esc_html_e( 'Change Password', 'resido' ); ?></a></li>
						<li><a href="<?php echo esc_url( wp_logout_url( site_url() ) ); ?>"><i class="ti-power-off"></i><?php esc_html_e( 'Log Out', 'resido' ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-9 col-md-12 col-sm-12">
			<?php
			if ( 'message' === $value ) {
				cl_get_template( 'dashboard/message.php' );
			} elseif ( 'profile' === $value ) {
				cl_get_template( 'dashboard/profile.php' );
			} elseif ( 'listings' === $value && $editlisting ) {
				cl_get_template( 'dashboard/editlisting.php' );
			} elseif ( 'agency' === $value && $editagency ) {
				cl_get_template( 'dashboard/editagency.php' );
			} elseif ( 'agent' === $value && $editagent ) {
				cl_get_template( 'dashboard/editagent.php' );
			} elseif ( 'listings' === $value ) {
				cl_get_template( 'dashboard/listings.php' );
			} elseif ( 'bookmarked' === $value ) {
				cl_get_template( 'dashboard/favourites.php' );
			} elseif ( 'changepassword' === $value ) {
				cl_get_template( 'dashboard/changepassword.php' );
			} elseif ( 'agency' === $value ) {
				cl_get_template( 'dashboard/agency.php' );
			} elseif ( 'agent' === $value ) {
				cl_get_template( 'dashboard/agent.php' );
			} else {
				cl_get_template( 'dashboard/overview.php' );
			}
			?>
		</div>
	</div>
</div>
</div>
