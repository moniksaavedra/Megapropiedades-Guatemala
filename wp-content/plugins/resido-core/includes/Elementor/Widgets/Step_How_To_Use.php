<?php
namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

class Step_How_To_Use extends Widget_Base {


	public function get_name() {
		return 'step_how_to_use';
	}

	public function get_title() {
		return __( 'Step How to Use', 'resido-core' );
	}
	public function get_icon() {
		return 'sds-widget-ico';
	}
	public function get_categories() {
		return array( 'resido' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general',
			array(
				'label' => esc_html__( 'General', 'resido-core' ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'     => __( 'Header', 'resido-core' ),
				'separator' => 'before',
				'type'      => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => __( 'Title', 'resido-core' ),
				'separator' => 'before',
				'type'      => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'column',
			array(
				'label'   => esc_html__( 'Column', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'col-lg-6 col-md-6' => esc_html__( '2', 'resido-core' ),
					'col-lg-4 col-md-4' => esc_html__( '3', 'resido-core' ),
					'col-lg-3 col-md-3' => esc_html__( '4', 'resido-core' ),
				),
				'default' => 'col-lg-4 col-md-4',

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item',
			array(
				'label' => esc_html__( 'item', 'resido-core' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_icon',
			array(
				'label' => esc_html__( 'Icon', 'resido-core' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$repeater->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'kidszone-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}} !important',
				),
			)
		);

		$repeater->add_control(
			'icon_size',
			array(

				'label'      => __( 'Width', 'plugin-domain' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$repeater->add_control(
			'mid_arrow',
			array(
				'label'   => esc_html__( 'Arrow Direction', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''      => esc_html__( 'Type 1', 'resido-core' ),
					'pos-2' => esc_html__( 'Type 2', 'resido-core' ),
				),
			)
		);

		$repeater->add_control(
			'post_arrow',
			array(
				'label'        => __( 'Remove Arrow', 'plugin-domain' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Hide', 'resido-core' ),
				'label_off'    => __( 'Show', 'resido-core' ),
				'return_value' => 'arrow-remove',
				'default'      => true,
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'resido-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'item_content',
			array(
				'label'       => esc_html__( 'Content', 'resido-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'default'     => '',
				'placeholder' => esc_html__( 'Type your description here', 'resido' ),

			)
		);

		$this->add_control(
			'items',
			array(
				'label'   => esc_html__( 'Repeater List', 'resido-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'list_title'   => esc_html__( 'Title #1', 'resido-core' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'resido-core' ),
					),
					array(
						'list_title'   => esc_html__( 'Title #2', 'resido-core' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'resido-core' ),
					),
				),
			)
		);

		$this->end_controls_section();

		resido_public_heading_control( $this, 'h4' );

		resido_public_title_control( $this, 'h4' );

		// Typography Section

		$this->start_controls_section(
			'typography_section',
			array(
				'label' => __( 'Typography Section', 'resido' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Item Title', 'resido' ),
				'selector' => '{{WRAPPER}} .middle-icon-features-content h4',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Item Description', 'resido' ),
				'selector' => '{{WRAPPER}} .middle-icon-features-content p',
			)
		);

		$this->end_controls_section();

		// Color Section

		$this->start_controls_section(
			'color_section',
			array(
				'label' => __( 'Color Section', 'resido' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Item Title Color', 'resido' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .middle-icon-features-content h4' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Item Description Color', 'resido' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .middle-icon-features-content p' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Header Func inp
		$public_header_tag = $settings['public_header_tag'];
		$heading           = $settings['heading'];
		$this->add_render_attribute( 'heading', 'class', 'typo-header-text' );
		$this->add_inline_editing_attributes( 'heading', 'none' );
		// Header Func inp

		// title Func inp
		$public_title_tag = $settings['public_title_tag'];
		$title            = $settings['title'];
		$this->add_render_attribute( 'title', 'class', 'typo-title-text' );
		$this->add_inline_editing_attributes( 'title', 'none' );
		// title Func inp
		?>

		<!-- ============================ Step How To Use Start ================================== -->
		<section>
			<div class="container working-process-container">

				<div class="row justify-content-center">
					<div class="col-lg-7 col-md-10 text-center">
						<div class="sec-heading center">
							<!-- Header Func outp -->
							<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
								<?php echo $heading; ?>
							</<?php echo $public_header_tag; ?>>
							<!-- Title Func outp -->
							<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string( 'title' ); ?>>
								<?php echo $title; ?>
							</<?php echo $public_title_tag; ?>>
						</div>
					</div>
				</div>

				<div class="row">
					<?php
					$i = 1;
					foreach ( $settings['items'] as $item ) {
						$item_icon    = $item['item_icon'];
						$item_title   = $item['item_title'];
						$item_content = $item['item_content'];
						$mid_arrow    = $item['mid_arrow'];
						$post_arrow   = $item['post_arrow'];
						?>
					 <div class="<?php echo $settings['column']; ?>">
							<div class="middle-icon-features-item <?php echo $post_arrow; ?>">
								<div class="count"><?php echo esc_html( $i ); ?></div>
								<div class="icon-features-wrap <?php echo $mid_arrow; ?>">
									<?php
									\Elementor\Icons_Manager::render_icon(
										( $item_icon ),
										array(
											'aria-hidden' => 'true',
											'class'       => 'elementor-repeater-item-' . $item['_id'],
										)
									);
									?>
								</div>
								<div class="middle-icon-features-content">
									<h4><?php echo $item_title; ?></h4>
									<p><?php echo $item_content; ?></p>
								</div>
							</div>
						</div> 
						<?php
						$i++;
					}
					?>
				</div>
			</div>
		</section>
		<div class="clearfix"></div>
		<?php
	}
	protected function content_template() {
	}
}
