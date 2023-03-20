<?php
namespace Resido\Helper\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Repeater;

class Advance_Search extends Widget_Base {



	public function get_name() {
		return 'advance_search';
	}

	public function get_title() {
		return __( 'Advance Search', 'resido-core' );
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
				'label' => esc_html__( 'Form', 'resido-core' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'input_columns',
			array(
				'label'      => __( 'Width', 'resido-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'col' ),
				'range'      => array(
					'col' => array(
						'min'  => 1,
						'max'  => 12,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'col',
					'size' => 6,
				),
			)
		);

		$repeater->add_control(
			'input_type',
			array(
				'label'   => esc_html__( 'Type', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'fa-edit'  => esc_html__( 'Input', 'resido-core' ),
					'fa-check' => esc_html__( 'Select', 'resido-core' ),
				),
				'default' => 'fa-edit',
			)
		);

		$repeater->add_control(
			'input_label',
			array(
				'label'   => esc_html__( 'Label', 'resido-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search for a location', 'resido-core' ),
			)
		);

		$repeater->add_control(
			'item_icon',
			array(
				'label'      => esc_html__( 'Icon', 'resido-core' ),
				'type'       => Controls_Manager::ICONS,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'input_type',
							'operator' => '==',
							'value'    => 'fa-edit',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'input_placeholder',
			array(
				'label'      => esc_html__( 'Placeholder', 'resido-core' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'input_type',
							'operator' => '==',
							'value'    => 'fa-edit',
						),
					),
				),
				'default'    => __( 'Keywords...', 'resido-core' ),
			)
		);

		$repeater->add_control(
			'select_type',
			array(
				'label'      => esc_html__( 'Select', 'resido-core' ),
				'type'       => Controls_Manager::SELECT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'input_type',
							'operator' => '==',
							'value'    => 'fa-check',
						),
					),
				),
				'options'    => array(
					'listing_minprice' => esc_html__( 'Min Price', 'resido-core' ),
					'listing_maxprice' => esc_html__( 'Max Price', 'resido-core' ),
					'listing_cate'     => esc_html__( 'Property Type', 'resido-core' ),
					'rlisting_st'      => esc_html__( 'Property Status', 'resido-core' ),
					'rl_features'      => esc_html__( 'Property Feature', 'resido-core' ),
					'listing_loc'      => esc_html__( 'Property Location', 'resido-core' ),
					'listing_beds'     => esc_html__( 'Bed Rooms', 'resido-core' ),
				),
				'default'    => 'listing_beds',
			)
		);

		$repeater->add_control(
			'select_type_min',
			array(
				'label'      => esc_html__( 'Min Price List', 'resido-core' ),
				'type'       => Controls_Manager::TEXTAREA,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'input_type',
							'operator' => '==',
							'value'    => 'fa-check',
						),
						array(
							'name'     => 'select_type',
							'operator' => '==',
							'value'    => 'listing_minprice',
						),
					),
				),
				'default'    => __( '100,200,300,400,500', 'resido-core' ),
			)
		);

		$repeater->add_control(
			'select_type_max',
			array(
				'label'      => esc_html__( 'Max Price List', 'resido-core' ),
				'type'       => Controls_Manager::TEXTAREA,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'input_type',
							'operator' => '==',
							'value'    => 'fa-check',
						),
						array(
							'name'     => 'select_type',
							'operator' => '==',
							'value'    => 'listing_maxprice',
						),
					),
				),
				'default'    => __( '100,200,300,400,500', 'resido-core' ),
			)
		);

		$repeater->add_control(
			'select_type_bed',
			array(
				'label'      => esc_html__( 'Bed Rooms', 'resido-core' ),
				'type'       => Controls_Manager::TEXTAREA,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'input_type',
							'operator' => '==',
							'value'    => 'fa-check',
						),
						array(
							'name'     => 'select_type',
							'operator' => '==',
							'value'    => 'listing_beds',
						),
					),
				),
				'default'    => __( '1,2,3,4,5', 'resido-core' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'   => esc_html__( 'Fields', 'resido-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'input_placeholder' => esc_html__( 'Keywords...', 'resido-core' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'typo',
			array(
				'label' => esc_html__( 'Form', 'resido-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'public_title_typography',
				'label'    => __( 'Typography', 'resido-core' ),
				'selector' => '{{WRAPPER}} .typo-title-text',
			)
		);

		$this->add_control(
			'public_title_color',
			array(
				'label'     => __( 'Color', 'resido-core' ),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .typo-title-text' => 'color: {{VALUE}} !important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'submit_typography',
				'label'    => __( 'Typography', 'resido-core' ),
				'selector' => '{{WRAPPER}} input.search_sbmtfrm',
			)
		);

		$this->add_control(
			'submit_clr',
			array(
				'label'     => __( 'Color', 'plugin-domain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input.search_sbmtfrm' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'submit_bg',
			array(
				'label'     => __( 'Background', 'plugin-domain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input.search_sbmtfrm' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$listing_option = resido_listing_option();
		?>
		<!-- search Form -->
		<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( $listing_option['listing_slug'] . '/?type=2' ) ); ?>">
			<div class="hero-search-content side-form">
				<div class="row">
					<input type="hidden" name="search" value="advanced">
					<?php
					$i = 1;
					foreach ( $settings['items'] as $item ) {
						$input_type    = $item['input_type'];
						$input_label   = $item['input_label'];
						$input_columns = $item['input_columns'];

						$column = 'col-sm-12 col-md-' . $input_columns['size'];

						$input_placeholder = $item['input_placeholder'];
						$item_icon         = $item['item_icon'];
						$select_type       = $item['select_type'];
						$select_type_min   = $item['select_type_min'];
						$select_type_max   = $item['select_type_max'];
						$select_type_bed   = $item['select_type_bed'];

						// Conditions
						if ( $select_type == 'listing_loc' ) {
							$input_id = 'cities';
							$null_val = __( 'All Cities', 'resido-core' );
							$taxonomy = 'rlisting_location';
						} elseif ( $select_type == 'listing_cate' ) {
							$input_id = 'ptypes';
							$null_val = __( 'Select Type', 'resido-core' );
							$taxonomy = 'rlisting_category';
						} elseif ( $select_type == 'rlisting_st' ) {
							$input_id = 'pstatus';
							$null_val = __( 'Select Type', 'resido-core' );
							$taxonomy = 'rlisting_status';
						} elseif ( $select_type == 'rl_features' ) {
							$input_id = 'pfeatures';
							$null_val = __( 'Select Features', 'resido-core' );
							$taxonomy = 'rlisting_features';
						} elseif ( $select_type == 'listing_beds' ) {
							$input_id = 'bedrooms';
							$null_val = __( 'Select Beds', 'resido-core' );
							$options  = explode( ',', $select_type_bed );
						} elseif ( $select_type == 'listing_minprice' ) {
							$input_id = 'minprice';
							$null_val = __( 'Min Price', 'resido-core' );
							$options  = explode( ',', $select_type_min );
						} elseif ( $select_type == 'listing_maxprice' ) {
							$input_id = 'maxprice';
							$null_val = __( 'Min Price', 'resido-core' );
							$options  = explode( ',', $select_type_max );
						}
						// Conditions

						if ( $input_type == 'fa-check' ) {
							?>
							<div class="<?php echo $column; ?>">
								<?php
								if ( $input_label ) {
									?>
									<label class="typo-title-text"><?php echo $input_label; ?></label>
									<?php
								}
								?>
								<div class="form-group">
									<select name="<?php echo $select_type; ?>" id="<?php echo $input_id; ?>" class="form-control">
										<option value=""><?php echo $null_val; ?></option>
										<?php
										if ( $select_type == 'listing_loc' || $select_type == 'listing_cate' || $select_type == 'rlisting_st' || $select_type == 'rl_features' ) {
											$rlisting_terms = get_terms(
												array(
													'taxonomy' => $taxonomy,
													'hide_empty' => false,
												)
											);
											if ( ! empty( $rlisting_terms ) ) {
												foreach ( $rlisting_terms as $rlisting_term ) {
													if ( $rlisting_term->parent != 0 ) {
														$parent = get_term_by( 'id', $rlisting_term->parent, $taxonomy );
														echo '<option value="' . $rlisting_term->slug . '">' . $rlisting_term->name . ',' . $parent->name . '</option>';
													} else {
														echo '<option value="' . $rlisting_term->slug . '">' . $rlisting_term->name . '</option>';
													}
												}
											}
										} elseif ( $select_type == 'listing_beds' || $select_type == 'listing_minprice' || $select_type == 'listing_maxprice' ) {
											var_dump( $options );
											foreach ( $options as $key => $option ) {
												?>
												<option value="<?php echo $option; ?>"><?php echo $option; ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
							</div>
						<?php } else { ?>
							<div class="<?php echo $column; ?>">
								<div class="form-group">
									<?php
									if ( $input_label ) {
										?>
										<label class="typo-title-text"><?php echo $input_label; ?></label>
										<?php
									}
									?>
									<div class="input-with-icon">
										<input type="text" name="s" class="form-control" placeholder="<?php echo $input_placeholder; ?>">
										<?php \Elementor\Icons_Manager::render_icon( ( $item_icon ), array( 'aria-hidden' => 'true' ) ); ?>
									</div>
								</div>
							</div>
							<?php
						}
						$i++;
					}
					?>
				</div>
			</div>
			<div class="hero-search-action">
				<input type="submit" class="btn search-btn search_sbmtfrm" id="searchsubmit" value="<?php _e( 'Search Result', 'resido-core' ); ?>" />
			</div>
		</form>
		<?php
	}
	protected function content_template() {     }
}
