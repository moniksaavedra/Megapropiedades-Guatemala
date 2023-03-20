<?php
$social_information = resido_get_options('social_information');
$header_right_menu  = resido_get_options('header_right_menu');
$phone_info         = resido_get_options('phone_info');
$email_info         = resido_get_options('email_info');
$header_top_status  = resido_get_options('header_top_status');

$resido_custom_logo             = get_post_meta(get_the_ID(), 'resido_core_custom_logo', array('size' => 'full'));
$resido_metabox_custom_logo_url = wp_get_attachment_image_url($resido_custom_logo, 'full');


$get_header_var = get_query_var('header_layout'); // get query var value
if (isset($get_header_var) && $get_header_var != '') {
	$get_header_var    = explode('_', $get_header_var);
	$header_top_status = $get_header_var[1];
}
?>

<!-- Start Navigation -->
<?php if ($header_top_status) { ?>
	<div class="top-header">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<div class="cn-info">
						<ul>
							<?php if ($phone_info) { ?>
								<li><i class="lni-phone-handset"></i><?php echo esc_html($phone_info); ?></li>
							<?php
							}
							if ($email_info) {
							?>
								<li><i class="ti-email"></i><?php echo esc_html($email_info); ?></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<ul class="top-social">
						<?php echo wp_kses($social_information, 'code_contxt'); ?>
					</ul>
				</div>

			</div>
		</div>
	</div>
<?php } ?>
<div class="header header-light">
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
					<a class="nav-brand" href="<?php echo esc_url(home_url('/')); ?>">
						<img src="<?php echo esc_url(RESIDO_IMG_URL . 'logo.svg'); ?>" alt="<?php esc_attr_e('Logo', 'resido'); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
					</a>
				<?php
				}
				?>
				<div class="nav-toggle"></div>
			</div>
			<div class="nav-menus-wrapper">
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
						<?php
						if (is_user_logged_in()) {
							do_action('user-dashboard-menu');
						} else {
						?>
							<li>
								<a href="<?php echo esc_js('javascript:void(0)'); ?>" data-bs-toggle="modal" data-bs-target="#login" class="text-success">
									<i class="fas fa-user-circle mr-2"></i><?php esc_html_e('Signin', 'resido'); ?></a>
							</li>
						<?php
						}
						if (is_user_logged_in()) {
						?>
							<li class="add-listing theme-bg">
								<a href="<?php echo esc_url(site_url('add-listing')); ?>"><?php esc_html_e('Add Listing', 'resido'); ?></a>
							</li>
						<?php } else { ?>
							<li class="add-listing theme-bg">
								<a href="<?php echo esc_js('javascript:void(0)'); ?>" data-bs-toggle="modal" data-bs-target="#login" class="text-success"><?php esc_html_e('Add Property', 'resido'); ?></a>
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