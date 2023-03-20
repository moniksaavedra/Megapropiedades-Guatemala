<?php
$sticky_onoff         = resido_get_options('sticky_onoff');
$header_default_style = resido_get_options('header_default_style');
$header_right_menu    = resido_get_options('header_right_menu');
$header_transparent   = resido_get_options('header_transparent');
$header_btn_url       = resido_get_options('header_btn_url');
$header_btn_lebel     = resido_get_options('header_btn_lebel');

if ($sticky_onoff) {
	$sticky_class = '';
} else {
	$sticky_class = 'sticky-off';
}
$get_header_var = get_query_var('header_layout'); // get query var value
if (isset($get_header_var) && $get_header_var != '') {
	$get_header_var     = explode('_', $get_header_var);
	$header_transparent = $get_header_var[1];
}

if ($header_transparent != 0) {
	$header_class 	= 'header-transparent';
	$logo			= 'logo-light.svg';
	$logo_fixed		= 'logo.svg';
} else {
	$header_class 	= 'header-light head-shadow';
	$logo			= 'logo.svg';
	$logo_fixed		= 'logo.svg';
}
$resido_custom_logo             = get_post_meta(get_the_ID(), 'resido_core_custom_logo', array('size' => 'full'));
$resido_metabox_custom_logo_url = wp_get_attachment_image_url($resido_custom_logo, 'full');
?>

<!-- Start Navigation -->
<div class="header change-logo <?php echo esc_attr($header_class . ' ' . $sticky_class); ?>">
	<div class="container">
		<nav id="navigation" class="navigation navigation-landscape">
			<div class="nav-header">
				<?php
				if (function_exists('get_custom_logo') && has_custom_logo()) {
					if ($resido_custom_logo && !is_front_page()) {
				?>
						<a class="nav-brand" href="<?php echo esc_url(home_url('/')); ?>">
							<img src="<?php echo esc_url($resido_metabox_custom_logo_url); ?>" alt="<?php esc_attr_e('Logo', 'resido'); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
						</a>
					<?php
					} else {
						$custom_logo_id = get_theme_mod('custom_logo');
						$image          = wp_get_attachment_image_src($custom_logo_id, 'full');
					?>
						<a class="nav-brand" href="<?php echo esc_url(home_url('/')); ?>">
							<img src="<?php echo esc_url($image[0]); ?>" alt="<?php esc_attr_e('Logo', 'resido'); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
						</a>
					<?php
					}
				} else {
					?>
					<a class="nav-brand static-logo" href="<?php echo esc_url(home_url('/')); ?>">
						<img src="<?php echo esc_url(RESIDO_IMG_URL . $logo); ?>" alt="<?php esc_attr_e('Logo', 'resido'); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
					</a>
					<a class="nav-brand fixed-logo" href="<?php echo esc_url(home_url('/')); ?>">
						<img src="<?php echo esc_url(RESIDO_IMG_URL . $logo_fixed); ?>" alt="<?php esc_attr_e('Logo', 'resido'); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
					</a>
				<?php
				}
				?>
				<div class="nav-toggle"></div>
			</div>
			<div class="nav-menus-wrapper" >
				<?php
				if (has_nav_menu('primary')) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'nav-menu',
							'container'      => 'ul',
						)
					);
				} else {
					wp_nav_menu(
						array(
							'menu_class' => 'nav-menu',
							'container'  => 'ul',
						)
					);
				}
				if ($header_right_menu) {
				?>
					<ul class="nav-menu nav-menu-social align-to-right">
						<?php if (is_user_logged_in()) { ?>
							<li>
								<a href="<?php echo esc_url(site_url('add-listing')); ?>"><img src="<?php echo esc_url(RESIDO_IMG_URL . 'submit.svg'); ?>" alt="<?php esc_attr_e('Submit', 'resido'); ?>" class="mr-2 width20" /><?php esc_html_e('Add Property', 'resido'); ?></a>
							</li>
						<?php } else { ?>
							<li>
								<a href="<?php echo esc_js('javascript:void(0)'); ?>" data-bs-toggle="modal" data-bs-target="#login" class="text-success"><img src="<?php echo esc_url(RESIDO_IMG_URL . 'submit.svg'); ?>" alt="<?php esc_attr_e('Submit', 'resido'); ?>" class="mr-2 width20" /><?php esc_html_e('Add Property', 'resido'); ?></a>
							</li>
						<?php } ?>
						<?php
						if (is_user_logged_in()) {
							do_action('user-dashboard-menu');
						} else {
						?>
							<li class="add-listing">
								<a href="<?php echo esc_js('javascript:void(0)'); ?>" data-bs-toggle="modal" data-bs-target="#login"><img src="<?php echo esc_url(RESIDO_IMG_URL . 'user-light.svg'); ?>" alt="<?php esc_attr_e('Add', 'resido'); ?>" class="mr-2 width12" /><?php esc_html_e('Sign In', 'resido'); ?></a>
							</li>
						<?php } ?>
					</ul>
				<?php } ?>
			</div>
		</nav>
	</div>
</div>
<!-- End Navigation -->
<div class="clearfix"></div>