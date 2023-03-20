<?php
namespace Resido\Helper\Widgets;

/*
==============================
custom Widget Selector Widget
==============================
*/

class Widget_Selector extends \WP_Widget {

	private $defaults = array();
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		 $widget_ops     = array(
			 'classname'                   => 'side_widget_ele',
			 'description'                 => __( 'Posts Widget Selector From Template' ),
			 'customize_selective_refresh' => true,
		 );
		 $this->defaults = array(
			 'template_option' => 2,

		 );
		 parent::__construct( 'widget_selector', __( 'Resido Widget Selector' ), $widget_ops );
	}

	public function widget( $args, $instance ) {

		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'resido_widget_selector', 'widget' );
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

		$sidebar_widget_elementor = isset( $instance['template_option'] ) ? $instance['template_option'] : '';
		$pluginElementor          = \Elementor\Plugin::instance();
		$resido_all_ssave_element = $pluginElementor->frontend->get_builder_content( $sidebar_widget_elementor );
		printf( $args['before_widget'] );
		echo do_shortcode( $resido_all_ssave_element );
		printf( $args['after_widget'] );
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['template_option'] ) ) {
			$instance['template_option'] = (int) $new_instance['template_option'];
		}

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete( 'resido_widget_selector', 'widget' );
	}

	public function form( $instance ) {
		$instance        = wp_parse_args( (array) $instance, $this->defaults );
		$template_option = isset( $instance['template_option'] ) ? esc_attr( $instance['template_option'] ) : '';
		?>
		<p><label for="<?php printf( $this->get_field_id( 'template_option' ) ); ?>"><?php _e( 'Select Template' ); ?></label>
		</p>
		<?php
		$elements_saved = sds_elementor_library();

		?>

		<select id="<?php echo $this->get_field_id( 'template_option' ); ?>" name="<?php echo $this->get_field_name( 'template_option' ); ?>">
			<?php
			foreach ( $elements_saved as $key => $element ) {
				if ( $template_option == $key ) {
					$sel = 'selected ="selected"';
				} else {
					$sel = '';
				}
				?>
				<option <?php echo $sel; ?> value="<?php echo esc_attr( $key ); ?>[]"><?php echo $element; ?></option>
				<?php
			}
			?>

		</select>
		<?php
	}
}
