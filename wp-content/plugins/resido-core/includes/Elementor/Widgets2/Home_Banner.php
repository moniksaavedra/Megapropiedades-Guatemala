<?php
namespace Resido\Helper\Elementor\Widgets2;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

class Home_Banner extends Widget_Base {

	public function get_name() {
		return 'home_banner';
	}

	public function get_title() {
		return __( 'Home Banner', 'resido-core' );
	}
	public function get_icon() {
		return 'sds-widget-ico';
	}
	public function get_categories() {
		return array( 'resido' );
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
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1' => esc_html__( 'Layout 1', 'resido-core' ),
					'2' => esc_html__( 'Layout 2', 'resido-core' ),
					'3' => esc_html__( 'Layout 3', 'resido-core' ),
					'4' => esc_html__( 'Layout 4', 'resido-core' ),
					'5' => esc_html__( 'Layout 5', 'resido-core' ),
					'6' => esc_html__( 'Layout 6', 'resido-core' ),
				),
				'default' => 'h1',

			)
		);

		$this->add_control(
			'title',
			array(
				'label'      => __( 'Title', 'resido-core' ),
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '!=',
							'value'    => '1',
						),
					),
				),
				'type'       => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'highlighted_title',
			array(
				'label'      => __( 'Highlighted Text', 'resido-core' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '!=',
							'value'    => '1',
						),
					),
				),
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
			'highlighted_heading',
			array(
				'label' => __( 'Highlighted Text', 'resido-core' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'bg_image',
			array(
				'label'     => esc_html__( 'BG Image', 'resido-core' ),
				'type'      => Controls_Manager::MEDIA,
				'separator' => 'before',
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),

			)
		);

		$this->add_control(
			'data_overlay',
			array(
				'label'      => __( 'Data Overlay', 'resido-core' ),
				'type'       => Controls_Manager::SLIDER,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '!=',
							'value'    => '1',
						),
					),
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
			)
		);

		$this->add_control(
			'search_shortcode',
			array(
				'label'   => esc_html__( 'Search Form', 'resido-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slide',
			array(
				'label' => esc_html__( 'Slides', 'resido-core' ),
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

		$this->end_controls_section();

		$this->start_controls_section(
			'button',
			array(
				'label' => esc_html__( 'Button', 'resido-core' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'submit_typography',
				'label'    => __( 'Typography', 'resido-core' ),
				'selector' => '{{WRAPPER}} .hero-search-content.side-form label',
				'{{WRAPPER}} input.search_sbmtfrm',
				'{{WRAPPER}} label',
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
					'{{WRAPPER}} input.search_sbmtfrm' => 'background-color: {{VALUE}}!important',
				),
			)
		);

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'      => __( 'Padding', 'plugin-domain' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} input.search_sbmtfrm' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		resido_public_heading_control( $this, 'h4' );

		resido_public_title_control( $this, 'h4' );
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

		$highlighted_heading = $settings['highlighted_heading'];
		$highlighted_title   = $settings['highlighted_title'];
		$data_overlay        = $settings['data_overlay'];

		$updated_heading = str_replace( $highlighted_heading, "<span class='font-normal'>" . $highlighted_heading . '</span>', $heading );
		$updated_title   = str_replace( $highlighted_title, "<span class='badge badge-success'>" . $highlighted_title . '</span>', $title );

		$search_shortcode = $settings['search_shortcode'];
		$layout           = $settings['layout'];

		if ( $layout == 1 ) {
			?>

			<!-- ============================ Hero Banner  Start================================== -->
			<div class="image-cover hero-banner" style="background:url(<?php echo $settings['bg_image']['url']; ?>) no-repeat;">
				<div class="container">
					<div class="hero-search-wrap">
						<div class="hero-search">
							<!-- Header Func outp -->
							<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
								<?php echo $updated_heading; ?>
							</<?php echo $public_header_tag; ?>>
							<!-- Header Func outp -->
						</div>
						<!-- Hero Search Form -->
						<?php echo do_shortcode( $search_shortcode ); ?>
						<!-- Hero Search Form -->
					</div>
				</div>
			</div>
			<!-- ============================ Hero Banner End ================================== -->
			<?php
		} elseif ( $layout == 2 ) {
			?>
			<!-- ============================ Hero Banner  Start================================== -->
			<div class="image-bottom hero-banner" style="background:url(<?php echo $settings['bg_image']['url']; ?>) no-repeat;" data-overlay="<?php echo $data_overlay['size']; ?>">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-9 col-md-11 col-sm-12">
							<div class="inner-banner-text text-center">
								<!-- Title Func outp -->
								<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string( 'title' ); ?>>
									<?php echo $updated_title; ?>
								</<?php echo $public_title_tag; ?>>
								<!-- Header Func outp -->
								<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
									<?php echo $updated_heading; ?>
								</<?php echo $public_header_tag; ?>>
							</div>
							<!-- Hero Search Form -->
							<?php echo do_shortcode( $search_shortcode ); ?>
							<!-- Hero Search Form -->

						</div>
					</div>
				</div>
			</div>
			<!-- ============================ Hero Banner End ================================== -->
			<?php
		} elseif ( $layout == 3 ) {
			?>
			<!-- ============================ Hero Banner  Start================================== -->
			<div class="image-cover hero-banner" style="background:url(<?php echo $settings['bg_image']['url']; ?>) no-repeat;" data-overlay="<?php echo $data_overlay['size']; ?>">
				<div class="container">
					<div class="text-center">
						<!-- Header Func outp -->
						<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
							<?php echo $updated_heading; ?>
						</<?php echo $public_header_tag; ?>>
						<!-- Title Func outp -->
						<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string( 'title' ); ?>>
							<?php echo $updated_title; ?>
						</<?php echo $public_title_tag; ?>>
					</div>
					<!-- Hero Search Form -->
					<?php echo do_shortcode( $search_shortcode ); ?>
					<!-- Hero Search Form -->

				</div>
			</div>
			<!-- ============================ Hero Banner End ================================== -->
			<?php
		} elseif ( $layout == 4 ) {
			?>
			<!-- ============================ Hero Banner  Start================================== -->
			<div class="image-cover hero-banner" style="background:#2540a2 url(<?php echo $settings['bg_image']['url']; ?>) no-repeat;" data-overlay="<?php echo $data_overlay['size']; ?>">
				<div class="container">
					<div class="row">
						<div class="col-lg-8 col-md-11 col-sm-12">
							<!-- Title Func outp -->
							<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string( 'title' ); ?>>
								<?php echo $updated_title; ?>
							</<?php echo $public_title_tag; ?>>
							<!-- Header Func outp -->
							<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
								<?php echo $updated_heading; ?>
							</<?php echo $public_header_tag; ?>>
							<!-- Hero Search Form -->
							<?php echo do_shortcode( $search_shortcode ); ?>
							<!-- Hero Search Form -->
						</div>
					</div>
				</div>
			</div>
			<!-- ============================ Hero Banner End ================================== -->
			<?php
		} elseif ( $layout == 5 ) {
			?>
			<div class="container">
				<div class="simple-search-wrap">
					<div class="hero-search-2">
						<!-- Title Func outp -->
						<<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string( 'title' ); ?>>
							<?php echo $updated_title; ?>
						</<?php echo $public_title_tag; ?>>
						<!-- Header Func outp -->
						<<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
							<?php echo $updated_heading; ?>
						</<?php echo $public_header_tag; ?>>
						<!-- Hero Search Form -->
						<?php echo do_shortcode( $search_shortcode ); ?>
						<!-- Hero Search Form -->

					</div>
				</div>
			</div>
			<?php
		} else {

			$texquery = array();

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
			$meta_query = array(
				array(
					'key'   => 'featured',
					'value' => '1',
				),
			);

			// $listing_option = resido_listing_option();
			// use in template file
			$order_by       = $settings['order_by'];
			$order          = $settings['order'];
			$posts_per_page = $settings['posts_per_page'];
			$pg_num         = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$args           = array(
				'post_type'      => 'cl_cpt',
				'post_status'    => 'publish',
				'paged'          => $pg_num,
				'posts_per_page' => $posts_per_page,
				'orderby'        => $order_by,
				'order'          => $order,
				'tax_query'      => $texquery,
			);
			$custom_query   = new \WP_Query( $args );
			$provider       = WPERECCP()->front->listing_provider;
			?>
			<div class="home-slider margin-bottom-0">
				<?php

				if ( $custom_query->have_posts() ) {
					while ( $custom_query->have_posts() ) {
						$custom_query->the_post();
						$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );

						?>
						<!-- Slide -->
						<div data-background-image="<?php echo get_the_post_thumbnail_url(); ?>" class="item">
							<div class="container">
								<div class="row">
									<div class="col-md-12">
										<div class="home-slider-container">

											<!-- Slide Title -->
											<div class="home-slider-desc">
												<div class="modern-pro-wrap">
													<?php
													if ( $rlisting_status ) {
														foreach ( $rlisting_status as $key => $single_rlisting_status ) {
															?>
															<span class="property-type"><?php echo $single_rlisting_status; ?></span>
														<?php } ?>
														<?php
													}
													if ( get_post_meta( get_the_ID(), 'featured', true ) == 1 ) {
														?>
														<span class="property-featured theme-bg"><?php echo esc_html( 'Featured' ); ?></span>
													<?php } ?>
												</div>
												<div class="home-slider-title">
													<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
													<span><i class="lni-map-marker"></i> 
													<?php // listing_meta_field( 'address' ); ?>
													<?php echo esc_html( get_post_meta( get_the_ID(), 'wperesds_address', true ) ); ?>
													</span>
												</div>

												<div class="slide-property-info">
													<ul>
														<li>
														<?php
														echo esc_html__( 'Beds: ', 'resido-core' );
														echo esc_html( get_post_meta( get_the_ID(), 'wperesds_beds', true ) );
														?>
														</li>
														<li>
														<?php
														echo esc_html__( 'Bath: ', 'resido-core' );
														echo esc_html( get_post_meta( get_the_ID(), 'wperesds_bath', true ) );
														?>
														</li>
														<li><?php echo esc_html__( 'sqft', 'resido-core' ) . ': ' . get_post_meta( get_the_ID(), 'wperesds_area', true ); ?></li>
													</ul>
												</div>

												<div class="listing-price-with-compare">
													<h4 class="list-pr theme-cl">
													<?php // echo esc_html( $listing_option['currency_symbol'] . get_post_meta( get_the_ID(), 'rlisting_sale_or_rent', true ) ); ?>
													<?php echo wp_kses( $provider->get_listing_pricing(), 'code_contxt' ); ?>
													</h4>
													<div class="lpc-right">

														<form action="<?php echo home_url( 'comparing' ); ?>" method="POST" class="comapreForm">
															<input name="tDta" class="tDta" type="hidden" value="<?php echo get_the_ID(); ?>">
															<a href="javascript:void(0)" title="<?php echo esc_html__( 'Compare', 'resido-compare' ); ?>" class="compare-bt-single"><i class="ti-control-shuffle"></i></a>
														</form>
														<?php

														global $current_user;
														if ( ! is_user_logged_in() ) {
															echo '<a href="JavaScript:Void(0);" data-bs-toggle="modal" data-bs-target="#login"><i class="ti-heart"></i></a>';
														} else {
															$user_meta = get_user_meta( $current_user->ID, '_favorite_posts' );
															if ( in_array( get_the_ID(), $user_meta ) ) {
																echo '<a href="javascript:void(0)" data-userid="' . $current_user->ID . '" data-postid="' . get_the_ID() . '" class="banner_fav" id="like_listing' . get_the_ID() . '"><i class="save_class_sdbr ti-heart"></i></a>';
															} else {
																echo '<a href="javascript:void(0)" data-userid="' . $current_user->ID . '" data-postid="' . get_the_ID() . '" class="banner_fav" id="like_listing' . get_the_ID() . '"><i class="save_class_sdbr ti-heart"></i></a>';
															}
														}
														?>
													</div>
												</div>

												<a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html__( 'View Details', 'resido-core' ); ?> <i class="fa fa-angle-right"></i></a>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				wp_reset_postdata();
				?>

			</div>
			<?php
		}
	}
	protected function content_template() {     }
}
