<?php
namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use \Elementor\Repeater;
use Elementor\Group_Control_Typography;

class Smart_Testimonials extends Widget_Base
{


	public function get_name()
	{
		return 'smart_testimonials';
	}

	public function get_title()
	{
		return __('Smart Testimonials', 'resido-core');
	}
	public function get_icon()
	{
		return 'sds-widget-ico';
	}
	public function get_categories()
	{
		return array('resido');
	}

	public function get_script_depends()
	{
		return array('smart_testimonials');
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
				'label'     => __('Title', 'resido-core'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'column',
			array(
				'label'   => esc_html__('Column', 'resido-core'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1' => esc_html__('1', 'resido-core'),
					'2' => esc_html__('2', 'resido-core'),
					'3' => esc_html__('3', 'resido-core'),
				),
				'default' => 'col-lg-4 col-md-4',

			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_author',
			array(
				'label'   => esc_html__('Author', 'resido'),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'item_designation',
			array(
				'label'   => esc_html__('Designation', 'resido'),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label'   => esc_html__('Rating', 'resido'),
				'type'    => Controls_Manager::NUMBER,
				'default' => __('5', 'resido'),
			)
		);

		$repeater->add_control(
			'item_content',
			array(
				'label'   => esc_html__('Content', 'resido'),
				'type'    => Controls_Manager::TEXTAREA,
			)
		);

		$repeater->add_control(
			'item_background',
			array(
				'label'   => esc_html__('BG Image', 'resido-core'),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),

			)
		);

		$this->add_control(
			'items',
			array(
				'label'   => esc_html__('Repeater List', 'resido'),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'item_content' => esc_html__('Title #1', 'resido'),
					),
					array(
						'item_content' => esc_html__('Title #2', 'resido'),
					),
				),
			)
		);

		$this->end_controls_section();

		resido_public_heading_control($this, 'h4');

		resido_public_title_control($this, 'p');

		//Typography Section

		$this->start_controls_section(
			'typography_section',
			array(
				'label' => __('Typography Section', 'resido'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_typography',
				'label'    => __('Author Name ', 'resido'),
				'selector' => '{{WRAPPER}} .st-author-info .st-author-title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'designation_typography',
				'label'    => __('Designation', 'resido'),
				'selector' => '{{WRAPPER}} .st-author-info .st-author-subtitle',
			)
		);


		$this->end_controls_section();

		//Color Section

		$this->start_controls_section(
			'color_section',
			array(
				'label' => __('Color Section', 'resido'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'author_color',
			array(
				'label'     => __('Author Name Color', 'resido'),
				'separator' => 'before',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .st-author-info .st-author-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'designation_color',
			array(
				'label'     => __('Designation Color', 'resido'),
				'separator' => 'before',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .st-author-info .st-author-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
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

		$column = $settings['column'];
?>

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

				<div class="row justify-content-center">

					<div class="col-lg-12 col-md-12">

						<div class="smart-textimonials smart-center" id="smart-textimonials" data-column="<?php echo esc_html($column); ?>">
							<?php
							$i = 1;
							foreach ($settings['items'] as $item) {
								$rating        		 = $item['rating'];
								$item_content        = $item['item_content'];
								$item_author         = $item['item_author'];
								$item_designation    = $item['item_designation'];
								$item_background     = ($item['item_background']['id'] != '') ? wp_get_attachment_image($item['item_background']['id'], 'full') : $item['item_background']['url'];
								$item_background_alt = get_post_meta($item['item_background']['id'], '_wp_attachment_image_alt', true);
							?>
								<!-- Single Item -->
								<div class="item">
									<div class="item-box">
										<div class="smart-tes-author">
											<div class="st-author-box">
												<div class="st-author-thumb">
													<?php
													if (wp_http_validate_url($item_background)) {
													?>
														<img src="<?php echo esc_url($item_background); ?>" class="img-fluid" alt="<?php esc_url($item_background_alt); ?>">
													<?php
													} else {
														echo $item_background;
													}
													?>
												</div>
											</div>
										</div>

										<div class="smart-tes-content">
											<?php if (isset($rating) && !empty($rating)) { ?>
												<div class="rating">
													<?php for ($x = 1; $x <= $rating; $x++) { ?>
														<i class="fas fa-star"></i>
													<?php } ?>
												</div>
											<?php } ?>

											<p><?php echo $item_content; ?></p>
										</div>

										<div class="st-author-info">
											<h4 class="st-author-title"><?php echo $item_author; ?></h4>
											<span class="st-author-subtitle"><?php echo $item_designation; ?></span>
										</div>
									</div>
								</div>


							<?php
								$i++;
							}
							?>


						</div>
					</div>

				</div>
			</div>
		</section>

<?php
	}
	protected function content_template()
	{
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Smart_Testimonials());
