<?php
namespace Resido\Helper\Elementor\Widgets2;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;

class Widget_Map extends Widget_Base {


	public function get_name() {
		return 'widget_map';
	}

	public function get_title() {
		return __( 'Map Widget', 'resido-core' );
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
			'enable_map',
			array(
				'label'   => __( 'Map From Api', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'fs-inner-container' => __( 'Enable Map', 'resido-core' ),
					'container-fluid'    => __( 'Disable Map', 'resido-core' ),
				),
				'default' => 'container-fluid',
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
				'label'     => __( 'Label', 'resido-core' ),
				'separator' => 'before',
				'type'      => Controls_Manager::TEXT,
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
	}
	protected function render() {
		$settings = $this->get_settings_for_display();

		$layout = cl_admin_get_option( 'default_layout' );

		// Layout Condition
		if ( isset( $layout ) && $layout ) {
			if ( $layout == 'grid' || $layout == 'classic' ) {
				$layout = 'grid-layout';
			} else {
				$layout = 'list-layout';
			}
		} else {
			$layout = 'list-layout';
		}
		// Layout Condition

		$enable_map = $settings['enable_map'];
		// title Func inp
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

		$map_data = wp_json_encode(
			array(
				'enable_geolocation' => cl_admin_get_option( 'enable_geolocation' ) != 1 ? false : true,
				'default_latitude'   => cl_admin_get_option( 'default_latitude' ),
				'default_longitude'  => cl_admin_get_option( 'default_longitude' ),
				'default_zoom'       => cl_admin_get_option( 'default_zoom' ),
				'geo_markup'         => esc_html__( 'You are within {{radius_value}} meters from this point', 'wp-essential-real-estate' ),
			)
		);
		$value    = utf8_encode( json_encode( $map_data ) );
		?>
		<div class="fs-container half-map">
			<div class="fs-left-map-box">
				<div class="home-map fl-wrap">
					<div class="hm-map-container fw-map">
						<div id="map" data-map_data="<?php echo esc_attr( $map_data ); ?>">
						</div>
					</div>
				</div>
			</div>
			<div class="<?php echo esc_html( $enable_map ); ?>">
				<div class="fs-content">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12">
							<div class="form-group">
								<div class="input-with-icon">
									<input type="text" class="form-control" name="s" id="name" placeholder="<?php _e( 'Keyword', 'resido-core' ); ?>">
									<i class="ti-search theme-cl"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12">
							<div class="form-group">
								<div class="input-with-icon">
									<select id="listing_city" name="listing_city" class="listing_city form-control">
										<option value=""><?php esc_html_e( 'All Cities', 'resido-core' ); ?></option>
										<?php
										$rlisting_location = get_terms(
											array(
												'taxonomy' => 'listing_location',
												'hide_empty' => false,
											)
										);
										if ( ! empty( $rlisting_location ) ) {
											foreach ( $rlisting_location as $single ) {
												// if ( $single->parent > 0 ) {
													echo '<option value="' . $single->slug . '">' . $single->name . '</option>';
												// }
											}
										}
										?>
									</select>
									<i class="ti-briefcase theme-cl"></i>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-4 col-sm-12">
							<div class="form-group">
								<div class="input-with-icon">
									<select name="listing_cate" id="list-category" class="form-control">
										<option value=""><?php esc_html_e( 'Select Category', 'resido-core' ); ?></option>
										<?php
										$rlisting_category = get_terms(
											array(
												'taxonomy' => 'listings_property',
												'hide_empty' => false,
											)
										);
										if ( ! empty( $rlisting_category ) ) {
											foreach ( $rlisting_category as $single ) {
												echo '<option value="' . $single->slug . '">' . $single->name . '</option>';
											}
										}
										?>
									</select>
									<i class="ti-layers theme-cl"></i>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group module">
								<a role="button" class="collapsed" data-bs-toggle="collapse" href="#advance-search" aria-expanded="false" aria-controls="advance-search"></a>
							</div>
						</div>
						<div class="collapse" id="advance-search" aria-expanded="false" role="banner">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<h4><?php esc_html_e( 'Amenities & Features', 'resido-core' ); ?></h4>
								<ul class="no-ul-list third-row">
									<?php
									$rlisting_features = get_terms(
										array(
											'taxonomy'   => 'listing_features',
											'hide_empty' => false,
										)
									);

									if ( ! empty( $rlisting_features ) ) {
										foreach ( $rlisting_features as $key => $single ) {
											?>
											<li>
												<input id="rlisting_features<?php echo $key; ?>" class="rlisting_features checkbox-custom" name="rlisting_features[]" type="checkbox" value="<?php echo $single->slug; ?>">
												<label for="rlisting_features<?php echo $key; ?>" class="checkbox-custom-label"><?php echo $single->name; ?></label>
											</li>
											<?php
										}
									}
									?>
								</ul>
							</div>
						</div>
					</div>
					<!--- All List -->
					<div class="row list-layout" id="archive_loop">
						<!-- Single List Start -->
						<?php
						$locations = array();
						if ( $custom_query->have_posts() ) {
							$key_num = 0;
							while ( $custom_query->have_posts() ) {
								$custom_query->the_post();
								$category                       = resido_get_listing_cat( get_the_ID() );
								$title                          = get_the_title();
								$featured_image_url             = get_the_post_thumbnail_url( get_the_ID() );
								$rlisting_latitude              = resido_get_listing_meta( get_the_ID(), 'wperesds_map_address_lat' );
								$rlisting_longitude             = resido_get_listing_meta( get_the_ID(), 'wperesds_map_address_lon' );
								$locations[ $key_num ]['url']   = get_post_permalink( get_the_ID() );
								$locations[ $key_num ]['image'] = get_the_post_thumbnail_url( get_the_ID() );
								// $locations[ $key_num ]['price']     = $listing_option['currency_symbol'] . ' ' . get_post_meta( get_the_ID(), 'rlisting_sale_or_rent', true );
								$locations[ $key_num ]['category']  = $category;
								$locations[ $key_num ]['title']     = $title;
								$locations[ $key_num ]['latitude']  = $rlisting_latitude;
								$locations[ $key_num ]['longitude'] = $rlisting_longitude;

								if ( $layout != 'list-layout' ) {
									// include RESIDO_LISTING_PATH . '/templates/loop/grid-listing.php';
									include RESIDO_CORE_PATH . '/includes/Elementor/Widgets2/Template/list_layout.php';
								} else {
									// include RESIDO_LISTING_PATH . '/templates/loop/listing.php';
									include RESIDO_CORE_PATH . '/includes/Elementor/Widgets2/Template/grid_layout.php';
								}
								$key_num++;
							}

							wp_localize_script( 'resido-map', 'locations_obj', $locations );
							wp_localize_script(
								'resido_loadmore',
								'resido_loadmore_params',
								array(
									'ajaxurl'      => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
									'layout'       => $layout,
									'posts'        => json_encode( $custom_query->query_vars ), // everything about your loop is here
									'current_page' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
									'max_page'     => $custom_query->max_num_pages,
								)
							);
							wp_enqueue_script( 'resido_loadmore' );
							wp_reset_postdata();
						}
						/* Restore original Post Data */
						?>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 mt-3">
							<div id="ajax_scroll_loadmore">
								<div id="loading_tag" class="text-center">
									<div class="resido_loadmore_map btn btn-theme" data-page_num="1">
										<?php esc_html_e( 'Load More', 'resido-core' ); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
