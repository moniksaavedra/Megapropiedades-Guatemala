<?php
namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use \Elementor\Repeater;

class Property_Location extends Widget_Base {


	public function get_name() {
		return 'property_location';
	}

	public function get_title() {
		return __( 'Property Location', 'resido-core' );
	}
	public function get_icon() {
		return 'sds-widget-ico';
	}
	public function get_categories() {
		return array( 'resido' );
	}

	public function get_location( $type ) {
		$get_rlistings_location = get_terms( 'rlisting_location', array( 'hide_empty' => 0 ) );
		$roptions               = array();
		foreach ( $get_rlistings_location as $state ) {
			if ( $type == 'state' ) {
				if ( $state->parent == '' ) {
					$roptions[ $state->term_id ] = $state->name;
				}
			} else {
				if ( $state->parent != '' ) {
					$roptions[ $state->term_id ] = $state->name;
				}
			}
		}

		return $roptions;
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
				'label' => __( 'Header', 'resido-core' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => __( 'Title', 'resido-core' ),
				'separator' => 'after',
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
					'col-lg-4 col-md-6' => esc_html__( '3', 'resido-core' ),
					'col-lg-3 col-md-6' => esc_html__( '4', 'resido-core' ),
				),
				'default' => 'col-lg-4 col-md-4',

			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'location_type',
			array(
				'label'   => esc_html__( 'Location Type', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'state' => esc_html__( 'State', 'resido-core' ),
					'city'  => esc_html__( 'City', 'resido-core' ),
				),
				'default' => 'city',

			)
		);

		$repeater->add_control(
			'state',
			array(
				'label'      => __( 'State', 'resido-core' ),
				'type'       => Controls_Manager::SELECT2,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'location_type',
							'operator' => '==',
							'value'    => 'state',
						),
					),
				),
				'options'    => $this->get_location( 'state' ),
			)
		);

		$repeater->add_control(
			'city',
			array(
				'label'      => __( 'City', 'resido-core' ),
				'type'       => Controls_Manager::SELECT2,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'location_type',
							'operator' => '==',
							'value'    => 'city',
						),
					),
				),
				'options'    => $this->get_location( 'city' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'  => esc_html__( 'Repeater List', 'resido-core' ),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			)
		);

		$this->add_control(
			'show_btn',
			array(
				'label'        => __( 'Bottom Button', 'plugin-domain' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'your-plugin' ),
				'label_off'    => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default'      => false,
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'      => __( 'Label', 'resido-core' ),
				'separator'  => 'before',
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'show_btn',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'         => esc_html__( 'Button Link', 'resido-core' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'plugin-domain' ),
				'show_external' => true,
				'conditions'    => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'button_label',
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'show_btn',
							'operator' => '==',
							'value'    => 'yes',
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

		resido_public_heading_control( $this, 'h4' );

		resido_public_title_control( $this, 'p' );

		// Color Section

		$this->start_controls_section(
			'color_section',
			array(
				'label' => __( 'Color Section', 'resido' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_title_color',
			array(
				'label'     => __( 'Button Title Color', 'resido' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-theme-light-2:hover, .btn.btn-theme-light-2:focus, .btn.btn-theme-light-2' => 'color: {{VALUE}}!important',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => __( 'Button BG Color', 'resido' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-theme-light-2:hover, .btn.btn-theme-light-2:focus, .btn.btn-theme-light-2' => 'background: {{VALUE}}!important',
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

		$button_label = $settings['button_label'];
		if ( $button_label ) {
			$button_link = $settings['button_link']['url'];
			if ( ! empty( $button_link ) ) {
				$this->add_render_attribute( 'button_link', 'href', $button_link );
				if ( $settings['button_link']['is_external'] ) {
					$this->add_render_attribute( 'button_link', 'target', '_blank' );
				}
				if ( ! empty( $settings['button_link']['nofollow'] ) ) {
					$this->add_render_attribute( 'button_link', 'rel', 'nofollow' );
				}
			}
		}

		?>

		<!-- ============================ Property Location Start ================================== -->
		<section>
			<div class="container">

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

						if ( $item['location_type'] == 'state' ) {
							$location_id = $item['state'];
						} else {
							$location_id = $item['city'];
						}

						$city = get_term_by( 'id', $location_id, 'rlisting_location' );

						if ( $city ) {
							if ( $city->parent ) {
								$parent            = get_term_by( 'id', $city->parent, 'rlisting_location' );
								$repeater_location = $city->name . ', ' . $parent->name;
							} else {
								$repeater_location = $city->name;
							}
							?>

							<div class="<?php echo $settings['column']; ?>">
								<div class="location-property-wrap">
									<div class="location-property-thumb">
										<a href="<?php echo get_term_link( $city ); ?>">
											<?php
											$cat_img_values = get_term_meta( $location_id, 'custom_meta_img' );
											foreach ( $cat_img_values as $key => $cat_img_value ) {
												$img_value = \RWMB_Image_Field::file_info( $cat_img_value, array( 'size' => 'full' ) );
												if ( $img_value['url'] ) {
													echo '<img src="' . $img_value['url'] . '" class="img-fluid" alt="">';
												}
											}
											?>
										</a>
									</div>
									<div class="location-property-content">
										<div class="lp-content-flex">
											<h4 class="lp-content-title"><?php echo $repeater_location; ?></h4>
											<?php
											if ( $city->count <= 1 ) {
												echo sprintf( '%d ' . __( 'property', 'resido-core' ), $city->count );
											} else {
												echo sprintf( '%d ' . __( 'properties', 'resido-core' ), $city->count );
											}
											?>

										</div>
										<div class="lp-content-right">
											<a href="<?php echo get_term_link( $city ); ?>" class="lp-property-view"><i class="ti-angle-right"></i></a>
										</div>
									</div>
								</div>
							</div>

							<?php
							$i++;
						}
					}
					?>
				</div>
				<?php if ( $button_label ) { ?>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 text-center">
							<a class="btn btn-theme-light-2 rounded" <?php echo $this->get_render_attribute_string( 'button_link' ); ?>><?php echo $button_label; ?></a>
						</div>
					</div>
				<?php } ?>

			</div>
		</section>
		<!-- ============================ Property Location End ================================== -->

		<?php
	}
	protected function content_template() {
	}
}
