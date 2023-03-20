<?php
namespace Resido\Helper\Widgets;

/*
==============================
Resido_Featured_Listing
==============================
*/

function featured_property_widget_func() {
	register_widget( 'Resido_Featured_Listing' );
}
add_action( 'widgets_init', 'featured_property_widget_func', 2 );

class Resido_Featured_Listing extends \WP_Widget {


	public function __construct() {
		$widget_ops = array(
			'classname'   => 'featured_property_widget',
			'description' => __( "Latest CPT's & Posts.", 'resido-core' ),
		);
		parent::__construct( 'wpsites-featured-posts', __( 'Resido Featured Property' ), $widget_ops );
		$this->alt_option_name = 'featured_property_widget';

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	public function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'resido_featured_property', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			printf( $cache[ $args['widget_id'] ] );
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Featured Property' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		$r = new \WP_Query(
			apply_filters(
				'widget_posts_args',
				array(
					'posts_per_page' => $number,
					'no_found_rows'  => true,
					'post_status'    => 'publish',
					'post_type'      => array(
						'cl_cpt',
						'ignore_sticky_posts' => true,
					),
					// 'meta_query'     => array(
					// array(
					// 'key'   => 'featured',
					// 'value' => '1',
					// ),
					// ),
				)
			)
		);

		if ( $r->have_posts() ) :
			?>


			<?php printf( $args['before_widget'] ); ?>

			<div class="sidebar-widgets">
				<?php
				if ( $title ) {
					printf( $args['before_title'] . $title . $args['after_title'] );
				}
				?>
				<div class="sidebar_featured_property">
					<?php
					while ( $r->have_posts() ) :
						$r->the_post();
						$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );
						?>
						<!-- List Sibar Property -->
						<div class="sides_list_property">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="sides_list_property_thumb">
									<img src="<?php echo esc_url( the_post_thumbnail_url() ); ?>" class="img-fluid" alt="<?php esc_attr_e( 'Resido Image', 'resido-core' ); ?>">
								</div>
							<?php endif; ?>
							<div class="sides_list_property_detail">
								<h4><a href="<?php esc_url( the_permalink() ); ?>"><?php the_title(); ?></a></h4>
								<?php
								if ( $show_date ) :
									echo '<span>' . esc_html( get_the_date() ) . '</span><br>';
								endif;
								?>
								<span><i class="ti-location-pin"></i>
								<?php echo esc_html( get_post_meta( get_the_ID(), 'wperesds_address', true ) ); ?>
								<?php // listing_meta_field( 'address' ); ?>
								</span>
								<div class="lists_property_price">
									<?php if ( $rlisting_status ) { ?>
										<div class="lists_property_types">
											<div class="property_types_vlix sale"><?php echo $rlisting_status[0]; ?></div>
										</div>
									<?php } ?>

									<div class="lists_property_price_value">
										<h4><?php // resido_currency_html(); ?></h4>
									</div>
								</div>
							</div>
						</div>
						<!-- List Sibar Property -->
					<?php endwhile; ?>
				</div>
				<?php printf( $args['after_widget'] ); ?>
			</div>
			<?php
			wp_reset_postdata();
		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'resido_featured_property', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['number']    = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['featured_property_widget'] ) ) {
			delete_option( 'featured_property_widget' );
		}

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete( 'resido_featured_property', 'widget' );
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Featured Property';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php printf( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'title' ) ); ?>" name="<?php printf( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php printf( $title ); ?>" />
		</p>

		<p><label for="<?php printf( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input id="<?php printf( $this->get_field_id( 'number' ) ); ?>" name="<?php printf( $this->get_field_name( 'number' ) ); ?>" type="number" value="<?php printf( $number ); ?>" size="3" />
		</p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php printf( $this->get_field_id( 'show_date' ) ); ?>" name="<?php printf( $this->get_field_name( 'show_date' ) ); ?>" />
			<label for="<?php printf( $this->get_field_id( 'show_date' ) ); ?>"><?php _e( 'Display post date?' ); ?></label>
		</p>
		<?php
	}
}
