<?php
namespace Resido\Helper\Widgets;

/*
==============================
Resido_Calculation_Widget
==============================
*/

function calculation_widget() {
	 register_widget( 'Resido_Calculation_Widget' );
}
add_action( 'widgets_init', 'calculation_widget', 2 );

class Resido_Calculation extends \WP_Widget {


	public function __construct() {
		$widget_ops = array(
			'classname'   => 'resido_calculation_widget',
			'description' => __( "Latest CPT's & Posts." ),
		);
		\WP_Widget::__construct( 'resido-calculation', __( 'Resido Calculation Widget' ), $widget_ops );
		$this->alt_option_name = 'resido_calculation_widget';

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	public function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'resido_calculation_wid', 'widget' );
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

		$title     = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Calculation Widget', 'resido-core' );
		$sub_title = ( ! empty( $instance['sub_title'] ) ) ? $instance['sub_title'] : __( 'View your Interest Rate', 'resido-core' );
		$title     = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		?>


		<?php printf( $args['before_widget'] ); ?>

		<!-- Mortgage Calculator -->
		<div class="sides-widget calculator_area">

			<div class="sides-widget-header">
				<div class="sides-widget-details">
					<?php
					if ( $title ) {
						printf( $args['before_title'] . $title . $args['after_title'] );
					}
					if ( $sub_title ) {
						echo '<span>' . $sub_title . '</span>';
					}
					?>
				</div>
				<div class="clearfix"></div>
			</div>

			<?php

			$currency_symbol = '$';
			?>

			<form class="contact_form" novalidate="novalidate" id="calculate_form">
				<div class="sides-widget-body simple-form">
					<div class="form-group">
						<div class="input-with-icon">
							<input type="number" class="form-control" id="calc_price" name="calc_price" placeholder="<?php echo esc_attr( $instance['sale_price'] ); ?> " value="">
							<span><?php echo esc_html( $currency_symbol ); ?></span>
						</div>
					</div>

					<div class="form-group">
						<div class="input-with-icon">
							<input type="number" class="form-control" id="down_payment" name="down_payment" placeholder="<?php echo esc_attr( $instance['down_payment'] ); ?>" value="">
							<span><?php echo esc_html( $currency_symbol ); ?></span>
						</div>
					</div>

					<div class="form-group">
						<div class="input-with-icon">
							<input type="number" class="form-control" id="period" name="period" placeholder="<?php echo esc_attr( $instance['loan_term'] ); ?> " value="">
							<i class="ti-calendar"></i>
						</div>
					</div>

					<div class="form-group">
						<div class="input-with-icon">
							<input type="number" class="form-control" id="interest_rate" name="interest_rate" placeholder="<?php echo esc_attr( $instance['interest_rate'] ); ?>" value="">
							<i class="fa fa-percent"></i>
						</div>
					</div>
					<input id="compare_get_currency" type="hidden" value="<?php echo esc_html( $currency_symbol ); ?>">
					<button data-bs-toggle="modal" data-bs-target="#calculate_modal" type="submit" value="submit" class="btn btn-black btn-md rounded full-width submit_btn btn_calculator"><?php echo wp_kses_post( $instance['button_text'] ); ?></button>

				</div>
			</form>
			<div class="calculator_box">
				<!-- Calculate_modal Modal -->
				<div class="modal fade" id="calculate_modal" tabindex="-1" role="dialog" aria-labelledby="calculate_content" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
						<div class="modal-content" id="calculate_content">
							<span class="mod-close" data-bs-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></span>
							<div class="modal-body">
								<h4 class="modal-header-title "><?php echo esc_html__( 'Result', 'resido-core' ); ?></h4>
								<div class="calculator_box">
									<div class="calculator_inner text-center">
										<div class="cal_item ">
											<p><?php echo esc_html__( 'Monthly Payment', 'resido-core' ); ?></p>
											<h2 class="monthly_result"></h2>
										</div>
										<div class="cal_item ">
											<p><?php echo esc_html__( 'Amount With Down Payment', 'resido-core' ); ?></p>
											<h2 class="tot__down_result"></h2>
										</div>
										<div class="cal_item">
											<p><?php echo esc_html__( 'Amount Without Down Payment', 'resido-core' ); ?></p>
											<h2 class="tot_result"></h2>
										</div>
										<div class="cal_item">
											<p><?php echo esc_html__( 'Interest Amount', 'resido-core' ); ?></p>
											<h2 class="interest_result"></h2>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Calculate_modal Modal -->

			</div>
		</div>

		<?php printf( $args['after_widget'] ); ?>
		<?php
		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'resido_calculation_wid', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance                  = $old_instance;
		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['sub_title']     = strip_tags( $new_instance['sub_title'] );
		$instance['sale_price']    = strip_tags( $new_instance['sale_price'] );
		$instance['down_payment']  = strip_tags( $new_instance['down_payment'] );
		$instance['loan_term']     = strip_tags( $new_instance['loan_term'] );
		$instance['interest_rate'] = strip_tags( $new_instance['interest_rate'] );
		$instance['button_text']   = strip_tags( $new_instance['button_text'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['resido_calculation_widget'] ) ) {
			delete_option( 'resido_calculation_widget' );
		}

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete( 'resido_calculation_wid', 'widget' );
	}

	public function form( $instance ) {
		$title         = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Calculation Widget';
		$sub_title     = isset( $instance['sub_title'] ) ? esc_attr( $instance['sub_title'] ) : 'View your Interest Rate';
		$sale_price    = isset( $instance['sale_price'] ) ? esc_attr( $instance['sale_price'] ) : 'Sale Price';
		$down_payment  = isset( $instance['down_payment'] ) ? esc_attr( $instance['down_payment'] ) : 'Down Payment';
		$loan_term     = isset( $instance['loan_term'] ) ? esc_attr( $instance['loan_term'] ) : 'Loan Term (Year)';
		$interest_rate = isset( $instance['interest_rate'] ) ? esc_attr( $instance['interest_rate'] ) : 'Interest Rate';
		$button_text   = isset( $instance['button_text'] ) ? esc_attr( $instance['button_text'] ) : 'Calculate';

		?>
		<p><label for="<?php printf( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'title' ) ); ?>" name="<?php printf( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php printf( $title ); ?>" />
		</p>

		<p><label for="<?php printf( $this->get_field_id( 'sub_title' ) ); ?>"><?php _e( 'Sub Title:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'sub_title' ) ); ?>" name="<?php printf( $this->get_field_name( 'sub_title' ) ); ?>" type="text" value="<?php printf( $sub_title ); ?>" />
		</p>

		<p><label for="<?php printf( $this->get_field_id( 'sale_price' ) ); ?>"><?php _e( 'Sale Price:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'sale_price' ) ); ?>" name="<?php printf( $this->get_field_name( 'sale_price' ) ); ?>" type="text" value="<?php printf( $sale_price ); ?>" />
		</p>

		<p><label for="<?php printf( $this->get_field_id( 'down_payment' ) ); ?>"><?php _e( 'Down Payment:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'down_payment' ) ); ?>" name="<?php printf( $this->get_field_name( 'down_payment' ) ); ?>" type="text" value="<?php printf( $down_payment ); ?>" />
		</p>

		<p><label for="<?php printf( $this->get_field_id( 'loan_term' ) ); ?>"><?php _e( 'Loan Term:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'loan_term' ) ); ?>" name="<?php printf( $this->get_field_name( 'loan_term' ) ); ?>" type="text" value="<?php printf( $loan_term ); ?>" />
		</p>

		<p><label for="<?php printf( $this->get_field_id( 'interest_rate' ) ); ?>"><?php _e( 'Interest Rate:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'interest_rate' ) ); ?>" name="<?php printf( $this->get_field_name( 'interest_rate' ) ); ?>" type="text" value="<?php printf( $interest_rate ); ?>" />
		</p>

		<p><label for="<?php printf( $this->get_field_id( 'button_text' ) ); ?>"><?php _e( 'Interest Rate:' ); ?></label>
			<input class="widefat" id="<?php printf( $this->get_field_id( 'button_text' ) ); ?>" name="<?php printf( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php printf( $button_text ); ?>" />
		</p>


		<?php
	}
}
