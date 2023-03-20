<?php
function resido_sidebar_list()
{
	$sidebars_list   = array();
	$get_all_sidebar = $GLOBALS['wp_registered_sidebars'];
	if (!empty($get_all_sidebar)) {
		foreach ($get_all_sidebar as $sidebar) {
			$sidebars_list[$sidebar['id']] = $sidebar['name'];
		}
	}
	return $sidebars_list;
}

add_filter('rwmb_meta_boxes', 'resido_core_page_meta_box');
function resido_core_page_meta_box($meta_boxes)
{

	$prefix     = 'resido_core';
	$posts_page = get_option('page_for_posts');
	if (!isset($_GET['post']) || intval($_GET['post']) != $posts_page) {
		$meta_boxes[] = array(
			'id'       => $prefix . '_page_wiget_meta_box',
			'title'    => esc_html__('Post Widget Settings', 'resido-core'),
			'pages'    => array(
				'page',
			),
			'context'  => 'normal',
			'priority' => 'core',
			'fields'   => array(
				array(
					'id'      => "{$prefix}_en_dis_breadcrumb",
					'name'    => esc_html__('Enable Breadcrumb', 'resido-core'),
					'desc'    => '',
					'type'    => 'radio',
					'std'     => '1',
					'options' => array(
						'1' => 'Enable',
						'2' => 'Disable',
					),
				),

				array(
					'id'      => "{$prefix}_breadcrumb_subtitle",
					'name'    => esc_html__('Breadcrumb Subtitle', 'resido-core'),
					'desc'    => '',
					'type'    => 'text',
				),

				array(
					'id'      => "{$prefix}_page_no_pad",
					'name'    => esc_html__('Full Width', 'resido-core'),
					'desc'    => '',
					'type'    => 'radio',
					'std'     => '2',
					'options' => array(
						'1' => 'Enable',
						'2' => 'Disable',
					),
				),
				array(
					'id'              => "{$prefix}_sidebar_list",
					'name'            => 'Sidebar Select for page',
					'type'            => 'select',
					'options'         => resido_sidebar_list(),
					'multiple'        => false,
					'placeholder'     => 'Select an Item',
					'select_all_none' => false,
				),
				array(
					'id'      => "{$prefix}_framework_page_style",
					'name'    => esc_html__('Sidebar Position', 'resido-core'),
					'desc'    => '',
					'type'    => 'radio',
					'std'     => 'left',
					'options' => array(
						'left'  => 'Left',
						'right' => 'right',
					),
				),
			),
		);

		$meta_boxes[] = array(
			'id'       => $prefix . '_page_meta_box',
			'title'    => esc_html__('Page Design Settings', 'resido-core'),
			'pages'    => array(
				'page',
			),
			'context'  => 'normal',
			'priority' => 'core',
			'fields'   => array(
				array(
					'id'   => "{$prefix}_custom_logo",
					'name' => esc_html__('Custom Logo', 'resido-core'),
					'type' => 'single_image',
				),
			),
		);

		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-gallery',
			'title'     => esc_html__('Manage Gallery Meta Fields', 'resido-core'),
			'pages'     => array(
				'service',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				array(
					'id'              => "{$prefix}_sidebar_list",
					'name'            => 'Sidebar',
					'type'            => 'select',
					'options'         => resido_sidebar_list(),
					'multiple'        => false,
					'placeholder'     => 'Select an Item',
					'select_all_none' => false,
				),
				array(
					'id'      => "{$prefix}_framework_page_style",
					'name'    => esc_html__('Sidebar Position', 'resido-core'),
					'desc'    => '',
					'type'    => 'radio',
					'std'     => 'left',
					'options' => array(
						'left'  => 'Left',
						'right' => 'right',
					),
				),

			),
		);
	}
	return $meta_boxes;
}
add_action('sidebar_left', 'sidebar_left_fun', 99);
function sidebar_left_fun()
{
	$framework_page_style = get_post_meta(get_the_ID(), 'resido_core_framework_page_style', true);
	if ($framework_page_style == 'left') :
		$resido_core_sidebar_list = get_post_meta(get_the_ID(), 'resido_core_sidebar_list', true);
?>
		<?php if ($resido_core_sidebar_list != '') { ?>
			<?php if (is_active_sidebar($resido_core_sidebar_list)) { ?>
				<div class="col-lg-4 col-sm-12">
					<div class="blog-sidebar">
						<?php dynamic_sidebar($resido_core_sidebar_list); ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	<?php
	endif;
}

add_action('sidebar_right', 'sidebar_right_fun', 99);
function sidebar_right_fun()
{
	$framework_page_style = get_post_meta(get_the_ID(), 'resido_core_framework_page_style', true);
	if ($framework_page_style == 'right') :
		$resido_core_sidebar_list = get_post_meta(get_the_ID(), 'resido_core_sidebar_list', true);
	?>
		<?php if ($resido_core_sidebar_list != '') { ?>
			<?php if (is_active_sidebar($resido_core_sidebar_list)) { ?>
				<div class="col-lg-4 col-sm-12">
					<div class="blog-sidebar">
						<?php dynamic_sidebar($resido_core_sidebar_list); ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>

<?php
	endif;
}
