<?php

namespace Essential\Restate\Admin\Settings\Payment;

use Essential\Restate\Traitval\Traitval;
use Essential\Restate\Front\Purchase\Payments\Clpaymentsquery;
use Essential\Restate\Front\Purchase\Gateways\Gateways;
use Essential\Restate\Common\Customer\Customer;
use Essential\Restate\Front\Models\Listings;
use Essential\Restate\Front\Purchase\Payments\Clpayment;
use Essential\Restate\Front\Purchase\Payments\Clpaymentstats;

class Subscriptionlist extends \WP_List_Table {

	use Traitval;

	/**
	 * Number of results to show per page
	 *
	 * @var string
	 * @since 1.4
	 */
	public $per_page = 30;

	/**
	 * URL of this page
	 *
	 * @var string
	 * @since 1.4.1
	 */
	public $base_url;

	/**
	 * Total number of payments
	 *
	 * @var int
	 * @since 1.4
	 */
	public $total_count;

	/**
	 * Total number of complete payments
	 *
	 * @var int
	 * @since 1.4
	 */
	public $complete_count;

	/**
	 * Total number of pending payments
	 *
	 * @var int
	 * @since 1.4
	 */
	public $pending_count;

	/**
	 * Total number of processing payments
	 *
	 * @var int
	 * @since 2.8
	 */
	public $processing_count;

	/**
	 * Total number of refunded payments
	 *
	 * @var int
	 * @since 1.4
	 */
	public $refunded_count;

	/**
	 * Total number of failed payments
	 *
	 * @var int
	 * @since 1.4
	 */
	public $failed_count;

	/**
	 * Total number of revoked payments
	 *
	 * @var int
	 * @since 1.4
	 */
	public $revoked_count;

	/**
	 * Total number of abandoned payments
	 *
	 * @var int
	 * @since 1.6
	 */
	public $abandoned_count;

	public function __construct() {
		global $status, $page;
		parent::__construct();
		$this->process_bulk_action();
		$this->base_url = admin_url( 'edit.php?post_type=cl_cpt&page=cl-subscription-history' );
	}

	public function advanced_filters() {
		$start_date       = isset( $_GET['start-date'] ) ? sanitize_text_field( $_GET['start-date'] ) : null;
		$end_date         = isset( $_GET['end-date'] ) ? sanitize_text_field( $_GET['end-date'] ) : null;
		$status           = isset( $_GET['status'] ) ? $_GET['status'] : '';
		$callgateways     = new Gateways();
		$all_gateways     = $callgateways->cl_get_payment_gateways();
		$gateways         = array();
		$selected_gateway = isset( $_GET['gateway'] ) ? sanitize_text_field( $_GET['gateway'] ) : 'all';

		if ( ! empty( $all_gateways ) ) {
			$gateways['all'] = __( 'All Gateways', 'resido-core' );

			foreach ( $all_gateways as $slug => $admin_label ) {
				$gateways[ $slug ] = $admin_label['admin_label'];
			}
		}

		/**
		 * Allow gateways that aren't registered the standard way to be displayed in the dropdown.
		 *
		 * @since 2.8.11
		 */
		$gateways = apply_filters( 'cl_payments_table_gateways', $gateways );
		?>
		<div id="cl-subscription-filters">
			<span id="cl-subscription-date-filters">
				<span>
					<label for="start-date"><?php _e( 'Start Date:', 'resido-core' ); ?></label>
					<input type="text" id="start-date" name="start-date" class="cl_datepicker" value="<?php echo esc_attr( $start_date ); ?>" placeholder="mm/dd/yyyy" />
				</span>
				<span>
					<label for="end-date"><?php _e( 'End Date:', 'resido-core' ); ?></label>
					<input type="text" id="end-date" name="end-date" class="cl_datepicker" value="<?php echo esc_attr( $end_date ); ?>" placeholder="mm/dd/yyyy" />
				</span>
			</span>
			<span id="cl-subscription-gateway-filter">
				<?php
				if ( ! empty( $gateways ) ) {
					echo WPERECCP()->admin->settings_instances->cl_admin_select_callback(
						array(
							'options'          => $gateways,
							'name'             => 'gateway',
							'id'               => 'gateway',
							'selected'         => $selected_gateway,
							'show_option_all'  => false,
							'show_option_none' => false,
						)
					);
				}
				?>
			</span>
			<span id="cl-subscription-after-core-filters">
				<?php do_action( 'cl_payment_advanced_filters_after_fields' ); ?>
				<input type="submit" class="button-secondary" value="<?php _e( 'Apply', 'resido-core' ); ?>" />
			</span>
			<?php if ( ! empty( $status ) ) : ?>
				<input type="hidden" name="status" value="<?php echo esc_attr( $status ); ?>" />
			<?php endif; ?>
			<?php if ( ! empty( $start_date ) || ! empty( $end_date ) || 'all' !== $selected_gateway ) : ?>
				<a href="<?php echo admin_url( 'edit.php?post_type=cl_cpt&page=cl-subscription-history' ); ?>" class="button-secondary"><?php _e( 'Clear Filter', 'resido-core' ); ?></a>
			<?php endif; ?>
			<?php do_action( 'cl_payment_advanced_filters_row' ); ?>
			<?php $this->search_box( __( 'Search', 'resido-core' ), 'cl-payments' ); ?>
		</div>

		<?php
	}

	/**
	 * Show the search field
	 *
	 * @since 1.4
	 *
	 * @param string $text Label for the search box
	 * @param string $input_id ID of the search box
	 *
	 * @return void
	 */

	public function display_rows() {
		foreach ( $this->items as $item ) {
			if ( get_post_type( $item->payment_meta['listing'][0]['id'] ) == 'pricing_plan' && $item->status == 'publish' ) {
				$this->single_row( $item );
			}
		}
	}


	/**
	 * Retrieve the table columns
	 *
	 * @since 1.4
	 * @return array $columns Array of all the list table columns
	 */
	public function get_columns() {
		 $columns = array(
			 'cb'            => '<input type="checkbox" />', // Render a checkbox instead of text
			 'ID'            => __( 'ID', 'resido-core' ),
			 'email'         => __( 'Email', 'resido-core' ),
			 'customer'      => __( 'Customer', 'resido-core' ),
			 'status'        => __( 'Payment Status', 'resido-core' ),
			 'package_name'  => __( 'Package', 'resido-core' ),
			 'listing_limit' => __( 'Listing Limit', 'resido-core' ),
			 'date'          => __( 'Start Date', 'resido-core' ),
			 'expire'        => __( 'Expire Date', 'resido-core' ),
		 );

		 return apply_filters( 'cl_payments_table_columns', $columns );
	}

	/**
	 * Retrieve the table's sortable columns
	 *
	 * @since 1.4
	 * @return array Array of all the sortable columns
	 */
	public function get_sortable_columns() {
		$columns = array(
			'ID'   => array( 'ID', true ),
			'date' => array( 'date', false ),
		);
		return apply_filters( 'cl_payments_table_sortable_columns', $columns );
	}

	/**
	 * Gets the name of the primary column.
	 *
	 * @since 2.5
	 * @access protected
	 *
	 * @return string Name of the primary column.
	 */
	protected function get_primary_column_name() {
		return 'ID';
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @since 1.4
	 *
	 * @param array  $subscription Contains all the data of the subscription
	 * @param string $column_name The name of the column
	 *
	 * @return string Column Name
	 */
	public function column_default( $subscription, $column_name ) {
		switch ( $column_name ) {
			case 'date':
				$date  = strtotime( $subscription->date );
				$value = date_i18n( get_option( 'date_format' ), $date );
				break;
			case 'status':
				$subscription = get_post( $subscription->ID );
				$value        = cl_get_payment_status( $subscription, true );
				break;
			default:
				$value = isset( $subscription->$column_name ) ? $subscription->$column_name : '';
				break;
		}
		return apply_filters( 'cl_payments_table_column', $value, $subscription->ID, $column_name );
	}

	/**
	 * Render the Email Column
	 *
	 * @since 1.4
	 * @param array $subscription Contains all the data of the subscription
	 * @return string Data shown in the Email column
	 */
	public function column_email( $subscription ) {

		$row_actions = array();

		$email = cl_get_payment_user_email( $subscription->ID );

		// Add search term string back to base URL
		$search_terms = ( isset( $_GET['s'] ) ? trim( $_GET['s'] ) : '' );
		if ( ! empty( $search_terms ) ) {
			$this->base_url = add_query_arg( 's', $search_terms, $this->base_url );
		}

		$row_actions['delete'] = '';

		$row_actions = apply_filters( 'cl_payment_row_actions', $row_actions, $subscription );

		if ( empty( $email ) ) {
			$email = __( '(unknown)', 'resido-core' );
		}

		$value = $email . $this->row_actions( $row_actions );

		return apply_filters( 'cl_payments_table_column', $value, $subscription->ID, 'email' );
	}

	/**
	 * Render the checkbox column
	 *
	 * @since 1.4
	 * @param array $subscription Contains all the data for the checkbox column
	 * @return string Displays a checkbox
	 */
	public function column_cb( $subscription ) {

			return sprintf(
				'<input type="checkbox" name="%1$s[]" value="%2$s" />',
				'subscription',
				$subscription->ID
			);
	}

	/**
	 * Render the ID column
	 *
	 * @since 2.0
	 * @param array $subscription Contains all the data for the checkbox column
	 * @return string Displays a checkbox
	 */
	public function column_ID( $subscription ) {
		$subscription_package_id = $subscription->payment_meta['cart_details'][0]['id'];
		$content_post            = get_post( $subscription_package_id );
		// print_r( $content_post->post_type );
		return cl_get_payment_number( $subscription->ID );
	}



	/**
	 * Render the Customer Column
	 *
	 * @since 2.4.3
	 * @param array $subscription Contains all the data of the subscription
	 * @return string Data shown in the User column
	 */
	public function column_customer( $subscription ) {

		$customer_id = cl_get_payment_customer_id( $subscription->ID );

		if ( ! empty( $customer_id ) ) {
			$customer = new Customer( $customer_id );
			$value    = '<a href="' . esc_url( admin_url( "edit.php?post_type=download&page=cl-customers&view=overview&id=$customer_id" ) ) . '">' . $customer->name . '</a>';
		} else {
			$email = cl_get_payment_user_email( $subscription->ID );
			$value = '<a href="' . esc_url( admin_url( "edit.php?post_type=download&page=cl-subscription-history&s=$email" ) ) . '">' . __( '(customer missing)', 'resido-core' ) . '</a>';
		}
		return apply_filters( 'cl_payments_table_column', $value, $subscription->ID, 'user' );
	}

	public function column_package_name( $subscription ) {
		$subscription_package = $subscription->payment_meta['cart_details'][0]['name'];
		return $subscription_package;
	}

	public function column_listing_limit( $subscription ) {
		$subscription_package_id = $subscription->payment_meta['cart_details'][0]['id'];
		$listing_limit           = get_post_meta( $subscription_package_id, 'resido_list_subn_limit', true );

		return $listing_limit;
	}
	public function column_expire( $subscription ) {
		$subscription_package_id = $subscription->payment_meta['cart_details'][0]['id'];
		$expire_duration         = get_post_meta( $subscription_package_id, 'resido_plan_expire', true );

		if ( $expire_duration ) {
			 $expire_date = date( get_option( 'date_format' ), strtotime( '+' . $expire_duration . 'days', strtotime( str_replace( '/', '-', $subscription->date ) ) ) ) . PHP_EOL;
		} else {
			$expire_date = esc_html__( 'Never Expire', 'resido-core' );
		}

		return $expire_date;
	}






	/**
	 * Process the bulk actions
	 *
	 * @since 1.4
	 * @return void
	 */
	public function process_bulk_action() {
		$ids    = isset( $_GET['subscription'] ) ? $_GET['subscription'] : false;
		$action = $this->current_action();

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		if ( empty( $action ) ) {
			return;
		}

		foreach ( $ids as $id ) {
			// Detect when a bulk action is being triggered...
			if ( 'delete' === $this->current_action() ) {
				cl_delete_purchase( $id );
			}

			do_action( 'cl_payments_table_do_bulk_action', $id, $this->current_action() );
		}
	}


	/**
	 * Retrieve all the data for all the payments
	 *
	 * @since 1.4
	 * @return array $payment_data Array of all the data for the payments
	 */
	public function payments_data() {
		$per_page   = $this->per_page;
		$orderby    = isset( $_GET['orderby'] ) ? urldecode( $_GET['orderby'] ) : 'ID';
		$order      = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
		$user       = isset( $_GET['user'] ) ? $_GET['user'] : null;
		$customer   = isset( $_GET['customer'] ) ? $_GET['customer'] : null;
		$status     = isset( $_GET['status'] ) ? $_GET['status'] : cl_get_payment_status_keys();
		$meta_key   = isset( $_GET['meta_key'] ) ? $_GET['meta_key'] : null;
		$year       = isset( $_GET['year'] ) ? $_GET['year'] : null;
		$month      = isset( $_GET['m'] ) ? $_GET['m'] : null;
		$day        = isset( $_GET['day'] ) ? $_GET['day'] : null;
		$search     = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : null;
		$start_date = isset( $_GET['start-date'] ) ? sanitize_text_field( $_GET['start-date'] ) : null;
		$end_date   = isset( $_GET['end-date'] ) ? sanitize_text_field( $_GET['end-date'] ) : $start_date;
		$gateway    = isset( $_GET['gateway'] ) ? sanitize_text_field( $_GET['gateway'] ) : null;

		/**
		 * Introduced as part of #6063. Allow a gateway to specified based on the context.
		 *
		 * @since 2.8.11
		 *
		 * @param string $gateway
		 */
		$gateway = apply_filters( 'cl_payments_table_search_gateway', $gateway );

		if ( ! empty( $search ) ) {
			$status = 'any'; // Force all subscription statuses when searching
		}

		if ( $gateway === 'all' ) {
			$gateway = null;
		}

		$args = array(
			'output'     => 'payments',
			'number'     => $per_page,
			'page'       => isset( $_GET['paged'] ) ? $_GET['paged'] : null,
			'orderby'    => $orderby,
			'order'      => $order,
			'user'       => $user,
			'customer'   => $customer,
			'status'     => $status,
			'meta_key'   => $meta_key,
			'year'       => $year,
			'month'      => $month,
			'day'        => $day,
			's'          => $search,
			'start_date' => $start_date,
			'end_date'   => $end_date,
			'plan'       => $gateway,
		);

		if ( is_string( $search ) && false !== strpos( $search, 'txn:' ) ) {

			$args['search_in_notes'] = true;
			$args['s']               = trim( str_replace( 'txn:', '', $args['s'] ) );
		}

		$p_query = new Clpaymentsquery();

		return $p_query->get_payments();
	}


	public function prepare_items() {
		wp_reset_vars( array( 'action', 'subscription', 'orderby', 'order', 's' ) );
		$columns               = $this->get_columns();
		$hidden                = array(); // No hidden columns
		$sortable              = $this->get_sortable_columns();
		$data                  = $this->payments_data();
		$status                = isset( $_GET['status'] ) ? $_GET['status'] : 'any';
		$this->_column_headers = array( $columns, $hidden, $sortable );

		switch ( $status ) {
			case 'publish':
				$total_items = $this->complete_count;
				break;
			case 'pending':
				$total_items = $this->pending_count;
				break;
			case 'processing':
				$total_items = $this->processing_count;
				break;
			case 'refunded':
				$total_items = $this->refunded_count;
				break;
			case 'failed':
				$total_items = $this->failed_count;
				break;
			case 'revoked':
				$total_items = $this->revoked_count;
				break;
			case 'abandoned':
				$total_items = $this->abandoned_count;
				break;
			case 'any':
				$total_items = $this->total_count;
				break;
			default:
				$count       = wp_count_posts( 'cl_payment' );
				$total_items = $count->{$status};
		}

		$this->items = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $this->per_page,
				'total_pages' => ceil( $total_items / $this->per_page ),
			)
		);
	}
}
