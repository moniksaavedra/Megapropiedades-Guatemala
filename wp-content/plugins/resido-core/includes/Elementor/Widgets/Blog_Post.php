<?php

namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use \Elementor\Repeater;

class Blog_Post extends Widget_Base
{


	public function get_name()
	{
		return 'blog_post';
	}
	public function get_title()
	{
		return __('Blog Post', 'resido-core');
	}
	public function get_icon()
	{
		return 'sds-widget-ico';
	}
	public function get_categories()
	{
		return array('resido');
	}
	private function get_blog_categories()
	{
		$options  = array();
		$taxonomy = 'category';
		if (!empty($taxonomy)) {
			$terms = get_terms(
				array(
					'parent'     => 0,
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);
			if (!empty($terms)) {
				foreach ($terms as $term) {
					if (isset($term)) {
						$options[''] = 'Select';
						if (isset($term->slug) && isset($term->name)) {
							$options[$term->slug] = $term->name;
						}
					}
				}
			}
		}
		return $options;
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'general',
			array(
				'label' => esc_html__('General', 'resido-core'),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label' => __('Header', 'resido-core'),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => __('Title', 'resido-core'),
				'separator' => 'after',
				'type'      => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'category_id',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __('Category', 'resido-core'),
				'options' => $this->get_blog_categories(),
			)
		);
		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __('Number of Post', 'bebio-core'),
				'type'    => Controls_Manager::TEXT,
				'default' => 3,
			)
		);
		$this->add_control(
			'column',
			array(
				'label'   => __('Number of Column', 'resido-core'),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => array(
					'col-md-6' => __('2', 'resido-core'),
					'col-md-4' => __('3', 'resido-core'),
					'col-md-3' => __('4', 'resido-core'),
				),
			)
		);
		$this->add_control(
			'order_by',
			array(
				'label'   => __('Order By', 'resido-core'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'          => __('Date', 'resido-core'),
					'ID'            => __('ID', 'resido-core'),
					'author'        => __('Author', 'resido-core'),
					'title'         => __('Title', 'resido-core'),
					'modified'      => __('Modified', 'resido-core'),
					'rand'          => __('Random', 'resido-core'),
					'comment_count' => __('Comment count', 'resido-core'),
					'menu_order'    => __('Menu order', 'resido-core'),
				),
			)
		);
		$this->add_control(
			'order',
			array(
				'label'   => __('Order', 'resido-core'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'desc' => __('DESC', 'resido-core'),
					'asc'  => __('ASC', 'resido-core'),
				),
			)
		);

		$this->end_controls_section();

		resido_public_heading_control($this, 'h4');

		resido_public_title_control($this, 'p');
	}
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		// Header Func inp
		$public_header_tag = $settings['public_header_tag'];
		$heading           = $settings['heading'];
		$this->add_render_attribute('heading', 'class', 'typo-header-text');
		$this->add_inline_editing_attributes('heading', 'none');
		// Header Func inp

		// title Func inp
		$public_title_tag = $settings['public_title_tag'];
		$title            = $settings['title'];
		$this->add_render_attribute('title', 'class', 'typo-title-text');
		$this->add_inline_editing_attributes('title', 'none');
		// title Func inp

		$column         = $settings['column'];
		$posts_per_page = $settings['posts_per_page'];
		$order_by       = $settings['order_by'];
		$order          = $settings['order'];
		$pg_num         = get_query_var('paged') ? get_query_var('paged') : 1;

		$args  = array(
			'post_type'      => array('post'),
			'post_status'    => array('publish'),
			'nopaging'       => false,
			'paged'          => $pg_num,
			'posts_per_page' => $posts_per_page,
			'orderby'        => $order_by,
			'order'          => $order,
		);
		$query = new \WP_Query($args);

?>

		<!-- ================================= Blog Grid ================================== -->
		<section>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-7 col-md-10 text-center">
						<div class="sec-heading center">
							<!-- Header Func outp -->
							<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string('heading'); ?>>
								<?php echo $heading; ?>
							</<?php echo $public_header_tag; ?>>
							<!-- Title Func outp -->
							<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string('title'); ?>>
								<?php echo $title; ?>
							</<?php echo $public_title_tag; ?>>
						</div>
					</div>
				</div>

				<div class="row">

					<?php
					$i = 0;
					if ($query->have_posts()) {
						while ($query->have_posts()) {
							$i++;
							$query->the_post();
					?>
							<!-- Single blog Grid -->
							<div class="<?php echo $column; ?>">
								<div class="blog-wrap-grid">
									<div class="blog-thumb">
										<a href="<?php echo esc_url(get_permalink()); ?>">
											<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'resido_galleries_home'); ?>" alt="<?php esc_attr_e('blog-feature-image', 'resido-core'); ?>">
										</a>
									</div>
									<div class="blog-info">
										<span class="post-date"><i class="ti-calendar"></i><?php echo get_the_date('M d, Y'); ?></span>
									</div>
									<div class="blog-body">
										<h4 class="bl-title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h4>
										<?php
										if (!empty(get_the_excerpt())) :
											if (get_option('rss_use_excerpt')) {
												the_excerpt();
											} else {
												the_excerpt();
											}
										endif;
										?>
										<a href="<?php echo esc_url(get_permalink()); ?>" class="bl-continue"><?php echo esc_html('Continue'); ?></a>
									</div>

								</div>
							</div>
					<?php
							$i = $i + 2;
						}
						wp_reset_postdata();
					}
					?>


				</div>

			</div>
		</section>
		<!-- ================= Blog Grid End ================= -->

<?php
	}
	protected function content_template()
	{
	}
}
