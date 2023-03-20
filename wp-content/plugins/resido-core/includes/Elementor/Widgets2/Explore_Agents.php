<?php
namespace Resido\Helper\Elementor\Widgets2;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;

class Explore_Agents extends Widget_Base {


	public function get_name() {
		return 'explore_agents';
	}
	public function get_title() {
		return __( 'Explore Agents', 'resido-core' );
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
			'posts_per_page',
			array(
				'label'   => __( 'Number of Post', 'bebio-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 3,
			)
		);
		$this->add_control(
			'column',
			array(
				'label'   => __( 'Number of Column', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => array(
					'col-md-6' => __( '2', 'resido-core' ),
					'col-md-4' => __( '3', 'resido-core' ),
					'col-md-3' => __( '4', 'resido-core' ),
				),
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
			'_button_title_color',
			array(
				'label'     => __( 'Item Button Title Color', 'resido' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fr-grid-footer-flex-right .prt-view' => 'color: {{VALUE}}!important',
				),
			)
		);

		$this->add_control(
			'item_button_bg_color',
			array(
				'label'     => __( 'Item Button BG Color', 'resido' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fr-grid-footer-flex-right .prt-view' => 'background: {{VALUE}}!important',
				),
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

		$column         = $settings['column'];
		$posts_per_page = $settings['posts_per_page'];
		$order_by       = $settings['order_by'];
		$order          = $settings['order'];
		$pg_num         = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		$args   = array(
			'post_type'      => array( 'listing_agents' ),
			'post_status'    => array( 'publish' ),
			'nopaging'       => false,
			'paged'          => $pg_num,
			'posts_per_page' => $posts_per_page,
			'orderby'        => $order_by,
			'order'          => $order,
		);
		$query  = new \WP_Query( $args );
		$prefix = 'resido_agents_';
		?>
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
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							?>
							<!-- Single Agent -->
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="agents-grid">
									<div class="agents-grid-wrap">
										<div class="fr-grid-thumb">
											<a href="<?php echo esc_url( get_permalink() ); ?>">
												<img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'resido_galleries_home' ); ?>" class="img-fluid mx-auto" alt="<?php esc_attr_e( 'agent-feature-image', 'resido-core' ); ?>" />
											</a>
										</div>
										<div class="fr-grid-deatil">
											<div class="fr-grid-deatil-flex">
												<h5 class="fr-can-name"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h5>
												<?php
												$agent_count_args  = array(
													'post_type' => 'cl_cpt',
													'post_status' => array( 'publish' ),
													'posts_per_page' => -1, // no limit,
													'meta_query' => array(
														array(
															'key' => 'resido_listing_rlagentinfo',
															'value' => get_the_ID(),
															'compare' => '=',
														),
													),
												);
												$agent_count_query = new \WP_Query( $agent_count_args );
												if ( $agent_count_query->post_count > 1 ) {
													$property_text = esc_html__( 'Properties', 'resido-core' );
												} else {
													$property_text = esc_html__( 'Property', 'resido-core' );

												}
												?>
												<span class="agent-property"><?php echo esc_html( $agent_count_query->post_count . ' ' . $property_text ); ?></span>
											</div>
											<div class="fr-grid-deatil-flex-right">
												<div class="agent-email"><a href="mailto:<?php listing_meta_field( $prefix . 'agent_email' ); ?>"><i class="ti-email"></i></a></div>
											</div>
										</div>
									</div>
									<div class="fr-grid-info">
										<ul>
											<li><strong><?php echo esc_html__( 'Call: ', 'resido-core' ); ?></strong><?php listing_meta_field( $prefix . 'agent_cell' ); ?></li>
										</ul>
									</div>
									<div class="fr-grid-footer">
										<div class="fr-grid-footer-flex">
											<span class="fr-position"><i class="lni-map-marker"></i><?php listing_meta_field( $prefix . 'agent_address' ); ?></span>
										</div>
										<div class="fr-grid-footer-flex-right">
											<a href="<?php echo esc_url( get_permalink() ); ?>" class="prt-view" tabindex="0"><?php echo esc_html__( 'View', 'resido-core' ); ?></a>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						wp_reset_postdata();
					}
					?>
				</div>
			</div>
		</section>
		<?php
	}
	protected function content_template() {}
}
