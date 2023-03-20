<?php

namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use \Elementor\Repeater;

class Download_App extends Widget_Base
{


	public function get_name()
	{
		return 'download_app';
	}

	public function get_title()
	{
		return __('Download App Section', 'resido-core');
	}
	public function get_icon()
	{
		return 'sds-widget-ico';
	}
	public function get_categories()
	{
		return array('resido');
	}

	public function get_city()
	{
		$uchildren = get_terms('rlisting_location', array('hide_empty' => 0));
		$roptions  = array();
		foreach ($uchildren as $state) {
			$roptions[$state->term_id] = $state->name;
			if ($state->parent != 0) {
				$roptions[$state->term_id] = $state->name;
			}
		}

		return $roptions;
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
				'type'  => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label' => __('Title', 'resido-core'),
				'type'  => \Elementor\Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'content',
			array(
				'label'     => __('Content', 'resido-core'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__('Image', 'resido-core'),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),

			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_icon',
			array(
				'label' => esc_html__('Icon', 'resido-core'),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$repeater->add_control(
			'button_label',
			array(
				'label' => __('Label', 'resido-core'),
				'type'  => \Elementor\Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'button_sub_label',
			array(
				'label'   => __('Sub text', 'resido-core'),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __('Download', 'resido-core'),
			)
		);

		$repeater->add_control(
			'button_link',
			array(
				'label'         => esc_html__('Button Link', 'resido-core'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://your-link.com', 'plugin-domain'),
				'show_external' => true,
				'conditions'    => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'button_label',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
				'default'       => array(
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				),

			)
		);

		$this->add_control(
			'items',
			array(
				'label'   => esc_html__('Repeater List', 'resido-core'),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'list_title'   => esc_html__('Title #1', 'resido-core'),
						'list_content' => esc_html__('Item content. Click the edit button to change this text.', 'resido-core'),
					),
					array(
						'list_title'   => esc_html__('Title #2', 'resido-core'),
						'list_content' => esc_html__('Item content. Click the edit button to change this text.', 'resido-core'),
					),
				),
			)
		);

		$this->end_controls_section();

		resido_public_heading_control($this, 'p');

		resido_public_title_control($this, 'h2');

		resido_public_content_control($this, 'p');
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

		// content Func inp
		$public_content_tag = $settings['public_content_tag'];
		$content            = $settings['content'];
		$this->add_render_attribute('content', 'class', 'typo-content-text');
		$this->add_inline_editing_attributes('content', 'none');
		// content Func inp

		$image     = ($settings['image']['id'] != '') ? wp_get_attachment_image($settings['image']['id'], 'full') : $settings['image']['url'];
		$image_alt = get_post_meta($settings['image']['id'], '_wp_attachment_image_alt', true);
?>
		<!-- ========================== Download App Section =============================== -->
		<section>
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-7 col-md-12 col-sm-12 content-column">
						<div class="content_block_2">
							<div class="content-box">
								<div class="sec-title light">
									<!-- Header Func outp -->
									<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string('heading'); ?>>
										<?php echo $heading; ?>
									</<?php echo $public_header_tag; ?>>
									<!-- Title Func outp -->
									<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string('title'); ?>>
										<?php echo $title; ?>
									</<?php echo $public_title_tag; ?>>
								</div>
								<div class="text">
									<!-- Title Func outp -->
									<<?php echo $public_content_tag; ?> <?php echo $this->get_render_attribute_string('content'); ?>>
										<?php echo $content; ?>
									</<?php echo $public_content_tag; ?>>
								</div>
								<div class="btn-box clearfix mt-5">

									<?php
									$i = 1;
									foreach ($settings['items'] as $item) {
										$item_icon    = $item['item_icon'];
										$button_label = $item['button_label'];

										$button_link = $item['button_link']['url'];
										if (!empty($button_link)) {
											$this->add_render_attribute('button_link' . $i, 'href', $button_link);
											if ($item['button_link']['is_external']) {
												$this->add_render_attribute('button_link' . $i, 'target', '_blank');
											}
											if (!empty($item['button_link']['nofollow'])) {
												$this->add_render_attribute('button_link' . $i, 'rel', 'nofollow');
											}
										}
									?>

										<a class="download-btn play-store" <?php echo $this->get_render_attribute_string('button_link' . $i); ?>>
											<?php \Elementor\Icons_Manager::render_icon(($item_icon), array('aria-hidden' => 'true')); ?>
											<span><?php echo $item['button_sub_label']; ?></span>
											<h3><?php echo $button_label; ?></h3>
										</a>

									<?php
										$i++;
									}
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-5 col-md-12 col-sm-12 image-column">
						<div class="image-box">
							<figure class="image">
								<?php
								if (wp_http_validate_url($image)) {
								?>
									<img src="<?php echo esc_url($image); ?>" class="img-fluid" alt="<?php esc_url($image_alt); ?>">
								<?php
								} else {
									echo $image;
								}
								?>
							</figure>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- ========================== Download App Section =============================== -->



<?php
	}
	protected function content_template()
	{
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Download_App());
