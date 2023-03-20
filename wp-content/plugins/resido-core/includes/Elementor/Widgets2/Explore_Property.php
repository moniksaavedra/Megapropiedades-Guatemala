<?php
namespace Resido\Helper\Elementor\Widgets2;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

class Explore_Property extends Widget_Base {


	public function get_name() {
		return 'explore_property';
	}

	public function get_title() {
		return __( 'Explore Property', 'resido-core' );
	}
	public function get_icon() {
		return 'sds-widget-ico';
	}
	public function get_categories() {
		return array( 'resido' );
	}

	public function get_script_depends() {
		return array( 'explore_property_slide' );
	}

	private function get_term_list() {
		$rlisting_status = get_terms(
			array(
				'taxonomy'   => 'listing_status',
				'hide_empty' => false,
			)
		);
		$options         = array();
		$options['']     = 'All';
		foreach ( $rlisting_status as $status ) {
			$options[ $status->slug ] = $status->name;
		}
		return $options;
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
			'layout',
			array(
				'label'   => __( 'Layout', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'grid-layout' => __( 'Grid', 'resido-core' ),
					'list-layout' => __( 'List', 'resido-core' ),
				),
				'default' => 'grid-layout',
			)
		);

		$this->add_control(
			'show_as_slide',
			array(
				'label'        => __( 'Slider', 'plugin-domain' ),
				'type'         => Controls_Manager::SWITCHER,
				'conditions'   => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '!=',
							'value'    => 'list-layout',
						),
					),
				),
				'label_on'     => __( 'Yes', 'resido-core' ),
				'label_off'    => __( 'No', 'resido-core' ),
				'return_value' => 'property-slide',
				'default'      => false,
			)
		);

		$this->add_control(
			'show_featured',
			array(
				'label'     => __( 'Featured Listings', 'plugin-domain' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'resido-core' ),
				'label_off' => __( 'No', 'resido-core' ),
				'default'   => false,
			)
		);

		$this->add_control(
			'column',
			array(
				'label'      => esc_html__( 'Column', 'resido-core' ),
				'type'       => Controls_Manager::SELECT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'show_as_slide',
							'operator' => '!=',
							'value'    => 'property-slide',
						),
					),
				),
				'options'    => array(
					'col-lg-12 col-md-12' => esc_html__( '1', 'resido-core' ),
					'col-lg-6 col-md-6'   => esc_html__( '2', 'resido-core' ),
					'col-lg-4 col-md-6'   => esc_html__( '3', 'resido-core' ),
					'col-lg-3 col-md-6'   => esc_html__( '4', 'resido-core' ),
				),
				'default'    => 'col-lg-4 col-md-4',

			)
		);

		$this->add_control(
			'column_slide',
			array(
				'label'      => esc_html__( 'Column', 'resido-core' ),
				'type'       => Controls_Manager::SELECT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'show_as_slide',
							'operator' => '==',
							'value'    => 'property-slide',
						),
					),
				),
				'options'    => array(
					'1' => esc_html__( '1', 'resido-core' ),
					'2' => esc_html__( '2', 'resido-core' ),
					'3' => esc_html__( '3', 'resido-core' ),
					'4' => esc_html__( '4', 'resido-core' ),
				),
				'default'    => '3',

			)
		);

		$this->add_control(
			'property_status',
			array(
				'label'   => esc_html__( 'Property Status', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_term_list(),

			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Posts Per Page', 'resido-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '6',
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => __( 'Order By', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'          => __( 'Date', 'resido-core' ),
					'ID'            => __( 'ID', 'resido-core' ),
					'author'        => __( 'Author', 'resido-core' ),
					'title'         => __( 'Title', 'resido-core' ),
					'modified'      => __( 'Modified', 'resido-core' ),
					'rand'          => __( 'Random', 'resido-core' ),
					'comment_count' => __( 'Comment count', 'resido-core' ),
					'menu_order'    => __( 'Menu order', 'resido-core' ),
				),
			)
		);
		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'desc' => __( 'DESC', 'resido-core' ),
					'asc'  => __( 'ASC', 'resido-core' ),
				),
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
							'name'     => 'show_as_slide',
							'operator' => '!=',
							'value'    => 'property-slide',
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
							'name'     => 'show_as_slide',
							'operator' => '!=',
							'value'    => 'property-slide',
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

		resido_public_title_control( $this, 'h4' );

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
					'{{WRAPPER}} .btn .btn-theme-light-2 rounded' => 'color: {{VALUE}}!important',
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

		$column_slide = $settings['column_slide'];
		$texquery     = array();

		if ( $settings['property_status'] ) {
			$texquery = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'listing_status',
					'field'    => 'slug',
					'terms'    => $settings['property_status'],
				),
			);
		}

		// use in template file
		$order_by       = $settings['order_by'];
		$order          = $settings['order'];
		$posts_per_page = $settings['posts_per_page'];
		$show_as_slide  = $settings['show_as_slide'];
		$show_featured  = $settings['show_featured'];
		$pg_num         = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$args           = array(
			'post_type'      => 'cl_cpt',
			'post_status'    => 'publish',
			'paged'          => $pg_num,
			'posts_per_page' => $posts_per_page,
			'orderby'        => $order_by,
			'order'          => $order,
			'tax_query'      => $texquery,
			'from_addon'     => 'yes',
		);
		if ( isset( $show_featured ) && $show_featured == true ) {
			$args['meta_query'] = array(
				array(
					'key'   => 'featured',
					'value' => 1,
				),
			);
		}
		$custom_query = new \WP_Query( $args );

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
		<!-- ================= Explore Property ================= -->
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

				<div class="row <?php echo $settings['layout'] . ' ' . $show_as_slide; ?>" data-column="<?php echo esc_html( $column_slide ); ?>">
					<?php
					if ( $custom_query->have_posts() ) {
						while ( $custom_query->have_posts() ) {
							$custom_query->the_post();
							$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );
							if ( $settings['layout'] == 'grid-layout' ) {
								include RESIDO_CORE_PATH . '/includes/Elementor/Widgets2/Template/grid_layout.php';
								// cl_get_template( 'wp-essential-real-estate/loop/archive_loop_grid.php' );
							} else {
								include RESIDO_CORE_PATH . '/includes/Elementor/Widgets2/Template/list_layout.php';
								// resido_get_listing_loop_part('listing');
							}
						}
					}
					wp_reset_postdata();
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
		<?php
	}
	protected function content_template() {
	}
}
