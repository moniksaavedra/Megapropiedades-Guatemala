<?php
namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use \Elementor\Repeater;

class Call_To_Action extends Widget_Base
{


	public function get_name()
	{
		return 'call_to_action';
	}
	public function get_title()
	{
		return __('CTA Section', 'resido-core');
	}
	public function get_icon()
	{
		return 'sds-widget-ico';
	}
	public function get_categories()
	{
		return array('resido');
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
			'layout',
			array(
				'label'   => esc_html__('Column', 'resido-core'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1' => esc_html__('Layout 1', 'resido-core'),
					'2' => esc_html__('Layout 2', 'resido-core'),
				),
				'default' => '1',

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
				'label'     => __('Title', 'resido-core'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'     => __('Label', 'resido-core'),
				'separator' => 'before',
				'type'      => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
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

		$this->end_controls_section();

		resido_public_heading_control($this, 'h4');

		resido_public_title_control($this, 'p');
		//Color Section

		$this->start_controls_section(
			'color_section',
			array(
				'label' => __('Color Section', 'resido'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_title_color',
			array(
				'label'     => __('Button Title Color', 'resido'),
				'separator' => 'before',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-call-to-act' => 'color: {{VALUE}}!important',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => __('Button BG Color', 'resido'),
				'separator' => 'before',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-call-to-act' => 'background: {{VALUE}}!important',
				),
			)
		);

		$this->end_controls_section();
	}
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$layout = $settings['layout'];

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

		$button_label = $settings['button_label'];
		$button_link  = $settings['button_link']['url'];
		if (!empty($button_link)) {
			$this->add_render_attribute('button_link', 'href', $button_link);
			if ($settings['button_link']['is_external']) {
				$this->add_render_attribute('button_link', 'target', '_blank');
			}
			if (!empty($settings['button_link']['nofollow'])) {
				$this->add_render_attribute('button_link', 'rel', 'nofollow');
			}
		}
		if ($layout == 1) { ?>
			<!-- ============================ Call To Action ================================== -->
			<section class="call-to-act-wrap">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">

							<div class="call-to-act">
								<div class="call-to-act-head">
									<!-- Header Func outp -->
									<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string('heading'); ?>>
										<?php echo $heading; ?>
									</<?php echo $public_header_tag; ?>>
									<!-- Title Func outp -->
									<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string('title'); ?>>
										<?php echo $title; ?>
									</<?php echo $public_title_tag; ?>>
								</div>
								<?php if ($button_label) { ?>
									<a class="btn btn-call-to-act" <?php echo $this->get_render_attribute_string('button_link'); ?>><?php echo $button_label; ?></a>
								<?php } ?>
							</div>

						</div>
					</div>
				</div>
			</section>
			<!-- ============================ Call To Action End ================================== -->
		<?php } else { ?>

			<!-- ============================ Smart Testimonials ================================== -->
			<section class="image-cover">
				<div class="container">
					<div class="row justify-content-center">

						<div class="col-lg-8 col-md-8">

							<div class="caption-wrap-content text-center">
								<!-- Header Func outp -->
								<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string('heading'); ?>>
									<?php echo $heading; ?>
								</<?php echo $public_header_tag; ?>>
								<!-- Title Func outp -->
								<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string('title'); ?>>
									<?php echo $title; ?>
								</<?php echo $public_title_tag; ?>>
								<?php if ($button_label) { ?>
									<a class="btn btn-light btn-md rounded" <?php echo $this->get_render_attribute_string('button_link'); ?>><?php echo $button_label; ?></a>
								<?php } ?>
							</div>
						</div>

					</div>
				</div>
			</section>
			<!-- ============================ Smart Testimonials End ================================== -->
<?php
		}
	}
	protected function content_template()
	{
	}
}
